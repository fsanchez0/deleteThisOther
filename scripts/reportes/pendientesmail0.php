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


$html = <<<cavecera
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>Despacho Padilla & Bujalil :: Pendiente por cobrar</title>
<link href="css/estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
body{
text-align:center;
margin:auto;
}
#fecha {
    text-align:right;
	font-family:"News Gothic MT", Arial, verdana;
	font-size:12px;
    margin:auto;
	}
#texto {
    text-align:left;
	font-family:"News Gothic MT", Arial, verdana;
	font-size:12px;
	margin-top:20px;
	}
#destinatario {
    text-align:left;
	font-family:"News Gothic MT", Arial, verdana;
	font-weight:bold;
	font-size:12px;
    margin:auto;
	}
#textofooter {
    text-align:center;
	font-family:"News Gothic MT", Arial, verdana;
	font-size:12px;
    margin:auto;
	}
#firma {
    text-align:center;
	font-family:"News Gothic MT", Arial, verdana;
	font-weight:bold;
	font-size:12px;
    margin:20px;
	}
#tabla {
    text-align:center;
	font-family:"News Gothic MT", Arial, verdana;
	font-weight:bold;
	font-size:12px;
    margin:20px;
	}
#tabla_encabezadoinquilino{
	text-align:center;
	height:10px;
	background-color:#009B7B;
	font-family:"News Gothic MT", Arial, verdana;
	font-size:12px;
	color:#FFFFFF;
	}
#tabla_datosinquilino{
	text-align:center;
	background-color:#ABD7CC;
	font-family:"News Gothic MT", Arial, verdana;
	font-size:13px;
	font-weight:bold;
	color:#000000;
	}
#tabla_encabezadopagos{
	text-align:center;
	height:32px;
	background-color:#CCCCCC;
	font-family:"News Gothic MT", Arial, verdana;
	font-size:12px;
	font-weight:bold;
	color:#000000;
	}
#tabla_fecha{
	text-align:center;
	height:32px;
	background-color:#E6E6E6;
	font-family:"News Gothic MT", Arial, verdana;
	font-size:12px;
	color:#000000;
	}
#cifras{
	text-align:center;
	height:32px;
	background-color:#E6E6E6;
	font-family:"News Gothic MT", Arial, verdana;
	font-size:12px;
	color:#000000;
	}
#total{
	text-align:center;
	background-color:#009B7B;
	font-family:"News Gothic MT", Arial, verdana;
	font-size:12px;
	color:#FFFFFF;
	}



</style></head>
<body>
<table width="612" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src='cid:header'></td>
  </tr>
  <tr>
    <td>
      <p><div id="fecha">Mxico D.F. a $dia de $mes de $anio</div>
      <div id="destinatario">A nuestro Inquilino:</div>
    <div id="texto">...Se le hace una cordial invitaci&oacute;n para poner al corriente sus pagos de renta. Debe hacer su pago en la sucursal de Banorte ms cercana.</div></td>
  </tr>
  <tr>
    <td><div id="texto">Depsito a la empresa No. 57685</div></p>
cavecera;


	$hoy=date('Y') . "-" . date('m') . "-" . date('d');
	$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as inqtel,tipocobro, fechagenerado , historia.fechanaturalpago, historia.cantidad, aplicado, historia.interes, historia.iva as ivah, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, calle, numeroext, numeroint, inmueble.colonia, delmun, estado, pais, inmueble.cp, inmueble.tel as itel, inquilino.email as emaili, fiador.email as emailf, observaciones, DATEDIFF('$hoy',fechanaturalpago) as atraso FROM contrato, cobros, inquilino,tipocobro, historia, fiador, inmueble, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and historia.aplicado=false and contrato.idfiador=fiador.idfiador and contrato.idinmueble = inmueble.idinmueble and historia.fechanaturalpago <= '$hoy' and contrato.idcontrato = $id and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais order by inquilino.idinquilino, fechanaturalpago, historia.idhistoria";
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

	$html .= "<table border=\"0\">";
	while($row = mysql_fetch_array($operacion))
	{

		if($ccontrato!=$row["elidcontrato"])
		{
			if($ccontrato!=0)
			{

				$html .= "<tr><td colspan=\"5\" align=\"right\">";
				$html .= "<table><tr><th>T. Renta</th><th>&nbsp;</td><th>T. Mantenimiento</th><th>&nbsp;</th><th>T. Interes</th><th>&nbsp;</th><th>Total</th></tr>";
				$html .= "<tr><td align=\"center\">$ " . number_format($rentas,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($otros,2) . "</td><td align=\"center\">+</td><td align=\"center\">$ " . number_format($interes,2) . "</td><td align=\"center\">=</td><td align=\"center\"><strong>$ " . number_format(($rentas+$otros+$interes),2) . "</strong></td></tr></table>";
				$html .= "</td></tr>";

				$html .= "</table></td></tr>";
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


			$html .= "<div id=\"destinatario\">Referencia: " .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "</div>";
    			$html .= "<div id=\"tabla\">";

			$Cabeceratabla="<tr ><th id=\"tabla_encabezadoinquilino\">Contrato: </th><td id=\"tabla_datosinquilino\" colspan=\"2\">" . $row["elidcontrato"]  . " </td></tr>";
			$Cabeceratabla .="<tr ><th id=\"tabla_encabezadoinquilino\">Inquilino: </th><td id=\"tabla_datosinquilino\"  colspan=\"2\">" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . " </td></tr>";
			$Cabeceratabla .="<tr><th id=\"tabla_encabezadoinquilino\">Inmueble: </th><td id=\"tabla_datosinquilino\"  colspan=\"2\">" .   $row["calle"] . " No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"] . " Deleg/Mun. ". $row["delmun"] . " C.P. " . $row["cp"]  .  " </td></tr>";
			$Cabeceratabla .="<tr><th id=\"tabla_encabezadoinquilino\">Obligado Solidario: </th><td id=\"tabla_datosinquilino\"  colspan=\"2\">" . $row["fnombre"] . " " . $row["fnombre2"] . " " . $row["fapaterno"] . " " . $row["famaterno"]  . "</td></tr>";
			//echo "\n<tr><td><br><table border=\"1\" width=\"100%\">$Cabeceratabla<tr ><th>Contrato</th><th>Nombre</th><th>Fecha nat. pago</th><th>Concepto</th><th>Cantidad</th></tr>\n";
			$html .= "\n<tr ><td width=\"631\"><br><table border=\"1\" width=\"100%\">$Cabeceratabla<tr id=\"tabla_fecha\"><th >Fecha nat. pago</th><th>Concepto</th><th>Cantidad</th></tr>\n";
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


		//echo "<tr><td>" . $row["elidcontrato"] . "</td><td>" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " .$row["amaterno"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>$concepto</td><td align=\"right\">" . ($row["cantidad"] + $row["iva"]) . "</td></tr>\n";
		$html .= "<tr ><td id=\"tabla_fecha\">" . $row["fechanaturalpago"] . "</td><td>$concepto<br><strong>" . $row["observaciones"] . "</strong></td><td align=\"right\">$ " . number_format($Pagado,2) . "</td></tr>\n";
		
		$imail=$row["emaili"];
		if($row["retraso"] >30)
		{
			if (is_null($row["emailf"])!=true)
			{
				$fmail=$row["emailf"];
			}
		
		}
		

	}
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
	$html .= "    Despacho Padilla &amp; Bujalil <br><img src='cid:footer'></div><br>";

	$html .= "  </tr>";
	$html .= "</table>";
	$html .= "</body>";
	$html .= "</html>";




	$mensaje = CambiaAcentosaHTML($html);
	
	
	$correoe=$imail;
	if ($fmail != "")
	{
		$correoe .= "," . $fmail;
	}
	
	echo $correoe;
	$esok=$enviocorreo->enviarp($correoe, "Pendiente de pago", $mensaje);

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>
