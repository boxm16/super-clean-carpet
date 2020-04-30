<?php
require_once 'Model/Customer.php';
require_once 'Dao/DataBaseConnection.php';
if (!isset($_SESSION)) {
    session_start();
} else {
    if (isset($_SESSION['customer'])) {
        header('Location : main.php');
    }
}
$identifier = "";
$identifierErrorMessage = "";
$password = "";
$passwordErrorMessage = "";
$customer = new Customer();
$dataBaseConnection = new DataBaseConnection();
if (isset($_POST['identifier'])) {
    $identifier = $_POST['identifier'];
    if ($identifier == "") {
        $identifierErrorMessage = "ΠΕΔΙΟ Email/Username/Identifier ΔΕΝ ΜΠΟΡΕΙ ΝΑ ΕΙΝΑΙ ΑΔΙΟ";
    }
}if (isset($_POST['password'])) {
    $password = $_POST['password'];
    if ($password == "") {
        $passwordErrorMessage = "ΠΕΔΙΟ Password ΔΕΝ ΜΠΟΡΕΙ ΝΑ ΕΙΝΑΙ ΑΔΙΟ";
    }

    if ($identifier != "" && $password != "") {
        
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            #pageTop{
                background: url('style/headerSliver.jpg') repeat-x;
                height: 90px;
                width:100%;
            }
            #pageTop>pageTopLogo{
                background: url('images/topLogo.jpg');
                height: 90px;
                width:100%;
            }

            #dispaly{
                width:1000px;
                height: 800px;
                float:left; 
                background-image: url('images/super-clean-image_3.jpg');
                background-repeat: no-repeat;
            }
            #inputs{
                width:250px;                         
                height:800px; 
                float:left;
                margin:20px;

            }
            #inputs>input{
                border:blue;
                border-style: solid;
                font-size: 20px;
            }

        </style>
    </head>
    <body>
        <?php include 'pageTop.php'; ?>
        <div id="mainPage">
            <form name="login_form" id="signupform" action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="POST">
                <div id="inputs">
                    <b> Email/Username/Identifier</b><br>
                    <input  name="identifier" type="text" value="<?php echo $identifier ?>"><br>
                    <span id="identifierError" style="color:red;  font-style: italic;"><?php echo $identifierErrorMessage; ?></span><br>
                    <b>Password</b><br>
                    <input name="password" type="password" ><br>
                    <span id="passwordError" style="color:red;font-style: italic;"><?php echo $passwordErrorMessage; ?></span><br>
                    <input type="submit"  value="Log In"> 
                </div>
            </form>
            <div  id="dispaly"></div>
        </div>
        <div id="bottomPage"></div>
    </body>
</html>
