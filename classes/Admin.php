<?php

require_once '../core/init.php';
require_once '../classes/DB.php';

class Admin {

    public static function login($username, $password) {
        $db = DB::getInstance();
        $results = $db->get('admins', array('username', '=', $username));

        if(!$results->error() && $results->count()) { 
            $user = $results->first();

            if(password_verify($password, $user->password)) { 
                session_start();
                $_SESSION['user'] = $user->id; 
                return true;
            }
        }

        return false;
    }

    public static function isLoggedIn() {
        session_start();
        return isset($_SESSION['user']);
    }

    public static function logout() {
        session_start();
        unset($_SESSION['user']);
    }
}

?>