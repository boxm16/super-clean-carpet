<?php

class Customer {

    private $id;
    private $emailIdentifier;
            private$password;
    private $passwordConfirmation;
    private $status;
    private $firstName;
    private $lastName;
    private $landlinePhone;
    private $mobilePhone;
    private $street;
    private $district;
    private $floor;
    private $postalCode;
    private $doorbellName;
    private $latitude;
    private $longitude;
    private $note;

    function getId() {
        return $this->id;
    }

    function getEmailIdentifier() {
        return $this->emailIdentifier;
    }

    function getPassword() {
        return $this->password;
    }

    function getPasswordConfirmation() {
        return $this->passwordConfirmation;
    }

    function getStatus() {
        return $this->status;
    }

    function getFirstName() {
        return $this->firstName;
    }

    function getLastName() {
        return $this->lastName;
    }

    function getLandlinePhone() {
        return $this->landlinePhone;
    }

    function getMobilePhone() {
        return $this->mobilePhone;
    }

    function getStreet() {
        return $this->street;
    }

    function getDistrict() {
        return $this->district;
    }

    function getFloor() {
        return $this->floor;
    }

    function getPostalCode() {
        return $this->postalCode;
    }

    function getDoorbellName() {
        return $this->doorbellName;
    }

    function getLatitude() {
        return $this->latitude;
    }

    function getLongitude() {
        return $this->longitude;
    }

    function getNote() {
        return $this->note;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setEmailIdentifier($emailIdentifier) {
        $this->emailIdentifier = $emailIdentifier;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setPasswordConfirmation($passwordConfirmation) {
        $this->passwordConfirmation = $passwordConfirmation;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    function setLandlinePhone($landlinePhone) {
        $this->landlinePhone = $landlinePhone;
    }

    function setMobilePhone($mobilePhone) {
        $this->mobilePhone = $mobilePhone;
    }

    function setStreet($street) {
        $this->street = $street;
    }

    function setDistrict($district) {
        $this->district = $district;
    }

    function setFloor($floor) {
        $this->floor = $floor;
    }

    function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;
    }

    function setDoorbellName($doorbellName) {
        $this->doorbellName = $doorbellName;
    }

    function setLatitude($latitude) {
        $this->latitude = $latitude;
    }

    function setLongitude($longitude) {
        $this->longitude = $longitude;
    }

    function setNote($note) {
        $this->note = $note;
    }

}
