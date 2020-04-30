<?php

session_start();
require_once 'Dao/DataBaseConnection.php';
require_once 'Model/Customer.php';
if (isset($_GET['getRoute'])) {//--this is if comes request for pickup routes that are scheduled
    $customerId = $_GET['userId'];
    $dataBaseConnection = new DataBaseConnection ();
    $scheduledPickUp = $dataBaseConnection->getScheduledPickUp($customerId);
    if (count($scheduledPickUp) == 0) {
        echo "";
        exit();
    } else {
        $scheduledPickUpId = $scheduledPickUp['id'];
        $_SESSION['scheduledPickUpId'] = $scheduledPickUpId;
        $json = json_encode($scheduledPickUp);
        echo $json;
        exit();
    }//----end for getting scheduled pickups
} elseif (isset($_GET['createPickUp'])) {//-now , a request to create new PickUp report
    $index = $_GET['createPickUp'];
    $routes = $_SESSION['routes'];
    $customerId = $_SESSION['id'];
    $route = $routes[$index];
    $routeId = $route['id'];
    $date = $route['date'];
    $dataBaseConnection = new DataBaseConnection ();
    $dataBaseConnection->createPickUpReport($routeId, $date, $customerId);
} elseif (isset($_GET['cancelPickUp'])) {
    if ($_GET['cancelPickUp'] == 1) {
        $scheduledPickUpId = $_SESSION['scheduledPickUpId'];
        $dataBaseConnection = new DataBaseConnection ();
        $dataBaseConnection->cancelPickUpReport($scheduledPickUpId);
    } else {
        header('Location:errorPage.php');
        exit();
    }
} else {
    echo "";
}
?>
