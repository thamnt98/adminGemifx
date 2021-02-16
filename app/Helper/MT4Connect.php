<?php

namespace App\Helper;

class MT4Connect
{

    public static function connect()
    {
        return fsockopen(config('mt4.vps_ip'), config('mt4.vps_port'), $errno, $errstr, 6);
    }

    public static function deleteMultiLiveAccount($logins)
    {
        try {
            $fp = self::connect();
            if (!$fp) {
                return 'Không thể kết nối tới MT4';
            }
            $message = '';
            foreach ($logins as $login) {
                $cmd = 'action=deleteaccount&login=' . $login;
                fwrite($fp, $cmd);
                stream_set_timeout($fp, 1);
                $result = '';
                $info = stream_get_meta_data($fp);
                while (!$info['timed_out'] && !feof($fp)) {
                    $str = @fgets($fp, 1024);
                    if (strpos($str, 'login')) {
                        $result .= $str;
                        $info = stream_get_meta_data($fp);
                    }
                }
                $result = explode('&', $result);
                $result =  explode('=', $result[0])[1];
                if ($result != 1) {
                    $message =  "Xóa tài khoản thất bại";
                    break;
                }
            }
            fclose($fp);
            return $message;
        } catch (\Exception $e) {
            return "Xóa tài khoản thất bại";
        }
    }

    public function deleteLiveAccount($login)
    {
        try {
            $fp = self::connect();
            if (!$fp) {
                return 'Không thể kết nối tới MT4';
            }
            $message = '';
            $cmd = 'action=deleteaccount&login=' . $login;
            fwrite($fp, $cmd);
            stream_set_timeout($fp, 1);
            $result = '';
            $info = stream_get_meta_data($fp);
            while (!$info['timed_out'] && !feof($fp)) {
                $str = @fgets($fp, 1024);
                if (strpos($str, 'login')) {
                    $result .= $str;
                    $info = stream_get_meta_data($fp);
                }
            }
            $result = explode('&', $result);
            $result =  explode('=', $result[0])[1];
            if ($result != 1) {
                $message =  "Xóa tài khoản thất bại";
            }
            fclose($fp);
            return $message;
        } catch (\Exception $e) {
            return "Xóa tài khoản thất bại";
        }
    }

    public static function openLiveAccount($data)
    {
        try {
            $fp = self::connect();
            if (!$fp) {
                return 'Không thể kết nối tới MT4';
            }
            $cmd = 'action=createaccount&login=next';
            foreach ($data as $key => $value) {
                $cmd = $cmd . '&' . $key . '=' . $value;
            }
            fwrite($fp, $cmd);
            stream_set_timeout($fp, 1);
            $result = '';
            $info = stream_get_meta_data($fp);
            while (!$info['timed_out'] && !feof($fp)) {
                $str = @fgets($fp, 1024);
                if (strpos($str, 'login')) {
                    $result .= $str;
                    $info = stream_get_meta_data($fp);
                }
            }
            fclose($fp);
            $result = explode('&', $result);
            $data['login'] = explode('=', $result[1])[1];
            return $data['login'];
        } catch (\Exception $e) {
            return "Xóa tài khoản thất bại";
        }
    }

    public static function updateLiveAccount($data)
    {
        try {
            $fp = self::connect();
            if (!$fp) {
                return 'Không thể kết nối tới MT4';
            }
            $cmd = 'action=modifyaccount';
            foreach ($data as $key => $value) {
                $cmd = $cmd . '&' . $key . '=' . $value;
            }
            fwrite($fp, $cmd);
            stream_set_timeout($fp, 1);
            $result = '';
            $info = stream_get_meta_data($fp);
            while (!$info['timed_out'] && !feof($fp)) {
                $str = @fgets($fp, 1024);
                if (strpos($str, 'login')) {
                    $result .= $str;
                    $info = stream_get_meta_data($fp);
                }
            }
            fclose($fp);
        } catch (\Exception $e) {
            return "Cập nhật tài khoản thất bại";
        }
    }

    public static function getOpenedTrades($logins, $data){
        if(empty($logins)){
            return [];
        }
        try {
            $fp = self::connect();
            if (!$fp) {
                return 'Không thể kết nối tới MT4';
            }
            $from = strtotime($data['from'] . ' 00:00:00');
            $to = strtotime($data['to'] . ' 23:59:59');
            $logins = ['2131836596', '2131851564' ];
            $loginString = '';
            foreach($logins as $login){
                $loginString .= $login. ';';
            }
            $logins = (rtrim($loginString, ';'));
            $cmd = 'action=gethistory&from=' .$from . '&to=' . $to  . '&login=' . $logins;
            fwrite($fp, $cmd);
            stream_set_timeout($fp, 1);
            $result = '';
            $info = stream_get_meta_data($fp);
            while (!$info['timed_out'] && !feof($fp)) {
                $str = @fgets($fp, 1024);
                if (strpos($str, 'size')) {
                    $size = explode('size', $str);
                    $userAmount = $size[1];
                    if($userAmount == "=0\n"){
                        break;
                    }
                }
                if (strpos($str, ';')) {
                    $result .= $str;
                    $info = stream_get_meta_data($fp);
                }
            }
            $array = array();
            if(!empty($result)){
                $result = explode('&', $result);
                fclose($fp);
                $lines = explode("\n", $result[0]);
                foreach ($lines as $line) {
                    $array[] = explode(";", $line);
                }
            }
            return $array;
        } catch (\Exception $e) {
            return "Hệ thống đang bị lỗi. Vui lòng thử lại sau ";
        }
    }
}
