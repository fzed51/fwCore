<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class VueException extends Exception {
}

interface iVue{
	public function generer();
}

/**
 * Description of vue
 *
 * @author Sandrine
 */
class Vue implements iVue {
    //put your code here
	protected $html;
	protected $form;
	private $_data;
	private $_script;
	private $_style;
			
	public function __construct() {
		$this->html = new Html();
		$this->form = new Form();
	}
	
	public function generer(){
		
	}
	
	public function set($nom, $donnée = null){
		if(is_null($donnée)){
			$this->sets($nom);
		}else{
			$this->_data[$nom] = $donnée;
		}
	}
	
	protected function sets(Array $données){
		if(is_array($données)){
			foreach ($données as $key => $value) {
				$this->set($key, $value);
			}
		}else{
			throw new VueException("Le format des données fournies à la vue ne sont pas valide ! ");
		}
	}
	
	public function addScript(){
		
	}
}
