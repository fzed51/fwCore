<?php

/**
 * Helper pour HTML5
 */

class Html{
	
	private $_stock_css = array();
	private $_stock_js = array();
	private $_daraForm = array();
	
	/**
	 * Transforme une array en chemin 
	 * @param array $controlAction
	 * @return string
	 */
	public function ctrAction(array $controlAction){
		$adrCtrlActin = join(WS, $controlAction);
		$adresse = WEB_ROOT . $adrCtrlActin;
		return $adresse;
	}
	
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
	 * formate une liste d'attributs HTML
	 * @param array $listeAttributs
	 * @return string
	 */
	protected function concatAttributs(array $listeAttributs){
		$attribut = array();
		foreach ($listeAttributs as $key => $value) {
			if(!is_bool($value)){
				if(strlen($value) > 0){
					$attribut[] = strtolower($key) . '="' . $value . '"';
				}
			} else {
				if($value){
					$attribut[] = strtolower($key);
				}
			}
		}
		return join(' ', $attribut);
	}

	protected function startElement($tag, $attrbs = array()) { 
		$attrb = $this->concatAttributs($attrbs);
		return "<$tag $attrb>";
	}
	
	protected function endElement($tag) {
		return "</$tag>";
	}

	protected function element($tag, $attrbs = array(), $content = ''){
		return $this->startElement($tag, $attrbs) 
				. $content
				. $this->endElement($tag);
	}
	
	protected function elementAutoClose($tag, $attrbs = array()){
		$attrb = $this->concatAttributs($attrbs);
		return "<$tag $attrb />";
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
	
	/**
	 * Ouvre la balise form
	 * @param string $action
	 * @param array $attributs
	 * @param string $id
	 * @return string
	 */
	public function startForm($action, array $attributs = array(), /*string*/$id = ''){
		$defaultAttrbs = array(
			'accept-charset' => '',
					// ISO-8859-1
					// ISO-8859-15
					// UTF-8
			'autocomplete' => '', // on | off
			'enctype' => '',
					// application/x-www-form-urlencoded
					// multipart/form-data
					// text/plain
			'method' => 'POST', // GET | POST
			'name' => '',
			'novalidate' => false,
			'target' => ''
					// _blank
					// _self
					// _parent
					// _top
		);
		if(isset($attributs['data'])){
			$this->_daraForm = $attributs['data'];
			unset($attributs['data']);
		}
		if(strlen($id)>0 && !isset($attributs['name'])){
			$attributs['name'] = $id
		}
		$attributs = array_merge($defaultAttrbs,$attributs,array(
					'action' => $action,
					'id' => $id
				));
		return $this->startElement('form', $attributs);
	}
	
	/**
	 * Ferme la balise form
	 * @return string
	 */
	public function endForm(){
		$this->_daraForm = array();
		return $this->endElement('form');
	}
}