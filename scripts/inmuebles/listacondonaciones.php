<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$accion=@$_GET["accion"];
$inquilino=@$_GET["inquilino"];
$inmueble=@$_GET["inmueble"];
$contrato=@$_GET["contrato"];

$misesion = new sessiones;
$renglones="";
if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='condonaciones.php'";
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





	$sql="select idhistoria, historia.idcontrato as idcontra,fechanaturalpago, fechagenerado, nombre, nombre2, apaterno, amaterno, tipocobro, historia.interes as hinteres, historia.cantidad as hcantidad, historia.iva as hiva, calle, numeroext, numeroint, inmueble.colonia, delmun, estado, pais from historia, inquilino,inmueble, cobros, tipocobro, contrato, estado, pais  where historia.idcontrato = contrato.idcontrato and historia.idcobros=cobros.idcobros and cobros.idtipocobro=tipocobro.idtipocobro and contrato.idinmueble=inmueble.idinmueble and contrato.idinquilino= inquilino.idinquilino and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais and aplicado = false ";
	switch($accion)
	{
	case 1: //inquilino
		$sql .= " and (nombre like '%$inquilino%' or nombre2 like '%$inquilino%' or  apaterno like '%$inquilino%'  or  amaterno  like '%$inquilino%')";
		break;
	case 2: //inmueble
		$sql .= " and (calle like '%$inmueble%' or numeroext like '%$inmueble%' or numeroint like '%$inmueble%' or inmueble.colonia like '%$inmueble%' or delmun like '%$inmueble%' or estado like '%$inmueble%' or pais like '%$inmueble%')";
		break;

	case 3: //contrato

		$sql .= " and historia.idcontrato = $contrato";
	}
	//echo $sql;
	$result = mysql_query ($sql);

	while ($row = @mysql_fetch_array($result))
	{
		$idhist=$row["idhistoria"];
		$accionboton="<input type =\"button\" value=\"Condonar\" onClick=\"Condonar ('tlista',this,$idhist,'$dirscript');\"  />";
		$concepto = $row["tipocobro"];

		if (is_null($row["hinteres"])==false and $row["hinteres"]==1)
		{
			$concepto = $row["tipocobro"] . "(INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . ")";

		}

		$renglones .= "<tr><td>" . $row["idcontra"] . "</td><td>" . $row["nombre"] . " " .  $row["nombre2"] . " " .  $row["apaterno"] . " " . $row["amaterno"] . " " . "</td><td>" . $row["calle"] . " " .  $row["numeroext"] . " - " .  $row["numeroint"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>$concepto</td><td align='right'>$ " . number_format($row["hcantidad"] + $row["hiva"],2)  . "</td><td>$accionboton</td></tr>";



	}


$html = <<<fin

<table border=1 id="tlista">
<tr>
<th>Contrato</th><th>Inquilino</th><th>Inmueble</th><th>F. Nat. Pago</th><th>Concepto</th><th>Por Pagar</th><th>Acci&oacute;n</th>
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