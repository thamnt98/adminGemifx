<?php

namespace App\Http\Controllers\Admin\Account;

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
        $isAdmin = Auth::user()->role == config('role.admin');
        $withdrawals = $this->withdrawalRepository->getWithdrawalByLogin($account->login);
        return view('admin.account.detaillive', compact('account', 'withdrawals', 'isAdmin'));
    }
}
