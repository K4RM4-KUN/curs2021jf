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
function buttonGet(){
    if(isset($_REQUEST["addmenu"])){
        header("Location:addMenu.php");
    } else if(isset($_REQUEST["modifymenu"])){
        header("Location:modifyMenu.php");
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

?>