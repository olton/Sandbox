<?php

set_exception_handler("ExceptionHandler::Handler");

class ExceptionHandler {
    private static $stack;
    private static $exception_point;
    private static $exception_initiator;
    private static $exception_func;
    private static $exception_message;
    private static $exception_code;
    private static $cli;

    public static function Log($message = "Unknown error"){
        error_log($message);
    }

    public static function IsCli(){
        return strpos(php_sapi_name(), "cli") !== false;
    }

    public static function setCliMarker(){
        self::$cli = strpos(php_sapi_name(), "cli") !== false;
    }

    public static function setInitiator(\Exception $e){
        self::$exception_initiator = array(
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode()
        );
        self::$exception_message = $e->getMessage();
        self::$exception_code = $e->getCode();
    }

    public static function setStack(\Exception $e){
        self::$stack = $e->getTrace();
    }

    public static function setPoint(){
        $point = isset(self::$stack[0]) ? self::$stack[0] : false;

        if ($point) {
            self::$exception_point = array(
                'file' => $point['file'],
                'line' => $point['line']
            );
        } else {
            self::$exception_point = false;
        }
    }

    public static function setFunc(){
        $func = isset(self::$stack[1]) ? self::$stack[1] : false;

        if ($func) {
            self::$exception_func = array(
                'class' => $func['class'],
                'type' => $func['type'],
                'func' => $func['function'],
                'method' => $func['class'].$func['type'].$func['function']
            );
        } else {
            if (isset(self::$stack[0]['function']) && !empty(self::$stack[0]['function'])) {
                self::$exception_func = array(
                    'class' => "",
                    'type' => "",
                    'func' => self::$stack[0]['function'],
                    'method' => self::$stack[0]['function']
                );
            } else {
                self::$exception_func = false;
            }
        }
    }

    public static function Handler(\Exception $e){
        self::setCliMarker();
        self::setInitiator($e);
        self::setStack($e);
        self::setPoint();
        self::setFunc();
        if (self::$cli) {
            self::CliOut();
        } else {
            self::WebOut();
        }
        self::Log(self::$exception_message);
    }

    public static function GetStack(){
        return self::$stack;
    }

    public static function CliOut(){
        echo "\nError " . "[".self::$exception_code."] ".self::$exception_message."\n";
        if (self::$exception_func) {
            echo "Func: " . self::$exception_func['method'] . "\n";
        }

        if (self::$exception_point) {
            echo "Point: " . self::$exception_point['file'] . " [Line: " . self::$exception_point['line'] . "]\n\n";

            $f = explode("\n", file_get_contents(self::$exception_point['file']));
            $start_line = self::$exception_point['line'] < 11 ? 0 : self::$exception_point['line'] - 10;
            $stop_line = self::$exception_point['line'] + 10;

            for ($i = $start_line; $i < $stop_line; $i++) {
                if (!isset($f[$i - 1])) break;
                echo ($i == self::$exception_point['line'] ? '> ' : '  ') . $i . "  " . $f[$i - 1]."\n";
            }
            echo "\n";
        }

        if (self::$exception_initiator) {
            echo "Exception: " . self::$exception_initiator['file'] . " [Line: " . self::$exception_initiator['line'] . "]\n\n";

            $start_line = self::$exception_initiator['line'] < 6 ? 0 : self::$exception_initiator['line'] - 5;
            $stop_line = self::$exception_initiator['line'] + 5;
            $f = explode("\n", file_get_contents(self::$exception_initiator['file']));
            for ($i = $start_line; $i < $stop_line; $i++) {
                if (!isset($f[$i - 1])) break;
                echo ($i == self::$exception_initiator['line'] ? '> ' : '  ') . $i . "  " . $f[$i - 1]."\n";
            }
        }
    }

    public static function WebOut(){
        echo "<h1 style='color: red'>Error</h1>";
        echo "<p><strong>[".self::$exception_code."] ". self::$exception_message."</strong></p>";
        if (self::$exception_func) {
            echo "<div style='padding: 10px; background: lightblue; color: #000;'><code><b>Func: </b>".self::$exception_func['method']."</code></div>";
        }
        if (self::$exception_point) {
            echo "<div style='padding: 10px; background: lightpink; color: #000;'><code><b>Point: </b>" . self::$exception_point['file'] . " [Line: " . self::$exception_point['line'] . "]</code></div>";

            $f = explode("\n", file_get_contents(self::$exception_point['file']));
            $start_line = self::$exception_point['line'] < 11 ? 0 : self::$exception_point['line'] - 10;
            $stop_line = self::$exception_point['line'] + 10;

            echo "<pre style='padding: 10px; background: #ffc; margin: 0'><code>";
            for ($i = $start_line; $i < $stop_line; $i++) {
                if (!isset($f[$i - 1])) break;
                echo "<span style='" . ($i == self::$exception_point['line'] ? 'color: red;' : '') . "'>$i. {$f[$i - 1]}</span>";
            }
            echo "</code></pre>";
        }

        if (self::$exception_initiator) {

            echo "<div style='padding: 10px; background: lightpink; color: #000; margin-bottom: 0'><code><b>Exception: </b>" . self::$exception_initiator['file'] . " [Line: " . self::$exception_initiator['line'] . "]</code></div>";

            $f = explode("\n", file_get_contents(self::$exception_initiator['file']));
            $start_line = self::$exception_initiator['line'] < 11 ? 0 : self::$exception_initiator['line'] - 10;
            $stop_line = self::$exception_initiator['line'] + 10;

            echo "<pre style='padding: 10px; background: #ffc; margin: 0'><code>";
            for ($i = $start_line; $i < $stop_line; $i++) {
                if (!isset($f[$i - 1])) break;
                echo "<span style='" . ($i == self::$exception_initiator['line'] ? 'color: red;' : '') . "'>$i. {$f[$i - 1]}</span>";
            }
            echo "</code></pre>";
        }
    }
}
