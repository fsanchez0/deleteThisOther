<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo

$id=@$_GET["id"]; //duenio
$fehagen=@$_GET["fechagen"]; //duenio
$html="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='duenios.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'] ;
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}


	if ($priv[0]!='1')
	{
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
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


		$sql="select * from duenio d, enviomail e  where d.idduenio = e.idduenio and fechareporte = '$fehagen' and d.idduenio = $id order by fechaenvio DESC,  horaenvio DESC";
		$html = "";
		$html .= "<table border=\"1\"><tr><th>Propierario</th><th>Correo</th><th>Fecha envio</th><th>horaenvio</th></tr>";
	
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"]  . " " . $row["amaterno"]);
			$fechaenvio=CambiaAcentosaHTML($row["fechaenvio"]);
			$horaenvio=CambiaAcentosaHTML($row["horaenvio"]);
			$mail=CambiaAcentosaHTML($row["maile"]);
			
			$html .= "<tr><td>$nombre</td><td>$mail</td><td>$fechaenvio</td><td>$horaenvio</td></tr>";	
			
			
		}
		echo $html .="</table>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>