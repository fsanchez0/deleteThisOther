<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo

$contrato=@$_POST["contrato"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='listapendientesmant.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}



	$html ="";
	$sql= "select *, i.tel as teli from mantenimientoseg ms, mantenimiento m, tiposervicio ts, contrato c, inquilino i, inmueble im, tipoinmueble ti where m.idcontrato = c.idcontrato and c.idinquilino = i.idinquilino and c.idinmueble = im.idinmueble and im.idtipoinmueble = ti.idtipoinmueble and ms.idmantenimiento = m.idmantenimiento and m.idtiposervicio = ts.idtiposervicio and cerrado=true and c.idcontrato = $contrato order by fechacita";

	$operacion = mysql_query($sql);
	
	$html .= "<table border=\"1\">";
	$html .= "<tr><th>Nombre de quien recibe</th><th>Tipo Servicio</th><th>Reporte</th><th>Fecha de programado</th><th>Fecha de Cita</th><th>Inquilino</th><th>Direcci&oacute;n</th><th>Correo</th><th>Tel&eacute;fono</th><th>Accion</th></tr>";
	while($row = mysql_fetch_array($operacion))
	{
	
		$html .="<tr><td>" . $row["recibe"] . "</td>";
		$html .="<td>" . $row["tiposervicio"] . "</td>";
		$html .="<td>" . $row["mantenimiento"] . "</td>";
		$html .="<td>" . $row["fechams"] . "</td>";
		$html .="<td>" . $row["fechacita"] . "</td>";
		$html .="<td>" . $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] .  "</td>";
		$html .="<td>" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . ", Col. " . $row["colonia"] . "</td>";
		$html .="<td>" . $row["email"] . "</td>";
		$html .="<td>" . $row["teli"] . "</td>";
		
		$pendiente="window.open( '$ruta/reportemant.php?id=" . $row["idmantenimientoseg"] . "');";
		//$pendiente="window.open( '$ruta/reportemant.php?id=" . $row["idmantenimientoseg"] . "');";
		$accionboton="<input type =\"button\" value=\"Ver\" onClick=\"cargarSeccion_new('$ruta/reportemant.php','contenido','accion=0&id= " . $row["idmantenimientoseg"] . "')\"  />";		
		$html .="<td>$accionboton</td></tr>";
	}
	$html .="</table>";
	echo CambiaAcentosaHTML($html);

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>