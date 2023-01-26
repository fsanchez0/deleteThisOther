<?php
//Es para realizar la busqueda dentro del directorio y mostrarla en
//el marcobusqueda, relacioando directamente con marcobusqueda.php

include 'general/sessionclase.php';
include 'general/calendarioclass.php';
include_once('general/conexion.php');
include 'general/funcionesformato.php';

$periodo=@$_GET["periodo"];

$fechas = New Calendario;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{


	$hoy = date("Y") . "-" . date("m") . "-" . date("d");
	$fechagsistema =mktime(0,0,0,substr($hoy, 5, 2),substr($hoy, 8, 2),substr($hoy, 0, 4));
	$sql3 = "select MIN(fechavencimiento) as fecham from historia where aplicado = false and idcontrato =";

	switch($periodo)
	{
	case 1: //Hoy

		//$sql1 = "select idcontrato, SUM(cantidad + iva) as csuma from historia where aplicado = false  and fechavencimiento = '$hoy' group by idcontrato";
		$sql1 = "select c.idcontrato,apaterno, SUM(cantidad + iva) as csuma from historia h,contrato c, inquilino i where h.idcontrato = c.idcontrato and c.idinquilino=i.idinquilino and aplicado = false  and activo=true   and fechavencimiento <= '$hoy' group by idcontrato,apaterno";
		//$sql2 = "select * from contrato where fechatermino = '$hoy'";
		$sql2="select * from contrato c, inquilino i where c.idinquilino = i.idinquilino and concluido <>true and  fechatermino <= '$hoy'";

		$sql4="select * from apartado a, inmueble i where a.idinmueble = i.idinmueble and cancelado = false   and vencimiento <= '$hoy'";
		break;

	case 2: //durante la siguiente semana
		$periodo = $fechas->calculafecha($fechagsistema, 7, 3);
		//$sql1="select idcontrato, SUM(cantidad + iva) as csuma from historia where aplicado = false  and fechavencimiento between '$hoy' and '$periodo' group by idcontrato";
		$sql1="select c.idcontrato, apaterno, SUM(cantidad + iva) as csuma from historia h,contrato c, inquilino i where h.idcontrato = c.idcontrato and c.idinquilino=i.idinquilino and aplicado = false   and fechavencimiento <='$periodo' group by idcontrato, apaterno";
		//$sql2="select * from contrato where  fechatermino between '$hoy' and '$periodo'";

		$sql2="select * from contrato c, inquilino i where c.idinquilino = i.idinquilino and  activo=true and  concluido <>true and fechatermino <= '$periodo'";

		$sql4="select * from apartado a, inmueble i where a.idinmueble = i.idinmueble and cancelado = false and vencimiento <= '$periodo'";
		break;

	case 3: //durante el siguiente mes
		$periodo = $fechas->calculafecha($fechagsistema, 2, 2);
		//$sql1="select idcontrato, SUM(cantidad + iva) as csuma from historia where aplicado = false  and fechavencimiento between '$hoy' and '$periodo' group by idcontrato";
		$sql1="select c.idcontrato, apaterno , SUM(cantidad + iva) as csuma from historia h,contrato c, inquilino i where h.idcontrato = c.idcontrato and c.idinquilino=i.idinquilino and aplicado = false    and fechavencimiento <= '$periodo' group by idcontrato, apaterno";
		//$sql2="select * from contrato where  fechatermino between '$hoy' and '$periodo'";

		$sql2="select * from contrato c, inquilino i where c.idinquilino = i.idinquilino and concluido <>true and fechatermino <= '$periodo'";

		$sql4="select * from apartado a, inmueble i where a.idinmueble = i.idinmueble and cancelado = false and vencimiento <= '$periodo'";
		break;
	}


	$periodo = $fechas->calculafecha($fechagsistema, 7, 3);

	$lista ="<table border = \"1\" class=\"letrasn\"> ";


	$operacion = mysql_query($sql1);
	while($row = mysql_fetch_array($operacion))
	{



		$operacion2 = mysql_query($sql3 . $row["idcontrato"]);
		$r=mysql_fetch_array($operacion2);

		$fa =mktime(0,0,0,substr($hoy, 5, 2),substr($hoy, 8, 2),substr($hoy, 0, 4));
		$fb =mktime(0,0,0,substr($r['fecham'], 5, 2),substr($r['fecham'], 8, 2),substr($r['fecham'], 0, 4));
		//echo $hoy . " = " . $r['fecham'] . " ". ($fa<=$fb) . "<br>";
		if($fa>=$fb)
		{
			$lista .= "<tr><td><a style=\"font-size:10;cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/inmuebles/cobro.php','contenido', 'paso=1&idcontrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " (" . CambiaAcentosaHTML($row["apaterno"]) . ") " . " $" .  number_format($row["csuma"],2) . "</a></td></tr> ";
		}
		else
		{
			$lista .= "<tr><td><a style=\"font-size:10;cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/inmuebles/anticipado2.php','contenido', 'paso=1&idcontrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " $" . $row["csuma"] . "</a></td></tr> ";
		}
		//$lista .= "<tr><td><a style=\"font-size:10;\" onClick=\"jabascript:cargarSeccion('scripts/cobro.php','contenido', 'paso=1&idcontrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " $" . $row["csuma"] . "</a></td></tr> ";
		//$lista .= "<tr><td><a style=\"font-size:10;\" onClick=\"jabascript:cargarSeccion('scripts/inmuebles/cobro.php','contenido', 'paso=1&idcontrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " $" . $row["csuma"] . "</a></td></tr> ";



	}


	//$sql="select * from contrato where  fechatermino between '$hoy' and '$periodo'";
	$operacion = mysql_query($sql2);
	while($row = mysql_fetch_array($operacion))
	{

		$lista .= "<tr><td><b><a style=\"font-size:10;color:red;cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/inmuebles/edocuenta.php','contenido', 'contrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " (" . CambiaAcentosaHTML($row["apaterno"]) . ")" . " " . $row["fechatermino"] . "<b></td></tr> ";
		//$lista .= "<tr><td><b><a style=\"font-size:10;color:red;\" onClick=\"jabascript:cargarSeccion('scripts/datoscontrato.php','contenido', 'idcontrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " " . $row["fechatermino"] . "</a><b></td></tr> ";
		//$lista .= "<tr><td><a style=\"font-size:10;\" onClick=\"jabascript:cargarSeccion('scripts/inmuebles/datoscontrato.php','contenido', 'idcontrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " " . $row["fechatermino"] . "</a></td></tr> ";


	}


	$operacion = mysql_query($sql4);
	while($row = mysql_fetch_array($operacion))
	{

		$lista .= "<tr><td><b><a style=\"font-size:10;color:red;cursor: pointer\" onClick=\"jabascript:alert('Esta por vencerce este apartado');\">Apartado " . $row["idapartado"] . " (" . CambiaAcentosaHTML($row["calle"] . " " .$row["numeroext"]. " " . $row["numeroint"]) .  ")" . " " . $row["vencimiento"] . "<b></td></tr> ";
		//$lista .= "<tr><td><b><a style=\"font-size:10;color:red;\" onClick=\"jabascript:cargarSeccion('scripts/datoscontrato.php','contenido', 'idcontrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " " . $row["fechatermino"] . "</a><b></td></tr> ";
		//$lista .= "<tr><td><a style=\"font-size:10;\" onClick=\"jabascript:cargarSeccion('scripts/inmuebles/datoscontrato.php','contenido', 'idcontrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " " . $row["fechatermino"] . "</a></td></tr> ";


	}


	echo $lista .="</table>";




}



?>