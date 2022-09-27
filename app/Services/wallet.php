<?php
/**
 * Created by PhpStorm.
 * User: jony
 * Date: 8/5/19
 * Time: 1:58 PM
 */

namespace App\Services;



use App\Model\WalletAddressHistory;

class wallet
{
  function AddWalletAddressHistory($wallet_id,$address,$coin_type){
       $wallet = new WalletAddressHistory();
       $wallet->wallet_id = $wallet_id;
       $wallet->address = $address;
       $wallet->coin_type = $coin_type;
       $wallet->save();
       return ['success'=>true];
}
}
