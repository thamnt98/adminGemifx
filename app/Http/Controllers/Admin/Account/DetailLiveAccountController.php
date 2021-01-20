<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Repositories\LiveAccountRepository;

class DetailLiveAccountController extends Controller
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

    public function main($id)
    {
        $account = $this->liveAccountRepository->find($id);
        return view('admin.account.detaillive', compact('account'));
    }
}
