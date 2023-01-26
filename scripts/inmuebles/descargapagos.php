<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
require_once("TCPDF/tcpdf.php");

$accion=@$_GET["accion"];
$fechainicio=@$_GET["fechainicio"];
$fechafinal=@$_GET["fechafinal"];

$misesion = new sessiones;

if($misesion->verifica_sesion()=="no")
{
	echo "A&uacute;n no se ha firmado con el servidor";
	exit();
}

$sql="select * from submodulo where archivo ='descargapagos.php'";
$operacion = mysql_query($sql);
while($row = mysql_fetch_array($operacion))
{
	$dirscript= $row['ruta'] . "/" . $row['archivo'];
	$ruta=$row['ruta'];
	$priv = $misesion->privilegios_secion($row['idsubmodulo']);
	$priv=split("\*",$priv);

}


if ($priv[0]!='1')
{
	$txtver = "";
	echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
	exit();
}

$botonGenerar="cargarSeccion('$dirscript','contenido','accion=1&fechainicio=' + fechai.value + '&fechafinal='+ fechaf.value)";

$html = <<<formulario

<center>
<h1>Descarga de Comprobantes de Pagos por fechas</h1>
<form >
<table border="1">
<tr>
	<td>Fecha Inicial</td><td> <input type="text" name="fechai" >(aaaa-mm-dd) </td>
</tr>
<tr>
	<td>Fecha Final</td><td> <input type="text" name ="fechaf">(aaaa-mm-dd)</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="button" value="Limpiar" onClick="fechai.value='';fechaf.value='';">
		<input type="button" value="Generar" onClick="$botonGenerar">
	</td>
</tr>
</table>
</form>
</center>

formulario;
echo $html;

if($accion==1) {
	
	if($fechainicio>$fechafinal){
		echo '<br>
		<center><h3>Rango de Fechas Invalido</h3></center>';
		exit();	
	}

	$idhistorias='';
	$sql0="SELECT idhistoria FROM historia WHERE cantidad>0 AND aprobado=1 AND fechapago BETWEEN '$fechainicio' AND '$fechafinal' order by fechapago";
	$resultado0 = @mysql_query ($sql0);
	while($row0 = mysql_fetch_array($resultado0)){
		$idhistorias .= $row0["idhistoria"].",";
	}

	$idhistorias = substr($idhistorias,0,-1);

	$sql1="SELECT * FROM pagoaplicado p, historiapago hp WHERE p.idpagoaplicado=hp.idpagoaplicado AND hp.idhistoria in ($idhistorias) GROUP BY hp.idpagoaplicado ";
	$resultado1 = @mysql_query ($sql1);

	$cont=0;

	//$dir=substr(getcwd(),0,(stripos(getcwd(),"scripts")-1))."\Pagos";
	$dir=substr(getcwd(),0,(stripos(getcwd(),"scripts")-1))."/Pagos";
	//mkdir($dir."\Temp",0777);
	mkdir($dir."/Temp",0777);

	//if(!file_exists($dir."\Temp")){
	if(!file_exists($dir."/Temp")){
		echo '<br>
		<center><h3>Error al crear Carpeta</h3></center>';
    	exit();
    }


	while($row1 = mysql_fetch_array($resultado1)){
		$htmlPago = $row1["distribucionpago"];
		$htmlPago = base64_decode($htmlPago);

		$localidad= stripos($htmlPago,"<h1>");
		$htmlPago=substr($htmlPago,$localidad);

		if(substr($htmlPago,0,8)=="<h1>NOTA"){
			$tipoComp="nota";
		}else{
			$tipoComp="cobro";
		}

		$lineaCIni= stripos($htmlPago,'name="contrato" value="') + 23 ;
		$lineaCFin= stripos($htmlPago,'"',$lineaCIni);
		$idcontrato=substr($htmlPago,$lineaCIni,($lineaCFin - $lineaCIni));
		//$nombrepdf=$dir ."\Temp\\".$idcontrato."_". $row1["idpagoaplicado"].".pdf";
		$nombrepdf=$dir ."/Temp/".$idcontrato."_". $row1["idpagoaplicado"].".pdf";

		if($tipoComp=="cobro"){
			//$htmlPago=preg_replace('/"1"/','"0"',$htmlPago,1);
		}

		$htmlPago=str_replace("'1'",'"1"', $htmlPago);
		$htmlPago=str_replace("</div>",'', $htmlPago);
		$htmlPago=str_replace('type="button"','type="hidden"', $htmlPago);
		$htmlPago=str_replace("</th<","</th><", $htmlPago);
		$htmlPago=str_replace('colspan="2">$',">$", $htmlPago);
		$htmlPago=str_replace('rowspan="4"','rowspan="4" colspan="2"', $htmlPago);
		$htmlPago=str_replace('<img src="imagenes/marca_telefono.png">','',$htmlPago);
		
		//$htmlPago=str_replace("imagenes/marca_telefono.png", "/../admin/imagenes/marca_telefono.png", $htmlPago);
		//$htmlPago=str_replace("<input ", "<input disabled hidden ", $htmlPago);

		//echo "<textarea>". $htmlPago."</textarea>";

		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetMargins(15, 30, 15, true); // put space of 30 on top 
        $pdf->AddPage();
        $pdf->writeHTML($htmlPago, true, false, true,false,'');
        $pdf->Image('../../imagenes/marca_telefono.png',70,'', 70, 40, '', '', '', true, 300);
        $pdf->output($nombrepdf,"F");

        $cont++;
		
    }

/*
    if(file_exists($dir."\pagos.zip")){
    	unlink($dir."\pagos.zip");
    }
 */   
    if(file_exists($dir."/pagos.zip")){
    	unlink($dir."/pagos.zip");
    }   
    

    $zip = new ZipArchive();
	//$ret = $zip->open($dir.'\pagos.zip',  ZIPARCHIVE::CREATE);
	$ret = $zip->open($dir.'/pagos.zip',  ZIPARCHIVE::CREATE);
	if ($ret !== TRUE) {
    	printf('Erróneo con el código %d', $ret);
	} else {
    	$options = array('add_path' => 'p_', 'remove_all_path' => TRUE);
    	//$zip->addGlob($dir.'\Temp\*.{pdf}', GLOB_BRACE, $options);
    	$zip->addGlob($dir.'/Temp/*.{pdf}', GLOB_BRACE, $options);
    	$zip->close();
	}

	//foreach(glob($dir . "\Temp\*") as $archivos_carpeta){
	foreach(glob($dir . "/Temp/*") as $archivos_carpeta){
    	unlink($archivos_carpeta);
    }
	//rmdir($dir."\Temp");
	rmdir($dir."/Temp");

	if($cont>0){ 
		echo '<br>
		<center><h3>Pagos Realizados del '.$fechainicio.' al '.$fechafinal.'</h3></center>
		<br>
		<center><a href="../../../Pagos/pagos.zip" >Descargar Archivo </a></center>';
	}else{
		echo '<br>
		<center><h3>No existen Pagos entre el '.$fechainicio.' y '.$fechafinal.'</h3></center>';
	}
}

?>