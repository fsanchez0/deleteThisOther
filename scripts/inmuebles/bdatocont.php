<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');

$cat=@$_GET["cat"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$idusuario = $misesion->usuario;
	$sql="select * from submodulo where archivo ='contratonuevo.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}
	$titulo="";
	$apartados="";
	$tipoboton="hidden";
	switch($cat)
	{
	case 0: //inquilino
		$titulo="Inquilino";
		break;
	case 1: //fiador
		$titulo="Obligado Solidario";
		break;
	case 2: //Inmueble
		$titulo="Inmueble";
		$apartados="Apartados";
		$tipoboton="checkbox";
		break;
	}


echo "<b>$titulo</b><input type=\"button\" value=\"Ocultar\" onClick=\"document.getElementById('bdato').innerHTML='';\"><table border=\"1\"><tr><td><input type=\"text\" name=\"bdatocat\" onKeyUp=\"cargarSeccion('$ruta/rescata.php', 'rescata', 'patron=' + this.value + '&cat=$cat&apartado=' + document.getElementById('apartadoid').checked);\"> $apartados <input type=\"$tipoboton\" name=\"apartado\" id=\"apartadoid\"> </td></tr><tr><td><div id=\"rescata\" style=\"height:300px; overflow:auto;width:300\"></div>	</td></tr></table>";
}

else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}


?>