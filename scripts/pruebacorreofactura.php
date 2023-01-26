<?php
date_default_timezone_set('America/Mexico_City');
include 'general/funcionesformato.php';
include 'general/sessionclase.php';
//include 'general/ftpclass.php';
include "insertcfdi/lxml3.php";
include_once('general/conexion.php');
//include ("cfdi/cfdiclassn.php");
include ("cfdi/cfdiclass33pyb.php");
include ("general/correoclassd.php");
require('fpdf.php');

header('Content-Type: text/html; charset=iso-8859-1');

$mail = New correo2;
$cfd =  New cfdi33class;
//$ftp= New ftpcfdi;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

echo "ejercicioss <br>";


    //$resultado ="c|error1";
	//$resultado ="f|error2";
	$resultado ="/home/rentaspb/contenedor/cfdi/HonProfPyB38.pdf|/home/rentaspb/contenedor/cfdi/HonProfPyB38.xml";
	$idfl = 65;
	//$directorio = "/home/rentaspb/contenedor/cfdi";

	
	//verificar el resultado apra saber si fue exitoso con los nombres de los archivos. $idfl es el actual, tomar del idclientelibre el 
	// o los correos electronicos a enviar el mensaje con las facturas.
	
	echo "Resultado: $resultado<br>";
	
    if(substr($resultado,0,1) <> 'c' &&  substr($resultado,0,1) <> 'f')
	{	
        echo "enviar correo";
		echo $sqlmail = "select * From cfdilibre f, clientelibre c where f.idclientelibre = c.idclientelibre and f.idcfdilibre = $idfl";
		//obtener información del cliente

		$operacionmail = mysql_query($sqlmail);
		$rowmail = mysql_fetch_array($operacionmail);

		$para ="";
		if($rowmail["emailcl"]!="" )
		{
			$para .=$rowmail["emailcl"] . "1,";
		}
		if($rowmail["emailcl1"]!="" )
		{
			$para .=$rowmail["emailcl1"] . "2,";
		}	
		if($rowmail["emailcl2"]!="" )
		{
			$para .=$rowmail["emailcl2"] . "3,";
		}
	    $para = substr($para,0,-1);
        echo "<br>$para  <br>se enviara como prueba a:<br>";
        echo $para = "lsolis@sismac.com,mizocotroco@hotmail.com";        

		echo $asunto = "Factura";
		echo $mensaje = "Apreciad@ " . $rowmail["nombrecl"] . ", se adjunta en este correo su factura.";
        //echo $malresultado = $mail->enviaf($para, $asunto, $mensaje,$resultado);

	}
	else
	{
		echo "error en el comprobante: $resultado";
	}
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>
