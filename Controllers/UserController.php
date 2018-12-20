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

    public function Profile(){
        if ($_SESSION['current'] == -1) {
            Url::Redirect("/");
        }

        $user = $this->model->User($_SESSION['current']);

        $params = [
            "page_title" => "The Sandbox :: User Profile",
            "user" => $user,
            "foot_scripts" => [
                "sandbox" => VIEW_PATH."js/sandbox.js"
            ]
        ];
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render("profile.phtml", $params);
    }

    public function Login(){
        if ($_SESSION['current'] != -1) {
            Url::Redirect("/");
        }

        $params = [
            "page_title" => "The Sandbox :: Login",
            "foot_scripts" => [
                "sandbox" => VIEW_PATH."js/sandbox.js"
            ]
        ];
        $view = new Viewer(VIEW_RENDER_PATH);
        echo $view->Render("index.phtml", $params);
    }

    public function SignUp(){

        if ($_SESSION['current'] != -1) {
            Url::Redirect("/");
        }

        $params = array(
            "page_title" => "The Sandbox :: Sign Up",
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

        if (in_array(strtolower($name), ["data", "temp"])) {
            $this->ReturnJSON(false, "USERNAME exists!", [$POST]);
        }

        if ($this->model->NameExists($name)) {
            $this->ReturnJSON(false, "User name exists", ['name'=>$name]);
        }

        if ($this->model->EmailExists($email)) {
            $this->ReturnJSON(false, "Email exists", ['email'=>$email]);
        }

        if (!file_exists(SANDBOX_PATH . $name)) {
            mkdir(SANDBOX_PATH . $name, 0755, true);
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
            "email" => "anon@sandbox.org.ua"
        ];
        Url::Redirect("/");
    }

    public function ProfileProcess(){
        global $POST;

        $user_id = $_SESSION['current'];
        $name = $POST['name'];
        $email = $POST['email'];
        $password = trim($POST['password']);
        $password_2 = trim($POST['password_2']);

        if ($user_id == -1) {
            $this->ReturnJSON(false, "Auth required!", []);
        }

        $user = $this->model->User($user_id);

        if (in_array(strtolower($name), ["data", "temp"])) {
            $this->ReturnJSON(false, "USERNAME exists!", [$POST]);
        }

        if ($user['name'] !== $name) {
            if ($this->model->NameExists($name)) {
                $this->ReturnJSON(false, "USERNAME exists!", [$POST]);
            }
        }

        if ($user['email'] !== $email) {
            if ($this->model->EmailExists($email)) {
                $this->ReturnJSON(false, "EMAIL exists!", [$POST]);
            }
        }

        $this->model->Save($user_id, $name, $email);

        if ($password !== "" && $password === $password_2) {
            $this->model->SetPassword($user_id, Security::EncodePassword($password));
        }

        $this->ReturnJSON(true, "OK", []);
    }
}