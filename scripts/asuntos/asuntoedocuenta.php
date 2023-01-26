<?php

// Genera el estado de cuenta de un asunto en particular, toma como variable el numero del asunto = idasunto de la tabla
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
$id = @$_GET["asunto"];
$prueba = New Calendario;
$fondo=" class='Fondo' ";
$clasef="";
$cambio = "";


$misesion = new sessiones;
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
	
	


if ($id)
	{

	$sql= "SELECT nombre, apaterno, amaterno, asunto.idasunto as aid, asunto.descripcion as adesc, fechaapertura, fechacierre, abogado, expediente, fecha, estadocuenta.descripcion as edesc, cantidad, pagado, tipocargo, fechapagado  FROM directorio, asunto,estadocuenta,tipocargo WHERE directorio.iddirectorio = asunto.iddirectorio and asunto.idasunto=estadocuenta.idasunto and estadocuenta.idtipocargo = tipocargo.idtipocargo and asunto.idasunto=$id";

	
	$result = @mysql_query ($sql);
	$Datos = 0;
	$cabecera="";
	$historia = "";
	$suma = 0;
	setlocale(LC_MONETARY, 'en_US');

	while ($row = mysql_fetch_array($result))
	{
		if($Datos==0)
		{
			$fechacierre="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if(is_null($row["fechacierre"])==false)
			{
				$fechacierre=$row["fechacierre"];
			}
			$cabecera = "<center><h2>Estado de cuenta del asunto No. $id</h2>";
			$cabecera .= "<table border = \"1\">";
			$cabecera .= "<tr><td class='Cabecera'>Fecha de apertura</td><td>" .  $prueba->formatofecha($row["fechaapertura"]) . "</td><td class='Cabecera'>Fecha de t&eacute;rmino</td><td>" .  $fechacierre . "</td></tr>\n";
			$cabecera .= "<tr><td class='Cabecera' >Expediente</td><td colspan=\"3\">" .  $row["expediente"]  . "</td></tr>\n";
			$cabecera .= "<tr><td class='Cabecera' >Cliente</td><td colspan=\"3\">" .  CambiaAcentosaHTML($row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"])  .  "</td></tr>\n";
			$cabecera .= "<tr><td class='Cabecera' >Abogado</td><td colspan=\"3\">" .  CambiaAcentosaHTML($row["abogado"])  . "</td></tr>\n";
			$cabecera .= "<tr><td class='Cabecera' >Descripci&oacute;n</td><td colspan=\"3\">" .  CambiaAcentosaHTML($row["adesc"])  . "</td></tr>\n";
			
			$Datos=1;


		}

		$estado="PENDIENTE";
		
		if (is_null($row["pagado"])==false and $row["pagado"]==1)
		{

			$estado="PAGADO";			

		}
		else
		{

			$suma += $row["cantidad"] ;
		}


		$concepto = CambiaAcentosaHTML($row["edesc"]);
		$fecha=$prueba->formatofecha($row["fecha"]);
		
		
		if (is_null($row["fechapagado"])==false )
		{

			$fecha2=$prueba->formatofecha($row["fechapagado"]);		

		}
		else
		{

			$fecha2="";
		}		
	
		
		if($clasef==$fondo)
		{
			$clasef="";
		}
		else
		{
			$clasef=$fondo;
		}

		
		$cantidad = $row["cantidad"];
		



		$historia .= "<tr $clasef><td align='center'>" . $fecha  . "</td><td align='center'>" . CambiaAcentosaHTML($row["tipocargo"]) . "</td><td>$concepto</td><td align='right'>$ " . number_format($cantidad,2)  . "</td><td align='center'>" . $fecha2  . "</td><td>$estado</td></tr>\n";



	}
	
	$historia .="</table>";
	$cabecera .= "<tr><td class='Cabecera' >Adeudo pendiente</td><td colspan=\"3\">$ " . number_format($suma,2) . "</td></tr>";
	$cabecera .= "</table></center>\n<br><table border = \"1\" width=\"100%\">";
	$cabecera .= "<tr class='Cabecera'><th>Fecha Generado</th><th>Tipo Cargo</th><th>Concepto</th><th>Cantidad</th><th>F. Pagado</th><th>Estado</th></tr>\n";
	
echo <<<elhtml1
<html>
<head><title>Estado de cuenta</title></head>
<link rel="stylesheet" type="text/css" href="../../estilos/estilos.css">
<body>
<table border="0" width="100%" >
<tr>
	<td><img src="../../imagenes/logo.png" ></td>
	<td align='center'>
	&nbsp;
	</td>
</tr>
</table>
elhtml1;
	
	echo $cabecera . $historia;

echo "</body></html>";


	}


}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}
?>