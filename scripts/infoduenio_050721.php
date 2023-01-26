<?php
include 'general/sessionclase.php';
include_once('general/conexion.php');
include 'general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];


$listac="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='duenios.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta= $row['ruta'] ;
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
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


	//inicia la variable que contendr· la consulta
	$sql="";


	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la acciÛn a realizar para la siguiente presiÛn del botÛn agregar
	//en su defecto, sera accÛn agregar
	//if($accion=="2")
	//{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from duenio where idduenio = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"]);
			$nombre2=CambiaAcentosaHTML($row["nombre2"]);
			$apaterno=CambiaAcentosaHTML($row["apaterno"]);
			$amaterno=CambiaAcentosaHTML($row["amaterno"]);
			$tel=CambiaAcentosaHTML($row["tel"]);
			$usuario=CambiaAcentosaHTML($row["usuario"]);
			$pwd=CambiaAcentosaHTML($row["pwd"]);
			$emaild=CambiaAcentosaHTML($row['emaild']);
			$rfcd=CambiaAcentosaHTML($row["rfcd"]);
			$banco=CambiaAcentosaHTML($row['banco']);
			$titularbanco=CambiaAcentosaHTML($row['titularbanco']);
			$cuenta=CambiaAcentosaHTML($row['cuenta']);
			$clabe=CambiaAcentosaHTML($row['clabe']);
			$called=CambiaAcentosaHTML($row['called']);
			$noexteriord=CambiaAcentosaHTML($row['noexteriord']);
			$nointeriord=CambiaAcentosaHTML($row['nointeriord']);
			$coloniad=CambiaAcentosaHTML($row['coloniad']);
			$delmund=CambiaAcentosaHTML($row['delmund']);
			$estadod=CambiaAcentosaHTML($row['estadod']);
			$paisd=CambiaAcentosaHTML($row['paisd']);
			$cpd=CambiaAcentosaHTML($row['cpd']);
			$diasapagar=CambiaAcentosaHTML($row['diasapagar']);
		
			$callep=CambiaAcentosaHTML($row['callep']);
			$noexteriorp=CambiaAcentosaHTML($row['noexteriorp']);
			$nointeriorp=CambiaAcentosaHTML($row['nointeriorp']);
			$coloniap=CambiaAcentosaHTML($row['coloniap']);
			$delmunp=CambiaAcentosaHTML($row['delmunp']);
			$estadop=CambiaAcentosaHTML($row['estadop']);
			$paisp=CambiaAcentosaHTML($row['paisp']);
			$cpp=CambiaAcentosaHTML($row['cpp']);
			
			$curp=CambiaAcentosaHTML($row['curp']);	
			$idasesor=$row['idusuario'];			
			
			//$porcentaje=CambiaAcentosaHTML($row['porcentaje']);
			$honorarios=CambiaAcentosaHTML($row['honorarios']);
			$iva=CambiaAcentosaHTML($row['ivad']);	
			
			
			//crear el proceso para mostrar los telefonos, correos y apoderados
			$tell ="";	
			$maill="";
			$listat="<table border='1'>";
			$listam="<table border='1'>";
			$sql1 = "select * from contacto where idduenio = $id";
			$operacion1 = mysql_query($sql1);
			
			while($row1 = mysql_fetch_array($operacion1))
			{
			
				
				if($row1["idtipocontacto"] == 1)
				{
					$tell .="1*" . $row1["contacto"] . "*" . $row1["notac"] . "*" . $row1["usar"] . "|";	
					$listat .="<tr><td>" . $row1["contacto"] . "</td><td>" . $row1["notac"] . "</td></tr>";
				
				}
				else
				{
					$maill .="2*" . $row1["contacto"] . "*" . $row1["notac"] . "*" . $row1["usar"] . "|";	
					$listam .="<tr><td>" . $row1["contacto"] . "</td><td>" . $row1["notac"] . "</td><td>" . $row1["usar"] . "</td></tr>";
			
				}


			}
			

			$listat .="</table>";
			$listam .="</table>";
			//$listat .="<input type='hidden' name='tell' id='tell' value='$acumulado'/>";
			//$listam .="<input type='hidden' name='maill' id='maill' value='$acumulado'/>";
			$tell = $listat ;
			$maill = $listam ;
			
			$btncta = "";
			
			
	$sqlc="select * from dueniodistribucion where idduenio = $id ";
	$datosc=mysql_query($sqlc);
	//echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	$listac = "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>RFC</th><th>Banco</th><th>Cuenta</th><th>Clabe</th><th>ID BANORTE</th><th>Notas</th></tr>";
	while($rowc = mysql_fetch_array($datosc))
	{
		$html = "<tr><td>" . $rowc["iddueniodistribucion"] . "</td><td>" . $rowc["nombre"] . "</td><td>" . $rowc["rfc"] . "</td><td>" . $rowc["banco"] . "</td><td>" . $rowc["cuenta"] . "</td><td>" . $rowc["clabe"] . "</td><td>" . $rowc["idbanco"] . "</td><td>" . $rowc["notas"] . "</td>";
		//$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/ctasduenio.php','listacuentas','accion=2&id=$id&idc=" .  $rowc["iddueniodistribucion"]  . "' )}\" $txtborrar>";
		//$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/lctaduenio.php','ctaform','accion=4&id= $id &idc=" .  $rowc["iddueniodistribucion"]  . "' )\" $txteditar>";
		$html .= "</tr>";
		$listac .= CambiaAcentosaHTML($html);
	}
	$listac .= "</table>";			
					
			
			$sqldu="select *,d.idduenio as idd from duenioinmueble di,duenio d where di.idduenio = d.idduenio and idinmueble = $id";
			$operaciondu = mysql_query($sqldu);
			$ldu="";
			$pl="";
			$dueniosl = "<table border = '1'>";
			while($rowdu = mysql_fetch_array($operaciondu))
			{
				$ldu .= $rowdu["idd"] . "|";
				$pl .= "&p_" . $rowdu["idd"] . "=p_" . $rowdu["idd"] . ".value";
				$dueniosl .="<tr><td>" . utf8_decode($rowdu["nombre"] . " " . $rowdu["nombre2"] . " " . $rowdu["apaterno"] . " " . $rowdu["amaterno"]) . "</td><td><input name='p_" . $rowdu["idduenio"] . "' type='text' value='" . $rowdu["porcentajed"] . "' size='5'></td><td><input type='button' value='X' onClick = \"cargarSeccion('$ruta/controlduenio.php','listaduenio','acumulado=' + acumulado.value + '&dato=" . $rowdu["idd"]  . "&operacion=2')\"></td></tr>";
			
			}
			$dueniosl .="</table><input type='hidden' name='acumulado' id='acumulado' value='$ldu'/>";
			
			
			
	$sql="select * from apoderado where idduenio = $id ";
	$datos=mysql_query($sql);
	//echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	$listaap = "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Tel.</th><th>mail</th><th>Facultades</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$listaap .= "<tr><td>" . $row["idapoderado"] . "</td><td>" . $row["nombreap"] . " " . $row["nombre2ap"] . " " . $row["apaternoap"] . " " . $row["amaternoap"]. "</td><td>" . $row["telap"] . "</td><td>" . $row["mailap"] . "</td><td>" . $row["facultades"] . "</td>";
		//$listaap .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/apoderados.php','listaap','accion=2&id=$id&idc=" .  $row["idapoderado"]  . "' )}\" $txtborrar>";
		//$listaap .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/apoderados.php','ctaform','accion=4&id=$id&idc=" .  $row["idapoderado"]  . "' )\" $txteditar>";
		$listaap .= "</tr>";
		//echo CambiaAcentosaHTML($html);
	}
	$listaap .="</table>";			
	$listaap = CambiaAcentosaHTML($listaap);		
			
	$sql="select *, i.idinmueble as idin from duenioinmueble d, inmueble i where idduenio = $id and d.idinmueble = i.idinmueble";
	$datos=mysql_query($sql);
	//echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	$listainm = "<table border=\"1\"><tr><th>Id</th><th>Calle</th><th>No. Ext. </th><th>No. Int.</th><th>Colonia</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$listainm .= "<tr ><td ><a style=\"cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/infoinm.php','infoinm', 'id=". $row["idin"] . "');\">" . $row["idin"] . "</a></td><td><a style=\"cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/infoinm.php','infoinm', 'id=". $row["idin"] . "');\">" . $row["calle"] . "</a></td><td><a style=\"cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/infoinm.php','infoinm', 'id=". $row["idin"] . "');\">" . $row["numeroext"] . "</a></td><td><a style=\"cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/infoinm.php','infoinm', 'id=". $row["idin"] . "');\">" . $row["numeroint"] . "</a></td><td><a style=\"cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/infoinm.php','infoinm', 'id=". $row["idin"] . "');\">" . $row["colonia"] . "</a></td>";
		//$listaap .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/apoderados.php','listaap','accion=2&id=$id&idc=" .  $row["idapoderado"]  . "' )}\" $txtborrar>";
		//$listaap .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/apoderados.php','ctaform','accion=4&id=$id&idc=" .  $row["idapoderado"]  . "' )\" $txteditar>";
		$listainm .= "</tr>";
		//echo CambiaAcentosaHTML($html);
	}
	$listainm .="</table>";			
	$listainm = CambiaAcentosaHTML($listainm);		
					
			
						
			
			
					
	}



	//}
	//else
	//{
	//	$accion="0";
	//}





	
	if(!$idasesor)
	{
		$idasesor = $misesion->usuario;
	}
	$sql="select * from usuario where idusuario = $idasesor";
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	$agenteselect=CambiaAcentosaHTML($row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"]  ) ;



//Genero el formulario de los submodulos

echo  <<<formulario1
<center>
<h1>$nombre $nombre2 $apaterno $amaterno</h1>
<form>
$agenteselect
<table border="0">

<tr>
	
	<td  align="center" valign="top">
	<fieldset><legend>Datos generales</legend>
	<table border = "0">

<tr>
	<td>RFC:</td>
	<td>$rfcd</td>
</tr>
<tr>
	<td>CURP:</td>
	<td>$curp</td>
</tr>
<tr>
	<td>Honorarios(%):</td>
	<td>$honorarios</td>
</tr>
<tr>
	<td>I.V.A.(%):</td>
	<td>$iva</td>
</tr>
<tr>
	<td>Dias a pagar:</td>
	<td>$diasapagar</td>
</tr>
<tr>
	<td>Usuario:</td>
	<td>$usuario</td>
</tr>
<!--
<tr>
	<td>Contrase&ntilde;a:</td>
	<td><input type="password" name="pwd" value="$pwd"></td>
</tr>
-->
	</table>
	</fieldset>

	<fieldset><legend>Tel&eacute;fonos</legend>

		<div id='teldiv'>$tell</div>

	</fieldset>
	
	<fieldset><legend>Correos</legend>
	
			<div id='maildiv'>$maill</div>
	
	</fieldset>	
	
	
	
<fieldset><legend>Apoderados</legend>
	
	<div id="listaap"> $listaap </div>
	</fieldset>		
	

	
</td>
<td  align="center">
	<fieldset><legend>Domicilio particular</legend>
	<table border = "0">

<tr>
	<td>Calle:</td>
	<td>$callep</td>
</tr>
<tr>
	<td>No. ext.:</td>
	<td>$noexteriorp</td>
</tr>
<tr>
	<td>No. Int.:</td>
	<td>$nointeriorp</td>
</tr>
<tr>
	<td>Colonia:</td>
	<td>$coloniap</td>
</tr>
<tr>
	<td>Deleg./Municipio:</td>
	<td>$delmunp</td>
</tr>
<tr>
	<td>Estado:</td>
	<td>$estadop</td>
</tr>
<tr>
	<td>Pa&iacute;s:</td>
	<td>$paisp</td>
</tr>
<tr>
	<td>C.P.:</td>
	<td>$cpp</td>
</tr>
	</table>
	</fieldset>
	
	<fieldset><legend>Domicilio Fiscal</legend>
	<table border = "0">

<tr>
	<td>Calle:</td>
	<td>$called</td>
</tr>
<tr>
	<td>No. ext.:</td>
	<td>$noexteriord</td>
</tr>
<tr>
	<td>No. Int.:</td>
	<td>$nointeriord</td>
</tr>
<tr>
	<td>Colonia:</td>
	<td>$coloniad</td>
</tr>
<tr>
	<td>Deleg./Municipio:</td>
	<td>$delmund</td>
</tr>
<tr>
	<td>Estado:</td>
	<td>$estadod</td>
</tr>
<tr>
	<td>Pa&iacute;s:</td>
	<td>$paisd</td>
</tr>
<tr>
	<td>C.P.:</td>
	<td>$cpd</td>
</tr>
	</table>
	</fieldset>	
	
<fieldset><legend>Datos de transferencia</legend>
	
	<div id="listacuentas"> $listac </div>
	</fieldset>	
	
<fieldset><legend>Propiedades</legend>
	
	<div id="listacuentas" style="height:300px; overflow:auto;"> $listainm </div>
	<div id="infoinm" ></div>
	</fieldset>		
	
	
	
	
	
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