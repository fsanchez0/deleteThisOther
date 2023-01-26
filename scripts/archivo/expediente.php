<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$elemento=@$_GET['elemento'];
$ubicacion=@$_GET['ubicacion'];
$descripcion=@$_GET['descripcion'];
$numero =0;
$nuevo="";

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
	$sql="select * from submodulo where archivo ='expediente.php'";
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
		$fechac=date("Y-m-d");
		
		$sql="select * from archivo where idarchivo = $elemento";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{			
			$numero=$row["consecutivo"]+1;
			$sql="update archivo set consecutivo = $numero where idarchivo = $elemento";
			$operacion = mysql_query($sql);
			break;
		}		
		
		
		
		$sql="insert into expediente (idarchivo,ubicacion,fechacreacion,fechacambio, numero,descripcione) values ($elemento,'$ubicacion','$fechac','$fechac',$numero,'$descripcion')";
		//echo "<br>Agrego";
		$nuevo ="<b>Se ha generado con exito el expediente $elemento.$numero</b>";
		$elemento="";
		$ubicacion="";
		$descripcion="";
		$numero="";
		
		
		break;

	case "1": //Borro
		
		$sql="delete from documento where idexpediente=$id";
		$operacion = mysql_query($sql);
		$sql="delete from expediente where idexpediente=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update expediente set ubicacion='$ubicacion', descripcione='$descripcion' where idexpediente=$id";
		///echo "<br>Actualizo";
		$elemento="";
		$ubicacion="";
		$descripcion="";
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
		$sql="select * from expediente where idexpediente = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$ubicacion=CambiaAcentosaHTML($row["ubicacion"]);
			$descripcion=CambiaAcentosaHTML($row["descripcione"]);
			$elemento=$row["idarchivo"];			
		}



	}
	else
	{
		$accion="0";
	}


	$sql="select * from archivo order by archivo";
	$margen = "<select name=\"elemento\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$marcar ="";		
		if($elemento==$row["idarchivo"])
		{
			$marcar=" selected ";
		}
		$margen .= "<option value=\"" . $row["idarchivo"] . "\" $marcar  >" . CambiaAcentosaHTML($row["archivo"]) .  "</option>";

	}
	$margen .="</select>";



//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Expedientes</h1>
<form>
<table border="0">
<tr>
	<td>Elemento:</td>
	<td>$margen</td>
</tr>
<tr>
	<td>Ubicaci&oacute;n:</td>
	<td><input type="text" name="ubicacion" value="$ubicacion"></td>
</tr>
<tr>
	<td>Descripci&oacute;n:</td>
	<td><input type="text" name="descripcion" value="$descripcion"></td>
</tr>
<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';elemento.value=0;ubicacion.value='';descripcion.value='';">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="agregar.disabled = true; if(ubicacion.value!='' && elemento.value>0 ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&elemento=' + elemento.value + '&ubicacion=' + ubicacion.value + '&descripcion=' + descripcion.value  )};if(this.value=='Agregar'&&privagregar.value==1){  cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&elemento=' + elemento.value + '&ubicacion=' + ubicacion.value + '&descripcion=' + descripcion.value )}};" >
		<input type="hidden" name="ids" value="$id">
		<input type="hidden" name="accion" value="$accion">
		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">

	</td>
</tr>
</table>
</form>
$nuevo
</center>
formulario1;

	//echo CambiaAcentosaHTML($html);
	
	$sql="select * from archivo a,expediente e where a.idarchivo = e.idarchivo ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th width='200'>Elemento</th><th >Expediente</th><th width='200'>Ubicaci&oacute;n</th><th>Aperturado</th><th width='300'>Descripci&oacute;n</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<tr><td>" . $row["idexpediente"] . "</td><td>" . $row["archivo"] . "</td><td>" . $row["idarchivo"] . "." . $row["numero"] . "</td><td>" . $row["ubicacion"] . "</td><td>" . $row["fechacreacion"] . "</td><td>" . $row["descripcione"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?, se borrar&aacute;n tambi&eacute;n los seguimientos asociados')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idexpediente"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idexpediente"]  . "' )\" $txteditar>";
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