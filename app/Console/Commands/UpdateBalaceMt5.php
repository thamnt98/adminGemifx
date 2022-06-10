<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\MT5Helper;
use App\Repositories\LiveAccountRepository;
use Psr\Log\LoggerInterface;
use Illuminate\Support\Facades\DB;

class UpdateBalaceMt5 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updateBalance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update balance';

    protected $liveAccountRepository;

    protected $logger;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LiveAccountRepository $liveAccountRepository, LoggerInterface $logger)
    {
        $this->liveAccountRepository = $liveAccountRepository;
        $this->logger = $logger;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $listArrayInfo = [];
        $listArrayLogin = [];
        $countUpdateSuccess = 0;
        try {
            //Get all login from mt5
            $getAllLogins = MT5Helper::getAllLogins();
            if (isset($getAllLogins['lstUser']) && !empty($getAllLogins)) {
                foreach ($getAllLogins['lstUser'] as $key => $info) {
                    if (isset($info['oInfo'])) {
                        $listArrayInfo[$info['oInfo']['Account']] = [
                            'balance' => $info['oInfo']['Balance'],
                            'equity' => $info['oAccount']['Equity']
                        ];
                    }
                }
            }
            //Get all login
            $listLogin = $this->liveAccountRepository->get();
            foreach($listLogin as $login){
                $listArrayLogin[$login->id] = $login->login;
            }
            DB::beginTransaction();
            //Update balance for login
            foreach($listArrayLogin as $idLogin =>$loginUser) {
                if(isset($listArrayInfo[$loginUser])) {
                    $liveAccount = $this->liveAccountRepository->update(['balance' => $listArrayInfo[$loginUser]['balance'], 'equity' => $listArrayInfo[$loginUser]['equity']], $idLogin);
                    $countUpdateSuccess++;
                }
            }
            DB::commit();
            echo "Update success with loggin accout with: ". $countUpdateSuccess;
            $this->logger->info('Update success with login account: '.$countUpdateSuccess);
        } catch (\Exception $e) {
            $this->logger->info('Error: ' . $e->getMessage());
        }
    }
}
