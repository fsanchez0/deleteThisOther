<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';




function facturas($idd,$fechag,$borrar) {

	$htmlr ="";
	$ligapdf="";
	$ligaxml="";
	$ligapdfn="";	
	$sql="select  distinct(idcfdiedoduenio), fechagen, e.idduenio from cfdipartidas cfdip, edoduenio e where cfdip.idedoduenio = e.idedoduenio and idduenio = $idd and fechagen ='$fechag'";
	//$sql="select * from facturacfdid a, facturacfdi b where idcfdiedoduenio in (select idcfdiedoduenio from cfdipartidas  where idedoduenio in (select idedoduenio from edoduenio where fechagen = '$fechag' and idduenio =$idd )) and a.idfacturacfdi = b.idfacturacfdi";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
	
	
		//para ver si se reporta y no
		$notificacion="";
		$sqlr= "select * from cfdipartidas c, edoduenio ed,duenio d where c.idedoduenio = ed.idedoduenio and ed.idduenio = d.idduenio and idcfdiedoduenio = " . $row["idcfdiedoduenio"];
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
		
			$idf = $row["idcfdiedoduenio"];
			$edocuenta="nuevaVP('" . $idf . "','2');this.disabled=true;";
			//$accionbotonfact="<br>" . '<form name="frm_' . $idf.  '" id="frm_' . $idf .  '" method="POST" action="scripts/reporte2.php" target="trg_' . $idf . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""><input type="hidden" name="archivo" id="archivo" value ="">' . "<input type =\"button\" value=\"Facturar. $idf\" onClick=\"$edocuenta\" $txtagregar /></form>";
			$accionbotonfact="<br>" . '<form name="frm_' . $idf.  '" id="frm_' . $idf .  '" method="POST" action="scripts/reporte2.php" target="trg_' . $idf . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""><input type="hidden" name="archivo" id="archivo" value ="">' . "<input type =\"button\" value=\"Facturar. $idf\" onClick=\"$edocuenta\" $borrar/></form>";
			$htmlr .= "<div id='cfdi_$idd'> $accionbotonfact";
			if($notificacion!='Reportada')
			{
				$htmlr .= "<input type ='button' value='Dividir Factura' onClick =\"window.open('scripts/duenios/dividirfact.php?ida=$idf')\"  $borrar>";		
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
			//$accionbotonfact='<form name="frm_' . $row["idcfdiedoduenio"] .  '" id="frm_' . $row["idcfdiedoduenio"] .  '" method="POST" action="scripts/reporte2.php" target="trg_' . $row["idcfdiedoduenio"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value ="2"><input type="hidden" name="archivo" id="archivo" value ="'. substr($r["archivotxt"],0,-4) . '">' . "<input type =\"button\" value=\"Facturar $notacreditocfdi\" onClick=\"$edocuenta\" $txtagregar /></form>";
			$accionbotonfact='<form name="frm_' . $row["idcfdiedoduenio"] .  '" id="frm_' . $row["idcfdiedoduenio"] .  '" method="POST" action="scripts/ .php" target="trg_' . $row["idcfdiedoduenio"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value ="2"><input type="hidden" name="archivo" id="archivo" value ="'. substr($r["archivotxt"],0,-4) . '">' . "<input type =\"button\" value=\"Facturar $notacreditocfdi\" onClick=\"$edocuenta\"  $borrar/></form>";				
			
			
			
		}
		else
		{
				//if($row["pdfok"]==0 and $accionbotonfact=="" )
				if($r["pdfok"]==0 and $accionbotonfact == "" )
				{
					$ligapdf="<input type=\"button\" value='Recuperar PDF' onClick=\"document.getElementById('cfdi" . $row["idcfdiedoduenio"] . "').innerHTML='Recuperando...';cargarSeccion('scripts/recuperar.php','cfdi" . $row["idcfdiedoduenio"] . "', 'id=" . $row["idcfdiedoduenio"] . "&filtro=2');\">";

				
				}
				else
				{
					//$ligapdf="<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivopdf"] . "\"  target=\"_blank\" >" .  $row["archivopdf"] . "\n</a>";
					//$ligapdf="<a href=\"scripts/general/descargarcfdi.php?f=" .  $r["archivopdf"] . "\"  target=\"_blank\" >" .  $r["archivopdf"] . "\n</a><br>";
					$ligapdf="<a href=\"scripts/general/descargarcfdi.php?f=" .  $r["archivopdf"] . "\"  target=\"_blank\" >" .  basename($r["archivopdf"]) . "\n</a><br>";
					//basename($filepath)
				}

				//if($row["xmlok"]==0 and $accionbotonfact=="")
				if($r["xmlok"]==0 and $accionbotonfact=="")
				{

					$ligaxml="<input type=\"button\" value='Recuperar XML' onClick=\"document.getElementById('cfdi" . $row["idcfdiedoduenio"] . "').innerHTML='Recuperando...';cargarSeccion('scripts/recuperar.php','cfdi" . $row["idcfdiedoduenio"] . "', 'id=" . $row["idcfdiedoduenio"] . "&filtro=2');\">";

				}
				else
				{
					//$ligaxml="<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivoxml"] . "\"  target=\"_blank\" >" .  $row["archivoxml"] . "\n</a>";
					//$ligaxml="<a href=\"scripts/general/descargarcfdi.php?f=" .  $r["archivoxml"] . "\"  target=\"_blank\" >" .  $r["archivoxml"] . "\n</a><br>";
					$ligaxml="<a href=\"scripts/general/descargarcfdi.php?f=" .  $r["archivoxml"] . "\"  target=\"_blank\" >" .  basename($r["archivoxml"]) . "\n</a><br>";
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
	//$sqlsincfdi="select  distinct(idcfdiedoduenio), fechagen, e.idduenio from cfdipartidas cfdip, edoduenio e where cfdip.idedoduenio = e.idedoduenio and idduenio = $idd and fechagen ='$fechag'";
	$sqlsincfdi= "select * from facturacfdid where idcfdiedoduenio in ( select distinct(idcfdiedoduenio) as idc from cfdipartidas cfdip, edoduenio e where cfdip.idedoduenio = e.idedoduenio and idduenio = $idd and fechagen ='$fechag')";
	$operacioncfdid = mysql_query($sqlsincfdi);
	if (mysql_num_rows($operacioncfdid)==0)
	{
		

		$edocuenta="nuevaVP('" . $idf . "','2');this.disabled=true;";
		$accionbotonfact="<br>" . '<form name="frm_' . $idf.  '" id="frm_' . $idf .  '" method="POST" action="scripts/reporte2.php" target="trg_' . $idf . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""><input type="hidden" name="archivo" id="archivo" value ="'. substr($r["archivotxt"],0,-4) . '">' . "<input type =\"button\" value=\"Facturar lo Reportado\" onClick=\"$edocuenta\" $txtborrar /></form>";	
		$htmlr .= "<div id='cfdi_$idd'> $accionbotonfact</div>";
		
		
		
	}
*/

    return $htmlr;
} 






$opt = @$_GET["opt"];
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



	if ($priv[1]=='1')
	{
		$txtagregar = "";
	}
	else
	{
		$txtagregar = " DISABLED ";
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


	$mes = date('m');
	$anio = date('Y');
	
	
	$selectmes="";
	
	
	
	
	$mesl[0][1] = "01";
	$mesl[0][2] = "02";
	$mesl[0][3] = "03";	
	$mesl[0][4] = "04";	
	$mesl[0][5] = "05";	
	$mesl[0][6] = "06";	
	$mesl[0][7] = "07";	
	$mesl[0][8] = "08";	
	$mesl[0][9] = "09";
	$mesl[0][10] = "10";
	$mesl[0][11] = "11";	
	$mesl[0][12] = "12";	

	$mesl[1][1] = "ENERO";
	$mesl[1][2] = "FEBRERO";
	$mesl[1][3] = "MARZO";	
	$mesl[1][4] = "ABRIL";	
	$mesl[1][5] = "MAYO";	
	$mesl[1][6] = "JUNIO";	
	$mesl[1][7] = "JULIO";	
	$mesl[1][8] = "AGOSTO";	
	$mesl[1][9] = "SEPTIEMBRE";
	$mesl[1][10] = "OCTUBRE";
	$mesl[1][11] = "NOVIEMBRE";	
	$mesl[1][12] = "DICIEMBRE";	

	$selectmes ="Mes <select id='mes' name='mes' onChange=\"cargarSeccion('$ruta/listaedosduenio.php','listaedoduenio', 'mes=' + this.value + '&anio=' + anio.value  + '&nombre=' + nombre.value);\"><option value ='' >todos</option>";
	for($r=1; $r<=12; $r++)
	{
		$seleccionopt="";
		//if ($idduenio>0)
		if ($mes==$mesl[0][$r])
		{
			$seleccionopt=" SELECTED ";
		}
		$selectmes .= "<option value=\"" . $mesl[0][$r] . "\" $seleccionopt>" . CambiaAcentosaHTML($mesl[1][$r]) . "</option>";

	}
	 $selectmes .="</select>";
	
	
	
	
	


	
			
	$sql = "select distinct(year(fechagen)) as anio from edoduenio where isnull(fechagen)=false ";
	$selectanio ="A&ntilde;o <select id='anio' name='anio' onChange=\"cargarSeccion('$ruta/listaedosduenio.php','listaedoduenio', 'mes=' + mes.value + '&anio=' + this.value + '&nombre=' + nombre.value);\"><option value ='' >todos</option>";
	$result = mysql_query ($sql);
	while ($row = @mysql_fetch_array($result))
	{
		$seleccionopt="";
		//if ($idduenio>0)
		if ($anio==$row["anio"])
		{
			$seleccionopt=" SELECTED ";
		}
		$selectanio .= "<option value=\"" . $row["anio"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["anio"]) . "</option>";

	}
	 $selectanio .="</select>";
	
	
		
	$sql = "select ed.idduenio, nombre, nombre2, apaterno, amaterno, fechagen, count(idedoduenio),idedoduenio from edoduenio ed, duenio d where ed.idduenio = d.idduenio and isnull(fechagen)=false and month(fechagen)=$mes and year(fechagen)=$anio group by idduenio, fechagen,nombre, nombre2, apaterno, amaterno order by idduenio, fechagen";
	
	
	//echo $sql;
	$result = mysql_query ($sql);
	$renglones = "<table border='1' ><tr><th>Id</th><th>Due&ntilde;o</th><th>Edo. Cuenta</th><th>Factura</th><th>Enviar</th><th>Subir PDF</th></tr>";
	while ($row = @mysql_fetch_array($result))
	{
		$idc=$row["idduenio"];
		$fecha=$row["fechagen"];
		$edocuenta="window.open( '$ruta/reported2.php?id=$idc&f=$fecha&e=0');";
		$accionboton="<input type =\"button\" value=\"Ver $fecha\" onClick=\"$edocuenta\"  />";
		
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
		
		$edomail="window.open( '$ruta/reported2.php?id=$idc&f=$fecha&e=1');";
		$acciomail="<input type =\"button\" value=\"Enviar\" onClick=\"$edomail\" $txteditar />$envioprevio";		
		$accionboton0 = facturas($idc,$fecha,$txtborrar);		
		
		$renglones .= "<tr><td>" . $row["idduenio"] . " </td><td>" .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] .  "</td><td>$accionboton</td><td>$accionboton0</td><td>$acciomail</td>";
		//$renglones.="<td><iframe src=\"http://192.168.1.250/scripts/duenios/cargaPDF.php?idduenio=".$row["idedoduenio"]."+&mes=".$row["idduenio"]."+&periodos=".$periodo."\" style=\"border:0px; height:110px;width:400px;\"></iframe><br>";
		$renglones.="<td><iframe src=\"https://rentascdmx.com/scripts/duenios/cargaPDF.php?idduenio=".$row["idedoduenio"]."+&mes=".$row["idduenio"]."+&periodos=".$periodo."\" style=\"border:0px; height:110px;width:400px;\" id=\"" . $row["idduenio"] . "\"></iframe><br>";		
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
		
		$renglones.="</td></tr>";


	}
	$renglones .= "</table>";
	$renglones = CambiaAcentosaHTML($renglones);
/*
<!--
<p>
 $opciones<br>
Buscar: <input type="text" name="patronb" id="patronb" value="" onKeyUp="cargarSeccion('$ruta/listaedoscuenta.php', 'listadirectorio', 'patron=' + this.value + '&opcion=' + document.getElementById('opciont').value)"/>
</p>
-->
*/

$html = <<<fin
<center>
<h1>Estados de cuenta Propietarios</h1>
<form>
Due&ntilde;o<input type="text" name="nombre" onChange="cargarSeccion('$ruta/listaedosduenio.php','listaedoduenio', 'mes=' + mes.value + '&anio=' + anio.value + '&nombre=' + this.value);">
$selectmes $selectanio
</form>
<div class='scroll' id="listaedoduenio">
$renglones
</div>
</center>
fin;

	echo 	CambiaAcentosaHTML($html) ;


}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}






?>