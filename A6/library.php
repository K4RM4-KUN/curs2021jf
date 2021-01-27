<?php
//connectToDB devuelve la variable $conn para conectarse a la base de datos
function connectToDB(){
    $conn = new mysqli('localhost','jfuentes','jfuentes','jfuentes_a5');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);

    }
    return $conn;
}

//consultDB es una consulta basica contra la base de datos
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

//insertUserDB inserta datos en la tabla users de la base de datos
function insertUserDB($username,$email,$pass){
    $conn = connectToDB();
    try{
        $conn -> query("INSERT INTO users (username, email, pass, id_rol) VALUES ('$username','$email','$pass',2)");
        $conn->close();
    } catch(mysqli_sql_exception $e){
        $e->errorMessage();
    }
}

//loginDataTest comprueba que los datos de login hayan sido introducidos y que el mail tenga @
function loginDataTest($mail,$pass){
    $error = "";
    if(empty($mail)){
        $error = "*El campo \"Email\" es obligatorio.";

    } else {
        if(empty($pass)){//Preguntar si alguna forma de cifrar la contraseña desde el principio
            $error = "*El campo \"Password\" es obligatorio.";

        } else {
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $error = "El campo \"Email\" debe ser un email.";

            } else {
                $error = "";
                if(rememberActive()){
                    $error = loginTest($mail,$pass);
                } else {
                    $error = loginTest($mail,md5($pass));
                }
            }

        }

    }
    return $error;

}

//loginTest comprueba contra la base de datos que el mail y la password son correctos
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
            remember();
            header("Location:loged.php");

        }else {
            $error = "La contraseña es incorrecta.";

        }

    } else {
        $error = "Este usuario no existe.";

    }
    return $error;

}

//controlLogedPrivate Comprueba si el usuario ya ha hecho login para que pueda acceder a las paginas "privadas"
function controlLogedPrivate(){
    if(!isset($_SESSION["id"])){
        header("Location:index.php");
    }
}

//controlLogedPublic Comprueba si el usuario ha hecho login para que no pueda acceder a las paginas de login o registro
function controlLogedPublic(){
    if(isset($_SESSION["id"])){
        header("Location:loged.php");
    } else if(!isset($_COOKIE["politicTerms"])){
        header("Location:index.php");
    }
}

//controlLogedPublic Comprueba si el usuario ha hecho login para que no pueda acceder a las paginas de login o registro
function controlLogedPublicIndexOnly(){
    if(isset($_SESSION["id"])){
        header("Location:loged.php");
    }
}

//userType devuelve true si el usuario es un admin y false si es un user
function userType($id){
    $conn = connectToDB();
    $sql = "SELECT users.id, rol.rol FROM rol INNER JOIN users ON rol.id = users.id_rol WHERE users.id = $id";
    $result = $conn->prepare($sql);
    if(!$result = $conn ->query($sql)){
        die("error ejecuntado la consulta".$conn->error);

    }

    if ($result->num_rows >= 0){
        $users = $result->fetch_assoc();

    }
    $tmpBool = false;
    if($users["rol"] == "admin"){
        $tmpBool = true;
    }

    $result->free();
    $conn->close();
    return $tmpBool;
}

//controlLogedAdmin comprueba usando la funcion userType si el usuario que ha hecho login es un admin
function controlLogedAdmin(){
    $admin = userType($_SESSION["id"]);
    if(!$admin){
        header("Location:loged.php");
    }
}

//logout destruye la session y te envia a "index.php" que se podria decir que es la pagina de inicio
function logout(){
    session_destroy();
    setcookie("rememberPass", "", time() - +(365*24*60*60)); 
    setcookie("rememberMail", "", time() - +(365*24*60*60)); 
    header("Location:index.php");
}

//getButton sirve para usar lso botones de configuracion o logout
function getButton(){
    $admin = userType($_SESSION["id"]);
    if(isset($_REQUEST["logout"])){
        logout();
    } else if (isset($_REQUEST["config"])){
        header("Location:logedConfig.php");//Go user configuration
    } else if(isset($_REQUEST["product"])){          
        header("Location:productMenu.php");
    }else if($admin){
        if(isset($_REQUEST["administration"])){
            header("Location:adminConfig.php");
        }
    } 
}

//registerDataTest comprueba que los campos hayan sido rellenados y ademas de que el nombre solo contenga letras y el mail tenga un @
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
                    if($pass == $_REQUEST["comppassword"]){
                        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                            $error = "El campo \"Email\" debe ser un email.";
            
                        } else {
                        $error = registerTest($username,$mail,md5($pass));

                        }

                    } else {
                        $error = "Las contraseñas no son iguales.";

                    }

                } else {
                    $error = "En el campo \"Nombre\" solo se permiten letras.";

                }

            }

        }

    }

    return $error;
}

//registerTest comprueba que los datos clave no esten repetidos dentro de la base de datos y si es asi los introduce en la base de datos
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

//userInfo crea dos variables session con el email y el usuario que se actualizan cada vez que se entra en una pagina privada
function userInfo(){
    $dataDB = consultDB("users",$_SESSION["id"],"id");
    $_SESSION["email"] = $dataDB["email"];
    $_SESSION["username"] = $dataDB["username"];
    $_SESSION["rol"] = $dataDB["id_rol"];
}

//configDataTest comprueba que los datos de cambio de datos introducidos por el usuarios tengan el formato correcto, no se repitan y que no sean iguales a los actuales
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
                if (!filter_var($_REQUEST["chmail"], FILTER_VALIDATE_EMAIL)) {
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
            if($_REQUEST["chpassword"] != $dataDB["pass"]){
                if($_REQUEST["chpassword"] == $_REQUEST["cmppassword"]){
                    $passok = true;

                } else {
                    $error = "Las contraseñas no son iguales.";
                }

            } else {
                $error = "El valor de \"Password\" introducido es igual al actual.";

            }

        } else{
        }

    } else {
        $error = "Debes introducir tu contraseña actual para cambiar los datos.";
    }

    if($error == "" && empty($_REQUEST["chmail"]) && empty($_REQUEST["chpassword"]) && empty($_REQUEST["chusername"])){
        $error = "Debes introducir los datos nuevos para poder cambiarlos.";

    }

    if($mailok){
        $dataDB = consultDB("users",$_REQUEST["chmail"],"email");
        if(!isset($dataDB["email"])){
            updateDataTest($_REQUEST["chmail"],"email",$_SESSION['id']);
            $error = "Los datos han sido cambiado correctamente.";
        } else {
            $error = "Este email ya esta siendo usado por otro usuario.";
        }

    }

    if($usernameok && ($error == "" || $error == "Los datos han sido cambiado correctamente.")  ){
        updateDataTest($_REQUEST["chusername"],"username",$_SESSION['id']);
        $error = "Los datos han sido cambiado correctamente.";
    }


    if($passok && ($error == "" || $error == "Los datos han sido cambiado correctamente.") ){
        updateDataTest(md5($_REQUEST["chpassword"]),"pass",$_SESSION['id']);
        $error = "Los datos han sido cambiado correctamente.";
    }

    return $error;
}

//updateDataTest se encarga de cambiar los datos del usuario(esta funcion es llamada desde la funcion "configDataTest")
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

//configDataTest comprueba que los datos de cambio de datos introducidos por el admin en el panel de administracion tengan el formato correcto, no se repitan y que no sean iguales a los actuales
function updateAdministratorTest($id,$username,$email,$rol){
    $usernameok = false;
    $mailok = false;
    $rolok = false;
    $error = "";
    $dataDB = consultDB("users",$id,"id");
    if($id == $dataDB["id"]){
        if(!empty($username)){
            if($username != $dataDB["username"]){
                $tmpData = preg_replace('([^A-Za-z])', ' ', $username);
                if($tmpData == $username){
                    $usernameok = true;

                } else {
                    $error = "En el campo \"Nombre\" solo se permiten letras.";

                }

            }else {

            }

        } else {

        }

        if(!empty($email) && $error == "" ){
            if($email != $dataDB["email"]){
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "El campo \"Email\" debe ser un email.";
    
                } else {
                    $mailok = true;

                }

            } else {

            }
                
        } else {

        }

        if(!empty($rol) && $error == ""){
            if($rol != $dataDB["id_rol"]){
                $rolok = true;

            } else {

            }

        } else{

        }

    } else {
        $error = "No puedes cambiar el \"ID\" de un usuario.";
    }

    if($error == "" && empty($email) && empty($password) && empty($username)){
        $error = "Debes introducir los datos nuevos para poder cambiarlos.";

    }

    if($mailok){
        $dataDB = consultDB("users",$email,"email");
        if(!isset($dataDB["email"])){
            updateDataTest($email,"email",$id);
            $error = "Los datos han sido cambiado correctamente.";
        } else {
            $error = "Este email ya esta siendo usado por otro usuario.";
        }

    }

    if($usernameok && ($error == "" || $error == "Los datos han sido cambiado correctamente.")  ){
        updateDataTest($username,"username",$id);
        $error = "Los datos han sido cambiado correctamente.";
    }


    if($rolok && ($error == "" || $error == "Los datos han sido cambiado correctamente.") ){
        updateDataTest($rol,"id_rol",$id);
        $error = "Los datos han sido cambiado correctamente.";
    }

    if($username == $dataDB["username"] && $rol == $dataDB["id_rol"]  && $email == $dataDB["email"] ){
        $error = "Ningun dato ha sido cambiado.";

    }

    return $error;
}

//deleteAdministratorTest elimina el usuario escogido en el panel de administracion
function deleteAdministratorTest($id){
    $conn = connectToDB();
    try{
        $sql =("DELETE FROM users WHERE id=".$id);
        $conn -> query($sql);
        $conn->close();
    } catch(mysqli_sql_exception $e){
        $e->errorMessage();
    }
}

//politicCookie crea la cookie politicTerms si el usuario accepta esta
function politicCookie(){
    if(isset($_COOKIE['politicTerms'])){
        $error = null;
    } else{
        $error = "DEBES ACEPTAR LAS COOKIES PARA PODER NAVEGAR POR LA WEB!";
    }
    return $error;
}

//rememberActive comprueba que la cookie rememberMail existe y devuelve un boolean
function rememberActive(){
    if(isset($_COOKIE['rememberMail'])){
        $tmpBool = true;
    } else {
        $tmpBool = false;
    }
    return $tmpBool;
}

//rememberUsing pasa los valores de las cookies rememberMail y rememberPass al comprobador del login para logearse con esos datos
function rememberUsing(){
    if(rememberActive()){
        $error = loginDataTest($_COOKIE["rememberMail"],$_COOKIE["rememberPass"]);
        echo $error;
    } 
}

//remember comprueba que el campo remember me ha sido seleccionado y entonces guardara los valores introducidos por el usuario en dos cookies "rememberMail y rememberPass"
function remember(){
    if(rememberActive()){
        $cookiValues = [$_REQUEST["mail"],md5($_REQUEST["password"])];
        setcookie("rememberMail",$cookiValues[0],time()+(1*31*60*60));
        setcookie("rememberPass",$cookiValues[1],time()+(1*31*60*60));
    } else {
        $nothing = "nothinghappend";
    }

}


?>