<?php
include 'general/funcionesformato.php';
include 'general/sessionclase.php';
//include_once('general/conexion.php');


require('fpdf.php');

/*
$campo1=@$_GET["campo1"];
$campo2=@$_GET["campo2"];
$campo3=@$_GET["campo3"];
$campo4=@$_GET["campo4"];
$campo5=@$_GET["campo5"];
$campo6=@$_GET["campo6"];
*/


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
/*
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='fiador.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}




	if ($priv[0]!='1')
	{
		//Es privilegio para poder ver eset modulo, y es negado
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}

	//para el privilegio de editar, si es negado deshabilida el botón
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el botón
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}

*/


//conte|posX|PosY|ancho|alto|enmarco

	$pdf=new FPDF('P','mm','letter');
	$pdf->AddPage();
	//$pdf->SetFont('Arial','B',12);
	$pdf->SetFont('Arial','',12);
	$pdf->SetAutoPageBreak(false , 1);


	//$aux= split("*", "campo2*15*100*130*17");
	//$a= $campo1 . " " . $campo2 . " " . $campo3 . " " . $campo4 . " " . $campo5 . " " . $campo6;
	//$pdf->Cell(10,10,$a,1);
	
	foreach( $_GET as $key => $value ) {
	   //echo "Key: $key; Valor: $value&lt;br&gt;\n";
	 	if($value)
		{
	
			$aux=split("[|]", $value);
			$pdf->SetXY(($aux[1]*10),($aux[2]*10));
			if($aux[5]=="0")
			{
				$pdf->SetLeftMargin($aux[3]*10);//derecho
				$pdf->SetLeftMargin($aux[4]*10);//izquierdo

			}


			//$pdf->MultiCell(($aux[3]*10),($aux[4]*10),$aux[0],1);
			$aux[0]=str_ireplace("\\", " ", $aux[0]);
			$aux[0]=str_ireplace("!", "\n", $aux[0]);
			$aux[0]=str_ireplace("Ã¡", "á", $aux[0]);
			$aux[0]=str_ireplace("Ã©", "é", $aux[0]);
			$aux[0]=str_ireplace("Ã­", "í", $aux[0]);
			$aux[0]=str_ireplace("Ã³", "ó", $aux[0]);
			$aux[0]=str_ireplace("Ãº", "ú", $aux[0]);

			$aux[0]=str_ireplace("Ã", "Á", $aux[0]);
			$aux[0]=str_ireplace("Ã‰", "É", $aux[0]);
			$aux[0]=str_ireplace("Ã", "Í", $aux[0]);
			$aux[0]=str_ireplace("Ã“", "Ó", $aux[0]);
			$aux[0]=str_ireplace("Ãš", "Ú", $aux[0]);
	
			$aux[0]=str_ireplace("Ã±", "ñ", $aux[0]);
			$aux[0]=str_ireplace("Ã‘", "Ñ", $aux[0]);



			//eval("\$aux[0]=" . $aux[0] . ";");

			//echo $aux[5];
			if($aux[5]=="0")
			{
				eval($aux[0] . ";");
	
			}
			else
			{
				eval($aux[0] . ";");
				$pdf->MultiCell(($aux[3]*10),5,$aux[0],1);
	
			}





		}


	}

	//$pdf->SetFont('Arial','B',12);
	//$pdf->Cell(40,10,'¡Hola, Mundo!',1);
	//$navegador = get_browser(null, true);
	$navegador= $_SERVER['HTTP_USER_AGENT'];
	//echo $navegador;
	//echo "ENCONTRE " . stristr($navegador, 'MSIE');

	if(stristr($navegador, 'msie') === FALSE) 
	{
   	 	$pdf->Output();
	}
	else
	
	{
   	 	
		$file=basename(tempnam(getcwd(),'tmp'));
		//Guardar el PDF en el fichero	
		$pdf->Output($file);
		//Redirecci—n por JavaScript
		echo "<HTML><SCRIPT>document.location='getpdf.php?f=$file';</SCRIPT></HTML>";  	
  	}

	//$pdf->Output();
	//Determinar un nombre temporal de fichero en el directorio actual
	//$file=basename(tempnam(getcwd(),'tmp'));
	//echo getcwd() . " <br>";
	//echo $file .=".pdf";
	//Guardar el PDF en el fichero	
	//$pdf->Output($file);
	//$pdf->close();
	//Redirecci—n por JavaScript
	//echo "<HTML><SCRIPT>document.location='getpdf.php?f=$file';</SCRIPT></HTML>";
	//exit;



}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>