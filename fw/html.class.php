<?php

/**
 * Html
 * Helper html pour HTML5
 * 
 * @package fwCore
 */

class Html{
	
	private $_stock_css = array();
	private $_stock_js = array();
	private $_daraForm = array();
	
	public function ctrAction(array $controlAction){
		$adrCtrlActin = join(WS, $controlAction);
		$adresse = WEB_ROOT . $adrCtrlActin;
		return $adresse;
	}
	
	protected function chercheFichier($nom, $extension){
		if(Tools::finiPar($nom, $extension)){
			$nom = substr($nom,0,-strlen($extension));
		}
		$locDir = '';
		$webDir = '';
		$fichier = '';
		switch ($extension) {
			case '.css':
				$locDir = ROOT_CSS;
				$webDir = WEB_CSS;
				break;
			case '.js':
				$locDir = ROOT_JS;
				$webDir = WEB_JS;
				break;
		}
		if(file_exists($locDir.$nom.$extension)){
			$fichier = $webDir.$nom.$extension;
		}
		if($fichier == '' || Configuration::get('mode') == Configuration::MODE_PROD){
			if(file_exists($locDir.$nom.'.min'.$extension)){
				$fichier = $webDir.$nom.'.min'.$extension;
			}
		}
		if($fichier == ''){
			throw new Exception("Le fichier '$nom' n'a pas été trouvé ! ");
		}
		return $fichier;
	}


	protected function concatAttributs($listeAttributs){
		$attribut = array();
		foreach ($listeAttributs as $key => $value) {
			if(!is_bool($value)){
				if(strlen($value)){
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
	 * function Link
	 *   
	 * @param string $fichier
	 * @param bool $return
	 * @param array $options
	 * @return null/string
	 */
	public function link(/*string*/$href, array $options = array()){
		$defaultOptions = array(
			"hreflang" => '',
			"type" => '',
			"rel" => '',
			"media" => '',
			"size" => ''
		);
		$option = array_merge($defaultOptions, $options);
		$attributs = array_merge(array('href'=>$href), $option);
		$link = $this->elementAutoClose('link', $attributs);
		return $link;
	}
	
	/**
	 * fonction css
	 * génère un lien avec une feuille de stye Externe;
	 * 
	 * @param	string		$fichier
	 * @param	bool		$return
	 * @param	array		$attributs
	 * @return	string/null
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
		$link = $this->link($href, $defaultAttrbs);
		if($return){
			return $link;
		}else{
			$this->_stock_css[] = $link;
			return null;
		}
	}
	
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
	
	public function scriptScr(/*string*/$src, array $attributs = array()) {
		$attributs = array_merge($attributs, array('src' => $src));
		return $this->script('', $attributs);
	}
	
	public function js(/*string*/$script, /*bool*/$return = true, array $attributs = array()){
		$defaultAttrbs = array(
			'async' => false,
			'charset' => '',
			'defer' => false,
			'src' => '',
			'type' => 'application/javascript',
		);
		$attributs = array_merge($defaultAttrbs, $attributs);
		$script = '';
		if($return){
			return $script;
		}else{
			$this->_stock_js[] = $script;
			return null;
		}
	}
	
	public function startForm($action, array $attributs = array()){
		$defaultAttrbs = array(
			'accept-charset' => 'stylesheet',
					// ISO-8859-1
					// ISO-8859-15
					// UTF-8
			'autocomplete' => '', // on | off
			'enctype' => 'application/x-www-form-urlencoded',
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
		$attributs = array_merge($defaultAttrbs, $attributs);
	}
	
	public function endForm(){
		$this->_daraForm = array();
		return $this->endElement('form');
	}
}