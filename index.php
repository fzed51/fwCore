<?php
/**
 * fwCore
 * @author Fabien SANCHEZ
 * @copyright 2013 Fabien SANCHEZ
 * @version 0.1.2
 * =========================================================================
 * fwCore est un framework léger
 * Il a été créer pour comprendre le fonctionnement d'un framework
 * =========================================================================
 * @license copyright 2013 Fabien SANCHEZ
 **/

// Constantes de chemin
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));

define('ROOT_WWW', ROOT . DS . 'www_root');
define('ROOT_APP', ROOT . DS . 'app');
define('ROOT_FWK', ROOT . DS . 'fw');

define('ROOT_MODEL',      ROOT_APP . DS . 'model');
define('ROOT_VUE',        ROOT_APP . DS . 'vue');
define('ROOT_CONTROLEUR', ROOT_APP . DS . 'controleur');

define('ROOT_JS',    ROOT_WWW . DS . 'script');
define('ROOT_CSS',   ROOT_WWW . DS . 'style');
define('ROOT_IMAGE', ROOT_WWW . DS . 'image');

define('ROOT_CONFIG', ROOT_FWK . DS . 'config');
echo "<pre>";
require(ROOT_FWK.DS.'bootstrap.php');


$c = get_defined_constants(true);
$v = get_defined_vars();
var_dump($c['user']);
var_dump($v);
echo "</pre>";
?>