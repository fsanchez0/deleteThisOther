<?php
include '../general/sessionclase.php';
//include_once( '../fpdf.php');
include 'reporteclassd.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclassd.php';

//define('BASE_DIR','/home/wwwarchivos/cfdi');
define('BASE_DIR','/home/rentaspb/contenedor/cfdi');
//define('BASE_DIR','/Library/WebServer/Documents/bujalil/scripts/duenios');

$idduenio = @$_GET["id"];
$fechagen = @$_GET["f"];
$e = @$_GET["e"];
$idedoduenioss=@$_GET["idedoduenio"];


//$enlace = mysql_connect('localhost', 'root', '');
//mysql_select_db('bujalil',$enlace) ;
$enviocorreo = New correo2;
$reporte = new duenioreporte;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='edosduenio.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

		$sql="select * from submodulo where archivo ='cargodueniod.php'";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$dirscript= $row['ruta'] . "/" . $row['archivo'];
			$ruta=$row['ruta'];
			$priv = $misesion->privilegios_secion($row['idsubmodulo']);
			$priv=split("\*",$priv);
	
		}



	$reporte->idduenio = $idduenio;
	$reporte->fechagen = $fechagen;
	$reporte->paraenviar=$e;
	$reporte->generaPDF();
	
	echo "enviando: $reporte->paraenviar";
	
	if($reporte->paraenviar==1)
	{
	
		//obtener los correos del dueño en cuestion
		$duenion="";
		$para = "";
		$sql = "select * from duenio d, contacto c where d.idduenio = c.idduenio and idtipocontacto = 2 and usar = 1 and d.idduenio =$idduenio";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			
			if ($para=="")
			{
				$para = $row['contacto'];
			}
			else
			{
				$para .= "," . $row['contacto'];
			
			}			
	
		}		
		
		$sqldd = "select *, nombre as nomd, nombre2 as nomd2, apaterno as apd, amaterno as amd from duenio  where idduenio =$idduenio";
		$operaciondd = mysql_query($sqldd);
		$rowdd = mysql_fetch_array($operaciondd);
		$duenion=$rowdd['nomd'] . " " . $rowdd['nomd2'] . " " . $rowdd['apd'] . " " . $rowdd['amd'];
		
		//obtener los nombres de los archivos de las facturas y concatenarlas con |,  las reportadas pdf y xml
		$facturas="";
		$sql= "select distinct(archivopdf) as pdf, archivoxml as xml from cfdipartidas c, edoduenio ed, facturacfdid fd, facturacfdi f, cfdiedoduenio cd where fd.idfacturacfdi = f.idfacturacfdi and fd.idcfdiedoduenio = cd.idcfdiedoduenio and cd.idcfdiedoduenio = c.idcfdiedoduenio and c.idedoduenio = ed.idedoduenio and ed.idduenio =" . $idduenio . " and fechagen = '" .  $fechagen . "' and reportado= 1 and pdfok=1";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			if ($facturas=="")
			{
				//$facturas = BASE_DIR . "/" . $row['pdf'] . "|" . BASE_DIR . "/" . $row['xml'] ;
				$facturas = $row['pdf'] . "|" . $row['xml'] ;
			}
			else
			{
				//$facturas .= "|" . BASE_DIR . "/" . $row['pdf'] . "|" . BASE_DIR . "/" . $row['xml'];
				$facturas .= "|" . $row['pdf'] . "|"  . $row['xml'];
			
			}			
	
		}
		//$facturas="";
		
		$edocuenta = BASE_DIR . '/reporte_' . $reporte->idduenio . '.pdf'; //para que no envie el mismo reporte
		//$edocuenta = "";
		
	
		$periodo = "";
		//Calculo del periodo para colocarlo en  el titulo
		//echo substr($fechamenos,5,2);
		$anio = (int)substr($fechagen,0,4);
		switch (substr($fechagen,5,2))
		{
			case 1:

				$periodo = "DICIEMBRE " . ($anio - 1) . " - ENERO $anio";
				break;
			case 2:

				$periodo = "ENERO - FEBRERO $anio";			
				break;
			case 3:

				$periodo = "FEBRERO - MARZO $anio";				
				break;
			case 4:

				$periodo = "MARZO - ABRIL $anio";				
				break;	
			case 5:

				$periodo = "ABRIL - MAYO $anio";				
				break;
			case 6:

				$periodo = "MAYO - JUNIO $anio";				
				break;
			case 7:

				$periodo = "JUNIO - JULIO $anio";				
				break;
			case 8:

				$periodo = "JULIO - AGOSTO $anio";				
				break;
			case 9:

				$periodo = "AGOSTO - SEPTIEMBRE $anio";				
				break;
			case 10:

				$periodo = "SEPTIEMBRE - OCTUBRE $anio";				
				break;
			case 11:

				$periodo = "OCTUBRE - NOVIEMBRE $anio";				
				break;
			case 12:

				$periodo = "NOVIEMBRE - DICIEMBRE $anio";				
				break;	
		}
			
			
		$hoy = date('Y-m-d');	
		$mes="";
		switch (substr($hoy,5,2))
		{
			case 1:
				
				$mes="ENERO";
				break;
			case 2:
				
				$mes= "FEBRERO";			
				break;
			case 3:
				
				$mes = "FMARZO";				
				break;
			case 4:
				
				$mes = "ABRIL";				
				break;	
			case 5:
				
				$mes = "MAYO";				
				break;
			case 6:
				
				$mes = "JUNIO";				
				break;
			case 7:
				
				$mes= "JULIO";				
				break;
			case 8:
				
				$mes = "AGOSTO";				
				break;
			case 9:
				
				$mes= "SEPTIEMBRE";				
				break;
			case 10:
				
				$mes = "OCTUBRE";				
				break;
			case 11:
				
				$mes = "NOVIEMBRE";				
				break;
			case 12:
				
				$mes = "DICIEMBRE";				
				break;	
		}			
			
	
		
		$sqlmail = "select count(*) as n, min(fechaenvio) as f from enviomail where idduenio = $idduenio and fechareporte = '$fechagen'";
		$operacion = mysql_query($sqlmail);
		$rowmail = mysql_fetch_array($operacion);
		$cuenta = "";
		if($rowmail['n']>0)
		{
			$cuenta = "Envio no. " . ($rowmail['n'] +1) . " (envio original " . $rowmail['f'] . ").";
		}
		else
		{
			$cuenta = "Envío original.";
		}
		
		$dueniona= utf8_encode ($duenion );
		$duenion = CambiaAcentosaHTML($duenion );
			
		//$mensaje="$cuenta<p style='text-align:right;'>México D.F. a " . substr($hoy,8,2) . " de $mes de " . substr($hoy,0,4) . "</p><p style='font-weight:bold;'>  $duenion <br> Presente:</p>";
		//$mensaje .= "<p>Por este conducto y conforme a lo pactado en el contrato de administración que tenemos celebrado, le hacemos llegar el estado de cuenta correspondiente al periodo $periodo, así como las facturas correspondientes. </p><p> Cualquier aclaración o comentario, por favor, hágannoslo llegar a la dirección de correo <a href='mailto:ayuda@padilla-bujalil.com.mx?Subject=Aclaracion del estado de cuenta $periodo de $duenion' target='_top'>ayuda@padilla-bujalil.com.mx</a> </p><p>Atentamente:<br>PADILLA & BUJALIL S.C.</p><br><img src='cid:my-attach'>"; 
		$mensaje ="$cuenta<p style='text-align:right;'>M&eacute;xico D.F. a " . substr($hoy,8,2) . " de $mes de " . substr($hoy,0,4) . "</p><p style='font-weight:bold;'>  $duenion <br> Presente:</p>";
		$mensaje .= "<p>Por este conducto y conforme a lo pactado en el contrato de administraci&oacute;n que tenemos celebrado, le hacemos llegar el estado de cuenta correspondiente al periodo $periodo, as&iacute; como las facturas correspondientes. </p><p> Cualquier aclaraci&oacute;n o comentario, por favor, h&aacute;gannoslo llegar a la direcci&oacute;n de correo <a href='mailto:ayuda@padilla-bujalil.com.mx?Subject=Aclaracion del estado de cuenta $periodo de $duenion' target='_top'>ayuda@padilla-bujalil.com.mx</a> </p><p>Atentamente:<br>PADILLA & BUJALIL S.C.</p><br><img src='cid:my-attach'>"; 
		//$correoe="lsolis@sismac.com";
		
//enviar ahora a todos los correos que tengan los dueños
		$sqlem="select * from contacto where idduenio = $idduenio and idtipocontacto = 2";
		$operacionem = mysql_query($sqlem);
		//$rowem = mysql_fetch_array($operacionem);
	
	
	
		$correoe = "";
		if (mysql_num_rows($operacionem)>0)
		{
			while($rowem = mysql_fetch_array($operacionem))
			{
				$correoe .= $rowem["contacto"] . ",";
			}
			$correoe .=  "ayuda@padilla-bujalil.com.mx,estadosdecuentapropietarios@padillabujalil.com,";		
		}
		else
		{
			$correoe="ayuda@padilla-bujalil.com.mx,estadosdecuentapropietarios@padillabujalil.com,";
		}				
		
		$correoe = substr($correoe,0,-1);
		
		//$correoe="ayuda@padilla-bujalil.com.mx";
		
		//$esok=$enviocorreo->enviar("ayuda@padilla-bujalil.com.mx", "Envio de estado de cuenta", $mensaje, $edocuenta, $facturas);
		//echo "enviando: Envio de estado de cuenta $periodo - $dueniona";
		if($fechagen>'2019-10-11')
		{
			$idedoduenioss=$idduenio . "_" . $fechagen;
		}
		$esok=$enviocorreo->enviar($correoe, "Envio de estado de cuenta $periodo - $dueniona", $mensaje, $edocuenta, $facturas,$idedoduenioss);
		
		if($esok)
		{
			//echo "se envio el correo";
			$hoy = date('Y-m-d');
			$hora = date('H:i:s');
			$sql = "insert into enviomail (idduenio,fechaenvio,horaenvio,maile,archivos,mensaje,fechareporte) value ($idduenio, '$hoy','$hora','$correoe','$edocuenta|$facturas','','$fechagen')";
			$operacion = mysql_query($sql);
			
			//renombramos el archivo
			if(rename($edocuenta,BASE_DIR . '/reporte_' . $reporte->idduenio ."-".date ("YdmHis", filemtime($edocuenta)).".pdf")) {
		        
		    }
		}
		
		//$esok=$enviocorreo->enviar($para, "Envio de estado de cuenta", $mensaje, 'reporte_0.pdf', $facturas,$idedoduenioss);
	}

	
	
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>
