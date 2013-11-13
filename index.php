<?php
/**
 * fwCore
 * @author Fabien SANCHEZ
 * @version 0.1.2
 * @license copyright 2013 Fabien SANCHEZ
 * =========================================================================
 * fwCore est un framework léger
 * Il a été créer pour comprendre le fonctionnement d'un framework
 * =========================================================================
 **/

// Constantes de chemin
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__) . DS);

define('ROOT_WWW', ROOT . 'www_root' . DS);
define('ROOT_APP', ROOT . 'app' . DS);
define('ROOT_FWK', ROOT . 'fw' . DS);
define('ROOT_LOG', ROOT . 'log' . DS);

define('ROOT_MODEL',      ROOT_APP . 'model' . DS);
define('ROOT_VUE',        ROOT_APP . 'vue' . DS);
define('ROOT_CONTROLEUR', ROOT_APP . 'controleur' . DS);

define('ROOT_JS',    ROOT_WWW . 'script' . DS);
define('ROOT_CSS',   ROOT_WWW . 'style' . DS);
define('ROOT_IMAGE', ROOT_WWW . 'image' . DS);

define('ROOT_CONFIG', ROOT_FWK . 'config' . DS);
echo "<pre>";
require(ROOT_FWK.'bootstrap.php');


$c = get_defined_constants(true);
$v = get_defined_vars();
var_dump($c['user']);
var_dump($v);
echo "</pre>";