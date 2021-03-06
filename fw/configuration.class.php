<?php
/**
 * Classe de gestion des paramètres de configuration.
 * Inspirée du SimpleFramework de Baptiste Pesquet
 * Inspirée du SimpleFramework de Frédéric Guillot
 * (https://github.com/fguillot/simpleFramework)
 *
 * @author Fabien Sanchez
 */
class Configuration {
	/** Constantes qui deffinissent les mode de fonctionnement */
	const MODE_PROD = 1;
	const MODE_DEV  = 0;
	
    /** @var array Tableau des paramètres de configuration */
    private static $parametres;
	
	/** @var integer Mode de fonctionnement de l'appli*/
	private static $mode;

    /**
     * Renvoie la valeur d'un paramètre de configuration
     * 
     * @param string $nom Nom du paramètre
     * @param string $valeurParDefaut Valeur à renvoyer par défaut
     * @return string Valeur du paramètre
     */
    public static function get($nom, $valeurParDefaut = null) {
        if ($nom == "mode"){
			return self::$mode;
		}
		$param = self::getParametres();
		if (isset($param[$nom])) {
            $valeur = $param[$nom];
        } else {
            $valeur = $valeurParDefaut;
        }
        return $valeur;
    }

    /**
     * Renvoie le tableau des paramètres en le chargeant au besoin depuis un 
	 * fichier de configuration. Les fichiers de configuration recherchés sont 
	 * Config/dev.ini et Config/prod.ini (dans cet ordre)
     * 
     * @return array Tableau des paramètres
     * @throws Exception Si aucun fichier de configuration n'est trouvé
     */
    private static function getParametres() {
        if (self::$parametres == null) {
            $cheminFichier = ROOT_CONFIG . "dev.ini";
			self::$mode = self::MODE_DEV;
            if (!file_exists($cheminFichier)) {
                $cheminFichier = ROOT_CONFIG . "prod.ini";
				self::$mode = self::MODE_PROD;
            }
            if (!file_exists($cheminFichier)) {
                throw new Exception("Aucun fichier de configuration trouvé");
				self::$mode = null;
            } else {
                self::$parametres = parse_ini_file($cheminFichier);
            }
        }
        return self::$parametres;
    }
}