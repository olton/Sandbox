<?php


namespace Models;


use Classes\Model;

class CodeModel extends Model {

    public function Templates(){
        $h = $this->Select("select * from templates");
        if ($this->Rows($h) === 0) {
            return false;
        }
        $templates = [];
        while ($r = $this->FetchArray($h)) {
            $templates[$r['id']] = $r;
        }
        return $templates;
    }

    public function Template($name){
        $h = $this->Select("select * from templates where name = " . $this->_e($name));
        if ($this->Rows($h) === 0) {
            return false;
        }
        $template = $this->FetchArray($h);
        return $template;
    }

    public function TemplateByID($id){
        $h = $this->Select("select * from templates where id = " . $this->_e($id));
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
                "title" => "Untitled code",
                "user" => -1,
                "html" => "",
                "css" => "",
                "js" => "",
                "hash" => "new",
                "template" => 1,
                "html_head" => "",
                "html_processor" => "none",
                "html_classes" => "",
                "body_classes" => "",
                "css_processor" => "none",
                "css_external" => "",
                "js_processor" => "none",
                "js_external" => "",
                "desc" => "",
                "tags" => "",
                "code_type" => "code"
            ];
        }

        $h = $this->Select("
            select t1.*, 
                t2.id as user_id, t2.name as user_name, t2.email as user_email, 
                t3.id as template_id, t3.name as template_name, t3.css as template_css, t3.html as template_html, t3.js as template_js, t3.libs as template_libs, t3.icon as template_icon, t3.title as template_title 
            from code t1
            left join user t2 on t1.user = t2.id 
            left join templates t3 on t1.template = t3.id 
            where t1.id = " . $this->_e($id) . " or t1.hash = " . $this->_e($id));

        if ($this->Rows($h) === 0) {
            return false;
        }

        $code = $this->FetchArray($h);

        return $code;
    }

    public function Save($id, $user, $title, $html, $css, $js, $template, $hash,
                         $html_head = "", $html_processor = "none", $html_classes = "", $body_classes = "", $css_processor = "none", $css_external = "", $js_processor = "none", $js_external = "", $desc = "", $tags = "", $code_type = "code"){
        $data = [
            "user" => $user,
            "title" => $title,
            "html" => $html,
            "css" => $css,
            "js" => $js,
            "template" => $template,
            "hash" => $hash,
            "created" => date("Y-m-d h:i:s"),
            "html_head" => $html_head,
            "html_classes" => $html_classes,
            "body_classes" => $body_classes,
            "html_processor" => $html_processor,
            "css_processor" => $css_processor,
            "css_external" => $css_external,
            "js_processor" => $js_processor,
            "js_external" => $js_external,
            "desc" => $desc,
            "tags" => $tags,
            "code_type" => $code_type
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

    public function AddTempFile($name, $user_id){
        $data = [
            "name" => $name,
            "user" => $user_id
        ];
        $this->Insert("temp_files", $data, true, false, true);
    }

    public function DeleteTempFile($name){
        $this->Delete("temp_files", "name = " . $this->_e($name));
    }

    public function List($user_id){
        $h = $this->Select("
            select t1.*, 
                t2.id as user_id, t2.name as user_name, t2.email as user_email, 
                t3.name as template_name, t3.css as template_css, t3.html as template_html, t3.js as template_js, t3.libs as template_libs, t3.icon as template_icon, t3.title as template_title 
            from code t1
            left join user t2 on t1.user = t2.id 
            left join templates t3 on t1.template = t3.id 
            where t1.user = " . $this->_e($user_id));

        if ($this->Rows($h) === 0) {
            return false;
        }

        $codes = [];
        while ($r = $this->FetchArray($h)) {
            $codes[$r['id']] = $r;
        }
        return $codes;
    }

    public function Fork($hash, $user_id) {
        $user_id = $this->_e($user_id);
        $this->Select("
            insert into code (title, `user`, html, css, js, created, template, `hash`, html_head, html_processor, html_classes, css_processor, `desc`, tags, code_type, js_processor, css_external, js_external, body_classes)
            select title, {$user_id}, html, css, js, now(), template, 'new', html_head, html_processor, html_classes, css_processor, `desc`, tags, code_type, js_processor, css_external, js_external, body_classes 
            from code where hash = " . $this->_e($hash));
        return $this->ID();
    }
}