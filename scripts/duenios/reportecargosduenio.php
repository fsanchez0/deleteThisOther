<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$fechai=@$_GET["fechai"];
$fechaf=@$_GET["fechaf"];
$accion=@$_GET["accion"];
$descargar=@$_GET["descargar"];

$ff="";
$repok="";

$misesion = new sessiones;

if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='reportecargosduenio.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta']  . "/" . $row['archivo'];
		$ruta= $row['ruta'] ;
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

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
		
//Genero el formulario de los submodulos

$botonDescarga="window.open('$dirscript?accion=1&descargar=1&fechai=' + fechai.value + '&fechaf=' + fechaf.value);";

$formulario =  <<<formulario1
<center>
<h1>Reporte de Cargos a Dueños</h1>
<form>
<table border="0">
<tr>
	<td>Fecha Inicio:</td>
	<td><input type="date" name="fechai" value="$fechai"></td>
</tr>
<tr>
	<td>Fecha Final:</td>
	<td><input type="date" name="fechaf" value="$fechaf"></td>
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="button" value="Limpiar" onClick="fechai.value='';fechaf.value='';">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="Generar" name="generar" onClick="if(fechai.value!='' ){ cargarSeccion('$dirscript','contenido','accion=1&descargar=0&fechai=' + fechai.value + '&fechaf=' + fechaf.value)};">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="Descargar" name="descargar" onClick="if(fechai.value!='' ){$botonDescarga};">
	</td>
</tr>
</table>
</form>
</center>
formulario1;

	if($accion==1){


		if($fechai)
		{
			if($fechaf)
			{
				$ff = " AND ed.fechagen BETWEEN '$fechai' AND '$fechaf' ";	
				$repok="1";

				$titulo = "<center><h2>Reporte Cargos a Dueño del $fechai al $fechaf </h2></center>";
			}
			else
			{
				$ff = " AND ed.fechagen = '$fechai' ";
				$repok="1";	

				$titulo = "<center><h2>Reporte Cargos a Dueño del $fechai </h2></center>";
			}
		}

		$sql = "SELECT fechaedo, fechagen, concat(d.nombre,' ',d.nombre2,' ', d.apaterno,' ', d.amaterno) as propietario, concat(i.calle, ' Col. ', i.colonia, ' Alc/Mun. ',i.delmun,' C.P. ',i.cp) as inmueble, notaedo as concepto, importe, iva, importe + iva as total FROM edoduenio ed, duenio d, inmueble i WHERE ed.idduenio = d.idduenio AND i.idinmueble = ed.idinmueble AND idhistoria IS NULL AND importe < 0 AND fechagen is not null ";

		$sql .= $ff;
		//echo '<br>'.$sql.'<br>';
		$tabla = "<table style='background-color:#9C0;' border='1'><tr>
			<th>Fecha Creación</th>
			<th>Fecha Estado</th>
			<th>Propietario</th>
			<th>Inmueble</th>
			<th>Concepto</th>
			<th>Importe</th>
			<th>Iva</th>
			<th>Total</th>
		</tr>";

		$operacion = mysql_query($sql);
		$contador=0;
		while($row = mysql_fetch_array($operacion))
		{

			$fechaCreacion = $row["fechaedo"];
			$fechaEstado = $row["fechagen"];
			$propietario = $row["propietario"];
			$inmueble = $row["inmueble"];
			$concepto = $row["concepto"];
			$importe = number_format($row["importe"],2,".",",");
			$iva = number_format($row["iva"],2,".",",");
			$total = number_format($row["total"],2,".",",");

			$contador++;
			if($contador==1) {
				$color="style='background-color:#FFFFFF;'";
			}else{
				$color="style='background-color:#CCCCCC;'";
				$contador=0;
			}
			
			$tabla .= "<tr $color><td>$fechaCreacion</td><td>$fechaEstado</td><td>$propietario</td><td>$inmueble</td><td>$concepto</td><td>$ $importe</td><td>$ $iva</td><td>$ $total</td></tr>";			
			
		}

		$tabla .="</table>";

		if($repok=="1")
		{

			if($descargar==1){
				header("Content-type: application/vnd.ms-excel");
				header("Content-type: application/x-msexcel"); 
				header("Content-Disposition: attachment; filename=Cargos_Duenio.xls");
				header("Pragma: no-cache");
				header("Expires: 0");

				echo $titulo;
				echo $tabla;

			}else{

				echo $formulario;
				echo "<br>";
				echo $titulo;
				echo "<center>";
				echo $tabla;
				echo "</center>";

			}

		}

	}else{
		echo $formulario;
	}
	
}else{
	echo "A&uacute;n no se ha firmado con el servidor";
}

?>