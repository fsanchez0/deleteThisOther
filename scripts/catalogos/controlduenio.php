<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$acumulado = @$_GET["acumulado"]; //lista de id's de dueños
$dato=@$_GET["dato"]; //id del dueño a operar
$o=@$_GET['operacion'];//operacion a realizar 1:agergar 2:quitar
$fechacontrato=@$_GET['fechacontrato'];//fecha del contrato con ese inmueble


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='inmuebles.php'";
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

	
	
	
	
		
	
	$mensajeerr="";
	$prop="";
	//echo $o;
	$listad="";
	switch($o)
	{
	case "1": // Agrego
		
		
		
		$fechaok = split("[-]", $fechacontrato);
	
		if(count($fechaok)<>3)
		{//error
		
			$mensajeerr = "<font color='red' size='1'>Fecha incorrecta</font><br>";

	
		}
		else
		{
			//ok
		if(strlen($fechaok[0])==4 && strlen($fechaok[1])==2 && strlen($fechaok[2])==2 )
		{
			//echo "OK";
			if(((int)$fechaok[1]>0 &&(int)$fechaok[1]<13) && ((int)$fechaok[2]>0 && (int)$fechaok[2]<32))
			{
			
			
		//$acumulado .= $dato . "|";
		$acumulado .= $dato . "*" . $fechacontrato . "|";		
		$pl="";
		$ld = split("[|]", substr($acumulado,0,-1));
		//echo count($ld);
		$prop = 100 / count($ld);
		$i=0;
		$listad .="<table border='1'>";
	//	foreach ($ld as $idd)
	
		foreach ($ld as $idd0)
		{
			$ld0 = split("[*]", $idd0);
			$idd=$ld0[0];
			$fecha =$ld0[1];
			
			$pl .= "'&p_$idd=' + p_$idd.value +";
			
			$sqldu="select * from duenio d where idduenio = $idd";
			$operaciondu = mysql_query($sqldu);
			$row = mysql_fetch_array($operaciondu);
			$i+=1;
			$listad .="<tr><td>" . utf8_decode($row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]) . "</td><td>$fecha</td><td><input name='p_" . $row["idduenio"] . "' type='text' value='$prop' size='5'></td><td><input type='button' value='X' onClick = \"cargarSeccion('$ruta/controlduenio.php','listaduenio','acumulado=' + acumulado.value + '&dato=" . $row["idduenio"] . "&operacion=2')\"></td></tr>";
		}
		$listad .="</table>";			
			
			}
			else
			{
				//error
				//mensaje de error y no se agrega nada a la lista
				$mensajeerr = "<font color='red' size='1'>Fecha fuera de rango </font><br>";
			}
			
			
			
		}
		else
		{
		
			$mensajeerr = "<font color='red' size='1'>Formato incorrecto</font><br>";
			
	
		}
		
		
	}		
		
		
		if($mensajeerr != "")
		{
			$ld = split("[|]", substr($acumulado,0,-1));
			//echo count($ld);
			$prop = 100 / count($ld);
			$i=0;
			$listad .="<table border='1'>";
			//foreach ($ld as $idd)
			if($acumulado)
			{
				foreach ($ld as $idd0)
				{
					$ld0 = split("[*]", $idd0);
					$idd=$ld0[0];
					$fecha =$ld0[1];
			
					$pl .= "'&p_$idd=' + p_$idd.value +";
			
					$sqldu="select * from duenio d where idduenio = $idd";
					$operaciondu = mysql_query($sqldu);
					$row = mysql_fetch_array($operaciondu);
					$i+=1;
					$listad .="<tr><td>$i" . utf8_decode($row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]) . "</td><td>$fecha</td><td><input name='p_" . $row["idduenio"] . "' type='text' value='$prop' size='5'></td><td><input type='button' value='X' onClick = \"cargarSeccion('$ruta/controlduenio.php','listaduenio','acumulado=' + acumulado.value + '&dato=" . $row["idduenio"] . "&operacion=2')\"></td></tr>";
				}
			}
			$listad .="</table>";		
		
		
		}
		
		
		
		$listad = "$mensajeerr $listad" ;
		
		
		
		
		
		
		
		
		



		break;

	case "2": //Borro
		
		$ld = split("[|]",substr($acumulado,0,-1));
		$ldn="";
		$pl="";
		if((count($ld)-1)>0)
		{
			$prop = 100 / (count($ld)-1);
		
		$listad .="<table border='1'>";
		foreach ($ld as $idd0)
		{
		
			$ld0 = split("[*]", $idd0);
			$idd=$ld0[0];
			$fecha =$ld0[1];			
		
		
			if($dato!=$idd)
			{
				$ldn .= $idd . "*" . $fecha . "|";
				$pl .= "idd*$prop|";
				$sqldu="select * from duenio d where idduenio = $idd";
				$operaciondu = mysql_query($sqldu);
				$row = mysql_fetch_array($operaciondu);
				$listad .="<tr><td>" . utf8_decode($row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]) . "</td><td>$fecha</td><td><input type='text' value='$prop' size='5'></td><td><input type='button' value='X' onClick = \"cargarSeccion('$ruta/controlduenio.php','listaduenio','acumulado=' + acumulado.value + '&dato=" . $row["idduenio"] . "&operacion=2')\"></td></tr>";
			}
		}
		$listad .="</table>";
		$acumulado = $ldn;
		
		
		}
		break;



	}	

	//$pl = substr($pl,0,-1);
	echo $listad .="<input type='hidden' name='acumulado' id='acumulado' value='$acumulado'/>";
	//echo "<input type='hidden' name='acumulado' id='acumulado' value='$acumulado'/>";
	//echo $listad .="<input type='hidden' name='pd' id='pd' value='$pl' />";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>