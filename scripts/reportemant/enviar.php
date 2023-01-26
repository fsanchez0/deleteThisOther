<?php
//enviar solicitud
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclass.php';


$inmueble=@$_GET['inmueble'];
$inquiilno=@$_GET['inquilino'];
$prioridad=@$_GET['prioridad'];
$email=@$_GET['correo'];
$mensaje=@$_GET['solicitud'];


$sql = "select * from inmueble where idinmueble = $inmueble";
$operacion = mysql_query($sql);
$row = mysql_fetch_array($operacion);

$direccion = $row["calle"] . " No. " . $row["numeroext"] . " - " . $row["numeroint"];

$sql = "select * from inquilino where idinquilino = $inquiilno";
$operacion = mysql_query($sql);
$row = mysql_fetch_array($operacion);

$inquilinon = $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"];

$sql = "select * from prioridadmant where idprioridadmant = $prioridad";
$operacion = mysql_query($sql);
$row = mysql_fetch_array($operacion);

$prioridadn = $row["prioridadmant"];

$sql="insert into reportemant (idinmueble, idinquilino,idprioridadmant,horar, fechar, notasr, emailr) values ($inmueble,$inquiilno,$prioridad,'". date("H:i") . "', '". date("Y-m-d") . "','$mensaje','$email')";
$operacion = mysql_query($sql);

if ($operacion==1)
{
	$enviocorreo = New correo;
	$mensaje = "Se ha registrado una solicitud de mantenimiento a las " . date("H:i") . " Hrs. del día " . date("d-M-Y") . "\r\n\r\n<br> Inmueble: $direccion <br>Inquilino: $inquilinon<br>Prioridad:<strong> $prioridadn </strong> <br> Solicitud:<br>$mensaje";
	//$enviocorreo->enviar("mizocotroco@hotmail.com", "Reporte de Mantenimiento ($prioridadn)", $mensaje);
	$enviocorreo->enviar("info@padilla-bujalil.com.mx", "Reporte de Mantenimiento ($prioridadn)", $mensaje);
	//$enviocorreo->enviar("miguelmp@prodigy.net.mx,lucero_cuevas@prodigy.net.mx,miguel_padilla@nextel.mx.blackberry.com,miguel@padilla-bujalil.com.mx,cemaj@prodigy.net.mx", "Cierre del día", $mensaje);

	echo "Su solicitud fue registrada exitosamente, pronto ser&aacute; contactado para atenderle";
	
	

}
else
{
	echo "Ocurrio un error durante el registro de su solicitud, le suplicamos lo intente de nuevo para poder atenderle";

}





?>