<?php

namespace Classes;

class Factory {
    protected static $classes = array();

    public static function GetClass($className, $params = NULL){
        if (!self::ClassExist($className)) {
            self::RegisterClass($className, $params);
        }
        return self::$classes[$className];
    }

    public static function ClassExist($className){
        return isset(self::$classes[$className]) ? true : false;
    }

    public static function RegisterClass($className, $params = NULL) {
        if (!isset(self::$classes[$className])) {
            self::$classes[$className] = new $className($params);
        }

        return self::$classes[$className];
    }

    public static function UnregisterClass($className){
        unset(self::$classes[$className]);
    }
}
