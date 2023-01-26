<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo

$id=@$_GET["id"]; //contrato

$html="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='pendientesp.php'";
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


		$sql="select * from envioclp e, usuario u where e.idusuario = u.idusuario and idcontrato = $id order by fechaclp DESC,  horaclp DESC";
		$html = "";
		$html .= "<table border=\"1\"><tr><th>usuario</th><th>Correo</th><th>Fecha envio</th><th>horaenvio</th><th>individual</th></tr>";
	
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"] .  " " . $row["apaterno"]  . " " . $row["amaterno"]);
			$fechaenvio=CambiaAcentosaHTML($row["fechaclp"]);
			$horaenvio=CambiaAcentosaHTML($row["horaclp"]);
			$mail=CambiaAcentosaHTML($row["mailclp"]);
			$individual=CambiaAcentosaHTML($row["individual"]);
			
			$html .= "<tr><td>$nombre</td><td>$mail</td><td>$fechaenvio</td><td>$horaenvio</td><td>$individual</td></tr>";	
			
			
		}
		echo $html .="</table>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>