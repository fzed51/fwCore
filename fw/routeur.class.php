<?php

/**
 * Description of Routeur
 *
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
		$action = $requette->getAction();
		$action = ucfirst(strtolower($action));
		if(class_exists($class_name, true)){
			$controleur = new $class_name();
			$controleur->executeAction($actio, $requette);
		} else {
			throw new RuntimeException("Le controleur '$ctrlNom' n'existe pas! ");
		}
	}
}
