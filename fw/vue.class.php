<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of vue
 *
 * @author Sandrine
 */
class vue {
    //put your code here
	private $html;
	private $form;
	
	public function __construct() {
		$this->html = new Html();
		$this->form = new Form();
	}
}
