<div id="pageTop" >
    <div id="pageTopLogo" style="margin-left: 30px; height:20px" >
        <a href="index.php"> <img src="images/topLogo.jpg" alt="logo" title="Super Clean Carpet"></a>
    </div>
    <div>
        <form name="login_form" id="signupform" action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="POST">
            <div id="inputs">
                <span id="errorDisplay" style="color:red;font-style: italic;"><?php echo $errorMessage; ?></span><br>
                <b> Email/Username/Identifier</b><br>
                <input  name="identifier" type="text" value="<?php echo $identifier ?>"><br><br>
                <b>Password</b><br>
                <input name="password" type="password" ><br><br>
                <input type="submit"  value="Log In"> 
            </div>
        </form>
    </div>
    <div id="logout" style="float:right; margin: 30px;">
        <a href="logout.php">Log Out</a>
    </div>
</div>
