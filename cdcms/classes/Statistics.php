<?php
class Statistics {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTotalChildren() {
        $sql = "SELECT COUNT(*) as total FROM children";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getTotalBabysitters() {
        $sql = "SELECT COUNT(*) as total FROM babysitters";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getEnrollmentStats() {
        $sql = "SELECT 
                    status,
                    COUNT(*) as count 
                FROM enrollments 
                GROUP BY status";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBabysitterBookings() {
        $sql = "SELECT 
                    b.name,
                    COUNT(e.id) as booking_count 
                FROM babysitters b 
                LEFT JOIN enrollments e ON b.id = e.babysitter_id 
                GROUP BY b.id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
