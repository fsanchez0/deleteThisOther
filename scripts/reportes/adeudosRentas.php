<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$descargar=@$_GET["descargar"];

$misesion = new sessiones;

if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='adeudosRentas.php'";
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

	$botonDescarga="window.open('$dirscript?descargar=1');";

	$formulario =  <<<formulario1
<center>
<h1>Reporte de Adeudo Equivalente en Rentas</h1>
<input type="button" value="Descargar" name="descargar" onClick="$botonDescarga;">
</center>
formulario1;
	
	$hoy=date('Y') . "-" . date('m') . "-" . date('d');

	$titulo = "<center><h2>Reporte de Adeudos en Rentas $hoy </h2></center>";

	$sql= "SELECT idhistoria, contrato.idcontrato AS elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno,tipocobro.idtipocobro AS elidtipocobro,historia.fechanaturalpago,historia.cantidad AS cantidadh,historia.iva AS ivah,historia.interes,cobros.cantidad AS cantidadC,cobros.iva AS ivaC, calle, numeroext, numeroint, inmueble.colonia, delmun, estado, pais, inmueble.cp, SUM(historia.cantidad) AS cantidadT, SUM(historia.iva) AS ivaT, SUM(historia.interes) AS interesT FROM contrato, cobros, inquilino,tipocobro, historia, inmueble, estado, pais  WHERE cobros.idtipocobro=tipocobro.idtipocobro AND contrato.idcontrato=historia.idcontrato AND historia.idcobros=cobros.idcobros AND contrato.idinquilino=inquilino.idinquilino AND contrato.idinmueble = inmueble.idinmueble AND inmueble.idestado = estado.idestado AND inmueble.idpais = pais.idpais AND litigio=false AND (contrato.concluido=0 OR contrato.concluido IS NULL) AND (historia.aplicado=false OR historia.aplicado=0 OR historia.aplicado IS NULL) AND cobros.idtipocobro IN (1,10) AND historia.fechanaturalpago <= '$hoy' GROUP BY contrato.idcontrato ORDER BY historia.idcontrato";

	$tabla= "<table border=1>
		<tr style='background-color:#9C0;'>
			<th>Contrato</th>
			<th>Inquilino</th>
			<th>Inmueble</th>
			<th>Delegaci&oacuten y/o Municipio</th>
			<th>Codigo Postal</th>
			<th>Renta</th>
			<th>Adeudo</th>
			<th># Rentas</th>
		</tr> ";

	$resultado= mysql_query($sql);
	while($row = mysql_fetch_array($resultado)){	

		$contratoid=$row["elidcontrato"];	
		$inquilinos = utf8_decode($row["nombre"]." ".$row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]);
		$inmuebles = utf8_decode($row["calle"] . " No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"]);
		$delagacion=utf8_decode($row["delmun"]);
		$codigop=$row["cp"];

		if($row["elidtipocobro"]==1){
			$cantidadR=$row["cantidadC"];
			$ivaR=$row["ivaC"];
			$totalR=($cantidadR+$ivaR);
		}else{
			$sqlRenta ="SELECT * FROM cobros WHERE idtipocobro=1 AND idcontrato=$contratoid";
			$resultadoRenta= mysql_query($sqlRenta);
			$rowRenta = mysql_fetch_array($resultadoRenta);
			$cantidadR=$rowRenta["cantidad"];
			$ivaR=$rowRenta["iva"];
			$totalR=($cantidadR+$ivaR);
		}

		$cantidadT=$row["cantidadT"];
		$ivaT=$row["ivaT"];
		$total=($cantidadT+$ivaT);		

		if($totalR>0){
			$numRentas = ($total/$totalR);
		}else{
			$numRentas =0;
		}
		

		$contador++;
		if($contador==1) {
			$color="style=background-color:#FFFFFF;";
		}else{
			$color="style=background-color:#CCCCCC;";
			$contador=0;
		}

		$tabla.= "<tr $color>
			<td><a href='/scripts/inmuebles/edocuenta.php?contrato=$contratoid' target='_blank'>".$contratoid."</a></td>
			<td>".$inquilinos."</td>
			<td>".$inmuebles."</td>
			<td>".$delagacion."</td>
			<td>".$codigop."</td>
			<td>$".number_format($totalR,2,".",",")."</td> 
			<td>$".number_format($total,2,".",",")."</td>
			<td>".number_format($numRentas,2)."</td>
		</tr> ";

	}
	$tabla.= "</table> "; 

	if($descargar==1){
		header("Content-type: application/vnd.ms-excel");
		header("Content-type: application/x-msexcel"); 
		header("Content-Disposition: attachment; filename=Adeudo_en_Rentas.xls");
		header("Pragma: no-cache");
		header("Expires: 0");

		echo $titulo;
		echo $tabla;

	}else{

		echo $formulario;
		echo "<br>";
		echo $titulo;
		echo $tabla;
	}	

}else{
	echo "A&uacute;n no se ha firmado con el servidor";
}

?>