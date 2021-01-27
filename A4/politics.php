<?php
    setcookie("politicTerms",$cookiTermValue = true,time()+(365*24*60*60));
    header("Location:login.php");
?>