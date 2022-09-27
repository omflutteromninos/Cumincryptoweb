<?php
/**
 * Created by PhpStorm.
 * User: bacchu
 * Date: 9/12/19
 * Time: 12:56 PM
 */

namespace App\Http\Services;

use App\Model\Coin;
use App\Model\UserVerificationCode;
use App\Model\Wallet;
use App\Services\Logger;
use App\Services\MailService;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public $logger;
    function __construct()
    {
        $this->logger = new Logger();
    }

    public function changePassword($request)
    {
        try {
            $user = Auth::user();
            if (!Hash::check($request->password, $user->password)) {
                $data['message'] = __('Old password doesn\'t match');
                return $data;
            }
            if (Hash::check($request->new_password, $user->password)) {
                $data['message'] = __('You already used this password');
                return $data;
            }

            $user->password = Hash::make($request->new_password);

            $user->save();

            return ['success' => true, 'message' => __('Password change successfully')];
        } catch (\Exception $exception)
        {
            return ['success' => false, 'message' => __('Something went wrong')];
        }
    }

    // sign up process
    public function signUpProcess($request)
    {
        $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong')];
        DB::beginTransaction();
        try {
            $mail_key = $this->generate_email_verification_key();
            $user = User::create([
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'email' => $request['email'],
                'role' => USER_ROLE_USER,
                'password' => Hash::make($request['password']),
            ]);
            UserVerificationCode::create(['user_id' => $user->id, 'code' => $mail_key, 'expired_at' => date('Y-m-d', strtotime('+15 days'))]);

            $coin = Coin::where('type', DEFAULT_COIN_TYPE)->first();
            Wallet::create([
                'user_id' => $user->id,
                'name' => 'Default wallet',
                'is_primary' => STATUS_SUCCESS,
                'coin_id' => $coin->id,
                'coin_type' => $coin->type,
            ]);
            app(CommonService::class)->generateNewCoinWallet($user->id);

            DB::commit();

            if (!empty($user)){
                $this->sendVerifyemail($user, $mail_key);
                $data = ['success' => true, 'data' => [], 'message' => __('Sign up successful, Please verify your mail')];

            } else {
                $data = ['success' => false, 'data' => [], 'message' => __('Sign up failed')];
            }
        } catch (\Exception $e) {
            $this->logger->log('signUpProcess', $e->getMessage());
            DB::rollback();
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong')];
        }

        return $data;
    }

// login process
    public function loginProcess($request)
    {
        $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong')];
        DB::beginTransaction();
        try {
            $user = User::where('email', $request->email)->first();

            if (!empty($user)) {
                if(empty($user->email_verified_at)) {
                    $user->email_verified_at =  0;
                }

                if(($user->role == USER_ROLE_USER)) {
                    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                        $token = $user->createToken($request->email)->accessToken;
                        if ($user->status == STATUS_SUCCESS) {
                            if (!empty($user->is_verified)) {
                                $verify_info = Cache::get('g2f_checked');
                                if(($user->g2f_enabled) && is_null($verify_info)) {
                                    $data['success'] = true;
                                    $data['data'] = ['access_token' => $token, 'access_type' => "Bearer", 'user_info' => null, 'g2f_verify'=>true,'email_verified' => 1];
                                    $data['message'] = __('Successfully logged in');
                                }else{
                                    createUserActivity(Auth::user()->id, USER_ACTIVITY_LOGIN);
                                    $user->photo = show_image($user->id,'user');
                                    $data['success'] = true;
                                    $data['data'] = ['access_token' => $token, 'access_type' => "Bearer", 'user_info' => $user,'g2f_verify'=>false,'email_verified' => 1];
                                    $data['message'] = __('Successfully logged in');
                                }
                            } else {
                                $existsToken = User::join('user_verification_codes','user_verification_codes.user_id','users.id')
                                    ->where('user_verification_codes.user_id',$user->id)
                                    ->whereDate('user_verification_codes.expired_at' ,'>=', Carbon::now()->format('Y-m-d'))
                                    ->first();

                                if(!empty($existsToken)) {
                                    $mail_key = $existsToken->code;
                                } else {
                                    $mail_key = randomNumber(6);
                                    UserVerificationCode::create(['user_id' => $user->id, 'code' => $mail_key, 'status' => STATUS_PENDING, 'expired_at' => date('Y-m-d', strtotime('+15 days'))]);
                                }
                                try {
                                    $this->sendVerifyemail($user, $mail_key);
                                    Auth::logout();
                                    $data = ['success' => false, 'email_verified' => 0, 'data' => [], 'message' => __('Your email is not verified yet. Please verify your mail.')];
                                } catch (\Exception $e) {
                                    $this->logger->log('sendVerifyEmail', $e->getMessage());
                                    Auth::logout();
                                    $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong')];
                                }
                            }
                        } elseif ($user->status == STATUS_SUSPENDED) {
                            Auth::logout();
                            $data = ['success' => false, 'data' => [], 'message' => __("Your account has been suspended. please contact support team to active again")];
                        } elseif ($user->status == STATUS_DELETED) {
                            Auth::logout();
                            $data = ['success' => false, 'data' => [], 'message' => __("Your account has been deleted. please contact support team to active again")];
                        } elseif ($user->status == STATUS_PENDING) {
                            Auth::logout();
                            $data = ['success' => false, 'data' => [], 'message' => __("Your account has been pending for admin approval. please contact support team to active again")];
                        } else {
                            Auth::logout();
                            $data = ['success' => false, 'data' => [], 'message' => __("Your account is deactivated, please contact to support")];
                        }

                    } else {
                        $data = ['success' => false, 'data' => [], 'message' => __("Email or Password doesn't match")];
                    }
                } else {
                    Auth::logout();
                    $data = ['success' => false, 'data' => [], 'message' => __('You have no login access')];
                }
            } else {
                $data = ['success' => false, 'data' => [], 'message' => __('You have no account,please register new account')];
            }
        } catch (\Exception $e) {
            $this->logger->log('signInProcess', $e->getMessage());
            DB::rollback();
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong')];
        }

        DB::commit();
        return $data;
    }

    // mail verification process
    public function emailVerifyProcess($request)
    {
        $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong')];
        try {
            $uvc = UserVerificationCode::join('users','users.id','=', 'user_verification_codes.user_id')
                ->where(['user_verification_codes.code' => $request->access_code,
                    'users.email'=>$request->email, 'user_verification_codes.status' => STATUS_PENDING])
                ->where('user_verification_codes.expired_at', '>=', date('Y-m-d'))
                ->select('user_verification_codes.id as uv_id', 'users.id', 'user_verification_codes.*')
                ->first();
            if ($uvc) {
                UserVerificationCode::where(['id' => $uvc->uv_id])->update(['status' => STATUS_SUCCESS]);
                User::where(['id' => $uvc->user_id])->update(['is_verified' => STATUS_SUCCESS]);

                $data = ['success' => true, 'message' => __('Email verified successfully')];
            } else {
                $data = ['success' => false, 'message' => __('Verification code expired or not found!')];
            }
        } catch (\Exception $e) {
            $this->logger->log('emailVerifyProcess', $e->getMessage());
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong')];
        }

        return $data;
    }

    //
    private function generate_email_verification_key()
    {
        $key = randomNumber(6);
        return $key;
    }

    // send verify email
    public function sendVerifyemail($user, $mail_key)
    {
        $mailService = new MailService();
        $userName = $user->first_name.' '.$user->last_name;
        $userEmail = $user->email;
        $companyName = isset(allsetting()['app_title']) && !empty(allsetting()['app_title']) ? allsetting()['app_title'] : __('Company Name');
        $subject = __('Email Verification | :companyName', ['companyName' => $companyName]);
        $data['data'] = $user;
        $data['key'] = $mail_key;
        $mailService->send('email.verifyapp', $data, $userEmail, $userName, $subject);
    }

    // send forgot password mail
    public function sendForgotPasswordCode($request)
    {
        $data = ['success' => false, 'message' => __('Something went wrong.')];

        $user = User::where(['email' => $request->email])->first();

        if ($user) {
            DB::beginTransaction();
            try {
                $key = randomNumber(6);
                $existsToken = User::join('user_verification_codes','user_verification_codes.user_id','users.id')
                    ->where('user_verification_codes.user_id', $user->id)
                    ->where('user_verification_codes.status', STATUS_PENDING)
                    ->whereDate('user_verification_codes.expired_at' ,'>=', Carbon::now()->format('Y-m-d'))
                    ->first();
                if(!empty($existsToken)) {
                    $token = $existsToken->code;
                } else {
                    UserVerificationCode::create(['user_id' => $user->id, 'code'=>$key,'expired_at' => date('Y-m-d', strtotime('+15 days')), 'status' => STATUS_PENDING]);
                    $token = $key;
                }

                $user_data = [
                    'user' => $user,
                    'token' => $token,
                ];

                $mailService = new MailService();
                $userName = $user->first_name.' '.$user->last_name;
                $userEmail = $user->email;
                $companyName = isset(allsetting()['app_title']) && !empty(allsetting()['app_title']) ? allsetting()['app_title'] : __('Company Name');
                $subject = __('Forgot Password | :companyName', ['companyName' => $companyName]);
                $mailService->send('email.password_reset', $user_data, $userEmail, $userName, $subject);

                $message = 'Mail sent successfully to ' . $user->email . ' with password reset code.';

                DB::commit();

                $data = ['success' => true, 'data' => [], 'message' => $message];
            } catch (\Exception $e) {
                $this->logger->log('sendForgotPasswordCode', $e->getMessage());
                DB::rollBack();
                $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong. Please check mail credential.')];
            }
        } else {
            $data = ['success' => false, 'data' => [], 'message' => __('Email not found')];
        }

        return $data;
    }

    // reset password process
    public function resetPasswordProcess($request)
    {
        $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        DB::beginTransaction();
        try {
            $vf_code = UserVerificationCode::where(['code' => $request->code, 'status' => STATUS_PENDING])->first();

            $user = User::where(['email' => $request->email])->first();
            if (!empty($user)) {
                $existsToken = User::join('user_verification_codes','user_verification_codes.user_id','users.id')
                    ->where('user_verification_codes.user_id',$user->id)
                    ->where('user_verification_codes.code', $request->code)
                    ->where('user_verification_codes.status', STATUS_PENDING)
                    ->whereDate('user_verification_codes.expired_at' ,'>=', Carbon::now()->format('Y-m-d'))
                    ->select('user_verification_codes.*')
                    ->first();
                if (empty($existsToken)) {
                    $data = ['success' => false, 'data' => [], 'message' => __('Code invalid or expired.')];
                    return $data;
                }
                $data_ins['password'] = hash::make($request->password);
                $data_ins['is_verified'] = STATUS_SUCCESS;
                if(!Hash::check($request->password, $user->password)) {

                    User::where(['id' => $existsToken->user_id])->update($data_ins);
                    UserVerificationCode::where(['id' => $existsToken->id])->delete();
                    DB::commit();

                    $data = ['success' => true, 'data' => [], 'message' => __('Password reset successfully.')];
                } else {
                    $data = ['success' => false, 'data' => [], 'message' => __('You already used this password')];
                }

            } else {
                $data = ['success' => false, 'data' => [], 'message' => __('User not found')];
            }
        } catch (\Exception $e) {
            $this->logger->log('resetPasswordProcess', $e->getMessage());
            DB::rollBack();
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }

        return $data;
    }

    public function changePasswordApp($request)
    {
        try {
            $user = Auth::user();
            if (!Hash::check($request->current_password, $user->password)) {
                $data['success'] = false;
                $data['data'] = null;
                $data['message'] = __('Old password doesn\'t match');
                return $data;
            }
            if (Hash::check($request->password, $user->password)) {
                $data['success'] = false;
                $data['data'] = null;
                $data['message'] = __('You already used this password');
                return $data;
            }
            $user->password = Hash::make($request->password);
            $user->save();
            return ['success' => true, 'data'=>[], 'message' => __('Password change successfully')];
        } catch (\Exception $exception)
        {
            return ['success' => false, 'data'=>[], 'message' => __('Something went wrong')];
        }
    }

}
