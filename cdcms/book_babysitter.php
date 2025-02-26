<?php 
require_once('config.php');
if(!isset($_SESSION['login_id'])) {
    redirect('login.php');
}
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Book a Babysitter</h3>
    </div>
    <div class="card-body">
        <form id="booking-form">
            <div class="form-group">
                <label>Select Child</label>
                <select class="form-control" name="child_id" required>
                    <?php 
                    $children = $conn->query("SELECT * FROM children WHERE parent_id = ".$_SESSION['login_id']);
                    while($row = $children->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['id'] ?>"><?php echo $row['firstname'].' '.$row['lastname'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" class="form-control" name="booking_date" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label>Time Slot</label>
                <select class="form-control" name="time_slot" required>
                    <option value="morning">Morning (8AM - 12PM)</option>
                    <option value="afternoon">Afternoon (1PM - 5PM)</option>
                    <option value="evening">Evening (6PM - 10PM)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Available Babysitters</label>
                <select class="form-control" name="babysitter_id" required>
                    <!-- Will be populated via AJAX -->
                </select>
            </div>
            <div class="form-group">
                <label>Notes</label>
                <textarea class="form-control" name="notes"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Book Now</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){
    // Update available babysitters when date or time slot changes
    $('[name="booking_date"], [name="time_slot"]').change(function(){
        updateAvailableBabysitters();
    });

    function updateAvailableBabysitters() {
        var date = $('[name="booking_date"]').val();
        var time_slot = $('[name="time_slot"]').val();
        if(date && time_slot) {
            $.ajax({
                url: 'classes/Bookings.php?action=get_available',
                method: 'POST',
                data: {date: date, time_slot: time_slot},
                success: function(response){
                    var babysitters = JSON.parse(response);
                    var options = '<option value="">Select Babysitter</option>';
                    babysitters.forEach(function(babysitter){
                        options += `<option value="${babysitter.id}">${babysitter.firstname} ${babysitter.lastname}</option>`;
                    });
                    $('[name="babysitter_id"]').html(options);
                }
            });
        }
    }

    $('#booking-form').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: 'classes/Bookings.php?action=create',
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
});
</script> 