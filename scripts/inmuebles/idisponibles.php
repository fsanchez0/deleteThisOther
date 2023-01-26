<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$id = @$_GET["id"];
$misesion = new sessiones;
$renglones="";
if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='idisponibles.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta']  . "/" . $row['archivo'];
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



	if ($id)
	{
		echo "<center><h1 >Inmueble Disponible</h1>";
		$sql="select * from inmueble where  idinmueble=$id";
		
		$result = mysql_query ($sql);
		echo "<table border=1>";
		while ($row = @mysql_fetch_array($result))
		{
			$html = "<tr><td><b>Direcci&oacute;n</b></td><td>"  . $row["calle"] . "No." . $row["numeroext"] . "-" .  $row["numeroint"] . " Col." . $row["colonia"] . " Alc/Mun. ". $row["delmun"] . " C.P." . $row["cp"] . "</td></tr>";
			$html .= "<tr><td><b>Descripci&oacute;n</b></td><td>" . $row["descripcion"] . "</td></tr>";
			$html .= "<tr><td><b>Notas</b></td><td>" . $row["notas"] . "</td></tr>";
			$html .= "<tr><td><b>Inventario</b></td><td>" . $row["inventario"] . "</td></tr>";
			$html .= "<tr><td><b>Estacionamiento</b></td><td>" . $row["estacionamiento"] . "</td></tr>";	
			$html .= "<tr><td><b>Tel&eacute;fono</b></td><td>" . $row["tel"] . "</td></tr>";
			echo CambiaAcentosaHTML($html);



		}
		echo "</table></center>";

	
	}
	else
	{



		$sql="select * from inmueble where  (not( idinmueble in (select idinmueble from contrato where concluido=false) ) or not(idinmueble in (select idinmueble from contrato))) and not (idinmueble in (select idinmueble from apartado where cancelado=0) )";
	
		$result = mysql_query ($sql);

		while ($row = @mysql_fetch_array($result))
		{
			$idhist=$row["idinmueble"];
			$accionboton="<input type =\"button\" value=\"Ver\" onClick=\"cargarSeccion('$dirscript', 'contenido', 'id=$idhist');\"  />";
				
			$renglones .= "<tr><td>" . $row["calle"] . "No." . $row["numeroext"] . "No. interior" . $row["numeroint"] .  " Col." . $row["colonia"] . " Alc/Mun. ". $row["delmun"] . " C.P." . $row["cp"] . "</td><td>" .   $row["numeroint"] . "</td><td>$accionboton</td></tr>";



		}


$html = <<<fin
<center>
<h1>Inmuebles Disponibles</h1>
<div class='scroll'>
<table border=1 id="tlista">
<tr>
<th>Direcci&oacute;n</th><th>Interior</th><th>Acci&oacute;n</th>
</tr>
$renglones
</table>
</div>
</center>
fin;
	echo CambiaAcentosaHTML($html);
	}



}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}
?>