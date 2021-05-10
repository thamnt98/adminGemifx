<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Repositories\LiveAccountRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
// use Dotenv\Validator;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Helper\MT4Connect;
use App\Models\WithdrawalFund;
use App\Repositories\DepositRepository;
use Illuminate\Support\Facades\DB;
use App\Repositories\WithdrawalRepository;

class LiveListController extends Controller
{
    const SUB_MONEY = '-';
    const SUCCESS = 1;
    const PROCESS = 2;
    /**
     * @var LiveAccountRepository
     */
    private $liveAccountRepository;
    private $userRepository;
    private $depositRepository;
    private $mt4;
    private $withdrawalRepository;

    /**
     * LiveListController constructor.
     */
    public function __construct(LiveAccountRepository $liveAccountRepository, UserRepository $userRepository, DepositRepository $depositRepository, MT4Connect $mt4, WithdrawalRepository $withdrawalRepository)
    {
        $this->liveAccountRepository = $liveAccountRepository;
        $this->userRepository = $userRepository;
        $this->depositRepository = $depositRepository;
        $this->withdrawalRepository = $withdrawalRepository;
        $this->mt4 = $mt4;
    }

    public function main(Request  $request)
    {
        $data = $request->except('_token');
        $accountList = $this->liveAccountRepository->getAccountListBySearch($data);
        $isAdmin = Auth::user()->role == config('role.admin');
        return view('admin.account.livelist', compact('accountList', 'data', 'isAdmin'));
    }

    /**
     * create deposite
     */
    public function createDeposit(Request $request)
    {
        $users = $this->userRepository->getUserBySelect(['email', 'phone_number', 'id']);
        return view('admin.account.create-deposit', compact('users'));
    }

    /**
     * validate deposite
     */
    public function validateDeposit($data)
    {
        return Validator::make(
            $data,
            [
                'customer' => ['required'],
                'login' => ['required'],
                'amount_money' => ['required', 'numeric', 'min:100000']
            ]
        );
    }
    /**
     * call ajax
     */
    public function listLogin(Request $request)
    {
        $customer = $request->customer;
        $listLogin = $this->liveAccountRepository->getListLogin($customer);
        $html = view('admin.account.login_ajax_view', compact('listLogin'))->render();
        return response()->json($html, 200);
    }
    /**
     * create deposite post
     */
    public function createDepositPost(Request $request)
    {
        try {
            $data = $request->all();
            $validateData = $this->validateDeposit($data);
            $customer = $data['customer'];
            if ($customer != null) {
                $listLogin = $this->liveAccountRepository->getListLogin($customer);
            } else {
                $listLogin = null;
            }
            if ($validateData->fails()) {
                return redirect()->back()->withErrors($validateData->errors())->withInput()->with(['listLogin' => $listLogin]);
            }
            $data_save = [
                'user_id' => $data['customer'],
                'login' => $data['login'],
                'amount_money' => $data['amount_money'],
                'type' => $data['type'],
                'status' => self::SUCCESS
            ];
            $balance = $this->depositRepository->changeMoneyUsd($data_save['amount_money']);
            $changeBalance = $this->mt4->changeBalance($data_save['login'], $balance);
            $code = self::getResult($changeBalance);
            if ($code == '1') {
                DB::beginTransaction();
                $order = $this->depositRepository->create($data_save);
                DB::commit();
                return redirect()->back()->with('success', 'Bạn đã tạo thành công');
            }
            return redirect()->back()->with('error', 'Approve thất bại');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Approve thất bại');
        }
    }

    public function createWithdrawal(Request $request)
    {
        $users = $this->userRepository->getUserBySelect(['email', 'phone_number', 'id']);
        return view('admin.account.create-withdrawal', compact('users'));
    }

    public function createWithdrawalPost(Request $request)
    {
        try {
            $data = $request->all();
            $validateData = $this->validateWithdrawal($data);
            $customer = $data['customer'];
            if ($customer != null) {
                $listLogin = $this->liveAccountRepository->getListLogin($customer);
            } else {
                $listLogin = null;
            }
            if ($validateData->fails()) {
                return redirect()->back()->withErrors($validateData->errors())->withInput()->with(['listLogin' => $listLogin]);
            }
            $data_save = [
                'user_id' => $data['customer'],
                'login' => $data['login'],
                'amount' => $data['amount'],
                'withdrawal_type' => $data['withdrawal_type'],
                'status' => self::SUCCESS
            ];
            $changeBalance = $this->mt4->changeBalance($data_save['login'], self::SUB_MONEY.$data_save['amount']);
            if (!$changeBalance) {
                throw new Exception('change balance fail');
            }
            $code = self::getResult($changeBalance);
            if ($code == '1') {
                DB::beginTransaction();
                $withdrawal = $this->withdrawalRepository->create($data_save);
                DB::commit();
                return redirect()->back()->with('success', 'Bạn đã tạo thành công');
            }
            return redirect()->back()->with('error', 'Approve thất bại');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Approve thất bại');
        }
    }

    /**
     * validate withdrawal
     */
    public function validateWithdrawal($data)
    {
        return Validator::make(
            $data,
            [
                'customer' => ['required'],
                'login' => ['required'],
                'amount' => ['required', 'numeric', 'min:10']
            ]
        );
    }

    public static function getResult($result)
    {
        $result = explode('&', $result);
        $resultCode = explode('=', $result[0])[1];
        return $resultCode;
    }
}
