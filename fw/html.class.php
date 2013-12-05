<?php

/**
 * Html
 * 
 * @package fwCore
 * @author Fabien SANCHEZ
 * @copyright 2013
 * @static
 * @access public
 */

class Html{
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
	 * 
	 * <!ELEMENT link EMPTY>
	 *	<!ATTLIST link
	 *	  %attrs;
	 *	  charset     %Charset;      #IMPLIED
	 *	  href        %URI;          #IMPLIED
	 *	  hreflang    %LanguageCode; #IMPLIED
	 *	  type        %ContentType;  #IMPLIED
	 *	  rel         %LinkTypes;    #IMPLIED
	 *	  rev         %LinkTypes;    #IMPLIED
	 *	  media       %MediaDesc;    #IMPLIED
	 *	  >
	 */
	public function link(/*string*/$href, /*bool*/$return = false, array $options = array()){
		$defaultOptions = array(
			"charset" => '',
			"hreflang" => '',
			"type" => 'text/css',
			"rel" => '',
			"rev" => '',
			"media" => 'screen'
			);
		$option = array_merge($defaultOptions, $options);
		$attributs = array_merge(array('href'=>$href), $option);
		$link = $this->elementAutoClose('link', $attributs);
		if($return){
			return $link;
		} else {
			echo $link;
		}
	}
	
	public function css(/*string*/$fichier){
		if(! filter_var($fichier, FILTER_VALIDATE_URL)){
			// TODO : détection du fichier css min pour le dev
		}
	}

}

?>