<?php

namespace App\Jobs;

use App\Http\Services\TransactionService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Throwable;

class Withdrawal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 0;
    private $request ;
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $request = (array)$this->request;
            // Transaction
            Log::info('before call');
            $trans = new TransactionService();
            $response = $trans->send($request['wallet_id'],$request['address'],$request['amount'],'',''
                ,$request['user_id'], $request['message'], isset($request['created_at']) ? $request['id'] : null);
            log::info('called');
            log::info(json_encode($response));

        }
        catch(\Exception $e) {
            log::info($e->getMessage());
            return false;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        Log::info(json_encode($exception));
        // Send user notification of failure, etc...
    }
}
