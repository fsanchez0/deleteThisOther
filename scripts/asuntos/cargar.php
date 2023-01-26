<?php
include '../general/cargadescarga.php';

$accion=@$_POST["accion"];
$listaa=@$_POST["listaa"];
$archvo=@$_GET["archivo"];
$larchivos=@$_GET["larchivos"];

$gestor= new carga;

//echo "Accion=" . $accion . " <br>Listaa= " . $listaa . " <br>Archivo= " . $archvo . " <br>listaarhivos= " . $larchivos . " <br> " ;

if($accion=="1")
{//subir arcchivo
	$gestor->cargar(basename( $_FILES['myfile']['name']), $_FILES['myfile']['tmp_name'],true,$listaa,0, 0);
}
else
{//borrar y quitar archivo
	$gestor->quitar_arhivo($larchivos,$archvo);
}

?>