<?php //Preguntar si es mejor pasar los datos por el session o consultar la DB dos veces
    session_start();
    include 'library.php';
    include 'libraryShop.php';
    //
    controlLogedPrivate();
    userInfo();
    buttonGet();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$_SESSION["username"]?></title>
</head>
<body>
    <a href="loged.php"><button type="button">Atras</button></a>
    <div style="text-align: center; margin-top:220px;">
        <h1>Administración de productos</h1> 
        <form  method="post" id="productmenu" name="productmenu">
            <input type="submit" value="Añadir productos" name="addmenu">
            <input type="submit" value="Modificar productos" name="modifymenu">
            <?php
                $admin = userType($_SESSION["id"]);
                if($admin){
                    echo'<input type="submit" value="Ventas" name="shopinfo">';
                }
            ?>
        </form>
    </div>
</body>
</html> 