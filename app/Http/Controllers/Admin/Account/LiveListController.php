<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Repositories\LiveAccountRepository;

class LiveListController extends Controller
{
    /**
     * @var LiveAccountRepository
     */
    private $liveAccountRepository;

    /**
     * LiveListController constructor.
     */
    public function __construct(LiveAccountRepository $liveAccountRepository)
    {
        $this->liveAccountRepository = $liveAccountRepository;
    }

    public function main()
    {
        $accountList = $this->liveAccountRepository->orderBy('user_id', 'asc')->paginate(20);
        return view('admin.account.livelist', compact('accountList'));
    }
}
