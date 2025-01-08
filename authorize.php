<?php
class Auth {

    public function bind_to_template($replacements, $template) {
        return preg_replace_callback('/{{(.+?)}}/', function($matches) use ($replacements) {
            return $replacements[$matches[1]];
        }, $template);
    }

    public function signup($conn, $ObjGlob, $ObjSendMail) {
        if (isset($_POST["signup"])) {

            $errors = array();

            $fullname = $_SESSION["fullname"] = $conn->escape_values(ucwords(strtolower($_POST["fullname"])));
            $email_address = $_SESSION["email_address"] = $conn->escape_values(strtolower($_POST["email_address"]));
            $username = $_SESSION["username"] = $conn->escape_values(strtolower($_POST["username"]));

            // Input Validation
            if (!$this->validateName($fullname)) {
                // Error message already set in validateName()
            }

            if (!$this->validateEmail($email_address)) {
                // Error message already set in validateEmail()
            }

            if (!$this->validateEmailDomain($email_address)) {
                // Error message already set in validateEmailDomain()
            }

            if ($this->emailExists($email_address, $conn)) {
                // Error message already set in emailExists()
            }

            if ($this->usernameExists($username, $conn)) {
                // Error message already set in usernameExists()
            }

            if (!$this->validateUsername($username)) {
                // Error message already set in validateUsername()
            }

            if (!count($errors)) {
                // Generate and store verification code
                $verification_code = rand(100000, 999999); 

                $cols = ['fullname', 'email', 'username', 'ver_code', 'ver_code_time'];
                $vals = [$fullname, $email_address, $username, $verification_code, time() + (60 * 10)]; // 10 minutes expiration
                $data = array_combine($cols, $vals);
                $insert = $conn->insert('users', $data);

                if ($insert === TRUE) {
                    // ** Replace with actual email template handling **
                    // $replacements = array(
                    //     'fullname' => $fullname, 
                    //     'email_address' => $email_address, 
                    //     'verification_code' => $verification_code, 
                    //     'site_full_name' => strtoupper($conf['site_initials'])
                    // );

                    // $ObjSendMail->SendMail([
                    //     'to_name' => $fullname,
                    //     'to_email' => $email_address,
                    //     'subject' => $this->bind_to_template($replacements, $lang["AccountVerification"]),
                    //     'message' => $this->bind_to_template($replacements, $lang["AccRegVer_template"])
                    // ], $conf);

                    header('Location: verify_code.php');
                    unset($_SESSION["fullname"], $_SESSION["username"]);
                    exit();
                } else {
                    die($insert);
                }
            } else {
                $ObjGlob->setMsg('msg', 'Error(s)', 'invalid');
                $ObjGlob->setMsg('errors', $errors, 'invalid');
            }
        }
    }

    public function verify_code($conn, $ObjGlob, $ObjSendMail) {
        if (isset($_POST["verify_code"])) {
            $errors = array();

            $ver_code = $_SESSION["ver_code"] = $conn->escape_values($_POST["ver_code"]);

            // Verify code is numeric
            if (!is_numeric($ver_code)) {
                $errors['not_numeric'] = "Invalid code format. Verification Code must contain numbers only";
            }

            // Verify code length
            if (strlen($ver_code) != 6) {
                $errors['lenght_err'] = "Invalid code length. Verification Code must have 6 digits";
            }

            // Verify code exists and is not expired
            $spot_ver_code_res = $conn->count_results(sprintf("SELECT ver_code FROM users WHERE ver_code = '%d' AND ver_code_time > NOW() LIMIT 1", $ver_code));
            if ($spot_ver_code_res != 1) {
                $errors['ver_code_not_exist'] = "Invalid or expired verification code";
            }

            if (!count($errors)) {
                $_SESSION['code_verified'] = $ver_code;
                header('Location: set_password.php');
            } else {
                $ObjGlob->setMsg('msg', 'Error(s)', 'invalid');
                $ObjGlob->setMsg('errors', $errors, 'invalid');
            }
        }
    }

    
    public function signin($conn, $ObjGlob, $ObjSendMail) {
        if (isset($_POST["signin"])) {

            $errors = array();
            $username = $_SESSION["username"] = $conn->escape_values(strtolower($_POST["username"]));
            $entered_password = $_SESSION["passphrase"] = $conn->escape_values($_POST["passphrase"]);

            // Verify Username or Email Exists
            $signin_query = sprintf("SELECT * FROM users WHERE username = '%s' OR email = '%s' LIMIT 1", $username, $username);
            $spot_username_res = $conn->count_results($signin_query);

            if ($spot_username_res == 0) {
                $errors['usernamenot_err'] = "Username or Email does not exist.";
            } else {
                // Fetch user data
                $_SESSION["consort_tmp"] = $conn->select($signin_query);

                // Verify password
                $stored_password = $_SESSION["consort_tmp"]["password"];
                if (password_verify($entered_password, $stored_password)) {
                    // Create login session
                    $_SESSION["consort"] = $_SESSION["consort_tmp"];
                } else {
                    unset($_SESSION["consort_tmp"]);
                    $errors['invalid_u_p'] = "Invalid username or password";
                }
            }

            if (!count($errors)) {
                header('Location: dashboard.php');
                exit();
            } else {
                $ObjGlob->setMsg('msg', 'Error(s)', 'invalid');
                $ObjGlob->setMsg('errors', $errors, 'invalid');
            }
        }
    }
}