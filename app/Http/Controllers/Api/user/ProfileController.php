<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Requests\Api\EditProdileRequest;
use App\Model\Coin;
use App\Model\MembershipPlan;
use App\Model\VerificationDetails;
use App\Services\Logger;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PragmaRX\Google2FA\Google2FA;

class ProfileController extends Controller
{
    function __construct()
    {}

    public function profileView(){
        try {
            $user = Auth::user();
            $user->photo = show_image($user->id,'user');
            $response_data['user'] = $user;
            $response_data['user']->role_name = ($response_data['user']->role==USER_ROLE_USER) ? 'User' : 'Admin';
            $response_data['clubInfos'] = get_plan_info($user->id);
            $response_data['languages'] = language();
            $response_data['genders'][1] = 'Male';
            $response_data['genders'][2] = 'Female';
            $response_data['genders'][3] = 'Other';
            if (isset($user)) {
                $data = ['success' => true, 'data' => $response_data, 'message' => __('Data get successfully')];
            } else {
                $data = ['success' => true, 'data' => [], 'message' => __('No data found')];
            }
        } catch (\Exception $e) {
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($data);
    }

    public function profileEdit(){
        try {
            $user = Auth::user();
            $img = asset('assets/img/avater.png');
            if (isset($user) && !empty($user->photo)) {
                $img = asset(IMG_USER_PATH.$user->photo);
            }
            $user->photo = $img;
            $response_data=[];
            $response_data['user'] = $user;
            $response_data['countries'] = country();
            $response_data['genders'][1] = 'Male';
            $response_data['genders'][2] = 'Female';
            $response_data['genders'][3] = 'Other';
            if (isset($user)) {
                $data = ['success' => true, 'data' => $response_data, 'message' => __('Data get successfully')];
            } else {
                $data = ['success' => true, 'data' => [], 'message' => __('No data found')];
            }
        } catch (\Exception $e) {
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($data);
    }

    public function phoneVerification(){
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if(!empty($user->phone)){
                $code = randomNumber(6);
                $message = "Verification code is $code. Use this code to verify your phone number.";
                $updated_array['verification_codes']=$code;
                User::where(['id' => Auth::id()])->update($updated_array);
                sendMessage($message,$user->phone);
                $data = ['success' => true, 'data' => ['phone' => true], 'message' => __('Verification code sent successfully!')];
            }
            else{
                $data = ['success' => false, 'data' => ['phone' => false], 'message' => __('Phone number not found!')];
            }
        } catch (\Exception $e) {
            DB::rollBack(); // failed and rollback
            $data = ['success' => false, 'data' => ['phone' => false], 'message' => $e->getMessage()];
        }
        DB::commit(); // success and commit
        return response()->json($data);
    }

    public function sendPhoneVerificationCode(Request $request){
        DB::beginTransaction();
        try {
            if(!empty($request->code)){
                $user = Auth::user();
                if($user->verification_codes==$request->code){
                    $updated_array['verification_codes'] = null;
                    $updated_array['phone_verified'] = STATUS_ACCEPTED;
                    User::where(['id' => Auth::id()])->update($updated_array);
                    $data = ['success' => true, 'data' => [], 'message' => __('Great! Your phone number verified successfully!')];
                }
                else{
                    $data = ['success' => false, 'data' => [], 'message' => __('Code not matched!')];
                }
            }
            else{
                $data = ['success' => false, 'data' => [], 'message' => __('Code not found!')];
            }
        } catch (\Exception $e) {
            DB::rollBack(); // failed and rollback
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        DB::commit(); // success and commit
        return response()->json($data);
    }

    public function saveEditedProfile(EditProdileRequest $request){
        DB::beginTransaction();
        try {
            $current_info = Auth::user();
            $update_info['first_name'] = $request->first_name;
            $update_info['last_name'] = $request->last_name;
            $update_info['country_code'] = $request->country_code;
            $update_info['phone'] = $request->phone;
            $update_info['country'] = $request->country;
            $update_info['gender'] = $request->gender;
            $update_info['birth_date'] = $request->birth_date;
            if($current_info->phone!=$request->phone){
                $update_info['phone_verified'] = STATUS_PENDING;
            }
            if(isset($request->photo) && $request->photo){
                $update_info['photo'] = uploadFile($request->photo, IMG_USER_PATH, !empty($current_info->photo) ? $current_info->photo : '');
            }
            User::where('id',Auth::id())->update($update_info);
            $update_user = User::where('id',Auth::id())->first();
            $data = ['success' => true, 'data' => $update_user, 'message' => __('Profile updated successfully!')];
        } catch (\Exception $e) {
            DB::rollBack(); // failed and rollback
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        DB::commit(); // success and commit
        return response()->json($data);
    }

    /**
     * subnit NID information
     */
    public function submitNIDPhoto(Request $request){
        if((isset($request->front_image) && !empty($request->front_image)) && (isset($request->back_image) && !empty($request->back_image))){
            $details = VerificationDetails::where('user_id', Auth::id())->where('field_name', 'nid_front')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }
            $details->field_name = 'nid_front';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($request->front_image, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
            $image['nid_front'] = asset(IMG_USER_PATH.$details->photo);
            $details = VerificationDetails::where('user_id', Auth::id())->where('field_name', 'nid_back')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }
            $details->field_name = 'nid_back';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($request->back_image, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
            $image['nid_back'] = asset(IMG_USER_PATH.$details->photo);
            $data = ['success' => true, 'data' => $image, 'message' => __('NID photo uploaded successfully')];
        }
        else{
            $data = ['success' => false, 'data' => [], 'message' => __('Image file missing!')];
        }
        return response()->json($data);
    }

    /**
     * subnit Passport information
     */
    public function submitPassportPhoto(Request $request){
        if((isset($request->front_image) && !empty($request->front_image)) && (isset($request->back_image) && !empty($request->back_image))){
            $details = VerificationDetails::where('user_id', Auth::id())->where('field_name', 'pass_front')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }
            $details->field_name = 'pass_front';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($request->front_image, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
            $image['pass_front'] = asset(IMG_USER_PATH.$details->photo);
            $details = VerificationDetails::where('user_id', Auth::id())->where('field_name', 'pass_back')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }
            $details->field_name = 'pass_back';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($request->back_image, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
            $image['pass_back'] = asset(IMG_USER_PATH.$details->photo);
            $data = ['success' => true, 'data' => $image, 'message' => __('passport photo uploaded successfully')];
        }
        else{
            $data = ['success' => false, 'data' => [], 'message' => __('Image file missing!')];
        }
        return response()->json($data);
    }

    /**
     * subnit Driving Lincense information
     */
    public function submitDrivingLincensePhoto(Request $request){
        if((isset($request->front_image) && !empty($request->front_image)) && (isset($request->back_image) && !empty($request->back_image))){
            $details = VerificationDetails::where('user_id', Auth::id())->where('field_name', 'drive_front')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }
            $details->field_name = 'drive_front';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($request->front_image, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
            $image['drive_front'] = asset(IMG_USER_PATH.$details->photo);
            $details = VerificationDetails::where('user_id', Auth::id())->where('field_name', 'drive_back')->first();
            if (empty($details)) {
                $details = new VerificationDetails();
            }
            $details->field_name = 'drive_back';
            $details->user_id = Auth::id();
            $details->status = STATUS_PENDING;
            $photo = uploadFile($request->back_image, IMG_USER_PATH, !empty($details->photo) ? $details->photo : '');
            $details->photo = $photo;
            $details->save();
            $image['drive_back'] = asset(IMG_USER_PATH.$details->photo);
            $data = ['success' => true, 'data' => $image, 'message' => __('Driving license photo uploaded successfully')];
        }
        else{
            $data = ['success' => false, 'data' => [], 'message' => __('Image file missing!')];
        }
        return response()->json($data);
    }

    public function saveLanguage(Request $request)
    {
        try {
            if (isset($request->lang) && !empty($request->lang)) {
                User::where('id', Auth::id())->update(['language' => $request->lang]);
                $data = ['success' => true, 'data' => ['language' => $request->lang], 'message' => __('Language changed successfully')];
            }
            else{
                $data = ['success' => false, 'data' => [], 'message' => __('Language not submitted!')];
            }
        } catch (\Exception $e) {
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($data);
    }

    public function googleSecretSave(Request $request)
    {
        if (!empty($request->code)) {
            $user = User::find(Auth::id());
            $google2fa = new Google2FA();
            if ($request->remove != 1) {
                $valid = $google2fa->verifyKey($request->google2fa_secret, $request->code);
                if ($valid) {
                    $user->google2fa_secret = $request->google2fa_secret;
                    $user->g2f_enabled = 1;
                    $user->save();
                    $data = ['success' => true, 'data' => ['secret_key' => $request->google2fa_secret], 'message' => __('Google authentication code added successfully')];
                    return response()->json($data);
                } else {
                    $data = ['success' => false, 'data' => [], 'message' => __('Google authentication code is invalid')];
                    return response()->json($data);
                }
            } else {
                if (!empty($user->google2fa_secret)) {
                    $google2fa = new Google2FA();
                    $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);
                    if ($valid) {
                        $user->google2fa_secret = null;
                        $user->g2f_enabled = '0';
                        $user->save();
                        $data = ['success' => true, 'data' => [], 'message' => __('Google authentication code removed successfully')];
                        return response()->json($data);
                    } else {
                        $data = ['success' => false, 'data' => [], 'message' => __('Google authentication code is invalid')];
                        return response()->json($data);
                    }
                } else {
                    $data = ['success' => false, 'data' => [], 'message' => __('Google authentication code is invalid')];
                    return response()->json($data);
                }
            }
        }
        $data = ['success' => false, 'data' => [], 'message' => __('Google authentication code can not be empty')];
        return response()->json($data);
    }

    public function idVerificationInfo(){
        try {
            $user = Auth::user();
            $details = VerificationDetails::where('user_id', Auth::id())->get();
            $data['nid']['status']='Not Submitted';
            $data['nid']['common'] = asset('assets/user/images/cards/nid.svg');
            $data['passport']['common'] = asset('assets/user/images/cards/passport.svg');
            $data['driving_license']['common'] = asset('assets/user/images/cards/driving-license.svg');
            $data['passport']['status']='Not Submitted';
            $data['passport']['status']='Not Submitted';
            $data['driving_license']['status']='Not Submitted';
            $data['nid']['front'] = '';
            $data['passport']['front'] = '';
            $data['driving_license']['front'] = '';
            $data['nid']['back'] = '';
            $data['passport']['back'] = '';
            $data['driving_license']['back'] = '';
            foreach($details as $detail){
                if($detail->field_name == 'nid_front'){
                    if($detail->status == STATUS_PENDING){
                        $data['nid']['status']='Pending';
                    } elseif ($detail->status == STATUS_ACCEPTED){
                        $data['nid']['status']='Approved';
                    }else{
                        $data['nid']['status']='Rejected';
                    }
                    $data['nid']['front'] = asset(IMG_USER_PATH.$detail->photo);
                }
                if($detail->field_name == 'nid_back'){
                    $data['nid']['back'] = asset(IMG_USER_PATH.$detail->photo);
                }
                if($detail->field_name == 'pass_front'){
                    if($detail->status == STATUS_PENDING){
                        $data['passport']['status']='Pending';
                    } elseif ($detail->status == STATUS_ACCEPTED){
                        $data['passport']['status']='Approved';
                    }else{
                        $data['passport']['status']='Rejected';
                    }
                    $data['passport']['front'] = asset(IMG_USER_PATH.$detail->photo);
                }
                if($detail->field_name == 'pass_back'){
                    $data['passport']['back'] = asset(IMG_USER_PATH.$detail->photo);
                }
                if($detail->field_name == 'drive_front'){
                    if($detail->status == STATUS_PENDING){
                        $data['driving_license']['status']='Pending';
                    } elseif ($detail->status == STATUS_ACCEPTED){
                        $data['driving_license']['status']='Approved';
                    }else{
                        $data['driving_license']['status']='Rejected';
                    }
                    $data['driving_license']['front'] = asset(IMG_USER_PATH.$detail->photo);
                }
                if($detail->field_name == 'drive_back'){
                    $data['driving_license']['back'] = asset(IMG_USER_PATH.$detail->photo);
                }
                $response = ['success' => true, 'data' => $data, 'message' => __('ID  verification info')];
            }
        } catch (\Exception $e) {
            DB::rollBack(); // failed and rollback
            $response = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($response);
    }

    public function gotoSettingPage(){
        try{
            $data['languages'] = langName();
            $user = Auth::user();
            if(empty($user->google2fa_secret)){
                $google2fa = new Google2FA();
                $google2fa->setAllowInsecureCallToGoogleApis(true);
                $data['google2fa_secret'] = $google2fa->generateSecretKey();
                $google2fa_url = $google2fa->getQRCodeGoogleUrl(
                    isset($default['app_title']) && !empty($default['app_title']) ? $default['app_title'] : 'cpocket',
                    isset(Auth::user()->email) && !empty(Auth::user()->email) ? Auth::user()->email : 'cpocket@email.com',
                    $data['google2fa_secret']
                );
                $data['qrcode'] = $google2fa_url;
            }
            else{
                $data['user'] = $user;
            }
            $response = ['success' => true, 'data' => $data, 'message' => __('Setting Info.')];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($response);
    }

    public function googleLoginEnableDisable()
    {
        if (!empty(Auth::user()->google2fa_secret)) {
            $user = Auth::user();
            if ($user->g2f_enabled == 0) {
                $user->g2f_enabled = '1';
                Cache::put('g2f_checked', true);
            } else {
                $user->g2f_enabled = '0';
                Cache::forget('g2f_checked');
            }
            $user->update();
            if ($user->g2f_enabled == 1){
                $response = ['success' => true, 'data' => [], 'message' => __('Google two factor authentication is enabled')];
            }
            else{
                $response = ['success' => true, 'data' => [], 'message' => __('Google two factor authentication is disabled')];
            }
        } else{
            $response = ['success' => false, 'data' => [], 'message' => __('For using google two factor authentication,please setup your authentication')];
        }
        return response()->json($response);
    }

    public function generalSettings(){
        try{
            $data['languages'] = langName();
            $data['settings'] = settings(['minimum_withdrawal_amount','maximum_withdrawal_amount','coin_price','coin_name']);
            $data['coins'] = Coin::where(['status' => STATUS_ACCEPTED])->select('id','name','type','minimum_withdrawal','maximum_withdrawal','withdrawal_fees')->get();
            $data['small_plan_description'] = MembershipPlan::where(['status'=>STATUS_ACTIVE])->orderBy('amount','asc')->first();
            $response = ['success' => true, 'data' => $data, 'message' => __('Setting Info.')];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($response);
    }


}
