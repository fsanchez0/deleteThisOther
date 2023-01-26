<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include ("buromdl.php");
header('Content-Type: text/html; charset=iso-8859-1');

$nombre = @$_GET["nombre"];
$fecha = @$_GET["fecha"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes"){
	header('Content-type: application/vnd.ms-excel');
	header("Content-Disposition: attachment; filename=Buro_$nombre.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	$buro =  New burodatosclass;
	$buro->fecharp=$fecha;
	$buro->construyedatos();

	//El contenido es una cadena separada por saltos de linea los renglones y los campos con pipes | 

	if($nombre=="Fisica"){
		$tabla = str_replace("\n","</td></tr><tr><td>",$buro->PF);
		$tabla= str_replace("|","</td><td>", $tabla);
		$tabla .="</td></tr>";
		$tabla = "<tr><td>".$tabla; 
		echo "<table border ='1'>".$tabla."</table>";
	}else if($nombre="Moral"){
		$tabla = str_replace("\n","</td></tr><tr><td>",$buro->PM);
		$tabla= str_replace("|","</td><td>", $tabla);
		$tabla .="</td></tr>";
		$tabla = "<tr><td>".$tabla; 
		echo "<table border ='1'>".$tabla."</table>";
	}
	
}else{
	echo "La sesi¨®n ha expirado";
}
?>