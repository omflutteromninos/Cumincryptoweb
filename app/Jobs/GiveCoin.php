<?php

namespace App\Jobs;

use App\Repository\CoinRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class GiveCoin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $data;
    public function __construct($data)
    {
        $this->data =  $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $data = $this->data;
            // give coin
            Log::info('before call give coin');
            $coin_repo = new CoinRepository();
            $response = $coin_repo->sendCoinToUser($data);
            Log::info('give coin called');

        }
        catch(\Exception $e) {
            Log::info($e->getMessage());
            return false;
        }
    }
}
