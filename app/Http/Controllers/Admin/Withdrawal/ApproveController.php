<?php

namespace App\Http\Controllers\Admin\Withdrawal;

use App\Http\Controllers\Controller;
use App\Repositories\WithdrawalRepository;

class ApproveController extends Controller
{

    /**
     * @var WithdrawalRepository
     */
    private $depositRepository;

    /**
     * ListController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(WithdrawalRepository $withdrawalRepository)
    {
        $this->withdrawalRepository = $withdrawalRepository;
    }

    public function main($id)
    {
        $result = $this->withdrawalRepository->update(['status' => config('deposit.status.yes')], $id);
        if ($result) {
            return redirect()->back()->with('success', 'Bạn đã approve thành công');
        }
        return redirect()->back()->with('error', 'Approve thất bại');
    }
}
