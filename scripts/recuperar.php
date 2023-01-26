<?php
include 'general/funcionesformato.php';
include 'general/sessionclase.php';
include 'general/ftpclass.php';
include "insertcfdi/lxml3.php";
include_once('general/conexion.php');
include ("cfdi/cfdiclassn.php");
require('fpdf.php');




$id=@$_GET["id"]; //para el Id de la consulta que se requiere hacer
$filtro=@$_GET["filtro"]; //para la especificacion del tipo re recibo "categoria";
$dir="../../../../home/wwwarchivos/cfdi/";

$xmlcfdi = new xmlcfd_cfdi;

function datoscfdi($archivocfdi,&$xmlcfdi,$id)
{
		$dir="/home/wwwarchivos/cfdi/";
		//$dir="C:\home\wwwarchivos\cfdi\\";
		$dir .= $archivocfdi;
		$xmlcfdi->leerXML($dir);

		if($id<39146){

			//hacer el update de los datos de la factura
			$sql = "update facturacfdi set ";
			$sql .= " serie= '" . $xmlcfdi->comprobante["serie"]["valor"] . "',";
			$sql .= " folio =" . $xmlcfdi->comprobante["folio"]["valor"] . ",";
			$fecha =substr($xmlcfdi->comprobante["fecha"]["valor"],0,10); 
			$hora =substr($xmlcfdi->comprobante["fecha"]["valor"],11); 
			$sql .= " fecha= '$fecha',";
			$sql .= " hora = '$hora',";
			$sql .= " noaprobacion = '" . $xmlcfdi->comprobante["noAprobacion"]["valor"] . "',";
			$sql .= " anioaprobacion = '" . $xmlcfdi->comprobante["anoAprobacion"]["valor"] . "',";
			$sql .= " subtotal = " . $xmlcfdi->comprobante["subTotal"]["valor"] . ",";
			if(@$xmlcfdi->comprobante["Impuestos"]["totalImpuestosRetenidos"]["valor"]=='')
			{
				$sql .= " retenciones=0 , ";	
			}
			else 
			{
				$sql .= " retenciones= " . @$xmlcfdi->comprobante["Impuestos"]["totalImpuestosRetenidos"]["valor"] . ",";
			}
			//$sql .= " retenciones= " . @$xmlcfdi->comprobante["Impuestos"]["totalImpuestosRetenidos"]["valor"] . ",";
			$sql .= " traslados = " . $xmlcfdi->comprobante["Impuestos"]["totalImpuestosTrasladados"]["valor"] . ",";
			$sql .= " total = " . $xmlcfdi->comprobante["total"]["valor"] . ",";
			$sql .= " concepto = '" . substr($xmlcfdi->comprobante["Conceptos"]["Concepto"]["descripcion"]["valor"]->asXML(),14,-1) . "' ";
			$sql .= " where idfacturacfdi = $id ";
			$operacionfn = mysql_query($sql);

		}else{
			//hacer el update de los datos de la factura
			$sql = "update facturacfdi set ";
			$sql .= " serie= '" . $xmlcfdi->comprobante["Serie"]["valor"] . "',";
			$sql .= " folio =" . $xmlcfdi->comprobante["Folio"]["valor"] . ",";
			$fecha =substr($xmlcfdi->comprobante["Fecha"]["valor"],0,10); 
			$hora =substr($xmlcfdi->comprobante["Fecha"]["valor"],11); 
			$sql .= " fecha= '$fecha',";
			$sql .= " hora = '$hora',";
			$sql .= " noaprobacion = '" . $xmlcfdi->comprobante["noAprobacion"]["valor"] . "',";
			$sql .= " anioaprobacion = '" . $xmlcfdi->comprobante["anoAprobacion"]["valor"] . "',";
			$sql .= " subtotal = " . $xmlcfdi->comprobante["SubTotal"]["valor"] . ",";
			if(@$xmlcfdi->comprobante["Impuestos"]["TotalImpuestosRetenidos"]["valor"]=='')
			{
				$sql .= " retenciones=0 , ";	
			}
			else 
			{
				$sql .= " retenciones= " . @$xmlcfdi->comprobante["Impuestos"]["TotalImpuestosRetenidos"]["valor"] . ",";
			}
			//$sql .= " retenciones= " . @$xmlcfdi->comprobante["Impuestos"]["totalImpuestosRetenidos"]["valor"] . ",";
			$sql .= " traslados = " . $xmlcfdi->comprobante["Impuestos"]["TotalImpuestosTrasladados"]["valor"] . ",";
			$sql .= " total = " . $xmlcfdi->comprobante["Total"]["valor"] . ",";
			$sql .= " concepto = '" . substr($xmlcfdi->comprobante["Conceptos"]["Concepto"]["Descripcion"]["valor"]->asXML(),14,-1) . "' ";
			$sql .= " where idfacturacfdi = $id ";
			$operacionfn = mysql_query($sql);
		}	
	
}




$cfd =  New cfdi32class;
$ftp= New ftpcfdi;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{


			switch($filtro)
			{
			case 1://facturalibre
				$sqlcfdi="select idfacturacfdi from flibrecfdi where idcfdilibre = $id";
				$operacion = mysql_query($sqlcfdi);
				//$r= mysql_fetch_array($operacion);
				//$idcfdi = $r["idfacturacfdi"];			
			
				break;
			case 2://duenios
				$sqlcfdi="select idfacturacfdi from facturacfdid where idcfdiedoduenio = $id";
				$operacion = mysql_query($sqlcfdi);
				//$r= mysql_fetch_array($operacion);
				//$idcfdi = $r["idfacturacfdi"];			
			
				break;				
			default:
				$sqlcfdi="select idfacturacfdi from historiacfdi where idhistoria = $id";
				$operacion = mysql_query($sqlcfdi);
				//$r= mysql_fetch_array($operacion);
				//$idcfdi = $r["idfacturacfdi"];			
			
			}

	while ($r = mysql_fetch_array($operacion))
	{

	//$sql="select * from facturacfdi where idfacturacfdi=$idcfdi";
	$sql="select archivotxt, archivopdf, archivoxml from facturacfdi where idfacturacfdi=" . $r["idfacturacfdi"];
	$idcfdi=$r["idfacturacfdi"];
	$operacioncfdi = mysql_query($sql);
	$row = mysql_fetch_array($operacioncfdi);


	$ftp->archivotxt = $row["archivotxt"];
	$ftp->archivopdf = $row["archivopdf"];
	$ftp->archivoxml = $row["archivoxml"];
	$ftp->archivopdfn = "";	
	
	$ac = $ftp->recoger($idcfdi,$filtro);
	//$ac=1;
	if($ac==0)
	{
			switch($filtro)
			{
			case 1://facturalibre
	
				echo "<input type=\"button\" value='Reintentar' onClick=\"cargarSeccion('../recuperar.php','cfdi$id', 'id=$id&filtro=$filtro');\">";
				break;
			case 2://facturaduenio
	
				echo "<input type=\"button\" value='Reintentar' onClick=\"cargarSeccion('../recuperar.php','cfdi$id', 'id=$id&filtro=$filtro');\">";
				break;				
			default:
				echo "<input type=\"button\" value='Reintentar' onClick=\"cargarSeccion('../recuperar.php','cfdi$id', 'id=$id&filtro=$filtro');\">";
				
			}
			
	}
	else
	{
			
			switch($filtro)
			{
			case 1://facturalibre		
				echo "<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivopdf"] . "\"  target=\"_blank\" >" .  $row["archivopdf"] . "\n</a>";
				echo "<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivoxml"] . "\"  target=\"_blank\" >" .  $row["archivoxml"] . "\n</a>";
				echo "<br>PDF PyB";	

				break;
			case 2://factura duenio		
				echo "<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivopdf"] . "\"  target=\"_blank\" >" .  $row["archivopdf"] . "\n</a>";
				echo "<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivoxml"] . "\"  target=\"_blank\" >" .  $row["archivoxml"] . "\n</a>";
				echo "<br>PDF PyB";	

				break;				
			default:
				echo "<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivopdf"] . "\"  target=\"_blank\" >" .  $row["archivopdf"] . "\n</a>";
				echo "<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivoxml"] . "\"  target=\"_blank\" >" .  $row["archivoxml"] . "\n</a>";
				echo "<br>PDF PyB";
			}


		datoscfdi($row["archivoxml"],$xmlcfdi,$idcfdi);

	}
	
	}

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}


?>
