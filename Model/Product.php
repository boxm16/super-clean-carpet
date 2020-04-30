<?php

class Product {

    private $id;
    private $description;
    private $cleaningPrice;
    private $storingPrice;

    function getId() {
        return $this->id;
    }

    function getDescription() {
        return $this->description;
    }

    function getCleaningPrice() {
        return $this->cleaningPrice;
    }

    function getStoringPrice() {
        return $this->storingPrice;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setCleaningPrice($cleaningPrice) {
        $this->cleaningPrice = $cleaningPrice;
    }

    function setStoringPrice($storingPrice) {
        $this->storingPrice = $storingPrice;
    }

}
