<?php

namespace Classes;


class Security {
    public static function GenerateEmailConfirmCode($email, $uid, $salt)
    {
        return sha1(md5($salt.$email.$uid.$salt));
    }

    public static function GenerateConfirmCode($phrase, $uid, $salt)
    {
        return sha1(md5($salt.$phrase.$uid.$salt));
    }

    public static function EncodePassword($password)
    {
        return sha1(md5($password));
    }

    public static function PasswordGenerator($length = 10){
        $password_chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890!@#$%^&*()_+~";
        $password = "";
        for($i = 0; $i < $length; $i++) {
            $password .= $password_chars[rand(0, strlen($password_chars)-1)];
        }
        return $password;
    }

    public static function XSS($data, $quotes = ENT_NOQUOTES){
        if (is_array($data)) {
            $escaped = array();
            foreach ($data as $key => $value) {
                $escaped[$key] = self::XSS($value);
            }
            return $escaped;
        }

        return htmlentities($data, $quotes);
    }

    public static function FilterData($data){
        if(is_array($data))
            $data = array_map(__FUNCTION__, $data);
        else{
            $data = trim(htmlentities(strip_tags($data)));
            if (get_magic_quotes_gpc())
                $data = stripslashes($data);
            $data = mysql_real_escape_string($data);
        }
        return $data;
    }

    public static function ClearQuotes($data, $replace = ""){
        if (is_array($data)) {
            $escaped = array();
            foreach ($data as $key => $value) {
                $escaped[$key] = self::ClearQuotes($value);
            }
            return $escaped;
        }
        return str_replace(array('"', "'"), $replace, $data);
    }
}