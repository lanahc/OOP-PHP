<?php
session_start();
var_dump($_SESSION);//

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'oop'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )

);

spl_autoload_register(function($class) {
    require_once '../classes/'.$class . '.php';
    
}
);
function sanitize($string) {
    return htmlspecialchars($string); 
}

class Input {
    public static function exists($type = 'post') {
        switch($type) {
            case 'post':
                return (!empty($_POST))? true : false;
            case 'get':
                return (!empty($_GET))? true : false;
            default:
                return false;
        }
    }

    public static function get($item) {
        if(isset($_POST[$item])) {
            return sanitize($_POST[$item]); 
        } else if(isset($_GET[$item])) {
            return sanitize($_GET[$item]);
        }
        return '';
    }
}

class Redirect {
    public static function to($location = null) {
        if($location) {
            if(is_numeric($location)) {
                switch($location) {
                    case 404:
                        header('HTTP/1.0 404 Not Found');
                        include 'includes/errors/404.php'; 
                        exit();
                    default:
                        header('HTTP/1.0 404 Not Found');
                        exit();
                }
            }
            header('Location: ' . $location);
            exit();
        }
    }
}
require_once '../functions/sanitize.php';
require_once '../classes/validate.php';



