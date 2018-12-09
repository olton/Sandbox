<?php


namespace Controllers;

use Classes\Controller;
use Classes\Hashids;
use Classes\Url;
use Classes\Viewer;
use Models\CodeModel;
use Models\UserModel;

class CodeController extends Controller {
    private $model;
    private $user_model;
    private $head = [
        "styles" => [
            "codemirror" => VIEW_PATH."vendors/codemirror-5.41.0/lib/codemirror.css",
            "codemirror-theme" => VIEW_PATH."vendors/codemirror-5.41.0/theme/idea.css",
            "codemirror-fold-gutter" => VIEW_PATH."vendors/codemirror-5.41.0/addon/fold/foldgutter.css",
            "codemirror-show-hint" => VIEW_PATH."vendors/codemirror-5.41.0/addon/hint/show-hint.css",
            "codemirror-fullscreen-css" => VIEW_PATH."vendors/codemirror-5.41.0/addon/display/fullscreen.css"
        ],
        "scripts" => [

        ]
    ];
    private $foot = [
        "scripts" => [
            "codemirror" => VIEW_PATH."vendors/codemirror-5.41.0/lib/codemirror.js",
            "codemirror-matchtags" => VIEW_PATH."vendors/codemirror-5.41.0/addon/edit/matchtags.js",
            "codemirror-closebrackets" => VIEW_PATH."vendors/codemirror-5.41.0/addon/edit/closebrackets.js",
            "codemirror-closetag" => VIEW_PATH."vendors/codemirror-5.41.0/addon/edit/closetag.js",
            "codemirror-continuecomment" => VIEW_PATH."vendors/codemirror-5.41.0/addon/comment/continuecomment.js",
            "codemirror-comment" => VIEW_PATH."vendors/codemirror-5.41.0/addon/comment/comment.js",
            "codemirror-foldcode" => VIEW_PATH."vendors/codemirror-5.41.0/addon/fold/foldcode.js",
            "codemirror-foldgutter" => VIEW_PATH."vendors/codemirror-5.41.0/addon/fold/foldgutter.js",
            "codemirror-xml-fold" => VIEW_PATH."vendors/codemirror-5.41.0/addon/fold/xml-fold.js",
            "codemirror-indent-fold" => VIEW_PATH."vendors/codemirror-5.41.0/addon/fold/indent-fold.js",
            "codemirror-comment-fold" => VIEW_PATH."vendors/codemirror-5.41.0/addon/fold/comment-fold.js",
            "codemirror-brace-fold" => VIEW_PATH."vendors/codemirror-5.41.0/addon/fold/brace-fold.js",
            "codemirror-css-hint" => VIEW_PATH."vendors/codemirror-5.41.0/addon/hint/css-hint.js",
            "codemirror-html-hint" => VIEW_PATH."vendors/codemirror-5.41.0/addon/hint/html-hint.js",
            "codemirror-javascript-hint" => VIEW_PATH."vendors/codemirror-5.41.0/addon/hint/javascript-hint.js",
            "codemirror-xml-hint" => VIEW_PATH."vendors/codemirror-5.41.0/addon/hint/xml-hint.js",
            "codemirror-active-line" => VIEW_PATH."vendors/codemirror-5.41.0/addon/selection/active-line.js",
            "codemirror-placeholder" => VIEW_PATH."vendors/codemirror-5.41.0/addon/display/placeholder.js",
            "codemirror-fullscreen" => VIEW_PATH."vendors/codemirror-5.41.0/addon/display/fullscreen.js",
            "codemirror-xml" => VIEW_PATH."vendors/codemirror-5.41.0/mode/xml/xml.js",
            "codemirror-htmlmixed" => VIEW_PATH."vendors/codemirror-5.41.0/mode/htmlmixed/htmlmixed.js",
            "codemirror-css" => VIEW_PATH."vendors/codemirror-5.41.0/mode/css/css.js",
            "codemirror-javascript" => VIEW_PATH."vendors/codemirror-5.41.0/mode/javascript/javascript.js",

            "sandbox" => VIEW_PATH."js/sandbox.js",
            "iframe" => VIEW_PATH."js/iframe.js",
            "editors" => VIEW_PATH."js/editors.js",
        ]
    ];

    public function __construct(){
        $this->model = new CodeModel();
        $this->user_model = new UserModel();
    }

    private function CreateFile($file_name, $code, $temp = false, $debug = false) {
        global $CODE_TEMPLATE, $CODE_DEBUG_TEMPLATE;

        $html_classes = $code['html_classes'];
        $head = $code['html_head'];
        $tags = $code['tags'];
        $desc = $code['desc'];

        if ($code['css_base'] == "none") {
            $css_base = "";
        } else {
            $css_base = '<link rel="stylesheet" href="/Sandbox/data/css/'.$code['css_base'].'.css'.'">'."\n";
        }

        $css_links = "";
        foreach (explode("\n", $code['css_external']) as $link) {
            $css_links .= '<link rel="stylesheet" href="'.$link.'">'."\n";
        }

        $title = $code['title'];
        $css_code = $code['css'];
        $body_classes = $code['body_classes'];
        $html_code = $code['html'];

        $js_links = "";
        foreach (explode("\n", $code['js_external']) as $link) {
            $js_links .= '<script src="'.$link.'"></script>'."\n";
        }

        $js_type = $code['js_type'];
        $js_code = $code['js'];

        $template_code = ($debug == false ? $CODE_TEMPLATE : $CODE_DEBUG_TEMPLATE);

        $template_content = html_entity_decode(str_replace(
            [
                "{{_html_classes_}}",
                "{{_head_}}",
                "{{_tags_}}",
                "{{_desc_}}",
                "{{_css_base_}}",
                "{{_css_links_}}",
                "{{_title_}}",
                "{{_css_code_}}",
                "{{_body_classes_}}",
                "{{_html_code_}}",
                "{{_js_links_}}",
                "{{_js_type_}}",
                "{{_js_code_}}",
            ],
            [
                $html_classes,
                $head,
                $tags,
                $desc,
                $css_base,
                $css_links,
                $title,
                $css_code,
                $body_classes,
                $html_code,
                $js_links,
                $js_type,
                $js_code
            ],
            $template_code
        ), ENT_QUOTES);

        $file = null;
        $result = false;

        try {
            $file = fopen(SANDBOX_PATH . ($temp ? "temp" . DSP : $_SESSION['user']['name'] . DSP ) . $file_name, 'w');
            fwrite($file, $template_content);
            $result = true;
        } catch (\Exception $e) {
            //
        } finally {
            @fclose($file);
        }

        return $result;
    }

    public function Blank($template = "metro4"){

        if ($_SESSION['current'] == -1) {
            Url::Redirect("/");
            exit(0);
        }

        $top_templates = $this->model->Templates("is_top=1");
        $templates = $this->model->Templates("is_top!=1");

        $tpl = $this->model->Template($template);
        $temp_file_name = uniqid($_SESSION['user']['name']."-".$tpl['name']."-").".html";
        $code = $this->model->Code(-1);

        $code['html_head'] = $tpl['head'];
        $code['html'] = $tpl['html'];
        $code['css'] = $tpl['css'];
        $code['js'] = $tpl['js'];
        $code['js_type'] = $tpl['js_type'];
        $code['css_external'] = $tpl['css_links'];
        $code['js_external'] = $tpl['js_links'];
        $code['layout'] = $_SESSION['user']['layout'];

        $code['template_data'] = $tpl;

        $code['temp_file'] = $temp_file_name;
        $code['saved'] = 0;
        $code['alien'] = 0;

        $_SESSION['temp_file'] = $temp_file_name;
        $_SESSION['layout'] = $code['layout'];

        $this->CreateFile($temp_file_name, $code, true, false);
        $code['iframe'] = ($_SERVER['HTTP_HOST'] === "sandbox.local" ? "http" : "https") ."://".$_SERVER['HTTP_HOST']."/Sandbox/temp/".$temp_file_name;
        $this->model->AddTempFile($temp_file_name, $_SESSION['current']);

        $params = [
            "page_title" => "Metro 4 Sandbox",
            "body_class" => "h-vh-100",
            "code" => $code,
            "top_templates" => $top_templates,
            "templates" => $templates,
            "head_styles" => $this->head['styles'],
            "foot_scripts" => $this->foot['scripts'],
        ];
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render("code.phtml", $params);
    }

    public function Editor($user, $hash){
        $regular_file = $hash.".html";

        if (!is_file(SANDBOX_PATH . "$user/".$regular_file)) {
            $code = $this->model->Code($hash);
            if ($code === false) {
                Url::Redirect("/");
                exit(0);
            }
            $this->CreateFile(
                $regular_file,
                $code,
                false,
                false
            );
        }

        $alien = $_SESSION['user']['name'] != $user ? 1 : 0;

        $top_templates = $this->model->Templates("is_top=1");
        $templates = $this->model->Templates("is_top!=1");

        $code = $this->model->Code($hash);
        $tpl = $this->model->TemplateByID($code['template']);

        if ($code === false) {
            @unlink(SANDBOX_PATH . "$user/$hash.html");
            Url::Redirect("/");
            exit(0);
        }

        $_SESSION['layout'] = $code['layout'];

        $code['iframe'] = "//".$_SERVER['HTTP_HOST']."/Sandbox/$user/$hash.html";
        $code['temp_file'] = "";
        $code['saved'] = 1;
        $code['alien'] = $alien;
        $code['template_data'] = $tpl;
        $params = [
            "page_title" => "Metro 4 Sandbox",
            "body_class" => "h-vh-100",
            "code" => $code,
            "top_templates" => $top_templates,
            "templates" => $templates,
            "head_styles" => $this->head['styles'],
            "foot_scripts" => $this->foot['scripts'],
        ];
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render("code.phtml", $params);
    }

    public function SaveProcess(){
        global $POST;

        $id = intval($POST['id']);
        $title = $POST['title'];
        $html = $POST['html'];
        $css = $POST['css'];
        $js = $POST['js'];
        $js_type = $POST['js_type'];
        $template = $POST['template'];
        $temp_file = $POST['temp_file'];
        $hash = $POST['hash'];
        $html_head = $POST['html_head'];
        $html_processor = $POST['html_processor'];
        $css_processor = $POST['css_processor'];
        $js_processor = $POST['js_processor'];
        $html_classes = $POST['html_classes'];
        $body_classes = $POST['body_classes'];
        $desc = $POST['desc'];
        $tags = $POST['tags'];
        $code_type = $POST['code_type'];
        $css_external = $POST['css_external'];
        $css_base = $POST['css_base'];
        $js_external = $POST['js_external'];
        $alien = intval($POST['alien']) === 1;
        $layout = isset($_SESSION['layout']) ? $_SESSION['layout'] : 'right';

        $saved = intval($POST['saved']) === 1;
        $can_save = isset($POST['can_save']) ? $POST['can_save'] != "false" : "false";

        if ($_SESSION['current'] == -1 || ($can_save == false && $saved == false)) {

            if ($can_save == true) {
                $this->ReturnJSON(false, "Auth required", []);
            }

            $tpl = $this->model->TemplateByID($template);
            $code = [
                "id" => -1,
                "title" => $title,
                "user" => $_SESSION['current'],
                "html" => $html,
                "css" => $css,
                "js" => $js,
                "js_type" => $js_type,
                "hash" => "new",
                "template" => $template,
                "html_head" => $html_head,
                "html_processor" => $html_processor,
                "html_classes" => $html_classes,
                "body_classes" => $body_classes,
                "css_processor" => $css_processor,
                "css_external" => $css_external,
                "css_base" => $css_base,
                "js_processor" => $js_processor,
                "js_external" => $js_external,
                "desc" => $desc,
                "tags" => $tags,
                "code_type" => $code_type,
                "layout" => $layout
            ];
            if ($temp_file == '') {
                $temp_file = uniqid($_SESSION['user']['name']."-".$tpl['name']."-").".html";
            }
            $this->CreateFile($temp_file, $code, true);
            $this->ReturnJSON(true, "OK", [
                "mode" => "temp",
                "code" => $code,
                "iframe" => "//".$_SERVER['HTTP_HOST']."/Sandbox/temp/".$temp_file
            ]);

        } else if ($can_save && $_SESSION['current'] == -1) {

            $this->ReturnJSON(false, "Auth required", []);

        } else {

            if ($alien) {
                $id = $this->model->Fork($hash, $_SESSION['current']);
            }

            $result = $this->model->Save(
                $id,
                $_SESSION['current'],
                $title,
                $html,
                $css,
                $js,
                $js_type,
                $template,
                $hash,
                $html_head,
                $html_processor,
                $html_classes,
                $body_classes,
                $css_processor,
                $css_external,
                $css_base,
                $js_processor,
                $js_external,
                $desc,
                $tags,
                $code_type
            );

            if (($can_save && !$saved)) {
                $hash_gen = new Hashids(HASH_SALT, 10);
                $hash = $hash_gen->encode($result);
                $this->model->UpdateHash($result, $hash);
            }

            unset($_SESSION['temp_file']);
            if ($temp_file !== "") @unlink(SANDBOX_PATH . "temp" . DSP . $temp_file);
            if ($temp_file !== "") $this->model->DeleteTempFile($temp_file);
            $regular_file= $hash . ".html";

            $code = $this->model->Code($hash);

            $this->CreateFile(
                $regular_file,
                $code,
                false
            );

            $this->ReturnJSON(true, "OK", [
                "mode" => "regular",
                "temp_file" => "",
                "saved" => 1,
                "url" => "/".$_SESSION['user']['name']."/code/".$hash,
                "debug_url" => "/".$_SESSION['user']['name']."/debug/".$hash,
                "iframe" => "//".$_SERVER['HTTP_HOST']."/Sandbox/".$_SESSION['user']['name']."/".$regular_file,
                "code" => $code
            ]);
        }
    }

    public function UnsavedProcess(){
        if (isset($_SESSION['temp_file'])) {
            @unlink(SANDBOX_PATH . "temp" . DSP . $_SESSION['temp_file']);
            $this->model->DeleteTempFile($_SESSION['temp_file']);
        }
    }

    public function List(){
        $list = $this->model->List($_SESSION['current']);
        return $list;
    }

    public function Debug($user, $hash){
        $regular_file = $hash . ".html";

        $code = $this->model->Code($hash);

        if ($code === false) {
            Url::Redirect("/not-found");
            exit(0);
        }

        $this->CreateFile(
            $regular_file,
            $code,
            false,
            true
        );

        $view = new Viewer("Sandbox/".$user."/");
        echo $view->Render($hash.".html", []);
    }

    public function Fork(){
        global $POST;

        $hash = $POST['hash'];
        $id = $this->model->Fork($hash, $_SESSION['current']);

        $hash_gen = new Hashids(HASH_SALT, 10);
        $hash = $hash_gen->encode($id);
        $this->model->UpdateHash($id, $hash);

        $this->ReturnJSON(true, "OK", ["redirect"=>"/".$_SESSION['user']["name"]."/code/".$hash]);
    }

    public function Delete(){
        global  $POST;

        $hash = $POST['hash'];
        $code = $this->model->Code($hash);
        if ($code['user'] !== $_SESSION['current']) {
            $this->ReturnJSON(false, "No permissions for delete code with hash " . $hash, ["hash"=>$hash]);
        }
        @unlink(SANDBOX_PATH . $_SESSION['user']['name'] . DSP . "$hash.html");
        $this->model->DeleteCode($hash);
        $this->ReturnJSON(true, "OK", ["hash"=>$hash]);
    }

    public function LayoutProcess(){
        global $POST;

        $hash = $POST['hash'];
        $layout = $POST['layout'];

        $this->user_model->SetLayout($_SESSION['current'], $layout);

        if ($hash == "new") {
            $this->ReturnJSON(true, "OK", []);
        }

        $this->model->SetLayout($hash, $layout);

        $this->ReturnJSON(true, "OK", []);
    }
}