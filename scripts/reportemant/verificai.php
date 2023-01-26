<?php
//verifica nombres para acceso
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$nombre=@$_GET['nombre'];

//verificar el nombre
//Aceso a la base..
$nombres=split(" ",$nombre);
$encontrados="";
//echo $sql="select c.idcontrato as nocontrato, calle, numeroext, numeroint, i.idinquilino as elinquilino, concluido, nombre,amaterno, im.idinmueble as elinmueble  from inquilino i, contrato c, inmueble im where i.idinquilino = c.idinquilino and c.idinmueble = im.idinmueble and c.concluido = false and CONCAT(nombre, ' ', amaterno ) like '%$nombre%'";
$sql="select c.idcontrato as nocontrato, calle, numeroext, numeroint, i.idinquilino as elinquilino, concluido, nombre,amaterno, im.idinmueble as elinmueble  from inquilino i, contrato c, inmueble im where i.idinquilino = c.idinquilino and c.idinmueble = im.idinmueble and c.concluido = false and nombre like '%". $nombres[0] . "%' and amaterno like '%". $nombres[1] . "%'";
$operacion = mysql_query($sql);
//echo mysql_num_rows($operacion);
if(mysql_num_rows($operacion)>0 && trim($nombre)!="")
{
	
	while($row = mysql_fetch_array($operacion))
	{
		$encontrados .="<p>";
		$encontrados .="Contrato " . $row["nocontrato"] . "<br>";
		$encontrados .="Inmueble:<input type=\"button\" value=\"Ir a la ayuda\" onClick=\"location.href='scripts/reportemant/ayuda.php?inquilino=" . $row["elinquilino"] . "&inmueble=" . $row["elinmueble"] . "'\"> <br>" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . "<br>";
		$encontrados .="</p>";				
		
	}
	echo $encontrados;
}
else
{

	echo "No se encontr&oacute; ning&uacute;n usuario con ese nombre";
}

?>