<?php

/**
 * Html
 * Helper html pour HTML5
 * 
 * @package fwCore
 */

class Html{
	
	private $_stock_css = array();
	private $_stock_js = array();
	
	public function adresse(array $controlAction){
		return $adresse;
	}
	
	protected function concatAttributs($listeAttributs){
		$attribut = array();
		foreach ($listeAttributs as $key => $value) {
			if(!is_bool($value)){
				if(strlen($value)){
					$attribut[] = strtolower($key) . '"' . $value . '"';
				}
			} else {
				if($value){
					$attribut[] = strtolower($key);
				}
			}
		}
		return join(' ', $attribut);
	}


	protected function element($tag, $attrbs = array(), $content = ''){
		$attrb = $this->concatAttributs($attrbs);
		return "<$tag $attrb>$content</$tag>";
	}
	
	protected function elementAutoClose($tag, $attrbs = array()){
		$attrb = $this->concatAttributs($attrbs);
		return "<$tag $attrb />";
	}
	
	/**
	 * function Link
	 *   
	 * @param string $fichier
	 * @param bool $return
	 * @param array $options
	 * @return null/string
	 */
	public function link(/*string*/$href, array $options = array()){
		$defaultOptions = array(
			"hreflang" => '',
			"type" => '',
			"rel" => '',
			"media" => '',
			"size" => ''
			);
		$option = array_merge($defaultOptions, $options);
		$attributs = array_merge(array('href'=>$href), $option);
		$link = $this->elementAutoClose('link', $attributs);
		return $link;
	}
	
	/**
	 * fonction css
	 * génère un lien avec une feuille de stye Externe;
	 * 
	 * @param type $fichier
	 * @param type $return
	 * @param type $attributs
	 * @return null
	 */
	public function css(/*string*/$fichier, $return = true, $attributs = array()){
		$defaultAttrbs = array(
			'rel' => 'stylesheet',
			'type' => 'text/css',
			'media' => 'screen'
		);
		$attributs = array_merge($defaultAttrbs, $attributs);
		$href = '';
		if(! filter_var($fichier, FILTER_VALIDATE_URL)){
			// TODO : détection du fichier css min pour le dev
		}
		$link = $this->link($href, $defaultAttrbs);
		if($return){
			return $link;
		}else{
			$this->_stock_css[] = $link;
			return null;
		}
	}

}