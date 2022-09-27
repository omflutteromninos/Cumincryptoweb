<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MembershipBonusDistributionHistory extends Model
{
    protected $fillable = [
        'user_id', 'wallet_id', 'plan_id','membership_id','distribution_date','bonus_amount','status','plan_current_bonus','bonus_type', 'bonus_coin_type', 'bonus_amount_btc'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class,'wallet_id');
    }
    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class,'plan_id');
    }
}
