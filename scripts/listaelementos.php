<?php
//Es para realizar la busqueda dentro del directorio y mostrarla en
//el marcobusqueda, relacioando directamente con marcobusqueda.php

include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$patron=@$_GET["patron"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

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






	if(!$patron=="")
	{
		$sql="select archivo, a.idarchivo as ida, idexpediente, numero, descripcione from archivo a,expediente e where (archivo like '%$patron%' or descripcione like '%$patron%') and a.idarchivo=e.idarchivo order by archivo";
	
		//$sql="select * from directorio ";
		$datos=mysql_query($sql);
		echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	//	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>A. Paterno</th><th>A. Materno</th><th>Tel. partiular</th><th>Tel. oficina</th><th>Tel. movil</th><th>Otro tel.</th><th>Direci&oacute;n</th><th>email</th><th>Nombre Fiscal</th><th>RFC</th><th>Direcci&oacute;n Fiscal</th><th>Acciones</th></tr>";
		echo "<table border=\"1\"><tr><th>Id</th><th>Elemento</th><th>Expediente</th><th>Descripci&oacute;n</th><th>Acciones</th></tr>";
		while($row = mysql_fetch_array($datos))
		{
	//		echo "<tr><td>" . $row["iddirectorio"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["apaterno"] . "</td><td>" . $row["amaterno"] . "</td><td>" . $row["telparticular"] . "</td><td>" . $row["teloficina"] . "</td><td>" . $row["telmovil"] . "</td><td>" . $row["telotros"] . "</td><td>" . $row["direccion"] . "</td><td>" . $row["email"] . "</td><td>" . $row["denominacionf"] . "</td><td>" . $row["rfc"] . "</td><td>" . $row["direccionf"] . "</td><td>";
			$html = "<tr><td>" . $row["idexpediente"] . "</td><td>" . $row["archivo"] . "</td><td>" . $row["ida"] . "." . $row["numero"] . "</td><td>" . $row["descripcione"] . "</td><td>";		
			$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento y todo su contenido?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idexpediente"]  . "' )}\" $txtborrar>";
			$html .= "<input type=\"button\" value=\"Seguimiento\" onClick=\"cargarSeccion('$ruta/expseguimientoe.php','contenido','accion=2&id=" .  $row["idexpediente"]  . "' )\" $txteditar>";
			$html .= "</td></tr>";
			echo CambiaAcentosaHTML($html);
		}
		echo "</table></div>";

	}



}



?>