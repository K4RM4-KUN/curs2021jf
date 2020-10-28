<?php
    session_start();
    include("library.php");
    controlLogedPublic();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_REQUEST["login"])){
            header("Location:login.php");
        } else if(isset($_REQUEST["register"])){
            header("Location:register.php");
        }
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
    <div style="text-align: center; margin-top:220px;">
        <form  method="post" id="start" name="start">
            <input type="submit" value="Iniciar sesion" name="login">    
            <input type="submit" value="Registrarse" name="register">     
        </form>
    </div>
</body>
</html>