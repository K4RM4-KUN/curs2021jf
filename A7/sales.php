<?php
    session_start();
    include("library.php");
    include("libraryShop.php");
    userInfo();
    controlLogedPrivate();
    controlLogedAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
</head>
<body>
    <a href="loged.php"><button type="button">Atras</button></a>
    <h1 style="margin-top:180px; text-align: center;">VENTAS EN MY WEB</h1>
    <p style="font-weight: bold; text-align: center;"><?php if(isset($error)){echo $error;}else{echo "</br>";}?></p>
    <div style="margin-top:25px;">
    <center>
        <table border = 1 >
            <tr style="text-align: center; font-weight: bold;" >
                <td>Ventas</td>
            </tr>
            <?php
                writeSales()
            ?>
        </table>
    </center>
    </div>
</body>
</html>