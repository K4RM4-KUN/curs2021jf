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
    <a href="loged.php"><button type="button">Volver a la pagina principal</button></a>
    <center>
    <div style="text-align: center; margin-top:180px;">
    <?php
        if($_GET["id_compra"] == $_SESSION["PrivateKey"]){
            $_SESSION["PrivateKey"] = "";
            echo"LA COMPRA HA SIDO UN EXITO!";
            foreach($_SESSION["chart"] as $key => $value){
                insertCommand($_SESSION["chart"][$key]);
            }
            $_SESSION["chart"] = [];
        } else {
            echo"PARECE QUE EL CODIGO PRIVADO NO COINCIDE, NO INTENTES HACER MALDADES <b>CERDO</b>!";
            $_SESSION["PrivateKey"] = "";
        }
    ?>
    </div>
    </center>
</body>
</html>