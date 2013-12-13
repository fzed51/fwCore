<?php

/**
 * Helper pour HTML5
 */

class Html extends ElementHtml {
	
	private $_stock_css = array();
	private $_stock_js = array();
	
	/**
	 * Cherche un fichier css ou js du site.
	 * retourne la version minifié si elle existe.
	 * @param string $nom
	 * @param string $extension
	 * @return string
	 * @throws Exception
	 */
	protected function chercheFichier($nom, $extension){
		if(Tools::finiPar($nom, $extension)){
			$nom = substr($nom,0,-strlen($extension));
		}
		$rootDir = '';
		$webDir = '';
		$fichier = '';
		switch ($extension) {
			case '.css':
				$rootDir = ROOT_CSS;
				$webDir = WEB_CSS;
				break;
			case '.js':
				$rootDir = ROOT_JS;
				$webDir = WEB_JS;
				break;
		}
		if(file_exists($rootDir.$nom.$extension)){
			$fichier = $webDir.$nom.$extension;
		}
		if($fichier == '' || Configuration::get('mode') == Configuration::MODE_PROD){
			if(file_exists($rootDir.$nom.'.min'.$extension)){
				$fichier = $webDir.$nom.'.min'.$extension;
			}
		}
		if($fichier == ''){
			throw new Exception("Le fichier '$nom' n'a pas été trouvé ! ");
		}
		return $fichier;
	}

	/**
	 * Génère une balise link
	 * @param string $fichier
	 * @param array $attributs
	 * @return string
	 */
	public function link(/*string*/$href, array $attributs = array()){
		$defaultAttrbs = array(
			"hreflang" => '',
			"type" => '',
			"rel" => '',
			"media" => '',
			"size" => ''
		);
		$attributs = array_merge($defaultAttrbs, $attributs, array('href'=>$href));
		$link = $this->elementAutoClose('link', $attributs);
		return $link;
	}
	
	/**
	 * Génère un lien avec une feuille de stye
	 * @param string $fichier
	 * @param bool $return
	 * @param array $attributs
	 * @return string|null
	 */
	public function css(/*string*/$fichier, /*bool*/$return = true, array $attributs = array()){
		$defaultAttrbs = array(
			'rel' => 'stylesheet',
			'type' => 'text/css',
			'media' => 'screen'
		);
		$attributs = array_merge($defaultAttrbs, $attributs);
		$href = '';
		if(! filter_var($fichier, FILTER_VALIDATE_URL)){
			$href = $this->chercheFichier($fichier, '.css');
		}
		$link = $this->link($href, $attributs);
		if($return){
			return $link;
		}else{
			$this->_stock_css[] = $link;
			return null;
		}
	}
	
	/**
	 * Génère une balise script
	 * @param string $script
	 * @param array $attributs
	 * @return string
	 */
	public function script(/*string*/$script, array $attributs = array()){
		$defaultAttrbs = array(
			'async' => false,
			'charset' => '',
			'defer' => false,
			'src' => '',
			'type' => 'application/javascript',
		);
		$attributs = array_merge($defaultAttrbs, $attributs);
		$script = $this->startElement('script', $attributs)
				. $script
				. $this->endElement('script');
		return $script;
	}
	
	/**
	 * 
	 * @param string $src
	 * @param array $attributs
	 * @return string
	 */
	public function scriptScr(/*string*/$src, array $attributs = array()) {
		$attributs = array_merge($attributs, array('src' => $src));
		return $this->script('', $attributs);
	}
	
	/**
	 * 
	 * @param string $src
	 * @param bool $return
	 * @param array $attributs
	 * @return string|null
	 */
	public function js(/*string*/$src, /*bool*/$return = true, array $attributs = array()){
		$defaultAttrbs = array(
			'type' => 'application/javascript'
		);
		$attributs = array_merge($defaultAttrbs, $attributs);
		if(! filter_var($src, FILTER_VALIDATE_URL)){
			$src = $this->chercheFichier($src, '.js');
		}
		$script = $this->scriptScr($src, $attributs);
		if($return){
			return $script;
		}else{
			$this->_stock_js[] = $script;
			return null;
		}
	}
	
}