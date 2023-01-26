<?
include 'sessionclase.php';
include_once('conexion.php');
$misesion = new sessiones;
$usuario=$_GET['usuario'];
$pwd=$_GET['pwd'];
echo <<<fin
<html>
<head>
<link rel="stylesheet" type="text/css" href="../estilos/estilos.css">
</head>
<body>
fin;

echo "antes de firmar<br>";
echo $misesion->usuario . " " . $misesion->nombre . " " . $misesion->autentificado . " " . $misesion->menu . " " . $misesion->privilegios . "<br>";

$misesion->firmarusuario($usuario,$pwd);
echo "despues de firmar <br>";
echo $misesion->usuario . " " . $misesion->nombre . " " . $misesion->autentificado . " " . $misesion->menu . " " . $misesion->privilegios . "<br>";

$misesion->cerrarsesion();
echo "despues de cerrar session <br>";
echo $misesion->usuario . " " . $misesion->nombre . " " . $misesion->autentificado . " " . $misesion->menu . " " . $misesion->privilegios . "<br>";


?>
</body>
</html>