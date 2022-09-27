<?php

namespace App\Jobs;

use App\Model\DepositeTransaction;
use App\Repository\CustomTokenRepository;
use App\Services\Logger;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Throwable;

class PendingDepositAcceptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 0;
    private $transactions;
    private $adminId;
    public function __construct($transactions,$adminId)
    {
        $this->transactions =  $transactions;
        $this->adminId =  $adminId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logger = new Logger();
        $tokenRepo = new CustomTokenRepository();
        try {
            $logger->log('PendingDepositAcceptJob', 'called');
            if (isset($this->transactions[0])) {
                $logger->log('PendingDepositAcceptJob ', 'pending deposit list found');
                $tokenRepo->tokenReceiveManuallyByAdmin($this->transactions,$this->adminId);
            } else {
                $logger->log('PendingDepositAcceptJob', 'deposit list not found');
            }
        } catch (\Exception $e) {
            $logger->log('PendingDepositAcceptJob', $e->getMessage());
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
