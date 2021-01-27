<?php
    session_start();
    include("library.php");
    controlLogedPublic();
    rememberUsing();
    if(isset($_SESSION["tmpname"])){
        $error = "El usuario ".$_SESSION["tmpname"]." ha sido creado correctamente, prueba de hacer login.";
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $error = loginDataTest($_REQUEST["mail"],$_REQUEST["password"]);
    };
    if(isset($_REQUEST["recoveryMail"])){
        header("Location:recoveryMailSoli.php");
    };
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <a href="index.php"><button type="button">Atras</button></a>
    <h1 style="text-align: center; margin-top:180px;">LOGIN</h1> 
    <div style="text-align: center; margin-top:10px;">
        <form  method="post" id="mylogin" name="mylogin">
            <p><?php if(isset($error)){echo "<b>".$error."</b>";} else {echo "</br>";}?></p>
            <label for="mail">Email <input type="text" name="mail" id="mail"></br></br>
            <label for="password">Password </label><input type="password" name="password" id="password"></br></br>
            <label for="rememberme"><input type="checkbox" name="rememberme" value="remember"> Remember me</label></br></br>
            <input type="submit" value="Iniciar sesion"></br></br>
            <!--<input type="submit" value="Has olvidado tu contraseña?" name="recoveryMail"></br></br>-->
            
        </form>
    </div>
</body>
</html>