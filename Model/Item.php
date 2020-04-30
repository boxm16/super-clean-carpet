<?php

require_once 'Product.php';

class Item extends Product {

    private  $spot;
    private $code;
    private $year;
    private $length;
    private $width;
    private $square;
    private $forCleaning;
    private $forStoring;
    private $forMending;
    private $cleaningCharge;
    private $storingCharge;
    private $mendingCharge;
    private $totalCharge;
    private $note;
    private $receivingReportId;
    private $status;
    private $deliveryReportId;

    function __construct() {
        $this->spot = "N/A";
        $this->code = 0;
        $this->year = 0;
        $this->length = 0;
        $this->width = 0;
        $this->square = 0;
        $this->forCleaning = false;
        $this->forStoring = false;
        $this->forMending = false;
        $this->cleaningCharge = 0.00;
        $this->storingCharge = 0.00;
        $this->mendingCharge = 0.00;
        $this->totalCharge = 0.00;
        $this->note = "";
        $this->receivingReportId = 0;
        $this->status = "";
        $this->deliveryReportId = 0;
    }

    function getSpot() {
        return $this->spot;
    }

    function getCode() {
        return $this->code;
    }

    function getYear() {
        return $this->year;
    }

    function getLength() {
        return $this->length;
    }

    function getWidth() {
        return $this->width;
    }

    function getSquare() {
        if ($this->length != null && $this->width != null) {
            $this->square = $this->length * $this->width;
        }
        return $this->square;
    }

    function getForCleaning() {
        return $this->forCleaning;
    }

    function getForStoring() {
        return $this->forStoring;
    }

    function getForMending() {
        return $this->forMending;
    }

    function getCleaningCharge() {
        if ($this->forCleaning) {
            $this->cleaningCharge = round((parent::getCleaningPrice() * $this->getSquare()), 2);
        }
        return $this->cleaningCharge;
    }

    function getStoringCharge() {
        if ($this->forStoring) {
            $this->storingCharge = round((parent::getStoringPrice() * $this->getSquare()), 2);
        }
        return $this->storingCharge;
    }

    function getMendingCharge() {
        return $this->mendingCharge;
    }

    function koko() {
        
    }

    function getTotalCharge() {

        $this->totalCharge = $this->getCleaningCharge() + $this->getStoringCharge() + $this->getMendingCharge();
        return $this->totalCharge;
    }

    function getNote() {
        return $this->note;
    }

    function getReceivingReportId() {
        return $this->receivingReportId;
    }

    function getStatus() {
        return $this->status;
    }

    function getDeliveryReportId() {
        return $this->deliveryReportId;
    }

    function setSpot($spot) {
        $this->spot = $spot;
    }

    function setCode($code) {
        $this->code = $code;
    }

    function setYear($year) {
        $this->year = $year;
    }

    function setLength($length) {
        $this->length = $length;
    }

    function setWidth($width) {
        $this->width = $width;
    }

    function setForCleaning($forCleaning) {
        $this->forCleaning = $forCleaning;
    }

    function setForStoring($forStoring) {
        $this->forStoring = $forStoring;
    }

    function setForMending($forMending) {
        $this->forMending = $forMending;
    }

    function setCleaningCharge($cleaningCharge) {
        $this->cleaningCharge = $cleaningCharge;
    }

    function setStoringCharge($storingCharge) {
        $this->storingCharge = $storingCharge;
    }

    function setMendingCharge($mendingCharge) {
        $this->mendingCharge = $mendingCharge;
    }

    function setTotalCharge($totalCharge) {
        $this->totalCharge = $totalCharge;
    }

    function setNote($note) {
        $this->note = $note;
    }

    function setReceivingReportId($receivingReportId) {
        $this->receivingReportId = $receivingReportId;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setDeliveryReportId($deliveryReportId) {
        $this->deliveryReportId = $deliveryReportId;
    }

}
