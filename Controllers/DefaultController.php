<?php


namespace Controllers;


use Classes\Controller;
use Classes\Viewer;
use Models\CodeModel;
use Models\UserModel;

class DefaultController extends Controller {

    private $code_model;
    private $user_model;

    public function __construct() {
        $this->code_model = new CodeModel();
        $this->user_model = new UserModel();
    }

    public function Index(){

        if ($_SESSION['current'] == -1) {
            $params = [
                "page_title" => "The Sandbox",
                "foot_scripts" => [
                    "sandbox" => VIEW_PATH . "js/sandbox.js"
                ]
            ];
            $page = "index.phtml";
        } else {

            $top_templates = $this->code_model->Templates("is_top=1");
            $templates = $this->code_model->Templates("is_top!=1");
            $codes = $this->code_model->List($_SESSION['current']);

            $params = [
                "page_title" => "Dashboard :: The Sandbox",
                "top_templates" => $top_templates,
                "templates" => $templates,
                "codes" => $codes,
                "foot_scripts" => [
                    "sandbox" => VIEW_PATH . "js/sandbox.js"
                ]
            ];
            $page = "dashboard.phtml";
        }
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render($page, $params);
    }

    public function PageNotFound(){

        $templates = $this->code_model->Templates();

        $params = array(
            "page_title" => "404 - Page not found!",
            "templates" => $templates,
            "foot_scripts" => [
                "sandbox" => VIEW_PATH."js/sandbox.js"
            ]
        );
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render("404.phtml", $params);
    }
}