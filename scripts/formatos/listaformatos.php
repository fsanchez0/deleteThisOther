<?php
//include '../general/cargadescarga.php';
include '../general/sessionclase.php';
include 'cargarclass.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$accion=@$_POST["accion"];
$idtipoformato=@$_POST["idtipoformato"];
$archvo=@$_POST["archivo"];
$id=@$_GET["id"];

$gestor= new carga;

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='listaformatos.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta= $row['ruta'];
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




	$sql="select * from formato, tipoformato where formato.idtipoformato = tipoformato.idtipoformato order by tipoformato,idformato ";
	$datos=mysql_query($sql);
	
	
	echo "<center>";
	echo "<h1>Lista de Formatos</h1>";
	
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	//form para poder lanzar el post a este archivo	
	echo "<table border=\"1\"><tr><th>Id</th><th>Tipo Formato</th><th>Formato</th><th>Fecha</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<tr><td>" . $row["idformato"] . "</td><td>" . $row["tipoformato"] . "</td><td><a href=\"$ruta/descargaformato.php?f=" .  $row["archivo"] . "\" target=\"_blank\">" . $row["archivo"] . "</a></td><td>" . $row["fechaf"] . "</td>";
		$html .= "</tr>";
		echo CambiaAcentosaHTML($html);
	}
	echo "</table></div>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}






?>