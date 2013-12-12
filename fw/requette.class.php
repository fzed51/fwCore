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
class Requette {
	
	const DEFAUT_CONTROLEUR = 'home';
	const DEFAUT_ACTION = 'index';
	const DEFAUT_ACCES = 'PGC';
	
	private $_acces;
	private $_data = array();
			
	public function __construct() {
		$this->_acces = self::DEFAUT_ACCES;
		$this->_data = array(
			'post'   => (isset($_POST))  ?$_POST  :array(),
			'get'    => (isset($_GET))   ?$_GET   :array(),
			'cookie' => (isset($_COOKIE))?$_COOKIE:array()
		);
	}
	
	public function __get($name) {
		for ($i = 0; $i < strlen($this->_acces); $i++) {
			$cherche = $this->_getNomAccesComplet($this->_acces[$i]);
			if(isset($this->_data[$cherche][$name])){
				return $this->_data[$cherche][$name];
			}
		}
		return null;
	}
	
	public function __isset($name) {
		for ($i = 0; $i < strlen($this->_acces); $i++) {
			$cherche = $this->_getNomAccesComplet($this->_acces[$i]);
			if(isset($this->_data[$cherche][$name])){
				return true;
			}
		}
		return false;
	}
	
	private function _getNomAccesComplet(/**/ $abrev){
		$_dicCherche = array(
			'P'=> 'post',
			'G'=> 'get',
			'C'=> 'cookie',
		);
		return  $_dicCherche[strtoupper($abrev)];
	}
	
	public function setPrioriteAcces(/*string*/ $ordre){
		// TODO : Validation de l'ordre
		$this->_acces = $ordre;
	}
	
	public function getControl(){
		if(isset($this->control)){
			return $this->control;
		}else{
			return self::DEFAUT_CONTROLEUR;
		}
	}
	
	public function getAction(){
		if(isset($this->action)){
			return $this->action;
		}else{
			return self::DEFAUT_ACTION;
		}
	}
}
