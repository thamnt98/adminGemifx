<?php

namespace App\Http\Controllers\Admin\Deposit;

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
    private $mt4;

    /**
     * ListController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(DepositRepository $depositRepository, MT4Connect $mt4)
    {
        $this->depositRepository = $depositRepository;
        $this->mt4 = $mt4;
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
            $changeBalance = $this->mt4->changeBalance($login, $usd);
            if(!$changeBalance){
                throw new Exception('change balance fail');
            }
            $code = self::getResult($changeBalance);
            if ($code == '1') {
                $result = $this->depositRepository->update(['status' => config('deposit.status.yes'), 'usd' => $usd], $id);
                return redirect()->back()->with('success', 'Bạn đã approve thành công');
            }
            return redirect()->back()->with('error', 'Approve thất bại');
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Approve thất bại');
        }
    }

    public static function getResult($result)
    {
        $result = explode('&', $result);
        $resultCode = explode('=', $result[0])[1];
        return $resultCode;
    }
}
