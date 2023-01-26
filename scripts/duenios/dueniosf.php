<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


//Modulo

$patron=@$_GET["patron"];



$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='repdet.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}



	$sql = "select * from contrato c, inmueble i, duenioinmueble di, duenio d where c.idinmueble = i.idinmueble and i.idinmueble = di.idinmueble and di.idduenio = d.idduenio and idcontrato = $patron";


	$contratosselect = "<select name=\"idduenio\" ><option value=\"0\">Todos</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";

		$contratosselect .= "<option value=\"" . $row["idduenio"] . "\" $seleccionopt>" . CambiaAcentosaHTML( $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"]  ) . "</option>";

	}
	echo $contratosselect .="</select>";





}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>