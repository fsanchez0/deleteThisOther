<?php
include '../general/funcionesformato.php';
include '../general/sessionclase.php';
//include '../general/ftpclass.php';
include_once('../general/conexion.php');
//include ("../cfdi/cfdiclassn.php");
require('../fpdf.php');




$id=@$_GET["id"]; //para el Id de la consulta que se requiere hacer: de arrendamiento idhistoria, de libre idfolio
$filtro=@$_GET["filtro"]; //para la especificacion del tipo re recibo inmueble=null, libre=0;
$datosl=@$_GET["datosl"]; //para recibir todos los datos para la factura segun el layaut que biene de la facturalibre


//$cfd =  New cfdi32class;
//$ftp= New ftpcfdi;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='verfacturascbb.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta= $row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

	$htmlr= "<center><h1>Lista de facturas CBB</h1>";
	
	$selectfolio="<select name='filtro' onchange=\"cargarSeccion('$dirscript', 'contenido', 'filtro=' + this.value  );\"><option value='0'>Seleccione uno</option>";

	$sql="select * from folioscbb";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$selectfolio .= "<option value ='" . $row["idfoliocbb"] . "'>" . $row["rfccbb"] . "(" . $row["nosicofi"] . " - " . $row["vigenciacbb"] . ")</opton>";
	
	}
	$selectfolio .="</select></center>";	
	
	$htmlr .= "<p>Filtro por Folios $selectfolio </p>";

	$htmlr .=" <table border=\"1\"><tr><th>ID</th><th>Fecha</th><th>Factura</th><th>Concepto</th><th>Total</th><th>Acciones</th></tr>";
	
	if($filtro)
	{
		$filtro = " where idfoliocbb = $filtro ";
	}


	$sql="select * from facturacbb $filtro order by fechacbb DESC, foliocbb DESC";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$htmlr .= "<tr><td>" . $row["idfacturacbb"] . "</td><td>" . $row["fechacbb"] . "</td><td>" . $row["seriecbb"] . $row["foliocbb"] . "</td><td>" . $row["conceptocbb"] . "</td><td>" . $row["totalcbb"] . "</td>";
		$htmlr .= "<td><br><a href=\"scripts/general/descargarcbb.php?f=" .  $row["facturacbb"] . "\"  target=\"_blank\" >" .  $row["facturacbb"] . "\n</a></td></tr>";		
		
	}
	$htmlr .="</table>";






	echo $htmlr;

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>