<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


function facturas($idd,$fechag, $txtborrar) {

	$htmlr ="";
	$ligapdf="";
	$ligaxml="";
	$ligapdfn="";	
	$sql="select  distinct(idcfdiedoduenio), fechagen, e.idduenio from cfdipartidas cfdip, edoduenio e where cfdip.idedoduenio = e.idedoduenio and idduenio = $idd and fechagen ='$fechag'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
	
	
		//para ver si se reporta y no
		$notificacion="";
		$sqlr= "select * from cfdipartidas c, edoduenio ed,duenio d where c.idedoduenio = ed.idedoduenio and ed.idduenio = d.idduenio and idcfdiedoduenio = " . $row["idcfdiedoduenio"]." and fechagen= '".$fechag."' ORDER BY reportado DESC";
		$opr = mysql_query($sqlr);
		$r = mysql_fetch_array($opr);
		
		if($r["reportado"]==1)
		{
			$notificacion="Reportada";
		}
		else
		{
			$notificacion="NO REPORTADA";
		}		
		
		$duenion=$r["nombre"] . " " . $r["nombre2"] . " " . $r["apaterno"] . " " . $r["amaterno"];
		
		
		$htmlr .= "<br>($notificacion)<br>";
		
		
		
		
		
		//verificar la factura
		$sqlcfdi="select * from facturacfdid fl, facturacfdi f where fl.idfacturacfdi=f.idfacturacfdi and fl.idcfdiedoduenio = " . $row["idcfdiedoduenio"];
		$operacionr = mysql_query($sqlcfdi);
		
		if (mysql_num_rows($operacionr)==0)
		{
			//echo "<br>" . mysql_num_rows($operacionr) . "<br>";
			$idf = $row["idcfdiedoduenio"];
			$edocuenta="nuevaVP('" . $idf . "','2');this.disabled=true;";
			//$accionbotonfact="<br>" . '<form name="frm_' . $idf.  '" id="frm_' . $idf .  '" method="POST" action="scripts/reporte2.php" target="trg_' . $idf . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""><input type="hidden" name="archivo" id="archivo" value ="">' . "<input type =\"button\" value=\"Facturar. $idf\" onClick=\"$edocuenta\" $txtagregar /></form>";	
			// Cambio 02/09/2021
			// Se agrega el parametro fechagen para determinar la fecha en que se generaron los conceptos a facturar
			$inputFechaGen = $notificacion!='Reportada'?'':'<input type="hidden" name="fechagen" id="fechagen" value ="'.$fechag.'">';
			$accionbotonfact="<br>" . '<form name="frm_' . $idf.  '" id="frm_' . $idf .  '" method="POST" action="scripts/reporte2.php" target="trg_' . $idf . '"><input type="hidden" name="idc" id="idc" value ="">'.$inputFechaGen.'<input type="hidden" name="filtro" id="filtro" value =""><input type="hidden" name="archivo" id="archivo" value ="">' . "<input type =\"button\" value=\"Facturar. $idf\" onClick=\"$edocuenta\" $txtborrar /></form>";	
			// Fin Cambio 02/09/2021
			$htmlr .= "<div id='cfdi_$idd'> $accionbotonfact";
			if($notificacion!='Reportada')
			{
				$htmlr .= "<input type ='button' value='Dividir Factura' onClick =\"window.open('scripts/duenios/dividirfact.php?ida=$idf')\" $txtborrar>";		
			}
			$htmlr .= "</div>";
			$accionbotonfact="";
		
		}
		else
		{		
		

		$r = mysql_fetch_array($operacionr);
		
			$accionbotonfact="";
			$ligapdf="";
			$ligaxml="";
			$ligapdfn="";
		
		
		//echo $r["txtok"];
		if($r["txtok"] == 0)
		{
			//$edocuenta="window.open( 'scripts/reporte2.php?id=" . $row["idfolios"] . "&filtro=2');this.disabled=true";
			//$accionbotonfact="<br><input type =\"button\" value=\"Facturar\" onClick=\"$edocuenta\"  />";
			$edocuenta="nuevaVP(" . $row["idcfdiedoduenio"] . ",'2');this.disabled=true;";
			$accionbotonfact='<form name="frm_' . $row["idcfdiedoduenio"] .  '" id="frm_' . $row["idcfdiedoduenio"] .  '" method="POST" action="scripts/reporte2.php" target="trg_' . $row["idcfdiedoduenio"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value ="2"><input type="hidden" name="archivo" id="archivo" value ="'. substr($r["archivotxt"],0,-4) . '">' . "<input type =\"button\" value=\"Facturar $notacreditocfdi\" onClick=\"$edocuenta\" $txtagregar /></form>";				
			
			
			
		}
		//echo $accionbotonfact . "..";
		
		else
		{
				//if($row["pdfok"]==0 and $accionbotonfact=="" )
				if($r["pdfok"]==0 and $accionbotonfact == "" )
				{
					$ligapdf="<input type=\"button\" value='Recuperar PDF' onClick=\"document.getElementById('cfdi" . $row["idcfdiedoduenio"] . "').innerHTML='Recuperando...';cargarSeccion('scripts/recuperar.php','cfdi" . $row["idcfdiedoduenio"] . "', 'id=" . $row["idcfdiedoduenio"] . "&filtro=2');\">";

				
				}
				else
				{
				    
			        $partes_ruta = pathinfo($r["archivopdf"]);
				    $narchivo = $partes_ruta['basename'];				    
					//$ligapdf="<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivopdf"] . "\"  target=\"_blank\" >" .  $row["archivopdf"] . "\n</a>";
					//$ligapdf="<a href=\"scripts/general/descargarcfdi.php?f=" .  $r["archivopdf"] . "\"  target=\"_blank\" >" .  $r["archivopdf"] . "\n</a><br>";
					$ligapdf="<a href=\"scripts/general/descargarcfdi.php?f=" .  $r["archivopdf"] . "\"  target=\"_blank\" >" .  $narchivo . "\n</a><br>";
				}

				//if($row["xmlok"]==0 and $accionbotonfact=="")
				if($r["xmlok"]==0 and $accionbotonfact=="")
				{

					$ligaxml="<input type=\"button\" value='Recuperar XML' onClick=\"document.getElementById('cfdi" . $row["idcfdiedoduenio"] . "').innerHTML='Recuperando...';cargarSeccion('scripts/recuperar.php','cfdi" . $row["idcfdiedoduenio"] . "', 'id=" . $row["idcfdiedoduenio"] . "&filtro=2');\">";

				}
				else
				{
				    
				    
				    $partes_ruta = pathinfo($r["archivoxml"]);
				    $narchivo = $partes_ruta['basename'];
					//$ligaxml="<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivoxml"] . "\"  target=\"_blank\" >" .  $row["archivoxml"] . "\n</a>";
					//$ligaxml="<a href=\"scripts/general/descargarcfdi.php?f=" .  $r["archivoxml"] . "\"  target=\"_blank\" >" .  $r["archivoxml"] . "\n</a><br>";
					$ligaxml="<a href=\"scripts/general/descargarcfdi.php?f=" .  $r["archivoxml"] . "\"  target=\"_blank\" >" .  $narchivo . "\n</a><br>";
				}

/*			
				//if($row["pdfnok"]==0 and $accionbotonfact=="")
				if($r["pdfn"]==0 and $accionbotonfact=="")
				{
					$edocuenta="";
					$ligapdfn="<br>PDF PyB";
				}			
				else
				{
					$ligapdfn="<br>PDF PyB";	
				}
				*/
			
			}	
		
		}
		$htmlr .= "<div id='cfdi" . $row["idcfdiedoduenio"] . "'> $ligapdf  $ligaxml  $ligapdfn $accionbotonfact</div>";
		$accionbotonfact="";
		$ligapdf="";
		$ligaxml="";
		$ligapdfn="";


	}

/*
	$sqlsincfdi= "select * from facturacfdid where idcfdiedoduenio in ( select distinct(idcfdiedoduenio) as idc from cfdipartidas cfdip, edoduenio e where cfdip.idedoduenio = e.idedoduenio and idduenio = $idd and fechagen ='$fechag')";
	$operacioncfdid = mysql_query($sqlsincfdi);
	if (mysql_num_rows($operacioncfdid)==0)
	{
		

		$edocuenta="nuevaVP('" . $idf . "','2');this.disabled=true;";
		$accionbotonfact="<br>" . '<form name="frm_' . $idf.  '" id="frm_' . $idf .  '" method="POST" action="scripts/reporte2.php" target="trg_' . $idf . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""><input type="hidden" name="archivo" id="archivo" value ="'. substr($r["archivotxt"],0,-4) . '">' . "<input type =\"button\" value=\"Facturar lo Reportado\" onClick=\"$edocuenta\" $txtagregar /></form>";	
		$htmlr .= "<div id='cfdi_$idd'> $accionbotonfact</div>";
		
		
		
	}
*/


    return $htmlr;
} 





$anio = @$_GET["anio"];
$mes = @$_GET["mes"];
$nombre = @$_GET["nombre"];

$misesion = new sessiones;
$renglones="";
if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='edosduenio.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta']  . "/" . $row['archivo'];
		$ruta= $row['ruta'] ;
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}
	//$usuario=$_SESSION["usuario"];
	$usuario=$misesion->usuario;

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

	$w="";
	if($nombre)
	{
		$w = " and (nombre like '%$nombre%' or nombre2 like '%$nombre%' or apaterno like '%$nombre%' or amaterno like '%$nombre%') ";
	}
	
		
	$sql = "select ed.idduenio, nombre, nombre2, apaterno, amaterno, fechagen, count(idedoduenio),idedoduenio from edoduenio ed, duenio d where ed.idduenio = d.idduenio and isnull(fechagen)=false and month(fechagen)=$mes and year(fechagen)=$anio $w group by idduenio, fechagen,nombre, nombre2, apaterno, amaterno order by idduenio, fechagen";
	
	$oculto="<input type='hidden' name='opciont' id='opciont' value='0'>";
	//$opciones = "..$selectanio ..<table border='0'><tr><td>$todos<td>$cerrados</td><td>$vigentes </td></tr></table>$oculto";
	
	
	//echo $sql;
	$result = mysql_query ($sql);
	//$renglones = "$selectmes $selectanio<table border='1' ><tr><th>Id</th><th>Due&ntilde;o</th><th>Edo. Cuenta</th><th>Factura</th><th>Enviar</th></tr>";
	$renglones = "<table border='1' ><tr><th>Id</th><th>Due&ntilde;o</th><th>Edo. Cuenta</th><th>Factura</th><th>Enviar</th><th>Subir PDF</th></tr>";
	while ($row = @mysql_fetch_array($result))
	{
		$idc=$row["idduenio"];
		$fecha=$row["fechagen"];
		$edocuenta="window.open( '$ruta/reported2.php?id=$idc&f=$fecha&e=0');";
		$accionboton="<input type =\"button\" value=\"Ver $fecha\" onClick=\"$edocuenta\"  />";
		// Cambio 15/09/21
		// Se agrega un nuevo boton para poder descargar el Estado de cuenta 
		// en formato excel
		$edocuenta="window.open( '$ruta/descargarEdoCuentaPropietario.php?id=$idc&f=$fecha&e=0');";
		$accionboton.="<br><input type =\"button\" value=\"Descargar Excel $fecha\" onClick=\"$edocuenta\"  />";
		// Fin Cambio 15/09/21
		
		$sqlmail="select max(fechaenvio) as fe,max(idenviomail) as ultimo, count(*) as envios from enviomail where idduenio = $idc and fechareporte = '$fecha'";
		
		$resultmail = mysql_query ($sqlmail);
		$rowmail = @mysql_fetch_array($resultmail);
		$envioprevio="";
		if(is_null($rowmail['fe'])==false)
		{
			$envioprevio="<br>Envios realizados: <a href=\"javascript:window.open( '$ruta/listamail.php?id=$idc&fechagen=$fecha'); \" >" . $rowmail['envios'] . "</a> <br>Ultimo envío: " .  $rowmail['fe'] ;
		}
		else
		{
			$envioprevio="";
		}		
		
		
		
		$idesduenio=$row["idedoduenio"];
		$edomail="if(confirm('Esta por enviar el estado de cuenta y sus factutras, desea continuar?')){ window.open( '$ruta/reported2.php?id=$idc&f=$fecha&e=1&idedoduenio=$idesduenio');}";
		$acciomail="<input type =\"button\" value=\"Enviar\" onClick=\"$edomail\" $txteditar />$envioprevio";			
		$accionboton0= facturas($idc,$fecha,$txtborrar);			
		$renglones .= "<tr><td>" . $row["idduenio"] . " </td><td>" .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] .  "</td><td>$accionboton</td><td>$accionboton0</td><td>$acciomail</td>";
		//$renglones.="<td><iframe src=\"http://192.168.1.250/scripts/duenios/cargaPDF.php?idduenio=".$row["idedoduenio"]."+&mes=".$row["idduenio"]."+&periodos=".$periodo."\" style=\"border:0px; height:110px;width:400px;\"></iframe><br>";
		$renglones.="<td><iframe src=\"https://rentascdmx.com/scripts/duenios/cargaPDF.php?idduenio=".$row["idedoduenio"]."+&mes=".$row["idduenio"]."+&periodos=".$periodo."\" style=\"border:0px; height:110px;width:400px;\"></iframe><br>";
		$iduenio=trim($row["idedoduenio"]);
		//$mes=trim($mes);
		
		
		
		
		
		if($fecha>"2019-10-11")
		{
		
		//++++ nuevo directorio +++
		
			$rutas="contratos/" . $idc . "_" . $fecha;
		
			$directorio = opendir($rutas); //ruta actual
		
			while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
			{
		
    			if (is_dir($archivo))//verificamos si es o no un directorio
    			{
        			//echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
    			}
    			else
    			{
					if ($usuario==19 || $usuario==1 || $usuario==51) 
					{
    					$btnborrar="&nbsp;&nbsp;<input type=\"button\" value=\"Borrar Archivo\" name=\"elimina\" onClick=\"window.open('scripts/duenios/borraPdf.php?ruta=$rutas&archivo=$archivo')\" >";
    				}
    				else
    				{
    					$btnborrar="";
    				}
        			$renglones.= "<a href='scripts/duenios/$rutas/$archivo' target='_blank'>".$archivo . "</a>$btnborrar<br />";
        		}
		
    		}		

		//++++ nuevo directorio
		

			//echo "cumple que: " . date("Y-m-d") . " = 2019-10-11" ;		
		
		}
		else
		{
		
		//+++++++
		
			$rutas="contratos/".$iduenio;
			$directorio = opendir($rutas); //ruta actual
			while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
			{
    			if (is_dir($archivo))//verificamos si es o no un directorio
    			{
        		//echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
    			}
    			else
    			{
					if ($usuario==19 || $usuario==1 || $usuario==51) 
					{
    					$btnborrar="&nbsp;&nbsp;<input type=\"button\" value=\"Borrar Archivo\" name=\"elimina\" onClick=\"window.open('scripts/duenios/borraPdf.php?ruta=$rutas&archivo=$archivo')\" >";
    				}
    				else
    				{
    					$btnborrar="";
    				}
        			$renglones.= "<a href='scripts/duenios/$rutas/$archivo' target='_blank'>".$archivo . "</a>$btnborrar<br />";
    			}		
		
			}
		//++++++++
		
		}
				
		
		
	/*	
		$rutas="contratos/".$iduenio;
		$directorio = opendir($rutas); //ruta actual
		while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
		{
    		if (is_dir($archivo))//verificamos si es o no un directorio
    		{
        	//echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
    		}
    		else
    		{
				if ($usuario==19 || $usuario==1) 
				{
    				$btnborrar="&nbsp;&nbsp;<input type=\"button\" value=\"Borrar Archivo\" name=\"elimina\" onClick=\"window.open('scripts/duenios/borraPdf.php?ruta=$rutas&archivo=$archivo')\" >";
    			}
    			else
    			{
    				$btnborrar="";
    			}
        		$renglones.= "<a href='scripts/duenios/$rutas/$archivo' target='_blank'>".$archivo . "</a>$btnborrar<br />";
    		}
		}
		*/
		$renglones.="</td></tr>";

	}
	
	
	
	$renglones .= "</table>";
	echo $renglones = CambiaAcentosaHTML($renglones);



}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}
?>
