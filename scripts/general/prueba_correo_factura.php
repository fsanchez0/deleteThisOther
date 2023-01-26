<?php
echo "hola";
/*
date_default_timezone_set('America/Mexico_City');
include 'general/funcionesformato.php';
include 'general/sessionclase.php';

include "insertcfdi/lxml3.php";
include_once('general/conexion.php');

include ("cfdi/cfdiclass33pyb.php");

require('fpdf.php');



header('Content-Type: text/html; charset=iso-8859-1');

*/



//$mail = New correo2;
//$cfd =  New cfdi33class;
//$ftp= New ftpcfdi;
//$misesion = new sessiones;
//if($misesion->verifica_sesion()=="yes")
//{

	
	$resultado ="c|error1";
	//$resultado ="f|error2";
	//$resultado ="/home/rentaspb/contenedor/cfdi/HonProfPyB38.pdf|/home/rentaspb/contenedor/cfdi/HonProfPyB38.xml";
	$difl = 65;
	

	
	//verificar el resultado apra saber si fue exitoso con los nombres de los archivos. $idfl es el actual, tomar del idclientelibre el 
	// o los correos electronicos a enviar el mensaje con las facturas.
	
	echo "Resultado: $resultado";
/*
	if(substr($resultado,0,1) <> 'c' &&  substr($resultado,0,1) <> 'f')
	{
		echo "enviar correo";
		echo $sqlmail = "select * From cfdilibre f, clientelibre c where f.idclientelibre = c.idclientelibre and f.idcfdilibre = $idfl";
		//obtener información del cliente

		$operacionmail = mysql_query($sqlmail);
		$rowmail = mysql_fetch_array($operacionmail);

		$para ="";
		if(is_null($rowmail["emailcl"])==false )
		{
			$para .=$rowmail["emailcl"] . ",";
		}
		if(is_null($rowmail["emailcl1"])==false )
		{
			$para .=$rowmail["emailcl1"] . ",";
		}	
		if(is_null($rowmail["emailcl2"])==false )
		{
			$para .=$rowmail["emailcl2"] . ",";
		}
		echo $para = substr($para,0,-1);
        echo "<br> se enviara como prueba a:<br>";
        echo $para = "lsolis@sismac.com,mizocotroco@hotmail.com";        

		$asunto = "Factura";
		$mensaje = "Apreciad@ " . $rowmail["nombrecl"] . ", se adjunta en este correo su factura.";

		echo $malresultado = $mail->enviaf($para, $asunto, $mensaje,$resultado);




	}
	else
	{
		echo "error en el comprobante: $resultado";
	}
*/
	//exit();
	// si el resultado tiene la primera letra diferente a "c" o "f", significa que ya trae los nombres de los archivos y se puede envíar la factura
    //$mail->enviaf($parac, $asuntoc, $mensajec,$facturas,$nombre)
    //$para = consulta para extraer los correos del $idfl o recucperarlos de las variables iniciales de correos
    //$asuntoc = Asunto del correo, por definir ej. Envio de factura.
    //$mensaje = Por definir, ej. Se envía correo de la factura como documento ajnuto, conserve el documento.
    //$facturas = debe de ser $resultado ya que el valor que debe de tener es pdf_archivo|xml_archivo con toda la ruta fisica 
    //$nombre = nombre del receptor de la factura. que se puede omitir, ya que el mensaje se puede editar con todo y el nombre.


/*	
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
*/



?>
