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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Add base URL definition
    var _base_url_ = '<?php echo base_url ?>';
    
    // Prevent any extension interference
    window.addEventListener('load', function() {
        // Override any problematic functions
        window.getCategory = null;
        
        // Add switch form functionality
        $('#switch_form').click(function(){
            if($('#registration-form').is(':visible')){
                $('#registration-form').hide();
                $('#login-form').show();
                $(this).text('Switch to Registration');
            }else{
                $('#registration-form').show();
                $('#login-form').hide();
                $(this).text('Switch to Login');
            }
        });
        
        // Your existing jQuery code
        $(function(){
            $('#registration-form').submit(function(e){
                e.preventDefault();
                e.stopPropagation();
                
                if($('#password').val() != $('#confirm_password').val()){
                    alert("Passwords do not match");
                    return false;
                }

                $.ajax({
                    url: _base_url_+"classes/Login.php?f=register_parent",
                    data: $(this).serialize(),
                    method: 'POST',
                    dataType: 'json',
                    beforeSend: function(){
                        // Add loading state if needed
                    },
                    success:function(resp){
                        try {
                            // Handle response as JSON
                            if(typeof resp === 'string') {
                                resp = JSON.parse(resp);
                            }
                            
                            if(resp.status == 'success'){
                                alert("Registration successful!");
                                location.href = _base_url_+"?page=parent_dashboard";
                            }else{
                                alert(resp.msg || "Registration failed");
                            }
                        } catch(e) {
                            console.error("JSON Parse error:", e);
                            alert("An error occurred while processing the response");
                        }
                    },
                    error:function(xhr, status, error){
                        console.log("Raw response:", xhr.responseText);
                        console.log("Status:", status);
                        console.log("Error:", error);
                        alert("An error occurred during registration");
                    }
                });
                
                return false;
            });

            $('#login-form').submit(function(e){
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
                    error: function(xhr, status, error) {
                        console.log("XHR Status:", status);
                        console.log("Error:", error);
                        console.log("Response:", xhr.responseText);
                        alert("An error occurred. Status: " + status + ", Error: " + error);
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
                            <form id="registration-form" class="needs-validation">
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
                            <form id="login-form" style="display: none;">
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