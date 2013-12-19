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
	private $error;
		
	/**
	 * Ouvre la balise form
	 * @param string $action
	 * @param array $attributs
	 * @param string $id
	 * @return string
	 */
	public function start($action, array $attributs = array(), /*string*/$id = ''){
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
		if(is_array($action)){
			$action = $this->ctrAction($action);
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
	public function end(){
		$this->_daraForm = array();
		return $this->endElement('form');
	}
	
	/**
	 * Initialise les données du formulaire
	 * @param array $data
	 * @return \form
	 */
	public function setData(array $data) {
		$this->data = $data;
		return $this;
	}
	/**
	 * Initialise les messages d'erreurs du formulaire
	 * @param array $error
	 * @return \form
	 */
	public function setError(array $error) {
		$this->error = $error;
		return $this;
	}
	
	/**
	 * Retourne le message d'erreur d'un champ dans un span
	 * @param string $field
	 * @return string
	 */
	protected function getError(/*string*/$field){
		if(isset($this->error[$field])){
			return $this->element(
					'span', 
					array('class'=>'error'), 
					$this->error[$field]
				);
		}
		return '';
	}

	/**
	 * Génère un tag label
	 * @param string $libelle
	 * @param array $attributs
	 * @return string
	 */
	public function label(/*string*/$libelle, array $attributs = array()){
		$label = $this->element('label', $attributs, $libelle);
		return $label;
	}
	
	/**
	 * Génère un tag input
	 * @param string $field
	 * @param string $label
	 * @param array $attributs
	 * @return string
	 */
	public function input(/*string*/ $field, /*string*/ $label = null,  array $attributs = array()){
		$defautAttibuts = array(
				'autocomplete' => '',
						// on
						// off
				'autofocus' => false,
				'disabled' => false,
				'list' => '',
					// id from data_list tag
				'max' => '',
					// max pour type date et number
				'min' => '',
					// min pour type date et number
				'step' => '',
					// min pour type number
				'type' => 'text',
					// button cf : $this->button
					// checkbox
					// color
					// date 
					// datetime 
					// datetime-local 
					// email 
					// file cf : $this->file
					// hidden cf : $this->hidden
					// image cf : $this->image
					// month 
					// number 
					// password
					// radio
					// range
					// reset cf : $this->reset
					// search
					// submit cf : $this->submit
					// tel
					// text
					// time 
					// url
					// week
				'value' => '',
				'pattern' => '',
				'placeholder' => '',
				'readonly' => false,
				'required' => false,
				'maxlength' => '',
				'size' => ''
			);
		if(!isset($attributs['value']) && isset($this->data[$field])){
			$attributs['value'] = $this->data[$field];
		}
		$attributs = array_merge($defautAttibuts, $attributs);
		$input = '';
		if(is_null($label)){
			if(!isset($attributs['id'])){
				$attributs['id'] = 'form' . ucfirst($field);
			}
			$input .= $this->label($label, array('for'=>$attributs['id']));
		}
		$input .= $this->elementAutoClose('input', $attributs);
		$input .= $this->getError($field);
		return $input;
	}
	
	public function hidden(/*string*/ $field, array $attributs = array()) {
		
	}
	public function image(/*string*/ $field, /*string*/ $label = null,  array $attributs = array()) {
		
	}
	public function file(/*string*/ $field, /*string*/ $label = null,  array $attributs = array()) {
		$defautAttibuts = array(
				'accept' => '',
						// audio/*
						// video/*
						// image/*
						// MIME_type
				'autofocus' => false,
				'disabled' => false,
				'type' => 'file',
				'multiple' => false,
				'placeholder' => '',
				'readonly' => false,
				'required' => false,
				'size' => ''
			);
		$attributs = array_merge($defautAttibuts, $attributs);
	}
	public function submit(/*string*/ $field, /*string*/ $label = null,  array $attributs = array()) {
		
	}
	public function reset(/*string*/ $field, /*string*/ $label = null,  array $attributs = array()) {
		
	}
	public function select(/*string*/ $field, /*string*/ $label = null,  array $attributs = array()) {
		
	}
}
