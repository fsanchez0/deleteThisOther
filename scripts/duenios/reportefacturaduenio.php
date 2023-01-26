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


	$sql="select * from submodulo where archivo ='reportefacturaduenio.php'";
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
<h1>Reporte de facturaci&oacute;n Dueños</h1>
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

				$titulo = "<center><h2>Reporte Facturacion Dueño del $fechai al $fechaf </h2></center>";
			}
			else
			{
				$ff = " AND ed.fechagen = '$fechai' ";
				$repok="1";	

				$titulo = "<center><h2>Reporte Facturacion Dueño del $fechai </h2></center>";
			}
		}

		$sql = "SELECT distinct(fi.idfacturacfdi), fi.serie as seried, fi.folio as foliod, ed.fechagen as fechad, cd.conceptod, fi.subtotal as subtotald, fi.traslados as impuestos, fi.total as totald, d.nombre, d.nombre2, d.apaterno, d.amaterno, d.rfcd, d.called, d.noexteriord, d.nointeriord, d.coloniad, d.delmund, d.estadod, d.paisd, d.cpd, fi.fecha, fi.hora, fi.pdfok, fi.txtok FROM facturacfdid fd, facturacfdi fi, cfdiedoduenio cd, duenio d, cfdipartidas cp, edoduenio ed WHERE fd.idcfdiedoduenio = cd.idcfdiedoduenio AND fd.idfacturacfdi = fi.idfacturacfdi AND cp.idcfdiedoduenio = fd.idcfdiedoduenio AND cp.idedoduenio = ed.idedoduenio AND d.idduenio = ed.idduenio ";

		$sql .= $ff;
		$sql .= " ORDER BY fi.idfacturacfdi";

		$tabla = "<table style='background-color:#9C0;' border='1'><tr>
			<th>Fecha creacion</th>
			<th>SerieFolio</th>
			<th>Recuperado(PDF)</th>
			<th>Nombre o Razon social</th>
			<th>RFC</th>
			<th>Concepto</th>
			<th>Emisor</th>
			<th>Subtotal</th>
			<th>Iva</th>
			<th>Total</th>
		</tr>";

		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{

			$fechaGen = $row["fechad"];
			$serieFolio = $row["seried"].$row["foliod"] ;
			
			$inqNombre = $row["nombre"]." ".$row["nombre2"]." ".$row["apaterno"]." ".$row["amaterno"];
			$inqRfc = $row["rfcd"];

			$conceptosLista = preg_split("/[*]/",$row["conceptod"]);

			$tablaCn="<table border='1'><tr style='background-color:#9C0;'><th>#</th><th>Descripcion</th><th>cantidad</th></tr>";

			foreach ($conceptosLista as $key => $value) {

				$contadorInterno++;
				if($contadorInterno==1) {
					$color="style='background-color:#FFFFFF;'";
				}else{
					$color="style='background-color:#CCCCCC;'";
					$contadorInterno=0;
				}

				$conceptoCant =	preg_split("/[|]/",$value);
				if($value!='' || $value!=NULL)	{
					$tablaCn .= "<tr $color><td>".($key + 1) ."</td><td>".$conceptoCant[0]."</td><td>".$conceptoCant[1] ."</td></tr>";
				}
			}
			$tablaCn .= "</table>";

			if(strpos($row["seried"],"Ter")==false)
			{
				$duenio = "Padilla & Bujalil S.C.";
			}else{
				$duenio = "Por cuenta de Tercero";
			}

			$importe = number_format($row["subtotald"],2,".",",");
			$iva = number_format($row["impuestos"],2,".",",");
			$total = number_format($row["totald"],2,".",",");

			$contador++;
			if($contador==1) {
				$color="style='background-color:#FFFFFF;'";
			}else{
				$color="style='background-color:#CCCCCC;'";
				$contador=0;
			}

			if($row["pdfok"]==1){
				$recu = "SI";
			}else{
				$recu = "NO";
				$color="style='background-color:#CE3939;'";
			}
			
			$tabla .= "<tr $color><td>$fechaGen</td><td>$serieFolio</td><td>$recu</td><td>$inqNombre</td><td>$inqRfc</td><td>$tablaCn</td><td>$duenio</td><td>$$importe</td><td>$ $iva</td><td>$ $total</td></tr>";			
			
		}

		$tabla .="</table>";

		if($repok=="1")
		{

			if($descargar==1){
				header("Content-type: application/vnd.ms-excel");
				header("Content-type: application/x-msexcel"); 
				header("Content-Disposition: attachment; filename=Facturacion_Duenio.xls");
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