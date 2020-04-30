<?php
session_start();
require_once 'Dao/DataBaseConnection.php';

if (isset($_SESSION['id'])) {
    header("location:main.php");
    exit();
}

$identifier = "";
$errorMessage = "";
if (isset($_POST['identifier'])) {
    $identifier = preg_replace('#[^a-z0-9@.]#i', '', $_POST['identifier']);
    $password = $_POST['password'];
    $dataBaseConnection = new DataBaseConnection();
    $user_id = $dataBaseConnection->getUserId($identifier, $password);

    if ($user_id != NULL) {
        $_SESSION['id'] = $user_id;
        header('Location:main.php');
    } else {
        $errorMessage = "Λάθος στοιχεία, δοκιμάστε ξανά";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
      
    </head>
    <body>
        <div id="pageTop" style="width: 100%; height: 80px; background: url(style/headerSliver.jpg) repeat-x">
            <div id="pageTopLogo" style="margin-left: 30px; margin-bottom: 10px; height:80px" >
                <a href="index.php"> <img src="images/topLogo.jpg" alt="logo" title="Super Clean Carpet"></a>
            </div>
        </div>
        <div id="pageMiddle">
            <div id="credentials" style="float:left">
                <form name="login_form" id="signupform" action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="POST">
                    <div id="inputs">
                        <br><br>
                        <span id="errorDisplay" style="color:red;font-style: italic;"><?php echo $errorMessage; ?></span><br>
                        <b> Email/Username/Identifier</b><br>
                        <input  name="identifier" type="text" value="<?php echo $identifier ?>"><br><br>
                        <b>Password</b><br>
                        <input name="password" type="password" ><br><br>
                        <input type="submit"  value="Log In"> 
                    </div>
                </form>
            </div>
            <div  id="dispaly" style="float:right; background-image: url(images/super-clean-image_3.jpg); background-repeat: no-repeat; width: 1200px;    height:900px;" ></div>
        </div>
        <div id="bottomPage"></div>
    </body>
</html>
