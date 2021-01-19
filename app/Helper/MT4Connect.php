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
}
