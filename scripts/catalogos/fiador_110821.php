<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/auditoriaclass.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$nombre=@$_GET['nombre'];
$nombre2=@$_GET['nombre2'];
$apaterno=@$_GET['apaterno'];
$amaterno=@$_GET['amaterno'];
$rfc=@$_GET['rfc'];
$curp=@$_GET['curp'];
$identificacion=@$_GET['identificacion'];
$datosinmueble=@$_GET['datosinmueble'];
$tel=@$_GET['tel'];
$direccion=@$_GET['direccion'];
$delegacion = @$_GET["delegacion"];
$colonia = @$_GET["colonia"];
$cp= @$_GET["cp"];
$idestado = @$_GET["idestado"];
$emailf = @$_GET["emailf"];



$misesion = new sessiones;
$aud = new auditoria;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='fiador.php'";
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

	//para el privilegio de editar, si es negado deshabilida el botn
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el botn
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}


	//inicia la variable que contendr la consulta
	$sql="";

	//Segun la accin que se tom, se proceder a editar el sql
	switch($accion)
	{
	case "0": // Agrego

		$sql="insert into fiador (nombre,nombre2,apaterno,amaterno,direccion,identificacion,datosinmueble,tel,delegacion,colonia,cp,idestado, email, rfc, curp) values ('$nombre','$nombre2','$apaterno','$amaterno','$direccion','$identificacion', '$datosinmueble','$tel','$delegacion','$colonia','$cp',$idestado,'$emailf','$rfc', '$curp')";
		//echo "<br>Agrego";
		$nombre="";
		$nombre2="";
		$apaterno="";
		$amaterno="";
		$rfc="";
		$curp="";
		$identificacion="";
		$datosinmueble="";
		$tel="";
		$direccion="";
		$delegacion = "";
		$colonia = "";
		$cp= "";
		$idestado = "";
		$emailf ="";


		

		break;

	case "1": //Borro

		$sql="delete from fiador where idfiador=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update fiador set nombre='$nombre',nombre2='$nombre2' ,apaterno='$apaterno',amaterno='$amaterno',identificacion='$identificacion', direccion='$direccion', datosinmueble='$datosinmueble', tel='$tel', delegacion = '$delegacion', colonia='$colonia', cp='$cp', idestado=$idestado, email ='$emailf', rfc='$rfc', curp ='$curp' where idfiador=$id";
		///echo "<br>Actualizo";
		$nombre="";
		$nombre2="";
		$apaterno="";
		$amaterno="";
		$rfc="";
		$curp="";
		$identificacion="";
		$datosinmueble="";
		$tel="";
		$direccion="";
		$delegacion = "";
		$colonia = "";
		$cp= "";
		$idestado = "";
		$emailf ="";

	}

	//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{

		$operacion = mysql_query($sql);
		if(substr($sql,0,6)=="insert")
		{
			$aud->tabla="fiador";
			$aud->idtabla=mysql_insert_id();
			$aud->accion = 1;
			$aud->usuario =$misesion->usuario;
			$aud->crearregistro();
		
		}
		elseif(substr($sql,0,6)=="update")
		{
			$aud->tabla="fiador";
			$aud->idtabla=$id;
			$aud->accion = 2;
			$aud->usuario =$misesion->usuario;
			$aud->crearregistro();
		
		
		}

	}
	//Preparo las variables para los botnes
	$boton1="Limpiar";
	$boton2="Agregar";

	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la accin a realizar para la siguiente presin del botn agregar
	//en su defecto, sera accn agregar
	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from fiador  where idfiador = $id ";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"]);
			$nombre2=CambiaAcentosaHTML($row["nombre2"]);
			$apaterno=CambiaAcentosaHTML($row["apaterno"]);
			$amaterno=CambiaAcentosaHTML($row["amaterno"]);
			$rfc=CambiaAcentosaHTML($row["rfc"]);
			$curp=CambiaAcentosaHTML($row["curp"]);
			$identificacion=CambiaAcentosaHTML($row["identificacion"]);
			$datosinmueble=CambiaAcentosaHTML($row["datosinmueble"]);
			$tel=CambiaAcentosaHTML($row["tel"]);
			$direccion=CambiaAcentosaHTML($row["direccion"]);
			$delegacion = CambiaAcentosaHTML($row["delegacion"]);
			$colonia = CambiaAcentosaHTML($row["colonia"]);
			$cp= CambiaAcentosaHTML($row["cp"]);
			$idestado = $row["idestado"];
			$emailf =$row["email"];
	
		}



	}
	else
	{
		$accion="0";
	}


	$sql="select * from estado";

	$estadoselect = "<select name=\"idestado\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		//if ($idduenio>0)
		if ($idestado==$row["idestado"])
		{
			$seleccionopt=" SELECTED ";
		}
		$estadoselect .= "<option value=\"" . $row["idestado"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["estado"]) . "</option>";

	}	
	$estadoselect .="</select>";
	

	$btnaud="";
	if($aud->id!=0)
	{
		$btnaud = "<input type='button' value='Imprimir movimiento' onClick=\"window.open('$ruta/impresiontransaccion.php?id=$aud->id&seccion=fiador');\">";
	
	}

//Genero el formulario de los submodulos

echo  <<<formulario1
<center>
$btnaud
<h1>Obligado solidario</h1>
<form>
<table border="0">
<tr>
	<td>Nombre:</td>
	<td><input type="text" name="nombre" value="$nombre"></td>
</tr>
<tr>
	<td>Segundo Nombre:</td>
	<td><input type="text" name="nombre2" value="$nombre2"></td>
</tr>
<tr>
	<td>A. Paterno:</td>
	<td><input type="text" name="apaterno" value="$apaterno"></td>
</tr>
<tr>
	<td>A. Materno:</td>
	<td><input type="text" name="amaterno" value="$amaterno"></td>
</tr>

<tr>
	<td>CURP:</td>
	<td><input type="text" name="curp" id="curp" value="$curp"></td>
</tr>

<tr>
	<td>RFC</td>
	<td><input type="text" name="rfc" id="rfc" value="$rfc"></td>
</tr>

<tr>
	<td>Identificacion:</td>
	<td><input type="text" name="identificacion" value="$identificacion"></td>
</tr>
<tr>
	<td>Direcci&oacute;n:</td>
	<td><textarea name="direccion" cols="30" rows="5">$direccion</textarea></td>
</tr>
<tr>
	<td>Alcald&iacute;a</td>
	<td><input type="text" name="delegacion" value="$delegacion"></td>
</tr>
<tr>
	<td>Colonia:</td>
	<td><input type="text" name="colonia" value="$colonia"></td>
</tr>
<tr>
	<td>C.P.:</td>
	<td><input type="text" name="cp" value="$cp"></td>
</tr>

<tr>
	<td>Estado</td>
	<td>$estadoselect</td>
</tr>
<tr>
	<td>Datos Inmueble:</td>
	<td><textarea name="datosinmueble" cols="30" rows="5">$datosinmueble</textarea></td>
</tr>
<tr>
	<td>Tel&eacute;fono:</td>
	<td><input type="text" name="tel" value="$tel"></td>
</tr>
<tr>
	<td>e-mail:</td>
	<td><input type="text" name="emailf" value="$emailf"></td>
</tr>
<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';nombre.value='';nombre2.value='';apaterno.value='';amaterno.value='';identificacion.value='';direccion.value='';datosinmueble.value=''; tel.value=''; delegacion.value=''; colonia.value=''; cp.value=''; idestado.value=0;emailf.value='';rfc.value=''; curp.value=''; ">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="tamrfc=rfc.value;if(nombre.value!='' && tamrfc.length==13 && validarCURPFiador()==true){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&nombre2=' + nombre2.value + '&identificacion=' + identificacion.value + '&direccion=' + direccion.value + '&datosinmueble=' + datosinmueble.value + '&tel=' + tel.value  + '&delegacion=' + delegacion.value  + '&colonia=' + colonia.value  + '&cp=' + cp.value  + '&idestado=' + idestado.value + '&emailf=' + emailf.value + '&curp=' + curp.value + '&rfc=' + rfc.value )};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&nombre2=' + nombre2.value + '&identificacion=' + identificacion.value + '&direccion=' + direccion.value + '&datosinmueble=' + datosinmueble.value + '&tel=' + tel.value  + '&delegacion=' + delegacion.value  + '&colonia=' + colonia.value  + '&cp=' + cp.value  + '&idestado=' + idestado.value + '&emailf=' + emailf.value + '&curp=' + curp.value + '&rfc=' + rfc.value)}}; if(nombre.value!='' &&  tamrfc.length==12){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&nombre2=' + nombre2.value + '&identificacion=' + identificacion.value + '&direccion=' + direccion.value + '&datosinmueble=' + datosinmueble.value + '&tel=' + tel.value  + '&delegacion=' + delegacion.value  + '&colonia=' + colonia.value  + '&cp=' + cp.value  + '&idestado=' + idestado.value + '&emailf=' + emailf.value + '&curp=' + curp.value + '&rfc=' + rfc.value )};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&nombre2=' + nombre2.value + '&identificacion=' + identificacion.value + '&direccion=' + direccion.value + '&datosinmueble=' + datosinmueble.value + '&tel=' + tel.value  + '&delegacion=' + delegacion.value  + '&colonia=' + colonia.value  + '&cp=' + cp.value  + '&idestado=' + idestado.value + '&emailf=' + emailf.value + '&curp=' + curp.value + '&rfc=' + rfc.value)}};" >
		<input type="hidden" name="ids" value="$id">
		<input type="hidden" name="accion" value="$accion">
		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">

	</td>
</tr>
</table>
</form>
</center>
formulario1;

	//echo CambiaAcentosaHTML($html);
	
	$sql="select * from fiador ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
//	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Segundo Nombre</th><th>A. Paterno</th><th>A. Materno</th><th>Identificacion</th><th>Direcci&oacute;n</th><th>Datos Inmueble</th><th>Tel&eacute;fono</th><th>Acciones</th></tr>";
	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Segundo Nombre</th><th>A. Paterno</th><th>A. Materno</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
//		echo "<tr><td>" . $row["idfiador"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["nombre2"] . "</td><td>" . $row["apaterno"] . "</td><td>" . $row["amaterno"] . "</td><td>" . $row["identificacion"] . "</td><td>" . $row["direccion"] . "</td><td>" . $row["datosinmueble"] . "</td><td>" . $row["tel"] . "</td><td>";
		$html = "<tr><td>" . $row["idfiador"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["nombre2"] . "</td><td>" . $row["apaterno"] . "</td><td>" . $row["amaterno"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idfiador"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idfiador"]  . "' )\" $txteditar>";
		$html .= "<input type='button' value='Movimientos' onClick=\"window.open('$ruta/movauditoria.php?id=" .  $row["idfiador"]  . "&tabla=fiador');\">";
		$html .= "</td></tr>";
		echo CambiaAcentosaHTML($html);
	}
	echo "</table></div>";





}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>