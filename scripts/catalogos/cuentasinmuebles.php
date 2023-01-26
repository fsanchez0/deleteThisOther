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
	//Cambio 28/06/2021
	// Se agregó código javascript en el atributo onchange para que se muestren 
	// u oculten los campos de pagos condomino
	$tipocuentaiselect = "<select name=\"idtipocuentai\" onchange=\"var mostrar, ocultar;if (this.value == 5){mostrar = 'condominio';}else{ocultar = 'condominio';}var elmOcultar = document.getElementsByClassName(ocultar);var elmMostrar = document.getElementsByClassName(mostrar);for(i=0;i<elmOcultar.length;i++) elmOcultar[i].style.display='none';for(i=0;i<elmMostrar.length;i++) elmMostrar[i].style.display='table-row';\"><option value=\"0\">Seleccione uno de la lista</option>";
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
	//Cambio 28/06/2021
	// Las próximas 17 lineas de código se encargar de prepara la información que se va  
	// a cargar en los campos para poder modificar un Pago condominio
	//echo CambiaAcentosaHTML($html);
	$displayCondominio = $idtipocuentai==5|$idtipocuentai=="5"?"table-row":"none";
	$titular = $rfc = $banco = $clabe = $ref = $admin = $correo = $tel = $dpago = $importe = "";
	if ($idtipocuentai==5){
		$notaciArray = explode("|",$notaci);
		if(count($notaciArray)>1){
			$titular = explode(": ",$notaciArray[0])[1];
			$rfc = explode(": ",$notaciArray[1])[1];
			$banco = explode(": ",$notaciArray[2])[1];
			$clabe = explode(": ",$notaciArray[3])[1];
			$ref = explode(": ",$notaciArray[4])[1];
			$admin = explode(": ",$notaciArray[5])[1];
			$correo = explode(": ",$notaciArray[6])[1];
			$tel = explode(": ",$notaciArray[7])[1];
			$dpago = explode(": ",$notaciArray[8])[1];
			$importe = explode(": ",$notaciArray[9])[1];
			$notaci = explode(": ",$notaciArray[10])[1];
		}
	}
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
		<!--Cambio 28/06/2021 
			Se agregaron los campos necesarios para poder agregar la información de
			pagos condominio
		-->
		<tr class=\"condominio\" style=\"display:$displayCondominio;\">
			<td valign=\"top\">Titular</td>
			<td>
				<input type=\"text\" id=\"cond_titular\" value=\"$titular\">
			</td>
		</tr>
		<tr class=\"condominio\" style=\"display:$displayCondominio;\">
			<td valign=\"top\">RFC</td>
			<td>
				<input type=\"text\" id=\"cond_rfc\" value=\"$rfc\">
			</td>
		</tr>
		<tr class=\"condominio\" style=\"display:$displayCondominio;\">
			<td valign=\"top\">Banco</td>
			<td>
				<input type=\"text\" id=\"cond_banco\" value=\"$banco\">
			</td>
		</tr>
		<tr class=\"condominio\" style=\"display:$displayCondominio;\">
			<td valign=\"top\">CLABE</td>
			<td>
				<input type=\"text\" id=\"cond_clabe\" value=\"$clabe\">
			</td>
		</tr>
		<tr class=\"condominio\" style=\"display:$displayCondominio;\">
			<td valign=\"top\">Referencia</td>
			<td>
				<input type=\"text\" id=\"cond_ref\" value=\"$ref\">
			</td>
		</tr>
		<tr class=\"condominio\" style=\"display:$displayCondominio;\">
			<td valign=\"top\">Nombre del administrador</td>
			<td>
				<input type=\"text\" id=\"cond_admin\" value=\"$admin\">
			</td>
		</tr>
		<tr class=\"condominio\" style=\"display:$displayCondominio;\">
			<td valign=\"top\">Correo</td>
			<td>
				<input type=\"text\" id=\"cond_correo\" value=\"$correo\">
			</td>
		</tr>
		<tr class=\"condominio\" style=\"display:$displayCondominio;\">
			<td valign=\"top\">Teléfono</td>
			<td>
				<input type=\"text\" id=\"cond_tel\" value=\"$tel\">
			</td>
		</tr>
		<tr class=\"condominio\" style=\"display:$displayCondominio;\">
			<td valign=\"top\">Días de pago</td>
			<td>
				<input type=\"text\" id=\"cond_dpago\" value=\"$dpago\">
			</td>
		</tr>
		<tr class=\"condominio\" style=\"display:$displayCondominio;\">
			<td valign=\"top\">Importe</td>
			<td>
				<input type=\"text\" id=\"cond_imp\" value=\"$importe\">
			</td>
		</tr>
		<!-- Fin Cambio -->
		<tr>
			<td valign=\"top\">Notas</td>
			<td>
				<textarea name=\"notaci\" cols=\"30\" rows=\"2\">$notaci</textarea>
			</td>
		</tr>
		<tr>
			<td colspan = \"2\" align = \"center\">
				<!--Cambio 28/06/2021
					Se agregó código javascript para que se haga la verficación del los campos de 
					pago condominio y tam,bién permite que se guarde la información
				-->
				<input type=\"button\" value=\"$boton2\" onClick=\"if(idtipocuentai.value == 5){if(cond_titular.value != '' &&cond_banco.value != '' &&cond_clabe.value != '' &&cond_ref.value != '' &&cond_admin.value != '' &&cond_correo.value != '' &&cond_tel.value != '' &&cond_dpago.value != '' && cond_imp.value != ''){notaci.value = 'Titular de la cuenta: ' + cond_titular.value +'|RFC: ' + cond_rfc.value +'|Banco: ' + cond_banco.value + '|CLABE: ' + cond_clabe.value + '|Referencia: ' + cond_ref.value + '|Nombre del administrador: '+cond_admin.value + '|Correo: '+cond_correo.value + '|Teléfono: '+cond_tel.value + '|Días de pago: '+cond_dpago.value + '|Importe: '+cond_imp.value+'|Notas: '+notaci.value;}else{alert('Todos los campos de pago condominal deben estar llenos.');return;}/*console.log(notaci.value);*/}if(cuentai.value !=''){cargarSeccion('$ruta/cuentasinmuebles.php','divcuentas','idi=' + ids.value + '&idtipocuentai=' + idtipocuentai.value  + '&notaci=' + notaci.value + '&cuentai=' + cuentai.value + '&accion=' + accioncta.value $envioid);};idtipocuentai.value = 0; cuentai.value=''; notaci.value='';cond_titular.value = cond_rfc.value = cond_banco.value = cond_clabe.value = cond_ref.value = cond_admin.value = cond_correo.value = cond_tel.value = cond_dpago.value = cond_imp.value = '';var elmOcultar = document.getElementsByClassName('condominio');for(i=0;i<elmOcultar.length;i++) elmOcultar[i].style.display='none';\">
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
		//Cambio 28/06/2021
		// Si el tipo de cuenta es igual a 5 se realiza el cambio de | por </br><hr> 
		if($row["idtipocuentai"] == 5 || $row["idtipocuentai"] == '5')
			$html = "<tr><td>" . $row["idcuentainmueble"] . "</td><td>" . $row["tipocuentai"] . "</td><td>" . $row["cuentainmueble"] . "</td><td>" . str_replace('|','</br><hr>',$row["notaci"]) . "</td><td>";
		else
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