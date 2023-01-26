<?php
// este es llamado desde asuntos.php
// con este se filtra la lista de asuntos para mostrarce en el contenedor

include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$opt=@$_GET['opt'];
$opt2=@$_GET['opt2'];
$filtro=@$_GET['filtro'];

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

	$sql="";
	switch($opt)
	{
	case "0": // expediente
		
		$sql=" and expediente like '%$filtro%' ";
		
		
		break;

	case "1": //cliente
		
		$sql=" and CONCAT(nombre,' ',apaterno, ' ',amaterno) like '%$filtro%' ";
		break;

	case "2": //abogado

		$sql=" and abogado like '%$filtro%' ";
	}	

	switch($opt2)
	{
	case "0": // Abierto
		
		$sql .=" and ( isnull(cerrado)=true or cerrado = 0) ";
		
		
		break;

	case "1": //cerrad0
		
		$sql .=" and cerrado = 1 ";
		
	}	


	$sql="select * from asunto,directorio where asunto.iddirectorio=directorio.iddirectorio $sql order by idasunto ";
	$datos=mysql_query($sql);
	
	
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
	echo "</table>";





}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>