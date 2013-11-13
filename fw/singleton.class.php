<?php

class Singleton{
    
    static private $_instance;
    protected function __construct() {}
    static public function getInstance() {
        if(true === is_null(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
}