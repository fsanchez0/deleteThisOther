<?php
$dbhost="localhost";
$dbname="bujalil";
$dbuser="root";
$dbpass="";
/*$_SESSION["contra"]=$_POST["idcontrato"];
$contrato=$_SESSION["contra"];*/
//session_start();
//$contrato=$_SESSION["contrato"];
$contrato=$_GET["idcontrato"];
$servicio=$_GET["servicio"];
$db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

if (isset($_POST) && count($_POST)>0)
{
	if ($db->connect_errno) 
	{
		die ("<span class='ko'>Fallo al conectar a MySQL: (" . $db->connect_errno . ") " . $db->connect_error."</span>");
	}
	else
	{
		$query=$db->query("update datoservicios set ".$_POST["campo"]."='".$_POST["valor"]."' where iddato='".intval($_POST["id"])."' limit 1");
		if ($query) echo "<span class='ok'>Valores modificados correctamente.</span>";
		else echo "<span class='ko'>".$db->error."</span>";
	}
}

if (isset($_GET) && count($_GET)>0)
{
	if ($db->connect_errno) 
	{
		die ("<span class='ko'>Fallo al conectar a MySQL: (" . $db->connect_errno . ") " . $db->connect_error."</span>");
	}
	else
	{
		$query=$db->query("select * from datoservicios where idcontrato='$contrato' and servicio='$servicio'");
		$datos=array();
		while ($usuarios=$query->fetch_array())
		{
			$datos[]=array(	"iddato"=>$usuarios["iddato"],
							"idcontrato"=>$usuarios["idcontrato"],
							"periodo"=>$usuarios["periodo"],
							"estatus"=>$usuarios["estatus"],
							"cantidad"=>$usuarios["cantidad"],
							"servicio"=>$usuarios["servicio"]
			);
		}
		
		echo json_encode($datos);
	}
}
//echo $contrato;
?>