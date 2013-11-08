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

	// Singleton
	static private $_instance = null;
	private function __construct() {echo __METHOD__.PHP_EOL;
		
		$this->_getCache();
	}
	static public function init() {echo __METHOD__.PHP_EOL;
		if (is_null(self::$_instance)) {
			self::$_instance = new Autoloader();
		}
		return self::$_instance;
	}

	// Cache
	private function _getCache() {echo __METHOD__.PHP_EOL;
		$conf_file = ROOT_CONFIG . DS . 'autoloader.ini';
		if (file_exists($conf_file)) {
			$conf = parse_ini_file($conf_file, true);
			if (isset($conf['dossier'])) {
				foreach ($conf['dossier'] as $k => $v) {
					$this->_dossiers[$k] = (int)$v;
				}
			}
			if (isset($conf['classes'])) {
				$this->_classes = $conf['classes'];
			}

		}
	}
	private function _setCache() {echo __METHOD__.PHP_EOL;
		$conf_file = ROOT_CONFIG . DS . 'autoloader.ini';
		$fCache = new SplFileObject($conf_file, 'w+');
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
	private function _scanDossier() {echo __METHOD__.PHP_EOL;
		foreach ($this->_dossiers as $dossier => $tScan) {
			_scanFichier($dossier, $tScan);
		}
		$this->_setCache();
	}
	private function _scanFichier( /*string*/ $dossier, /*int*/ $typeScan = self::SANS_SOUSDOSSIER) {echo __METHOD__.PHP_EOL;
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
					$this->_scanClass($dossier . DS . $item->getFilename());
				}
			}
		}
	}
	private function _scanClass( /*string*/ $fichier) {echo __METHOD__.PHP_EOL;
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

	public function addDir( /*string*/ $dossier, /*int*/ $typeScan) {echo __METHOD__.PHP_EOL;
		$NON = 0;
		$PARTIEL = 1;
		$TOTAL = 2;

		$refactor = $NON;

		if (is_dir($dossier)) {
			if (is_readable($dossier)) {
				if (isset($this->_dossiers[$dossier])) {
					if ($this->_dossiers[$dossier] != $typeScan) {
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
	}

	// Loader
	public function loader($class) {echo __METHOD__.PHP_EOL;
		if (!class_exists($class)) {
			if (!isset($this->_classes[$class])) {
				$this->_scanDossier();
			}
			if (isset($this->_classes[$class])) {
				require ($this->_classes[$class]);
				return true;
			}
			return false;
		}
	}

}