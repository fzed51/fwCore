<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tools
 *
 * @author fabien.sanchez
 */
class Tools {
	static public function duplicInvertArray(array &$array){
		$iArray = array();
		foreach ($array as $k => $v) {
			$iArray[$v] = $k;
		}
		$array = array_merge($array, $iArray);
	}
	
	static public function finiPar(/*string*/$chaineSource, /*string*/$chaineRecherche){
		$longRecherche = strlen($chaineRecherche);
		if($longRecherche<=strlen($chaineSource)){
			$finIdentique = substr_compare($chaineSource, 
					$chaineRecherche, 
					-$longRecherche, 
					$longRecherche, true
				);
			if($finIdentique == 0){
				return true;
			}
		}
		return false;
	}
}
