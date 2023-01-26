<?php

include "calendarioclass.php";
$fechas = New Calendario;
$fechas->DatosConexion('localhost','root','','bujalil');
$fechagsistema =mktime(0,0,0,5,26,2007);
echo $fechas->fechagracia("2007-09-29");



?>