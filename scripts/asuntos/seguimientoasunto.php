<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$cantidad=@$_GET['cantidad'];
$idtipocargo=@$_GET['idtipocargo'];
$descripcion=@$_GET['descripcion'];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	
	$sql="select * from submodulo where archivo ='asuntos.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta=$row['ruta'];
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
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

	$cambio="";
	switch($accion)
	{
	case "0": // Cobros para el asunto
		//if ($cantidad==0) break;
		$diferencia = $cantidad;		
		$sql = "select * from estadocuenta where idtipocargo<>1 and pagado=false and idasunto = " . $id . " order by fecha";
		$datos = mysql_query($sql);
		$hoy=date('Y-m-d');
		while($row = mysql_fetch_array($datos))
		{	
			if($diferencia>=$row["cantidad"])
			{
				if($diferencia==$row["cantidad"])
				{
					$diferencia=0;
					$sql1="update estadocuenta set pagado = true, fechapagado='$hoy' where idestadocuenta=" . $row["idestadocuenta"] ;
					$operacion = mysql_query($sql1);
					break;
				}
				else
				{
					$diferencia=$diferencia - $row["cantidad"];
					$sql1="update estadocuenta set pagado = true, fechapagado='$hoy' where idestadocuenta=" . $row["idestadocuenta"] ;
					$operacion = mysql_query($sql1);
				}
			
			}
			else
			{
				$resto = $row["cantidad"] - $diferencia;
				$sql1 ="update estadocuenta set cantidad = " . $diferencia . ", pagado=true, fechapagado='$hoy' where idestadocuenta=" . $row["idestadocuenta"] ;
				$operacion = mysql_query($sql1);
				
				$sql1 ="insert into estadocuenta (idasunto, idtipocargo, descripcion, fecha, cantidad, pagado, idusuario) values ";
				$sql1 .="(" . $row["idasunto"] . "," . $row["idtipocargo"] . ",'" . CambiaAcentosaHTML($row["descripcion"]) . "','" . $row["fecha"] . "',$resto,false," .  $row["idusuario"] . ")";
				$operacion = mysql_query($sql1);
				$diferencia = 0;
				break;
			}
		}	
		
		if ($diferencia>0)
		{		
			$sql="select * from estadocuenta where idtipocargo=1 and pagado=false and idasunto = " . $id . " order by fecha";
			
			
			$datos = mysql_query($sql);
			while($row = mysql_fetch_array($datos))
			{	
				if($diferencia>=$row["cantidad"])
				{
					if($diferencia==$row["cantidad"])
					{
						$diferencia=0;
						$sql1="update estadocuenta set pagado = true, fechapagado='$hoy' where idestadocuenta=" . $row["idestadocuenta"] ;
						$operacion = mysql_query($sql1);
						break;
					}
					else
					{
						$diferencia=$diferencia - $row["cantidad"];
						$sql1="update estadocuenta set pagado = true, fechapagado='$hoy' where idestadocuenta=" . $row["idestadocuenta"] ;
						$operacion = mysql_query($sql1);
					}
						
				}
				else
				{
					$resto = $row["cantidad"] - $diferencia;
					$sql1 ="update estadocuenta set cantidad = " . $diferencia . ", pagado=true, fechapagado='$hoy' where idestadocuenta=" . $row["idestadocuenta"] ;
					$operacion = mysql_query($sql1);
						
					$sql1 ="insert into estadocuenta (idasunto, idtipocargo, descripcion, fecha, cantidad, pagado, idusuario) values ";
					$sql1 .="(" . $row["idasunto"] . "," . $row["idtipocargo"] . ",'" . CambiaAcentosaHTML($row["descripcion"]) . "','" . $row["fecha"] . "',$resto,false," .  $row["idusuario"] . ")";
					$operacion = mysql_query($sql1);
					$diferencia=0;
					break;
				}
			}	
			
			
			
		}
		
		if($diferencia>0)
		{
		
			$cambio="<br><font color=\"red\">Devolver cambio de $ $diferencia</font>";
		}
		
		
		break;

	case "1": //Nuevo cargo
		
		$sql="select * from asunto where idasunto=$id";
		$cerrado=false;
		$datos = mysql_query($sql);
		while($row = mysql_fetch_array($datos))
		{
			$cerrado=$row["cerrado"];		
		}
		if ($cerrado == false)
		{
			$hoy = date("Y") . "-" . date("m") . "-" . date("d");
			$sql1 ="insert into estadocuenta (idasunto, idtipocargo, descripcion, fecha, cantidad, pagado, idusuario,venciDo) values ";
			$sql1 .="(" . $id . "," . $idtipocargo . ",'" . $descripcion . "','" . $hoy . "'," . $cantidad . ",false," .  $misesion->usuario . ",false)";
			$operacion = mysql_query($sql1);
		}
		break;
/*
	case "3": //para recibo

		$sql = "update modulo set nombre='$nombremodulo' where idmodulo=$id";
		///echo "<br>Actualizo";
		$nombremodulo="";
*/
	}






	
	$expediente ="";
	$fechaapertura = "";
	$fechacierre="";
	$cliente = "";
	$descripcion="";
	$abogado = "";
	$gastos=0;
	$honorarios=0;
	$pgastos=0;
	$phonorarios=0;
	$sql="select * from asunto,directorio where asunto.iddirectorio=directorio.iddirectorio and idasunto =" . $id;
	$datos=mysql_query($sql);
	while($row = mysql_fetch_array($datos))
	{	
		$expediente = $row["expediente"];
		$fechaapertura = $row["fechaapertura"];
		$fechacierre=$row["fechacierre"];
		$cliente = CambiaAcentosaHTML($row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"]);
		$descripcion=CambiaAcentosaHTML($row["descripcion"]);
		$abogado = CambiaAcentosaHTML($row["abogado"]);	
	}
	
	$sql="select SUM(cantidad) as honorarios from estadocuenta where idtipocargo = 1 and idasunto =" . $id;
	$datos=mysql_query($sql);
	while($row = mysql_fetch_array($datos))
	{	
		$honorarios=$row["honorarios"];	
	}	
	$sql="select SUM(cantidad) as honorarios from estadocuenta where pagado=true and idtipocargo = 1 and idasunto =" . $id;
	$datos=mysql_query($sql);
	while($row = mysql_fetch_array($datos))
	{	
		$phonorarios=$row["honorarios"];	
	}	

	$thonorarios=$honorarios-$phonorarios;

	$sql="select SUM(cantidad) as losdemas from estadocuenta where idtipocargo <> 1 and idasunto =" . $id;
	$datos=mysql_query($sql);
	while($row = mysql_fetch_array($datos))
	{	
		$gastos=$row["losdemas"];	
	}	
	$sql="select SUM(cantidad) as losdemas from estadocuenta where pagado=true and idtipocargo <> 1 and idasunto =" . $id;
	$datos=mysql_query($sql);
	while($row = mysql_fetch_array($datos))
	{	
		$pgastos=$row["losdemas"];	
	}
	
	$tgastos=$gastos-$pgastos;
	
	$cglobal=$honorarios + $gastos;
	$pcglobal=$phonorarios + $pgastos;
	$tcglobal=$cglobal - $pcglobal;
	
	
	
	$idtipo="<select name=\"idtipocargo\">\n<option value=\"0\">Seleccione de la lista</option>";
	$sql = "select * from tipocargo";
	$datos=mysql_query($sql);
	while($row = mysql_fetch_array($datos))
	{	
		$idtipo .= "<option value=\"". $row["idtipocargo"] . "\">" . CambiaAcentosaHTML($row["tipocargo"]) . "</option>";
		
	}	
	$idtipo .="</select>";
	
	
	
	$igualas="<table border=\"1\"><tr><th colspan=5>Igualas vigentes</th></tr><tr><th>Id</th><th>T. Cargo</th><th>Periodo</th><th>Cantidad</th><th>Acciones</th></tr>";
	$sql="select i.idiguala as idi, tipocargo, nombre, cantidad from iguala i, tipocargo t, periodo p where i.idasunto=$id and i.idperiodo = p.idperiodo and i.idtipocargo = t.idtipocargo and cancelado=false";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$igualas .= "<tr><td>" . $row["idi"] . "</td>";
		$igualas .= "<td>" . $row["tipocargo"]  . "</td>";
		$igualas .= "<td>" . $row["nombre"] . "</td>";
		$igualas .= "<td>" . $row["cantidad"] . "</td>";			
		$igualas .= "<td><input type=\"button\" value=\"cancelar\" onClick=\"cargarSeccion('$ruta/igualas.php','contenido','accion=3&ida=$id&idi=" . $row["idi"] . "')\"></td></tr>";		
	}
	$igualas .="</table>";	
	
	
	/*
	$igualas="<table border=\"1\"><tr><th>T.Cargo</th><th>Periodo</th><th>Cantidad</th><th>Accion</th></tr>";
	$igualas .="<tr><td>Honorarios</td><td>Mensual</td><td>5000</td><td><input type=\"button\" value=\"Cancelar\"></td></tr>";
	$igualas .="</table>";
	*/
	
	$edocuenta="window.open( '$ruta/asuntoedocuenta.php?asunto=" . $id . "');";
echo <<<datosasunto

<table border="0">
<tr>
	<td colspan="2">

		<table border="0" width="100%">
		<tr>
			<td><b>Asunto #:</b></td>
			<td>$id	</td>
		</tr>
		<tr>
			<td><b>Expediente:</b></td>
			<td>$expediente</td>
		</tr>
		<tr>
			<td><b>F. apertura:</b></td>
			<td>$fechaapertura</td>
		</tr>
		<tr>
			<td><b>F. cierre:</b></td>
			<td>$fechacierre</td>
		</tr>

		<tr>
			<td><b>Cliente:</b></td>
			<td>$cliente</td>
		</tr>
		<tr>
			<td><b>Descripci&oacute;n:</b></td>
			<td>$descripcion</td>
		</tr>
		<tr>
			<td><b>Abogado:</b></td>
			<td>$abogado</td>
		</tr>
		<tr>
			<td colspan = "2" align="center">
				<input type="button" value="Agregar Igualas" onClick="cargarSeccion('$ruta/igualas.php','contenido','ida=$id' )">&nbsp;&nbsp;&nbsp;<input type="button" value="Ver estado de cuenta" onClick="$edocuenta">
			</td>

		</tr>

		</table>



	</td>

</tr>
<tr>
	<td align="center" colspan="2">	$igualas</td>
</tr>
<tr>
<td>
<fieldset >
	<legend>Cobrar:</legend>
	<form >
		<center>
		$<input type="text" name="cantidad"  /><br>
		<input type="button" value="Cobrar" onClick = "cargarSeccion('$ruta/seguimientoasunto.php','contenido', 'accion=0&id=$id&cantidad=' + cantidad.value);"/>
		$cambio
		</center>
	</form>
</fieldset>


<fieldset >
	<legend>Nuevo Cargo:</legend>
	<form >

		Tipo $idtipo <br>
		<input type="text" name="porcentaje" id="porcentaje" size="2" onChange="cantidad.value=(this.value/100)*importe.value;"/>% de <input type="text" name="importe" id="importe" onChange="cantidad.value=this.value*(porcentaje.value/100);" /><br>
		Cantidad <input type="text" name="cantidad"  /><br>
		Descripci&oacute;n<br><textarea rows="5" cols="20" name="descripcion"></textarea><br>
		<center><input type="button" value="Agregar"  onClick = "cargarSeccion('$ruta/seguimientoasunto.php','contenido', 'accion=1&id=$id&idtipocargo='+ idtipocargo.value + '&descripcion=' + descripcion.value + '&cantidad=' + cantidad.value);" /></center>

	</form>
</fieldset>


</td>
<td>


<fieldset>
	<legend>Resumen:</legend>

		<div id="resumenasunto">
			<table border="0" width="100%">
			<tr>
				<td colspan="2"><b>Gastos</b></td>
			</tr>
			<tr>
				<td>Total:</td>
				<td>$gastos</td>
			</tr>
			<tr>
				<td>Pagado:</td>
				<td>$pgastos</td>
			</tr>
			<tr>
				<td>Saldo:</td>
				<td>$tgastos</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2"><b>Honorarios</b></td>
			</tr>
			<tr>
				<td>Total:</td>
				<td>$honorarios</td>
			</tr>
			<tr>
				<td>Pagado:</td>
				<td>$phonorarios</td>
			</tr>
			<tr>
				<td>Saldo:</td>
				<td>$thonorarios</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
				<td colspan="2"><b>Globales</b></td>
			</tr>
			<tr>
				<td>Total:</td>
				<td>$cglobal</td>
			</tr>
			<tr>
				<td>Pagado :</td>
				<td>$pcglobal</td>
			</tr>
			<tr>
				<td>Saldo :</td>
				<td>$tcglobal</td>
			</tr>

			</table>

		</div>
</fieldset>
</td>
</tr>
</table>




datosasunto;

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>