<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


//Modulo

$filtro=@$_GET["filtro"];
//$idcontrato=@$_GET["idcontrato"];
$idduenio=@$_GET["idduenio"];

$miwhere="";

$titulo="";
$reporte="";



$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='reportedetallado.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta= $row['ruta'];
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}


	$patron = "";
	if($filtro<>'')
	{
		$patron = " and CONCAT(nombre, ' ',nombre2, ' ', apaterno, ' ', amaterno ) like '%$filtro%' ";
	}

	//$sql = "select d.idduenio as idd, nombre, nombre2, apaterno, amaterno, count(idcontrato) from duenio d, duenioinmueble di, inmueble i, contrato c where d.idduenio = di.idduenio and di.idinmueble = i.idinmueble and i.idinmueble = c.idinmueble and c.activo = true group by d.idduenio, nombre, nombre2, apaterno, amaterno";
	//$sql = "select d.idduenio as idd, nombre, nombre2, apaterno, amaterno, count(idcontrato) from duenio d, duenioinmueble di, inmueble i, contrato c where d.idduenio = di.idduenio and di.idinmueble = i.idinmueble and i.idinmueble = c.idinmueble  group by d.idduenio, nombre, nombre2, apaterno, amaterno";
	$sql = "select d.idduenio as idd, nombre, nombre2, apaterno, amaterno from duenio d, duenioinmueble di, inmueble i where d.idduenio = di.idduenio and di.idinmueble = i.idinmueble $patron and d.activo=1  group by d.idduenio, nombre, nombre2, apaterno, amaterno";
	$periodo = date("Y-m-d");	
	$operacion = mysql_query($sql);
	$reporte0 = "<table border='1'><tr><th>id</th><th>Due&ntilde;o</th><th>Accion</th></tr>";
	while($row = mysql_fetch_array($operacion))	
	{
		$sqlc0 = "select count(idduenio) as tot from edoduenio where idduenio = " . $row["idd"] .  " and isnull(fechagen)=true and tocado = true ";
		$operacionc0 = mysql_query($sqlc0);
		$row0 = mysql_fetch_array($operacionc0);
		$color="";		
		if($row0["tot"]>0)
		{
			
			$color = " bgcolor='#0000FF' ";	
		}

		$controles ="<td><input type=\"button\" value=\"Ver\" onClick = \"cargarSeccion('$ruta/edoduenioseleccion.php','contenido', 'id=" . $row["idd"] . "&filtro=1&periodo=$periodo');\"></td>";

		
		$reporte0 .= "<tr $color><td>" . $row["idd"] .  "</td><td>" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] .  "</td>$controles</tr>";
		
	}
	
	$reporte0 .="</table>";	
	



	echo CambiaAcentosaHTML($reporte0);

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>