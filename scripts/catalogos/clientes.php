<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
/*
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$nombre=@$_GET['nombre'];
$apaterno=@$_GET['apaterno'];
$amaterno=@$_GET['amaterno'];
$telparticular=@$_GET['telparticular'];
$teloficina=@$_GET['teloficina'];
$telmovil=@$_GET['telmovil'];
$telotros=@$_GET['telotros'];
$direccion=@$_GET['direccion'];
$email=@$_GET['email'];
$denominacionf=@$_GET['denominacionf'];
$rfc=@$_GET['rfc'];
$direccionf=@$_GET['direccionf'];
$notas=@$_GET['notas'];
$pagina=@$_GET['pagina'];
*/
$accion = @$_POST["accion"];
$id=@$_POST["id"];
$nombre=@$_POST['nombre'];
$apaterno=@$_POST['apaterno'];
$amaterno=@$_POST['amaterno'];
$telparticular=@$_POST['telparticular'];
$teloficina=@$_POST['teloficina'];
$telmovil=@$_POST['telmovil'];
$telotros=@$_POST['telotros'];
$direccion=@$_POST['direccion'];
$email=@$_POST['email'];
$denominacionf=@$_POST['denominacionf'];
$rfc=@$_POST['rfc'];
$direccionf=@$_POST['direccionf'];
$notas=@$_POST['notas'];
$pagina=@$_POST['pagina'];



$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$idusuario = $misesion->usuario;
	$sql="select * from submodulo where archivo ='clientes.php'";
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

		$sql="insert into directorio (idusuario,nombre,apaterno,amaterno,telparticular,teloficina,telmovil,telotros,direccion,email,denominacionf,rfc,direccionf,notas,pagina) values ( $idusuario,'$nombre','$apaterno','$amaterno','$telparticular','$teloficina','$telmovil','$telotros','$direccion','$email','$denominacionf','$rfc','$direccionf','$notas','$pagina')";
		//echo "<br>Agrego";
		
		$nombre="";
		$apaterno="";
		$amaterno="";
		$telparticular="";
		$teloficina="";
		$telmovil="";
		$telotros="";
		$direccion="";
		$email="";
		$denominacionf="";
		$rfc="";
		$direccionf="";
		$notas="";
		$pagina="";
		
				

		break;

	case "1": //Borro

		$sql="delete from directorio where iddirectorio=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update directorio set nombre='$nombre',apaterno='$apaterno',amaterno='$amaterno',telparticular='$telparticular', teloficina='$teloficina', telmovil='$telmovil', telotros='$telotros',direccion='$direccion',email='$email',denominacionf='$denominacionf',rfc='$rfc', direccionf='$direccionf' ,notas='$notas', pagina='$pagina' where iddirectorio=$id";
		///echo "<br>Actualizo";
		$nombre="";
		$apaterno="";
		$amaterno="";
		$telparticular="";
		$teloficina="";
		$telmovil="";
		$telotros="";
		$direccion="";
		$email="";
		$denominacionf="";
		$rfc="";
		$direccionf="";		
		$notas="";
		$pagina="";
	}
	
	//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{

		$operacion = mysql_query($sql);

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
		$sql="select * from directorio where iddirectorio = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"]);
			$apaterno=CambiaAcentosaHTML($row["apaterno"]);
			$amaterno=CambiaAcentosaHTML($row["amaterno"]);
			$telparticular=CambiaAcentosaHTML($row["telparticular"]);
			$teloficina=CambiaAcentosaHTML($row["teloficina"]);
			$telmovil=CambiaAcentosaHTML($row["telmovil"]);
			$telotros=CambiaAcentosaHTML($row['telotros']);
			$direccion=CambiaAcentosaHTML($row['direccion']);
			$email=CambiaAcentosaHTML($row["email"]);
			$denominacionf=CambiaAcentosaHTML($row["denominacionf"]);
			$rfc=CambiaAcentosaHTML($row["rfc"]);			
			$direccionf=CambiaAcentosaHTML($row['direccionf']);
			$notas=CambiaAcentosaHTML($row['notas']);
			$pagina=$row['pagina'];
		}



	}
	else
	{
		$accion="0";
	}



//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Directorio</h1>
<form>
<table border="0">
<tr>
	<td>Nombre:</td><td>A.Paterno:</td><td>A. Materno:</td>
</tr>
<tr>
	<td><input type="text" name="nombre" value="$nombre"></td>
	<td><input type="text" name="apaterno" value="$apaterno"></td>
	<td><input type="text" name="amaterno" value="$amaterno"></td>
</tr>
<tr>
	<td colspan="3" height="10"></td>
</tr>
<tr>
	<td>Tel. particular:</td><td>Tel. Oficina:</td><td>Tel. Movil:</td>
</tr>
<tr>
	<td><input type="text" name="telparticular" value="$telparticular"></td>
	<td><input type="text" name="teloficina" value="$teloficina"></td>
	<td><input type="text" name="telmovil" value="$telmovil"></td>
</tr>
<tr>
	<td colspan="3" height="10"></td>
</tr>
<tr>
	<td>Otro Tel.:</td><td>e-mail:</td><td>Pagina Web:</td>
</tr>
<tr>
	<td><input type="text" name="telotros" value="$telotros"></td>
	<td><input type="text" name="email" value="$email"></td>
	<td><input type="text" name="pagina" value="$pagina"></td>
</tr>
<tr>
	<td colspan="3" height="10"></td>
</tr>
<tr>
	<td colspan="3">Direcci&oacute;n:</td>
</tr>
<tr>
	<td colspan="3"><textarea rows="3" cols="53" name="direccion">$direccion</textarea></td>
</tr>
<tr>
	<td colspan="3" height="10"></td>
</tr>
<tr>
	<td>Nombre fiscal:</td>
	<td colspan="2"><input type="text" name="denominacionf" value="$denominacionf" size="45"></td>
</tr>
<tr>
	<td>RFC:</td>
	<td colspan="2"><input type="text" name="rfc" value="$rfc"></td>
</tr>
<tr>
	<td>Direccion Fiscal:</td>
	<td colspan="2"><input type="button" onClick="addArea2('direccionf','notas');" value="Ver editor"><input type="button" onClick="removeArea2('direccionf','notas');" value="Quitar editor"><br><textarea rows="3" cols="34" name="direccionf" id="direccionf">$direccionf</textarea></td>
</tr>
<tr>
	<td>Notas:</td>
	<td colspan="2"><textarea rows="3" cols="34" name="notas" id="notas">$notas</textarea></td>
</tr>
<tr>
	<td colspan="3" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';nombre.value='';apaterno.value='';amaterno.value='';telparticular.value='';teloficina.value='';telmovil.value='';telotros.value='';direccion.value='';email.value='';denominacionf.value='';rfc.value='';direccionf.value='';notas.value=''; pagina.value='';">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(nombre.value!='' && apaterno.value!='' && amaterno.value!=''){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion_new('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&telparticular=' + telparticular.value + '&teloficina=' + teloficina.value + '&telmovil=' + telmovil.value +  '&telotros=' + telotros.value + '&direccion=' + direccion.value + '&email=' + email.value + '&denominacionf=' + denominacionf.value +  '&rfc=' + rfc.value + '&direccionf=' + direccionf.value + '&notas=' + notas.value + '&pagina=' + pagina.value,'POST')};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion_new('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&telparticular=' + telparticular.value + '&teloficina=' + teloficina.value + '&telmovil=' + telmovil.value +  '&telotros=' + telotros.value + '&direccion=' + direccion.value + '&email=' + email.value + '&denominacionf=' + denominacionf.value +  '&rfc=' + rfc.value + '&direccionf=' + direccionf.value  + '&notas=' + notas.value + '&pagina=' + pagina.value,'POST')}};" >
		<input type="hidden" name="ids" value="$id">
		<input type="hidden" name="accion" value="$accion">
		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">

	</td>
</tr>
</table>
</form>

<form>
<b>Nombre </b><input type="text" name="nombreb" value="" onKeyUp="cargarSeccion('$ruta/listadirectorio.php', 'listadirectorio', 'patron=' + this.value)">

	<div id="listadirectorio" class="scroll">
	
	</div>

</center>

formulario1;

	//echo CambiaAcentosaHTML($html);
/*	
	$sql="select * from directorio ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
//	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>A. Paterno</th><th>A. Materno</th><th>Tel. partiular</th><th>Tel. oficina</th><th>Tel. movil</th><th>Otro tel.</th><th>Direci&oacute;n</th><th>email</th><th>Nombre Fiscal</th><th>RFC</th><th>Direcci&oacute;n Fiscal</th><th>Acciones</th></tr>";
	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>A. Paterno</th><th>A. Materno</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
//		echo "<tr><td>" . $row["iddirectorio"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["apaterno"] . "</td><td>" . $row["amaterno"] . "</td><td>" . $row["telparticular"] . "</td><td>" . $row["teloficina"] . "</td><td>" . $row["telmovil"] . "</td><td>" . $row["telotros"] . "</td><td>" . $row["direccion"] . "</td><td>" . $row["email"] . "</td><td>" . $row["denominacionf"] . "</td><td>" . $row["rfc"] . "</td><td>" . $row["direccionf"] . "</td><td>";
		$html = "<tr><td>" . $row["iddirectorio"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["apaterno"] . "</td><td>" . $row["amaterno"] . "</td><td>";		
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["iddirectorio"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["iddirectorio"]  . "' )\" $txteditar>";
		$html .= "</td></tr>";
		echo CambiaAcentosaHTML($html);
	}
	echo "</table></div>";


*/


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



/*


<tr>
	<td>Nombre:</td>
	<td><input type="text" name="nombre" value="$nombre"></td>
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
	<td>Tel. partiular:</td>
	<td><input type="text" name="telparticular" value="$telparticular"></td>
</tr>
<tr>
	<td>Tel. Oficina:</td>
	<td><input type="text" name="teloficina" value="$teloficina"></td>
</tr>
<tr>
	<td>Tel. Movil:</td>
	<td><input type="text" name="telmovil" value="$telmovil"></td>
</tr>
<tr>
	<td>Otro Tel.:</td>
	<td><input type="text" name="telotros" value="$telotros"></td>
</tr>
<tr>
	<td>Direcci&oacute;n:</td>
	<td><textarea rows="3" cols="20" name="direccion">$direccion</textarea></td>
</tr>
<tr>
	<td>e-mail:</td>
	<td><input type="text" name="email" value="$email"></td>
</tr>
*/
?>