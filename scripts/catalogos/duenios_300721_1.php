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
$tel=@$_GET['tel'];
$usuario=@$_GET['usuario'];
$pwd=@$_GET['pwd'];
$emaild=@$_GET['emaild'];
$rfcd=@$_GET["rfcd"];
$curp =@$_GET["curp"];
$banco=@$_GET['banco'];
$titularbanco=@$_GET['titularbanco'];
$cuenta=@$_GET['cuenta'];
$clabe=@$_GET['clabe'];
$called=@$_GET['called'];
$noexteriord=@$_GET['noexteriord'];
$nointeriord=@$_GET['nointeriord'];
$coloniad=@$_GET['coloniad'];
$delmund=@$_GET['delmund'];
$estadod=@$_GET['estadod'];
$paisd=@$_GET['paisd'];
$cpd=@$_GET['cpd'];
$diasapagar=@$_GET['diasapagar'];

$callep=@$_GET['callep'];
$noexteriorp=@$_GET['noexteriorp'];
$nointeriorp=@$_GET['nointeriorp'];
$coloniap=@$_GET['coloniap'];
$delmunp=@$_GET['delmunp'];
$estadop=@$_GET['estadop'];
$paisp=@$_GET['paisp'];
$cpp=@$_GET['cpp'];
$estadost=@$_GET['estado'];
$idasesor = @$_GET['idasesor'];
//$porcentaje=@$_GET['porcentaje'];

$honorarios=@$_GET['honorarios'];
$iva=@$_GET['iva'];

//para las listas de telefonos, correos y cuentas de pago
$tell =@$_GET['tell'];
$maill =@$_GET['maill'];
$cuantal =@$_GET['cuental'];

$aplicar = @$_GET['aplicar'];


if($paisd==null){$paisd="MEXICO";};
if($estadod==null){$estadod="Ciudad de Mexico";};
if($paisp==null){$paisp="MEXICO";};
if($estadop==null){$estadop="Ciudad de Mexico";};
if($honorarios==null){$honorarios=10;};
if($iva==null){$iva=16;};
if($idasesor==null){$idasesor=0;};

$btncta = " disabled ";
$listac="";

$aud = new auditoria;
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

		//$sql="insert into duenio (nombre,nombre2,apaterno,amaterno,tel,usuario,pwd,emaild,rfcd,banco,titularbanco,cuenta,clabe,called,noexteriord,nointeriord,coloniad,delmund,estadod,paisd,cpd, porcentaje,honorarios,ivad) values ('$nombre','$nombre2','$apaterno','$amaterno','$tel','$usuario','$pwd','$emaild','$rfcd','$banco','$titularbanco','$cuenta','$clabe','$called','$noexteriord','$nointeriord','$coloniad','$delmund','$estadod','$paisd','$cpd',$porcentaje,$honorarios,$iva)";
		$sql="insert into duenio (nombre,nombre2,apaterno,amaterno,tel,usuario,pwd,emaild,rfcd,banco,titularbanco,cuenta,clabe,called,noexteriord,nointeriord,coloniad,delmund,estadod,paisd,cpd,honorarios,ivad,callep,noexteriorp,nointeriorp,coloniap,delmunp,estadop,paisp,cpp,curp,idusuario,diasapagar) values ('$nombre','$nombre2','$apaterno','$amaterno','$tel','$usuario','$pwd','$emaild','$rfcd','$banco','$titularbanco','$cuenta','$clabe','$called','$noexteriord','$nointeriord','$coloniad','$delmund','$estadod','$paisd','$cpd',$honorarios,$iva,'$callep','$noexteriorp','$nointeriorp','$coloniap','$delmunp','$estadop','$paisp','$cpp','$curp',$idasesor,'$diasapagar')";
	
	
		$operacion = mysql_query($sql);
		
		$id= mysql_insert_id();
		
			$aud->tabla="duenio";
			$aud->idtabla=$id;
			$aud->accion = 1;
			$aud->usuario =$misesion->usuario;
			$aud->crearregistro();		
		

		
		if($tell !="" || $tell==null)
		{
		
			$ld = split("[|]", substr($tell,0,-1));
			$i=0;
			foreach ($ld as $idd)
			{
				$dats = split("[*]", $idd);
				if($dats[3]=="" || $dats[3]==null){$dats[3]=false;}
				$sql1="insert into contacto (idduenio, idtipocontacto,contacto, notac)values($id," . $dats[0] . ",'" . $dats[1] . "','" . $dats[2] . "')";
				$operacion1 = mysql_query($sql1);

			}

		}
		
		if($maill !="" || $maill==null)
		{
			$ld = split("[|]", substr($maill,0,-1));
			$i=0;
			foreach ($ld as $idd)
			{
				$dats = split("[*]", $idd);
				if($dats[3]=="" || $dats[3]==null){$dats[3]=false;}
				$sql1="insert into contacto (idduenio, idtipocontacto,contacto, notac, usar)values($id," . $dats[0] . ",'" . $dats[1] . "','" . $dats[2] . "'," . $dats[3] . ")";
				$operacion1 = mysql_query($sql1);

			}		
		
		
		}	
	
			
		if ($aplicar == "1")
		{
			
			$accion = "2";
			
		}
	
		$sql="";
	
		//echo "<br>Agrego";
		$nombre="";
		$nombre2="";
		$apaterno="";
		$amaterno="";
		$tel="";
		$usuario="";
		$pwd="";
		$emaild="";
		$rfcd="";
		$banco="";
		$titularbanco="";
		$cuenta="";
		$clabe="";
		$called="";
		$noexteriord="";
		$nointeriord="";
		$coloniad="";
		$delmund="";
		$estadod="Distrito Federal";
		$paisd="MEXICO";
		$cpd="";
		$diasapagar="";
		
		$callep="";
		$noexteriorp="";
		$nointeriorp="";
		$coloniap="";
		$delmunp="";
		$estadop="Distrito Federal";
		$paisp="MEXICO";
		$cpp="";
		$curp="";
		$idasesor="0";		
		//$porcentaje="";
		$honorarios="10";
		$iva="16";		
		
		$tell ="";
		$maill ="";		

		break;

	case "1": //Borro

		$sql = "delete from contacto where idduenio = $id";
		$operacion = mysql_query($sql);
		$sql="delete from duenio where idduenio=$id";
		
		//borrar tambien datos asociados en tabla contacto y apoderado
		
		//echo "<br>Borro";
		$id="";
		break;

	// Cambio 30/07/2021
	// Se agregó un caso para cambiar el RFC de un propietario
	// a uno generico
	case "5": // Coloco RFC Genérico
		$sql = "UPDATE duenio SET rfcd = 'XAXX010101000' WHERE idduenio = $id";
		break;
	// Fin Cambio 30/07/2021

	case 4:
	$sql2=mysql_query("SELECT nombre,nombre2,apaterno,amaterno FROM duenio WHERE idduenio='$id'");
	$datosdu = mysql_fetch_row($sql2);
	$nameduenio=$datosdu[0]." ".$datosdu[1]." ".$datosdu[2]." ".$datosdu[30];
	$sql3=mysql_query("UPDATE duenio SET activo='$estadost' WHERE idduenio='$id'");
	if ($estadost==1) {
		$msj="activado";
	}
	else{
		$msj="desactivado";
	}
	if ($results=$sql3) 
  echo "<center><div style=\"background-color:#2EFE64; width:400px;\"><strong>Se ha $msj el dueño: $nameduenio</strong></div></center>";
else 
  echo "<center><div style=\"background-color:#FA5858; width:400px;\"><strong>Ha ocurrido un error.</strong></div></center>";


	break;

	case "3": //Actualizo

		//$sql = "update duenio set nombre='$nombre',nombre2='$nombre2' ,apaterno='$apaterno',amaterno='$amaterno',tel='$tel', usuario='$usuario', pwd='$pwd', emaild='$emaild',rfcd='$rfcd', banco='$banco', titularbanco='$titularbanco', cuenta='$cuenta', clabe='$clabe', called='$called', noexteriord='$noexteriord', nointeriord='$nointeriord', coloniad='$coloniad', delmund='$delmund', estadod='$estadod', paisd='$paisd', cpd='$cpd', porcentaje=$porcentaje, honorarios=$honorarios, ivad=$iva where idduenio=$id";
		$sql = "update duenio set nombre='$nombre',nombre2='$nombre2' ,apaterno='$apaterno',amaterno='$amaterno',tel='$tel', usuario='$usuario', pwd='$pwd', emaild='$emaild',rfcd='$rfcd', banco='$banco', titularbanco='$titularbanco', cuenta='$cuenta', clabe='$clabe', called='$called', noexteriord='$noexteriord', nointeriord='$nointeriord', coloniad='$coloniad', delmund='$delmund', estadod='$estadod', paisd='$paisd', cpd='$cpd', honorarios=$honorarios, ivad=$iva, callep='$callep', noexteriorp='$noexteriorp', nointeriorp='$nointeriorp', coloniap='$coloniap', delmunp='$delmunp', estadop='$estadop', paisp='$paisp', cpp='$cpp', curp='$curp', idusuario = $idasesor, diasapagar='$diasapagar' where idduenio=$id";
		$operacion = mysql_query($sql);
		
			$aud->tabla="duenio";
			$aud->idtabla=$id;
			$aud->accion = 2;
			$aud->usuario =$misesion->usuario;
			$aud->crearregistro();		

		$sql = "delete from contacto where idduenio = $id";
		$operacion = mysql_query($sql);
		
		if($tell !="" || $tell=null)
		{
		
			$ld = split("[|]", substr($tell,0,-1));
			$i=0;
			foreach ($ld as $idd)
			{
				$dats = split("[*]", $idd);
				if($dats[3]=="" || $dats[3]==null){$dats[3]=false;}
				$sql1="insert into contacto (idduenio, idtipocontacto,contacto, notac)values($id," . $dats[0] . ",'" . $dats[1] . "','" . $dats[2] . "')";
				$operacion1 = mysql_query($sql1);

			}

		}
		
		if($maill !="" || $maill=null)
		{
			$ld = split("[|]", substr($maill,0,-1));
			$i=0;
			foreach ($ld as $idd)
			{
				$dats = split("[*]", $idd);
				if($dats[3]=="" || $dats[3]==null){$dats[3]=false;}
				$sql1="insert into contacto (idduenio, idtipocontacto,contacto, notac, usar)values($id," . $dats[0] . ",'" . $dats[1] . "','" . $dats[2] . "'," . $dats[3] . ")";
				$operacion1 = mysql_query($sql1);

			}		
		
		
		}		
		
		$sql="";
		
		
		///echo "<br>Actualizo";
		$nombre="";
		$nombre2="";
		$apaterno="";
		$amaterno="";
		$tel="";
		$usuario="";
		$pwd="";
		$emaild="";
		$rfcd="";
		$banco="";
		$titularbanco="";
		$cuenta="";
		$clabe="";
		$called="";
		$noexteriord="";
		$nointeriord="";
		$coloniad="";
		$delmund="";
		$estadod="Distrito Federal";
		$paisd="MEXICO";	
		$cpd="";
		$diasapagar="";
		
		
		$callep="";
		$noexteriorp="";
		$nointeriorp="";
		$coloniap="";
		$delmunp="";
		$estadop="Distrito Federal";
		$paisp="MEXICO";
		$cpp="";
		$curp="";
		$idasesor="0";			
		
		
		//$porcentaje="";
		$honorarios="";
		$iva="";	
		$tell ="";
		$maill ="";	

	}

	//ejecuto la consulta si tiene algo en la variable
	//echo $sql;
	
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
					$listat .="<tr><td>" . $row1["contacto"] . "</td><td>" . $row1["notac"] . "</td><td><input type='button' value='X' onClick = \"cargarSeccion('$ruta/contactoduenio.php','teldiv','acumulado=' + tell.value + '&dato=" . $row1["contacto"] . "&operacion=2&tipoc=1')\"></td></tr>";
				
				}
				else
				{
					$maill .="2*" . $row1["contacto"] . "*" . $row1["notac"] . "*" . $row1["usar"] . "|";	
					$listam .="<tr><td>" . $row1["contacto"] . "</td><td>" . $row1["notac"] . "</td><td>" . $row1["usar"] . "</td><td><input type='button' value='X' onClick = \"cargarSeccion('$ruta/contactoduenio.php','maildiv','acumulado=' + maill.value + '&dato=" . $row1["contacto"] . "&operacion=2&tipoc=2')\"></td></tr>";
			
				}


			}
			

			$listat .="</table>";
			$listam .="</table>";
			//$listat .="<input type='hidden' name='tell' id='tell' value='$acumulado'/>";
			//$listam .="<input type='hidden' name='maill' id='maill' value='$acumulado'/>";
			$tell = $listat . "<input type='hidden' name='tell' id='tell' value='$tell'/>";
			$maill = $listam . "<input type='hidden' name='maill' id='maill' value='$maill'/>";
			
			$btncta = "";
			
			
	$sqlc="select * from dueniodistribucion where idduenio = $id ";
	$datosc=mysql_query($sqlc);
	//echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	$listac = "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Banco</th><th>Cuenta</th><th>Clabe</th><th>IdBanorte</th><th>Accion</th></tr>";
	while($rowc = mysql_fetch_array($datosc))
	{
		$html = "<tr><td>" . $rowc["iddueniodistribucion"] . "</td><td>" . $rowc["nombre"] . "</td><td>" . $rowc["banco"] . "</td><td>" . $rowc["cuenta"] . "</td><td>" . $rowc["clabe"] . "</td><td>" . $rowc["idbanco"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/ctasduenio.php','listacuentas','accion=2&id=$id&idc=" .  $rowc["iddueniodistribucion"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/lctaduenio.php','ctaform','accion=3&id= $id &idc=" .  $rowc["iddueniodistribucion"]  . "' )\" $txteditar>";
		$html .= "</td></tr>";
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
	$listaap = "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Tel.</th><th>mail</th><th>Facultades</th><th>Accion</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$listaap .= "<tr><td>" . $row["idapoderado"] . "</td><td>" . $row["nombreap"] . " " . $row["nombre2ap"] . " " . $row["apaternoap"] . " " . $row["amaternoap"]. "</td><td>" . $row["telap"] . "</td><td>" . $row["mailap"] . "</td><td>" . $row["facultades"] . "</td><td>";
		$listaap .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/apoderados.php','listaap','accion=2&id=$id&idc=" .  $row["idapoderado"]  . "' )}\" $txtborrar>";
		$listaap .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/apoderados.php','apoderadoform','accion=4&id=$id&idc=" .  $row["idapoderado"]  . "' )\" $txteditar>";
		$listaap .= "</td></tr>";
		//echo CambiaAcentosaHTML($html);
	}
	$listaap .="</table>";			
	$listaap = CambiaAcentosaHTML($listaap);		
			
			
			
						
			
			
					
		}



	}
	else
	{
		$accion="0";
	}




	if(!$tell or $tell=="")
	{
		$tell="<input type='hidden' name='tell' id='tell' value=''/>";
	}

	if(!$maill or $maill=="")
	{
		$maill="<input type='hidden' name='tmaill' id='maill' value=''/>";
	}

	
	if(!$idasesor)
	{
		$idasesor = $misesion->usuario;
	}
	$sql="select * from usuario where activo = true";

	$agenteselect = "Agente <select name=\"idasesor\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		//if ($idduenio>0)
		if ($idasesor==$row["idusuario"])
		{
			$seleccionopt=" SELECTED ";
		}
		$agenteselect .= "<option value=\"" . $row["idusuario"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"]  ) . "</option>";

	}
	$agenteselect .="</select>";	
	
	if($diasapagar=="")
	{
		$diasapagar="Dentro de los 10 primeros dias siguientes al mes que corresponda";
	}

	$desact="";
	if ($id)
	{
		$desact=" disabled ";
	}



	$btnaud="";
	if($aud->id!=0)
	{
		$btnaud = "<input type='button' value='Imprimir movimiento' onClick=\"window.open('$ruta/impresiontransaccion.php?id=$aud->id&seccion=Propietario');\"";
	
	}	
	
//Genero el formulario de los submodulos

echo  <<<formulario1
<center>
<h1>Due&ntilde;os</h1>
$btnaud
<form>
$agenteselect
<table border="0">

<tr>
	
	<td  align="center" valign="top">
	<fieldset style="background-color:#e0f8f5"><legend>Datos generales</legend>
	<table border = "0">


<tr>
	<td>Nombre / Empresa (Completo):</td>
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
	<td><input type="text" name="rfcd" value="$rfcd"></td>
</tr>
<tr>
	<td>CURP:</td>
	<td><input type="text" name="curp" value="$curp"></td>
</tr>
<!--
<tr>
	<td>Tel&eacute;fono:</td>
	<td><input type="text" name="tel" value="$tel"></td>
</tr>
<tr>
	<td>e-mail:</td>
	<td><input type="text" name="emaild" value="$emaild"></td>
</tr>

<tr>
	<td>Porcentaje:</td>
	<td><input type="text" name="porcentaje" value="$porcentaje"></td>
</tr>
-->
<tr>
	<td>Honorarios(%):</td>
	<td><input type="text" name="honorarios" value="$honorarios"></td>
</tr>
<tr>
	<td>I.V.A.(%):</td>
	<td><input type="text" name="iva" value="$iva"></td>
</tr>
<tr>
	<td>Dias a pagar:</td>
	<td><input type="text" name="diasapagar" value="$diasapagar"></td>
</tr>
<tr>
	<td>Usuario:</td>
	<td><input type="text" name="usuario" value="$usuario"></td>
</tr>
<tr>
	<td>Contrase&ntilde;a:</td>
	<td><input type="password" name="pwd" value="$pwd"></td>
</tr>
	</table>
	</fieldset>

	<fieldset><legend>Tel&eacute;fonos</legend>
	<table border = "0">	
	<tr>
		<td valign="top">Tel.</td>
		<td>
			<input type="text" name="teln" value="">
		</td>
	</tr>
		<tr>
		<td valign="top">Notas</td>
		<td>
			<textarea name="notasn" cols="30" rows="2"></textarea>
		</td>
	</tr>
	<tr>
		<td colspan = "2" align = "center">
 		
		<input type="button" value="Agregar" onClick="if(teln.value !=''){cargarSeccion('$ruta/contactoduenio.php','teldiv','acumulado=' + tell.value + '&contacto=' + teln.value  + '&notac=' + notasn .value + '&operacion=1&tipoc=1');}; notasn.value=''; teln.value='';">
		</td>
	</tr>		
	<tr>
		<td valign="top" align="center" colspan="2">
			<div id='teldiv'>$tell</div>
		</td>

	</tr>	
	</table>
	</fieldset>
	
	<fieldset><legend>Correos</legend>
	<table border = "0">	
	<tr>
		<td valign="top">Correo</td>
		<td>
			<input type="text" name="mailn" value="">
		</td>
	</tr>
		<tr>
		<td valign="top">Notas</td>
		<td>
			<textarea name="notasm" cols="30" rows="2"></textarea>
		</td>
	</tr>
	
	<tr>
		<td valign="top">Ocupar</td>
		<td>
			<input type="checkbox" name="ocupar" value="">
		</td>
	</tr>	
	<tr>
		<td colspan = "2" align = "center">
 		
		<input type="button" value="Agregar" onClick="if(mailn.value !=''){cargarSeccion('$ruta/contactoduenio.php','maildiv','acumulado=' + maill.value + '&contacto=' + mailn.value  + '&notac=' + notasm.value + '&ocupar=' + ocupar.checked + '&operacion=1&tipoc=2');};ocupar.checked = false; notasm.value=''; mailn.value='';">
		</td>
	</tr>		
	<tr>
		<td valign="top" align="center" colspan="2">
			<div id='maildiv'>$maill</div>
		</td>

	</tr>	
	</table>
	</fieldset>	
	
	
	
<fieldset><legend>Apoderados</legend>
	<div id="apoderadoform">
	
	<table border = "0">

<tr>
	<td>Nombre:</td>
	<td><input type="text" name="nombreap" value="$nombreap"></td>
</tr>
<tr>
	<td>Segundo nombre:</td>
	<td><input type="text" name="nombre2ap" value="$nombre2ap"></td>
</tr>
<tr>
	<td>A. paterno:</td>
	<td><input type="text" name="apaternoap" value="$amaternoap"></td>
</tr>
<tr>
	<td>Ap. materno:</td>
	<td><input type="text" name="amaternoap" value="$apaternoap"></td>
</tr>
<tr>
	<td>Telefono:</td>
	<td><input type="text" name="telap" value="$telap"></td>
</tr>
<tr>
	<td>Correo:</td>
	<td><input type="text" name="mailap" value="$mailap"></td>
</tr>
<tr>
	<td>Facultades:</td>
	<td><textarea name="facultades" cols='30' rows='4'></textarea></td>
</tr>

<tr>
	<td colspan="2" align="center">
	<input type="button" value="Agregar" name="ctabtn" $btncta onClick="if(nombreap.value!='' ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$ruta/apoderados.php','listaap','accion=' + accionc.value + '&id=' + ids.value + '&nombreap=' + nombreap.value + '&nombre2ap=' + nombre2ap.value + '&apaternoap=' + apaternoap.value + '&amaternoap=' + amaternoap.value +  '&telap=' + telap.value + '&mailap=' + mailap.value   + '&fcultades=' + facultades.value  )};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$ruta/apoderados.php','listaap','accion=1&id=' + ids.value + '&nombreap=' + nombreap.value + '&nombre2ap=' + nombre2ap.value + '&apaternoap=' + apaternoap.value + '&amaternoap=' + amaternoap.value +  '&telap=' + telap.value + '&mailap=' + mailap.value   + '&facultades=' + facultades.value ); nombreap.value='';nombre2ap.value='';apaternoap.value='';amaternoap.value='';telap.value='';mailap.value='';facultades.value='';}};">
	<input type="hidden" name="accionc" value="1">
	</td>
</tr>
	</table>
	</div>
	
	<div id="listaap"> $listaap </div>
	</fieldset>		
	
	
	
	
	
	
	
	
	
	
</td>
<td  align="center" valign = "top">
	<fieldset style="background-color:#e0f8f5"><legend>Domicilio particular</legend>
	<table border = "0">

<tr>
	<td>Calle:</td>
	<td><input type="text" name="callep" value="$callep" onChange = "called.value=this.value"></td>
</tr>
<tr>
	<td>No. ext.:</td>
	<td><input type="text" name="noexteriorp" value="$noexteriorp" onChange = "noexteriord.value=this.value"></td>
</tr>
<tr>
	<td>No. Int.:</td>
	<td><input type="text" name="nointeriorp" value="$nointeriorp" onChange = "nointeriord.value=this.value"></td>
</tr>
<tr>
	<td>Colonia:</td>
	<td><input type="text" name="coloniap" value="$coloniap" onChange = "coloniad.value=this.value"></td>
</tr>
<tr>
	<td>Deleg./Municipio:</td>
	<td><input type="text" name="delmunp" value="$delmunp" onChange = "delmund.value=this.value"></td>
</tr>
<tr>
	<td>Estado:</td>
	<td><input type="text" name="estadop" value="$estadop" onChange = "estadod.value=this.value"></td>
</tr>
<tr>
	<td>Pa&iacute;s:</td>
	<td><input type="text" name="paisp" value="$paisp" onChange = "paisd.value=this.value"></td>
</tr>
<tr>
	<td>C.P.:</td>
	<td><input type="text" name="cpp" value="$cpp" onChange = "cpd.value=this.value"></td>
</tr>
	</table>
	</fieldset>
	
	<fieldset style="background-color:#e0f8f5"><legend>Domicilio Fiscal</legend>
	<table border = "0">

<tr>
	<td>Calle:</td>
	<td><input type="text" name="called" value="$called"></td>
</tr>
<tr>
	<td>No. ext.:</td>
	<td><input type="text" name="noexteriord" value="$noexteriord"></td>
</tr>
<tr>
	<td>No. Int.:</td>
	<td><input type="text" name="nointeriord" value="$nointeriord"></td>
</tr>
<tr>
	<td>Colonia:</td>
	<td><input type="text" name="coloniad" value="$coloniad"></td>
</tr>
<tr>
	<td>Deleg./Municipio:</td>
	<td><input type="text" name="delmund" value="$delmund"></td>
</tr>
<tr>
	<td>Estado:</td>
	<td><input type="text" name="estadod" value="$estadod"></td>
</tr>
<tr>
	<td>Pa&iacute;s:</td>
	<td><input type="text" name="paisd" value="$paisd"></td>
</tr>
<tr>
	<td>C.P.:</td>
	<td><input type="text" name="cpd" value="$cpd"></td>
</tr>
	</table>
	</fieldset>	
	
<input name="aplicard" id="aplicard" type = "button" value = "Aplicar" $desact onClick="if(nombre.value!='' ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&nombre2=' + nombre2.value +  '&usuario=' + usuario.value + '&pwd=' + pwd.value   + '&rfcd=' + rfcd.value  + '&banco=' + banco.value  + '&titularbanco=' + titularbanco.value  + '&cuenta=' + cuenta.value  + '&clabe=' + clabe.value  + '&called=' + called.value  + '&noexteriord=' + noexteriord.value  + '&nointeriord=' + nointeriord.value  + '&coloniad=' + coloniad.value  + '&delmund=' + delmund.value  + '&estadod=' + estadod.value  + '&paisd=' + paisd.value  + '&cpd=' + cpd.value  +  '&honorarios=' + honorarios.value + '&iva=' + iva.value + '&callep=' + callep.value  + '&noexteriorp=' + noexteriorp.value  + '&nointeriorp=' + nointeriorp.value  + '&coloniap=' + coloniap.value  + '&delmunp=' + delmunp.value  + '&estadop=' + estadop.value  + '&paisp=' + paisp.value  + '&cpp=' + cpp.value   + '&curp=' + curp.value  + '&tell=' + tell.value + '&maill=' + maill.value +  '&idasesor=' + idasesor.value +  '&diasapagar=' + diasapagar.value )};if(this.value=='Aplicar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&nombre2=' + nombre2.value +  '&usuario=' + usuario.value + '&pwd=' + pwd.value    + '&rfcd=' + rfcd.value  + '&banco=' + banco.value  + '&titularbanco=' + titularbanco.value  + '&cuenta=' + cuenta.value  + '&clabe=' + clabe.value  + '&called=' + called.value  + '&noexteriord=' + noexteriord.value  + '&nointeriord=' + nointeriord.value  + '&coloniad=' + coloniad.value  + '&delmund=' + delmund.value  + '&estadod=' + estadod.value  + '&paisd=' + paisd.value  + '&cpd=' + cpd.value   + '&honorarios=' + honorarios.value + '&iva=' + iva.value + '&callep=' + callep.value  + '&noexteriorp=' + noexteriorp.value  + '&nointeriorp=' + nointeriorp.value  + '&coloniap=' + coloniap.value  + '&delmunp=' + delmunp.value  + '&estadop=' + estadop.value  + '&paisp=' + paisp.value  + '&cpp=' + cpp.value   + '&curp=' + curp.value + '&tell=' + tell.value + '&maill=' + maill.value +  '&aplicar=1' +  '&idasesor=' + idasesor.value +  '&diasapagar=' + diasapagar.value )}};" >	

<fieldset><legend>Datos de transferencia</legend>
	<div id="ctaform">
	
	<table border = "0">

<tr>
	<td>Banco:</td>
	<td><input type="text" name="banco" value="$banco"></td>
</tr>
<tr>
	<td>Titular en el banco:</td>
	<td><input type="text" name="titularbanco" value="$titularbanco"></td>
</tr>
<tr>
	<td>RFC:</td>
	<td><input type="text" name="rfcc" value="$rfcc"></td>
</tr>
<tr>
	<td>Cuenta:</td>
	<td><input type="text" name="cuenta" value="$cuenta"></td>
</tr>
<tr>
	<td>CLABE:</td>
	<td><input type="text" name="clabe" value="$clabe"></td>
</tr>
<tr>
	<td>ID Banorte:</td>
	<td><input type="text" name="idbanco" value="$idbanco"></td>
</tr>
<tr>
	<td>Porcentaje(%):</td>
	<td><input type="text" name="porcentaje" value="$porcentaje"></td>
</tr>
<tr>
	<td>Notas:</td>
	<td><textarea name="notac" cols="20" rows="4"></textarea></td>
</tr>
<tr>
	<td colspan="2" align="center">
	<input type="button" value="Agregar" name="ctabtn" $btncta onClick="if(titularbanco.value!='' ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$ruta/ctasduenio.php','listacuentas','accion=2&id=' + ids.value + '&banco=' + banco.value + '&titular=' + titularbanco.value + '&rfcc=' + rfcc.value + '&cuenta=' + cuenta.value +  '&clabe=' + clabe.value + '&porcentaje=' + porcentaje.value   + '&notas=' + notac.value + '&idbanco=' + idbanco.value )};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$ruta/ctasduenio.php','listacuentas','accion=1&id=' + ids.value + '&banco=' + banco.value + '&titular=' + titularbanco.value + '&rfcc=' + rfcc.value + '&cuenta=' + cuenta.value +  '&clabe=' + clabe.value + '&porcentaje=' + porcentaje.value   + '&notas=' + notac.value + '&idbanco=' + idbanco.value ); ;banco.value='';titularbanco.value='';rfcc.value='';cuenta.value='';clabe.value='';porcentaje.value='';notac.value='';idbanco.value=''}};">
	<input type="hidden" name="accionc" value="1">
	</td>
</tr>
	</table>
	</div>
	
	<div id="listacuentas"> $listac </div>
	</fieldset>	
	
	
	
	</td>
	
	

	
</tr>


<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';nombre.value='';nombre2.value='';apaterno.value='';amaterno.value='';usuario.value='';pwd.value='';rfcd.value='';banco.value='';titularbanco.value='';cuenta.value='';clabe.value='';called.value='';noexteriord.value='';nointeriord.value='';coloniad.value='';delmund.value='';estadod.value='Distrito Federal';paisd.value='MEXICO';cpd.value='';honorarios.value='10';iva.value='16';callep.value='';noexteriorp.value='';nointeriorp.value='';coloniap.value='';delmunp.value='';estadop.value='Distrito Federal';paisp.value='MEXICO';cpp.value='';curp.value='';ctabtn.disabled = true;document.getElementById('teldiv').innerHTML='';document.getElementById('maildiv').innerHTML='';document.getElementById('listacuentas').innerHTML='';document.getElementById('listaap').innerHTML='';idasesor=0; nombreap.value='';nombre2ap.value='';apaternoap.value='';amaternoap.value='';telap.value='';mailap.value='';facultades.value='';diasapagar.value='Dentro de los 10 primeros dias siguientes al mes que corresponda';aplicard.disabled = false">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(nombre.value!='' ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&nombre2=' + nombre2.value +  '&usuario=' + usuario.value + '&pwd=' + pwd.value   + '&rfcd=' + rfcd.value  + '&banco=' + banco.value  + '&titularbanco=' + titularbanco.value  + '&cuenta=' + cuenta.value  + '&clabe=' + clabe.value  + '&called=' + called.value  + '&noexteriord=' + noexteriord.value  + '&nointeriord=' + nointeriord.value  + '&coloniad=' + coloniad.value  + '&delmund=' + delmund.value  + '&estadod=' + estadod.value  + '&paisd=' + paisd.value  + '&cpd=' + cpd.value  +  '&honorarios=' + honorarios.value + '&iva=' + iva.value + '&callep=' + callep.value  + '&noexteriorp=' + noexteriorp.value  + '&nointeriorp=' + nointeriorp.value  + '&coloniap=' + coloniap.value  + '&delmunp=' + delmunp.value  + '&estadop=' + estadop.value  + '&paisp=' + paisp.value  + '&cpp=' + cpp.value   + '&curp=' + curp.value  + '&tell=' + tell.value + '&maill=' + maill.value +  '&idasesor=' + idasesor.value +  '&diasapagar=' + diasapagar.value  )};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&nombre=' + nombre.value + '&apaterno=' + apaterno.value + '&amaterno=' + amaterno.value + '&nombre2=' + nombre2.value +  '&usuario=' + usuario.value + '&pwd=' + pwd.value    + '&rfcd=' + rfcd.value  + '&banco=' + banco.value  + '&titularbanco=' + titularbanco.value  + '&cuenta=' + cuenta.value  + '&clabe=' + clabe.value  + '&called=' + called.value  + '&noexteriord=' + noexteriord.value  + '&nointeriord=' + nointeriord.value  + '&coloniad=' + coloniad.value  + '&delmund=' + delmund.value  + '&estadod=' + estadod.value  + '&paisd=' + paisd.value  + '&cpd=' + cpd.value   + '&honorarios=' + honorarios.value + '&iva=' + iva.value + '&callep=' + callep.value  + '&noexteriorp=' + noexteriorp.value  + '&nointeriorp=' + nointeriorp.value  + '&coloniap=' + coloniap.value  + '&delmunp=' + delmunp.value  + '&estadop=' + estadop.value  + '&paisp=' + paisp.value  + '&cpp=' + cpp.value   + '&curp=' + curp.value + '&tell=' + tell.value + '&maill=' + maill.value +  '&idasesor=' + idasesor.value +  '&diasapagar=' + diasapagar.value )}};" >
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
	
	$sql="select * from duenio ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Segundo Nombre</th><th>A. Paterno</th><th>A. Materno</th><th>RFC</th><th>Tel&eacute;fono</th><th>C.P.</th><th>Usuario</th><th>Acciones</th><th>Activar / Desactivar</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$status=$row["activo"];
		if ($status==1) {
			$desactivar="";
			$activos="disabled";
		}
		else{
			$activos="";
			$desactivar="disabled";
		}

		$html = "<tr><td>" . $row["idduenio"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["nombre2"] . "</td><td>" . $row["apaterno"] . "</td><td>" . $row["amaterno"] . "</td><td>" . $row["rfcd"] . "</td><td>" . $row["tel"] . "</td><td>" . $row["cpd"] . "</td><td>" . $row["usuario"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idduenio"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idduenio"]  . "' )\" $txteditar>";
		$html .= "<input type='button' value='Movimientos' onClick=\"window.open('$ruta/movauditoria.php?id=" .  $row["idduenio"]  . "&tabla=duenio');\">";
		// Cambio 30/07/21
		// Se agrega boton para poder modificar el RFC del inquilino, pero solo puede acceder a el 
		// Noemí, Miguel y Yo
		if ($_SESSION['usuario'] == 19 || $_SESSION['usuario'] == 1 || $_SESSION['usuario'] == 3)
			$html .= "<input type=\"button\" value=\"RFC Gen&eacute;rico\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea cambiar el RFC de &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=5&id=" .  $row["idduenio"]  . "' )}\">";
		// Fin Cambio 30/07/21
		$html .= "</td><td><input type='button' value='Activar' onClick=\"cargarSeccion('$dirscript','contenido','accion=4&id=" .  $row["idduenio"]  . "&estado=1' );this.disabled=true\" $activos><input type='button' value='Desactivar' onClick=\"cargarSeccion('$dirscript','contenido','accion=4&id=" .  $row["idduenio"]  . "&estado=0' );this.disabled=true\" $desactivar></td></tr>";
		echo CambiaAcentosaHTML($html);
	}
	echo "</table></div>";





}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>