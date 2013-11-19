<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of requette
 *
 * @author fabien.sanchez
 */
class requette {
	
	const DEFAUT_CONTROLEUR = 'home';
	const DEFAUT_ACTION = 'index';
	const DEFAUT_ACCES = 'PGC';
	
	private $_acces;
	private $_data = array();
			
	public function __construct() {
		$this->acces = self::DEFAUT_ACCES;
		$this->_data = array(
			'post'   => (isset($_POST))  ?$_POST  :array(),
			'get'    => (isset($_GET))   ?$_GET   :array(),
			'cookie' => (isset($_COOKIE))?$_COOKIE:array()
		);
	}
	
	public function __get($name) {
		for ($i = 0; $i < strlen($this->_acces); $i++) {
			switch (strtoupper( $this->_acces[$i])) {
				case 'P':
					$cherche = 'post';
					break;
				case 'G':
					$cherche = 'get';
					break;
				case 'C':
					$cherche = 'cookie';
					break;
			}
			if(isset($this->_data[$cherche][$name])){
				return $this->_data[$cherche][$name];
			}
		}
		return null;
	}
	
	public function __isset($name) {
		for ($i = 0; $i < strlen($this->_acces); $i++) {
			switch (strtoupper( $this->_acces[$i])) {
				case 'P':
					$cherche = 'post';
					break;
				case 'G':
					$cherche = 'get';
					break;
				case 'C':
					$cherche = 'cookie';
					break;
			}
			if(isset($this->_data[$cherche][$name])){
				return true;
			}
		}
		return false;
	}
	
}
