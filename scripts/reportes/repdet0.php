<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


//Modulo

$filtro=@$_GET["filtro"];
$agrupado=@$_GET["agrupado"];
$periodo=@$_GET["periodo"];
$fechai=@$_GET["fechai"];
$fechaf=@$_GET["fechaf"];
$titulo="";
$reporte="";



$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='repdet.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

	//echo "Filtro=$filtro   Agrupado=$agrupado    Periodo=$periodo    Fechai=$fechai  and  Fechaf=$fechaf";

	$sql = "select idhistoria, fechapago,fechagenerado, fechanaturalpago, fechavencimiento,tipocobro, historia.cantidad as cantidadh, historia.iva as ivah, historia.interes as inth, nombre, nombre2, apaterno, amaterno, calle, numeroint, numeroext, contrato.idcontrato as idcont from historia,contrato, inquilino, inmueble, cobros, tipocobro where historia.idcobros = cobros.idcobros and historia.idcontrato= contrato.idcontrato and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble = inmueble.idinmueble and cobros.idtipocobro = tipocobro.idtipocobro ";

	if($fechai && $fechaf)
	{

		$titulo = " entre las fechas $fechai y $fechaf ";

		if($filtro=="1")
		{
			//echo "Pagados ";
			$sql .= " and historia.aplicado=true and fechapago between '" . $fechai . "' and '" . $fechaf . "' order by fechapago, idhistoria";

			$titulo = "Pagados "  . $titulo;
		}
		else
		{
			//echo "Por pagar";
			$sql .= " and historia.aplicado=false and fechavencimiento between '" . $fechai . "' and '" . $fechaf . "' order by fechavencimiento, idhistoria ";

			$titulo = "Por cobrar " . $titulo;
		}
		//echo $sql;
		if($agrupado=="2")
		{
			//echo "agrupar";
			$titulo .= " agrupado por ";
			if($periodo=="1")
			{
				//echo "agrupo por dia";
				$titulo .= " d&iacute;a.";
				$reporte="";
				$operacion = mysql_query($sql);
				$primer="";
				$sumap=0;
				$sumag=0;
				while($row = mysql_fetch_array($operacion))
				{

					if ($filtro=="1")
					{

						if($primer!=$row["fechapago"])
						{
							if ($primer=="")
							{
								$primer=$row["fechapago"];
								$reporte .="<b>Fecha $primer </b>\n";
								$reporte .="<table border=\"1\" width=\"100%\">\n";
								$reporte .="<tr><th>Contrato</th><th>Inquilino</th><th>Direcci&oacute;n</th><th>Fecha P.</th><th>Conepto</th><th>Cantidad</th></tr>\n";

							}
							else
							{

								$reporte .= "</table>\n";
								$reporte .= "<p align=\"rigth\">Total:$ " . number_format($sumap,2) . " </p><br><br>\n";
								$sumap=0;
								$primer=$row["fechapago"];
								$reporte .="<b>Fecha $primer </b>\n";
								$reporte .="<table border=\"1\" width=\"100%\">";
								$reporte .="<tr><th>Contrato</th><th>Inquilino</th><th>Direcci&oacute;n</th><th>Fecha P.</th><th>Conepto</th><th>Cantidad</th></tr>";
							}

						}

						$concepto = $row["tipocobro"];

						if ($row["inth"]==1)
						{

							$concepto = $row["tipocobro"] . "(INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . ")";

						}
												
						$reporte .= "<tr><td>" . $row["idcont"] . "</td><td>" . $row["nombre"] . " "  . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]  . "</td><td>" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td align=\"right\">$ " . number_format(($row["cantidadh"] ),2) . "</td></tr>";
						$sumap += ($row["cantidadh"] );
						$sumag += ($row["cantidadh"] );
/*						
						$reporte .= "<tr><td>" . $row["idcont"] . "</td><td>" . $row["nombre"] . " "  . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]  . "</td><td>" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td align=\"right\">$ " . number_format(($row["cantidadh"] + $row["ivah"]),2) . "</td></tr>";
						$sumap += ($row["cantidadh"] + $row["ivah"]);
						$sumag += ($row["cantidadh"] + $row["ivah"]);
*/
					}
					else
					{

						if($primer!=$row["fechavencimiento"])
						{
							if ($primer=="")
							{
								$primer=$row["fechavencimiento"];
								$reporte .="<b>Fecha $primer </b>\n";
								$reporte .="<table border=\"1\" width=\"100%\">\n";
								$reporte .="<tr><th>Contrato</th><th>Inquilino</th><th>Direcci&oacute;n</th><th>Fecha P.</th><th>Conepto</th><th>Cantidad</th></tr>\n";

							}
							else
							{

								$reporte .= "</table>\n";
								$reporte .= "<p align=\"rigth\">Total:$ " . number_format($sumap,2) . " </p><br><br>\n";
								$sumap=0;
								$primer=$row["fechavencimiento"];
								$reporte .="<b>Fecha $primer </b>\n";
								$reporte .="<table border=\"1\" width=\"100%\">";
								$reporte .="<tr><th>Contrato</th><th>Inquilino</th><th>Direcci&oacute;n</th><th>Fecha P.</th><th>Conepto</th><th>Cantidad</th></tr>";
							}

						}

						$concepto = $row["tipocobro"];

						if ($row["inth"]==1)
						{

							$concepto = $row["tipocobro"] . "(INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . ")";

						}
						$reporte .= "<tr><td>" . $row["idcont"] . "</td><td>" . $row["nombre"] . " "  . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]  . "</td><td>" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td align=\"right\">$ " . number_format(($row["cantidadh"] + $row["ivah"]) ,2) . "</td></tr>";
						$sumap += ($row["cantidadh"] + $row["ivah"]);
						$sumag += ($row["cantidadh"] + $row["ivah"]);
					}






				}
				$reporte .= "</table>\n";
				$reporte .= "<p align=\"rigth\">Total:$ " . number_format($sumap,2) . " </p><br><br>\n";
				$reporte .= "<p align=\"rigth\">Gran Total:$ " . number_format($sumag,2) . " </p>\n";


			}
			else
			{


				//echo "Por mes";
				$titulo .= " mes.";
				$reporte="";
				$operacion = mysql_query($sql);
				$primer="";
				$sumap=0;
				$sumag=0;
				while($row = mysql_fetch_array($operacion))
				{

					if ($filtro=="1")
					{

						if($primer!=substr($row["fechapago"],5,2))
						{
							if ($primer=="")
							{
								$primer=substr($row["fechapago"],5,2);
								$reporte .="<b>Mes $primer -" . substr($row["fechapago"],0,4) .  " </b>\n";
								$reporte .="<table border=\"1\" width=\"100%\">\n";
								$reporte .="<tr><th>Contrato</th><th>Inquilino</th><th>Direcci&oacute;n</th><th>D&iacute;a</th><th>Fecha P.</th><th>Conepto</th><th>Cantidad</td></tr>\n";

							}
							else
							{

								$reporte .= "</table>\n";
								$reporte .= "<p align=\"rigth\">Total:$ " . number_format($sumap,2) . "  </p><br><br>\n";
								$sumap=0;
								$primer=substr($row["fechapago"],5,2);
								$reporte .="<b>Mes $primer -" . substr($row["fechapago"],0,4) .  " </b>\n";
								$reporte .="<table border=\"1\" width=\"100%\">";
								$reporte .="<tr><th>Contrato</th><th>Inquilino</th><th>Direcci&oacute;n</th><th>D&iacute;a</th><th>Fecha P.</th><th>Conepto</th><th>Cantidad</td></tr>";
							}

						}

						$concepto = $row["tipocobro"];

						if ($row["inth"]==1)
						{

							$concepto = $row["tipocobro"] . "(INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . ")";

						}
						
						$reporte .= "<tr><td>" . $row["idcont"] . "</td><td>" . $row["nombre"] . " "  . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]  . "</td><td>" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"] . "</td><td>" . substr($row["fechapago"],8,2) . "</td><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td align=\"right\">$ " . number_format(($row["cantidadh"] ) ,2) . "</td></tr>";
						$sumap += ($row["cantidadh"] );
						$sumag += ($row["cantidadh"] );						

/*						
						$reporte .= "<tr><td>" . $row["idcont"] . "</td><td>" . $row["nombre"] . " "  . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]  . "</td><td>" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"] . "</td><td>" . substr($row["fechapago"],8,2) . "</td><td>" . $concepto . "</td><td align=\"right\">$ " . number_format(($row["cantidadh"] + $row["ivah"]) ,2) . "</td></tr>";
						$sumap += ($row["cantidadh"] + $row["ivah"]);
						$sumag += ($row["cantidadh"] + $row["ivah"]);
*/
					}
					else
					{

						if($primer!=substr($row["fechavencimiento"],5,2))
						{
							if ($primer=="")
							{
								$primer=substr($row["fechavencimiento"],5,2);
								$reporte .="<b>Mes $primer -" . substr($row["fechavencimiento"],0,4) .  " </b>\n";
								$reporte .="<table border=\"1\" width=\"100%\">\n";
								$reporte .="<tr><th>Contrato</th><th>Inquilino</th><th>Direcci&oacute;n</th><th>D&iacute;a</th><th>Fecha P.</th><th>Conepto</th><th>Cantidad</td></tr>\n";

							}
							else
							{

								$reporte .= "</table>\n";
								$reporte .= "<p align=\"rigth\">Total:$ " . number_format($sumap,2) . " </p><br><br>\n";
								$sumap=0;
								$primer=substr($row["fechavencimiento"],5,2);
								$reporte .="<b>Mes $primer -" . substr($row["fechavencimiento"],0,4) .  " </b>\n";
								$reporte .="<table border=\"1\" width=\"100%\">";
								$reporte .="<tr><th>Contrato</th><th>Inquilino</th><th>Direcci&oacute;n</th><th>D&iacute;a</th><th>Fecha P.</th><th>Conepto</th><th>Cantidad</td></tr>\n";
							}

						}

						$concepto = $row["tipocobro"];

						if ($row["inth"]==1)
						{

							$concepto = $row["tipocobro"] . "(INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . ")";

						}
						$reporte .= "<tr><td>" . $row["idcont"] . "</td><td>" . $row["nombre"] . " "  . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]  . "</td><td>" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"] . "</td><td>" . substr($row["fechavencimiento"],8,2) . "</td><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td align=\"right\">$ " . number_format(($row["cantidadh"] + $row["ivah"]) ,2) . "</td></tr>";
						$sumap += ($row["cantidadh"] + $row["ivah"]);
						$sumag += ($row["cantidadh"] + $row["ivah"]);
					}






				}
				$reporte .= "</table>\n";
				$reporte .= "<p align=\"rigth\">Total:$ " . number_format($sumap,2) . " </p><br><br>\n";
				$reporte .= "<p align=\"rigth\">Gran Total:$ " . number_format($sumag,2) . " </p>\n";



			}

		}
		else
		{
			//echo "Sin Agrupar";
			$titulo .= " sin agrupar.";

			$reporte="";
			//echo $sql;
			$operacion = mysql_query($sql);
			$primer="";
			$sumap=0;
			$sumag=0;
			$reporte .="<b>Intervalo $fechai a $fechaf </b>\n";
			$reporte .="<table border=\"1\" width=\"100%\">\n";
			$reporte .="<tr><th>Contrato</th><th>Inquilino</th><th>Direcci&oacute;n</th><th>Fecha Pagado</th><th>Fecha Pago</th><th>Conepto</th><th>Cantidad</th></tr>\n";
			while($row = mysql_fetch_array($operacion))
			{
				if ($filtro=="1")   //pagados
				{


					$concepto = $row["tipocobro"];

					if ($row["inth"]==1)
					{

						$concepto = $row["tipocobro"] . "(INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . ")";

					}
					$reporte .= "<tr><td>" . $row["idcont"] . "</td><td>" . $row["nombre"] . " "  . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]  . "</td><td>" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"] . "</td><td>" . $row["fechapago"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td align=\"right\">$ " . number_format(($row["cantidadh"] ),2) . "</td></tr>";					
					$sumag += ($row["cantidadh"]);
/*					
					$reporte .= "<tr><td>" . $row["idcont"] . "</td><td>" . $row["nombre"] . " "  . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]  . "</td><td>" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"] . "</td><td>" . $row["fechapago"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td align=\"right\">$ " . number_format(($row["cantidadh"] + $row["ivah"]),2) . "</td></tr>";					
					$sumag += ($row["cantidadh"] + $row["iva"]);
*/
				}
				else
				{


					$concepto = $row["tipocobro"];

					if ($row["inth"]==1)
					{

						$concepto = $row["tipocobro"] . "(INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . ")";

					}
					$reporte .= "<tr><td>" . $row["idcont"] . "</td><td>" . $row["nombre"] . " "  . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]  . "</td><td>" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"] . "</td><td>" . $row["fechavencimiento"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td align=\"right\">$ " . number_format(($row["cantidadh"] + $row["ivah"]),2) . "</td></tr>";
					$sumag += ($row["cantidadh"] + $row["ivah"]);
				}
			}
			$reporte .= "</table>\n";
			$reporte .= "<p align=\"rigth\">Gran Total:$ " . number_format($sumag,2) . " </p>\n";

		}




	}




$html = <<<formulario

<center>
<h1>Reporte por fechas para Inmuebles</h1>
<form >
<table border="1">
<tr>
	<td>Filtro por: </td>
	<td align="center"><input type="radio" value="1" name="filtro" checked onClick ="filtroe.value=this.value;">Pagados&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="2" name="filtro" onClick ="filtroe.value=this.value;"> Por Cobrar <input type='hidden' name='filtroe' value="1" ></td>
</tr>
<tr>
	<td valign="top">Agrupar</td>
	<td>
		<table border="1" width="100%">
		<tr>
			<td align="center"><input type="radio" value="1" name="agrupado" checked onClick="document.getElementById('periodo1').disabled=true;document.getElementById('periodo2').disabled=true;agrupadoe.value=this.value;">Sin agrupar&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="2" name="agrupado" onClick="document.getElementById('periodo1').disabled=false;document.getElementById('periodo2').disabled=false;agrupadoe.value=this.value;">Agrupado Por:<input type='hidden' name='agrupadoe' value="1" ></td>
		</tr>
		<tr>
			<td align="center"><input type="radio" value="1" name="periodo" checked id="periodo1" disabled onClick ="periodoe.value=this.value;">D&iacute;a&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="2" name="periodo" id="periodo2" disabled onClick ="periodoe.value=this.value;">Mes<input type='hidden' name='periodoe' value="1" ></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>Fecha Inicial</td><td> <input type="text" name="fechai" >(aaaa-mm-dd)</td>
</tr>
<tr>
	<td>Fecha Final</td><td> <input type="text" name ="fechaf">(aaaa-mm-dd)</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="button" value="Limpiar" onClick="fechai.value='';fechaf.value='';">
		<input type="button" value="Generar" onClick="cargarSeccion('$dirscript','contenido','filtro=' + filtroe.value + '&agrupado=' + agrupadoe.value + '&periodo=' + periodoe.value + '&fechai=' + fechai.value + '&fechaf='+ fechaf.value)">
	</td>
</tr>
</table>
</form>
<input type="button" value="Imprmir reporte" onClick="imprimir('reportediv');">
<div class="scroll" id="reportediv">
<h2>$titulo</h2>
$reporte
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