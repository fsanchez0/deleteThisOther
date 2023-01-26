<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/auditoriaclass.php';

//Modulo
$id=@$_GET["id"];
$tabla=@$_GET["tabla"];

$misesion = new sessiones;
$aud = new auditoria;
if($misesion->verifica_sesion()=="yes")
{
	$aud->tabla=$tabla;
	$aud->idtabla=$id;
	$audtxt = "<html><head><title>Cambios realizados</title></head>";
	$audtxt .= "<body'>";
	//$audtxt .= "<h1>$seccion</h1>";
	$audtxt .= $aud->listamovimientos();
	echo $audtxt .= "</body></html>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>




