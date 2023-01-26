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
$fechanacimiento=@$_GET['fechanacimiento'];
$curp=@$_GET['curp'];
$nombrenegocio=@$_GET['nombrenegocio'];
//$gironegocio=@$_GET['gironegocio'];
$tel=@$_GET['tel'];
$rfc=@$_GET['rfc'];
$direccionf=@$_GET['direccionf'];
$email=@$_GET['email'];
$email1=@$_GET['email1'];
$email2=@$_GET['email2'];
$delegacion = @$_GET["delegacion"];
$colonia = @$_GET["colonia"];
$cp= @$_GET["cp"];
$idestado = @$_GET["idestado"];
$noexterior= @$_GET["noexterior"];
$nointerior= @$_GET["nointerior"];
$localidad= @$_GET["localidad"];
$referencia= @$_GET["referencia"];
$pais= @$_GET["pais"];

$callei=@$_GET["callei"];	
$cpi=@$_GET["cpi"];	
$delmuni=@$_GET["delmuni"];	
$idestadoi=@$_GET["idestadoi"];
$coloniai=@$_GET["coloniai"];	

$nacionalidad=@$_GET['idnacionalidad'];
$actividad=@$_GET['idactividadeconomica'];
$giro=@$_GET['idgirocomercial'];

$idc_usocfdi=@$_GET['idc_usocfdi'];
$tipofactura=@$_GET['tipofactura'];

$isDisabled = "";

if($pais==null){$pais="MEXICO";};
if($idestadoi==null){$idestadoi="9";};
if($idestado==null){$idestado="9";};
if($nacionalidad==null){$nacionalidad=110;};
if($idc_usocfdi==null){$idc_usocfdi=3;};

$aud = new auditoria;
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
	$verifRFC = "";

	//Segun la accin que se tom, se proceder a editar el sql
	switch($accion)
	{
	case "0": // Agrego

		$rfcSql = "select rfc from inquilino where rfc='$rfc'";
		$vacio=mysql_query($rfcSql);
		$filas=mysql_num_rows($vacio);
		
		if($tel!="" && $email!="" && $filas==0){
		//$sql="insert into inquilino (nombre,nombre2,apaterno,amaterno,nombrenegocio,gironegocio,tel,rfc,direccionf,delegacion,colonia,cp,idestado,noexteriori,nointeriori,localidadi,referenciai,paisi,callei,delmuni,coloniai, cpi, idestadoi,email,email1,email2, idactividadeconomica,idgirocomercial,idnacionalidad) values ('$nombre','$nombre2','$apaterno','$amaterno','$nombrenegocio','$gironegocio','$tel','$rfc','$direccionf','$delegacion','$colonia','$cp',$idestado,'$noexterior','$nointerior','$localidad','$referencia','$pais','$callei','$delmuni','$coloniai', '$cpi', $idestadoi,'$email','$email1','$email2', $actividad,$giro,$nacionalidad)";
		$sql="insert into inquilino (nombre,nombre2,apaterno,amaterno,nombrenegocio,tel,rfc,direccionf,delegacion,colonia,cp,idestado,noexteriori,nointeriori,localidadi,referenciai,paisi,callei,delmuni,coloniai, cpi, idestadoi,email,email1,email2, idactividadeconomica,idgirocomercial,idnacionalidad, curp, fechanacimiento,idc_usocfdi, tipofactura) values ('$nombre','$nombre2','$apaterno','$amaterno','$nombrenegocio','$tel','$rfc','$direccionf','$delegacion','$colonia','$cp',$idestado,'$noexterior','$nointerior','$localidad','$referencia','$pais','$callei','$delmuni','$coloniai', '$cpi', $idestadoi,'$email','$email1','$email2', $actividad,$giro,$nacionalidad,'$curp','$fechanacimiento',$idc_usocfdi,'$tipofactura')";
		//echo "<br>Agrego";
		$nombre="";
		$nombre2="";
		$apaterno="";
		$amaterno="";
		$curp="";
		$fechanacimiento="";
		$nombrenegocio="";
		//$gironegocio="";
		$tel="";
		$rfc="";
		$direccionf="";
		$email="";
		$email1="";
		$email2="";
		$delegacion = "";
		$colonia = "";
		$cp= "";
		$idestado = "9";	
		$noexterior="";	
		$nointerior="";	
		$localidad="";	
		$referencia="";	
		
		$pais="MEXICO";	

		$callei= "";	
		$cpi="";	
		$delmuni="";	
		$idestadoi="9";	
		$coloniai="";

		$idc_usocfdi=3;
		$tipofactura="PUE";

		$nacionalidad=110;
		$actividad="";
		$giro="";
		$verifRFC = "";
		}else{
			if($tel=="" || $email==""){
				echo "<center><div style='background-color:red;width:400px;color:white;font-size:18px;'>Debe llenar los campos de tel�fono y correo electr�nico</div></center>";	
			}
			if($filas!=0){
				$verifRFC = "RFC duplicado";
			}
		}
		break;

	case "1": //Borro

		$sql="delete from inquilino where idinquilino=$id";
		//echo "<br>Borro";
		$id="";
		break;

	// Cambio 10/08/2021
	// Se agrega un caso que hace lo mismo del caso 2 o accion=2 para poder modificar s
	case "6": // Ver
		$isDisabled = "disabled";
		$accion = "2";
		break;
	// Fin Cambio 10/08/2021

	case "3": //Actualizo

		//echo $sql = "update inquilino set nombre='$nombre',nombre2='$nombre2' ,apaterno='$apaterno',amaterno='$amaterno',nombrenegocio='$nombrenegocio', gironegocio='$gironegocio', tel='$tel', rfc='$rfc', direccionf='$direccionf', email='$email', email1='$email1', email2='$email2', delegacion = '$delegacion', colonia='$colonia', cp='$cp', idestado=$idestado ,noexteriori ='$noexterior', nointeriori= '$nointerior',localidadi ='$localidad',referenciai= '$referencia',paisi='$pais' ,callei='$callei',delmuni='$delmuni',coloniai='$coloniai', cpi ='$cpi',idestadoi= $idestadoi,idactividadeconomica=$actividad,idgirocomercial=$giro,idnacionalidad=$nacionalidad where idinquilino=$id";
		$sql = "update inquilino set nombre='$nombre',nombre2='$nombre2' ,apaterno='$apaterno',amaterno='$amaterno',nombrenegocio='$nombrenegocio',  tel='$tel', rfc='$rfc', direccionf='$direccionf', email='$email', email1='$email1', email2='$email2', delegacion = '$delegacion', colonia='$colonia', cp='$cp', idestado=$idestado ,noexteriori ='$noexterior', nointeriori= '$nointerior',localidadi ='$localidad',referenciai= '$referencia',paisi='$pais' ,callei='$callei',delmuni='$delmuni',coloniai='$coloniai', cpi ='$cpi',idestadoi= $idestadoi,idactividadeconomica=$actividad,idgirocomercial=$giro,idnacionalidad=$nacionalidad,curp='$curp',fechanacimiento='$fechanacimiento', idc_usocfdi=$idc_usocfdi, tipofactura='$tipofactura' where idinquilino=$id";
		///echo "<br>Actualizo";
		$nombre="";
		$nombre2="";
		$apaterno="";
		$amaterno="";
		$curp="";
		$fechanacimiento="";
		$nombrenegocio="";
		//$gironegocio="";
		$tel='';
		$rfc="";
		$direccionf="";
		$email="";
		$email1="";
		$email2="";
		$delegacion = "";
		$colonia = "";
		$cp= "";
		$idestado = "9";	

		$callei= "";	
		$cpi="";	
		$delmuni="";	
		$idestadoi="9";	
		$coloniai="";			
		
		
		$noexterior="";	
		$nointerior="";	
		$localidad="";	
		$referencia="";	
		
		$nacionalidad=110;
		$actividad="";
		$giro="";

		$idc_usocfdi=3;
		$tipofactura="PUE";
		
		$pais="MEXICO";
	// Cambio 28/07/2021
	// Se agregó un break, tambien se agregó un caso para cambiar el RFC de un inquilino
	// a uno generico
	break;

	case "4": // Coloco RFC Genérico
		$sql = "UPDATE inquilino SET rfc = 'XAXX010101000' WHERE idinquilino = $id";
		break;
	// Fin Cambio 28/07/2021

	}

	//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{
		
		$operacion = mysql_query($sql);
		
		if(substr($sql,0,6)=="insert")
		{
			$aud->tabla="inquilino";
			$aud->idtabla=mysql_insert_id();
			$aud->accion = 1;
			$aud->usuario =$misesion->usuario;
			$aud->crearregistro();
		
		}
		elseif(substr($sql,0,6)=="update")
		{
			$aud->tabla="inquilino";
			$aud->idtabla=$id;
			$aud->accion = 2;
			$aud->usuario =$misesion->usuario;
			$aud->crearregistro();
		
		
		}		

	}
	//Preparo las variables para los botnes
	$boton1="Limpiar";
	$boton2="Agregar";

	
	$estilo1="style='display:inline'";
	$estilo2="style='display:none'";	

	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la accin a realizar para la siguiente presin del botn agregar
	//en su defecto, sera accn agregar
	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from inquilino where idinquilino = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"]);
			$nombre2=CambiaAcentosaHTML($row["nombre2"]);
			$apaterno=CambiaAcentosaHTML($row["apaterno"]);
			$amaterno=CambiaAcentosaHTML($row["amaterno"]);
			$curp=CambiaAcentosaHTML($row["curp"]);
			$fechanacimiento=CambiaAcentosaHTML($row["fechanacimiento"]);
			$nombrenegocio=CambiaAcentosaHTML($row["nombrenegocio"]);
			//$gironegocio=CambiaAcentosaHTML($row["gironegocio"]);
			$tel=CambiaAcentosaHTML($row["tel"]);
			$rfc=CambiaAcentosaHTML($row['rfc']);
			$direccionf=CambiaAcentosaHTML($row['direccionf']);
			$email=CambiaAcentosaHTML($row['email']);
			$email1=CambiaAcentosaHTML($row['email1']);
			$email2=CambiaAcentosaHTML($row['email2']);
			$delegacion = CambiaAcentosaHTML($row["delegacion"]);
			$colonia = CambiaAcentosaHTML($row["colonia"]);
			$cp= CambiaAcentosaHTML($row["cp"]);
			$idestado = $row["idestado"];
			
			$callei= $row["callei"];
			$cpi=$row["cpi"];
			$delmuni=$row["delmuni"];
			$idestadoi=$row["idestadoi"];
			$coloniai=$row["coloniai"];

			
			$noexterior=$row["noexteriori"];
			$nointerior=$row["nointeriori"];
			$localidad=$row["localidadi"];
			$referencia=$row["referenciai"];
			$pais= $row["paisi"];
			
			$nacionalidad=$row['idnacionalidad'];
			$actividad=$row['idactividadeconomica'];
			$giro=$row['idgirocomercial'];

			$idc_usocfdi=$row['idc_usocfdi'];
			$tipofactura=$row['tipofactura'];

			
			if(strlen($rfc)==12)
			{
				$estilo1="style='display:none'";
				$estilo2="style='display:inline'";
			}
			else
			{
				$estilo1="style='display:inline'";
				$estilo2="style='display:none'";
			}
				

		}



	}
	else
	{
		$accion="0";
	}
	$hatilitar = "disabled";
	if(strlen($rfc)==12)
	{
		$hatilitar = "";
	}


	$sql="select * from estado";

	$estadoselect = "<select name=\"idestado\" $isDisabled><option value=\"0\">Seleccione uno de la lista</option>";
	$estadoselecti = "<select name=\"idestadoi\" $isDisabled><option value=\"0\">Seleccione uno de la lista</option>";
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
		
		$seleccionopt="";
		//if ($idduenio>0)
		if ($idestadoi==$row["idestado"])
		{
			$seleccionopt=" SELECTED ";
		}		
		$estadoselecti .= "<option value=\"" . $row["idestado"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["estado"]) . "</option>";

	}	
	$estadoselect .="</select>";
	$estadoselecti .="</select>";
	

	$sql="select * from actividadeconomica";

	$actividadselect = "<select name=\"idactividadeconomica\" $isDisabled>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		//if ($idduenio>0)
		if ($actividad==$row["idactividadeconomica"])
		{
			$seleccionopt=" SELECTED ";
		}
		$actividadselect .= "<option value=\"" . $row["idactividadeconomica"] . "\" $seleccionopt title=\"" . CambiaAcentosaHTML($row["actividadeconomica"]  ) . "\">" . CambiaAcentosaHTML(substr($row["actividadeconomica"],0,40)  ) . "</option>";

	}
	$actividadselect .="</select>";		
	

	$sql="select * from girocomercial";

	$giroselect = "<select name=\"idgirocomercial\" $isDisabled>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		//if ($idduenio>0)
		if ($giro==$row["idgirocomercial"])
		{
			$seleccionopt=" SELECTED ";
		}
		$giroselect .= "<option value=\"" . $row["idgirocomercial"] . "\" $seleccionopt title=\"" . CambiaAcentosaHTML($row["girocomercial"]  ) . "\">" . CambiaAcentosaHTML(substr($row["girocomercial"],0,40)  ) . "</option>";

	}
	$giroselect .="</select>";	
	
	$sql="select * from pais";

	$nacionalidadselect = "<select name=\"idnacionalidad\" $isDisabled><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		//if ($idduenio>0)
		if ($nacionalidad==$row["idpais"])
		{
			$seleccionopt=" SELECTED ";
		}
		$nacionalidadselect .= "<option value=\"" . $row["idpais"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["pais"]  ) . "</option>";

	}
	$nacionalidadselect .="</select>";		
	
	
	$sql="select * from c_usocfdi";
	$usocfdiSelect = "<select name=\"idc_usocfdi\" id=\"idc_usocfdi\" $isDisabled>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion)){
		$seleccionopt="";
		if ($idc_usocfdi==$row["idc_usocfdi"]){
			$seleccionopt=" SELECTED ";
		}
		$usocfdiSelect .= "<option value=\"" . $row["idc_usocfdi"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["descripcionucfdi"]  ) . "</option>";
	}
	$usocfdiSelect .="</select>";	

	$tipoFacturaArray = array("PUE","PPD");
	$tipofacturaSelect = "<select name=\"tipofactura\" id=\"tipofactura\" disabled>";
	foreach ($tipoFacturaArray as $key => $value){
		$seleccionopt="";
		if ($tipofactura==$value){
			$seleccionopt=" SELECTED ";
		}
		$tipofacturaSelect .= "<option value=\"$value\" $seleccionopt> $value </option>";
	}
	$tipofacturaSelect .="</select>";
	
	
	
	$btnaud="";
	if($aud->id!=0)
	{
		$btnaud = "<input type='button' value='Imprimir movimiento' onClick=\"window.open('$ruta/impresiontransaccion.php?id=$aud->id&seccion=Inquilino');\"";
	
	}


//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Inquilinos</h1>
$btnaud
<form>
<table border="1">
<tr>
	<td>Nombre / Empresa(completo):</td>
	<td><input type="text" name="nombre" value="$nombre" $isDisabled></td>
</tr>
<tr>
	<td>Segundo Nombre:</td>
	<td><input type="text" name="nombre2" value="$nombre2" $isDisabled></td>
</tr>
<tr>
	<td>A. Paterno:</td>
	<td><input type="text" name="apaterno" value="$apaterno" $isDisabled></td>
</tr>
<tr>
	<td>A. Materno:</td>
	<td><input type="text" name="amaterno" value="$amaterno" $isDisabled></td>
</tr>

<tr>

	<td>Fecha de nacimiento o Fecha de Constitucion: (aaaa-mm-dd)</td>

	<td><input type="text" name="fechanacimiento" id="fechanacimiento" value="$fechanacimiento" $isDisabled></td>

</tr>

<tr>

	<td>CURP:</td>

	<td><input type="text" name="curp" id="curp" value="$curp" $isDisabled></td>

</tr>

<tr>
	<td>RFC:</td>
	<td>
		<input type="text" name="rfc" id="rfc" value="$rfc" $isDisabled onChange="aus=this.value;if(aus.length==12){document.getElementById('accionistas').disabled=false;document.getElementById('girocom').style.display='inline';document.getElementById('actividadeco').style.display='none';}else{document.getElementById('accionistas').disabled=true;document.getElementById('girocom').style.display='none';document.getElementById('actividadeco').style.display='inline';};">		
		<input type="button" id="accionistas" value="Accionistas" $isDisabled onClick="cargarSeccion('$ruta/accionistas.php','contenido','idic=$id') ;" $hatilitar >
		<font color="#F6210A">$verifRFC</font>

	</td>
</tr>
<tr>
	<td>Nacionalidad:</td>
	<td>$nacionalidadselect</td>
</tr>
<tr >
	<td>Nombre Negocio:</td>
	<td><input type="text" name="nombernegocio" value="$nombrenegocio" $isDisabled></td>
</tr>
<tr>
	<td colspan="2">
	<div id="actividadeco" $estilo1>
	<table border="0">
	<tr>
	<td>Actividad Economica:</td>
	<td>$actividadselect</td>
	</tr>
	</table>
		
	</div>
	<div id="girocom" $estilo2>
	<table border="0">
	<tr id="girocom" >
	<td>Giro Negocio:</td>
	<td>$giroselect</td>
	</tr>
	</table>
		
	</div>	
	</td>

</tr>


<!--
<tr >
	<td>Giro Negocio:</td>
	<td><input type="text" name="gironegocio" value="" $isDisabled></td>
</tr>
-->

<tr>
	<td>Correo electr&oacute;nico 1:</td>
	<td><input type="text" name="email" value="$email" $isDisabled></td>
</tr>
<tr>
	<td>Correo electr&oacute;nico 2:</td>
	<td><input type="text" name="email1" value="$email1" $isDisabled></td>
</tr>
<tr>
	<td>Correo electr&oacute;nico 3:</td>
	<td><input type="text" name="email2" value="$email2" $isDisabled></td>
</tr>
<tr>
	<td>Tel&eacute;fono:</td>
	<td><input type="text" name="tel" value="$tel" $isDisabled></td>
</tr>

<tr>
	<td>Direcci&oacute;n:</td>
	<td><textarea name="direccionf" cols='20' rows='3' $isDisabled>$direccionf</textarea></td>

</tr>
<tr>
	<td>Alcald&iacute;a</td>
	<td><input type="text" name="delegacion" value="$delegacion" $isDisabled></td>
</tr>
<tr>
	<td>Colonia:</td>
	<td><input type="text" name="colonia" value="$colonia" $isDisabled></td>
</tr>

<tr>
	<td>C.P.:</td>
	<td><input type="text" name="cp" value="$cp" $isDisabled></td>
</tr>
<tr>
	<td>Estado</td>
	<td>$estadoselect</td>
</tr>

<tr>
	<td>Tipo de Facturacion</td>
	<td>$tipofacturaSelect</td>
</tr>

<tr>
	<td>Uso CFDI</td>
	<td>$usocfdiSelect</td>
</tr>

<tr>
	<td colspan='2' align = 'center'>
		<fieldset><legend>Domicilio fiscal</legend>
		
		<table border='0'>
		<tr>
			<td>Calle:</td>
			<td><input type="text" name="callei" value="$callei" $isDisabled></td>
		</tr>		
		<tr>
			<td>No. Exterior:</td>
			<td><input type="text" name="noexterior" value="$noexterior" $isDisabled></td>
		</tr>
		<tr>
			<td>No. Interior:</td>
			<td><input type="text" name="nointerior" value="$nointerior" $isDisabled></td
		</tr>
		<tr>
			<td>Colonia:</td>
			<td><input type="text" name="coloniai" value="$coloniai" $isDisabled></td
		</tr>	
		<tr>
			<td>C.P.:</td>
			<td><input type="text" name="cpi" value="$cpi" $isDisabled></td
		</tr>
		<tr>
			<td>Alc./Mun.:</td>
			<td><input type="text" name="delmuni" value="$delmuni" $isDisabled></td
		</tr>		
		<tr>
			<td>Localidad:</td>
			<td><input type="text" name="localidad" value="$localidad" $isDisabled></td>
		</tr>
		<tr>
			<td>Referencia:</td>
			<td><input type="text" name="referencia" value="$referencia" $isDisabled></td>
		</tr>
		<tr>
			<td>Pais:</td>
			<td><input type="text" name="pais" value="$pais" $isDisabled></td>
		</tr>
		<tr>
			<td>Estado</td>
			<td>$estadoselecti</td>
		</tr>		
		</table>
		</fieldset>
	</td>
</tr>
<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" $isDisabled onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';nombre.value='';nombre2.value='';apaterno.value='';amaterno.value='';nombernegocio.value='';tel.value='';rfc.value='';direccionf.value='';email.value='';email1.value='';email2.value='';document.getElementById('accionistas').disabled=true; delegacion.value=''; colonia.value=''; cp.value=''; idestado.value=9; noexterior.value='';  nointerior.value='';  localidad.value='';  referencia.value='';  pais.value='MEXICO';callei.value='';delmuni.value='';coloniai.value=''; cpi.value='';idestadoi.value=9;idactividadeconomica.value = 1; idgirocomercial.value=1;idnacionalidad.value=110;curp.value=''; fechanacimiento.value='';idc_usocfdi.value=3;tipofactura.value='PUE';">&nbsp;&nbsp;&nbsp;&nbsp;

		<input type="button" value="$boton2" $isDisabled name="agregar" onClick="ok=0;aus = rfc.value;fech = fechanacimiento.value; if(aus.length==12){if(nombre.value != '' && fechanacimiento.value!='' && fech.length==10  ){ ok=1}}else{if(nombre.value != '' && apaterno.value!='' && amaterno.value!='' && curp.value!='' && fechanacimiento.value!='' && validarCURP()==true && fech.length==10){ok=1}};   if(ok==1) { if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&nombre2=' + nombre2.value + '&nombrenegocio=' + nombernegocio.value + '&tel=' + tel.value + '&rfc=' + rfc.value + '&direccionf=' + direccionf.value + '&email=' + email.value +  '&email1=' + email1.value + '&email2=' + email2.value + '&delegacion=' + delegacion.value  + '&colonia=' + colonia.value  + '&cp=' + cp.value  + '&idestado=' + idestado.value + '&noexterior=' + noexterior.value  + '&nointerior=' + nointerior.value  + '&localidad=' + localidad.value  + '&referencia=' + referencia.value  + '&pais=' + pais.value + '&callei=' + callei.value  + '&delmuni=' + delmuni.value + '&coloniai=' + coloniai.value + '&cpi=' + cpi.value + '&idestadoi=' + idestadoi.value + '&idactividadeconomica=' + idactividadeconomica.value + '&idgirocomercial=' + idgirocomercial.value + '&idnacionalidad=' + idnacionalidad.value + '&curp=' + document.getElementById('curp').value + '&fechanacimiento=' + document.getElementById('fechanacimiento').value + '&idc_usocfdi=' + idc_usocfdi.value + '&tipofactura=' + tipofactura.value)};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&nombre2=' + nombre2.value + '&nombrenegocio=' + nombernegocio.value + '&tel=' + tel.value + '&rfc=' + rfc.value + '&direccionf=' + direccionf.value+ '&email=' + email.value +  '&email1=' + email1.value + '&email2=' + email2.value + '&delegacion=' + delegacion.value  + '&colonia=' + colonia.value  + '&cp=' + cp.value  + '&idestado=' + idestado.value + '&noexterior=' + noexterior.value  + '&nointerior=' + nointerior.value  + '&localidad=' + localidad.value  + '&referencia=' + referencia.value  + '&pais=' + pais.value + '&callei=' + callei.value  + '&delmuni=' + delmuni.value + '&coloniai=' + coloniai.value + '&cpi=' + cpi.value + '&idestadoi=' + idestadoi.value + '&idactividadeconomica=' + idactividadeconomica.value + '&idgirocomercial=' + idgirocomercial.value + '&idnacionalidad=' + idnacionalidad.value + '&curp=' + document.getElementById('curp').value + '&fechanacimiento=' + document.getElementById('fechanacimiento').value + '&idc_usocfdi=' + idc_usocfdi.value  + '&tipofactura=' + tipofactura.value)}};" >
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

//$pendiente="window.open( '$ruta/pendientec.php?contrato=" . $row["elidcontrato"] . "');";
	//echo CambiaAcentosaHTML($html);
	
	$sql="select * from inquilino ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
//	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Segundo Nombre</th><th>A. Paterno</th><th>A. Materno</th><th>Nombre Negocio</th><th>Giro Negocio</th><th>Tel&eacute;fono</th><th>RFC</th><th>Direcci&oacute;n Fiscal</th><th>Acciones</th></tr>";
	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Segundo Nombre</th><th>A. Paterno</th><th>A. Materno</th><th>RFC</th><th>Acciones</th></tr>";
	// Cambio 28/07/2021
	// Se agregó una nueva variable para poder almacenar el id del usuario que actualmente esta
	// firmado
	$usuarios = $_SESSION['usuario'];
	// Fin Cambio 28/07/2021
	while($row = mysql_fetch_array($datos))
	{
//		echo "<tr><td>" . $row["idinquilino"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["nombre2"] . "</td><td>" . $row["apaterno"] . "</td><td>" . $row["amaterno"] . "</td><td>" . $row["nombrenegocio"] . "</td><td>" . $row["gironegocio"] . "</td><td>" . $row["tel"] . "</td><td>" . $row["rfc"] . "</td><td>" . $row["direccionf"] . "</td><td>";
		$html = "<tr><td>" . $row["idinquilino"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["nombre2"] . "</td><td>" . $row["apaterno"] . "</td><td>" . $row["amaterno"] . "</td><td>" . $row["rfc"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idinquilino"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idinquilino"]  . "' )\" $txteditar>";
		$html .= "<input type=\"button\" value=\"Ver\" onClick=\"cargarSeccion('$dirscript','contenido','accion=6&id=" .  $row["idinquilino"]  . "' )\" $txteditar>";
		$html .= "<input type='button' value='Movimientos' onClick=\"window.open('$ruta/movauditoria.php?id=" .  $row["idinquilino"]  . "&tabla=inquilino');\">";
		// Cambio 28/07/21
		// Se agrega un nuevo boton que solo será visible para Noemi, Miguel
		if ($usuarios == 19 || $usuarios == 1 || $usuarios == 3) {
			$html .= "<input type=\"button\" value=\"RFC Gen&eacute;rico\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea cambiar el RFC de &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=4&id=" .  $row["idinquilino"]  . "' )}\">";
		}
		// Fin Cambio 28/07/2021
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