<?php

    include_once('../general/conexion.php');
    ini_set("display_errors",1);

    $sql = "SELECT idedoduenio, notaedo FROM rentaspb_adminren.edoduenio WHERE notaedo LIKE 'Honorario %'";
	$operacion = mysql_query($sql);
    while($row=mysql_fetch_array($operacion)){
        // echo "<br>";
        // print_r($row);
        // echo "<br>";

        $porcentaje = array();
        preg_match("/\d+%/",$row["notaedo"],$porcentaje);
        // echo "<br>";
        // print_r($porcentaje[0]);
        // echo "<br>";
        $nuevaNotaEdo = "({$porcentaje[0]} mas 16% I.V.A)"; 
        $nuevaNotaEdo=preg_replace("/\d+%/",$nuevaNotaEdo,$row["notaedo"]);

        $sql1 = "UPDATE edoduenio
        SET 
            notaedo = '$nuevaNotaEdo'
        WHERE
            idedoduenio = {$row['idedoduenio']};";

        echo $sql1;
        echo "<br>";

        // @mysql_query($sql1);
    }
?>