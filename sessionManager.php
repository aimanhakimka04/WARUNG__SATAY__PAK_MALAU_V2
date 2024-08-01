<?php
class SessionManager {
    public function __construct() {
        if(session_status() == PHP_SESSION_NONE) {
            
        }
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
}
?>
