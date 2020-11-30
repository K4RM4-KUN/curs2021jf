<?php
function generateHMS(){
    for($i=0;$i<24;$i++){
        if(date("H") != $i){
            echo($i." ");
        } else{
            echo("<b>".$i."</b> ");
        }
    }
    echo("</br>");
    for($i=0;$i<60;$i++){
        if(date("i") != $i){
            echo($i." ");
        } else{
            echo("<b>".$i."</b> ");
        }
    }
    echo("</br>");
    for($i=0;$i<60;$i++){
        if(date("s") != $i){
            echo($i." ");
        } else{
            echo("<b>".$i."</b> ");
        }
    }
}


?>