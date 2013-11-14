<?php
/**
 * Description of session
 *
 * @author fabien.sanchez
 */ 
class Session implements ArrayAccess, Countable{

	protected function __Construct(){
		session_start();
	}
	static private $_instance;
    static public function getInstance() {
        if(true === is_null(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	public function offsetExists ($index) {
		return isset($_SESSION[$index]);
	}

	public function offsetGet ($index) {
		return $_SESSION[$index];
	}

	public function offsetSet ($index, $newval) {
		$_SESSION[$index] = $newval;
	}

	public function offsetUnset ($index) {
		unset($_SESSION[$index]);
	}

	public function count () {
		return count($_SESSION);
	}

	public function raz(){
		$_SESSION = array();
	}

	public function get($index, $default = null){
		if(isset($_SESSION[$index])){
			return $_SESSION[$index];
		}else{
			return $default;
		}
	}

}
