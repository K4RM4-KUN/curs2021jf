<?php
    session_start();
    include("library.php");
    controlLogedPublic();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $error = recoveryDataTest($_REQUEST["mail"]);
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
    <a href="login.php"><button type="button">Atras</button></a>
    <h1 style="text-align: center; margin-top:180px;">RECUPERAR CONTRASEÑA</h1> 
    <div style="text-align: center; margin-top:10px;">
        <form  method="post" id="recover" name="recover">
            <p><?php if(isset($error)){echo "<b>".$error."</b>";} else {echo "</br>";}?></p>
            <label for="mail">Email <input type="text" name="mail" id="mail"></br></br>
            <input type="submit" value="Enviar mail de recuperacion"></br></br>
        </form>
    </div>
</body>
</html>