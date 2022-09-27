<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Requests\Api\withDrawApiRequest;
use App\Http\Requests\WalletCreateRequest;
use App\Http\Services\TransactionService;
use App\Jobs\Withdrawal;
use App\Model\Coin;
use App\Model\CoWalletWithdrawApproval;
use App\Model\DepositeTransaction;
use App\Model\TempWithdraw;
use App\Model\Wallet;
use App\Model\WalletAddressHistory;
use App\Model\WalletCoUser;
use App\Model\WithdrawHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class WalletController extends Controller
{
    function __construct()
    {}

    public function myPocketList(Request $request){
        $limit = $request->limit ?? 5;
        $wallets = Wallet::where(['user_id'=> Auth::id(), 'type'=> PERSONAL_WALLET])->orderBy('id', 'ASC')->paginate($limit);
        foreach ($wallets as $wallet){
            $wallet->minimum_withdrawal = $wallet->coin->minimum_withdrawal;
            $wallet->maximum_withdrawal = $wallet->coin->maximum_withdrawal;
            unset($wallet->coin);
        }
        $data = ['success' => true, 'data' => $wallets, 'message' => __('Wallet List')];
        return response()->json($data);
    }

    public function myMultiSignaturePocketList(){
        $co_wallets = Wallet::select('wallets.*')
            ->join('wallet_co_users', 'wallet_co_users.wallet_id','=','wallets.id')
            ->where(['wallets.type'=> CO_WALLET, 'wallet_co_users.user_id'=>Auth::id()])
            ->orderBy('id', 'ASC')->get();
        $data = ['success' => true, 'data' => $co_wallets, 'message' => __('Co-Wallet List')];
        return response()->json($data);
    }

    public function pocketCoinList(){
        $lists = Coin::where(['status' => STATUS_ACCEPTED])->select('id','name','type','minimum_withdrawal','maximum_withdrawal','withdrawal_fees')->get();
        $data = ['success' => true, 'data' => $lists, 'message' => __('Coin List')];
        return response()->json($data);
    }

    public function createWallet(WalletCreateRequest $request){
        if (!empty($request->wallet_name)) {
            $wallet_type = $request->type ?? PERSONAL_WALLET;
            $coin = Coin::where(['type' => strtoupper($request->coin_type)])->first();
            $alreadyWallet =  Wallet::where(['coin_id' => $coin->id,'type' => $wallet_type, 'user_id' => Auth::id()])->first();
            if($alreadyWallet) {
                $data = ['success' => false, 'data' => [], 'message' => __('You already have this type of wallet')];
            }
            else{
                try {
                    DB::beginTransaction();
                    $wallet = new Wallet();
                    $wallet->user_id = Auth::id();
                    $wallet->type = $request->type ?? PERSONAL_WALLET;
                    $wallet->name = $request->wallet_name;
                    $wallet->coin_type = strtoupper($request->coin_type);
                    $wallet->status = STATUS_SUCCESS;
                    $wallet->balance = 0;
                    $wallet->coin_id = $coin->id;
                    if (co_wallet_feature_active() && $request->type == CO_WALLET) {
                        $key = Str::random(64);
                        while (true) {
                            $keyExists = Wallet::where(['key' => $key])->first();
                            if (!empty($keyExists)) $key = Str::random(64);
                            else break;
                        }
                        $wallet->key = $key;
                    }
                    $wallet->save();
                    if (co_wallet_feature_active() && $request->type == CO_WALLET) {
                        WalletCoUser::create([
                            'user_id' => Auth::id(),
                            'wallet_id' => $wallet->id
                        ]);
                    }
                    DB::commit();
                    $data = ['success' => true, 'data' => [], 'message' => __('Pocket created successfully')];
                } catch (\Exception $e) {
                    Log::alert($e->getMessage());
                    DB::rollBack();
                    $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
                }
            }
        }
        return response()->json($data);
    }

    public function importWalletByKey(Request $request)
    {
        if (!empty($request->key)) {
            $wallet = Wallet::where(['key' => $request->key, 'status' => STATUS_ACTIVE])->first();
            if (empty($wallet)) {
                $data = ['success' => false, 'data' => [], 'message' => __('Invalid Key')];
                return response()->json($data);
            }
            $alreadyCoUser = WalletCoUser::where(['user_id'=>Auth::id(), 'wallet_id'=>$wallet->id])->first();
            if(!empty($alreadyCoUser)){
                $data = ['success' => false, 'data' => [], 'message' => __('Already imported')];
                return response()->json($data);
            }

            $maxCoUser = settings(MAX_CO_WALLET_USER_SLUG);
            $maxCoUser = !empty($maxCoUser) ? $maxCoUser : 2;
            $coUserCount = WalletCoUser::where(['wallet_id' => $wallet->id])->count();
            if($coUserCount >= $maxCoUser){
                $data = ['success' => false, 'data' => [], 'message' => __("Can't import this pocket. Max co user limit reached.")];
                return response()->json($data);
            }
            try {
                WalletCoUser::create([
                    'user_id' => Auth::id(),
                    'wallet_id' => $wallet->id
                ]);
            } catch (\Exception $e) {
                Log::alert($e->getMessage());
                $data = ['success' => false, 'data' => [], 'message' => __("Something went wrong.")];
                return response()->json($data);
            }
            $data = ['success' => true, 'data' => [], 'message' => __("Co Pocket imported successfully")];
            return response()->json($data);
        }
        $data = ['success' => false, 'data' => [], 'message' => __("Key can't be empty")];
        return response()->json($data);
    }

    public function walletDetailsByid($id)
    {
        $data['wallet_id'] = $id;
        $data['wallet_types'][PERSONAL_WALLET] = 'Personal Wallet';
        $data['wallet_types'][CO_WALLET] = 'Multi-Signature Wallet';
        $data['wallet_details'] = Wallet::select('wallets.*')
            ->join('wallet_co_users', 'wallet_co_users.wallet_id', '=', 'wallets.id')
            ->where(['wallets.id' => $id, 'wallet_co_users.user_id' => Auth::id()])
            ->Orwhere(['wallets.id' => $id, 'wallets.user_id' => Auth::id()])
            ->first();
        return response()->json(['success' => true, 'data' => $data, 'message' => __('Wallet details')]);
    }

    public function makePrimaryAccount(Request $request)
    {
        if($request->wallet_id){
            $wallet = Wallet::where(['id'=>$request->wallet_id])->first();
            if (!empty($wallet) && $wallet->type == CO_WALLET){
                return response()->json(['success' => false, 'data' => [], 'message' => __('Something went wrong')]);
            }
            Wallet::where(['user_id' => Auth::id(), 'type' => PERSONAL_WALLET])->update(['is_primary' => 0]);
            Wallet::where(['id' => $request->wallet_id])->update(['is_primary' => 1]);
            return response()->json(['success' => true, 'data' => [], 'message' => __('Default set successfully')]);
        }
        else{
            return response()->json(['success' => false, 'data' => [], 'message' => __('Wallet details')]);
        }
    }

    public function coUserList($id)
    {
        if($id){
            $co_users = Wallet::select('wallets.id','wallets.name as wallet_name','wallets.coin_type','users.id as user_id','users.first_name',
                'users.last_name','users.email','users.phone')
                ->join('wallet_co_users', 'wallet_co_users.wallet_id', '=', 'wallets.id')
                ->join('users', 'users.id', '=', 'wallet_co_users.user_id')
                ->where('wallets.id', '=', $id)
                ->get();
            return response()->json(['success' => true, 'data' => $co_users, 'message' => __('Co-User List')]);
        }
        else{
            return response()->json(['success' => false, 'data' => [], 'message' => __('Wallet Id not found!')]);
        }
    }

    public function gotoAddressApp(Request $request)
    {
        try {
            if($request->wallet_id){
                $address = DB::table('wallet_address_histories')->select('wallet_address_histories.address')
                    ->where('wallet_address_histories.wallet_id', '=', $request->wallet_id)
                    ->orderBy('wallet_address_histories.id','DESC')
                    ->first();
            }
            if(isset($address->address)) {
                return response()->json(['success' => true, 'data' => ['address'=>$address->address], 'message' => __('Address found successfully')]);
            } else {
                $myWallet = Wallet::find($request->wallet_id);
                $address = get_coin_payment_address($myWallet->coin_type);
                return response()->json(['success' => true, 'data' => ['address'=>$address], 'message' => __('Address generated successfully')]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'data' => [], 'message' => __($e->getMessage())]);
        }
    }

    public function generateNewAddressApp(Request $request)
    {
        try {
            $wallet = new \App\Services\wallet();
            $myWallet = Wallet::find($request->wallet_id);
            $address = get_coin_payment_address($myWallet->coin_type);
            if (!empty($address)) {
                $wallet->AddWalletAddressHistory($request->wallet_id, $address, $myWallet->coin_type);
                return response()->json(['success' => true, 'data' => ['address'=>$address], 'message' => __('Address generated successfully')]);
            } else {
                return response()->json(['success' => false, 'data' => [], 'message' => __('Address not generated')]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'data' => [], 'message' => __($e->getMessage())]);
        }
    }

    public function showPassAddress(Request $request){
        try {
            if($request->wallet_id){
                $addresses = DB::table('wallet_address_histories')->select('wallet_address_histories.address','wallet_address_histories.created_at')
                    ->where('wallet_address_histories.wallet_id', '=', $request->wallet_id)
                    ->get();
                return response()->json(['success' => true, 'data' => ['addresses'=>$addresses], 'message' => __('Address List')]);
            }
            else{
                return response()->json(['success' => false, 'data' => [], 'message' => __('Wallet Id not found!')]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'data' => [], 'message' => __($e->getMessage())]);
        }
    }

    public function depositeList(Request $request){
        try {
            $limit = $request->limit ?? 5;
            if($request->receiver_wallet_id){
                $histories = DepositeTransaction::where('receiver_wallet_id', $request->receiver_wallet_id)->orderBy('id','desc')->paginate($limit);
                foreach ($histories as &$history){
                    $history->transaction_hash = $history->transaction_id;
                }
                return response()->json(['success' => true, 'data' => ['deposites'=>$histories], 'message' => __('Deposite List')]);
            }
            else{
                return response()->json(['success' => false, 'data' => [], 'message' => __('Wallet missing')]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'data' => [], 'message' => __($e->getMessage())]);
        }
    }

    public function withdrawList(Request $request){
        try {
            $limit = $request->limit ?? 5;
            if($request->wallet_id){
                $withdraws = WithdrawHistory::where('wallet_id', $request->wallet_id)->orderBy('id','desc')->paginate($limit);
                return response()->json(['success' => true, 'data' => ['withdraws'=>$withdraws], 'message' => __('Withdraw List')]);
            }
            else{
                return response()->json(['success' => false, 'data' => [], 'message' => __('Wallet missing')]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'data' => [], 'message' => __($e->getMessage())]);
        }
    }

    public function withdrawalProcess(withDrawApiRequest $request)
    {
        $transactionService = new TransactionService();
        $wallet = Wallet::join('coins', 'coins.id', '=', 'wallets.coin_id')
            ->where(['wallets.id'=>$request->wallet_id, 'wallets.user_id'=>Auth::id()])
            ->select('wallets.*', 'coins.status as coin_status', 'coins.is_withdrawal', 'coins.minimum_withdrawal',
                'coins.maximum_withdrawal', 'coins.withdrawal_fees')
            ->first();

        if(co_wallet_feature_active() && empty($wallet)) {
            $wallet = Wallet::join('wallet_co_users', 'wallet_co_users.wallet_id', '=', 'wallets.id')
                ->join('coins', 'coins.id', '=', 'wallets.coin_id')
                ->select('wallets.*','coins.status as coin_status', 'coins.is_withdrawal', 'coins.minimum_withdrawal',
                    'coins.maximum_withdrawal', 'coins.withdrawal_fees')
                ->where(['wallets.id' => $request->wallet_id, 'wallets.type' => CO_WALLET, 'wallet_co_users.user_id' => Auth::id()])
                ->first();
        }
        $address = $request->address;
        $user = Auth::user();
        if(empty($wallet)) return response()->json(['success'=>false,'message'=> __('Pocket not found.')]);
        if ($wallet->balance >= $request->amount) {
            $checkValidate = $transactionService->checkWithdrawalValidation( $request, $user, $wallet);
            if ($checkValidate['success'] == false) {
                return response()->json(['success' => $checkValidate['success'], 'message' => $checkValidate['message']]);
            }
        } else {
            return response()->json(['success' => false, 'message' => __('Wallet has no enough balance')]);
        }
        $google2fa = new Google2FA();
        if (empty($request->code)) {
            return response()->json(['success'=>false,'message'=> __('Verify code is required')]);
        }
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $request = new Request();
        $request = $request->merge($data);
        if ($valid) {
            try {
                if ($wallet->type == PERSONAL_WALLET) {
                    dispatch(new Withdrawal($request->all()))->onQueue('withdrawal');
                    return response()->json(['success'=>true,'message'=> __('Withdrawal placed successfully')]);
                } else if (co_wallet_feature_active() && $wallet->type == CO_WALLET) {
                    DB::beginTransaction();
                    $tempWithdraw = TempWithdraw::create([
                        'user_id' => $user->id,
                        'wallet_id' => $wallet->id,
                        'amount' => $request->amount,
                        'address' => $request->address,
                        'message' => $request->message
                    ]);

                    CoWalletWithdrawApproval::create([
                        'temp_withdraw_id' => $tempWithdraw->id,
                        'wallet_id' => $wallet->id,
                        'user_id' => $user->id
                    ]);
                    DB::commit();
                    if ($transactionService->isAllApprovalDoneForCoWalletWithdraw($tempWithdraw)['success']) {
                        dispatch(new Withdrawal($tempWithdraw->toArray()))->onQueue('withdrawal');
                        return response()->json(['success'=>true,'message'=> __('Withdrawal placed successfully')]);
                    }
                    return response()->json(['success'=>true,'message'=> __('Process successful. Need other co users approval.')]);
                } else {
                    return response()->json(['success'=>false,'message'=> __('Invalid Pocket type.')]);
                }

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e->getMessage());
                return response()->json(['success'=>false,'message'=> __('Something went wrong.')]);
            }
        } else{
            return response()->json(['success'=>false,'message'=> __('Google two factor authentication is invalid')]);
        }
    }

    public function coWalletPendingWithdrawList(Request $request){
        try{
            if(co_wallet_feature_active()) {
                $data['pending_withdraws'] = TempWithdraw::where(['wallet_id'=>$request->wallet_id, 'status'=>STATUS_PENDING])->orderBy('id','desc')->get();
            }
         } catch (\Exception $e) {
            return response()->json(['success'=>false,'data'=>[],'message'=> __('Pending withdrawal list')]);
        }
        return response()->json(['success'=>true,'data'=>$data,'message'=> __('Something went wrong.')]);
    }

    public function coWalletUserStatusPendingWithdrawal(Request $request){
        try{
            $data['tempWithdraw'] = TempWithdraw::where(['status'=>STATUS_PENDING, 'id'=>$request->id])->first();
            $response = (new TransactionService())->approvalCounts($data['tempWithdraw']);
            $data['total_required_approval'] = $response['requiredUserApprovalCount'];
            $data['approved_count'] = $response['alreadyApprovedUserCount'];
            $data['wallet'] = Wallet::select('wallets.*')
                ->join('wallet_co_users', 'wallet_co_users.wallet_id','=','wallets.id')
                ->where(['wallets.id'=>$data['tempWithdraw']->wallet_id, 'wallets.type'=> CO_WALLET, 'wallet_co_users.user_id'=>Auth::id()])
                ->first();
            $data['co_users'] = WalletCoUser::select(DB::raw('wallet_co_users.*,
                            (CASE WHEN wallet_co_users.user_id=co_wallet_withdraw_approvals.user_id THEN '
                .STATUS_ACCEPTED.' ELSE '.STATUS_PENDING.' END) approved'))
                ->leftJoin('co_wallet_withdraw_approvals', function ($join) use ($data) {
                    $join->on('wallet_co_users.wallet_id', '=', 'co_wallet_withdraw_approvals.wallet_id')
                        ->on('wallet_co_users.user_id', '=', 'co_wallet_withdraw_approvals.user_id')
                        ->on('co_wallet_withdraw_approvals.temp_withdraw_id','=', DB::raw($data['tempWithdraw']->id));
                })
                ->where('wallet_co_users.wallet_id', $data['wallet']->id)
                ->get();
        } catch (\Exception $e) {
            return response()->json(['success'=>false,'data'=>[],'message'=> __('Something went wrong.')]);
        }
        return response()->json(['success'=>true,'data'=>$data,'message'=> __('Pending withdrawal Details')]);
    }

    public function pendingWithdrawalRequestApprove(Request $request){
        $tempWithdraw = TempWithdraw::where(['status'=>STATUS_PENDING, 'id'=>$request->id])->first();
        if(empty($tempWithdraw)) {
            return response()->json(['success'=>false,'data'=>[],'message'=> __('Invalid withdrawal')]);
        }
        $userAlreadyApproved = CoWalletWithdrawApproval::where(['temp_withdraw_id'=>$tempWithdraw->id, 'user_id'=>Auth::id()])->first();
        if(!empty($userAlreadyApproved)) {
            return response()->json(['success'=>false,'data'=>[],'message'=> __('You already approved')]);
        }
        $wallet = Wallet::select('wallets.*')
            ->join('wallet_co_users', 'wallet_co_users.wallet_id','=','wallets.id')
            ->where(['wallets.id'=>$tempWithdraw->wallet_id, 'wallets.type'=> CO_WALLET, 'wallet_co_users.user_id'=>Auth::id()])
            ->first();
        if(empty($wallet)){
            return response()->json(['success'=>false,'data'=>[],'message'=> __('Invalid pocket')]);
        }
        try {
            CoWalletWithdrawApproval::create([
                'temp_withdraw_id' => $tempWithdraw->id,
                'wallet_id' => $wallet->id,
                'user_id' => Auth::id()
            ]);

            if ((new TransactionService())->isAllApprovalDoneForCoWalletWithdraw($tempWithdraw)['success']) {
                dispatch(new Withdrawal($tempWithdraw->toArray()))->onQueue('withdrawal');
                $message = __('All approval done and withdrawal placed successfully.');
                return response()->json(['success'=>true,'data'=>[],'message'=> __($message)]);
            } else {
                $message = __('Approved successfully.');
                return response()->json(['success'=>true,'data'=>[],'message'=> __($message)]);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success'=>false,'data'=>[],'message'=> __('Something went wrong')]);
        }
    }
    public function pendingWithdrawalRequestReject(Request $request) {
        $tempWithdraw = TempWithdraw::where(['status'=>STATUS_PENDING, 'id'=>$request->id, 'user_id'=> Auth::id()])->first();
        if(empty($tempWithdraw)) {
            return response()->json(['success'=>false,'data'=>[],'message'=> __('Invalid withdrawal')]);
        }
        try {
            $tempWithdraw->status = STATUS_REJECTED;
            $tempWithdraw->save();
            return response()->json(['success'=>true,'data'=>[],'message'=> __('Withdraw rejected successfully')]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success'=>false,'data'=>[],'message'=> __('Something went wrong.')]);
        }
    }


}
