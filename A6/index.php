<?php
    session_start();
    include 'library.php';
    include 'libraryShop.php';
    controlLogedPublicIndexOnly();
    $error = politicCookie();
    if($error != ""){
        echo "<div style='border=1'><a>Politic Terms</a><br><a href=politics.php>Aceptar</a> <a href=http://www.google.com>Denegar</a></div>";
    } else if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_REQUEST["login"])){
            header("Location:login.php");
        } else if(isset($_REQUEST["register"])){
            header("Location:register.php");
        }
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
    <div style="text-align: center; margin-top:220px; margin-bottom:50px;">
        <h1>HAZ LOGIN O REGISTRATE!</h1>
        <form  method="post" id="start" name="start">
            <p><?php if(isset($error)){echo "<b>".$error."</b>";} else {echo "</br>";}?></p>
            <input type="submit" value="Iniciar sesion" name="login"<?php if(isset($error)){echo "disabled";}?>>    
            <input type="submit" value="Registrarse" name="register"<?php if(isset($error)){echo "disabled";}?>>     
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

                if(isset($_REQUEST["search"]) || isset($_REQUEST["showall"]) && !isset($_REQUEST["searchC"]) && !empty($_REQUEST["searchtxt"])){
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
                                    echo '<tr><td><a>'.$product["preu"].'€</a></td></tr>';
                                    echo '<tr><td><form><input type="hidden" value="'.$product["id"].'"><input type="submit" value="Añadir al carrito" name="addChart"></form></td></tr>
                                    </table></td>';
                                } else {
                                    $continue = false;
                                }

                            }
                            echo "</tr><tr><td height='25px'></td></tr>";
                        }
                    }
                } else {
                    $sql = "SELECT products.id, images.path_img, products.nombre, products.descr, products.preu FROM images INNER JOIN products ON images.product_id = products.id";
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
                                    echo '<tr><td><a>'.$product["preu"].'€</a></td></tr>';
                                    echo '<tr><td><form><input type="hidden" value="'.$product["id"].'"><input type="submit" value="Añadir al carrito" name="addChart"></form></td></tr>
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