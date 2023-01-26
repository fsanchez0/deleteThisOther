<?php
//Este script es para condonar un interes durante el proceso del cierre del dia
//Toma el Id de la histora que se va condonar en el interes,
//se actualiza la fecha del siguiente vencimiento y ya no se aplica el interes para esa ocaci�n (mes o periodo)
// regresa un 1 para confirmar la correcta operaci�n de la condonaci�n.

include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/correoclassd.php';


$misesion = new sessiones;
$enviocorreo = New correo2;

if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='condonaciones.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] ;// . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}


	if ($priv[0]!='1')
	{
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}



	$id=@$_GET["id"];

	$fechas = New Calendario;
	//$fechas->DatosConexion('localhost','root','','bujalil');
	//$enlace = mysql_connect('localhost', 'root', '');
	//mysql_select_db('bujalil',$enlace) ;

//	$sql = "select historia.idcontrato as idcontra, historia.idcobros as idcobr, historia.cantidad as cantida, historia.interes as hinteres, historia.iva as hiva, cobros.interes as cinteres, fechanaturalpago,fechagenerado, fechagracia, fechavencimiento, historia.idusuario as idusuarioh, idhistoria, numero, idmargen, concluido, fechatermino, vencido, cobros.idprioridad as idprioridadc, cobros.iva as ivac, cobros.cantidad as cantidadc from historia,cobros,periodo, contrato where historia.idcobros = cobros.idcobros and cobros.idperiodo=periodo.idperiodo  and aplicado = false and historia.idcontrato = contrato.idcontrato and idhistoria=$id";
	$sql = "select historia.idcontrato as idcontra, historia.idcobros as idcobr, historia.cantidad as cantida, historia.interes as hinteres, historia.iva as hiva, cobros.interes as cinteres, fechanaturalpago,fechagenerado, fechagracia, fechavencimiento, historia.idusuario as idusuarioh, idhistoria, numero, idmargen, concluido, fechatermino, vencido, cobros.idprioridad as idprioridadc, cobros.iva as ivac, cobros.cantidad as cantidadc, terceros, tipocobro from historia,cobros,periodo,tipocobro, contrato, folios where cobros.idtipocobro=tipocobro.idtipocobro and tipocobro.idfolios = folios.idfolios and historia.idcobros = cobros.idcobros and cobros.idperiodo=periodo.idperiodo  and aplicado = false and historia.idcontrato = contrato.idcontrato and idhistoria=$id";

	$result = @mysql_query ($sql);
	while ( $row = mysql_fetch_array($result))
	{
		$hoy=date('Y') . "-" . date('m') . "-" . date('d');
		$fechavencimiento=$row["fechavencimiento"];
		$fechanpago=$row["fechanaturalpago"];

		$fechagsistema =mktime(0,0,0,substr($fechavencimiento, 5, 2),substr($fechanpago, 8, 2),substr($fechavencimiento, 0, 4));
		$fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
		$ProxVencimiento =$fechas->fechagracia($fechanaturalpago);


		//$sql2="update historia set condonado=true, aplicado=true, cantidad = cantidad + iva where idhistoria = " . $id;
		$sql2="update historia set condonado=true, aplicado=true, cantidad = cantidad where idhistoria = " . $id;
		$result2 = @mysql_query ($sql2);

		//****Env�o de correo electronico por la condonacion
		$sqlDatos="select calle, numeroext, numeroint, inm.colonia, delmun, inm.cp, nombre, nombre2, apaterno, amaterno from contrato c, inquilino inq, inmueble inm where c.idinquilino=inq.idinquilino and c.idinmueble=inm.idinmueble and idcontrato=".$row["idcontra"];
		$resultDatos=mysql_query($sqlDatos);
		$datos=mysql_fetch_array($resultDatos);

		$mensaje = "<br><center><img src='http://pyb.loginto.me/imagenes/headp1.jpg'></center>
				<center><h1>CONDONACION PADILLA & BUJALIL<h1></center>
				<div style='background-color:#DDDEDE;'>
				<center><p style = 'font-size:16px'>Se aplico una condonacion al Contrato:<strong> ".$row["idcontra"]."</strong>, ubicado en:<strong> ".$datos["calle"]. " ".$datos["numeroext"]. " ".$datos["numeroint"]. " ".$datos["colonia"]. " ".$datos["cp"]. " ".$datos["delmun"]. "</strong>, del inquilino:<strong> ".$datos["nombre"]. " ".$datos["nombre2"]. " ".$datos["apaterno"]. " ".$datos["amaterno"]. "</strong> </p></center>
				<center><h3>Concepto</h3></center>
				<center><p style='font-size:16px;background-color:#00a27d;'><strong> ".$row["tipocobro"]."</strong> con fecha de pago del <strong> ".$row["fechanaturalpago"]. "</strong> y con un importe de: <strong>$".number_format(($row["cantida"] + $row["hiva"]),2). "</strong> </p></center></div>";

		ob_start();
		
		$correo= "miguel@padilla-bujalil.com.mx";
		$envio=$enviocorreo->enviarp($correo, "Condonacion P&B", $mensaje);
		//$correo= "alberto@padilla-bujalil.com.mx";
		//$envio=$enviocorreo->enviarPrueba($correo, "Condonacion P&B", $mensaje);	

		ob_end_clean();

//+*************
		if($row["terceros"]== true && $row["hinteres"]==false)
		{
			cargaredoduenio($id, number_format(($row["hiva"]+$row["cantida"]),2,".",""));
		}		
//**************		
//aqui es donde se deber�a de colocar el registro en el estado de cuenta
//realizar cargo del honorario si es requerido		
		
// Aqui se crea la condonaci�n y ponde como fecha generado la fecha generado del original, y pone el interes del original (original=registro que se condona)
		$sql2="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechapago, cantidad, aplicado,interes,vencido, fechavencimiento,condonado,idmetodopago) ";
		//$sql2 .= " values (" . $row["idcobr"] . ", " . $row["idcontra"] . ", 1, " . $misesion->usuario . ", '" . $row["fechagenerado"] . "', '". $row["fechanaturalpago"] . "','" . $row["fechagracia"] . "','$hoy'," . (-1 * ($row["cantida"] + $row["hiva"])) . ", true," . $row["hinteres"] . ",false, '$hoy',false,1)";
		$sql2 .= " values (" . $row["idcobr"] . ", " . $row["idcontra"] . ", 1, " . $misesion->usuario . ", '" . $row["fechagenerado"] . "', '". $row["fechanaturalpago"] . "','" . $row["fechagracia"] . "','$hoy'," . (-1 * ($row["cantida"] )) . ", true," . $row["hinteres"] . ",false, '$hoy',false,1)";
		$result2 = @mysql_query ($sql2);

		/*$sql2 = "update historia set fechavencimiento = '" . $ProxVencimiento . "' where idhistoria = " . $id;
		$result2 = @mysql_query ($sql2);
		$sql2 = "update historia set vencido = true where idhistoria = " . $id;
		$result2 = @mysql_query ($sql2);
		*/

		if($row["hinteres"]==false || is_null($row["hinteres"])==true)
		{
			//Cuando es un pago comun, y requiere que se genere el siguiente en caso de no estar vencido
			if($row["vencido"]!=true)
			{
				//Independientemente de que actualie el vencimiento del pago para no aplicar interes, en el caso de que no este vencido
				//debe de preparar el pago del siguiente mes.
				$fechavencimiento=$row["fechavencimiento"];
				$fechanpago=$row["fechanaturalpago"];
				$fechagsistema =mktime(0,0,0,substr($fechanpago, 5, 2),substr($fechanpago, 8, 2),substr($fechanpago, 0, 4));

				$fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
				$ProxVencimiento =$fechas->fechagracia($fechanaturalpago);
				
				$fechagsistema =mktime(0,0,0,substr($fechanaturalpago, 5, 2),substr($fechanaturalpago, 8, 2),substr($fechanaturalpago, 0, 4));
				
				
				if($fechanaturalpago!=$fechanpago){
				
					//if (mktime(0,0,0,substr($ProxVencimiento, 5, 2),substr($ProxVencimiento, 8, 2),substr($ProxVencimiento, 0, 4)) < mktime(0,0,0,substr($row["fechatermino"], 5, 2),substr($row["fechatermino"], 8, 2),substr($row["fechatermino"], 0, 4)))
					if (mktime(0,0,0,substr($fechanaturalpago, 5, 2),substr($fechanaturalpago, 8, 2),substr($fechanaturalpago, 0, 4)) <= mktime(0,0,0,substr($row["fechatermino"], 5, 2),substr($row["fechatermino"], 8, 2),substr($row["fechatermino"], 0, 4)))
					{
						if($row["concluido"]==false || is_null($row["concluido"])==true)
						{
							$sql2="insert into historia (idcontrato,idcobros,fechagenerado,fechanaturalpago,fechagracia,cantidad,iva,interes,vencido,aplicado, idusuario,idprioridad,fechavencimiento,condonado,idmetodopago) values(";
							//$sql2 .= $row["idcontra"] . "," . $row["idcobr"] . ",'" . $hoy . "', '" . $fechanaturalpago . "', '" . $ProxVencimiento . "'," . $row["cantidadc"] . "," . $row["ivac"] . ",false,false,false," . $row["idusuarioh"] . ", " .$row["idprioridadc"] . " , '" . $ProxVencimiento . "', false,1)";
							$sql2 .= $row["idcontra"] . "," . $row["idcobr"] . ",'" . $hoy . "', '" . $fechanaturalpago . "', '" . $ProxVencimiento . "'," . ($row["cantidadc"] * (1+ ($row["ivac"]/100))) . "," . $row["ivac"] . ",false,false,false," . $row["idusuarioh"] . ", " .$row["idprioridadc"] . " , '" . $ProxVencimiento . "', false,1)";
					
							//$result2 = @mysql_query ($sql2);
						}
					}
					/*
					else
					{
						if($row["ultimo"]==false || is_null($row["ultimo"])==true)
						{
							$sql1="insert into historia (idcontrato, idcobros, fechagenerado, fechanaturalpago, fechagracia,cantidad,idusuario,idprioridad,notas,fechavencimiento, iva,condonado) ";
							$sql1 .= " values (" . $row["idc"] . ", " . $row["idcob"] . ",'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "'," . $row["ccantidad"] . ", " . $misesion->usuario . "," . $row ["idcprioridad"] . ",'" . $row["tipocobro"] . "','" . $fechagracia . "'," . $row["ivac"] . ",false)";
							$result2 = @mysql_query ($sql2);
							$sql2 = "update contrato set ultimo = true where idcontrato = " . $row["idc"];
							$result2 = @mysql_query ($sql2);
						
						}
						else
						{
							$fincontrato="Se ha llegado a la fecha de t&eacute;rmino del contrato, no se agregar&aacute;n m&aacute;s pagos";
						}
					
					
					}
					*/
				}
			}
		}


		echo "1";
	}


}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}

function cargaredoduenio($idhist, $imp)
{
//encuentro al due�o o due�os
	$sql = "select *, i.idinmueble as idinm, cn.idcontrato as idcn,c.idtipocobro as idtc from historia h,cobros c, inmueble i, duenioinmueble di, duenio d, contrato cn, tipocobro tc where c.idtipocobro = tc.idtipocobro and h.idcobros = c.idcobros and h.idcontrato = cn.idcontrato and cn.idinmueble = i.idinmueble and i.idinmueble = di.idinmueble and di.idduenio = d.idduenio and idhistoria = $idhist";
	$result = @mysql_query ($sql);
	while ($row = mysql_fetch_array($result))
	{	
//calcula honorario
		//$impedo = ($row["porcentaje"]/100) * $imp;
		//$impedo = ($row["porcentajed"]/100) * $imp;
		//$hon = $impedo * ($row["honorarios"]/100) * (-1);//cambiar al campo due�oinmueble
		//$ivah = $hon * ($row["ivad"]/100);
		
		$idd = $row["idduenio"];
		$idtc = $row["idtc"];
		$idinm = $row["idinm"];
		$idcon = $row["idcn"];
		$fechantp =$row["fechanaturalpago"];
		$tipocobrto = $row["tipocobro"];
		$fechaedod = date("Y") . "-" . date("m") . "-" . date("d");
		$horaedod = date("H") . ":" . date("i") . ":" . date("s");
		
		$sql0 = "select sum(iva) as ivah from historia where idcontrato = $idcon and fechanaturalpago = '$fechantp' and interes=false and idcobros =" . $row["idcbs"] ;
		$result0 = @mysql_query ($sql0);
		$row0 = mysql_fetch_array($result0);


		$imp0 = $imp;
		if($row0["ivah"]>0 && $verificado ==0)
		{
			$imp = $imp / (1+($row["ivad"]/100));
			$verificado = 1;
		}		

		if($idtc == 7 || $idtc == 4)
		{
			//$impedo = ($row["porcentaje"]/100) * $imp;
			$impedo = ($row["porcentajed"]/100) * $imp;
			$impedo0 = ($row["porcentajed"]/100) * $imp0;
				
			$hon = $impedo  * (-1);
			$ivah = $hon * ($row["ivad"]/100);
			$impedo = $impedo * (-1);
			
			
			$pcomicion=100;
		}
		else
		{
	//$impedo = ($row["porcentaje"]/100) * $imp;
			$impedo = ($row["porcentajed"]/100) * $imp;
			$impedo0 = ($row["porcentajed"]/100) * $imp0;
			
			$hon = $impedo * ($row["honorarios"]/100) * (-1);
			$impedo = $impedo * (-1);
			$ivah = $hon * ($row["ivad"]/100);		
		}
		
		
		
		$notas =$row["notas"];
		
		if(substr($notas,0,11)=="PARCIALIDAD")
		{
			$notas ="PARCIALIDAD";
		}		
		
		
		if($notas=="LIQUIDADO")
		{
			$notas ="FINIQUITO";
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
		

//insert del ingreso del duenio
		$sqling = "insert into edoduenio (idduenio,idcontrato, idinmueble, idtipocobro, fechaedo,horaedo,importe, reportado,liquidado,referencia,notaedo, iva, enviado, idhistoria)values";
		if($tipocobrto=="DEPOSITO")
			$sqling .= "($idd ,$idcon, $idinm, $idtc , '$fechaedod' , '$horaedod', $impedo0 , false, false, 'h_$idhist' , '$nota $tipocobrto en garantia',0, false,$idhist  )";
		else
			$sqling .= "($idd ,$idcon, $idinm, $idtc , '$fechaedod' , '$horaedod', $impedo0 , false, false, 'h_$idhist' , '$nota $tipocobrto correspondiente al mes de $mes',0, false,$idhist  )";			
		$resulting = @mysql_query ($sqling);

//insert del cobro de honorario
		$sqlhon = "insert into edoduenio (idduenio,idcontrato, idinmueble, idtipocobro,fechaedo,horaedo,importe, reportado,liquidado,referencia,notaedo, iva, enviado,idhistoria)values";
		if($tipocobrto=="DEPOSITO")
			$sqlhon .= "($idd ,$idcon, $idinm , $idtc , '$fechaedod' , '$horaedod', $hon , false, false, 'h_$idhist' , 'Honorario (" . $row["honorarios"] . "% mas 16% I.V.A) por corretaje de inmueble',$ivah, false ,$idhist )";
		else
			$sqlhon .= "($idd ,$idcon, $idinm , $idtc , '$fechaedod' , '$horaedod', $hon , false, false, 'h_$idhist' , 'Honorario (" . $row["honorarios"] . "% mas 16% I.V.A) de administracion $tipocobrto $mes',$ivah, false ,$idhist )";
		$resulting = @mysql_query ($sqlhon);
		
//insert del descuento por nota de credito	
		$impedo0 *= (-1); 	
		$sqling = "insert into edoduenio (idduenio,idcontrato, idinmueble, idtipocobro, fechaedo,horaedo,importe, reportado,liquidado,referencia,notaedo, iva, enviado, idhistoria, facturar)values";
		if($tipocobrto=="DEPOSITO")
			$sqling .= "($idd ,$idcon, $idinm, $idtc , '$fechaedod' , '$horaedod', $impedo , false, false, 'h_$idhist' , '$nota Condonacion $tipocobrto en garantia',0, false ,$idhist,false)";
		else 
			$sqling .= "($idd ,$idcon, $idinm, $idtc , '$fechaedod' , '$horaedod', $impedo , false, false, 'h_$idhist' , '$nota Condonacion $tipocobrto correspondiente al mes de $mes',0, false ,$idhist,false)";
		$resulting = @mysql_query ($sqling);		

	}


}


?>