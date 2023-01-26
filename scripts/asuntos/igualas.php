<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclass.php';


//$paso=@$_GET["paso"];
$ida=@$_GET["ida"];
$idi=@$_GET["idi"];
//$inquilino=@$_GET["inquilino"];
//$fiador=@$_GET["fiador"];
//$inmueble=@$_GET["inmueble"];
//$tipocontrato=@$_GET["tipocontrato"];
$fechainicio=@$_GET["fechainicio"];
//$fechatermino=@$_GET["fechatermino"];
//$deposito=@$_GET["deposito"];
$accion=@$_GET["accion"];
$periodo=@$_GET["periodo"];
$tipocargo=@$_GET["tipocargo"];
//$prioridad=@$_GET["prioridad"];
$cantidad=@$_GET["cantidad"];
//$interes=@$_GET["interes"];
//$iva=@$_GET["iva"];
$descripcion=@$_GET["descripcion"];
$idiguala=@$_GET["idiguala"];
//$nombre="";
//$nombre2="";
//$apaterno="";
//$amaterno="";
//$idinquilino="";
//$nombref="";
//$nombre2f="";
//$apaternof="";
//$amaternof="";
//$idinquilinof="";
//$idinmueble="";


$addtext="";
$fechas = New Calendario;
$enviocorreo = New correo;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$idusuario = $misesion->usuario;
	$sql="select * from submodulo where archivo ='asuntos.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		//$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}



	
	if ($priv[0]!='1')
	{
		//Es privilegio para poder ver eset modulo, y es negado
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}

	//para el privilegio de editar, si es negado deshabilida el botón
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el botón
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}




	
	switch ($accion)
	{

	case 1: //Agregar iguala
	
		//ver lo de lafecha de inicio por lo pronto poner la de hoy
		//$fechainicio=date("Y-m-d");
			
		$sql="insert into iguala (idasunto, idperiodo, idtipocargo, descripcion, cantidad, fechainicio, cancelado) values ($ida, $periodo, $tipocargo,'$descripcion',$cantidad, '$fechainicio', false)";
		$operacion = mysql_query($sql);
		$idi=mysql_insert_id();
		$fechagsistema =mktime(0,0,0,substr($fechainicio, 5, 2),substr($fechainicio, 8, 2),substr($fechainicio, 0, 4));
		
		
		//conslta para determinar el salto del intervalo de tiempo
		$sql="select * from periodo where  idperiodo = $periodo";
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
		$fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
		
		$sql ="insert into estadocuenta (idasunto, idtipocargo, descripcion, fecha, cantidad, pagado, idusuario,idiguala,fechavencimiento,vencido) values ";
		$sql .="($ida,$tipocargo,'" . CambiaAcentosaHTML($descripcion) . "','$fechainicio',$cantidad,false," .  $misesion->usuario . ",$idi,'$fechanaturalpago', false)";		
		$operacion = mysql_query($sql);
		
		
		$periodo="";
		$tipocargo="";
		$cantidad="";
		$descripcion="";		
		break;
		
	case 2: //Borrar cobro
		$sql="delete from iguala where idiguala=$idi";
		$operacion = mysql_query($sql);
		
	case 3: //cancelo iguala
		$sql="update iguala set cancelado = true where idiguala=$idi";
		$operacion = mysql_query($sql);		
	}
	
	
	$sql="select * from periodo";
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
	
	$sql="select * from tipocargo";
	$tipocontratoselect= "<select name=\"tipocargo\"><option value=\"0\">Seleccione uno de la lista</option>";;
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($tipocargo==$row["idtipocargo"])
		{
			$seleccionopt=" SELECTED ";
		}
		$tipocontratoselect .= "<option value=\"" . $row["idtipocargo"] . "\" $seleccionopt>" . $row["tipocargo"] . "</option>";

	}
	$tipocontratoselect .="</select>";
	
/*	
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
		if ($recibo==$row["idrgrupo"])
		{
			$seleccionopt=" SELECTED ";
		}
		$rgruposelect .= "<option value=\"" . $row["idrgrupo"] . "\" $seleccionopt>" . $row["rgrupo"] . "</option>";

	}
	$rgruposelect .="</select>";
*/	

	$listacargos="<table border=\"1\"><tr><th>Id</th><th>T. Cargo</th><th>Periodo</th><th>Cantidad</th><th>Acciones</th></tr>";
		$sql="select i.idiguala as idi, tipocargo, nombre, cantidad from iguala i, tipocargo t, periodo p where i.idasunto=$ida and i.idperiodo = p.idperiodo and i.idtipocargo = t.idtipocargo";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$listacargos .= "<tr><td>" . $row["idi"] . "</td>";
			$listacargos .= "<td>" . $row["tipocargo"]  . "</td>";
			$listacargos .= "<td>" . $row["nombre"] . "</td>";
			$listacargos .= "<td>" . $row["cantidad"] . "</td>";			
			$listacargos .= "<td><input type=\"button\" value=\"Borrar\" onClick=\"cargarSeccion('$ruta/igualas.php','contenido','accion=2&ida=$ida&idi=" . $row["idi"] . "');this.disabled =true\"></td></tr>";		
		}
		$listacargos .="</table>";


$html = <<<paso2
<form>
<h3 align="center">Configuraci&oacute;n de igualas para el asunto $ida</h3>
<center>
<input type="button" value="Regresar al Asunto" onClick="cargarSeccion('$ruta/seguimientoasunto.php','contenido','id=$ida' )">
<table border="1">
<tr>
	<td valign="top">
		<table border="1">
		<tr>
			<td>Tipo Cargo</td>
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
			<td>Fecha de inicio (aaaa-mm-dd)</td>
			<td><input type="text" name="fechainicio" value="$fechainicio"></td>
		</tr>
                <tr>
			<td>Importe $</td>
			<td><input type="text" name="importe" value=0 onChange="iva.value=((+porcentaje.value)/100)*(+importe.value); cantidad.value = 0 + (+iva.value) + (+this.value);"></td>
		</tr>
                <tr>
			<td> IVA <input type="text" value = 0 name="porcentaje" id="porcentaje" size="2" onChange="iva.value=((+this.value)/100) * (+importe.value); cantidad.value = 0 + +(iva.value) + (+importe.value);"/>%,</td>
			<td><input type="text" name="iva" value=0></td>
		</tr>                	
		<tr>
			<td>Cantidad  $</td>
			<td><input type="text" name="cantidad" value="$cantidad"></td>
		</tr>

		<tr>
			<td>Descripci&oacute;n</td>
			<td><input type="text" name="descripcion" value="$descripcion"></td>
		</tr>	
		
		
		</table>
	</td>
	<td valign="top">
		$listacargos
	</td>
	
</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="button" name="agregar" value="Agregar" onClick="cargarSeccion('$ruta/igualas.php','contenido','accion=1&ida=$ida&periodo='+periodo.value+'&tipocargo='+ tipocargo.value +'&cantidad='+cantidad.value +'&descripcion='+ descripcion.value +'&fechainicio='+ fechainicio.value );this.disabled =true">
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
	