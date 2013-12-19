<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of userControleur
 *
 * @author fabien.sanchez
 */
class userControleur extends Controleur{
	public function avantIndex() {
		$this->auth->setLevelNeeded(1);
	}
	public function index(){
		$this->titre = "User";
	}
	public function avantLogin() {
		$this->auth->setLevelNeeded(0);
	}
	public function login(){
		$this->titre = "User - login";
	}
}
