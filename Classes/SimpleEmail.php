<?php

namespace Classes;

class SimpleEmail extends Super {
    private $to = null;
    private $cc = null;
    private $bcc = null;
    private $from = null;
    private $reply = null;
    private $subject = "";
    private $message = "";
    private $is_html = true;
    private $attachments = null;
    private $charset = "UTF-8";

    private function uid(){
        return md5(uniqid(time()));
    }

    private function encodeSubject(){
        return '=?'.$this->charset.'?B?'.base64_encode($this->subject).'?=';
    }

    public function IsHtml($val = true){
        $this->is_html = $val;
        return $this;
    }

    public function SetCharset($charset = "UTF-8"){
        $this->charset = $charset;
        return $this;
    }

    public function From($from = "webmaster@localhost"){
        $this->from = $from;
        return $this;
    }

    public function To($to = array()){
        $this->to = $to;
        return $this;
    }

    public function Cc($cc = array()){
        $this->cc = $cc;
        return $this; 
    }

    public function Bcc($bcc = array()){
        $this->bcc = $bcc;
        return $this;
    }

    public function Reply($reply = null){
        $this->reply = $reply;
        return $this;
    }

    public function Subject($val){
        $this->subject = $val;
        return $this;
    }

    public function Message($val){
        $this->message = $val;
        return $this;
    }

    // Функция возвращает false усли не может с локалхоста отправить сообщение
    public function Send(){
/*
        $f=@fsockopen("localhost", 25, $errno, $errstr, 30);
        if ($f){
            fclose($f);
        } else {
            return false;
        }
*/
        $to = join(", ", $this->to);
        $sbj = $this->encodeSubject();
        $msg = $this->message;
        $headers = null;
        $params = null;


        $headers[] = "MIME-Version: 1.0";
        if ($this->is_html) {
            $headers[] = "Content-type: text/html; charset={$this->charset}";
        } else {
            $headers[] = "Content-type: text/plain";
        }
        if ($this->from) {
            $headers[] = "From: {$this->from}";
        }
        if ($this->cc) { 
            $headers[] = "Cc: " . join(", ", $this->cc);
        }
        if ($this->bcc) {
            $headers[] = "Bcc: " . join(", ", $this->bcc);
        }
        if ($this->reply) {
            $headers[] = "Reply-To: {$this->reply}";
        }

        return mail($to, $sbj, $msg, join("\r\n", $headers), $params);
    }
}
