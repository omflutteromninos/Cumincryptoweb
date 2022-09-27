<?php

use App\Model\Coin;
use App\Model\Wallet;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coin::firstOrCreate(['name'=>'Bitcoin'], ['type'=>'BTC']);
        Coin::firstOrCreate(['name'=>'Tether USD'], ['type'=>'USDT']);
        Coin::firstOrCreate(['name'=>'Ether'], ['type'=>'ETH']);
        Coin::firstOrCreate(['name'=>'Litecoin'], ['type'=>'LTC']);
        Coin::firstOrCreate(['name'=>'Ether'], ['type'=>'DOGE']);
        Coin::firstOrCreate(['name'=>'Bitcoin Cash'], ['type'=>'BCH']);
        Coin::firstOrCreate(['name'=>'Dash'], ['type'=>'DASH']);

        try {
            Coin::firstOrCreate(['type' => DEFAULT_COIN_TYPE], ['type' => DEFAULT_COIN_TYPE, 'name' => settings('coin_name')]);
            Coin::firstOrCreate(['type' => "LTCT"], ['type' => 'LTCT', 'name' => 'Ltct coin']);
            $users = User::select('*')->get();
            if (isset($users[0])) {
                foreach ($users as $user) {
                    $coins = Coin::select('*')->get();
                    $count = $coins->count();
                    for($i=0; $count > $i; $i++) {
                        Wallet::firstOrCreate(['user_id' => $user->id, 'coin_type' => $coins[$i]->type],
                            ['name' =>  $coins[$i]->type.' Wallet','user_id' => $user->id, 'coin_type' => $coins[$i]->type, 'coin_id' => $coins[$i]->id]);
                    }
                }
            }
            $wallets = Wallet::where('coin_id', null)->get();
            if (isset($wallets[0])) {
                foreach ($wallets as $wallet) {
                    $coin = Coin::where(['type' => $wallet->coin_type])->first();
                    $wallet->update(['coin_id' => $coin->id]);
                }
            } else {

            }
        } catch (\Exception $e) {
        }
    }
}
