<?php //Preguntar si es mejor pasar los datos por el session o consultar la DB dos veces
    session_start();
    include 'library.php';
    include 'libraryShop.php';
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
    <div style="text-align: center; margin-top:220px; margin-bottom:50px;">
        <h1>Hello <?=ucfirst($_SESSION["username"])?>!</h1> 
        <form  method="post" id="end" name="end">
            <input type="submit" value="Configuracion" name="config">
            <input type="submit" value="Productos" name="product">
            <?php
                $admin = userType($_SESSION["id"]);
                if($admin){
                    echo'<input type="submit" value="Administracion" name="administration">';
                }
            ?>
            <input type="submit" value="Cerrar sesion" name="logout">
        </form>
    </div>
    <hr>
    <div style="text-align: center; margin-top:50px;">
        <h1 style="margin-bottom:20px;">PRODUCTOS</h1>
        <form method=post style="margin-bottom:20px;">
            <input type="submit" value="Mostrar Todos" name="showall">
        </form>
        <form method=post style="margin-bottom:20px;">
            <input type="text" name="searchtxt" placeholder="Buscar..."><input type="submit" value="Buscar" name="search">
        </form>
        <center>
            <table><?php
                $conn = connectToDB2();
                if(isset($_REQUEST["search"]) || isset($_REQUEST["showall"]) && !isset($_REQUEST["searchC"])){
                    if(isset($_REQUEST["searchtxt"])){
                        $tmp = $_REQUEST["searchtxt"];
                        $sql = "SELECT products.id, images.path_img, products.nombre, products.descr, products.preu FROM (images INNER JOIN products ON images.product_id = products.id) INNER JOIN pro_cat ON pro_cat.product_id = products.id INNER JOIN category ON pro_cat.category_id = category.id WHERE products.nombre LIKE '%$tmp%' OR products.descr LIKE '%$tmp%' OR category.nombre LIKE '%$tmp%'";
                    }else {
                        $sql = "SELECT products.id, images.path_img, products.nombre, products.descr, products.preu FROM images INNER JOIN products ON images.product_id = products.id";
                    }
                    $result = $conn->prepare($sql);
                    if(!$result = $conn ->query($sql)){
                        die("error ejecuntado la consulta".$conn->error);
                    } 
                    if ($result->num_rows >= 0){
                        $continue = true;
                        while($continue){
                            echo "<tr>";
                            for($x=0 ; $x<4 && $continue ; $x++){
                                if($product = $result->fetch_assoc()){
                                    echo '<td><table border=1 style="border-color:#0F9582; background-color:#AAD0E8;">
                                        <tr><td width="250px" height="350px"><img style="width:100%; height:100%;" src="'.$product["path_img"].'"></td></tr>
                                        <tr><td><a>'.substr($product["nombre"],0,20);
                                        if(strlen($product["nombre"])>20){echo'...';}
                                        echo'</a></td></tr>';
                                    echo '<tr><td width="250px"><a>'.substr($product["descr"],0,20);
                                    if(strlen($product["descr"])>20){echo'...';}
                                    echo'</a></td></tr>';
                                    echo '<tr><td><a>'.$product["preu"].'€</a></td></tr>
                                    </table></td>';
                                } else {
                                    $continue = false;
                                }

                            }
                            echo "</tr><tr><td height='25px'></td></tr>";
                        }
                    }
                } 
            ?></table>
        </center>
    </div>
</body>
</html>