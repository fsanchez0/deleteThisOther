<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$id=@$_GET["asunto"];



$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

/*	
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='pendientesasu.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}
*/
$html = <<<cavecera
<html>
<head>
	<title>Grupo Bujalil S.C.</title>
<head>
<link rel="stylesheet" type="text/css" href="../../estilos/estilos.css">
</HEAD>
<BODY>
cavecera;
//	$hoy=date('Y') . "-" . date('m') . "-" . date('d');
	$sql= "SELECT nombre,apaterno, amaterno, telparticular, teloficina,telmovil,telotros,abogado, expediente,asunto.descripcion as asudesc,asunto.idasunto as idasu, tipocargo,estadocuenta.descripcion as edodesc, fecha, cantidad from tipocargo, estadocuenta, asunto, directorio where directorio.iddirectorio = asunto.iddirectorio and asunto.idasunto = estadocuenta.idasunto and estadocuenta.idtipocargo = tipocargo.idtipocargo and pagado = false and asunto.idasunto=$id order by asunto.idasunto, fecha";
	$html .= "<h1>Asuntos por cobrar</h1>\n";

	$operacion = mysql_query($sql);
	$ccontrato=0;
	$grentas=0;
	$gotros=0;
	$ginteres=0;
	$rentas=0;
	$otros=0;
	$interes=0;
	$html .= "<table border=\"0\">";
	while($row = mysql_fetch_array($operacion))
	{

		if($ccontrato!=$row["idasu"])
		{
			if($ccontrato!=0)
			{

				$html .= "<tr><td colspan=\"5\" align=\"right\">";
				$html .= "<table><tr><th align=\"rigth\">Total</th></tr>";
				$html .= "<tr><td align=\"rigth\"><strong>$ " . number_format($rentas,2) . "</strong></td></tr></table>";
				$html .= "</td></tr>";

				$html .= "</table></td></tr>";
				//pies de datos de las sumas


				//reinicio de las sumas				
				$rentas=0;


				//Saltos de lineas
				//echo "<br><br><br><br>";
			}

			
			
			//$pendiente="window.open( 'scripts/pendientec.php?contrato=" . $row["elidcontrato"] . "');";
			//$accionboton="<input type =\"button\" value=\"Ver\" onClick=\"$pendiente\"  />";
			$Cabeceratabla="<tr><th>Asunto: </th><td colspan=\"3\" >" . $row["idasu"]  . " </td></tr>";
			$Cabeceratabla .="<tr><th>Expediente: </th><td colspan=\"3\">" . $row["expediente"]  . " </td></tr>";
			$Cabeceratabla .="<tr><th>Abogado: </th><td colspan=\"3\">" . $row["abogado"]  . " </td></tr>";
			$Cabeceratabla .="<tr><th>Cliente: </th><td colspan=\"3\">" . $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"] . " (Tel. part. " . $row["telparticular"] . " Tel.Ofic. " . $row["teloficina"] . " Tel.Movil. " . $row["telmovil"] . " Otros tel. " . $row["telotros"] .")</td></tr>";
			$Cabeceratabla .="<tr><th>Descripci&oacute;n: </th><td colspan=\"3\" >" .   $row["asudesc"] . " </td></tr>";
					
			$html .= "\n<tr><td><br><table border=\"1\" width=\"100%\">$Cabeceratabla<tr><th>Fecha cargo</th><th>Tipo Cargo</th><th>Descripci&oacute;n</th><th>Cantidad</th></tr>\n";
			$ccontrato=$row["idasu"];
		}


		
		$rentas +=$row["cantidad"];
		$grentas +=$row["cantidad"];
		$Pagado=$row["cantidad"];
		//echo "<tr><td>" . $row["elidcontrato"] . "</td><td>" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " .$row["amaterno"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>$concepto</td><td align=\"right\">" . ($row["cantidad"] + $row["iva"]) . "</td></tr>\n";
		$html .= "<tr><td>" . $row["fecha"] . "</td><td>" . $row["tipocargo"] . "</td><td>" . $row["edodesc"] . "</td><td align=\"right\">$ " . number_format($Pagado,2) . "</td></tr>\n";

	}
	
	$html .= "<tr><td colspan=\"5\" align=\"right\">";
	$html .= "<table><tr><th align=\"rigth\">Total</th></tr>";
	$html .= "<tr><td align=\"rigth\"><strong>$ " . number_format($rentas,2) . "</strong></td></tr></table>";
	$html .= "</td></tr>";
/*
	echo "</table></td></tr>";
	echo "<tr><td align=\"right\"><br>";
	echo "<table><tr><th align=\"rigth\">Gran Total</th></tr>";
	echo "<tr><td align=\"rigth\"><strong>$ " . number_format($grentas,2) . "</strong></td></tr></table>";
	echo "</td></tr>";
	echo "</table>";
	*/
	echo CambiaAcentosaHTML($html);
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>