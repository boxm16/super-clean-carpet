<?php

require_once 'Customer.php';
require_once 'Item.php';

class Report {

    private $id;
    private $date;
    private $number;
    private $customer;
    private $type;
    private $items;
    private $routeId;

    function __construct() {
        $this->id = 0;
        $this->date = "";
        $this->number = 0;
        $this->customer = new Customer();
        $this->items = array();
        $this->routeIid = 0;
    }

    function getType() {
        return $this->type;
    }

    function setType($type) {
        $this->type = $type;
    }

    function getId() {
        return $this->id;
    }

    function getDate() {
        return $this->date;
    }

    function getNumber() {
        return $this->number;
    }

    function getCustomer() {
        return $this->customer;
    }

    function getItems() {
        return $this->items;
    }

   
    function setId($id) {
        $this->id = $id;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function setNumber($number) {
        $this->number = $number;
    }

    function setCustomer($customer) {
        $this->customer = $customer;
    }

    function setItems($items) {
        $this->items = $items;
    }
    function getRouteId() {
        return $this->routeId;
    }

    function setRouteId($routeId) {
        $this->routeId = $routeId;
    }


   

}
