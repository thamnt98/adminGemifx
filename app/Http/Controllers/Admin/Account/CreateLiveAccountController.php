<?php

namespace App\Http\Controllers\Admin\Account;

use App\Helper\MT5Helper;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateLiveAccountController extends Controller
{

    private $userRepository;
    private $mT5Helper;

    /**
     * LiveListController constructor.
     */
    public function __construct(UserRepository $userRepository, MT5Helper $mT5Helper)
    {
        $this->userRepository = $userRepository;
        $this->mT5Helper = $mT5Helper;
    }

    public function main($id)
    {
        $users = $this->userRepository->getUserBySelect(['email', 'phone_number', 'id']);
        $groups = $this->mT5Helper->getGroups();
        return view('admin.account.createlive', compact('users', 'id', 'groups'));
    }
}
