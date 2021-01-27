<?php
echo(md5("pass"));
//$conn = new mysqli('localhost','usuari','passdb','basedades');
$conn = new mysqli('localhost','jfuentes','jfuentes','jfuentes_a5');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM users WHERE `username` = \"saul\"";
$result = $conn->prepare($sql);
if(!$result = $conn ->query($sql)){
    die("error ejecuntado la consulta".$conn->error);
}

if ($result->num_rows >= 0){
    while($users = $result->fetch_assoc()){
        echo $users["username"].", ".$users["email"]."<br>";
    }
}
$result->free();
$conn->close();
?>