<?php

namespace App\Console\Commands;

use App\Jobs\CustomTokenDepositJob;
use App\Repository\CustomTokenRepository;
use App\Services\Logger;
use Illuminate\Console\Command;

class CustomTokenDeposit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom-token-deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor the blockchain deposit and if find new deposit then send this token to admin address';

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
        $logger->log('custom token deposit command', 'Called');
        $repo = new CustomTokenRepository();
        $repo->depositCustomToken();
        $logger->log('custom token deposit command', 'Executed');
    }
}
