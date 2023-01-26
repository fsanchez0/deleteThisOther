<?php
include '../general/sessionclase.php';
//include_once( '../fpdf.php');
include 'reporteclass.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$idduenio = @$_GET["idduenio"];
$periodo = @$_GET["periodo"];



//$enlace = mysql_connect('localhost', 'root', '');
//mysql_select_db('bujalil',$enlace) ;
$reporte = new duenioreporte;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='cargodueniod.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

		$sql="select * from submodulo where archivo ='cargodueniod.php'";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$dirscript= $row['ruta'] . "/" . $row['archivo'];
			$ruta=$row['ruta'];
			$priv = $misesion->privilegios_secion($row['idsubmodulo']);
			$priv=split("\*",$priv);
	
		}



	$reporte->idduenio = $idduenio;
	$reporte->fechamenos = $periodo;
	$reporte->generaPDF();

	
	
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>