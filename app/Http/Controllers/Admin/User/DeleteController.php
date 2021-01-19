<?php

namespace App\Http\Controllers\Admin\User;

use App\Helper\MT4Connect;
use App\Http\Controllers\Controller;
use App\Repositories\LiveAccountRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class DeleteController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var LiveAccountRepository
     */
    private $liveAccountRepository;

    /**
     * ListController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, LiveAccountRepository $liveAccountRepository)
    {
        $this->userRepository = $userRepository;
        $this->liveAccountRepository = $liveAccountRepository;
    }

    public function main($id)
    {
        try {
            DB::beginTransaction();
            $this->userRepository->delete($id);
            $message = $this->liveAccountRepository->deleteLiveAccountByUserId($id);
            if (!isEmpty($message)) {
                return redirect()->back()->with('error', $message);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Bạn đã xóa thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Xóa thất bại');
        }
    }
}
