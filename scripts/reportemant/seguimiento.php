<?php

include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclass.php';


$rmantenimiento=@$_GET['rmantenimiento'];
$mensaje=@$_GET['solicitud'];


$sql = "select * from reportemant where idreportemant = $rmantenimiento";
$operacion = mysql_query($sql);
$row = mysql_fetch_array($operacion);

$datos = $row["idrmanteminieto"] . " (" . $row["rechar"] . ")<br>Solicitud:<br> " . $row["notasr"];
$inquilino = $row["idinquilino"];
$cerrado = $row["cerrado"];

$sql = "select * from inquilino where idinquilino = $inquilino";
$operacion = mysql_query($sql);
$row = mysql_fetch_array($operacion);

$nombre = $row["nombre"]; //. " " . $row["apaterno"] . " " . $row["amaterno"];



if(trim($mensaje)!="" && $cerrado!=1)
{
$sql="insert into seguimientomant (idreportemant, notasm, usuario,horasm, fechasm) values ($rmantenimiento,'$mensaje', '$nombre','". date("H:i") . "', '". date("Y-m-d") . "')";
$operacion = mysql_query($sql);
}

$seguimientod = "<table border = \"1\" ><tr><th>Fecha</th><th>Escribi&oacute;</th><th>Mensaje</th></tr>";
$sql = "select * from seguimientomant where idreportemant = $rmantenimiento";
$operacion = mysql_query($sql);
while($row = mysql_fetch_array($operacion))
{
	
	$seguimientod .= "<tr>";	
	$seguimientod .= "<td>" . $row["fechasm"] . " " . $row["horasm"] . "</td><td>" . $row["usuario"] . "</td><td>" . $row["notasm"] . "</td>";	
	$seguimientod .= "</tr>";		
		
}
$seguimientod .= "</table>";


echo <<<formulario
<center>
<h2>Seguimiento</h2>

<form>
Mensaje:<br>
<textarea name="mensaje" cols="50" rows="10"> </textarea><br>
<input type="button" value="Enviar" onClick="cargarSeccion('seguimiento.php','resultados', 'solicitud='+ mensaje.value + '&rmantenimiento=$rmantenimiento');">


<form>
<br><br><br>
$seguimientod
</center>
formulario;


?>