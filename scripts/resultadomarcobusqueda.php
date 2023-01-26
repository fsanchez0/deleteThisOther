<?php
//Es para realizar la busqueda dentro del directorio y mostrarla en
//el marcobusqueda, relacioando directamente con marcobusqueda.php

include 'general/sessionclase.php';
include_once('general/conexion.php');
header('Content-Type: text/html; charset=iso-8859-1');

$patron=@$_GET["patron"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	if(!$patron=="")
	{
		$sql="select * from directorio where CONCAT(nombre, ' ', apaterno, ' ', amaterno ) like '%$patron%' or denominacionf like '%$patron%' or notas like '%$patron%' ";
	
		$datos=mysql_query($sql);	
		echo "<table border=\"1\" width=\"100%\" class=\"letrasn\">";
		while($row = mysql_fetch_array($datos))
		{
	
			echo "<tr><td><a style=\"font-size:10;cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/datoscliente.php','contenido', 'id=". $row["iddirectorio"] . "');\">" . $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"] . " (" . $row["denominacionf"]  . ")</a></td></tr> ";
	
		}
		echo "</table>";
	}



}



?>