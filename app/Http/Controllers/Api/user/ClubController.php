<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Requests\Api\TransferCoinClubRequest;
use App\Http\Requests\Api\TransferCoinMainRequest;
use App\Model\MembershipBonusDistributionHistory;
use App\Model\MembershipClub;
use App\Model\MembershipPlan;
use App\Repository\ClubRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    /**
     * Initialize product service
     *
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->clubRepo = new ClubRepository;
    }
    public function membershipPlanList()
    {
        try {
            $data=[];
            $plans = MembershipPlan::select('membership_plans.*')->where('status','=',STATUS_ACTIVE)->get()->toArray();
            foreach ($plans as $key=>$plan){
                foreach ($plan as $index=>$pl){
                    if($index=='bonus_type'){
                        $data[$key][$index] = $pl;
                        $data[$key]['bonus_type_name'] = sendFeesType($pl);
                    }
                    elseif($index=='image'){
                        $data[$key]['image'] = show_plan_image($plan['id'],$plan['image']);
                    }
                    elseif($index=='status'){
                        $data[$key][$index] = $pl;
                        $data[$key]['status_name'] = status($pl);
                    }
                    else{
                        $data[$key][$index] = $pl;
                    }
                }
            }
            $response = ['success' => true, 'data'=>$data, 'message' => __('Membership Plan List Here')];
        } catch(\Exception $e) {
            $response = ['success' => false, 'data'=>[], 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }

    public function transferCoinToClubWallet(TransferCoinClubRequest $request){
        $response = $this->clubRepo->transferCoinToMembershipClub($request);
        return response()->json($response);
    }

    public function transferCoinToMainWallet(TransferCoinMainRequest $request){
        $response = $this->clubRepo->transferCoinToMyWallet($request);
        return response()->json($response);
    }

    public function membershipBonusHistory(Request $request){
        try {
            $limit = $request->limit ?? 5;
            $bonus_history = MembershipBonusDistributionHistory::where('membership_bonus_distribution_histories.user_id',Auth::id())->orderBy('id','desc')->paginate($limit);
            foreach ($bonus_history as $history){
                $history->plan_name = !empty($history->plan->plan_name) ? $history->plan->plan_name : 'N/A';
                $history->wallet_name = !empty($history->wallet->name) ? $history->wallet->name : 'N/A';
                $history->email = !empty($history->wallet->user->email) ? $history->wallet->user->email : 'N/A';
            }
            $response = ['success' => true, 'data'=>$bonus_history, 'message' => __('Member bonus history')];
        }
        catch(\Exception $e) {
            $response = ['success' => false, 'data'=>[], 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }

    public function membershipDetails(){
        try {
            $club = MembershipClub::where(['user_id' => Auth::id(), 'status'=>STATUS_ACTIVE])->first();
            $club->bonus_amount = user_plan_bonus($club->user_id);
            $club->coin_name = settings('coin_name');
            $club->plan_name = $club->plan->plan_name;
            $club->membership_status = !empty($club->plan_id) ? $club->plan->plan_name. __(' Member') : __('Normal Member');
            $club->plan_image = show_plan_image($club->plan_id,$club->plan->image);
            $response = ['success' => true, 'data'=>$club, 'message' => __('Membership Details')];
        }
        catch(\Exception $e) {
            $response = ['success' => false, 'data'=>[], 'message' => __($e->getMessage())];
        }
        return response()->json($response);
    }
}
