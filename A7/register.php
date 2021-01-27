<?php
    session_start();
    include("library.php");
    controlLogedPublic();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $error = registerDataTest($_REQUEST["username"],$_REQUEST["mail"],$_REQUEST["password"]);
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
    <a href="index.php"><button type="button">Atras</button></a>
    <h1 style="text-align: center; margin-top:180px;">REGISTRARSE</h1> 
    <div style="text-align: center; margin-top:10px;">
        <form  method="post" id="register" name="register">
            <p><?php if(isset($error)){echo "<b>".$error."</b>";} else {echo "</br>";}?></p>
            <label for="username">Nombre </label><input type="text" name="username" id="username"></br></br>
            <label for="mail">Email </label><input type="text" name="mail" id="mail"></br></br>
            <label for="password">Password </label><input type="password" name="password" id="password"></br></br>
            <label for="comppassword">Verificar password </label><input type="password" name="comppassword" id="comppassword"></br></br>
            <input type="submit" value="Registrarse"></br></br>
            
        </form>
    </div>
</body>
</html>