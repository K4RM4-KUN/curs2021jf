<?php
    session_start();
    include("libreria.php");
    if(!politicCookie()){
        echo "<div style='border=1'><a>Politic Terms</a><br><a href=politics.php>Aceptar</a> <a href=http://www.google.com>Denegar</a></div>";
    }
    if(rememberActive()){ 
        $errortmp = errorControl("test","mail");
        if(errorControl($_COOKIE['rememberMail'],"mail") != "No puedes iniciar session si no aceptas las cookies"){
            $errorMail = errorControl($_COOKIE['rememberMail'],"mail");
            if($errorMail == ""){
                $errorPassword = errorControl($_COOKIE['rememberPass'],"password");
                    echo $_COOKIE['rememberMail']." ".$_COOKIE['rememberPass'];
                if($errorPassword == ""){
                    $_SESSION["password"]= $_COOKIE['rememberPass'];
                    $_SESSION["mail"]= $_COOKIE['rememberMail'];
                    $_SESSION["loged"]= true;
                    header('Location:loged.php');
                }
            } 

        }
    }
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(errorControl($_REQUEST["mail"],"mail") != "No puedes iniciar session si no aceptas las cookies"){
            $errorMail = errorControl($_REQUEST["mail"],"mail");
            if($errorMail == ""){
                $errorPassword = errorControl($_REQUEST["password"],"password");
                if($errorPassword == ""){
                    remember(); //Esta linea esta desactivada por que remember(); no funciona correctamente
                    $_SESSION["mail"]=$_REQUEST["mail"];
                    $_SESSION["loged"]= true;
                    header('Location:loged.php');
                }
            } 
        } else {
            $errorCooki = errorControl($_REQUEST["mail"],"mail");
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
    <div style="text-align: center; margin-top:220px;">
        <form  method="post" id="mylogin" name="mylogin">
            <label for="mail">Email:<input type="text" name="mail" id=""></br><span class="error"><?if(isset($errorMail))echo "<b>".$errorMail."</b>";?></span></br></br>
            <label for="password">Password:</label><input type="password" name="password" id=""></br><span class="error"><?if(isset($errorPassword))echo "<b>".$errorPassword."</b>";?></span></br>
            <span class="error"><?if(isset($errorCooki))echo "<b>".$errorCooki."</b></br>";?></span></br>
            <label for="rememberme"><input type="checkbox" name="rememberme" value="remember" <?if(rememberActive()){echo "checked /> Remembered";}else{echo"/> Remember me";}?></label></br></br>
            <input type="submit" value="Iniciar sesion"></br>
            
        </form>
    </div>
</body>
</html>