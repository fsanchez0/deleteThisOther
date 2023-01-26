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
	$sql="select * from submodulo where archivo ='periodos.php'";
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

		$sql="insert into periodo (nombre,idmargen,numero) values ('$nombre',$idmargen,$numero)";
		//echo "<br>Agrego";
		$nombre="";
		$idmargen="";
		$numero="";
		
		break;

	case "1": //Borro

		$sql="delete from periodo where idperiodo=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update periodo set nombre='$nombre',idmargen=$idmargen,numero=$numero where idperiodo=$id";
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
		$sql="select * from periodo where idperiodo = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"]);
			$idmargen=$row["idmargen"];
			$numero=$row["numero"];
		}



	}
	else
	{
		$accion="0";
	}


	$sql="select * from margen";
	$margen = "<select name=\"idmargen\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$marcar ="";		
		if($idmargen==$row["idmargen"])
		{
			$marcar=" selected ";
		}
		$margen .= "<option value=\"" . $row["idmargen"] . "\" $marcar  >" . CambiaAcentosaHTML($row["margen"]) .  "</option>";

	}
	$margen .="</select>";



//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Periodos</h1>
<form>
<table border="0">
<tr>
	<td>Nombre:</td>
	<td><input type="text" name="nombre" value="$nombre"></td>
</tr>
<tr>
	<td>M&aacute;rgen:</td>
	<td>$margen</td>
</tr>
<tr>
	<td>N&uacute;mero:</td>
	<td><input type="text" name="numero" value="$numero"></td>
</tr>

<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';nombre.value='';idmargen.value=0;numero.value='';">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(nombre.value!='' && numero.value!='' && idmargen.value>0 ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&idmargen=' + idmargen.value + '&numero=' + numero.value )};if(this.value=='Agregar'&&privagregar.value==1){  cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&idmargen=' + idmargen.value + '&numero=' + numero.value )}};" >
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
	
	$sql="select * from periodo,margen where periodo.idmargen=margen.idmargen order by idperiodo ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Margen</th><th>N&uacute;mero</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<tr><td>" . $row["idperiodo"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["margen"] . "</td><td>" . $row["numero"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idperiodo"]  . "' )}\" $txtborrar>";
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