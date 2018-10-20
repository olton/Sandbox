<?php

namespace Classes;

class Viewer extends Super {
    protected $path;

    public function __construct($path = ''){
        $this->path = $path;
    }

    private function _render($_t, $_p = array()){
        extract($_p);
        ob_start();
        include($_t);
        return ob_get_clean();
    }

    /**
     * @param $_t
     * @param array $_p
     * @return string
     * @throws \Exception
     */
    public function Render($_t, $_p = array()){
        $_t = $this->path . $_t;
        if (!file_exists($_t)) throw new \Exception("Template not found" . ". File: $_t", E_USER_ERROR);
        return $this->_render($_t, $_p);
    }

    public function SetPath($path = "") {
        $this->path = $path;
        return $this;
    }
}
