<?php

class Inventory {
    private $itemID;
    private $name;
    private $quantity;

    public function __construct($itemID, $name, $quantity) {
        $this->itemID = $itemID;
        $this->name = $name;
        $this->quantity = $quantity;
    }

    public function updateQuantity($newQuantity) {
        $this->quantity = $newQuantity;
        // Logic to update the quantity in the database
    }

    // Getters for properties
    public function getItemID() {
        return $this->itemID;
    }

    public function getName() {
        return $this->name;
    }

    public function getQuantity() {
        return $this->quantity;
    }
}
?>