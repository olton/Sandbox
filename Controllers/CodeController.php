<?php


namespace Controllers;


use Classes\Controller;
use Classes\Hashids;
use Classes\Viewer;
use Models\CodeModel;

class CodeController extends Controller {
    private $model;
    private $head = [
        "styles" => [
            "codemirror" => VIEW_PATH."vendors/codemirror-5.40.2/lib/codemirror.css",
            "codemirror-theme" => VIEW_PATH."vendors/codemirror-5.40.2/theme/idea.css",
            "codemirror-fold-gutter" => VIEW_PATH."vendors/codemirror-5.40.2/addon/fold/foldgutter.css",
            "codemirror-show-hint" => VIEW_PATH."vendors/codemirror-5.40.2/addon/hint/show-hint.css"
        ],
        "scripts" => [

        ]
    ];
    private $foot = [
        "scripts" => [
            "codemirror" => VIEW_PATH."vendors/codemirror-5.40.2/lib/codemirror.js",
            "codemirror-matchbrackets" => VIEW_PATH."vendors/codemirror-5.40.2/addon/edit/matchbrackets.js",
            "codemirror-closebrackets" => VIEW_PATH."vendors/codemirror-5.40.2/addon/edit/closebrackets.js",
            "codemirror-closetag" => VIEW_PATH."vendors/codemirror-5.40.2/addon/edit/closetag.js",
            "codemirror-continuecomment" => VIEW_PATH."vendors/codemirror-5.40.2/addon/comment/continuecomment.js",
            "codemirror-comment" => VIEW_PATH."vendors/codemirror-5.40.2/addon/comment/comment.js",
            "codemirror-foldcode" => VIEW_PATH."vendors/codemirror-5.40.2/addon/fold/foldcode.js",
            "codemirror-foldgutter" => VIEW_PATH."vendors/codemirror-5.40.2/addon/fold/foldgutter.js",
            "codemirror-xml-fold" => VIEW_PATH."vendors/codemirror-5.40.2/addon/fold/xml-fold.js",
            "codemirror-indent-fold" => VIEW_PATH."vendors/codemirror-5.40.2/addon/fold/indent-fold.js",
            "codemirror-comment-fold" => VIEW_PATH."vendors/codemirror-5.40.2/addon/fold/comment-fold.js",
            "codemirror-brace-fold" => VIEW_PATH."vendors/codemirror-5.40.2/addon/fold/brace-fold.js",
            "codemirror-css-hint" => VIEW_PATH."vendors/codemirror-5.40.2/addon/hint/css-hint.js",
            "codemirror-html-hint" => VIEW_PATH."vendors/codemirror-5.40.2/addon/hint/html-hint.js",
            "codemirror-javascript-hint" => VIEW_PATH."vendors/codemirror-5.40.2/addon/hint/javascript-hint.js",
            "codemirror-xml-hint" => VIEW_PATH."vendors/codemirror-5.40.2/addon/hint/xml-hint.js",
            "codemirror-active-line" => VIEW_PATH."vendors/codemirror-5.40.2/addon/selection/active-line.js",
            "codemirror-placeholder" => VIEW_PATH."vendors/codemirror-5.40.2/addon/display/placeholder.js",
            "codemirror-xml" => VIEW_PATH."vendors/codemirror-5.40.2/mode/xml/xml.js",
            "codemirror-htmlmixed" => VIEW_PATH."vendors/codemirror-5.40.2/mode/htmlmixed/htmlmixed.js",
            "codemirror-css" => VIEW_PATH."vendors/codemirror-5.40.2/mode/css/css.js",
            "codemirror-javascript" => VIEW_PATH."vendors/codemirror-5.40.2/mode/javascript/javascript.js",

            "sandbox" => VIEW_PATH."js/sandbox.js",
            "iframe" => VIEW_PATH."js/iframe.js",
            "editors" => VIEW_PATH."js/editors.js",
        ]
    ];

    public function __construct(){
        $this->model = new CodeModel();
    }

    private function CreateFile($file_name, $template, $title, $css, $html, $js, $temp = false) {
        $template_content = file_get_contents(SANDBOX_PATH . "templates" . DSP . "{$template}.html");
        $template_content = str_replace(['_title_', '_css_', '_html_', '_js_'], [$title, $css, $html, $js], $template_content);

        $file = fopen(SANDBOX_PATH . ($temp ? "temp" . DSP : $_SESSION['user']['name'] . DSP ) . $file_name, 'w');
        fwrite($file, html_entity_decode($template_content, ENT_QUOTES));
        fclose($file);
    }

    public function Blank($template = "metro4"){
        $tpl = $this->model->Template($template);
        $temp_file_name = uniqid("m4-sandbox-").".html";
        $code = $this->model->Code(-1);

        $code['html'] = $tpl['html'];
        $code['css'] = $tpl['css'];
        $code['js'] = $tpl['js'];
        $code['iframe'] = "//".$_SERVER['HTTP_HOST']."/Sandbox/temp/".$temp_file_name;
        $code['temp_file'] = $temp_file_name;
        $code['saved'] = 0;
        $code['template_id'] = $tpl['id'];
        $code['template_icon'] = $tpl['icon'];
        $code['template_libs'] = $tpl['libs'];
        $code['template_name'] = $tpl['name'];
        $code['template_html'] = $tpl['html'];
        $code['template_css'] = $tpl['css'];
        $code['template_js'] = $tpl['js'];
        $code['template_title'] = $tpl['title'];

        $_SESSION['temp_file'] = $temp_file_name;

        $this->CreateFile($temp_file_name, $template, $code['title'], $code['css'], $code['html'], $code['js'], true);

        $this->model->AddTempFile($temp_file_name, $_SESSION['current']);

        $params = [
            "page_title" => "Metro 4 Sandbox",
            "body_class" => "h-vh-100",
            "code" => $code,
            "head_styles" => $this->head['styles'],
            "foot_scripts" => $this->foot['scripts'],
        ];
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render("code.phtml", $params);
    }

    public function Editor($user, $hash){
        $code = $this->model->Code($hash);
        $code['iframe'] = "//".$_SERVER['HTTP_HOST']."/Sandbox/$user/$hash.html";
        $code['temp_file'] = "";
        $code['saved'] = 1;
        $params = [
            "page_title" => "Metro 4 Sandbox",
            "body_class" => "h-vh-100",
            "code" => $code,
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
        $template = $POST['template'];
        $temp_file = $POST['temp_file'];
        $hash = $POST['hash'];
        $saved = intval($POST['saved']) === 1;
        $can_save = $POST['can_save'] != "false";

        if (($can_save && !$saved) || $saved) {
            $result = $this->model->Save($id, $_SESSION['current'], $title, $html, $css, $js, $template, $hash);
            if (($can_save && !$saved)) {
                $hash_gen = new Hashids(HASH_SALT, 10);
                $hash = $hash_gen->encode($result);
                $this->model->UpdateHash($result, $hash);
                $id = $result;
            }
            unset($_SESSION['temp_file']);
            if ($temp_file !== "") unlink(SANDBOX_PATH . "temp" . DSP . $temp_file);
            if ($temp_file !== "") $this->model->DeleteTempFile($temp_file);
            $regular_file= $hash . ".html";
            $tpl = $this->model->TemplateByID($template);
            $this->CreateFile($regular_file, $tpl['name'], $title, $css, $html, $js, false);
            $this->ReturnJSON(true, "OK", [
                "mode" => "regular",
                "title" => $title,
                "id" => $id,
                "hash" => $hash,
                "temp_file" => "",
                "saved" => 1,
                "url" => "/".$_SESSION['user']['name']."/code/".$hash,
                "iframe" => "//".$_SERVER['HTTP_HOST']."/Sandbox/".$_SESSION['user']['name']."/".$regular_file
            ]);
        } else {
            $tpl = $this->model->TemplateByID($template);
            $this->CreateFile($temp_file, $tpl['name'], $title, $css, $html, $js, true);
            $this->ReturnJSON(true, "OK", [
                "mode" => "temp",
                "title" => $title,
                "iframe" => "//".$_SERVER['HTTP_HOST']."/Sandbox/temp/".$temp_file
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
        $view = new Viewer("Sandbox/".$user."/");
        echo $view->Render($hash.".html", []);
    }

}