<?php
session_start();
include("library.php");
require './phpmailer/src/PHPMailer.php';
require './phpmailer/src/SMTP.php';
require './phpmailer/src/Exception.php';
require './phpmailer/src/OAuth.php';
publicPage();
if(isset($_REQUEST["initiateLogin"])){
    $error = testLogin($_REQUEST["usernameLogin"],$_REQUEST["passwordLogin"]);
}
if(isset($_REQUEST["recover"])){
    $error = testRecover($_REQUEST["usernameLogin"],intval($_REQUEST["solution"]),$_SESSION["result"]);
    $plus = forRecover();
}
if(!isset($error)){
    $plus = forRecover();
} else if($error != "Debes resolver la suma para conseguir una nueva contraseña."){
    $plus = forRecover();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div style="text-align: center; margin-top: 220px;">
        <h1>LOGIN</h1>
        <p><?php if(isset($error)){echo "<b>".$error."</b>";} else {echo "</br>";}?></p>
        <form method="post" name="session-login">
            <label for="username">Username: </label><input type="text" name="usernameLogin" id="username" placeholder="pepito@mail.com"></br></br>
            <label for="pass">Password: </label><input type="password" name="passwordLogin" id="pass"></br></br>
            <input type="submit" name="initiateLogin" value="Iniciar session"></br></br>
            <label for="result" ><?php if(isset($plus)){echo $plus;} else {echo"";};?><label><input type="text" name="solution" placeholder="resultado" id="result">
            <input type="submit" name="recover" value="¿Has olvidado tu contraseña?">
        </form>
    </div>
</body>
</html>