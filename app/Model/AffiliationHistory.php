<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AffiliationHistory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'amount',
        'system_fees',
        'transaction_id',
        'child_id',
        'level',
        'status',
        'order_type',
        'coin_type',
        'wallet_id'
    ];

    protected $dates = ['deleted_at'];

    public function child()
    {
        return $this->belongsTo(User::class, 'child_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
