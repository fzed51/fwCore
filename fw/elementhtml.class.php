<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of elementhtml
 *
 * @author fabien.sanchez
 */
class ElementHtml {
	
	/**
	 * formate une liste d'attributs HTML
	 * @param array $listeAttributs
	 * @return string
	 */
	protected function concatAttributs(array $listeAttributs){
		$attribut = array();
		foreach ($listeAttributs as $key => $value) {
			if(!is_bool($value)){
				if(strlen($value) > 0){
					$attribut[] = strtolower($key) . '="' . $value . '"';
				}
			} else {
				if($value){
					$attribut[] = strtolower($key);
				}
			}
		}
		return join(' ', $attribut);
	}

	protected function startElement($tag, $attrbs = array()) { 
		$attrb = $this->concatAttributs($attrbs);
		return "<$tag $attrb>";
	}
	
	protected function endElement($tag) {
		return "</$tag>";
	}

	protected function element($tag, $attrbs = array(), $content = ''){
		return $this->startElement($tag, $attrbs) 
				. $content
				. $this->endElement($tag);
	}
	
	protected function elementAutoClose($tag, $attrbs = array()){
		$attrb = $this->concatAttributs($attrbs);
		return "<$tag $attrb />";
	}
	
	/**
	 * Transforme une array en chemin 
	 * @param array $controlAction
	 * @return string
	 */
	public function ctrAction(array $controlAction){
		$adrCtrlActin = join(WS, $controlAction);
		$adresse = WEB_ROOT . $adrCtrlActin;
		return $adresse;
	}
}
