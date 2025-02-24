<?php
require_once 'user.php'; // Include User class

class StaffUser extends User {
    public function viewBookings() {
        // Implement logic to view bookings
    }

    public function checkInChild(Child $child, Booking $booking) {
        // Implement logic to check in a child
    }

    public function checkOutChild(Child $child, Booking $booking) {
        // Implement logic to check out a child
    }
}
?>