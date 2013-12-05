<?php

/**
 * Log
 * @author Fabien SANCHEZ
 * @version 1.2
 */
 
class Log {

	// Fichier de sortie
	static private $_config = array();
	static private $_id = null;
	static private $_init = false;
	static private $_entete = false;
	
	// Constante de niveau
	const DEBUGFIN = 1;
	const DEBUG = 2;
	const NOTICE = 4;
	const WARNING = 8;
	const ERROR = 16;	
	static public $DIC_NIV = array ();
	
	/**
	 * MyDebug::init()
	 * 
	 * @static
	 * @return void
	 */
	 
	static public function init(array $options = array()) {
		if(!self::$_init){
			$default =	array(
							"niveau"	=> self::WARNING,
							"fichier"	=> self::_autoFileName(),
							"longid"	=> 3
						);
			self::_construitDictionnaire();
			self::$_config = array_merge($default, $options);
			self::$_id = self::_generateId();
			self::$_init = true;			
		}else {
			throw new BadMethodCallException('Le log est déjà initialisé! ');
		}
	}
	
	static private function _construitDictionnaire(){
		self::$DIV_NIV = array(
			self::DEBUGFIN => 'DEBUGFIN',
			self::DEBUG    => 'DEBUG',
			self::NOTICE   => 'NOTICE',
			self::WARNING  => 'WARNING',
			self::ERROR    => 'ERROR'
		);
		Tools::duplicInvertArray(self::$DIV_NIV);
	}
    
    private static function _autoFileName(){
    	return ROOT_LOG . 'log-' . date("Ymd") . 'txt';
    }
	
	static private function _generateId(){
		$alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$id = '';
		for ($i = 0; $i < self::$_config['longid']; $i++) {
			$id .= substr($alpha, rand(0, strlen($alpha)-1), 1);
		}
		return $id;
	}
	
	public static function trace($niveau, $message) {
		if (self::$_config['niveau'] <= $niveau) {
			if (!self::$_init) {
				self::init();
			}
			$msgOut = '';
			if (self::$_entete !== true){
				$msgOut .= self::_entete();
			}
			$msgOut .= self::_ajouteIdDebutLigne(self::$_id, $message);
			$fileOut = fopen(self::$_config['fichier'], 'a');
			if ($fileOut !== false) {
				fwrite($fileOut, trim($msgOut) . PHP_EOL);
				fclose($fileOut);
			}
		}
	}
	
	static public function traceFonction() {
		// Déclaration des variables locales
		$backtrace = debug_backtrace();
		$fonctionTest = $backtrace[1];
		$fonctionParent = "";
		$fonction = "";
		$fonctionMere = "";
		$fichier = "";
		$class = "";
		$classParent = "";
		$arguments = "";
		$out = "";
		$fileOut = "";
		// Détermination du fichier / ligne
		if (isset($fonctionTest['file']) && isset($fonctionTest['line'])) {
			$fichier = pathinfo($fonctionTest['file'], PATHINFO_BASENAME) . '[' 
					. $fonctionTest['line'] . ']';
		} else {
			$fichier = '';
		}
		// Détermination des arguments
		$arguments = self::_parametre($fonctionTest['args']);
			// Détermination de la fonction
		if (isset($fonctionTest['class']) && $fonctionTest['class'] != '') {
			$class = $fonctionTest['class'] . ':';
		} else {
			$class = '';
		}
		$fonction = $class . $fonctionTest['function'] . '(' . $arguments . ')';
		// Détermination de l'utilisation de la mémoire
		$memoire = '[' . self::_octeLisible(memory_get_usage()) . '/' 
				. self::_octeLisible(memory_get_peak_usage
			()) . ']';
		// Détermination de la fonction parent (fichier / function)
		if (isset($backtrace[2])) {
			$fonctionParent = $backtrace[2];
			if (isset($fonctionParent['class']) && $fonctionParent['class'] != '') {
				$classParent = $fonctionParent['class'] . ':';
			} else {
				$classParent = '';
			}
			$fonctionMere = $classParent . $fonctionParent['function'] . '()';
			$out = "{$fichier}> {$fonctionMere}> {$fonction}  {$memoire}\n";
		} else {
			$out = "{$fichier}> {$fonction}  {$memoire}\n";
		}
		self::trace(self::DEBUGFIN, $out);
	}
	
	static private function _octeLisible($octe) {
		// Initialisation des variables locales
		$lstUnit = array(
			'o',
			'ko',
			'Mo',
			'Go',
			'To',
			'Po',
			'Eo',
			'Zo',
			'Yo');
		$unit = 0;
		$retour = $octe;
		// Réduction à l'unité la plus importante
		while ($retour > 1024 && $unit < (count($lstUnit) - 1)) {
			$retour = $retour / 1024;
			$unit++;
		}
		// Affichage arrondi au milli�me
		return round($retour, 3) . $lstUnit[$unit];
	}
	
	static private function _parametre($lstArgs) {

		$out = "";
		$longChaine = 25; // longueur max des string

		if (!is_array($lstArgs)) $lstArgs = array($lstArgs);
		
		foreach ($lstArgs as $arg) {

			if (strlen($out) > 0) $out .= ', ';

			switch (getType($arg)) {
				case "boolean":
					if ($arg) {
						$out .= 'vrai';
					} else {
						$out .= 'faux';
					}
					break;
				case "integer":
					$out .= "$arg";
					break;
				case "double":
					if ($arg > -1 && $arg < 1) {
						$out .= round($arg, 3);
					} else {
						$out .= $arg;
					}
					break;
				case "string":
					if (strlen($arg) > $longChaine) {
						$out .= "'" . substr($arg, 0, ($longChaine - 6)) . "...'(" . strlen($arg) . ')';
					} else {
						$out .= "'$arg'";
					}
					break;
				case "array":
					$out .= 'Array(' . count($arg) . ')';
					break;
				case "object":
					$out .= 'Objet(' . get_class($arg) . '[' . count($arg) . '])';
					break;
				case "resource":
					$out .= 'Ressources';
					break;
				case "NULL":
					$out .= 'NULL';
					break;
				default:
					$out .= '?';
			}

		}

		return $out;
	}
	
	static private function _entete() {
		if(self::$_config['niveau'] <= self::WARNING){
			return '';
		}
		$maintenant = new DateTime();
		$enteteOut = $maintenant->format('\l\e j/m/Y \à H:i:s') . "\n"
			. "Utilisateur \n";
		if (isset($_SERVER['REMOTE_ADDR'])){
			$enteteOut .= "- IP         : {$_SERVER['REMOTE_ADDR']}\n";
        }elseif(isset($_SERVER['COMPUTERNAME'])){
            $enteteOut .= "- POSTE      : {$_SERVER['COMPUTERNAME']}\n";
        }
		try {
			$userInfo = array();
			if (isset($userInfo['Platform'])){
				$enteteOut .= "- OS         : {$userInfo['Platform']}\n";}
			if (isset($userInfo['Parent'])){
				$enteteOut .= "- navigateur : {$userInfo['Parent']}\n";}
		}
		catch (Exception $e) {
			$enteteOut .= "- info navigateur innaccessible!";
		}
		if (isset($_SERVER['SCRIPT_NAME'])){
			$enteteOut .= $_SERVER['SCRIPT_NAME'] . "\n";
		}
		if(self::$_config['niveau'] <= self::DEBUG){
			if (isset($_POST) && count($_POST) > 0) {
				$enteteOut .= "- [POST]" . " -> " . self::_listeVarGlob($_POST) 
						. "\n";
			}
			if (isset($_GET) && count($_GET) > 0) {
				$enteteOut .= "- [GET]" . " -> " . self::_listeVarGlob($_GET) 
						. "\n";
			}
			if (isset($_COOKIE) && count($_COOKIE) > 0) {
				$enteteOut .= "- [COOKIE]" . " -> " 
						. self::_listeVarGlob($_COOKIE) . "\n";
			}
			if (isset($_FILES) && count($_FILES) > 0) {
				$enteteOut .= "- [FILES]" . " -> " . $_FILES['name'] . '(' 
						. self::_octeLisible($_FILES['size']) . ')' . "\n";
			}
		}
		$enteteOut = self::_encadre($enteteOut);
		$enteteOut = "\n" 
				. trim(self::_ajouteIdDebutLigne(self::$ID, $enteteOut)) 
				. "\n\n";
		return $enteteOut;
	}
	
	static private function _encadre($msg, $cadre = '+-+|+-+|') {
		$msgOut = '';
		$msg = str_replace("\t", "  ", trim($msg));
		$lignes = explode("\n", $msg);
		$nbLigne = count($lignes);
		$longMax = 0;
		for ($i = 0; $i < $nbLigne; $i++){
			if (strlen($lignes[$i]) > $longMax){
				$longMax = strlen($lignes[$i]);
			}
		}
		// ligne du haut
		$msgOut = $cadre[0] . str_repeat($cadre[1],$longMax + 4) . $cadre[2] 
				. PHP_EOL;
		// autre ligne
		for ($i = 0; $i < $nbLigne; $i++) {
			$msgOut .= $cadre[7] . '  ' . $lignes[$i] 
					. str_repeat(' ', $longMax - strlen($lignes[$i])) . '  ' 
					. $cadre[3] . PHP_EOL;
		}
		// ligne du bas
		$msgOut = $cadre[4] . str_repeat($cadre[5],$longMax + 4) . $cadre[6] 
				. PHP_EOL;
		return $msgOut;
	}
	
	static private function _ajouteIdDebutLigne($Id, $msg) {
		$msgOut = '';
		$separateur = ' - ';
		$separateur2 = ' \ ';
		$msg = trim($msg);
		$lignes = explode("\n", $msg);
		$nbLigne = count($lignes);
		for ($i = 0; $i < $nbLigne; $i++) {
			if ($i > 0)
				$separateur = $separateur2;
			$msgOut .= $Id . $separateur . $lignes[$i] . "\n";
		}
		return trim($msgOut);
	}
	
	static private function _listeVarGlob($tableau, $format = -1) {

		$outLong = '';
		$outMoy = '';
		$outCourt = '';
		$lstOut = array(
			'outLong',
			'outMoy',
			'outCourt');

		foreach ($tableau as $cle => $val) {
			// virgule
			foreach ($lstOut as $out) {
				if (strlen($$out) > 0)
					$$out .= ',';
			}

			// clé
			foreach ($lstOut as $out) {
				$$out .= "[$cle]";
			}

			if (is_numeric($val)) {
				// long
				$outLong .= "={$val}";
				// moyen
				$outMoy .= sprintf("~%.3e", $val);
			} else {
				// long
				$outLong .= "='{$val}'";
				// moyen
				$outMoy .= sprintf("~'%.12s...'(%d)", $val, strlen($val));
			}

			if ($format == -1) {
				if (strlen($outLong) < 70) return $outLong;
				if (strlen($outMoy) < 70)  return $outMoy;
				return $outCourt;
			} else {
				if (isset($lstOut[$format])) return $$lstOut[$format];
				else return "";
			}

		}
	}
    
    static public function traceVar(/*mixed*/ $var, /*string*/ $name = null){
        
        $trace = '';
        if(isset($name)){
            $trace .= "$name = ";
        }
        $trace .= print_r($var, true);
        self::trace(self::DEBUG, $trace);
        
    }
    
}