<?php


namespace Controllers;


use Classes\Controller;
use Models\UserModel;

class GithubOAuthController extends Controller {

    private $authorizeURL = 'https://github.com/login/oauth/authorize';
    private $tokenURL = 'https://github.com/login/oauth/access_token';
    private $apiURLBase = 'https://api.github.com/';
    private $clientID = "66e591bdf1bbf36247fe";
    private $clientSecret = "d4f736391a78349fd10033d6e9ed32183a6fc863";

    private function apiRequest($url, $post=FALSE, $headers=array()) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if($post) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        $headers[] = 'Accept: application/json';
        $headers[] = 'User-Agent: Metro 4 Sandbox';
        if($_SESSION['access_token']) $headers[] = 'Authorization: Bearer ' . $_SESSION['access_token'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        return json_decode($response);
    }

    public function Login(){
        $_SESSION['state'] = hash('sha256', microtime(TRUE).rand().$_SERVER['REMOTE_ADDR']);
        unset($_SESSION['access_token']);
        $params = array(
            'client_id' => $this->clientID,
            'redirect_uri' => 'http://' . $_SERVER['SERVER_NAME'] . "/oauth/github/return",
            'scope' => 'user',
            'state' => $_SESSION['state']
        );
        header('Location: ' . $this->authorizeURL . '?' . http_build_query($params));
    }

    public function Return(){
        global $GET;
        $state = isset($GET['state']) ? $GET['state'] : false;
        $code = $GET['code'];
        if (!$state || $_SESSION['state'] !== $state) {
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit(0);
        }
        $token = $this->apiRequest($this->tokenURL, array(
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => 'http://' . $_SERVER['SERVER_NAME'] . "/oauth/github/return",
            'state' => $_SESSION['state'],
            'code' => $code
        ));
        $_SESSION['access_token'] = $token->access_token;

        $user = $this->User();
        if (!$user) {
            header('Location: ' . "/login");
            exit(0);
        }

        $user_model = new UserModel();
        $user_data = $user_model->UserByName($user->login);

        if ($user_data != false && $user->email == $user_data['email']) {
            $_SESSION['current'] = $user_data['id'];
            $_SESSION['user']['name'] = $user_data['name'];
            $_SESSION['user']['email'] = $user_data['email'];
            $_SESSION['user']['oauth'] = 'github';
        }

        header('Location: ' . "/");
    }

    public function User(){
        if (!isset($_SESSION['access_token'])) {
            return false;
        }
        $url = $this->apiURLBase . "user?access_token=".$_SESSION['access_token'];
        $user = $this->apiRequest($url);
        return $user;
    }
}