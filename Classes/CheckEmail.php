<?php


namespace Classes;

define('CHECK_EMAIL_DEBUG_OK', false);

class CheckEmail {
    var $timeout = 10;

    function _is_valid_email($email = "") {
        return preg_match('/^[.\w-]+@([\w-]+\.)+[a-zA-Z]{2,6}$/', $email);
    }

    function execute($email = "") {
        if (!$this->_is_valid_email($email)) return false;
        $host = substr(strstr($email, '@'), 1);

        $mxhosts = [];
        $host .= ".";

        if (getmxrr($host, $mxhosts[0], $mxhosts[1]) == true) {
            array_multisort($mxhosts[1], $mxhosts[0]);
        } else {
            $mxhosts[0] = $host;
            $mxhosts[1] = 10;
        }
        if (CHECK_EMAIL_DEBUG_OK) print_r($mxhosts);

        $port = 25;
        $localhost = "event.org.ua";
        $sender = 'info@' . $localhost;

        $result = false;
        $id = 0;
        while (!$result && is_array($mxhosts[0]) && $id < count($mxhosts[0])) {
            if (function_exists("fsockopen")) {
                if (CHECK_EMAIL_DEBUG_OK) print_r($id . " " . $mxhosts[0][$id]);
                try {
                    $connection = @fsockopen($mxhosts[0][$id], $port, $errno, $error, $this->timeout);
                    if ($connection) {
                        @fputs($connection, "HELO $localhost\r\n"); // 250
                        $data = @fgets($connection, 1024);
                        $response = substr($data, 0, 1);
                        if (CHECK_EMAIL_DEBUG_OK) print_r($data);
                        if ($response == '2') // 200, 250 etc.
                        {
                            @fputs($connection, "MAIL FROM:<$sender>\r\n");
                            $data = @fgets($connection, 1024);
                            $response = substr($data, 0, 1);
                            if (CHECK_EMAIL_DEBUG_OK) print_r($data);
                            if ($response == '2') // 200, 250 etc.
                            {
                                @fputs($connection, "RCPT TO:<$email>\r\n");
                                $data = @fgets($connection, 1024);
                                $response = substr($data, 0, 1);
                                if (CHECK_EMAIL_DEBUG_OK) print_r($data);
                                if ($response == '2') // 200, 250 etc.
                                {
                                    @fputs($connection, "data\r\n");
                                    $data = @fgets($connection, 1024);
                                    $response = substr($data, 0, 1);
                                    if (CHECK_EMAIL_DEBUG_OK) print_r($data);
                                    if ($response == '2') // 200, 250 etc.
                                    {
                                        $result = true;
                                    }
                                }
                            }
                        }
                        @fputs($connection, "QUIT\r\n");
                        @fclose($connection);
                        if ($result) return 1;
                    }
                } catch (\Exception $e) {
                    return -1;
                }
            } else  break;
            $id++;
        } //while
        return 0;
    }
}