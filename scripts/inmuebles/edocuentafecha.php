<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$accion = @$_GET["contrato"];
$idhistoria= @$_GET["idhistoria"];
$fecha= @$_GET["fecha"];
$prueba = New Calendario;
$fondo=" class='Fondo' ";
$fondot1=" class='Fondot1' ";
$fondot2=" class='Fondot2' ";
$clasef="";
//$clasef=$fondo;
$cambio = "";

$misesion = new sessiones;



if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='edoscuentainmfecha.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta']  . "/" . $row['archivo'];
		$ruta= $row['ruta'] ;
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}


	if ($priv[0]!='1')
	{
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}



	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}



if ($accion){

	
	
	if($idhistoria)
	{
		$sql="update historia set fechapago='$fecha' where idhistoria = $idhistoria";
		$result1 = @mysql_query ($sql);
		
		echo $sql="update edoduenio set fechaedo = '$fecha' where idhistoria =$idhistoria";
		$result1 = @mysql_query ($sql);
	
	}
	

	//$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno,inquilino.tel as telinq,tipocobro, historia.fechagenerado, historia.fechanaturalpago, historia.fechagracia, historia.fechapago, historia.cantidad, aplicado, historia.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, inmueble.estado, inmueble.pais, inmueble.tel as telinm, historia.iva as ivah,aplicado, condonado FROM contrato, cobros, inquilino, inmueble, tipocobro,historia, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $accion and inmueble.idestado =estado.idestado and inmueble.idpais=pais.idpais order by fechanaturalpago, fechapago, aplicado";

	$sql="select tipocobro, (b.cantidad + b.iva) suma,(b.interes *100) elinteres from contrato c, cobros b, tipocobro t where c.idcontrato = b.idcontrato and b.idtipocobro = t.idtipocobro and b.idperiodo<>1 and c.idcontrato = $accion";

	$listacobros="<table border='1' ><tr class='Cabecera'><th>Concepto</th><th>Cantidad</th><th>Interes</th></tr>";

	$result1 = @mysql_query ($sql);
	while ($row = mysql_fetch_array($result1))
	{
		$listacobros .="<tr><td class='Cabecera'>" . $row["tipocobro"]  ."</td><td align='right'>$" .  $row["suma"]  . "</td><td align='right'>" .  $row["elinteres"]  . "%</td></tr>";

	}
	$listacobros .="</table>";

	//$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as telinq,tipocobro, historia.fechagenerado, historia.fechanaturalpago, historia.fechagracia, historia.fechapago, historia.cantidad, aplicado, historia.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, inmueble.estado, inmueble.pais, inmueble.tel as telinm, historia.iva as ivah,aplicado, condonado, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel FROM contrato, cobros, inquilino, inmueble, tipocobro,historia, fiador WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $accion and contrato.idfiador=fiador.idfiador order by fechanaturalpago, tipocobro,interes,fechagenerado,fechapago, aplicado";
	$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as telinq,tipocobro, historia.fechagenerado, historia.fechanaturalpago, historia.fechagracia, historia.fechapago, historia.cantidad, aplicado, historia.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, estado, pais, inmueble.tel as telinm, historia.iva as ivah,aplicado, condonado, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, historia.notas as hnotas, fiador.email as emailf, notanc, idhistoria FROM contrato, cobros, inquilino, inmueble, tipocobro,historia, fiador, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $accion and contrato.idfiador=fiador.idfiador and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais order by fechanaturalpago, tipocobro,interes,fechagenerado,fechapago, aplicado";



	$result = @mysql_query ($sql);
	$Datos = 0;
	$cabecera="";
	$historia = "";
	$suma = 0;
	$grupoint=0;
	$tablainterna="";
	$tablalisto="";
	$concepto="";
	$operacion="";
	$auxt=0;
	$principio="";
	$principioa="";
	$fechaa="";
	$fechab="";
	$partea="";
	$parteb="";
	$conceptob="";
	$vcambio=0;
	$idhistoria="";

	$claseft=$fondot1;
	setlocale(LC_MONETARY, 'en_US');

	while ($row = mysql_fetch_array($result))
	{
		//echo $auxt;

		if($cambio=="")
		{
			$cambio=$row["fechanaturalpago"];
			$clasef=$fondo;
			$claseft=$fondot1;

		}
		if($cambio!=$row["fechanaturalpago"])
		{
			$cambio=$row["fechanaturalpago"];
			$vcambio=1;
			if($clasef==$fondo)
			{
				$clasef="";
				$claseft=$fondot2;
			}
			else
			{
				$clasef=$fondo;
				$claseft=$fondot1;
			}

		}




		if($Datos==0)
		{
			//$cabecera = "<center><h2>Estado de cuenta contrato No. $accion</h2>";
			$cabecera .= "<table border = \"1\">";
			$cabecera .= "<tr><td class='Cabecera'>Inquilino</td><td>" .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "(Tels. " . $row["telinq"] . ", " . $row["telinm"] . ")</td></tr>\n";
			$direccion =$row["calle"] . " No. " . $row["numeroext"] . " " . $row["numeroint"] . " COL." . $row["colonia"] . " Alc./Mun." . $row["delmun"] . " C.P." . $row["cp"] . " " . $row["pais"] . " " . $row["estado"];
			$cabecera .= "<tr><td class='Cabecera'>Direcci&oacute;n</td><td>$direccion </td></tr>\n";
			$elfiadorh = $row["fnombre"] . " " . $row["fnombre2"] . " " . $row["fapaterno"] . " " . $row["famaterno"]  . " (Tel. " . $row["ftel"] . ", email: " . $row["emailf"] . ")";
			$cabecera .= "<tr><td class='Cabecera'>Obligado solidario</td><td>$elfiadorh </td></tr>\n";

			$Datos=1;


		}

		$estado="PENDIENTE";


		if (is_null($row["vencido"])==false and $row["vencido"]==1)
		{

			$estado="PENDIENTE";

		}

		if (is_null($row["aplicado"])==false and $row["aplicado"]==1)
		{

			//$estado="ABONO";
			//$estado=$row["hnotas"];

			if(is_null($row["hnotas"])==true)
			{
				$estado="PAGADO";
			}
			else
			{
				$estado=$row["hnotas"];
			}

			if($row["cantidad"] <0)
			{
				$estado="CONDONADO";
			}
		}
		else
		{

			$suma += $row["cantidad"] + $row["ivah"] ;
		}

		if (is_null($row["condonado"])==false and $row["condonado"]==1)
		{

			$estado="CARGO GENERADO";

		}



		$conceptob= $row["tipocobro"] ;
		$fechaa=$prueba->formatofecha($row["fechanaturalpago"]);
		$fechab=$prueba->formatofecha($row["fechagracia"]);
		$partea="<tr $clasef>";
		$parteb="<td>\n<table border ='1' $claseft >";
		
		$notacreditot="";
		
		if($row["notanc"])
		{
			$notacreditot= "  (N.C.:" . $row["notanc"] . ")";
		}

		if ($concepto!=$row["tipocobro"] || $vcambio==1)
		{
			if($vcambio==1)
			{
				$vcambio=0;
			}
			$concepto = $row["tipocobro"];
			$conceptoa=$concepto ;
			//$concepto = $row["tipocobro"];
			$operacion = "PAGO" . $notacreditot ;


			//$principioa=$principio;
			//$principio="<tr $clasef><td align='center'>" . $prueba->formatofecha($row["fechanaturalpago"])  . "</td><td align='center'>" . $prueba->formatofecha($row["fechagracia"]) . "</td><td>$conceptoa</td>";

			if($auxt==0)
			{


				$conceptoa=$row["tipocobro"]  . $notacreditot;
				$principio="<tr $clasef><td align='center'>" . $prueba->formatofecha($row["fechanaturalpago"])  . "</td><td align='center'>" . $prueba->formatofecha($row["fechagracia"]) . "</td><td>$conceptoa</td><td>\n<table border ='1' $claseft >";
				$auxt=1;
				$tablainterna="";
			}
			else
			{



				$principioa=$principio;


				//echo $clasef . " :: " . $claseft . "<br>";
				$tablalisto ="$tablainterna";
				//$tablalisto ="\n<table border ='1' $claseft >$tablainterna";
				$principio="<tr $clasef><td align='center'>" . $prueba->formatofecha($row["fechanaturalpago"])  . "</td><td align='center'>" . $prueba->formatofecha($row["fechagracia"]) . "</td><td>$conceptoa</td><td>\n<table border ='1' $claseft >";
				/*
				$fechaa=$prueba->formatofecha($row["fechanaturalpago"]);
				$fechab=$prueba->formatofecha($row["fechagracia"]);
				$partea="<tr $clasef>";
				$parteb="<td>\n<table border ='1' $claseft >";
		*/
			}


		}
		else
		{
			$operacion = "PAGO" . $notacreditot ;

		}


		if (is_null($row["interes"])==false and $row["interes"]==1)
		{

			$operacion = "INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . "(" . $row["tipocobro"] . ")" . $notacreditot ;


		}
		if(is_null($row["fechapago"]))
		{
			$fechapago="&nbsp;";

		}
		else
		{
			$fechapago=$prueba->formatofecha($row["fechapago"]);
		}




		if ($row["aplicado"]==false )
		{
			$Pagado=($row["cantidad"] + $row["ivah"]);

		}
		else
		{
			$Pagado=$row["cantidad"] ;
		}





		if($tablalisto!="")
		{


			//$tablalisto .="</table>\n";

			$historia .= "$principioa $tablalisto</table> </td></tr>\n";
			//$historia .= "<tr $clasef><td align='center'>" . $prueba->formatofecha($row["fechanaturalpago"])  . "</td><td align='center'>" . $prueba->formatofecha($row["fechagracia"]) . "</td><td>$conceptoa</td><td >$tablalisto</td>\n";
			$tablalisto ="";

			$tablainterna = "<tr><td width='200'>$operacion</td><td align='right' width='100'>$ " . number_format($Pagado,2)  . "</td><td align='center' width='100'> $fechapago</td><td width='100'> $estado</td><td width='100' align='center'><input type='text' name='i" . $row["idhistoria"] . "' size='10'><br><font size=1>(aaaa-mm-dd)</font> <input type='button' value='Ap' onClick=\"window.location='edocuentafecha.php?contrato=$accion&idhistoria=" . $row["idhistoria"] . "&fecha=' + i" . $row["idhistoria"] . ".value\"> </td></tr>\n";



		}
		else
		{
			$tablainterna .= "<tr><td width='200'>$operacion</td><td align='right' width='100'>$ " . number_format($Pagado,2)  . "</td><td align='center' width='100'> $fechapago</td><td width='100'>$estado</td><td width='100' align='center'><input type='text' name='i" . $row["idhistoria"] . "' size='10'><br><font size=1>(aaaa-mm-dd)</font> <input type='button' value='Ap' onClick=\"window.location='edocuentafecha.php?contrato=$accion&idhistoria=" . $row["idhistoria"] . "&fecha=' + i" . $row["idhistoria"] . ".value\"\"> </td></tr>\n";
		}





	}

	if($tablainterna!="")
	{



		$principio="$partea<td align='center'>$fechaa</td><td align='center'>$fechab</td><td>$conceptob</td>$parteb";


		$historia .= "$principio $tablainterna</table> </td></tr>\n";

	}


	$historia .= "</table>";
	$cabecera .= "<tr><td class='Cabecera'>Adeudo pendiente</td><td>$ " . number_format($suma,2) . "</td></tr>";
	$cabecera .= "</table></center>\n";
	$cabecera = "<center><h2>Estado de cuenta contrato No. $accion</h2>\n<table border='0'> <tr><td>$cabecera</td><td>&nbsp;&nbsp;</td><td valign='top'>$listacobros</td></tr></table>\n";
	$cabecera .= "<br>\n<table border = \"1\" width=\"100%\">\n\t<tr class='Cabecera'><th>Fecha Pago</th><th>Fecha Gracia</th><th>Concepto</th><th width='600'>Detalle<br>\n<table border ='1'  class='Cabecera'><tr><th width='200'>Operaci&oacute;n</th><th width='100'>Cantidad</th><th width='100'>F.Pagado</th><th width='100'>Estado</th><th width='100'>C. fecha</th></tr></table>\n</th></tr>\n";
	//$cabecera .= "<tr class='Cabecera'><th>Fecha Pago</th><th>Fecha Gracia</th><th>Concepto</th><th>Cantidad</th><th>Fecha Pagado</th><th>Estado</th></tr>\n";
	//echo $cabecera . $historia;

echo <<< elhtml
<html>
<head><title>Estado de cuenta</title></head>
<link rel="stylesheet" type="text/css" href="../../estilos/estilos.css">
<body>
<table border="0" width="100%" >
<tr>
	<td><img src="../../imagenes/logo.png" ></td>
	<td align='center'>
	&nbsp;
	</td>
</tr>
</table>
<form>


elhtml;


	echo CambiaAcentosaHTML($cabecera . $historia);

echo "</form></body></html>";

}


}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}

?>
