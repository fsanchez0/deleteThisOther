<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];

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
	$sql="select * from submodulo where archivo ='mantenimiento.php'";
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
	
	if($id<>"0")
	{

	$sql = "select * from contrato c, inmueble i, duenio d, tipoinmueble ti, duenioinmueble di where c.idinmueble = i.idinmueble and i.idinmueble = di.idinmueble and di.idduenio = d.idduenio and i.idtipoinmueble = ti.idtipoinmueble and idcontrato = $id";
	$operacion = mysql_query($sql);
	//ejecuto la consulta si tiene algo en la variable
	$duenio ="";
	while($row = mysql_fetch_array($operacion))
	{
		$tipoinmueble = $row["tipoinmueble"];
		$direccion = $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . ", Col. " . $row["colonia"] . ", Alc. " . $row["delmun"]  . ", C.P. " . $row["cp"];
		$duenio .= $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "<br>";
	}

//Genero el formulario de los submodulos

echo <<<formulario1

<b>Tipo de inmueble:</b> $tipoinmueble<br>
<b>Direcci&oacute;n:</b> $direccion<br>
<b>Due&ntilde;o:</b> $duenio


formulario1;

	}

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>