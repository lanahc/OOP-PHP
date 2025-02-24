<?php

class Booking {
    private $bookingID;
    private $child; // Child object
    private $date;
    private $time;
    private $status; // e.g., 'pending', 'confirmed', 'cancelled'

    public function __construct($bookingID, Child $child, $date, $time, $status = 'pending') {
        $this->bookingID = $bookingID;
        $this->child = $child;
        $this->date = $date;
        $this->time = $time;
        $this->status = $status;
    }

    public function updateStatus($newStatus) {
        $this->status = $newStatus;
        // Logic to update the status in the database
    }

    // Getters for properties
    public function getBookingID() {
        return $this->bookingID;
    }

    public function getChild() {
        return $this->child;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTime() {
        return $this->time;
    }

    public function getStatus() {
        return $this->status;
    }
}
?>