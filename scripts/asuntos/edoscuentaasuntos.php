<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$misesion = new sessiones;
$renglones="";
if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='edoscuentaasuntos.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta=$row['ruta'] ;
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


	
	$sql = "select * from asunto,directorio where asunto.iddirectorio=directorio.iddirectorio and (idasunto in (select idasunto from estadocuenta where pagado=false ) or  cerrado=false) order by idasunto";
	
	$result = mysql_query ($sql);

	while ($row = @mysql_fetch_array($result))
	{
		$idc=$row["idasunto"];
		$edocuenta="window.open( '$ruta/asuntoedocuenta.php?asunto=" . $idc . "');";
		
		$accionboton="<input type =\"button\" value=\"Ver\" onClick=\"$edocuenta\"  />";
				
		$renglones .= "<tr><td>" . $row["idasunto"] . " </td><td>" .  $row["expediente"]  .  "</td><td>" . CambiaAcentosaHTML($row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"]) . "</td><td>" . CambiaAcentosaHTML($row["descripcion"]) . "</td><td>" . CambiaAcentosaHTML($row["abogado"]) . "</td><td>$accionboton</td></tr>";



	}


echo <<<fin
<center>
<h1>Estados de cuenta de Asuntos</h1>

<div class='scroll'>
<table border=1 id="tlista">
<tr>
<th>No.</th><th>Expediente</th><th>Cliente</th><th>Descripci&oacute;n</th><th>Abogado</th><th>Edo. Cuenta</th>
</tr>
$renglones
</table>
</div>
</center>
fin;




}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}
?>