<?php
    session_start();
    include("library.php");
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
    <div style="text-align: center; margin-top:220px;">
        <form  method="post" id="register" name="register">
            <p><?php if(isset($error)){echo "<b>".$error."</b>";} else {echo "</br>";}?></p>
            <label for="mail">Nombre <input type="text" name="username" id="username"></br></br>
            <label for="mail">Email <input type="text" name="mail" id="mail"></br></br>
            <label for="password">Password </label><input type="password" name="password" id="password"></br></br>
            <input type="submit" value="Registrarse"></br></br>
            
        </form>
    </div>
</body>
</html>