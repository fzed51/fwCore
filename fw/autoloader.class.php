<?php

/**
 * AutoLoader
 * @author Fabien SANCHEZ
 * @version 1.0 
 **/

class AutoloaderException extends Exception {
}

class Autoloader {

	// constantes
	const SANS_SOUSDOSSIER = 0;
	const AVEC_SOUSDOSSIER = 1;

	private $_dossiers = array();
	private $_classes = array();
	private $_CONF_FILE = '';

	// Singleton
	static private $_instance = null;
	private function __construct() {
		$this->_CONF_FILE = ROOT_CONFIG . 'autoloader.cache.ini';
		$this->_getCache();
	}
	static public function getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	// Cache
	private function _getCache() {
		if (file_exists($this->_CONF_FILE)) {
			$conf = parse_ini_file($this->_CONF_FILE, true);
			if (isset($conf['dossiers'])) {
				foreach ($conf['dossiers'] as $k => $v) {
					$this->_dossiers[$k] = intval($v);
				}
			}
			if (isset($conf['classes'])) {
				$this->_classes = $conf['classes'];
			}
		}
	}
	private function _setCache() {
		$fCache = new SplFileObject($this->_CONF_FILE, 'w+');
		$fCache->fwrite('[dossiers]' . PHP_EOL . PHP_EOL);
		foreach ($this->_dossiers as $k => $v) {
			$fCache->fwrite("$k=$v" . PHP_EOL);
		}
		$fCache->fwrite(PHP_EOL . '[classes]' . PHP_EOL . PHP_EOL);
		foreach ($this->_classes as $k => $v) {
			$fCache->fwrite("$k=\"$v\"" . PHP_EOL);
		}
	}

	// Scan des dossier
	private function _scanDossier() {
		foreach ($this->_dossiers as $dossier => $tScan) {
			$this->_scanFichier($dossier, $tScan);
		}
		$this->_setCache();
	}
	private function _scanFichier( /*string*/ $dossier, /*int*/ $typeScan = self::SANS_SOUSDOSSIER) {
		switch ($typeScan) {
			case self::AVEC_SOUSDOSSIER:
				$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dossier));
				break;
			case self::SANS_SOUSDOSSIER:
				$dir = new DirectoryIterator($dossier);
				break;
			default:
		}
		foreach ($dir as $item) {
			if ($item->isFile()) {
				if (preg_match('/\.class\.php$/', $item->getFilename())) {
					$this->_scanClass($dossier . $item->getFilename());
				}
			}
		}
	}
	private function _scanClass( /*string*/ $fichier) {
		$tokens = token_get_all(file_get_contents($fichier, false));
		$tokens = array_filter($tokens, 'is_array');

		$classDetect = false;
		foreach ($tokens as $token) {
			if ($token[0] === T_INTERFACE || $token[0] === T_CLASS) {
				$classDetect = true;
			} elseif ($classDetect && $token[0] === T_STRING) {
				$this->_classes[strtolower($token[1])] = $fichier;
				$classDetect = false;
			}
		}
	}

	public function addDir( /*string*/ $dossier, /*int*/ $typeScan) {
		$NON = 0;
		$PARTIEL = 1;
		$TOTAL = 2;
		$refactor = $NON;
		$dossier = (substr($dossier, -1))?$dossier:$dossier.DS;

		if (is_dir($dossier)) {
			if (is_readable($dossier)) {
				if (isset($this->_dossiers[$dossier])) {
					if ($this->_dossiers[$dossier] != $typeScan) {
						$this->_dossiers[$dossier] = $typeScan;
						$refactor = $TOTAL;
					}
				} else {
					$this->_dossiers[$dossier] = $typeScan;
					$refactor = $PARTIEL;
				}
				switch ($refactor) {
					case $TOTAL:
						$this->_scanDossier();
						break;
					case $PARTIEL:
						$this->_scanFichier($dossier, $typeScan);
						$this->_setCache();
						break;
				}
			} else {
				throw new AutoloaderException("Le dossier '$dossier' n'est pas lisible!");
			}
		} else {
			throw new AutoloaderException("Le dossier '$dossier' n'existe pas ou n'est pas un dossier!");
		}
		return $this;
	}

	// Loader
	public function load($class) {
		if (!class_exists($class)) {
			if (!isset($this->_classes[strtolower($class)])) {
				$this->_scanDossier();
			}
			if (isset($this->_classes[strtolower($class)])) {
				require ($this->_classes[strtolower($class)]);
				return true;
			} else {
				throw new AutoloaderException("La class &lt;$class&gt; ne peut pas être chargée! ");
			}
			return false;
		}
	}
	
	public function start(){
		spl_autoload_register(array($this, 'load'));
	}
        
}