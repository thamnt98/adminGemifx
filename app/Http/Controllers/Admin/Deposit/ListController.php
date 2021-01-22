<?php

namespace App\Http\Controllers\Admin\Deposit;

use App\Http\Controllers\Controller;
use App\Repositories\DepositRepository;

class ListController extends Controller
{
    /**
     * @var DepositRepository
     */
    private $depositRepository;

    /**
     * ListController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(DepositRepository $depositRepository)
    {
        $this->depositRepository = $depositRepository;
    }


    public function main()
    {
        $orders = $this->depositRepository->paginate(20);
        return view('admin.deposit.list', compact('orders'));
    }
}
