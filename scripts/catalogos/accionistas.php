<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["idc"];
$idi=@$_GET['idic'];
$idpaisc=@$_GET['idpaisc'];
$idestadoc=@$_GET['idestadoc'];
$rfc=@$_GET['rfcc'];
$curp=@$_GET['curp'];
$nombre=@$_GET['nombrec'];
$nombre2=@$_GET['nombrec2'];
$apaterno=@$_GET['apaternoc'];
$amaterno=@$_GET['amaternoc'];
$porcentaje=@$_GET['porcentaje'];
$direccion=@$_GET['direccionc'];
$colonia=@$_GET['coloniac'];
$delegmun=@$_GET['delegmunc'];
$ciudad=@$_GET['ciudadc'];
$cp=@$_GET['cpc'];
$tel=@$_GET['telc'];


//$idi=1;

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='inquilinos.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta= $row['ruta'] ;
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


	//inicia la variable que contendrá la consulta
	$sql="";

	//Segun la acción que se tomó, se procederá a editar el sql
	switch($accion)
	{
	case "0": // Agrego

		$sql="insert into accionista (nombreac1,nombreac2,apaternoac,amaterno,porcentaje,direccionac,coloniaac,delegmunac,ciudadac,cpac, telac, idinquilino,idpais,idestado, rfc,curp) values ('$nombre','$nombre2','$apaterno','$amaterno',$porcentaje,'$direccion','$colonia','$delegmun','$ciudad','$cp','$tel',$idi, $idpaisc, $idestadoc, '$rfc','curp')";
		//echo "<br>Agrego";
		$idpaisc="";
		$idestadoc="";
		$rfc="";
		$curp="";
		$nombre="";
		$nombre2="";
		$apaterno="";
		$amaterno="";
		$porcentaje="";
		$direccion="";
		$colonia="";
		$delegmun="";
		$ciudad="";
		$cp="";
		$tel="";

		break;

	case "1": //Borro

		$sql="delete from accionista where idaccionista=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update accionista set nombreac1='$nombre',nombreac2='$nombre2' ,apaternoac='$apaterno',amaterno='$amaterno',porcentaje='$porcentaje', direccionac='$direccion', coloniaac='$colonia', delegmunac='$delegmun', ciudadac='$ciudad', cpac='$cp', telac='$tel', idinquilino=$idi, idpais=$idpaisc, idestado = $idestadoc, rfc='$rfc', curp='$curp' where idaccionista=$id";
		///echo "<br>Actualizo";
		$idpaisc="";
		$idestadoc="";
		$rfc="";
		$curp="";
		$nombre="";
		$nombre2="";
		$apaterno="";
		$amaterno="";
		$porcentaje="";
		$direccion="";
		$colonia="";
		$delegmun="";
		$ciudad="";
		$cp="";
		$tel="";

	}

	//ejecuto la consulta si tiene algo en la variable
	//echo $sql;
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
	//Preparo las variables para los botónes
	$boton1="Limpiar";
	$boton2="Agregar";

	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la acción a realizar para la siguiente presión del botón agregar
	//en su defecto, sera accón agregar
	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from accionista where idaccionista = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			
			$idpaisc=$row['idpais'];
			$idestadoc=$row['idestado'];
			$rfc=CambiaAcentosaHTML($row["rfc"]);
			$curp=CambiaAcentosaHTML($row["curp"]);
			$nombre=CambiaAcentosaHTML($row["nombreac1"]);
			$nombre2=CambiaAcentosaHTML($row["nombreac2"]);
			$apaterno=CambiaAcentosaHTML($row["apaternoac"]);
			$amaterno=CambiaAcentosaHTML($row["amaterno"]);
			$porcentaje=$row["porcentaje"];
			$direccion=CambiaAcentosaHTML($row["direccionac"]);
			$colonia=CambiaAcentosaHTML($row["coloniaac"]);
			$delegmun=CambiaAcentosaHTML($row["delegmunac"]);
			$ciudad=CambiaAcentosaHTML($row["ciudadac"]);
			$cp=CambiaAcentosaHTML($row["cpac"]);
			$tel=CambiaAcentosaHTML($row["telac"]);			

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
		if ($idestadoc==$row["idestado"])
		{
			$seleccionopt=" SELECTED ";
		}
		$estadoselect .= "<option value=\"" . $row["idestado"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["estado"]) . "</option>";

	}	
	$estadoselect .="</select>";

	$sql="select * from pais";

	$paisselect = "<select name=\"idpais\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		//if ($idduenio>0)
		if ($idpaisc==$row["idpais"])
		{
			$seleccionopt=" SELECTED ";
		}
		$paisselect .= "<option value=\"" . $row["idpais"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["pais"]) . "</option>";

	}	
	$paisselect .="</select>";


//Genero el formulario de los submodulos

echo <<<formulario1


<center>
<h1>Acconistas</h1>
<form>
<table border="0">
<tr>
	<td>Acciones (%):</td>
	<td><input type="text" name="porcentaje" value="$porcentaje"></td>
</tr>
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
	<td>RFC:</td>
	<td><input type="text" name="rfc" value="$rfc"></td>
</tr>
<tr>
	<td>CURP:</td>
	<td><input type="text" name="curp" value="$curp"></td>
</tr>
<tr>
	<td>Pais:</td>
	<td>$paisselect</td>
</tr>
<tr>
	<td>Estado:</td>
	<td>$estadoselect</td>
</tr>
<tr>
	<td>Direcci&oacute;:</td>
	<td><textarea name="direccion" cols="50" rows="4">$direccion</textarea></td>
</tr>
<tr>
	<td>Alc/Mun:</td>
	<td><input type="text" name="delegmun" value="$delegmun"></td>
</tr>
<tr>
	<td>Colonia:</td>
	<td><input type="text" name="colonia" value="$colonia"></td>
</tr>
<tr>
	<td>Ciudad:</td>
	<td><input type="text" name="ciudad" value="$ciudad"></td>
</tr>
<tr>
	<td>C.P.:</td>
	<td><input type="text" name="cp" value="$cp"></td>
</tr>
<tr>
	<td>Tel.:</td>
	<td><input type="text" name="tel" value="$tel"></td>
</tr>

<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';nombre.value='';nombre2.value='';apaterno.value='';amaterno.value='';curp.value='';direccion.value='';tel.value='';rfc.value='';colonia.value='';delegmun.value='';ciudad.value='';cp.value='';idpais.value=0;idestado.value=0;porcentaje.value=''">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(nombre.value != '' && apaterno.value!='' && amaterno.value!=''){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$ruta/accionistas.php','contenido','accion=' + accion.value + '&idc=' + ids.value +  '&idic=' + idi.value + '&nombrec=' + nombre.value + '&apaternoc=' + apaterno.value + '&amaternoc=' + amaterno.value + '&nombrec2=' + nombre2.value + '&curp=' + curp.value + '&coloniac=' + colonia.value + '&telc=' + tel.value + '&rfcc=' + rfc.value + '&direccionc=' + direccion.value + '&ciudadc=' + ciudad.value + '&idpaisc=' + idpais.value +  '&idestadoc=' + idestado.value +  '&porcentaje=' + porcentaje.value +  '&delegmunc=' + delegmun.value +  '&cpc=' + cp.value )};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$ruta/accionistas.php','contenido','accion=' + accion.value + '&idc=' + ids.value +  '&idic=' + idi.value + '&nombrec=' + nombre.value + '&apaternoc=' + apaterno.value + '&amaternoc=' + amaterno.value + '&nombrec2=' + nombre2.value + '&curp=' + curp.value + '&coloniac=' + colonia.value + '&telc=' + tel.value + '&rfcc=' + rfc.value + '&direccionc=' + direccion.value + '&ciudadc=' + ciudad.value + '&idpaisc=' + idpais.value +  '&idestadoc=' + idestado.value +  '&porcentaje=' + porcentaje.value  +  '&delegmunc=' + delegmun.value +  '&cpc=' + cp.value)}};" >
		<input type="hidden" name="ids" value="$id">
		<input type="hidden" name="idi" value="$idi">
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
	
	if($idi!="")
	{
		$sql="select * from accionista where idinquilino = $idi ";
		$datos=mysql_query($sql);
		echo "<center><div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
//		echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Segundo Nombre</th><th>A. Paterno</th><th>A. Materno</th><th>Nombre Negocio</th><th>Giro Negocio</th><th>Tel&eacute;fono</th><th>RFC</th><th>Direcci&oacute;n Fiscal</th><th>Acciones</th></tr>";
		echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Segundo Nombre</th><th>A. Paterno</th><th>A. Materno</th><th>Aciones</th></tr>";
		while($row = mysql_fetch_array($datos))
		{
//			echo "<tr><td>" . $row["idinquilino"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["nombre2"] . "</td><td>" . $row["apaterno"] . "</td><td>" . $row["amaterno"] . "</td><td>" . $row["nombrenegocio"] . "</td><td>" . $row["gironegocio"] . "</td><td>" . $row["tel"] . "</td><td>" . $row["rfc"] . "</td><td>" . $row["direccionf"] . "</td><td>";
			$html = "<tr><td>" . $row["idaccionista"] . "</td><td>" . $row["nombreac1"] . "</td><td>" . $row["nombreac2"] . "</td><td>" . $row["apaternoac"] . "</td><td>" . $row["amaterno"] . "</td><td>" . $row["porcentaje"] . "</td><td>";
			$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/accionistas.php','contenido','accion=1&idc=" .  $row["idaccionista"]  . "&idic=$idi' )}\" $txtborrar>";
			$html .= "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$ruta/accionistas.php','contenido','accion=2&idc=" .  $row["idaccionista"]  . "' )\" $txteditar>";
			$html .= "</td></tr>";
			echo CambiaAcentosaHTML($html);
		}
		echo "</table></div></center>";
	}
	else
	{
		echo "Aun no se ha registrado el inquilino, debe guardarlo para poder asignar accionistas";
	
	}




}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>