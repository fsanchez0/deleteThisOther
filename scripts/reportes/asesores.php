<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


//Modulo

$filtro=@$_GET["filtro"];
$generar=@$_GET["generar"];
$idasesor=@$_GET["idasesor"];
$fechai=@$_GET["fechai"];
$fechaf=@$_GET["fechaf"];
$descargar=@$_GET["descargar"];

if(!$filtro){
	$filtro=2;
}

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='asesores.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

	$reporte="";
	$titulo="";

	if($fechai && $fechaf && $generar==1)
	{	

		$titulo = " Reporte entre las fechas $fechai y $fechaf ";

		if($filtro=="1"){
			
			$reporte .="<table border=\"1\" width=\"100%\">\n";
			$reporte .="<tr><th>Asesor</th><th>Inmueble</th><th>Direcci&oacute;n</th><th>Fecha Contrato</th><th>Dueño</th></tr>\n";
		
			if($idasesor=="0"){
				$sql = "select inmueble.idinmueble, calle, numeroint, numeroext, colonia, delmun, cp, fechacontrato, asesor.nombre as nombreas, asesor.apellido as apellidoas, duenio.nombre,  duenio.nombre2, duenio.apaterno, duenio.amaterno from inmueble, asesor, duenio, duenioinmueble where inmueble.idasesor=asesor.idasesor and inmueble.idinmueble=duenioinmueble.idinmueble and duenioinmueble.idduenio=duenio.idduenio and fechacontrato between '" . $fechai . "' and '" . $fechaf . "' group by idinmueble";
			}else{
				$sql = "select inmueble.idinmueble, calle, numeroint, numeroext, colonia, delmun, cp, fechacontrato, asesor.nombre as nombreas, asesor.apellido as apellidoas, duenio.nombre,  duenio.nombre2, duenio.apaterno, duenio.amaterno from inmueble, asesor, duenio, duenioinmueble where inmueble.idasesor=asesor.idasesor and inmueble.idinmueble=duenioinmueble.idinmueble and duenioinmueble.idduenio=duenio.idduenio and inmueble.idasesor=$idasesor and fechacontrato between '" . $fechai . "' and '" . $fechaf . "' group by idinmueble";
			}

		}else{

			$slqContratos="select c.idcontrato, fechainicio, fechagenerado from contrato c, historia h, cobros cb where c.idcontrato=h.idcontrato and h.idcobros=cb.idcobros and c.idcontrato=cb.idcontrato and idperiodo!=1 and (c.litigio=0 or c.litigio is null) and (parcialde=idhistoria or parcialde is null) and ( (fechainicio between '" . $fechai . "' and '" . $fechaf . "') or (fechagenerado between '" . $fechai . "' and '" . $fechaf . "')) group by c.idcontrato";
			
			$operacionContratos = mysql_query($slqContratos);

			$añoInicio=substr($fechai,0,4);
			$mesInicio=substr($fechai,5,2);

			$añoFin=substr($fechaf,0,4);
			$mesFin=substr($fechaf,5,2);
			$contratosfinal="";

			while($rowContratos = mysql_fetch_array($operacionContratos)){

				if($rowContratos["fechainicio"]<$rowContratos["fechagenerado"]){
					$fechaTemp=$rowContratos["fechainicio"];
				}elseif($rowContratos["fechainicio"]>$rowContratos["fechagenerado"]){
					$fechaTemp=$rowContratos["fechagenerado"];
				}else{
					$fechaTemp=$rowContratos["fechainicio"];
				}

				$añoTemp=substr($fechaTemp,0,4);
				$mesTemp=substr($fechaTemp,5,2);

				if($añoTemp>=$añoInicio && $añoTemp<=$añoFin){
					if($mesTemp>=$mesInicio && $mesTemp<=$mesFin){
						$contratosfinal .= $rowContratos["idcontrato"].",";							
					}
				}
			}
			
			$reporte .="<table border=\"1\" width=\"100%\">\n";
			$reporte .="<tr><th>Asesor</th><th>Contrato</th><th>Direcci&oacute;n</th><th>Inquilino</th><th>Fecha Inicio</th><th>Fecha Termino</th><th>Cobros</th></tr>\n";

			if($idasesor=="0"){
				$sql = "select idcontrato, fechainicio, fechatermino, inmueble.calle, inmueble.numeroint, inmueble.numeroext, inmueble.colonia, inmueble.delmun, inmueble.cp, asesor.nombre as nombreas, asesor.apellido as apellidoas, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno from contrato, inmueble, asesor, inquilino where contrato.idinmueble = inmueble.idinmueble and contrato.idasesor=asesor.idasesor and contrato.idinquilino=inquilino.idinquilino and idcontrato in (".substr($contratosfinal,0,-1).") group by idcontrato";
			}else{
				$sql = "select idcontrato, fechainicio, fechatermino, inmueble.calle, inmueble.numeroint, inmueble.numeroext, inmueble.colonia, inmueble.delmun, inmueble.cp, asesor.nombre as nombreas, asesor.apellido as apellidoas, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno from contrato, inmueble, asesor, inquilino where contrato.idinmueble = inmueble.idinmueble and contrato.idasesor=asesor.idasesor and contrato.idinquilino=inquilino.idinquilino and contrato.idasesor=$idasesor and idcontrato in (".substr($contratosfinal,0,-1).") group by idcontrato";
			}
		}

		$color = "";
		$operacion = mysql_query($sql);
		
		while($row = mysql_fetch_array($operacion)){

			if($filtro=="1"){

				if($color==""){
					$color = " style='background-color:#009B7B' ";
				}else{
					$color="";
				}

				$reporte .= "<tr $color><td>" . $row["nombreas"] . " "  . $row["apellidoas"] . "</td><td>" . $row["idinmueble"] ."</td><td>" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"]. " " . $row["colonia"] . " " . $row["delmun"] . "</td><td>" . $row["fechacontrato"] . "</td><td>" . $row["nombre"] . " ". $row["nombre2"] . " "  . $row["apaterno"]. " " . $row["amaterno"] . "</td></tr>";

			}elseif($filtro=="2"){
				$reporte .= "<tr><td>" . $row["nombreas"] . " "  . $row["apellidoas"] . "</td><td>" . $row["idcontrato"] ."</td><td>" . $row["calle"] . " " .$row["numeroext"] . " " . $row["numeroint"]. " " . $row["colonia"] . " " . $row["delmun"] . "</td><td>" . $row["nombre"] . " ". $row["nombre2"] . " "  . $row["apaterno"]. " " . $row["amaterno"] . "</td><td>" . $row["fechainicio"] . "</td><td>" . $row["fechatermino"] . "</td><td>
					<table border=\"1\">
						<tr><th>Concepto</th><th>Periodo</th><th>Cantidad</th><th>Iva</th><th>Total</th></tr>";

				$color2 = "";
				$sqlCobros="select tipocobro, cantidad, iva, nombre from cobros, tipocobro, periodo where cobros.idtipocobro=tipocobro.idtipocobro and cobros.idperiodo=periodo.idperiodo and cobros.idcontrato=".$row["idcontrato"];

				$operacionCobros = mysql_query($sqlCobros);
				while($rowCobros = mysql_fetch_array($operacionCobros)){

					if($color2==""){
						$color2 = " style='background-color:#009B7B' ";
					}else{
						$color2="";
					}
				
					$reporte .= "<tr $color2><td>".$rowCobros["tipocobro"]."</td><td>".$rowCobros["nombre"]."</td><td>$".$rowCobros["cantidad"]."</td><td>$".$rowCobros["iva"]."</td><td>$". ($rowCobros["cantidad"] + $rowCobros["iva"]) ."</td></tr>";

				}

				$reporte .="</table><td><tr>";
			}
		}

		$reporte .="</table>";
	}


	if($filtro==1){
		$checkedAdmin="checked";
		$checkedRenta="";
	}else{
		$checkedRenta="checked";
		$checkedAdmin="";
	
	}

	$sql0="select asesor.idasesor, asesor.nombre, asesor.apellido from asesor, asesorcategoria where asesor.idasesor=asesorcategoria.idasesor and asesorcategoria.idcategoriaas=$filtro order by idasesor";
	$asesorSelect= "<select name=\"idasesor\"><option value=\"0\">Todos los asesores</option>";;
	$operacion0 = mysql_query($sql0);
	while($row0 = mysql_fetch_array($operacion0)) {
		$seleccionAsesor="";
		if (@$idasesor==$row0["idasesor"]) {
			$seleccionAsesor=" SELECTED ";
		}
		$asesorSelect .= "<option value=\"" . $row0["idasesor"] . "\" $seleccionAsesor>" . $row0["nombre"]." ".$row0["apellido"] . "</option>";
	}
	$asesorSelect .="</select>";

	$descargaFun="window.open('$dirscript?filtro=$filtro&idasesor=$idasesor&fechai=$fechai&fechaf=$fechaf&generar=1&descargar=1')";

$html = <<<formulario

<center>
<h1>Reporte Asesores</h1>
<form >
<table border="1">
<tr>
	<td>Tipo de Asesores por: </td>
	<td align="center"><input type="radio" value="1" name="filtro" $checkedAdmin onChange ="cargarSeccion('$dirscript','contenido','filtro=1&idasesor=' + idasesor.value + '&fechai=' + fechai.value + '&fechaf='+ fechaf.value);">RC Administracion&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="2" name="filtro" $checkedRenta onChange ="cargarSeccion('$dirscript','contenido','filtro=2&idasesor=' + idasesor.value + '&fechai=' + fechai.value + '&fechaf='+ fechaf.value);">RC Renta<input type='hidden' name='filtroe' value="$filtro" ></td>
</tr>
<tr>
	<td>Fecha Inicial</td><td> <input type="text" name="fechai" value=$fechai>(aaaa-mm-dd)</td>
</tr>
<tr>
	<td>Fecha Final</td><td> <input type="text" name ="fechaf" value=$fechaf>(aaaa-mm-dd)</td>
</tr>
<tr>
	<td>Asesor</td><td>$asesorSelect</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="button" value="Limpiar" onClick="fechai.value='';fechaf.value='';idasesor.value=0">
		<input type="button" value="Generar" onClick="cargarSeccion('$dirscript','contenido','filtro=' + filtroe.value + '&idasesor=' + idasesor.value + '&fechai=' + fechai.value + '&fechaf='+ fechaf.value + '&generar=1')">
	</td>
</tr>
</table>
</form>
<input type="button" value="Imprmir reporte" onClick="imprimir('reportediv');">
<input type="button" value="Descargar" onClick=$descargaFun>
<div class="scroll" id="reportediv">
<center><h2>$titulo</h2></center>
$reporte
</div>

</center>

formulario;

	if($descargar==1){
		$hoy=date("Y")."-".date("m")."-".date("d");
		header('Content-type: application/vnd.ms-excel');
		header("Content-Disposition: attachment; filename=Reportes_Asesores_$hoy.xls");
		header("Pragma: no-cache");
		header("Expires: 0");

		echo "<center><h2>$titulo</h2></center>
			$reporte";

	}else{
		echo CambiaAcentosaHTML($html);
	}

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>