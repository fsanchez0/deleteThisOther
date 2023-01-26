<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo

$inquilino=@$_POST["inquilino"];
$vigente=@$_POST["vigente"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='listapendientesmant.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}



	$html ="";
	//$sql = "select *, i.idinquilino as idinquilinoc,  nombrei from contrato c, (select idinquilino, concat(nombre, ' ', nombre2, ' ',apaterno, ' ',amaterno) as nombrei from  inquilino) i where c.idinquilino = i.idinquilino and activo = $vigente group by i.idinquilino order by nombrei";
	$sql = "select * from contrato where idinquilino = $inquilino and activo=$vigente";
	$operacion = mysql_query($sql);
	
	$html .= "Inquilino:<select name='contratol' onChange=\"cargarSeccion_new('$ruta/lmant.php','listacerrados','contrato=' + this.value )\"><option value=''>Seleccione uno</option> ";
	while($row = mysql_fetch_array($operacion))
	{
	
		$html .="<option value='" . $row["idcontrato"]  . "'>" . $row["idcontrato"] . "</option>";
	}
	$html .="</select>";
	echo CambiaAcentosaHTML($html);
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>