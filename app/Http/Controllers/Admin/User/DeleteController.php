<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Repositories\DepositRepository;
use App\Repositories\LiveAccountRepository;
use App\Repositories\UserRepository;
use App\Repositories\WithdrawalRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class DeleteController extends Controller
{
    const ACTIVE = 1;
    const INACTIVE = 0;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var LiveAccountRepository
     */
    private $liveAccountRepository;
    private $withdrawalRepository;
    private $depositRepository;

    /**
     * ListController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
        LiveAccountRepository $liveAccountRepository,
        WithdrawalRepository $withdrawalRepository,
        DepositRepository $depositRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->liveAccountRepository = $liveAccountRepository;
        $this->withdrawalRepository = $withdrawalRepository;
        $this->depositRepository = $depositRepository;
    }

    public function main($id)
    {
        try {
            DB::beginTransaction();
            $this->userRepository->delete($id);
            $this->withdrawalRepository->deleteWithdrawalByUserId($id);
            $this->depositRepository->deleteDepositByUserId($id);
            DB::commit();
            return redirect()->back()->with('success', 'Bạn đã xóa thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Xóa thất bại');
        }
    }

    public function active($id)
    {
        try {
            DB::beginTransaction();
            $this->userRepository->update(['check_active' => self::ACTIVE], $id);
            DB::commit();
            return redirect()->back()->with('success', 'Bạn đã xác thực hồ sơ thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Xác thực hồ sơ thất bại');
        }
    }

    public function inactive($id)
    {
        try {
            DB::beginTransaction();
            $this->userRepository->update(['check_active' => self::INACTIVE], $id);
            DB::commit();
            return redirect()->back()->with('success', 'Bạn đã hủy xác thực thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Hủy xác thực thất bại');
        }
    }
}
