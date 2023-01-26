<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


//Modulo

$generar=@$_GET["generar"];
$fechai=@$_GET["fechai"];
$fechaf=@$_GET["fechaf"];
$descargar=@$_GET["descargar"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='reporteCondonaciones.php'";
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

		$reporte .="<table border=\"1\" width=\"100%\">\n";
		$reporte .="<tr><th>Usuario</th><th>Contrato</th><th>Direcci&oacute;n</th><th>Inquilino</th><th>Concepto</th><th>Fecha</th><th>Importe</th></tr>\n";

		$color = "";
		$sql = "select inm.idinmueble, inm.calle, inm.numeroint, inm.numeroext, inm.colonia, inm.delmun, inm.cp, inq.nombre,  inq.nombre2, inq.apaterno, inq.amaterno, h.idcontrato, fechanaturalpago, h.cantidad, h.iva, tipocobro, u.nombre as nombreu, u.apaterno as apaternou from historia h, contrato c, inmueble inm, inquilino inq, cobros cb, tipocobro t, usuario u where h.idcontrato=c.idcontrato and c.idinmueble=inm.idinmueble and c.idinquilino= inq.idinquilino and h.idcobros=cb.idcobros and cb.idtipocobro=t.idtipocobro and h.idusuario=u.idusuario and (condonado is null or condonado=0) and (notacredito is null or notacredito=0) and h.cantidad<0 and aplicado=1 and aprobado=1 and fechapago between '" . $fechai . "' and '" . $fechaf . "' order by idcontrato";
		$operacion = mysql_query($sql);
		
		while($row = mysql_fetch_array($operacion)){

			if($color==""){
				$color = " style='background-color:#009B7B' ";
			}else{
				$color="";
			}

			$reporte .= "<tr $color><td>" . $row["nombreu"] ." " . $row["apaternou"] ."</td><td>".$row["idcontrato"]."</td><td>" . $row["calle"] . " "  . $row["numeroext"] . " "  . $row["numeroint"] ." "  . $row["colonia"] ." "  . $row["cp"] ." "  . $row["delmun"] ."</td><td>" . $row["nombre"] ." "  . $row["nombre2"] ." "  . $row["apaterno"] ." "  . $row["amaterno"] ."</td><td>" . $row["tipocobro"]. "</td><td>" . $row["fechanaturalpago"] . "</td><td>" . number_format((($row["cantidad"] + $row["iva"])*(-1)),2) . "</td></tr>";

		}

		$reporte .="</table>";
	}

	$descargaFun="window.open('$dirscript?fechai=$fechai&fechaf=$fechaf&generar=1&descargar=1')";

$html = <<<formulario

<center>
<h1>Reporte Condonaciones</h1>
<form >
<table border="1">
<tr>
	<td>Fecha Inicial</td><td> <input type="text" name="fechai" value=$fechai>(aaaa-mm-dd)</td>
</tr>
<tr>
	<td>Fecha Final</td><td> <input type="text" name ="fechaf" value=$fechaf>(aaaa-mm-dd)</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="button" value="Limpiar" onClick="fechai.value='';fechaf.value=''">
		<input type="button" value="Generar" onClick="cargarSeccion('$dirscript','contenido','fechai=' + fechai.value + '&fechaf='+ fechaf.value + '&generar=1')">
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
		header("Content-Disposition: attachment; filename=Reportes_Condonaciones_$hoy.xls");
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