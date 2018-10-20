<?php

namespace Classes;


class DBProvider extends Factory {
    public static function GetDriver($config, $db = 'MySQL')
    {
        switch (strtoupper($db)) {
            default: return self::GetClass('Classes\MySQL', $config);
        }
    }
}