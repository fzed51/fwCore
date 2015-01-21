<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelException extends Exception {
}

abstract class Model {
	
	protected $_prefix;
	protected $_table;
	protected $_config;
	protected $_pk;

	final protected function setTable($table){
		$this->_table = $pk;
		return $this;
	}
	final protected function getTable() {
		return $this->_table;
	}
	final protected function setConfig($config) {
		$this->_config = $pk;
		return $this;
	}
	final protected function getConfig() {
		return $this->_config;
	}
	final protected function setPk($pk) {
		$this->_pk = $pk;
		return $this;
	}
	final protected function getPk() {
		return $this->_pk;
	}
	final protected function setPrefix($prefix) {
		$this->_prefix = $prefix;
		return $this;
	}
	
	public function getById($id){
	}
	public function getByFiltre(array $filtre = array()){
	} 
	public function getAll(){
	}
	
}