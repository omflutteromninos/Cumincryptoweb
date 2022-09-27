<?php

namespace App\Jobs;

use App\Model\Coin;
use App\Model\Wallet;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class AdjustWalletJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('adjust wallet coin job -> '.' Called');
        try {
            Log::info('adjust wallet coin job -> '.' Start');
            Coin::firstOrCreate(['type' => DEFAULT_COIN_TYPE], ['type' => DEFAULT_COIN_TYPE, 'name' => settings('coin_name')]);
            Coin::firstOrCreate(['type' => "LTCT"], ['type' => 'LTCT', 'name' => 'Ltct coin']);
            $users = User::select('*')->get();
            if (isset($users[0])) {
                foreach ($users as $user) {
                    $coins = Coin::select('*')->get();
                    $count = $coins->count();
                    for($i=0; $count > $i; $i++) {
                        Wallet::updateOrCreate(['user_id' => $user->id, 'coin_type' => $coins[$i]->type],
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
                Log::info('adjust wallet coin job -> '.' Wallet updated');
            } else {
                Log::info('adjust wallet coin job -> '.' No wallet found');
            }
            Log::info('adjust wallet coin job -> '.' Executed');
        } catch (\Exception $e) {
            Log::info('adjust wallet coin exception -> '.$e->getMessage());
        }
    }
}
