<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$patron=@$_GET["id"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	
	$sql="select * from directorio where iddirectorio=$patron";
	
	$datos=mysql_query($sql);
	//echo "<table border=\"1\">";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<h2>" . $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "</h2>";
		$html .= "<table border=\"1\">";
		$html .= "<tr><td><b>Direcci&oacute;n</b></td><td>" . $row["direccion"] . "</td></tr> ";
		$html .= "<tr><td><b>Tel. Particular</b></td><td>" . $row["telparticular"] . "</td></tr> ";
		$html .= "<tr><td><b>Tel. Oficina</b></td><td>" . $row["teloficina"] . "</td></tr> ";
		$html .= "<tr><td><b>tel. Movil</b></td><td>" . $row["telmovil"] . "</td></tr> ";
		$html .= "<tr><td><b>Otros tels.</b></td><td>" . $row["telotros"] . "</td></tr> ";		
		$html .= "<tr><td><b>e-mail</b></td><td>" . $row["email"] . "</td></tr> ";
		$html .= "<tr><td><b>Nombre fiscal</b></td><td>" . $row["denominacionf"] . "</td></tr> ";		
		$html .= "<tr><td><b>RFC</b></td><td>" . $row["rfc"] . "</td></tr> ";
		$html .= "<tr><td><b>Direcci&oacute;n fiscal</b></td><td>" . $row["direccionf"] . "</td></tr> ";
		$html .= "<tr><td><b>Notas</b></td><td>" . $row["notas"] . "</td></tr> ";
		echo CambiaAcentosaHTML($html);
		
	}
	echo "</table>";



}


?>