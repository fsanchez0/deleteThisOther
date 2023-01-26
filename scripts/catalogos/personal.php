<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$personal=@$_GET['personal'];
$idtipopersonal=@$_GET['idtipopersonal'];
$activo=@$_GET['activo'];
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
	$sql="select * from submodulo where archivo ='personal.php'";
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


	//inicia la variable que contendr· la consulta
	$sql="";

	//Segun la acciÛn que se tomÛ, se proceder· a editar el sql
	switch($accion)
	{
	case "0": // Agrego

		$sql="insert into personal (personal,idtipopersonal, pactivo) values ('$personal',$idtipopersonal,$activo)";
		//echo "<br>Agrego";
		$personal="";
		$idtipopersonal="";
		$activo="";
		
		break;

	case "1": //Borro

		$sql="delete from personal where idpersonal=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update personal set personal='$personal',idtipopersonal=$idtipopersonal,pactivo=$activo where idpersonal=$id";
		///echo "<br>Actualizo";
		$personal="";
		$idtipopersonal="";
		$activo="";

	}

	//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
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
		$sql="select * from personal where idpersonal = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$personal=CambiaAcentosaHTML($row["personal"]);
			$idtipopersonal=$row["idtipopersonal"];
			
			if($row["pactivo"]==1)
			{
				$activo=" checked ='true' ";
			}
			else
			{
				$activo="";
			}
		}
		



	}
	else
	{
		$accion="0";
	}


	$sql="select * from tipopersonal";
	$margen = "<select name=\"idtipopersonal\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$marcar ="";		
		if($idtipopersonal==$row["idtipopersonal"])
		{
			$marcar=" selected ";
		}
		$margen .= "<option value=\"" . $row["idtipopersonal"] . "\" $marcar  >" . CambiaAcentosaHTML($row["tipopersonal"]) .  "</option>";

	}
	$margen .="</select>";



//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Personal</h1>
<form>
<table border="0">
<tr>
	<td>Nombre:</td>
	<td><input type="text" name="personal" value="$personal"></td>
</tr>
<tr>
	<td>Tipo Personal:</td>
	<td>$margen</td>
</tr>
<tr>
	<td>Activo:</td>
	<td><input type="checkbox" name="activo" $activo ></td>
</tr>

<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';personal.value='';idtipopersonal.value=0;activo.checked=false;">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(personal.value!='' && idtipopersonal.value>0 ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&personal=' + personal.value + '&idtipopersonal=' + idtipopersonal.value + '&activo=' + activo.checked )};if(this.value=='Agregar'&&privagregar.value==1){  cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&personal=' + personal.value + '&idtipopersonal=' + idtipopersonal.value + '&activo=' + activo.checked)}};" >
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
	
	$sql="select * from personal p,tipopersonal tp where p.idtipopersonal=tp.idtipopersonal order by personal ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Tipo</th><th>Activo</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<tr><td>" . $row["idpersonal"] . "</td><td>" . $row["personal"] . "</td><td>" . $row["tipopersonal"] . "</td><td>" . $row["pactivo"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idpersonal"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idpersonal"]  . "' );\" $txteditar>";		
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