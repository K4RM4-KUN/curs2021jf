<?php //Preguntar si es mejor pasar los datos por el session o consultar la DB dos veces
    session_start();
    include 'library.php';
    controlLogedPrivate();
    userInfo();
    getButton();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$_SESSION["username"]?></title>
</head>
<body>
    <div style="text-align: center; margin-top:220px;">
        <h1>Hello <?=ucfirst($_SESSION["username"])?>!</h1> 
        <form  method="post" id="end" name="end">
            <input type="submit" value="Configuracion" name="config">
            <?php
                $admin = userType($_SESSION["id"]);
                if($admin){
                    echo'<input type="submit" value="Administracion" name="administration">';
                }
            ?>
            <input type="submit" value="Cerrar sesion" name="logout">
        </form>
    </div>
</body>
</html>