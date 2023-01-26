<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclass.php';

$accion = @$_GET["accion"];
//$id=@$_GET["id"];
$lsti=@$_GET["lsti"];
$lsto=@$_GET["lsto"];
$asunto=@$_GET["asunto"];
$mensaje=@$_GET["mensaje"];
$chki=@$_GET["chki"];
$chko=@$_GET["chko"];
$opt1=@$_GET["opt1"];


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


	if($accion)
	{// cuando se envia el correo

		
		if($opt1=="false")
		{
						
			$reporte="";
			$plista1 = split('[|]',$lsti);
			foreach($plista1 as $id)
			{
				if($id)
				{
					$sql = "select nombre, nombre2, apaterno, amaterno, email from inquilino  where idinquilino = $id";
					$datos=mysql_query($sql); 
					$row = mysql_fetch_array($datos);
					$mail =  $row["email"];	
					try
					{
						$enviocorreo->enviar($mail, $asunto, $mensaje);
						$reporte .= $row["nombre"] . " " . $row["apaterno"] . "(" . $row["email"] . ") Ok<br>";
						
					}
					catch (Exception $e)
					{
						$reporte .= $row["nombre"] . " " . $row["apaterno"] . "(" . $row["email"] . ") " . $e->getMessage() . "<br>" ;
					}								
					
				}
			}
			
			$plista1 = split('[|]',$lsto);
			foreach($plista1 as $id)
			{	
				if($id)
				{	
					$sql = "select nombre, nombre2, apaterno, amaterno, email from fiador  where idfiador = $id";
					$datos=mysql_query($sql); 
					$row = mysql_fetch_array($datos);
					$mail =  $row["email"];	
					try
					{
						$enviocorreo->enviar($mail, $asunto, $mensaje);
						$reporte .= $row["nombre"] . " " . $row["apaterno"] . "(" . $row["email"] . ") Ok<br>";
						
					}
					catch (Exception $e)
					{
						$reporte .= $row["nombre"] . " " . $row["apaterno"] . "(" . $row["email"] . ") " . $e->getMessage() . "<br>";
					}			
				}	
			}
		
		
		}
		else
		{
			$reporte="";
			if($chki=="true")
			{
				$sql = "select nombre, nombre2, apaterno, amaterno, email from inquilino  where idinquilino = $id";
				$datos=mysql_query($sql); 
				$row = mysql_fetch_array($datos);
				$mail =  $row["email"];	
				try
				{
					$enviocorreo->enviar($mail, $asunto, $mensaje);
					$reporte .= $row["nombre"] . " " . $row["apaterno"] . "(" . $row["email"] . ") Ok";
					
				}
				catch (Exception $e)
				{
					$reporte .= $row["nombre"] . " " . $row["apaterno"] . "(" . $row["email"] . ") " . $e->getMessage() ;
				}			
		
		
		
			}
			if($chko=="true")
			{
				$sql = "select nombre, nombre2, apaterno, amaterno, email from fiador  where idfiador = $id";
				$datos=mysql_query($sql); 
				$row = mysql_fetch_array($datos);
				$mail =  $row["email"];	
				try
				{
					$enviocorreo->enviar($mail, $asunto, $mensaje);
					$reporte .= $row["nombre"] . " " . $row["apaterno"] . "(" . $row["email"] . ") Ok";
					
				}
				catch (Exception $e)
				{
					$reporte .= $row["nombre"] . " " . $row["apaterno"] . "(" . $row["email"] . ") " . $e->getMessage() ;
				}					
		
		
			}
	
		}
		
		echo $reporte;
		
		


	}
	else //Cuando se abre por primera vez
	{
echo <<<correo
<center>
<h1>Env&iacute;o de Correos</h1>

<table border="0">
<tr>
	<td align="center">
		<input type="checkbox" name="chki" id="chki" checked> Inquilinos &nbsp;&nbsp;&nbsp;&nbsp; 
		<input type="checkbox" name="chko" id="chko" checked> Obligados Solidarios
	</td>
</tr>
<tr>
	<td align="center">
		<input type="radio" name="opte" id="opte1" value="1" onclick="cargarSeccion('$ruta/tipoc.php','tipoc','accion=1&chki=' + document.getElementById('chki').checked + '&chko=' + document.getElementById('chko').checked);" checked> Todos &nbsp;&nbsp;&nbsp;&nbsp; 
		<input type="radio" name="opte" value="2" onclick="cargarSeccion('$ruta/tipoc.php','tipoc','accion=2&chki=' + document.getElementById('chki').checked + '&chko=' + document.getElementById('chko').checked)"> Inmueble &nbsp;&nbsp;&nbsp;&nbsp; 
		<input type="radio" name="opte" value="3" onclick="cargarSeccion('$ruta/tipoc.php','tipoc','accion=3&chki=' + document.getElementById('chki').checked + '&chko=' + document.getElementById('chko').checked)"> Especificos
	<td>
</tr>
<tr>
	<td>
		<div id="tipoc"> </div>
	</td>
</tr>
<tr>
	<td>
		<input type="hidden" value="" name="lsti" id="lsti">
		<input type="hidden" value="" name="lsto" id="lsto">		
		<div id="listac"></div>
	<td>
</tr>
<tr>
	<td>
		Asunto <input type="text" value="" name="asunto" id="asunto"/><br>
		Mensaje<br>
		<textarea name="mensaje" id="mensaje"  cols="70" rows="10"></textarea><br>
		<input type="button" value="enviar" onclick="cargarSeccion('$dirscript','contenido','accion=1&chki=' + document.getElementById('chki').checked + '&chko=' + document.getElementById('chko').checked + '&lsti=' + document.getElementById('lsti').value + '&lsto=' + document.getElementById('lsto').value + '&opt1=' + document.getElementById('opte1').checked + '&asunto=' + document.getElementById('asunto').value + '&mensaje=' + document.getElementById('mensaje').value)  ">
	</td>
</tr>

</table>
</center>

correo;

	}

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
