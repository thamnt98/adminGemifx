<?php

namespace App\Http\Controllers\Admin\Withdrawal;

use App\Http\Controllers\Controller;
use App\Repositories\WithdrawalRepository;
use App\Helper\MT4Connect;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class ApproveController extends Controller
{
    const SUBTRACT = '-';

    /**
     * @var WithdrawalRepository
     */
    private $withdrawalRepository;
    private $mt4;

    /**
     * ListController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(WithdrawalRepository $withdrawalRepository, MT4Connect $mt4)
    {
        $this->withdrawalRepository = $withdrawalRepository;
        $this->mt4 = $mt4;
    }

    public function main($id, Request $request)
    {
        try{
            $amount = $request->amount;
            $withdrawal = $this->withdrawalRepository->findWithDrawalFun($id);
            if(!$withdrawal){
                return new Exception('find withdrawal fail');
            }
            $login = $withdrawal->login;
            $changeBalance = $this->mt4->changeBalance($login, self::SUBTRACT.$amount, 'Withdrawal to Bank');
            $code = self::getResult($changeBalance);
            if($code == '1'){
                DB::beginTransaction();
                $this->withdrawalRepository->update(['status' => config('deposit.status.yes'), 'amount' => $amount], $id);
                DB::commit();
                return redirect()->back()->with('success', 'You are approve success');
            }
            return redirect()->back()->with('error', 'Approve fail');
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->with('error', 'Approve fail');
        }
    }

    public static function getResult($result)
    {
        $result = explode('&', $result);
        $resultCode = explode('=', $result[0])[1];
        return $resultCode;
    }
}
