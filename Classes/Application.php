<?php

namespace Classes;


class Application {
    private $router;
    private $config;

    public function __construct(/*array*/ $config)
    {
        $this->config = $config;

        if (isset($config['database'])) {
            $GLOBALS['database']['provider'] = DBProvider::GetDriver($config['database'], $config['database']['provider']);
        } else {
            throw new \Exception("DB is not defined", E_USER_ERROR);
        }

        if (isset($config['routes'])) {
            $defaults = isset($config['defaults']) ? $config['defaults'] : array();
            $this->router = new Router($config['routes'], $defaults);
        } else {
            throw new \Exception("Routing is not defined", E_USER_ERROR);
        }
    }

    public function Run($hooks = array())
    {

        if (!empty($hooks) && !empty($hooks['preprocess'])) {
            foreach($hooks['preprocess'] as $process){
                require_once($process);
            }
        }

        $this->router->Run($this->config['controller']);

        if (!empty($hooks) && !empty($hooks['postprocess'])) {
            foreach($hooks['postprocess'] as $process){
                require_once($process);
            }
        }
    }

    public function GetConfig()
    {
        return $this->config;
    }
}