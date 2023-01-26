<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$idc=@$_GET["idc"];
$accion=@$_GET["accion"];
$periodo=@$_GET["periodo"];
$tipocobro=@$_GET["tipocobro"];
$prioridad=@$_GET["prioridad"];
$cantidad=@$_GET["cantidad"];
$interes=@$_GET["interes"];
$iva=@$_GET["iva"];
$recibo=@$_GET["recibo"];


$cabecera = "";

//modificacion, agregar campos para la generacion automatica de conceptos y que genere los cargos periodicos
//en la fecha siguiente natural de pago para todos los  cargos, incluyendo los inmediatos, donde la fecha natural de pago
//depende del la fecha de inicio del contrato.
$fechanaturalpago=@$_GET["fechanaturalpago"];
$observaciones=@$_GET["observaciones"];
$fechas = New Calendario;
$misesion = new sessiones;

if($misesion->verifica_sesion()=="yes")
{	
	$idusuario = $misesion->usuario;
	$sql="select * from submodulo where archivo ='agregarcobro.php'";
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




	if($accion==1)
	{
		$fechanaturalpago=$_GET['fechanaturalpago'];
		//Agrego el cobro
		$sql="insert into cobros (idcontrato, idperiodo, idtipocobro, idprioridad, cantidad, interes, iva, idrgrupo) values ($idc, $periodo, $tipocobro, $prioridad,$cantidad, $interes, $iva,$recibo)";
		$operacion = mysql_query($sql);
			
		//tomar el id del cobro	
		$idcobro=mysql_insert_id();
			
		//Agrego al historial
		$fechas = New Calendario;
		//$fechas->DatosConexion('localhost','root','','bujalil');
		
				
		//Crear en el historial los cobros que fueron asociados al contrato
		$fechagenerado = date("Y") . "-" . date("m") . "-" . date("d");
		$sql="select * from cobros,periodo,tipocobro where cobros.idperiodo=periodo.idperiodo and cobros.idtipocobro=tipocobro.idtipocobro and idcobros =$idcobro";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
		
			$sqlh="select c.idcontrato as idc, cb.idcobros as idcc, numero, idmargen, cb.cantidad as cantidadc, cb.iva as ivac, cb.idprioridad as idprioridadc,fechainicio,  fechatermino, max(fechanaturalpago) as fechau from cargos_por_aplicar h, contrato c, cobros cb, periodo where  h.idcontrato = c.idcontrato and concluido=false and cb.idcobros = h.idcobros and cb.idperiodo=periodo.idperiodo and numero >=1 and cb.idcobros=$idcobro group by c.idcontrato, cb.idcobros, fechatermino, cb.cantidad , cb.iva, cb.idprioridad, fechainicio ";
			$operacionh = mysql_query($sqlh);
			$rowh = mysql_fetch_array($operacionh);
			$idco=$rowh["idc"];
			$fechaa = date("Y-m-") . substr($rowh["fechainicio"], 5, 2);
			$fechat = $rowh["fechatermino"];

		
		
			if($row["numero"]==0) //inmediato y verificar las fechas de generacion para hacer los cargos correctos sobre fecha colocada
			{
			

				//$fechagsistema = mktime(0,0,0,substr($fechagenerado, 5, 2),substr($fechagenerado, 8, 2),substr($fechagenerado, 0, 4));
				$fechagsistema = mktime(0,0,0,substr($fechanaturalpago, 5, 2),substr($fechanaturalpago, 8, 2),substr($fechanaturalpago, 0, 4));

				//$fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
			
			
			
				//$fechagsistema =mktime(0,0,0,substr($fechagenerado, 5, 2),substr($fechagenerado, 8, 2),substr($fechagenerado, 0, 4));
			
				$pfechagr = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);//feha del sistema + 1 (1) mes (2)
			
				$fechagracia = $fechas->fechagracia($pfechagr);
			
				$sql1="insert into cargos_por_aplicar (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado, observaciones, idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "','" . $fechagracia . "'," . $row["cantidad"] . "," . $row["iva"] . ",false, false,false,false,'$observaciones',".$row["idcategoriat"].")";
				$operacion1 = mysql_query($sql1);
			}
			else
			{
				//*********************************introducir la generaciÛn del resto de los pagos*********************************
				//falta determinar la fecha natural de pago inicial, que seria la última correspondiente del mes, para que para el siguiente cobro aparezca
				//el nuevo cobro,  esto es, verificar la fecha de hoy, 
				// verificar si el dia de hoy es menor al dia que se debe de pagar, si es así, colocar el dia del periodo anterior, si es mayor,
				//usar el mismo dia del periodo del periodo corriente, con eso ya generaria el siguiente pago en el procedimiento

				$fechaa="";
				$fechat="";
				$idco="";
				$idcc="";
				//$lista="";
				$e="";
				$i=0;
				$HoyFecha = date('Y') . "-" . date('m') . "-" . date('d');

				$sqlh="select c.idcontrato as idc, cb.idcobros as idcc, numero, idmargen, cb.cantidad as cantidadc, cb.iva as ivac, cb.idprioridad as idprioridadc,fechainicio,  fechatermino from contrato c, cobros cb, periodo where  c.idcontrato = cb.idcontrato and concluido=false and  cb.idperiodo=periodo.idperiodo and numero >=1 and cb.idcobros=$idcobro group by c.idcontrato, cb.idcobros, fechatermino, cb.cantidad , cb.iva, cb.idprioridad, fechainicio ";
				$operacionh = mysql_query($sqlh);
				$rowh = mysql_fetch_array($operacionh);
				$idco=$rowh["idc"];
				//$fechaa = date("Y-m-") . substr($rowh["fechainicio"], 5, 2)
				$fechat = $rowh["fechatermino"];
				if($fechanaturalpago >= $fechat)
				{
					$fechaa = $fechat;
				}
				else
				{
					$fechaa = $fechanaturalpago;
				}
				
				//$fechaa =$rowh["fechau"];
				
				

					
					$i=0;
					$sqlh2="";
					while ($fechaa <= $fechat )
					{
						$i++;
						if($sqlh2 != "")
						{
							//$lista .= $e;
							//$lista .= $sql2 . "<br>";
							//ejecuta el sql
							$operacionh2 = mysql_query($sqlh2);
						}
						
						$idcc=$rowh["idcc"];
					
						$ProxVencimiento=$fechas->fechagracia($fechaa);
						
						//$e = "<li>$i  $fechaa   y fecha de gracia $ProxVencimiento </li>";
						
						$sqlh2="insert into cargos_por_aplicar (idcontrato,idcobros,fechagenerado,fechanaturalpago,fechagracia,cantidad,iva,interes,vencido,aplicado, idusuario,idprioridad,fechavencimiento,condonado, observaciones,idcategoriat) values(";			
						$sqlh2 .= $idco . "," . $idcc . ",'" . $HoyFecha . "', '" . $fechaa  . "', '" . $ProxVencimiento . "'," .  number_format($rowh["cantidadc"],2,".","") . "," . number_format($rowh["ivac"],2,".","") . ",false,false,false," . $misesion->usuario . "," . $rowh["idprioridadc"] . " , '" . $ProxVencimiento . "',false,'$observaciones',".$row["idcategoriat"].");";
						//echo "<br>";
						$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));
						$fechaa = $fechas->calculafecha($fechagsistema, $rowh["numero"], $rowh["idmargen"]);				
					}
					//$ProxVencimiento=$fechas->fechagracia($fechaa);
					$operacionh2 = mysql_query($sqlh2);
					$e="";
			//		$lista .= "</ol>";
					//$lista .= "<br>";
					
				//*******************************************************************************************************************
			}
		
			
			
		}
			
			$periodo="";
			$tipocobro="";
			$prioridad="";
			$cantidad="";
			$interes="";
			$iva="";
			$recibo="";
			$fechanatualpago="";
			$observaciones="";
			
			
					
	}
	
//catalogos del fomulario
			
	$sql="select * from periodo where nombre='INMEDIATO'";
	$periodoselect= "<select name=\"periodo\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($periodo==$row["idperiodo"])
		{
			$seleccionopt=" SELECTED ";
		}
		$periodoselect .= "<option value=\"" . $row["idperiodo"] . "\" $seleccionopt>" . $row["nombre"] . "</option>";

	}
	$periodoselect .="</select>";
	
	$sql="select * from tipocobro";
	$tipocontratoselect= "<select name=\"tipocobro\"><option value=\"0\">Seleccione uno de la lista</option>";;
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($tipocobro==$row["idtipocobro"])
		{
			$seleccionopt=" SELECTED ";
		}
		$tipocontratoselect .= "<option value=\"" . $row["idtipocobro"] . "\" $seleccionopt>" . $row["tipocobro"] . "</option>";

	}
	$tipocontratoselect .="</select>";
	
	
	$sql="select * from prioridad";
	$prioridadselect= "<select name=\"prioridad\"><option value=\"0\">Seleccione uno de la lista</option>";;
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($prioridad==$row["idprioridad"])
		{
			$seleccionopt=" SELECTED ";
		}
		$prioridadselect .= "<option value=\"" . $row["idprioridad"] . "\" $seleccionopt>" . $row["prioridad"] . "</option>";

	}
	$prioridadselect .="</select>";
	
	
	$sql="select * from rgrupo";
	$rgruposelect= "<select name=\"recibo\"><option value=\"0\">Seleccione uno de la lista</option>";;
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if($row["idrgrupo"]==1)
			$seleccionopt=" SELECTED ";
		$rgruposelect .= "<option value=\"" . $row["idrgrupo"] . "\" $seleccionopt>" . $row["rgrupo"] . "</option>";

	}
	$rgruposelect .="</select>";
	
//para colocar la fecha de donde se partira a contar las parcialidades	
	$sqlh="select c.idcontrato as idc, cb.idcobros as idcc, numero, idmargen, cb.cantidad as cantidadc, cb.iva as ivac, cb.idprioridad as idprioridadc,fechainicio,  fechatermino, max(fechanaturalpago) as fechau from cargos_por_aplicar h, contrato c, cobros cb, periodo where  h.idcontrato = c.idcontrato and concluido=false and cb.idcobros = h.idcobros and cb.idperiodo=periodo.idperiodo and numero >=1 and cb.idcontrato=$idc group by c.idcontrato, cb.idcobros, fechatermino, cb.cantidad , cb.iva, cb.idprioridad, fechainicio ";
	$operacionh = mysql_query($sqlh);
	$rowh = mysql_fetch_array($operacionh);
	$idco=$rowh["idc"];
	$fechaa = date("Y-m-") . substr($rowh["fechainicio"], 8, 2);
	$fechat = $rowh["fechatermino"];
	
	if ($fechaa < date("Y-m-d"))
	{
		$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));
		$fechaa = $fechas->calculafecha($fechagsistema,$rowh["numero"], $rowh["idmargen"]);			
	
	}	
	if($fechat < date("Y-m-d"))
	{
		$fechaa=$fechat;
	}
	
		$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as telinq,tipocobro, cargos_por_aplicar.fechagenerado, cargos_por_aplicar.fechanaturalpago, cargos_por_aplicar.fechagracia, cargos_por_aplicar.fechapago, cargos_por_aplicar.cantidad, aplicado, cargos_por_aplicar.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, estado, pais, inmueble.tel as telinm, cargos_por_aplicar.iva as ivah,aplicado, condonado, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, cargos_por_aplicar.notas as hnotas, fiador.email as emailf FROM contrato, cobros, inquilino, inmueble, tipocobro,cargos_por_aplicar, fiador, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=cargos_por_aplicar.idcontrato and cargos_por_aplicar.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $idc and contrato.idfiador=fiador.idfiador and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais order by fechanaturalpago, tipocobro,interes,fechagenerado,fechapago, aplicado";
		$result = @mysql_query ($sql);	
		$row = mysql_fetch_array($result);
		$cabecera .= "<table border = \"1\">";
		$inquilino=@$_GET['inquilino'];
		$cabecera .= "<tr><td class='Cabecera'>Inquilino</td><td>" .$inquilino. ")</td></tr>\n";
		$direccion =@$_GET['direccion'] . " " . $row["pais"] . " " . $row["estado"];

		$cabecera .= "<tr><td class='Cabecera'>Direcci&oacute;n</td><td>$direccion </td></tr>\n";
		$elfiadorh = $row["fnombre"] . " " . $row["fnombre2"] . " " . $row["fapaterno"] . " " . $row["famaterno"]  . " (Tel. " . $row["ftel"] . ", email: " . $row["emailf"] . ")";
		$cabecera .= "<tr><td class='Cabecera'>Obligado solidario</td><td>$elfiadorh </td></tr>\n</table>";
		
//*************************************************	
	
	

	$listacobros="<table border=\"1\"><tr><th>Id</th><th>T. Cobro</th><th>Periodo</th><th>Prioridad</th><th>Cantidad</th><th>Interes</th><th>IVA</th></tr>";
		$sql="select * from cobros, tipocobro, periodo,prioridad where cobros.idprioridad=prioridad.idprioridad and cobros.idperiodo = periodo.idperiodo and cobros.idtipocobro = tipocobro.idtipocobro and cobros.idcontrato = $idc";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$listacobros .= "<tr><td>" . $row["idcobros"] . "</td>";
			$listacobros .= "<td>" . $row["tipocobro"]  . "</td>";
			$listacobros .= "<td>" . $row["nombre"] . "</td>";
			$listacobros .= "<td>" . $row["prioridad"] . "</td>";
			$listacobros .= "<td>" . $row["cantidad"] . "</td>";
			$listacobros .= "<td>" . $row["interes"] . "</td>";
			$listacobros .= "<td>" . $row["iva"] . "</td>";
			$listacobros .= "</tr>";		
		}
		$listacobros .="</table>";
		

$html = <<<paso2
<form>
<h3 align="center">Configuraci&oacute;n de pagos periodicos para el contrato $idc</h3>
<center>
$cabecera
<table border="1">
<tr>
	<td valign="top">
		<table border="1">
		<tr>
			<td>Tipo Cobro</td>
			<td>
				$tipocontratoselect
			</td>
		</tr>			
		<tr>
			<td>Periodo</td>
			<td>
				$periodoselect
			</td>
		</tr>
			
		<tr>
			<td>Prioridad</td>
			<td>
				$prioridadselect
			</td>
		</tr>
		<tr>
			<td>Fecha Natual de pago</td>
			<td><input type="text" name="fechanaturalpago" value="$fechaa"><br>(aaaa-mm-dd)</td>
		</tr>
		<tr>
			<td>Cantidad $</td>
			<td><input type="text" name="cantidad" value="$cantidad"></td>
		</tr>
		<tr>
			<td>Interes (n/100)</td>
			<td><input type="text" name="interes" value="$interes"></td>
		</tr>
		<tr>
			<td>IVA(%)</td>
			<td><input type="text" name="iva" value="$iva"></td>
		</tr>
		<tr>
			<td>Recibos de</td>
			<td>
				$rgruposelect
			</td>
		</tr>	
		<tr>
			<td>Observaciones</td>
			<td><input type="text" name="observaciones" value="$observaciones"></td>
		</tr>		
		
		</table>
	</td>
	<td valign="top">
		$listacobros
	</td>
	
</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="button" name="agregar" value="Agregar" onClick="cargarSeccion('$dirscript/agregarc.php','contenido','accion=1&idc=$idc&periodo='+periodo.value+'&tipocobro='+ tipocobro.value +'&prioridad='+prioridad.value+'&cantidad='+cantidad.value + '&interes='+ interes.value + '&iva=' + iva.value + '&recibo='+ recibo.value  + '&fechanaturalpago='+ fechanaturalpago.value  + '&observaciones='+ observaciones.value)">
			</td>
		</tr>

</table>


</form>
</center>
paso2;

	echo CambiaAcentosaHTML($html);
}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}



?>