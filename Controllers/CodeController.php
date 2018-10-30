<?php


namespace Controllers;


use Classes\Controller;
use Classes\Hashids;
use Classes\Url;
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

    private function CreateFile($file_name, $template, $code, $temp = false) {
        $template_content = file_get_contents(SANDBOX_PATH . "templates" . DSP . "{$template}.html");
        $template_content = str_replace(
            ['_title_', '_html_head_', '_html_classes_', '_body_classes_', '_style_', '_html_', '_js_', '_tags_', '_desc_'],
            [$code['title'], $code['html_head'], $code['html_classes'], $code['body_classes'], $code['css'], $code['html'], $code['js'], $code['tags'], $code['desc']],
            $template_content
        );

        $css_external_array = explode("\n", $code['css_external']);
        $css_external = "";
        if  (count($css_external_array) > 0) foreach ($css_external_array as $css) {
            $css_external .= "<link rel='stylesheet' href='$css'>\n";
        }
        $template_content = str_replace("_css_external_", $css_external, $template_content);

        $js_external_array = explode("\n", $code['js_external']);
        $js_external = "";
        if  (count($js_external_array) > 0) foreach ($js_external_array as $js) {
            $js_external .= "<script src='$js'></script>\n";
        }
        $template_content = str_replace("_javascript_external_", $js_external, $template_content);

        $file = null;
        $result = false;

        try {
            $file = fopen(SANDBOX_PATH . ($temp ? "temp" . DSP : $_SESSION['user']['name'] . DSP ) . $file_name, 'w');
            fwrite($file, html_entity_decode($template_content, ENT_QUOTES));
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

        $templates = $this->model->Templates();

        $tpl = $this->model->Template($template);
        $temp_file_name = uniqid($_SESSION['user']['name']."-".$tpl['name']."-").".html";
        $code = $this->model->Code(-1);

        $code['html'] = $tpl['html'];
        $code['css'] = $tpl['css'];
        $code['js'] = $tpl['js'];
        $code['temp_file'] = $temp_file_name;
        $code['saved'] = 0;
        $code['template'] = $tpl['id'];
        $code['template_id'] = $tpl['id'];
        $code['template_icon'] = $tpl['icon'];
        $code['template_libs'] = $tpl['libs'];
        $code['template_name'] = $tpl['name'];
        $code['template_html'] = $tpl['html'];
        $code['template_css'] = $tpl['css'];
        $code['template_js'] = $tpl['js'];
        $code['template_title'] = $tpl['title'];
        $code['alien'] = 0;

        $_SESSION['temp_file'] = $temp_file_name;

        $this->CreateFile($temp_file_name, $template, $code, true);
        $code['iframe'] = ($_SERVER['HTTP_HOST'] === "sandbox.local" ? "http" : "https") ."://".$_SERVER['HTTP_HOST']."/Sandbox/temp/".$temp_file_name;
        $this->model->AddTempFile($temp_file_name, $_SESSION['current']);

        $params = [
            "page_title" => "Metro 4 Sandbox",
            "body_class" => "h-vh-100",
            "code" => $code,
            "templates" => $templates,
            "head_styles" => $this->head['styles'],
            "foot_scripts" => $this->foot['scripts'],
        ];
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render("code.phtml", $params);
    }

    public function Editor($user, $hash){

        if (!is_file(SANDBOX_PATH . "$user/$hash.html")) {
            $this->model->DeleteCode($hash);
            Url::Redirect("/");
            exit(0);
        }

        $alien = $_SESSION['user']['name'] != $user ? 1 : 0;

        $templates = $this->model->Templates();

        $code = $this->model->Code($hash);

        if ($code === false) {
            @unlink(SANDBOX_PATH . "$user/$hash.html");
            Url::Redirect("/");
            exit(0);
        }

        $code['iframe'] = "//".$_SERVER['HTTP_HOST']."/Sandbox/$user/$hash.html";
        $code['temp_file'] = "";
        $code['saved'] = 1;
        $code['alien'] = $alien;
        $params = [
            "page_title" => "Metro 4 Sandbox",
            "body_class" => "h-vh-100",
            "code" => $code,
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
        $js_external = $POST['js_external'];
        $alien = intval($POST['alien']) === 1;

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
                "hash" => "new",
                "template" => $template,
                "html_head" => $html_head,
                "html_processor" => $html_processor,
                "html_classes" => $html_classes,
                "body_classes" => $body_classes,
                "css_processor" => $css_processor,
                "css_external" => $css_external,
                "js_processor" => $js_processor,
                "js_external" => $js_external,
                "desc" => $desc,
                "tags" => $tags,
                "code_type" => $code_type
            ];
            if ($temp_file == '') {
                $temp_file = uniqid($_SESSION['user']['name']."-".$tpl['name']."-").".html";
            }
            $this->CreateFile($temp_file, $tpl['name'], $code, true);
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
                $template,
                $hash,
                $html_head,
                $html_processor,
                $html_classes,
                $body_classes,
                $css_processor,
                $css_external,
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
                $id = $result;
            }

            unset($_SESSION['temp_file']);
            if ($temp_file !== "") @unlink(SANDBOX_PATH . "temp" . DSP . $temp_file);
            if ($temp_file !== "") $this->model->DeleteTempFile($temp_file);
            $regular_file= $hash . ".html";
            $tpl = $this->model->TemplateByID($template);

            $code = $this->model->Code($id);

            $this->CreateFile(
                $regular_file,
                $tpl['name'],
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

        $this->ReturnJSON(true, "OK", ["redirect"=>$_SESSION['current']."/code/".$hash]);
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
}