<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of form
 *
 * @author fabien.sanchez
 */
class form extends ElementHtml{
	
	private $data;
		
	/**
	 * Ouvre la balise form
	 * @param string $action
	 * @param array $attributs
	 * @param string $id
	 * @return string
	 */
	public function startForm($action, array $attributs = array(), /*string*/$id = ''){
		$defaultAttrbs = array(
			'accept-charset' => '',
					// ISO-8859-1
					// ISO-8859-15
					// UTF-8
			'autocomplete' => '', // on | off
			'enctype' => '',
					// application/x-www-form-urlencoded
					// multipart/form-data
					// text/plain
			'method' => 'POST', // GET | POST
			'name' => '',
			'novalidate' => false,
			'target' => ''
					// _blank
					// _self
					// _parent
					// _top
		);
		if(isset($attributs['data'])){
			$this->_daraForm = $attributs['data'];
			unset($attributs['data']);
		}
		if(strlen($id)>0 && !isset($attributs['name'])){
			$attributs['name'] = $id;
		}
		$attributs = array_merge($defaultAttrbs,$attributs,array(
					'action' => $action,
					'id' => $id
				));
		return $this->startElement('form', $attributs);
	}
	
	/**
	 * Ferme la balise form
	 * @return string
	 */
	public function endForm(){
		$this->_daraForm = array();
		return $this->endElement('form');
	}
	
	/**
	 * Génère un tag label
	 * @param string $libelle
	 * @param array $attributs
	 * @return string
	 */
	public function label(/*string*/$libelle, array $attributs = array()){
		$label = '';
		return $label;
	}
	
	/**
	 * Génère un tag input
	 * @param string $id
	 * @param string $type
	 * @param array $attributs
	 * @return string
	 */
	public function input(/*string*/$id,/*string*/$type = 'text',  array $attributs = array()) {
		if(!isset($attributs['value']) && isset($this->data['id'])){
			$attributs['value'] = $this->data['id'];
		}
		$label = '';
		if(isset($attributs['label'])){
			$label = $attributs['label'];
			unset($attributs['label']);
		}
		$input = '';
		return $input;
	}
	
}
