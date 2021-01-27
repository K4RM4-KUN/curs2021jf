<?php
session_start();
include("library.php");
userInfo();
controlLogedPrivate();
controlLogedAdmin();
if(isset($_REQUEST["modify"])){
    $error =  updateAdministratorTest($_REQUEST["id"],$_REQUEST["username"],$_REQUEST["email"],$_REQUEST["rol"]);

} else if(isset($_REQUEST["delete"])){
    deleteAdministratorTest($_REQUEST["id"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration</title>
</head>
<body>
    <a href="loged.php"><button type="button">Atras</button></a>
    <h1 style="margin-top:180px; text-align: center;">ADMINISTRACION DE USUARIOS</h1>
    <p style="font-weight: bold; text-align: center;"><?php if(isset($error)){echo $error;}else{echo "</br>";}?></p>
    <div style="margin-top:25px;">
    <center>
        <table border = 1>
            <tr>
            <td style="text-align: center; font-weight: bold;" width="50px">ID</td>
            <td style="text-align: center; font-weight: bold;" >Nombre</td>
            <td style="text-align: center;font-weight: bold;" >Email</td>
            <td style="text-align: center; font-weight: bold;" >Rol</td>
            <td style="text-align: center; font-weight: bold;" ></td>
            <td style="text-align: center; font-weight: bold;" ></td>
            </tr>
            <?php
            $conn = connectToDB();
            $sql = "SELECT * FROM users";
            $result = $conn->prepare($sql);
            if(!$result = $conn ->query($sql)){
                die("error ejecuntado la consulta".$conn->error);

            }

            if ($result->num_rows >= 0){
                while($users = $result->fetch_assoc()){
                    $admin = userType($users["id"]);
                    echo '<tr>
                        <form  method="post" id="changeUserData" name="'.$users["id"].'">
                        <td><input name="id" style="text-align: center;" size="3" value="'.$users["id"].'" readonly></td>
                        <td><input name="username" style="text-align: center;" type="text" size="10" value="'.ucfirst($users["username"]).'"></td>
                        <td><input name="email" style="text-align: center;" type="text" size="22" value="'.$users["email"].'"></td>';
                        if($_SESSION["email"] == $users["email"]){
                            echo '<td><input name="rol" style="text-align: center;" type="text" size="5" value="admin" readonly></td>';
                        } else if(!$admin){
                                echo'<td><select name="rol"><option value="user">USER</option><option value="1">ADMIN</option></select></td>';
                            } else{
                                echo'<td><select name="rol"><option value="admin">ADMIN</option><option value="2">USER</option></select></td>';
                            }
                        echo '<td><input type="submit" value="Modificar" name="modify"></td>';
                        
                        if($users["email"] != $_SESSION["email"]){  
                            echo '<td><input type="submit" value="Eliminar" name="delete"></td>';
                        } else {
                            echo '<td><input type="submit" value="Eliminar" name="delete" disabled></td>';
                        }
                        echo '</form>
                        </tr>';

                }
        
            }

            $result->free();
            $conn->close();
            ?>
        </table>
    </center>
    </div>
</body>
</html>