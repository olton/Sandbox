<?php

namespace Classes;

class Url {
    public static function Redirect($destination){
        header("Location: $destination");
        exit(0);
    }

    public static function Redirect301($destination){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: $destination");
        exit(0);
    }

    public static function Redirect302($destination){
        header("HTTP/1.1 302 Found");
        header("Location: $destination");
        exit(0);
    }

    public static function Redirect303($destination){
        header("HTTP/1.1 302 See Other");
        header("Location: $destination");
        exit(0);
    }

    public static function Redirect404($page){
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        $c = file_get_contents($page);
        echo $c;
    }

    public static function TrimUri($steps = 1){
        $uri = self::Uri();
        $a = explode("/", $uri);
        for($i=0;$i<$steps;$i++){
            array_pop($a);
        }
        return join("/", $a);
    }

    public static function Uri(){
        //return "/items/12";
        if(!empty($_SERVER['REQUEST_URI'])) {return trim($_SERVER['REQUEST_URI'], '/');}
        if(!empty($_SERVER['QUERY_STRING'])) { return trim($_SERVER['QUERY_STRING'], '/');}
        return "/";
    }

    public static function CurrentPage($uri = false){
        $uri = $uri?:self::Uri();
        $elements = explode("/", $uri);
        $element = array_pop($elements);
        $current = !is_numeric($element)?1:intval($element);
        $current = $current>0?$current:1;
        return $current;
    }

    public static function Pager($all = 0, $on_page = 10, $distance = 6, $captions = array()){
        if (!isset($captions['top'])) $captions['top'] = "";
        if (!isset($captions['bottom'])) $captions['bottom'] = "";
        if (!isset($captions['next'])) $captions['next'] = "";
        if (!isset($captions['prior'])) $captions['prior'] = "";
        if (!isset($captions['pages'])) $captions['pages'] = "Стр.";
        if (!isset($captions['goto'])) $captions['goto'] = "&nbsp;";
        if (!isset($captions['space'])) $captions['space'] = "...";

        $uri = self::Uri();
        $a = explode("/", $uri);
        if (is_numeric($a[count($a)-1]))
            array_pop($a);
        $cUri = join("/", $a);

        $current = self::CurrentPage();
        $pages = @ceil($all/$on_page);
        $pages = $pages<=0?1:$pages;
        if ($current > $pages) self::Redirect("/".$cUri);
        $start = 1;
        $pager = array();

        
        // Всего страниц
/*
        $pager[] = "<form id='_pager_select_page' style='float: right;'><span>{$captions['pages']}: <strong>$current / $pages</strong></span>";

        // Перейти к странице №
        $pager[] = "<span>{$captions['goto']}</span>\n";
        $_p = "<select onchange='window.location.href=\"/$cUri/\"+this.value' style='width: 50px'>\n";
        for($i=1;$i<=$pages;$i++){
            $_p .= "<option value='$i'".($i == $current ? " selected": "").">$i</option>\n";
        }
        $_p .= "</select></form>\n";
        $pager[] = $_p;
*/

        // Prior
        $pager[] = "<ul>";
        if ($current > $start) {
            $pager[] = "<li class='prev'><a href='/".$cUri."/".($current-1)."'>{$captions['prior']}</a></li>";
            if ($distance > 0 && $current - $distance / 2 + 1  > $start) {
                $pager[] = "<li><a href='/".$cUri."'>{$start}</a></li>";
                $pager[] = "<li class='disabled'><a href='#'>".$captions['space']."</a></li>";
            }
        } else {
            $pager[] = "<li class='prev disabled'><a href='#' onclick='return false;'>{$captions['prior']}</a></li>";
        }

        if ($distance > 0) {
            if ($pages < $distance){
                for($i=0;$i<$pages;$i++){
                    $page = $i+1;
                    if ($page == $current) {
                        $pager[] = "<li class='active'><a href='/".$cUri."/$page'>{$page}</a></li>";
                    } else {
                        $pager[] = "<li><a href='/".$cUri."/$page'>{$page}</a></li>";
                    }
                }
            } else {
                $right_add = 0;
                $left_delta = floor($distance/2);
                $left = $current - $left_delta;
                if ($left < 0) {
                    $right_add = abs($left) - 1;
                    $left = 0;
                }
                $right = $current + $left_delta + $right_add ;
                $right_delta = $pages - $right;
                if ($right_delta < 0){
                    $left = $left + $right_delta + 1;
                    $right = $pages;
                }
                if ($left<0) $left=0;


                for($i=$left;$i<$right;$i++){
                    $page = $i+1;
                    if ($page == $current) {
                        $pager[] = "<li class='active'><a href='/".$cUri."/$page'>{$page}</a></li>";
                    } else {
                        $pager[] = "<li><a href='/".$cUri."/$page'>{$page}</a></li>";
                    }
                }

                if ($right<$pages) {
                    $pager[] = "<li class='disabled'><a href='#'>".$captions['space']."</a></li>";
                    $pager[] = "<li><a href='/".$cUri."/$pages'>{$pages}</a></li>";
                }
            }
        }

        // Next
        if ($current < $pages) {
            $pager[] = "<li class='next'><a href='/".$cUri."/".($current+1)."'>{$captions['next']}</a></li>";
        } else {
            $pager[] = "<li class='next disabled'><a href='#' onclick='return false'>{$captions['next']}</a></li>";
        }
        $pager[] = "</ul>";
        return join("\n", $pager);
    }

    public static function IPv42Int($ip = '127.0.0.1'){
       $a=explode(".",$ip);
       return $a[0]*256*256*256+$a[1]*256*256+$a[2]*256+$a[3];
    }

    public static function Int2IPv4 ($int){
       $d[0]=(int)($int/256/256/256);
       $d[1]=(int)(($int-$d[0]*256*256*256)/256/256);
       $d[2]=(int)(($int-$d[0]*256*256*256-$d[1]*256*256)/256);
       $d[3]=$int-$d[0]*256*256*256-$d[1]*256*256-$d[2]*256;
       return "$d[0].$d[1].$d[2].$d[3]";
    }

    public static function GetIP(){
        $ip = getenv("HTTP_X_FORWARDED_FOR");
        if (empty($ip) || $ip=='unknown') $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
    }

    public static function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
