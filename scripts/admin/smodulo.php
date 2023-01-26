<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$idmodulo=@$_GET['idmodulo'];
$nombre=@$_GET['nombre'];
$ruta=@$_GET['ruta'];
$archivo=@$_GET['archivo'];
$version=@$_GET['version'];
$importancia=@$_GET['importancia'];


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='smodulo.php'";
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

		$sql="insert into submodulo (nombre,idmodulo,fecha,version,ruta, archivo,idimportancia) values ('$nombre',$idmodulo,'$fecha','$version','$ruta','$archivo',$importancia)";
		//echo "<br>Agrego";
		$nombre="";
		$idmodulo="";
		$ruta="";
		$archivo="";
		$version="";

		break;

	case "1": //Borro

		$sql="delete from submodulo where idsubmodulo=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo
		$fecha= date('Y') . "-" . date('m') . "-" . date('d');
		$sql = "update submodulo set nombre='$nombre',idmodulo=$idmodulo,fecha='$fecha',version='$version', ruta='$ruta', archivo='$archivo', idimportancia=$importancia  where idsubmodulo=$id";
		///echo "<br>Actualizo";
		$nombre="";
		$idmodulo="";
		$ruta="";
		$archivo="";
		$version="";
		$importancia="";

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
		$sql="select * from submodulo where idsubmodulo = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"]);
			$idmodulo=$row["idmodulo"];
			$ruta=$row["ruta"];
			$archivo=$row["archivo"];
			$version=$row["version"];
			$importancia=$row["idimportancia"];
		}



	}
	else
	{
		$accion="0";
	}

	//preparo la lista del select para los modulos
	$sql="select * from modulo";

	$moduloselect = "<select name=\"idmodulo\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($idmodulo==$row["idmodulo"])
		{
			$seleccionopt=" SELECTED ";
		}
		$moduloselect .= "<option value=\"" . $row["idmodulo"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["nombre"]) . "</option>";

	}
	$moduloselect .="</select>";



	//preparo la lista del select para la importancia
	$sql="select * from importancia";

	$importanciaselect = "<select name=\"importancia\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($importancia==$row["idimportancia"])
		{
			$seleccionopt=" SELECTED ";
		}
		$importanciaselect .= "<option value=\"" . $row["idimportancia"] . "\" $seleccionopt>(" . $row["numero"] . ")" . CambiaAcentosaHTML($row["nombre"])  . "</option>";

	}
	$importanciaselect .="</select>";


//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Sub M&oacute;dulos</h1>
<form>
<table border="0">
<tr>
	<td>M&oacute;dulo:</td>
	<td>
		$moduloselect
	</td>
</tr>
<tr>
	<td>Sub M&oacute;dulo:</td>
	<td><input type="text" name="nombre" value="$nombre"></td>
</tr>
<tr>
	<td>Importancia:</td>
	<td>
		$importanciaselect
	</td>
</tr>
<tr>
	<td>Ruta:</td>
	<td><input type="text" name="ruta" value="$ruta"></td>
</tr>
<tr>
	<td>Archivo:</td>
	<td><input type="text" name="archivo" value="$archivo"></td>
</tr>
<tr>
	<td>Versi&oacute;n:</td>
	<td><input type="text" name="version" value="$version"></td>
</tr>
<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';nombre.value='';ruta.value='';idmodulo.value='0';importancia.value='0';archivo.value='';version.value=''">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(nombre.value!='' && ruta.value!='' && idmodulo.value!='0' && archivo.value!='' && version.value!='' ){   if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&ruta=' + ruta.value + '&idmodulo=' + idmodulo.value + '&archivo=' + archivo.value + '&version=' + version.value + '&importancia='+importancia.value)};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&ruta=' + ruta.value + '&idmodulo=' + idmodulo.value + '&archivo=' + archivo.value + '&version=' + version.value+ '&importancia='+importancia.value)}};" >
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

	$sql="select idsubmodulo, modulo.nombre as mnombre, submodulo.nombre as smnombre, ruta,archivo,version, fecha from modulo,submodulo where modulo.idmodulo = submodulo.idmodulo order by modulo.idmodulo, idsubmodulo";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>M&oacute;dulo</th><th>Sub m&oacute;dulo</th><th>Ruta</th><th>Archivo</th><th>Fecha (aaaa-mm-dd)</th><th>Versi&oacute;n</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		echo "<tr><td>" . $row["idsubmodulo"] . "</td><td>" . CambiaAcentosaHTML($row["mnombre"]) . "</td><td>" . CambiaAcentosaHTML($row["smnombre"]) . "</td><td>" . $row["ruta"] . "</td><td>" . $row["archivo"] . "</td><td>" . $row["fecha"] . "</td><td>" . $row["version"] . "</td><td>";
		echo "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idsubmodulo"]  . "' )}\" $txtborrar>";
		echo "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idsubmodulo"]  . "' )\" $txteditar>";
		echo "</td></tr>";
	}
	echo "</table></div>";





}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>