<?php
if(!isset($_SESSION['login_id']) || $_SESSION['login_type'] != 'parent'){
    header("Location: ./?page=auth");
    exit;
}
?>

<div class="content py-3">
    <div class="container">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">My Children</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary" id="add_child">
                        <i class="fas fa-plus"></i> Add Child
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="children_list">
                    <?php 
                    $children = $conn->query("SELECT * FROM children_list WHERE parent_id = '{$_SESSION['login_id']}' ORDER BY firstname ASC");
                    while($row = $children->fetch_assoc()):
                    ?>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= ucwords($row['firstname'] . ' ' . $row['lastname']) ?></h5>
                                <div class="child-info">
                                    <p><i class="fas fa-birthday-cake mr-2"></i> <?= date("M d, Y", strtotime($row['dob'])) ?></p>
                                    <p><i class="fas fa-user mr-2"></i> <?= $row['gender'] ?></p>
                                    <p><i class="fas fa-layer-group mr-2"></i> <?= $row['age_group'] ?></p>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-sm btn-info edit-child" data-id="<?= $row['id'] ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <!-- Parent Profile Section -->
        <div class="card card-outline card-primary mt-3">
            <div class="card-header">
                <h3 class="card-title">My Profile</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-info" id="edit_profile">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> <?= ucwords($_SESSION['login_firstname'] . ' ' . $_SESSION['login_lastname']) ?></p>
                        <p><strong>Email:</strong> <?= $_SESSION['login_email'] ?></p>
                        <p><strong>Contact:</strong> <?= $_SESSION['login_contact'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Address:</strong> <?= $_SESSION['login_address'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Child Modal -->
<div class="modal fade" id="child_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Child Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="child_form">
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Middle Name</label>
                        <input type="text" name="middlename" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <div class="d-flex">
                            <div class="custom-control custom-radio mr-3">
                                <input class="custom-control-input" type="radio" name="gender" value="Male" required>
                                <label class="custom-control-label">Male</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" name="gender" value="Female">
                                <label class="custom-control-label">Female</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Age Group</label>
                        <input type="text" class="form-control" readonly>
                        <input type="hidden" name="age_group">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_child">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profile_modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="profile_form">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="firstname" class="form-control" value="<?= $_SESSION['login_firstname'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="lastname" class="form-control" value="<?= $_SESSION['login_lastname'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Contact</label>
                        <input type="text" name="contact" class="form-control" value="<?= $_SESSION['login_contact'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" required><?= $_SESSION['login_address'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>New Password (leave blank if no change)</label>
                        <input type="password" name="new_password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_profile">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
$(function(){
    // Add Child
    $('#add_child').click(function(){
        $('#child_form')[0].reset();
        $('#child_form input[name="id"]').val('');
        $('#child_modal').modal('show');
    });

    // Edit Child
    $('.edit-child').click(function(){
        var id = $(this).data('id');
        $.ajax({
            url: _base_url_+'classes/ParentPortal.php?f=get_child',
            method: 'POST',
            data: {id: id},
            dataType: 'json',
            success: function(resp){
                if(resp.status == 'success'){
                    $('#child_form')[0].reset();
                    $('#child_form input[name="id"]').val(resp.data.id);
                    $('#child_form input[name="firstname"]').val(resp.data.firstname);
                    $('#child_form input[name="middlename"]').val(resp.data.middlename);
                    $('#child_form input[name="lastname"]').val(resp.data.lastname);
                    $('#child_form input[name="gender"][value="'+resp.data.gender+'"]').prop('checked', true);
                    $('#child_form input[name="dob"]').val(resp.data.dob);
                    $('#child_form input[name="age_group"]').val(resp.data.age_group);
                    $('#child_modal').modal('show');
                }
            }
        });
    });

    // Save Child
    $('#save_child').click(function(){
        if($('#child_form').valid()){
            $.ajax({
                url: _base_url_+'classes/ParentPortal.php?f=save_child',
                method: 'POST',
                data: $('#child_form').serialize(),
                dataType: 'json',
                success: function(resp){
                    if(resp.status == 'success'){
                        location.reload();
                    }else{
                        alert_toast(resp.msg, 'error');
                    }
                }
            });
        }
    });

    // Edit Profile
    $('#edit_profile').click(function(){
        $('#profile_modal').modal('show');
    });

    // Save Profile
    $('#save_profile').click(function(){
        if($('#profile_form').valid()){
            $.ajax({
                url: _base_url_+'classes/ParentPortal.php?f=update_profile',
                method: 'POST',
                data: $('#profile_form').serialize(),
                dataType: 'json',
                success: function(resp){
                    if(resp.status == 'success'){
                        location.reload();
                    }else{
                        alert_toast(resp.msg, 'error');
                    }
                }
            });
        }
    });
});
</script> 