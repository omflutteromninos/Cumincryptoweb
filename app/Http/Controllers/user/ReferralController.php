<?php

namespace App\Http\Controllers\user;

use App\Model\AffiliationCode;
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
        config()->set('database.connections.mysql.strict', false);
        DB::reconnect();
    }

    /*
     * myReferral
     *
     */

    public function myReferral()
    {
        $data['title'] = __('My Referral');
        $data['user'] = Auth::user();
        $data['referrals_3'] = DB::table('referral_users as ru1')->where('ru1.parent_id', Auth::user()->id)
            ->Join('referral_users as ru2', 'ru2.parent_id', '=', 'ru1.user_id')
            ->Join('referral_users as ru3', 'ru3.parent_id', '=', 'ru2.user_id')
            ->join('users', 'users.id', '=', 'ru3.user_id')
            ->select('ru3.user_id as level_3', 'users.email','users.first_name as full_name','users.created_at as joining_date')
            ->get();
        $data['referrals_2'] = DB::table('referral_users as ru1')->where('ru1.parent_id', Auth::user()->id)
            ->Join('referral_users as ru2', 'ru2.parent_id', '=', 'ru1.user_id')
            ->join('users', 'users.id', '=', 'ru2.user_id')
            ->select('ru2.user_id as level_2','users.email','users.first_name as full_name','users.created_at as joining_date')
            ->get();
        $data['referrals_1'] = DB::table('referral_users as ru1')->where('ru1.parent_id', Auth::user()->id)
            ->join('users', 'users.id', '=', 'ru1.user_id')
            ->select('ru1.user_id as level_1','users.email','users.first_name as full_name','users.created_at as joining_date')
            ->get();
        $referralUsers = [];

        foreach ($data['referrals_1'] as $level1) {
            $referralUser['id'] = $level1->level_1;
            $referralUser['full_name'] = $level1->full_name;
            $referralUser['email'] = $level1->email;
            $referralUser['joining_date'] = $level1->joining_date;
            $referralUser['level'] = __("Level 1");
            $referralUsers [] = $referralUser;
        }

        foreach ($data['referrals_2'] as $level2) {
            $referralUser['id'] = $level2->level_2;
            $referralUser['full_name'] = $level2->full_name;
            $referralUser['email'] = $level2->email;
            $referralUser['joining_date'] = $level2->joining_date;
            $referralUser['level'] = __("Level 2");
            $referralUsers [] = $referralUser;
        }

        foreach ($data['referrals_3'] as $level3) {
            $referralUser['id'] = $level3->level_3;
            $referralUser['full_name'] = $level3->full_name;
            $referralUser['email'] = $level3->email;
            $referralUser['joining_date'] = $level3->joining_date;
            $referralUser['level'] = __("Level 3");
            $referralUsers [] = $referralUser;
        }
        $data['referrals'] = $referralUsers;

        if (!$data['user']->Affiliate) {
            $created = $this->affiliateRepository->create($data['user']->id);
            if ($created < 1) {
                return redirect()->back()->with(['dismiss' => __('Failed to generate new referral code.')]);
            }
        }

        $data['url'] = url('') . '/referral-reg?ref_code=' . $data['user']->affiliate->code;

        $maxReferralLevel = max_level();
        $referralQuery = $this->affiliateRepository->childrenReferralQuery($maxReferralLevel);

        $referralAll = $referralQuery['referral_all']->where('ru1.parent_id', $data['user']->id)
            ->select('ru1.parent_id', DB::raw($referralQuery['select_query']))
            ->first();

        for ($i = 0; $i < $maxReferralLevel; $i++) {
            $level = 'level' . ($i + 1);
            $data['referralLevel'] [($i + 1)] = $referralAll->{$level};
        }

        $data['select'] = 'affiliate';
        $data['max_referral_level'] = $maxReferralLevel;

        //calculate per users monthly earning from their 3 level Children
        //'level',

        $monthlyEarnings = AffiliationHistory::select(
            DB::raw('DATE_FORMAT(`created_at`,\'%Y-%m\') as "year_month"'),
            DB::raw('SUM(amount) AS total_amount'),
            DB::raw('COUNT(DISTINCT(child_id)) AS total_child'))
            ->where('user_id', $data['user']->id)
            ->where('status', 1)
            ->groupBy('year_month')
//            ->groupBy('year_month', 'level')
            ->get();
        $monthlyEarningData = [];
        foreach ($monthlyEarnings as $monthlyEarning) {
            $monthlyEarningData[$monthlyEarning->year_month]['year_month'] = $monthlyEarning->year_month;
            $monthlyEarningData[$monthlyEarning->year_month]['total_amount'] = $monthlyEarning->total_amount;

        }
        $affiliationKeys = array_flip(array_keys($monthlyEarningData));
        $data['monthArray'] = $affiliationKeys;
        $data['monthlyEarningHistories'] = $monthlyEarningData;

        return view('user.referral.index', $data);
    }

    public function __destruct()
    {
        config()->set('database.connections.mysql.strict', true);
        DB::reconnect();
    }

    /*
     * signup
     *
     * It's for referral signup.
     *
     *
     *
     *
     */

    public function signup(Request $request)
    {
        $code = $request->get('ref_code');

        if ($code) {
            $parentUser = AffiliationCode::where('code', $code)->first();
            if ($parentUser) {
                return view('auth.signup');
            } else {
                return redirect()->route('signUp')->with('dismiss', __('Invalid referral code.'));
            }
        }

        return redirect()->route('signUp')->with('dismiss', __('Invalid referral code.'));
    }

    // my referral earning
    public function myReferralEarning(Request $request)
    {
        $data['title'] = __('My referral Earning History');
        if ($request->ajax()) {
            $items = AffiliationHistory::where(['user_id' => Auth::id(), 'status' => STATUS_ACTIVE])->select('*');

            return datatables()->of($items)
                ->addColumn('created_at', function ($item) {
                    return $item->created_at;
                })->addColumn('child_id', function ($item) {
                    return $item->child->first_name.' '.$item->child->last_name;
                })->addColumn('coin_type', function ($item) {
                    return find_coin_type($item->coin_type);
                })->addColumn('status', function ($item) {
                    return deposit_status($item->status);
                })
                ->make(true);
        }

        return view('user.referral.earning_history', $data);
    }
}
