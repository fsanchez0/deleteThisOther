<?php
include_once('../general/conexion.php');

$fecha=date('Y') . "-" . date('m') . "-" . date('d');
$nuevafecha = strtotime ( '-60 day' , strtotime ( $fecha ) ) ;
$hoy = date ( 'Y-m-d' , $nuevafecha );
	$sql= "SELECT idhistoria, contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as inqtel,tipocobro, fechagenerado , historia.fechanaturalpago, historia.cantidad, aplicado, historia.interes, historia.iva as ivah, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, calle, numeroext, numeroint, inmueble.colonia, delmun, estado, pais, inmueble.cp, inmueble.tel as itel, inquilino.email as emaili, fiador.email as emailf,observaciones FROM contrato, cobros, inquilino,tipocobro, historia, fiador, inmueble, estado, pais  WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and historia.aplicado=false and contrato.idfiador=fiador.idfiador and contrato.idinmueble = inmueble.idinmueble and litigio=false and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais and historia.fechanaturalpago <= '$hoy' order by inquilino.idinquilino, historia.idcontrato, fechanaturalpago"; //, historia.idhistoria";
	
	$operacion = mysql_query($sql);
	$ccontrato=0;
	$grentas=0;
	$gotros=0;
	$ginteres=0;
	$rentas=0;
	$otros=0;
	$interes=0;
	
	$tn=0;
	while($row = mysql_fetch_array($operacion))
	{

		if($ccontrato!=$row["elidcontrato"])
		{
		
			$masivo .=$row["elidcontrato"] ."|";
			if($ccontrato!=0)
			{

				
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
			
			
						
			

			
			//$excel="mostrarexcel('excelenvio',event);cargarSeccion('$ruta/descargarexcel.php','excelenvio','contrato=" . $row["elidcontrato"] . "' )";
			
			//$excel="";
			//$excel="mostrarrdivexcel('excelenvio',event);cargarSeccion('$ruta/masivoexcel.php','excelenvio','contrato=" . $row["elidcontrato"] . "' )";
			
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
	
	}
	$html =  "<center><table><tr><th>G. T. Renta</th><th>&nbsp;</td><th>G .T. Mantenimiento</th><th>&nbsp;</th><th>G. T. Inter&eacute;s</th><th>&nbsp;</th><th>Gran Total</th></tr>";
	$html .=  "<tr><td align=\"center\">$ " . number_format($grentas,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($gotros,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($ginteres,2) . "</td><td align=\"center\">=</td><td align=\"center\"><strong>$ " . number_format(($grentas+$gotros+$ginteres),2) . "</strong></td></tr></table></center>";
	echo $html;
?>