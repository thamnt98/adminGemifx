<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class CreateLiveAccountController extends Controller
{

    private $userRepository;

    /**
     * LiveListController constructor.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function main()
    {
        $users = $this->userRepository->getUserBySelect(['email', 'phone_number']);
        return view('admin.account.createlive', compact('users'));
    }
}
