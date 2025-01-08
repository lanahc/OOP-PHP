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
    public function main_form($ObjGlob) {
?>
        <div class="h-100 p-5 text-bg-dark rounded-3">
            <h2>Welcome</h2>
            <?php
            // Display user-specific content here
            // For example:
            if (isset($_SESSION['user_data'])) {
                echo "Welcome, " . $_SESSION['user_data']['fullname'] . "!";
            } else {
                echo "You are not logged in.";
            }
            ?>
        </div>
<?php
    }
    public function profile_form($ObjGlob, $conn) {
        ?>
                <div class="row align-items-md-stretch">
                    <div class="col-md-9">
                        <div class="h-100 p-5 text-bg-dark rounded-3">
                            <h2>Update Profile</h2>
                            <?php
                            print $ObjGlob->getMsg('msg');
                            $err = $ObjGlob->getMsg('errors');
        
                            $spot_profile = $conn->select(sprintf("SELECT `users`.`userId`, `users`.`fullname`, `users`.`email`, `users`.`username`, `users`.`genderId`, `users`.`roleId`, `gender`.`gender`, `roles`.`role` 
                                                                FROM `users` 
                                                                LEFT JOIN gender USING(genderId) 
                                                                LEFT JOIN roles USING(roleId) 
                                                                WHERE `users`.`userId` = '%d' LIMIT 1", $_SESSION['consort']['userId']));
        
                            ?>
                            <form action="<?php print basename($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="fullname" class="form-label">Fullname:</label>
                                    <input type="text" name="fullname" class="form-control form-control-lg" maxlength="50" id="fullname" placeholder="Enter your name" value="<?php print $spot_profile["fullname"]; ?>">
                                    <?php print (isset($err['nameLetters_err'])) ? "<span class='invalid'>" . $err['nameLetters_err'] . "</span>" : ''; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="email_address" class="form-label">Email Address:</label>
                                    <input type="email" name="email_address" class="form-control form-control-lg" maxlength="50" id="email_address" placeholder="Enter your email address" value="<?php print $spot_profile["email"]; ?>">
                                    <?php print (isset($err['email_format_err'])) ? "<span class='invalid'>" . $err['email_format_err'] . "</span>" : ''; ?>
                                    <?php print (isset($err['mailExists_err'])) ? "<span class='invalid'>" . $err['mailExists_err'] . "</span>" : ''; ?>
                                    <?php print (isset($err['mailDomain_err'])) ? "<span class='invalid'>" . $err['mailDomain_err'] . "</span>" : ''; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username:</label>
                                    <input type="text" name="username" class="form-control form-control-lg" maxlength="50" id="username" placeholder="Enter your username" value="<?php print $spot_profile["username"]; ?>">
                                    <?php print (isset($err['usernameExists_err'])) ? "<span class='invalid'>" . $err['usernameExists_err'] . "</span>" : ''; ?>
                                    <?php print (isset($err['usernameLetters_err'])) ? "<span class='invalid'>" . $err['usernameLetters_err'] . "</span>" : ''; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="genderId" class="form-label">Gender:</label>
                                    <select name="genderId" id="genderId" class="form-control form-control-lg" required>
                                        <option value="<?php print $spot_profile["genderId"]; ?>"><?php print $spot_profile["gender"]; ?></option>
                                        <?php
                                        $spot_gender_rows = $conn->select_while("SELECT * FROM gender");
                                        foreach ($spot_gender_rows as $spot_gender_values) {
                                            if ($spot_gender_values["genderId"] == $spot_profile["genderId"]) {
                                                continue;
                                            }
                                            ?>
                                            <option value="<?php print $spot_gender_values["genderId"]; ?>"><?php print $spot_gender_values["gender"]; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <?php print (isset($err['invalid_selection'])) ? "<span class='invalid'>" . $err['invalid_selection'] . "</span>" : ''; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="roleId" class="form-label">Role:</label>
                                    <select name="roleId" id="roleId" class="form-control form-control-lg" required>
                                        <option value="<?php print $spot_profile["roleId"]; ?>"><?php print $spot_profile["role"]; ?></option>
                                        <?php
                                        $spot_role_rows = $conn->select_while("SELECT * FROM roles");
                                        foreach ($spot_role_rows as $spot_role_values) {
                                            if ($spot_role_values["roleId"] == 1 || $spot_role_values["roleId"] == $spot_profile["roleId"]) {
                                                continue;
                                            }
                                            ?>
                                            <option value="<?php print $spot_role_values["roleId"]; ?>"><?php print $spot_role_values["role"]; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <?php print (isset($err['invalid_selection'])) ? "<span class='invalid'>" . $err['invalid_selection'] . "</span>" : ''; ?>
                                </div>
                                <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
        <?php
            }
            public function search_users(){
                ?>
              <form action="" method="post">
                <input class="form-control form-control-lg" type="text" name="in_put" id="in_put" placeholder="Search..." autofocus>
              </form>
              <br>
              <?php
               }
        }
        ?>


