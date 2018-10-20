<?php

namespace Classes;

class Super {
    protected $_items;

    public function __set($name, $value){
        $this->_items[$name] = $value;
    }

    public function __get($name) {
        return isset($this->_items[$name]) ? $this->_items[$name] : NULL;
    }
}
