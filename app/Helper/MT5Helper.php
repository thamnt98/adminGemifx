<?php

namespace App\Helper;

use App\Models\Admin;
use GuzzleHttp\Client;
use App\Models\LiveAccount;
use App\Models\User;
use App\Repositories\LiveAccountRepository;

class MT5Helper
{
    protected static $mt5Url = 'http://79.143.176.19:17014/ManagerAPIFOREX/';

    protected static $session = '';

    /**
     * @var LiveAccountRepository
     */
    private $liveAccountRepository;
    /**
     * MT4Connect constructor.
     */
    
    private function __construct(LiveAccountRepository  $liveAccountRepository)
    {
        $this->liveAccountRepository = $liveAccountRepository;
    }

    public static function openAccount($data)
    {
        self::connectMT5();
        $endpoint = self::$mt5Url . 'ADD_MT_USER';
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'debug' => true
            ]
        ]);
        $data['Session'] = self::$session;
        $data['ManagerIndex'] = 101;
        $body = json_encode($data);
        $response = $client->request('POST', $endpoint, ['body' => $body]);
        $result = json_decode($response->getBody(), true);
        return $result;
    }

    public static function updateAccount($type, $login, $data)
    {
        self::connectMT5();
        $endpoint = self::$mt5Url . $type . '?Session=' . self::$session . '&ManagerIndex=101&Account=' . $login;
        foreach ($data as $key => $value) {
            $endpoint = $endpoint . '&' . $key . '=' . $value;
        }
        $client = new Client();
        $response = $client->request('GET', $endpoint);
        $result = json_decode($response->getBody());
        return $result;
    }

    public function getGroups()
    {
        $this->connectMT5();
        $endpoint = self::$mt5Url . 'GET_GROUPS?Session=' . self::$session . '&ManagerIndex=101';
        $client = new Client();
        $response = $client->request('GET', $endpoint);
        $result = json_decode($response->getBody());
        return $result->lstGroups;
    }

    private static function connectMT5()
    {
        $endpoint = self::$mt5Url . 'LOGIN_SESSION?Email=startingmt5broker@gmail.com&Password=rasa8r&Source=1';
        $client = new Client();
        $response = $client->request('GET', $endpoint);
        $result = json_decode($response->getBody());
        self::$session = $result->Session;
    }

    public function makeDeposit($data)
    {
        self::connectMT5();
        $endpoint = self::$mt5Url . 'MAKE_DEPOIST_BALANCE?Session=' . self::$session . '&ManagerIndex=101';
        foreach ($data as $key => $value) {
            $endpoint = $endpoint . '&' . $key . '=' . $value;
        }
        $client = new Client();
        $response = $client->request('GET', $endpoint);
        $result = json_decode($response->getBody());
        return $result;
    }

    public function makeWithdrawal($data)
    {
        self::connectMT5();
        $endpoint = self::$mt5Url . 'MAKE_WITHDRAW_BALANCE?Session=' . self::$session . '&ManagerIndex=101';
        foreach ($data as $key => $value) {
            $endpoint = $endpoint . '&' . $key . '=' . $value;
        }
        $client = new Client();
        $response = $client->request('GET', $endpoint);
        $result = json_decode($response->getBody());
        return $result;
    }

    public function getOpenedTrades($logins, $data)
    {
        $lots = 0;
        $commission = 0;
        $trades = [];
        foreach ($logins as $login => $commissionValue) {
            $data['Account'] = $login;
            $tradeByLogin = self::getClosedAll($data);
            $trades = array_merge($trades, $tradeByLogin);
            foreach ($tradeByLogin as $key => $trade) {
                if (strtotime($trade->Close_Time) - strtotime($trade->Open_Time) > 180) {
                    $lots += $lots + $trade->Lot;
                    $symbol = $trade->Symbol;
                    if (in_array($symbol, config('trader_type.USStocks'))) {
                        $commission += round($trade->Lot * $commissionValue[0], 2);
                    } elseif (in_array($symbol, config('trader_type.Forex'))) {
                        $commission += round($trade->Lot * $commissionValue[1], 2);
                    } else {
                        $commission += round($trade->Lot * $commissionValue[2], 2);
                    }
                }
            }
        }
        return [$trades, $lots, $commission];
    }

    public static function getClosedAll($data)
    {
        self::connectMT5();
        $endpoint = self::$mt5Url . 'GET_CLOSED_ALL?Session=' . self::$session . '&ManagerIndex=101';
        foreach ($data as $key => $value) {
            $endpoint = $endpoint . '&' . $key . '=' . $value;
        }
        $client = new Client();
        $response = $client->request('GET', $endpoint);
        $result = json_decode($response->getBody());
        return $result->lstCLOSE;
    }

    public function transferCommission()
    {
        $admins = Admin::where('role', config('role.staff'))->get();
        $to = date('Y-m-d H:i:s', strtotime('now'));
        $from = date('Y-m-d H:i:s', strtotime('-1 week'));
        foreach ($admins as $key => $admin) {
            $logins = $this->liveAccountRepository->getLoginsByAdmin($admin);
            $result = $this->getOpenedTrades($logins, $from, $to);
            $commission = $result[2];
            if ($commission) {
                $userId = User::where('email', $admin->email)->pluck('id');
                $account = LiveAccount::where('user_id', $userId[0])->pluck('login');
                if (count($account)) {
                    $data = [
                        'Account' => $account[0],
                        'Amount' => $commission,
                        'Comment' => 'transfer commission'
                    ];
                    $this->makeWithdrawal($data);
                }
            }
        }
    }
}
