<?php


namespace Controllers;


use Classes\Controller;
use Classes\Viewer;

class DefaultController extends Controller {
    public function Index(){
        $params = [
            "page_title" => "Metro 4 Sandbox",
            "foot_scripts" => [
                "sandbox" => VIEW_PATH."js/sandbox.js"
            ]
        ];
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render("index.phtml", $params);
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