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
$idmetodopago =@$_GET["idmetodopago"];
$cuentapago=@$_GET["cuentapago"];
$fechapago=@$_GET["fechapago"];
$iddiferencia=@$_GET["iddiferencia"];
$impaplicado = $efectivo;
$tiempoa = date("H") . ":" . date("m") . ":" . date("s");	
$fechas = New Calendario;
$mensaje="";
$txtnull="0000";
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



	$sql="select * from submodulo where archivo ='cobropc.php'";
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



	$metodopago="";
	//preparo la lista del select para los metodo de pago
	$sql="select * from metodopago";

	$pagoselect = "<select name=\"idmetodopago\" id=\"idmetodopago\" onChange=\"var id=document.getElementById('idmetodopago').value; if(id==1){document.getElementById('cuentapago').value='".$txtnull."';}else{document.getElementById('cuentapago').value='';}\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($idmetodopago==$row["idmetodopago"])
		{
			$seleccionopt=" SELECTED ";
			$metodopago=CambiaAcentosaHTML($row["metodopago"]);
			
		}
		$pagoselect .= "<option value=\"" . $row["idmetodopago"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["metodopago"]) . "</option>";

	}
	$pagoselect .="</select>";


$fincontrato="";


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
<h1>Cobrar PC</h1>
<form>
<table>
<tr>
<td><b>Paso1</b></td><td>Paso2</td><td>Paso3</td><td>Paso4</td>
</tr>
<tr>

<td>Nombre </td><td colspan="3"><input type="text" name="nombreb" id="nombre"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busquedapc.php','busquedacobro', 'btn=1&dato='+nombreb.value);efect=0;"> </td>
</tr>
<tr>
<td>Inmueble</td><td colspan="3"> <input type="text" name="inmuebleb" id="inmueble"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busquedapc.php','busquedacobro', 'btn=2&dato='+inmuebleb.value);efect=0;"></td>
</tr>
<tr>
<td>No. contrato </td><td colspan="3"><input type="text" name="nocontratob" id="nocontrato"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busquedapc.php','busquedacobro', 'btn=3&dato='+ nocontratob.value);efect=0;"></td>
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

if(is_null($fechapago)==true || $fechapago==""){
	$fechapago=date('Y-m-d');
}


echo <<< paso2


<div name="cobro" id="cobro">
<h1>Cobrar PC</h1>
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
<td>Metodo de Pago</td><td colspan="3">$pagoselect</td>
</tr>
<tr>
<td>Cuenta de pago</td><td colspan="3"><input type="text" name="cuentapago" id="cuentapago"  ></td>
</tr>
<tr>
<td>$ Recibido</td><td colspan="3"><input type="text" name="recibido" id="recibido" onBlur="efect=this.value;" value="$efectivo" autofocus> </td>
</tr>
<tr>
<td>Fecha de pago</td><td colspan="3"><input type="text" name="fechapago" id="fechapago" value="$fechapago" > </td>
</tr>
<tr>
<!--<td colspan="3" align="center"><input type="button" value="Aceptar" onClick = "pasosCobro(2,$idcontrato,efect);"> </td>-->
<td colspan="3" align="center"><input type="button" value="Aceptar" onClick = "efect=recibido.value;cuentap=cuentapago.value;if(idmetodopago.value!=0 && recibido.value!=''){cargarSeccion('$dirscript','contenido', 'paso=2&idcontrato=$idcontrato&efectivo='+efect + '&idmetodopago=' + idmetodopago.value + '&cuentapago='+cuentap + '&fechapago='+ fechapago.value + '&iddiferencia=$iddiferencia');}else{alert('Debe de seleccionar metodo de pago y poner el importe recibido')}"> </td>
</tr>
</form>
</table>
</div>
paso2;
	break;
//************************************** FIN PASO 2 ******************************************************************

//************************************** PASO 3 *******************************************************************
case 2: //Muestra los pendientes de pagos que seran afectados

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
//$sql = "select idhistoria, historia.cantidad as cantidadh, cobros.cantidad as cantidadc, historia.iva as ivah, calle, numeroext, numeroint,fechanaturalpago, fechagenerado,tipocobro, historia.interes as interesh from historia, cobros, contrato, inmueble, tipocobro where historia.idcontrato=contrato.idcontrato and contrato.idinmueble=inmueble.idinmueble and historia.idcobros=cobros.idcobros and historia.idcontrato=$idc and cobros.idtipocobro=tipocobro.idtipocobro and (aplicado = false or isnull(aplicado)=true) and fechagracia<='" . date("Y")  . "-" . date("m") . "-" . date("d") . "' order by historia.idprioridad, historia.fechagracia";
$sql = "select idhistoria, historia.cantidad as cantidadh, cobros.cantidad as cantidadc, historia.iva as ivah, calle, numeroext, numeroint,fechanaturalpago, fechagenerado,tipocobro, historia.interes as interesh, fechagracia from historia, cobros, contrato, inmueble, tipocobro where historia.idcontrato=contrato.idcontrato and contrato.idinmueble=inmueble.idinmueble and historia.idcobros=cobros.idcobros and historia.idcontrato=$idc and cobros.idtipocobro=tipocobro.idtipocobro and (aplicado = false or isnull(aplicado)=true)  order by historia.fechagracia, historia.idprioridad ";
$result = @mysql_query ($sql);
$norecibos=0;
$difdinamica=$efectivo;
$ultimo=0;
$suma=0;
$direccioninmueble ="";
$fechahoy=date("Y-m-d");
$color = "";
//Verifico cuales estan pendientes y genero los pagos a los que se les debe de cobrar
$listapagos="<div id=\"porconfirmar\"><table border=\"1\"><tr><td>Fecha Pago</td><td>Descripci&oacute;n</td><td>Cantidad</td><td>Confirmaci&oacute;n</td></tr>";
if($result)
{

//Muestro todos los pagos que se deben de hacer hasta la fecha de hoy, solo falta darle formato, colocar los conceptos del estado de cuenta
//y el metodo para recalcular el efectivo que se va a recibir cuando cambien el valor de alguno de la lista
	while ($row = mysql_fetch_array($result))
	{
		//echo $row["cantidadh"] . " id " . $row["idhistoria"] . " ya<br>";
		//$suma+=$row["cantidadh"] + $row["ivah"];

		//Va aplicando los pagos y va descontando del dinero depositado
		//$difdinamica=$difdinamica - ($row["cantidadh"] + $row["ivah"]);

		//Verifica si es el ultimo por aplicar
		
		if($row["fechagracia"]>$fechahoy)
		{
			$color = " style ='color:#0000FF' ";
		}
		else
		{
			$color = "";
			$suma+=$row["cantidadh"] + $row["ivah"];
		}
		
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
				//Cuando la diferencia es menor que cero, significa que no alcanzÛ a pagar
				//por completo a un concepto y marca como ultimo en aplicar
				$ultimo=1;
				//$norecibos++;
				//$matrecibos[$norecibos][1]=$row["idhistoria"]; //Id del pagado
				//$matrecibos[$norecibos][2]=(-1 * $difdinamica); //lo que falta por pagar
				//$matrecibos[$norecibos][3]=0;					//para asber que no fue pagado en forma completa
				
				//$listapagos .= "<tr><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . ($row["cantidadh"] + $row["ivah"]) . "</td><td><input type=\"text\" name=\"c_" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "\" id=\"" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "\"  value=\"" . ($difdinamica + $row["cantidadh"] + $row["ivah"] ) . "\" onChange=\"compruevasuma('porconfirmar','" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "', 'totalconfirmar')\"></td></tr>";
				$listapagos .= "<tr $color ><td >" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . ($row["cantidadh"] + $row["ivah"]) . "</td><td><input type=\"text\" name=\"c_" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "\" id=\"" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "\"  value=\"" . ($difdinamica + $row["cantidadh"] + $row["ivah"] ) . "\" onChange=\"compruevasuma('porconfirmar','" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "', 'totalconfirmar')\"></td></tr>";
				
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
				$listapagos .= "<tr $color ><td >" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . ($row["cantidadh"] + $row["ivah"]) . "</td><td><input type=\"text\" name=\"c_" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "\" id=\"" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "\" value=\"" . ($row["cantidadh"] + $row["ivah"]) . "\" onChange=\"compruevasuma('porconfirmar','" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "', 'totalconfirmar')\"></td></tr>";
				
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
			
			$listapagos .= "<tr $color ><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . ($row["cantidadh"] + $row["ivah"]) . "</td><td><input type=\"text\" name=\"c_" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "\" id=\"" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "\" onChange=\"compruevasuma('porconfirmar','" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"]))  . "', 'totalconfirmar')\"></td></tr>";
			$controlesjava.="&c_" . $row["idhistoria"] . "=' + c_" . hacernombre($row["idhistoria"], ($row["cantidadh"] + $row["ivah"])) . ".value + '";
		}
	}
}
$listapagos.="</table><input type=\"hidden\" name=\"vuelto\" id=\"vuelto\" value=\"$difdinamica\"><input type=\"hidden\" name=\"totalconfirmar\" id=\"totalconfirmar\" value=\"$efectivo\"></div>";

$suma="$ " . number_format($suma,2);
$efectivo="$ " . number_format($efectivo,2);
//$diferencia="$ " . number_format($diferencia,2);
//$edocuenta="window.open( 'scripts/edocuenta.php?contrato=" . $idcontrato . "');";


//solo falta agregar el boton de envÌo para procesar el paso 4 que ser· el que haga los cargos correspondientes
//a los elementos seleccionados con la cantidad colocada.
echo <<< paso3

<form>
<h1 align="center">Cobrar PC</h1>
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
<td>Metodo de Pago</td><td colspan="3">$metodopago</td>
</tr>
<tr>
<td>Cuenta de Pago</td><td colspan="3">$cuentapago</td>
</tr>
<tr>
<td>$ Recibido</td><td colspan="3"><h2>$efectivo</h2></td>
</tr>
<tr>
<td>Fecha de pago</td><td colspan="3">$fechapago </td>
</tr>
<tr>

<td colspan="4" align="center"><input type="button" value="Aplicar" onClick = "cargarSeccion('$dirscript','contenido', 'paso=3&idcontrato=$idcontrato&efectivo='+efect + '&idmetodopago= $idmetodopago&cuentapago=$cuentapago&fechapago=$fechapago&cambio=' + cambio.value  + '&iddiferencia=$iddiferencia' + '$controlesjava');this.disabled =true"> 
<input type="hidden" name="cambio" value="$diferencia">

</td>

</tr>
<tr>

<td colspan="4" align="center">
		<b>Importe por pagar</b><br>
		<b style="color:#0000FF">Importe para pagos anticipados</b>
</td>

</tr>
	
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
case 3:  //PASO 3: Realiza el pago y muestra el resultado, asÌ como los botones para crear recibos.

//Ahora, aquÌ se va a colocar la lista de solo los pagados el dia de hoy, con su descripciÛn como en el paso 3, se deben de
//recibir variables del Post que comiencen con c_ ya que el resto es el id que se tiene queafectar con el valor que traiga en el la variable
//Hay que reordenar los botones de los recibos y colocar el metodo para notificaciÛn por correo en una ventana independiente al terminar de cargar
//los resultados y no afecte la funciÛn operativa de la pagina.  Esto nos permitir· generar los recibos en el caso de que no se pueda enviar el correo

$fecha = date("Y-m-d");
$usuario = $misesion->usuario;

$variables = "";
foreach($_GET as $campo => $valor)
{
	$variables .= "$campo=$valor|";
}
$variables = substr($variables,0,-1);
$sql ="insert into cobropc (idusuario, idcontrato, fechapc, efectivo, variables) values ($usuario, $idcontrato, '$fechapago', $efectivo,'$variables')";
$result = @mysql_query ($sql);

	if($result)
	{
		echo "<h1>Cobro Almacenado satisfactoriamente</h1>";
	}
	else
	{
		echo "<h1>No se almacen&oacute; la transacci&oacute;n</h1>";
	}



	break;
//************************************ FIN PASO 4 *************************************************************


default:

echo <<< pasod

<script language="javascript" type="text/javascript">
var efect;
efect=0;

</script>
<div name="cobro" id="cobro" align="center">
<h1>Cobrar PC</h1>
<table>
<tr>
<td><b>Paso1</b></td><td>Paso2</td><td>Paso3</td>
</tr>
<tr>
<td>Nombre </td><td colspan="2"><input type="text" name="nombreb" id="nombre"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda.php','busquedacobro', 'btn=1&dato='+nombreb.value);efect=0;"> </td>
</tr>
<tr>
<td>Inmueble</td><td colspan="2"> <input type="text" name="inmuebleb" id="inmueble"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda.php','busquedacobro', 'btn=2&dato='+inmuebleb.value);efect=0;"></td>
</tr>
<tr>
<td>No. contrato </td><td colspan="2"><input type="text" name="nocontratob" id="nocontrato"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda.php','busquedacobro', 'btn=3&dato='+nocontratob.value);efect=0;"></td>
</tr>
</table>

<div name="busquedacobro" id="busquedacobro">

</div>
</div>
pasod;

}

function cargaredoduenio($idhist, $imp,$fechad)
{
//encuentro al dueño o dueños

	//echo "<br>*****************<br>";
	//echo "inicio con idhistoria: $idhist <br>";
	//echo "inicio con importe: $imp <br>";
	$verificado = 0;
	$imp0=0;
	$sql = "select *, i.idinmueble as idinm, cn.idcontrato as idcn,c.idtipocobro as idtc, h.notas as nh, h.idcobros as idcbs from historia h,cobros c, inmueble i, duenioinmueble di, duenio d, contrato cn, tipocobro tc where c.idtipocobro = tc.idtipocobro and h.idcobros = c.idcobros and h.idcontrato = cn.idcontrato and cn.idinmueble = i.idinmueble and i.idinmueble = di.idinmueble and di.idduenio = d.idduenio and idhistoria = $idhist";
	$result = @mysql_query ($sql);
	while ($row = mysql_fetch_array($result))
	{	
//calcula honorario
		//verificar si hay iva en el concepto
		
		$idd = $row["idduenio"];
		$idtc = $row["idtc"];
		$idinm = $row["idinm"];
		$idcon = $row["idcn"];
		$fechantp =$row["fechanaturalpago"];
		$tipocobrto = $row["tipocobro"];
		$tipocontrato = $row["idtipocontrato"];
		//$idtipocontrato = $row["idtipocontrato"];
		//$fechaedod = date("Y") . "-" . date("m") . "-" . date("d");
		$fechaedod = $fechad;
		$horaedod = date("H") . ":" . date("i") . ":" . date("s");
		$mes='';
		$notas = $row["nh"];
		$pcomicion = 	$row["honorarios"];

		$sql0 = "select sum(iva) as ivah from historia where idcontrato = $idcon and fechanaturalpago = '$fechantp' and interes=false and idcobros =" . $row["idcbs"] ;
		$result0 = @mysql_query ($sql0);
		$row0 = mysql_fetch_array($result0);
		
		$imp0 = $imp;
		//echo "Importe para duenio: $imp0 <br>";
		//echo "importe para calculo de honorario: $imp <br>";		
		
		if($row0["ivah"]>0 && $verificado ==0)
		{
			$imp = $imp / (1+($row["ivad"]/100));
			$verificado = 1;
		}		
		//echo "Despues de la verificación del iva del concepto<br>";
		//echo "Importe para duenio: $imp0 <br>";
		//echo "importe para calculo de honorario: $imp <br>";
		
		if($idtc == 7 || $idtc == 4)
		{
			//$impedo = ($row["porcentaje"]/100) * $imp;
			$impedo = ($row["porcentajed"]/100) * $imp;
			$impedo0 = ($row["porcentajed"]/100) * $imp0;
				
			$hon = $impedo  * (-1);
			$ivah = $hon * ($row["ivad"]/100);
			$pcomicion=100;
		}
		else
		{
	//$impedo = ($row["porcentaje"]/100) * $imp;
			$impedo = ($row["porcentajed"]/100) * $imp;
			$impedo0 = ($row["porcentajed"]/100) * $imp0;
			
			$hon = $impedo * ($row["honorarios"]/100) * (-1);
			$ivah = $hon * ($row["ivad"]/100);		
		}

		//echo "Despues de la verificación de la proporcion de duenio<br>";
		//echo "Importe para duenio: $impedo0 <br>";
		//echo "importe para calculo de honorario: $impedo <br>";		
		
		
		
		if(substr($notas,0,11)=="PARCIALIDAD")
		{
			$notas ="PARCIALIDAD ";
		}		
		
		
		
		if($notas=="LIQUIDADO")
		{

	
			$sqlf="select * from historia where idcontrato = " . $row["idcn"] . " and parcialde =" . $row["parcialde"];
			$resultf = @mysql_query ($sqlf);
			if(mysql_num_rows($resultf)>1)
			{
				$notas ="FINIQUITO ";	
			}
			else
			{
				$notas ="";
			}

			//$notas ="FINIQUITO";
		}
		
		switch(substr($fechantp,5,2))
		{
		case '01':
			$mes='ENERO';
			break;
		case '02':
			$mes='FEBRERO';
			break;
		case '03':
			$mes='MARZO';
			break;
		case '04':
			$mes='ABRIL';
			break;
		case '05':
			$mes='MAYO';
			break;
		case '06':
			$mes='JUNIO';
			break;
		case '07':
			$mes='JULIO';
			break;																	
		case '08':
			$mes='AGOSTO';
			break;
		case '09':
			$mes='SEPTIEMBRE';
			break;			
		case '10':
			$mes='OCTUBRE';
			break;					
		case '11':
			$mes='NOVIEMBRE';
			break;
		case '12':
			$mes='DICIEMBRE';
			break;
			
		}
		
		$mes .= " " . substr($fechantp,0,4);
		
		//echo "Ejecucuion de consulta para duenio<br>";
//insert del ingreso del duenio
		$sqling = "insert into edoduenio (idduenio,idcontrato, idinmueble, idtipocobro,idhistoria, fechaedo,horaedo,importe, reportado,liquidado,referencia,notaedo, iva, enviado)values";
		//$sqling .= "($idd ,$idcon, $idinm, $idtc , $idhist , '$fechaedod' , '$horaedod', $impedo , false, false, 'h_$idhist' , '" . $notas . "$tipocobrto correspondiente al mes de $mes' ,0, false  )";
		$sqling .= "($idd ,$idcon, $idinm, $idtc , $idhist , '$fechaedod' , '$horaedod', $impedo0 , false, false, 'h_$idhist' , '" . $notas . "$tipocobrto correspondiente al mes de $mes' ,0, false  )";
		$resulting = @mysql_query ($sqling);

//insert del cobro de honorario


		if($hon <>0)
		{
	
		//echo "<br>Ejecucuion de consulta para honorarios<br>";		
		
		$sqlhon = "insert into edoduenio (idduenio,idcontrato, idinmueble, idtipocobro, idhistoria,fechaedo,horaedo,importe, reportado,liquidado,referencia,notaedo, iva, enviado)values";
		//$sqlhon .= "($idd ,$idcon, $idinm , $idtc , $idhist , '$fechaedod' , '$horaedod', $hon , false, false, 'h_$idhist' , 'Honorario " . $row["honorarios"] . "% de administracion $tipocobrto $mes',$ivah, false  )";
		$sqlhon .= "($idd ,$idcon, $idinm , $idtc , $idhist , '$fechaedod' , '$horaedod', $hon , false, false, 'h_$idhist' , 'Honorario " . $pcomicion . "% de administracion $tipocobrto $mes',$ivah, false  )";
		
		//echo "<br>**************************<br>";
		
		$resulting = @mysql_query ($sqlhon);
		} 
		$imp = $imp0;

	}


}


?>
