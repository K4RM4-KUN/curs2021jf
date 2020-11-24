<?php //Preguntar si es mejor pasar los datos por el session o consultar la DB dos veces
    session_start();
    include 'library.php';
    include 'libraryShop.php';
    //
    controlLogedPrivate();
    userInfo();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $error = testDataAdd($_REQUEST["productName"],$_REQUEST["productDesc"],$_REQUEST["productPrice"],$_REQUEST["productCat"]);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$_SESSION["username"]?></title>
</head>
<body>
    <a href="productMenu.php"><button type="button">Atras</button></a>
    <div style="text-align: center; margin-top:180px;">
        <h1>Añadir productos</h1>
        <p><?php if(isset($error)){echo "<b>".$error."</b>";} else if (!isset($error) || $error == "") {echo "</br>";}?></p>
        <form enctype="multipart/form-data" method="post" id="addmenu" name="addmenu">
            <label for="productname">Nombre del producto </label><input type="text" name="productName" id="productname" value="<?php if(isset($_REQUEST["productName"]) && !empty($error)){echo $_REQUEST["productName"];}?>" maxlength="50"></br>
            <label for="productdesc">Descripcion </label><textarea name="productDesc" id="productdesc" style="resize: none;"rows="2" cols="35" maxlength="150"><?php if(isset($_REQUEST["productDesc"]) && !empty($error)){echo $_REQUEST["productDesc"];}?></textarea></br>
            <label for="productprice">Precio </label><input type="number" name="productPrice" placeholder="0.00" min="0" step="0.01" id="productprice" value="<?php if(isset($_REQUEST["productPrice"]) && !empty($error)){echo $_REQUEST["productPrice"];}?>" maxlength="11"></br>
            <label for="productcat">Categoria</label><input type="text" name="productCat" id="productcat" value="<?php if(isset($_REQUEST["categoryName"]) && !empty($error)){echo $_REQUEST["categoryName"];}?>" maxlength="20"></br>
            <label for="productdesc">Imagen </label><input type="file" name="productImg" id="productimg"></br>
            <input type="submit" value="Añadir producto" name="add">
        </form>
    </div>
</body>
</html> 