<?php
if(isset($_SESSION['login_id'])) {
    header("Location: ./");
    exit;
}
?>

<div class="content py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <?php if(!isset($_GET['token'])): ?>
                <!-- Password Reset Request Form -->
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Reset Password</h3>
                    </div>
                    <div class="card-body">
                        <form id="reset_request_form">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group text-right">
                                <button class="btn btn-primary">Send Reset Link</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php else: ?>
                <!-- Password Reset Form -->
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Create New Password</h3>
                    </div>
                    <div class="card-body">
                        <form id="reset_password_form">
                            <input type="hidden" name="token" value="<?= $_GET['token'] ?>">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <div class="form-group text-right">
                                <button class="btn btn-primary">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    $('#reset_request_form').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: _base_url_+'classes/Login.php?f=reset_request',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(resp){
                if(resp.status == 'success'){
                    alert_toast(resp.msg, 'success');
                    setTimeout(function(){
                        location.href = _base_url_;
                    }, 2000);
                }else{
                    alert_toast(resp.msg, 'error');
                }
            }
        });
    });

    $('#reset_password_form').submit(function(e){
        e.preventDefault();
        if($('input[name="password"]').val() != $('input[name="confirm_password"]').val()){
            alert_toast('Passwords do not match', 'error');
            return false;
        }
        $.ajax({
            url: _base_url_+'classes/Login.php?f=reset_password',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(resp){
                if(resp.status == 'success'){
                    alert_toast(resp.msg, 'success');
                    setTimeout(function(){
                        location.href = _base_url_+'?page=auth';
                    }, 2000);
                }else{
                    alert_toast(resp.msg, 'error');
                }
            }
        });
    });
});
</script> 