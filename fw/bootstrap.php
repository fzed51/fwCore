<?php

/**
 * bootstrap
 * Initialisation du framework
 * @author Fabien SANCHEZ
 * @version 0.1
 **/

require_once('autoloader.class.php');
$autoloader = AutoLoader::init();
$autoloader->addDir(ROOT_FWK, Autoloader::SANS_SOUSDOSSIER)
	->addDir(ROOT_MODEL, Autoloader::SANS_SOUSDOSSIER)
	->addDir(ROOT_VUE, Autoloader::AVEC_SOUSDOSSIER)
	->addDir(ROOT_CONTROLEUR, Autoloader::SANS_SOUSDOSSIER)
	->start();