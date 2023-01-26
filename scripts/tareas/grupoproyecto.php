<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$grupo=@$_GET['grupo'];


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='grupoproyecto.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
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

	$sql="";
	switch($accion)
	{
	case "0": // Agrego

		$sql="insert into grupoproyecto (grupoproyecto) values ('$grupo')";
		//echo "<br>Agrego";
		$nombremodulo="";
		$orden="";
		break;

	case "1": //Borro

		 $sql="delete from grupoproyecto where idgrupoproyecto=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update grupoproyecto set grupoproyecto='$grupo' where idgrupoproyecto=$id";
		///echo "<br>Actualizo";
		$grupo="";
		

	}
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
	$boton1="Limpiar";
	$boton2="Agregar";

	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
	}
	else
	{
		$accion="0";
	}



echo <<<formulario1
<center>
<h1>Grupos de Proyectos</h1>
<form>
<table border="0">
<tr>
	<td>Grupo:</td>
	<td><input type="text" name="grupo" value="$grupo"></td>
</tr>

<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="idmodulo.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';grupo.value='',orden.value=''">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(grupo.value!=''){   if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + idmodulo.value + '&grupo=' + grupo.value )};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + idmodulo.value + '&grupo=' + grupo.value  )}};" >
		<input type="hidden" name="idmodulo" value="$id">
		<input type="hidden" name="accion" value="$accion">
		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">

	</td>
</tr>
</table>
</form>
</center>
formulario1;

	$sql="select * from grupoproyecto";
	$datos=mysql_query($sql);
	echo "<center><div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Grupo</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		echo "<tr><td>" . $row["idgrupoproyecto"] . "</td><td>" . CambiaAcentosaHTML($row["grupoproyecto"]) . "</td><td>";
		echo "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idgrupoproyecto"]  . "' )}\" $txtborrar>";
		echo "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idgrupoproyecto"]  . "&grupo=" . CambiaAcentosaHTML($row["grupoproyecto"]) .  "' )\" $txteditar>";
		//echo "<input type=\"hidden\" name=\"id\" value=\"" . $row["idmodulo"] . "\">";
		echo "</td></tr>";
	}
	echo "</table></div></center>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>