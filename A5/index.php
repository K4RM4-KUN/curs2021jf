<?php
    session_start();
    include("library.php");
    controlLogedPublicIndexOnly();
    $error = politicCookie();
    if($error != ""){
        echo "<div style='border=1'><a>Politic Terms</a><br><a href=politics.php>Aceptar</a> <a href=http://www.google.com>Denegar</a></div>";
    } else if($_SERVER['REQUEST_METHOD'] == 'POST'){
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
            <p><?php if(isset($error)){echo "<b>".$error."</b>";} else {echo "</br>";}?></p>
            <input type="submit" value="Iniciar sesion" name="login"<?php if(isset($error)){echo "disabled";}?>>    
            <input type="submit" value="Registrarse" name="register"<?php if(isset($error)){echo "disabled";}?>>     
        </form>
    </div>
</body>
</html>