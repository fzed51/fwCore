<?php

/**
 * bootstrap
 * Initialisation du framework
 **/

require_one('autoloader.class.php');
$autoloader = new AutoLoader();
$autoloader->addDir(ROOT_FWK);
$autoloader->addDir(ROOT_APP);
$autoloader->addDir(ROOT_MODEL);
$autoloader->addDir(ROOT_VUE);
$autoloader->addDir(ROOT_CONTROLEUR);

?>