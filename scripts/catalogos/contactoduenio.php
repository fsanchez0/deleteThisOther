<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$acumulado = @$_GET["acumulado"]; //lista de los contactos del duenio
$dato=@$_GET["dato"]; //id del contacto a operar
$o=@$_GET['operacion'];//operacion a realizar 1:agergar 2:quitar
$contacto = @$_GET['contacto']; //contacto en si, (osea el telefono o el correo)
$notac = @$_GET['notac']; // notas para el contacto
$usar = @$_GET['ocupar']; //bool para ver si se utilizara  el contacto
$tipoc = @$_GET['tipoc'];//si es telefono:1 o mail:2


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='duenios.php'";
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



	$prop="";
	//echo $o;
	$listad="";
	switch($o)
	{
	case "1": // Agrego
		
		//$acumulado .= $dato . "|";		
		$acumulado .= $tipoc . "*" . $contacto . "*" . $notac . "*" . $usar . "|";	
		$ld = split("[|]", substr($acumulado,0,-1));
		$i=0;
		$listad .="<table border='1'>";
		foreach ($ld as $idd)
		{
			$dats = split("[*]", $idd);
			if($tipoc == 1)
			{
				
				$listad .="<tr><td>" . $dats[1] . "</td><td>" . $dats[2] . "</td><td><input type='button' value='X' onClick = \"cargarSeccion('$ruta/contactoduenio.php','teldiv','acumulado=' + tell.value + '&dato=" . $dats[1] . "&operacion=2&tipoc=1')\"></td></tr>";
			
			}
			else
			{

				$listad .="<tr><td>" . $dats[1] . "</td><td>" . $dats[2] . "</td><td>" . $dats[3] . "</td><td><input type='button' value='X' onClick = \"cargarSeccion('$ruta/contactoduenio.php','maildiv','acumulado=' + maill.value + '&dato=" . $dats[1] . "&operacion=2&tipoc=2')\"></td></tr>";
			
			}
			
		}
		$listad .="</table>";


		break;

	case "2": //Borro
		//echo $acumulado . "</br>";
		$ld = split("[|]",substr($acumulado,0,-1));
		$ldn="";
		if((count($ld)-1)>0)
		{
		
			$listad .="<table border='1'>";
			
			foreach ($ld as $idd)
			{
				$dats = split("[*]", $idd);
				//echo $dato . " = " . $dats[1] . "<br>";
				if($dato!=$dats[1])
				{
					$ldn .= $dats[0] . "*" . $dats[1] . "*" . $dats[2] . "*" . $dats[3] . "|";	
					
					if($tipoc == 1)
					{
				
						$listad .="<tr><td>" . $dats[1] . "</td><td>" . $dats[2] . "</td><td><input type='button' value='X' onClick = \"cargarSeccion('$ruta/contactoduenio.php','teldiv','acumulado=' + tell.value + '&dato=" . $dats[1] . "&operacion=2&tipoc=1')\"></td></tr>";
			
					}
					else
					{
						$listad .="<tr><td>" . $dats[1] . "</td><td>" . $dats[2] . "</td><td>" . $dats[3] . "</td><td><input type='button' value='X' onClick = \"cargarSeccion('$ruta/contactoduenio.php','maildiv','acumulado=' + maill.value + '&dato=" . $dats[1] . "&operacion=2&tipoc=2')\"></td></tr>";
					}		
				}
			}
			$acumulado = $ldn;
			$listad .="</table>";
			
			
		
		
		}
		break;



	}	


	if($tipoc == 1)
	{
				
		 $listad .="<input type='hidden' name='tell' id='tell' value='$acumulado'/>";
		
	}
	else
	{
		 $listad .="<input type='hidden' name='maill' id='maill' value='$acumulado'/>";
	}

	echo $listad;// .="<input type='hidden' name='acumulado' id='acumulado' value='$acumulado'/>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>