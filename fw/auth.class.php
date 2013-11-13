<?php

class Auth extends Singleton{
	
	private $_levelNeeded;
	private $_session;
	private $_id;
	private $_level;
	
	private function __Construct(){
		$this->_levelNeeded = 1;
		$this->_session = Session::getInstance();
		$this->_id = 0;
		$this->_level = 0;
	}
	
	public function setLevelNeeded(/*int*/$levelNeeded){
		$this->_levelNeeded = $levelNeeded;
	}
	
	public function test(){
		if(isset($this->_session['Auth'])){
			$auth = $this->_session['Auth'];
			if(isset($auth['id'], $auth['level'])){
				$this->_id = $auth['id'];
				$this->_level = $auth['level'];
			} else {
				return false;
			}
		}		
		if($this->_level >= $this->_levelNeeded){
			return true;
		} else {
			return false;
		}
	}
	
	public function getId(){
		return $this->_id;
	}
	
}
