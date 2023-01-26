<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$idsubmodulo=@$_GET["submodulo"];
$ver=@$_GET["ver"];
$add=@$_GET["add"];
$borrar=@$_GET["borrar"];
$editar=@$_GET["editar"];
$idusu=@$_GET["usuario"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='privilegios.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$rutaf=$row['ruta'] . "/";
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

 	if($priv[1]=='1' and $accion=="0")
	{

		//hacer el proceso de inserción
		$hoy = date('Y') . "-" . date('m') . "-" . date('d');
		$sql="select * from privilegio where idusuario=$idusu and idsubmodulo=$idsubmodulo";
		$operacion = mysql_query($sql);
		if(mysql_num_rows($operacion)>0)
		{
			$sql = "update privilegio set ver=$ver, agregar=$add, editar=$editar, borrar=$borrar, fecha=$hoy where idusuario=$idusu and idsubmodulo=$idsubmodulo";
		}
		else
		{
			$sql="insert privilegio (idusuario,idsubmodulo,ver,agregar,editar,borrar,fecha) values ($idusu,$idsubmodulo,$ver,$add,$editar,$borrar,'$hoy')";
		}

		$operacion = mysql_query($sql);

	}



	if($priv[2]=='1' and $accion=="2")
	{

		//hacer el proceso de actualización
		$hoy = date('Y') . "-" . date('m') . "-" . date('d');
		$sql = "update privilegio set ver=$ver, agregar=$add, editar=$editar, borrar=$borrar, fecha=$hoy where idprivilegio=$id";
		$operacion = mysql_query($sql);

	}
	if($priv[3]=='1' and $accion=="1")
	{
		//hacer el proceso de borrado del registro
		$sql = "delete from privilegio where idprivilegio=$id";
		$operacion = mysql_query($sql);

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

	$sql="select idprivilegio,modulo.nombre as mmodulo, submodulo.nombre as sbmodulo, ver, agregar, editar, borrar  from privilegio, submodulo, modulo where privilegio.idsubmodulo=submodulo.idsubmodulo and submodulo.idmodulo=modulo.idmodulo and idusuario=$idusu order by modulo.idmodulo, submodulo.idsubmodulo" ;
	$datos=mysql_query($sql);
	echo "<form><table border=\"1\"><tr><th>Id</th><th>M&oacute;dulo</th><th>submodulo</th><th>Ver</th><th>Agregar</th><th>Editar</th><th>Borrar</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{

		if($row["ver"]==true)
		{
			$ver="checked";
		}
		else
		{
			$ver="";
		}
		if($row["agregar"]==true)
		{
			$agregar="checked";
		}
		else
		{
			$agregar="";
		}
		if($row["editar"]==true)
		{
			$editar="checked";
		}
		else
		{
			$editar="";
		}
		if($row["borrar"]==true)
		{
			$borrar="checked";
		}
		else
		{
			$borrar="";
		}


		echo "<tr><td>" . $row["idprivilegio"] . "</td><td>" . CambiaAcentosaHTML($row["mmodulo"]) . "</td><td>" . CambiaAcentosaHTML($row["sbmodulo"]) . "</td> ";
		echo "<td><input type=\"checkbox\" name=\"leer" . $row["idprivilegio"] . "\" $ver></td><td><input type=\"checkbox\" name=\"add" . $row["idprivilegio"] . "\" $agregar></td><td><input type=\"checkbox\" name=\"editar" . $row["idprivilegio"] . "\" $editar></td><td><input type=\"checkbox\" name=\"borrar" . $row["idprivilegio"] . "\" $borrar></td><td>";
		echo "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('" . $rutaf . "listapriv.php','listapriv','accion=1&id=" .  $row["idprivilegio"]  . "&usuario=$idusu' )}\" $txtborrar>";
		echo "<input type=\"button\" value=\"Actualizar\" onClick=\"if(leer" . $row["idprivilegio"] . ".checked){verv=1}else{verv=0};if(add" . $row["idprivilegio"] . ".checked){agregarv=1}else{agregarv=0};if(editar" . $row["idprivilegio"] . ".checked){editarv=1}else{editarv=0};if(borrar" . $row["idprivilegio"] . ".checked){borrarv=1}else{borrarv=0};cargarSeccion('" . $rutaf . "listapriv.php','listapriv','accion=2&id=" .  $row["idprivilegio"]  . "&ver=' + verv + '&add=' + agregarv + '&editar=' + editarv + '&borrar=' + borrarv + '&usuario=$idusu' )\" $txteditar>";

		echo "</td></tr>";
	}
	echo "</table></form>";



}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}