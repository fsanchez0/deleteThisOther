<?php
//Este modulo consta de 3 archivos:
//privilegios.php (El script principal)
//submodulopriv.php (El que se encarga de actualizar al submodulo para la asignación)
//listapriv.php (El que hace las operaicones de ABC de la tabla)
//Los últimos 2, deben de estar en la misma ruta que el principal, ya que las referencias se
//hacen partiendo de la ruta original, de otr aforma no se pueden acceder y funcionará mal el modulo

include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$idsubmodulo=@$_GET["idsubmodulo"];
$ver=@$_GET["ver"];
$add=@$_GET["add"];
$borrar=@$_GET["borrar"];
$editar=@$_GET["editar"];
$idusu=@$_GET["usuario"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='privilegios.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$rutaf=$row['ruta'] ;
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}


	if ($priv[0]!='1')
	{
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}

	if ($priv[1]=='1')
	{
		$txtagregar = "";
	}
	else
	{
		$txtagregar = " DISABLED ";
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

	$boton1="Limpiar";
	$boton2="Agregar";




	//preparo la lista del select para los usuarios
	$sql="select * from usuario where activo = true";
	$usuarios = "<select name=\"usuario\" onChange=\"cargarSeccion('" . $rutaf . "/listapriv.php','listapriv','usuario=' + this.value  ); \"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$marcar ="";
		if($idusu==$row["idusuario"])
		{
			$marcar=" checked ";
		}
		$usuarios .= "<option value=\"" . $row["idusuario"] . "\"   >" . $row["usuario"] . "(" . CambiaAcentosaHTML($row["nombre"] . " " . $row["apaterno"]) . ")</option>";

	}
	$usuarios .="</select>";

	//preparo la lista del select para los modulos
	$sql="select * from modulo";
	$modulos = "<select name=\"modulo\" onChange=\"cargarSeccion('" . $rutaf . "/submodpriv.php','prsubmenu','id=' + this.value  ); \"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if (@$idmodulo==$row["idmodulo"])
		{
			$seleccionopt=" SELECTED ";
		}
		$modulos .= "<option value=\"" . $row["idmodulo"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["nombre"]) . "</option>";

	}
	$modulos .="</select>";




echo <<<formulario1
<center>
<h1>Privilegios</h1>
<form>
<table border="0">
<tr>
	<td valign="top"><b>Usuario:</b></td>
	<td>

		$usuarios

	</td>
</tr>
<tr>
	<td valign="top"><b>M&oacute;dulo</b></td>
	<td>
		$modulos
	</td>
</tr>
<tr>
	<td valign="top"><b>Sub m&oacute;dulo</b></td>
	<td>
		<div id="prsubmenu">
			<select name="idsubmodulo" size="4">
			</select>
		</div>
	</td>
</tr>
<tr>
	<td valign="top"><b>Privilegios</b></td>
	<td>
		<table border="0">
		<tr align="center">
			<td>Leer<br><input type="checkbox" name="leer"></td>
			<td>Agregar<br><input type="checkbox" name="add"></td>
			<td>Editar<br><input type="checkbox" name="editar"></td>
			<td>Borrar<br><input type="checkbox" name="borrar"></td>

		</tr>

		</table>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="button" value="$boton1" onClick="this.value='Limpiar';leer.checked=false;add.checked=false;editar.checked=false;borrar.checked=false;" />&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" $txtagregar onClick="if(add.checked || leer.checked || editar.checked || borrar.checked){  cargarSeccion('$rutaf/listapriv.php','listapriv','accion=0&submodulo=' + idsubmodulo.value + '&ver=' + leer.checked + '&add=' + add.checked + '&editar=' + editar.checked + '&borrar=' + borrar.checked + '&usuario=' + usuario.value)};" />
	</td>
</tr>
</table>
</form>
</center>
formulario1;


	echo "<div name=\"listapriv\" id=\"listapriv\" class=\"scroll\">";

	echo "</div>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>