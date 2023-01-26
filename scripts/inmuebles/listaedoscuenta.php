<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$opt = @$_GET["opcion"];
$patron = @$_GET["patron"];
$contra=@$_GET["contra"];
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
		$opttodos="checked ";
		$filtro="";
		if ($patron!="") {
			$filtro = "and (CONCAT(nombre, ' ',nombre2, ' ', apaterno, ' ', amaterno ) like '%$patron%' or calle like '%$patron%')";
		}
		if ($contra!="") {
			$filtro = "and (contrato.idcontrato like '%$contra%')";
		}
		$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and enedicion=false $filtro";

		break;
	case 1: //Cerrados
		$optcerrados=" checked ";
		$filtro="";
		if ($patron!="") {
			$filtro = "and (CONCAT(nombre, ' ',nombre2, ' ', apaterno, ' ', amaterno ) like '%$patron%' or calle like '%$patron%')";
		}
		if ($contra!="") {
			$filtro = "and (contrato.idcontrato like '%$contra%')";
		}
		$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and concluido = true  $filtro";
		break;
	case 2: //vigentes
		$optvigentes=" checked ";
		$filtro="";
		if ($patron!="") {
			$filtro = "and (CONCAT(nombre, ' ',nombre2, ' ', apaterno, ' ', amaterno ) like '%$patron%' or calle like '%$patron%')";
		}
		if ($contra!="") {
			$filtro = "and (contrato.idcontrato like '%$contra%')";
		}

		$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and concluido = false and enedicion=false $filtro";
		break;
	default: //vigentes
		$optvigentes=" checked ";
		$filtro="";
		if ($patron!="") {
			$filtro = "and (CONCAT(nombre, ' ',nombre2, ' ', apaterno, ' ', amaterno ) like '%$patron%' or calle like '%$patron%')";
		}
		if ($contra!="") {
			$filtro = "and (contrato.idcontrato like '%$contra%')";
		}
		$sql = "select * from inquilino, contrato, inmueble where inquilino.idinquilino = contrato.idinquilino and contrato.idinmueble=inmueble.idinmueble and concluido = false and enedicion=false $filtro";
	
	}
	

	
	
	$result = mysql_query ($sql);

	while ($row = @mysql_fetch_array($result))
	{
		$idc=$row["idcontrato"];
		$edocuenta="window.open( '$ruta/edocuenta.php?contrato=" . $idc . "');";
		$accionboton="<input type =\"button\" value=\"Ver\" onClick=\"$edocuenta\"  />";
				
		$renglones .= "<tr><td>" . $row["idcontrato"] . " </td><td>" .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] .  "</td><td>" . $row["calle"] . "No." . $row["numeroext"] . " Col." . $row["colonia"] . " Alc/Mun. ". $row["delmun"] . " C.P." . $row["cp"] . "</td><td>" .   $row["numeroint"] . "</td><td>$accionboton</td></tr>";



	}


$html = <<<fin

<table border=1 id="tlista">
<tr>
<th>Cont.</th><th>Inquilino</th><th>Direcci&oacute;n</th><th>Interior</th><th>Edo. Cuenta</th>
</tr>
$renglones
</table>

fin;

	echo CambiaAcentosaHTML($html);


}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}
?>