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
class Controleur {
    
    private $data = array();
	
	public function __construct() {
		$this->session = Session::getInstance();
		$this->auth = Auth::getInstance();
		$this->requette = null;
		$this->name = __CLASS__;
		$this->action = '';
		$this->vue = null;
	}
	
	public function __isset($key){
		return isset($this->data[$key]);
	}
	public function __get($key){
		if ($this->data[$key]){
			return $this->data[$key];
		} else {
			throw new ControleurException("La propriété '$key' n'existe pas!");
		}
		
	}
	public function __set($key, $val){
		$this->data[$key]=$val;
	}

    public function executeAction(/*string*/$action, Requette $requette){
		$this->requette = $requette;
		$action = ucfirst(strtolower($action));
		$avantAction = 'avant'.$action;
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
			$this->vue = new Vue($this->name, $action);
			$this->$action($requette);
			ob_start(ob_gzhandler);
			$this->vue->sets($this->data);
			echo $this->vue->generer();
			ob_end_flush();
		} else {
			throw new ControleurException("L'action '{$this->action}' n'existe pas dans le controleur '{$this->name}'!");
		}
    }
    
}
