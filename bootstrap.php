<?php

date_default_timezone_set("Europe/Kiev");

function app_autoload($name)
{
    $name = str_replace("\\", "/", $name);
    $name = APP_ROOT . "$name.php";

    if (file_exists($name)) {
        include_once($name);
    }
}

spl_autoload_register('app_autoload');
