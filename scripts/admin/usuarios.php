<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$nombre=@$_GET['nombre'];
$apaterno=@$_GET['apaterno'];
$amaterno=@$_GET['amaterno'];
$usuario=@$_GET['usuario'];
$pwd=@$_GET['pwd'];
$activo=@$_GET['activo'];
$email=@$_GET['email'];

if(!$activo)
{
	$activo=0;
}

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='usuarios.php'";
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

		$sql="insert into usuario (nombre,apaterno,amaterno,usuario,pwd,activo,email) values ('$nombre','$apaterno','$amaterno','$usuario','$pwd',$activo,'$email')";
		//echo "<br>Agrego";
		$nombre="";
		$apaterno="";
		$amaterno="";
		$usuario="";
		$pwd="";
		$email="";
		$activo=0;

		break;

	case "1": //Borro

		$sql="delete from usuario where idusuario=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update usuario set nombre='$nombre',apaterno='$apaterno',amaterno='$amaterno',usuario='$usuario', pwd='$pwd', activo=$activo, email='$email'  where idusuario=$id";
		///echo "<br>Actualizo";
		$nombre="";
		$apaterno="";
		$amaterno="";
		$usuario="";
		$pwd="";
		$email="";
		$activo=0;

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
		$sql="select * from usuario where idusuario = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"]);
			$apaterno=CambiaAcentosaHTML($row["apaterno"]);
			$amaterno=CambiaAcentosaHTML($row["amaterno"]);
			$usuario=$row["usuario"];
			$pwd=$row["pwd"];
			$email=$row["email"];
			$activo=$row["activo"];
		}



	}
	else
	{
		$accion="0";
	}


	if($activo==0)
	{
		$checar="";

	}
	else
	{
		$checar=" checked ";
	}

//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Usuarios</h1>
<form>
<table border="0">
<tr>
	<td>Nombre:</td>
	<td><input type="text" name="nombre" value="$nombre"></td>
</tr>
<tr>
	<td>A. Paterno:</td>
	<td><input type="text" name="apaterno" value="$apaterno"></td>
</tr>
<tr>
	<td>A. Materno:</td>
	<td><input type="text" name="amaterno" value="$amaterno"></td>
</tr>
<tr>
	<td>Usuario:</td>
	<td><input type="text" name="usuario" value="$usuario"></td>
</tr>
<tr>
	<td>Contrase&ntilde;a:</td>
	<td><input type="password" name="pwd" value="$pwd"></td>
</tr>
<tr>
	<td>e-mail:</td>
	<td><input type="text" name="email" value="$email"></td>
</tr>
<tr>
	<td>Activo:</td>
	<td><input type="checkbox" name="activo" value="$activo" $checar> </td>
</tr>
<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';nombre.value='';apaterno.value='';amaterno.value='';usuario.value='';pwd.value='';email.value='';activo.checked=false">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(activo.checked){act=1}else{act=0}; if(usuario.value!='' && pwd.value!='' ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&usuario=' + usuario.value + '&pwd=' + pwd.value + '&email=' + email.value + '&activo=' + act)};if(this.value=='Agregar'&&privagregar.value==1){  cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&usuario=' + usuario.value + '&pwd=' + pwd.value + '&email=' + email.value + '&activo=' + act)}};" >
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

	$sql="select * from usuario ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>A. Paterno</th><th>A. Materno</th><th>Usuario</th><th>e-mail</th><th>Activo</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		echo "<tr><td>" . $row["idusuario"] . "</td><td>" . CambiaAcentosaHTML($row["nombre"]) . "</td><td>" . CambiaAcentosaHTML($row["apaterno"]) . "</td><td>" . CambiaAcentosaHTML($row["amaterno"]) . "</td><td>" . $row["usuario"] . "</td><td>" . $row["email"] . "</td><td>" . $row["activo"] . "</td><td>";
		echo "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idusuario"]  . "' )}\" $txtborrar>";
		echo "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idusuario"]  . "' )\" $txteditar>";
		echo "</td></tr>";
	}
	echo "</table></div>";





}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>