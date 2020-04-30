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
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>ADDRESS</title>


        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


        <style>
            body {
                font-family: 'Montserrat', sans-serif;
            }
            h1{
                text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 40px;
            }

            #map {
                width: 100%; height: 400px; margin-top: 40px; margin-bottom: 40px;
            }

            .info-window {
                font-family: 'Montserrat', sans-serif;
            }
            .info-content {
                color: #999;
            }


        </style>
    </head>
    <body>

        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <div id="pageTop" style="width: 100%; height: 80px; background: url(style/headerSliver.jpg) repeat-x;">
                        <div id="pageTopLogo" style="margin-left: 30px; margin-bottom: 10px; height:80px"  >
                            <a href="index.php"> <img src="images/topLogo.jpg" alt="logo" title="Super Clean Carpet"></a>
                            <h4 style="float:right; margin: 20px;"><?php echo $customer->getFirstName() ?>&nbsp;&nbsp;<?php echo $customer->getLastName() ?></h4>

                        </div>
                        <div>
                            <a href="logout.php" style="float:right; margin-right: 50px;">Log Out</a>
                        </div>
                    </div>
                    <h1><center>Your contact information</center></h1>

                    <hr>
                    <div class="row">
                        <div class="col-sm-8 ">
                            <h6><i>Type your address to find on map</i></h6>
                            <br>
                            <input id="address" type="text" style="width:600px;"/>
                            <br><br>
                            <input id="submit"  type="button" value="Click to find your address on the map">

                            <h6><i>Drag the marker on the map to pin your address</i></h6>
                            <div id="map"></div>

                        </div>

                        <div class="col-sm-4 ">

                            <form modelAttribute="user" cssClass="login-form" action="saveAddress.php" method="POST">
                                <div>
                                    <input  hidden="hidden"  path="id" /> 
                                </div> 

                                <div class="form-group">
                                    <label cssClass="text-uppercase" >STREET</label><br>
                                    <input name="street" id="street" cssClass="form-control" value="<?php echo $customer->getStreet(); ?>" placeholder="Type your street name"/> 

                                </div>  


                                <div class="form-group">
                                    <label cssClass="text-uppercase" >DISTRICT</label><br>
                                    <input name="district" id="district" cssClass="form-control" value="<?php echo $customer->getDistrict(); ?>" placeholder="Type your district name"/> 

                                </div> 

                                <div class="form-group">
                                    <label cssClass="text-uppercase" >POSTAL CODE</label><br>
                                    <input name="postalCode" id="postalCode" cssClass="form-control" value="<?php echo $customer->getPostalCode(); ?>" placeholder="Type your postal code"/> 

                                </div>  

                                <div class="form-group">
                                    <label cssClass="text-uppercase" >FLOOR</label><br>
                                    <input name="floor" id="floor" cssClass="form-control" value="<?php echo $customer->getFloor(); ?>" placeholder="Type your floor number"/> 

                                </div>  

                                <div class="form-group">
                                    <label cssClass="text-uppercase" >NAME ON DOORBELL</label><br>
                                    <input name="doorbellName" cssClass="form-control" value ="<?php echo $customer->getDoorbellName(); ?>" placeholder="Type your doorbell name"/> 
                                </div>  
                                <div class="form-group">
                                    <label  cssClass="text-uppercase" >LANDLINE PHONE</label><br>
                                    <input name="landlinePhone" cssClass="form-control" value="<?php echo $customer->getLandlinePhone(); ?>" placeholder="Type your landline phone number"/> 

                                </div> 
                                <div class="form-group">
                                    <label  cssClass="text-uppercase" >MOBILE PHONE</label><br>
                                    <input  name="mobilePhone" cssClass="form-control" value="<?php echo $customer->getMobilePhone(); ?>" placeholder="Type your mobile phone number"/> 
                                </div> 
                                <input name="latitude" id="latitude" cssClass="form-control" hidden="true" value="<?php echo $customer->getLatitude(); ?>"  readonly="true"/> 
                                <input name="longitude" id="longitude" cssClass="form-control" hidden="true" value="<?php echo $customer->getLongitude(); ?>"  readonly="true"/> 



                                <hr>
                                <button>CONFIRM YOUR CONTACT INFORMATION</button>     <br><br>
                            </form>
                            <hr>
                        </div>

                    </div> 

                </div> 
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <!--
                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
                <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
        -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCyRVA60P_cw-KswNxngA-CUyYmJM1LLg&language=el"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


        <script >


            var marker = new google.maps.Marker({
                draggable: true,
                animation: google.maps.Animation.DROP
            });
            var infowindow = new google.maps.InfoWindow({
                maxWidth: 400
            });

            $(function () {

                function initMap() {
                    var savedLat = document.getElementById("latitude").value;
                    var savedLong = document.getElementById("longitude").value;

                    var location = new google.maps.LatLng(savedLat, savedLong);

                    var mapCanvas = document.getElementById('map');

                    var mapOptions = {
                        center: location,
                        zoom: 18,
                        panControl: false,
                        scrollwheel: true,
                        mapTypeId: 'satellite'
                    };

                    var map = new google.maps.Map(mapCanvas, mapOptions);

                    marker.setPosition(location);
                    marker.setDraggable(true);
                    marker.setMap(map);


                    marker.addListener('click', function () {
                        infowindow.open(map, marker);
                    });

                    google.maps.event.addListener(marker, 'dragend', function () {

                        geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                if (results[0]) {

                                    fu(results[0]);
                                }
                            } else {
                                alert("Out of range");
                            }
                        }
                        );
                    });
                    document.getElementById('submit').addEventListener('click', function () {
                        geocodeAddress(geocoder, map);
                    });
                }
                google.maps.event.addDomListener(window, 'load', initMap);
                var geocoder = new google.maps.Geocoder();

            });



            function geocodeAddress(geocoder, resultsMap) {
                var address = document.getElementById('address').value;
                geocoder.geocode({'address': address}, function (results, status) {
                    if (status === 'OK') {
                        resultsMap.setCenter(results[0].geometry.location);

                        marker.setMap(resultsMap);
                        marker.setPosition(results[0].geometry.location);
                        fu(results[0]);

                    } else {
                        alert('Geocode was not successful for the following reason: ' + status);
                    }
                });
            }

            function fu(results) {
                var address = results.address_components;
                var street = '';
                var street_number = '';
                var district = '';
                var postal_code = '';
                for (var i = 0; i < address.length; i++) {
                    if (address[i].types.includes("route")) {
                        street = address[i].long_name;
                    }

                    if (address[i].types.includes("street_number")) {
                        street_number = address[i].long_name;
                    }

                    if (address[i].types.includes("locality") |
                            address[i].types.includes("administrative_area_level_4") |
                            address[i].types.includes("administrative_area_level_5")) {
                        district = address[i].long_name;
                    }
                    if (address[i].types.includes("postal_code")) {
                        postal_code = address[i].long_name;
                    }
                }

                $('#address').val(results.formatted_address);
                $('#latitude').val(marker.getPosition().lat());
                $('#longitude').val(marker.getPosition().lng());
                $('#street').val(street + " " + street_number);
                $('#district').val(district);
                $('#postalCode').val(postal_code);
                infowindow.setContent(results.formatted_address);
                infowindow.open(map, marker);
            }

        </script>
    </body>
</html>
