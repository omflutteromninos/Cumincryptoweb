<?php

namespace App\Http\Controllers\user;

use App\Http\Requests\btcDepositeRequest;
use App\Http\Services\TransactionService;
use App\Model\Bank;
use App\Model\BuyCoinHistory;
use App\Model\BuyCoinReferralHistory;
use App\Model\Coin;
use App\Model\CoinRequest;
use App\Model\DepositeTransaction;
use App\Model\Wallet;
use App\Model\WalletAddressHistory;
use App\Model\WithdrawHistory;
use App\Repository\AffiliateRepository;
use App\Repository\CoinRepository;
use App\Services\CoinPaymentsAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Model\IcoPhase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Stripe\Charge;
use Stripe\Stripe;

class CoinController extends Controller
{
    // buy coin
    public function buyCoinPage()
    {
        try {
            $data['title'] = __('Buy Coin');
            $data['settings'] = allsetting();
            $data['banks'] = Bank::where(['status' => STATUS_ACTIVE])->get();
            if(env('APP_ENV') == 'local') {
                $data['coins'] = Coin::where(['status' => STATUS_ACTIVE])->where('type', '<>', DEFAULT_COIN_TYPE)->get();
            } else {
                $data['coins'] = Coin::where(['status' => STATUS_ACTIVE])->whereNotIn('type', [DEFAULT_COIN_TYPE,COIN_TYPE_LTCT])->get();
            }
            $baseCoin = strtoupper(allsetting('base_coin_type')) ?? 'BTC';
            $url = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms='.$baseCoin);
            $data['coin_price'] = settings('coin_price');
            $data['btc_dlr'] = (settings('coin_price') * json_decode($url,true)[$baseCoin]);
            $data['btc_dlr'] = custom_number_format($data['btc_dlr']);

            $activePhases = checkAvailableBuyPhase();

            $data['no_phase'] = false;
            if ($activePhases['status'] == false) {
                $data['no_phase'] = true;
            } else {
                if ($activePhases['futurePhase'] == false) {
                    $phase_info = $activePhases['pahse_info'];
                    if (isset($phase_info)) {
                        $data['coin_price'] =  number_format($phase_info->rate,4);
                        $data['btc_dlr'] = ($data['coin_price'] * json_decode($url,true)[$baseCoin]);
                        $data['btc_dlr'] = custom_number_format($data['btc_dlr']);
                    }
                }
            }
            $data['activePhase'] = $activePhases;



            return view('user.buy_coin.index',$data);
        } catch (\Exception $e) {
            Log::info('buy coin  '.$e->getMessage());
            return redirect()->back()->with('dismiss', $e->getMessage());
        }

    }

    public function buyCoinRate(Request $request)
    {
        if ($request->ajax()) {
            $data['amount'] = isset($request->amount) ? $request->amount : 0;

            $data['coin_type'] = isset($request->payment_type) ? check_coin_type($request->payment_type) : strtoupper(allsetting('base_coin_type'));

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
                $url = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms='.$data['coin_type']);
                $data['btc_dlr'] = $data['coin_price'] * (json_decode($url,true)[$data['coin_type']]);
            }

            $data['btc_dlr'] = custom_number_format($data['btc_dlr']);

            return response()->json($data);
        }
    }


    // buy coin process
    public function buyCoin(btcDepositeRequest $request)
    {
        try {
            $baseCoin = strtoupper(allsetting('base_coin_type')) ?? 'BTC';
            $coinRepo = new CoinRepository();
            $url = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms='.$baseCoin);

            if (isset(json_decode($url, true)[$baseCoin])) {
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
                        // $affiliation_percentage = calculate_phase_percentage($request->coin, $phase->affiliation_percentage);
                        $affiliation_percentage = 0;
                        $bonus = calculate_phase_percentage($request->coin, $phase->bonus);
                        //  $coin_amount = ($request->coin + $bonus) - ($phase_fees + $affiliation_percentage);
                        $coin_amount = ($request->coin - $bonus) ;

                        $coin_price_doller = bcmul($coin_amount, $phase->rate,8);
                        $coin_price_btc = bcmul(custom_number_format(json_decode($url, true)[$baseCoin]), $coin_price_doller,8);
//                    $coin_price_btc = custom_number_format($coin_price_btc);

                    } else {
                        $coin_price_doller = bcmul($request->coin, settings('coin_price'),8);
                        $coin_price_btc = bcmul(custom_number_format(json_decode($url, true)[$baseCoin]), $coin_price_doller,8);
//                    $coin_price_btc = custom_number_format($coin_price_btc);
                    }

                } else {
                    $coin_price_doller = bcmul($request->coin, settings('coin_price'),8);
                    $coin_price_btc = bcmul(custom_number_format(json_decode($url, true)[$baseCoin]), $coin_price_doller,8);
//                $coin_price_btc = custom_number_format($coin_price_btc);
                }

                if ($request->payment_type == BTC) {
                    $buyCoinWithCoinPayment = $coinRepo->buyCoinWithCoinPayment($request, $coin_amount, $coin_price_doller,$phase_id,$referral_level, $phase_fees, $bonus, $affiliation_percentage);

                    if($buyCoinWithCoinPayment['success'] = true) {
                        return redirect()->route('buyCoinByAddress', $buyCoinWithCoinPayment['data']->address)->with('success', $buyCoinWithCoinPayment['message']);

                    } else {
                        return redirect()->back()->with('dismiss', $buyCoinWithCoinPayment['message']);
                    }

                }  elseif ($request->payment_type == BANK_DEPOSIT) {
                    $buyCoinWithBank = $coinRepo->buyCoinWithBank($request, $coin_amount, $coin_price_doller, $coin_price_btc, $phase_id, $referral_level, $phase_fees, $bonus, $affiliation_percentage);

                    if($buyCoinWithBank['success'] = true) {
                        return redirect()->back()->with('success', $buyCoinWithBank['message']);

                    } else {
                        return redirect()->back()->with('dismiss', $buyCoinWithBank['message']);
                    }
                } elseif ($request->payment_type == STRIPE) {
                    $buyCoinWithStripe = $coinRepo->buyCoinWithStripe($request, $coin_amount, $coin_price_doller, $coin_price_btc, $phase_id, $referral_level, $phase_fees, $bonus, $affiliation_percentage);

                    if ($buyCoinWithStripe['success'] = true) {
                        return redirect()->back()->with('success', $buyCoinWithStripe['message']);

                    } else {
                        return redirect()->back()->with('dismiss', $buyCoinWithStripe['message']);
                    }
                } else {
                    return redirect()->back()->with('dismiss', "Something went wrong");
                }

            } else {
                return redirect()->back()->with('dismiss', "Something went wrong");
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('dismiss', "Something went wrong");
        }
    }

    //bank details
    public function bankDetails(Request $request)
    {
        $data = ['success' => false, 'message' => __('Invalid request'), 'data_genetare'=> ''];
        $data_genetare = '';
        if(isset($request->val)) {
            $bank = Bank::where('id', $request->val)->first();
            if (isset($bank)) {
                $data_genetare = '<h3 class="text-center">'.__('Bank Details').'</h3><table class="table">';
                $data_genetare .= '<tr><td>'.__("Bank Name").' :</td> <td>'.$bank->bank_name.'</td></tr>';
                $data_genetare .= '<tr><td>'.__("Account Holder Name").' :</td> <td>'.$bank->account_holder_name.'</td></tr>';
                $data_genetare .= '<tr><td>'.__("Bank Address").' :</td> <td>'.$bank->bank_address.'</td></tr>';
                $data_genetare .= '<tr><td>'.__("Country").' :</td> <td>'.country($bank->country).'</td></tr>';
                $data_genetare .= '<tr><td>'.__("IBAN").' :</td> <td>'.$bank->iban.'</td></tr>';
                $data_genetare .= '<tr><td>'.__("Swift Code").' :</td> <td>'.$bank->swift_code.'</td></tr>';
                $data_genetare .= '</table>';
                $data['data_genetare'] = $data_genetare;
                $data['success'] = true;
                $data['message'] = __('Data get successfully.');
            }
        }

        return response()->json($data);
    }

    // coin payment success page
    public function buyCoinByAddress($address)
    {
        $data['title'] = __('Coin Payment');
        if (is_numeric($address)) {
            $coinAddress = BuyCoinHistory::where(['user_id' => Auth::id(), 'id' => $address, 'status' => STATUS_PENDING])->first();
        } else {
            $coinAddress = BuyCoinHistory::where(['user_id' => Auth::id(), 'address' => $address, 'status' => STATUS_PENDING])->first();
        }
        if (isset($coinAddress)) {
            $data['coinAddress'] = $coinAddress;
            return view('user.buy_coin.payment_success', $data);
        } else {
            return redirect()->back()->with('dismiss', __('Address not found'));
        }
    }

    // buy coin history
    public function buyCoinHistory(Request $request)
    {
        $data['title'] = __('Buy Coin History');
        if ($request->ajax()) {
            $items = BuyCoinHistory::where(['user_id'=>Auth::id()]);
            return datatables($items)
                ->addColumn('type', function ($item) {
                    return byCoinType($item->type);
                })
                ->addColumn('status', function ($item) {
                    return deposit_status($item->status);
                })
                ->make(true);
        }

        return view('user.buy_coin.buy_history', $data);
    }

    // buy coin history
    public function buyCoinReferralHistory(Request $request)
    {
        $data['title'] = __('Buy Coin referral History');
        if ($request->ajax()) {
            $items = BuyCoinReferralHistory::where(['user_id'=>Auth::id(), 'status' => STATUS_ACTIVE]);
            return datatables($items)
                ->addColumn('created_at', function ($item) {
                    return $item->created_at;
                })
                ->addColumn('status', function ($item) {
                    return deposit_status($item->status);
                })
                ->addColumn('wallet_id', function ($item) {
                    return check_default_coin_type($item->wallet->coin_type);
                })
                ->make(true);
        }

        return view('user.buy_coin.buy_referral_history', $data);
    }

    // give or request coin
    public function requestCoin(Request $request)
    {
        $data['title'] = __('Request or Give Coin ');
        $data['wallets'] = Wallet::where(['user_id' => Auth::id(), 'coin_type' => 'Default'])->where('balance','>',0)->get();
        $data['coin'] = Coin::where(['type' => DEFAULT_COIN_TYPE])->first();
        $data['qr'] = (!empty($request->qr)) ? $request->qr : 'requests';

        return view ('user.request_coin.coin_request', $data);
    }


    // send coin request
    public function sendCoinRequest(Request $request)
    {
        $coin = Coin::where(['type' => DEFAULT_COIN_TYPE])->first();
        $rules = [
            'email' => 'required|exists:users,email',
            'amount' => ['required','numeric','min:'.$coin->minimum_withdrawal,'max:'.$coin->maximum_withdrawal]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            $data['message'] = $errors;
            return redirect()->route('requestCoin', ['qr' => 'requests'])->with(['dismiss' => $errors[0]]);
        }

        try {
            $response = app(CoinRepository::class)->sendCoinAmountRequest($request);
            if ($response['success'] == true) {
                return redirect()->route('requestCoin', ['qr' => 'requests'])->with('success', $response['message']);
            } else {
                return redirect()->route('requestCoin', ['qr' => 'requests'])->withInput()->with('success', $response['message']);
            }
        } catch(\Exception $e) {
            return redirect()->back()->with(['dismiss' => $e->getMessage()]);
        }
    }

    // send coin request
    public function giveCoin(Request $request)
    {
        $coin = Coin::where(['type' => DEFAULT_COIN_TYPE])->first();
        $rules = [
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => ['required','numeric','min:'.$coin->minimum_withdrawal,'max:'.$coin->maximum_withdrawal],
            'email' => 'required|exists:users,email'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            $data['message'] = $errors;
            return redirect()->route('requestCoin', ['qr' => 'give'])->with(['dismiss' => $errors[0]]);
        }

        try {
            $response = app(CoinRepository::class)->giveCoinToUser($request);
            if ($response['success'] == true) {
                return redirect()->route('requestCoin', ['qr' => 'give'])->with('success', $response['message']);
            } else {
                return redirect()->route('requestCoin', ['qr' => 'give'])->withInput()->with('success', $response['message']);
            }
        } catch(\Exception $e) {
            return redirect()->back()->with(['dismiss' => $e->getMessage()]);
        }
    }

    // send coin history
    public function giveCoinHistory(Request $request)
    {
        $data['title'] = __('Send Coin History');
        if ($request->ajax()) {
            $items = CoinRequest::where(['sender_user_id'=>Auth::id()]);
            return datatables($items)
                ->editColumn('sender_user_id', function ($item) {
                    return $item->sender->email;
                })
                ->editColumn('coin_type', function ($item) {
                    return settings('coin_name');
                })
                ->editColumn('receiver_user_id', function ($item) {
                    return $item->receiver->email;
                })
                ->editColumn('status', function ($item) {
                    return deposit_status($item->status);
                })
                ->make(true);
        }

        return view('user.request_coin.coin_give_history', $data);
    }


    // send coin history
    public function receiveCoinHistory(Request $request)
    {
        $data['title'] = __('Received Coin History');
        if ($request->ajax()) {
            $items = CoinRequest::where(['receiver_user_id'=>Auth::id()]);
            return datatables($items)
                ->editColumn('sender_user_id', function ($item) {
                    return $item->sender->email;
                })
                ->editColumn('coin_type', function ($item) {
                    return settings('coin_name');;
                })
                ->editColumn('receiver_user_id', function ($item) {
                    return $item->receiver->email;
                })
                ->editColumn('status', function ($item) {
                    return deposit_status($item->status);
                })
                ->make(true);
        }

        return view('user.request_coin.coin_receive_history', $data);
    }

    // pending request coin history
    public function pendingRequest(Request $request)
    {
        $data['title'] = __('Pending Request');
        if ($request->ajax()) {
            $items = CoinRequest::where(['sender_user_id'=>Auth::id(), 'status'=> STATUS_PENDING]);
            return datatables($items)
                ->editColumn('receiver_user_id', function ($item) {
                    return $item->receiver->email;
                })
                ->editColumn('coin_type', function ($item) {
                    return settings('coin_name');;
                })
                ->addColumn('action', function ($wdrl) {
                    $action = '<ul>';
                    $action .= accept_html('acceptCoinRequest',encrypt($wdrl->id));
                    $action .= reject_html('declineCoinRequest',encrypt($wdrl->id));
                    $action .= '<ul>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('user.request_coin.coin_pending_request', $data);
    }

    // coin request accept process

    public function acceptCoinRequest($id)
    {
        try {
            $request_id = decrypt($id);
        } catch (\Exception $e) {
            return redirect()->back();
        }
        try {
            $response = app(CoinRepository::class)->acceptCoinRequest($request_id);
            if ($response['success'] == true) {
                return redirect()->back()->with('success', $response['message']);
            } else {
                return redirect()->back()->withInput()->with('success', $response['message']);
            }
        } catch(\Exception $e) {
            return redirect()->back()->with(['dismiss' => $e->getMessage()]);
        }
    }

    // pending coin reject process
    public function declineCoinRequest($id)
    {
        if (isset($id)) {
            try {
                $request_id = decrypt($id);
            } catch (\Exception $e) {
                return redirect()->back();
            }
            try {
                $response = app(CoinRepository::class)->rejectCoinRequest($request_id);
                if ($response['success'] == true) {
                    return redirect()->back()->with('success', $response['message']);
                } else {
                    return redirect()->back()->withInput()->with('success', $response['message']);
                }
            } catch(\Exception $e) {
                return redirect()->back()->with(['dismiss' => $e->getMessage()]);
            }

        }
    }

    // withdrawal coin history
    public function defaultWithdrawalHistory(Request $request)
    {
        $data['title'] = __('Withdrawal Coin History');
        if ($request->ajax()) {
            $items =WithdrawHistory::where('user_id',Auth::id());
            return datatables($items)

                ->editColumn('status', function ($item) {
                    return deposit_status($item->status);
                })
                ->make(true);
        }

        return view('user.withdrawal_coin.withdrawal_history', $data);
    }

    // when withdrawal failed then it should be called
    public function withdrawalCancelCallback(Request $request)
    {
        Log::info('withdrawalCancelCallback called');
        DB::beginTransaction();
        try {
            $temp = WithdrawHistory::find($request->temp_id);
            if ($temp) {
                $temp->delete();

                DB::commit();
                $data['success'] = true;
                $data['message'] = __('Withdrawal cancelled');
                Log::info('Default Withdrawal cancelled');
            } else {
                $this->logger->log('withdrawalCancelCallback','temp withdrawal not found '.$request->temp_id);
                $data['success'] = false;
                $data['message'] = __('Temp withdrawal not found');
            }
        } catch (\Exception $exception) {
            $this->logger->log('withdrawalCancelCallback',$exception->getMessage());
            DB::rollBack();
            $data['success'] = false;
            $data['message'] = __('Something went wrong');
        }
        return response()->json($data);
    }

    // default coin deposit
    public function depositCallback(Request $request)
    {

        Log::info('call deposit wallet');
        $data = ['success'=>false,'message'=>'something went wrong'];

        DB::beginTransaction();
        try {
            $request = (object)$request;
            Log::info(json_encode($request));

            $walletAddress = DepositeTransaction::where('transaction_id', $request->transactionHash)->first();
            $wallet = Wallet::where("user_id",Auth::id())->where("coin_type", DEFAULT_COIN_TYPE)->first();

            if (empty($walletAddress) && !empty($wallet)) {

                //    $wallet =  $walletAddress->wallet;
                $data['user_id'] = $wallet->user_id;
                if (!empty($wallet)){
                    $checkDeposit = DepositeTransaction::where('transaction_id', $request->transactionHash)->first();
                    if (isset($checkDeposit)) {
                        $data = ['success'=>false,'message'=>'Transaction id already exists in deposit'];
                        Log::info('Transaction id already exists in deposit');
                        return $data;
                    }
                    $depositData = [
                        'address' => $request->from,
                        'address_type' => ADDRESS_TYPE_EXTERNAL,
                        'amount' => $request->value,
                        'fees' => 0,
                        'doller' => $request->transactionIndex * settings('coin_price'),
                        'btc' => 0,
                        'type' => $wallet->coin_type,
                        'transaction_id' => $request->transactionHash,
                        'confirmations' => $request->blockNumber,
                        'status' => STATUS_SUCCESS,
                        'receiver_wallet_id' => $wallet->id
                    ];

                    $depositCreate = DepositeTransaction::create($depositData);
                    Log::info(json_encode($depositCreate));

                    if (($depositCreate)) {
                        Log::info('Balance before deposit '.$wallet->balance);
                        $wallet->increment('balance', $depositCreate->amount);

                        Log::info('Balance after deposit '.$wallet->balance);
                        $data['message'] = 'Deposited successfully';
                        $data['hash'] = $request->transactionHash;
                        $data['success'] = true;
                    } else {
                        Log::info('Deposit not created ');
                        $data['message'] = 'Deposit not created';
                        $data['success'] = false;
                    }

                } else {
                    $data = ['success'=>false,'message'=>'No wallet found'];
                    Log::info('No wallet found');
                }
            }

            DB::commit();
            return $data;
        } catch (\Exception $e) {
            $data['message'] = $e->getMessage().' '.$e->getLine();
            Log::info($data['message']);
            DB::rollback();

            return $data;
        }
    }

    // withdrawal coin
    public function withdrawalCoin(Request $request)
    {
        $data['title'] = __('Withdrawal Coin ');
        $data['wallet'] = Wallet::join('coins', 'coins.id', '=', 'wallets.coin_id')
            ->where(['wallets.user_id' => Auth::id(), 'wallets.coin_type' => DEFAULT_COIN_TYPE])
            ->select('wallets.*', 'coins.status as coin_status', 'coins.is_withdrawal', 'coins.minimum_withdrawal',
                'coins.maximum_withdrawal', 'coins.withdrawal_fees')
            ->first();
        $data['qr'] = (!empty($request->qr)) ? $request->qr : 'requests';

        return view ('user.withdrawal_coin.withdrawal_coin', $data);
    }

    // withdrawal callback
    public function callback(Request $request)
    {
        try {
            $temp = WithdrawHistory::find($request->temp);
            $temp->status = STATUS_ACCEPTED;
            $temp->transaction_hash = $request->hash;
            $temp->save();

            $wallet = Wallet::find($temp->wallet_id);
            $deductAmount = $temp->amount + $temp->fees;
            $wallet->decrement('balance', $deductAmount);

            $data['success'] = true;
            $data['message'] = __('Withdrawal is now completed');
            $data['hash'] = $request->hash;
        }catch (\Exception $exception){
            $data['success'] = false;
            $data['message'] = __('Something went wrong');
        }
        return response()->json($data);

    }

    // check default balance
    public function checkBalance($balance,Request $request)
    {
        Log::info('withdrawal balance '. $balance);
        Log::info(json_encode($request->all()));
        $transactionService = new TransactionService();
        $wallet = Wallet::join('coins', 'coins.id', '=', 'wallets.coin_id')
            ->where(['wallets.user_id' => Auth::id(), 'wallets.coin_type' => DEFAULT_COIN_TYPE])
            ->select('wallets.*', 'coins.status as coin_status', 'coins.is_withdrawal', 'coins.minimum_withdrawal',
                'coins.maximum_withdrawal', 'coins.withdrawal_fees')
            ->first();
        $user = Auth::user();
        if ($wallet) {
            $data['success'] = $balance < $wallet->balance;
            Log::info('with bl '. $balance);
            Log::info('wallet bl '. $wallet->balance);
            if ($data['success']){
                $checkKyc = $transactionService->kycValidationCheck($user->id);
                if ($checkKyc['success'] == false) {
                    return response()->json(['success' => $checkKyc['success'], 'message' => $checkKyc['message']]);
                }
                $checkValidate = $transactionService->checkWithdrawalValidation( $request, $user, $wallet);
                Log::info(json_encode($checkValidate));
                if ($checkValidate['success'] == false) {
                    return response()->json(['success' => $checkValidate['success'], 'message' => $checkValidate['message']]);
                } else {
                    $result = $transactionService->sendChainExternal($wallet->id,$request->address,$request->amount);
                    Log::info('withdrawal result '.json_encode($result));
                    if ($result['success']){
                        $data['cl'] = allsetting('chain_link');
                        $data['ca'] = allsetting('contract_address');
                        $data['wa'] = allsetting('wallet_address');
                        $data['pk'] = allsetting('private_key');
                        $data['chain_link'] = allsetting('chain_link');
                        $data['success'] = true;
                        $data['message'] = $result['message'];
                        $data['temp'] = $result['temp'];
                        Log::info('chain '.json_encode($data));
                        return $data;
                    } else {
                        return response()->json($result);
                    }
                }
            }else{
                $data['message'] = __("Amount can't be more then available balance");
                $data['success'] = false;
                Log::info(json_encode($data));
                return response()->json($data);
            }
        } else {
            $data['message'] = __("Wallet not found");
            $data['success'] = false;
            Log::info(($data));
            return response()->json($data);
        }
    }
}
