<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include "../general/calendarioclass.php";
include '../general/correoclass.php';
header('Content-Type: text/html; charset=iso-8859-1');

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$idtiposervicio=@$_POST['idtiposervicio'];
$idcontrato=@$_POST['idcontrato'];
$nombrer=@$_POST['nombrer'];
$fecham=@$_POST['fecham'];
$horam=@$_POST['horam'];
$reporte=@$_POST['reporte'];
$subsecuentes=@$_POST['subsecuentes'];

$productos=@$_POST["productos"];
$equipos=@$_POST["equipos"];
$trabajador=@$_POST["trabajador"];
$cliente=@$_POST["cliente"];
$responsabilidad=@$_POST["responsabilidad"];
$supervisor=@$_POST["supervisor"];

/*
if(!$activo)
{
	$activo=0;
}
*/
$enviocorreo = New correo;
$fechas = New Calendario;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='listapendientesmant.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta =$row['ruta'];
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

	//para el privilegio de editar, si es negado deshabilida el botÛn
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el botÛn
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}


	$bloquear="";
	$bloquearc="";
	$nuevoid = "";
	

	if ($accion == '1' || $accion == '2' || $accion == '3')
	{
		$cal='0';
		switch($accion)
		{
		case 1://bueno
			$cal='1';			
			break;
		case 2://regular
			$cal='2';
			break;
		case 3://malo
			//agregar el nuevo registro con los datos del anterior pero con el dato nuevo de malo		
			$sqlc = "select * from mantenimientoseg where idmantenimientoseg = $id";
			$operacionc = mysql_query($sqlc);
			$row = mysql_fetch_array($operacionc);
		
			if($row["malo"]!=1)
			{			
			
				$sqlc="insert into mantenimientoseg (idmantenimiento, recibe, observacionesm, fechams, horams, fechacita, horacita, novisita,malo) values ";
				$sqlc .=" (" . $row["idmantenimiento"] . ",'" . $row["recibe"] . "', '" . $row["observacionesm"] . "','" . $row["fechams"] . "','" . $row["horams"] . "','" . $row["fechams"] . "','" . $row["horams"] . "','" . $row["novisita"] . "',true)";
				$operacionc = mysql_query($sqlc);
				$nuevoid=mysql_insert_id();
			}		
		
			$cal='3';
			break;
		}	
		
		$sqlc="update mantenimientoseg set productosutilizados='$productos', equiposutilizados='$equipos',reportepersonal='$trabajador', comentariocliente='$cliente', responsabilidadinquilino=$responsabilidad, idsupervisor=$supervisor, evaluacion=$cal, cerrado=true   where idmantenimientoseg = $id";
		$operacionc = mysql_query($sqlc);
		
		
		$bloquearc=" disabled ";
		if ($nuevoid!="")
		{
			$id=$nuevoid;	
		}
	
	}	
	
	
	
	$malo='';
	$sql = "select *, inq.nombre as inqnombre, inq.nombre2 as inqnombre2, inq.apaterno as inqapaterno, inq.amaterno as inqamaterno, d.nombre as dnombre, d.nombre2 as dnombre2, d.apaterno as dapaterno, d.amaterno as damaterno, inm.colonia as coli from mantenimientoseg ms, mantenimiento m, contrato c, inmueble inm, inquilino inq, duenioinmueble di, duenio d, tipoinmueble ti, tiposervicio ts  where ";
	$sql .="ms.idmantenimiento = m.idmantenimiento and m.idcontrato = c.idcontrato and c.idinquilino = inq.idinquilino and m.idtiposervicio = ts.idtiposervicio and ";
	$sql .=" c.idinmueble = inm.idinmueble and inm.idinmueble = di.idinmueble and di.idduenio = d.idduenio and inm.idtipoinmueble = ti.idtipoinmueble and  idmantenimientoseg = $id";
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	
	$tiposervicio = $row["tiposervicio"];
	$tipoinmueble = $row["tipoinmueble"];
	$inquilino = $row["inqnombre"] . " " . $row["inqnombre2"] . " " . $row["inqapaterno"]  . " " . $row["inqamaterno"];
	$direccion = $row["calle"] . " " . $row["numeroext"]  . " " . $row["numeroint"] . ", Col. " . $row["coli"]  . " Alc/Mun. " . $row["delmun"]  . ", C.P. " . $row["cp"];
	$propietario = $row["dnombre"] . " " . $row["dnombre2"] . " " . $row["dapaterno"]  . " " . $row["damaterno"];
	$fechap = $row["fechams"];
	$horap =$row["horams"];
	$noaccion=$row["novisita"];
	$idpersonal = $row["idpersonal"];
	$idsupervisor = $row["idsupervisor"];
	$fechacita=$row["fechacita"];
	$horacita=$row["horacita"];
	$trabajo=$row["observacionesm"];
	$malo=$row["malo"];
	
	$productosutilizados=$row["productosutilizados"];
	$equiposutilizados=$row["equiposutilizados"];
	$reportepersonal=$row["reportepersonal"];
	$comentariocliente=$row["comentariocliente"];
	
	if($row["malo"]==1)
	{
		$malo=" Generaci&oacute;n de servicio por mala calificaci&oacute;n ";
	}
	else
	{
		$malo="";
	}
	
	
	if($row["responsabilidadinquilino"]==1)
	{
		$responsabilidadinquilino=" checked ";
	}
	else
	{
		$responsabilidadinquilino="";
	}
	
	
	$sqlS="select * from personal where idtipopersonal <>2";
	$selectpersonal = "<select name=\"idpersonal\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacionS = mysql_query($sqlS);
	while($rowS = mysql_fetch_array($operacionS))
	{
		$marcar ="";		
		if($idpersonal==$rowS["idpersonal"])
		{
			$marcar=" selected ";
		}
		$selectpersonal .= "<option value=\"" . $rowS["idpersonal"] . "\" $marcar  >" . CambiaAcentosaHTML($rowS["personal"]) .  "</option>";

	}
	$selectpersonal .="</select>";
	
	
	$sqlS="select * from personal where idtipopersonal = 2";
	$selectsupervisor = "<select name=\"idsupervisor\" id=\"idsupervisor\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacionS = mysql_query($sqlS);
	while($rowS = mysql_fetch_array($operacionS))
	{
		$marcar ="";		
		if($idpersonal==$rowS["idpersonal"])
		{
			$marcar=" selected ";
		}
		$selectsupervisor .= "<option value=\"" . $rowS["idpersonal"] . "\" $marcar  >" . CambiaAcentosaHTML($rowS["personal"]) .  "</option>";

	}
	$selectsupervisor .="</select>";	
	$hora=substr($horacita,0,2);
	$minutos=substr($horacita,3,2);
	$am="";
	$pm="";
	if($hora>'12')
	{
		$pm=" selected ";	
	}
	else 
	{
		$am=" selected ";
	}
	
	
	

	


	
	
	
	
	$htmlresto ="";
	$botonaccion="";
//investigar si ya tiene agendada la cita por medio del campo cambiofecha	
	if($row["cambiofecha"]==1)
	{
		$recibe = $row["recibe"];
		$fechacita = $row["fechacita"];
		$horacita = $row["horacita"];
//para mostrar el boton de imprimir documento
		$botonaccion="";
		
		
		if($row["reagendado"]==1)		
		{
			$bloquear=" disabled ";
		
			$botonaccion="window.open( '$ruta/imprimirmant.php?accion=0&id=" . $row["idmantenimientoseg"] . "&recibe='+ recibe.value + '&fechacita=' + fechacita.value + '&horacita=' + horacita.value + '&idpersonal=' + idpersonal.value + '&trabajo=' + trabajo.value);";
			$botonaccion ="<input type='button' value='Imprimir' onClick=\"$botonaccion\">	";	
		}
		else 
		{
			$botonaccion1="this.disabled=true;window.open( '$ruta/imprimirmant.php?accion=2&id=" . $row["idmantenimientoseg"] . "&recibe='+ recibe.value + '&fechacita=' + fechacita.value + '&horacita=' + horacita.value + '&idpersonal=' + idpersonal.value + '&trabajo=' + trabajo.value);";
			$botonaccion1 ="<input type='button' value='Re-agendar Cita' onClick=\"$botonaccion1\">	";	
			
			$botonaccion="window.open( '$ruta/imprimirmant.php?accion=0&id=" . $row["idmantenimientoseg"] . "&recibe='+ recibe.value + '&fechacita=' + fechacita.value + '&horacita=' + horacita.value + '&idpersonal=' + idpersonal.value + '&trabajo=' + trabajo.value);";
			$botonaccion ="$botonaccion1 &nbsp;&nbsp;<input type='button' value='Imprimir' onClick=\"$botonaccion\">	";	
		}
		
		
		if($row['evaluacion']>0)
		{
			$liga1="javascript:alert('ya fue calificado')";
			$liga2="javascript:alert('ya fue calificado')";
			$liga3="javascript:alert('ya fue calificado')";	
			$bloquearc=" disabled ";		
		}
		else
		{
			$liga1="javascript:cargarSeccion_new('$ruta/reportemant.php','contenido','accion=1&id=$id&productos=' + document.getElementById('productos').value + '&equipos=' + document.getElementById('equipos').value  + '&trabajador=' + document.getElementById('trabajador').value  + '&cliente=' + document.getElementById('cliente').value  + '&responsabilidad=' + document.getElementById('responsabilidad').checked  + '&supervisor=' + document.getElementById('idsupervisor').value )";
			$liga2="javascript:cargarSeccion_new('$ruta/reportemant.php','contenido','accion=2&id=$id&productos=' + document.getElementById('productos').value + '&equipos=' + document.getElementById('equipos').value  + '&trabajador=' + document.getElementById('trabajador').value  + '&cliente=' + document.getElementById('cliente').value  + '&responsabilidad=' + document.getElementById('responsabilidad').checked  + '&supervisor=' + document.getElementById('idsupervisor').value )";
			$liga3="javascript:cargarSeccion_new('$ruta/reportemant.php','contenido','accion=3&id=$id&productos=' + document.getElementById('productos').value + '&equipos=' + document.getElementById('equipos').value  + '&trabajador=' + document.getElementById('trabajador').value  + '&cliente=' + document.getElementById('cliente').value  + '&responsabilidad=' + document.getElementById('responsabilidad').checked  + '&supervisor=' + document.getElementById('idsupervisor').value )";
			//$liga3="javascript:alert('malo')";			
		}
	
		
// Html del resto del formulario
$htmlresto ="	
		<fieldset>
			<legend>Registro del mantenimiento </legend>
			<table>
			<tr>
				<td>Productos utilizados</td><td> <textarea name='productos' id='productos' rows='5' cols='80' $bloquearc>$productosutilizados</textarea> </td>
			</tr>
			<tr>
				<td>Equipos utilizados</td><td> <textarea name='equipos' id='equipos' rows='5' cols='80' $bloquearc>$equiposutilizados</textarea> </td>
			</tr>						
			<tr>
				<td>Reporte del personal</td><td> <textarea name='trabajador' id='trabajador' rows='5' cols='80' $bloquearc>$reportepersonal</textarea> </td>
			</tr>			
			<tr>
				<td>Comentarios cliente</td><td> <textarea name='cliente' id='cliente' rows='5' cols='80' $bloquearc>$comentariocliente</textarea> </td>
			</tr>			
			<tr>
				<td>Resp. del inquilino</td><td> <input type='checkbox' name='responsabilidad' id='responsabilidad' value='1' $responsabilidadinquilino $bloquearc></td>
			</tr>
			<tr>
				<td>Supervisor </td><td> $selectsupervisor  </td>
			</tr>			
			<tr>
				<td colspan='2' align = 'center'> <a href=\"$liga1\" >OK</a> &nbsp;&nbsp;&nbsp; <a href=\"$liga2\" $bloquearc2>Regular</a> &nbsp;&nbsp;&nbsp; <a href=\"$liga3\" $bloquearc2>Malo</a> </td>
			</tr>						
		</fieldset>";
	
	
	
	
		
		
		
	}
	else
	{
		$recibe = $inquilino;
		$fechacita = $fechap;
		$horacita =$horap;
//para guardar los datos de la cita
		$botonaccion="this.disabled=true;window.open( '$ruta/imprimirmant.php?accion=1&id=" . $row["idmantenimientoseg"] . "&recibe='+ recibe.value + '&fechacita=' + fechacita.value + '&horacita=' + horacita.value + '&idpersonal=' + idpersonal.value + '&trabajo=' + trabajo.value);";
		$botonaccion ="<input type='button' value='Agendar Cita' onClick=\"$botonaccion\">	";	
		
	}

	//inicia la variable que contendr· la consulta
	$sql="";

	//Segun la acciÛn que se tomÛ, se proceder· a editar el sql

	switch($accion)
	{
	case "0": // bueno y regular

		$sqlm="update mantenimientoseg set recibe='$recibe', fechacita='$fechacita', horacita='$horacita', cambiofecha=true, evaluacion = 0 where idmantenimientoseg = $id";
		
		
				
		break;
	case "1": //  regular
		$sqlm="update mantenimientoseg set recibe='$recibe', fechacita='$fechacita', horacita='$horacita', cambiofecha=true, evaluacion = 1 where idmantenimientoseg = $id";

		break;

	case "3": //malo

		$sql="delete from mantenimientoseg where idmantenimiento=$id";
		$operacion = mysql_query($sql);
		$sql="delete from mantenimiento where idmantenimiento=$id";
		//echo "<br>Borro";
		
		$idtiposervicio="";
		$idcontrato="";
		$nombrer="";
		$fecham="";
		$horam="";
		$reporte="";
		$subsecuentes="";
		break;

	}

	//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
	//Preparo las variables para los botÛnes
	$boton1="Limpiar";
	$boton2="Agregar";

	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la acciÛn a realizar para la siguiente presiÛn del botÛn agregar
	//en su defecto, sera accÛn agregar
	





//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Seguimiento de Mantenimiento</h1>
<form>
<table border="0">
<tr>
	<td>
		<fieldset>
			<legend>Informaci&oacute;n del Mantenimiento (<b>$tiposervicio</b> $malo)</legend>
			Numero de servicio: <b>$noaccion </b><br>
			Inquilino: <b>$inquilino</b> <br>
			Direcci&oacute;n : <b>$direccion</b> <br>
			Tipo de inmueble : <b>$tipoinmueble </b> <br>
			Propietario: <b>$propietario</b><br>
			Fecha programada: <b>$fechap </b>
		</fieldset>
	</td>
	<td>
		<fieldset>
			<legend>Agendar cita</legend>
			Nombre de quien recibe:<br> <input type="text" name="recibe" value= "$recibe" $bloquear>    <br>
			Fecha (aaaa-mm-dd):<br> <input type="text" name="fechacita" value="$fechacita" $bloquear> &nbsp;&nbsp;&nbsp;<br>
			Hora: <input type="text" name="hora" value="$hora" $bloquear size='3' onblur="horac=0;if(tipoh.value=='PM') {horac=12 + Number(this.value);}else{horac=this.value};if(this.value>=0&&this.value<=12){ horacita.value=(this.value + ':' + minutos.value + ':00');}else{alert('minutos incorrectas');this.value=''};">:<input type="text" name="minutos" value="$minutos" $bloquear size='3' onblur="horac=0;if(tipoh.value=='PM') {horac=12 + Number(hora.value);}else{horac=hora.value};if(this.value>=0&&this.value<=59){ horacita.value=(hora.value + ':' + minutos.value + ':00');}else{alert('minutos incorrectos');this.value=''};">
			
			
			<input type="hidden" name="horacita" value="$horacita">
			<select name='tipoh' onchange="horac=0;if(this.value=='PM'){horac = 12 + Number(hora.value); horacita.value = horac + ':' + minutos.value + ':00';}else{horacita.value = hora.value + ':' + minutos.value + ':00';};">
				<option value='AM' $am>AM</option>
				<option value='PM' $pm>PM</option>
			</select><br>
			
			Realiz&oacute; el mantenimiento:<br> $selectpersonal <br>
			Trabajo a realizar: <br><input type="text" name="trabajo" value= "$trabajo" $bloquear> 
						
			<center>
				$botonaccion
			</center>
		</fieldset>		
	
	</td>
</tr>
<tr>
	<td colspan="2">
		$htmlresto 
	</td>
</tr>
</table>
</form>
</center>
formulario1;

	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\"></div>";





}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>