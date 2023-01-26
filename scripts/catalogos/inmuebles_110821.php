<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/auditoriaclass.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$calle=@$_GET["calle"];
$noext=@$_GET["numext"];
$noint=@$_GET["numint"];
$colonia=@$_GET["colonia"];
$delnum=@$_GET["delmun"];
$idestado=@$_GET["idestado"];
$idpais=@$_GET["idpais"];
$cp=@$_GET["cp"];
$descripcion=@$_GET["descripcion"];
$notas=@$_GET["notas"];
$inventario=@$_GET["inventario"];
$esta=@$_GET["estacionamiento"];
$tel=@$_GET["tel"];
$idduenio=@$_GET["duenio"];
$dueniosl=@$_GET["acumulado"];
$predial=@$_GET["predial"];
$idtipoinmueble=@$_GET["idtipoinmueble"];
$idasesor=@$_GET["idasesor"];
$aplicar = @$_GET['aplicar'];
$mts2=@$_GET['mts2'];
$apli=@$_GET['apli'];
$html="";

$aud = new auditoria;
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
	$btnblo="";
	//echo $accion;
	switch($accion)
	{
	case "0": // Agrego

		$sql="insert into inmueble (calle,numeroext,numeroint,colonia,delmun,idestado,idpais,cp,descripcion,notas,inventario,estacionamiento,tel,predial,idtipoinmueble,mts2,idasesor) values ('$calle','$noext','$noint','$colonia','$delnum',$idestado,$idpais,'$cp','$descripcion','$notas','$inventario','$esta','$tel','$predial',$idtipoinmueble,$mts2,$idasesor)";
		$operacion = mysql_query($sql);
		$idi = mysql_insert_id();
		
			$aud->tabla="inmueble";
			$aud->idtabla=$idi;
			$aud->accion = 1;
			$aud->usuario =$misesion->usuario;
			$aud->crearregistro();	
		
		$sqld = "delete from duenioinmueble where idinmueble = $idi";
		$operaciond = mysql_query($sqld);
		$ld = split("[|]",substr($dueniosl,0,-1));

		//echo count($ld);
		$prop = 100 / count($ld);		
		
		$idds="";
		$idn=0;
		foreach($ld as $idd0)
		{
			//eval("$porcentaje = @_GET['P_$idd']");
			$ld0 = split("[*]", $idd0);
			$idd=$ld0[0];
			$fecha =$ld0[1];
			$idds .= $idd . ",";
			$idn +=1;
			$sqld = "insert into duenioinmueble (idinmueble,idduenio,porcentajed,fechacontrato) values ($idi,$idd,$prop,'$fecha')";
			$operaciond = mysql_query($sqld);
		}
		
		$idds=substr($idds,0,-1);
		// investigo las cuentas de pago para agregarlas a todos los dueños
		
		
			$nombre="";
			$porcentaje="";
			$bando="";
			$cuenta="";
			$clabe="";
			$idbanco = "";
			$rfc="";
			$notas="";		
		
		$sqld = "select * from dueniodistribucion where idduenio in ($idds)";
		$operaciond = mysql_query($sqld);
		if(mysql_num_rows($operaciond)>0)
		{//has cuentas que asignar
			//tomo la primer cuenta
			$rowd= mysql_fetch_array($operaciond);
			$nombre=$rowd["nombre"];
			$porcentaje=$rowd["porcentaje"];
			$banco=$rowd["banco"];
			$cuenta=$rowd["cuenta"];
			$clabe=$rowd["clabe"];
			$idbanco = $rowd["idbanco"];
			$rfc=$rowd["rfc"];
			$notas=$rowd["notas"];					
		}
		//integro los datos de la cuenta al dueño que no tenga
		$dueniosctas = preg_split("/[,]/", $idds);
		foreach($dueniosctas as $iddcta)
		{
			//$sqld = "insert into duenioinmueble (idinmueble,idduenio) values ($id,$idd)";
			//echo $p="p_$idd";
			//echo $porcentaje = @$_GET[$p];
			$sqld = "select * from dueniodistribucion where idduenio = $iddcta";
			$operaciond = mysql_query($sqld);
			if(mysql_num_rows($operaciond)==0)
			{
				$sqlcta="insert into dueniodistribucion (idduenio,banco,nombre,rfc,cuenta,clabe,porcentaje,notas,idbanco) values ($iddcta,'$banco','$nombre','$rfc','$cuenta','$clabe',$porcentaje,'$notas','$idbanco')";
				$operacioncta = mysql_query($sqlcta);
			}

		}		
		
		
		
		$sql = "";
		

		//echo "<br>Agrego";
		$id="";
		$calle="";
		$noext="";
		$noint="";
		$colonia="";
		$delnum="";
		$idestado="";
		$idpais="";
		$cp="";
		$descripcion="";
		$notas="";
		$inventario="";
		$esta="";
		$tel="";
		$idduenio="";
		$dueniosl="";
		$mts2="";
		
		if ($apli == "1")
		{
			
			$accion = "2";
			$id=$idi;
			
		}	

		break;

	case "1": //Borro
	
		$sqld = "delete from duenioinmueble where idinmueble = $id";
		$operaciond = mysql_query($sqld);
		 $sql="delete from inmueble where idinmueble=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sqld = "delete from duenioinmueble where idinmueble = $id";
		$operaciond = mysql_query($sqld);
		
		
		
		
		//echo $dueniosl;
		$ld = split("[|]",substr($dueniosl,0,-1));
		
		$prop = 100 / count($ld);	
		foreach($ld as $idd0)
		{
			//$sqld = "insert into duenioinmueble (idinmueble,idduenio) values ($id,$idd)";
			//echo $p="p_$idd";
			//echo $porcentaje = @$_GET[$p];
			
			$ld0 = split("[*]", $idd0);
			$idd=$ld0[0];
			$fecha =$ld0[1];
			
			
			$sqld = "insert into duenioinmueble (idinmueble,idduenio,porcentajed,fechacontrato) values ($id,$idd,$prop,'$fecha')";
			$operaciond = mysql_query($sqld);
		}
	


		$sql = "update inmueble set  calle='$calle', numeroext='$noext', numeroint='$noint', colonia='$colonia', delmun='$delnum', idestado=$idestado, idpais=$idpais, cp='$cp', descripcion='$descripcion', notas='$notas', inventario='$inventario', estacionamiento='$esta', tel='$tel', predial='$predial', idtipoinmueble = $idtipoinmueble, mts2=$mts2, idasesor=$idasesor  where idinmueble=$id";
		///echo "<br>Actualizo";
		//$id="";
		$calle="";
		$noext="";
		$noint="";
		$colonia="";
		$delnum="";
		$idestado="";
		$idpais="";
		$cp="";
		$descripcion="";
		$notas="";
		$inventario="";
		$esta="";
		$tel="";
		$idduenio="";
		$dueniosl="";
		$predial="";
		$idtipoinmueble = "";
		$mts2="";
		$idasesor="";

	}
	
	if($sql!="")
	{
		
		$operacion = mysql_query($sql);
		if(substr($sql,0,6)=="update")
		{
			$aud->tabla="inmueble";
			$aud->idtabla=$id;
			$aud->accion = 2;
			$aud->usuario =$misesion->usuario;
			$aud->crearregistro();
		
		
		}
		$id="";
	}
	$boton1="Limpiar";
	$boton2="Agregar";




	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";

		$sql="select * from inmueble where idinmueble = $id ";

		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$btnblo=" disabled ";
			
			$calle=CambiaAcentosaHTML($row["calle"]);
			$noext=CambiaAcentosaHTML($row["numeroext"]);
			$noint=CambiaAcentosaHTML($row["numeroint"]);
			$colonia=CambiaAcentosaHTML($row["colonia"]);
			$delnum=CambiaAcentosaHTML($row["delmun"]);
			$idestado=$row["idestado"];
			$idpais=$row["idpais"];
			$cp=CambiaAcentosaHTML($row["cp"]);
			$descripcion=CambiaAcentosaHTML($row["descripcion"]);
			$notas=CambiaAcentosaHTML($row["notas"]);
			$inventario=CambiaAcentosaHTML($row["inventario"]);
			$esta=CambiaAcentosaHTML($row["estacionamiento"]);
			$tel=CambiaAcentosaHTML($row["tel"]);
			$predial=CambiaAcentosaHTML($row["predial"]);
			$idtipoinmueble=$row["idtipoinmueble"];
			$mts2=$row["mts2"];
			$asesor=$row["idasesor"];
			//$idduenio=CambiaAcentosaHTML($row["idduenio"]);
			
			$sqldu="select *,d.idduenio as idd from duenioinmueble di,duenio d where di.idduenio = d.idduenio and idinmueble = $id";
			$operaciondu = mysql_query($sqldu);
			$ldu="";
			$pl="";
			$dueniosl = "<table border = '1'>";
			while($rowdu = mysql_fetch_array($operaciondu))
			{
				$ldu .= $rowdu["idd"] . "*" . $rowdu["fechacontrato"] . "|";
				$pl .= "&p_" . $rowdu["idd"] . "=p_" . $rowdu["idd"] . ".value";
				$dueniosl .="<tr><td>" . utf8_decode($rowdu["nombre"] . " " . $rowdu["nombre2"] . " " . $rowdu["apaterno"] . " " . $rowdu["amaterno"]) . "</td><td>" . $rowdu["fechacontrato"] . "</td><td><input name='p_" . $rowdu["idduenio"] . "' type='text' value='" . $rowdu["porcentajed"] . "' size='5'></td><td><input type='button' value='X' onClick = \"cargarSeccion('$ruta/controlduenio.php','listaduenio','acumulado=' + acumulado.value + '&dato=" . $rowdu["idd"]  . "&operacion=2')\"></td></tr>";
			
			}
			$dueniosl .="</table><input type='hidden' name='acumulado' id='acumulado' value='$ldu'/>";
			
			
	$sql="select * from cuentainmueble c, tipocuentai i where c.idtipocuentai = i.idtipocuentai and idinmueble = $id order by i.idtipocuentai";
	$datos=mysql_query($sql);
	if(mysql_num_rows($datos)>0)
	{
	$html = "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	$html .= "<table border=\"1\"><tr><th>Id</th><th>Tipo</th><th>Cuenta</th><th>Notas</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		//Cambio 28/06/2021
		// Si el tipo de cuenta es igual a 5 se realiza el cambio de | por </br><hr> 	
		if($row["idtipocuentai"] == 5 || $row["idtipocuentai"] == '5')
			$html .= "<tr><td>" . $row["idcuentainmueble"] . "</td><td>" . $row["tipocuentai"] . "</td><td>" . $row["cuentainmueble"] . "</td><td>" . str_replace('|','</br><hr>',$row["notaci"]) . "</td><td>";
		else
			$html .= "<tr><td>" . $row["idcuentainmueble"] . "</td><td>" . $row["tipocuentai"] . "</td><td>" . $row["cuentainmueble"] . "</td><td>" . $row["notaci"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/cuentasinmuebles.php','divcuentas','accion=1&idi=$id&id=" .  $row["idcuentainmueble"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/cuentasinmuebles.php','divcuentas','accion=2&idi=$id&id=" .  $row["idcuentainmueble"] . "' )\" $txteditar>";
		//echo "<input type=\"hidden\" name=\"id\" value=\"" . $row["idmodulo"] . "\">";
		$html .= "</td></tr>";
		$html = CambiaAcentosaHTML($html);
	}
	$html .=  "</table></div>";	
	}			
			
			
		}


	}
	else
	{
		$accion="0";
	}

	
	if(!$dueniosl || $dueniosl=="")
	{
		$dueniosl="<input type='hidden' name='acumulado' id='acumulado' value=''/>";
	}


	$sql="select * from duenio";

	$duenioselect = "<select name=\"duenio\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		//if ($idduenio>0)
		if ($idduenio==$row["idduenio"])
		{
			$seleccionopt=" SELECTED ";
		}
		$duenioselect .= "<option value=\"" . $row["idduenio"] . "\" $seleccionopt>" . $row["nombre"] . " " . $row["nombre2"] ." " . $row["apaterno"] . " " . $row["amaterno"]  . "</option>";

	}
	$duenioselect .="</select>";

	if(!$idestado){$idestado=9;}
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
	
	
	if(!$idpais){$idpais=110;}
	$sql="select * from pais";

	$paisselect = "<select name=\"idpais\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		//if ($idduenio>0)
		if ($idpais==$row["idpais"])
		{
			$seleccionopt=" SELECTED ";
		}
		$paisselect .= "<option value=\"" . $row["idpais"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["pais"]) . "</option>";

	}
	$paisselect .="</select>";	
	
	$sql="select * from tipoinmueble";

	$tiponimuebleselect = "<select name=\"idtipoinmueble\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		//if ($idduenio>0)
		if ($idtipoinmueble==$row["idtipoinmueble"])
		{
			$seleccionopt=" SELECTED ";
		}
		$tiponimuebleselect .= "<option value=\"" . $row["idtipoinmueble"] . "\" $seleccionopt>" . $row["tipoinmueble"]  . "</option>";

	}
	$tiponimuebleselect .="</select>";	
	
	
	$sql="select * from tipocuentai";

	//Cambio 28/06/2021
	// Se agregó código javascript en el atributo onchange para que se muestren 
	// u oculten los campos de pagos condomino	
	$tipocuentaiselect = "<select name=\"idtipocuentai\" onchange=\"var mostrar, ocultar;if (this.value == 5){mostrar = 'condominio';}else{ocultar = 'condominio';}var elmOcultar = document.getElementsByClassName(ocultar);var elmMostrar = document.getElementsByClassName(mostrar);for(i=0;i<elmOcultar.length;i++) elmOcultar[i].style.display='none';for(i=0;i<elmMostrar.length;i++) elmMostrar[i].style.display='table-row';\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		//if ($idduenio>0)
		/*
		if ($idtipoinmueble==$row["idtipocuentai"])
		{
			$seleccionopt=" SELECTED ";
		}
		*/
		$tipocuentaiselect .= "<option value=\"" . $row["idtipocuentai"] . "\" $seleccionopt>" . $row["tipocuentai"]  . "</option>";

	}
	$tipocuentaiselect .="</select>";	

	$sql="select asesor.idasesor, asesor.nombre, asesor.apellido from asesor, asesorcategoria where asesor.idasesor=asesorcategoria.idasesor and asesorcategoria.idcategoriaas=1";
	$asesorSelect= "<select name=\"idasesor\">";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion)) {
		$seleccionAsesor="";
		if (@$asesor==$row["idasesor"]) {
			$seleccionAsesor=" SELECTED ";
		}
		$asesorSelect .= "<option value=\"" . $row["idasesor"] . "\" $seleccionAsesor>" . $row["nombre"]." ".$row["apellido"] . "</option>";
		}
	$asesorSelect .="</select>";
		
	$btnaud="";
	if($aud->id!=0)
	{
		$btnaud = "<input type='button' value='Imprimir movimiento' onClick=\"window.open('$ruta/impresiontransaccion.php?id=$aud->id&seccion=Inmueble');\"";
	
	}

	
	

echo <<<formulario1

<center>
<h1>Inmuebles</h1>
$btnaud
<form>
<input type="hidden" name="idinm" value="">
<table border="0">
<tr>
	<td valign="top"><b>Due&ntilde;o</b></td>
	<td>
		$duenioselect <input type="button" value="+" onClick="if(duenio.value !='0'){f= prompt ('Fecha de contrato (aaaa-mm-dd)', '');cargarSeccion('$ruta/controlduenio.php','listaduenio','acumulado=' + acumulado.value + '&dato=' + duenio.value + '&operacion=1&fechacontrato=' + f);}">
	</td>
	
	<td rowspan="5" valign="top">


	<input type="button" value="Aplicar" name="aplicar" $btnblo onClick="if(acumulado.value==''){alert('Debe de tener almenos un dueño, mismo que puede agregar presionando el botón con signo de + que esta a la derecha de la lista');  }else{   if(this.value=='Aplicar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=0&id=' + ids.value + '&acumulado=' + acumulado.value + '&calle=' + calle.value + '&numext=' + numext.value + '&numint=' + numint.value + '&colonia=' + colonia.value + '&delmun=' + delmun.value + '&idestado=' + idestado.value + '&idpais=' + idpais.value + '&cp=' + cp.value + '&descripcion=' + descripcion.value + '&inventario=' + inventario.value + '&estacionamiento=' + estacionamiento.value + '&tel=' + tel.value + '&notas=' + notas.value + '&predial=' + predial.value + '&idtipoinmueble=' + idtipoinmueble.value + '&mts2=' + mts2.value + '&idasesor=' + idasesor.value + '&apli=1')};}" >



		<div id='divcuentas'>
		<fieldset><legend>Cuentas asociadas</legend>
		<table border = "0">
		<tr>
			<td valign="top">Tipo de cuenta</td>
			<td>
				$tipocuentaiselect
			</td>
		</tr>	
		<tr>
			<td valign="top">Cuenta</td>
			<td>
				<input type="text" name="cuentai" value="">
			</td>
		</tr>
		<!--Cambio 28/06/2021 
			Se agregaron los campos necesarios para poder agregar la información de
			pagos condominio
		-->
		<tr class="condominio" style="display:none;">
			<td valign="top">Titular</td>
			<td>
				<input type="text" id="cond_titular"></input>
			</td>
		</tr>
		<tr class="condominio" style="display:none;">
			<td valign="top">RFC</td>
			<td>
				<input type="text" id="cond_rfc"></input>
			</td>
		</tr>
		<tr class="condominio" style="display:none;">
			<td valign="top">Banco</td>
			<td>
				<input type="text" id="cond_banco"></input>
			</td>
		</tr>
		<tr class="condominio" style="display:none;">
			<td valign="top">CLABE</td>
			<td>
				<input type="text" id="cond_clabe"></input>
			</td>
		</tr>
		<tr class="condominio" style="display:none;">
			<td valign="top">Referencia</td>
			<td>
				<input type="text" id="cond_ref"></input>
			</td>
		</tr>
		<tr class="condominio" style="display:none;">
			<td valign="top">Nombre del administrador</td>
			<td>
				<input type="text" id="cond_admin"></input>
			</td>
		</tr>
		<tr class="condominio" style="display:none;">
			<td valign="top">Correo</td>
			<td>
				<input type="text" id="cond_correo"></input>
			</td>
		</tr>
		<tr class="condominio" style="display:none;">
			<td valign="top">Teléfono</td>
			<td>
				<input type="text" id="cond_tel"></input>
			</td>
		</tr>
		<tr class="condominio" style="display:none;">
			<td valign="top">Días de pago</td>
			<td>
				<input type="text" id="cond_dpago"></input>
			</td>
		</tr>
		<tr class="condominio" style="display:none;">
			<td valign="top">Importe</td>
			<td>
				<input type="text" id="cond_imp"></input>
			</td>
		</tr>
		<!-- Fin Cambio -->
		<tr>
			<td valign="top">Notas</td>
			<td>
				<textarea name="notaci" cols="30" rows="2"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan = "2" align = "center">
				<!--Cambio 28/06/2021
					Se agregar código javascript para poder almacenar la información de pago condominio
				-->
				<input type="button" value="Agregar" onClick="if(idtipocuentai.value == 5){if(cond_titular.value != '' &&cond_banco.value != '' &&cond_clabe.value != '' &&cond_ref.value != '' &&cond_admin.value != '' &&cond_correo.value != '' &&cond_tel.value != '' &&cond_dpago.value != '' && cond_imp.value != ''){notaci.value = 'Titular de la cuenta: ' + cond_titular.value +'|RFC: ' + cond_rfc.value +'|Banco: ' + cond_banco.value + '|CLABE: ' + cond_clabe.value + '|Referencia: ' + cond_ref.value + '|Nombre del administrador: '+cond_admin.value + '|Correo: '+cond_correo.value + '|Teléfono: '+cond_tel.value + '|Días de pago: '+cond_dpago.value + '|Importe: '+cond_imp.value+'|Notas: '+notaci.value;}else{alert('Todos los campos de pago condominal deben estar llenos.');return;}/*console.log(notaci.value);*/}if(cuentai.value !=''){cargarSeccion('$ruta/cuentasinmuebles.php','divcuentas','idi=' + ids.value + '&idtipocuentai=' + idtipocuentai.value  + '&notaci=' + notaci.value + '&cuentai=' + cuentai.value + '&accion=0');};idtipocuentai.value = 0; cuentai.value=''; notaci.value='';cond_titular.value = cond_rfc.value = cond_banco.value = cond_clabe.value = cond_ref.value = cond_admin.value = cond_correo.value = cond_tel.value = cond_dpago.value = cond_imp.value = '';var elmOcultar = document.getElementsByClassName('condominio');for(i=0;i<elmOcultar.length;i++) elmOcultar[i].style.display='none';">
			</td>
		</tr>		
		<tr>
			<td valign="top" align="center" colspan="2">
				$html
			</td>

		</tr>	
		</table>		
		
		
		
		
		</fieldset>
		</div>
	
	
	</td>
	
	
</tr>

<tr>
	<td valign="top" align="center" colspan="2">
		<div id='listaduenio'>$dueniosl</div>
	</td>

</tr>
<tr>
	<td valign="top"><b>Tipo de inmueble</b></td>
	<td>
		$tiponimuebleselect
	</td>
</tr>
<tr>
	<td valign="top"><b>Asesor </b></td>
	<td>$asesorSelect</td>
</tr>
<tr>
	<td valign="top"><b>Direcci&oacute;n</b></td>
	<td>
		<table border="0">
		<tr>
			<td>Calle</td><td><input type="text" name="calle" value="$calle" size="44"></td>
		<tr>
			<td colspan="2">Num.Ext.<input type="text" name="numext" value="$noext" size="15">&nbsp;&nbsp;
			Num. Int.<input type="text" name="numint" value="$noint" size="15"></td>
		</tr>
		<tr>
			<td>Colonia</td><td><input type="text" name="colonia" value="$colonia" size="44"></td>
		</tr>
		<tr>
			<td>Alc. / Mun.</td><td><input type="text" name="delmun" value="$delnum" size="44"></td>
		</tr>
		<tr>
			<td>Pa&iacute;s</td><td>$paisselect</td>
		</tr>
		<tr>
			<td>Estado</td><td>$estadoselect</td>
		</tr>

		<tr>
			<td>C.P.</td><td><input type="text" name="cp" value="$cp" size="44"></td>
		</tr>
		<tr>
			<td>Tama&ntilde;o</td><td><input type="text" name="mts2" value="$mts2" size="15"> Mts<sup>2</sup></td>
		</tr>		
		<tr>
			<td>C. Predial</td><td><input type="text" name="predial" value="$predial" size="44"></td>
		</tr>		
		</table>
	</td>
</tr>
<tr>
	<td valign="top"><b>Otros Datos</b></td>
	<td>
		<table border="0">
		<tr>
			<td>
				Descripci&oacute;n
				<br><textarea cols="20" rows="2" name="descripcion">$descripcion</textarea>
			</td>
			<td>
				Notas
				<br><textarea cols="20" rows="2" name="notas">$notas</textarea>
			</td>
		</tr>
		<tr>
			<td>
				Inventario
				<br><textarea cols="20" rows="2" name="inventario">$inventario</textarea>
			</td>
			<td>
				Estacionamiento
				<br><textarea cols="20" rows="2" name="estacionamiento">$esta</textarea>
			</td>
		</tr>
		<tr>
			<td >Tel&eacute;fono</td><td><input type="text" name="tel" value="$tel"></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="3" align="center">
		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';calle.value='' ;numext.value='' ;numint.value='';colonia.value='';delmun.value='';idestado.value=9;idpais.value=110;cp.value='';descripcion.value='';inventario.value='';estacionamiento.value='';tel.value='';predial.value='';notas.value='';duenio.value='0';idtipoinmueble.value='0';idasesor.value='1';mts2.value='';document.getElementById('listaduenio').innerHTML='';document.getElementById('divcuentas').innerHTML='';aplicar.disabled = false">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(acumulado.value==''){alert('Debe de tener almenos un dueño, mismo que puede agregar presionando el botón con signo de + que esta a la derecha de la lista');  }else{ if( mts2.value==''){ alert ('Debe de coloccar los metros cuarados del inmueble');  }else{  if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&acumulado=' + acumulado.value + '&calle=' + calle.value + '&numext=' + numext.value + '&numint=' + numint.value + '&colonia=' + colonia.value + '&delmun=' + delmun.value + '&idestado=' + idestado.value + '&idpais=' + idpais.value + '&cp=' + cp.value + '&descripcion=' + descripcion.value + '&inventario=' + inventario.value + '&estacionamiento=' + estacionamiento.value + '&tel=' + tel.value + '&notas=' + notas.value + '&predial=' + predial.value + '&idtipoinmueble=' + idtipoinmueble.value + '&mts2=' + mts2.value + '&idasesor=' + idasesor.value )};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&acumulado=' + acumulado.value + '&calle=' + calle.value + '&numext=' + numext.value + '&numint=' + numint.value + '&colonia=' + colonia.value + '&delmun=' + delmun.value + '&idestado=' + idestado.value + '&idpais=' + idpais.value + '&cp=' + cp.value + '&descripcion=' + descripcion.value + '&inventario=' + inventario.value + '&estacionamiento=' + estacionamiento.value + '&tel=' + tel.value + '&notas=' + notas.value  + '&predial=' + predial.value + '&idtipoinmueble=' + idtipoinmueble.value  + '&mts2=' + mts2.value + '&idasesor=' + idasesor.value )};}}" >
	</td>
</tr>
</table>
		<input type="hidden" name="ids" value="$id">
		<input type="hidden" name="accion" value="$accion">
		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">
</form>
</center>

formulario1;

	//echo CambiaAcentosaHTML($html);

	$sql="select * from inmueble,  estado, pais where  inmueble.idpais=pais.idpais and inmueble.idestado = estado.idestado ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Due&ntilde;o</th><th>Direcci&oacute;n</th><th>C. Predial</th><th>Acci&oacute;n</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
	
	
			$sqldu="select * from duenioinmueble di,duenio d where di.idduenio = d.idduenio and idinmueble = " . $row["idinmueble"];
			$operaciondu = mysql_query($sqldu);
			$ldu="";
			$dueniosl = "";
			while($rowdu = mysql_fetch_array($operaciondu))
			{

				$dueniosl .="" . $rowdu["nombre"] . " " . $rowdu["nombre2"] . " " . $rowdu["apaterno"] . " " . $rowdu["amaterno"] . "<br>";
			
			}

	
	
	
	
	
		$html = "<tr><td>" . $row["idinmueble"] . "</td><td>$dueniosl</td>";
		$html .= "<td>" .  $row["calle"] . "No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"] . " Deleg/Mun. ". $row["delmun"] . " C.P." . $row["cp"] . " Pa&iacute;s " . $row["pais"] . " Edo " . $row["estado"] . "</td>";
		$html .= "<td>" .  $row["predial"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idinmueble"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idinmueble"]  . "' )\" $txteditar>";
		$html .= "<input type='button' value='Movimientos' onClick=\"window.open('$ruta/movauditoria.php?id=" .  $row["idinmueble"]  . "&tabla=inmueble');\">";
		//echo "<input type=\"hidden\" name=\"id\" value=\"" . $row["idinmueble"] . "\">";
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