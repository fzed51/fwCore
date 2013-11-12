<?php

class Html{
	static public function adresse(array $controlAction){
		return $adresse;
	}
	static public function link(/*string*/$libelle, array $controlAction, array $options = array()){
		$options['raw']=false;
		return $link;
	}
	static public function link(/*string*/$libelle, /*string*/$href, array $options = array()){
		$defaultOptions = array(
			"raw" => false;
			"title" => '';
			);
		return $link;
	}

}

?>