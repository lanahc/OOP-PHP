<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `service_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>

<style>
    .form-group {
        margin-bottom: 1.5rem;y
    }
    .form-control {
        border: 1px solid #ced4da;
        border-radius: 10px;
        padding: 0.75rem;
        transition: all 0.3s ease;
        font-family: "Times New Roman", Times, serif;
    }
    .form-control:focus {
        border-color: #4b6cb7;
        box-shadow: 0 0 0 0.2rem rgba(75, 108, 183, 0.25);
    }
    .control-label {
        font-weight: bold;
        margin-bottom: 0.5rem;
        font-family: "Times New Roman", Times, serif;
        color: #182848;
    }
    .note-editor {
        border-radius: 10px;
        overflow: hidden;
    }
    .modal-content {
        border-radius: 15px;
        border: none;
    }
    .modal-header {
        background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
        color: white;
        border-radius: 15px 15px 0 0;
    }
    .image-preview {
        max-width: 200px;
        border-radius: 10px;
        margin-top: 10px;
    }
    .price-input {
        position: relative;
    }
    .price-input:before {
        content: '₱';
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
    }
    .price-input input {
        padding-left: 25px;
    }
</style>

<div class="container-fluid">
    <form action="" id="service-form" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        
        <div class="form-group">
            <label for="category" class="control-label">Program Category</label>
            <select name="category" id="category" class="form-control" required>
                <option value="">Select Category</option>
                <option value="daycare" <?= isset($category) && $category == 'daycare' ? "selected" :"" ?>>Daycare Program</option>
                <option value="preschool" <?= isset($category) && $category == 'preschool' ? "selected" :"" ?>>Preschool Program</option>
                <option value="afterschool" <?= isset($category) && $category == 'afterschool' ? "selected" :"" ?>>After School Care</option>
                <option value="special" <?= isset($category) && $category == 'special' ? "selected" :"" ?>>Special Programs</option>
            </select>
        </div>

        <div class="form-group">
            <label for="name" class="control-label">Program Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter program name" value ="<?php echo isset($name) ? $name : '' ?>" required>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price" class="control-label">Program Fee</label>
                    <div class="price-input">
                        <input type="number" name="price" id="price" class="form-control" placeholder="0.00" value="<?php echo isset($price) ? $price : '' ?>" step="0.01" min="0" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="duration" class="control-label">Duration</label>
                    <input type="text" name="duration" id="duration" class="form-control" placeholder="e.g., 3 months, 1 year" value="<?php echo isset($duration) ? $duration : '' ?>" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="age_group" class="control-label">Age Group</label>
            <input type="text" name="age_group" id="age_group" class="form-control" placeholder="e.g., 2-4 years" value="<?php echo isset($age_group) ? $age_group : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="description" class="control-label">Program Description</label>
            <textarea rows="4" name="description" id="description" class="form-control summernote" required><?php echo isset($description) ? $description : '' ?></textarea>
        </div>

        <div class="form-group">
            <label for="program_image" class="control-label">Program Image</label>
            <input type="file" name="program_image" id="program_image" class="form-control" accept="image/*" <?php echo !isset($id) ? 'required' : '' ?>>
            <?php if(isset($image_path) && !empty($image_path)): ?>
                <img src="<?php echo base_url . $image_path ?>" alt="Program Image" class="image-preview mt-2">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="schedule" class="control-label">Schedule Options</label>
            <div class="schedule-options">
                <div class="form-check">
                    <input type="checkbox" name="schedule[]" value="morning" class="form-check-input" id="morning" <?php echo isset($schedule) && strpos($schedule, 'morning') !== false ? 'checked' : '' ?>>
                    <label class="form-check-label" for="morning">Morning (8:00 AM - 12:00 PM)</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="schedule[]" value="afternoon" class="form-check-input" id="afternoon" <?php echo isset($schedule) && strpos($schedule, 'afternoon') !== false ? 'checked' : '' ?>>
                    <label class="form-check-label" for="afternoon">Afternoon (1:00 PM - 5:00 PM)</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" name="schedule[]" value="fullday" class="form-check-input" id="fullday" <?php echo isset($schedule) && strpos($schedule, 'fullday') !== false ? 'checked' : '' ?>>
                    <label class="form-check-label" for="fullday">Full Day (8:00 AM - 5:00 PM)</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="status" class="control-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="1" <?= isset($status) && $status == 1 ? "selected" :"" ?>>Active</option>
                <option value="0" <?= isset($status) && $status == 0 ? "selected" :"" ?>>Inactive</option>
            </select>
        </div>
    </form>
</div>
<script>
    $(function(){
        $('#uni_modal #service-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_service",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp.status == 'success'){
                        location.reload();
                    }else if(!!resp.msg){
                        el.addClass("alert-danger")
                        el.text(resp.msg)
                        _this.prepend(el)
                    }else{
                        el.addClass("alert-danger")
                        el.text("An error occurred due to unknown reason.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    end_loader();
                }
            })
        })
    })
</script>