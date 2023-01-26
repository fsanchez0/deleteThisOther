<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');

$idhistoria = @$_GET["historia"];
$misesion = new sessiones;

if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='verpago.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta']  . "/" . $row['archivo'];
		$ruta= $row['ruta'] ;
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

	$sql="SELECT * FROM pagoaplicado p, historiapago hp WHERE p.idpagoaplicado=hp.idpagoaplicado AND hp.idhistoria=$idhistoria";
	$resultado = @mysql_query ($sql);

	$row = mysql_fetch_array($resultado);
	if($row){
		$html = $row["distribucionpago"];

		$html = base64_decode($html);

		$html=str_replace("imagenes/marca_telefono.png", "/../imagenes/marca_telefono.png", $html);
		$html=str_replace("<input ", "<input disabled hidden ", $html);
		echo $html;	
	}else{
		echo "<center><strong> No se encontro el detalle del pago realizado </strong></center>";
	}
	

}
?>