<?php
/**
 * fwCore
 * @author Fabien SANCHEZ
 * @copyright 20132
 * @version 0.1
 * =========================================================================
 * fwCore est un framework léger
 * Il a été créer pour comprendre le fonctionnement d'un framework
 * =========================================================================
 * @license  
 **/

// Constantes de chemin
define('DS', SEPARATOR);
define('ROOT', dirname(__FILE__));
define('ROOT_WWW', ROOT . DS . 'www_root');
define('ROOT_APP', ROOT . DS . 'app');
define('ROOT_FWK', ROOT . DS . 'fw');
define('ROOT_MODEL', ROOT_APP . DS . 'MODEL');
define('ROOT_VUE', ROOT_APP . DS . 'vue');
define('ROOT_CONTROLEUR', ROOT_APP . DS . 'controleur');
define('ROOT_JS', ROOT_WWW . DS . 'script');
define('ROOT_CSS',     ROOT_WWW . DS . 'style');
define('ROOT_IMAGE', ROOT_WWW . DS . 'image');
?>
