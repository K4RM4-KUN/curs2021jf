<?php //Preguntar si es mejor pasar los datos por el session o consultar la DB dos veces
    session_start();
    include('library.php');
    controlLogedPrivate();
    userInfo();
    getButton();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $error = configDataTest();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$_SESSION["username"]?></title>
</head>
<body>
    <form  method="post" id="back" name="back"><input type="submit" value="back" name="goback"></form>
    <div style="text-align: center; margin-top:50px;">
        <form  method="post" id="change" name="change">
            <h1>Configuracion del usuario <?=$_SESSION["username"]?>.</h1> 
            <p><?php if(isset($error)){echo "<b>".$error."</b>";} else {echo "</br>";}?></p>
            <label for="mail">Nombre nuevo <input type="text" name="chusername" id="username"></br></br>
            <label for="mail">Email nuevo <input type="text" name="chmail" id="mail"></br></br>
            <label for="password">Nueva password </label><input type="password" name="chpassword" id="password"></br></br>
            <label for="password">Password actual </label><input type="password" name="actualpassword" id="password"></br></br>
            <input type="submit" value="Cambiar datos"></br></br>
        </form>
    </div>
</body>
</html>