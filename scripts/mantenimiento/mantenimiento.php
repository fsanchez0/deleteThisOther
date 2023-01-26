<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include "../general/calendarioclass.php";
include '../general/correoclass.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$idtiposervicio=@$_GET['idtiposervicio'];
$idcontrato=@$_GET['idcontrato'];
//$nombrer=@$_GET['nombrer'];
//$fecham=@$_GET['fecham'];
//$horam=@$_GET['horam'];
$reporte=@$_GET['reporte'];
$subsecuentes=@$_GET['subsecuentes'];


/*
if(!$activo)
{
	$activo=0;
}
*/
$enviocorreo = New correo;
$fechas = New Calendario;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='mantenimiento.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta =$row['ruta'];
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

	//para el privilegio de editar, si es negado deshabilida el botÛn
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el botÛn
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}

/*
	//inicia la variable que contendr· la consulta
	$sql="";
	$hoy = date('Y-m-d');
	//Segun la acciÛn que se tomÛ, se proceder· a editar el sql
	switch($accion)
	{
	case "0": // Agrego

		$sqlm="insert into mantenimiento (idcontrato,idtiposervicio,mantenimiento,fechamant,terminadom) values ($idcontrato,$idtiposervicio,'$reporte','$hoy',false)";
		$operacion = mysql_query($sqlm);
		
		$idmantemiento = mysql_insert_id();
		if($subsecuentes=='false')
		{
			$sql = "insert into mantenimientoseg (idmantenimiento, fechams, horams, fechacita,horacita,cambiofecha,cerrado, observacionesm) values ($idmantemiento,'$hoy','12:00:00','$hoy','12:00:00',0,0,'$reporte')";
			$operacion = mysql_query($sql);
		}
		else
		{

//**********************

			$sqlh="select c.idcontrato as idc, numero, idmargen,  fechatermino, fechainicio from mantenimiento m, contrato c, tiposervicio ts, periodo where  m.idcontrato = c.idcontrato and concluido=false and m.idtiposervicio = ts.idtiposervicio and ts.idperiodo=periodo.idperiodo and numero >=1 and m.idmantenimiento =$idmantemiento";
			$operacionh = mysql_query($sqlh);
			while($rowh = mysql_fetch_array($operacionh))
			{

				$idco=$rowh["idc"];
				$fechat = $rowh["fechatermino"];

				$fechaa = $rowh["fechainicio"];

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
						//echo $sqlh2;
						$operacionh2 = mysql_query($sqlh2);
					}
				
					//$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));
					//$fechaa = $fechas->calculafecha($fechagsistema, $rowh["numero"], $rowh["idmargen"]);
					$ProxVencimiento=$fechas->fechagracia($fechaa);

					$sqlh2 = "insert into mantenimientoseg (idmantenimiento, fechams, horams, fechacita,horacita,cambiofecha,cerrado,observacionesm) values "; 
					$sqlh2 .= "($idmantemiento,'$ProxVencimiento','$horam','$ProxVencimiento','$horam',0,0,'$reporte')";

					$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));
					$fechaa = $fechas->calculafecha($fechagsistema, $rowh["numero"], $rowh["idmargen"]);
				
				}
					if($sqlh2 != "")
					{
						//$lista .= $e;
						//$lista .= $sql2 . "<br>";
						//ejecuta el sql
						//echo $sqlh2;
					    $operacionh2 = mysql_query($sqlh2);
					}				


			}
			
//***********************		
		
		}
		
		$idtiposervicio="";
		$idcontrato="";
		$nombrer="";
		$fecham="";
		$horam="";
		$reporte="";
		$subsecuentes="";
		$sql="";
		//$enviocorreo->enviar("miguel@padilla-bujalil.com.mx, contabilidad@padilla-bujalil.com.mx ", "Cobro realizado", "Se asigno el mantenimiento al contrato $idco");

		
		
		break;

	case "1": //Borro

		$sql="delete from mantenimientoseg where idmantenimiento=$id";
		$operacion = mysql_query($sql);
		$sql="delete from mantenimiento where idmantenimiento=$id";
		//echo "<br>Borro";
		
		$idtiposervicio="";
		$idcontrato="";
		$nombrer="";
		$fecham="";
		$horam="";
		$reporte="";
		$subsecuentes="";
		break;

	case "3": //Actualizo

		$sql = "update mantenimiento set idtiposervicio=$idtiposervicio,idcontrato=$idcontrato,mantenimiento='$nombrer' where idmantenimiento=$id";
		///echo "<br>Actualizo";
		
		$idtiposervicio="";
		$idcontrato="";
		$nombrer="";
		$fecham="";
		$horam="";
		$reporte="";
		$subsecuentes="";

	}

	//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{

		echo $operacion = mysql_query($sql);

	}
	
	*/
	//Preparo las variables para los botÛnes
	$boton1="Limpiar";
	$boton2="Agregar";

	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la acciÛn a realizar para la siguiente presiÛn del botÛn agregar
	//en su defecto, sera accÛn agregar
	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from tiposervicio where idtiposervicio = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["tiposervicio"]);
			$idmargen=$row["idperiodo"];
			$numero=$row["diasanticipacion"];
		}



	}
	else
	{
		$accion="0";
	}


	$sql="select * from tiposervicio";
	$tiposervicioselect = "<select name=\"idtiposervicio\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$marcar ="";		
		if($idtiposervicio==$row["idtiposervicio"])
		{
			$marcar=" selected ";
		}
		$tiposervicioselect .= "<option value=\"" . $row["idtiposervicio"] . "\" $marcar  >" . CambiaAcentosaHTML($row["tiposervicio"]) .  "</option>";

	}
	$tiposervicioselect .="</select>";


	$sql="select * from contrato c, inquilino i where c.idinquilino = i.idinquilino and activo = true and concluido = false order by nombre, c.idcontrato";
	$contratoselect = "<select name=\"idcontrato\" onChange=\"cargarSeccion('$ruta/infocontrato.php','infocontrato','accion=2&id=' +this.value );alert('verificando servicios');cargarSeccion('$ruta/mcontrato.php','busquedacobro','accion=&idcontrato=' +this.value )\" ><option value=\"0\" >Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$marcar ="";		
		if($idcontrato==$row["idcontrato"])
		{
			$marcar=" selected ";
		}
		$contratoselect .= "<option value=\"" . $row["idcontrato"] . "\" $marcar  >"  .  CambiaAcentosaHTML($row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"]  . " " . $row["amaterno"]) . $row["idcontrato"] . "</option>";

	}
	$contratoselect .="</select>";


//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Mantenimiento</h1>
<form>
<table border="0">
<tr>
	<td>Tipo servicio:</td>
	<td>$tiposervicioselect</td>
	<td rowspan="7" ><div id="servidios"></div></td>
</tr>
<tr>
	<td>Contrato:</td>
	<td>$contratoselect</td>
</tr>
<tr>
	<td colspan="2"><div id="infocontrato"></div></td>
</tr>
<tr>
	<td>Reporte:</td>
	<td><input type="text" name="reporte" value="$reporte"></td>
</tr>
<tr>
	<td colspan="3" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';nombre.value='';idmargen.value=0;numero.value='';">&nbsp;&nbsp;&nbsp;&nbsp;
<!--		<input type="button" value="$boton2" name="agregar" onClick="if(idtiposervicio.value>0 && idcontrato.value>0  && reporte.value!=''){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&idtiposervicio=' + idtiposervicio.value + '&idcontrato=' + ididcontrato.value +  '&reporte=' + reporte.value)};if(this.value=='Agregar'&&privagregar.value==1){ subsecuentes = confirm('Si desea generar todas las citas del contrato presione ACEPTAR, si desea solo generar una sola cita precoine CANCELAR'); cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value  + '&idtiposervicio=' + idtiposervicio.value + '&idcontrato=' + idcontrato.value  + '&reporte=' + reporte.value + '&subsecuentes=' + subsecuentes )}}else{alert('Debe de llenar todos los campos');};" > -->
		<input type="button" value="$boton2" name="agregar" onClick="if(idtiposervicio.value>0 && idcontrato.value>0  && reporte.value!=''){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$ruta/mcontrato.php','busquedacobro','accion=' + accion.value + '&id=' + ids.value + '&idtiposervicio=' + idtiposervicio.value + '&idcontrato=' + ididcontrato.value +  '&reporte=' + reporte.value)};if(this.value=='Agregar'&&privagregar.value==1){ subsecuentes = confirm('Si desea generar todas las citas del contrato presione ACEPTAR, si desea solo generar una sola cita presione CANCELAR'); cargarSeccion('$ruta/mcontrato.php','busquedacobro','accion=' + accion.value + '&id=' + ids.value  + '&idtiposervicio=' + idtiposervicio.value + '&idcontrato=' + idcontrato.value  + '&reporte=' + reporte.value + '&subsecuentes=' + subsecuentes )}}else{alert('Debe de llenar todos los campos');};" >
		<input type="hidden" name="ids" value="$id">
		<input type="hidden" name="accion" value="$accion">
		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">

	</td>
</tr>
</table>
</form>
</center>
formulario1;

	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\"></div>";





}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>