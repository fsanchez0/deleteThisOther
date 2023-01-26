<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion =@$_GET["accion"];
$banco = @$_GET["banco"]; //lista de los contactos del duenio
$idd=@$_GET["id"]; //id del duenio a operar
$idc=@$_GET['idc'];//id de la cuenta
$titular = @$_GET['titular']; //contacto en si, (osea el telefono o el correo)
$notacta = @$_GET['notas']; // notas para el contacto
$rfcc = @$_GET['rfcc']; //bool para ver si se utilizara  el contacto
$cuenta = @$_GET['cuenta'];//si es telefono:1 o mail:2
$clabe = @$_GET['clabe'];//si es telefono:1 o mail:2
$porcentaje = @$_GET['porcentaje'];//si es telefono:1 o mail:2
$idbanco=@$_GET["idbanco"];

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
	//echo $accion;
	$listad="";
	switch($accion)
	{
	case "1": // Agrego
		
		$sql="insert into dueniodistribucion (idduenio,banco,nombre,rfc,cuenta,clabe,porcentaje,notas,idbanco) values ($idd,'$banco','$titular','$rfcc','$cuenta','$clabe',$porcentaje,'$notacta','$idbanco')";
	


		break;

	case "2": //Borro
		
		$sql="delete from dueniodistribucion where iddueniodistribucion=$idc";
		break;

	case "3": //Actualizo
		 $sql="update dueniodistribucion  set banco='$banco', nombre='$titular', rfc='$rfcc', cuenta='$cuenta', clabe='$clabe', porcentaje= $porcentaje ,notas='$notacta', idbanco='$idbanco' where iddueniodistribucion = $idc";
	
	}	
	//echo $sql;
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
	
	
	
	$sql="select * from dueniodistribucion where idduenio = $idd ";
	$datos=mysql_query($sql);
	//echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Banco</th><th>Cuenta</th><th>Clabe</th><th>IdBanco</th><th>Accion</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<tr><td>" . $row["iddueniodistribucion"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["banco"] . "</td><td>" . $row["cuenta"] . "</td><td>" . $row["clabe"] . "</td><td>" . $row["idbanco"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/ctasduenio.php','listacuentas','accion=2&id=$idd&idc=" .  $row["iddueniodistribucion"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/lctaduenio.php','ctaform','accion=2&id=$idd&idc=" .  $row["iddueniodistribucion"]  . "' )\" $txteditar>";
		$html .= "</td></tr>";
		echo CambiaAcentosaHTML($html);
	}
	echo "</table>";	
	
	
	
	

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>