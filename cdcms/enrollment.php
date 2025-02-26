<?php
// Add this at the top of enrollment.php
if(!isset($_SESSION['login_id'])){
    echo "<script>location.href='./?page=auth';</script>";
    exit;
}
// Add this near the top of the file, after any existing PHP opening tags
$program_id = isset($_GET['program_id']) ? $_GET['program_id'] : '';
if($program_id){
    $program = $conn->query("SELECT * FROM service_list WHERE id = ".$conn->real_escape_string($program_id));
    if($program->num_rows > 0){
        $program_data = $program->fetch_assoc();
    }
}
?>

<div class="content py-3">
    <div class="container-fluid">
        <h3 class="text-center"><b>Enrollment Form</b></h3>
        <?php if(isset($program_data)): ?>
        <h4 class="text-center text-muted">Program: <?= ucwords($program_data['name']) ?></h4>
        <?php endif; ?>
        <hr class="bg-navy">
        <div id="msg-container"></div>
        <div class="card card-outline card-info rounded-0 shadow">
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <form action="" id="enrollment-form">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="program_id" value="<?= $program_id ?>">
                        <fieldset>
                            <legend class="text-navy">Child's Information</legend>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <input type="text" id="child_firstname" name="child_firstname" autofocus class="form-control form-control-sm form-control-border" placeholder="Firstname" required>
                                    <small class="text-muted px-4">First Name</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" id="child_middlename" name="child_middlename" class="form-control form-control-sm form-control-border" placeholder="(optional)">
                                    <small class="text-muted px-4">Middle Name</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" id="child_lastname" name="child_lastname" class="form-control form-control-sm form-control-border" placeholder="Last Name" required>
                                    <small class="text-muted px-4">Last Name</small>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-4 form-group">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <small class="text-muted">Gender</small>
                                        </div>
                                        <div class="form-group col-auto">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="genderMale" name="gender" value="Male" required checked>
                                                <label for="genderMale" class="custom-control-label">Male</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-auto">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="genderFemale" name="gender" value="Female">
                                                <label for="genderFemale" class="custom-control-label">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <input type="date" id="child_dob" name="child_dob" class="form-control form-control-sm form-control-border" required>
                                    <small class="text-muted px-4">Date of Birthday</small>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend class="text-navy">Parent/Guardian's Information</legend>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <input type="text" id="parent_firstname" name="parent_firstname" class="form-control form-control-sm form-control-border" placeholder="Firstname" required>
                                    <small class="text-muted px-4">First Name</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" id="parent_middlename" name="parent_middlename" class="form-control form-control-sm form-control-border" placeholder="Middlname" placeholder="(optional)">
                                    <small class="text-muted px-4">Middle Name</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" id="parent_lastname" name="parent_lastname" class="form-control form-control-sm form-control-border" placeholder="Lastname" required>
                                    <small class="text-muted px-4">Last Name</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <input type="text" id="parent_contact" name="parent_contact" class="form-control form-control-sm form-control-border" placeholder="Contact" required>
                                    <small class="text-muted px-4">Contact #</small>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="text" id="parent_email" name="parent_email" class="form-control form-control-sm form-control-border" placeholder="Email" required>
                                    <small class="text-muted px-4">Email</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <small class="text-muted">Address</small>
                                    <textarea name="address" id="address" rows="3" style="resize:none" class="form-control form-control-sm rounded-0" placeholder="Here Street, There City, Anywhere State, 2306"></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <hr class="bg-navy">
                        <center>
                            <button class="btn btn-lg bg-navy rounded-pill mx-2 col-3">Submit Enrollment</button>
                            <a class="btn btn-lg btn-light border rounded-pill mx-2 col-3" href="./">Cancel</a>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#enrollment-form').submit(function(e){
            e.preventDefault();
            var _this = $(this);
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url: _base_url_+'classes/Master.php?f=save_enrollment',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err)
                    alert_toast("An error occurred", 'error');
                    end_loader();
                },
                success:function(resp){
                    if(typeof resp =='object' && resp.status == 'success'){
                        _this.hide();
                        $('#msg-container').html(`
                            <div class="alert alert-success">
                                ${resp.msg}
                            </div>
                        `);
                        $("html, body").animate({ scrollTop: 0 }, "fast");
                    }else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            $("html, body").animate({ scrollTop: 0 }, "fast");
                    }else{
                        alert_toast("An error occurred", 'error');
                        console.log(resp)
                    }
                    end_loader()
                }
            })
        });
    });
</script>