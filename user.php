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


</body>
</html>