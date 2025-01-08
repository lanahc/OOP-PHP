<?php

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

    public function verify_profile() {
        if (isset($_SESSION["consort"]) && ($_SESSION["consort"]["genderId"] == 0 || $_SESSION["consort"]["roleId"] == 0)) {
            header("Location: complete_reg.php");
            exit();
        }
    }

    // Check if user is signed in to view protected pages
    public function checkSignin() {
        if (!isset($_SESSION["consort"]) || !is_array($_SESSION["consort"])) {
            $this->setMsg('msg', 'User must signin.', 'invalid-feedback');
            header("Location: signin.php");
            exit();
        }
    }

    // Function to validate name (only letters allowed)
    public function validateName($name) {
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $this->setMsg('errors', array('nameLetters_err' => "Only letters and spaces allowed in Name."), 'invalid-feedback');
            return false;
        }
        return true;
    }

    // Function to validate email format
    public function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setMsg('errors', array('email_format_err' => "Invalid email format."), 'invalid-feedback');
            return false;
        }
        return true;
    }

    // Function to check if email already exists in the database (replace with actual database query)
    public function emailExists($email, $conn) {
        // Example:
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $this->setMsg('errors', array('mailExists_err' => "Email already exists."), 'invalid-feedback');
            return true; // Email exists
        }
        return false; // Email does not exist
    }

    // Function to check if email domain is allowed (example: only allow @example.com)
    public function validateEmailDomain($email) {
        $allowedDomains = array('@example.com', '@yourdomain.com'); // Add allowed domains
        if (!in_array(substr(strrchr($email, "@"), 1), $allowedDomains)) {
            $this->setMsg('errors', array('mailDomain_err' => "Only emails from allowed domains are permitted."), 'invalid-feedback');
            return false;
        }
        return true;
    }

    // Function to validate username (letters, numbers, and underscores allowed)
    public function validateUsername($username) {
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
            $this->setMsg('errors', array('usernameLetters_err' => "Only letters, numbers, and underscores allowed in Username."), 'invalid-feedback');
            return false;
        }
        return true;
    }

    // Function to check if username already exists in the database (replace with actual database query)
    public function usernameExists($username, $conn) {
        // Example:
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $this->setMsg('errors', array('usernameExists_err' => "Username already exists."), 'invalid-feedback');
            return true; // Username exists
        }
        return false; // Username does not exist
    }

    // ... Add more validation functions as needed (e.g., password validation, etc.) ...

}

?>