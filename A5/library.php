<?php
function connectToDB(){
    $conn = new mysqli('localhost','jfuentes','jfuentes','jfuentes_a5');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);

    }
    return $conn;
}

function consultDB($table,$data,$column){
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

function insertUserDB($username,$email,$pass){
    $conn = connectToDB();
    try{
        $conn -> query("INSERT INTO users (username, email, pass) VALUES ('$username','$email','$pass')");
        $conn->close();
    } catch(mysqli_sql_exception $e){
        $e->errorMessage();
    }
}

function loginDataTest($mail,$pass){
    $error = "";
    if(empty($mail)){
        $error = "*El campo \"Email\" es obligatorio.";

    } else {
        if(empty($pass)){//Preguntar si alguna forma de cifrar la contraseña desde el principio
            $error = "*El campo \"Password\" es obligatorio.";

        } else {
            if(strpos($mail, "@") == false){
                $error = "El campo \"Email\" debe ser un email.";

            } else {
                $error = "";
                $error = loginTest($mail,md5($pass));
            }

        }

    }
    return $error;

}

function loginTest($mail,$pass){
    $dataDB = consultDB("users",$mail,"email");
    $mailDB = $dataDB["email"];
    $passDB = $dataDB["pass"];
    $error = "";
    if($mail == $mailDB){
        $error = "";
        if($pass == $passDB){
            $error = "";
            $_SESSION["id"] = $dataDB["id"];
            header("Location:loged.php");

        }else {
            $error = "La contraseña es incorrecta.";

        }

    } else {
        $error = "Este usuario no existe.";

    }
    return $error;

}

function controlLogedPrivate(){
    if(!isset($_SESSION["id"])){
        header("Location:index.php");
    }
}

function controlLogedPublic(){
    if(isset($_SESSION["id"])){
        header("Location:loged.php");
    }
}

function logout(){
    session_destroy();
    header("Location:index.php");
}

function getButton(){
    if(isset($_REQUEST["logout"])){
        logout();
    } else if (isset($_REQUEST["config"])){
        header("Location:logedConfig.php");//Go user configuration
    } else if(isset($_REQUEST["goback"])){
        header("Location:".$_SERVER['HTTP_REFERER']);//Go back
    }
}

function registerDataTest($username,$mail,$pass){
    $error = "";
    if(empty($username)) {
        $error = "*El campo \"Nombre\" es obligatorio.";

    } else {
        if(empty($mail)){
            $error = "*El campo \"Email\" es obligatorio.";

        } else {
            if(empty($pass)){
                $error = "*El campo \"Password\" es obligatorio.";

            } else {
                $tmpData = preg_replace('([^A-Za-z])', ' ', "$username");
                if($tmpData == $username){
                    $error = "";
                    if(strpos($mail, "@") == false){
                        $error = "El campo \"Email\" debe ser un email.";

                    } else {
                    $error = registerTest($username,$mail,md5($pass));

                    }

                } else {
                    $error = "En el campo \"Nombre\" solo se permiten letras.";

                }

            }

        }

    }

    return $error;
}

function registerTest($username,$mail,$pass){
    $dataDB = consultDB("users",$mail,"email");
    if(isset($dataDB["mail"])){
        $mailDB = $dataDB["mail"];

    } else {
        $mailDB = "notExists";
        $error = "";
        if($mail != $mailDB){
            $error = "";
            insertUserDB($username,$mail,$pass);
            $_SESSION["tmpname"] = ucfirst($username);
            header("Location:login.php");

        } else {
            $error = "Este usuario ya existe.";

        }

    }
    return $error;
    
}

function userInfo(){
    $dataDB = consultDB("users",$_SESSION["id"],"id");
    $_SESSION["email"] = $dataDB["email"];
    $_SESSION["username"] = $dataDB["username"];
}

function configDataTest(){
    $usernameok = false;
    $mailok = false;
    $passok = false;
    $error = "";
    $dataDB = consultDB("users",$_SESSION["id"],"id");

    if(md5($_REQUEST["actualpassword"]) == $dataDB["pass"]){
        if(!empty($_REQUEST["chusername"])){
            if($_REQUEST["chusername"] != $dataDB["username"]){
                $tmpData = preg_replace('([^A-Za-z])', ' ', $_REQUEST['chusername']);
                if($tmpData == $_REQUEST["chusername"]){
                    $usernameok = true;

                } else {
                    $error = "En el campo \"Nombre\" solo se permiten letras.";

                }

            }else {
                $error = "El valor de \"Nombre\" introducido es igual al actual.";

            }

        } else {

        }

        if(!empty($_REQUEST["chmail"]) && $error == "" ){
            if($_REQUEST["chmail"] != $dataDB["email"]){
                if(strpos($_REQUEST["chmail"], "@") == false){
                    $error = "El campo \"Email\" debe ser un email.";

                } else {
                    $mailok = true;

                }

            } else {
                $error = "El valor de \"Email\" introducido es igual al actual.";

            }
                
        } else {

        }

        if(!empty($_REQUEST["chpassword"]) && $error == ""){
            if($_REQUEST["chmail"] != $dataDB["email"]){
                $passok = true;

            } else {
                $error = "El valor de \"Password\" introducido es igual al actual.";

            }

        } else{

        }

    } else {
        $error = "Debes introducir tu contraseña actual para cambiar los datos.";
    }

    if($error == "" && empty($mail) && empty($pass) && empty($username)){
        $error = "Debes introducir los datos nuevos para poder cambiarlos.";
    }

    if($mailok){
        $dataDB = consultDB("users",$_REQUEST["chmail"],"email");
        if(!isset($dataDB["email"])){
            updateDataTest($_REQUEST["chmail"],"email",$_SESSION['id']);
        } else {
            $error = "Este email ya esta siendo usado por otro usuario.";
        }

    }

    if($usernameok && $error == "" ){
        updateDataTest($_REQUEST["chusername"],"username",$_SESSION['id']);

    }


    if($passok && $error == "" ){
        updateDataTest(md5($_REQUEST["chpassword"]),"pass",$_SESSION['id']);

    }

    return $error;
}

function updateDataTest($data,$column,$userid){
    $conn = connectToDB();
    try{
        $sql =("UPDATE users SET ".$column."='".$data."' WHERE id=".$userid);
        $conn -> query($sql);
        $conn->close();
    } catch(mysqli_sql_exception $e){
        $e->errorMessage();
    }
}

?>