<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclassd.php';
//Modulo

$id=@$_GET["contrato"];


$enviocorreo = New correo2;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
/*
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='privilegios.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}
*/
$conref=mysql_query("SELECT apartado.referencia FROM apartado,contrato WHERE contrato.idcontrato='$id' AND contrato.idapartado=apartado.id");
while($ref=mysql_fetch_array($conref)){
$referenciass=$ref[0];
}
$datoss=mysql_query("SELECT inquilino.nombre,inquilino.nombre2,inquilino.apaterno,inquilino.amaterno,inmueble.calle,inmueble.numeroext,inmueble.numeroint,inmueble.colonia,inmueble.delmun,inmueble.cp,fiador.nombre,fiador.nombre2,fiador.apaterno,fiador.amaterno FROM contrato,inmueble,inquilino,fiador WHERE contrato.idcontrato='$id' AND contrato.idinquilino=inquilino.idinquilino AND contrato.idfiador=fiador.idfiador AND contrato.idinmueble=inmueble.idinmueble");
while($dats=mysql_fetch_array($datoss)){
$nominq=$dats[0]." ".$dats[1]." ".$dats[2]." ".$dats[3];
$nomfiador=$dats[10]." ".$dats[11]." ".$dats[12]." ".$dats[13];
$calleinm=$dats[4];
$numextinm=$dats[5];
$numintinm=$dats[6];
$coloniainm=$dats[7];
$delmuninm=$dats[8];
$cpinm=$dats[9];
}
$dia=date("d");
$anio= date("Y");
$mes=date("m");

switch($mes)
{
case 1:
	$mes="Enero";
	break;
case 2:
	$mes="Febrero";
	break;
case 3:
	$mes="Marzo";
	break;
case 4:
	$mes="Abril";
	break;
case 5:
	$mes="Mayo";
	break;
case 6:
	$mes="Junio";
	break;
case 7:
	$mes="Julio";
	break;
case 8:
	$mes="Agosto";
	break;
case 9:
	$mes="Septiembre";
	break;
case 10:
	$mes="Octubre";
	break;
case 11:
	$mes="Noviembre";
	break;
case 12:
	$mes="Diciembre";
	break;
}


//identifico si es $tipo osea individual o multiple
//$id=substr($id,0,-1);
$ids = preg_split("/[|]/", $id);
//print_r ($ids);
//echo "lista de $id:(" . $ids[0] . ")" . count($ids);
$masivook=0;
if(count($ids)>1)
{
	$tipo = 0;
	$ids = preg_split("/[|]/", substr($id,0,-1));
	
	$hoy=date('Y') . "-" . date('m') . "-" . date('d');
	$sqlbtn="select count(*) as m from envioclp where fechaclp = '$hoy' and individual = 0";
	$operacionbtn = mysql_query($sqlbtn);
	$rowbtn = mysql_fetch_array($operacionbtn);
	$habilitado = "";
	
	if($rowbtn["m"]>0)
	{
		$masivook=1;
	}
}
else
{
	$tipo = 1;
}
$body="
text-align:center;
margin:auto;
font-family:Arial, Helvetica, sans-serif;
font-size:12px;
line-height:20px;
";

$img="
border:none;
";
$table="
	width:80%;
";
$content="
	text-align:left;
	margin:auto;
	width:600px;
	";
	$head="
	text-align:left;
	margin:auto;
	";
	$tablas="
	text-align:center;
	margin:auto;
	";
	$h1="
	text-align:center;
	font-size:20px;
	color:#333;
	margin:10px 0px;
	padding:5px;
	border-bottom: 1px dotted #000;
		";
		$contrato="
	text-align:left;
	color:#063;
	background-color:#F30;
	color:#FFFFFF;
		";
		$fila1="
	text-align:left;
	color:#063;
	background-color:#DDD;
	color:#000000;
		";
		$fila2="
	text-align:left;
	background-color:#ffffff;
	color:#000000;
		";
		$fila3="
	text-align:center;

	background-color:#CCCCCC;
	color:#000000;
	width:149px;
	padding: 5px 0px;
		";
		$contrato2="
	text-align:left;
	color:#063;
	background-color:#333;
	color:#FFFFFF;
		";
		$fila4="
	text-align:left;
	color:#063;
	background-color:#CCC;
	color:#000000;
	height:24px;
		";
		$nota="
	text-align:center;
	color:#039;
	padding:5px;
	font-size:15px;
	font-family:Arial, Helvetica, sans-serif;
	background-color:#C7E7FF;
	border: 2px #039 solid;
	width: 50%;
	margin: 5px auto 10px auto;
		";
$fecha="
	margin:10px 0px;
	font-weight:bold;
";
$texto="
	background-color:#9CF;
	border:2px solid #039;
	padding:10px;
	text-align:center;
";
$destinatario="
	margin:10px 0px;
	text-align:center;
";
$destinatarioh3="
	border-bottom:1px dotted #666;
";

$textofooter="
	text-align:center;
	padding:10px;
	background-color:#EEE;
	margin-top:20px;
";
$textofooterp="
	padding:0px;
	margin:0px;
";

$textofooterh2="
	font-size:30px;
	font-style:italic;
	margin:10px;
";
$pie="
	background-color:#009660;
	padding:5px;
	color:#FFF;
	font-size:14px;
	font-weight:bold;
	text-align: center;
";
$piea="
	color:#FFF;
	text-decoration:none;
";
if($masivook==0)
{

foreach ($ids as $id)
{


$html = <<<cavecera
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Padilla & Bujalil S.C. :: Pendiente por cobrar</title>
</head>
<body style="$body">
<div id="content">
  <div style="$head"><img src="images/headp1.jpg" alt="Padilla Bujalil. Pendientes por pagar" style="$img" />
  </div>
  <div style="$tablas">
  <div style="$fecha"><br>México D.F. a $dia de $mes de $anio</div>
  <div style="$destinatario">
    <h3 style="$destinatarioh3">A nuestro Inquilino:</h3>
   <p>Se le hace una cordial invitación para poner al corriente sus pagos de renta. <br />
      Debe hacer su pago en la sucursal de <strong>Banorte</strong> más cercana.</p>
<div style="$texto">Deposito a la empresa No. 57685<br>Referencia:  $nominq </div>
</div>
  <table border="0" width="100%">
    <tbody>
      <tr>
        <td  style="$contrato" id="tabla_encabezadoinquilino">Contrato:
          </th></td>
        <td  style="$contrato" id="tabla_datosinquilino" colspan="2">$id</td>
      </tr>
      <tr>
        <td style="$fila1" id="tabla_encabezadoinquilino">Inquilino:
          </th></td>
        <td style="$fila1" id="tabla_datosinquilino" colspan="2">$nominq</td>
      </tr>
      <tr>
        <td style="$fila2" id="tabla_encabezadoinquilino">Inmueble:
          </th></td>
        <td style="$fila2" id="tabla_datosinquilino" colspan="2">$calleinm No. $numextinm Int. $numintinm Col. $coloniainm Deleg/Mun. $delmuninm C.P. $cpinm</td>
      </tr>
      <tr>
        <td style="$fila1" id="tabla_encabezadoinquilino">Obligado Solidario:
          </th></td>
        <td style="$fila1" id="tabla_datosinquilino" colspan="2">$nomfiador</td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr style="$fila3" id="tabla_fecha">
        <th>Fecha nat. pago</th>
        <th>Concepto</th>
        <th>Cantidad</th>
      </tr>
cavecera;


	$fecha=date('Y') . "-" . date('m') . "-" . date('d');
$nuevafecha = strtotime ( '-60 day' , strtotime ( $fecha ) ) ;
$hoy = date ( 'Y-m-d' , $nuevafecha );
$hoy2=date('Y-m-d');
  $sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as inqtel,tipocobro, fechagenerado , historia.fechanaturalpago, historia.cantidad, aplicado, historia.interes, historia.iva as ivah, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, calle, numeroext, numeroint, inmueble.colonia, delmun, estado, pais, inmueble.cp, inmueble.tel as itel, inquilino.email as emaili, fiador.email as emailf, observaciones FROM contrato, cobros, inquilino,tipocobro, historia, fiador, inmueble, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and historia.aplicado=false and contrato.idfiador=fiador.idfiador and contrato.idinmueble = inmueble.idinmueble and historia.fechanaturalpago <= '$hoy2' and historia.fechanaturalpago >= '$hoy' and contrato.idcontrato = $id and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais order by inquilino.idinquilino, fechanaturalpago, historia.idhistoria";
	//$html .= "<h1>Pendientes por cobrar</h1>\n";

	$operacion = mysql_query($sql);
	$ccontrato=0;
	$grentas=0;
	$gotros=0;
	$ginteres=0;
	$rentas=0;
	$otros=0;
	$interes=0;



	$imail="";
	$fmail="";

	//$html .= "<table border=\"0\">";
	while($row = mysql_fetch_array($operacion))
	{

		if($ccontrato!=$row["elidcontrato"])
		{
			if($ccontrato!=0)
			{

				//$html .= "<tr><td colspan=\"5\" align=\"right\">";
				//$html .= "<table><tr><th>T. Renta</th><th>&nbsp;</td><th>T. Mantenimiento</th><th>&nbsp;</th><th>T. Interes</th><th>&nbsp;</th><th>Total</th></tr>";
				//$html .= "<tr><td align=\"center\">$ " . number_format($rentas,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($otros,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($interes,2) . "</td><td align=\"center\">=</td><td align=\"center\"><strong>$ " . number_format(($rentas+$otros+$interes),2) . "</strong></td></tr></table>";
				//$html .= "</td></tr>";

				//$html .= "</table></td></tr>";
				//pies de datos de las sumas


				//reinicio de las sumas
				$grentas +=$rentas;
				$gotros +=$otros;
				$ginteres +=$interes;
				$rentas=0;
				$otros=0;
				$interes=0;

				//Saltos de lineas
				//echo "<br><br><br><br>";
			}

/*
			$html .= "<div id=\"destinatario\">Referencia: " .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "</div>";
    			$html .= "<div id=\"tabla\">";

			$Cabeceratabla="<tr ><th id=\"tabla_encabezadoinquilino\">Contrato: </th><td id=\"tabla_datosinquilino\" colspan=\"2\">" . $row["elidcontrato"]  . " </td></tr>";
			$Cabeceratabla .="<tr ><th id=\"tabla_encabezadoinquilino\">Inquilino: </th><td id=\"tabla_datosinquilino\"  colspan=\"2\">" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . " </td></tr>";
			$Cabeceratabla .="<tr><th id=\"tabla_encabezadoinquilino\">Inmueble: </th><td id=\"tabla_datosinquilino\"  colspan=\"2\">" .   $row["calle"] . " No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"] . " Deleg/Mun. ". $row["delmun"] . " C.P. " . $row["cp"]  .  " </td></tr>";
			$Cabeceratabla .="<tr><th id=\"tabla_encabezadoinquilino\">Obligado Solidario: </th><td id=\"tabla_datosinquilino\"  colspan=\"2\">" . $row["fnombre"] . " " . $row["fnombre2"] . " " . $row["fapaterno"] . " " . $row["famaterno"]  . "</td></tr>";
			//echo "\n<tr><td><br><table border=\"1\" width=\"100%\">$Cabeceratabla<tr ><th>Contrato</th><th>Nombre</th><th>Fecha nat. pago</th><th>Concepto</th><th>Cantidad</th></tr>\n";
			$html .= "\n<tr ><td width=\"631\"><br><table border=\"1\" width=\"100%\">$Cabeceratabla<tr id=\"tabla_fecha\"><th >Fecha nat. pago</th><th>Concepto</th><th>Cantidad</th></tr>\n";
			*/
			$ccontrato=$row["elidcontrato"];
		}


		if (is_null($row["interes"])==false and $row["interes"]==1)
		{

			$concepto = "INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . "(" . $row["tipocobro"] . ")";
			$interes += $row["cantidad"] + $row["ivah"];
			$Pagado=$row["cantidad"] + $row["ivah"] ;

		}
		else
		{
			$concepto = $row["tipocobro"];
			if(strtoupper($row["tipocobro"])=="RENTA")
			{

				if ($row["aplicado"]==false )
				{
					$rentas +=($row["cantidad"] + $row["ivah"]);
					$Pagado=($row["cantidad"] + $row["ivah"]);

				}
				else
				{
					$rentas +=$row["cantidad"] ;
					$Pagado=$row["cantidad"] ;
				}



				//$rentas +=$row["cantidad"] + $row["ivah"];

			}
			else
			{
				if ($row["aplicado"]==false )
				{
					$otros +=($row["cantidad"] + $row["ivah"]);
					$Pagado=($row["cantidad"] + $row["ivah"]);

				}
				else
				{
					$otros +=$row["cantidad"] ;
					$Pagado=$row["cantidad"] ;
				}


				//$otros +=$row["cantidad"] + $row["ivah"];
			}


		}

		$html.="<tr>";
        $html.='<td id="tabla_fecha">'.$row["fechanaturalpago"].'</td>';
       $html.='<td> '.utf8_encode($concepto).' <strong>'.$row["observaciones"].'</strong><br /></td>';
       $html.=' <td align="right">$'. number_format($Pagado,2).'</td>';
      $html.="</tr>";
		//echo "<tr><td>" . $row["elidcontrato"] . "</td><td>" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " .$row["amaterno"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>$concepto</td><td align=\"right\">" . ($row["cantidad"] + $row["iva"]) . "</td></tr>\n";
		//$html .= "<tr ><td id=\"tabla_fecha\">" . $row["fechanaturalpago"] . "</td><td>$concepto<br><strong>" . $row["observaciones"] . "</strong></td><td align=\"right\">$ " . number_format($Pagado,2) . "</td></tr>\n";
		
		$imail=$row["emaili"];
		if($row["retraso"] >30)
		{
			if (is_null($row["emailf"])!=true)
			{
				$fmail=$row["emailf"];
			}
		
		}
		

	}
	/*
	$html .= "<tr><td colspan=\"5\" align=\"right\">";
	$html .= "<table><tr><th>T. Renta</th><th>&nbsp;</td><th>T. Mantenimiento</th><th>&nbsp;</th><th>T. Inter&eacute;s</th><th>&nbsp;</th><th>Total</th></tr>";
	$html .= "<tr><td align=\"center\">$ " . number_format($rentas,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($otros,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($interes,2) . "</td><td align=\"center\">=</td><td align=\"center\"><strong>$ " . number_format(($rentas+$otros+$interes),2) . "</strong></td></tr></table>";
	$html .= "</td></tr>";
	$html .= "</table></div></td></tr>";

	$html .= "<tr>";
	$html .= "    <td><div id=\"textofooter\"><br><br>Le recordamos nuestro telfono para cualquier informacin: <br />";
	$html .= "    <img src='cid:telefono'><br/>";
	$html .= "      Por su atencin, gracias.</div>";
	$html .= "      <div id=\"firma\">Atentamente<br>";
	$html .= "    Padilla &amp; Bujalil S.C.<br><img src='cid:footer'></div><br>";

	$html .= "  </tr>";
	$html .= "</table>";
	$html .= "</body>";
	$html .= "</html>";
	*/
		$html.="<tr>";
      $html.="<td colspan=\"5\" align=\"right\" style=\"$tablainferior\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
         $html.="<tbody>";
          $html.="<tr style=\"$contrato\">";
          $html.="<th width=\"100\" align=\"center\">T. Renta</th>";
          $html.="<th align=\"center\"> </th>";
          $html.="<th width=\"100\" align=\"center\">T. Mantenimiento</th>";
          $html.="<th align=\"center\"> </th>";
          $html.="<th width=\"100\" align=\"center\">T. Interés</th>";
          $html.="<th align=\"center\"> </th>";
          $html.="<th width=\"100\" align=\"center\">Total</th>";
          $html.="</tr>";
          $html.="<tr>";
          $html.="<td align=\"center\">$".number_format($rentas,2)."</td>";
          $html.="<td align=\"center\">+</td>";
          $html.="<td align=\"center\">$".number_format($otros,2)."</td>";
          $html.="<td align=\"center\">+</td>";
          $html.="<td align=\"center\">$".number_format($interes,2)."</td>";
          $html.="<td align=\"center\">=</td>";
          $html.="<td align=\"center\"><strong>$".number_format($rentas+$otros+$interes)."</strong></td>";
          $html.="</tr>";
          $html.="</tbody>";
          $html.="</table></td>";
      	  $html.="</tr></tbody></table>";
		  $html.="<div style=\"$textofooter\">";
          $html.="<p>Le recordamos nuestro teléfono para cualquier información:</p>";
          $html.="<h2>5592-8816</h2>";
          $html.="<h2>Ext. 119</h2>";
          $html.="<p>Por su atención, gracias.</p>";
          $html.="</div>";
 		  $html.= "</div>";
  		  $html.="<div style=\"$pie\"><a href=\"http://www.padilla-bujalil.com.mx\" style=\"$piea\">www.padilla-bujalil.com.mx</a></div>";
		  $html.="</div>";
		  $html.="</body>";
		  $html.="</html>";




	$mensaje = CambiaAcentosaHTML($html);
	
	
	$correoe=$imail;
	if ($fmail != "")
	{
		$correoe .= "," . $fmail;
	}
	
	echo $correoe;
	$esok=$enviocorreo->enviarp($correoe, "Pendiente de pago", $mensaje);
	
	if($esok)
		{
			//echo "se envio el correo";
			$hoy = date('Y-m-d');
			$hora = date('H:i:s');
			$sql = "insert into envioclp (idcontrato,idusuario,fechaclp,horaclp,mailclp,contenidoclp,individual) value ($id," . $misesion->usuario . ", '$hoy','$hora','$correoe','',$tipo)";
			$operacion = mysql_query($sql);
			
			
		}	
	
	
}
	

}	
else
{
	echo "Ya existe un env&iacute;o masivo el d&iacute;a de hoy, no se enviar&aacute; ning&uacute;n correo en esta modalidad.";
}
	echo "<input type='button' value='cerrar' onclick=\"ocultardivmail('emailenvio')\" />";

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>
