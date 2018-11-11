<?php

namespace Classes;

class Controller extends Super {
    public function ReturnData($data = array()){
        header('Content-Type: text/json; charset=UTF-8');
        echo json_encode($data);
        exit(0);
    }

    public function ReturnJSON($result = true, $message = "OK", $data = array()){
        header('Content-Type: text/json; charset=UTF-8');
        echo json_encode(array("result"=>$result, "message"=>$message, "data"=>$data));
        exit(0);
    }

    public function ReturnHTML($html){
        header('Content-Type: text/html; charset=UTF-8');
        echo $html;
        exit(0);
    }

    public function ReturnContent($content, $type = 'application/json', $charset = 'UTF-8'){
        header('Content-Type: '.$type.'; charset='.$charset);
        echo $content;
        exit(0);
    }

    public function ReturnFile($file)
    {
        header("HTTP/1.1 200 OK");
        header("Cache-Control: None");
        header("Pragma: no-cache");
        header("Accept-Ranges: bytes");
        header('Content-Disposition: attachment; filename="'.$file.'"');
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . filesize($file));
        header("Proxy-Connection: close");
        readfile($file);
        exit(0);
    }

    public function Response($status = 'ok', $code = 0, $message = 'Command completed', $data = array()){
        header('Content-Type: text/json; charset=UTF-8');
        echo json_encode(array(
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ));
        exit(0);
    }
}
