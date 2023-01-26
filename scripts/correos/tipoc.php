<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclass.php';

$accion = @$_GET["accion"];
$chki=@$_GET["chki"];
$chko=@$_GET["chko"];

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


	$binquilinos = "<input type='button' value='+' name='btni' onclick=\"document.getElementById('lsti').value += document.getElementById('si').value + '|'; cargarSeccion('$ruta/listac.php','listac','lsti=' + document.getElementById('lsti').value + '&lsto=' + document.getElementById('lsto').value )\">";
	$bobligados = "<input type='button' value='+' name='btno' onclick=\"document.getElementById('lsto').value += document.getElementById('so').value + '|'; cargarSeccion('$ruta/listac.php','listac','lsti=' + document.getElementById('lsti').value + '&lsto=' + document.getElementById('lsto').value )\">";		

	$inquilinos = "<select name='si' id='si' >";
	$obligados = "<select name='so' id='so'>";
	
	switch($accion)
	{
	case 1: //todos
		//echo "chki =$chki , chko= $chko ";
		break;
	case 2: //inmuebles de contratos vigentes
		//hacer el select para inquilinos y la construcción del combo de selección al igual que de los obligados solidarios
		
		$sql = "select p.idinquilino as idi, nombre, nombre2, apaterno, amaterno, calle, numeroext, numeroint from inquilino p, contrato c, inmueble i where p.idinquilino = c.idinquilino and c.idinmueble = i.idinmueble and c.concluido = false order by calle, numeroext, numeroint";
		$datos=mysql_query($sql); 
		while($row = mysql_fetch_array($datos))
		{		
			$inquilinos .= "<option value='" . $row["idi"] . "'>" . " (" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . ")"  . $row["nombre"] . " " . $row["apaterno"] . "</option>";			
		}
		$inquilinos .= "</select>";
		
		$sql = "select p.idfiador as idi, nombre, nombre2, apaterno, amaterno, calle, numeroext, numeroint from fiador p, contrato c, inmueble i where p.idfiador = c.idfiador and c.idinmueble = i.idinmueble and c.concluido = false order by calle, numeroext, numeroint";
		$datos=mysql_query($sql);
		while($row = mysql_fetch_array($datos))
		{		
			$obligados .= "<option value='" . $row["idi"] . "'>" . " (" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . ")"  . $row["nombre"] . " " . $row["apaterno"] . "</option>";			
		}
		$obligados .= "</select>";
		
		
		if($chki=='true')
		{
		echo "Inquilinos $inquilinos $binquilinos <br>";
		}
		if($chko=='true')
		{
		echo "Obligados Solidarios $obligados $bobligados";
		}
		break;
	case 3: //todos los elementos vigentes y no vigentes
		
		$sql = "select p.idinquilino as idi, nombre, nombre2, apaterno, amaterno, calle, numeroext, numeroint from inquilino p, contrato c, inmueble i where p.idinquilino = c.idinquilino and c.idinmueble = i.idinmueble order by calle, numeroext, numeroint";
		$datos=mysql_query($sql); 
		while($row = mysql_fetch_array($datos))
		{		
			$inquilinos .= "<option value='" . $row["idi"] . "'>" .  " (" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . ")"  . $row["nombre"] . " " . $row["apaterno"] . "</option>";			
		}
		$inquilinos .= "</select>";
		
		$sql = "select p.idfiador as idi, nombre, nombre2, apaterno, amaterno, calle, numeroext, numeroint from fiador p, contrato c, inmueble i where p.idfiador = c.idfiador and c.idinmueble = i.idinmueble order by calle, numeroext, numeroint";
		$datos=mysql_query($sql);
		while($row = mysql_fetch_array($datos))
		{		
			$obligados .= "<option value='" . $row["idi"] . "'>" .  " (" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . ")"  . $row["nombre"] . " " . $row["apaterno"] . "</option>";			
		}
		$obligados .= "</select>";		
		
		if($chki=='true')
		{
		echo "Inquilinos $inquilinos $binquilinos <br>";
		}
		if($chko=='true')
		{
		echo "Obligados Solidarios $obligados $bobligados";
		}
		break;	
	

	}
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
