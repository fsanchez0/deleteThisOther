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

		$sql="select * from submodulo where archivo ='cobro.php'";
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

		//$sql = "select *, c.idcontrato as idc from inquilino i, contrato c, inmueble inm where i.idinquilino = c.idinquilino and c.idinmueble=inm.idinmueble and ( i.nombre  like '%$dato%' or i.nombre2  like '%$dato%' or i.apaterno like '%$dato%' or i.amaterno like '%$dato%') and concluido = false";
		$sql = "select *, c.idcontrato as idc from inquilino i, contrato c, inmueble inm, apartado a where i.idinquilino = c.idinquilino and c.idinmueble=inm.idinmueble  and inm.idinmueble=a.idinmueble and a.estatus='Fir'  and ( i.nombre  like '%$dato%' or i.nombre2  like '%$dato%' or i.apaterno like '%$dato%' or i.amaterno like '%$dato%') and concluido = false";

		break;
	case 2: //inmueble

		//$sql = "select *, c.idcontrato as idc from inquilino i, contrato c, inmueble inm where i.idinquilino = c.idinquilino and c.idinmueble=inm.idinmueble and ( inm.calle  like '%$dato%' or inm.colonia  like '%$dato%' or inm.delmun like '%$dato%' or inm.descripcion like '%$dato%') and concluido = false";
		//$sql = "select *, c.idcontrato as idc from inquilino i, contrato c, inmueble inm, apartado a where i.idinquilino = c.idinquilino and c.idinmueble=inm.idinmueble  and inm.idinmueble=a.idinmueble and a.estatus='Fir'  and ( inm.calle  like '%$dato%' or inm.colonia  like '%$dato%' or inm.delmun like '%$dato%' or inm.descripcion like '%$dato%') and concluido = false";
		$sql = "select *, c.idcontrato as idc from inquilino i, contrato c, inmueble inm, apartado a where i.idinquilino = c.idinquilino and c.idinmueble=inm.idinmueble  and inm.idinmueble=a.idinmueble   and ( inm.calle  like '%$dato%' or inm.colonia  like '%$dato%' or inm.delmun like '%$dato%' or inm.descripcion like '%$dato%') and concluido = false";

		break;
	case 3: //contrato

		//$sql = "select *, c.idcontrato as idc from inquilino i, contrato c, inmueble inm where i.idinquilino = c.idinquilino and c.idinmueble=inm.idinmueble and c.idcontrato=$dato and concluido = false";
		//$sql = "select *, c.idcontrato as idc from inquilino i, contrato c, inmueble inm, apartado a where i.idinquilino = c.idinquilino and c.idinmueble=inm.idinmueble  and inm.idinmueble=a.idinmueble and a.estatus='Fir'  and c.idcontrato=$dato and concluido = false";
        $sql = "select *, c.idcontrato as idc from inquilino i, contrato c, inmueble inm, apartado a where i.idinquilino = c.idinquilino and c.idinmueble=inm.idinmueble  and inm.idinmueble=a.idinmueble   and c.idcontrato=$dato and concluido = false";

		break;
	case 4: //inmueble

		//$sql = "select *, c.idcontrato as idc from inquilino i, contrato c, inmueble inm, apartado a where i.idinquilino = c.idinquilino and c.idinmueble=inm.idinmueble and inm.idinmueble=a.idinmueble and a.estatus='Fir' and a.referencia like '%$dato%'  and concluido = false ";
        $sql = "select *, c.idcontrato as idc from inquilino i, contrato c, inmueble inm, apartado a where i.idinquilino = c.idinquilino and c.idinmueble=inm.idinmueble and inm.idinmueble=a.idinmueble and  a.referencia like '%$dato%'  and concluido = false ";

		break;

	}
	//echo $sql;
	$result = @mysql_query ($sql);

	if (!$result)
	{
		echo "<strong> No hay ningun resultado con ese patron de busqueda</strong>";
	}
	else
	{
		echo "<table border=1>\n<tr>\n<th>Contrato</th><th>Nombre</th><th>Inmueble</th><th>referencia</th>\n</tr>";
		while ($row = mysql_fetch_array($result))
		{
			$idc=$row["idc"];
			$html = "<tr>\n";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&efectivo=0');efect=0;\">" .  $idc . "</a></td>";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&efectivo=0');efect=0;\">" .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "</a></td>";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&efectivo=0');efect=0;\">" .  $row["calle"] . "No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"] . " Alc/Mun. ". $row["delmun"] . " C.P." . $row["cp"] . "</a></td>";
            $html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&efectivo=0');efect=0;\">" .  $row["referencia"] . "</a></td>";

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