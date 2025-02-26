<?php 
require_once('../config.php');
if(!isset($_SESSION['login_id']) || $_SESSION['login_type'] != 1):
    redirect('login.php');
endif;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../includes/header.php'); ?>
    <title>Parents List | <?php echo $_settings->info('name') ?></title>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        <?php include('../includes/topbar.php'); ?>
        <?php include('../includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Parents List</h1>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Parents</h3>
                            <div class="card-tools">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addParentModal">
                                    <i class="fas fa-plus"></i> Add New Parent
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="parents-list" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    $qry = $conn->query("SELECT * FROM parent_list ORDER BY lastname ASC, firstname ASC");
                                    while($row = $qry->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $row['lastname'].', '.$row['firstname'].' '.$row['middlename'] ?></td>
                                        <td><?php echo $row['email'] ?></td>
                                        <td><?php echo $row['contact'] ?></td>
                                        <td>
                                            <?php if($row['status'] == 1): ?>
                                                <span class="badge badge-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm view_parent" data-id="<?php echo $row['id'] ?>">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm edit_parent" data-id="<?php echo $row['id'] ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm delete_parent" data-id="<?php echo $row['id'] ?>">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Add Parent Modal -->
    <div class="modal fade" id="addParentModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="parent-form">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New Parent</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
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
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Contact</label>
                            <input type="text" name="contact" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <script>
    $(document).ready(function(){
        $('#parents-list').DataTable();

        // View Parent
        $('.view_parent').click(function(){
            var id = $(this).data('id');
            $.ajax({
                url: '../classes/Parents.php?action=get_single',
                method: 'POST',
                data: {id: id},
                success: function(response){
                    var data = JSON.parse(response);
                    // Implement view logic here
                }
            });
        });

        // Edit Parent
        $('.edit_parent').click(function(){
            var id = $(this).data('id');
            $('#addParentModal .modal-title').text('Edit Parent');
            $.ajax({
                url: '../classes/Parents.php?action=get_single',
                method: 'POST',
                data: {id: id},
                success: function(response){
                    var data = JSON.parse(response);
                    $('#parent-form [name="id"]').val(data.id);
                    $('#parent-form [name="firstname"]').val(data.firstname);
                    $('#parent-form [name="middlename"]').val(data.middlename);
                    $('#parent-form [name="lastname"]').val(data.lastname);
                    $('#parent-form [name="email"]').val(data.email);
                    $('#parent-form [name="contact"]').val(data.contact);
                    $('#parent-form [name="address"]').val(data.address);
                    $('#parent-form [name="status"]').val(data.status);
                    $('#addParentModal').modal('show');
                }
            });
        });

        // Save Parent
        $('#parent-form').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: '../classes/Parents.php?action=save',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response){
                    var resp = JSON.parse(response);
                    if(resp.status == 'success'){
                        alert(resp.msg);
                        location.reload();
                    } else {
                        alert(resp.msg);
                    }
                }
            });
        });

        // Delete Parent
        $('.delete_parent').click(function(){
            if(confirm('Are you sure you want to delete this parent?')){
                var id = $(this).data('id');
                $.ajax({
                    url: '../classes/Parents.php?action=delete',
                    method: 'POST',
                    data: {id: id},
                    success: function(response){
                        var resp = JSON.parse(response);
                        if(resp.status == 'success'){
                            alert(resp.msg);
                            location.reload();
                        } else {
                            alert(resp.msg);
                        }
                    }
                });
            }
        });
    });
    </script>
</body>
</html>