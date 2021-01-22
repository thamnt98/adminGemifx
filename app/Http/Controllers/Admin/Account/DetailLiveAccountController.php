<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Repositories\LiveAccountRepository;
use App\Repositories\WithdrawalRepository;

class DetailLiveAccountController extends Controller
{

     /**
     * @var LiveAccountRepository
     */
    private $liveAccountRepository;
    private $withdrawalRepository;

    /**
     * LiveListController constructor.
     */
    public function __construct(LiveAccountRepository $liveAccountRepository, WithdrawalRepository $withdrawalRepository)
    {
        $this->liveAccountRepository = $liveAccountRepository;
        $this->withdrawalRepository = $withdrawalRepository;
    }

    public function main($id)
    {
        $account = $this->liveAccountRepository->find($id);
        $withdrawals = $this->withdrawalRepository->getWithdrawalByLogin($account->login);
        return view('admin.account.detaillive', compact('account', 'withdrawals'));
    }
}
