<?php


namespace Controllers;


use Classes\Controller;
use Classes\Viewer;

class UserController extends Controller {
    public function Login(){
        $params = [
            "page_title" => "Login :: Metro 4 Sandbox",
            "foot_scripts" => [
                "sandbox" => VIEW_PATH."js/sandbox.js"
            ]
        ];
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render("login.phtml", $params);
    }

    public function SignUp(){
        $params = array(
            "page_title" => "Sign up :: Metro 4 Sandbox",
            "foot_scripts" => [
                "sandbox" => VIEW_PATH."js/sandbox.js"
            ]
        );
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render("signup.phtml", $params);
    }
}