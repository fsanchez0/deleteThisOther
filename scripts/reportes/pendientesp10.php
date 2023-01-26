<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo

$id=@$_GET["id"];

$cverde = "contrato";
$cazul = "contrato2";
$fverde ="fila1"; //clase para fondo verde
$fazul = "fila4"; //clase para fondo azul
$gris = "fila3";  //clase para entre renglones
$blanco ="fila2";
$masivo = "";//para enviar todos los id

$ccontrato1 = $cazul;
$cfondo=$fverde; //variable para el cambio de fondo azul o verde
$filad=$gris;

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
	$sql= "SELECT idhistoria, contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as inqtel,tipocobro, fechagenerado , historia.fechanaturalpago, historia.cantidad, aplicado, historia.interes, historia.iva as ivah, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, calle, numeroext, numeroint, inmueble.colonia, delmun, estado, pais, inmueble.cp, inmueble.tel as itel, inquilino.email as emaili, fiador.email as emailf,observaciones FROM contrato, cobros, inquilino,tipocobro, historia, fiador, inmueble, estado, pais  WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and historia.aplicado=false and contrato.idfiador=fiador.idfiador and contrato.idinmueble = inmueble.idinmueble and litigio=false and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais and historia.fechanaturalpago <= '$hoy' order by inquilino.idinquilino, historia.idcontrato, fechanaturalpago";  //, historia.idhistoria";
	
	$html = "<input type=\"button\" value=\"Imprimir\" onClick=\"imprimirv('imprimirdiv') \"><div id=\"imprimirdiv\">";
	
	$html .= "<div id=\"content\">  <div id=\"head\"><img src=\"imagenes/head.jpg\" width=\"612\" height=\"134\" alt=\"Padilla Bujalil. Pendientes por pagar\" /><div id=\"tablas\"><h1>Pendientes de Inmuebles por cobrar </h1>\n";

	$operacion = mysql_query($sql);
	$ccontrato=0;
	$grentas=0;
	$gotros=0;
	$ginteres=0;
	$rentas=0;
	$otros=0;
	$interes=0;
	$html .= "<table border=\"0\" >";
	$tn=0;
	while($row = mysql_fetch_array($operacion))
	{

		if($ccontrato!=$row["elidcontrato"])
		{
		
			$masivo .=$row["elidcontrato"] ."|";
			if($ccontrato!=0)
			{

				$html .=  "<tr><td colspan=\"5\" align=\"right\">";
				$html .=  "<table><tr><th>T. Renta</th><th>&nbsp;</td><th>T. Mantenimiento</th><th>&nbsp;</th><th>T. Inter&eacute;s</th><th>&nbsp;</th><th>Total</th></tr>";
				$html .=  "<tr><td align=\"center\">$ " . number_format($rentas,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($otros,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($interes,2) . "</td><td align=\"center\">=</td><td align=\"center\"><strong>$ " . number_format(($rentas+$otros+$interes),2) . "</strong></td></tr></table>";
				$html .=  "</td></tr>";

				$html .=  "<tr><td colspan=\"4\"><img src=\"imagenes/notas.gif\" width=\"590\" height=\"99\" alt=\"Notas\" /></td>                 </tr></table></td></tr>";
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
			
			
			if($ccontrato1 == $cazul)
			{
				$ccontrato1= $cverde;
				$cfondo= $fverde;
			}
			else
			{
				$ccontrato1= $cazul;
				$cfondo= $fazul;			
			}
			
			$filad=$gris;
			$pendiente="window.open( '$ruta/pendientec.php?contrato=" . $row["elidcontrato"] . "');";
			//$pendientemail="window.open( '$ruta/pendientesmail.php?contrato=" . $row["elidcontrato"] . "');";
			$pendientemail="mostrarrdivmail('emailenvio',event);cargarSeccion('$ruta/pendientesmail.php','emailenvio','contrato=" . $row["elidcontrato"] . "' )";
			$verenvios= "<input type ='button' value='ver envios' onClick =\"window.open('$ruta/listamailclp.php?id=". $row["elidcontrato"] . "')\">";
			$accionboton="<input type =\"button\" value=\"Ver\" onClick=\"$pendiente\"  /><input type =\"button\" value=\"Enviar correo\" onClick=\"$pendientemail\"  /> ";
			$accionboton.="<input type =\"checkbox\" value=\"". $row["elidcontrato"] . "\" name='c_". $row["elidcontrato"] . "' checked onchange=\"ok=1;if(this.checked==true){ok=1}else{ok=0};actualizamailsp(this.name,ok)\"  /> Envio masivo $verenvios";
			$Cabeceratabla="<tr><td class=\"$ccontrato1\">Contrato: </th><td colspan=\"3\" class=\"$ccontrato1\">" . $row["elidcontrato"]  . " $accionboton&nbsp;&nbsp;$accionlitigio</td></tr>";
			$Cabeceratabla .="<tr class=\"$cfondo\"><th>Inquilino: </th><td colspan=\"3\">" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "(Tel. " . $row["inqtel"] . ", correo electr&oacute;nico ". $row["emaili"]  . ")</td></tr>";
			$Cabeceratabla .="<tr class=\"$blanco\"><th>Inmueble: </th><td colspan=\"3\">" .   $row["calle"] . " No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"] . " Deleg/Mun. ". $row["delmun"] . " C.P. " . $row["cp"]  . " Tel. " . $row["itel"] . " </td></tr>";
			$Cabeceratabla .="<tr class=\"$cfondo\"><th>Obligado Solidario: </th><td colspan=\"3\">" . $row["fnombre"] . " " . $row["fnombre2"] . " " . $row["fapaterno"] . " " . $row["famaterno"]  . " (Tel. " . $row["ftel"] . ", email " . $row["emailf"] . ")</td></tr>";
			$Cabeceratabla .="<tr class=\"$blanco\"><td colspan=\"4\">&nbsp;</td></tr><tr>";
			//echo "\n<tr><td><br><table border=\"1\" width=\"100%\">$Cabeceratabla<tr><th>Contrato</th><th>Nombre</th><th>Fecha nat. pago</th><th>Concepto</th><th>Cantidad</th></tr>\n";
			$html .=  "\n<tr><td><br><table width=\"596\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#999999\" id=\"tbl_$tn\">$Cabeceratabla<tr><td class=\"$filad\">Fecha nat. pago</td><td class=\"$filad\">Concepto</td><td class=\"$filad\">Cantidad</td><td class=\"$filad\">Acci&oacute;n</td></tr>\n";
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

		if($filad==$gris)
		{
			$filad=$blanco;
		}
		else
		{
			$filad=$gris;
		}
		//echo "<tr><td>" . $row["elidcontrato"] . "</td><td>" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " .$row["amaterno"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>$concepto</td><td align=\"right\">" . ($row["cantidad"] + $row["iva"]) . "</td></tr>\n";
		$idhist = $row["idhistoria"];
		$accionboton="<input type =\"button\" value=\"Condonar\" onClick=\"Condonar ('tbl_$tn',this,$idhist,'scripts/inmuebles');\"  />";
		$html .=  "<tr class=\"$filad\"><td>" . $row["fechanaturalpago"] . "</td><td>$concepto <b>" . $row["observaciones"] . "</b></td><td align=\"right\">$ " . number_format($Pagado,2) . "</td><td align=\"center\">$accionboton</td></tr>\n";

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
	$html .=  "</table></div><div id='emailenvio' style='width:300; height:100; background-color: lightblue; color: #ffffff; position: absolute;display:none;height:100px; overflow:auto;'></div>";
	
	
	
	$sqlbtn="select count(*) as m from envioclp where fechaclp = '$hoy' and individual = 0";
	$operacionbtn = mysql_query($sqlbtn);
	$rowbtn = mysql_fetch_array($operacionbtn);
	$habilitado = "";
	
	if($rowbtn["m"]>0)
	{
		$habilitado=" disabled ";
	}
	
	
	$html = "<input type=\"button\" value=\"envio masivo\" $habilitado onClick=\"mostrarrdivmail('emailenvio',event);cargarSeccion('$ruta/pendientesmail.php','emailenvio','contrato=' + masivo.value ) \"><input type=\"hidden\" value=\"$masivo\" id=\"masivo\" name=\"masivo\"  \">" . $html;
	echo CambiaAcentosaHTML($html);



}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>