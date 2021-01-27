<?php
    session_start();
    include 'library.php';
    include 'libraryShop.php';
    controlLogedPrivate();
    userInfo();
    controlPurchasePages();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="chart.php"><button type="button">Volver al carrito</button></a>
    <div style="text-align: center; margin-top:180px;">
    <?php
        if($_GET["id_compra"] == $_SESSION["PrivateKey"]){
            echo"HA HABIDO UN ERROR DESCONOCIDO, PRUEBALO OTRA VEZ, SI ESTO NO FUNCIONA POR FAVOR PONTE EN CONTACTO CON LOS ADMINISTRADORES";
            $_SESSION["PrivateKey"] = "";
        } else{
            echo"PARECE QUE EL CODIGO PRIVADO NO COINCIDE, NO INTENTES HACER MALDADES <b>CERDO</b>!";
            $_SESSION["PrivateKey"] = "";
        }
    ?>
    </div>
    
</body>
</html>