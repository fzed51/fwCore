<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ControleurException extends Exception {
}

/**
 * Description of Controleur
 *
 * @author Sandrine
 */
abstract class Controleur {
    
    private $_data = array();
	
	public function __construct() {
		$this->session = Session::getInstance();
		$this->auth = Auth::getInstance();
		$this->requette = null;
		$this->name = __CLASS__;
		$this->action = '';
		$this->vue = null;
	}
	
	public function __isset($key){
		return isset($this->_data[$key]);
	}
	public function __get($key){
		if (isset($this->_data[$key])){
			return $this->_data[$key];
		} else {
			throw new ControleurException("La propriété '$key' n'existe pas!");
		}
		
	}
	public function __set($key, $val){
		$this->_data[$key]=$val;
	}
	
	/**
	 * 
	 * @param string $name
	 */
	public function setNom($name){
		$this->name = $name;
	}

    public function executeAction(/*string*/$action, Requette $requette){
		$this->requette = $requette;
		$action = strtolower($action);
		$avantAction = 'avant'.ucfirst($action);
		if(method_exists($this, 'initAppControleur')){
			$this->initAppControleur();
		}
		if(method_exists($this, 'initControleur')){
			$this->initControleur();
		}
        if(method_exists($this, $avantAction)){
			$this->$avantAction();
		}
		if(method_exists($this, $action)){
			if($this->auth->test()){
				$this->vue = new Vue(array($this->name, $action));
				$this->$action($requette);
				ob_start('ob_gzhandler');
				$this->vue->set($this->_data);
				echo $this->vue->generer();
				ob_end_flush();
			}
		} else {
			throw new ControleurException("L'action '{$this->action}' n'existe pas dans le controleur '{$this->name}'!");
		}
    }
    
}
