<?php
include '../general/funcionesformato.php';
include '../general/sessionclase.php';
include '../general/ftpclass.php';
include_once('../general/conexion.php');
include ("../cfdi/cfdiclassn.php");
require('../fpdf.php');




$id=@$_GET["id"]; //para el Id de la consulta que se requiere hacer: de arrendamiento idhistoria, de libre idfolio
$filtro=@$_GET["filtro"]; //para la especificacion del tipo re recibo inmueble=null, libre=0;
$datosl=@$_GET["datosl"]; //para recibir todos los datos para la factura segun el layaut que biene de la facturalibre
$anio=@$_GET["anio"];
$mes=@$_GET["mes"];

$cfd =  New cfdi32class;
$ftp= New ftpcfdi;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='reporte_ingresos.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];		
		$ruta= $row['ruta'];
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

	$raiz=$_SERVER['DOCUMENT_ROOT'];

	$m1='';
	$m2='';
	$m3='';
	$m4='';
	$m5='';
	$m6='';
	$m7='';
	$m8='';
	$m9='';
	$m10='';
	$m11='';
	$m12='';
	$m13='';	
	
	$filtro="";
	
	if(!$anio!='' || !$mes!='')
	{//falta alguno y es vigente
		$mes = 13;	
		$filtro = 1;
		$anio=2014;
	}
	else
	{
	
		if(!$anio!='')
		{
			$anio=date('Y');
		}
		if(!$mes!='')
		{
			$mes=date('m');
		}
	
	}
	
	switch($mes)
	{
	case 1:
		$m1=' selected ';
		$mes = 12;
		$anio -=1;
		break;
	case 2:
		$m2=' selected ';
		$mes -=1;
		break;
	case 3:	
		$m3=' selected ';
		$mes -=1;
		break;
	case 4:
		$m4=' selected ';
		$mes -=1;
		break;
	case 5:
		$m5=' selected ';
		$mes -=1;
		break;
	case 6:
		$m6=' selected ';
		$mes -=1;
		break;
	case 7:
		$m7=' selected ';
		$mes -=1;
		break;
	case 8:
		$m8=' selected ';
		$mes -=1;
		break;
	case 9:
		$m9=' selected ';
		$mes -=1;
		break;
	case 10:
		$m10=' selected ';
		$mes -=1;
		break;
	case 11:		
		$m11=' selected ';
		$mes -=1;
		break;
	case 12:
		$m12= ' selected ';
		$mes -=1;
		break;
	case 13:
		$m13= ' selected ';
		$filtro = 1;
		
	}



	$htmlr = "<center><h1>Reporte de Ingresos</h1>";
	
	$htmlr .= "Nota: Los ingresos son los obtenidos en el mes anterior del periodo, ej. Reporte Enero-Febrero, muestra todos los ingresos de Enero sin importar si fueron reportados o no en ese mes. De la misma forma, se estan omitiendo todos los que fueron condonaci&oacute;n. ";
	
	$htmlr .="<form>Mes <select name='mes' onChange=\"cargarSeccion_new('$dirscript','contenido','mes=' + mes.value + '&anio=' + anio.value )\"><option value='01' $m1>Diciembre - Enero</option>";	
	$htmlr .="<option value='02' $m2>Enero - Febrero</option>";	
	$htmlr .="<option value='03' $m3>Febrero - Marzo</option>";	
	$htmlr .="<option value='04' $m4>Marzo - Abril</option>";
	$htmlr .="<option value='05' $m5>Abril - Mayo</option>";		
	$htmlr .="<option value='06' $m6>Mayo - Junio</option>";	
	$htmlr .="<option value='07' $m7>Junio - Julio</option>";	
	$htmlr .="<option value='08' $m8>Julio - Agosto</option>";	
	$htmlr .="<option value='09' $m9>Agosto - Septiembre</option>";	
	$htmlr .="<option value='10' $m10>Septiembre - Octubre</option>";	
	$htmlr .="<option value='11' $m11>Octubre - Noviembre</option>";	
	$htmlr .="<option value='12' $m12>Noviembre - Diciembre</option>";	
	$htmlr .="<option value='13' $m13>Corriente</option>";	
	$htmlr .="</select>&nbsp;&nbsp;";	
	$htmlr .="A&ntilde;o: <input type='text' name='anio' value='$anio' onChange=\"cargarSeccion_new('$dirscript','contenido','mes=' + mes.value + '&anio=' + anio.value )\"></center></form>";
		
	
	if($filtro == 1)
	{//mes corriente
		//para fechaedo el mes de hoy menos 1 y el año de hoy
		$anio=date('Y');
		$mes=date('m');
		if($mes == 1)
		{
			$anio -=1;
			$mes = 12;
			
		}
		else
		{
			$mes -=1;
		}
		
		
	}

	$filtro = " and month(fechaedo)=$mes and year(fechaedo)=$anio ";
	
	$recuperado=0;
	$recuperadoiva=0;	
	$htmlrecuperado="";
	
	$corriente=0;
	$corrienteiva=0;
	$htmlcorriente="";
	
	$anticipado=0;
	$anticipadoiva=0;
	$htmlanticipado="";
	
	$csvrecuperado="";
	$csvcorriente="";
	$csvanticipado="";
			
	//$sql = "select idhistoria, importe, iva, fechaedo from edoduenio where importe <0 $filtro and not(condonado in( select condonado from edoduenio where condonado >0 $filtro))";
	$sql = "select idhistoria, importe, iva, fechaedo,idcontrato,e.idduenio,notaedo, fechaedo, nombre, nombre2, apaterno, amaterno from edoduenio e, duenio d where e.idduenio = d.idduenio and importe <0 $filtro and not(condonado in( select condonado from edoduenio where condonado >0 $filtro)) order by e.idduenio, idcontrato";
	
	
	
	
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
	   if(substr(trim($row["notaedo"]),0,1)=="H")
	   {
		if(is_null( $row["idhistoria"])==true)
		{//es manual y se suma en corriente
			$corriente += $row["importe"];
			$corrienteiva += $row["iva"];
			$htmlcorriente .="<tr><td>" . $row["nombre"] . " " .  $row["nombre2"] . " " .  $row["apaterno"] . " " .  $row["amaterno"] . "</td><td>" . $row["idcontrato"]. "</td><td>" . $row["fechaedo"]. "</td><td>" . $row["notaedo"]. "</td><td>" . number_format($row["importe"],2) . "</td><td>" . number_format($row["iva"],2) . "</td><td>" . number_format(($row["importe"] + $row["iva"]),2) . "</td><td>" . number_format(($corriente + $corrienteiva),2)  . "</td></tr>";
			$csvcorriente .= str_replace(",","",$row["nombre"] . " " .  $row["nombre2"] . " " .  $row["apaterno"] . " " .  $row["amaterno"]) . "," . $row["idcontrato"]. "," . $row["fechaedo"]. "," . str_replace(",","",$row["notaedo"]) . "," . $row["importe"] . "," . $row["iva"] . "," . ($row["importe"] + $row["iva"]) . "," . ($corriente + $corrienteiva)  . "\n";
		
		}
		else
		{
		
			$sqlh = "select DATEDIFF('" . $row["fechaedo"] . "',fechagracia) as dias from historia where idhistoria =" . $row["idhistoria"] ;
			$operacionh = mysql_query($sqlh);
			$rowh = mysql_fetch_array($operacionh);
			
			if($rowh["dias"]>0)
			{//es recuperado
				$recuperado +=$row["importe"];
				$recuperadoiva +=$row["iva"];
				$htmlrecuperado .="<tr><td>" . $row["nombre"] . " " .  $row["nombre2"] . " " .  $row["apaterno"] . " " .  $row["amaterno"]. "</td><td>" . $row["idcontrato"]. "</td><td>" . $row["fechaedo"]. "</td><td>" . $row["notaedo"]. "</td><td>" . number_format($row["importe"],2) . "</td><td>" . number_format($row["iva"],2) . "</td><td>" . number_format(($row["importe"] + $row["iva"]),2) . "</td><td>" . number_format(($recuperado + $recuperadoiva),2)  . "</td></tr>";
				$csvrecuperado .=str_replace(",","",$row["nombre"] . " " .  $row["nombre2"] . " " .  $row["apaterno"] . " " .  $row["amaterno"]) . "," . $row["idcontrato"]. "," . $row["fechaedo"]. "," . str_replace(",","",$row["notaedo"]) . "," . $row["importe"] . "," . $row["iva"] . "," . ($row["importe"] + $row["iva"]) . "," . ($corriente + $corrienteiva)  . "\n";
			}
			elseif($rowh["dias"]>=-30)
			{//es corriente --- verificar el mes para dar 30, 31, 29 o 28
				$corriente += $row["importe"];
				$corrienteiva += $row["iva"];
				$htmlcorriente .="<tr><td>" . $row["nombre"] . " " .  $row["nombre2"] . " " .  $row["apaterno"] . " " .  $row["amaterno"] . "</td><td>" . $row["idcontrato"]. "</td><td>" . $row["fechaedo"]. "</td><td>" . $row["notaedo"]. "</td><td>" . number_format($row["importe"],2) . "</td><td>" . number_format($row["iva"],2) . "</td><td>" . number_format(($row["importe"] + $row["iva"]),2) . "</td><td>" . number_format(($corriente + $corrienteiva),2)  . "</td></tr>";
				$csvcorriente .=str_replace(",","",$row["nombre"] . " " .  $row["nombre2"] . " " .  $row["apaterno"] . " " .  $row["amaterno"]) . "," . $row["idcontrato"]. "," . $row["fechaedo"]. "," . str_replace(",","",$row["notaedo"]) . "," . $row["importe"] . "," . $row["iva"] . "," . ($row["importe"] + $row["iva"]) . "," . ($corriente + $corrienteiva)  . "\n";
			
			}
			else
			{//es anticipado
				$anticipado +=$row["importe"];
				$anticipadoiva +=$row["iva"];
				$htmlanticipado .="<tr><td>" . $row["nombre"] . " " .  $row["nombre2"] . " " .  $row["apaterno"] . " " .  $row["amaterno"] . "</td><td>" . $row["idcontrato"]. "</td><td>" . $row["fechaedo"]. "</td><td>" . $row["notaedo"]. "</td><td>" . $row["importe"]. "</td><td>" . $row["iva"] . "</td><td>" . ($row["importe"] + $row["iva"]) . "</td><td>" . number_format(($anticipado + $anticipadoiva),2)  . "</td></tr>";
				$csvanticipado .=str_replace(",","",$row["nombre"] . " " .  $row["nombre2"] . " " .  $row["apaterno"] . " " .  $row["amaterno"]) . "," . $row["idcontrato"]. "," . $row["fechaedo"]. "," . str_replace(",","",$row["notaedo"]) . "," . $row["importe"] . "," . $row["iva"] . "," . ($row["importe"] + $row["iva"]) . "," . ($corriente + $corrienteiva)  . "\n";
			}
			
		}
	   }
	}
	
	$recuperado *=(-1);
	$recuperadoiva *=(-1);
	$corriente *=(-1);
	$corrienteiva *=(-1);
	$anticipado *=(-1);
	$anticipadoiva *=(-1);
	
	//consulta para las notas de credito directas a los duenios
	//hace falta
	
	//muestro el resultado separado y sumado al final
	
	if((($recuperado + $recuperadoiva) + ($corriente + $corrienteiva) + ($anticipado + $anticipadoiva) )>0)
	{
	
	$htmlr .="<center> <table border=\"1\"><tr><th>Tipo de ingreso</th><th>Importe</th><th>I.V.A.</th><th>Total</th></tr>";
	$htmlr .=" <tr><td>Recuperado</td><td>" . number_format($recuperado,2) . "</td><td>" . number_format($recuperadoiva,2) . "</td><td>" . number_format(($recuperado + $recuperadoiva),2) . "</td></tr>";
	$htmlr .=" <tr><td>Corriente</td><td>" . number_format($corriente,2) . "</td><td>" . number_format($corrienteiva,2) . "</td><td>" . number_format(($corriente + $corrienteiva),2) . "</td></tr>";
	$htmlr .=" <tr><td>Anticipado</td><td>" . number_format($anticipado,2) . "</td><td>" . number_format($anticipadoiva,2) . "</td><td>" . number_format(($anticipado + $anticipadoiva),2) . "</td></tr>";
	$htmlr .=" <tr><td colspan='3'>Total de ingresos</td><td>" . number_format((($recuperado + $recuperadoiva) + ($corriente + $corrienteiva) + ($anticipado + $anticipadoiva) ),2)  . "</td></tr>";		
	
	
	//$htmlr .="<tr><td colspan='4'>Total</td><td>$suma</td></tr></table>";
	$htmlr .="</table></center>";
	
	$htmlr .="<center>Recuperado detalle <table border=\"1\"><tr><th>Due&ntilde;o</th><th>Contrato</th><th>Fecha</th><th>Descripci&oacute;n</th><th>Importe</th><th>iva</th><th>total</th><th>Acumulado</th></tr>$htmlrecuperado</table>";
	$htmlr .="Corriente detalle <table border=\"1\"><tr><th>Due&ntilde;o</th><th>Contrato</th><th>Fecha</th><th>Descripci&oacute;n</th><th>Importe</th><th>iva</th><th>total</th><th>Acumulado</th></tr>$htmlcorriente</table>";
	$htmlr .="Anticipado detalle <table border=\"1\"><tr><th>Due&ntilde;o</th><th>Contrato</th><th>Fecha</th><th>Descripci&oacute;n</th><th>Importe</th><th>iva</th><th>total</th><th>Acumulado</th></tr>$htmlanticipado</table></center>";
	
	$csv ="Recuperado detalle\nDueño,Contrato,Fecha,Descripcion,Importe,iva,total,Acumulado\n$csvrecuperado\n";
	$csv .="Corriente detalle\nDueño,Contrato,Fecha,Descripcion,Importe,iva,total,Acumulado\n$csvcorriente\n";
	$csv .="Anticipado detalle\nDueño,Contrato,Fecha,Descripcion,Importe,iva,total,Acumulado\n$csvanticipado\n";
	
	
	}
	

	$archivo = fopen($raiz . "/$ruta/ingreso.csv", "w");
	fwrite($archivo,$csv);
	fclose($archivo);	

	$htmlr .="<a href='$ruta/ingreso.csv" . "'>Archivo CSV</a></center>";	
	
	echo $htmlr;

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>