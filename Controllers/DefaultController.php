<?php


namespace Controllers;


use Classes\Controller;
use Classes\Viewer;
use Models\CodeModel;

class DefaultController extends Controller {

    private $code_model;

    public function __construct() {
        $this->code_model = new CodeModel();
    }

    public function Index(){

        if ($_SESSION['current'] == -1) {
            $params = [
                "page_title" => "Metro 4 Sandbox",
                "foot_scripts" => [
                    "sandbox" => VIEW_PATH . "js/sandbox.js"
                ]
            ];
            $page = "index.phtml";
        } else {

            $templates = $this->code_model->Templates();
            $codes = $this->code_model->List($_SESSION['current']);

            $params = [
                "page_title" => "Dashboard :: Metro 4 Sandbox",
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
        $params = array(
            "page_title" => "404 - Page not found!",
            "foot_scripts" => [
                "sandbox" => VIEW_PATH."js/sandbox.js"
            ]
        );
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render("404.phtml", $params);
    }
}