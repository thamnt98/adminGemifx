<?php

namespace App\Helper;

use GuzzleHttp\Client;

class MT5Helper
{
    protected static $mt5Url = 'http://79.143.176.19:17014/ManagerAPIFOREX/';

    protected static $session = '';

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

//    public function getAccountInfo($login){
//        $endpoint = self::$mt5Url . 'GET_USER_INFO?Session=' .$this->session. '&ManagerIndex=101&Account=' . $login;
//        $client = new Client();
//        $response = $client->request('GET', $endpoint);
//        $result = json_decode($response->getBody());
//        return $result;
//    }

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
            foreach($tradeByLogin as $key => $trade ){
                if (strtotime($trade->Close_Time) - strtotime($trade->Open_Time) > 180) {
                    $lots += $lots + $trade->Lot;
                    $symbol = $trade->Symbol;
                    if(in_array($symbol, config('trader_type.USStocks'))){
                        $commission += round($trade->Lot * $commissionValue[0] , 2);
                    }elseif(in_array($symbol, config('trader_type.Forex'))){
                        $commission += round($trade->Lot * $commissionValue[1] , 2);
                    }
                    else{
                        $commission += round($trade->Lot * $commissionValue[2] , 2);
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
}
