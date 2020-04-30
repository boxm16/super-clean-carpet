<?php
require_once 'Model/Customer.php';
require_once 'Dao/DataBaseConnection.php';
require_once 'Model/Item.php';
require_once 'Model/Report.php';
session_start();


if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $dataBaseConnection = new DataBaseConnection();
    $customer = $dataBaseConnection->getUser($userId);
    $routes = $dataBaseConnection->getAvailableRoutes($customer->getPostalCode());
    $activeItems = $dataBaseConnection->getActiveItems($userId);
    $deliveryReport = $dataBaseConnection->getScheduledDeliveryReportId($customer->getId());
    $scheduledItems = array();
    $processingItems = array();
    $readyItems = array();
    $scheduledItemsTotalCharge = 0;
    foreach ($activeItems as $item) {
        if ($item->getStatus() == 'scheduled') {
            array_push($scheduledItems, $item);
        } else if ($item->getStatus() == 'ready') {
            array_push($readyItems, $item);
            array_push($processingItems, $item);
        } else {
            array_push($processingItems, $item);
        }
    }
    $_SESSION['scheduledItems'] = $scheduledItems;

    if ($routes == null) {
        header("Location:errorPage.php");
        exit();
    }
} else {
    header("Location:index.php");
    exit();
}
?>
<!DOCTYPE html>


<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <title>MAIN PAGE</title>


        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                setNewPickUpRequestWindow();
                showMyPickUpOrder();
                displayTotalPay();
                setNewDeliveryRequestWindow();
            });
        </script>
    </head>
    <body >



        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <div class="table-responsive">
                        <div id="pageTop" style="width: 100%; height: 80px; background: url(style/headerSliver.jpg) repeat-x;">
                            <div id="pageTopLogo" style="margin-left: 30px; margin-bottom: 10px; height:80px"  >
                                <a href="index.php"> <img src="images/topLogo.jpg" alt="logo" title="Super Clean Carpet"></a>
                                <h4 style="float:right; margin: 20px;"><?php echo $customer->getFirstName() ?>&nbsp;&nbsp;<?php echo $customer->getLastName() ?></h4>

                            </div>
                            <div>
                                <a href="logout.php" style="float:right; margin-right: 50px;">Log Out</a>
                            </div>
                        </div>
                        <div>
                            <center> <h4>Η ΔΙΕΥΘΥΝΣΗ ΜΟΥ</h4></center>

                            <table class="table table-bordered">
                                <tr>
                                    <th> ΟΔΟΣ </th>
                                    <td><?php echo $customer->getStreet() ?> </td>
                                    <td colspan="2">
                                        <button class="btn btn-warning btn-sm btn-block"   onclick="location.href = 'address.php'">MODIFY ADDRESS</button>
                                    </td>
                                </tr>
                                <tr>
                                    <th> ΠΕΡΙΟΧΗ </th>
                                    <td><?php echo $customer->getDistrict() ?> </td>
                                    <th> ΣΤΑΘΕΡΟ ΤΗΛΕΦΩΝΟ </th>
                                    <td><?php echo $customer->getLandlinePhone() ?> </td>
                                </tr>
                                <tr>
                                    <th> Τ.Κ. </th>
                                    <td><?php echo $customer->getPostalCode() ?> </td>
                                    <th> ΚΙΝΗΤΟ ΤΗΛΕΦΩΝΟ </th>
                                    <td><?php echo $customer->getMobilePhone() ?> </td>
                                </tr>
                                <tr>
                                    <th> ΟΡΟΦΟΣ </th>
                                    <td><?php echo $customer->getFloor() ?> </td>
                                    <th> ΟΝΟΜΑ ΣΤΟ ΚΟΥΔΟΥΝΙ </th>
                                    <td><?php echo $customer->getDoorbellName() ?> </td>
                                </tr>
                            </table>
                        </div>

                        <hr>



                        <!-- PICK UP TABLE start-->
                        <h1><center>PICK UP</center></h1>
                        <table class="table table-bordered">
                            <tr>
                                <td id="myPickUpOrderRow">   <h5> <div id="myPickUpOrderDisplay"></div></h5></td>
                                <td> <!-- Button trigger modal -->
                                    <button type="button" id="newPickUpButton" class="btn btn-primary btn-block" data-toggle="modal" data-target="#newPickUpRequestWindow">
                                        ΖΗΤΗΣΕ ΝΕΟ PICK-UP
                                    </button>
                                    <button type="button" id="cancelMyPickUpButton" class="btn btn-danger btn-block" data-toggle="modal" data-target="#cancelMyPickUpRequestWindow">
                                        ΑΚΥΡΩΣΕ PICK-UP
                                    </button></td>
                            </tr>
                        </table>
                        <hr>
                        <!-- PICK UP TABLE end-->


                        <!-- Modal for pick-up request start-->
                        <div class="modal fade" id="newPickUpRequestWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">ΔΙΑΛΕΞΕ ΔΡΟΜΟΛΟΓΙΟ ΓΙΑ PICK-UP</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <center> <h3><div id="noAvailablePickUpRoutesField"></div></h3></center><!-- if there is no avaliable routes, here will be displayed message -->
                                        <div class="modal-body">
                                            <h5>  <select onchange="onSelectPickUp()" name='availablePickUpRoutes' id="availablePickUpRoutes">


                                                    <?php
                                                    for ($index = 0; $index < count($routes); $index++) {
                                                        $route = $routes[$index];
                                                        $id = $route['id'];
                                                        $date = $route['date'];
                                                        $day = $route['dayOfWeek'];
                                                        $name = $route['name'];
                                                        $routeString = $date . '-' . $day . '-' . $name;
                                                        echo "<option value='$index'>$routeString</option>";
                                                    }
                                                    ?>
                                                </select></h5>
                                            <input id="availablePickUpRoutesIndex" name="availablePickUpRoutesIndex" type="text" hidden="hidden">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ΑΚΥΡΩΣΗ</button>
                                            <button id="createNewPickUpRequestButton" data-dismiss="modal" type="button" class="btn btn-primary" onclick="createPickUpReport()">ΕΠΙΛΟΓΗ</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end of modal window for pick-up request-->  

                        <!-- Modal for pick-up cancel start -->
                        <div class="modal fade" id="cancelMyPickUpRequestWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">ΠΡΟΣΟΧΗ</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>ΕΙΣAI ΣΙΓΟΥΡΟΣ ΟΤΙ ΘΕΛΕΙΣ ΝΑ ΑΚΥΡΩΣΕΙΣ ΠΡΟΓΡΑΜΜΑΤΙΣΜΕΝΟ PICK-UP</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">OXI</button>
                                            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="cancelPickUpReport()">ΝΑΙ, ΘΕΛΩ ΝΑ ΑΚΥΡΩΣΩ PICK-UP</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end of modal window for pick-up cancel-->  
                        <hr>

                        <!--DELIVERY TABLE START -->
                        <h1><center>DELIVERY</center></h1>
                        <table class="table table-bordered">
                            <tr>
                                <td id="myDeliveryOrderRow" <?php
                                if ($deliveryReport->getId() != NULL) {
                                    echo 'style="background-color:blue;"';
                                }
                                ?>>   
                                    <h5> <div id="myDeliveryOrderDisplay">
                                            <?php
                                            if ($deliveryReport->getId() != NULL) {
                                                echo "<center>ΕΧΕΤΕ ΠΡΟΓΡΑΜΜΑΤΙΣΜΕΝΟ ΡΑΝΤΕΒΟΥ ΓΙΑ DELIVERY ΣΤΙΣ " . $deliveryReport->getDate() . "</center>";
                                            } else {
                                                echo "<center>ΔΕΝ ΕΧΕΤΕ ΠΡΟΓΡΑΜΜΑΤΙΣΜΕΝΟ ΡΑΝΤΕΒΟΥ ΓΙΑ DELIVERY</center>";
                                            }
                                            ?>
                                        </div>
                                    </h5>
                                </td>
                            </tr>
                        </table>
                        <!-- DELIVERY ITEMS -->
                        <form action="controller.php" method="POST">

                            <table id="deliveryTable" class="table-hover " border="5" width="100%">
                                <thead>
                                <th>ΠΕΡΙΓΡΑΦΗ</th>
                                <th>ΚΟΔ.</th>
                                <th>ΧΡΝ.</th>

                                <th>Κ.</th>
                                <th>Φ.</th>
                                <th>Ε.</th>

                                <th>ΜΗΚ.</th>
                                <th>ΠΛΤ.</th>
                                <th>ΤΡΓ.</th>

                                <th>Τ.Κ.</th>
                                <th>Τ.Φ.</th>


                                <th>Χ.Κ.</th>
                                <th>Χ.Φ.</th>
                                <th>Χ.Ε.</th>
                                <th>ΣΗΜΕΙΩΣΕΙΣ</th>
                                <th>ΣΥΝ.</th>


                                </thead>


                                <?php
                                foreach ($scheduledItems as $item) {
                                    $scheduledItemsTotalCharge += $item->getTotalCharge();
                                    ?>

                                    <tr class="scheduledItem" style="font-size: 20px; <?php
                                    echo 'background-color:blue;';
                                    ?>">
                                        <td><?php echo $item->getDescription(); ?></td> 
                                        <td><?php echo $item->getCode(); ?></td>
                                        <td><?php echo $item->getYear(); ?></td>
                                        <td><input type="checkbox" disabled="true" <?php echo ($item->getForCleaning() == 1 ? 'checked' : ''); ?> ></td>
                                        <td><input type="checkbox" disabled="true" <?php echo ($item->getForStoring() == 1 ? 'checked' : ''); ?> ></td>
                                        <td><input type="checkbox" disabled="true" <?php echo ($item->getForMending() == 1 ? 'checked' : ''); ?> ></td>

                                        <td><?php echo $item->getLength(); ?></td>
                                        <td><?php echo $item->getWidth(); ?></td>
                                        <td><?php echo $item->getSquare(); ?></td>

                                        <td><?php echo $item->getCleaningPrice(); ?></td>
                                        <td><?php echo $item->getStoringPrice(); ?></td>

                                        <td><?php echo $item->getCleaningCharge(); ?></td>

                                        <td><?php echo $item->getStoringCharge(); ?></td>

                                        <td><?php echo $item->getMendingCharge(); ?></td>

                                        <td><?php echo $item->getNote(); ?></td>

                                        <td class="scheduleItemTotal"><?php echo $item->getTotalCharge() ?></td>
                                        <td class="itemId" hidden=""> <?php echo $item->getId() ?> </td>

                                    </tr>

                                <?php } ?>
                                <tr>
                                    <td colspan='14' rowspan="4" >
                                        <button <?php if ($deliveryReport->getId() == NULL) echo "disabled"; ?> type="button" id="newDeliveryButton" class="btn btn-success btn-block" data-toggle="modal" data-target="#cancelDeliveryRequestWindow">
                                            ΑΚΥΡΩΣΕ DELIVERY
                                        </button>
                                    </td>
                                </tr>
                                <tr style="font-weight:  bold;">
                                    <td style="font-weight:  bold;">ΜΕΡΙΚΟ ΣΥΝΟΛΟ</td>
                                    <td id="scheduledTotal"><?php echo round($scheduledItemsTotalCharge, 2); ?></td>
                                </tr>
                                <tr style="font-weight:  bold;">
                                    <td >ΦΠΑ 24%</td>
                                    <td id="scheduledFPA"><?php echo round($scheduledItemsTotalCharge * 0.24, 2); ?></td>
                                </tr>
                                <tr style="font-weight:  bold;">
                                    <td >ΤΕΛΙΚΟ ΣΥΝΟΛΟ</td>
                                    <td id="scheduledPayTotal"><?php echo round($scheduledItemsTotalCharge + ($scheduledItemsTotalCharge * 0.24), 2); ?></td>
                                </tr>
                            </table>
                            <input name="cancelDeliveryReport" type="text" id="" value="1" hidden="true">
                            <!-- Modal for delivery request start-->
                            <div class="modal fade" id="cancelDeliveryRequestWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">ΠΡΟΣΟΧΗ</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>ΕΙΣAI ΣΙΓΟΥΡΟΣ ΟΤΙ ΘΕΛΕΙΣ ΝΑ ΑΚΥΡΩΣΕΙΣ ΠΡΟΓΡΑΜΜΑΤΙΣΜΕΝΟ DELIVERY</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">OXI</button>
                                                <button type="submit" class="btn btn-primary" >ΝΑΙ, ΘΕΛΩ ΝΑ ΑΚΥΡΩΣΩ DELIVERY</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end of modal window for delivery request-->  
                        </form>


                        <!--DELIVERY ITEMS END -->



                        <form action="controller.php" method="POST">

                            <h1>ΤΑ ΤΕΜΑΧΙΑ ΜΟΥ ΥΠΟ ΤΗΝ ΕΠΕΞΕΡΓΑΣΙΑ</h1>
                            <table id="customerTable" class="table-hover " border="5" width="100%">
                                <thead>
                                <th>ΠΕΡΙΓΡΑΦΗ</th>
                                <th>ΚΟΔ.</th>
                                <th>ΧΡΝ.</th>

                                <th>Κ.</th>
                                <th>Φ.</th>
                                <th>Ε.</th>

                                <th>ΜΗΚ.</th>
                                <th>ΠΛΤ.</th>
                                <th>ΤΡΓ.</th>

                                <th>Τ.Κ.</th>
                                <th>Τ.Φ.</th>


                                <th>Χ.Κ.</th>
                                <th>Χ.Φ.</th>
                                <th>Χ.Ε.</th>

                                <th>ΣΥΝ.</th>
                                <th>ΣΗΜΕΙΩΣΕΙΣ</th>
                                <th>STATUS</th>
                                <th>ΔΙΑΛΟΓΗ</th>
                                </thead>


                                <?php foreach ($processingItems as $item) { ?>
                                    <tr class="item" style="font-size: 20px; <?php
                                    if ($item->getStatus() == 'ready') {
                                        echo 'background-color: green;';
                                    } elseif ($item->getStatus() == 'processing') {
                                        echo 'background-color:red;';
                                    }
                                    ?>">
                                        <td><?php echo $item->getDescription(); ?></td> 
                                        <td><?php echo $item->getCode(); ?></td>
                                        <td><?php echo $item->getYear(); ?></td>
                                        <td><input type="checkbox" disabled="true" <?php echo ($item->getForCleaning() == 1 ? 'checked' : ''); ?> ></td>
                                        <td><input type="checkbox" disabled="true" <?php echo ($item->getForStoring() == 1 ? 'checked' : ''); ?> ></td>
                                        <td><input type="checkbox" disabled="true" <?php echo ($item->getForMending() == 1 ? 'checked' : ''); ?> ></td>

                                        <td><?php echo $item->getLength(); ?></td>
                                        <td><?php echo $item->getWidth(); ?></td>
                                        <td><?php echo $item->getSquare(); ?></td>

                                        <td><?php echo $item->getCleaningPrice(); ?></td>
                                        <td><?php echo $item->getStoringPrice(); ?></td>

                                        <td><?php echo $item->getCleaningCharge(); ?></td>
                                        <td><?php echo $item->getStoringCharge(); ?></td>
                                        <td><?php echo $item->getMendingCharge(); ?></td>


                                        <td class="itemTotal"><?php echo $item->getTotalCharge(); ?></td>
                                        <td><?php echo $item->getNote(); ?></td>
                                        <td><?php echo $item->getStatus(); ?></td>
                                        <td>
                                            <input class="checker" type="checkbox" onclick="displayTotalPay()" <?php
                                            if ($item->getStatus() == 'processing') {
                                                echo 'disabled="true"';
                                            } elseif ($item->getStatus() == 'ready') {
                                                echo 'checked';
                                            }
                                            ?> >
                                        </td>
                                        <td class="itemId" hidden=""> <?php echo $item->getId() ?> </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan='13' rowspan="4" >
                                        <p>ΣΗΜΕΙΩΣΕΙΣ:ΚΟΔ-κοδικός τεμαχίου, ΧΡΝ-χρονιά, Κ-τεμάχιο είναι για καθάρισμα, 
                                            Φ-τεμάχιο είναι για φύλαξη, Ε-τεμάχιο είναι για επιδιόρθωση, ΜΗΚ-μήκος τεμαχίου, 
                                            ΠΛΤ-πλάτος τεμαχίου, ΤΡΓ-τετραγωνικά τεμαχίου, Τ.Κ.-τιμή καθαρίσματος ανά τετραγωνικό μέτρο,
                                            Τ.Φ.-τιμή φύλαξης ανά τετραγωνικό μέτρο, Χ.Κ-χρέωση για καθάρισμα, Χ.Φ-χρέωση για φύλαξη, 
                                            Χ.Ε-χρέωση για επιδιόρθωση, ΣΥΝ-σύνολο χρέωση για τεμάχιο.
                                        </p>
                                    </td>
                                </tr>
                                <tr style="font-weight:  bold;">
                                    <td style="font-weight:  bold;">ΜΕΡΙΚΟ ΣΥΝΟΛΟ</td>
                                    <td id="total"></td>
                                    <td colspan="3" rowspan="3">
                                        <button <?php
                                        if (count($scheduledItems) != 0 || count($readyItems) == 0) {
                                            echo "disabled";
                                        }
                                        ?> type="button" id="askDeliveryButton" class="btn btn-success btn-block" data-toggle="modal" data-target="#newDeliveryRequestWindow">

                                            ΖΗΤΗΣΕ DELIVERY
                                        </button>
                                        <?php if ($deliveryReport->getId() != NULL) echo "<h6> Εφόσον έχετε ήδη προγραμματισμένο ραντεβου για Delivery, δεν μπορήτε να ζητήσετε καινούριο ραντεβού. Για να ζητήσετε καινούριο ραντεβού ακυρώστε πρώτα ήδη προγραμματισμένο ραντεβού για Delivery</h6>"; ?>
                                    </td>

                                </tr>
                                <tr style="font-weight:  bold;">
                                    <td >ΦΠΑ 24%</td>
                                    <td id="fpa"></td>

                                </tr>
                                <tr style="font-weight:  bold;">
                                    <td >ΤΕΛΙΚΟ ΣΥΝΟΛΟ</td>
                                    <td id="payTotal"></td>

                                </tr>

                            </table>
                            <input name="checkedItemsIds" type="text" id="checkedItemsIds" hidden="true">
                            <!-- Modal for delivery request start-->
                            <div class="modal fade" id="newDeliveryRequestWindow" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">ΔΙΑΛΕΞΕ ΔΡΟΜΟΛΟΓΙΟ ΓΙΑ DELIVERY</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <center> <h3><div id="noAvailableDeliveryRoutesField"></div></h3></center><!-- if there is no avaliable routes, here will be displayed message -->
                                            <div class="modal-body">
                                                <h5>  <select onchange="onSelectDelivery()"  id="availableDeliveryRoutes">


                                                        <?php
                                                        for ($index = 0; $index < count($routes); $index++) {
                                                            $route = $routes[$index];
                                                            $id = $route['id'];
                                                            $date = $route['date'];
                                                            $day = $route['dayOfWeek'];
                                                            $name = $route['name'];
                                                            $routeString = $date . '-' . $day . '-' . $name;
                                                            echo "<option value='$index'>$routeString</option>";
                                                        }
                                                        ?>
                                                    </select></h5>
                                                <input id="availableDeliveryRoutesIndex" name="availableDeliveryRoutesIndex" type="text" hidden="hidden">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ΑΚΥΡΩΣΗ</button>
                                                <button id="createNewDeliveryRequestButton"  type="submit" class="btn btn-primary" ">ΕΠΙΛΟΓΗ</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end of modal window for delivery request-->  
                        </form>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function setNewPickUpRequestWindow() {
                if ("<?php echo count($routes) > 0 ?>") {
                    $("#createNewPickUpRequestButton").prop('disabled', false);
                    $("#availablePickUpRoutes").prop('hidden', false);
                    $("#availablePickUpRoutesIndex").val("0");
                } else {
                    $("#createNewPickUpRequestButton").prop('disabled', true);
                    $("#noAvailablePickUpRoutesField").append("ΔΕΝ ΥΠΑΡΧΟΥΝ ΑΥΤΗ ΤΗ ΣΤΙΜΓΗ ΔΡΟΜΟΛΟΓΙΑ");
                    $("#availablePickUpRoutes").prop('hidden', true);
                }

            }

            function setNewDeliveryRequestWindow() {
                if ("<?php echo count($routes) > 0 ?>") {
                    $("#createNewDeliveryRequestButton").prop('disabled', false);
                    $("#availableDeliveryRoutes").prop('hidden', false);
                    $("#availableDeliveryRoutesIndex").val("0");
                } else {
                    $("#createNewDeliveryRequestButton").prop('disabled', true);
                    $("#noAvailableDeliveryRoutesField").append("ΔΕΝ ΥΠΑΡΧΟΥΝ ΑΥΤΗ ΤΗ ΣΤΙΜΓΗ ΔΡΟΜΟΛΟΓΙΑ");
                    $("#availableDeliveryRoutes").prop('hidden', true);
                }

            }


            function onSelectPickUp() {
                var index = document.getElementById("availablePickUpRoutes").selectedIndex;
                document.getElementById("availablePickUpRoutesIndex").value = index;
            }
            function onSelectDelivery() {

                var index = document.getElementById("availableDeliveryRoutes").selectedIndex;
                document.getElementById("availableDeliveryRoutesIndex").value = index;
            }


            function showMyPickUpOrder() {
                $("#myPickUpOrderDisplay").empty();
                $.ajax({url: 'ajax.php?getRoute=1&userId="<?php echo $customer->getId(); ?>" '
                    , contentType: 'application/json; charset=UTF-8',
                    success: function (result) {
                        //i couldnt get greek characters rigth here, only ?? coming, so i use only numbers
                        if (result == "") {
                            $("#myPickUpOrderDisplay").append("ΔΕΝ ΕΧΕΤΕ ΠΡΟΓΡΑΜΜΑΤΙΣΜΕΝO ΡΑΝΤΕΒΟΥ ΓΙΑ PICK_UP");
                            $("#newPickUpButton").prop('hidden', false);
                            $("#cancelMyPickUpButton").prop('hidden', true);
                            $("#myPickUpOrderRow").css({'background-color': 'white'});
                        } else {
                            var jsonObj = JSON.parse(result);
                            //i couldnt get greek characters rigth here, only ?? coming,for parameter 'name', so i use only numbers
                            $("#myPickUpOrderDisplay").append("ΕΧΕΤΕ ΠΡΟΓΡΑΜΜΑΤΙΣΜΕΝΟ  ΡΑΝΤΕΒΟΥ ΓΙΑ PICK-UP ΣΤΙΣ " + jsonObj.date);
                            $("#pickUpReportId").append(jsonObj.id);
                            $("#newPickUpButton").prop('hidden', true);
                            $("#cancelMyPickUpButton").prop('hidden', false);
                            $("#myPickUpOrderRow").css({'background-color': '#90EE90'});
                        }
                    }
                }
                );
            }

            function  createPickUpReport() {
                var index = $("#availablePickUpRoutesIndex").val();
                $("#myPickUpOrderDisplay").empty();
                $("#myPickUpOrderDisplay").append("ΑΙΤΗΣΗ ΕΠΕΞΕΡΓΑΖΕΤΕ");
                $.ajax({url: 'ajax.php?createPickUp=' + index, contentType: 'application/json; charset=UTF-8',
                    success: function (result) {
                        showMyPickUpOrder();
                        // $("#myPickUpOrderDisplay").append(result);

                    }
                }
                );
            }
            function cancelPickUpReport() {
                $("#myPickUpOrderDisplay").empty();
                $("#myPickUpOrderDisplay").append("ΑΙΤΗΣΗ ΑΚΗΡΩΣΗΣ ΕΠΕΞΕΡΓΑΖΕΤΕ");
                $.ajax({url: 'ajax.php?cancelPickUp=1', contentType: 'application/json; charset=UTF-8',
                    success: function () {
                        showMyPickUpOrder();
                    }
                });
            }

            function stringifyCheckedItems() {

                var itemsIds = document.getElementsByClassName('itemId');
                var checker = document.getElementsByClassName('checker');
                var selectedItemsIds = [];
                var a;
                for (a = 0; a < checker.length; a++) {
                    if (checker[a].getAttribute('checked') != null) {
                        if (checker[a].checked)
                            selectedItemsIds.push(itemsIds[a].innerHTML);
                    }
                }
                var itemIdsString = selectedItemsIds.join(',');
                document.getElementById('checkedItemsIds').value = itemIdsString;

            }

            function displayTotalPay() {
                var totalRows = document.getElementsByClassName('itemTotal');
                var checker = document.getElementsByClassName('checker');
                var askDeliveryButton = document.getElementById('askDeliveryButton');
                askDeliveryButton.setAttribute('disabled', 'true');
                var total = 0;
                var x;
                for (x = 0; x < totalRows.length; x++) {

                    if (checker[x].getAttribute('checked') != null) {

                        if (checker[x].checked) {
                            total += parseFloat(totalRows[x].innerHTML);
                            askDeliveryButton.removeAttribute('disabled');
                        }

                    }

                }
                document.getElementById('total').innerHTML = total.toFixed(2);
                document.getElementById('fpa').innerHTML = (total * 24 / 100).toFixed(2);

                document.getElementById('payTotal').innerHTML = (total + (total * 24 / 100)).toFixed(2);


                stringifyCheckedItems();

            }
        </script>
    </body>
</html>
