<?php

namespace App\Console\Commands;

use App\Jobs\AdjustWalletJob;
use App\Model\Coin;
use App\Model\Wallet;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AdjustWalletCoin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust-wallet-coin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add missing coin id at wallet table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('adjust wallet coin command -> '.' Called');
        try {
            Log::info('adjust wallet coin command -> '.' Command goes to queue');
            dispatch(new AdjustWalletJob())->onQueue('default');

        } catch (\Exception $e) {
            Log::info('adjust wallet coin command exception -> '.$e->getMessage());
        }

    }
}
