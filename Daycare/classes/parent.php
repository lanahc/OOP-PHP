<?php
require_once 'user.php'; // Include User class

class ParentUser extends User {
    private $children = [];
    private $bookings = [];

    public function addChild(Child $child) {
        $this->children[] = $child;
    }

    public function getChildren() {
        return $this->children;
    }

    public function makeBooking(Booking $booking) {
        $this->bookings[] = $booking;
        // Logic to save the booking to the database
    }

    public function viewBookings() {
        return $this->bookings;
    }

    public function cancelBooking(Booking $booking) {
        // Logic to cancel the booking and update database
        $key = array_search($booking, $this->bookings);
        if ($key !== false) {
            unset($this->bookings[$key]);
            return true;
        }
        return false;
    }
}
?>