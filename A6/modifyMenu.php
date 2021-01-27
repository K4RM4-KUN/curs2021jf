<?php    
    session_start();
    include 'library.php';
    include 'libraryShop.php';
    //
    controlLogedPrivate();
    userInfo();
    if(isset($_REQUEST["modify"])){
        $error = updateProductTest($_REQUEST["id"],$_REQUEST["nombre"],$_REQUEST["descripcion"],$_REQUEST["precio"]);
    } else if(isset($_REQUEST["delete"])){
        deleteProductTest($_REQUEST["id"]);
    }else if(isset($_REQUEST["delete_cat"])){
        deleteCategoryTest($_REQUEST["catid"],$_REQUEST["proid"]);
    }else if(isset($_REQUEST["add_cat"])){
        addCategoryTest($_REQUEST["id"],$_REQUEST["cat_nom"]);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
</head>
<body>
    <a href="productMenu.php"><button type="button">Atras</button></a>
    <h1 style="margin-top:180px; text-align: center;">ADMINISTRACION DE PRODUCTOS</h1>
    <p style="font-weight: bold; text-align: center;"><?php if(isset($error)){if(empty($error)){echo $error;}else{{echo "</br>";}}}else{echo "</br>";}?></p>
    <div style="margin-top:25px;">
    <center>
        <table border = 1 style="border-color:#0F9582; background-color:#AAD0E8;">
            <tr>
            <td style="text-align: center; font-weight: bold;">Categorias</td>
            <td style="text-align: center; font-weight: bold;" >Imagen</td>
            <td style="text-align: center;font-weight: bold;" >Nombre</td>
            <td style="text-align: center; font-weight: bold;" >Descripcion</td>
            <td style="text-align: center; font-weight: bold;" >Precio</td>
            <td style="text-align: center; font-weight: bold;" >Cambiar Imagen</td>
            <td style="text-align: center; font-weight: bold;" ></td>
            <td style="text-align: center; font-weight: bold;" ></td>
            </tr>
            <?php
            $conn = connectToDB2();
            $user_id = $_SESSION["id"];
            $sql = "SELECT products.id, images.path_img, products.nombre, products.descr, products.preu FROM images INNER JOIN products ON images.product_id = products.id WHERE products.tuser_id = $user_id";
            
            $result = $conn->prepare($sql);
            if(!$result = $conn ->query($sql)){
                die("error ejecuntado la consulta".$conn->error);
            }

            if ($result->num_rows >= 0){
                while($product = $result->fetch_assoc()){
                    $tmpId = $product["id"];
                    echo '<tr>';
                            echo'<td width="210px">
                                    <form>
                                        <input type="hidden" name="proid" style="text-align: center;" size="1" value="'.$product["id"].'"></br>
                                        <input name="cat_nom" type="text" maxlength="20" placeholder="Añadir categoria" size="15">
                                        <input type="submit" name="add_cat" value="Añadir">
                                        <table>';  
                                        $sql2 = "SELECT category.nombre, category.id FROM category INNER JOIN pro_cat ON pro_cat.category_id = category.id WHERE product_id = $tmpId";
                                        $result2 = $conn->prepare($sql2);
                                        if(!$result2 = $conn ->query($sql2)){
                                            die("error ejecuntado la consulta1".$conn->error);
                                        }
                                        while($product2 = $result2->fetch_assoc()){
                                            echo '<tr>
                                                <td><input type="hidden" name="catid" style="text-align: center;" size="1" value="'.$product2["id"].'"><a>-'.$product2["nombre"].'</a></td>
                                                <td><input type="submit" name="delete_catB" value="Eliminar"></td>
                                            </tr>';
                                        }
                                    echo'</table>
                                    </form>
                                </td>';
                        echo '<form enctype="multipart/form-data" method="post" id="changeUserData" name="'.$product["id"].'">
                                <input type="hidden" name="id" style="text-align: center;" size="1" value="'.$product["id"].'" readonly>
                                <td width="90px" height="120px"><img width="100%" height="100%" src="./'.$product["path_img"].'"></td>
                                <td><input name="nombre" style="text-align: center;" size="25" value="'.$product["nombre"].'" maxlength="50"></td>
                                <td><textarea name="descripcion" style="text-align: center; resize: none;" rows="7" cols="35" maxlength="150">'.$product["descr"].'</textarea></td>
                                <td><input type="number" name="precio" placeholder="0.00" min="0" step="0.01" id="productprice" value="'.$product["preu"].'" maxlength="11"></td>';
                              
                            echo'<td><input type="file" name="pr_imagen"></td>
                                <td><input type="submit" value="Modificar" name="modifyB"></td>
                                <td><input type="submit" name="delete" value="Eliminar"></td>
                            </form>
                        </tr>';
                    $result2->free();
                }
            }
            $result->free();
            $conn->close();
            ?>
        </table>
    </center>
    </div>
</body>
</html>