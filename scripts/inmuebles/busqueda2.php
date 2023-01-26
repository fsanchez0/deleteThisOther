<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$dato = @$_GET["dato"];
$btn = @$_GET["btn"];



//$enlace = mysql_connect('localhost', 'root', '');
//mysql_select_db('bujalil',$enlace) ;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='busqueda.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}
	
	
//	$sql="select * from submodulo where archivo ='anticipado.php'";
	$sql="select * from submodulo where archivo ='anticipado2.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}
		

	switch ($btn)
	{
	case 1: //nombre

		$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and ( inquilino.nombre  like '%$dato%' or inquilino.nombre2  like '%$dato%' or inquilino.apaterno like '%$dato%' or inquilino.amaterno like '%$dato%') and concluido = false";

		break;
	case 2: //inmueble

		$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and ( inmueble.calle  like '%$dato%' or inmueble.colonia  like '%$dato%' or inmueble.delmun like '%$dato%' or inmueble.descripcion like '%$dato%') and concluido = false";

		break;
	case 3: //contrato

		$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato=$dato and concluido = false";

	}
	$result = @mysql_query ($sql);

	if (!$result)
	{
		echo "<strong> No hay ningun resultado con ese patron de busqueda</strong>";
	}
	else
	{
		echo "<table border=1>\n<tr>\n<th>Contrato</th><th>Nombre</th><th>Inmueble</th>\n</tr>";
		while ($row = mysql_fetch_array($result))
		{
			$idc=$row["idcontrato"];
			$html = "<tr>\n";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&efectivo=0');efect=0;\">" .  $idc . "</a></td>";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&efectivo=0');efect=0;\">" .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "</a></td>";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&efectivo=0');efect=0;\">" .  $row["calle"] . "No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"] . " Alc/Mun. ". $row["delmun"] . " C.P." . $row["cp"] . "</a></td>";

			/*
			echo "<td><a onClick=\"jabascript:pasosCobro(1,$idc,0);efect=0;\">" .  $idc . "</a></td>";
			echo "<td><a onClick=\"jabascript:pasosCobro(1,$idc,0);efect=0;\">" .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "</a></td>";
			echo "<td><a onClick=\"jabascript:pasosCobro(1,$idc,0);efect=0;\">" .  $row["calle"] . " No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"] . " Alc/Mun. ". $row["delmun"] . " C.P." . $row["cp"] . "</a></td>";
	*/
			$html .= "</tr>\n";
			echo CambiaAcentosaHTML($html);
		}
		echo "</table>";
	}
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>