<?php
include "general/calendarioclass.php";
//include 'general/sessionclase.php';
include_once('general/conexion.php');
$accion = @$_GET["num"];
echo '<link rel="stylesheet" type="text/css" href="../estilos/estilos.css">';
$prueba = New Calendario;
//$prueba->DatosConexion('localhost','root','','bujalil');
//$prueba->BuscarMes(-1);
$prueba->Gmes($accion);

//echo $fechanaturalpago = $prueba->calculafecha(mktime(0,0,0,11,1,2001), 61, 3);

?>