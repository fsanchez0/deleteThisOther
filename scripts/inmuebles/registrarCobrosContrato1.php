<?php

include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclass.php';
	
$idc=@$_GET["idc"];
$idcobro=@$_GET["idcobro"];
		
	
		$fechas = New Calendario;
		//$fechas->DatosConexion('localhost','root','','bujalil');
		//actualizar contrato y colocarlo como enedicion = false
		
		$sql="update contrato set enedicion=false where idcontrato = $idc";
		$operacion = mysql_query($sql);
	
		//tomar el dato de inicio de contrato para la aplicain del historial
		$sql="select * from contrato where idcontrato =$idc";
		$operacion = mysql_query($sql);
		extract( mysql_fetch_array($operacion));
		
		//Crear en el historial los cobros que fueron asociados al contrato
			
		//echo $sql = "update inquilino set direccionf= '" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . "',idestado = " . $row["idestado"] . ", colonia = '" . $row["colonia"] . "',cp = '" . $row["cp"] . "',delegacion = '" . $row["delmun"] . "',callei = '" . $row["calle"] . "',noexteriori = '" . $row["numeroext"] . "',nointeriori = '" . $row["numeroint"] . "',coloniai = '" . $row["colonia"] . "',cpi = '" . $row["cp"] . "',delmuni = '" . $row["delmun"] . "',paisi = '" . $row["pais"] . "',idestadoi = " . $row["idestado"] . " where idinquilino = $idinquilino";
		//$operacion = mysql_query($sql);
		
		
		
		$final="<table border='1'>";
		$final2="<table border='1'>";
		$va=0;
		$fechagenerado = date("Y") . "-" . date("m") . "-" . date("d");
		// Cambio 10/08/21
		// Se modifica la consulta para poder obtener los datos del contrato y poder determinar si se va a agregar
		// el dato parcialde al crear los registros en la historia
		$sql="SELECT * FROM cobros, periodo, tipocobro, contrato WHERE cobros.idtipocobro = tipocobro.idtipocobro AND cobros.idperiodo = periodo.idperiodo AND contrato.idcontrato = cobros.idcontrato AND contrato.idcontrato = $idc ";
		// Fin Cambio 10/08/21
		if(!empty($idcobro)){
			$sql = $sql." AND cobros.idcobros = $idcobro";
		}
		$sql = $sql." ORDER BY cobros.idprioridad";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			// Cambio 10/08/21
			// Se alamacena el dato del tipo de facturacion
			$facturacionPrevia = intval($row["facturaprevia"]);
			// Fin Cambio 10/08/21
			$fechagsistema =mktime(0,0,0,substr($fechainicio, 5, 2),substr($fechainicio, 8, 2),substr($fechainicio, 0, 4));
			$fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
			//verificar aqui si el tipo de prioridad, todas se generan para pago inmediato e iniciar con el proceso
			//de generacion de proximos vencimientos, para el INMEDIATO, poner fecha de vencimiento automatico de
			//un mes para fecha de vencimiento, aunque se deje la fecha de gracia el dia de la generacion

			$fechagracia = $fechas->fechagracia($fechanaturalpago);
			
			
			/*if($cantidadapa>0)
			{
				//$aux = $cantidadapa - ($row["cantidad"] + $row["iva"] );
				$aux = $cantidadapa - ($row["cantidad"] * (1 + ($row["iva"]/100)) );
				//echo "<br>$aux<br>";
				if($aux>0)
				{
					//$cantidadapa = $cantidadapa - ($row["cantidad"] + $row["iva"] );
					$cantidadapa = $cantidadapa - ($row["cantidad"] * (1 + ($row["iva"]/100)) );
					//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechagenerado . "'," . ($row["cantidad"] + $row["iva"] ) . "," . $row["iva"] . ",false, true ,false,false,'Aplicado del apartado',1)";
					//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechagenerado . "'," .  number_format(($row["cantidad"] + $row["iva"] ), 2, '.', '')  . "," .  number_format($row["iva"], 2, '.', '') . ",false, true ,false,false,'Aplicado del apartado',1,".$row["idcategoriat"].")";
					$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechagenerado . "'," .  number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '')  . "," .  number_format($row["iva"], 2, '.', '') . ",false, true ,false,false,'Aplicado del apartado',1,".$row["idcategoriat"].")";
					$va=1;
					
									
					
					if($row["idperiodo"]!=1)
					{
						//echo "<br>$sql1";
						$operacion1 = mysql_query($sql1);
						
						$idhistoriaf=mysql_insert_id();						
						$funcionrecibo="nuevaVP(" . $idhistoriaf . ",1)";
						//Crear el nuevo pago si se pago por completo						
						$final .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . ($row["cantidad"] * (1 + ($row["iva"]/100))  ) . "</td><td>Pagado</td><td>";
						$final .= '<p><input type="button" value="' . CambiaAcentosaHTML($row["tipocobro"]) . $addtext . ' (Recibo F)" onClick="' . $funcionrecibo . '"> </p>';
						$final .="</td></tr>";
						$final2 .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . ($row["cantidad"] * (1 + ($row["iva"]/100))  ) . "</td><td>Pagado</td><td>";
						$final2 .= '<p>Aplicado del apartado</p>';
						$final2 .="</td></tr>";
						$va=0;							
						//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "','" . $fechagracia . "'," . $row["cantidad"] . "," . $row["iva"] . ",false, false,false,false,1)";
						//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "','" . $fechagracia . "'," . number_format($row["cantidad"], 2, '.', '') . "," . number_format($row["iva"], 2, '.', '')  . ",false, false,false,false,1,".$row["idcategoriat"].")";
						$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "','" . $fechagracia . "'," . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "," . number_format($row["iva"], 2, '.', '')  . ",false, false,false,false,1,".$row["idcategoriat"].")";
					}
				}
				else
				{
					if($aux ==0)
					{
						$cantidadapa = $cantidadapa - ($row["cantidad"] * (1 + ($row["iva"]/100)) );
						//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . ($row["cantidad"] + $row["iva"] ) . "," . $row["iva"] . ",false, true ,false,false,'Aplicado del apartado',1)";
						//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . number_format(($row["cantidad"] + $row["iva"] ), 2, '.', '')  . "," . number_format($row["iva"], 2, '.', '')  . ",false, true ,false,false,'Aplicado del apartado',1,".$row["idcategoriat"].")";
						$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '')  . "," . number_format($row["iva"], 2, '.', '')  . ",false, true ,false,false,'Aplicado del apartado',1,".$row["idcategoriat"].")";
						$va=1;
						if($row["idperiodo"]!=1)
						{
							//echo "<br>$sql1";
							$operacion1 = mysql_query($sql1);
							
						$idhistoriaf=mysql_insert_id();						
						$funcionrecibo="nuevaVP(" . $idhistoriaf . ",1)";
						//Crear el nuevo pago si se pago por completo						
						$final .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "</td><td>Pagado</td><td>";
						$final .= '<p><input type="button" value="' . CambiaAcentosaHTML($row["tipocobro"]) . $addtext . ' (Recibo F)" onClick="' . $funcionrecibo . '"> </p>';
						$final .="</td></tr>";	
						$final2 .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "</td><td>Pagado</td><td>";
						$final2 .= '<p>Aplicado del apartado</p>';
						$final2 .="</td></tr>";
						
							$va=0;
							//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "','" . $fechagracia . "'," . $row["cantidad"] . "," . $row["iva"] . ",false, false,false,false,1,".$row["idcategoriat"].")";
							$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "','" . $fechagracia . "'," . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "," . $row["iva"] . ",false, false,false,false,1,".$row["idcategoriat"].")";
						}
						
					
					}
					else
					{
						
						$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . number_format( $cantidadapa, 2, '.', '') . "," . number_format($row["iva"], 2, '.', '') . ",false, true ,false,false,'Aplicado del apartado',1,".$row["idcategoriat"].")";
						//echo "<br>$sql1";
						$operacion1 = mysql_query($sql1);
						
						
						$idhistoriaf=mysql_insert_id();						
						$funcionrecibo="nuevaVP(" . $idhistoriaf . ",2)";
						//Crear el nuevo pago si se pago por completo						
						$final .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . $cantidadapa . "</td><td>Pagado</td><td>";
						$final .= '<p><input type="button" value="' . CambiaAcentosaHTML($row["tipocobro"]) . $addtext . ' (Recibo NF)" onClick="' . $funcionrecibo . '"> </p>';
						$final .="</td></tr>";							
						$final2 .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . $cantidadapa . "</td><td>Pagado</td><td>";
						$final2 .= '<p>Aplicado del apartado</p>';
						$final2 .="</td></tr>";
						
						
						
						$va=0;
						$aux = $aux * (-1);
						//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . $aux . ",0,false, false ,false,false,1)";
						$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . number_format($aux, 2, '.', '') . ",0,false, false ,false,false,1,".$row["idcategoriat"].")";
						$cantidadapa = 0;
					}
				
				}
			
				//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . $row["cantidad"] . "," . $row["iva"] . ",false, false,false,false)";
			}						
			else
			{*/
				//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . $row["cantidad"] . "," . $row["iva"] . ",false, false,false,false,1)";
				//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . number_format($row["cantidad"], 2, '.', '') . "," . number_format($row["iva"], 2, '.', '') . ",false, false,false,false,1,".$row["idcategoriat"].")";
				$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "," . number_format($row["iva"], 2, '.', '') . ",false, false,false,false".($facturacionPrevia==1?",6,":",1,").$row["idcategoriat"].")";
				echo "<br>$sql1<br>";
			
			//}
			//echo "<br>$sql1";
			$operacion1 = mysql_query($sql1);
			
			// Cambio 10/08/21
			// Se agrega codgio para capturar el id del nuevo registro, para que despues
			// se modifique el valor de parcialde en el registro de la historia
			$idhistoria = mysql_insert_id();

			if($facturacionPrevia==1){
				$sqlh2_1 = "UPDATE historia SET parcialde = $idhistoria WHERE idhistoria = $idhistoria";
				$operacion1 = mysql_query($sqlh2_1);
			}
			// Fin Cambio 10/08/21

//*********************************introducir la generacin del resto de los pagos*********************************
$fechaa="";
$fechat="";
$idco="";
$idcc="";
//$lista="";
$e="";
$i=0;
$HoyFecha = date('Y') . "-" . date('m') . "-" . date('d');


	$sqlh="select c.idcontrato as idc, cb.idcobros as idcc, numero, idmargen, cb.cantidad as cantidadc, cb.iva as ivac, cb.idprioridad as idprioridadc,  fechatermino, max(fechanaturalpago) as fechau, idcategoriat from historia h, contrato c, cobros cb, periodo where  h.idcontrato = c.idcontrato and concluido=false and cb.idcobros = h.idcobros and cb.idperiodo=periodo.idperiodo and numero >=1 group by c.idcontrato, cb.idcobros, fechatermino, cb.cantidad , cb.iva, cb.idprioridad ";
	$operacionh = mysql_query($sqlh);
	while($rowh = mysql_fetch_array($operacionh))
	{
		
		if ($idco != $rowh["idc"])
		{
			$idco=$rowh["idc"];
			$fechat = $rowh["fechatermino"];
		}
		$fechaa = $rowh["fechau"];
		//$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));		
		//$lista .= "<p> Se generarn parael contrato $idc, el cobro $idcc en las fechas partiendo de $fechaa a la fecha de termino $fechat:</p>";
		//$fechaa = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
//		$lista .= "<ol>";
		$i=0;
		$sqlh2="";
		while ($fechaa < $fechat )
		{
			$i++;
			if($sqlh2 != "")
			{
				//$lista .= $e;
				//$lista .= $sql2 . "<br>";
				//ejecuta el sql
				// Cambio 10/08/21
				// Se agrega codgio para capturar el id del nuevo registro, para que despues
				// se modifique el valor de parcialde en el registro de la historia	
				echo "<br>$sqlh2<br>";
				$operacionh2 = mysql_query($sqlh2);	
				
				$idhistoria = mysql_insert_id();

				if($facturacionPrevia==1){
					$sqlh2_1 = "UPDATE historia SET parcialde = $idhistoria WHERE idhistoria = $idhistoria";
					$operacion1 = mysql_query($sqlh2_1);
				}
				// Fin Cambio 10/08/21
			}
			
			$idcc=$rowh["idcc"];
			
			$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));
			$fechaa = $fechas->calculafecha($fechagsistema, $rowh["numero"], $rowh["idmargen"]);
			$ProxVencimiento=$fechas->fechagracia($fechaa);
			
			//$e = "<li>$i  $fechaa   y fecha de gracia $ProxVencimiento </li>";
			
			//$sqlh2="insert into historia (idcontrato,idcobros,fechagenerado,fechanaturalpago,fechagracia,cantidad,iva,interes,vencido,aplicado, idusuario,idprioridad,fechavencimiento,condonado,idmetodopago,idcategoriat) values(";			
			//$sqlh2 .= $idco . "," . $idcc . ",'" . $HoyFecha . "', '" . $fechaa  . "', '" . $ProxVencimiento . "'," .  number_format($rowh["cantidadc"],2,".","") . "," . number_format($rowh["ivac"],2,".","") . ",false,false,false," . $misesion->usuario . "," . $rowh["idprioridadc"] . " , '" . $ProxVencimiento . "',false,1,".$rowh["idcategoriat"].");";
			
            $sqlh2="insert into historia (idcontrato,idcobros,fechagenerado,fechanaturalpago,fechagracia,cantidad,iva,interes,vencido,aplicado, idusuario,idprioridad,fechavencimiento,condonado,idmetodopago,idcategoriat) values(";			
			$sqlh2 .= $idco . "," . $idcc . ",'" . $HoyFecha . "', '" . $fechaa  . "', '" . $ProxVencimiento . "'," .  number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "," . number_format($rowh["ivac"],2,".","") . ",false,false,false," . "3" . "," . $rowh["idprioridadc"] . " , '" . $ProxVencimiento . "',false,1,".$rowh["idcategoriat"].");";
			//echo "<br>";
				
		}
		$e="";
//		$lista .= "</ol>";
		//$lista .= "<br>";
	}			
			
			
//*******************************************************************************************************************
			
			if($va==1)
			{
						$idhistoriaf=mysql_insert_id();						
						$funcionrecibo="nuevaVP(" . $idhistoriaf . ",1)";
						//Crear el nuevo pago si se pago por completo						
						$final .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "</td><td>Pagado</td><td>";
						$final .= '<p><input type="button" value="' . CambiaAcentosaHTML($row["tipocobro"]) . $addtext . ' (Recibo F)" onClick="' . $funcionrecibo . '"> </p>';
						$final .="</td></tr>";
						$final2 .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "</td><td>Pagado</td><td>";
						$final2 .= '<p>Aplicado del apartado</p>';
						$final2 .="</td></tr>";						
						$va=0;				
			
			
			}
			
	
	
		}
		$final .= "</table>";
		$final2 .= "</table>";

		$edocuenta="window.open( '$ruta/edocuenta.php?contrato=$idc');";
		$enviomail="window.open( '$ruta/enviocontrato.php?idc=$idc&correo='+correo.value);";
		
		$accionboton="<input type =\"button\" value=\"Ver\" onClick=\"$edocuenta\"  />";
		
		$enviomail =  file_get_contents("http://rentascdmx.com/".$ruta."/enviocontrato.php?idc=$idc&correo=bancos@padillabujalil.com");
		
		echo "<br> Contrato $idc Generado satisfactoriamente $accionboton<br><br>$final<br>$enviomail";

		$sqlSaldo = "SELECT * FROM diferencia WHERE idinquilino=$idinquilino AND idinmueble=$idinmueble AND aplicado IS NULL";
		$operacionSaldo = mysql_query($sqlSaldo);
		$rowSaldo = mysql_fetch_array($operacionSaldo);
		if(is_null($rowSaldo)==false && sizeof($rowSaldo)>1)
		{	
			$saldoRuta="cargarSeccion('$ruta/cobro.php','contenido','paso=1&idcontrato=$idcontrato&efectivo=".($rowSaldo["importe"] * 1)."&idmetodopago=".$rowSaldo["metodopagod"]."&fechapago=".$rowSaldo["fecha"]."&iddiferencia=".$rowSaldo["iddiferencia"]."');";
			$botonSaldo="<br><strong>Cuenta con saldo a Favor. </strong><input type =\"button\" value=\"Aplicar Saldo\" onClick=\"$saldoRuta\"  />";
		}else{
			$botonSaldo="";
		}
		echo $botonSaldo;
?>