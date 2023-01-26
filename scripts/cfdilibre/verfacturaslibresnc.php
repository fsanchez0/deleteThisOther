<?php
include '../general/funcionesformato.php';
include '../general/sessionclase.php';
include '../general/ftpclass.php';
include_once('../general/conexion.php');
include ("../cfdi/cfdiclassn.php");
require('../fpdf.php');




$id=@$_GET["id"]; //para el Id de la consulta que se requiere hacer: de arrendamiento idhistoria, de libre idfolio
$filtro=@$_GET["filtro"]; //para la especificacion del tipo re recibo inmueble=null, libre=0;
$datosl=@$_GET["datosl"]; //para recibir todos los datos para la factura segun el layaut que biene de la facturalibre
$anio=@$_GET["anio"];
$mes=@$_GET["mes"];

$cfd =  New cfdi32class;
$ftp= New ftpcfdi;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='verfacturaslibresnc.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=	$row['ruta'] . "/";	
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}


	if ($priv[0]!='1')
	{
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}



	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}



	$m1='';
	$m2='';
	$m3='';
	$m4='';
	$m5='';
	$m6='';
	$m7='';
	$m8='';
	$m9='';
	$m10='';
	$m11='';
	$m12='';
	
	if(!$anio!='')
	{
		$anio=date('Y');
	}
	if(!$mes!='')
	{
		$mes=date('m');
	}
	switch($mes)
	{
	case 1:
		$m1=' selected ';
		break;
	case 2:
		$m2=' selected ';
		break;
	case 3:	
		$m3=' selected ';
		break;
	case 4:
		$m4=' selected ';
		break;
	case 5:
		$m5=' selected ';
		break;
	case 6:
		$m6=' selected ';
		break;
	case 7:
		$m7=' selected ';
		break;
	case 8:
		$m8=' selected ';
		break;
	case 9:
		$m9=' selected ';
		break;
	case 10:
		$m10=' selected ';
		break;
	case 11:		
		$m11=' selected ';
		break;
	case 12:
		$m12= ' selected ';
	}



	$htmlr = "<center><h1>Lista de facturas libres para Notas de credito</h1>";
	
	$htmlr .="<form>Mes <select name='mes' onChange=\"cargarSeccion_new('$dirscript','contenido','mes=' + mes.value + '&anio=' + anio.value )\"><option value='01' $m1>Enero</option>";	
	$htmlr .="<option value='02' $m2>Febrero</option>";	
	$htmlr .="<option value='03' $m3>Marzo</option>";	
	$htmlr .="<option value='04' $m4>Abril</option>";
	$htmlr .="<option value='05' $m5>Mayo</option>";		
	$htmlr .="<option value='06' $m6>Junio</option>";	
	$htmlr .="<option value='07' $m7>Julio</option>";	
	$htmlr .="<option value='08' $m8>Agosto</option>";	
	$htmlr .="<option value='09' $m9>Septiembre</option>";	
	$htmlr .="<option value='10' $m10>Octubre</option>";	
	$htmlr .="<option value='11' $m11>Noviembre</option>";	
	$htmlr .="<option value='12' $m12>Diciembre</option>";	
	$htmlr .="</select>&nbsp;&nbsp;";	
	$htmlr .="A&ntilde;o: <input type='text' name='anio' value='$anio' onChange=\"cargarSeccion_new('$dirscript','contenido','mes=' + mes.value + '&anio=' + anio.value )\"></center></form>";
		
	
	

	$htmlr .=" <table border=\"1\"><tr><th>ID</th><th>Fecha</th><th>Factura</th><th>Concepto</th><th>Total</th><th>Acciones</th></tr>";
	

	//$sql="select * from cfdilibre";
	$sql="select * from flibrecfdi fl, facturacfdi f, cfdilibre l where month(l.fechal)=$mes and year(l.fechal)=$anio and fl.idfacturacfdi=f.idfacturacfdi and fl.idcfdilibre = l.idcfdilibre and f.xmlok=1 and l.notacreditol = 0";
	//$sql="select * from cfdilibre where month(fechal)=$mes and year(fechal)=$anio";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$htmlr .= "<tr><td>" . $row["idcfdilibre"] . "</td><td>" . $row["fechal"] . "</td><td>" . $row["seriel"] . $row["foliol"] . "</td><td>" . $row["conceptol"] . "</td><td>" . number_format($row["totall"],2,".",",") . "</td>";
		
		//$cont = file_get_contents("http://" . $_SERVER['SERVER_NAME'] . "/scripts/cfdi/paso/" . $row["seriel"] . $row["foliol"] . ".txt");
		//$cont = file_get_contents("../cfdi/paso/" . $row["seriel"] . $row["foliol"] . ".txt");
		
		//$htmlr .= "<td><textarea>$cont</textarea></td></tr>";
		$htmlr .="<td><input type=\"button\" value='Crear Nota' onClick=\"cargarSeccion('" . $ruta . "notacreditolibre.php','contenido', 'id=" . $row["idcfdilibre"] . "');\"></td></tr>";		
		//http://padilla.com/scripts/cfdi/paso/HonProfREN11595.txt
		//       padilla.com/scripts/cfdi/paso/HonProfPyB11595.txt
		//http://padilla.com/scripts/cfdi/paso/HonProfPyB11595.txt
		
		//preparar los botones o ligas

		
	}
	$htmlr .="</table>";





	echo $htmlr;

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>
