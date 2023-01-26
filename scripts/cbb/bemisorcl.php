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
		$sql="select * from clientecbb where rfccbb like '%$patron%'";
	
		$datos=mysql_query($sql);	
		echo "<table border=\"1\" width=\"100%\" class=\"letrasn\">";
		while($row = mysql_fetch_array($datos))
		{
			$emailcl = "";
			if(is_null($row["correo1"])==false)
			{
				$emailcl=$row["correo1"];
			}
			
			if(is_null($row["correo2"])==false)
			{
				if($emailcl != "")
				{
					$emailcl .= "," . $row["correo2"];
				}
				else
				{
					$emailcl = $row["correo2"];
				}
			}
			
			if(is_null($row["correo3"])==false)
			{
				if($emailcl != "")
				{
					$emailcl .= "," . $row["correo3"];
				}
				else
				{
					$emailcl = $row["correo3"];
				}
			}
			
			echo "<tr><td><a style=\"font-size:10;cursor: pointer\" onClick=\"jabascript:document.getElementById('RNombre').value ='" . $row["nombrecbb"] . "';document.getElementById('RCalle').value ='" . $row["callecbb"] . "';document.getElementById('RNoext').value ='" . $row["noextcbb"] . "';document.getElementById('RNoint').value ='" . $row["nointcbb"] . "';document.getElementById('RColon').value ='" . $row["colcbb"] . "';document.getElementById('RLoc').value ='" . $row["loccbb"] . "';document.getElementById('RRef').value ='" . $row["refcbb"] . "';document.getElementById('RMunic').value ='" . $row["delmuncbb"] . "';document.getElementById('REdo').value ='" . $row["estadocbb"] . "';document.getElementById('Rpais').value ='" . $row["paiscbb"] . "';document.getElementById('RCP').value ='" . $row["cpcbb"] . "';document.getElementById('RRFC').value ='" . $row["rfccbb"] . "';document.getElementById('idreceptor').value ='" . $row["idclientecbb"] . "';document.getElementById('c1').value ='" . $row["correo1"] . "';document.getElementById('c2').value ='" . $row["correo2"] . "';document.getElementById('c3').value ='" . $row["correo3"] . "';document.getElementById('EmailCont').value ='" .  $emailcl    . "';document.getElementById('emisorcl').innerHTML=''\">" . $row["rfccbb"] . " " . $row["nombrecbb"] .  "</a></td></tr> ";
	
		}
		echo "</table>";
	}



}



?>