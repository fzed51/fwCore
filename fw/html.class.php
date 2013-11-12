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
	static public function adresse(array $controlAction){
		return $adresse;
	}
	static public function link(/*string*/$libelle, /*string|array*/$href, array $options = array()){
		$defaultOptions = array(
			"raw" => false,
			"title" => ''
			);
		if( gettype($href) == 'array'){
			$href = self::adresse($href);
		}
		if( gettype($href) != 'string'){
			throw new InvalidArgumentException("la cible de <Html::link> n'est pas valide!");
		}
		return $link;
	}

}

?>