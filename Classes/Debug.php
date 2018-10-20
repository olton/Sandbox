<?php

namespace Classes;


class Debug {
    public static function Dump()
    {
        if (func_num_args() > 0) {
            echo "<pre>";
            for ($i = 0; $i < func_num_args(); $i++) {
                var_dump(func_get_arg($i));
            }
            echo "</pre>";
        }
    }

    public static function Stop($message = 'Stop', $error_type = E_USER_ERROR)
    {
        throw new \Exception($message, $error_type);
    }
}