<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ListController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function main(){
        $userList = $this->userRepository->get(['first_name', 'last_name', 'email', 'phone_number', 'copy_of_id', 'country', 'address']);
        return view('admin.user.list', compact('userList'));
    }
}
