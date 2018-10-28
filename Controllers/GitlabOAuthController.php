<?php


namespace Controllers;


use Classes\Controller;
use Models\UserModel;

require_once "GitlabOAuthConfig.php";

class GitlabOAuthController extends Controller {

    private $authorizeURL = 'https://gitlab.com/oauth/authorize';
    private $tokenURL = 'https://gitlab.com/oauth/token';
    private $apiURLBase = 'https://gitlab.com/api/v4/';
    private $clientID = GITLAB_OAUTH_CLIENT_ID;
    private $clientSecret = GITLAB_OAUTH_CLIENT_SECRET;
    private $protocol;
    private $return_url;

    public function __construct(){
        $this->protocol = $_SERVER['SERVER_NAME'] == 'sandbox.local' ? 'http://' : 'https://';
        $this->return_url = $this->protocol . $_SERVER['SERVER_NAME'] . "/oauth/gitlab/return";
    }

    private function apiRequest($url, $post=FALSE, $headers=array()) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if($post) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        $headers[] = 'Accept: application/json';
        $headers[] = 'User-Agent: Metro 4 Sandbox';
//        if($_SESSION['access_token']) $headers[] = 'Authorization: Bearer ' . $_SESSION['access_token'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        return json_decode($response);
    }

    public function Login(){
        $_SESSION['state'] = hash('sha256', microtime(TRUE).rand().$_SERVER['REMOTE_ADDR']);
        unset($_SESSION['access_token']);
        $params = array(
            'client_id' => $this->clientID,
            'redirect_uri' => $this->return_url,
            'response_type' => "code",
            'scope' => 'read_user',
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
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->return_url,
            'code' => $code
        ));
        $_SESSION['access_token'] = $token->access_token;

        $user = $this->User();
        if (!$user) {
            header('Location: ' . "/login");
            exit(0);
        }

        $user_model = new UserModel();
        $user_data = $user_model->UserByName($user->username);

        if (!$user_data) {
            $id = $user_model->Save(-1, $user->username, $user->email, "gitlab");
            $user_data = $user_model->User($id);
        }

        $_SESSION['current'] = $user_data['id'];
        $_SESSION['user']['name'] = $user_data['name'];
        $_SESSION['user']['email'] = $user_data['email'];
        $_SESSION['user']['oauth'] = 'gitlab';

        header('Location: ' . "/");
    }

    private function User(){
        if (!isset($_SESSION['access_token'])) {
            return false;
        }
        $url = $this->apiURLBase . "user?access_token=".$_SESSION['access_token'];
        $user = $this->apiRequest($url);
        return $user;
    }
}