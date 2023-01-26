<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


//Modulo

$patron=@$_GET["patron"];



$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='cargoduenio.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}



	$sql = "select * from inmueble i, duenioinmueble di, duenio d where  i.idinmueble = di.idinmueble and di.idduenio = d.idduenio and d.idduenio = $patron";


	$contratosselect = "<select name=\"idinmueble\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";

		$contratosselect .= "<option value=\"" . $row["idinmueble"] . "\" $seleccionopt>" . CambiaAcentosaHTML( $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"]  ) . "</option>";

	}
	echo $contratosselect .="</select>";





}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>