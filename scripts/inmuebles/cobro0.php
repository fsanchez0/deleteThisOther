<?php
// Proceso para el asistente
include '../general/correoclass.php';
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$enviocorreo = New correo;
$accion = @$_GET["paso"];
$idcontrato = @$_GET["idcontrato"];
$efectivo = @$_GET["efectivo"];
$cambio=@$_GET["cambio"];

$fechas = New Calendario;
$mensaje="";
$diferencia=0;
//$fechas->DatosConexion('localhost','root','','bujalil');
$misesion = new sessiones;


function hacernombre($elidh, $elvalorh)
{
	//Genera uan cadena para un nombre donde los valores con punto los pasa a _ para no irumpir con la sintaxis de HTML y javascript
	$nombrev= (string)$elvalorh;
	$nombrev = split("[.]",$nombrev);
	if(count($nombrev)>1)
	{
		$nombrev = $nombrev[0] . "_" . $nombrev[1];	
	}
	else
	{
		$nombrev=(string)$elvalorh;
	}
	return (string)$elidh . "_" .  $nombrev;

}


function rehacervalor($elnombre)
{
	//regenera un numero que fue puesto como nombre
	$datos=split("[_]", $elnomre);
	if(count($datos)>2)
	{
		return (double)($datos[1] . "." . $datos[2]);
	}
	else
	{
		return (double)($datos[1]);
	}

}


if($misesion->verifica_sesion()=="no")
{
	echo "A&uacute;n no se ha firmado con el servidor";
	exit;
}



	$sql="select * from submodulo where archivo ='cobro0.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
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







$fincontrato="";
//echo $accion;

switch ($accion)
{
//**************************** PASO 1 ***************************
case 0: //PASO 1 : Busqueda de los pendientes

echo <<< paso1


<script language="javascript" type="text/javascript">
//var efect;
efect=0;

</script>
<div name="cobro" id="cobro" align="center">
<h1>Cobrar</h1>
<form>
<table>
<tr>
<td><b>Paso1</b></td><td>Paso2</td><td>Paso3</td><td>Paso4</td>
</tr>
<tr>

<td>Nombre </td><td colspan="3"><input type="text" name="nombreb" id="nombre"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda3.php','busquedacobro', 'btn=1&dato='+nombreb.value);efect=0;"> </td>
</tr>
<tr>
<td>Inmueble</td><td colspan="3"> <input type="text" name="inmuebleb" id="inmueble"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda3.php','busquedacobro', 'btn=2&dato='+inmuebleb.value);efect=0;"></td>
</tr>
<tr>
<td>No. contrato </td><td colspan="3"><input type="text" name="nocontratob" id="nocontrato"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda3.php','busquedacobro', 'btn=3&dato='+ nocontratob.value);efect=0;"></td>
</tr>
</table>
</form>
<div name="busquedacobro" id="busquedacobro" class="scroll">

</div>
</div>
paso1;
	break;
//***************************************FIN PASO 1 ***********************************************

//**************************************** PASO 2 *************************************************
case 1:  //PASO 2: Obtiene el adeudo del inquilino y solicita el pago

$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato=$idcontrato";
$result = @mysql_query ($sql);
$idc="";
$nombre="";
$suma = 0;
while ($row = mysql_fetch_array($result))
{
		$idc=$row["idcontrato"];
		$nombre =  CambiaAcentosaHTML($row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]);
}


$sql = "select * from historia where idcontrato=$idc and aplicado = false or isnull(aplicado) = true";
$result = @mysql_query ($sql);
while ($row = mysql_fetch_array($result))
{
		$suma+=$row["cantidad"] + $row["iva"];
}

$suma="$ " . number_format($suma,2);

echo <<< paso2


<div name="cobro" id="cobro">
<h1>Cobrar</h1>
<form>
<table>
<tr>
<td>Paso1</td><td><b>Paso2</b></td><td>Paso3</td><td>Paso4</td>
</tr>
<tr>
<td>Contrato</td><td colspan="3"><input type="hidden" name="contrato" value="$idcontrato">$idc </td>
</tr>
<tr>
<td>Nombre </td><td colspan="3">  $nombre</td>
</tr>
<tr>
<td>Por pagar</td><td colspan="3">$suma</td>
</tr>
<tr>
<td>$ Recibido</td><td colspan="3"><input type="text" name="recibido" id="recibido" onBlur="efect=this.value;" > </td>
</tr>
<tr>
<!--<td colspan="3" align="center"><input type="button" value="Aceptar" onClick = "pasosCobro(2,$idcontrato,efect);"> </td>-->
<td colspan="3" align="center"><input type="button" value="Aceptar" onClick = "cargarSeccion('$dirscript','contenido', 'paso=2&idcontrato=$idcontrato&efectivo='+efect);"> </td>
</tr>
</form>
</table>
</div>
paso2;
	break;
//************************************** FIN PASO 2 ******************************************************************

//************************************** PASO 3 *******************************************************************
case 2: //Muestra los pendientes de pagos que ser?n afectados

$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato=$idcontrato";
$result = @mysql_query ($sql);
$idc="";
$nombre="";
$suma = 0;
$controlesjava="";
//Obtengo el nombre del inquilino
while ($row = mysql_fetch_array($result))
{
		$idc=$row["idcontrato"];
		$nombre =  CambiaAcentosaHTML($row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]);
}
//Obtengo los pendientes de pagos hasta el dia de hoy
$sql = "select idhistoria, historia.cantidad as cantidadh, cobros.cantidad as cantidadc, historia.iva as ivah, calle, numeroext, numeroint,fechanaturalpago, fechagenerado,tipocobro, historia.interes as interesh from historia, cobros, contrato, inmueble, tipocobro where historia.idcontrato=contrato.idcontrato and contrato.idinmueble=inmueble.idinmueble and historia.idcobros=cobros.idcobros and historia.idcontrato=$idc and cobros.idtipocobro=tipocobro.idtipocobro and (aplicado = false or isnull(aplicado)=true) and fechagracia<='" . date("Y")  . "-" . date("m") . "-" . date("d") . "' order by historia.idprioridad, historia.fechagracia";
$result = @mysql_query ($sql);
$norecibos=0;
$difdinamica=$efectivo;
$ultimo=0;
$suma=0;
$direccioninmueble ="";
//Verifico cuales estan pendientes y genero los pagos a los que se les debe de cobrar
$listapagos="<div id=\"porconfirmar\"><table border=\"1\"><tr><td>Fecha Pago</td><td>Descripci&oacute;n</td><td>Cantidad</td><td>Confirmaci&oacute;n</td></tr>";
if($result)
{

//Muestro todos los pagos que se deben de hacer hasta la fecha de hoy, solo falta darle formato, colocar los conceptos del estado de cuenta
//y el metodo para recalcular el efectivo que se va a recibir cuando cambien el valor de alguno de la lista
	while ($row = mysql_fetch_array($result))
	{
		//echo $row["cantidadh"] . " id " . $row["idhistoria"] . " ya<br>";
		$suma+=$row["cantidadh"] + $row["ivah"];

		//Va aplicando los pagos y va descontando del dinero depositado
		//$difdinamica=$difdinamica - ($row["cantidadh"] + $row["ivah"]);

		//Verifica si es el ultimo por aplicar
		
		$concepto = CambiaAcentosaHTML($row["tipocobro"]);
			
		if (is_null($row["interesh"])==false and $row["interesh"]==1)
		{
			
			$concepto = "INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . "(" . CambiaAcentosaHTML($row["tipocobro"]) . ")";
			
		}
		
		
		
		
		if($ultimo==0)
		{



			$difdinamica = $difdinamica - ($row["cantidadh"] + $row["ivah"]);
			if ($difdinamica<0)
			{
				//Cuando la diferencia es menor que cero, significa que no alcanz? a pagar
				//por completo a un concepto y marca como ultimo en aplicar
				$ultimo=1;
				//$norecibos++;
				//$matrecibos[$norecibos][1]=$row["idhistoria"]; //Id del pagado
				//$matrecibos[$norecibos][2]=(-1 * $difdinamica); //lo que falta por pagar
				//$matrecibos[$norecibos][3]=0;					//para asber que no fue pagado en forma completa
				
				//$listapagos .= "<tr><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . ($row["cantidadh"] + $row["ivah"]) . "</td><td><input type=\"text\" name=\"c_" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "\" id=\"" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "\"  value=\"" . ($difdinamica + $row["cantidadh"] + $row["ivah"] ) . "\" onChange=\"compruevasuma('porconfirmar','" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "', 'totalconfirmar')\"></td></tr>";
				$listapagos .= "<tr><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . ($row["cantidadh"] + $row["ivah"]) . "</td><td><input type=\"text\" name=\"c_" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "\" id=\"" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "\"  value=\"" . ($difdinamica + $row["cantidadh"] + $row["ivah"] ) . "\" onChange=\"compruevasuma('porconfirmar','" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "', 'totalconfirmar')\"></td></tr>";
				
				$direccioninmueble ="Inmueble: " . $row["calle"] . " " . $row["numeroext"]  . " " . $row["numeroint"] . " \r\n";
				$difdinamica=0;
				//$controlesjava.="&c_" . $row["idhistoria"] . "=' + c_" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"]) . ".value + '";
				$controlesjava.="&c_" . $row["idhistoria"] . "=' + c_" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"])) . ".value + '";
			}

			else
			{
				//Aplica el pago y lo agrega a la matriz
				//$norecibos++;
				//$matrecibos[$norecibos][1]=$row["idhistoria"]; //Id del pagado
				//$matrecibos[$norecibos][2]=$row["cantidadh"] + $row["ivah"];  //lo que falta por pagar
				//$matrecibos[$norecibos][3]=1;				   //para saber si se pago por completo
				
				//$listapagos .= "<tr><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . ($row["cantidadh"] + $row["ivah"]) . "</td><td><input type=\"text\" name=\"c_" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "\" id=\"" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "\" value=\"" . ($row["cantidadh"] + $row["ivah"]) . "\" onChange=\"compruevasuma('porconfirmar','" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "', 'totalconfirmar')\"></td></tr>";
				$listapagos .= "<tr><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . ($row["cantidadh"] + $row["ivah"]) . "</td><td><input type=\"text\" name=\"c_" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "\" id=\"" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "\" value=\"" . ($row["cantidadh"] + $row["ivah"]) . "\" onChange=\"compruevasuma('porconfirmar','" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "', 'totalconfirmar')\"></td></tr>";
				
				//$controlesjava.="&c_" . $row["idhistoria"] . "=' + c_" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"]) . ".value + '";
				$controlesjava.="&c_" . $row["idhistoria"] . "=' + c_" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"])) . ".value + '";
				if($difdinamica==0)
				{
					$ultimo=1;
					$direccioninmueble =CambiaAcentosaHTML("Inmueble: " . $row["calle"] . " " . $row["numeroext"]  . " " . $row["numeroint"] . " \r\n");					
				}

			}
		}
		else
		{
			//$listapagos .= "<tr><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . ($row["cantidadh"] + $row["ivah"]) . "</td><td><input type=\"text\" name=\"c_" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "\" id=\"" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "\" onChange=\"compruevasuma('porconfirmar','" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "', 'totalconfirmar')\"></td></tr>";
			//$controlesjava.="&c_" . $row["idhistoria"] . "=' + c_" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"]) . ".value + '";
			
			$listapagos .= "<tr><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . ($row["cantidadh"] + $row["ivah"]) . "</td><td><input type=\"text\" name=\"c_" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "\" id=\"" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "\" onChange=\"compruevasuma('porconfirmar','" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "', 'totalconfirmar')\"></td></tr>";
			$controlesjava.="&c_" . $row["idhistoria"] . "=' + c_" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"])) . ".value + '";
		}
	}
}
$listapagos.="</table><input type=\"hidden\" name=\"vuelto\" id=\"vuelto\" value=\"$difdinamica\"><input type=\"hidden\" name=\"totalconfirmar\" id=\"totalconfirmar\" value=\"$efectivo\"></div>";

$suma="$ " . number_format($suma,2);
$efectivo="$ " . number_format($efectivo,2);
//$diferencia="$ " . number_format($diferencia,2);
//$edocuenta="window.open( 'scripts/edocuenta.php?contrato=" . $idcontrato . "');";


//solo falta agregar el boton de env?o para procesar el paso 4 que ser? el que haga los cargos correspondientes
//a los elementos seleccionados con la cantidad colocada.
echo <<< paso3

<form>
<h1 align="center">Cobrar</h1>
<center>
<table>
<tr>
<td>Paso1</td><td>Paso2</td><td><b>Paso3</b></td><td>Paso4</td>
</tr>
<tr>
<td>Contrato</td><td colspan="3"><input type="hidden" name="contrato" value="$idcontrato">$idc </td>
</tr>
<tr>
<td>Nombre </td><td colspan="3"> $nombre</td>
</tr>
<tr>
<td>Por pagar</td><td colspan="3">$suma</td>
</tr>
<tr>
<td>$ Recibido</td><td colspan="3"><h2>$efectivo</h2></td>
</tr>
<tr>

<td colspan="3" align="center"><input type="button" value="Aplicar" onClick = "cargarSeccion('$dirscript','contenido', 'paso=3&idcontrato=$idcontrato&efectivo='+efect + '&cambio=' + cambio.value + '$controlesjava');this.disabled =true"> 
<input type="hidden" name="cambio" value="$diferencia">

</td>

</tr>
</table>
</center>
$listapagos
</form>
<!--cargarSeccion('scripts/cobro.php','contenido', 'paso=3&idcontrato=$idcontrato&efectivo='+efect + '&cambio=' + cambio.value + '$controlesjava');
<tr><tr>
<td>Cambio</td><td colspan="3"><h1>$diferencia</h1></td>
</tr>
<td colspan="3" align="center">
<!--<p><input type="button" value="ReciboF/ReciboNF concepto 1"></p>
<p><input type="button" value="ReciboF/ReciboNF concepto 2"></p>
<p><input type="button" value="ReciboF/ReciboNF concepto 3"></p> 
-->


paso3;



	break;
//******************************** FIN PASO 3 ***************************************************************


//*************************************** INICIO PASO 4 ************************************************************
case 3:  //PASO 3: Realiza el pago y muestra el resultado, as? como los botones para crear recibos.

//Ahora, aqu? se va a colocar la lista de solo los pagados el dia de hoy, con su descripci?n como en el paso 3, se deben de
//recibir variables del Post que comiencen con c_ ya que el resto es el id que se tiene queafectar con el valor que traiga en el la variable
//Hay que reordenar los botones de los recibos y colocar el metodo para notificaci?n por correo en una ventana independiente al terminar de cargar
//los resultados y no afecte la funci?n operativa de la pagina.  Esto nos permitir? generar los recibos en el caso de que no se pueda enviar el correo

$cambio=@$_GET["cambio"];


$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato=$idcontrato";
$result = @mysql_query ($sql);
$idc="";
$nombre="";
$suma = 0;
$final="";
while ($row = mysql_fetch_array($result))
{
		$idc=$row["idcontrato"];
		$nombre =  CambiaAcentosaHTML($row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]);
}

$sql = "select SUM(historia.cantidad + historia.iva) as suma from historia, cobros, contrato, inmueble  where historia.idcontrato=contrato.idcontrato and contrato.idinmueble=inmueble.idinmueble and historia.idcobros=cobros.idcobros and historia.idcontrato=$idc and (aplicado = false or isnull(aplicado)=true) and fechagracia<='" . date("Y")  . "-" . date("m") . "-" . date("d") . "' order by historia.idprioridad, historia.fechagracia";
$result = @mysql_query ($sql);
while ($row = mysql_fetch_array($result))
{
		$suma=$row["suma"];		
}

$sql = "select  calle, numeroext, numeroint from contrato, inmueble where contrato.idcontrato=$idc and contrato.idinmueble=inmueble.idinmueble";
$result = @mysql_query ($sql);
//$norecibos=0;
//$difdinamica=$efectivo;
//$ultimo=0;
//$suma=0;
$direccioninmueble ="";
if($result)
{

	while ($row = mysql_fetch_array($result))
	{
		
		
		$direccioninmueble =CambiaAcentosaHTML("Inmueble: " . $row["calle"] . " " . $row["numeroext"]  . " " . $row["numeroint"] . " \r\n");
			
	}
}

$final="<table border =\"1\"><tr><th>Fecha de pago</th><th>Concepto</th><th>Cantidad</th><th>Estado</th><th>Recibo</th></tr>";
foreach($_GET as $campo => $valor)
{
	
	if($valor=="" || $valor=="0"){continue;}
	//echo "<br>$campo = $valor<br>";
	$dhistoria=split("[_]",$campo);
	if(count($dhistoria)>1)
	{
		
		$sql = "select tipocobro, fechanaturalpago, numero, idmargen, historia.idcontrato as idc, cobros.idcobros as idcob, cobros.cantidad as ccantidad, cobros.idprioridad as idcprioridad, historia.interes as inter, cobros.iva as ivac, vencido, concluido, fechatermino, historia.cantidad as hcantidad, historia.iva as hiva from historia, cobros, tipocobro, periodo, contrato where historia.idcobros=cobros.idcobros and cobros.idtipocobro=tipocobro.idtipocobro and cobros.idperiodo=periodo.idperiodo and historia.idcontrato = contrato.idcontrato and idhistoria="  . $dhistoria[1];
		//echo $sql . "<br>";
		$result = @mysql_query ($sql);
		while ($row = mysql_fetch_array($result))
		{
		
			//verificar si el dato de cantidad es = a la suma de cantidad e iva
			//si es as?, el proceso normal 			
			if((string)($row["hiva"] + $row["hcantidad"])==$valor)
			{
				$funcionrecibo="";
				$addtext="";
				if (is_null($row["inter"])==true || $row["inter"]==false )
				{
					$funcionrecibo="nuevaVP(" . $dhistoria[1] . ",1)";
										
				}
				else			
				{
					$funcionrecibo="nuevaVP(" . $dhistoria[1] . ",3)";
					$addtext=" -interes- ";
				}
				//echo '<p><input type="button" value="' . $row["tipocobro"] . $addtext . ' (Recibo F)" onClick="' . $funcionrecibo . '"> </p>';
				
				//Crear el nuevo pago si se pago por completo
				$fechagenerado = date("Y") . "-" . date("m") . "-" . date("d");				
				
				$final .="<tr><td>$fechagenerado</td><TD>" . CambiaAcentosaHTML($row["tipocobro"]) . " $addtext (F.N.P. " . CambiaAcentosaHTML($row["fechanaturalpago"])  . ")</td><td>" . ($row["hcantidad"]+$row["hiva"]) . "</td><td>Pagado</td><td>";
				$final .= '<p><input type="button" value="' . CambiaAcentosaHTML($row["tipocobro"]) . $addtext . ' (Recibo F)" onClick="' . $funcionrecibo . '"> </p>';
				$final .="</td></tr>";

									
				$fechagsistema =mktime(0,0,0,substr($row["fechanaturalpago"], 5, 2),substr($row["fechanaturalpago"], 8, 2),substr($row["fechanaturalpago"], 0, 4));

				//echo $row["fechanaturalpago"] . "<br>";				
						
				$fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
						
				//echo $fechanaturalpago . "<br>";
				
//if($fechanaturalpago!=0)
				if($fechanaturalpago!=$row["fechanaturalpago"])
				{
					//echo "<p>verifico si no es interes</p>";
					if(is_null($row["inter"])==true || $row["inter"]==false  )
					{
						//echo "<p>verifico si no esta vencido o terminado</p>";
						if($row["vencido"]==false && ($row["concluido"]==false || is_null($row["concluido"])==true))
						{
						
						
							$fechagracia = $fechas->fechagracia($fechanaturalpago);
							
							//echo $fechagracia . "<br>";
						
							//echo "<p>verifico t?rmino de contrato</p>"; 
							if (mktime(0,0,0,substr($fechagracia, 5, 2),substr($fechagracia, 8, 2),substr($fechagracia, 0, 4)) < mktime(0,0,0,substr($row["fechatermino"], 5, 2),substr($row["fechatermino"], 8, 2),substr($row["fechatermino"], 0, 4)))
							{
					
								$sql1="insert into historia (idcontrato, idcobros, fechagenerado, fechanaturalpago, fechagracia,cantidad,idusuario,idprioridad,notas,fechavencimiento, iva,condonado) ";
								//$sql1 .= " values (" . $row["idc"] . ", " . $row["idcob"] . ",'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "'," . number_format($row["ccantidad"],2,".","") . ", " . $misesion->usuario . "," . $row ["idcprioridad"] . ",'" . $row["tipocobro"] . "','" . $fechagracia . "'," . number_format($row["ivac"],2,".","") . ",false)";
								$sql1 .= " values (" . $row["idc"] . ", " . $row["idcob"] . ",'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "'," . number_format($row["ccantidad"],2,".","") . ", " . $misesion->usuario . "," . $row ["idcprioridad"] . ",'PENDIENTE','" . $fechagracia . "'," . number_format($row["ivac"],2,".","") . ",false)";								
								
								//echo "<br><b>ejecuto en completos, siguiente pago</b><br>" . $sql1 . "<br>";
								//agrega siguiente pago, eliminado por cambio de buro
								//$result0 = mysql_query ($sql1);
							}
							else
							{


								if($row["ultimo"]==false || is_null($row["ultimo"])==true)
								{
									$sql1="insert into historia (idcontrato, idcobros, fechagenerado, fechanaturalpago, fechagracia,cantidad,idusuario,idprioridad,notas,fechavencimiento, iva,condonado) ";
									//$sql1 .= " values (" . $row["idc"] . ", " . $row["idcob"] . ",'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "'," . number_format($row["ccantidad"],2,".","") . ", " . $misesion->usuario . "," . $row ["idcprioridad"] . ",'" . $row["tipocobro"] . "','" . $fechagracia . "'," . number_format($row["ivac"],2,".","") . ",false)";
									$sql1 .= " values (" . $row["idc"] . ", " . $row["idcob"] . ",'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "'," . number_format($row["ccantidad"],2,".","") . ", " . $misesion->usuario . "," . $row ["idcprioridad"] . ",'PENDIENTE','" . $fechagracia . "'," . number_format($row["ivac"],2,".","") . ",false)";									
									//agrega siguiente pago, eliminado por cambio de buro
									//$result2 = @mysql_query ($sql2);
									$sql2 = "update contrato set ultimo = true where idcontrato = " . $row["idc"];
									$result2 = @mysql_query ($sql2);

								}
								else
								{
									$fincontrato="Se ha llegado a la fecha de t&eacute;rmino del contrato, no se agregar&aacute;n m&aacute;s pagos";
								}
							}
						
						}
					}
						
				}
				//$sql1 = "update historia set aplicado = true, fechapago='" . $fechagenerado . "', cantidad = " . number_format($valor,2,".",""). " where idhistoria=" . $dhistoria[1];
				$sql1 = "update historia set aplicado = true, fechapago='" . $fechagenerado . "', cantidad = " . number_format($valor,2,".",""). ", notas = 'LIQUIDADO' where idhistoria=" . $dhistoria[1];				
				
				
				//echo "<br><b>ejecuto en completos actualizo cantidad y aplico pago</b><br>" . $sql . "<br>";
				$result0 = mysql_query ($sql1);

						
			}//if
			else//para cuando no cumpla que sean iguales, significa que es menor
			{
				//echo "entre cuando en el campo $campo y el valor no es igual $valor=".($row["ivah"] + $row["hcantidad"]) . "<br>";
				$sql = "select historia.idhistoria as idhistoriah, historia.idcobros as idcobrosh, historia.idcontrato as idcontratoh, historia.idusuario as idusuarioh, historia.idprioridad as idprioridadh, fechagenerado, fechanaturalpago, fechagracia, fechapago, historia.cantidad as cantidadh, vencido, aplicado, notas, tipocobro,  historia.interes as inter, tipocobro, numero, idmargen, cobros.idcobros as idcob,cobros.cantidad as ccantidad, cobros.idprioridad as idcprioridad,fechavencimiento, historia.interes as hinteres, vencido, cobros.iva as ivac, historia.iva as ivah, fechatermino, concluido from contrato, historia, cobros, tipocobro, periodo where historia.idcobros=cobros.idcobros and cobros.idtipocobro=tipocobro.idtipocobro and cobros.idperiodo=periodo.idperiodo and historia.idcontrato = contrato.idcontrato and idhistoria=" . $dhistoria[1];
				
				//echo $sql . "<br>";
				
				$result0 = @mysql_query ($sql);
				while ($row0 = mysql_fetch_array($result0))
				{
				//Creo las variables con los elementos importantes por si es necesario crear un nuevo registro
				//por pago parcial
					$idcobro=$row0["idcobrosh"];
					$idcontrato=$row0["idcontratoh"];
					$idusuario=$row0["idusuarioh"];
					$idprioridad=$row0["idprioridadh"];
					$fechanaturalpago=$row0["fechanaturalpago"];
					$fechagracia=$row0["fechagracia"];
					$cantidad=$row0["cantidadh"] + $row0["ivah"];
					$fechavencimiento =$row0["fechavencimiento"];
					$hinteres =$row0["hinteres"];
					$vencido = $row0["vencido"];
					$notas=CambiaAcentosaHTML($row0["notas"]);
					//$iva=$row["ivah"];
					$fechagenerado = date("Y") . "-" . date("m") . "-" . date("d");
					
					//echo "Tengo todas las variables y ahora verifico valor : $valor <br>";
					//verifico si hubo ultimo para generar el bot?n y actualizar el registro
					
					//echo "Es diferente de 0<br>";
					//Creo la consluta para agregar un nuevo registro con los datos
					//nuevos de cobro
					//$sql1 = "insert into historia (idcobros, idcontrato, idusuario, idprioridad, fechagenerado, fechanaturalpago, fechagracia, cantidad, notas, vencido, aplicado,fechavencimiento, interes,condonado) values ($idcobro,$idcontrato," . $misesion->usuario . ",$idprioridad,'" . date('Y') . "-" . date('m') . "-" . date('d') . "','$fechanaturalpago', '$fechagracia'," . number_format(($cantidad - $valor),2,".","") . ", 'Diferencia del pago realizado', $vencido ,0 ,'$fechavencimiento', $hinteres, false)";
					$sql1 = "insert into historia (idcobros, idcontrato, idusuario, idprioridad, fechagenerado, fechanaturalpago, fechagracia, cantidad, notas, vencido, aplicado,fechavencimiento, interes,condonado) values ($idcobro,$idcontrato," . $misesion->usuario . ",$idprioridad,'" . date('Y') . "-" . date('m') . "-" . date('d') . "','$fechanaturalpago', '$fechagracia'," . number_format(($cantidad - $valor),2,".","") . ", 'PENDIENTE', $vencido ,0 ,'$fechavencimiento', $hinteres, false)";
					//echo $sql1 . "<br>";
					//echo "<br><b>ejecuto en ulti moregistro, siguiente pago si diferencia es menor que cero</b><br>" . $sql1 . "<br>";
					//Creo la consulta para actualizar el registro de historia de donde se pago una parte con la diferencia
					
					//******
					$estadopago="PARCIALIDAD, POR CUBRIR $" . number_format(($cantidad - $valor),2,".","");
					
					$sql2 = "update historia set cantidad =" . number_format($valor,2,".","") ." , aplicado = true, fechapago='" . $fechagenerado . "', notas = '$estadopago' where idhistoria = " . $dhistoria[1];
					//*****
					//echo "<br><b>ejecuto en ulti moregistro, actualizo por la diferencia que pago  es menor que cero</b><br>" . $sql2 . "<br>";
					//echo $sql2 . "<br>";
				
						//en el evento onClick debe de ir el procedimiento para impresion del recibo usando el id del historial
						
					//OJO, cuando se hace un pago parcial, es necesario actualizar los datos del registro para que
					//muester los datos correctos.
					$funcionrecibo="";
					$addtext="";
					if (is_null($row0["inter"])==true or $row0["inter"]==false )
					{
						$funcionrecibo="nuevaVP(" . $dhistoria . ",2)";
					}
					else			
					{
						$funcionrecibo="nuevaVP(" . $dhistoria . ",3)";
						$addtext=" -interes- ";
					}	
					//echo '<p><input type="button" value="' . $row0["tipocobro"] . $addtext .  '(Recibo NF)" onClick="' . $funcionrecibo . '"></p>';
					
					
					$final .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " $addtext (F.N.P. " . CambiaAcentosaHTML($row["fechanaturalpago"])  . ")</td><td>" . ($row["hcantidad"]+$row["hiva"]) . "</td><td>Parcial</td><td>";
					$final .= '<p><input type="button" value="' . CambiaAcentosaHTML($row0["tipocobro"]) . $addtext .  '(Recibo NF)" onClick="' . $funcionrecibo . '"></p>';
					$final .="</td></tr>";
					
					$result1 = mysql_query ($sql1);
					$result1 = mysql_query ($sql2);
					
				
				}
				
				
					
			
			}
			
		}//loop
		
	
	}


}

$final .= "</table>";

//echo $suma . " esta es la suma<br>";
//echo $efectivo . " esto es el efectivo <br>";
$saldo = $efectivo-$suma;
$edocuenta="window.open( '$ruta/edocuenta.php?contrato=" . $idcontrato . "');";

$suma="$ " . number_format($suma,2);
if($cambio==""){$cambio=0;};


$efectivo="$ " . number_format($efectivo,2);
$diferencia="$ " . number_format($cambio,2);
echo <<< paso3



<h1>Cobrar</h1>
<table>
<tr>
<td>Paso1</td><td>Paso2</td><td>Paso3</td><td><b>Paso4</b></td>
</tr>
<tr>
<td>Contrato</td><td colspan="3"><input type="hidden" name="contrato" value="$idcontrato">$idc </td>
</tr>
<tr>
<td>Inmueble </td><td colspan="3"> $direccioninmueble</td>
</tr>
<tr>
<td>Nombre </td><td colspan="3"> $nombre</td>
</tr>
<tr>
<td>Por pagar</td><td colspan="3">$suma</td>
</tr>
<tr>
<td>$ Recibido</td><td colspan="3"><h2>$efectivo</h2></td>
</tr>
<tr>
<td>Cambio</td><td colspan="3"><h1>$cambio</h1></td>
</tr>
<tr>
<td>Saldo</td><td colspan="3"><h1>$saldo</h1></td>
</tr>

<tr>
<td>Estado de Cuenta</td><td colspan="3"><input type="button" value="Ver Estado de cuenta" onClick="$edocuenta"/><br></td>
</tr>


<tr>
<td colspan="4" align="center">
<!--<p><input type="button" value="ReciboF/ReciboNF concepto 1"></p>
<p><input type="button" value="ReciboF/ReciboNF concepto 2"></p>
<p><input type="button" value="ReciboF/ReciboNF concepto 3"></p> -->
$final

paso3;


if($saldo>=0)
{
	$saldo = "Cambio de $ $saldo";

}
else
{
	$salto = -1 * $saldo;
	$saldo = "A&uacute;n debe $ $saldo";
}


$mensaje="Cobro el d?a " . date('d-M-Y') . " a las " . date('H:i')  . "\r\n<br>";
$mensaje .= "Contrato: $idc \r\n<br>";
$mensaje .= "Inquilino: $nombre \r\n<br>";
$mensaje .= $direccioninmueble ."\r\n<br>" ;
$mensaje .= "Pag?: $efectivo \r\n<br>";
$mensaje .= "$saldo";


echo "<p><font color=\"red\">$fincontrato</font></p>";
echo <<< interbotones
</td>
</tr>
</div>
</table>


interbotones;
//$enviocorreo->enviar("mizocotroco@hotmail.com", "Cobro realizado", $mensaje);
//$enviocorreo->enviar("miguelmp@prodigy.net.mx,lucero_cuevas@prodigy.net.mx,miguel_padilla@nextel.mx.blackberry.com,cemaj@prodigy.net.mx", "Cobro realizado", $mensaje);
$enviocorreo->enviar("miguelmp@prodigy.net.mx,miguel_padilla@nextel.mx.blackberry.com,cemaj@prodigy.net.mx", "Cobro realizado", $mensaje);

	break;
//************************************ FIN PASO 4 *************************************************************


default:

echo <<< pasod

<script language="javascript" type="text/javascript">
var efect;
efect=0;

</script>
<div name="cobro" id="cobro" align="center">
<h1>Cobrar</h1>
<table>
<tr>
<td><b>Paso1</b></td><td>Paso2</td><td>Paso3</td>
</tr>
<tr>
<td>Nombre </td><td colspan="2"><input type="text" name="nombreb" id="nombre"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda3.php','busquedacobro', 'btn=1&dato='+nombreb.value);efect=0;"> </td>
</tr>
<tr>
<td>Inmueble</td><td colspan="2"> <input type="text" name="inmuebleb" id="inmueble"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda3.php','busquedacobro', 'btn=2&dato='+inmuebleb.value);efect=0;"></td>
</tr>
<tr>
<td>No. contrato </td><td colspan="2"><input type="text" name="nocontratob" id="nocontrato"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda3.php','busquedacobro', 'btn=3&dato='+nocontratob.value);efect=0;"></td>
</tr>
</table>

<div name="busquedacobro" id="busquedacobro">

</div>
</div>
pasod;

}

?>