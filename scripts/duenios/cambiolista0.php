<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo

$ida = @$_GET["ida"];
$s =@$_GET["s"];


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='reportedetallado.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta= $row['ruta'] ;
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







	$sqlp = "select *,c.idedoduenio as idf from cfdipartidas c, edoduenio d where c.idcfdiedoduenio = $ida and c.idedoduenio = d.idedoduenio";
	$optp="";
	$operacionp = mysql_query($sqlp);
	while($rowp = mysql_fetch_array($operacionp))
	{	
		$optp .= "<option value='" . $rowp["idf"] . "'>" . $rowp["notaedo"] . "</option>";

	}
	switch($s)
	{
	case 0: //de
		$sella="<select  name='la[]' id='la' size='10' multiple >$optp</select>";
		break;
		
	case 1: //para
	
	
		break;
	}
	echo $sella="<select  name='lb[]' id='lb' size='10' multiple >$optp</select>";
	

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>