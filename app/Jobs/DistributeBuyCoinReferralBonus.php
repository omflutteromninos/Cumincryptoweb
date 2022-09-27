<?php

namespace App\Jobs;

use App\Repository\AffiliateRepository;
use App\Services\Logger;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DistributeBuyCoinReferralBonus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $transaction;
    private $logger;
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
        $this->logger = new Logger();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->logger->log('DistributeBuyCoinReferralBonus', 'called');
            $repo = new AffiliateRepository();
            $repo->storeAffiliationHistoryForBuyCoin($this->transaction);
            $this->logger->log('DistributeBuyCoinReferralBonus', 'executed');
        } catch (\Exception $e) {
            $this->logger->log('DistributeBuyCoinReferralBonus', $e->getMessage());
        }
    }
}
