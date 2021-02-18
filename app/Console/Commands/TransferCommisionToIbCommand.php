<?php

namespace App\Console\Commands;

use App\Helper\MT4Connect;
use App\Repositories\LiveAccountRepository;
use Illuminate\Console\Command;

class TransferCommisionToIbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer commission to IB after order closed';
    /**
     * @var MT4Connect
     */
    private $MT4Connect;
    /**
     * @var LiveAccountRepository
     */
    private $liveAccountRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MT4Connect $MT4Connect, LiveAccountRepository $liveAccountRepository)
    {
        $this->MT4Connect = $MT4Connect;
        $this->liveAccountRepository = $liveAccountRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->MT4Connect->transferCommission();
    }
}
