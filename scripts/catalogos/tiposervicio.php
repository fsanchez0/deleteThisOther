<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$nombre=@$_GET['nombre'];
$numero=@$_GET['numero'];
$idmargen=@$_GET['idmargen'];
/*
if(!$activo)
{
	$activo=0;
}
*/
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='tiposervicio.php'";
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

	//para el privilegio de editar, si es negado deshabilida el bot?n
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el bot?n
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}


	//inicia la variable que contendr? la consulta
	$sql="";

	//Segun la acci?n que se tom?, se proceder? a editar el sql
	switch($accion)
	{
	case "0": // Agrego

		$sql="insert into tiposervicio (tiposervicio,idperiodo,diasanticipacion) values ('$nombre',$idmargen,$numero)";
		//echo "<br>Agrego";
		$nombre="";
		$idmargen="";
		$numero="";
		
		break;

	case "1": //Borro

		$sql="delete from tiposervicio where idtiposervicio=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update tiposervicio set tiposervicio='$nombre',idperiodo=$idmargen,diasanticipacion=$numero where idtiposervicio=$id";
		///echo "<br>Actualizo";
		$nombre="";
		$idmargen="";
		$numero="";

	}

	//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
	//Preparo las variables para los bot?nes
	$boton1="Limpiar";
	$boton2="Agregar";

	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la acci?n a realizar para la siguiente presi?n del bot?n agregar
	//en su defecto, sera acc?n agregar
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


	$sql="select * from periodo";
	$margen = "<select name=\"idperiodo\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$marcar ="";		
		if($idmargen==$row["idperiodo"])
		{
			$marcar=" selected ";
		}
		$margen .= "<option value=\"" . $row["idperiodo"] . "\" $marcar  >" . CambiaAcentosaHTML($row["nombre"]) .  "</option>";

	}
	$margen .="</select>";



//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Tipo servicio</h1>
<form>
<table border="0">
<tr>
	<td>Tipo servicio:</td>
	<td><input type="text" name="nombre" value="$nombre"></td>
</tr>
<tr>
	<td>Periodo:</td>
	<td>$margen</td>
</tr>
<tr>
	<td>Notificacion (dias antes):</td>
	<td><input type="text" name="numero" value="$numero"></td>
</tr>

<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';nombre.value='';idmargen.value=0;numero.value='';">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(nombre.value!='' && numero.value!='' && idperiodo.value>0 ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&idmargen=' + idperiodo.value + '&numero=' + numero.value )};if(this.value=='Agregar'&&privagregar.value==1){  cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&idmargen=' + idperiodo.value + '&numero=' + numero.value )}};" >
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

	//echo CambiaAcentosaHTML($html);
	
	$sql="select * from periodo p,tiposervicio ts where p.idperiodo=ts.idperiodo order by idtiposervicio ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Tipo servicio</th><th>Periodo</th><th>Notificacion</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<tr><td>" . $row["idtiposervicio"] . "</td><td>" . $row["tiposervicio"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["diasanticipacion"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idtiposervicio"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idtiposervicio"]  . "' )\" $txteditar>";
		
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