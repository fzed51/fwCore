<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ElementHtml
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
		$strAttribut = array();
		foreach ($listeAttributs as $key => $value) {
			if(!is_bool($value)){
				if(strlen($value) > 0){
					$strAttribut[] = strtolower($key) . '="' . $value . '"';
				}
			} else {
				if($value){
					$strAttribut[] = strtolower($key);
				}
			}
		}
		return join(' ', $strAttribut);
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
		if(Configuration::get('engine_rewrite', 'Off') == 'On'){
			$adrCtrlActin = join(WS, $controlAction);
			$adresse = WEB_ROOT . $adrCtrlActin;
		} else {
			$adresse = WEB_ROOT . "index.php?control={$controlAction[0]}&action={$controlAction[1]}";
		}
		
		return urldecode($adresse);
	}
}
