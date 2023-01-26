<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/auditoriaclass.php';

//Modulo
$id=@$_GET["id"];
$seccion=@$_GET["seccion"];

$misesion = new sessiones;
$aud = new auditoria;
if($misesion->verifica_sesion()=="yes")
{
	$aud->id=$id;
	$audtxt = "<html><head><title>Impresion de $seccion</title></head>";
	$audtxt .= "<body onLoad='window.print();'>";
	$audtxt .= "<center><h1>$seccion</h1></center>";
	$audtxt .= $aud->imprimir();
	echo $audtxt .= "</body></html>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>




