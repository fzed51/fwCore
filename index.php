<?php
/**
 * fwCore
 * @author Fabien SANCHEZ
 * @version 0.3.1
 * @license copyright 2013 Fabien SANCHEZ
 * ==========================================================================
 * fwCore est un framework léger
 * Il a été créer pour comprendre le fonctionnement d'un framework
 * ==========================================================================
 **/

// Constantes de chemin
define('DS', DIRECTORY_SEPARATOR);
define('WS', '/');
define('ROOT', dirname(__FILE__) . DS);
define('WEB_ROOT', '' . WS);

define('ROOT_WWW', ROOT . 'www_root' . DS);
define('ROOT_APP', ROOT . 'app' . DS);
define('ROOT_FWK', ROOT . 'fw' . DS);
define('ROOT_LOG', ROOT . 'log' . DS);

define('ROOT_MODEL',      ROOT_APP . 'model' . DS);
define('ROOT_VUE',        ROOT_APP . 'vue' . DS);
define('ROOT_CONTROLEUR', ROOT_APP . 'controleur' . DS);
define('ROOT_CONFIG',     ROOT_APP . 'config' . DS);

define('ROOT_JS',    ROOT_WWW . 'script' . DS);
define('WEB_JS',     WEB_ROOT . 'script' . WS);
define('ROOT_CSS',   ROOT_WWW . 'style' . DS);
define('WEB_CSS',    WEB_ROOT . 'style' . WS);
define('ROOT_IMAGE', ROOT_WWW . 'image' . DS);
define('WEB_IMAGE',  WEB_ROOT . 'image' . WS);

require(ROOT_FWK.'bootstrap.php');