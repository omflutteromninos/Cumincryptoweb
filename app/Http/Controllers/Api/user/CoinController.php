<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Requests\Admin\GiveCoinRequest;
use App\Http\Requests\Api\CoinSwapRequest;
use App\Http\Requests\Api\GiveCoinApp;
use App\Http\Requests\Api\SendCoinRequest;
use App\Http\Requests\btcDepositeRequest;
use App\Model\Bank;
use App\Model\BuyCoinHistory;
use App\Model\Coin;
use App\Model\CoinRequest;
use App\Model\IcoPhase;
use App\Model\Wallet;
use App\Model\WalletSwapHistory;
use App\Repository\CoinRepository;
use App\Repository\WalletRepository;
use App\Services\CoinPaymentsAPI;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CoinController extends Controller
{
    function __construct()
    {}
    public function sendCoinRequestApp(SendCoinRequest $request){
        try {
            $user = User::where(['email'=> $request->email, 'role'=> USER_ROLE_USER, 'status'=> STATUS_ACTIVE])->first();
            if (isset($user)) {
                if ($user->email == Auth::user()->email) {
                    $response = ['success' => false, 'data' => [], 'message' => __('You can not send request to your own email')];
                    return response()->json($response);
                }
                $myWallet = get_primary_wallet(Auth::id(), 'Default');
                $userWallet = get_primary_wallet($user->id, 'Default');
                $data = [
                    'amount' => $request->amount,
                    'sender_user_id' => $user->id,
                    'sender_wallet_id' => $userWallet->id,
                    'receiver_user_id' => Auth::id(),
                    'receiver_wallet_id' => $myWallet->id
                ];
                CoinRequest::create($data);
                $response = ['success' => true, 'message' => __('Request sent successfully. Please wait for user approval')];
            } else {
                $response = ['success' => false, 'message' => __('User not found')];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => __('Something went wrong')];
        }
        return response()->json($response);
    }

    public function pendingCoinRequest(Request $request){
        $limit = $request->limit ?? 5;
        $coin_requests = DB::table('coin_requests')->select('coin_requests.*','users.email as sender_email','new_users.email as receiver_email')
            ->join('users', 'users.id','=','coin_requests.sender_user_id')
            ->join('users as new_users', 'new_users.id','=','coin_requests.receiver_user_id')
            ->where('coin_requests.sender_user_id', '=', Auth::id())
            ->where('coin_requests.status', '=', STATUS_PENDING)
            ->orderBy('coin_requests.id','desc')
            ->paginate($limit);
        $response = ['success' => true, 'data' => ['pending_history'=>$coin_requests,'coin_name'=>settings('coin_name')], 'message' => __('Pending request list here')];
        return response()->json($response);
    }

    public function requestCoinApp(){
        try {
            $default_wallet = Wallet::where(['user_id' => Auth::id(), 'coin_type' => 'Default'])->where('balance','>',0)->get();
            $response = ['success' => true, 'data' => $default_wallet, 'message' => __('Default wallet information!')];
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => __('Something went wrong')];
        }
        return response()->json($response);
    }

    public function giveCoinApp(GiveCoinApp $request){
        try {
            $response = app(CoinRepository::class)->giveCoinToUser($request);
        } catch(\Exception $e) {
            $response = ['success' => false, 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }

    public function sendCoinHistory(Request $request){
        try {
            $limit = $request->limit ?? 5;
            $coin_requests = DB::table('coin_requests')->select('coin_requests.*','users.email as sender_email','new_users.email as receiver_email')
                ->join('users', 'users.id','=','coin_requests.sender_user_id')
                ->join('users as new_users', 'new_users.id','=','coin_requests.receiver_user_id')
                ->where('coin_requests.sender_user_id', '=', Auth::id())
                ->orderBy('coin_requests.id','desc')
                ->paginate($limit);
            $response = ['success' => true, 'data' =>['coin_history'=>$coin_requests,'coin_name'=>settings('coin_name')], 'message' => __('Send coin history here')];
        } catch(\Exception $e) {
            $response = ['success' => false, 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }
    public function receiveCoinHistory(Request $request){
        try {
            $limit = $request->limit ?? 5;
            $receive_coin_list = DB::table('coin_requests')->select('coin_requests.*','users.email as sender_email','new_users.email as receiver_email')
                ->join('users', 'users.id','=','coin_requests.sender_user_id')
                ->join('users as new_users', 'new_users.id','=','coin_requests.receiver_user_id')
                ->where('coin_requests.receiver_user_id', '=', Auth::id())
                ->orderBy('coin_requests.id','desc')
                ->paginate($limit);
            $response = ['success' => true, 'data' => ['receive_coin_list'=>$receive_coin_list,'coin_name'=>settings('coin_name')], 'message' => __('Receive coin history here')];
            return response()->json($response);
        } catch(\Exception $e) {
            $response = ['success' => false, 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }

    public function approvalActionForCoinRequest(Request $request){
        if($request->request_id && !empty($request->action_flag)){
            try {
                if($request->action_flag=='accept'){
                    $response = app(CoinRepository::class)->acceptCoinRequest($request->request_id);
                }
                elseif($request->action_flag=='reject'){
                    $response = app(CoinRepository::class)->rejectCoinRequest($request->request_id);
                }
                else{
                    $response = ['success' => false, 'message' => __('Invalid action flag given!')];
                }
            } catch(\Exception $e) {
                $response = ['success' => false, 'message' => __($e->getMessage())];
            }
        }else{
            $response = ['success' => false, 'message' => __('Request Id Or action flag missing!')];
        }
        return response()->json($response);
    }

    public function getBuyCoinAndPhaseInformation(){
        try{
            $data['settings'] = allsetting();
            $data['banks'] = Bank::where(['status' => STATUS_ACTIVE])->get();
            if(env('APP_ENV') == 'local') {
                $data['coins'] = Coin::where(['status' => STATUS_ACTIVE])->where('type', '<>', DEFAULT_COIN_TYPE)->get();
            } else {
                $data['coins'] = Coin::where(['status' => STATUS_ACTIVE])->whereNotIn('type', [DEFAULT_COIN_TYPE,COIN_TYPE_LTCT])->get();
            }
            $url = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC');
            $data['coin_price'] = settings('coin_price');
            $data['base_coin_type'] = allsetting('base_coin_type');
            $data['coin_name'] = settings('coin_name');
            $data['btc_dlr'] = (settings('coin_price') * json_decode($url,true)['BTC']);
            $data['btc_dlr'] = custom_number_format($data['btc_dlr']);
            $activePhases = checkAvailableBuyPhase();
            $data['no_phase'] = false;
            if ($activePhases['status'] == false) {
                $data['no_phase'] = true;
            } else {
                if ($activePhases['futurePhase'] == false) {
                    $phase_info = $activePhases['pahse_info'];
                    if (isset($phase_info)) {
                        $data['coin_price'] =  $phase_info->rate;
                        $data['btc_dlr'] = ($data['coin_price'] * json_decode($url,true)['BTC']);
                        $data['btc_dlr'] = custom_number_format($data['btc_dlr']);
                    }
                }
            }
            $data['activePhase'] = $activePhases;
            if(isset($data['settings']['payment_method_coin_payment']) && ($data['settings']['payment_method_coin_payment'] == 1)){
                $data['payment_methods'][BTC] = 'Coin Payment';
            }
            if(isset($data['settings']['payment_method_bank_deposit']) && ($data['settings']['payment_method_bank_deposit'] == 1)){
                $data['payment_methods'][BANK_DEPOSIT] = 'Bank Deposit';
            }
            if(isset($data['settings']['payment_method_stripe']) && ($data['settings']['payment_method_stripe'] == 1)){
                $data['payment_methods'][STRIPE] = 'Credit Card';
            }
            $response = ['success' => true, 'data'=>$data, 'message' => __('Buy coin and phase information')];
        } catch(\Exception $e) {
            $response = ['success' => false, 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }

    public function buyCoinThroughApp(btcDepositeRequest $request){
        try {
            $coinRepo = new CoinRepository();
            $url = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC');
            if (isset(json_decode($url, true)['BTC'])) {
                $phase_fees = 0;
                $affiliation_percentage = 0;
                $bonus = 0;
                $coin_amount = $request->coin;
                $phase_id = '';
                $referral_level = '';
                if (isset($request->phase_id)) {
                    $phase = IcoPhase::where('id',$request->phase_id)->first();
                    if (isset($phase)) {
                        $total_sell = BuyCoinHistory::where('phase_id',$phase->id)->sum('coin');
                        if (($total_sell + $coin_amount) > $phase->amount) {
                            return redirect()->back()->with('dismiss', __('Insufficient phase amount'));
                        }
                        $phase_id = $phase->id;
                        $referral_level = $phase->affiliation_level;
                        $phase_fees = calculate_phase_percentage($request->coin, $phase->fees);
                        $affiliation_percentage = 0;
                        $bonus = calculate_phase_percentage($request->coin, $phase->bonus);
                        $coin_amount = ($request->coin - $bonus) ;
                        $coin_price_doller = bcmul($coin_amount, $phase->rate,8);
                        $coin_price_btc = bcmul(custom_number_format(json_decode($url, true)['BTC']), $coin_price_doller,8);
                    } else {
                        $coin_price_doller = bcmul($request->coin, settings('coin_price'),8);
                        $coin_price_btc = bcmul(custom_number_format(json_decode($url, true)['BTC']), $coin_price_doller,8);
                    }
                } else {
                    $coin_price_doller = bcmul($request->coin, settings('coin_price'),8);
                    $coin_price_btc = bcmul(custom_number_format(json_decode($url, true)['BTC']), $coin_price_doller,8);
                }
                if ($request->payment_type == BTC) {
                    $buyCoinWithCoinPayment = $coinRepo->buyCoinWithCoinPayment($request, $coin_amount, $coin_price_doller,$phase_id,$referral_level, $phase_fees, $bonus, $affiliation_percentage);
                    if($buyCoinWithCoinPayment['success'] = true) {
                        $response = ['success' => true, 'data'=>['address'=>$buyCoinWithCoinPayment['data']->address], 'message' => __($buyCoinWithCoinPayment['message'])];
                    } else {
                        $response = ['success' => false, 'message' => __($buyCoinWithCoinPayment['message'])];
                    }
                }  elseif ($request->payment_type == BANK_DEPOSIT) {
                    $buyCoinWithBank = $coinRepo->buyCoinWithBank($request, $coin_amount, $coin_price_doller, $coin_price_btc, $phase_id, $referral_level, $phase_fees, $bonus, $affiliation_percentage);
                    if($buyCoinWithBank['success'] = true) {
                        $response = ['success' => true, 'message' => __($buyCoinWithBank['message'])];
                    } else {
                        $response = ['success' => false, 'message' => __($buyCoinWithBank['message'])];
                    }
                } elseif ($request->payment_type == STRIPE) {
                    $buyCoinWithStripe = $coinRepo->buyCoinWithStripe($request, $coin_amount, $coin_price_doller, $coin_price_btc, $phase_id, $referral_level, $phase_fees, $bonus, $affiliation_percentage);
                    if ($buyCoinWithStripe['success'] = true) {
                        $response = ['success' => true, 'message' => __($buyCoinWithStripe['message'])];
                    } else {
                        $response = ['success' => false, 'message' => __($buyCoinWithStripe['message'])];
                    }
                } else {
                    $response = ['success' => false, 'message' => __('Something went wrong')];
                }
            } else {
                $response = ['success' => false, 'message' => __('Something went wrong')];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }

    public function buyCoinHistoryApp(Request $request)
    {
        try {
            $limit = $request->limit ?? 5;
            $histories = BuyCoinHistory::where(['user_id'=>Auth::id()])->orderBy('id','desc')->paginate($limit);
            foreach($histories as $index=>&$history){
                $history->type = byCoinType($history->type);
                $history->status = deposit_status($history->status);
            }
            $response = ['success' => true, 'data'=>$histories, 'message' => __('Buy Coin History Here')];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data'=>[], 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }

    public function getCoinRate(CoinSwapRequest $request){
        try {
            $wallet_repo = new WalletRepository();
            $res = $wallet_repo->get_wallet_rate($request);
            $response = ['success' => true, 'data'=>$res, 'message' => __('Coin rate here')];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data'=>[], 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }

    public function coinSwapApp(Request $request){
        try {
            $res = Wallet::where('user_id', Auth::id())->get();
            $response = ['success' => true, 'data'=>$res, 'message' => __('Wallet here')];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data'=>[], 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }

    public function swapCoinApp(CoinSwapRequest $request){
        try {
            $wallet_repo = new WalletRepository();
            $fromWallet = Wallet::where(['id'=>$request->from_coin_id])->first();
            if (!empty($fromWallet) && $fromWallet->type == CO_WALLET){
                $response = ['success' => false, 'data'=>[], 'message' => __('Something went wrong')];
                return response()->json($response);
            }
            $rate_response = $wallet_repo->get_wallet_rate($request);
            if ($rate_response['success'] == false) {
                $response = ['success' => false, 'data'=>[], 'message' => __('Something went wrong')];
                return response()->json($response);
            }
            $swap_coin = $wallet_repo->coinSwap($rate_response['from_wallet'], $rate_response['to_wallet'], $rate_response['convert_rate'], $rate_response['amount'], $rate_response['rate']);
            if ($swap_coin['success'] == true) {
                $response = ['success' => true, 'data'=>[], 'message' => __($swap_coin['message'])];
            } else {
                $response = ['success' => false, 'data'=>[], 'message' => __($swap_coin['message'])];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'data'=>[], 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }

    public function showSwapCoinHistory(Request $request){
        try {
            $limit = $request->limit ?? 5;
            $lists = WalletSwapHistory::where(['user_id' => Auth::id()])->orderBy('id','desc')->paginate($limit);
            foreach ($lists as &$list){
                $list->from_wallet_name = $list->fromWallet->name;
                $list->to_wallet_name = $list->toWallet->name;
                $list->requested_amount_string = $list->requested_amount. ' ' .check_default_coin_type($list->from_coin_type);
                $list->converted_amount_string = $list->converted_amount. ' ' .check_default_coin_type($list->to_coin_type);
            }
            $response = ['success' => true, 'data'=>$lists, 'message' => __('Swap coin history here')];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data'=>[], 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }

    public function buyCoinRateApp(Request $request)
    {

        try {
            $data['amount'] = isset($request->amount) ? $request->amount : 0;

            $data['coin_type'] = isset($request->payment_type) ? check_coin_type($request->payment_type) : allsetting('base_coin_type');

            $coin_price = settings('coin_price');
            $activePhases = checkAvailableBuyPhase();
            $data['phase_fees'] = 0;
            $data['bonus'] = 0;
            $data['no_phase'] = false;
            if ($activePhases['status'] == false) {
                $data['no_phase'] = true;
            } else {
                if ($activePhases['futurePhase'] == false) {

                    $phase_info = $activePhases['pahse_info'];
                    if (isset($phase_info)) {
                        $coin_price =  customNumberFormat($phase_info->rate);
                        $data['phase_fees'] = calculate_phase_percentage($data['amount'], $phase_info->fees);
                        $affiliation_percentage = 0;
                        $data['bonus'] = calculate_phase_percentage($data['amount'], $phase_info->bonus);


                        // $coin_amount = ($data['amount'] + $data['bonus']) - ($data['phase_fees'] + $affiliation_percentage);
                        $coin_amount = ($data['amount'] - $data['bonus']);
                        $data['amount'] = $coin_amount;
                        $data['phase_fees'] = customNumberFormat($data['phase_fees']);
                    }
                }
            }

            $data['coin_price'] = bcmul($coin_price,$data['amount'],8);
            $data['coin_price'] = customNumberFormat($data['coin_price']);
            if ($request->pay_type == BTC) {
                $coinpayment = new CoinPaymentsAPI();
                $api_rate = $coinpayment->GetRates('');


                $data['btc_dlr'] = converts_currency($data['coin_price'], $data['coin_type'],$api_rate);

            } else {
                $data['coin_type'] = allsetting('base_coin_type');
                $url = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC');
                $data['btc_dlr'] = $data['coin_price'] * (json_decode($url,true)['BTC']);
            }

            $data['btc_dlr'] = custom_number_format($data['btc_dlr']);
            $response = ['success' => true, 'data'=>$data, 'message' => __('')];
        } catch (\Exception $e) {
            $response = ['success' => false, 'data'=>[], 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }

}
