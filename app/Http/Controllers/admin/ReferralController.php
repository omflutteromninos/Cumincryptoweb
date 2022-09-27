<?php

namespace App\Http\Controllers\admin;

use App\Model\AffiliationHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReferralController extends Controller
{
    // admin referral bonus history
    public function adminReferralBonusHistory(Request $request)
    {
        $data['title'] = __('Referral Bonus Distribution (External Withdrawal)');
        if ($request->ajax()) {
            $data['items'] = AffiliationHistory::select('*');
            return datatables()->of($data['items'])
//                ->addColumn('plan_id', function ($item) {
//                    return !empty($item->plan->plan_name) ? $item->plan->plan_name : 'N/A';
//                })
//                ->addColumn('wallet_id', function ($item) {
//                    return !empty($item->wallet->name) ? $item->wallet->name : 'N/A';
//                })
                ->addColumn('status', function ($item) {
                    return status($item->status);
                })
                ->make(true);
        }

        return view('admin.referral.bonus_distribution_list_withdrawal', $data);
    }
}
