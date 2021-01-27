<?php
session_start();
include("library.php");
privatePage();
if(isset($_REQUEST["logout"])){
    session_destroy();
    header("Location:index.php");
}
if(isset($_REQUEST["recoverPass"])){
    $error = testPasswordRecover($_REQUEST["passRecover"],$_REQUEST["passRecoverRepeat"],$_SESSION["username"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private</title>
</head>
<body>
    <div style="text-align: center; margin-top: 220px;">
        <h1>Hola <?php echo $_SESSION["name"]."!";?></h1>
        <form method="post" name="button-list">
        <input type="submit" name="logout" value="Logout">
        </form>
    <?php
        if(isset($_SESSION["usedTmpPass"])){
            if(!$_SESSION["usedTmpPass"]){
                echo '<div style="text-align: center; margin-top: 220px;">
                        <h1>Introduce una nueva contraseña!</h1>
                        <h3>La nueva contraseña que has utilizado para hacer login solo funcionara 1 vez, cambia la contraseña ahora...</h3>
                        <form method="post" name="recover-password">
                            <label for="passRecover">Password </label><input type="password" name="passRecover" id="pass"></br></br>
                            <label for="passRecoverRepeat">Repite la password </label><input type="password" name="passRecoverRepeat" id="passR"></br></br>
                            <input type="submit" name="recoverPass" value="Cambiar contraseña">
                        </form>
                    </div>';
            } 

        }
    ?>
        <p><?php if(isset($error)){echo "<b>".$error."</b>";} else {echo "</br>";}?></p>
    </div>
</body>
</html>