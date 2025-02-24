<?php

class Child {
    private $childID;
    private $name;
    private $age;
    private $parentID; // User ID of the parent

    public function __construct($childID, $name, $age, $parentID) {
        $this->childID = $childID;
        $this->name = $name;
        $this->age = $age;
        $this->parentID = $parentID;
    }

    // Getters for properties
    public function getChildID() {
        return $this->childID;
    }

    public function getName() {
        return $this->name;
    }

    public function getAge() {
        return $this->age;
    }

    public function getParentID() {
        return $this->parentID;
    }
}
?>