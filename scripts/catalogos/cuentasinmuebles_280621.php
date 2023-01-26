<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$idi=@$_GET["idi"]; //idinmueble
$cuentai=@$_GET['cuentai'];
$notaci=@$_GET['notaci'];
$idtipocuentai=@$_GET['idtipocuentai'];


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='inmuebles.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'] ;
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

	$sql="";
	switch($accion)
	{
	case "0": // Agrego

		$sql="insert into cuentainmueble (idinmueble, idtipocuentai, cuentainmueble, notaci) values ($idi, $idtipocuentai,'$cuentai','$notaci')";
		//echo "<br>Agrego";
		$metodopago="";
		$cuentai="";
		$notaci="";
		$idtipocuentai="";
		
		break;

	case "1": //Borro

		 $sql="delete from cuentainmueble where idcuentainmueble=$id";
		//echo "<br>Borro";
		$id="";
		
		break;

	case "3": //Actualizo

		$sql = "update cuentainmueble set idinmueble='$idi', idtipocuentai='$idtipocuentai', cuentainmueble='$cuentai', notaci='$notaci' where idcuentainmueble=$id";
		///echo "<br>Actualizo";
		$metodopago="";
		$cuentai="";
		$notaci="";
		$idtipocuentai="";		
		


	}
	//echo $sql;
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
	$boton1="Limpiar";
	$boton2="Agregar";
	$envioid="";
	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		
		$sql="select * from cuentainmueble where idcuentainmueble = $id ";

		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);

		$cuentai=CambiaAcentosaHTML($row["cuentainmueble"]);
		$notaci=CambiaAcentosaHTML($row["notaci"]);
		$idtipocuentai=$row["idtipocuentai"];
		$envioid=" + '&id=$id'";
		
		
	}
	else
	{
		$accion="0";
	}

	$sql="select * from tipocuentai";

	$tipocuentaiselect = "<select name=\"idtipocuentai\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		
		
		if ($idtipocuentai==$row["idtipocuentai"])
		{
			$seleccionopt=" SELECTED ";
		}
		
		$tipocuentaiselect .= "<option value=\"" . $row["idtipocuentai"] . "\" $seleccionopt>" . $row["tipocuentai"]  . "</option>";

	}
	$tipocuentaiselect .="</select>";	

	//echo CambiaAcentosaHTML($html);
	
$formulario = "<table border = \"0\">
		<tr>
			<td valign=\"top\">Tipo de cuenta</td>
			<td>
				$tipocuentaiselect
			</td>
		</tr>	
		<tr>
			<td valign=\"top\">Cuenta</td>
			<td>
				<input type=\"text\" name=\"cuentai\" value=\"$cuentai\">
			</td>
		</tr>
		<tr>
			<td valign=\"top\">Notas</td>
			<td>
				<textarea name=\"notaci\" cols=\"30\" rows=\"2\">$notaci</textarea>
			</td>
		</tr>
		<tr>
			<td colspan = \"2\" align = \"center\">
 		
				<input type=\"button\" value=\"$boton2\" onClick=\"if(cuentai.value !=''){cargarSeccion('$ruta/cuentasinmuebles.php','divcuentas','idi=' + ids.value + '&idtipocuentai=' + idtipocuentai.value  + '&notaci=' + notaci.value + '&cuentai=' + cuentai.value + '&accion=' + accioncta.value $envioid);};idtipocuentai.value = 0; cuentai.value=''; notaci.value='';\">
				<input type=\"hidden\" name=\"accioncta\" value=\"$accion\">
			</td>
		</tr>		
		<tr>
			<td valign=\"top\" align=\"center\" colspan=\"2\">";
	
	
	
	$sql="select * from cuentainmueble c, tipocuentai i where c.idtipocuentai = i.idtipocuentai and idinmueble = $idi order by i.idtipocuentai";
	$datos=mysql_query($sql);
	$formulario .= "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	$formulario .= "<table border=\"1\"><tr><th>Id</th><th>Tipo</th><th>Cuenta</th><th>Notas</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<tr><td>" . $row["idcuentainmueble"] . "</td><td>" . $row["tipocuentai"] . "</td><td>" . $row["cuentainmueble"] . "</td><td>" . $row["notaci"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/cuentasinmuebles.php','divcuentas','accion=1&idi=$idi&id=" .  $row["idcuentainmueble"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/cuentasinmuebles.php','divcuentas','accion=2&idi=$idi&id=" .  $row["idcuentainmueble"] . "' )\" $txteditar>";
		//echo "<input type=\"hidden\" name=\"id\" value=\"" . $row["idmodulo"] . "\">";
		$html .= "</td></tr>";
		$formulario .= CambiaAcentosaHTML($html);
	}
	$formulario .= "</table></div>";
	
	
	echo $formulario .="			</td>

		</tr>	
		</table>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>