<?php

namespace App\Http\Controllers\Admin\Account;

use App\Helper\MT5Helper;
use App\Http\Controllers\Controller;
use App\Repositories\LiveAccountRepository;
use App\Repositories\WithdrawalRepository;
use Illuminate\Support\Facades\Auth;

class DetailLiveAccountController extends Controller
{

     /**
     * @var LiveAccountRepository
     */
    private $liveAccountRepository;
    private $withdrawalRepository;
    private $mT5Helper;

    /**
     * LiveListController constructor.
     */
    public function __construct(LiveAccountRepository $liveAccountRepository, WithdrawalRepository $withdrawalRepository, MT5Helper $mT5Helper)
    {
        $this->liveAccountRepository = $liveAccountRepository;
        $this->withdrawalRepository = $withdrawalRepository;
        $this->mT5Helper = $mT5Helper;
    }

    public function main($id)
    {
        $account = $this->liveAccountRepository->find($id);
        $isAdmin = Auth::user()->role == config('role.admin');
        $withdrawals = $this->withdrawalRepository->getWithdrawalByLogin($account->login);
        $groups = $this->mT5Helper->getGroups();
        return view('admin.account.detaillive', compact('account', 'withdrawals', 'isAdmin', 'groups'));
    }
}
