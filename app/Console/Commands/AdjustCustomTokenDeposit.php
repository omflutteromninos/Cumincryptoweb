<?php

namespace App\Console\Commands;

use App\Jobs\GetDepositBalanceFormUserJob;
use App\Repository\CustomTokenRepository;
use App\Services\Logger;
use Illuminate\Console\Command;

class AdjustCustomTokenDeposit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adjust-token-deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adjust token deposit';

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
        $logger = new Logger();
        $logger->log('adjust token deposit command', 'Called');
        $repo = new CustomTokenRepository();
        $repo->getDepositTokenFromUser();
        $logger->log('adjust token deposit command', 'executed');
    }
}
