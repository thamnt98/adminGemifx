<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function main(Request $request)
    {
        $data = $request->except('_token');
        $userList = $this->userRepository->getUserListBySearch($data);
        return view('admin.user.list', compact('userList', 'data'));
    }
}
