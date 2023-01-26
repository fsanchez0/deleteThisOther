<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclass.php';

$lsti = @$_GET["lsti"];
$lsto=@$_GET["lsto"];

$enviocorreo = New correo;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='correoe.php'";
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
	//echo "listai:" . $lsti . "; listao:" .$lsto;
	$lista = " <table border ='1' width='100%'><tr><th> Inquilino </th><th>Acci&oacute;n</th></tr>";
	$plista1 = split('[|]',$lsti);
	foreach($plista1 as $id)
	{
		if($id)
		{
			$sql = "select p.idinquilino as idi, nombre, nombre2, apaterno, amaterno, calle, numeroext, numeroint from inquilino p, contrato c, inmueble i where p.idinquilino = c.idinquilino and c.idinmueble = i.idinmueble and c.idinquilino = $id";
			$datos=mysql_query($sql); 
			$row = mysql_fetch_array($datos);
			$lista .= "<tr><td>" . " (" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . ")"  . $row["nombre"] . " " . $row["apaterno"] . "</td><td><input type='button' value='-' onclick=\"quitard(1," . $row["idi"] . "); cargarSeccion('$ruta/listac.php','listac','lsti=' + document.getElementById('lsti').value + '&lsto=' + document.getElementById('lsto').value)\"></td></tr>";			
		}
	}
	
	$plista1 = split('[|]',$lsto);
	foreach($plista1 as $id)
	{	
		if($id)
		{	
			$sql = "select p.idfiador as idi, nombre, nombre2, apaterno, amaterno, calle, numeroext, numeroint from fiador p, contrato c, inmueble i where p.idfiador = c.idfiador and c.idinmueble = i.idinmueble and c.idfiador = $id";
			$datos=mysql_query($sql);
			$row = mysql_fetch_array($datos);	
			$lista .= "<tr><td>" . " (" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . ")"  . $row["nombre"] . " " . $row["apaterno"] . "</td><td><input type='button' value='-' onclick=\"quitard(2," . $row["idi"] . "); cargarSeccion('$ruta/listac.php','listac','lsti=' + document.getElementById('lsti').value + '&lsto=' + document.getElementById('lsto').value)\"></td></tr>";			
		}	
	}

	$lista .= "</table>";
	echo $lista;




}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
