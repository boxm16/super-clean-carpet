<?php

require_once 'Model/Report.php';
require_once 'Model/Item.php';
require_once 'Dao/DataBaseConnection.php';
session_start();

if (isset($_POST['availableDeliveryRoutesIndex'])) {
    $index = $_POST['availableDeliveryRoutesIndex'];
    $itemsString = $_POST['checkedItemsIds'];
    $items = array_map('intval', explode(',', $itemsString));
    $routes = $_SESSION['routes'];
    $route = $routes[$index];
    $routeId = $route['id'];
    $date = $route['date'];
    $report = new Report();
    $report->setRouteId($routeId);
    $report->setDate($date);

    $report->setItems($items);
    $customer = new Customer();
    $customer->setId($_SESSION['id']);
    $report->setType('DELIVERY');
    $report->setCustomer($customer);
    $dataBaseConnection = new DataBaseConnection();
    $dataBaseConnection->createDeliveryReport($report);
    header('Location:main.php');
    exit();
} else if (isset($_POST['cancelDeliveryReport'])) {
    $customerId = $_SESSION['id'];
    $scheduledItems = $_SESSION['scheduledItems'];
    $dataBaseConnection = new DataBaseConnection();
    $dataBaseConnection->cancelDeliveryReport($customerId, $scheduledItems);
    unset($_POST['cancelDeliveryReport']);
    unset($_SESSION['scheduledItems']);
    header('Location:main.php');
    exit();
}

