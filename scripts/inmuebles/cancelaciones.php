<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';



$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='cancelaciones.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] ;// . "/" . $row['archivo'];
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





	$sql="select contrato.idcontrato as idcontra,fechainicio, fechatermino, nombre, nombre2, apaterno, amaterno, calle, numeroext, numeroint, colonia, delmun, estado, pais from contrato, inquilino,inmueble where contrato.idinmueble=inmueble.idinmueble and contrato.idinquilino= inquilino.idinquilino and concluido = false order by contrato.idcontrato";
	$result = mysql_query ($sql);
	$renglones="";
	while ($row = @mysql_fetch_array($result))
	{
	  //$idhist=$row["idhistoria"];
		/*if($filtro=="true")
		{
			$accionboton="<input type =\"button\" value=\"Activar\" onClick=\"cargarSeccion ('$dirscript/listacc.php','listacc','filtro=true&idcontrato=" . $row["idcontra"] . "&accion=1');\"  />";
		}
		else
		{*/
			$accionboton="<input type =\"button\" value=\"Cancelar\" onClick=\"cargarSeccion ('$dirscript/listacc.php','listacc','filtro=false&idcontrato=" . $row["idcontra"] . "&accion=2');\"  />";
		
		//}
		
		//$concepto = $row["tipocobro"];
		

		$renglones .= "<tr><td>" . $row["idcontra"] . "</td><td>" . $row["nombre"] . " " .  $row["nombre2"] . " " .  $row["apaterno"] . " " . $row["amaterno"] . " " . "</td><td>" . $row["calle"] . " " .  $row["numeroext"] . " - " .  $row["numeroint"] . "</td><td>" . $row["fechainicio"] . "</td><td>" . $row["fechatermino"] . "</td><td>$accionboton</td></tr>";

	}






$html = <<<fin
<center>
<h1>Cancelaci&oacute;n de contratos</h1>
<form>
<table border = "0">
<tr>
	<td> <input type = "radio" checked name="filtro1" value="false" onClick="nombrei.value='';selefiltro.value=false;cargarSeccion('$dirscript/listacc.php', 'listacc', 'filtro=' + this.value)">Contratos vigentes&nbsp;&nbsp;</td>
	<td>
		<input type = "radio" name="filtro1" value="true" onClick="nombrei.value='';selefiltro.value=true;cargarSeccion('$dirscript/listacc.php', 'listacc', 'filtro=' + this.value)">Contratos cancelados
		<input type="hidden" name="selefiltro" id="selefiltro" value="false">
	</td>	
</tr>
<tr>
	<td colspan="2" align="center">
		Nombre <input type="text" name="nombrei" id="nombrei" value="" onKeyUp="cargarSeccion('$dirscript/listacc.php', 'listacc', 'filtro=' + selefiltro.value + '&nombrei=' + this.value);" >
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		Contrato <input type="text" name="contratoi" id="contratoi" value="" onKeyUp="cargarSeccion('$dirscript/listacc.php', 'listacc', 'filtro=' + selefiltro.value + '&contratoi=' + this.value);" >
	</td>
</tr>
</table>

</form>

</center><br><br>
<div id="listacc" class="scroll">
<table border=1 id="tlista">
<tr>
<th>Contrato</th><th>Inquilino</th><th>Inmueble</th><th>F. Inicio</th><th>F. T&eacute;rmino</th><th>Acci&oacute;n</th>
</tr>
$renglones
</table>

</div>

fin;
	echo CambiaAcentosaHTML($html);


}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}

?>