<?php
//Clase de las sesiones para el sitio


class auditoria
{
	var $tabla;
	var $idtabla;
	var $accion;//1: creado, 2:editado
	var $usuario;//idusuario activo
	



	//***************************************
	//Constructor de objeto
	//***************************************
	function auditoria ()
	{
	
		$this->tabla="";
		$this->idtabla=0;
		$this->accion = 1;
		$this->usuario =0;
		$this->id =0;
		
	}



	//crea el registro
	function crearregistro()
	{
		$fecha=date("Y-m-d");
		$hora =date("H:m:s");
		$sqlacc="insert into cambios (idusuario, idaccionc,tabla,idalterado,fecha,hora) values($this->usuario, $this->accion,'$this->tabla',$this->idtabla,'$fecha','$hora')";
		$operacionacc = mysql_query($sqlacc);
		$this->id = mysql_insert_id();
		
	}
	
	function listamovimientos()
	{
		$sqlacc="select * from cambios c, accionc a, usuario u where c.idusuario = u.idusuario and c.idaccionc = a.idaccionc and tabla = '$this->tabla' and idalterado = $this->idtabla";
		$operacionacc = mysql_query($sqlacc);
		$htmlacc="<table><tr><th>Usuario</th><th>Fecha</th><th>Hora</th><th>Accion</th></tr>";
		
		while($rowacc = mysql_fetch_array($operacionacc))
		{
			
			$htmlacc .="<tr><td>" . $rowacc["nombre"] . " " . $rowacc["apaterno"]  . " " . $rowacc["amaterno"] . "</td><td>" . $rowacc["fecha"] . "</td><td>" . $rowacc["hora"] . "</td><td>" . $rowacc["acccionc"] . "</td></tr>";
		
		}
		return $htmlacc .="</table>";

	}


	//construye salida
	function imprimir()
	{
		$htmlacc="";
		//usuario, fecha y hora
		$sqlacc = "select * from cambios where idcambios = $this->id";
		$operacionacc = mysql_query($sqlacc);
		$rowacc = mysql_fetch_array($operacionacc);
		$this->tabla=$rowacc["tabla"];
		$this->idtabla=$rowacc["idalterado"];		
		$this->usuario =$rowacc["idusuario"];		
		
		$sqlacc = "select * from usuario where idusuario = $this->usuario";
		$operacionacc = mysql_query($sqlacc);
		$rowacc = mysql_fetch_array($operacionacc);
		$nombreu = $rowacc["nombre"] . " " . $rowacc["apaterno"]  . " " . $rowacc["amaterno"];
		
		switch($this->tabla)
		{
		case 'inmueble': //manejar la informacion del inmueble
		
		$sql="select * from inmueble i, estado e, pais p, tipoinmueble t where i.idestado=e.idestado and i.idpais = p.idpais and i.idtipoinmueble = t.idtipoinmueble and idinmueble = $this->idtabla";

		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$btnblo=" disabled ";
			
			$calle=CambiaAcentosaHTML($row["calle"]);
			$noext=CambiaAcentosaHTML($row["numeroext"]);
			$noint=CambiaAcentosaHTML($row["numeroint"]);
			$colonia=CambiaAcentosaHTML($row["colonia"]);
			$delnum=CambiaAcentosaHTML($row["delmun"]);
			$estado=$row["estado"];
			$pais=$row["pais"];
			$cp=CambiaAcentosaHTML($row["cp"]);
			$descripcion=CambiaAcentosaHTML($row["descripcion"]);
			$notas=CambiaAcentosaHTML($row["notas"]);
			$inventario=CambiaAcentosaHTML($row["inventario"]);
			$esta=CambiaAcentosaHTML($row["estacionamiento"]);
			$tel=CambiaAcentosaHTML($row["tel"]);
			$predial=CambiaAcentosaHTML($row["predial"]);
			$tipoinmueble=$row["tipoinmueble"];
			$mts2=$row["mts2"];
			//$idduenio=CambiaAcentosaHTML($row["idduenio"]);
			
			$sqldu="select *,d.idduenio as idd from duenioinmueble di,duenio d where di.idduenio = d.idduenio and idinmueble = $this->idtabla";
			$operaciondu = mysql_query($sqldu);
			$ldu="";
			$pl="";
			$dueniosl = "<table border = '1'>";
			while($rowdu = mysql_fetch_array($operaciondu))
			{
				
				$dueniosl .="<tr><td>" . utf8_decode($rowdu["nombre"] . " " . $rowdu["nombre2"] . " " . $rowdu["apaterno"] . " " . $rowdu["amaterno"]) . "</td><td>" . $rowdu["fechacontrato"] . "</td><td><input name='p_" . $rowdu["idduenio"] . "' type='text' value='" . $rowdu["porcentajed"] . "' size='5'></td></tr>";
			
			}
			$dueniosl .="</table>";
			
			
			$sql="select * from cuentainmueble c, tipocuentai i where c.idtipocuentai = i.idtipocuentai and idinmueble = $this->idtabla order by i.idtipocuentai";
			$datos=mysql_query($sql);
			if(mysql_num_rows($datos)>0)
			{
			$html = "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
			$html .= "<table border=\"1\"><tr><th>Id</th><th>Tipo</th><th>Cuenta</th><th>Notas</th></tr>";
			while($row = mysql_fetch_array($datos))
			{
				$html .= "<tr><td>" . $row["idcuentainmueble"] . "</td><td>" . $row["tipocuentai"] . "</td><td>" . $row["cuentainmueble"] . "</td><td>" . $row["notaci"] . "</td></tr>";
				$html = CambiaAcentosaHTML($html);
			}
			$html .=  "</table></div>";	
			}			
			
			
		}
		
		$htmlin ="<table border= '0'>";
		//$htmlin .= "<tr><th>Due&ntilde;o(s)</th><td>$dueniosl </td></tr>"; 
		$htmlin .= "<tr><th rowspan='18' width='114'>&nbsp;</th><th>Due&ntilde;o(s)</th><td>$dueniosl </td></tr>"; 
		$htmlin .= "<tr><th>Cale</th><td>$calle</td></tr>"; 
		$htmlin .= "<tr><th>No. Exterior</th><td>$noext</td></tr>"; 
		$htmlin .= "<tr><th>No. Interior</th><td>$noint</td></tr>"; 
		$htmlin .= "<tr><th>Colonia</th><td>$colonia</td></tr>"; 
		$htmlin .= "<tr><th>Alc./Mun.</th><td>$delnum</td></tr>"; 
		//pais, consultar el pais
		$htmlin .= "<tr><th>Pa&iacute;s</th><td>$pais</td></tr>";
		//estado, consultar el estado 
		$htmlin .= "<tr><th>Estado</th><td>$estado</td></tr>"; 
		$htmlin .= "<tr><th>C.P.</th><td>$cp</td></tr>"; 
		$htmlin .= "<tr><th>Descripcion</th><td>$descripcion</td></tr>"; 
		$htmlin .= "<tr><th>Notas</th><td>$notas</td></tr>"; 
		$htmlin .= "<tr><th>Inventario</th><td>$inventario</td></tr>"; 
		$htmlin .= "<tr><th>Estacionamiento</th><td>$esta</td></tr>"; 
		$htmlin .= "<tr><th>Tel.</th><td>$tel</td></tr>"; 
		$htmlin .= "<tr><th>Predial</th><td>$predial</td></tr>"; 
		//tipo de inmueble
		$htmlin .= "<tr><th>Tipo de inmueble</th><td>$tipoinmueble</td></tr>";
		$htmlin .= "<tr><th>Mts.<sup>2</sup></th><td>$mts2</td></tr>"; 
		$htmlin .= "<tr><th>Cuentas asociadas</sup></th><td>$html</td></tr>"; 	
		$htmlin .= "</table>";  	
		
		
		
		
			break;
		case 'duenio':
		
		
		$sql="select * from duenio where idduenio = $this->idtabla";
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
			$listat="<table border='1' width='100%'>";
			$listam="<table border='1' width='100%'>";
			$sql1 = "select * from contacto where idduenio = $this->idtabla";
			$operacion1 = mysql_query($sql1);
			
			while($row1 = mysql_fetch_array($operacion1))
			{
			
				
				if($row1["idtipocontacto"] == 1)
				{
					
					$listat .="<tr><td>" . $row1["contacto"] . "</td><td>" . $row1["notac"] . "</td></tr>";
				
				}
				else
				{

					$listam .="<tr><td>" . $row1["contacto"] . "</td><td>" . $row1["notac"] . "</td><td>" . $row1["usar"] . "</td></tr>";
			
				}


			}
			

			$listat .="</table>";
			$listam .="</table>";
			//$listat .="<input type='hidden' name='tell' id='tell' value='$acumulado'/>";
			//$listam .="<input type='hidden' name='maill' id='maill' value='$acumulado'/>";
			$tell = $listat . "<input type='hidden' name='tell' id='tell' value='$tell'/>";
			$maill = $listam . "<input type='hidden' name='maill' id='maill' value='$maill'/>";
			
			$btncta = "";
			
			
			$sqlc="select * from dueniodistribucion where idduenio = $this->idtabla ";
			$datosc=mysql_query($sqlc);
			//echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
			$listac = "<table border=\"1\" width='100%'><tr><th>Id</th><th>Nombre</th><th>Banco</th><th>Cuenta</th><th>Clabe</th><th>ID Banorte</th></tr>";
			while($rowc = mysql_fetch_array($datosc))
			{
				$html = "<tr><td>" . $rowc["iddueniodistribucion"] . "</td><td>" . $rowc["nombre"] . "</td><td>" . $rowc["banco"] . "</td><td>" . $rowc["cuenta"] . "</td><td>" . $rowc["clabe"] . "</td><td>" . $rowc["idbanco"] . "</td>";
				//$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/ctasduenio.php','listacuentas','accion=2&id=$id&idc=" .  $rowc["iddueniodistribucion"]  . "' )}\" $txtborrar>";
				//$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/lctaduenio.php','ctaform','accion=3&id= $id &idc=" .  $rowc["iddueniodistribucion"]  . "' )\" $txteditar>";
				$html .= "</tr>";
				$listac .= CambiaAcentosaHTML($html);
			}
			$listac .= "</table>";			
					
			
			$sqldu="select *,d.idduenio as idd from duenioinmueble di,duenio d where di.idduenio = d.idduenio and idinmueble = $this->idtabla";
			$operaciondu = mysql_query($sqldu);
			$ldu="";
			$pl="";
			$dueniosl = "<table border = '1' width='100%'>";
			while($rowdu = mysql_fetch_array($operaciondu))
			{
				$ldu .= $rowdu["idd"] . "|";
				$pl .= "&p_" . $rowdu["idd"] . "=p_" . $rowdu["idd"] . ".value";
				$dueniosl .="<tr><td>" . utf8_decode($rowdu["nombre"] . " " . $rowdu["nombre2"] . " " . $rowdu["apaterno"] . " " . $rowdu["amaterno"]) . "</td><td><input name='p_" . $rowdu["idduenio"] . "' type='text' value='" . $rowdu["porcentajed"] . "' size='5'></td></tr>";
			
			}
			$dueniosl .="</table><input type='hidden' name='acumulado' id='acumulado' value='$ldu'/>";
			
			
			
			$sql="select * from apoderado where idduenio = $this->idtabla ";
			$datos=mysql_query($sql);		
			//echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
			$listaap = "<table border=\"1\" width='100%'><tr><th>Id</th><th>Nombre</th><th>Tel.</th><th>mail</th><th>Facultades</th></tr>";
			while($row = mysql_fetch_array($datos))
			{
				$listaap .= "<tr><td>" . $row["idapoderado"] . "</td><td>" . $row["nombreap"] . " " . $row["nombre2ap"] . " " . $row["apaternoap"] . " " . $row["amaternoap"]. "</td><td>" . $row["telap"] . "</td><td>" . $row["mailap"] . "</td><td>" . $row["facultades"] . "</td>";
				//$listaap .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/apoderados.php','listaap','accion=2&id=$id&idc=" .  $row["idapoderado"]  . "' )}\" $txtborrar>";
				//$listaap .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/apoderados.php','apoderadoform','accion=4&id=$id&idc=" .  $row["idapoderado"]  . "' )\" $txteditar>";
				$listaap .= "</tr>";
				//echo CambiaAcentosaHTML($html);
			}
			$listaap .="</table>";			
			$listaap = CambiaAcentosaHTML($listaap);	
		}

		$htmlin ="<table border= '0'>";
		//$htmlin .= "<tr><th>Nombre</th><td>$nombre $nombre2 $apaterno $amaterno</td></tr>"; 
		$htmlin .= "<tr><th rowspan='34' width='114'>&nbsp;</th><th>Nombre</th><td>$nombre $nombre2 $apaterno $amaterno</td></tr>"; 
		$htmlin .= "<tr><th>RFC</th><td>$rfcd</td></tr>"; 
		$htmlin .= "<tr><th>CURP</th><td>$curp</td></tr>"; 
		$htmlin .= "<tr><th>Honorario</th><td>$honorarios</td></tr>"; 
		$htmlin .= "<tr><th>IVA</th><td>$iva</td></tr>"; 
		$htmlin .= "<tr><th>Dias a pagar</th><td>$diasapagar</td></tr>"; 
		$htmlin .= "<tr><th>Usuario</th><td>$usuario</td></tr>";
		$htmlin .= "<tr><th>Contrase&ntilde;a</th><td>$pwd</td></tr>"; 
		
		$htmlin .= "<tr><th colspan = '2'>Domicilio particular</th></tr>"; 
		$htmlin .= "<tr><th>Calle</th><td>$called</td></tr>"; 
		$htmlin .= "<tr><th>No. Exterior</th><td>$noexteriord</td></tr>"; 
		$htmlin .= "<tr><th>No. Interior</th><td>$nointeriord</td></tr>"; 
		$htmlin .= "<tr><th>Colonia</th><td>$coloniad</td></tr>"; 
		$htmlin .= "<tr><th>Alc./Mun.</th><td>$delmund</td></tr>"; 
		$htmlin .= "<tr><th>Estado</th><td>$estadod</td></tr>"; 
		$htmlin .= "<tr><th>Pais</th><td>$paisd</td></tr>"; 
		$htmlin .= "<tr><th>C.P.</th><td>$cpd</td></tr>";
		
		$htmlin .= "<tr><th colspan = '2'>Domicilio fiscal</th></tr>"; 
		$htmlin .= "<tr><th>Calle</th><td>$callep</td></tr>"; 
		$htmlin .= "<tr><th>No. Exterior</th><td>$noexteriorp</td></tr>"; 
		$htmlin .= "<tr><th>No. Interior</th><td>$nointeriorp</td></tr>"; 
		$htmlin .= "<tr><th>Colonia</th><td>$coloniap</td></tr>"; 
		$htmlin .= "<tr><th>Alc./Mun.</th><td>$delmunp</td></tr>"; 
		$htmlin .= "<tr><th>Estado</th><td>$estadop</td></tr>"; 
		$htmlin .= "<tr><th>Pais</th><td>$paisd</td></tr>"; 
		$htmlin .= "<tr><th>C.P.</th><td>$cpp</td></tr>";
		$htmlin .= "<tr><th colspan = '2'>Telefonos</th></tr>";
		$htmlin .= "<tr><td colspan = '2'>$listat</td></tr>";
		$htmlin .= "<tr><th colspan = '2'>Correos</th></tr>";
		$htmlin .= "<tr><td colspan = '2'>$listam</td></tr>";
		$htmlin .= "<tr><th colspan = '2'>Datos de Transferencia</th></tr>";
		$htmlin .= "<tr><td colspan = '2'>$listac</td></tr>";
		$htmlin .= "<tr><th colspan = '2'>Apoderados</th></tr>";
		$htmlin .= "<tr><td colspan = '2'>$listaap</td></tr>";	
		$htmlin .= "</table>";  		
	
		
			
		
			break;
			
		
		case 'fiador':// datos del fiador
		
		$sql="select * from fiador f, estado e  where f.idestado = e.idestado and idfiador = $this->idtabla ";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"]);
			$nombre2=CambiaAcentosaHTML($row["nombre2"]);
			$apaterno=CambiaAcentosaHTML($row["apaterno"]);
			$amaterno=CambiaAcentosaHTML($row["amaterno"]);
			$identificacion=CambiaAcentosaHTML($row["identificacion"]);
			$datosinmueble=CambiaAcentosaHTML($row["datosinmueble"]);
			$tel=CambiaAcentosaHTML($row["tel"]);
			$direccion=CambiaAcentosaHTML($row["direccion"]);
			$delegacion = CambiaAcentosaHTML($row["delegacion"]);
			$colonia = CambiaAcentosaHTML($row["colonia"]);
			$cp= CambiaAcentosaHTML($row["cp"]);
			$estado = $row["estado"];
			$emailf =$row["email"];
	
		}

		$htmlin ="<table border= '0'>";
		//$htmlin .= "<tr><th>Nombre</th><td>$nombre $nombre2 $apaterno $amaterno</td></tr>";
		$htmlin .= "<tr><th rowspan='9' width='114'>&nbsp;</th><th>Nombre</th><td>$nombre $nombre2 $apaterno $amaterno</td></tr>";
		$htmlin .= "<tr><th>Identificaci&oacute;n</th><td>$identificacion</td></tr>"; 
		$htmlin .= "<tr><th>Datosinmueble</th><td>$datosinmueble</td></tr>"; 
		$htmlin .= "<tr><th>Tel</th><td>$tel</td></tr>"; 
		$htmlin .= "<tr><th>Direccion</th><td>$direccion</td></tr>"; 
		$htmlin .= "<tr><th>Alc./Mun.</th><td>$delegacion</td></tr>"; 
		//estado, consultar el estado 
		$htmlin .= "<tr><th>Estado</th><td>$estado</td></tr>"; 
		$htmlin .= "<tr><th>C.P.</th><td>$cp</td></tr>"; 
		$htmlin .= "<tr><th>e-mail</th><td>$emailf</td></tr>"; 
		$htmlin .= "</table>";  	
		
			
			
			break;
		case 'inquilino':
		
		$sql="select * from inquilino i, estado e where i.idestadoi = e.idestado and idinquilino = $this->idtabla";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"]);
			$nombre2=CambiaAcentosaHTML($row["nombre2"]);
			$apaterno=CambiaAcentosaHTML($row["apaterno"]);
			$amaterno=CambiaAcentosaHTML($row["amaterno"]);
			$nombrenegocio=CambiaAcentosaHTML($row["nombrenegocio"]);
			$gironegocio=CambiaAcentosaHTML($row["gironegocio"]);
			$tel=CambiaAcentosaHTML($row["tel"]);
			$rfc=CambiaAcentosaHTML($row['rfc']);
			$direccionf=CambiaAcentosaHTML($row['direccionf']);
			$email=CambiaAcentosaHTML($row['email']);
			$email1=CambiaAcentosaHTML($row['email1']);
			$email2=CambiaAcentosaHTML($row['email2']);
			$delegacion = CambiaAcentosaHTML($row["delegacion"]);
			$colonia = CambiaAcentosaHTML($row["colonia"]);
			$cp= CambiaAcentosaHTML($row["cp"]);
			$estado = $row["estado"];
			
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
			
			$sql="select * from pais where idpais = $nacionalidad";
			$operacion1 = mysql_query($sql);
			$row1 = mysql_fetch_array($operacion1);
			$nacionalidad=$row1['pais'];
			
			$sql="select * from actividadeconomica where idactividadeconomica= $actividad";
			$operacion1 = mysql_query($sql);
			$row1 = mysql_fetch_array($operacion1);
			$actividad=$row1['actividadeconomica'];			
						
			$sql="select * from girocomercial where idgirocomercial = $giro";
			$operacion1 = mysql_query($sql);
			$row1 = mysql_fetch_array($operacion1);
			$giro=$row1['girocomercial'];	

		}
				
		//hacer tabla de accionistas
		/*
		if($idi!="")
		{
			$sql="select * from accionista where idinquilino = $idi ";
			$datos=mysql_query($sql);
			echo "<center><div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
			$html = "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Segundo Nombre</th><th>A. Paterno</th><th>A. Materno</th><th>Aciones</th></tr>";
			while($row = mysql_fetch_array($datos))
			{
				$html .= "<tr><td>" . $row["idaccionista"] . "</td><td>" . $row["nombreac1"] . "</td><td>" . $row["nombreac2"] . "</td><td>" . $row["apaternoac"] . "</td><td>" . $row["amaterno"] . "</td><td>" . $row["porcentaje"] . "</td><td>";
				$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/accionistas.php','contenido','accion=1&idc=" .  $row["idaccionista"]  . "&idic=$idi' )}\" $txtborrar>";
				$html .= "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$ruta/accionistas.php','contenido','accion=2&idc=" .  $row["idaccionista"]  . "' )\" $txteditar>";
				$html .= "</td></tr>";
				echo CambiaAcentosaHTML($html);
			}
			$html .= "</table></div></center>";
		}
		*/
		
		
		
		$htmlin ="<table border= '0'>";
		//$htmlin .= "<tr><th>Nombre</th><td>$nombre $nombre2 $apaterno $amaterno </td></tr>"; 
		$htmlin .= "<tr><th rowspan='24' width='114'>&nbsp;</th><th>Nombre</th><td>$nombre $nombre2 $apaterno $amaterno </td></tr>"; 
		$htmlin .= "<tr><th>RFC</th><td>$rfc</td></tr>"; 
		$htmlin .= "<tr><th>Nacionalidad</th><td>$nacionalidad</td></tr>";
		$htmlin .= "<tr><th>Nombre Negocio</th><td>$nombrenegocio</td></tr>"; 
		if(strlen($rfc)==12)
		{
			$htmlin .= "<tr><th>Giro comercial</th><td>$giro</td></tr>";
		}
		else
		{
			$htmlin .= "<tr><th>Actividad economica</th><td>$actividad</td></tr>";
		}
		//$htmlin .= "<tr><th>Giro</th><td>$gironegocio</td></tr>"; 
		$htmlin .= "<tr><th>Correo 1</th><td>$email</td></tr>"; 
		$htmlin .= "<tr><th>Correo 2</th><td>$email1</td></tr>"; 
		$htmlin .= "<tr><th>Correo 3</th><td>$email2</td></tr>"; 
		$htmlin .= "<tr><th>Tel.</th><td>$tel</td></tr>"; 

		$htmlin .= "<tr><th>Alcaldia</th><td>$delegacion</td></tr>";
		$htmlin .= "<tr><th>Colonia</th><td>$colonia</td></tr>";
		$htmlin .= "<tr><th>Alcaldia</th><td>$delegacion</td></tr>";
		$htmlin .= "<tr><th>C.P.</th><td>$cp</td></tr>"; 
		//estado, consultar el estado 
		$htmlin .= "<tr><th>Estado</th><td>$estado</td></tr>"; 
		$htmlin .= "<tr><th colspan='2'>Domicilio fiscal</td></tr>"; 
		$htmlin .= "<tr><th>Calle</th><td>$callei</td></tr>"; 
		$htmlin .= "<tr><th>No. exterior</th><td>$noexterior</td></tr>"; 
		$htmlin .= "<tr><th>No. interior</th><td>$nointerior</td></tr>"; 
		$htmlin .= "<tr><th>Colonia</th><td>$coloniai</td></tr>"; 
		$htmlin .= "<tr><th>C.P.</th><td>$cpi</td></tr>"; 
		$htmlin .= "<tr><th>Alc./Mun.</th><td>$delmuni</td></tr>"; 
		$htmlin .= "<tr><th>Localidad</th><td>$localidad</td></tr>"; 
		$htmlin .= "<tr><th>Referencia</th><td>$referencia</td></tr>";
		$htmlin .= "<tr><th>Pa&iacute;s</th><td>$pais</td></tr>"; 
		$htmlin .= "<tr><th>Estado</th><td>$estado</td></tr>"; 	
		//$htmlin .= "<tr><th colspan='2'>Accionistas</td></tr>"; 
		//$htmlin .= "<tr><td>$html</td></tr>"; 
		$htmlin .= "</table>";  	
		
		
		
			break;
			
				
		}

		$htmlin="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Realizado por: $nombreu <br>" . $htmlin;

		return $htmlin;

	}



}

?>