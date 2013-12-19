<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HomeControleur
 *
 * @author fabien.sanchez
 */
class HomeControleur extends Controleur{
	public function avantIndex() {
		$this->auth->setLevelNeeded(0);
	}
	public function index(){
		$this->titre = "Bonjour";
	}
}
