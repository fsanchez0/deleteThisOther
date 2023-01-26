<?php
include 'general/sessionclase.php';
include_once('general/conexion.php');
include '../general/funcionesformato.php';
header('Content-Type: text/html; charset=iso-8859-1');

$patron=@$_GET["id"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	
	$sql="select * from directorio where iddirectorio=$patron";
	
	$datos=mysql_query($sql);
	//echo "<table border=\"1\">";
	while($row = mysql_fetch_array($datos))
	{
		echo "<h2>" . $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "</h2>";
		echo "<table border=\"1\">";
		echo "<tr><td><b>Direcci&oacute;n</b></td><td>" . utf8_decode($row["direccion"]) . "</td></tr> ";
		echo "<tr><td><b>Tel. Particular</b></td><td>" . utf8_decode($row["telparticular"]) . "</td></tr> ";
		echo "<tr><td><b>Tel. Oficina</b></td><td>" . utf8_decode($row["teloficina"]) . "</td></tr> ";
		echo "<tr><td><b>tel. Movil</b></td><td>" . utf8_decode($row["telmovil"]) . "</td></tr> ";
		echo "<tr><td><b>Otros tels.</b></td><td>" . utf8_decode($row["telotros"]) . "</td></tr> ";		
		echo "<tr><td><b>e-mail</b></td><td>" . utf8_decode($row["email"]) . "</td></tr> ";
		echo "<tr><td><b>Pagina Web</b></td><td>" . utf8_decode($row["pagina"]) . "</td></tr> ";
		echo "<tr><td><b>Nombre fiscal</b></td><td>" . utf8_decode($row["denominacionf"]) . "</td></tr> ";		
		echo "<tr><td><b>RFC</b></td><td>" . utf8_decode($row["rfc"]) . "</td></tr> ";
		echo "<tr><td><b>Direcci&oacute;n fiscal</b></td><td>" . utf8_decode($row["direccionf"]) . "</td></tr> ";
		echo "<tr><td><b>Notas</b></td><td>" . utf8_decode($row["notas"]) . "</td></tr> ";
		
		
	}
	echo "</table>";



}


?>