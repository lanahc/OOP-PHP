<?php
// users.php

if (isset($_GET['form'])) {
    $form = $_GET['form'];
} else {
    $form = 'signup'; 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($form) . " Form"; ?></title> 
    <link rel="stylesheet" href="bootstrap-5.3.3/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<body>

    <div class="container">
        <div class="row align-items-md-stretch">
            <div class="col-md-9">
                <div class="h-100 p-5 text-bg-dark rounded-3">
                    <h2><?php echo ucfirst($form) . " Form"; ?></h2> 

                    <?php 
                    if (isset($_POST['submit'])) {
                        // Handle form submission based on the form type
                        if ($form === 'signup') {
                            // Signup form logic
                            // ... (your signup validation and processing here)
                        } elseif ($form === 'login') {
                            // Login form logic
                            // ... (your login validation and processing here)
                        } 
                        // ... add more form types as needed
                    }
                    ?>

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                        <?php if ($form === 'signup') { ?>
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name:</label>
                                <input type="text" name="fullname" class="form-control form-control-lg" maxlength="50" id="fullname" placeholder="Enter your name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email_address" class="form-label">Email Address:</label>
                                <input type="email" name="email_address" class="form-control form-control-lg" maxlength="50" id="email_address" placeholder="Enter your email address" required>
                            </div>
                      <?php } elseif ($form === 'login') { ?>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" name="username" class="form-control form-control-lg" maxlength="50" id="username" placeholder="Enter your username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" name="password" class="form-control form-control-lg" id="password" required>
                            </div>
                        <?php } ?> 
                        <button type="submit" name="signup" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php

class TwoFactorAuthForm {

    private $otp;
    private $errors = [];

    public function __construct($otp) {
        $this->otp = $otp;
    }

    public function validate() {
        // Check if OTP is empty
        if (empty($this->otp)) {
            $this->errors['otp_empty'] = "OTP is required.";
        } 

        // Check if OTP contains only digits
        if (!preg_match('/^[0-9]+$/', $this->otp)) {
            $this->errors['otp_invalid'] = "OTP should contain only digits.";
        }

        // Check if OTP has the expected length (e.g., 6 digits)
        if (strlen($this->otp) != 6) {
            $this->errors['otp_length'] = "OTP should be 6 digits long.";
        }

        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Factor Authentication</title>
</head>
<body>

    <div class="container"> 
        <h2>Two-Factor Authentication</h2>
        <?php
        if (isset($_POST['submit'])) {
            $otp = $_POST['otp'] ?? '';

            $twoFactorForm = new TwoFactorAuthForm($otp);

            if ($twoFactorForm->validate()) {
                // Handle successful OTP verification (e.g., redirect to the next page)
                // ...
            } else {
                $errors = $twoFactorForm->getErrors();
            }
        }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="otp">Enter OTP:</label>
                <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter your OTP" required>
                <?php if (isset($errors['otp_empty'])) : ?>
                    <span class="error"><?php echo $errors['otp_empty']; ?></span>
                <?php endif; ?>
                <?php if (isset($errors['otp_invalid'])) : ?>
                    <span class="error"><?php echo $errors['otp_invalid']; ?></span>
                <?php endif; ?>
                <?php if (isset($errors['otp_length'])) : ?>
                    <span class="error"><?php echo $errors['otp_length']; ?></span>
                <?php endif; ?>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Verify</button>
        </form>
    </div>

</body>
</html>

<?php

class Form {

    private $name;
    private $email;
    private $phone;
    private $address;
    private $gender;
    private $errors = [];

    public function __construct($name, $email, $phone, $address, $gender) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->gender = $gender;
    }

    public function validate() {
        // Name validation
        if (empty($this->name)) {
            $this->errors['name_empty'] = "Name is required.";
        } 

        // Email validation
        if (empty($this->email)) {
            $this->errors['email_empty'] = "Email is required.";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email_invalid'] = "Invalid email format.";
        }

        // Phone validation
        if (empty($this->phone)) {
            $this->errors['phone_empty'] = "Phone number is required.";
        } elseif (!preg_match('/^[0-9]{10}$/', $this->phone)) { 
            $this->errors['phone_invalid'] = "Invalid phone number format. Please enter 10 digits.";
        }

        // Address validation
        if (empty($this->address)) {
            $this->errors['address_empty'] = "Address is required.";
        }

        // Gender validation
        if (empty($this->gender)) {
            $this->errors['gender_empty'] = "Gender is required.";
        }

        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
</head>
<body>

    <div class="container">
        <h2>Signup Form</h2>
        <?php
        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $gender = $_POST['gender'];

            $form = new Form($name, $email, $phone, $address, $gender);

            if ($form->validate()) {
                // Handle successful signup (e.g., redirect to welcome page)
                // ...
            } else {
                $errors = $form->getErrors();
            }
        }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
                <?php if (isset($errors['name_empty'])) : ?>
                    <span class="error"><?php echo $errors['name_empty']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                <?php if (isset($errors['email_empty'])) : ?>
                    <span class="error"><?php echo $errors['email_empty']; ?></span>
                <?php elseif (isset($errors['email_invalid'])) : ?>
                    <span class="error"><?php echo $errors['email_invalid']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" required>
                <?php if (isset($errors['phone_empty'])) : ?>
                    <span class="error"><?php echo $errors['phone_empty']; ?></span>
                <?php elseif (isset($errors['phone_invalid'])) : ?>
                    <span class="error"><?php echo $errors['phone_invalid']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your address" required></textarea>
                <?php if (isset($errors['address_empty'])) : ?>
                    <span class="error"><?php echo $errors['address_empty']; ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="Male">
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
                    <label class="form-check-label" for="female">Female</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="other" value="Other">
                    <label class="form-check-label" for="other">Other</label>
                </div>
                <?php if (isset($errors['gender_empty'])) : ?>
                    <span class="error"><?php echo $errors['gender_empty']; ?></span>
                <?php endif; ?>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Sign Up</button>
        </form>
    </div>

</body>
</html>
</body>
</html>