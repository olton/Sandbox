<?php


namespace Models;


use Classes\Model;

class CodeModel extends Model {

    public function Template($name){
        $h = $this->Select("select * from templates where name = " . $this->_e($name));
        if ($this->Rows($h) === 0) {
            return false;
        }
        $template = $this->FetchArray($h);
        return $template;
    }

    public function Code($id){

        if ($id === -1) {
            return [
                "id" => -1,
                "user" => -1,
                "html" => "",
                "css" => "",
                "js" => "",
                "hash" => "new",
                "title" => "Untitled code",
                "template" => "default"
            ];
        }

        $h = $this->Select("
            select t1.*, t2.id as user_id, t2.name, t2.email 
            from code t1
            left join user t2 on t1.user = t2.id 
            where t1.id = " . $this->_e($id) . " or t1.hash = " . $this->_e($id));

        if ($this->Rows($h) === 0) {
            return false;
        }

        $code = $this->FetchArray($h);

        return $code;
    }

    public function Save($id, $user, $title, $html, $css, $js, $template, $hash){
        $data = [
            "user" => $user,
            "title" => $title,
            "html" => $html,
            "css" => $css,
            "js" => $js,
            "template" => $template,
            "hash" => $hash,
            "created" => date("Y-m-d h:i:s")
        ];

        if ($id == -1) {
            $this->Insert("code", $data, true, true, true);
            return $this->ID();
        } else {
            $this->Update("code", $data, "id = " . $this->_e($id), true);
            return $this->Rows();
        }
    }

    public function UpdateHash($id, $hash){
        $data = [
            "hash" => $hash
        ];
        $this->Update("code", $data, "id = " . $this->_e($id), true);
    }
}