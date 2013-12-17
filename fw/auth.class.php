<?php

class Auth {
	
	private $_levelNeeded;
	private $_session;
	private $_data;
	private $_level;
	private $_ipAdresse;
	
	protected function __Construct(){
		$this->_levelNeeded = 1;
		$this->_session = Session::getInstance();
		$this->_data = array();
		$this->_level = 0;
		$this->_ipAdresse = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE);
		if(isset($this->_session['Auth'])){
			$auth = $this->_session['Auth'];
			if(isset($auth['data'], $auth['level'], $auth['ipAdresse'])){
				$this->_data = $auth['data'];
				$this->_level = $auth['level'];
				$this->_ipAdresse = $auth['ipAdresse'];
			}
		}
	}
	static private $_instance;
	static public function getInstance() {
		if(true === is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function setLevelNeeded(/*int*/$levelNeeded){
		$this->_levelNeeded = $levelNeeded;
	}
	
	public function test(){		
		if($this->_ipAdresse == filter_var ($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) 
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
