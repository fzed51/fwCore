<?php
/**
 * Description of session
 *
 * @author fabien.sanchez
 */
class Session extends ArrayObject{
    // Singleton
    static private $handle;
    private function __Construct(){
        session_start();
    }
    static public function get(){
            if(is_null(self::$handle)){
                    self::$handle = new Auth();
            }
            return self::$handle;
    }
    public function offsetExists ($index) {
        return isset($_SESSION[$index]);
    }

    public function offsetGet ($index) {
        return $_SESSION[$index];
    }

    public function offsetSet ($index, $newval) {
        $_SESSION[$index] = $newval;
    }

    public function offsetUnset ($index) {
        unset($_SESSION[$index]);
    }

    public function append ($value) {
        throw new LogicException("Impossible d'ajouter '$value' de cette façon!");
    }

    public function getArrayCopy () {
        return $_SESSION;
    }

    public function count () {
        return count($_SESSION);
    }
    
    public function raz(){
        $_SESSION = array();
    }
}
