<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * DetailController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function main($id)
    {
        $user = $this->userRepository->find($id);
        return view('admin.user.detail', compact('user'));
    }
}
