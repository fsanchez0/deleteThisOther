<?php
//Es para realizar la busqueda dentro del directorio y mostrarla en
//el marcobusqueda, relacioando directamente con marcobusqueda.php

include 'general/sessionclase.php';
include_once('general/conexion.php');
header('Content-Type: text/html; charset=iso-8859-1');

$patron=@$_GET["patron"];
$optd =@$_GET["filtro"]; 

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	if(!$patron=="")
	{
	
		switch($optd)
		{
		case 1://nombre
			$sql="select *,CONCAT(nombre, ' ',nombre2, ' ', apaterno, ' ', amaterno ) as nomdue, d.idduenio as idd from duenio d where CONCAT(nombre, ' ',nombre2, ' ', apaterno, ' ', amaterno ) like '%$patron%'";

			break;
		case 2://inmueble
			$sql="select distinct(CONCAT(nombre, ' ',nombre2, ' ', apaterno, ' ', amaterno )) as nomdue, d.idduenio as idd from duenio d, duenioinmueble di, inmueble i where d.idduenio = di.idduenio and di.idinmueble = i.idinmueble and (CONCAT(nombre, ' ',nombre2, ' ', apaterno, ' ', amaterno ) like '%$patron%' or calle like '%$patron%'  or colonia like '%$patron%' or notas like '%$patron%'  or descripcion like '%$patron%' or inventario like '%$patron%') ";

			break;
		case 3://apoderado
			$sql="select *, CONCAT(nombre, ' ',nombre2, ' ', apaterno, ' ', amaterno ) as nomdue,d.idduenio as idd from duenio d, apoderado a where d.idduenio = a.idduenio and (CONCAT(nombre, ' ',nombre2, ' ', apaterno, ' ', amaterno ) like '%$patron%' or CONCAT(nombreap, ' ',nombre2ap, ' ', apaternoap, ' ', amaternoap ) like '%$patron%')";

			break;
		}
		//$sql="select * from directorio where CONCAT(nombre, ' ', apaterno, ' ', amaterno ) like '%$patron%' or denominacionf like '%$patron%' or notas like '%$patron%' ";
		//echo $sql;
		$datos=mysql_query($sql);	
		echo "<table border=\"1\" width=\"100%\" class=\"letrasn\">";
		while($row = mysql_fetch_array($datos))
		{
	
			echo "<tr><td><a style=\"font-size:10;cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/infoduenio.php','contenido', 'id=". $row["idd"] . "');\">" . utf8_decode ($row["nomdue"]) .  "</a></td></tr> ";
	
		}
		echo "</table>";
	}



}



?>