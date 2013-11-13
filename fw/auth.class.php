<?php

class Auth extends Singleton{
	
	private $_levelNeeded;
	private $_session;
	
	public function setLevelNeeded(/*int*/$levelNeeded){
		$this->_levelNeeded = $levelNeeded;
	}

	private function __Construct(){
		$this->_levelNeeded = 1;
		$this->_session = Session::getInstance();
	}
}
