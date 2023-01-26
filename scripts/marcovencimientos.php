<?php

//es el formulario para preparar la busqueda en la herramienta
//lateral de la ventana principal requiere "resultadomarcobusqueda.php"

include 'general/sessionclase.php';
include 'general/calendarioclass.php';
include_once('general/conexion.php');
include 'general/funcionesformato.php';

$fechas = New Calendario;

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
/*
	$sql="select * from submodulo where archivo ='cobro.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}
	*/
	$sql = "select * from privilegio p, submodulo s where idusuario=" . $misesion->usuario . " and p.idsubmodulo=s.idsubmodulo and s.archivo='cobro.php'";
	$operacion = mysql_query($sql);
if(mysql_num_rows($operacion)>0)
{

	$hoy = date("Y") . "-" . date("m") . "-" . date("d");

	$fechagsistema =mktime(0,0,0,substr($hoy, 5, 2),substr($hoy, 8, 2),substr($hoy, 0, 4));

	$periodo = $fechas->calculafecha($fechagsistema, 7, 3);

	$lista ="<table border = \"1\" class=\"letrasn\"> ";

	$sql="select c.idcontrato, apaterno, SUM(cantidad + iva) as csuma from historia h,contrato c, inquilino i where h.idcontrato = c.idcontrato and c.idinquilino=i.idinquilino and aplicado = false  and fechavencimiento between '$hoy' and '$periodo' group by idcontrato, apaterno";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		//$lista .= "<tr><td><a style=\"font-size:10;cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/cobro.php','contenido', 'paso=1&idcontrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " $" . $row["csuma"] . "</a></td></tr> ";
		$apaterno=CambiaAcentosaHTML($row["apaterno"]);
		$lista .= "<tr><td><a style=\"font-size:10;\cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/inmuebles/cobro.php','contenido', 'paso=1&idcontrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " (" . $apaterno . ")" . " $" . $row["csuma"] . "</a></td></tr> ";



	}


	//$sql="select * from contrato c, inquilino i where c.idinquilino = i.idinquilino and fechatermino between '$hoy' and '$periodo'";
	$sql="select * from contrato c, inquilino i where c.idinquilino = i.idinquilino and concluido <>true and fechatermino <= '$periodo'";
	//$sql="select * from contrato c, inquilino i where c.idinquilino = i.idinquilino and concluido <>true and activo=true and fechatermino <= '$periodo'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		$lista .= "<tr><td><b><a style=\"font-size:10;color:red;cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/inmuebles/edocuenta.php','contenido', 'contrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " (" . CambiaAcentosaHTML($row["apaterno"]) . ")" . " " . $row["fechatermino"] . "</a><b></td></tr> ";
		//$lista .= "<tr><td><a style=\"font-size:10;\" onClick=\"jabascript:cargarSeccion('scripts/inmuebles/datoscontrato.php','contenido', 'idcontrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " " . $row["fechatermino"] . "</a></td></tr> ";


	}

	$lista .="</table>";

echo <<<formulariocl
<center>

<form>
<table border="0">
<tr>
	<td><b class="letrasn">Pr&oacute;ximos vencimientos</b><br>
	<select name="periodo" onChange="cargarSeccion('scripts/resultadomarcovendimientos.php', 'busvenimientos', 'periodo=' + this.value)"">
		<option value="1">D&iacute;a de hoy</option>
		<option value="2" selected>Durante la sig. Semana</option>
		<option value="3">Durante los dos sig. Mes</option>
	</select>
</tr>
<tr>
	<td>
	<div id="busvenimientos" style="height:100px; width:180; overflow:auto;">
	$lista
	</div>
	</td>
</tr>
</table>


</form>
</center>
<input type="button" value="Lista Vencimientos" onclick="cargarSeccion('scripts/vencimientos/vencimiento.php','contenido')" style="cursor:pointer;">
formulariocl;

}
else
{
	echo "";
}

}

?>