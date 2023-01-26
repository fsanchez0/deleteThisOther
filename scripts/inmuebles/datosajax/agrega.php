<?php
include("../../general/conexion.php");
$idcontra=$_POST["idcontra"];
$periodo=$_POST["idperiodo"];
$servicio=$_POST["idservicio"];
$monto=$_POST["total"];
$estatus=$_POST["status"];
$anio=date("Y");
$period=$periodo." ".$anio;
$sql="INSERT INTO datoservicios (idcontrato,periodo,anio,cantidad,estatus,servicio) VALUES ('$idcontra','$period','$anio','$monto','$estatus','$servicio')";
$ejecuta=mysql_query($sql);
if(!$ejecuta){
	echo "<div class='alert alert-danger'><strong>¡Se ha producido un error!</strong></div>";
echo "<script>window.location();</script>";

}else{
echo "<div class='alert alert-success'><strong>¡Se ha guardados los datos!</strong></div>";
echo "<script>window.location();</script>";
}
?>