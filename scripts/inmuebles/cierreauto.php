<?php

//nota importante sobre el script
// debe de tener más de uan face:
//Primera: (listo)
//        mostrar todo lo que se vence y permitir operaciones de condonación
//Segunda: (listo)
//        Al presionar el botón u opción de condonación, generar el proceso para
//        solo actualizar el siguietne vencimiento sin aplicar el interes
//Tercera: (listo)
//        Botón para aplicar todas las demas o de aceptar todos los cargos

//IMPORTANTE¡¡
//        Aplicado el interes no se puede quitar, solo directo de la base.

//*********************************************************************
//Este modulo biene con 2 archivos, este y el archivo de condonar.php
//el archivo condonar.php debe de estar en la dirección de scripts/condonar.php
//si es necesario cambiar la carpeta, hay que editar el archivo para que funcione el JavaScript
//*********************************************************************


include '../general/correoclass.php';
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');


$enviocorreo = New correo;

$ver=@$_GET["ver"];
$hora=date('H');
$mensaje="";
$pagos="";
$pasuntos ="";
if( $hora!=21)
{

echo "&nbsp;";
exit;

}

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$fechas = New Calendario;
	//$fechas->DatosConexion('localhost','root','','bujalil');
	//$enlace = mysql_connect('localhost', 'root', '');
	//mysql_select_db('bujalil',$enlace) ;
	$HoyFecha = date('Y') . "-" . date('m') . "-" . date('d');

	//$sql = "select * from historia where aplicado=true and fechapago='$HoyFecha'";
	$sql = "select idhistoria, fechapago,fechagenerado, fechavencimiento,tipocobro, historia.cantidad as cantidadh, historia.iva as ivah, historia.interes as inth, nombre, nombre2 apaterno, amaterno, calle, numeroint, numeroext, contrato.idcontrato as idcont from historia,contrato, inquilino, inmueble, cobros, tipocobro where historia.idcobros = cobros.idcobros and historia.idcontrato= contrato.idcontrato and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble = inmueble.idinmueble and cobros.idtipocobro = tipocobro.idtipocobro and aplicado=true and fechapago='$HoyFecha' and historia.cantidad >0 and condonado=false  ";



	$result = @mysql_query ($sql);
	$suma = 0;

	while ($row = mysql_fetch_array($result))
	{
		//$suma += $row["cantidad"];
		$pagos .=  $row["idcont"] . "\t" . $row["fechapago"] . " \t" .  $row["nombre"] . " "  . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]  . "\t" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"] . "\t $" . number_format(($row["cantidadh"]) ,2) . " \r\n\r\n<br><br>";
		$suma += $row["cantidadh"];
	}

	//Para los Asuntos
	
	$sql = "select asunto.idasunto as idasu,  tipocargo,  cantidad , nombre, apaterno, amaterno from asunto, estadocuenta, tipocargo, directorio where asunto.idasunto = estadocuenta.idasunto and estadocuenta.idtipocargo = tipocargo.idtipocargo and asunto.iddirectorio=directorio.iddirectorio and estadocuenta.fechapagado='$HoyFecha' ";
	$result = @mysql_query ($sql);	
	$asuma=0;
	while ($row = mysql_fetch_array($result))
	{
		//$suma += $row["cantidad"];
		$pasuntos .=  $row["idasu"] . "\t" .   $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "\t" .   $row["tipocargo"] .  "\t $" . number_format(($row["cantidad"]) ,2) . " \r\n\r\n<br><br>";
		$asuma += $row["cantidad"];
	}


	//para igualas
	$sql = "select i.idiguala as idi, e.idestadocuenta as ide, numero, idmargen, p.idperiodo as idperiod,  vencido, fechavencimiento, i.cantidad as cant, i.idasunto as ida, i.idtipocargo as idt, i.descripcion as descr  from estadocuenta e,iguala i,periodo p where e.idiguala = i.idiguala and i.idperiodo=p.idperiodo and e.idasunto = i.idasunto and fechavencimiento<='$HoyFecha' and  vencido=false ";
	$result = @mysql_query ($sql);
	while ($row = mysql_fetch_array($result))
	{
		$sql2 = "update estadocuenta set vencido = true where idestadocuenta = " . $row["ide"];
		$result2 = @mysql_query ($sql2);
		
		
		$fechagsistema =mktime(0,0,0,substr($row["fechavencimiento"], 5, 2),substr($row["fechavencimiento"], 8, 2),substr($row["fechavencimiento"], 0, 4));
						
		
		$fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
		$sql2="insert into estadocuenta (idasunto, idtipocargo, idusuario, descripcion, fecha, cantidad, pagado, fechavencimiento,idiguala, vencido) values";miguel@padilla-bujalil.com.mx,lucero@padilla-bujalil.com.mx,mapadilla@padilla-bujalil.com.mx
		$sql2 .= "(" . $row["ida"] . ", " . $row["idt"] . "," . $misesion->usuario . ",'" . $row["descr"] . "','" . $row["fechavencimiento"] . "'," . $row["cant"] . ",false,'$fechanaturalpago'," . $row["idi"] . ",false)";
		$result2 = @mysql_query ($sql2);
	}	
	
	
	//fin igualas





	//$sql = "select historia.idcontrato as idcontra, historia.idcobros as idcobr, historia.cantidad as cantida, cobros.interes as cinteres, fechanaturalpago, fechagracia, fechavencimiento, historia.idusuario as idusuarioh, idhistoria, numero, idmargen, concluido, fechatermino, vencido, cobros.idprioridad as idprioridadc, cobros.iva as ivac, cobros.cantidad as cantidadc from historia,cobros,periodo, contrato where historia.idcobros = cobros.idcobros and cobros.idperiodo=periodo.idperiodo and historia.interes = false and aplicado = false and historia.idcontrato = contrato.idcontrato and fechavencimiento<='$HoyFecha'";
	//$sql = "select historia.idcontrato as idcontra, historia.idcobros as idcobr, historia.cantidad as cantida, cobros.interes as cinteres, fechanaturalpago, fechagracia, fechavencimiento, historia.idusuario as idusuarioh, idhistoria, numero, idmargen, concluido, fechatermino, vencido, cobros.idprioridad as idprioridadc, cobros.iva as ivac, cobros.cantidad as cantidadc, calle, numeroext, numeroint from historia,cobros,periodo, contrato, idinmueble where contrato.idinmueble=inmueble.idinmueble and historia.idcobros = cobros.idcobros and cobros.idperiodo=periodo.idperiodo and historia.interes = false and aplicado = false and historia.idcontrato = contrato.idcontrato and fechavencimiento<='$HoyFecha'";
	$sql = "select historia.idcontrato as idcontra, historia.idcobros as idcobr, historia.cantidad as cantida, cobros.interes as cinteres, fechanaturalpago, fechagracia, fechavencimiento, historia.idusuario as idusuarioh, idhistoria, numero, idmargen, concluido, fechatermino, vencido, cobros.idprioridad as idprioridadc, cobros.iva as ivac, cobros.cantidad as cantidadc, calle, numeroext, numeroint, inquilino.nombre as nombrei, nombre2, apaterno, amaterno, ultimo, activo from historia,cobros,periodo, contrato, inmueble, inquilino where contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and historia.idcobros = cobros.idcobros and cobros.idperiodo=periodo.idperiodo and historia.interes = false and aplicado = false and historia.idcontrato = contrato.idcontrato and fechavencimiento<='$HoyFecha' and historia.cantidad >0 and condonado=false";

	$result = mysql_query ($sql);

	while ($row = @mysql_fetch_array($result))
	{
		if($row["cinteres"]!=0)
		{




		//También hay que verificar si la ultima fecha generada en el cierre, no esta vencida si lo esta
		//aplicar las penalizaciones correspondientes segun el cobro programado

			$idhist=$row["idhistoria"];
			$accionboton="<input type =\"button\" value=\"Condonar\" onClick=\"Condonar ('cierretbl',this,$idhist);\"  />";
			$Apagar=$row["cinteres"]*$row["cantida"];
			if (date('Y')<2010)
			{
				$ivainteres=$Apagar * .15;
			}
			else
			{			
				$ivainteres=$Apagar * .16;
			}
			$TotalPagar = $Apagar+$ivainteres;
			if($ver==1){


				$fechavencimiento=$row["fechavencimiento"];
				$fechanpago=$row["fechanaturalpago"];




				$fechagsistema =mktime(0,0,0,substr($fechavencimiento, 5, 2),substr($fechanpago, 8, 2),substr($fechavencimiento, 0, 4));

				$fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);

				//$ProxVencimiento =$fechas->fechagracia($fechanaturalpago);
				
				if($fechavencimiento!=$fechanaturalpago)
				{
					$ProxVencimiento =$fechas->fechagracia($fechanaturalpago);								
				}
				else
				{
					$fechanaturalpago = $fechas->calculafecha($fechagsistema, 1, 2);
					$ProxVencimiento =$fechas->fechagracia($fechanaturalpago);
				}				
				
				if(($row["concluido"]==false || is_null($row["concluido"])==true) && $fechanaturalpago!=0 && $row["activo"] == true)
				{

					$ivainteres=$Apagar * .15;
					$sql2="insert into historia (idcontrato,idcobros,fechagenerado,fechanaturalpago,fechagracia,cantidad,iva,interes,idusuario,idprioridad,fechavencimiento,condonado) values(";
					$sql2 .= $row["idcontra"] . "," . $row["idcobr"] . ",'" . $HoyFecha . "', '" . $row["fechanaturalpago"] . "', '" . $row["fechagracia"] . "'," . number_format($Apagar,2,".","") . "," . number_format($ivainteres,2,".","") . ",true," . $row["idusuarioh"] . ", 1, '" . $ProxVencimiento . "',false)";

					//echo "Agrego nuevo registro de interes<br>";
					$result2 = @mysql_query ($sql2);
					$sql2 = "update historia set fechavencimiento = '" . $ProxVencimiento . "', vencido = true where idhistoria = " . $row["idhistoria"];

					//echo "Actualizo el registro como vencido<br>";
					$result2 = @mysql_query ($sql2);
				}
				//echo $sql2 = "update historia set vencido = true where idhistoria = " . $row["idhistoria"];
				//$result2 = @mysql_query ($sql2);

				if($row["vencido"]!=true)
				{
					//Independientemente de que agregue el interes, en el caso de que no este vencido
					//debe de preparar el pago del siguiente mes
					$fechavencimiento=$row["fechavencimiento"];
					$fechanpago=$row["fechanaturalpago"];
					$fechagsistema =mktime(0,0,0,substr($fechanpago, 5, 2),substr($fechanpago, 8, 2),substr($fechanpago, 0, 4));

					$fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
					$ProxVencimiento =$fechas->fechagracia($fechanaturalpago);

					//if($fechanaturalpago!=0)
					if($fechanaturalpago!=$fechanpago)
					{
						if($row["concluido"]==false || is_null($row["concluido"])==true)
						{
							
							if (mktime(0,0,0,substr($ProxVencimiento, 5, 2),substr($ProxVencimiento, 8, 2),substr($ProxVencimiento, 0, 4)) < mktime(0,0,0,substr($row["fechatermino"], 5, 2),substr($row["fechatermino"], 8, 2),substr($row["fechatermino"], 0, 4)))
							{
								$sql2="insert into historia (idcontrato,idcobros,fechagenerado,fechanaturalpago,fechagracia,cantidad,iva,interes,vencido,aplicado, idusuario,idprioridad,fechavencimiento,condonado) values(";
								$sql2 .= $row["idcontra"] . "," . $row["idcobr"] . ",'" . $HoyFecha . "', '" . $fechanaturalpago . "', '" . $ProxVencimiento . "'," . number_format($row["cantidadc"],2,".","") . "," . number_format($row["ivac"],2,".","") . ",false,false,false," . $row["idusuarioh"] . ", " .$row["idprioridadc"] . " , '" . $ProxVencimiento . "',false)";
								//echo "Creo el siguietne pago<br>";
								//$result2 = @mysql_query ($sql2);
							}
							else
							{
								if($row["ultimo"]==false || is_null($row["ultimo"])==true)
								{
									//$sql2="insert into historia (idcontrato,idcobros,fechagenerado,fechanaturalpago,fechagracia,cantidad,iva,interes,vencido,aplicado, idusuario,idprioridad,fechavencimiento,condonado) values(";
									//$sql2 .= $row["idcontra"] . "," . $row["idcobr"] . ",'" . $HoyFecha . "', '" . $fechanaturalpago . "', '" . $ProxVencimiento . "'," . $row["cantidadc"] . "," . $row["ivac"] . ",false,false,false," . $misesion->usuario . ", " .$row["idprioridadc"] . " , '" . $ProxVencimiento . "',false)";
									//$result2 = @mysql_query ($sql2);
									$sql2 = "update contrato set ultimo = true where idcontrato = " . $row["idcontra"];
									$result2 = @mysql_query ($sql2);

								}

							}



						}
					}


				}

				$accionboton="Aplicado";
			}
			if(($row["concluido"]==false || is_null($row["concluido"])==true) )
			{
				//$mensaje .= $row["idcontra"] . "\t" .  $row["nombre"] . " "  . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]  . "\t" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"] . "\t" .  $row["cinteres"] . "\t" . $row["cantida"] . "\t $Apagar \t $accionboton \r\n\r\n<br><br>";
				$mensaje .= $row["idcontra"] . "\t" .  $row["nombrei"] . " "  . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]  . "\t" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"] . "\t" .  $row["cinteres"] . "\t" . $row["cantida"] . "\t $TotalPagar \t $accionboton \r\n\r\n<br><br>";
			}

		}
		else
		{
		//Para crear el siguiente mes aunque no tenga interes como el mantenimiento
			if($row["vencido"]!=true and $ver==1)
			{

				$fechavencimiento=$row["fechavencimiento"];
				$fechanpago=$row["fechanaturalpago"];
				$fechagsistema =mktime(0,0,0,substr($fechanpago, 5, 2),substr($fechanpago, 8, 2),substr($fechanpago, 0, 4));

				$fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
				$ProxVencimiento =$fechas->fechagracia($fechanaturalpago);

				//if($fechanaturalpago!=0)
				if($fechanaturalpago!=$fechanpago)
					{
						if($row["concluido"]==false || is_null($row["concluido"])==true)
						{
							
							if (mktime(0,0,0,substr($ProxVencimiento, 5, 2),substr($ProxVencimiento, 8, 2),substr($ProxVencimiento, 0, 4)) < mktime(0,0,0,substr($row["fechatermino"], 5, 2),substr($row["fechatermino"], 8, 2),substr($row["fechatermino"], 0, 4)))
							{
								$sql2="insert into historia (idcontrato,idcobros,fechagenerado,fechanaturalpago,fechagracia,cantidad,iva,interes,vencido,aplicado, idusuario,idprioridad,fechavencimiento,condonado) values(";
								$sql2 .= $row["idcontra"] . "," . $row["idcobr"] . ",'" . $HoyFecha . "', '" . $fechanaturalpago . "', '" . $ProxVencimiento . "'," . number_format($row["cantidadc"],2,".","") . "," . number_format($row["ivac"],2,".","") . ",false,false,false," . $row["idusuarioh"] . "," . $row["idprioridadc"] . " , '" . $ProxVencimiento . "',false)";
								//$result2 = @mysql_query ($sql2);
								//$prueba .= "<br>" . $sql2;
								$sql2 = "update historia set fechavencimiento = '" . $ProxVencimiento . "' where idhistoria = " . $row["idhistoria"];
								$result2 = @mysql_query ($sql2);
								//$prueba .= "<br>" . $sql2;
								$sql2 = "update historia set vencido = true where idhistoria = " . $row["idhistoria"];
								$result2 = @mysql_query ($sql2);
							}
							else
							{
								if($row["ultimo"]==false || is_null($row["ultimo"])==true)
								{
									//$sql2="insert into historia (idcontrato,idcobros,fechagenerado,fechanaturalpago,fechagracia,cantidad,iva,interes,vencido,aplicado, idusuario,idprioridad,fechavencimiento,condonado) values(";
									//$sql2 .= $row["idcontra"] . "," . $row["idcobr"] . ",'" . $HoyFecha . "', '" . $fechanaturalpago . "', '" . $ProxVencimiento . "'," . $row["cantidadc"] . "," . $row["ivac"] . ",false,false,false," . $misesion->usuario . ", " .$row["idprioridadc"] . " , '" . $ProxVencimiento . "',false)";
									//$result2 = @mysql_query ($sql2);
									$sql2 = "update contrato set ultimo = true where idcontrato = " . $row["idcontra"];
									$result2 = @mysql_query ($sql2);

								}

							}


						}
					}
			//echo $prueba;
			}

		}

	}
	
	
	
	$sql1="select * from apartado a, inmueble i where a.idinmueble = i.idinmueble and cancelado = false and vencimiento <= '$HoyFecha'";
	//$sql1="select * from apartado a, inmueble i where a.idinmueble = i.idinmueble  and vencimiento <= '$HoyFecha'";
	$operacion = mysql_query($sql1);
	$datosapartado="";
	while($row = mysql_fetch_array($operacion))
	{
	
		$sql2 = "update apartado set cancelado = true where idapartado = " . $row["idapartado"];
		
		$datosapartado .=  $row["idapartado"] . "\t" .   $row["nombre"]  . "\t" .   $row["calle"] . " " .$row["numeroext"]. " " . $row["numeroint"] .  "\t $" . number_format(($row["deposito"]) ,2) . " \r\n\r\n<br><br>";
		$operacion2 = mysql_query($sql2);
	}		
	echo $datosapartado;
	if(	$datosapartado!="")
	{
		$datosapartado="<br><br>Se han cancelado apartados de los cuales falta su devoluci&oacute;n y estos son:<br><br> $datosapartado";
	}	
	
	
	
	//echo $ver;
	if($ver==1)
	{

		echo "Se ha realizado el cierre autom&aacute;tico a las " . date("H:i:s") . " Hrs.";
		$mensaje = "Se ha realizado el cierre automático a las " . date("H:i") . " Hrs. del día " . date("d-M-Y") . "\r\n\r\n<br><br> Se cobró hasta el momento de asuntos: $" . number_format($asuma ,2) . " de la siguiente forma: \r\n\r\n<br><br> $pasuntos \r\n\r\n\r\n<br><br>" . "Se cobró hasta el momento en inmuebles: $" . number_format($suma ,2)      . "  de la siguietne forma: \r\n\r\n<br><br> $pagos \r\n \r\n<br><br>Se aplicó interes a los siguientes (Se agrega el IVA al interes en el c&aacute;lculo)<br>: \r\n\r\n<br><br>" . $mensaje . $datosapartado;

		//$enviocorreo->enviar("mizocotroco@hotmail.com", "Cierre del día automático", $mensaje);
		//$enviocorreo->enviar("miguel@padilla-bujalil.com.mx,lucero@padilla-bujalil.com.mx,mapadilla@padilla-bujalil.com.mx", "Cierre del día", $mensaje);
		$enviocorreo->enviar("miguel@padilla-bujalil.com.mx,mapadilla@padilla-bujalil.com.mx", "Cierre del día", $mensaje);

	}
	else
	{

		echo "&nbsp;";
	}


}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}
?>
