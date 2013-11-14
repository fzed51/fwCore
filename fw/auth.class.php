<?php

class Auth extends Singleton{
	
	private $_levelNeeded;
	private $_session;
	private $_data;
	private $_level;
	private $_ipAdresse;
	
	private function __Construct(){
		$this->_levelNeeded = 1;
		$this->_session = Session::getInstance();
		$this->_data = array();
		$this->_level = 0;
		$this->_ipAdresse = filter_input (INPUT_SERVER,'REMOTE_ADDR', FILTER_VALIDATE_IP);
		if(isset($this->_session['Auth'])){
			$auth = $this->_session['Auth'];
			if(isset($auth['data'], $auth['level'], $auth['ipAdresse'])){
				$this->_data = $auth['data'];
				$this->_level = $auth['level'];
				$this->_ipAdresse = $auth['ipAdresse'];
			}
		}
	}
	
	public function setLevelNeeded(/*int*/$levelNeeded){
		$this->_levelNeeded = $levelNeeded;
	}
	
	public function test(){		
		if($this->_ipAdresse == filter_input (INPUT_SERVER,'REMOTE_ADDR', FILTER_VALIDATE_IP) 
				&& $this->_level >= $this->_levelNeeded){
			return true;
		} else {
			return false;
		}
	}
	
	public function getData(){
		return $this->_data;
	}
	
}
