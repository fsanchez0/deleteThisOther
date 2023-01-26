<?php
$ruta=$_GET["ruta"];
$archivo=$_GET["archivo"];

$rutas= $ruta."/".$archivo;
//unlink($directorio);


if (!unlink($rutas)){
//si no puede ser muestro un mensaje 🙂
echo '<script>alert("No se pudo borrar el archivo '.$archivo.'")</script>';
echo "<script>window.close();</script>";
}
else{
	echo '<script>alert("Se ha borrado el archivo '.$archivo.' por favor, recargue la pagina") </script>';
	echo "<script>window.close();</script>";
}
?>