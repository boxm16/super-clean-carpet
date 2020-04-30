<?php

require_once './Model/Customer.php';
require_once './Model/Item.php';
require_once './Model/Report.php';

class DataBaseConnection {

    private $db_connection;

    function __construct() {
        $this->db_connection = new mysqli("remotemysql.com", "2cMB8HiJvS", "rcYF70B1fj", "2cMB8HiJvS");
        if ($this->db_connection->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            exit();
        }
    }

    public function getUserId($identifier, $password) {
        $id = NULL;
        $sql = "SELECT id FROM customer WHERE email_identifier='" . $identifier . "';";
        $result = $this->db_connection->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            if ($row != NULL) {
                $id = $row["id"];
            }
        } return $id;
    }

    public function getUser($id) {
        $sql = "SELECT * FROM customer WHERE id='" . $id . "'";
        $result = $this->db_connection->query($sql);
        if ($result) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $customer = new Customer;
                $customer->setId($row["id"]);
                $customer->setEmailIdentifier("emil_identifier");
                $customer->setStatus($row["status"]);
                $customer->setFirstName($row["first_name"]);
                $customer->setLastName($row["last_name"]);
                $customer->setLandlinePhone($row["landline_phone"]);
                $customer->setMobilePhone($row["mobile_phone"]);
                $customer->setStreet($row["street"]);
                $customer->setDistrict($row["district"]);
                $customer->setPostalCode($row["postal_code"]);
                $customer->setFloor($row["floor"]);
                $customer->setDoorbellName($row["doorbell_name"]);
                $customer->setLongitude($row["longitude"]);
                $customer->setLatitude($row["latitude"]);
                $customer->setNote($row["note"]);

                return $customer;
            }
        } else {
            return null;
        }
    }

    public function getAvailableRoutes($user_id) {
        $sql = "SELECT id, name, day_1, day_2, day_3, day_4, day_5, day_6, day_7  FROM rout " .
                "INNER JOIN rout_lot ON rout.id=rout_lot.rout " .
                "INNER JOIN post_box ON rout_lot.lot=post_box.lot " .
                "WHERE post_box.postal_code='" . $user_id . "' " . " and status='active';";
        $result = $this->db_connection->query($sql);
        $availableRoutes = array();
        $routes = array();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                array_push($availableRoutes, $row);
            }
            $date = new DateTime(NULL, new DateTimeZone('Europe/Athens'));
            $date->modify('+1 day');
            $endDate = new DateTime(Null, new DateTimeZone('Europe/Athens'));
            $endDate->modify('+15 day');
            while ($date < $endDate) {
                foreach ($availableRoutes as $value) {
                    $dayOfWeek = $date->format('D');
                    if ($dayOfWeek == 'Mon' && $value['day_1'] == '1') {
                        $route = array('id' => $value['id'], 'date' => $date->format('Y-m-d'), 'dayOfWeek' => "ΔΕΥΤΕΡΑ", 'name' => $value['name']);
                        array_push($routes, $route);
                    }
                    if ($dayOfWeek == 'Tue' && $value['day_2'] == '1') {
                        $route = array('id' => $value['id'], 'date' => $date->format('Y-m-d'), 'dayOfWeek' => "ΤΡΙΤΗ", 'name' => $value['name']);
                        array_push($routes, $route);
                    }
                    if ($dayOfWeek == 'Wed' && $value['day_3'] == '1') {
                        $route = array('id' => $value['id'], 'date' => $date->format('Y-m-d'), 'dayOfWeek' => "ΤΕΤΑΡΤΗ", 'name' => $value['name']);
                        array_push($routes, $route);
                    }
                    if ($dayOfWeek == 'Thu' && $value['day_4'] == '1') {
                        $route = array('id' => $value['id'], 'date' => $date->format('Y-m-d'), 'dayOfWeek' => "ΠΕΜΠΤΗ", 'name' => $value['name']);
                        array_push($routes, $route);
                    }
                    if ($dayOfWeek == 'Fri' && $value['day_5'] == '1') {
                        $route = array('id' => $value['id'], 'date' => $date->format('Y-m-d'), 'dayOfWeek' => "ΠΑΡΑΣΚΕΥΗ", 'name' => $value['name']);
                        array_push($routes, $route);
                    }
                    if ($dayOfWeek == 'Sat' && $value['day_6'] == '1') {
                        $route = array('id' => $value['id'], 'date' => $date->format('Y-m-d'), 'dayOfWeek' => "ΣΑΒΒΑΤΟ", 'name' => $value['name']);
                        array_push($routes, $route);
                    }
                    if ($dayOfWeek == 'Sun' && $value['day_7'] == '1') {
                        $route = array('id' => $value['id'], 'date' => $date->format('Y-m-d'), 'dayOfWeek' => "ΚΥΡΙΑΚΗ", 'name' => $value['name']);
                        array_push($routes, $route);
                    }
                }

                $date->modify('+1 day');
            }



            $_SESSION['routes'] = $routes;
            return $routes;
        } else {
            return null;
        }
    }

    public function createPickUpReport($routeId, $date, $customerId) {
        $sql = "INSERT INTO report(date, customer_id, type, status, route_id) VALUES('$date',$customerId,'PICKUP','scheduled',$routeId);";
        $this->db_connection->query($sql);
    }

    public function getScheduledPickUp($customerId) {
        $sql = "SELECT report.id, date, name FROM report  INNER JOIN rout ON rout.id=report.route_id  WHERE report.status='scheduled' AND report.type='PICKUP' AND report.customer_id=$customerId LIMIT 1; ";
        $result = $this->db_connection->query($sql);
        $scheduledPickUp = array();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $date = $row['date'];
                $name = $row['name'];
                $scheduledPickUp = array('id' => $id, 'date' => $date, 'name' => $name);
            }
        }
        return $scheduledPickUp;
    }

    public function cancelPickUpReport($scheduledPickUpId) {
        $sql = "UPDATE report SET status='canceled' WHERE id=$scheduledPickUpId ;";
        $this->db_connection->query($sql);
    }

    public function getActiveItems($userId) {
        $activeItems = array();
        $items = $this->getItems($userId);
        foreach ($items as $item) {
            if ($item->getStatus() != 'delivered') {
                array_push($activeItems, $item);
            }
        }
        return $activeItems;
    }

    public function getItems($userId) {
        $sql = "SELECT item.id, item_code, item_year, product_description, cleaning_price, storing_price, "
                . "length, width, cleaning, storing, mending, mending_charge, item.status  "
                . " FROM item "
                . "INNER JOIN product ON item.product_id=product.product_id "
                . "INNER JOIN report ON report.id=item.receiving_report_id"
                . " WHERE customer_id=$userId; ";
        $items = array();
        $result = $this->db_connection->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $item = new Item();
                $item->setId($row['id']);
                $item->setDescription($row['product_description']);
                $item->setCode($row['item_code']);
                $item->setYear($row['item_year']);
                $item->setForCleaning($row['cleaning']);
                $item->setForStoring($row['storing']);
                $item->setForMending($row['mending']);
                $item->setLength($row['length']);
                $item->setWidth($row['width']);
                $item->setCleaningPrice($row['cleaning_price']);
                $item->setStoringPrice($row['storing_price']);
                $item->setMendingCharge($row['mending_charge']);
                $item->setStatus($row['status']);
                array_push($items, $item);
            }
        } return $items;
    }

    function createDeliveryReport($report) {
        $date = $report->getDate();
        $number = $report->getNumber();
        $customerId = $report->getCustomer()->getId();
        $type = "DELIVERY";
        $status = "scheduled";
        $routeId = $report->getRouteId();
        $sql_1 = "INSERT INTO report (date, number, customer_id, type, status, route_id) VALUES ('$date', $number , $customerId, '$type', '$status', $routeId);";
        if ($this->db_connection->query($sql_1) === true) {
            $lastId = $this->db_connection->insert_id;
            $items = $report->getItems();
            $sql_2 = "";
            foreach ($items as $item) {
                $sql_2 .= "UPDATE item SET delivery_report_id=$lastId, status='$status' WHERE id=$item;";
            }
            $this->db_connection->multi_query($sql_2);
        } else {
            echo mysqli_error($this->db_connection);
        }
    }

    function getScheduledDeliveryReportId($customerId) {
        $report = new Report();
        $sql = "SELECT id, date FROM report WHERE customer_id=$customerId and type ='DELIVERY' and status='scheduled';";

        $result = $this->db_connection->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $report->setId($row['id']);
                $report->setDate($row['date']);
            }
        }
        return $report;
    }

    function cancelDeliveryReport($customerId, $scheduledItems) {
        $sql_1 = "UPDATE report SET status='canceled' WHERE customer_id=$customerId and status='scheduled';";
        if ($this->db_connection->query($sql_1) === true) {
            $sql_2 = "";
            foreach ($scheduledItems as $item) {
                $itemId = $item->getId();

                $sql_2 .= "UPDATE item SET delivery_report_id=0, status='ready' WHERE id=$itemId;";
            }
            $this->db_connection->multi_query($sql_2);
        } else {
            echo mysqli_error($this->db_connection);
        }
    }
    
    function updateAddress($customer){
        $street=$customer->getStreet();
        $district=$customer->getDistrict();
        $floor=$customer->getFloor();
        $postalCode=$customer->getPostalCode();
        $doorbellName=$customer->getDoorbellname();
        $landlinePhone=$customer->getLandlinePhone();
        $mobilePhone=$customer->getMobilePhone();
        $latitude=$customer->getLatitude();
        $longitude=$customer->getLongitude();
        $customerId=$customer->getId();
         $sql = "UPDATE customer SET street = '$street', district='$district', floor='$floor', postal_code=$postalCode, doorbell_name='$doorbellName', landline_phone=$landlinePhone, mobile_phone=$mobilePhone, latitude=$latitude, longitude=$longitude WHERE id = $customerId";
        if ($this->db_connection->query($sql) === true) {
     
        } else {
            echo mysqli_error($this->db_connection);
            var_dump($landlinePhone);
           exit();
        }
        
    }

}
