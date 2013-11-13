<?php
/**
 * Description of session
 *
 * @author fabien.sanchez
 */ 
class Session extends singleton implements ArrayAccess, Countable{

	private function __Construct(){
		session_start();
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
