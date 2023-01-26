<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


//Modulo

$id=@$_GET["id"];



$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='privilegios.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}


	$sql="select *  from submodulo where idmodulo=$id order by idmodulo, idsubmodulo" ;
	$modulos = "<select name=\"idsubmodulo\" size=\"4\" >";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		$modulos .= "<option value=\"" . $row["idsubmodulo"] . "\" >" . CambiaAcentosaHTML($row["nombre"]) . "</option>";

	}
	echo $modulos .="</select>";



}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>