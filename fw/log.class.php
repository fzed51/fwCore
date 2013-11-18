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
	static public $DIC_NIV = array (
		1 => 'DEBUGFIN',
		2 => 'DEBUG',
		4 => 'NOTICE',
		8 => 'WARNING',
		16=> 'ERROR',
		'DEBUGFIN' => 1,
		'DEBUG'    => 2,
		'NOTICE'   => 4,
		'WARNING'  => 8,
		'ERROR'    => 16
	);
	
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
			self::$_config = array_merge($default, $options);
			self::$_id = self::_generateId();
			self::$_init = true;			
		}else {
			throw new BadMethodCallException('Le log est déjà initialisé! ');
		}
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
		$msgOut = '';
		if (self::$_config['niveau'] <= $niveau) {
			if (self::$_init !== true) {
				self::init();
			}
			if (self::$_entete !== true){
				$msgOut .= self::entete();
			}
			$msgOut .= self::ajouteIdDebutLigne(self::$_id, $message);
			$msgOut = trim($msgOut);
			$msgOut .= "\n";
			$fileOut = fopen(self::$_config['fichier'], 'a');
			if ($fileOut !== false) {
				fwrite($fileOut, $msgOut);
				fclose($fileOut);
			}
		}
	}
	
	/**
	 * MyDebug::traceFonction()
	 * 
	 * @static
	 * @access public
	 * @return void
	 */
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
			$fichier = pathinfo($fonctionTest['file'], PATHINFO_BASENAME) . '[' . $fonctionTest['line'] . ']';
		} else {
			$fichier = '';
		}
		// Détermination des arguments
		$arguments = self::parametre($fonctionTest['args']);
			// Détermination de la fonction
		if (isset($fonctionTest['class']) && $fonctionTest['class'] != '') {
			$class = $fonctionTest['class'] . ':';
		} else {
			$class = '';
		}
		$fonction = $class . $fonctionTest['function'] . '(' . $arguments . ')';
		// Détermination de l'utilisation de la mémoire
		$memoire = '[' . self::octeLisible(memory_get_usage()) . '/' . self::octeLisible(memory_get_peak_usage
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

	/**
	 * MyDebug::octeLisible()
	 * 
	 * @static
	 * @access public
	 * @param entier $octe
	 * @return chaine
	 */
	public static function octeLisible($octe) {
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

	/**
	 * MyDebug::parametre()
	 * 
	 * @static
	 * @access public
	 * @param tableau/mix $lstArgs
	 * @return chaine
	 */
	public static function parametre($lstArgs) {

		$out = "";
		$longChaine = 25; // longueur max des string

		if (!is_array($lstArgs))
			$lstArgs = array($lstArgs);

		foreach ($lstArgs as $arg) {

			if (strlen($out) > 0)
				$out .= ', ';

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

	/**
	 * MyDebug::entete()
	 * 
	 * @static
	 * @access privé
	 * @return string
	 */
	private static function entete() {
		$enteteOut = "";
		$maintenant = new DateTime();
		
		$enteteOut .= $maintenant->format('\l\e j/m/Y \à H:i:s') . "\n";
		$enteteOut .= "Utilisateur \n";
		if (isset($_SERVER['REMOTE_ADDR'])){
			$enteteOut .= "- IP         : {$_SERVER['REMOTE_ADDR']}\n";
        }elseif(isset($_SERVER['COMPUTERNAME'])){
            $enteteOut .= "- POSTE      : {$_SERVER['COMPUTERNAME']}\n";
        }
		try {
			$userInfo = array();
			if (isset($userInfo['Platform']))
				$enteteOut .= "- OS         : {$userInfo['Platform']}\n";
			if (isset($userInfo['Parent']))
				$enteteOut .= "- navigateur : {$userInfo['Parent']}\n";
		}
		catch (exception $excep) {
			$enteteOut .= "- info navigateur innaccessible!";
		}
		if (isset($_SERVER['SCRIPT_NAME']))
			$enteteOut .= $_SERVER['SCRIPT_NAME'] . "\n";
		if (isset($_POST) && count($_POST) > 0) {
			$enteteOut .= "- [POST]";
			$enteteOut .= " -> " . self::listeVarGlob($_POST);
			$enteteOut .= "\n";
		}
		if (isset($_GET) && count($_GET) > 0) {
			$enteteOut .= "- [GET]";
			$enteteOut .= " -> " . self::listeVarGlob($_GET);
			$enteteOut .= "\n";
		}
		if (isset($_FILES) && count($_COOKIE) > 0) {
			$enteteOut .= "- [COOKIE]";
			$enteteOut .= " -> " . self::listeVarGlob($_COOKIE);
			$enteteOut .= "\n";
		}
		if (isset($_FILES) && count($_FILES) > 0) {
			$enteteOut .= "- [FILES]";
			$enteteOut .= " -> " . $_FILES['name'] . '(' . self::octeLisible($_FILES['size']) . ')';
			$enteteOut .= "\n";
		}

		$enteteOut = self::encadre($enteteOut);
		$enteteOut = "\n" . self::ajouteIdDebutLigne(self::$ID, $enteteOut) . "\n\n";

		return $enteteOut;
	}



	/**
	 * MyDebug::encadre()
	 * 
	 * @static
	 * @access privé
	 * @param chaine $msg
	 * @param chaine(8) $cadre commence par le coin haut gauche, tourne dans le sens des aiguille d'une montre et fini par le cot� gauche
	 * @return chaine
	 */
	private static function encadre($msg, $cadre = '+-+|+-+|') {
		$msgOut = '';
		$msg = trim($msg);
		$lignes = explode("\n", $msg);
		$nbLigne = count($lignes);
		$longMax = 0;
		for ($i = 0; $i < $nbLigne; $i++)
			if (strlen($lignes[$i]) > $longMax)
				$longMax = strlen($lignes[$i]);
		// ligne du haut
		$msgOut = $cadre[0];
		for ($i = 0; $i < ($longMax + 4); $i++)
			$msgOut .= $cadre[1];
		$msgOut .= $cadre[2] . "\n";
		// autre ligne
		for ($i = 0; $i < $nbLigne; $i++) {
			$msgOut .= $cadre[7] . '  ';
			$msgOut .= $lignes[$i];
			for ($j = strlen($lignes[$i]); $j < $longMax; $j++)
				$msgOut .= ' ';
			$msgOut .= '  ' . $cadre[3] . "\n";
		}
		// ligne du bas
		$msgOut .= $cadre[4];
		for ($i = 0; $i < ($longMax + 4); $i++)
			$msgOut .= $cadre[5];
		$msgOut .= $cadre[6];
		return $msgOut;
	}

	/**
	 * MyDebug::ajouteIdDebutLigne()
	 * 
	 * @static
	 * @access priv�
	 * @param chaine $id
	 * @param chaine $msg
	 * @return chaine
	 */
	private static function ajouteIdDebutLigne($Id, $msg) {
		$msgOut = '';
		$separateur = ' - ';
		$separateur2 = ' \ ';
		$msg = trim($msg);
		$lignes = explode("\n", $msg);
		$nbLigne = count($lignes);
		for ($i = 0; $i < $nbLigne; $i++) {
			if ($i == 1)
				$separateur = $separateur2;
			$msgOut .= $Id . $separateur . $lignes[$i] . "\n";
		}
		return trim($msgOut);
	}

	/**
	 * MyDebug::listeVarGlob()
	 * 
	 * @static
	 * @access privé
	 * @param tableau $tableau
	 * @param entier $format -1 auto, 0 long, 1 moyen, 2 court
	 * @return
	 */
	private static function listeVarGlob($tableau, $format = -1) {

		$outLong = '';
		$outMoy = '';
		$outCourt = '';
		$lstOut = array(
			'outLong',
			'outMoy',
			'outCourt');
		$type = '';

		foreach ($tableau as $cle => $val) {
			// virgule
			foreach ($lstOut as $out) {
				if (strlen($$out) > 0)
					$$out .= ',';
			}

			// cl�
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
				if (strlen($outLong) < 70)
					return $outLong;
				if (strlen($outMoy) < 70)
					return $outMoy;
				return $outCourt;
			} else {
				if (isset($lstOut[$format]))
					return $$lstOut[$format];
				else
					return "";
			}

		}
	}
    
    public static function traceVar(/*mixed*/ $var, /*string*/ $name = null){
        
        $trace = '';
        if(isset($name)){
            $trace .= "$name = ";
        }
        $trace .= print_r($var, true);
        self::trace($trace);
        
    }
    
}