<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
//include '../general/funcionesformato.php';
$accion = @$_GET["accion"];
$id=@$_GET["id"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$idusuario = $misesion->usuario;
	$sql="select * from submodulo where archivo ='expseguimientop.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta= $row['ruta'];
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

	switch($accion)
	{
	case "1": //Borro

		$sql="delete from documento where idexpediente=$id";
		$operacion = mysql_query($sql);
		$sql="delete from expediente where idexpediente=$id";
		//echo "<br>Borro";
		$id="";
		break;
	}
	//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}




echo <<<formulario1

<center>
<h1>Seguimiento de expedientes</h1>

<p><b>Buscar </b><input type="text" name="nombreb" value="" onKeyUp="cargarSeccion('$ruta/listaelementos.php', 'listadirectorio', 'patron=' + this.value)"></p>

	<div id="listadirectorio" class="scroll">

	</div>

</center>


formulario1;


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>