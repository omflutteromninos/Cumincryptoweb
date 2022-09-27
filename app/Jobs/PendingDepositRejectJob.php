<?php

namespace App\Jobs;

use App\Services\Logger;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PendingDepositRejectJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 0;
    private $transaction;
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logger = new Logger();
        try {
            $logger->log('PendingDepositRejectJob', 'called');
            $deposit = $this->transaction;
            if ($deposit->status == STATUS_PENDING) {
                $deposit->update(['status' => STATUS_REJECTED]);
                $logger->log('PendingDepositRejectJob', 'deposit rejected successfully . deposit id = '.$deposit->id);
            } else {
                $logger->log('PendingDepositRejectJob', 'deposit status not pending . deposit id = '.$deposit->id);
            }
        } catch (\Exception $e) {
            $logger->log('PendingDepositRejectJob', $e->getMessage());
        }
    }
}
