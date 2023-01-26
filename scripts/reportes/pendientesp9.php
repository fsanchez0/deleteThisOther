<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo

$id=@$_GET["id"];



$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='pendientesp.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta = $row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

	if ($priv[2]=='1')
	{
		if($id)
		{
			$sql= "update contrato set litigio=true where idcontrato = $id";	
			$operacion = mysql_query($sql);
		}
		
	}

	$hoy=date('Y') . "-" . date('m') . "-" . date('d');
	$sql= "SELECT idhistoria, contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as inqtel,tipocobro, fechagenerado , historia.fechanaturalpago, historia.cantidad, aplicado, historia.interes, historia.iva as ivah, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, calle, numeroext, numeroint, inmueble.colonia, delmun, estado, pais, inmueble.cp, inmueble.tel as itel, inquilino.email as emaili, fiador.email as emailf FROM contrato, cobros, inquilino,tipocobro, historia, fiador, inmueble, estado, pais  WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and historia.aplicado=false and contrato.idfiador=fiador.idfiador and contrato.idinmueble = inmueble.idinmueble and litigio=false and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais and historia.fechanaturalpago <= '$hoy' order by inquilino.idinquilino, historia.idcontrato, fechanaturalpago";  //, historia.idhistoria";
	
	$html = "<input type=\"button\" value=\"Imprimir\" onClick=\"imprimirv('imprimirdiv') \"><div id=\"imprimirdiv\">";
	
	$html .= "<h1>Pendientes de Inmuebles por cobrar</h1>\n";

	$operacion = mysql_query($sql);
	$ccontrato=0;
	$grentas=0;
	$gotros=0;
	$ginteres=0;
	$rentas=0;
	$otros=0;
	$interes=0;
	$html .= "<table border=\"0\">";
	$tn=0;
	while($row = mysql_fetch_array($operacion))
	{

		if($ccontrato!=$row["elidcontrato"])
		{
			if($ccontrato!=0)
			{

				$html .=  "<tr><td colspan=\"5\" align=\"right\">";
				$html .=  "<table><tr><th>T. Renta</th><th>&nbsp;</td><th>T. Mantenimiento</th><th>&nbsp;</th><th>T. Inter&eacute;s</th><th>&nbsp;</th><th>Total</th></tr>";
				$html .=  "<tr><td align=\"center\">$ " . number_format($rentas,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($otros,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($interes,2) . "</td><td align=\"center\">=</td><td align=\"center\"><strong>$ " . number_format(($rentas+$otros+$interes),2) . "</strong></td></tr></table>";
				$html .=  "</td></tr>";

				$html .=  "</table></td></tr>";
				//pies de datos de las sumas


				//reinicio de las sumas
				//$grentas +=$rentas;
				//$gotros +=$otros;
				//$ginteres +=$interes;
				$rentas=0;
				$otros=0;
				$interes=0;
				$tn++;

				//Saltos de lineas
				//echo "<br><br><br><br>";
			}
			$accionlitigio="";
			if ($priv[2]=='1')
			{
				//$pendiente="window.open( 'scripts/pendientec.php?contrato=" . $row["elidcontrato"] . "');";
				$accionlitigio="<input type =\"button\" value=\"A litigio\" onClick=\"cargarSeccion('$dirscript','contenido','id=" .  $row["elidcontrato"]  . "' )\"  />";
			}
			
			
			$pendiente="window.open( '$ruta/pendientec.php?contrato=" . $row["elidcontrato"] . "');";
			$accionboton="<input type =\"button\" value=\"Ver\" onClick=\"$pendiente\"  />";
			$Cabeceratabla="<tr><th>Contrato: </th><td colspan=\"3\">" . $row["elidcontrato"]  . " $accionboton&nbsp;&nbsp;$accionlitigio</td></tr>";
			$Cabeceratabla .="<tr><th>Inquilino: </th><td colspan=\"3\">" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "(Tel. " . $row["inqtel"] . ", correo electr&oacute;nico ". $row["emaili"]  . ")</td></tr>";
			$Cabeceratabla .="<tr><th>Inmueble: </th><td colspan=\"3\">" .   $row["calle"] . " No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"] . " Deleg/Mun. ". $row["delmun"] . " C.P. " . $row["cp"]  . " Tel. " . $row["itel"] . " </td></tr>";
			$Cabeceratabla .="<tr><th>Obligado Solidario: </th><td colspan=\"2\">" . $row["fnombre"] . " " . $row["fnombre2"] . " " . $row["fapaterno"] . " " . $row["famaterno"]  . " (Tel. " . $row["ftel"] . ", email " . $row["emailf"] . ")</td></tr>";
			//echo "\n<tr><td><br><table border=\"1\" width=\"100%\">$Cabeceratabla<tr><th>Contrato</th><th>Nombre</th><th>Fecha nat. pago</th><th>Concepto</th><th>Cantidad</th></tr>\n";
			$html .=  "\n<tr><td><br><table border=\"1\" width=\"100%\" id=\"tbl_$tn\">$Cabeceratabla<tr><th>Fecha nat. pago</th><th>Concepto</th><th>Cantidad</th><th>Acci&oacute;n</th></tr>\n";
			$ccontrato=$row["elidcontrato"];
		}


		if (is_null($row["interes"])==false and $row["interes"]==1)
		{

			$concepto = "INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . "(" . $row["tipocobro"] . ")";
			$interes += $row["cantidad"] + $row["ivah"];
			$ginteres +=$row["cantidad"] + $row["ivah"];
			$Pagado=$row["cantidad"]  + $row["ivah"];

		}
		else
		{
			$concepto = $row["tipocobro"];
			if(strtoupper($row["tipocobro"])=="RENTA")
			{
			
				if ($row["aplicado"]==false )
				{
					$rentas +=($row["cantidad"] + $row["ivah"]);
					$grentas +=($row["cantidad"] + $row["ivah"]);
					$Pagado=($row["cantidad"] + $row["ivah"]);
			
				}
				else
				{
					$rentas +=$row["cantidad"] ;
					$grentas +=$row["cantidad"] ;
					$Pagado=$row["cantidad"] ;
				}

			
			
				//$rentas +=$row["cantidad"] + $row["ivah"];

			}
			else
			{
				if ($row["aplicado"]==false )
				{
					$otros +=($row["cantidad"] + $row["ivah"]);
					$gotros +=($row["cantidad"] + $row["ivah"]);
					$Pagado=($row["cantidad"] + $row["ivah"]);
			
				}
				else
				{
					$otros +=$row["cantidad"] ;
					$gotros +=$row["cantidad"] ;
					$Pagado=$row["cantidad"] ;
				}			
			
			
				//$otros +=$row["cantidad"] + $row["ivah"];
			}


		}


		//echo "<tr><td>" . $row["elidcontrato"] . "</td><td>" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " .$row["amaterno"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>$concepto</td><td align=\"right\">" . ($row["cantidad"] + $row["iva"]) . "</td></tr>\n";
		$idhist = $row["idhistoria"];
		$accionboton="<input type =\"button\" value=\"Condonar\" onClick=\"Condonar ('tbl_$tn',this,$idhist,'scripts/inmuebles');\"  />";
		$html .=  "<tr><td>" . $row["fechanaturalpago"] . "</td><td>$concepto</td><td align=\"right\">$ " . number_format($Pagado,2) . "</td><td align=\"center\">$accionboton</td></tr>\n";

	}
	$html .=  "<tr><td colspan=\"5\" align=\"right\">";
	$html .=  "<table><tr><th>T. Renta</th><th>&nbsp;</td><th>T. Mantenimiento</th><th>&nbsp;</th><th>T. Inter&eacute;s</th><th>&nbsp;</th><th>Total</th></tr>";
	$html .=  "<tr><td align=\"center\">$ " . number_format($rentas,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($otros,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($interes,2) . "</td><td align=\"center\">=</td><td align=\"center\"><strong>$ " . number_format(($rentas+$otros+$interes),2) . "</strong></td></tr></table>";
	$html .=  "</td></tr>";
	$html .=  "</table></td></tr>";
	$html .=  "<tr><td align=\"right\"><br>";
	$html .=  "<table><tr><th>G. T. Renta</th><th>&nbsp;</td><th>G .T. Mantenimiento</th><th>&nbsp;</th><th>G. T. Inter&eacute;s</th><th>&nbsp;</th><th>Gran Total</th></tr>";
	$html .=  "<tr><td align=\"center\">$ " . number_format($grentas,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($gotros,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($ginteres,2) . "</td><td align=\"center\">=</td><td align=\"center\"><strong>$ " . number_format(($grentas+$gotros+$ginteres),2) . "</strong></td></tr></table>";
	$html .=  "</td></tr>";
	$html .=  "</table></div>";
	echo CambiaAcentosaHTML($html);



}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>