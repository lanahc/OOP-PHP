<?php
// functions.php

class Fncs {
    public function setMsg($name, $values, $class) {
        if (is_array($values)) {
            $_SESSION[$name] = $values;
        } else {
            $_SESSION[$name] = '<span class="' . $class . '">' . $values . '</span>';
        }
    }

    public function getMsg($name) {
        if (isset($_SESSION[$name])) {
            $session = $_SESSION[$name];
            unset($_SESSION[$name]);
            return $session;
        }
    }

    // Check user is signed in to view protected pages
    public function checkSignin() {
        if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) { 
            $this->setMsg('msg', 'User must signin.', 'invalid-feedback');
            header("Location: signin.php");
            exit();
        }
    }
}
?>