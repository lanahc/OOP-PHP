<?php

class TwoFactorAuth {
    private $userID;
    private $secret; // Secret key for generating codes
    private $method; // 'email' or 'sms'

    public function __construct($userID, $secret, $method) {
        $this->userID = $userID;
        $this->secret = $secret;
        $this->method = $method;
    }

    public function generateCode() {
        // Logic to generate a 6-digit code based on the secret
        // You can use a library like GoogleAuthenticator or build your own
        // For now, let's just return a random number
        return rand(100000, 999999);
    }

    public function verifyCode($code) {
        // Logic to verify the code
        // This might involve comparing the provided code with a generated code
        // For now, let's just return true for testing
        return true;
    }

    public function sendCode($code) {
        // Logic to send the code via email or SMS
        if ($this->method === 'email') {
            // Send email
        } elseif ($this->method === 'sms') {
            // Send SMS
        }
    }
}
?>