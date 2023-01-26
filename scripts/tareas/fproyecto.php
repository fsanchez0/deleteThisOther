<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Formulario para el script de proyecto.php
//Es para mostrar los datos para editar o para agregar un elemento nuevo
//interactua con el otro script en las acciones de editar y nuevo
$accion = @$_GET["accion"];
$id=@$_GET["id"];


$listagrupos="";
$proyecto = "";
$desc="";
$grupo="";


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='proyecto.php'";
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
	
	
	
	$boton1="Cancelar";
	$boton2="Agregar";

	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		
		$sql="select * from proyecto where idproyecto = $id";
		$datos=mysql_query($sql);
		$row = mysql_fetch_array($datos);
		$proyecto = $row["proyecto"];
		$desc = $row["notaproyecto"];
		$grupo = $row["idgrupoproyecto"];
		
		
		
		
	}
	else
	{
		$accion="0";
	}	
	

	
	$sql="select * from grupoproyecto where idgrupoproyecto >1";
	$datos=mysql_query($sql);
	$marcar="";
	$listagrupos="<select name='grupof' id='grupof' ><option value='1'>No asignado</option>";
	while($row = mysql_fetch_array($datos))
	{
		
		if($grupo==$row["idgrupoproyecto"])
		{
			$marcar="selected";
		}
		
		$listagrupos .= "<option value='" . $row["idgrupoproyecto"] . "' $marcar>" . $row["grupoproyecto"] . "</option>";		
		$marcar="";
	}
	$listagrupos .="</select>";	
	
	

echo <<<formularioA
<form>
<table>
<tr>
	<td>Nombre</td>
	<td><input type="text" name="proyecto" value="$proyecto"/></td>

</tr>
<tr>
	<td>Grupo</td>
	<td>$listagrupos</td>

</tr>
<tr>
	<td colspan="2" >
	Descripcion:<br>
	<textarea name="desc" cols="30" rows="3">$desc</textarea>
	</td>

</tr>


<tr>
	<td colspan="2" align="center">
	

		<input type="button" value="$boton1" onClick="document.getElementById('frmproyectonuevo').innerHTML=''">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(proyecto.value!=''){   if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + idmodulo.value + '&proyecto=' + proyecto.value + '&grupo=' + grupof.value + '&desc=' + desc.value )};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + idmodulo.value + '&proyecto=' + proyecto.value + '&grupo=' + grupof.value + '&desc=' + desc.value )}};" >
		<input type="hidden" name="idmodulo" value="$id">
		<input type="hidden" name="accion" value="$accion">
		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">

	
	</td>

</tr>



</table>


</form>

formularioA;

	
	

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>	