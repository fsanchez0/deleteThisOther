<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$opt = @$_GET["opt"];
$misesion = new sessiones;
$renglones="";
if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='edoscuentainm.php'";
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


	$opttodos="";
	$optcerrados="";
	$optvigentes="";
	switch ($opt)
	{
	case 0: //todos
		$opttodos=" checked ";
		$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and enedicion=false";
	
		break;
	case 1: //Cerrados
		$optcerrados=" checked ";
		$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and concluido = true ";
		break;
	case 2: //vigentes
		$optvigentes=" checked ";
		$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and concluido = false and enedicion=false";
		break;
	default: //vigentes
		$optvigentes=" checked ";
		$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and concluido = false and enedicion=false";
	
	}
	

	//$todos="<input type='radio' onClick = \"cargarSeccion('$dirscript','contenido', 'opt=0');\"  $opttodos>Todos";
	//$cerrados="<input type='radio' onClick = \"cargarSeccion('$dirscript','contenido', 'opt=1');\"  $optcerrados>Cerrados";
	//$vigentes="<input type='radio' onClick = \"cargarSeccion('$dirscript','contenido', 'opt=2');\"  $optvigentes>Vigentes";
	$todos="<input type='radio' name='opciontv' onClick = \"document.getElementById('patronb').value = '';document.getElementById('opciont').value=0;cargarSeccion('$ruta/listaedoscuenta.php', 'listadirectorio', 'patron=&opcion=' + document.getElementById('opciont').value)\"  $opttodos>Todos";
	$cerrados="<input type='radio' name='opciontv' onClick = \"document.getElementById('patronb').value = '';document.getElementById('opciont').value=1;cargarSeccion('$ruta/listaedoscuenta.php', 'listadirectorio', 'patron=&opcion=' + document.getElementById('opciont').value)\"  $optcerrados>Concluidos";
	$vigentes="<input type='radio' name='opciontv' onClick = \"document.getElementById('patronb').value = '';document.getElementById('opciont').value=2;cargarSeccion('$ruta/listaedoscuenta.php', 'listadirectorio', 'patron=&opcion=' + document.getElementById('opciont').value)\"  $optvigentes>Vigentes";
	
	$oculto="<input type='hidden' name='opciont' id='opciont' value='0'>";
	$opciones = "<table border='0'><tr><td>$todos<td>$cerrados</td><td>$vigentes </td></tr></table>$oculto";
	
	
	$result = mysql_query ($sql);

	while ($row = @mysql_fetch_array($result))
	{
		$idc=$row["idcontrato"];
		$edocuenta="window.open( '$ruta/edocuenta.php?contrato=" . $idc . "');";
		$accionboton="<input type =\"button\" value=\"Ver\" onClick=\"$edocuenta\"  />";
				
		$renglones .= "<tr><td>" . $row["idcontrato"] . " </td><td>" .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] .  "</td><td>" . $row["calle"] . "No." . $row["numeroext"] . " Col." . $row["colonia"] . " Alc/Mun. ". $row["delmun"] . " C.P." . $row["cp"] . "</td><td>" .   $row["numeroint"] . "</td><td>$accionboton</td></tr>";



	}


$html = <<<fin
<center>
<h1>Estados de cuenta de Contratos</h1>
<p>
$opciones<br>
Buscar: <input type="text" name="patronb" id="patronb" value="" onKeyUp="cargarSeccion('$ruta/listaedoscuenta.php', 'listadirectorio', 'patron=' + this.value + '&opcion=' + document.getElementById('opciont').value)"/>
<br><br>
Contrato: <input type="text" name="contratob" id="contratob" value="" onKeyUp="cargarSeccion('$ruta/listaedoscuenta.php', 'listadirectorio', 'contra=' + this.value + '&opcion=' + document.getElementById('opciont').value)"/>
</p>



<div class='scroll' id="listadirectorio">
<table border=1 id="tlista" >
<tr>
<th>Cont.</th><th>Inquilino</th><th>Direcci&oacute;n</th><th>Interior</th><th>Edo. Cuenta</th>
</tr>
$renglones
</table>
</div>
</center>
fin;

	echo 	CambiaAcentosaHTML($html) ;


}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}
?>