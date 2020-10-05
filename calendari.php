<html>
<body>
<?php
ini_set('display_errors', '0');
$mesActual = getdate();#getdate() es una funcion que crea una array con disitintos datos sobre la fecha(año actual ,mes actual ,dia actual...), estos datos me ayudaran durante el programa para crear el calendario correctamente
$monthDays = date(t)+1;#date(t) te da un valor numerico igual a la cantidad de dias de el mes actual
$counter = 1;#esto es simplemente un contador para uno de los bucles
$diaSemana=date("w",mktime(0,0,0,$mesActual[mday],1,$mesActual[year]));#Esta variable consigue el valor numerico igual al dia de la semana del primer dia de el mes actual, pasandole a mktime primero la hora(0,0,0) y despues el mes actual, luego 1 que se refiere al primer dia del mes y por utlimo el año actual
switch ($diaSemana) {
    case 0:
        $diaSemana = $diaSemana+1;
        break;
    case 1:
        $diaSemana = $diaSemana+5;
        break;
    case 2:
        $diaSemana = $diaSemana-2;
        break;
    case 3:
        $diaSemana = $diaSemana-2;
        break;
    case 4:
        $diaSemana = $diaSemana-2;
        break;
    case 5:
        $diaSemana = $diaSemana-2;
        break;
    case 6:
        $diaSemana = $diaSemana-2;
        break;
    }
#Tabla
echo('<table border="1" style="text-align:center;>');
echo('<tr><th colspan="7"></th></tr>');
echo('<tr><th colspan="7">');
echo("$mesActual[month]</th></tr>");
echo("<tr><th>Lunes</th><th>Martes</th><th>Miercoles</th><th>Jueves</th><th>Viernes</th><th>Sabado</th><th>Domingo</th></tr>");

#Creación del calendario
while ($counter != $monthDays) {
    echo("<tr>");

    $week = 0;
    if ($counter == 1){ #Este if hace que el primer dia del mes empiece por el dia de la semana(lunes,martes...) correcto
        for($i=0;$i < $diaSemana;$i++){
            echo('<td></td>');
        	$week++;
        }

    }

    while ($week != 7 and $counter != $monthDays){ #Con este bucle creo todos los dias del mes
        if ($counter == $mesActual[mday]){
            echo('<td bgcolor="FF5050" style="font-weight:bold;">');
            echo("$counter</td>");
        }else{
        echo("<td>$counter</td>");
        }
        $counter++;
        $week++;
    }

    echo("</tr>");
}
echo('</table>');
?>
</body>
</html>