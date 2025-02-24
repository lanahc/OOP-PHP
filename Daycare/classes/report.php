<?php

class Report {
    private $reportID;
    private $type; // e.g., 'daily_bookings', 'monthly_inventory'
    private $data;

    public function __construct($reportID, $type, $data) {
        $this->reportID = $reportID;
        $this->type = $type;
        $this->data = $data;
    }

    public function generate() {
        // Logic to generate the report based on the type and data
        // This might involve querying the database and formatting the data
        return $this->data; // For now, just return the data
    }

    public function export($format) {
        // Logic to export the report to PDF, Excel, etc.
        // Use libraries like TCPDF (for PDF) or PHPExcel (for Excel)
        // based on the $format
        // For now, let's just return a message
        return "Report exported to " . $format;
    }

    // Getters for properties
    public function getReportID() {
        return $this->reportID;
    }

    public function getType() {
        return $this->type;
    }

    public function getData() {
        return $this->data;
    }
}
?>