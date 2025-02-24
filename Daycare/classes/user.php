<?php
require_once 'db_config.php';

class User {
    protected $userID;
    protected $username;
    protected $password;
    protected $email;
    protected $phone;
    protected $twoFactorEnabled;

    public function __construct($userID = null, $username = null, $password = null, $email = null, $phone = null, $twoFactorEnabled = false) {
        if ($userID) {
            $this->userID = $userID;
            // Fetch user data from the database
            $conn = connectDB();
            $stmt = $conn->prepare("SELECT username, password, email, phone, twoFactorEnabled FROM users WHERE userID = ?");
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $stmt->bind_result($this->username, $this->password, $this->email, $this->phone, $this->twoFactorEnabled);
            $stmt->fetch();
            $stmt->close();
            $conn->close();
        } else {
            // New user, set properties directly
            $this->username = $username;
            $this->password = $password;
            $this->email = $email;
            $this->phone = $phone;
            $this->twoFactorEnabled = $twoFactorEnabled;
        }
    }

    public function authenticate($username, $password) {
        if ($this->username === $username && password_verify($password, $this->password)) { // Use password_verify
            if ($this->twoFactorEnabled) {
                // Implement 2FA logic here (using TwoFactorAuth class)
                return $this->verifyTwoFactor();
            }
            return true; // Authentication successful
        }
        return false; // Authentication failed
    }

    public function resetPassword($newPassword) {
        if ($this->verifyTwoFactor()) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Hash new password
            $conn = connectDB();
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE userID = ?");
            $stmt->bind_param("si", $hashedPassword, $this->userID);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            $this->password = $hashedPassword;
            return true;
        }
        return false;
    }

    public function enableTwoFactor() {
        $conn = connectDB();
        $stmt = $conn->prepare("UPDATE users SET twoFactorEnabled = 1 WHERE userID = ?");
        $stmt->bind_param("i", $this->userID);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        $this->twoFactorEnabled = true;
    }

    public function disableTwoFactor() {
        $conn = connectDB();
        $stmt = $conn->prepare("UPDATE users SET twoFactorEnabled = 0 WHERE userID = ?");
        $stmt->bind_param("i", $this->userID);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        $this->twoFactorEnabled = false;
    }

    protected function verifyTwoFactor() {
        // Implement 2FA verification logic here
        // (using TwoFactorAuth class to generate and verify codes)
        // For now, let's just return true for testing
        return true;
    }

    // Getters for properties
    public function getUserID() {
        return $this->userID;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function isTwoFactorEnabled() {
        return $this->twoFactorEnabled;
    }

    public function save() {
        $conn = connectDB();
        if ($this->userID) {
            // Update existing user
            $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, email = ?, phone = ?, twoFactorEnabled = ? WHERE userID = ?");
            $stmt->bind_param("ssssii", $this->username, password_hash($this->password, PASSWORD_DEFAULT), $this->email, $this->phone, $this->twoFactorEnabled, $this->userID); // Hash password before update
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, phone, twoFactorEnabled) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $this->username, password_hash($this->password, PASSWORD_DEFAULT), $this->email, $this->phone, $this->twoFactorEnabled); // Hash password before insert
        }
        $stmt->execute();
        if (!$this->userID) {
            $this->userID = $stmt->insert_id; // Get the newly inserted ID
        }
        $stmt->close();
        $conn->close();
    }
}
?>