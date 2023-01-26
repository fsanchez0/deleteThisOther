<?php
//Es para realizar la busqueda dentro del directorio y mostrarla en
//el marcobusqueda, relacioando directamente con marcobusqueda.php

include '../general/sessionclase.php';
include_once('../general/conexion.php');
header('Content-Type: text/html; charset=iso-8859-1');

$patron=@$_GET["patron"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	if(!$patron=="")
	{
		$sql="select * from clientelibre where rfccl like '%$patron%'";
	
		$datos=mysql_query($sql);	
		echo "<table border=\"1\" width=\"100%\" class=\"letrasn\">";
		while($row = mysql_fetch_array($datos))
		{
			$emailcl = "";
			if(is_null($row["emailcl"])==false)
			{
				$emailcl=$row["emailcl"];
			}
			
			if(is_null($row["emailcl1"])==false)
			{
				if($emailcl != "")
				{
					$emailcl .= "," . $row["emailcl1"];
				}
				else
				{
					$emailcl = $row["emailcl1"];
				}
			}
			
			if(is_null($row["emailcl2"])==false)
			{
				if($emailcl != "")
				{
					$emailcl .= "," . $row["emailcl2"];
				}
				else
				{
					$emailcl = $row["emailcl2"];
				}
			}
			
			echo "<tr><td><a style=\"font-size:10;cursor: pointer\" onClick=\"jabascript:document.getElementById('RNombre_62').value ='" . $row["nombrecl"] . "';document.getElementById('Rcalle_69').value ='" . $row["callecl"] . "';document.getElementById('RColon_70').value ='" . $row["coloniacl"] . "';document.getElementById('RMunic_71').value ='" . $row["delmuncl"] . "';document.getElementById('REdo_72').value ='" . $row["estadocl"] . "';document.getElementById('Rpais_73').value ='" . $row["paiscl"] . "';document.getElementById('RCP_74').value ='" . $row["cpcl"] . "';document.getElementById('RRFC_61').value ='" . $row["rfccl"] . "';document.getElementById('idreceptor').value ='" . $row["idclientelibre"] . "';document.getElementById('idcl').value ='" . $row["idclientelibre"] . "';document.getElementById('c1').value ='" . $row["emailcl"] . "';document.getElementById('c2').value ='" . $row["emailcl1"] . "';document.getElementById('c3').value ='" . $row["emailcl2"] . "';document.getElementById('EmailCont_109').value ='" .  $emailcl    . "';document.getElementById('emisorcl').innerHTML=''\">" . $row["rfccl"] . " " . $row["nombrecl"] .  "</a></td></tr> ";
	
		}
		echo "</table>";
	}



}



?>