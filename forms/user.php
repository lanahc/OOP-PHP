// users.php
<?php
class users{
    public function sign_up_form($ObjGlob){
?> 
     div class="h-100 p-5 text-bg-dark rounded-3">
    <h2>Sign Up</h2>
    <?php
    print $ObjGlob->getMsg('msg');
    $err = $ObjGlob->getMsg('errors');
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name:</label>
                                <input type="text" name="fullname" class="form-control form-control-lg" maxlength="50" id="fullname" placeholder="Enter your name" required>
                        <?php print (isset($err['nameLetters_err'])) ? "<span class='ivalid'>" . $err['nameLetters_err'] . "</span": '';?> 
                            </div>
                            <div class="mb-3">
                                <label for="email_address" class="form-label">Email Address:</label>
                                <input type="email" name="email_address" class="form-control form-control-lg" maxlength="50" id="email_address" placeholder="Enter your email address" required>
                                <?php print (isset($err['email_format_err'])) ? "<span class='invalid'>" . $err['email_format_err'] . "</span>" : '' ; ?>
                                <?php print (isset($err['mailExists_err'])) ? "<span class='invalid'>" . $err['mailExists_err'] . "</span>" : '' ; ?>
                                <?php print (isset($err['mailDomain_err'])) ? "<span class='invalid'>" . $err['mailDomain_err'] . "</span>" : '' ; ?>
                            </div>
                            <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" name="username" class="form-control form-control-lg" maxlength="50" id="username" placeholder="Enter your username" <?php print (isset($_SESSION["username"])) ? 'value="'.$_SESSION["username"].'"'  : ''; unset($_SESSION["username"]); ?> >
                    <?php print (isset($err['usernameExists_err'])) ? "<span class='invalid'>" . $err['usernameExists_err'] . "</span>" : '' ; ?>
                    <?php print (isset($err['usernameLetters_err'])) ? "<span class='invalid'>" . $err['usernameLetters_err'] . "</span>" : '' ; ?>
                </div>
                <button type="submit" name="signup" class="btn btn-primary">Submit</button>
                     
 <?php    
}

    public function verification_otp_form($ObjGlob) {
?>
    <div class="h-100 p-5 text-bg-dark rounded-3">
        <h2>Verify OTP</h2>
        <?php
        print $ObjGlob->getMsg('msg');
        $err = $ObjGlob->getMsg('errors');
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="mb-3">
                <label for="otp" class="form-label">Enter OTP:</label>
                <input type="text" name="otp" class="form-control form-control-lg" id="otp" placeholder="Enter OTP" required>
                <?php print (isset($err['otp_invalid'])) ? "<span class='invalid'>" . $err['otp_invalid'] . "</span>" : ''; ?>
            </div>
            <button type="submit" name="verify_otp" class="btn btn-primary">Verify</button>
        </form>
    </div>
    <?php
    }

    public function sign_in_form($ObjGlob) {
?>
        <div class="h-100 p-5 text-bg-dark rounded-3">
            <h2>Sign In</h2>
            <?php
            print $ObjGlob->getMsg('msg');
            $err = $ObjGlob->getMsg('errors');
            ?>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" name="username" class="form-control form-control-lg" id="username" placeholder="Enter Username" required>
                    <?php print (isset($err['username_invalid'])) ? "<span class='invalid'>" . $err['username_invalid'] . "</span>" : ''; ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" name="password" class="form-control form-control-lg" id="password" placeholder="Enter Password" required>
                    <?php print (isset($err['password_invalid'])) ? "<span class='invalid'>" . $err['password_invalid'] . "</span>" : ''; ?>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </form>
    </div>
    <?php
}
}
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
        <h2>Form</h2>
        <?php
        if (isset($_POST['submit'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $gender = $_POST['gender'] ?? '';

    $form = new Form($name, $email, $phone, $address, $gender);

    if ($form->validate()) {
        // Handle successful signup (e.g., redirect to welcome page)
        // ...
    } else {
        $errors = $form->getErrors();
    }
} ?>
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


<?php

class ProfileForm {
    private $firstName;
    private $lastName;
    private $email;
    private $phone;
    private $address;
    private $errors = [];

    public function __construct($firstName, $lastName, $email, $phone, $address) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    // Similar getter and setter methods for lastName, email, phone, and address

    public function validate() {
        if (empty($this->firstName)) {
            $this->errors['firstName_empty'] = "First name is required.";
        }
        if (empty($this->lastName)) {
            $this->errors['lastName_empty'] = "Last name is required.";
        }
        // Add more validation rules here (e.g., email format, phone number format)
        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    public function saveToDatabase() {
        // Implement database interaction here
        // (e.g., insert or update user data in a database)
        // This is a placeholder for database logic
        echo "Profile data saved to the database."; 
    }
}

// Usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $profileForm = new ProfileForm($firstName, $lastName, $email, $phone, $address);

    if ($profileForm->validate()) {
        // Save the profile data to the database
        $profileForm->saveToDatabase(); 
        // Redirect to profile page
        header("Location: profile.php");
        exit();
    }
}

// Display the form 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile Form</title>
</head>
<body>

    <h2>Profile Form</h2>

    <form method="post" action="">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" value="<?php echo isset($_POST['firstName']) ? $_POST['firstName'] : ''; ?>">
        <?php if (isset($profileForm) && isset($profileForm->getErrors()['firstName_empty'])) : ?>
            <span class="error"><?php echo $profileForm->getErrors()['firstName_empty']; ?></span>
        <?php endif; ?>

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" value="<?php echo isset($_POST['lastName']) ? $_POST['lastName'] : ''; ?>">
        <?php if (isset($profileForm) && isset($profileForm->getErrors()['lastName_empty'])) : ?>
            <span class="error"><?php echo $profileForm->getErrors()['lastName_empty']; ?></span>
        <?php endif; ?>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">

        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>">

        <label for="address">Address:</label>
        <textarea id="address" name="address"><?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?></textarea>

        <button type="submit">Submit</button>
    </form>

</body>
</html>

<?php
