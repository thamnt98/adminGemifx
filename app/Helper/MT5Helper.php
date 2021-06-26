<?php

namespace App\Helper;

use GuzzleHttp\Client;

class MT5Helper
{
    protected static $mt5Url = 'http://79.143.176.19:17014/ManagerAPIFOREX/';

    protected static $session = '';


    private static function connectMT5(){
        $endpoint = self::$mt5Url . 'LOGIN_SESSION?Email=startingmt5broker@gmail.com&Password=rasa8r&Source=1';
        $client = new Client();
        $response = $client->request('GET', $endpoint);
        $result = json_decode($response->getBody());
        self::$session =  $result->Session;
    }

    public function getGroups()
    {
        $this->connectMT5();
        $endpoint = self::$mt5Url . 'GET_GROUPS?Session=' .self::$session. '&ManagerIndex=101';
        $client = new Client();
        $response = $client->request('GET', $endpoint);
        $result = json_decode($response->getBody());
        return $result->lstGroups;
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

    public function getAccountInfo($login){
        $endpoint = self::$mt5Url . 'GET_USER_INFO?Session=' .$this->session. '&ManagerIndex=101&Account=' . $login;
        $client = new Client();
        $response = $client->request('GET', $endpoint);
        $result = json_decode($response->getBody());
        return $result;
    }


    public static function updateAccount($type, $login, $data){
        self::connectMT5();
        $endpoint = self::$mt5Url . $type . '?Session=' .self::$session. '&ManagerIndex=101&Account=' . $login;
        foreach ($data as $key => $value){
            $endpoint = $endpoint. '&' . $key . '=' . $value;
        }
        $client = new Client();
        $response = $client->request('GET', $endpoint);
        $result = json_decode($response->getBody());
        return $result;
    }

}
