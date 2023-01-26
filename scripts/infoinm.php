<?php
include 'general/sessionclase.php';
include_once('general/conexion.php');
include 'general/funcionesformato.php';

//Modulo

$id=@$_GET["id"];
$html="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='duenios.php'";
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






		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";

		$sql="select * from inmueble i, pais p, estado e, tipoinmueble t  where idinmueble = $id  and i.idpais = p.idpais and i.idestado = e.idestado and i.idtipoinmueble = t.idtipoinmueble ";

		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
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
			$tipoinmueble = CambiaAcentosaHTML($row["tipoinmueble"]);
			$pais = CambiaAcentosaHTML($row["pais"]);
			$estado = CambiaAcentosaHTML($row["estado"]);
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
		//$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idcuentainmueble"] . "' )\" $txteditar>";
		//echo "<input type=\"hidden\" name=\"id\" value=\"" . $row["idmodulo"] . "\">";
		$html .= "</td></tr>";
		$html = CambiaAcentosaHTML($html);
	}
	$html .=  "</table></div>";	
	}			
			
			
		}



/*	
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
*/	

/*
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

	$tipocuentaiselect = "<select name=\"idtipocuentai\"><option value=\"0\">Seleccione uno de la lista</option>";
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
		
		$tipocuentaiselect .= "<option value=\"" . $row["idtipocuentai"] . "\" $seleccionopt>" . $row["tipocuentai"]  . "</option>";

	}
	$tipocuentaiselect .="</select>";	
		


	*/
	

echo <<<formulario1

<center>

<form>
<input type="hidden" name="idinm" value="">
<table border="0">
<tr>
	<td valign="top" align="center" colspan="2">
		<div id='listaduenio'>$dueniosl</div>
	</td>
</tr>
<tr>
	<td valign="top" align="center" colspan="2">
		<div id='divcuentas'>$html</div>
	</td>
</tr>	

<tr>
	<td valign="top"><b>Tipo de inmueble</b></td>
	<td>
		$tipoinmueble
	</td>
</tr>
<tr>
	<td valign="top"><b>Direcci&oacute;n</b></td>
	<td>
		<table border="1">
		<tr>
			<td>Calle</td><td>$calle</td>
		<tr>
			<td colspan="2">Num.Ext. $noext &nbsp;&nbsp;
			Num. Int.$noint</td>
		</tr>
		<tr>
			<td>Colonia</td><td>$colonia</td>
		</tr>
		<tr>
			<td>Alc. / Mun.</td><td>$delnum</td>
		</tr>
		<tr>
			<td>Pa&iacute;s</td><td>$pais</td>
		</tr>
		<tr>
			<td>Estado</td><td>$estado</td>
		</tr>

		<tr>
			<td>C.P.</td><td>$cp</td>
		</tr>
		<tr>
			<td>Tama&ntilde;o</td><td>$mts2 Mts<sup>2</sup></td>
		</tr>		
		<tr>
			<td>C. Predial</td><td>$predial</td>
		</tr>		
		</table>
	</td>
</tr>
<tr>
	<td valign="top"><b>Otros Datos</b></td>
	<td>
		<table border="1">
		<tr>
			<td>
				Descripci&oacute;n
				</td><td>$descripcion
			</td>
		</tr>
		<tr>
			<td>
				Notas
				</td><td>$notas
			</td>
		</tr>
		<tr>
			<td>
				Inventario
				</td><td>$inventario
			</td>
		</tr>
		<tr>	
			<td>
				Estacionamiento
				</td><td>$esta
			</td>
		</tr>
		<tr>
			<td >Tel&eacute;fono</td><td>$tel</td>
		</tr>
		</table>
	</td>
</tr>

</table>

</form>
</center>

formulario1;




}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>