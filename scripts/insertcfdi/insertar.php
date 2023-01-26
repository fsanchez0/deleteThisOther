<?php
include "lxml3.php";
include_once('../general/conexion.php');

$archivo=@$_GET["archivo"]; //para el Id de la consulta que se requiere hacer

//header("Content-type: text/xml");
$dir="../../../../../home/wwwarchivos/cfdi/";

$prueba = new xmlcfd_cfdi;

//$prueba->leerXML("cfdi/" . $archivo);
$prueba->leerXML($dir . $archivo);

//hacer el insert de los datos de la factura
$sql = "insert into facturacfdi (serie, folio, fecha, hora, noaprobacion,anioaprobacion,subtotal,retenciones,traslados,total,concepto,archivoxml,xmlok,archivotxt,txtok,archivopdf,pdfok) values (";
$sql .= "'" . $prueba->comprobante["serie"]["valor"] . "',";
$sql .= "'" . $prueba->comprobante["folio"]["valor"] . "',";
$fecha =substr($prueba->comprobante["fecha"]["valor"],0,10); 
$hora =substr($prueba->comprobante["fecha"]["valor"],12); 
$sql .= "'$fecha',";
$sql .= "'$hora',";
$sql .= "'" . $prueba->comprobante["noAprobacion"]["valor"] . "',";
$sql .= "'" . $prueba->comprobante["anoAprobacion"]["valor"] . "',";
$sql .= "'" . $prueba->comprobante["subTotal"]["valor"] . "',";
$sql .= "'" . @$prueba->comprobante["Impuestos"]["totalImpuestosRetenidos"]["valor"] . "',";
$sql .= "'" . $prueba->comprobante["Impuestos"]["totalImpuestosTrasladados"]["valor"] . "',";
$sql .= "'" . $prueba->comprobante["total"]["valor"] . "',";
$sql .= "'" . $prueba->comprobante["Conceptos"]["Concepto"][0]["descripcion"]["valor"] . "',";
$sql .= "'$archivo',1,";
$sql .= "'" . substr($archivo,0,-3) . "txt',1,";
$sql .= "'" . substr($archivo,0,-3) . "pdf',1)";

$result = @mysql_query ($sql);
if($result)
{
//si hay un insert correcto
echo "OK";
}
else
{
//si no hay insert correcto
echo "ERROR: $sql";
}

?>