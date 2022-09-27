<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BuyCoinHistory extends Model
{
    protected $fillable = ['confirmations','status','coin_type','phase_id','referral_level','fees','bonus','requested_amount','referral_bonus','stripe_token'];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
