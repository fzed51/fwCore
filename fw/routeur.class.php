<?php

/**
 * Description of Routeur
 * @author Sandrine
 */

class Routeur {
    public function erreur404 ($message = ''){
        header('location:');
    }
	
	public function erreur500 ($message = ''){
        header('location:');
    }
	
	public function route(Requette $requette){
		$ctrlNom = $requette->getControl();
		$class_name = ucfirst(strtolower($ctrlNom))+'Controleur';
		$action = ucfirst(strtolower($requette->getAction()));
		if(class_exists($class_name, true)){
			$controleur = new $class_name();
			$controleur->executeAction($action, $requette);
		} else {
			throw new RuntimeException("Le controleur '$ctrlNom' n'existe pas! ");
		}
	}
}
