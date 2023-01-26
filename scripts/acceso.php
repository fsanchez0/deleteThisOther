<?php
//Menu dinamico
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include 'general/sessionclase.php';
include_once('general/conexion.php');
include 'general/funcionesformato.php';

$usuario=@$_GET['usuario'];
$pwd=@$_GET['pwd'];
$cerrar=@$_GET['cerrar'];


$usuario = str_replace (" ","",$usuario);
$pwd = str_replace (" ","",$pwd);

$usuario = str_replace ("\"","",$usuario);
$pwd = str_replace ("\"","",$pwd);

$usuario = str_replace ("'","",$usuario);
$pwd = str_replace ("'","",$pwd);


$misesion = new sessiones;
	$dirscript="scripts/acceso.php";
	/*$sql="select * from submodulo where archivo ='acceso.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

*/
if($misesion->verifica_sesion()=="yes")
{

	if ($cerrar!='1')
	{
		echo CambiaAcentosaHTML($misesion->menu);
		echo "<center><input type=\"button\" value=\"Terminar\" onClick=\"cargarSeccion('$dirscript','menug','cerrar=1' ); \"></center>";
		exit;
	}
	else
	{
		//echo $cerrar;
		$misesion->cerrarsesion();

	}


}

if(!$usuario || !$pwd)
{

echo <<<fusuario
<form>
usuario<br>
<input type="text" name="usuario"><br>
contrase&ntilde;a<br>
<input type="password" name="pwd"><br>
<center><input type="button" value="Ingresar" onClick="cargarSeccion('$dirscript','menug','usuario=' + usuario.value + '&pwd=' + pwd.value); actualizap(); "></center>
</form>
fusuario;

//<center><input type="button" value="Ingresar" onClick="cargarSeccion('$dirscript','menug','usuario=' + usuario.value + '&pwd=' + pwd.value);actualizap();  "></center>

}
else
{

	if($misesion->firmarusuario($usuario,$pwd)=="true")
	{

	//echo $misesion->usuario . " " . $misesion->nombre . " " . $misesion->autentificado . " " . $misesion->menu . " " . $misesion->privilegios . "<br>";
		echo $misesion->menu;
		echo "<center><input type=\"button\" value=\"Terminar\" onClick=\"actualizap();cargarSeccion('scripts/acceso.php','menug','cerrar=1' ); \"></center>";
		
	}
	else
	{
echo <<<fusuario1
<form>
usuario<br>
<input type="text" name="usuario"><br>
contrase&ntilde;a<br>
<input type="password" name="pwd"><br>
<input type="button" value="Ingresar" onClick="cargarSeccion('$dirscript','menug','usuario=' + usuario.value + '&pwd=' + pwd.value);actualizap(); ">
</form>
fusuario1;

	}
}




?>