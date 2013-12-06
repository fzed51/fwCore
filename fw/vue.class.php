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
	protected $fileLayout;
	protected $fileVue;
	private $_data;
	private $_script;
	private $_style;
			
	public function __construct(/*string|array*/$fichier) {
		$this->html = new Html();
		$this->form = new Form();
		$this->setLayout('defaut');
		$this->setVue($fichier);
	}
	
	public function generer(){
		
	}
	
	public function setLayout(/*string*/ $fichier){
		$filename = ROOT_VUE . $fichier . 'phtml';
		if(file_exists($filename)){
			$this->fileLayout = $filename;
		} else {
			throw new VueException("Le layout '$fichier' n'a pas été trouvé ! ");
		}
	}
	
	public function setVue(/*string|array*/$fichier) {
		$nom = '';
		if(is_array($fichier)){
			$nom = $fichier[count($fichier)-1];
			$fichier = join(DS, $fichier);
		}else{
			$nom = $fichier;
		}
		$filename = ROOT_VUE . $fichier . 'phtml';
		if(file_exists($filename)){
			$this->fileLayout = $filename;
		} else {
			throw new VueException("La page '$nom' n'a pas été trouvée ! ");
		}
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
	
	public function addScript($script){
		$this->_script[] = $script;
	}
	public function addStyle($style){
		$this->_style[] = $style;
	}
}
