<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Services\CommonService;
use App\Http\Services\TransactionService;
use App\Model\ActivityLog;
use App\Model\BuyCoinHistory;
use App\Model\Coin;
use App\Model\DepositeTransaction;
use App\Model\Faq;
use App\Model\MembershipClub;
use App\Model\Notification;
use App\Model\WithdrawHistory;
use App\Services\Logger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private $logger;
    function __construct()
    {
        $this->logger = new Logger();
    }

    public function userDashboardApp(Request $request){
        $balance = getUserBalance(Auth::id());
        $data['balance'] = $balance['available_coin'] ?? 0;
        $data['total_buy_coin'] = BuyCoinHistory::where(['user_id'=> Auth::id(),'status'=> STATUS_ACTIVE])->sum('coin');
        $data['blocked_coin'] = 0;
        $membership = MembershipClub::where(['user_id' => Auth::id(), 'status' => STATUS_ACTIVE])->first();
        if (isset($membership)) {
            $data['blocked_coin'] = $membership->amount;
        }
        $from = Carbon::now()->subMonth(6)->format('Y-m-d h:i:s');
        $to = Carbon::now()->format('Y-m-d h:i:s');

        $common_service = new CommonService();

        if (!$request->ajax()){
            $sixmonth_diposites = $common_service->AuthUserDiposit($from,$to);
            $sixmonth_withdraws = $common_service->AuthUserWithdraw($from,$to);

            ///////////////////////////////////////////   six month data /////////////////////////////
            $data['sixmonth_diposites'] = [];
            $months = previousMonthName(5);
            $data['last_six_month'] =  $months;
            foreach ($months as $key => $month){
                $data['sixmonth_diposites'][$key]['country'] = $month;
                $data['sixmonth_diposites'][$key]['year2004'] = (isset($sixmonth_diposites[$month])) ? $sixmonth_diposites[$month] : 0;
                $data['sixmonth_diposites'][$key]['year2005'] = (isset($sixmonth_withdraws[$month])) ? $sixmonth_withdraws[$month] : 0;
            }
        }


        $data['completed_withdraw']  = WithdrawHistory::join('wallets','wallets.id','withdraw_histories.wallet_id')
            ->where('withdraw_histories.status',STATUS_SUCCESS)
            ->where('wallets.user_id',Auth::id())->sum('withdraw_histories.amount');
        $data['pending_withdraw']  = WithdrawHistory::join('wallets','wallets.id','withdraw_histories.wallet_id')
            ->where('withdraw_histories.status',STATUS_PENDING)
            ->where('wallets.user_id',Auth::id())->sum('withdraw_histories.amount');

        $allMonths = all_months();
        // deposit
        $monthlyDeposits = DepositeTransaction::join('wallets', 'wallets.id', 'deposite_transactions.receiver_wallet_id')
            ->where('wallets.user_id', Auth::id())
            ->select(DB::raw('sum(deposite_transactions.amount) as totalDepo'), DB::raw('MONTH(deposite_transactions.created_at) as months'))
            ->whereYear('deposite_transactions.created_at', Carbon::now()->year)
            ->where('deposite_transactions.status', STATUS_SUCCESS)
            ->groupBy('months')
            ->get();

        if (isset($monthlyDeposits[0])) {
            foreach ($monthlyDeposits as $depsit) {
                $data['deposit'][$depsit->months] = $depsit->totalDepo;
            }
        }
        $allDeposits = [];
        foreach ($allMonths as $month) {
            $allDeposits[] =  isset($data['deposit'][$month]) ? $data['deposit'][$month] : 0;
        }
        $data['monthly_deposit'] = $allDeposits;

        // withdrawal
        $monthlyWithdrawals = WithdrawHistory::join('wallets', 'wallets.id', 'withdraw_histories.wallet_id')
            ->select(DB::raw('sum(withdraw_histories.amount) as totalWithdraw'), DB::raw('MONTH(withdraw_histories.created_at) as months'))
            ->whereYear('withdraw_histories.created_at', Carbon::now()->year)
            ->where('withdraw_histories.status', STATUS_SUCCESS)
            ->groupBy('months')
            ->get();

        if (isset($monthlyWithdrawals[0])) {
            foreach ($monthlyWithdrawals as $withdraw) {
                $data['withdrawal'][$withdraw->months] = $withdraw->totalWithdraw;
            }
        }
        $allWithdrawal = [];
        foreach ($allMonths as $month) {
            $allWithdrawal[] =  isset($data['withdrawal'][$month]) ? $data['withdrawal'][$month] : 0;
        }
        $data['monthly_withdrawal'] = $allWithdrawal;

        // withdrawal
        $monthlyBuyCoins = BuyCoinHistory::select(DB::raw('sum(coin) as totalCoin'), DB::raw('MONTH(created_at) as months'))
            ->whereYear('created_at', Carbon::now()->year)
            ->where(['user_id'=> Auth::id(),'status'=> STATUS_SUCCESS])
            ->groupBy('months')
            ->get();

        if (isset($monthlyBuyCoins[0])) {
            foreach ($monthlyBuyCoins as $coin) {
                $data['coin'][$coin->months] = $coin->totalCoin;
            }
        }
        $allBuyCoin = [];
        foreach ($allMonths as $month) {
            $allBuyCoin[] =  isset($data['coin'][$month]) ? $data['coin'][$month] : 0;
        }
        $data['monthly_buy_coin'] = $allBuyCoin;
        $data = ['success' => true, 'data' => $data, 'message' => __('Dashboard data')];
        return response()->json($data);
    }

    //notification list
    public function notificationList()
    {
        try {
            $list = Notification::where(['user_id' => Auth::id(), 'status' => STATUS_PENDING])->orderby('id','desc')->get();
            if (isset($list[0])) {
                $data = ['success' => true, 'data' => $list, 'message' => __('Data get successfully')];
            } else {
                $data = ['success' => true, 'data' => [], 'message' => __('No data found')];
            }
        } catch (\Exception $e) {
            $this->logger('notificationList', $e->getMessage());
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($data);
    }

    //faq list
    public function faqList(Request $request)
    {
        try {
            $limit = $request->limit ?? 5;
            $list = Faq::where(['status' => STATUS_ACCEPTED])->paginate($limit);
            if (isset($list[0])) {
                $data = ['success' => true, 'data' => $list, 'message' => __('Data get successfully')];
            } else {
                $data = ['success' => true, 'data' => $list, 'message' => __('No data found')];
            }
        } catch (\Exception $e) {
            $this->logger('faqList', $e->getMessage());
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($data);
    }

    //user activity list
    public function activityList(Request $request)
    {
        try {
            $limit = $request->limit ?? 5;
            $lists = ActivityLog::where(['user_id' => Auth::id()])->paginate($limit);
            foreach($lists as $list){
                $list->action=userActivity($list->action);
            }
            if($lists) {
                $data = ['success' => true, 'data' => $lists, 'message' => __('Data get successfully')];
            } else {
                $data = ['success' => true, 'data' => $lists, 'message' => __('No data found')];
            }
        } catch (\Exception $e) {
            $this->logger('activityList', $e->getMessage());
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($data);
    }

    //trendingCoinList
    public function trendingCoinList(){
        try {
            $list = Coin::where(['status' => STATUS_ACCEPTED])->get();
            if (isset($list[0])) {
                $data = ['success' => true, 'data' => $list, 'message' => __('Data get successfully')];
            } else {
                $data = ['success' => true, 'data' => [], 'message' => __('No data found')];
            }
        } catch (\Exception $e) {
            $this->logger('trendingCoinList', $e->getMessage());
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($data);
    }

    //overviewCoinList
    public function overviewCoinList(){
        try {
            $list = Coin::where(['status' => STATUS_ACCEPTED])->get();
            if (isset($list[0])) {
                $data = ['success' => true, 'data' => $list, 'message' => __('Data get successfully')];
            } else {
                $data = ['success' => true, 'data' => [], 'message' => __('No data found')];
            }
        } catch (\Exception $e) {
            $this->logger('overviewCoinList', $e->getMessage());
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($data);
    }

    public function depositeOrWithdrawList(Request $request){
        $limit = $request->limit ?? 5;
        $tr = new TransactionService();
        if ($request->type == 'deposit') {
            $histories = $tr->depositTransactionHistories(Auth::id())->paginate($limit);
            foreach ($histories as &$history){
                $history->transaction_hash = $history->transaction_id;
            }
            $data = ['success' => true, 'data' => $histories, 'message' => __('Deposit List')];
            return response()->json($data);
        } else {
            $histories = $tr->withdrawTransactionHistories(Auth::id())->paginate($limit);
            $data = ['success' => true, 'data' => $histories, 'message' => __('Withdraw List')];
            return response()->json($data);
        }
    }

    public function depositeAndWithdrawList(Request $request){
        try {
            $limit = $request->limit ?? 5;
            $second = DB::table('withdraw_histories')
                ->select('withdraw_histories.address','withdraw_histories.amount','withdraw_histories.transaction_hash','withdraw_histories.status',
                    'withdraw_histories.created_at')
                ->join('wallets','wallets.id','=','withdraw_histories.wallet_id')
                ->where('wallets.user_id','=',Auth::id())
                ->orderBy('withdraw_histories.created_at','desc');
            $histories = DB::table('deposite_transactions')
                ->select('deposite_transactions.address','deposite_transactions.amount','deposite_transactions.transaction_id as transaction_hash',
                    'deposite_transactions.status','deposite_transactions.created_at')
                ->join('wallets as wallet_1','wallet_1.id','=','deposite_transactions.receiver_wallet_id')
                ->where('wallet_1.user_id','=',Auth::id())
                ->orderBy('deposite_transactions.created_at','desc')
                ->union($second)
                ->paginate($limit);
            $data = ['success' => true, 'data' => $histories, 'message' => __('See all list')];
        } catch (\Exception $e) {
            $data = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($data);
    }

}
