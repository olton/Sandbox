<?php


namespace Controllers;


use Classes\Controller;
use Classes\Security;
use Classes\Url;
use Classes\Viewer;
use Models\UserModel;

class UserController extends Controller {

    private $model;

    public function __construct(){
        $this->model = new UserModel();
    }

    public function Login(){

        if ($_SESSION['current'] != -1) {
            Url::Redirect("/");
        }

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

        if ($_SESSION['current'] != -1) {
            Url::Redirect("/");
        }

        $params = array(
            "page_title" => "Sign up :: Metro 4 Sandbox",
            "foot_scripts" => [
                "sandbox" => VIEW_PATH."js/sandbox.js"
            ]
        );
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render("signup.phtml", $params);
    }

    public function LoginProcess(){
        global $POST;
        $name = $POST['name'];
        $pass = Security::EncodePassword($POST['password']);
        $result = $this->model->CheckAuth($name, $pass);
        if ($result === false) {
            $this->ReturnJSON(false, "Auth error!", ["user"=>$POST['name'], "pass"=>$POST['password']]);
        } else {
            $user = $this->model->User($result);
            $this->model->Logged($result);
            $_SESSION['current'] = $result;
            $_SESSION['user'] = $user;
            $this->ReturnJSON(true, "OK", $user);
        }
    }

    public function SignUpProcess(){
        global $POST;

        $name = $POST['name'];
        $email = $POST['email'];
        $pass = Security::EncodePassword($POST['password']);

        if ($this->model->NameExists($name)) {
            $this->ReturnJSON(false, "User name exists", ['name'=>$name]);
        }

        if ($this->model->EmailExists($email)) {
            $this->ReturnJSON(false, "Email exists", ['email'=>$email]);
        }

        $result = $this->model->Save(-1, $name, $email);
        $this->model->SetPassword($result, $pass);
        $this->model->Logged($result);
        $user = $this->model->User($result);

        $this->ReturnJSON(true, "OK", $user);
    }

    public function LogoutProcess(){
        $_SESSION['current'] = -1;
        $_SESSION['user'] = [
            "name" => "anon",
            "email" => "anon@sandbox.metroui.org.ua"
        ];
        Url::Redirect("/");
    }
}