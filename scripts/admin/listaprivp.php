<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$ver=@$_GET["ver"];
$idusu=@$_GET["usuario"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='privilegiofile.php'";
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
		$sql="select * from privilegiofile where idusuario=$idusu";
		$operacion = mysql_query($sql);
		if(mysql_num_rows($operacion)>0)
		{
			$sql = "update privilegiofile set ver=$ver where idusuario=$idusu";
		}
		else
		{
			$sql="insert privilegiofile (idusuario,ver) values ($idusu,$ver)";
		}

		$operacion = mysql_query($sql);

	}



	if($priv[2]=='1' and $accion=="2")
	{

		//hacer el proceso de actualización
		$hoy = date('Y') . "-" . date('m') . "-" . date('d');
		$sql = "update privilegiofile set ver=$ver where idprivilegio=$id";
		$operacion = mysql_query($sql);

	}
	if($priv[3]=='1' and $accion=="1")
	{
		//hacer el proceso de borrado del registro
		$sql = "delete from privilegiofile where idprivilegio=$id";
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

	$sql="select idprivilegio,ver  from privilegiofile where idusuario=$idusu" ;
	$datos=mysql_query($sql);
	echo "<form><table border=\"1\"><tr><th>Id</th><th>Ver</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{

		if($row["ver"]==1)
		{
			$ver="checked";
		}
		else
		{
			$ver="";
		}
		


		echo "<tr><td>" . $row["idprivilegio"]. "</td> ";
		echo "<td><input type=\"checkbox\" name=\"leer" . $row["idprivilegio"] . "\" $ver></td><td>";
		echo "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('" . $rutaf . "listaprivp.php','listapriv','accion=1&id=" .  $row["idprivilegio"]  . "&usuario=$idusu' )}\" $txtborrar>";
		echo "<input type=\"button\" value=\"Actualizar\" onClick=\"if(leer" . $row["idprivilegio"] . ".checked){verv=1}else{verv=0};cargarSeccion('" . $rutaf . "listaprivp.php','listapriv','accion=2&id=" .  $row["idprivilegio"]  . "&ver=' + verv + '&usuario=$idusu' )\" $txteditar>";

		echo "</td></tr>";
	}
	echo "</table></form>";



}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}