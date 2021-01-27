<?php
function errorControl($data,$type){
    if(politicCookie()){
        $error = "";
        switch($type){
            case "mail":
                if(empty("$data")){
                    $error = "Este campo es obligatorio*";
                }else{
                    if(strpos($data, "@") == false){
                        $error = "Tiene que ser un email.";
                    } else {
                        if($data == "javi@mail.com"){
                            $error = "";
                        }else {
                            $error = "El email es incorrecto!";
                        }
                    }
                }
                break;
            case "password":
                $tmpData = preg_replace('([^A-Za-z0-9 ])', ' ', "$data");
                if($tmpData == $data){
                    if(empty("$data")){
                        $error = "Este campo es obligatorio*";
                    }else{
                        $cifrado = "1a1dc91c907325c69271ddf0c944bc72";
                        if(rememberActive()){
                            if($data == $cifrado){
                                $error = "";
                            }else {
                                $error = "La contraseña no es correcta!";
                            }

                        } else if(md5($data) == $cifrado){
                            $error = "";
                        } else {
                            $error = "La contraseña no es correcta!";
                        }
                    }
                } else {
                    $error = "Solo se permiten numeros o letras";
                }
            break;
        }
    } else {
        $error="No puedes iniciar session si no aceptas las cookies";
    }
    return $error;
}

function rememberActive(){
    if(isset($_COOKIE['rememberMail'])){
        $activa = true;
    } else{
        $activa = false;
    }
    return $activa;

}

function remember(){
    if(isset($_REQUEST["rememberme"])){
        $cookiValues = [$_REQUEST["mail"],md5($_REQUEST["password"])];
        setcookie("rememberMail",$cookiValues[0],time()+(1*31*60*60));
        setcookie("rememberPass",$cookiValues[1],time()+(1*31*60*60));
    } else {
        $nothing = "nothinghappend";
    }

}

function politicCookie(){
    if(isset($_COOKIE['politicTerms'])){
        $activa = true;
    } else{
        $activa = false;
    }
    return $activa;
}

function controlLogin(){
    if(!($_SESSION['loged'])){
        header('Location:login.php');
    }else{
        $mail = $_SESSION["mail"];
    }
    return $mail;
}

function logout(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        session_destroy();
        setcookie("rememberPass", "", time() - +(365*24*60*60)); 
        setcookie("rememberMail", "", time() - +(365*24*60*60)); 
        header('Location:login.php');
    }
}

function formLogout(){
    echo '<form  method="post" id="mylogin" name="mylogin"><input type="submit" value="Cerrar sesion"></form>';  
}
?>