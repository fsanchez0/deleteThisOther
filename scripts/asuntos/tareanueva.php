<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$idusuario=@$_GET['idusuario'];
$idasunto=@$_GET['idasunto'];
$tarea=@$_GET['tarea'];
$vencimiento=@$_GET['vencimiento'];
$notas=@$_GET['notas'];
$importancia=@$_GET['importancia'];


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='tareanueva.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
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


	//inicia la variable que contendrá la consulta
	$sql="";

	//Segun la acción que se tomó, se procederá a editar el sql
	switch($accion)
	{
	case "0": // Agrego
		$fecha=date('Y') . "-" . date('m') . "-" . date('d');

		$sql="insert into tarea (idasunto,idusuario,tarea,vencimiento,notatarea) values ($idasunto,$idusuario,'$tarea','$vencimiento','$notas')";
		//echo "<br>Agrego";
		$idasunto="";
		$idusuario="";
		$tarea="";
		$vendimiento="";
		$notas="";

		break;

	case "1": //Borro

		$sql="delete from tarea where idtarea=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo
		$fecha= date('Y') . "-" . date('m') . "-" . date('d');
		$sql = "update tarea set idasunto=$idasunto,idusuario=$idusuario,vencimiento='$vencimiento',notatarea='$notas',tarea='$tarea' where idtarea=$id";
		///echo "<br>Actualizo";
		$idasunto="";
		$idusuario="";
		$tarea="";
		$vencimiento="";
		$notas="";

	}

	//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{
	  //echo $sql;
		$operacion = mysql_query($sql);

	}
	//Preparo las variables para los botónes
	$boton1="Limpiar";
	$boton2="Agregar";

	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la acción a realizar para la siguiente presión del botón agregar
	//en su defecto, sera accón agregar
	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from tarea where idtarea = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$idasunto=$row["idasunto"];
			$idusuario=$row["idusuario"];
			$tarea=CambiaAcentosaHTML($row["tarea"]);
			$vencimiento=$row["vencimiento"];
			$notas=CambiaAcentosaHTML($row["notatarea"]);
		}



	}
	else
	{
		$accion="0";
	}

	//preparo la lista del select para los modulos
	$sql="select * from usuario";

	$usuarioselect = "<select name=\"idusuario\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($idusuario==$row["idusuario"])
		{
			$seleccionopt=" SELECTED ";
		}
		$usuarioselect .= "<option value=\"" . $row["idusuario"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["usuario"]) . "</option>";

	}
	$usuarioselect .="</select>";



	//preparo la lista del select para la importancia
	$sql="select * from asunto where cerrado = 0";

	$asuntoselect = "<select name=\"idasunto\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($idasunto==$row["idasunto"])
		{
			$seleccionopt=" SELECTED ";
		}
		$asuntoselect .= "<option value=\"" . $row["idasunto"] . "\" $seleccionopt>(" . $row["idasunto"] . ")" . CambiaAcentosaHTML($row["expediente"])  . "</option>";

	}
	$asuntoselect .="</select>";


//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Asignaci&oacute;n de Tareas</h1>
<form>
<table border="0">
<tr>
	<td>Asunto:</td>
	<td>
		$asuntoselect
	</td>
</tr>
<tr>
	<td>Usuario:</td>
	<td>
		$usuarioselect
	</td>
</tr>
<tr>
	<td>Tarea:</td>
	<td><input type="text" name="tarea" value="$tarea"></td>
</tr>
<tr>
	<td>Vencimiento(YYY-MM-DD):</td>
	<td><input type="text" name="vencimiento" value="$vencimiento"></td>
</tr>
<tr>
	<td>Notas:</td>
	<td>
		<textarea name="notas" cols="20" rows="4">$notas</textarea>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';idasunto.value='0';idusuario.value='0';tarea.value='';vencimiento.value='';notas.value='';">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(tarea.value!='' && vencimiento.value!='' && idasunto.value!='0' && idusuario.value!='0'  ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&tarea=' + tarea.value + '&notas=' + notas.value + '&idasunto=' + idasunto.value + '&idusuario=' + idusuario.value + '&vencimiento=' + vencimiento.value )};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&tarea=' + tarea.value + '&notas=' + notas.value + '&idasunto=' + idasunto.value + '&idusuario=' + idusuario.value + '&vencimiento=' + vencimiento.value )}};" >
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

	$sql="select idtarea, tarea, vencimiento,notatarea,usuario.nombre as usunombre ,expediente from tarea,asunto,usuario where tarea.idasunto = asunto.idasunto and tarea.idusuario = usuario.idusuario and terminado = 0 order by vencimiento, idtarea";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>No.</th><th>Tarea</th><th>Usuario</th><th>Asunto</th><th>Vencimiento (aaaa-mm-dd)</th><th>Notas</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		echo "<tr><td>" . $row["idtarea"] . "</td><td>" . CambiaAcentosaHTML($row["tarea"]) . "</td><td>" . CambiaAcentosaHTML($row["usunombre"]) . "</td><td>" . CambiaAcentosaHTML($row["expediente"]) . "</td><td>" . $row["vencimiento"] . "</td><td>" . CambiaAcentosaHTML($row["notatarea"]) . "</td><td>";
		echo "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idtarea"]  . "' )}\" $txtborrar>";
		echo "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idtarea"]  . "' )\" $txteditar>";
		echo "</td></tr>";
	}
	echo "</table></div>";





}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>