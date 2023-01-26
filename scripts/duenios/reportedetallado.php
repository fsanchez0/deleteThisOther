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



/*
	$sql = "select * from edoduenio e, inmueble i where e.idinmueble = i.idinmueble ";
	
	
	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		
	echo $sql .= $miwhere . " order by idduenio, idcontrato,idedoduenio";
	
	
	$operacion = mysql_query($sql);

	$controlesjava="";
	$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	$reporte="";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idcontrato"] != $idcontrato)
		{
			if($idcontrato !="")
			{
				$reporte .= "</table><br><br>";
			}
			$idcontrato = $row["idcontrato"];
			//$reporte .= "</table><br><br>";
			$reporte .= "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	
		}
	
		$suma = $row["importe"] + $row["iva"];
		$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		
		$controles ="<td><input type=\"text\" name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\"  value=\"" . $row["notaedo"]  . "\" ></td><td><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\"></td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".value + '";
	
		$reporte .= "<tr><td>" . $row["idcontrato"] .  "</td><td>$inmueble</td><td>" . $row["importe"] .  "</td><td>" . $row["ivad"] .  "</td><td>$suma</td>$controles</tr>";
		
	}
	
	$reporte .="</table>";
	
	$reporte .= "<input type=\"button\" value=\"Aplicar\" onClick = \"cargarSeccion('$dirscript','contenido', 'paso=3&idcontrato=$idcontrato&efectivo='+efect + '&idmetodopago= $idmetodopago&cambio=' + cambio.value + '$controlesjava');this.disabled =true\"> $reporte";
	
	
*/

	//$sql = "select d.idduenio as idd, nombre, nombre2, apaterno, amaterno, count(idcontrato) from duenio d, duenioinmueble di, inmueble i, contrato c where d.idduenio = di.idduenio and di.idinmueble = i.idinmueble and i.idinmueble = c.idinmueble and c.activo = true group by d.idduenio, nombre, nombre2, apaterno, amaterno";
	//$sql = "select d.idduenio as idd, nombre, nombre2, apaterno, amaterno, count(idcontrato) from duenio d, duenioinmueble di, inmueble i, contrato c where d.idduenio = di.idduenio and di.idinmueble = i.idinmueble and i.idinmueble = c.idinmueble  group by d.idduenio, nombre, nombre2, apaterno, amaterno";
	$sql = "select d.idduenio as idd, nombre, nombre2, apaterno, amaterno from duenio d, duenioinmueble di, inmueble i where d.idduenio = di.idduenio and di.idinmueble = i.idinmueble and d.activo=1   group by d.idduenio, nombre, nombre2, apaterno, amaterno";
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
	

/*
<form >
<!--
<table border="1">
<tr>
	<td valign="top">Filtro</td>
	<td>
		<input type="radio" value="1" name="filtro" checked onClick ="filtroe.value=this.value;">Anteriores &nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" value="2" name="filtro" onClick ="filtroe.value=this.value;" checked> Pendientes 
		<input type='hidden' name='filtroe' value="1" >
		
	</td>
</tr>

</table>
-->
</form>
*/


$html = <<<formulario

<center>
<h1>Reporte detallado due&ntilde;os</h1>
<form>
Due&ntilde;o <input type='text' name='elduenio' onKeyUp="cargarSeccion('$ruta/ldetalladafiltro.php', 'reportediv', 'filtro=' + this.value)">
<div class="scroll" id="reportediv">
$reporte0
</div>

</center>

formulario;
	echo CambiaAcentosaHTML($html);

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>