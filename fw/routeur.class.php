<?php

/**
 * Description of Routeur
 * @author Sandrine
 */

class Routeur {
	
	/**
	 * Route une requete
	 * @param Requette $requette
	 */
	public function route(Requette $requette){
		$ctrlNom = $requette->getControl();
		$class_name = ucfirst(strtolower($ctrlNom)).'Controleur';
		$action = ucfirst(strtolower($requette->getAction()));
		try{
			if(class_exists($class_name, true)){
				$controleur = new $class_name();
				$controleur->setNom($ctrlNom);
				$controleur->executeAction($action, $requette);
			} else {
				throw new ControleurException("Le controleur '$ctrlNom' n'existe pas! ");
			}
		} catch (AuthException $e){
			$this->erreurAuth();
		} catch (ControleurException $e){
			$this->erreur404($e->getMessage());
		} catch (VueException $e){
			$this->erreur404($e->getMessage());
		} catch (modelexception $e){
			$this->erreur500($e->getMessage());
		} catch (Exception $e){
			$this->erreur500($e->getMessage());			
		}
	}
	
	/**
	 * redirige l'application
	 * @param string|array $url
	 * @param boo $redirect
	 * @param int $http_response_code
	 */
	public function redirect(/*string|array*/$url, /*bool*/$redirect = false){
	//public function redirect(/*string|array*/$url, /*int*/$http_response_code = 200){
		$elementHtlm = new ElementHtml();
		if($redirect){
			$http_response_code = 302;
		}else{
			$http_response_code = 200;
		}
		$serveur = filter_var($_SERVER['SERVER_NAME'], FILTER_SANITIZE_URL);
		if(is_array($url)){
			$url = $elementHtlm->ctrAction($url);
		}
		header("Location: http://$serveur$url",true,$http_response_code);
		exit();
	}
	
	public function erreurAuth(){
        //$this->redirect(array('user', 'login'), 401);
        $this->redirect(array('user', 'login'), true);
	}
	
    public function erreur404 ($message = ''){
        //$this->redirect(array('home', 'index'), 404);
        $this->redirect(array('home', 'index'), true);
    }
	
	public function erreur500 ($message = ''){
        //$this->redirect(array('home', 'index'), 500);
        $this->redirect(array('home', 'index'), true);
    }
}
