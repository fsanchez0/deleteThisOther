<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='tareas.php'";
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


	$sql="select * from asunto";
	$asunto = "<table border =\"1\" style=\"font-size:8pt\">";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$asunto .= "<tr><td onClick=\"getElementById('idasunto').value='" . $row["idasunto"] . "';getElementById('asunto').value='" . CambiaAcentosaHTML("(" . $row["expediente"] . ")" . $row["descripcion"] )   . "';getElementById('selectidasunto').innerHTML='';getElementById('selectidasunto').style.height=0;\" style=\"cursor:pointer\">" . CambiaAcentosaHTML("(" . $row["expediente"] . ")" . $row["descripcion"] ) .  "</td></tr>";		
	}
	$asunto .="</table>";
	echo $asunto;

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>