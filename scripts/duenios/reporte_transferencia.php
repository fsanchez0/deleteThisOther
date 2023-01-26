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
if($mes==""){
$mes1=date("m");
}else{
$mes1=$mes;
}
if($anio==""){
$anio1=date("Y");
}else{
$anio1=$anio;
}
$diaultimo = date("d", mktime(0,0,0, $mes1+1, 0, $anio1));
$fechas2=date('Y-m-d', mktime(0,0,0, $mes1, $diaultimo, $anio1));
$fechabuscar=date('Y-m-d', mktime(0,0,0, $mes1, 1, $anio1));


$cfd =  New cfdi32class;
$ftp= New ftpcfdi;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='reporte_transferencia.php'";
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
		$anio=date('Y');
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
		if($anio == date('Y') && $mes== date('m'))
		{
			$filtro = 1;
		}
	
	}
	
	switch($mes)
	{
	case 1:
		$m1=' selected ';
		break;
	case 2:
		$m2=' selected ';
		break;
	case 3:	
		$m3=' selected ';
		break;
	case 4:
		$m4=' selected ';
		break;
	case 5:
		$m5=' selected ';
		break;
	case 6:
		$m6=' selected ';
		break;
	case 7:
		$m7=' selected ';
		break;
	case 8:
		$m8=' selected ';
		break;
	case 9:
		$m9=' selected ';
		break;
	case 10:
		$m10=' selected ';
		break;
	case 11:		
		$m11=' selected ';
		break;
	case 12:
		$m12= ' selected ';
		break;
	case 13:
		$m13= ' selected ';
		$filtro = 1;
		
	}

	

	$htmlr = "<center><h1>Reporte de transferencias</h1>";
	
	$htmlr .= "Nota: Para que el reporte del mes corriente este correcto es necesario que todos los estados de cuenta ya esten marcados.";
	
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
		
	$csv="";
	$suma=0;

	//$htmlr .=" <table border=\"1\"><tr><th>Due&ntilde;o</th><th>Fecha</th><th>Contrato</th><th>Concepto</th><th>Importe</th></tr>";
	$htmlr .="<center> <table border=\"1\"><tr><th>Due&ntilde;o</th><th>Importe</th></tr>";
	//$csv="Dueño,Fecha,Contrato,Concepto,Importe\n";
	$csv="Dueño,Importe\n";
	
/*	
Consulta para transferencias, detalle por operacion mes anterior, mes corriente, se agrega la fechagen para anteriores.
select * from edoduenio where isnull(fechagen)=true and reportado = true order by idduenio, importe, fechaedo 

consulta para transferencias, por contratos
select idduenio, idcontrato, sum(importe + iva) from edoduenio where isnull(fechagen)=true and reportado = true group by idduenio, idcontrato order by idduenio, importe, fechaedo 

Consulta total a transferir
select sum(importe + iva) from edoduenio where isnull(fechagen)=true and reportado = true order by idduenio, importe, fechaedo 
*/


	//$sql="select * from cfdiedoduenio where month(fechad)=$mes and year(fechad)=$anio";
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
		
		//$filtro = " and month(fechaedo)<=$mes and year(fechaedo)=$anio and isnull(fechagen)=true ";
		$filtro = " and fechaedo<'$anio-" . ($mes+1) . "-01' and isnull(fechagen)=true ";
	}
	else
	{
		$filtro = " and month(fechagen)=$mes and year(fechagen)=$anio";
	
	}
	
	
	//$sql="select * from edoduenio where reportado = true $filtro order by idduenio, importe, fechaedo ";
	$sql="select idduenio,  sum(importe + iva) as importes from edoduenio where reportado = true and traspaso = 0 and fechagen between '$fechabuscar' AND '$fechas2' group by idduenio order by idduenio ";

	
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
	
	
		//para ver si se reporta y no
		$notificacion="";
		$sqlr= "select * from duenio d where idduenio = " . $row["idduenio"];
		$opr = mysql_query($sqlr);
		$r = mysql_fetch_array($opr);
		
		$duenion=CambiaAcentosaHTML($r["nombre"] . " " . $r["nombre2"] . " " . $r["apaterno"] . " " . $r["amaterno"]);
		
		
		//$htmlr .= "<tr><td>$duenion</td><td>" . $row["fechaedo"] . "</td><td>" . $row["idcontrato"] . "</td><td>" . $row["notaedo"] . "</td><td>" . ($row["importe"] + $row["iva"]) . "</td></tr>\n";
		//$csv .= "$duenion," . $row["fechaedo"] . "," . $row["idcontrato"] . "," . $row["notaedo"] . "," . ($row["importe"] + $row["iva"]) . "\n";
		if($row["importes"]>=0)
		{
		$htmlr .= "<tr><td>$duenion</td><td align='right'>" . number_format($row["importes"],2) . "</td></tr>\n";
		$csv .= "$duenion," . $row["importes"] . "\n";
		
		//$suma += $row["importe"] + $row["iva"];
		$suma += $row["importes"] ;
		}

		
	}
	
	//$htmlr .="<tr><td colspan='4'>Total</td><td>$suma</td></tr></table>";
	$htmlr .="<tr><td>Total</td><td>" . number_format($suma,2) . "</td></tr></table>";
	//$csv .= ",,,Total,$suma\n";
	$csv .= "Total,$suma\n";
	
	
	//$archivo = fopen($raiz . "/bujalil/$ruta/traspaso.csv", "w");
	$archivo = fopen($raiz . "/$ruta/traspaso.csv", "w");
	fwrite($archivo,$csv);
	fclose($archivo);	

	$htmlr .="<a href='$ruta/traspaso.csv" . "'>Archivo CSV</a></center>";
	//$htmlr .="<textarea cols='40' rows='20'>$csv</textarea>";

	echo $htmlr;

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>