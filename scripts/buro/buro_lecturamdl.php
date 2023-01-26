<?php
//lectura de cinta para bases del envÌo al burÛ
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include ("buromdl.php");
header('Content-Type: text/html; charset=iso-8859-1');

$fecha = @$_GET["fecha"];


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='buro_lecturamdl.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);
		$ruta=$row['ruta'];

	}


	if ($priv[0]!='1')
	{
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}



	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}








if($fecha)
{
	$buro =  New burodatosclass;
	$buro->fecharp=$fecha;
	$buro->construyedatos();

	$descFisica="window.open('$ruta/descargaBuro.php?nombre=Fisica&fecha=".$fecha."');";
	$descMoral="window.open('$ruta/descargaBuro.php?nombre=Moral&fecha=".$fecha."');";

	echo 'Personas Fisicas <input type="button" value="Descargar" onClick="'.$descFisica.'")><br>';
	echo "<textarea rows=\"30\" cols=\"100\">";
	echo $buro->PF ;
	echo "</textarea><br>";
	echo 'Personas Morales <input type="button" value="Descargar" onClick="'.$descMoral.'")><br>';
	echo "<textarea rows=\"30\" cols=\"100\">";
	echo $buro->PM ;
	echo "</textarea>";
	echo "<div id=cuerpo style:><div id='desc' style='width:300; height:100; position: absolute;display:none;height:100px; overflow:auto;'></div>";
}
else
{
$fecha = date("Y-m-") . "01";
echo <<<pfecha
	<center>
	<form>
		Fecha (aaaa-mm-dd) <input type="text" name="fecha" value="$fecha"><br>
		Nota: debe de colocarce el primero del mes siguiente al que se quiere reportar <br>(ej. para reportar enero hay que poner 2011-02-01)
		<br><input type="button" value="Generar" onClick="cargarSeccion('$dirscript','contenido','fecha=' + fecha.value )">
	
	</form>
	</center>
pfecha;

}

}

?>