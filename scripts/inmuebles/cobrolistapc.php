<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$dato = @$_GET["dato"];
$btn = @$_GET["btn"];



//$enlace = mysql_connect('localhost', 'root', '');
//mysql_select_db('bujalil',$enlace) ;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{


		$sql="select * from submodulo where archivo ='cobrolistapc.php'";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$dirscript= $row['ruta'] . "/" . $row['archivo'];
			$ruta=$row['ruta'];
			$priv = $misesion->privilegios_secion($row['idsubmodulo']);
			$priv=split("\*",$priv);
	
		}


	$html = "";
	$sql = "select * from cobropc";
	$result = @mysql_query ($sql);

	if (!$result)
	{
		echo "<strong> No hay ningun resultado con ese patron de busqueda</strong>";
	}
	else
	{
		$html = "<table border=1>\n<tr>\n<th>Contrato</th><th>Nombre</th><th>Inmueble</th><th>Fecha del pago</th><th>Importe</th><th>Acci&oacute;n</th>\n</tr>";
		while ($row = mysql_fetch_array($result))
		{
			$idc=$row["idcontrato"];
			$id= $row["idcobropc"];	
			$fechapagopc = $row["fechapc"];					
			$sqld = "select * from contrato c, inquilino iq, inmueble i where c.idinquilino = iq.idinquilino and c.idinmueble = i.idinmueble and c.idcontrato =$idc";
			$resultd = @mysql_query ($sqld);
			$rowd = mysql_fetch_array($resultd);
						
			//info del inquilino
			$nombre = trim($rowd["nombre"] . " " . $rowd["nombre2"] . " " . $rowd["apaterno"] . " " . $rowd["amaterno"]);
			
			//info del inmueble
			$inmueble = $rowd["calle"] . " " . $rowd["numeroext"] . " " . $rowd["numeroint"];
			
			//dato del importe
			$importe = $row["efectivo"];
			
			$html .= "<tr>\n";
			
			$js = "cargarSeccion('$ruta/cobroconfirmar.php','verificacion', 'accion=1&idcobropc=$id')";
			$jsv = "cargarSeccion('$ruta/cobroconfirmar.php','verificacion', 'accion=2&idcobropc=$id')";
			$jsb = "cargarSeccion('$ruta/cobroconfirmar.php','verificacion', 'accion=3&idcobropc=$id')";
			$btn = "<input type='button' value='Verificar' onclick=\"$jsv\">";
			$btn .= "<input type='button' value='Aplicar' onclick=\"$js\">";
			$btn .= "<input type='button' value='borrar' onclick=\"$jsb\">";
			$html .="<td>$idc</td><td>$nombre</td><td>$inmueble</td><td>$fechapagopc</td><td>$importe</td>";			
			$html .="<td>$btn</td>";

			$html .= "</tr>\n";
			$html = CambiaAcentosaHTML($html);
		}
		$html .= "</table>";
	
	}
	
	
echo $pagina= "<center>
<h1>Lista de captura de pagos por confirmar</h1>
<div id=\"verificacion\"></div>
<input type=\"button\" value=\"Limpiar\" onclick=\"document.getElementById('verificacion').innerHTML='';\">
$html
</center>";

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>
