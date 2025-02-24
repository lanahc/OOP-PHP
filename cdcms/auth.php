<?php
if(isset($_SESSION['login_id'])){
    header("Location: ./");
    exit;
}
$program_id = isset($_GET['program_id']) ? $_GET['program_id'] : '';
$redirect = $program_id ? "?page=enrollment&program_id=".$program_id : "";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Parent Registration/Login</title>
    <script>
    // Prevent any extension interference
    window.addEventListener('load', function() {
        // Override any problematic functions
        window.getCategory = null;
        
        // Your existing jQuery code
        $(function(){
            $('#registration_form').submit(function(e){
                e.preventDefault();
                e.stopPropagation(); // Stop event propagation
                
                if($('#password').val() != $('#confirm_password').val()){
                    alert("Passwords do not match");
                    return false;
                }

                $.ajax({
                    url: _base_url_+"classes/Master.php?f=register_parent",
                    data: $(this).serialize(),
                    method: 'POST',
                    dataType: 'json',
                    success:function(resp){
                        if(resp.status == 'success'){
                            alert("Registration successful!");
                            location.href = _base_url_;
                        }else{
                            alert(resp.msg);
                        }
                    },
                    error:function(err){
                        console.log(err);
                        alert("An error occurred");
                    }
                });
                
                return false; // Prevent form submission
            });

            $('#login_form').submit(function(e){
                e.preventDefault();
                e.stopPropagation(); // Stop event propagation
                
                $.ajax({
                    url: _base_url_+"classes/Login.php?f=login_parent",
                    data: $(this).serialize(),
                    method: 'POST',
                    dataType: 'json',
                    success:function(resp){
                        if(resp.status == 'success'){
                            location.href = _base_url_;
                        }else{
                            alert(resp.msg);
                        }
                    },
                    error: function(err) {
    console.log(err);
    alert("An error occurred: " + err.status + " - " + err.statusText);
}
                });
                
                return false; // Prevent form submission
            });
        });
    });
    </script>
</head>
<body>
    <div class="content py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h4 class="card-title">Parent Registration/Login</h4>
                            <div class="card-tools">
                                <button class="btn btn-primary" id="switch_form">Switch to Login</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Registration Form -->
                            <form action="" id="registration_form" class="needs-validation">
                                <input type="hidden" name="redirect" value="<?= $redirect ?>">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="firstname">First Name</label>
                                            <input type="text" class="form-control" id="firstname" name="firstname" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="middlename">Middle Name</label>
                                            <input type="text" class="form-control" id="middlename" name="middlename">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="lastname">Last Name</label>
                                            <input type="text" class="form-control" id="lastname" name="lastname" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="contact">Contact Number</label>
                                    <input type="text" class="form-control" id="contact" name="contact" required>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Register</button>
                                </div>
                            </form>

                            <!-- Login Form -->
                            <form action="" id="login_form" style="display: none;">
                                <input type="hidden" name="redirect" value="<?= $redirect ?>">
                                <div class="form-group">
                                    <label for="login_email">Email Address</label>
                                    <input type="email" class="form-control" id="login_email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="login_password">Password</label>
                                    <input type="password" class="form-control" id="login_password" name="password" required>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <a href="index.php?page=reset_password">Forgot Password?</a>
                                    </div>
                                    <div class="col text-right">
                                        <button type="submit" class="btn btn-primary">Login</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    </style>

    <div id="msg_alert" style="display:none;" class="alert"></div>
</body>
</html> 