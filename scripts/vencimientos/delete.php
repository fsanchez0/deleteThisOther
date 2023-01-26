<?php
	include("../general/conexion.php");
	$cod=$_GET["codigo"];
	mysql_query("DELETE FROM datoservicios WHERE iddato='$cod'");
	header("Location: listaservicios.php");
?>