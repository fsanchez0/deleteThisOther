<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include '../general/correoclass.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

// Proceso para el asistente

$enviocorreo = New correo;
$accion = @$_GET["paso"];
$idcontrato = @$_GET["idcontrato"];
$efectivo = @$_GET["efectivo"];


$fechas = New Calendario;
$mensaje="";
//$fechas->DatosConexion('localhost','root','','bujalil');
//$enlace = mysql_connect('localhost', 'root', '');
//mysql_select_db('bujalil',$enlace) ;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="no")
{
	echo "A&uacute;n no se ha firmado con el servidor";
	exit;
}


	$sql="select * from submodulo where archivo ='anticipado.php'";
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


switch ($accion)
{
case 0: //PASO 1 : Busqueda de los pendientes

$html=  <<< paso1


<script language="javascript" type="text/javascript">
var efect;
efect=0;

</script>
<div name="cobro" id="cobro" align="center">
<h1>Anticipar</h1>
<form>
<table>
<tr>
<td><b>Paso1</b></td><td>Paso2</td><td>Paso3</td>
</tr>
<tr>

<td>Nombre </td><td colspan="2"><input type="text" name="nombreb" id="nombre"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda2.php','busquedacobro', 'btn=1&dato='+nombreb.value);efect=0;"> </td>
</tr>
<tr>
<td>Inmueble</td><td colspan="2"> <input type="text" name="inmuebleb" id="inmueble"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda2.php','busquedacobro', 'btn=2&dato='+inmuebleb.value);efect=0;"></td>
</tr>
<tr>
<td>No. contrato </td><td colspan="2"><input type="text" name="nocontratob" id="nocontrato"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda2.php','busquedacobro', 'btn=3&dato='+ nocontratob.value);efect=0;"></td>
</tr>
</table>
</form>
<div name="busquedacobro" id="busquedacobro" class="scroll">

</div>
</div>
paso1;
	echo CambiaAcentosaHTML($html);
	break;


case 1:  //PASO 2: Obtiene el adeudo del inquilino y solicita el pago



$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato=$idcontrato";
$result = @mysql_query ($sql);
$idc="";
$nombre="";
$suma = 0;
while ($row = mysql_fetch_array($result))
{
		$idc=$row["idcontrato"];
		$nombre =  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"];
}


$sql = "select * from historia where idcontrato=$idc and aplicado = false or isnull(aplicado) = true";
$result = @mysql_query ($sql);
while ($row = mysql_fetch_array($result))
{
		$suma+=$row["cantidad"] + $row["iva"];
}

$suma="$ " . number_format($suma,2);

html = <<< paso2


<div name="cobro" id="cobro">
<h1>Anticipar</h1>
<form>
<table>
<tr>
<td>Paso1</td><td><b>Paso2</b></td><td>Paso3</td>
</tr>
<tr>
<td>Contrato</td><td colspan="2"><input type="hidden" name="contrato" value="$idcontrato">$idc </td>
</tr>
<tr>
<td>Nombre </td><td colspan="2">  $nombre</td>
</tr>
<tr>
<td>Por pagar</td><td colspan="2">$suma</td>
</tr>
<tr>
<td>$ Recibido</td><td colspan="2"><input type="text" name="recibido" id="recibido" onBlur="efect=this.value;" > </td>
</tr>
<tr>
<!--<td colspan="3" align="center"><input type="button" value="Aceptar" onClick = "pasosCobro(2,$idcontrato,efect);this.disabled =true"> </td>-->
<td colspan="3" align="center"><input type="button" value="Aceptar" onClick = "cargarSeccion('$dirscript','contenido', 'paso=2&idcontrato=$idcontrato&efectivo='+efect);"> </td>
</tr>
</form>
</table>
</div>
paso2;
	echo CambiaAcentosaHTML($html);
	break;


case 2:  //PASO 3: Realiza el pago y muestra el resultado, así como los botones para crear recibos.

$enlace = mysql_connect('localhost', 'root', '');
mysql_select_db('bujalil',$enlace) ;

$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato=$idcontrato";
$result = @mysql_query ($sql);
$idc="";
$nombre="";
$suma = 0;
while ($row = mysql_fetch_array($result))
{
		$idc=$row["idcontrato"];
		$nombre =  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"];
}

$norecibos=0;
$difdinamica=$efectivo;
$ultimo=0;
$suma=0;
$direccioninmueble ="";


//$sql = "select idhistoria, historia.cantidad as cantidadh, cobros.cantidad as cantidadc, historia.iva as ivah from historia, cobros where historia.idcobros=cobros.idcobros and historia.idcontrato=$idc and (aplicado = false or isnull(aplicado)=true) and fechagracia>'" . date("Y")  . "-" . date("m") . "-" . date("d") . "' order by historia.idprioridad, historia.fechagracia";
$sql = "select idhistoria, historia.cantidad as cantidadh, cobros.cantidad as cantidadc, historia.iva as ivah, calle, numeroext, numeroint from historia, cobros, contrato, inmueble where historia.idcobros=cobros.idcobros and historia.idcontrato=$idc and (aplicado = false or isnull(aplicado)=true) and fechagracia>'" . date("Y")  . "-" . date("m") . "-" . date("d") . "' and historia.idcontrato = contrato.idcontrato and contrato.idinmueble=inmueble.idinmueble order by historia.idprioridad, historia.fechagracia";
$result = @mysql_query ($sql);
//echo $ultimoj;
//$direccioninmueble="";
if($result)
{

	while ($row = mysql_fetch_array($result))
	{
		//echo $row["cantidadh"] . " id " . $row["idhistoria"] . " ya<br>";
		$suma+=$row["cantidadh"] + $row["ivah"];

		//Va aplicando los pagos y va descontando del dinero depositado
		//$difdinamica=$difdinamica - ($row["cantidadh"] + $row["ivah"]);

		//Verifica si es el ultimo por aplicar
		if($ultimo==0){

			$difdinamica = $difdinamica - ($row["cantidadh"] + $row["ivah"]);
			if ($difdinamica<0)
			{
				//Cuando la diferencia es menor que cero, significa que no alcanzó a pagar
				//por completo a un concepto y marca como ultimo en aplicar
				$ultimo=1;
				$norecibos++;
				$matrecibos[$norecibos][1]=$row["idhistoria"]; //Id del pagado
				$matrecibos[$norecibos][2]=(-1 * $difdinamica); //lo que falta por pagar
				$matrecibos[$norecibos][3]=0;					//para asber que no fue pagado en vorma completa
				$direccioninmueble ="Inmueble: " . $row["calle"] . " " . $row["numeroext"]  . " " . $row["numeroint"] . " \r\n";

			}

			else
			{
				//Aplica el pago y lo agrega a la matriz
				$norecibos++;
				$matrecibos[$norecibos][1]=$row["idhistoria"]; //Id del pagado
				$matrecibos[$norecibos][2]=$row["cantidadh"] + $row["ivah"];  //lo que falta por pagar
				$matrecibos[$norecibos][3]=1;				   //para saber si se pago por completo
				if($difdinamica==0)
				{
					$ultimo=1;
					$direccioninmueble ="Inmueble: " . $row["calle"] . " " . $row["numeroext"]  . " " . $row["numeroint"] . " \r\n";
				}

			}
		}
	}
}


//}



//echo $suma . " esta es la suma<br>";
//echo $efectivo . " esto es el efectivo <br>";
$diferencia = $efectivo-$suma;
$edocuenta="window.open( 'scripts/edocuenta.php?contrato=" . $idcontrato . "');";

$suma="$ " . number_format($suma,2);
$efectivo="$ " . number_format($efectivo,2);
$diferencia="$ " . number_format($diferencia,2);
$html = <<< paso3


<div name="cobro" id="cobro">
<h1>Anticipar</h1>
<table>
<tr>
<td>Paso1</td><td>Paso2</td><td><b>Paso3</b></td>
</tr>
<tr>
<td>Contrato</td><td colspan="2"><input type="hidden" name="contrato" value="$idcontrato">$idc </td>
</tr>
<tr>
<td>Nombre </td><td colspan="2"> $nombre</td>
</tr>
<tr>
<td>Por pagar</td><td colspan="2">$suma</td>
</tr>
<tr>
<td>$ Recibido</td><td colspan="2"><h2>$efectivo</h2></td>
</tr>
<tr>
<td>Cambio</td><td colspan="2"><h1>$diferencia</h1></td>
</tr>
<tr>
<td>Estado de Cuenta</td><td colspan="2"><input type="button" value="Ver Estado de cuenta" onClick="$edocuenta"/><br></td>
</tr>


<tr>
<td colspan="3" align="center">
<!--<p><input type="button" value="ReciboF/ReciboNF concepto 1"></p>
<p><input type="button" value="ReciboF/ReciboNF concepto 2"></p>
<p><input type="button" value="ReciboF/ReciboNF concepto 3"></p> -->


paso3;
	echo CambiaAcentosaHTML($html);

$mensaje="Pago anticipado el día " . date('d-M-Y') . " a las " . date('H:i')  . "\r\n";
$mensaje .= "Contrato: $idc \r\n";
$mensaje .= "Inquilino: $nombre \r\n";
$mensaje .= $direccioninmueble ;
$mensaje .= "Pagó: $efectivo \r\n";
$mensaje .= "Saldo: $diferencia";


//botones para generar recibos

	// hacemos un proceso para generar los botones y aplicar los pagos completos siempre que hay más de un
	// elemento del arreglo
	$idusuario=1;
	for( $i=1; $i<$norecibos; $i++)
	{
		//Creo la consulta para obtener los datos para mostrar en los botones y los datos de los mismos
		//echo $sql = "select * from historia, cobros, tipocobro, periodo where historia.idcobros=cobros.idcobros and cobros.idtipocobro=tipocobro.idtipocobro and cobros.idperiodo=periodo.idperiodo and idhistoria=" . $matrecibos[$i][1];
		$sql = "select tipocobro, fechanaturalpago, numero, idmargen, historia.idcontrato as idc, cobros.idcobros as idcob, cobros.cantidad as ccantidad, cobros.idprioridad as idcprioridad, historia.interes as inter, cobros.iva as ivac, vencido, concluido, fechatermino from historia, cobros, tipocobro, periodo, contrato where historia.idcobros=cobros.idcobros and cobros.idtipocobro=tipocobro.idtipocobro and cobros.idperiodo=periodo.idperiodo and historia.idcontrato = contrato.idcontrato and idhistoria=" . $matrecibos[$i][1];
		$result = @mysql_query ($sql);
		while ($row = mysql_fetch_array($result))
		{
			//en el evento onClick debe de ir el procedimiento para impresion del recibo usando el id del historial
			$funcionrecibo="";
			$addtext="";
			if (is_null($row["inter"])==true || $row["inter"]==false )
			{
				$funcionrecibo="nuevaVP(" . $matrecibos[$i][1] . ",1)";
			}
			else			
			{
				$funcionrecibo="nuevaVP(" . $matrecibos[$i][1] . ",3)";
				$addtext=" -interes- ";
			}			
			//$funcionrecibo="nuevaVP(" . $matrecibos[$i][1] . ",1)";
			echo '<p><input type="button" value="' . $row["tipocobro"] . $addtext . '(Recibo F)" onClick="' . $funcionrecibo . '"> </p>';


			//Crear el nuevo pago si se pago por completo

			$fechagenerado = date("Y") . "-" . date("m") . "-" . date("d");
			//echo "<br>";
			//echo "La fecha de naturalpago es: " . $row["fechanaturalpago"] . " : " . substr($row["fechanaturalpago"], 5, 2) . " " . substr($row["fechanaturalpago"], 8, 2) . " " . substr($row["fechanaturalpago"], 0, 4);
			$fechagsistema =mktime(0,0,0,substr($row["fechanaturalpago"], 5, 2),substr($row["fechanaturalpago"], 8, 2),substr($row["fechanaturalpago"], 0, 4));

			$fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
			
			if($fechanaturalpago!=0)
			{
				if(is_null($row["inter"])==true || $row["inter"]==false  )
				{

					if($row["vencido"]==false && ($row["concluido"]==false || is_null($row["concluido"])==true))
					{


						$fechagracia = $fechas->fechagracia($fechanaturalpago);

						//echo "<br>primero   " . $fechagracia . "|" . $row["fechatermino"] . "<br>";


						if (mktime(0,0,0,substr($fechagracia, 5, 2),substr($fechagracia, 8, 2),substr($fechagracia, 0, 4)) < mktime(0,0,0,substr($row["fechatermino"], 5, 2),substr($row["fechatermino"], 8, 2),substr($row["fechatermino"], 0, 4)))
						{

							$sql1="insert into historia (idcontrato, idcobros, fechagenerado, fechanaturalpago, fechagracia,cantidad,idusuario,idprioridad,notas,fechavencimiento, iva,condonado) ";
							$sql1 .= " values (" . $row["idc"] . ", " . $row["idcob"] . ",'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "'," . $row["ccantidad"] . ", " . $misesion->usuario . "," . $row ["idcprioridad"] . ",'" . $row["tipocobro"] . "','" . $fechagracia . "'," . $row["ivac"] . ",false)";
							//echo $sql1 . "<br>";
							
							//para agregar siguiente pago, elimiado por cambio de buro
							//$result0 = mysql_query ($sql1);
						}
						else
						{
							$fincontrato="Se ha llegado a la fecha de t&eacute;rmino del contrato, no se agregar&aacute;n m&aacute;s pagos";
						}

					}
				}

			}




		}
		//echo "Actualizo el registro omo aplicado";
		$sql = "update historia set aplicado = true, fechapago='" . $fechagenerado . "', cantidad = " . $matrecibos[$i][2] . " where idhistoria=" . $matrecibos[$i][1];
		$result = mysql_query ($sql);




	}
	//para el ultimo elemento del arreglo


	//hago la consulta para regenerar los elementos del registro si es un pago parcial
	//echo $sql = "select historia.idhistoria as idhistoriah, historia.idcobros as idcobrosh, historia.idcontrato as idcontratoh, historia.idusuario as idusuarioh, historia.idprioridad as idprioridadh, fechagenerado, fechanaturalpago, fechagracia, fechapago, historia.cantidad as cantidadh, vencido, aplicado, notas, tipocobro, textorecibof, textorecibonf from historia, cobros, tipocobro where historia.idcobros=cobros.idcobros and cobros.idtipocobro=tipocobro.idtipocobro and  idhistoria=" . $matrecibos[$norecibos][1];
	 $sql = "select historia.idhistoria as idhistoriah, historia.idcobros as idcobrosh, historia.idcontrato as idcontratoh, historia.idusuario as idusuarioh, historia.idprioridad as idprioridadh, fechagenerado, fechanaturalpago, fechagracia, fechapago, historia.cantidad as cantidadh, vencido, aplicado, notas, tipocobro,  historia.interes as inter, tipocobro, numero, idmargen, cobros.idcobros as idcob,cobros.cantidad as ccantidad, cobros.idprioridad as idcprioridad,fechavencimiento, historia.interes as hinteres, vencido, cobros.iva as ivac, historia.iva as ivah, fechatermino, concluido from contrato, historia, cobros, tipocobro, periodo where historia.idcobros=cobros.idcobros and cobros.idtipocobro=tipocobro.idtipocobro and cobros.idperiodo=periodo.idperiodo and historia.idcontrato = contrato.idcontrato and idhistoria=" . $matrecibos[$norecibos][1];

	if($norecibos!=0)
	{

		$result = @mysql_query ($sql);
		while ($row = mysql_fetch_array($result))
		{
			//Creo las variables con los elementos importantes por si es necesario crear un nuevo registro
			//por pago parcial
			$idcobro=$row["idcobrosh"];
			$idcontrato=$row["idcontratoh"];
			$idusuario=$row["idusuarioh"];
			$idprioridad=$row["idprioridadh"];
			$fechanaturalpago=$row["fechanaturalpago"];
			$fechagracia=$row["fechagracia"];
			$cantidad=$row["cantidadh"]+ $row["ivah"];
			$fechavencimiento =$row["fechavencimiento"];
			$hinteres =$row["hinteres"];
			$vencido = $row["vencido"];
			$notas=$row["notas"];
			//$iva=$row["ivah"];
			$fechagenerado = date("Y") . "-" . date("m") . "-" . date("d");
			//verifico si hubo ultimo para generar el botón y actualizar el registro
			if ($difdinamica>=0)
			{
				$funcionrecibo="";
				$addtext="";
				if (is_null($row["inter"])==true || $row["inter"]==false )
				{
					$funcionrecibo="nuevaVP(" . $matrecibos[$i][1] . ",1)";
				}
				else			
				{
					$funcionrecibo="nuevaVP(" . $matrecibos[$i][1] . ",3)";
					$addtext=" -interes- ";
				}			
				echo '<input type="button" value="' . $row["tipocobro"] . $addtext . '(Recibo F)" onClick="' . $funcionrecibo . '">';



				$fechagsistema =mktime(0,0,0,substr($row["fechanaturalpago"], 5, 2),substr($row["fechanaturalpago"], 8, 2),substr($row["fechanaturalpago"], 0, 4));
				
				echo "se calcula la fecha " . $row["fechanaturalpago"] . " con los parametros de numero " . $row["numero"] . " y margen " . $row["idmargen"];
				echo $fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);

				//if($fechanaturalpago!=0)
				if($fechanaturalpago!=$row["fechanaturalpago"])
				{
					if(is_null($row["inter"])==true || $row["inter"]==false  )
					{

						if($row["vencido"]==false && ($row["concluido"]==false or is_null($row["concluido"])==true))
						{

							$fechagracia = $fechas->fechagracia($fechanaturalpago);

							//echo "<br> segundo  " . $fechagracia . "|" . $row["fechatermino"] . "<br>";

							if (mktime(0,0,0,substr($fechagracia, 5, 2),substr($fechagracia, 8, 2),substr($fechagracia, 0, 4)) < mktime(0,0,0,substr($row["fechatermino"], 5, 2),substr($row["fechatermino"], 8, 2),substr($row["fechatermino"], 0, 4)))
							{
								//$fechagracia = $fechas->fechagracia($fechanaturalpago);

								$sql1="insert into historia (idcontrato, idcobros, fechagenerado, fechanaturalpago, fechagracia,cantidad,idusuario,idprioridad,notas,fechavencimiento, iva,condonado) ";
								$sql1 .= " values (" . $idcontrato . ", " . $row["idcob"] . ",'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "'," . $row["ccantidad"] . ", " . $misesion->usuario . "," . $row ["idcprioridad"] . ",'" . $row["tipocobro"] . "','" . $fechagracia . "'," . $row["ivac"] . ",false)";
								//echo $sql1 . "<br>";
								
								//agrega nuevo cobro, se elimina por cambio de buro
								//$result0 = mysql_query ($sql1);
							}
							else
							{
								$fincontrato="Se ha llegado a la fecha de t&eacute;rmino del contrato, no se agregar&aacute;n m&aacute;s pagos";
							}
						}
					}

				}




			}

			else
			{
				//Creo la consluta para agregar un nuevo registro con los datos
				//nuevos de cobro
				$sql1 = "insert into historia (idcobros, idcontrato, idusuario, idprioridad, fechagenerado, fechanaturalpago, fechagracia, cantidad, notas, vencido, aplicado,fechavencimiento, interes,condonado) values ($idcobro,$idcontrato," . $misesion->usuario . ",$idprioridad,'" . date('Y') . "-" . date('m') . "-" . date('d') . "','$fechanaturalpago', '$fechagracia'," . $matrecibos[$i][2] . ", 'Diferencia del pago realizado', $vencido ,0 ,'$fechavencimiento', $hinteres,false)";

				//Creo la consulta para actualizar el registro de historia de donde se pago una parte con la diferencia
				$sql2 = "update historia set cantidad = $cantidad - " . $matrecibos[$norecibos][2] . " , aplicado = true, fechapago='" . $fechagenerado . "' where idhistoria = " . $matrecibos[$norecibos][1] ;



				//en el evento onClick debe de ir el procedimiento para impresion del recibo usando el id del historial
				$funcionrecibo="";
				if (is_null($row["inter"])==true || $row["inter"]==false )
				{
					$funcionrecibo="nuevaVP(" . $matrecibos[$i][1] . ",2)";
				}
				else			
				{
					$funcionrecibo="nuevaVP(" . $matrecibos[$i][1] . ",3)";
				}	
				echo '<br><input type="button" value="' . $row["tipocobro"] . '(Recibo NF)" onClick="' . $funcionrecibo . '">';
				$result1 = mysql_query ($sql1);
				$result1 = mysql_query ($sql2);
			}

		}
	}
	//Actualizo el registro parcial como pagado ya actualizado con su nuevo valor
	//$sql = "update historia set aplicado = true, fechapago='" . $fechagenerado . "' where idhistoria=" . $matrecibos[$norecibos][1];
	if ($difdinamica>=0)
	{
		$sql = "update historia set aplicado = true, fechapago='" . $fechagenerado . "', cantidad = " . $matrecibos[$norecibos][2] . " where idhistoria=" . $matrecibos[$norecibos][1];
		$result1 = mysql_query ($sql);
		echo "<p><font color=\"red\">$fincontrato</font></p>";
	}
echo <<< interbotones
</td>
</tr>
</div>
</table>


interbotones;
//$enviocorreo->enviar("mizocotroco@hotmail.com", "Cobro anticipado realizado", $mensaje);
//$enviocorreo->enviar("miguelmp@prodigy.net.mx,cemaj@prodigy.net.mx,lucero_cuevas@prodigy.net.mx,miguel_padilla@nextel.mx.blackberry.com,miguel@padilla-bujalil.com.mx", "Cobro anticipado realizado", $mensaje);
$enviocorreo->enviar("miguelmp@prodigy.net.mx,cemaj@prodigy.net.mx,miguel_padilla@nextel.mx.blackberry.com,miguel@padilla-bujalil.com.mx", "Cobro anticipado realizado", $mensaje);

	break;



default:

$html = <<< pasod

<script language="javascript" type="text/javascript">
var efect;
efect=0;

</script>
<div name="cobro" id="cobro" align="center">
<h1>Anticipar</h1>
<table>
<tr>
<td><b>Paso1</b></td><td>Paso2</td><td>Paso3</td>
</tr>
<tr>
<td>Nombre </td><td colspan="2"><input type="text" name="nombreb" id="nombre"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda2.php','busquedacobro', 'btn=1&dato='+nombreb.value);efect=0;"> </td>
</tr>
<tr>
<td>Inmueble</td><td colspan="2"> <input type="text" name="inmuebleb" id="inmueble"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda2.php','busquedacobro', 'btn=2&dato='+inmuebleb.value);efect=0;"></td>
</tr>
<tr>
<td>No. contrato </td><td colspan="2"><input type="text" name="nocontratob" id="nocontrato"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda2.php','busquedacobro', 'btn=3&dato='+nocontratob.value);efect=0;"></td>
</tr>
</table>

<div name="busquedacobro" id="busquedacobro">

</div>
</div>
pasod;
	echo CambiaAcentosaHTML($html);

}

?>