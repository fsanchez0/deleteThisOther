<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
// Proceso para el asistente
include '../general/correoclass.php';
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$enviocorreo = New correo;
$accion = @$_GET["accion"];
$id = @$_GET["idcobropc"];

//echo $accion;

//$fechas->DatosConexion('localhost','root','','bujalil');
$fechas = new Calendario;
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


	//echo "accion: " . $accion . "; idcobropc=" . $id . ".";
	$sql="select * from submodulo where archivo ='cobrolistapc.php'";
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

//echo $accion;
switch ($accion)
{
case 1:  //PASO 3: Realiza el pago y muestra el resultado, asÌ como los botones para crear recibos.

//Ahora, aquÌ se va a colocar la lista de solo los pagados el dia de hoy, con su descripciÛn como en el paso 3, se deben de
//recibir variables del Post que comiencen con c_ ya que el resto es el id que se tiene queafectar con el valor que traiga en el la variable
//Hay que reordenar los botones de los recibos y colocar el metodo para notificaciÛn por correo en una ventana independiente al terminar de cargar
//los resultados y no afecte la funciÛn operativa de la pagina.  Esto nos permitir· generar los recibos en el caso de que no se pueda enviar el correo


	$sqlpc = "select * from cobropc where idcobropc = $id";
	$resultpc = @mysql_query ($sqlpc);
	$DatosPago="";
	while ($rowpc = mysql_fetch_array($resultpc))
	{
		$idcontrato =$rowpc["idcontrato"];
		$DatosPago = $rowpc["variables"];
		$fechapago=$rowpc["fechapc"];
		$efectivo=$rowpc["efectivo"];
	}

	$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato=$idcontrato";
	$result = @mysql_query ($sql);
	$idc="";
	$nombre="";
	$suma = 0;
	$final="";
	$idhistoriaAplicados= array();
	$idinmueble=0;
	$idinquilino=0;

	while ($row = mysql_fetch_array($result))
	{
		$idc=$row["idcontrato"];
		$nombre =  CambiaAcentosaHTML($row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]);
		$idinquilino=$row["idinquilino"];
		$idinmueble=$row["idinmueble"];
	}

	//$sql = "select SUM(historia.cantidad + historia.iva) as suma from historia, cobros, contrato, inmueble  where historia.idcontrato=contrato.idcontrato and contrato.idinmueble=inmueble.idinmueble and historia.idcobros=cobros.idcobros and historia.idcontrato=$idc and (aplicado = false or isnull(aplicado)=true) and fechagracia<='" . date("Y")  . "-" . date("m") . "-" . date("d") . "' order by historia.idprioridad, historia.fechagracia";
	$sql = "select SUM(historia.cantidad + historia.iva) as suma from historia, cobros, contrato, inmueble  where historia.idcontrato=contrato.idcontrato and contrato.idinmueble=inmueble.idinmueble and historia.idcobros=cobros.idcobros and historia.idcontrato=$idc and (aplicado = false or isnull(aplicado)=true)  order by historia.fechagracia, historia.idprioridad";
	$result = @mysql_query ($sql);
	while ($row = mysql_fetch_array($result))
	{
		$suma=$row["suma"];		
	}

	$sql = "select  calle, numeroext, numeroint from contrato, inmueble where contrato.idcontrato=$idc and contrato.idinmueble=inmueble.idinmueble";
	$result = @mysql_query ($sql);

	$direccioninmueble ="";
	if($result)
	{

		while ($row = mysql_fetch_array($result))
		{
		
			$direccioninmueble =CambiaAcentosaHTML("Inmueble: " . $row["calle"] . " " . $row["numeroext"]  . " " . $row["numeroint"] . " \r\n");
			
		}
	}

	$final="<table border =\"1\"><tr><th>Fecha de pago</th><th>Concepto</th><th>Cantidad</th><th>Estado</th><th>Recibo</th></tr>";



	$DatosPago=split("[|]",$DatosPago);
	$idmetodopago="";
	$parcialde="";
	$cuentapago="";
	foreach($DatosPago as $Dat)
//foreach($_GET as $campo => $valor)
	{
		$aux = split("[=]",$Dat);
		$valor = $aux[1];
		$campo = $aux[0];
	
		if($valor=="" || $valor=="0"){continue;}
		if($campo=="idmetodopago"){$idmetodopago=$valor;}
		if($campo=="cuentapago"){$cuentapago=$valor;}
		//echo "<br>$campo = $valor<br>";
		$dhistoria=split("[_]",$campo);
		if(count($dhistoria)>1)
		{
		
			//$sql = "select tipocobro, fechanaturalpago, numero, idmargen, historia.idcontrato as idc, cobros.idcobros as idcob, cobros.cantidad as ccantidad, cobros.idprioridad as idcprioridad, historia.interes as inter, cobros.iva as ivac, vencido, concluido, fechatermino, historia.cantidad as hcantidad, historia.iva as hiva, parcialde from historia, cobros, tipocobro, periodo, contrato where historia.idcobros=cobros.idcobros and cobros.idtipocobro=tipocobro.idtipocobro and cobros.idperiodo=periodo.idperiodo and historia.idcontrato = contrato.idcontrato and idhistoria="  . $dhistoria[1];
			$sql = "select tipocobro, fechanaturalpago, numero, idmargen, historia.idcontrato as idc, cobros.idcobros as idcob, cobros.cantidad as ccantidad, cobros.idprioridad as idcprioridad, historia.interes as inter, cobros.iva as ivac, vencido, concluido, fechatermino, historia.cantidad as hcantidad, historia.iva as hiva, parcialde, terceros, tipocobro.idcategoriat from historia, cobros, tipocobro, periodo, contrato, folios where historia.idcobros=cobros.idcobros and cobros.idtipocobro=tipocobro.idtipocobro and cobros.idperiodo=periodo.idperiodo and historia.idcontrato = contrato.idcontrato and tipocobro.idfolios = folios.idfolios and idhistoria="  . $dhistoria[1];		
			//echo $sql . "<br>";
			$result = @mysql_query ($sql);
			while ($row = mysql_fetch_array($result))
			{
		
		
				if(is_null($row["parcialde"])==true)
				{					
					$parcialde = $dhistoria[1];
				}
				else
				{
					$parcialde=$row["parcialde"];
				}		
		

				//verificar si el dato de cantidad es = a la suma de cantidad e iva
				//si es asÌ, el proceso normal 		
				$valor = number_format($valor,2,'.','');	
				if((string)($row["hiva"] + $row["hcantidad"])==$valor)
				{
					
					$funcionrecibo="";
					$addtext="";
					if (is_null($row["inter"])==true || $row["inter"]==false )
					{
						//$funcionrecibo="nuevaVP(" . $dhistoria[1] . ",1);this.disabled=true;";
						$funcionrecibo="nuevaVP(" . $dhistoria[1] . ",'');this.disabled=true;";
										
					}
					else			
					{
						//$funcionrecibo="nuevaVP(" . $dhistoria[1] . ",3)this.disabled=true;";
						$funcionrecibo="nuevaVP(" . $dhistoria[1] . ",'');this.disabled=true;";
						$addtext=" -interes- ";
					}
					//echo '<p><input type="button" value="' . $row["tipocobro"] . $addtext . ' (Recibo F)" onClick="' . $funcionrecibo . '"> </p>';
				
					//Crear el nuevo pago si se pago por completo
					$fechagenerado = $fechapago; //date("Y") . "-" . date("m") . "-" . date("d");				
				
					$final .="<tr><td>$fechagenerado</td><TD>" . CambiaAcentosaHTML($row["tipocobro"]) . " $addtext (F.N.P. " . CambiaAcentosaHTML($row["fechanaturalpago"])  . ")</td><td>" . ($row["hcantidad"]+$row["hiva"]) . "</td><td>Pagado</td><td>";
					$final .= '<p><form name="frm_' . $dhistoria[1] .  '" id="frm_' . $dhistoria[1] .  '" method="POST" action="scripts/reporte2.php" target="trg_' . $dhistoria[1] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""><input type="button" value="' . CambiaAcentosaHTML($row["tipocobro"]) . $addtext . ' (Recibo F)" onClick="' . $funcionrecibo . '"> </form> </p>';
					$final .="</td></tr>";

									
					$fechagsistema =mktime(0,0,0,substr($row["fechanaturalpago"], 5, 2),substr($row["fechanaturalpago"], 8, 2),substr($row["fechanaturalpago"], 0, 4));
					
					//echo $row["fechanaturalpago"] . "<br>";				
					//echo "valo totals. " . $row["fechanaturalpago"] . " $fechagsistema " . $row["numero"] . " " . $row["idmargen"] ;	
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
						
								//echo "<p>verifico tÈrmino de contrato</p>"; 
								if (mktime(0,0,0,substr($fechagracia, 5, 2),substr($fechagracia, 8, 2),substr($fechagracia, 0, 4)) < mktime(0,0,0,substr($row["fechatermino"], 5, 2),substr($row["fechatermino"], 8, 2),substr($row["fechatermino"], 0, 4)))
								{
					
									$sql1="insert into historia (idcontrato, idcobros, fechagenerado, fechanaturalpago, fechagracia,cantidad,idusuario,idprioridad,notas,fechavencimiento, iva,condonado,idmetodopago,parcialde,cuentapago,idcategoriat) ";
									//$sql1 .= " values (" . $row["idc"] . ", " . $row["idcob"] . ",'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "'," . number_format($row["ccantidad"],2,".","") . ", " . $misesion->usuario . "," . $row ["idcprioridad"] . ",'" . $row["tipocobro"] . "','" . $fechagracia . "'," . number_format($row["ivac"],2,".","") . ",false)";
									$sql1 .= " values (" . $row["idc"] . ", " . $row["idcob"] . ",'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "'," . number_format($row["ccantidad"],2,".","") . ", " . $misesion->usuario . "," . $row ["idcprioridad"] . ",'PENDIENTE','" . $fechagracia . "'," . number_format($row["ivac"],2,".","") . ",false,1,$parcialde,'" . $cuentapago . "',".$row["idcategoriat"].")";
								
									//echo "<br><b>ejecuto en completos, siguiente pago</b><br>" . $sql1 . "<br>";
								
								
									// para generar nuevo cobro, eliminado por cambio de buro
									//$result0 = mysql_query ($sql1);
								}
								else
								{


									if($row["ultimo"]==false || is_null($row["ultimo"])==true)
									{
										$sql1="insert into historia (idcontrato, idcobros, fechagenerado, fechanaturalpago, fechagracia,cantidad,idusuario,idprioridad,notas,fechavencimiento, iva,condonado,idmetodopago, parcialde,cuentapago,idcategoriat) ";
										//$sql1 .= " values (" . $row["idc"] . ", " . $row["idcob"] . ",'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "'," . number_format($row["ccantidad"],2,".","") . ", " . $misesion->usuario . "," . $row ["idcprioridad"] . ",'" . $row["tipocobro"] . "','" . $fechagracia . "'," . number_format($row["ivac"],2,".","") . ",false)";
										$sql1 .= " values (" . $row["idc"] . ", " . $row["idcob"] . ",'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "'," . number_format($row["ccantidad"],2,".","") . ", " . $misesion->usuario . "," . $row ["idcprioridad"] . ",'PENDIENTE','" . $fechagracia . "'," . number_format($row["ivac"],2,".","") . ",false,1, $parcialde,'" . $cuentapago . "',".$row["idcategoriat"].")";
										// genera ultimo cobro, eliminado por cambio del buro
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
				
					//aplica el dinero sobre el registro
					// aplicar $estadopagado en el nuevo campo en notas estas se aplicaran en el esetado de cuenta
					//$sql1 = "update historia set aplicado = true, fechapago='" . $fechagenerado . "', cantidad = " . number_format($valor,2,".",""). " where idhistoria=" . $dhistoria[1];
				
					//$sql1 = "update historia set aplicado = true, fechapago='" . $fechagenerado . "', cantidad = " . number_format($valor,2,".",""). ", notas = 'LIQUIDADO', idmetodopago = $idmetodopago, parcialde = $parcialde, cuentapago = '" . $cuentapago . "' where idhistoria=" . $dhistoria[1];				
				
					//$tiempoa = date("H") . ":" . date("m") . ":" . date("s");	
					//$sql1 = "update historia set aplicado = true, fechapago='" . $fechagenerado . "', cantidad = " . number_format($valor,2,".","") . ", notas = 'LIQUIDADO', idmetodopago = $idmetodopago, parcialde = $parcialde, cuentapago = '" . $cuentapago . "',  horaaplicado = '" . $tiempoa . "',  impaplicado = '" . $impaplicado . "'  where idhistoria=" . $dhistoria[1];				
					$idhistoriaAplicados["h_".$dhistoria[1]]=$dhistoria[1];	
					$sql1 = "update historia set aplicado = true, fechapago='" . $fechapago . "', cantidad = " . number_format($valor,2,".","") . ", notas = 'LIQUIDADO', idmetodopago = $idmetodopago, parcialde = $parcialde, cuentapago = '" . $cuentapago . "',  horaaplicado = '" . $tiempoa . "',  impaplicado = '" . $impaplicado . "'  where idhistoria=" . $dhistoria[1];				
				
					//echo " | " . $row["terceros"] . "-" . $row["inter"];
					//echo "<br><b>ejecuto en completos actualizo cantidad y aplico pago</b><br>" . $sql . "<br>";
					$result0 = mysql_query ($sql1);
					if($row["terceros"]== true && $row["inter"]==false)
					{
						//cargaredoduenio($dhistoria[1], number_format($valor,2,".",""));
						cargaredoduenio($dhistoria[1], number_format($valor,2,".",""), $fechapago);
					}
						
				}//if
				else//para cuando no cumpla que sean iguales, significa que es menor
				{
					//echo "entre cuando en el campo $campo y el valor no es igual $valor=".($row["ivah"] + $row["hcantidad"]) . "<br>";
					//$sql = "select historia.idhistoria as idhistoriah, historia.idcobros as idcobrosh, historia.idcontrato as idcontratoh, historia.idusuario as idusuarioh, historia.idprioridad as idprioridadh, fechagenerado, fechanaturalpago, fechagracia, fechapago, historia.cantidad as cantidadh, vencido, aplicado, notas, tipocobro,  historia.interes as inter, tipocobro, numero, idmargen, cobros.idcobros as idcob,cobros.cantidad as ccantidad, cobros.idprioridad as idcprioridad,fechavencimiento, historia.interes as hinteres, vencido, cobros.iva as ivac, historia.iva as ivah, fechatermino, concluido, parcialde from contrato, historia, cobros, tipocobro, periodo where historia.idcobros=cobros.idcobros and cobros.idtipocobro=tipocobro.idtipocobro and cobros.idperiodo=periodo.idperiodo and historia.idcontrato = contrato.idcontrato and idhistoria=" . $dhistoria[1];
					$sql = "select historia.idhistoria as idhistoriah, historia.idcobros as idcobrosh, historia.idcontrato as idcontratoh, historia.idusuario as idusuarioh, historia.idprioridad as idprioridadh, fechagenerado, fechanaturalpago, fechagracia, fechapago, historia.cantidad as cantidadh, vencido, aplicado, notas, tipocobro,  historia.interes as inter, tipocobro, numero, idmargen, cobros.idcobros as idcob,cobros.cantidad as ccantidad, cobros.idprioridad as idcprioridad,fechavencimiento, historia.interes as hinteres, vencido, cobros.iva as ivac, historia.iva as ivah, fechatermino, concluido, parcialde, terceros, tipocobro.idcategoriat from contrato, historia, cobros, tipocobro, periodo, folios where historia.idcobros=cobros.idcobros and cobros.idtipocobro=tipocobro.idtipocobro and cobros.idperiodo=periodo.idperiodo and historia.idcontrato = contrato.idcontrato and tipocobro.idfolios = folios.idfolios and idhistoria=" . $dhistoria[1];
				
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
						$fechagenerado = $fechapago; //date("Y") . "-" . date("m") . "-" . date("d");
						
						if(is_null($row0["parcialde"])==true)
						{					
							$parcialde = $dhistoria[1];
						}
						else
						{
							$parcialde=$row0["parcialde"];
						}
					
					
					
						//echo "Tengo todas las variables y ahora verifico valor : $valor <br>";
						//verifico si hubo ultimo para generar el botÛn y actualizar el registro
					
						//echo "Es diferente de 0<br>";
						//Creo la consluta para agregar un nuevo registro con los datos
						//nuevos de cobro
						//$sql1 = "insert into historia (idcobros, idcontrato, idusuario, idprioridad, fechagenerado, fechanaturalpago, fechagracia, cantidad, notas, vencido, aplicado,fechavencimiento, interes,condonado) values ($idcobro,$idcontrato," . $misesion->usuario . ",$idprioridad,'" . date('Y') . "-" . date('m') . "-" . date('d') . "','$fechanaturalpago', '$fechagracia'," . number_format(($cantidad - $valor),2,".","") . ", 'Diferencia del pago realizado', $vencido ,0 ,'$fechavencimiento', $hinteres, false)";
						//$sql1 = "insert into historia (idcobros, idcontrato, idusuario, idprioridad, fechagenerado, fechanaturalpago, fechagracia, cantidad, notas, vencido, aplicado,fechavencimiento, interes,condonado,idmetodopago) values ($idcobro,$idcontrato," . $misesion->usuario . ",$idprioridad,'" . date('Y') . "-" . date('m') . "-" . date('d') . "','$fechanaturalpago', '$fechagracia'," . number_format(($cantidad - $valor),2,".","") . ", 'PENDIENTE', $vencido ,0 ,'$fechavencimiento', $hinteres, false,1)";
						$sql1 = "insert into historia (idcobros, idcontrato, idusuario, idprioridad, fechagenerado, fechanaturalpago, fechagracia, cantidad, notas, vencido, aplicado,fechavencimiento, interes,condonado,idmetodopago,parcialde,cuentapago,idcategoriat) values ($idcobro,$idcontrato," . $misesion->usuario . ",$idprioridad,'" . date('Y') . "-" . date('m') . "-" . date('d') . "','$fechanaturalpago', '$fechagracia'," . number_format(($cantidad - $valor),2,".","") . ", 'PENDIENTE', $vencido ,0 ,'$fechavencimiento', $hinteres, false,1," . $parcialde .  ",'" . $cuentapago . "',".$row0["idcategoriat"].")";
						//echo $sql1 . "<br>";
						//echo "<br><b>ejecuto en ulti moregistro, siguiente pago si diferencia es menor que cero</b><br>" . $sql1 . "<br>";
						//Creo la consulta para actualizar el registro de historia de donde se pago una parte con la diferencia
//*****					
						$estadopago="PARCIALIDAD, POR CUBRIR $" . number_format(($cantidad - $valor),2,".","");
					
						//$tiempoa = date("H") . ":" . date("m") . ":" . date("s");	
					
						//$sql2 = "update historia set cantidad =" . number_format($valor,2,".","") ." , aplicado = true, fechapago='" . $fechagenerado . "', notas = '$estadopago', idmetodopago=$idmetodopago, parcialde = " . $parcialde . ", cuentapago = '" . $cuentapago . "',  horaaplicado = '" . $tiempoa . "',  impaplicado = '" . $impaplicado . "' where idhistoria = " . $dhistoria[1];
						$idhistoriaAplicados["h_".$dhistoria[1]]=$dhistoria[1];	
						$sql2 = "update historia set cantidad =" . number_format($valor,2,".","") ." , aplicado = true, fechapago='" . $fechapago . "', notas = '$estadopago', idmetodopago=$idmetodopago, parcialde = " . $parcialde . ", cuentapago = '" . $cuentapago . "',  horaaplicado = '" . $tiempoa . "',  impaplicado = '" . $impaplicado . "' where idhistoria = " . $dhistoria[1];

						$funcionrecibo="";
						$addtext="";
						if (is_null($row0["inter"])==true or $row0["inter"]==false )
						{
							//$funcionrecibo="nuevaVP(" . $dhistoria . ",2)this.disabled=true;";
							$funcionrecibo="nuevaVP(" . $dhistoria . ",'');this.disabled=true;";
						}
						else			
						{
							//$funcionrecibo="nuevaVP(" . $dhistoria . ",3)this.disabled=true;";
							$funcionrecibo="nuevaVP(" . $dhistoria . ",'');this.disabled=true;";
							$addtext=" -interes- ";
						}	
						//echo '<p><input type="button" value="' . $row0["tipocobro"] . $addtext .  '(Recibo NF)" onClick="' . $funcionrecibo . '"></p>';
					
					
						//$final .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " $addtext (F.N.P. " . CambiaAcentosaHTML($row["fechanaturalpago"])  . ")</td><td>" . ($row["hcantidad"]+$row["hiva"]) . "</td><td>Parcial</td><td>";
						//$final .="<tr><td>$fechagenerado</td><td>" . CambiaAcentosaHTML($row["tipocobro"]) . " $addtext (F.N.P. " . CambiaAcentosaHTML($row["fechanaturalpago"])  . ")</td><td>$valor</td><td>Parcial</td><td>";
						$final .="<tr><td>$fechapago</td><td>" . CambiaAcentosaHTML($row["tipocobro"]) . " $addtext (F.N.P. " . CambiaAcentosaHTML($row["fechanaturalpago"])  . ")</td><td>$valor</td><td>Parcial</td><td>";
					
					
						//$final .= '<p><input type="button" disabled="disabled" value="' . CambiaAcentosaHTML($row0["tipocobro"]) . $addtext .  '(Recibo NF)" onClick="' . $funcionrecibo . '"></p>';
						$final .= '<p>No se genera recibo</p>';
						$final .="</td></tr>";
					
						$result1 = mysql_query ($sql1);
						$result1 = mysql_query ($sql2);
					
						if($row0["terceros"]== true && $row0["inter"]==false)
						{
							//cargaredoduenio($dhistoria[1], number_format($valor,2,".",""));
							cargaredoduenio($dhistoria[1], number_format($valor,2,".",""), $fechapago);
						}					
					
				
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
	$reportecobro="window.open( '$ruta/reporte_final_cobro.php?contrato=" . $idcontrato . "&impaplicado=" . $impaplicado . "&fechapago=" . $fechagenerado . "&horaaplicado=" . $tiempoa . "');";

	$suma="$ " . number_format($suma,2);
	if($cambio==""){$cambio=0;};


	$efectivo="$ " . number_format($efectivo,2);
	$diferencia="$ " . number_format($cambio,2);
	
	$sqlc="select tipocobro, (b.cantidad + b.iva) suma,(b.interes *100) elinteres from contrato c, cobros b, tipocobro t where c.idcontrato = b.idcontrato and b.idtipocobro = t.idtipocobro and b.idperiodo<>1 and c.idcontrato = $idcontrato";

	$listacobros="<table border='1' ><tr ><th>Concepto</th><th>Cantidad</th><th>Interes</th></tr>";

	$result1c = @mysql_query ($sqlc);
	while ($rowc = mysql_fetch_array($result1c))
	{
		$listacobros .="<tr><td >" . CambiaAcentosaHTML($rowc["tipocobro"])  ."</td><td align='right'>$" .  $rowc["suma"]  . "</td><td align='right'>" .  $rowc["elinteres"]  . "%</td></tr>";

	}
	$listacobros .="</table>";



$htmlPago = <<< paso3

<input type="button" value="Imprimir" onClick="imprimirv('imprimirdiv') ">
<div id="imprimirdiv">
<h1>Cobrar</h1>
<table border ="0">
<tr>
<td>Paso1</td><td>Paso2</td><td>Paso3</td><td ><b>Paso4</b></td>
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
<td>Por pagar</td><td colspan="2">$suma</td><td rowspan="4" valign="top">$listacobros</td>
</tr>
<tr>
<td>$ Recibido</td><td colspan="2"><h2>$efectivo</h2></td>
</tr>
<tr>
<td>Cambio</td><td colspan="2"><h1>$cambio</h1></td>
</tr>
<tr>
<td>Saldo</td><td colspan="2"><h1>$saldo</h1></td>
</tr>

<tr>
<td>Estado de Cuenta</td><td colspan="3"><input type="button" value="Ver Estado de cuenta" onClick="$edocuenta"/><input type="button" value="Reporte del cobro" onClick="$reportecobro"/><br></td>
</tr>


<tr>
<td colspan="4" align="center">
<!--<p><input type="button" value="ReciboF/ReciboNF concepto 1"></p>
<p><input type="button" value="ReciboF/ReciboNF concepto 2"></p>
<p><input type="button" value="ReciboF/ReciboNF concepto 3"></p> -->
$final
<br>
<img src="imagenes/marca_telefono.png">
</div>
paso3;

	$diferenciaTxt="";

	echo $saldo=round($saldo,2); 
	if($saldo>0)
	{	
		$sqlCambio="INSERT INTO diferencia (idinquilino,idinmueble,importe,fecha,metodopagod) VALUES ($idinquilino,$idinmueble,$saldo,'$fechapago',$idmetodopago)";
		$diferenciaQuery = @mysql_query ($sqlCambio);

		if($diferenciaQuery==true)
		{
			$diferenciaTxt="Se guado el Cambio";
		}
		else
		{
			$diferenciaTxt="Error al guadar el Cambio";
		}

		$saldo = "Cambio de $ $saldo";
	}
	else
	{
		$salto = -1 * $saldo;
		$saldo = "A&uacute;n debe $ $saldo";
	}

	if($iddiferencia!=0 || $iddiferencia!="")
	{
		$hoy=date('Y-m-d');
		$sqlDiferencia="UPDATE diferencia SET aplicado=1, fechaaplicado='$hoy' WHERE iddiferencia=$iddiferencia";
		$diferenciaAplicada = @mysql_query ($sqlDiferencia);	
		echo "<strong>Saldo guardado, aplicado<strong><br>";
	}

//echo "fin";

$htmlPago .= "<p><font color=\"red\">$fincontrato</font></p>";
$htmlPago .= <<< interbotones
</td>
</tr>
</div>
</table>

interbotones;


	$importetotal=substr($efectivo,2);
	$importetotal=str_replace(",", "", $importetotal);
	$pagoencode=base64_encode($htmlPago);
	$sqlPagos="INSERT INTO pagoaplicado (distribucionpago,importetotal) VALUES ('$pagoencode',$importetotal)";
	$insertAplicado = @mysql_query ($sqlPagos);
	$newId = @mysql_insert_id();

	if($newId)
	{ 
		foreach ($idhistoriaAplicados as $key => $value) 
		{
			$sqlHistPagos="INSERT INTO historiapago (idpagoaplicado,idhistoria) VALUES ($newId,$value)";
			$histPagos = @mysql_query ($sqlHistPagos);
		}
	}
	else
	{
		echo "<h3>Error, no se guardo el comprobante del pago</h3><br>";
	}
	echo "<h3>$diferenciaTxt</h3><br>";
	echo $htmlPago;

	$sqlpc = "delete from cobropc where idcobropc = $id";
	$resultpc = @mysql_query ($sqlpc);
//$enviocorreo->enviar("mizocotroco@hotmail.com", "Cobro realizado", $mensaje);
//$enviocorreo->enviar("miguelmp@prodigy.net.mx,lucero_cuevas@prodigy.net.mx,miguel_padilla@nextel.mx.blackberry.com,cemaj@prodigy.net.mx", "Cobro realizado", $mensaje);
//$enviocorreo->enviar("miguelmp@prodigy.net.mx,miguel_padilla@nextel.mx.blackberry.com,cemaj@prodigy.net.mx", "Cobro realizado", $mensaje);

//$enviocorreo->enviar("miguelmp@prodigy.net.mx,cemaj@prodigy.net.mx", "Cobro realizado", $mensaje);


//$enviocorreo->enviar("contabilidad@padilla-bujalil.com.mx", "Cobro realizado", $mensaje);


	break;
//************************************ FIN PASO 4 *************************************************************
	
	
	
	
		
case 2: //PASO 1 : muestra datos


	$sqlpc = "select * from cobropc where idcobropc = $id";
	$resultpc = @mysql_query ($sqlpc);
	$DatosPago="";
	while ($rowpc = mysql_fetch_array($resultpc))
	{
		$idcontrato =$rowpc["idcontrato"];
		$DatosPago = $rowpc["variables"];
		$fechapago=$rowpc["fechapc"];
		$efectivo=$rowpc["efectivo"];
	}


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



	$listapagos="<div id=\"porconfirmar\"><table border=\"1\"><tr><td>Fecha Pago</td><td>Descripci&oacute;n</td><td>Cantidad</td></tr>";
	
	
	$DatosPago=split("[|]",$DatosPago);
	
	
	
	$sumavalor=0;
	//var_dump($DatosPago);
	foreach($DatosPago as $Dat)	
	{
		//echo $Dat ."<br>";
		$aux = split("[=]",$Dat);
		$valori = $aux[1];
		$campo = $aux[0];		
		
		
		//echo $campo;
		$idhistoria0=split("[_]",$campo);
		//var_dump($idhistoria0);
		//echo "largo " . sizeof($idhistoria0) . " " . strrpos($campo, "_") . "<br>";
		//echo "<br>valori=" . $valori;

		if(sizeof($idhistoria0)>1)
		{
			$sumavalor +=$valori;
			$idh0 = $idhistoria0[1];
			//echo $valor . ".<br>";
			$sql = "select idhistoria, historia.cantidad as cantidadh, cobros.cantidad as cantidadc, historia.iva as ivah, calle, numeroext, numeroint,fechanaturalpago, fechagenerado,tipocobro, historia.interes as interesh, fechagracia from historia, cobros, contrato, inmueble, tipocobro where historia.idcontrato=contrato.idcontrato and contrato.idinmueble=inmueble.idinmueble and historia.idcobros=cobros.idcobros and historia.idcontrato=$idc and historia.idhistoria =$idh0 and cobros.idtipocobro=tipocobro.idtipocobro and (aplicado = false or isnull(aplicado)=true)  order by historia.fechagracia, historia.idprioridad ";
			$result = @mysql_query ($sql);
			$norecibos=0;
			$difdinamica=$efectivo;
			$ultimo=0;
			$suma=0;
			$direccioninmueble ="";
			$fechahoy=date("Y-m-d");
			$color = "";	
			//echo $result . "<br>";		
			if($result)
			{

					
				
				while ($row = mysql_fetch_array($result))
				{
	
			//var_dump($DatosPago);
			//var_dump($Dat);
			//echo "<br>valori=" . $valori;
			//exit();		
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
							$listapagos .= "<tr $color ><td >" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . $valori . "</td></tr>";
		
						}

						else
						{
							$listapagos .= "<tr $color ><td >" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . $valori . "</td></tr>";
				

						}
					}
					else
					{
						//$listapagos .= "<tr><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . ($row["cantidadh"] + $row["ivah"]) . "</td><td><input type=\"text\" name=\"c_" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "\" id=\"" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "\" onChange=\"compruevasuma('porconfirmar','" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"])  . "', 'totalconfirmar')\"></td></tr>";
						//$controlesjava.="&c_" . $row["idhistoria"] . "=' + c_" . $row["idhistoria"] . "_" . ($row["cantidadh"] + $row["ivah"]) . ".value + '";
			
						$listapagos .= "<tr $color ><td>" . $row["fechanaturalpago"] . "</td><td>" . $concepto . "</td><td>" . $valori . "</td></tr>";
			
					}
				}
			
				
		
			}					
		}
		
	}


$sumavalor = number_format($sumavalor,2,".",",");

echo <<< paso1


<form>
<center>
<table>
<tr>
<td>Fecha del pago: </td><td colspan="3">$fechapago</td>
</tr>
<tr>
<td>Contrato: </td><td colspan="3">$idcontrato</td>
</tr>
<tr>
<td>Nombre: </td><td colspan="3"> $nombre</td>
</tr>
	
</table>
</center>
Por confirmar el total de:$sumavalor<br>
$listapagos
</form>

</div>
paso1;
	break;
//***************************************FIN PASO 1 ***********************************************
	
	

//**************************** BORRAR ***************************
case 3: //PASO 1 : Borrar registro


	$sqlpc = "delete from cobropc where idcobropc = $id";
	$resultpc = @mysql_query ($sqlpc);
	
	$mensaje="";
	if ($restpc)
	{
		$mensaje="<b>Se elimino correctamente la instrucci&oacute;n</b>";
	}
	else
	{
		$mensaje="<b>No se pudo borrar la instrucci&oacute;n</b>";
	}
	

echo <<< paso1


<script language="javascript" type="text/javascript">
//var efect;
efect=0;

</script>
<div name="cobro" id="cobro" align="center">
<h1>$mensaje</h1>

</div>
paso1;
	break;
//***************************************FIN PASO 1 ***********************************************


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
