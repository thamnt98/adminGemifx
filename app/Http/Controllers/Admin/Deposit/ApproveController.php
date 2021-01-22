<?php

namespace App\Http\Controllers\Admin\Deposit;

use App\Http\Controllers\Controller;
use App\Repositories\DepositRepository;

class ApproveController extends Controller
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

    public function main($id)
    {
        $result = $this->depositRepository->update(['status' => config('deposit.status.yes')], $id);
        if ($result) {
            return redirect()->back()->with('success', 'Bạn đã approve thành công');
        }
        return redirect()->back()->with('error', 'Approve thất bại');
    }
}
