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
	$sql="select * from submodulo where archivo ='cargoduenio.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

		$sql="select * from submodulo where archivo ='cargoduenio.php'";
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

		$sql = "select *, d.idduenio as idd, indu.idinmueble as idin, d.nombre as nombred, d.nombre2 as nombre2d, d.apaterno as apaternod, d.amaterno as amaternod,  i.nombre as nombrei, i.nombre2 as nombre2i, i.apaterno as apaternoi, i.amaterno as amaternoi from inquilino i, contrato, inmueble, duenioinmueble indu, duenio d where indu.idinmueble = inmueble.idinmueble and indu.idduenio = d.idduenio and i.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and ( i.nombre  like '%$dato%' or i.nombre2  like '%$dato%' or i.apaterno like '%$dato%' or i.amaterno like '%$dato%') and concluido = false";
		$sql2 ="";
		break;
	case 2: //inmueble

		$sql = "select *, d.idduenio as idd, indu.idinmueble as idin, d.nombre as nombred, d.nombre2 as nombre2d, d.apaterno as apaternod, d.amaterno as amaternod,  i.nombre as nombrei, i.nombre2 as nombre2i, i.apaterno as apaternoi, i.amaterno as amaternoi  from inquilino i, contrato, inmueble, duenioinmueble indu, duenio d where indu.idinmueble = inmueble.idinmueble and indu.idduenio = d.idduenio and i.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and ( inmueble.calle  like '%$dato%' or inmueble.colonia  like '%$dato%' or inmueble.delmun like '%$dato%' or inmueble.descripcion like '%$dato%') and concluido = false";
		$sql2 = "select *, d.idduenio as idd, indu.idinmueble as idin, d.nombre as nombred, d.nombre2 as nombre2d, d.apaterno as apaternod, d.amaterno as amaternod   from  inmueble, duenioinmueble indu, duenio d where indu.idinmueble = inmueble.idinmueble and indu.idduenio = d.idduenio and ( inmueble.calle  like '%$dato%' or inmueble.colonia  like '%$dato%' or inmueble.delmun like '%$dato%' or inmueble.descripcion like '%$dato%')";

		break;
	case 3: //contrato

		$sql = "select *, d.idduenio as idd, indu.idinmueble as idin, d.nombre as nombred, d.nombre2 as nombre2d, d.apaterno as apaternod, d.amaterno as amaternod,  i.nombre as nombrei, i.nombre2 as nombre2i, i.apaterno as apaternoi, i.amaterno as amaternoi from inquilino i, contrato, inmueble, duenioinmueble indu, duenio d where indu.idinmueble = inmueble.idinmueble and indu.idduenio = d.idduenio and i.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato=$dato and concluido = false";
		$sql2 ="";
		break;
	case 4: //contrato

		$sql = "select *, d.idduenio as idd, indu.idinmueble as idin, d.nombre as nombred, d.nombre2 as nombre2d, d.apaterno as apaternod, d.amaterno as amaternod,  i.nombre as nombrei, i.nombre2 as nombre2i, i.apaterno as apaternoi, i.amaterno as amaternoi from inquilino i, contrato, inmueble, duenioinmueble indu, duenio d where indu.idinmueble = inmueble.idinmueble and indu.idduenio = d.idduenio and i.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and ( d.nombre  like '%$dato%' or d.nombre2  like '%$dato%' or d.apaterno like '%$dato%' or d.amaterno like '%$dato%') and concluido = false";
		$sql2 ="";		
	}
	//echo $sql;
	$result = @mysql_query ($sql);

	if (!$result)
	{
		echo "<strong> No hay ningun resultado con ese patron de busqueda</strong>";
	}
	else
	{
		echo "<table border=1>\n<tr>\n<th>Due&ntilde;o</th><th>Contrato</th><th>Inquilino</th><th>Inmueble</th>\n</tr>";
		
		if($sql2<>"")
		{	
		//echo $sql2;
		$result2 = @mysql_query ($sql2);
		while ($row2 = mysql_fetch_array($result2))
		{
			$idc=0;
			$idd=$row2["idd"];
			$idin=$row2["idin"];
			$duenio = $row2["nombred"] . " " . $row2["nombre2d"] . " " . $row2["apaternod"] . " " . $row2["amaternod"];
			
			$html = "<tr>\n";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&idinmueble=$idin&id=$idd');efect=0;\">" .  $duenio . "</a></td>";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&idinmueble=$idin&id=$idd');efect=0;\">" .  $idc . "</a></td>";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&idinmueble=$idin&id=$idd');efect=0;\">Sin inquilino</a></td>";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&idinmueble=$idin&id=$idd');efect=0;\">" .  $row2["calle"] . "No." . $row2["numeroext"] . " Int." . $row2["numeroint"] . " Col." . $row2["colonia"] . " Alc/Mun. ". $row2["delmun"] . " C.P." . $row2["cp"] . "</a></td>";

			/*
			echo "<td><a onClick=\"jabascript:pasosCobro(1,$idc,0);efect=0;\">" .  $idc . "</a></td>";
			echo "<td><a onClick=\"jabascript:pasosCobro(1,$idc,0);efect=0;\">" .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "</a></td>";
			echo "<td><a onClick=\"jabascript:pasosCobro(1,$idc,0);efect=0;\">" .  $row["calle"] . " No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"] . " Deleg/Mun. ". $row["delmun"] . " C.P." . $row["cp"] . "</a></td>";
	*/
			$html .= "</tr>\n";
			echo CambiaAcentosaHTML($html);
		}				
				
		}
		
		
		
		while ($row = mysql_fetch_array($result))
		{
			$idc=$row["idcontrato"];
			$idd=$row["idd"];
			$idin=$row["idin"];
			$duenio = $row["nombred"] . " " . $row["nombre2d"] . " " . $row["apaternod"] . " " . $row["amaternod"];
			
			$html = "<tr>\n";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&idinmueble=$idin&id=$idd');efect=0;\">" .  $duenio . "</a></td>";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&idinmueble=$idin&id=$idd');efect=0;\">" .  $idc . "</a></td>";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&idinmueble=$idin&id=$idd');efect=0;\">" .  $row["nombrei"] . " " . $row["nombre2i"] . " " . $row["apaternoi"] . " " . $row["amaternoi"] . "</a></td>";
			$html .= "<td><a onClick=\"jabascript:cargarSeccion('$dirscript','contenido', 'paso=1&idcontrato=$idc&idinmueble=$idin&id=$idd');efect=0;\">" .  $row["calle"] . "No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"] . " Alc/Mun. ". $row["delmun"] . " C.P." . $row["cp"] . "</a></td>";

			/*
			echo "<td><a onClick=\"jabascript:pasosCobro(1,$idc,0);efect=0;\">" .  $idc . "</a></td>";
			echo "<td><a onClick=\"jabascript:pasosCobro(1,$idc,0);efect=0;\">" .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "</a></td>";
			echo "<td><a onClick=\"jabascript:pasosCobro(1,$idc,0);efect=0;\">" .  $row["calle"] . " No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"] . " Deleg/Mun. ". $row["delmun"] . " C.P." . $row["cp"] . "</a></td>";
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