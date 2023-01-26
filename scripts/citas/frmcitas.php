<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclass.php';

//Formulario para el script de tarea.php
//Es para mostrar los datos para editar o para agregar un elemento nuevo
//interactua con el otro script en las acciones de editar y nuevo
$accion = @$_GET["accion"];
$id=@$_GET["id"];

$nombre=@$_GET["nombre"];
$tels=@$_GET["tels"];


$email=@$_GET['email'];
$fecha=@$_GET['fecha'];
$hora=@$_GET['hora'];
$atiende=@$_GET['atiende'];
$nota=@$_GET['nota'];
$atendida=@$_GET['atendida'];
$idinmueble=@$_GET['idinmueble'];


$mostrarformulario=1;
$mensaje="";

$enviocorreo = New correo;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='citas.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
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
	
	
	
//***************************	
	
	
	
	$mensaje="";
	$sql="";
	
	switch($accion)
	{
	case "0": // Agrego

		
		$sql="insert into cita (nombrec,telc,emailc,fechac,horac,atiende,nota, idinmueble, atendida) values ('$nombre','$tels','$email','$fecha','$hora','$atiende','$nota',$idinmueble, false)";
		$mostrarformulario=0;
		//echo "<br>Agrego";		
		break;

	case "1": //Borro


		$sql = "delete from seguimientocita where idcita=$id";
		$datos=mysql_query($sql);

		$sql = "delete from cita where idcita=$id";
		$mostrarformulario=0;

		break;

	case "3": //Actualizo

		$sql = "update cita set nombrec='$nombre',telc='$tels', emailc='$email', fechac='$fecha', atiende='$atiende', nota='$nota', idinmueble=$idinmueble, horac='$hora' where idcita=$id";
		///echo "<br>Actualizo";
		$mostrarformulario=0;
		break;
		
	case "4": //terminar tarea
	
		$sql="update cita set atendida=1 where idcita = $id";
		$mostrarformulario=0;

	}
//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{
		//echo $sql . "<br>";
		$mensaje=$sql;
		$operacion = mysql_query($sql);		
		
	}	
	
	
//**********************************	
	
	
	
	$boton1="Cancelar";
	$boton2="Agregar";

	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from cita where idcita = $id";
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
		
		$nombre=CambiaAcentosaHTML($row["nombrec"]);
		$tels=CambiaAcentosaHTML($row["telc"]);
		$email=CambiaAcentosaHTML($row["emailc"]);
		$fecha=CambiaAcentosaHTML($row["fechac"]);
		$hora=CambiaAcentosaHTML($row["horac"]);
		$atiende=CambiaAcentosaHTML($row["atiende"]);
		$nota=CambiaAcentosaHTML($row["nota"]);
		$idinmueble=$row["idinmueble"];
		
		
	}
	else
	{
		$accion="0";
	}	
	

	
		
	
	if($mostrarformulario==1)
	{


	$sql="select * from inmueble where not(idinmueble in (select idinmueble from contrato where concluido=false)) and not(idinmueble in (select idinmueble from apartado where cancelado = false ))";
	$datos=mysql_query($sql);
	$selectinmuebles="<select id='idinmueble' name='idinmueble'> <option valie='0'>Seleccione uno de la lista</option>";
	$marca="";
	while($row = mysql_fetch_array($datos))
	{
		if($idinmueble == $row["idinmueble"])	
		{
			$marca=" selected ";
		}
		$selectinmuebles .="<option value=" . $row["idinmueble"] . " $marca>" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . "</option>";
	}
	$selectinmuebles .="</select>";

	

echo <<<formularioA
<center>

<form>
<table border="0">
<tr>
	<td>Inmueble:</td>
	<td>$selectinmuebles</td>
</tr>
<tr>
	<td>Nombre:</td>
	<td><input type="text" name="nombre" id="nombre" value="$nombre" ></td>
</tr>
<tr>
	<td>Telefono(s):</td>
	<td><input type="text" name="tels" id="tels" value="$tels" ></td>
</tr>
<tr>
	<td>e-mail:</td>
	<td><input type="text" name="email" id="email" value="$email" ></td>
</tr>
<tr>
	<td>Atiende:</td>
	<td><input type="text" name="atiende" id="atiende" value="$atiende" ></td>
</tr>
<tr>
	<td>Fecha: <br>(aaaa-mm-dd)</td>
	<td><input type="text" name="fecha" id="recha" value="$fecha" ></td>
</tr>
<tr>
	<td>Hora: <br>(HH:mm)</td>
	<td><input type="text" name="hora" id="hora" value="$hora" ></td>
</tr>
<tr>
	<td>Notas:</td>
	<td>
		<textarea name="nota" id="nota" rows="3" cols="40">$nota</textarea>
	</td>
</tr>

<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="document.getElementById('frmcita').innerHTML=''">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="Agregar" onClick="if(nombre.value!='' && fecha.value!='' && tels.value!='' && hora.value!=''  && idinmueble.value!='0' ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$ruta/frmcitas.php','frmcita','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value  + '&tels=' + tels.value + '&email=' + email.value + '&fecha=' + fecha.value + '&hora=' + hora.value + '&atiende=' + atiende.value + '&nota=' + nota.value + '&idinmueble=' + idinmueble.value )};if(this.value=='Agregar'&&privagregar.value==1){  cargarSeccion('$ruta/frmcitas.php','frmcita','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value  + '&tels=' + tels.value + '&email=' + email.value + '&fecha=' + fecha.value + '&hora=' + hora.value + '&atiende=' + atiende.value + '&nota=' + nota.value  + '&idinmueble=' + idinmueble.value)}}else{alert('fallo');};" >
		<input type="hidden" name="ids" value="$id">
		<input type="hidden" name="accion" value="$accion">
		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">

	</td>
</tr>
</table>
</form>

formularioA;
	}
	else
	{
		echo "";
	}
	
	
	if($mensaje!="")
	{
		$mensaje = $misesion->usuario . ">> " . $mensaje;          
		echo "<!-- ";
		$enviocorreo->enviar("lsolis@sismac.com", "Movimiento Citas", $mensaje);   
		echo "-->";
	}                                                             

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>	