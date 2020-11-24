<?php
//connectToDB devuelve la variable $conn para conectarse a la base de datos
function connectToDB2(){
    $conn = new mysqli('localhost','jfuentes','jfuentes','jfuentes_a5');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);

    }
    return $conn;
}

//consultDB es una consulta basica contra la base de datos
function consultDB2($table,$data,$column){
    $conn = connectToDB();
    $sql = "SELECT * FROM $table where $column = \"$data\"";
    $result = $conn->prepare($sql);
    if(!$result = $conn ->query($sql)){
        die("error ejecuntado la consulta".$conn->error);

    }

    if ($result->num_rows >= 0){
        $users = $result->fetch_assoc();

    }

    return $users;
    $result->free();
    $conn->close();
}

//
function consultBool($table,$data,$column){
    $conn = connectToDB();
    $resultB = false;
    $sql = "SELECT * FROM $table where $column = \"$data\"";
    $result = $conn->prepare($sql);
    if(!$result = $conn ->query($sql)){
        die("error ejecuntado la consulta".$conn->error);

    }

    if ($result->num_rows >= 0){
        $try = $result->fetch_assoc();
        if($try["product_id"] == $data){
            $resultB = true;
        } else {
            $resultB = false;
        }
    }
    return $resultB;
    $result->free();
    $conn->close();
}

//
function buttonGet(){
    $admin = userType($_SESSION["id"]);
    if(isset($_REQUEST["addmenu"])){
        header("Location:addMenu.php");
    } else if(isset($_REQUEST["modifymenu"])){
        header("Location:modifyMenu.php");
    } else if(isset($_REQUEST["addChart"])){
        if(!isset($_SESSION["chart"])){
            $_SESSION["chart"] = [];
        }
        if(!in_array($_REQUEST["id"],$_SESSION["chart"])){
            addToChart($_REQUEST["id"]);
        }
    } else if(isset($_REQUEST["delChart"])){
        delFromChart($_REQUEST["id"]);
    }else if(isset($_REQUEST["shopinfo"])){
        if($admin){
            header("Location:sales.php");
        }
    } 
}

//
function addToChart($product){
    array_push($_SESSION["chart"],$product);
}

//
function delFromChart($product){
    foreach($_SESSION["chart"] as $key => $value){
        if($product == $_SESSION["chart"][$key]){
            unset($_SESSION["chart"][$key]);
        }
    }
}

//
function writeChart(){
    if(isset($_SESSION["chart"]) && !empty($_SESSION["chart"])){
        $totalPrice = 0;
        $productsId = 0;
        $conn = connectToDB2();
        $_SESSION["price"] = 0;
        foreach($_SESSION["chart"] as $key => $value){
            $sql = "SELECT products.id, images.path_img, products.nombre, products.descr, products.preu FROM images INNER JOIN products ON images.product_id = products.id WHERE products.id = ".$_SESSION["chart"][$key];
            $result = $conn->prepare($sql);
            if(!$result = $conn ->query($sql)){
                die("Consulta 1 error: ---> ".$conn->error);
            }
            if ($result->num_rows >= 0){
                $product = $result->fetch_assoc();
                echo '<tr>
                    <td style="width: 900px;">
                        <table border=1>
                            <tr style="text-align:center;">
                                <td width="20%" height="250px">
                                    <img src="'.$product["path_img"].'" width="100%" height="100%">
                                </td>
                                <td width="20%">
                                    <a>'.$product["nombre"].'</a>
                                </td>
                                <td width="30%">
                                    <a>'.$product["descr"].'</a>
                                </td>
                                <td width="15%">
                                    <a>'.$product["preu"].'€</a>
                                </td>
                                <td width="8%">
                                    <form method="post" id="delFromChart" name="'.$product["id"].'">
                                        <input type="hidden" name="id" value="'.$product["id"].'"/> 
                                        <input style="float:right;" type="submit" value="Quitar producto" name="delChart"/>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </td></tr>';
                $totalPrice += $product["preu"];
                $_SESSION["price"] += intval(strtr(strval($product["preu"]),array('.'=> '')));
            }
        }
        echo '<tr>
                <td><a style="float:right;"><b>TOTAL PRICE: '.$totalPrice.'€</b></a></td>
            </tr>
            <tr>
                <td><button style="float:right;" id="checkout-button">Comprar</button></td>
            </tr>';
        $result->free();
        $conn->close();
    }
}

//
function testDataAdd($productname,$productdesc,$productprice,$productcat){
    $error = "";
    if(empty($productname)){
        $error = "El campo \"Nombre\" es obligatorio.";
    } else {
        if(empty($productprice)){
            $error = "El campo \"Descripcion\" es obligatorio.";
        } else {
            if(empty($productprice)){
                $error = "El campo \"Precio\" es obligatorio.";
            } else {
                if(empty($productcat)){
                    $error = "El campo \"Categoria\" es obligatorio.";
                } else {
                    $productprice = floatval(str_replace('.', '.', $productprice));
                    $UploadDir = 'files/';
                    
                    $fichero_subido = $UploadDir . basename($_FILES['productImg']['name']);
                    if (move_uploaded_file($_FILES['productImg']['tmp_name'], $fichero_subido)) {
                        $error = uploadData($productname,$productdesc,$productprice,ucfirst($productcat),$fichero_subido);
                    } else {
                        $error = "Ha habido un error subiendo la imagen...";
                    }

                }
            }
        }
    }
    return $error;
}

//
function uploadData($productname,$productdesc,$productprice,$productcat,$productimgpath){
    $error = "";
    $users = consultDB2("users",$_SESSION["email"],"email");
    $tmpId = intval($users["id"]);
    $productId = -1;
        try{
            $conn = connectToDB();        
            $conn -> query("INSERT INTO products (nombre, descr, preu, tuser_id) VALUES ('$productname','$productdesc',$productprice,$tmpId)");
            $productId = $conn->insert_id;
        } catch(mysqli_sql_exception $e){
            $error->errorMessage();
        }
        $conn->close();
    if($error == "" && $productId != -1){
        $conn = connectToDB();
        try{
            $conn -> query("INSERT INTO images (nombre,path_img,product_id) VALUES ('$productname','$productimgpath',$productId)");
        } catch(mysqli_sql_exception $e){
            $error->errorMessage();
        }
        $conn->close();
        $error = "";
        $error = addCategoryTest($productId,$productcat);
    } else {
        $error = "Error desconocido";
    }
    return $error;
}

//
function deleteProductTest($id){
    $conn = connectToDB();
    try{
        $sql =("DELETE FROM products WHERE id=".$id);
        $conn -> query($sql);
        $conn->close();
    } catch(mysqli_sql_exception $e){
        $e->errorMessage();
    }
}

//
function updateProductTest($productId,$productname,$productdesc,$productprice){
    $namebool = false;
    $descbool = false;
    $pricebool = false;
    $imgbool = false;
    $error = "";
    $dataDB = consultDB2("products",$productId,"id");
    $dataDBimg = consultDB2("images",$dataDB['id'],"product_id");
    if($productId == $dataDB["id"]){
        if($productname != $dataDB["nombre"]){
            $namebool = true;
        } else if ($productname == $dataDB["nombre"]){
            $namebool = true;
        }

        if($productdesc != $dataDB["descr"]){
            $descbool = true;
        } else if ($productdesc == $dataDB["descr"]){
            $descbool = true;
        }

        if($productprice != $dataDB["preu"]){
            $pricebool = true;
        } else if ($productprice == $dataDB["preu"]){
            $pricebool = true;
        }

        if(empty($_FILES["imagen"]["name"])){
            $imgbool = true; 
        } else {
            $imgbool = true;   
        }
        
    }
    if($namebool && $descbool && $pricebool && $imgbool){
        if(empty($productname) || $productname == $dataDB["nombre"]){

        } else {
            updateDB("products",$productname,"nombre",$productId,"id");
        }

        if(empty($productdesc) || $productdesc == $dataDB["descr"]){

        }else {
            updateDB("products",$productdesc,"descr",$productId,"id");
        }

        if(empty($productprice) || $productprice == $dataDB["preu"]){

        } else{
            updateDB("products",$productprice,"preu",$productId,"id");
        }
        
        if(empty($_FILES["pr_imagen"]["name"])){

        }else {  
            $UploadDir = 'files/';
            $fichero_subido = $UploadDir . basename($_FILES['pr_imagen']['name']);
            if($fichero_subido != $dataDBimg["path_img"]){
                if (move_uploaded_file($_FILES['pr_imagen']['tmp_name'], $fichero_subido)) {
                    updateDB("images",$fichero_subido,"path_img",$productId,"product_id");
                } else {
                    $error = "Ha habido un error subiendo la imagen...";
                }
            } else {
                $error = "Es la misma imagen...";
            } 
        }
    }
    return $error;
}

//
function updateDB($tabla,$data,$column,$id,$where){
    $conn = connectToDB2();
    try{
        $sql = ("UPDATE $tabla SET ".$column."='".$data."' WHERE ".$where."=".$id);
        $conn -> query($sql);
        $conn->close();
    } catch(mysqli_sql_exception $e){
        $e->errorMessage();
    }

}

//
function deleteCategoryTest($catid,$proid){
    $conn = connectToDB();
    try{
        $str = ('DELETE FROM pro_cat WHERE category_id = '.$catid.' AND product_id = '.$proid);
        $sql = $str;
        $conn -> query($sql);
        $conn->close();
    } catch(mysqli_sql_exception $e){
        $e->errorMessage();
    }
}

//
function addCategoryTest($proid,$nombre){
    $error = "";
    $nombre = ucfirst($nombre);
    $productCat = consultDB2("category",$nombre,"nombre");
    $tmpName = intval($productCat["nombre"]);
    if($productCat["nombre"] == $nombre){
        $tmpId = intval($productCat["id"]);
        try{
            $conn = connectToDB();        
            $conn -> query("INSERT INTO pro_cat (product_id, category_id) VALUES ('$proid','$tmpId')");
        } catch(mysqli_sql_exception $e){
            $error->errorMessage();
        }
        $conn->close();
    } else {
        $tmpId = intval($productCat["id"]);
        try{
            $conn = connectToDB();        
            $conn -> query("INSERT INTO category (nombre) VALUES ('$nombre')");
            $productId = $conn->insert_id;
        } catch(mysqli_sql_exception $e){
            $error->errorMessage();
        }
        $conn->close();
        if($error == "" && $productId != -1){
            $conn = connectToDB();
            try{
                $conn -> query("INSERT INTO pro_cat (product_id, category_id) VALUES ('$proid','$productId')");
            } catch(mysqli_sql_exception $e){
                $error->errorMessage();
            }
            $conn->close();
            $error = "";
        } else {
            $error = "Error desconocido";
        }
    }
    
    return $error;
}

//
function checkedSearch($option){
    if(isset($_REQUEST["mycheckbox"])){
        foreach($_REQUEST["mycheckbox"] as $value){
            if($value == $option){
                echo"checked='checked'";
            } 
        }
    }
}

//
function newCode(){
    $result ="";
    $chars = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    for($i=0;$i<45;$i++){
        $result = $result.$chars[rand(0, strlen($chars)-1)];
    }
    return $result;
}

//
function insertCommand($id){
    $userid = $_SESSION["id"];
    try{
        $conn = connectToDB2();    
        $sql = "INSERT INTO command (product_id, cuser_id, tdate) VALUES ($id,$userid,CURRENT_TIMESTAMP)";
        $conn -> query($sql);
    } catch(mysqli_sql_exception $e){
        $error->errorMessage();
        echo "$error";
    }
    $conn->close();
}

//
function writeSales(){
    $totalSold = 0;
    $conn = connectToDB2();
    $sql = "SELECT products.id, products.nombre, products.preu, products.tuser_id, command.cuser_id FROM command INNER JOIN products ON products.id = command.product_id";
    $result = $conn->prepare($sql);
    if(!$result = $conn ->query($sql)){
        die("error ejecuntado la consulta".$conn->error);
    } 
    if ($result->num_rows >= 0){
        $continue = true;
        echo'
        <tr>
            <td>
                <table border = 1 style="border-color:#0F9582; background-color:#AAD0E8;">
                    <tr style="text-align: center; font-weight: bold;"> 
                    <td width="50px">ID Producto</td>
                    <td>Nombre Producto</td>
                    <td>Precio Producto</td>
                    <td>Vendedor</td>
                    <td>Comprador</td>
                    </tr>';
        while($continue){
            if($product = $result->fetch_assoc()){
                echo '<tr style="text-align: center;">
                        <td><a>'.$product["id"].'</a></td>
                        <td><a>'.$product["nombre"].'</a></td>
                        <td><a>'.$product["preu"].'</a></td>';

                $vendor = $product["tuser_id"];
                $buyer = $product["cuser_id"];
                $sql2 = "SELECT username FROM users WHERE id = $vendor";
                $result2 = $conn->prepare($sql2);
                if(!$result2 = $conn ->query($sql2)){
                    die("error ejecuntado la consulta".$conn->error);
                } else if ($result2->num_rows >= 0){
                    $vendorAr = $result2->fetch_assoc();
                }
                $sql3 = "SELECT username FROM users WHERE id = $buyer";
                $result3 = $conn->prepare($sql3);
                if(!$result3 = $conn ->query($sql3)){
                    die("error ejecuntado la consulta".$conn->error);
                } else if ($result3->num_rows >= 0){
                    $buyerAr = $result3->fetch_assoc();
                }
                echo '<td><a>'.$vendorAr["username"].'</a></td>
                    <td><a>'.$buyerAr["username"].'</a></td>';
                    
                echo'</tr>';
                $totalSold += $product["preu"];
            } else {
                $continue = false;
            }
        }
        echo '
        </table>
            </td>
                </tr>
                <tr style="text-align: right;">
                    <td>
                        <a>Cantidad Total: <b>'.$totalSold.'€</b></a>
                    </td>
                </tr>';
    }
    $result2->free();
    $result3->free();
    $result->free();
    $conn->close();
}
?>