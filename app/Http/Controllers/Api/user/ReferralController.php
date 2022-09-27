<?php

namespace App\Http\Controllers\Api\user;

use App\Model\AffiliationHistory;
use App\Repository\AffiliateRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReferralController extends Controller
{
    protected $affiliateRepository;

    public function __construct(AffiliateRepository $affiliateRepository)
    {
        $this->affiliateRepository = $affiliateRepository;
    }

    public function generateReferralLink(){
        $user = Auth::user();
        if (!$user->Affiliate) {
            $created = $this->affiliateRepository->create($user->id);
            if ($created < 1) {
                $response = ['success' => false, 'data'=>[], 'message' => __('Failed to generate new referral code.')];
                return response()->json($response);
            }
        }
        $data['url'] = url('') . '/referral-reg?ref_code=' . $user->affiliate->code;
        $response = ['success' => true, 'data'=>$data, 'message' => __('Referral link here.')];
        return response()->json($response);
    }
    public function myReferenceReferral(Request $request){
        try{
            $limit = $request->limit ?? 3;
            $ref['referrals_3'] = DB::table('referral_users as ru1')->where('ru1.parent_id', Auth::user()->id)
                ->Join('referral_users as ru2', 'ru2.parent_id', '=', 'ru1.user_id')
                ->Join('referral_users as ru3', 'ru3.parent_id', '=', 'ru2.user_id')
                ->join('users', 'users.id', '=', 'ru3.user_id')
                ->select('ru3.user_id as level_3', 'users.email','users.first_name as full_name','users.created_at as joining_date')
                ->get();
            $ref['referrals_2'] = DB::table('referral_users as ru1')->where('ru1.parent_id', Auth::user()->id)
                ->Join('referral_users as ru2', 'ru2.parent_id', '=', 'ru1.user_id')
                ->join('users', 'users.id', '=', 'ru2.user_id')
                ->select('ru2.user_id as level_2','users.email','users.first_name as full_name','users.created_at as joining_date')
                ->get();
            $ref['referrals_1'] = DB::table('referral_users as ru1')->where('ru1.parent_id', Auth::user()->id)
                ->join('users', 'users.id', '=', 'ru1.user_id')
                ->select('ru1.user_id as level_1','users.email','users.first_name as full_name','users.created_at as joining_date')
                ->get();
            $referralUsers = [];
            foreach ($ref['referrals_1'] as $level1) {
                if(count($referralUsers)==$limit){
                    break;
                }
                $referralUser['id'] = $level1->level_1;
                $referralUser['full_name'] = $level1->full_name;
                $referralUser['email'] = $level1->email;
                $referralUser['joining_date'] = $level1->joining_date;
                $referralUser['level'] = __("Level 1");
                $referralUsers [] = $referralUser;
            }
            foreach ($ref['referrals_2'] as $level2) {
                if(count($referralUsers)==$limit){
                    break;
                }
                $referralUser['id'] = $level2->level_2;
                $referralUser['full_name'] = $level2->full_name;
                $referralUser['email'] = $level2->email;
                $referralUser['joining_date'] = $level2->joining_date;
                $referralUser['level'] = __("Level 2");
                $referralUsers [] = $referralUser;
            }
            foreach ($ref['referrals_3'] as $level3) {
                if(count($referralUsers)==$limit){
                    break;
                }
                $referralUser['id'] = $level3->level_3;
                $referralUser['full_name'] = $level3->full_name;
                $referralUser['email'] = $level3->email;
                $referralUser['joining_date'] = $level3->joining_date;
                $referralUser['level'] = __("Level 3");
                $referralUsers [] = $referralUser;
            }
            $data['referral'] = $referralUsers;
            $maxReferralLevel = 3;
            $referralQuery = $this->affiliateRepository->childrenReferralQuery($maxReferralLevel);
            $referralAll = $referralQuery['referral_all']->where('ru1.parent_id', Auth::id())
                ->select('ru1.parent_id', DB::raw($referralQuery['select_query']))
                ->first();
            for ($i = 0; $i < $maxReferralLevel; $i++) {
                $level = 'level' . ($i + 1);
                $data['referralLevel'] ["Level ".($i+1)] = $referralAll->{$level};
            }
            $response = ['success' => true, 'data' => $data, 'message' => __('Referral and Number of Referral Level Member Here')];
        }catch (\Exception $e) {
            $response = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($response);
    }


    public function myReferenceList(){
        try{
            $result = DB::table('referral_users')
                ->select('referral_users.user_id as level_one_user','ru.user_id as level_two_user','ru1.user_id as level_three_user',
                    'users.email as level_one_user_email','u.email as level_two_user_email','u1.email as level_three_user_email',
                    'users.created_at as level_one_user_joining_date','u.created_at as level_two_user_joining_date','u1.created_at as level_three_user_joining_date',
                    DB::raw('CONCAT(users.first_name," ",users.last_name) as level_one_user_name'),
                    DB::raw('CONCAT(u.first_name," ",u.last_name) as level_two_user_name'),
                    DB::raw('CONCAT(u1.first_name," ",u1.last_name) as level_three_user_name'))
                ->leftJoin('referral_users as ru', 'ru.parent_id', '=', 'referral_users.user_id')
                ->leftJoin('referral_users as ru1', 'ru1.parent_id', '=', 'ru.user_id')
                ->leftJoin('users', 'users.id', '=', 'referral_users.user_id')
                ->leftJoin('users as u', 'u.id', '=', 'ru.user_id')
                ->leftJoin('users as u1', 'u1.id', '=', 'ru1.user_id')
                ->where('referral_users.parent_id','=',Auth::id())
                ->get();
            $data = [];
            $data1 = [];
            foreach ($result as $res){
                if($res->level_one_user){
                    $data[$res->level_one_user]['id'] = $res->level_one_user;
                    $data[$res->level_one_user]['full_name'] = $res->level_one_user_name;
                    $data[$res->level_one_user]['email'] = $res->level_one_user_email;
                    $data[$res->level_one_user]['joining_date'] = $res->level_one_user_joining_date;
                    $data[$res->level_one_user]['level'] = __("Level 1");
                    $data1[]=$data[$res->level_one_user];
                }
                if($res->level_two_user){
                    $data[$res->level_two_user]['id'] = $res->level_two_user;
                    $data[$res->level_two_user]['full_name'] = $res->level_two_user_name;
                    $data[$res->level_two_user]['email'] = $res->level_two_user_email;
                    $data[$res->level_two_user]['joining_date'] = $res->level_two_user_joining_date;
                    $data[$res->level_two_user]['level'] = __("Level 2");
                    $data1[]=$data[$res->level_two_user];
                }
                if($res->level_three_user){
                    $data[$res->level_three_user]['id'] = $res->level_three_user;
                    $data[$res->level_three_user]['full_name'] = $res->level_three_user_name;
                    $data[$res->level_three_user]['email'] = $res->level_three_user_email;
                    $data[$res->level_three_user]['joining_date'] = $res->level_three_user_joining_date;
                    $data[$res->level_three_user]['level'] = __("Level 3");
                    $data1[]=$data[$res->level_three_user];
                }
            }
            $response = ['success' => true, 'data' => $data1, 'message' => __('Referral and Number of Referral Level Member Here')];
        }catch (\Exception $e) {
            $response = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($response);
    }

    public function myEarnings(Request $request){
        try{
            $limit=1000;
            if(isset($request->limit)){
                $limit = $request->limit;
            }
            $monthlyEarnings = AffiliationHistory::select(
                DB::raw('DATE_FORMAT(`created_at`,\'%Y-%m\') as "year_month"'),
                DB::raw('SUM(amount) AS total_amount'),
                DB::raw('COUNT(DISTINCT(child_id)) AS total_child'))
                ->where('user_id', Auth::id())
                ->where('status', 1)
                ->groupBy('year_month')
                ->get()->toArray();
            $monthlyEarningData = [];
            foreach ($monthlyEarnings as $index=>$monthlyEarning) {
                foreach ($monthlyEarning as $key=>$earning) {
                    if($key == 'year_month'){
                        $monthlyEarningData[$index][$key] = date('M Y', strtotime($earning));
                    }
                    elseif ($key == 'total_amount'){
                        $monthlyEarningData[$index][$key] = visual_number_format($earning);
                    }
                }
                if(count($monthlyEarningData)==$limit){
                    break;
                }
            }
            $response = ['success' => true, 'data' => $monthlyEarningData, 'message' => __('My Earnings')];
        }catch (\Exception $e) {
            $response = ['success' => false, 'data' => [], 'message' => __('Something went wrong.')];
        }
        return response()->json($response);
    }
}
