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
	public function link(/*string*/$fichier, /*bool*/$return = false, array $options = array()){
		$defaultOptions = array(
			"charset" => '',
			"hreflang" => '',
			"type" => 'text/css',
			"rel" => '',
			"rev" => '',
			"media" => 'screen'
			);
		$href = '';
		$option = array_merge($defaultOptions, $options);
		if(filter_var($fichier, FILTER_VALIDATE_URL)){
			$href = $fichier;
		}else{
			// TODO : détection du fichier css min pour le dev
		}
		if($return){
			return $link;
		} else {
			echo $link;
		}
	}

}

?>