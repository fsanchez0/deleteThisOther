<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$id=@$_GET["id"];
$seguimiento=@$_GET["seguimiento"];




$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='citas.php'";
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


	if($seguimiento!="")
	{
		$fechasc=date("Y-m-d");
		$horasc=date("H:i");
		$sql="insert into seguimientocita (idcita,seguimientocita, fechasc, horasc) values ($id,'$seguimiento','$fechasc','$horasc')";
		$operacion = mysql_query($sql);

	}

	
	
	$sql="select nombrec,emailc, nota, telc,fechac,horac,atiende, calle, numeroext, numeroint, idcita from cita c, inmueble i where c.idinmueble = i.idinmueble and idcita=$id order by fechac, horac";
	
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
	
		$elidt=$row["idcita"];
		$nombre=CambiaAcentosaHTML($row["nombrec"]);
		$tel=CambiaAcentosaHTML($row["telc"]);
		$email=CambiaAcentosaHTML($row["emailc"]);
		$nota=CambiaAcentosaHTML($row["nota"]);
		$fecha=$row["fechac"];
		$hora=$row["horac"];
		$atiende=$row["atiende"];
		$inmueble=$row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"];
		

	}
	
	
	$sql= "select seguimientocita, fechasc, horasc from seguimientocita sc where sc.idcita = $id order by idseguimientocita desc, fechasc desc,  horasc desc";
	$listas= "<table border =\"1\"><tr><th>Fecha</th><th>Hora</th><th>Seguimiento</th></tr>";
	
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{					
		$listas .= "<tr><td>" .  $row["fechasc"] . "</td><td>" . $row["horasc"] . "</td><td>" . $row["seguimientocita"] . "</td></tr>";
	}	
	$listas .= "</table>";
	
	
	$bregresar="<input type=\"button\" value=\"Regresar a citas\" onClick=\"cargaTareas('$ruta/citas.php','contenido','id=$id');\" >";
	$bseguimiento = "<input type=\"button\" value=\"Agregar\" onClick=\"cargarSeccion('$ruta/vercita.php','contenido','id=$id&seguimiento=' + seguimiento.value )\"><br>";
	
echo <<<formulario1
<center>
<table border="0">
<tr>
<td valign="top">
<center> $bregresar</center>
<table border="0">
<tr>
	<td>Inmueble:</td>
	<td>$inmueble</td>
</tr>
<tr>
	<td>Nombre:</td>
	<td>$nombre</td>
</tr>
<tr>
	<td>Telefono(s):</td>
	<td>$tel</td>
</tr>
<tr>
	<td>e-mail:</td>
	<td>$email</td>
</tr>
<tr>
	<td>Atiende:</td>
	<td>$atiende</td>
</tr>
<tr>
	<td>Fecha: <br>(aaaa-mm-dd)</td>
	<td>$fecha</td>
</tr>
<tr>
	<td>Hora: <br>(HH:mm)</td>
	<td>$hora</td>
</tr>
<tr>
	<td>Notas:</td>
	<td>
		$nota
	</td>
</tr>
</table>


</td>
</tr>

<tr>
<td align="center">
	Seguimiento<br>
	<textarea name="seguimiento" id="seguimiento" cols="40" rows="10"></textarea>
	<br>$bseguimiento
</td>
</tr>
</table>
        
	


$listas
</center>
formulario1;


	

}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}


/*
<td>
&nbsp;&nbsp;
</td>
<td valign="top">
	<table>
	<tr>
		<td>
			<b>Subtareas</b>
<form>	
	<input type="button" id="btnpnuevo" value="Nueva" onClick="cargarSeccion('$ruta/ftareast.php','frmtareas','id=$id')">
	<input type="button" id="btnactualizar" value="Actualizar" onClick="cargarSeccion('$ruta/listasub.php','listasub','id=$id')">
</form			
		</td>
	</tr>
	
	<tr>
		<td>
			<div id="frmtareas"></div>
			<div id="listasub">$listasub</div>
		</td>
	</tr>
	</table>
</td>
*/



?>