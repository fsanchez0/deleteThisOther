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


	$sql="select * from submodulo where archivo ='reportefacturalibre.php'";
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
<h1>Reporte de facturaci&oacute;n Libre</h1>
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
		<input type="button" value="Generar" name="generar" onClick="if(fechai.value!='' ){ cargarSeccion('$dirscript','reportediv','accion=1&descargar=0&fechai=' + fechai.value + '&fechaf=' + fechaf.value)};">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="Descargar" name="descargar" onClick="if(fechai.value!='' ){$botonDescarga};">
	</td>
</tr>
</table>
</form>
<div id="reportediv">
 </div>
</center>
formulario1;

	if($accion==1){


		if($fechai)
		{
			if($fechaf)
			{
				$ff = " AND c.fechal BETWEEN '$fechai 00:00:00' AND '$fechaf 23:59:59' ";	
				$repok="1";

				$titulo = "<center><h2>Reporte Facturacion Libre del $fechai al $fechaf </h2></center>";
			}
			else
			{
				$ff = " AND c.fechal = '$fechai' ";
				$repok="1";	
				$titulo = "<center><h2>Reporte Facturacion Libre del $fechai </h2></center>";
			}
		}

		$sql = "SELECT c.seriel, c.foliol, c.fechal, c.conceptol, c.subtotall, c.impuestol, c.totall, cl.nombrecl, cl.rfccl, cl.callecl, cl.noexteriorcl, cl.nointeriorcl, cl.coloniacl, cl.delmuncl, cl.estadocl, cl.paiscl, cl.cpcl, cl.loccl, cl.refcl, cl.emailcl, cl.emailcl1, cl.emailcl2, fc.fecha, fc.hora, fc.pdfok, fc.xmlok FROM cfdilibre c, flibrecfdi fl, facturacfdi fc, clientelibre cl WHERE c.idcfdilibre=fl.idcfdilibre AND fl.idfacturacfdi=fc.idfacturacfdi AND c.idclientelibre=cl.idclientelibre ";

		$sql .= $ff;
		$sql .= " ORDER BY fc.idfacturacfdi";

		$tabla = "<table border='1'><tr style='background-color:#9C0;'>
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

			$fechaGen = $row["fechal"];
			$serieFolio = $row["seriel"].$row["foliol"] ;

			$contador++;
			if($contador==1){
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
			
			$inqNombre=$row["nombrecl"];
			$inqRfc=$row["rfccl"];	
			$concepto =$row["conceptol"];		

			if(strpos($row["seriel"],"Ter")==false)
			{
				$duenio = "Padilla & Bujalil S.C.";
			}else{
				$duenio = "Por cuenta de Tercero";
			}

			$importe = number_format($row["subtotall"],2,".",",");
			$iva = number_format($row["impuestol"],2,".",",");
			$total = number_format($row["totall"],2,".",",");
		
			$tabla .= "<tr $color><td>$fechaGen</td><td>$serieFolio</td><td>$recu</td><td>$inqNombre</td><td>$inqRfc</td><td>$concepto</td><td>$duenio</td><td>$ $importe</td><td>$ $iva</td><td>$ $total</td></tr>";

		}

		$tabla .="</table>";

		if($repok=="1")
		{

			if($descargar==1){
				header("Content-type: application/vnd.ms-excel");
				header("Content-type: application/x-msexcel"); 
				header("Content-Disposition: attachment; filename=Facturacion_Libre.xls");
				header("Pragma: no-cache");
				header("Expires: 0");

				echo $titulo;
				echo $tabla;

			}else{
				echo "<center>";
				echo $titulo;
				echo "<br>";				
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