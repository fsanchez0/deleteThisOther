<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo

$id=@$_GET["id"];
$ver=@$_GET["ver"];
$idpropietarios=@$_GET["idpropietarios"];

$cverde = "text-align:left;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#063;
	background-color:#9C0;
	color:#FFFFFF;";
$cazul = "text-align:left;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#063;
	background-color:#9C0;
	color:#FFFFFF;";
$fverde ="text-align:left;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#063;
	background-color:#EEE;
	color:#000000;"; //clase para fondo verde
$fazul = "text-align:left;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#063;
	background-color:#CCC;
	color:#000000;
	height:24px;"; //clase para fondo azul
$gris = "text-align:center;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	background-color:#CCCCCC;
	color:#000000;
	width:149px;
	padding: 5px 0px;";  //clase para entre renglones
$blanco ="text-align:left;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	background-color:#ffffff;
	color:#000000;";
$h1="text-align:center;
	background-color:#009762;
	font-size:20px;
	color:#FFF;
	padding:10px;
	margin: 0px;";
$pie="background-color:#009660;
	padding:5px;
	color:#FFF;
	font-size:14px;
	font-weight:bold;
	text-align: center;";
$piea="color:#FFF;
	text-decoration:none;";

$masivo = "";//para enviar todos los id

$ccontrato1 = $cazul;
$cfondo=$fverde; //variable para el cambio de fondo azul o verde
$filad=$gris;

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes"){
	
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='pendientesSelectP.php?ver=0'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion)){		
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta = $row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}
	echo '<div id="body"  height"1400" width="1200">';

	if ($priv[2]=='1'){
		if($id){
			$sql= "update contrato set litigio=true where idcontrato = $id";	
			$operacion = mysql_query($sql);
		}
	}


	$hoy=date('Y') . "-" . date('m') . "-" . date('d');

	if($ver==0){
		//CREACION TABLA DE SELECCION DE INMUEBLES
		$sqlSelect = "SELECT  idduenio, nombre, nombre2, apaterno, amaterno, tel, rfcd FROM duenio WHERE idduenio IN (SELECT duenioinmueble.idduenio FROM contrato, historia, inmueble, estado ,pais, duenioinmueble WHERE contrato.idcontrato=historia.idcontrato and historia.aplicado=false and contrato.idinmueble = inmueble.idinmueble and litigio=false and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais and duenioinmueble.idinmueble = inmueble.idinmueble and historia.fechanaturalpago <='$hoy' group by duenioinmueble.idduenio order by duenioinmueble.idduenio)";

		$result = mysql_query ($sqlSelect);

		if (!$result){
			echo "Error";
		}else{
			$idprop= array();
			$nombreProp= array();
			$cont = 0;

			echo '<div id="head" align="center" height"1400" width="1200">';
			echo '<table id="Table" border="0" aling="center"> <tr><th colspan="2" align="center">REPORTE COBRANZA POR PROPIETARIO</th></tr>';

			echo '<tr> <td><table id="ListTable" border="1"> <tr><th colspan="2">Lista Propietarios</th></tr> <tbody id="TbodyList">';
			echo '<tr><th>ID</th><th>PROPIETARIO</th></tr>';
			while ($row = mysql_fetch_array($result)){
				$idprop=$row["idduenio"];
				$nombreProp[$row["idduenio"]]=$row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"];
				$funIntercambio = "intercambioTablas(this.parentNode.parentNode.rowIndex ,'ListTable','TbodySelect')";
				$html = '<tr><td>'.$idprop.'</td><td><p onClick="'.$funIntercambio.'"><font size="2">'.$nombreProp[$idprop].'</font></p></td></tr>';
				echo CambiaAcentosaHTML($html);
				$cont ++;
			}
			echo "</tbody></table></td>";
			$funReporte = "reporteP('SelectTable')";
			echo '<td valign="top"> <table id="SelectTable" border="1" style=background-color:#9C0> <tr><th colspan="2">Propietarios Seleccionados <input type="button" value="Reporte" onClick="'.$funReporte.'"></th></tr> <tbody id="TbodySelect">';
			echo '<tr><th>ID</th><th>PROPIETARIO</th></tr>';
			echo "</tbody></table></td>";

			echo "</tr></table>";
			echo '</div>';
		}

	}else{

		if($_REQUEST["descargar"]==true){
			$idpropietarios=$_REQUEST["idpropDes"];;
		}

		$sql= "SELECT idhistoria, contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as inqtel,tipocobro, fechagenerado , historia.fechanaturalpago, historia.cantidad, aplicado, historia.interes, historia.iva as ivah, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, calle, numeroext, numeroint, inmueble.colonia, delmun, estado, pais, inmueble.cp, inmueble.tel as itel, inquilino.email as emaili, fiador.email as emailf,observaciones FROM contrato, cobros, inquilino,tipocobro, historia, fiador, inmueble, estado, pais  WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and historia.aplicado=false and contrato.idfiador=fiador.idfiador and contrato.idinmueble = inmueble.idinmueble and litigio=false and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais and historia.fechanaturalpago <= '$hoy' and inmueble.idinmueble in (select idinmueble from duenioinmueble where idduenio in ($idpropietarios)) order by inquilino.idinquilino, historia.idcontrato, fechanaturalpago";  //, historia.idhistoria";

		if($_REQUEST["descargar"]==true){

			$fecha = date("d-m-Y");
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename=CobranzaExigible_'.$fecha.'.xls');
			header('Pragma: no-cache');
			header('Expires: 0');
			$resultado= mysql_query($sql);
			$tabla= "<center><h1>Reporte de Cobranza Exigible</h1></center>";
			$tabla.= "<table border=1> ";
			$tabla.= "<tr style='background-color:#5C9CCF; font-color:white;'> ";
			$tabla.=     "<th>Contrato</th> ";
			$tabla.= 	"<th>Inquilino</th> ";
			$tabla.= 	"<th>Telefono</th> ";
			$tabla.= 	"<th>Correo Electronico</th> ";
			$tabla.= 	"<th>Inmueble</th> ";
			$tabla.= 	utf8_decode("<th>Alcaldia y/o Municipio</th> ");
			$tabla.= 	"<th>Codigo Postal</th> ";
			$tabla.= 	"<th>Telefono</th> ";
			$tabla.= 	"<th>Obligado Solidario</th> ";
			$tabla.= 	"<th>Telefono</th> ";
			$tabla.= 	"<th>Correo Electronico</th> ";
			$tabla.= 	"<th>Fecha Natural Pago</th> ";
			$tabla.= 	"<th>Concepto</th> ";
			$tabla.= 	"<th>Cantidad</th> ";
			$tabla.= "</tr> ";

			while($row = mysql_fetch_array($resultado)){	

				$inquilinos = utf8_decode($row["nombre"]." ".$row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]);
				$telinquilino=$row["inqtel"];
				$emailinquilino=$row["emaili"];
				$inmuebles = utf8_decode($row["calle"] . " No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"]);
				$delagacion=utf8_decode($row["delmun"]);
				$codigop=$row["cp"];
				$telinmueble=$row["itel"];
				$obligados= utf8_decode($row["fnombre"] . " " . $row["fnombre2"] . " " . $row["fapaterno"] . " " . $row["famaterno"]);
				$telobligado=$row["ftel"];
				$emailobligado=$row["emailf"];
				$fechanatpago=$row["fechanaturalpago"];
				$conceptos=utf8_decode($row["tipocobro"]);
				$cantida=$row["cantidad"];
				$contratoid=$row["elidcontrato"];
				$ivah=$row["ivah"];
				$total=($cantida+$ivah);

				$contador++;
				if ($contador==1) {
					$color="style=background-color:#BCD8F0;";
				}
				if ($contador==2) {
					$color="style=background-color:#DEEBF3;";
					$contador=0;
				}
				$tabla.= "<tr $color> ";
				$tabla.= 	"<td>".$contratoid."</td> "; 
				$tabla.= 	"<td>".$inquilinos."</td> "; 
				$tabla.= 	"<td>".$telinquilino."</td> "; 
				$tabla.= 	"<td>".$emailinquilino."</td> "; 
				$tabla.= 	"<td>".$inmuebles."</td> "; 
				$tabla.= 	"<td>".$delagacion."</td> ";
				$tabla.= 	"<td>".$codigop."</td> ";
				$tabla.= 	"<td>".$telinmueble."</td> ";
				$tabla.= 	"<td>".$obligados."</td> ";
				$tabla.= 	"<td>".$telobligado."</td> ";
				$tabla.= 	"<td>".$emailobligado."</td> ";
				$tabla.= 	"<td>".$fechanatpago."</td> "; 
				$tabla.= 	"<td>".$conceptos."</td> "; 
				//echo 	"<td>".$total."</td> "; 
				$tabla.= "<td>".number_format($total,2)."</td> ";
				$tabla.= "</tr> ";

			}
			$tabla.= "</table> "; 
			echo $tabla;

			//echo "<SCRIPT>history.back(1)</SCRIPT>";  
		}else{

			//CREACION DE REPORTE CON INMUEBLES SELECCIONADOS

			$html = "<input type=\"button\" value=\"Imprimir\" onClick=\"imprimirv('imprimirdiv') \"><div id=\"imprimirdiv\">";
		
			$html .= '<div id="content">  <div id="head" align="center"><img src="http://'.$_SERVER["SERVER_NAME"].'/imagenes/headp.jpg" width="612" height="134" alt="Padilla Bujalil. Pendientes por pagar" /><div id="tablas"><h1 style="'.$h1.'">Pendientes de Inmuebles por cobrar </h1><br>';

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
			while($row = mysql_fetch_array($operacion)){

				if($ccontrato!=$row["elidcontrato"]){
			
					$masivo .=$row["elidcontrato"] ."|";
					if($ccontrato!=0){

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
					if ($priv[2]=='1'){
						//$pendiente="window.open( 'scripts/pendientec.php?contrato=" . $row["elidcontrato"] . "');";
						$accionlitigio="<input type =\"button\" value=\"A litigio\" onClick=\"cargarSeccion('$ruta/pendientesSelectP.php','contenido','ver=0&id=" .  $row["elidcontrato"]  . "' )\"  />";
					}
							
					if($ccontrato1 == $cazul){
						$ccontrato1= $cverde;
						$cfondo= $fverde;
					}else{
						$ccontrato1= $cazul;
						$cfondo= $fazul;			
					}
				
					$filad=$gris;
					$pendiente="window.open( '$ruta/pendientesc.php?contrato=" . $row["elidcontrato"] . "');";
					//$pendientemail="window.open( '$ruta/pendientesmail.php?contrato=" . $row["elidcontrato"] . "');";
					$pendientemail="mostrarrdivmail('emailenvio',event);cargarSeccion('$ruta/pendientesmail.php','emailenvio','contrato=" . $row["elidcontrato"] . "' )";
					$verenvios= "<input type ='button' value='ver envios' onClick =\"window.open('$ruta/listamailclp.php?id=". $row["elidcontrato"] . "')\">";
					$accionboton="<input type =\"button\" value=\"Ver\" onClick=\"$pendiente\"  /><input type =\"button\" value=\"Enviar correo\" onClick=\"$pendientemail\"  /> ";
					$accionboton.="<input type =\"checkbox\" value=\"". $row["elidcontrato"] . "\" name='c_". $row["elidcontrato"] . "' checked onchange=\"ok=1;if(this.checked==true){ok=1}else{ok=0};actualizamailsp(this.name,ok)\"  /> Envio masivo $verenvios";
					$Cabeceratabla="<tr><td style=\"$ccontrato1\">Contrato: </th><td colspan=\"3\" style=\"$ccontrato1\">" . $row["elidcontrato"]  . " $accionboton&nbsp;&nbsp;$accionlitigio</td></tr>";
					$Cabeceratabla .="<tr style=\"$cfondo\"><th>Inquilino: </th><td colspan=\"3\">" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "(Tel. " . $row["inqtel"] . ", correo electr&oacute;nico ". $row["emaili"]  . ")</td></tr>";
					$Cabeceratabla .="<tr style=\"$blanco\"><th>Inmueble: </th><td colspan=\"3\">" .   $row["calle"] . " No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"] . " Deleg/Mun. ". $row["delmun"] . " C.P. " . $row["cp"]  . " Tel. " . $row["itel"] . " </td></tr>";
					$Cabeceratabla .="<tr style=\"$cfondo\"><th>Obligado Solidario: </th><td colspan=\"3\">" . $row["fnombre"] . " " . $row["fnombre2"] . " " . $row["fapaterno"] . " " . $row["famaterno"]  . " (Tel. " . $row["ftel"] . ", email " . $row["emailf"] . ")</td></tr>";
					$Cabeceratabla .="<tr style=\"$blanco\"><td colspan=\"4\">&nbsp;</td></tr><tr>";
					//echo "\n<tr><td><br><table border=\"1\" width=\"100%\">$Cabeceratabla<tr><th>Contrato</th><th>Nombre</th><th>Fecha nat. pago</th><th>Concepto</th><th>Cantidad</th></tr>\n";
					$html .=  "\n<tr><td><br><table width=\"612\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#FFFFFF\" id=\"tbl_$tn\">$Cabeceratabla<tr><td style=\"$filad\">Fecha nat. pago</td><td style=\"$filad\">Concepto</td><td style=\"$filad\">Cantidad</td><td style=\"$filad\">Acci&oacute;n</td></tr>\n";
					$ccontrato=$row["elidcontrato"];
				}

				if (is_null($row["interes"])==false and $row["interes"]==1){
					$concepto = "INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . "(" . $row["tipocobro"] . ")";
					$interes += $row["cantidad"] + $row["ivah"];
					$ginteres +=$row["cantidad"] + $row["ivah"];
					$Pagado=$row["cantidad"]  + $row["ivah"];

				}else{
					$concepto = $row["tipocobro"];
					if(strtoupper($row["tipocobro"])=="RENTA"){
						if ($row["aplicado"]==false ){
							$rentas +=($row["cantidad"] + $row["ivah"]);
							$grentas +=($row["cantidad"] + $row["ivah"]);
							$Pagado=($row["cantidad"] + $row["ivah"]);
						}else{
							$rentas +=$row["cantidad"] ;
							$grentas +=$row["cantidad"] ;
							$Pagado=$row["cantidad"] ;
						}
						//$rentas +=$row["cantidad"] + $row["ivah"];
					}else{
						if ($row["aplicado"]==false ){
							$otros +=($row["cantidad"] + $row["ivah"]);
							$gotros +=($row["cantidad"] + $row["ivah"]);
							$Pagado=($row["cantidad"] + $row["ivah"]);
					
						}else{
							$otros +=$row["cantidad"] ;
							$gotros +=$row["cantidad"] ;
							$Pagado=$row["cantidad"] ;
						}			
						//$otros +=$row["cantidad"] + $row["ivah"];
					}
				}

				if($filad==$gris){
					$filad=$blanco;
				}else{
					$filad=$gris;
				}
				//echo "<tr><td>" . $row["elidcontrato"] . "</td><td>" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " .$row["amaterno"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>$concepto</td><td align=\"right\">" . ($row["cantidad"] + $row["iva"]) . "</td></tr>\n";
				$idhist = $row["idhistoria"];
				$accionboton="<input type =\"button\" value=\"Condonar\" onClick=\"Condonar ('tbl_$tn',this,$idhist,'scripts/inmuebles');\"  />";
				$html .=  "<tr style=\"$filad\"><td>" . $row["fechanaturalpago"] . "</td><td>$concepto <b>" . $row["observaciones"] . "</b></td><td align=\"right\">$ " . number_format($Pagado,2) . "</td><td align=\"center\">$accionboton</td></tr>\n";

			}
		
			$html .=  "<tr><td colspan=\"5\" align=\"right\">";
			$html .=  "<table><tr><th>T. Renta</th><th>&nbsp;</td><th>T. Mantenimiento</th><th>&nbsp;</th><th>T. Inter&eacute;s</th><th>&nbsp;</th><th>Total</th></tr>";
			$html .=  "<tr><td align=\"center\">$ " . number_format($rentas,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($otros,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($interes,2) . "</td><td align=\"center\">=</td><td align=\"center\"><strong>$ " . number_format(($rentas+$otros+$interes),2) . "</strong></td></tr></table>";
			$html .=  "</td></tr>";
			$html .=  "</table></td></tr>";
			$html .=  "<tr><td align=\"right\"><br>";
			$html .=  "<table><tr><th>G. T. Renta</th><th>&nbsp;</td><th>G .T. Mantenimiento</th><th>&nbsp;</th><th>G. T. Inter&eacute;s</th><th>&nbsp;</th><th>Gran Total</th></tr>";
			$html .=  "<tr><td align=\"center\">$ " . number_format($grentas,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($gotros,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($ginteres,2) . "</td><td align=\"center\">=</td><td align=\"center\"><strong>$ " . number_format(($grentas+$gotros+$ginteres),2) . "</strong></td></tr></table>";
			$html.="<div style=\"$pie\"><a style=\"$piea\" href=\"http://www.padilla-bujalil.com.mx\">www.padilla-bujalil.com.mx</a></div>";
			$html .=  "</td></tr>";
			$html .=  "</table></div><div id='emailenvio' style='width:300; height:100; background-color: lightblue; color: #ffffff; position: absolute;display:none;height:100px; overflow:auto;'></div>";
			$sqlbtn="select count(*) as m from envioclp where fechaclp = '$hoy' and individual = 0";
			$operacionbtn = mysql_query($sqlbtn);
			$rowbtn = mysql_fetch_array($operacionbtn);
			$habilitado = "";
		
			if($rowbtn["m"]>0){
				$habilitado=" disabled ";
			}
			$html = "<input type=\"button\" value=\"envio masivo\" $habilitado onClick=\"mostrarrdivmail('emailenvio',event);cargarSeccion('$ruta/pendientesmail.php','emailenvio','contrato=' + masivo.value ) \"><input type=\"hidden\" value=\"$masivo\" id=\"masivo\" name=\"masivo\"  \">
				<form action='/scripts/reportes/pendientesSelectP.php?ver=1' method='post'>
	 			<input type='hidden' name='descargar' value=true><input type='hidden' name='idpropDes' value=\"$idpropietarios\"><br>
				<input type='submit' value='Descargar Excel'>" . $html;
			echo CambiaAcentosaHTML($html);
		}
	}
	echo '</div>';
}else{
	echo "A&uacute;n no se ha firmado con el servidor";
}
?>