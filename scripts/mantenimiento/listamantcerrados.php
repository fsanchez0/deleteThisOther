<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo

$id=@$_GET["id"];
$anio=@$_GET["anio"];
$mes=@$_GET["mes"];


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='listapendientesmant.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

	if ($priv[2]=='1')
	{
		if($id)
		{
			$sql= "update contrato set litigio=false where idcontrato = $id";	
			$operacion = mysql_query($sql);
		}
		
	}
	
	$m1='';
	$m2='';
	$m3='';
	$m4='';
	$m5='';
	$m6='';
	$m7='';
	$m8='';
	$m9='';
	$m10='';
	$m11='';
	$m12='';
	
	if(!$anio!='')
	{
		$anio=date('Y');
	}
	if(!$mes!='')
	{
		$mes=date('m');
	}
	switch($mes)
	{
	case 1:
		$m1=' selected ';
		break;
	case 2:
		$m2=' selected ';
		break;
	case 3:	
		$m3=' selected ';
		break;
	case 4:
		$m4=' selected ';
		break;
	case 5:
		$m5=' selected ';
		break;
	case 6:
		$m6=' selected ';
		break;
	case 7:
		$m7=' selected ';
		break;
	case 8:
		$m8=' selected ';
		break;
	case 9:
		$m9=' selected ';
		break;
	case 10:
		$m10=' selected ';
		break;
	case 11:		
		$m11=' selected ';
		break;
	case 12:
		$m12= ' selected ';
	}
	
	
	

	$hoy=date('Y') . "-" . date('m') . "-" . date('d');
	$sql= "select *, i.tel as teli from mantenimientoseg ms, mantenimiento m, tiposervicio ts, contrato c, inquilino i, inmueble im, tipoinmueble ti where m.idcontrato = c.idcontrato and c.idinquilino = i.idinquilino and c.idinmueble = im.idinmueble and im.idtipoinmueble = ti.idtipoinmueble and ms.idmantenimiento = m.idmantenimiento and m.idtiposervicio = ts.idtiposervicio and cerrado=true and datediff(fechacita,'$hoy') <= ts.diasanticipacion and month(fechacita)=$mes and year(fechacita)=$anio order by fechacita";
	$html = "<h1>Lista de mantenimientos cerrados</h1>\n";

	$operacion = mysql_query($sql);
	$ccontrato=0;
	$grentas=0;
	$gotros=0;
	$ginteres=0;
	$rentas=0;
	$otros=0;
	$interes=0;
		
	
	
	$html .="<form><center>";
	$html .="Contrato Vigente:<select name='vigente' onChange=\"cargarSeccion_new('$ruta/linquilinos.php','inquilino','vigente=' + this.value )\"><option value='' >Seleccione uno</option><option value='0' >Cerrado</option> <option value='1' >Vigente</option></select> ";
	$html .="<div id='inquilino'>Inquilino:<select name='vigente' ><option value=''>Seleccione uno</option></select> </div>";
	$html .="<div id='contrato'>Contrato:<select name='vigente' onChange=\"cargarSeccion_new('$ruta/listamantcerrados.php','listacerrados','mes=' + mes.value + '&anio=' + anio.value )\"><option value=''>Seleccione uno</option></select>  </div>";
	
/*	
	$html .="Mes <select name='mes' onChange=\"cargarSeccion_new('$ruta/listamantcerrados.php','contenido','mes=' + mes.value + '&anio=' + anio.value )\"><option value='01' $m1>Enero</option>";	
	$html .="<option value='02' $m2>Febrero</option>";	
	$html .="<option value='03' $m3>Marzo</option>";	
	$html .="<option value='04' $m4>Abril</option>";
	$html .="<option value='05' $m5>Mayo</option>";		
	$html .="<option value='06' $m6>Junio</option>";	
	$html .="<option value='07' $m7>Julio</option>";	
	$html .="<option value='08' $m8>Agosto</option>";	
	$html .="<option value='09' $m9>Septiembre</option>";	
	$html .="<option value='10' $m10>Octubre</option>";	
	$html .="<option value='11' $m11>Noviembre</option>";	
	$html .="<option value='12' $m12>Diciembre</option>";	
	$html .="</select>&nbsp;&nbsp;";	
	$html .="A&ntilde;o: <input type='text' name='anio' value='$anio' onChange=\"cargarSeccion_new('$ruta/listamantcerrados.php','contenido','mes=' + mes.value + '&anio=' + anio.value )\"></center>";
*/	
	$html .= "<div id=\"listacerrados\"></div>";
/*	
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
	*/
	echo CambiaAcentosaHTML($html);



}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>