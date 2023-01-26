<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$prioridad=@$_GET['prioridad'];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='prioridades.php'";
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

		$sql="insert into prioridad (prioridad) values ('$prioridad')";
		//echo "<br>Agrego";
		$prioridad="";
		break;

	case "1": //Borro

		 $sql="delete from prioridad where idprioridad=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update prioridad set prioridad='$prioridad' where idprioridad=$id";
		///echo "<br>Actualizo";
		$prioridad="";

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
<h1>Prioridad</h1>
<form>
<table border="0">
<tr>
	<td>Prioridad:</td>
	<td><input type="text" name="prioridad" value="$prioridad"></td>
</tr>
<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="idmodulo.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';prioridad.value=''">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(prioridad.value!=''){   if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + idmodulo.value + '&prioridad=' + prioridad.value )};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + idmodulo.value + '&prioridad=' + prioridad.value )}};" >
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

	//echo CambiaAcentosaHTML($html);
	
	$sql="select * from prioridad";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Prioridad</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<tr><td>" . $row["idprioridad"] . "</td><td>" . $row["prioridad"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idprioridad"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idprioridad"]  . "&prioridad=" . $row["prioridad"] . "' )\" $txteditar>";
		//echo "<input type=\"hidden\" name=\"id\" value=\"" . $row["idmodulo"] . "\">";
		$html .= "</td></tr>";
		echo CambiaAcentosaHTML($html);
	}
	echo "</table></div>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>