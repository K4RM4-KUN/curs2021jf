<?php
    session_start();
    include 'libreria.php';
    $mail= controlLogin();
    logout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$mail?></title>
</head>
<body>
    <div style="text-align: center; margin-top:220px;">
        <h1>Hello <?=$mail?>!</h1>
        <?php formLogout(); ?>
    </div>
</body>
</html>