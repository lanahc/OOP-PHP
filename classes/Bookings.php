<?php
require_once('../config.php');

class Bookings extends DBConnection {
    public function __construct(){
        parent::__construct();
    }

    public function verify_enrollment($code) {
        $stmt = $this->conn->prepare("
            SELECT id FROM enrollment_list 
            WHERE code = ? AND status = 1
        ");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows == 0) {
            return json_encode([
                'status' => 'failed',
                'msg' => 'Invalid or inactive enrollment code'
            ]);
        }
        
        return json_encode([
            'status' => 'success'
        ]);
    }

    public function get_available_babysitters($date, $time_slot) {
        $stmt = $this->conn->prepare("
            SELECT b.* FROM babysitter_list b 
            WHERE b.status = 1 
            AND b.id NOT IN (
                SELECT babysitter_id 
                FROM babysitter_bookings 
                WHERE booking_date = ? 
                AND time_slot = ? 
                AND status = 1
            )
        ");
        $stmt->bind_param("ss", $date, $time_slot);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function create_booking() {
        extract($_POST);
        
        // Validate inputs
        if(empty($child_id) || empty($babysitter_id) || empty($booking_date) || empty($time_slot) || empty($enrollment_code)) {
            return json_encode([
                'status' => 'failed',
                'msg' => 'All fields are required'
            ]);
        }

        // Verify enrollment code
        $verify = json_decode($this->verify_enrollment($enrollment_code));
        if($verify->status == 'failed') {
            return json_encode([
                'status' => 'failed',
                'msg' => $verify->msg
            ]);
        }

        // Check if slot is still available
        $check = $this->conn->prepare("
            SELECT id FROM babysitter_bookings 
            WHERE babysitter_id = ? 
            AND booking_date = ? 
            AND time_slot = ? 
            AND status = 1
        ");
        $check->bind_param("iss", $babysitter_id, $booking_date, $time_slot);
        $check->execute();
        if($check->get_result()->num_rows > 0) {
            return json_encode([
                'status' => 'failed',
                'msg' => 'This slot is no longer available'
            ]);
        }

        // Create booking
        $stmt = $this->conn->prepare("
            INSERT INTO babysitter_bookings (
                parent_id, child_id, babysitter_id, 
                booking_date, time_slot, notes, enrollment_code
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $parent_id = $_SESSION['login_id'];
        $stmt->bind_param("iiissss", 
            $parent_id, $child_id, $babysitter_id, 
            $booking_date, $time_slot, $notes, $enrollment_code
        );

        if($stmt->execute()) {
            return json_encode([
                'status' => 'success',
                'msg' => 'Booking created successfully'
            ]);
        } else {
            return json_encode([
                'status' => 'failed',
                'msg' => 'Failed to create booking'
            ]);
        }
    }

    public function get_parent_bookings() {
        $parent_id = $_SESSION['login_id'];
        $stmt = $this->conn->prepare("
            SELECT bb.*, 
                   b.firstname as babysitter_firstname, 
                   b.lastname as babysitter_lastname,
                   c.firstname as child_firstname,
                   c.lastname as child_lastname
            FROM babysitter_bookings bb 
            JOIN babysitters b ON b.id = bb.babysitter_id
            JOIN children c ON c.id = bb.child_id
            WHERE bb.parent_id = ?
            ORDER BY bb.booking_date DESC, bb.time_slot
        ");
        $stmt->bind_param("i", $parent_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}

// Handle AJAX requests
if(isset($_GET['action'])) {
    $booking = new Bookings();
    switch($_GET['action']) {
        case 'create':
            echo $booking->create_booking();
            break;
        case 'get_available':
            $date = $_POST['date'];
            $time_slot = $_POST['time_slot'];
            $result = $booking->get_available_babysitters($date, $time_slot);
            $babysitters = array();
            while($row = $result->fetch_assoc()) {
                $babysitters[] = $row;
            }
            echo json_encode($babysitters);
            break;
        case 'verify_enrollment':
            echo $booking->verify_enrollment($_POST['enrollment_code']);
            break;
        case 'list':
            $result = $booking->get_parent_bookings();
            $bookings = array();
            while($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
            echo json_encode($bookings);
            break;
    }
}
?> 