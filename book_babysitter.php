<?php 
require_once('config.php');
if(!isset($_SESSION['login_id'])) {
    redirect('login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Babysitter</title>
    
    <!-- Add jQuery before any scripts that use it -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
    <!-- If you don't have jQuery locally, use CDN: -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    
    <!-- Include Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Your existing header -->
    <?php require_once('inc/header.php') ?>
    
    <style>
        .booking-container {
            max-width: 800px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .booking-header {
            background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .booking-body {
            padding: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-control {
            border: 2px solid #e1e5eb;
            border-radius: 10px;
            padding: 0.75rem;
        }
        .btn-book {
            background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            border: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <div class="booking-header">
            <h3>Book a Babysitter</h3>
            <p>Schedule your childcare service</p>
        </div>
        
        <div class="booking-body">
            <form id="booking-form">
                <div class="form-group">
                    <label>Enrollment Code</label>
                    <input type="text" class="form-control" name="enrollment_code" required>
                    <small class="text-muted">Enter the enrollment code provided during registration</small>
                </div>

                <div class="form-group">
                    <label>Select Child</label>
                    <select class="form-control" name="child_id" required>
                        <option value="">Select Child</option>
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
                        <option value="">Select Time Slot</option>
                        <option value="morning">Morning (8AM - 12PM)</option>
                        <option value="afternoon">Afternoon (1PM - 5PM)</option>
                        <option value="evening">Evening (6PM - 10PM)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Available Babysitters</label>
                    <select class="form-control" name="babysitter_id" required>
                        <option value="">Please select date and time first</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <textarea class="form-control" name="notes" rows="3"></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-book">Book Now</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Move script to after jQuery is loaded -->
    <script>
    // Wait for document to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Now use jQuery
        $(document).ready(function(){
        
            $('[name="enrollment_code"]').on('change', function() {
                var code = $(this).val();
                if(code) {
                    $.ajax({
                        url: 'classes/Bookings.php?action=verify_enrollment',
                        method: 'POST',
                        data: {enrollment_code: code},
                        success: function(response){
                            var resp = JSON.parse(response);
                            if(resp.status == 'failed'){
                                alert(resp.msg);
                                $('[name="enrollment_code"]').val('');
                            }
                        }
                    });
                }
            });

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
    });
    </script>

    <!-- Include your footer -->
    <?php require_once('inc/footer.php') ?>
</body>
</html>