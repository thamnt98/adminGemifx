<?php

namespace App\Console\Commands;

use App\Helper\MT4Connect;
use App\Helper\MT5Helper;
use App\Repositories\LiveAccountRepository;
use Illuminate\Console\Command;
use App\Models\Admin;
use App\Models\User;
use App\Models\LiveAccount;
use Illuminate\Support\Facades\Log;

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

    protected $liveAccountRepository;

    /**
     * GetTradeListController constructor.
     * @param MT5Helper $mT5Helper
     * @param LiveAccountRepository $liveAccountRepository
     */
    public function __construct(LiveAccountRepository $liveAccountRepository)
    {
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
        $admins = Admin::where('role', config('role.staff'))->get();
        $data['EndTm'] = date('Y-m-d H:i:s', strtotime('now'));
        $data['startTm']  = date('Y-m-d H:i:s', strtotime('2021-07-01 00:00:00'));
        foreach ($admins as $key => $admin) {
            $logins = $this->liveAccountRepository->getLoginsByAdmin($admin);
            $result = MT5Helper::getOpenedTrades($logins, $data);
            $commission = $result[2];
            if ($commission) {
                $userId = User::where('email', $admin->email)->pluck('id');
                $account = LiveAccount::where('user_id', $userId[0])->pluck('login');
                if (count($account)) {
                    $data = [
                        'Account' => $account[0],
                        'Amount' => $commission,
                        'Comment' => 'transfer commission'
                    ];
                    MT5Helper::makeDeposit($data, false);
                    Log::channel('transfer_commission')->info('Ib id: ' . $admin->ib_id . '------------' . 'Login: ' . $account[0] . '----------------' . 'Amount: ' .  $commission);
                }
            }
        }
    }
}
