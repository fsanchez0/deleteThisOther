<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion =@$_GET["accion"];
$nombre = @$_GET["nombreap"]; //lista de los contactos del duenio
$idd=@$_GET["id"]; //id del duenio a operar
$idc=@$_GET['idc'];//id de la apoderado
$nombre2 = @$_GET['nombre2ap']; //contacto en si, (osea el telefono o el correo)
$apaterno = @$_GET['apaternoap']; // notas para el contacto
$amaterno = @$_GET['amaternoap']; //bool para ver si se utilizara  el contacto
$tel = @$_GET['telap'];//si es telefono:1 o mail:2
$mail = @$_GET['mailap'];//si es telefono:1 o mail:2
$facultades = @$_GET['facultades'];//si es telefono:1 o mail:2
//echo $idd . " " . $idc;
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

//echo $accion;

if($accion == "4")
{

	$sql = "select * from apoderado where idapoderado = $idc";
	$operacion = mysql_query($sql);
	$row =  mysql_fetch_array($operacion);
	
	$idd=$row["idduenio"]; //id del duenio a operar
	//$idc=@$_GET['idc'];//id de la apoderado	
	$nombre = $row["nombreap"];
	$nombre2 = $row['nombre2ap']; //contacto en si, (osea el telefono o el correo)
	$apaterno = $row['apaternoap']; // notas para el contacto
	$amaterno =$row['amaternoap']; //bool para ver si se utilizara  el contacto
	$tel = $row['telap'];//si es telefono:1 o mail:2
	$mail = $row['mailap'];//si es telefono:1 o mail:2
	$facultades = $row['facultades'];//si es telefono:1 o mail:2	
	
	$formulario = "	<table border = \"0\"><tr>	<td>Nombre:</td><td><input type='text' name='nombreap' value='$nombre'></td></tr>";
	$formulario .= "<tr><td>Segundo nombre:</td><td><input type='text' name='nombre2ap' value='$nombre2'></td></tr>";
	$formulario .= "<tr><td>A. paterno:</td><td><input type='text' name='apaternoap' value='$apaterno'></td></tr>";
	$formulario .= "<tr><td>Ap. materno:</td><td><input type='text' name='amaternoap' value='$amaterno'></td></tr>";
	$formulario .= "<tr><td>Telefono:</td><td><input type='text' name='telap' value='$tel'></td></tr>";
	$formulario .= "<tr><td>Correo:</td><td><input type='text' name='mailap' value='$mail'></td></tr>";
	$formulario .= "<tr><td>Facultades:</td><td><textarea name='facultades' cols='30' rows='4'>$facultades</textarea></td></tr>";
	echo $formulario .= "<tr><td colspan='2' align='center'><input type='button' value='Actualizar' name='ctabtn' $btncta onClick=\"if(nombreap.value!='' ){if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$ruta/apoderados.php','listaap','accion=3&id=' + ids.value + '&idc=$idc&nombreap=' + nombreap.value + '&nombre2ap=' + nombre2ap.value + '&apaternoap=' + apaternoap.value + '&amaternoap=' + amaternoap.value +  '&telap=' + telap.value + '&mailap=' + mailap.value   + '&facultades=' + facultades.value  ); nombreap.value='';nombre2ap.value='';apaternoap.value='';amaternoap.value='';telap.value='';mailap.value='';facultades.value='';};if(this.value=='Agregar'&&privagregar.value==1){ alert('agrego');cargarSeccion('$ruta/apoderados.php','listaap','accion=1&id=' + ids.value + '&nombreap=' + nombreap.value + '&nombre2ap=' + nombre2ap.value + '&apaternoap=' + apaternoap.value + '&amaternoap=' + amaternoap.value +  '&telap=' + telap.value + '&mailap=' + mailap.value   + '&facultades=' + facultades.value ); nombreap.value='';nombre2ap.value='';apaternoap.value='';amaternoap.value='';telap.value='';mailap.value='';facultades.value='';}}; this.value ='Agregar';accionc.value='1';\">		</td></tr></table>";



//formulario;	



}
else
{

	$prop="";
	//echo $o;
	$listad="";
	switch($accion)
	{
	case "1": // Agrego
		
		$sql="insert into apoderado (idduenio,nombreap,nombre2ap,apaternoap,amaternoap,telap,mailap,facultades) values ($idd,'$nombre','$nombre2','$apaterno','$amaterno','$tel','$mail','$facultades')";
	


		break;

	case "2": //Borro
		
		$sql="delete from apoderado where idapoderado=$idc";
		break;

	case "3": //Actualizo
		$sql="update apoderado  set nombreap='$nombre', nombre2ap='$nombre2', apaternoap='$apaterno', amaternoap='$amaterno', telap='$tel', mailap= '$mail ',facultades='$facultades' where idapoderado = $idc";
		break;
		
	}
		
	//echo $sql;
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
		
	
	$sql="select * from apoderado where idduenio = $idd ";
	$datos=mysql_query($sql);
	//echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Tel.</th><th>mail</th><th>Facultades</th><th>Accion</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<tr><td>" . $row["idapoderado"] . "</td><td>" . $row["nombreap"] . " " . $row["nombre2ap"] . " " . $row["apaternoap"] . " " . $row["amaternoap"]. "</td><td>" . $row["telap"] . "</td><td>" . $row["mailap"] . "</td><td>" . $row["facultades"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/apoderados.php','listaap','accion=2&id=$idd&idc=" .  $row["idapoderado"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/apoderados.php','apoderadoform','accion=4&id=$idd&idc=" .  $row["idapoderado"]  . "' )\" $txteditar>";
		$html .= "</td></tr>";
		echo CambiaAcentosaHTML($html);
	}
	echo "</table>";	
	
	
}
	

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>