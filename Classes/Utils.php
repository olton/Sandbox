<?php

namespace Classes;


class Utils {
    public static function nvl($val, $return = 0) {
        return isset($val) ? $val : $return;
    }

    public static function fvl($val, $return = 0) {
        return isset($val) && $val ? $val : $return;
    }
}