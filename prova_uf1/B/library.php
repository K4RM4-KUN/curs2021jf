<?php
//
function privatePage(){
    if(!isset($_SESSION["loged"])){
        header("Location:index.php");
    } else if($_SESSION["loged"] == false){
        header("Location:index.php");
    }
}

function publicPage(){
    if(isset($_SESSION["loged"])){
        header("Location:home.php");
    }
}

//
function connectToDataBase(){
    $conn = new mysqli('localhost','jfuentes','jfuentes','jfuentes_db_prova');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

//
function consultDataBase($table,$data,$column){
    $conn = connectToDataBase();
    $sql = "SELECT * FROM $table where $column = \"$data\"";
    $result = $conn->prepare($sql);
    if(!$result = $conn ->query($sql)){
        die("Error consultando la base de datos:".$conn->error);

    }

    if ($result->num_rows >= 0){
        $users = $result->fetch_assoc();

    }

    return $users;
    $result->free();
    $conn->close();
}

//
function testLogin($username,$password){
    $error = "";
    if(empty($username) || empty($password)){
        $error = "Debe introducir los datos para hacer login.";
    } else {
        if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $error = "El campo \"Email\" debe ser un email.";
        } else {
            $error = testLoginDataBase($username,md5($password));
        }
    }
    return $error;
}

//
function testLoginDataBase($username,$password){
    $error = "";
    $recieve = consultDataBase("usuaris_examen",$username,"username");
    if(!isset($recieve["username"])){
        $error = "Este usuario no existe";
    } else if($recieve["password"] == $password){
        $_SESSION["loged"] = true;
        $_SESSION["name"] = $recieve["nom"];
        $_SESSION["username"] = $recieve["username"];
        header("Location: home.php");
    } else if ($recieve["password"] != $password){
        $error = "La contraseña no es correcta";
    }
    return $error;
}

//
function testRecover($username,$solution,$result){
    $error = "";
    if(!empty($solution)){
        if($solution == $result){
            if(empty($username)){
                $error = "Debes introducir el email en username para que te enviemos una nueva contraseña a tu email.";
            } else {
                $error = sendMailForRecover($username);
            }
        } else {
            $error = "Debes resolver correctamente la suma para conseguir una nueva contraseña.";
        }
    } else {
        $error = "Debes resolver la suma para conseguir una nueva contraseña.";
    }
    return $error;
}

//
function sendMailForRecover($username){
    $error = "";
    $recieve = consultDataBase("usuaris_examen",$username,"username");
    if(isset($recieve["username"])){
        $newPass = newCode(8);
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = "jfuentesa@fp.insjoaquimmir.cat";
        $mail->Password = "calabasa";
        $mail->setFrom('no-replay@my.web', 'Password Recovery - My Web.');
        $mail->addAddress($username, 'Sr/ra');
        $mail->Subject = 'Recuperacion de contraseña.';
        $mail->Body = '<html><body>Hola,'.$username.'</br>La contraseña solo se puede utilizar una vez!----Esta es tu nueva contraseña: <b>'.$newPass.'</b>-----<a href="https://dawjavi.insjoaquimmir.cat/jfuentes/UF1/prova_uf1/B/index.php">LOGIN AQUI</a>.</body></html>';
        $mail->CharSet = 'UTF-8'; // Con esto ya funcionan los acentos
        $mail->IsHTML(true);
        if ($mail->send()){
            $error = "Un correo ha sido enviado a ".$username;
            $_SESSION["usedTmpPass"] = false;
            $conn = connectToDataBase();
            $newPass = md5($newPass);
            try{
                $sql = ("UPDATE usuaris_examen SET password='$newPass' WHERE username='$username'");
                $conn -> query($sql);
                $conn->close();
            } catch(mysqli_sql_exception $e){
                $e->errorMessage();
            }
        } else {
            $error = "Parece que ha habido un error, prueba otra vez.";
        }
    } else {
        $error = "Este usuario no existe";
    }
    return $error;
}

//
function newCode($len = 45){
    $result ="";
    $chars = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    for($i=0;$i<$len;$i++){
        $result = $result.$chars[rand(0, strlen($chars)-1)];
    }
    return $result;
}

//
function forRecover(){
    $v1 = rand(1,9);
    $v2 = rand(1,9);
    $problem = $v1." + ".$v2." = ";
    $_SESSION["result"] = intval($v1+$v2);
    return $problem;
}

//
function testPasswordRecover($pass,$passR,$username){
    $error = "";
    if($pass == $passR){
        $conn = connectToDataBase();
        $pass = md5($pass);
        try{
            $sql = ("UPDATE usuaris_examen SET password='$pass' WHERE username='$username'");
            $conn -> query($sql);
            $conn->close();
        } catch(mysqli_sql_exception $e){
            $e->errorMessage();
        }
        $_SESSION["usedTmpPass"] = true;
    } else {
        $error = "Las contraseñas no coinciden, prueba otra vez...";
    }
    return $error;
}
?>