<?php

include_once('Model/Customer.php');
include_once('Dao/DataBaseConnection.php');
session_start();
if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    if (isset($_POST['street'])) {
        $customer = new Customer();
        $customer->setId($userId);
        $customer->setStreet($_POST['street']);
        $customer->setDistrict($_POST['district']);

        $postalCode = $_POST['postalCode'];
        $postalCode = preg_replace('/\s+/', '', $postalCode); //replacing whitespace in postalc code
        $customer->setPostalCode($postalCode);

        $customer->setFloor($_POST['floor']);
        $customer->setDoorbellName($_POST['doorbellName']);
        $customer->setLandlinePhone($_POST['landlinePhone']);
        $customer->setMobilePhone($_POST['mobilePhone']);
        $customer->setLatitude($_POST['latitude']);
        $customer->setLongitude($_POST['longitude']);
        $dataBaseConnection=new DataBaseConnection();
        $dataBaseConnection->updateAddress($customer);
       
        header('Location:main.php');
    }
} else {
    header('Location:errorPage.php');
}
