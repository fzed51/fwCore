<?php

class Auth{
	
	static private $needed = null;
	static public function isNeeded(/*bool*/$needed){
		self::$needed = $needed;
	}

	// Singleton
	static private $handle
	private function __Construct(){

	}
	static public function init(){
		if(is_null(self::$handle)){
			self::$handle = new Auth();
		}
		return self::$handle;
	}

}

?>