<?php

namespace App\Http\Controllers\Admin\Deposit;

use App\Helper\MT5Helper;
use App\Http\Controllers\Controller;
use App\Repositories\DepositRepository;
use Exception;
use App\Helper\MT4Connect;
use Illuminate\Http\Request;

class ApproveController extends Controller
{

    /**
     * @var DepositRepository
     */
    private $depositRepository;
    private $mT5Helper;

    /**
     * ListController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(DepositRepository $depositRepository, MT5Helper $mT5Helper)
    {
        $this->depositRepository = $depositRepository;
        $this->mT5Helper = $mT5Helper;
    }

    public function main($id, Request $request)
    {
        try{
            $usd = $request->usd;
            $order = $this->depositRepository->findOrders($id);
            if($order === null){
                throw new Exception('Find order fail');
            }
            $login = $order->login;
            $data = [
                'Account' => $login,
                'Amount' => $usd,
                'Comment' => 'Deposit to NL'
            ];
            $result = $this->mT5Helper->makeDeposit($data);
            if (is_null($result->ERR_MSG)) {
                $this->depositRepository->update(['status' => config('deposit.status.yes'), 'usd' => $usd], $id);
                return redirect()->back()->with('success', 'Bạn đã approve thành công');
            }
            return redirect()->back()->with('error', 'Approve thất bại');
        }catch(Exception $e){
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Approve thất bại');
        }
    }
}
