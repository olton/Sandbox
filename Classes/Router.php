<?php

namespace Classes;

class Router extends Super {
    public $request_uri;
    public $routes;
    public $controller, $controller_name;
    public $action, $id;
    public $params;
    public $route_found = false;
    protected $default_controller = 'DefaultController';
    protected $default_action = 'PageNotFound';

    public function __construct($routes = null, $defaults = array()){
        //Debug::Dump($defaults);
        $request = $_SERVER['REQUEST_URI'];
        $pos = strpos($request, '?');
        if ($pos) $request = substr($request, 0, $pos);

        $this->request_uri = $request;
        $this->routes = array();

        if (is_array($routes) && !empty($routes)) {
            foreach($routes as $route) {
                $method = $route[0];
                $path = $route[1];
                $control = (isset($route[2]) && !empty($route[2])) ? $route[2] : array();
                $rules = (isset($route[3]) && !empty($route[3])) ? $route[3] : array();
                $this->map($path, $control, $rules, $method);
            }
        }

        if (!empty($defaults)) {
            if (isset($defaults['controller'])) $this->default_controller = $defaults['controller'];
            if (isset($defaults['action'])) $this->default_action = $defaults['action'];
        }

        $this->default_routes();
        //var_dump($this->routes);
    }

    public function map($rule, $target = array(), $conditions = array(), $method = 'ANY'){
        $this->routes[$rule] = new Route($rule, $this->request_uri, $target, $conditions, $method);
    }

    public function default_routes(){
        $this->map('/:controller');
        $this->map('/:controller/:action');
        $this->map('/:controller/:action/:id');
    }

    private function set_route($route){
        $this->route_found = true;
        $params = $route->params;
        if (isset($params['controller'])) {
            $this->controller = $params['controller'];
            unset($params['controller']);
        }
        if (isset($params['action'])) {
            $this->action = $params['action'];
            unset($params['action']);
        }
        if (isset($params['id'])) $this->id = $params['id'];
        $this->params = array_merge($params, $_GET, $_POST);

        if (empty($this->controller)) $this->controller = $this->default_controller;
        if (empty($this->action)) $this->action = $this->default_action;
        if (empty($this->id)) $this->id = null;

        $w = explode('_', $this->controller);
        foreach ($w as $k => $v) $w[$k] = ucfirst($v);
        $this->controller_name = implode('', $w);
    }

    public function execute(){
        foreach ($this->routes as $route) {
            if ($route->is_matched) {
                $this->set_route($route);
                break;
            }
        }
    }

    public function Run($controllers_path = ''){
        $this->execute();

        $controller = $controllers_path . $this->controller_name;
        $action = $this->action;
        $params = $this->params;

        if (!$this->route_found || !is_callable(array($controller, $action))) {
            $controller = $controllers_path . $this->default_controller;
            $action = $this->default_action;
            $params = array();
        }

        $controller_class = new $controller;
        call_user_func_array(array($controller_class, $action), $params);
    }
}

class Route extends Super{
    public $is_matched = false;
    public $params;
    public $url;
    public $method;
    private $conditions;

    function __construct($url, $request_uri, $target, $conditions, $method = 'ANY'){
        $this->url = $url;
        $this->params = array();
        $this->conditions = $conditions;
        $this->method = $method;
        $p_names = array();
        $p_values = array();

        preg_match_all('@:([\w]+)@', $url, $p_names, PREG_PATTERN_ORDER);

        $p_names = $p_names[0];

        $url_regex = preg_replace_callback('@:[\w]+@', array($this, 'regex_url'), $url);
        $url_regex .= '/?';

        if (preg_match('@^' . $url_regex . '$@', $request_uri, $p_values)) {
            array_shift($p_values);
            foreach ($p_names as $index => $value) $this->params[substr($value, 1)] = urldecode($p_values[$index]);
            foreach ($target as $key => $value) $this->params[$key] = $value;
            $this->is_matched = true;
        }

        if ($method != 'ANY' && $_SERVER['REQUEST_METHOD'] != $method) {
            $this->is_matched = false;
        }

        unset($p_names);
        unset($p_values);
    }

    function regex_url($matches){
        $key = str_replace(':', '', $matches[0]);
        if (array_key_exists($key, $this->conditions)) {
            return '(' . $this->conditions[$key] . ')';
        }
        else {
            return '([a-zA-Z0-9_\+\-%]+)';
        }
    }
}
