<?php

//Requiere asuntobusqueda.php para filtrar la lista

include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$expediente=@$_GET['expediente'];
$abogado=@$_GET['abogado'];
$descripcion=@$_GET['descripcion'];
$idcliente=@$_GET['idcliente'];


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
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
		$hoy = date('Y-m-d');
		$sql="insert into asunto (iddirectorio,descripcion,abogado,expediente,fechaapertura) values ($idcliente,'$descripcion','$abogado','$expediente','$hoy')";		
		$descripcion="";
		$expediente="";
		$abogado="";
		$idcliente="";
		
		break;

	case "1": //Borro
		
		$sql="delete from asunto where idasunto=$id";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update asunto set descripcion='$descripcion',iddirectorio=$idcliente,abogado='$abogado',expediente='$expediente' where idasunto=$id";
		$descripcion="";
		$expediente="";
		$abogado="";
		$idcliente="";
		break;
	case "4": //cerrar asunto
		$hoy = date('Y-m-d');
		$sql="update asunto set cerrado=true, fechacierre='$hoy' where idasunto=$id";
		$id="";
		break;
	
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
		$sql="select * from asunto where idasunto = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$descripcion=CambiaAcentosaHTML($row["descripcion"]);
			$idcliente=$row["iddirectorio"];
			$abogado=CambiaAcentosaHTML($row["abogado"]);
			$expediente=$row["expediente"];
		}



	}
	else
	{
		$accion="0";
	}

	// genero el select para el directorio
	$sql="select * from directorio order by nombre";
	$margen = "<select name=\"idcliente\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$marcar ="";		
		if($idcliente==$row["iddirectorio"])
		{
			$marcar=" selected ";
		}
		$margen .= "<option value=\"" . $row["iddirectorio"] . "\" $marcar  >" . CambiaAcentosaHTML($row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"]) . "</option>";

	}
	$margen .="</select>";



//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Asuntos</h1>
<form>
<table border="0">
<tr>
	<td>Cliente:</td>
	<td>$margen</td>
</tr>

<tr>
	<td>Descripcion:</td>
	<td><textarea name="descripcion" cols="20" rows="4">$descripcion</textarea></td>
</tr>
<tr>
	<td>Abogado:</td>
	<td><input type="text" name="abogado" value="$abogado"></td>
</tr>
<tr>
	<td>Expediente:</td>
	<td><input type="text" name="expediente" value="$expediente"></td>
</tr>
<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';descripcion.value='';idcliente.value=0;abogado.value='';expediente.value='';">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(idcliente.value>0 && descripcion.value!='' && abogado.value!='' ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&idcliente=' + idcliente.value + '&descripcion=' + descripcion.value + '&abogado=' + abogado.value + '&expediente=' + expediente.value )};if(this.value=='Agregar'&&privagregar.value==1){  cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&idcliente=' + idcliente.value + '&descripcion=' + descripcion.value + '&abogado=' + abogado.value + '&expediente=' + expediente.value )}};" >
		<input type="hidden" name="ids" value="$id">
		<input type="hidden" name="accion" value="$accion">
		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">

	</td>
</tr>
<tr>
	<td colspan="2">
		<fieldset >
			<legend>Filtro de asuntos:</legend>
			<form >
			<table border="0">
			<tr>
				<td>
				
				<input type="radio" name="filtro" value="1" checked onClick="txtfiltro.value=''; cargarSeccion('$ruta/asuntobusqueda.php', 'busquedaasunto', 'filtro=&opt=' + valoropt('filtro') + '&opt2=' + valoropt('filtro2'))" />Cliente<br>
				<input type="radio" name="filtro" value="0" onClick="txtfiltro.value=''; cargarSeccion('$ruta/asuntobusqueda.php', 'busquedaasunto', 'filtro=&opt=' + valoropt('filtro') + '&opt2=' + valoropt('filtro2'))" />Expediente<br>
				<input type="radio" name="filtro" value="2" onClick="txtfiltro.value=''; cargarSeccion('$ruta/asuntobusqueda.php', 'busquedaasunto', 'filtro=&opt=' + valoropt('filtro') + '&opt2=' + valoropt('filtro2'))" />Abogado<br>
				</td>
		
				<td>
					<input type="text" name="txtfiltro" size="40" onKeyUp="cargarSeccion('$ruta/asuntobusqueda.php', 'busquedaasunto', 'filtro=' + this.value + '&opt=' + valoropt('filtro') )"  />
				</td>
				<td>
					<input type="radio" name="filtro2" value="0" checked onClick="txtfiltro.value=''; cargarSeccion('$ruta/asuntobusqueda.php', 'busquedaasunto', 'filtro=&opt=' + valoropt('filtro') + '&opt2=' + valoropt('filtro2') )" />Abiertos<br>
					<input type="radio" name="filtro2" value="1" onClick="txtfiltro.value=''; cargarSeccion('$ruta/asuntobusqueda.php', 'busquedaasunto', 'filtro=&opt=' + valoropt('filtro') + '&opt2=' + valoropt('filtro2') )" />Cerrados<br>
				</td>
			</tr>
			
			</table>
			</form>
		</fieldset>
	
	</td>

</tr>
</table>
</form>


</center>
formulario1;

	//Genera la lista de los asuntos la primera vez
	$sql="select * from asunto,directorio where asunto.iddirectorio=directorio.iddirectorio and ( isnull(cerrado)=true or cerrado = 0)  order by idasunto ";
	$datos=mysql_query($sql);
	
	echo "<div name=\"busquedaasunto\" id=\"busquedaasunto\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Expediente</th><th>Cliente</th><th>Descripci&oacute;n</th><th>Abogado</th><th>Cerrado</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		echo "<tr><td>" . $row["idasunto"] . "</td><td>" . $row["expediente"] . "</td><td>" . CambiaAcentosaHTML($row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"]) . "</td><td>" . CambiaAcentosaHTML($row["descripcion"]) . "</td><td>" . CambiaAcentosaHTML($row["abogado"]) . "</td><td>" . $row["cerrado"] . "</td><td align=\"center\">";
		echo "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idasunto"]  . "' )}\" $txtborrar ><br>";
		echo "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idasunto"]  . "' )\" $txteditar><br>";
		echo "<input type=\"button\" value=\"Cerrar\" onClick=\" if(confirm('&iquest;Est&aacute; seguro que desea cerrar este asunto?')){cargarSeccion('$dirscript','contenido','accion=4&id=" .  $row["idasunto"]  . "' )}\" $txteditar><br>";
		echo "<input type=\"button\" value=\"Seguimiento\" onClick=\"cargarSeccion('$ruta/seguimientoasunto.php','contenido','id=" .  $row["idasunto"]  . "' )\" $txteditar>";
		echo "</td></tr>";
	}
	echo "</table></div>";





}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>