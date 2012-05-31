<?php
class Session {
    public function __set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function __get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
    }
    
}