<?php
include '../general/funcionesformato.php';
include '../general/sessionclase.php';
include '../general/ftpclass.php';
include_once('../general/conexion.php');
include ("../cfdi/cfdiclassn.php");
require('../fpdf.php');




$id=@$_GET["id"]; //para el Id de la consulta que se requiere hacer: de arrendamiento idhistoria, de libre idfolio
$filtro=@$_GET["filtro"]; //para la especificacion del tipo re recibo inmueble=null, libre=0;
$datosl=@$_GET["datosl"]; //para recibir todos los datos para la factura segun el layaut que biene de la facturalibre
//$anio=@$_GET["anio"];
//$mes=@$_GET["mes"];


$cfd =  New cfdi32class;
$ftp= New ftpcfdi;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='verfacturaslibresnc.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];		
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

	//para extraer la información de la factura
	$sqlcfdi="select * from flibrecfdi fl, facturacfdi f, cfdilibre l where fl.idfacturacfdi=f.idfacturacfdi and fl.idcfdilibre = l.idcfdilibre and fl.idcfdilibre = $id";
	$operacionr = mysql_query($sqlcfdi);
	$r = mysql_fetch_array($operacionr);	
	$cont = file_get_contents("../cfdi/paso/" . $r["archivotxt"]);		
	$xml = file_get_contents("/home/wwwarchivos/cfdi/" . $r["archivoxml"]);		
	
	$idclientelibre = $r["idclientelibre"];
	
	//falta extraer la información del archiv ode texto par construir los valores del nuevoarchivo
	$ini = strpos($xml, "UUID=");
	$fin = strpos($xml," ",$ini+6);
	
	$uuid = substr($xml,$ini+6,$fin-($ini+6)-1);
	
	
	$ini = strpos($cont,"Serie");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);
	
	
	
	$v2_Version="3.3";
	$v5_Serie="";
	$v6_Folio="";
	$v8_Fecha="";
	$v22_Pago="UNA SOLA EXHIBICION";
	$v10_MetPago="EFECTIVO";
	$v31_LugarExp="CIUDAD DE MEXICO";

	$ini = strpos($cont,"NoCta");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);	
	
	
	$v23_NoCta=$ps;
	$v41_ERFC="PAB0802225K4";
	$v42_ENombre="PADILLA y BUJALIL S.C.";
	$v46_ECalle="AV. INSURGENTES CENTRO";
	$v83_ENoext="23";
	$v84_ENoint="102";
	$v47_EColon="SAN RAFAEL";
	$v48_EMunic="CUAUHTEMOC";
	$v49_EEdo="CIUDAD DE MEXICO";
	$v50_Epais="MEXICO";
	$v51_ECP="06470";

	$ini = strpos($cont,"RRFC");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);	
	
	$v61_RRFC=$ps;
	
	$ini = strpos($cont,"RNombre");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);		
	$v62_RNombre=$ps;
	
	$ini = strpos($cont,"RCalle");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);	
	$v69_RCalle=$ps;
	
	$ini = strpos($cont,"RNoex");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);		
	$v83_RNoext=$ps;
	
	$ini = strpos($cont,"RNoint");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);		
	$v84_RNoint=$ps;
	
	$ini = strpos($cont,"RColon");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);		
	$v70_RColon=$ps;
	
	$ini = strpos($cont,"RLoc");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);		
	$v66_RLoc=$ps;
	
	$ini = strpos($cont,"RRef");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);	
	$v67_RRef=$ps;
	
	$ini = strpos($cont,"RMunic");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);		
	$v71_RMunic=$ps;
	
	$ini = strpos($cont,"REdo");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);	
	$v72_REdo=$ps;
	
	$ini = strpos($cont,"Rpais");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);		
	$v73_Rpais=$ps;
	
	$ini = strpos($cont,"RCP");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);		
	$v74_RCP=$ps;
	
	$ini = strpos($cont,"Numlin");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);		
	$v122_Numlin="1";
	$v127_Cant="1";
	$v129_UM="SRV";
	
	$ini = strpos($cont,"Desc");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);	
	$v130_Desc=$ps;

	$ini = strpos($cont,"PrecMX");
	$ini = strpos($cont,"\t",$ini);
	$fin = strpos($cont,"\n",$ini);
	$ps = substr($cont,$ini+1,$fin-$ini);	
	
	$v131_PrecMX=$ps;
	$v134_ImporMX="";
	
	
	//*****
	

	$tiporelacion="";

	$tiporelacion = "<select name=\"40_Campo0\"><option value=\"0\">Seleccione uno de la lista</option>";
	$tiporelacion .= "<option value=\"01\" >" . CambiaAcentosaHTML("Nota de crédito de los documentos relacionados") . "</option>";
	$tiporelacion .= "<option value=\"02\" >" . CambiaAcentosaHTML("Nota de débito de los documentos relacionados") . "</option>";
	$tiporelacion .= "<option value=\"03\" >" . CambiaAcentosaHTML("Devolución de mercancia sobre facturas o traslados previos") . "</option>";
	$tiporelacion .= "<option value=\"04\" >" . CambiaAcentosaHTML("Sustitución de los CFDI previos") . "</option>";
	$tiporelacion .= "<option value=\"05\" >" . CambiaAcentosaHTML("Traslados de mercancias facturados previamente") . "</option>";
	$tiporelacion .= "<option value=\"06\" >" . CambiaAcentosaHTML("Factura generada por los traslados previos") . "</option>";
	$tiporelacion .= "<option value=\"07\" >" . CambiaAcentosaHTML("CFDI por aplicación de anticipo") . "</option>";
	$tiporelacion .="</select>";


	
	$sql="select * from metodopago";
	$metodopagoselect = "<select name=\"idmetodopago\" onChange=\"document.getElementById('10_FormaPago').value=this.value;\">";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		$metodopagoselect .= "<option value=\"" . $row["clavefpagosat"] . "\" >" . CambiaAcentosaHTML($row["metodopago"]) . "</option>";

	}
	$metodopagoselect .="</select>";



	$sql="select * from c_usocfdi";
	$usoCFDIselect = "<select name=\"idc_usocfdi\" onChange=\"document.getElementById('RUsoCFDI_65').value=this.value;\">";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$selec="";
		if ($row["idc_usocfdi"]==3)
		{
			$selec=" SELECTED ";
		}
		$usoCFDIselect .= "<option value=\"" . $row["claveucfdi"] . "\" $selec>" . CambiaAcentosaHTML($row["claveucfdi"].": ".$row["descripcionucfdi"] ) . "</option>";

	}
	$usoCFDIselect .="</select>";



	$sql="select * from c_prodserv";
	$prodserSelect = "<select name=\"idc_prodserv\" onChange=\"document.getElementById('ClaveProdServ_121').value=this.value;\">";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$prodserSelect .= "<option value=\"" . $row["claveps"] . "\" $selec>" . CambiaAcentosaHTML($row["claveps"].": ".$row["descripcionps"] ) . "</option>";

	}
	$prodserSelect .="</select>";


	$sql="select * from c_unidadmed";
	$unidmedSelect = "<select name=\"idc_unidadmed\" onChange=\"document.getElementById('ClaveUnidad_127').value=this.value;\">";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$unidmedSelect .= "<option value=\"" . $row["claveum"] . "\" $selec>" . CambiaAcentosaHTML($row["claveum"].": ".$row["nombreum"] ) . "</option>";

	}
	$unidmedSelect .="</select>";



	$sql="select * from folios";
	$foliosselect = "<select name=\"idfolios\" onChange=\"MostrarTer(this); idc.value =this.value;\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		$foliosselect .= "<option value=\"" . $row["idfolios"] . "\" >" . CambiaAcentosaHTML($row["serie"]) . "</option>";

	}
	$foliosselect .="</select>";



	$sql="select * from duenio";
	$duenioselect = "<select name=\"idter\" id=\"idter\"  STYLE=\"font-family : monospace;  font-size : 6pt\">";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		$duenioselect .= "<option value=\"" . $row["idduenio"] . "\" >" . CambiaAcentosaHTML($row["rfcd"]) . " " . CambiaAcentosaHTML($row["nombre"]) .  " " . CambiaAcentosaHTML($row["apaterno"]) . "</option>";

	}
	$duenioselect .="</select>";

	$date=date("Y-m-d H:i:s");
	$date=date("Y-m-d H:i:s",strtotime("$date -6 hours"));
	$date=str_replace(" ","T",$date);
	//$date="2018-12-31T12:00:00";


//**** nuevo

$html = <<<htmlt

<form name='cfdilibre' method="post" action="scripts/reporte2.php" target="gcfdi">

<div style="height:0px; width:0px; overflow:auto;">
<table border = "0" id="comp1">

<tr><td>Clave para PAC</td><td> <input type='text' name ='1_CFD' value='380'/></td></tr>
<tr><td>Version CFDi</td><td> <input type='text' name ='2_Version' value='3.3'/></td></tr>
<tr><td>Version CFDi</td><td> <input type='text' name ='3_Complemento' id ='3_Complemento' value=''/></td></tr>
<tr><td>Serie</td><td> <input type='text' name ='5_Serie' value=''/></td></tr>
<tr><td>Folio</td><td> <input type='text' name ='6_Folio' value=''/></td></tr>

<tr><td>Fecha</td><td> <input type='text' name ='8_Fecha' value='$date'/></td></tr>

<tr><td>Metodo de pago</td><td> <input type='text' name ='10_FormaPago' id ='10_FormaPago' value='01'/></td></tr>

<tr><td>Campo</td><td> <input type='text' name ='26_DivisOp' value='MXN'/></td></tr>

<tr><td>Tipo de comprobante (ingreso, egreso, traslado)</td><td> <input type='text' name ='29_EfectoCFD' value='E'/></td></tr>
<tr><td>Forma de pago</td><td> <input type='text' name ='30_MetPago' id ='30_MetPago'  value='PUE'/></td></tr>

<tr><td>Lugar de expedicion</td><td> <input type='text' name ='31_LugarExp' value='06470'/></td></tr>
<tr><td>uuid</td><td> <input type='text' name ='39_campo0' value='REL'/></td></tr>
<tr><td>uuid</td><td> <input type='text' name ='39_campo1' value='01'/></td></tr>
<tr><td>uuid</td><td> <input type='text' name ='40_campo2' value='$uuid'/></td></tr>
</table>
</div>


<div style="height:0px; width:0px; overflow:auto;">
<table border = "0" id="emisor">
<tr><td>RFC emisor</td><td> <input type='text' name ='41_ERFC' value='PAB0802225K4'/></td></tr>
<tr><td>Nombre emisor</td><td> <input type='text' name ='42_ENombre' value='PADILLA & BUJALIL S.C.'/></td></tr>
<tr><td>Regimen Fiscal</td><td> <input type='text' name ='43_RegFiscal' value='601'/></td></tr>

<tr><td>Calle emisor</td><td> <input type='text' name ='46_ECalle' value='AV. INSURGENTES CENTRO'/></td></tr>
<tr><td>Colonia emisor</td><td> <input type='text' name ='47_EColon' value='SAN RAFAEL'/></td></tr>
<tr><td>Alc. emisor</td><td> <input type='text' name ='48_EMunic' value='CUAUHTEMOC'/></td></tr>
<tr><td>Estado emisor</td><td> <input type='text' name ='49_EEdo' value='CIUDAD DE MEXICO'/></td></tr>
<tr><td>Pais emisor</td><td> <input type='text' name ='50_Epais' value='MEXICO'/></td></tr>
<tr><td>C.P. emisor</td><td> <input type='text' name ='51_ECP' value='06470'/></td></tr>

</table>

</div>


<table border="0">
<tr>
	<td>

<fieldset >
	<legend>Receptor:</legend>
	<input type="hidden" name="idreceptor" id="idreceptor" value = "$idclientelibre">
	<div id="emisorcl"></div>

<table border = '1' id='receptor'>
	<tr><td>* RFC</td><td> <input type='text' name ='61_RRFC' id ='RRFC_61' value='$v61_RRFC' onKeyUp="cargarSeccion('scripts/cfdilibre/bemisorcl.php', 'emisorcl', 'patron=' + this.value);document.getElementById('idreceptor').value ='';"/></td></tr>
	<tr><td>Nombre</td><td> <input type='text' name ='62_RNombre' id='RNombre_62' value='$v62_RNombre'/><input type=hidden name ='65_RUsoCFDI' id='RUsoCFDI_65' value='G03'/></td></tr>

	<tr><td>Calle</td><td> <input type='text' name ='69_Rcalle' id ='Rcalle_69' value='$v69_RCalle'/></td></tr>
	<tr><td>Colonia</td><td> <input type='text' name ='70_RColon' id ='RColon_70' value='$v70_RColon'/></td></tr>
	<tr><td>Alc./Mun.</td><td> <input type='text' name ='71_RMunic' id ='RMunic_71' value='$v71_RMunic'/></td></tr>
	<tr><td>Estado</td><td> <input type='text' name ='72_REdo' id ='REdo_72'  value='$v72_REdo'/></td></tr>
	<tr><td>Pais</td><td> <input type='text' name ='73_Rpais' id ='Rpais_73' value='$v73_Rpais'/></td></tr>
	<tr><td>C.P.</td><td> <input type='text' name ='74_RCP' id ='RCP_74' value='$v74_RCP'/><input type='hidden' name ='106_Cont' id ='Cont_106' value='R'/><input type='hidden' name ='107_TipoCont' id ='TipoCont_107' value='SMTP'/><input type='hidden' name ='109_EmailCont' id ='EmailCont_109' value=''/><input type='hidden' name ='110_TelefonoCont' id ='TelefonoCont_110' value=''/></td></tr>

</table>


<table border="1">
<tr><td width="99">Correo 1</td><td> <input type='text' name ='c1' id ='c1' value='' onBlur="document.getElementById('EmailCont_109').value='';if(document.getElementById('c1').value != ''){document.getElementById('EmailCont_109').value = document.getElementById('c1').value;};if(document.getElementById('c2').value != ''){if(document.getElementById('EmailCont_109').value != ''){document.getElementById('EmailCont_109').value =document.getElementById('EmailCont_109').value + ',' + document.getElementById('c2').value;}else{document.getElementById('EmailCont_109').value = document.getElementById('c2').value;};};if(document.getElementById('c3').value != ''){if(document.getElementById('EmailCont_109').value != ''){document.getElementById('EmailCont_109').value =document.getElementById('EmailCont_109').value + ',' + document.getElementById('c3').value;}else{document.getElementById('EmailCont_109').value = document.getElementById('c3').value;};};"/></td></tr>
<tr><td>Correo 2</td><td> <input type='text' name ='c2' id ='c2' value='' onBlur="document.getElementById('EmailCont_109').value='';if(document.getElementById('c1').value != ''){document.getElementById('EmailCont_109').value = document.getElementById('c1').value;};if(document.getElementById('c2').value != ''){if(document.getElementById('EmailCont_109').value != ''){document.getElementById('EmailCont_109').value =document.getElementById('EmailCont_109').value + ',' + document.getElementById('c2').value;}else{document.getElementById('EmailCont_109').value = document.getElementById('c2').value;};};if(document.getElementById('c3').value != ''){if(document.getElementById('EmailCont_109').value != ''){document.getElementById('EmailCont_109').value =document.getElementById('EmailCont_109').value + ',' + document.getElementById('c3').value;}else{document.getElementById('EmailCont_109').value = document.getElementById('c3').value;};};"/></td></tr>
<tr><td>Correo 3</td><td> <input type='text' name ='c3' id ='c3' value='' onBlur="document.getElementById('EmailCont_109').value='';if(document.getElementById('c1').value != ''){document.getElementById('EmailCont_109').value = document.getElementById('c1').value;};if(document.getElementById('c2').value != ''){if(document.getElementById('EmailCont_109').value != ''){document.getElementById('EmailCont_109').value =document.getElementById('EmailCont_109').value + ',' + document.getElementById('c2').value;}else{document.getElementById('EmailCont_109').value = document.getElementById('c2').value;};};if(document.getElementById('c3').value != ''){if(document.getElementById('EmailCont_109').value != ''){document.getElementById('EmailCont_109').value =document.getElementById('EmailCont_109').value + ',' + document.getElementById('c3').value;}else{document.getElementById('EmailCont_109').value = document.getElementById('c3').value;};};"/></td></tr>
</table>
</fieldset> 

<fieldset >
	<legend>Confirmaci&oacute;n:</legend>
	<center>
	<table border="0">
	<tr>
		<td>* Serie a usar</td>
		<td>$foliosselect</td>
	</tr>	
	<tr>
		<td colspan="2" align="center"> 
			<input type='hidden' name='idc' id='id' value='id'>
			<input type='hidden' name='idcl' id='idcl' value='idcl'>
			<input type='hidden' name='filtro'  id='filtro'  value='1'>
			<input type='hidden' name='tipocfd'  id='tipocfd'  value='2'>
			<input type='hidden' name='datosl' id='datosl' value='datosl'>
			
			<input type='submit' value='facturar'  onClick="infoter='';if(document.getElementById('predial').value!=''){infoter=leerinputster('tterceros'); };idc.value =idfolios.value; idcl.value= idreceptor.value; document.getElementById('datosl').value=leerinputs('comp1') + leerinputs('emisor') + leerinputs('receptor') + leerinputs('conceptos') + infoter + leerinputs('resumen'); if(document.getElementById('RRFC_61').value!='' && metodopago.value!='' && idfolios.value>0 && idmetodopago.value!='Seleccione uno de la lista' && totalr.value>0 ){  window.open('','gcfdi' ); }else{alert('Los campos con * son requeridos');return false;}"/>
		</td>
	</tr>	
	</table>
	</center>
	<div id="divpredial">
	Tercero $duenioselect 
	<input type="button" name="addter" id="addter" value="+" onClick="addTer(document.getElementById('idter'),document.getElementById('predial').value,1,0); document.getElementById('3_Complemento').value='TERCERO';"/>
	<br>
	Predial <input type="text" name="predial" id="predial" onChange="document.getElementById('173_NumCPred').value=this.value;"/>
	</div>
	<div id="tercerosshow">
		Terceros<br>

		<table border="1" id="tterceros" >
		<tr>
			<th>Nombre</th><th>Porcentaje</th><th>Accion</th>
		</tr>
		</table>

	</div>		
	
	
	
	
</fieldset>






</td>
<td>



<fieldset >
	<legend>Datos Comprobante:</legend>
	<table border="1">
	<tr>
		<td>* Uso CFDI</td>
		<td>$usoCFDIselect</td>
	</tr>
	<tr>
		<td>* Metodo de pago</td>
		<td>$metodopagoselect</td>
	</tr>
	<tr>
		<td>* Clave del Producto/Servicio</td>
		<td>$prodserSelect</td>
	</tr>
	<tr>
		<td>* Unidad de medida del Producto/Servicio</td>
		<td>$unidmedSelect</td>
	</tr>
	<tr>
		<td>Impuesto (IVA %)</td>
		<td> <input type='text' name ='iva' id ='iva' value='16' onChange="document.getElementById('196_PorImpT').value=(this.value/100);document.getElementById('164_PorImp').value=(this.value/100); calcular();"/>%</td>
	</tr>	
	</table>
	
	
</fieldset>
<br>
<fieldset class="fieldsetIzquierda">
	<legend>Documento Relacionado</legend>
	<table>
	<tr class="backGris">
		<td align="right"><span class="infoObligatoria">*</span>UUID</td>
		<td> <input type='text' name ='Campo02' value='$uuid' disabled></td>
	</tr>
	<tr>
		<td align="right"><span class="infoObligatoria">*</span>Tipo Relaci&oacute;n</td>
		<td> <input type='hidden' name ='Campo01' value='01'><input type='text' value='Nota de credito de los documentos relacionados' disabled></td>
	</tr>	
	</table>
</fieldset>



<fieldset >
	<legend>Concepto:</legend>

<table border = "1" id="conceptos">
<!-- información de los conceptos de la factura -->
<!-- Aqui es donde hay que poner el procedimiento para activar los conceptos con el 117 y poner 
todos los valores en la cadena formateada-->
<tr><td>No. de linea concepto</td><td> <input type='text' name ='119_Numlin' value='1' disabled = 'true'/><input type=hidden name ='121_ClaveProdServ' id='ClaveProdServ_121' value='80131500'/></td></tr>

<tr><td>* Cantidad (No.) </td><td> <input type='text' name ='125_Cant' id ='125_Cant' value='1' onChange="calcular();"/><input type=hidden name ='127_ClaveUnidad' id='ClaveUnidad_127' value='E48'/></td></tr>

<tr><td>Unidad de Medida</td><td> <input type='text' name ='128_UM' value='SRV'/></td></tr>


<tr><td>Descripci&oacute;n</td><td> <input type='text' name ='130_Desc' value='Nota de credito: $v130_Desc'/></td></tr>
<tr><td>* Precio unitario</td><td> <input type='text' name ='131_PrecMX' id ='131_PrecMX' value='$v131_PrecMX' onChange="calcular();"/></td></tr>


<tr><td>Importe total (sin iva)</td><td> <input type='text' name ='132_ImporMX' id ='132_ImporMX' value='0' disabled = false/><input type='hidden' name ='160_DescTipoImp' id ='160_DescTipoImp' value='002'/><input type='hidden' name ='161_BaseImp' id ='161_BaseImp' value='0'/><input type='hidden' name ='162_CategImp' id ='162_CategImp' value='T'/><input type='hidden' name ='163_TipoFactor' id ='163_TipoFactor' value='Tasa'/><input type='hidden' name ='164_PorImp' id ='164_PorImp' value='0.160000'/></td></tr>

<tr><td>Traslados importe</td><td> <input type='text' name ='165_ImporImp' id ='165_ImporImp' value='0'/></td></tr>

</td></tr>

</table>
</fieldset >


<fieldset >
	<legend>Resumen:</legend>
	<table border="1" >
	<tr>
		<td>Sub total:</td>
		<td> <input type='text' name ='subtotalr' id ='subtotalr' value='0' /></td>
	</tr>
	<tr>
		<td>Impuesto: </td>
		<td><input type='text' name ='impuestor' id ='impuestor' value='0' /></td>
	</tr>

	<!-- 
	<tr><td>Ret. I.V.A.</td><td> <input type='text' name ='retiva' id ='retiva' value='66.66' size="3" onChange="calcular();"/>%<input type='text' name ='impretencioniva' id ='impretencioniva' value='' disabled/></td></tr>
	<tr><td>Ret. I.S.R.</td><td> <input type='text' name ='retisr' id ='retisr' value='10' size="3" onChange="calcular();"/>%<input type='text' name ='impretencionisr' id ='impretencionisr' value='' disabled/></td></tr>
	-->
	<tr>
		<td>Total </td>
		<td> <input type='text' name ='totalr' id ='totalr' value='0' /></td>
	</tr>	
	</table>
	
	
</fieldset>

</td>
</tr>



</table>




<div style="height:0px; width:0px; overflow:auto;">
<table border = "1" id="resumen">

<tr><td>Cuenta Predial</td><td> <input type='text' name ='173_NumCPred' id ='173_NumCPred' value=''/></td></tr>
<tr><td>Total trasladado cfdi</td><td> <input type='text' name ='189_TotImpT' id ='189_TotImpT' value=''/></td></tr>
<tr><td>Total Impuestos cfdi</td><td> <input type='text' name ='190_TotImp' id ='190_TotImp' value='0'/></td></tr>

<tr><td>Subtotal cfdi</td><td> <input type='text' name ='191_TotNeto' id ='191_TotNeto' value='0'/></td></tr>
<tr><td>Bruto cfdi</td><td> <input type='text' name ='192_TotBruto' id ='192_TotBruto' value='0'/></td></tr>
<tr><td>Importe cfdi</td><td> <input type='text' name ='193_Importe' id ='193_Importe' value='0'/></td></tr>


<!-- Traslados 
aqui es donde hay que hacer la agrupacion de ivas y poner retenciones generales de ivas por concepto-->
<tr><td>Traslados impuesto</td><td> <input type='text' name ='194_TipImpT' id ='194_TipImpT' value='002'/></td></tr>
<tr><td>Traslados factor</td><td> <input type='text' name ='195_TipFactT' id ='195_TipFactT' value='Tasa'/></td></tr>
<tr><td>Traslados porcentaje</td><td> <input type='text' name ='196_PorImpT' id ='196_PorImpT' value='0.160000'/></td></tr>
<tr><td>Traslados importe</td><td> <input type='text' name ='197_MonImpT' id ='197_MonImpT' value='0'/></td></tr>

<tr><td>Tercero</td><td> <input type='text' name ='187_Campo0_' id ='187_Campo0_' value=''/></td></tr>

</table>
</div>

</form>



htmlt;


//****
/*
//**** formulario
$html = <<<htmlt

<form name='cfdilibre' method="post" action="scripts/reporte2.php" target="gcfdi">

<div style="height:0px; width:0px; overflow:auto;">
<table border = "0" id="comp1">

<tr><td>Clave para PAC</td><td> <input type='text' name ='1_CFD' value='380'/></td></tr>
<tr><td>Version CFDi</td><td> <input type='text' name ='2_Version' value='3.3'/></td></tr>

<!--
<tr><td>Campo</td><td> <input type='text' name ='3_TipoEnvio' value=''/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='4_TipoCliente' value=''/></td></tr>
-->
<tr><td>Tipo de comprobante (ingreso, egreso, traslado)</td><td> <input type='text' name ='5_EfectoCFD' value='ingreso'/></td></tr>
<!--
<tr><td>Campo</td><td> <input type='text' name ='6_Certificado' value=''/></td></tr>
-->
<tr><td>Serie</td><td> <input type='text' name ='7_Serie' value=''/></td></tr>
<tr><td>Folio</td><td> <input type='text' name ='8_Folio' value=''/></td></tr>
<!--
<tr><td>Campo</td><td> <input type='text' name ='9_Funcion' value=''/></td></tr>
-->
<tr><td>Fecha</td><td> <input type='text' name ='10_Fecha' value=''/></td></tr>

<!--
<tr><td>Campo</td><td> <input type='text' name ='11_AnoApro' value='MDX'/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='12_NoAprob' value=''/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='13_NoRecep' value=''/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='14_FecNoRecep' value='2011-12-16'/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='15_OrdComp' value='12312'/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='16_FecOrdComp' value=''/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='17_NoDocInt' value=''/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='18_NoRem' value=''/></td></tr>
-->

<tr><td>Forma de pago</td><td> <input type='text' name ='19_Pago' id ='19_Pago'  value='UNA SOLA EXHIBICION'/></td></tr>
<tr><td>Metodo de pago</td><td> <input type='text' name ='20_MetPago' id ='20_MetPago' value='EFECTIVO'/></td></tr>
<!--
<tr><td>Campo</td><td> <input type='text' name ='21_CondPago' value=''/></td></tr>
-->

<tr><td>Lugar de expedicion</td><td> <input type='text' name ='22_LugarExp' value='DISTRITO FEDERAL'/></td></tr>
<tr><td>Cuenta Pago</td><td> <input type='text' name ='23_NoCta' id ='NoCta_23' value=''/></td></tr>
<!--

<tr><td>Campo</td><td> <input type='text' name ='24_FechaCancel' value=''/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='25_Notas' value=''/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='26_NotasImp' value=''/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='27_ImpLetra' value='( CERO PESOS 00/100 M.N. )'/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='28_TermsFlete' value=''/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='29_NotasEmp' value=''/></td></tr>
-->
<tr><td>uuid</td><td> <input type='text' name ='39_campo01' value='01'/></td></tr>
<tr><td>uuid</td><td> <input type='text' name ='40_campo02' value='$uuid'/></td></tr>
</table>
</div>

<div style="height:0px; width:0px; overflow:auto;">
<table border = "0" id="emisor">
<tr><td>RFC emisor</td><td> <input type='text' name ='41_ERFC' value='$v41_ERFC'/></td></tr>
<tr><td>Nombre emisor</td><td> <input type='text' name ='42_ENombre' value='$v42_ENombre'/></td></tr>
<tr><td>Calle emisor</td><td> <input type='text' name ='46_ECalle' value='$v46_ECalle'/></td></tr>
<tr><td>No. exterio emisor</td><td> <input type='text' name ='83_ENoext' value='$v83_ENoext'/></td></tr>
<tr><td>No. interior emisor</td><td> <input type='text' name ='84_ENoint' value='$v84_ENoint'/></td></tr>
<tr><td>Colonia emisor</td><td> <input type='text' name ='47_EColon' value='$v47_EColon'/></td></tr>
<tr><td>Deleg. emisor</td><td> <input type='text' name ='48_EMunic' value='$v48_EMunic'/></td></tr>
<tr><td>Estado emisor</td><td> <input type='text' name ='49_EEdo' value='$v49_EEdo'/></td></tr>
<tr><td>Pais emisor</td><td> <input type='text' name ='50_Epais' value='$v50_Epais'/></td></tr>
<tr><td>C.P. emisor</td><td> <input type='text' name ='51_ECP' value='$v51_ECP'/></td></tr>
</table>

</div>
<table>
<tr>
	<td width="260">

<fieldset class="fieldsetIzquierda">
	<legend>Receptor</legend>
	<input type="hidden" name="idreceptor" id="idreceptor" value = "$idclientelibre">
	<div id="emisorcl"></div>

<table id='receptor' width="100%">
	<tr class="backGris"><td align="right"><span class="infoObligatoria">*</span>RFC</td><td> <input type='text' name ='61_RRFC' id ='RRFC_61' value='$v61_RRFC' onKeyUp="cargarSeccion('scripts/cfdilibre/bemisorcl.php', 'emisorcl', 'patron=' + this.value);document.getElementById('idreceptor').value ='';"/></td></tr>
	<tr><td align="right">Nombre</td><td> <input type='text' name ='62_RNombre' id='RNombre_62' value='$v62_RNombre' disabled/></td></tr>
	<tr class="backGris"><td align="right">Calle</td><td> <input type='text' name ='69_RCalle' id ='RCalle_69' value='$v69_RCalle' disabled/></td></tr>
	<tr><td align="right">No. exterior</td><td> <input type='text' name ='83_RNoext' id ='RNoext_83' value='$v83_RNoext' disabled/></td></tr>
	<tr class="backGris"><td align="right">No. interior</td><td> <input type='text' name ='84_RNoint' id ='RNoint_84' value='$v84_RNoint' disabled/></td></tr>
	<tr><td align="right">Colonia</td><td> <input type='text' name ='65_RColon' id ='RColon_65' value='$v65_RColon' disabled/></td></tr>
	<!--<tr class="backGris"><td align="right">Localizaci&oacute;n</td><td> <input type='text' name ='66_RLoc' id ='RLoc_66' value='$v66_RLoc' disabled/></td></tr>-->
	<tr><td align="right">Referencia</td><td> <input type='text' name ='67_RRef' id ='RRef_67' value='$v67_RRef' disabled/></td></tr>
	<tr class="backGris"><td align="right">Deleg./Mun.</td><td> <input type='text' name ='68_RMunic' id ='RMunic_68' value='$v68_RMunic' disabled/></td></tr>
	<tr><td align="right">Estado</td><td> <input type='text' name ='69_REdo' id ='REdo_69'  value='$v69_REdo' disabled/></td></tr>
	<tr class="backGris"><td align="right">Pa&iacute;s</td><td> <input type='text' name ='70_Rpais' id ='Rpais_70' value='$v70_Rpais' disabled/></td></tr>
	<tr><td align="right">C.P.</td><td> <input type='text' name ='71_RCP' id ='RCP_71' value='$v71_RCP' disabled/><input type='hidden' name ='117_Cont' id ='Cont_117' value='R'/><input type='hidden' name ='118_TipoCont' id ='TipoCont_118' value='SMTP'/><input type='hidden' name ='119_EmailCont' id ='EmailCont_119' value=''/><input type='hidden' name ='120_TelefonoCont' id ='TelefonoCont_120' value=''/><input type='hidden' name ='121_InfoCont' id ='InfoCont_121' value='.'/></td></tr>
	<tr class="backGris"><td align="right">Correo 1</td><td> <input type='text' name ='c1' id ='c1' value='' onBlur="document.getElementById('EmailCont_119').value='';if(document.getElementById('c1').value != ''){document.getElementById('EmailCont_119').value = document.getElementById('c1').value;};if(document.getElementById('c2').value != ''){if(document.getElementById('EmailCont_119').value != ''){document.getElementById('EmailCont_119').value =document.getElementById('EmailCont_119').value + ',' + document.getElementById('c2').value;}else{document.getElementById('EmailCont_119').value = document.getElementById('c2').value;};};if(document.getElementById('c3').value != ''){if(document.getElementById('EmailCont_119').value != ''){document.getElementById('EmailCont_119').value =document.getElementById('EmailCont_119').value + ',' + document.getElementById('c3').value;}else{document.getElementById('EmailCont_119').value = document.getElementById('c3').value;};};"/></td></tr>
	<tr><td align="right">Correo 2</td><td> <input type='text' name ='c2' id ='c2' value='' onBlur="document.getElementById('EmailCont_119').value='';if(document.getElementById('c1').value != ''){document.getElementById('EmailCont_119').value = document.getElementById('c1').value;};if(document.getElementById('c2').value != ''){if(document.getElementById('EmailCont_119').value != ''){document.getElementById('EmailCont_119').value =document.getElementById('EmailCont_119').value + ',' + document.getElementById('c2').value;}else{document.getElementById('EmailCont_119').value = document.getElementById('c2').value;};};if(document.getElementById('c3').value != ''){if(document.getElementById('EmailCont_119').value != ''){document.getElementById('EmailCont_119').value =document.getElementById('EmailCont_119').value + ',' + document.getElementById('c3').value;}else{document.getElementById('EmailCont_119').value = document.getElementById('c3').value;};};"/></td></tr>
	<tr class="backGris"><td align="right">Correo 3</td><td> <input type='text' name ='c3' id ='c3' value='' onBlur="document.getElementById('EmailCont_119').value='';if(document.getElementById('c1').value != ''){document.getElementById('EmailCont_119').value = document.getElementById('c1').value;};if(document.getElementById('c2').value != ''){if(document.getElementById('EmailCont_119').value != ''){document.getElementById('EmailCont_119').value =document.getElementById('EmailCont_119').value + ',' + document.getElementById('c2').value;}else{document.getElementById('EmailCont_119').value = document.getElementById('c2').value;};};if(document.getElementById('c3').value != ''){if(document.getElementById('EmailCont_119').value != ''){document.getElementById('EmailCont_119').value =document.getElementById('EmailCont_119').value + ',' + document.getElementById('c3').value;}else{document.getElementById('EmailCont_119').value = document.getElementById('c3').value;};};"/></td></tr>

</table>

</fieldset> 
<fieldset class="fieldsetIzquierda">
	<legend>Confirmaci&oacute;n</legend>
	<table border="0">
	<tr class="backGris">
		<td><span class="infoObligatoria">*</span>Serie a usar<br>
		$foliosselect <span align="right"><input type='submit' value='Facturar'  onClick="infoter='';if(document.getElementById('predial').value!=''){infoter=leerinputster('tterceros'); };idc.value =idfolios.value; idcl.value= idreceptor.value; document.getElementById('datosl').value=leerinputs('comp1') + leerinputs('emisor') + leerinputs('receptor') + leerinputs('conceptos') + infoter + leerinputs('resumen'); if(document.getElementById('RRFC_57').value!='' && metodopago.value!='' && idfolios.value>0 && idmetodopago.value!='Seleccione uno de la lista' && totalr.value>0 ){  window.open('','gcfdi' ); }else{alert('Los campos con * son requeridos');return false;}"/> 
		
			<input type='hidden' name='idc' id='id' value='id'>
			<input type='hidden' name='idcl' id='idcl' value='idcl'>
			<input type='hidden' name='filtro'  id='filtro'  value='1'>
			<input type='hidden' name='tipocfd'  id='tipocfd'  value='2'>
			<input type='hidden' name='datosl' id='datosl' value='datosl'></span>
		</td>
	</tr>	
	<tr>
		<td>
			<div id="divpredial">
				Tercero $duenioselect
				<span align="right">
					<input type="button" name="addter" id="addter" value="+ Agregar" onClick="addTer(document.getElementById('idter'), document.getElementById('predial').value,1,0);"/>
				</span>
				Predial<br><input type="text" name="predial" id="predial"/>
			</div>
		</td>
	</tr>
	<tr class="backGris">
		<td>
			<div id="tercerosshow">
				Terceros<br>
				<table border="1" id="tterceros" >
					<tr>
						<th>Nombre</th><th>Porcentaje</th><th>Acci&oacute;n</th>
					</tr>
				</table>
			</div>		
		</td>
	</tr>
	</table>	
</fieldset>
</td>
<td valign="top">

<fieldset class="fieldsetIzquierda">
	<legend>Datos Comprobante</legend>
	<table>
	<tr class="backGris">
		<td align="right"><span class="infoObligatoria">*</span>Forma de pago</td>
		<td> <input type='text' name ='metodopago' value='UNA SOLA EXHIBICION' onChange="document.getElementById('19_Pago').value=this.value"/></td>
	</tr>
	<tr>
		<td align="right"><span class="infoObligatoria">*</span>M&eacute;todo de pago</td>
		<td>$metodopagoselect</td>
	</tr>
	<tr class="backGris"><td align="right">No. Cuenta</td><td> <input type='text' name ='NoCta' onChange="document.getElementById('NoCta_23').value=this.value;"/></td></tr>	
	<tr>
		<td align="right"> <input type="checkbox" name="isTax" id="isTax" checked onclick="showMe('iva','isTax')"> Impuesto (IVA)</td>
		<td> <input type='text' name ='iva' id ='iva' value='16' onChange="document.getElementById('237_PorImpT').value=this.value; calcular();"/></td>
	</tr>	
	</table>
</fieldset>
<br>
<fieldset class="fieldsetIzquierda">
	<legend>Documento Relacionado</legend>
	<table>
	<tr class="backGris">
		<td align="right"><span class="infoObligatoria">*</span>UUID</td>
		<td> <input type='text' name ='Campo02' value='$uuid' disabled></td>
	</tr>
	<tr>
		<td align="right"><span class="infoObligatoria">*</span>Tipo Relaci&oacute;n</td>
		<td> <input type='hidden' name ='Campo01' value='01'><input type='text' value='Nota de credito de los documentos relacionados' disabled></td>
	</tr>	
	</table>
</fieldset>
<br>
<fieldset class="fieldsetIzquierda">
	<legend>Concepto</legend>

<table id="conceptos">
<!-- información de los conceptos de la factura -->
<!-- Aqui es donde hay que poner el procedimiento para activar los conceptos con el 117 y poner 
todos los valores en la cadena formateada-->
<tr class="backGris"><td align="right">No. de l&iacute;nea concepto</td><td> <input type='text' name ='122_Numlin' value='1' disabled = 'true'/></td></tr>
<tr><td align="right"><span class="infoObligatoria">*</span>Cantidad (No.) </td><td> <input type='text' name ='127_Cant' id ='127_Cant' value='1' onChange="calcular();"/></td></tr>
<tr class="backGris"><td align="right">Unidad de Medida</td><td> <input type='text' name ='129_UM' value='$v129_UM'/></td></tr>
<tr><td align="right">Descripci&oacute;n</td><td> <input type='text' name ='130_Desc' value='$v130_Desc'/></td></tr>
<tr class="backGris"><td align="right"><span class="infoObligatoria">*</span>Precio unitario</td><td> <input type='text' name ='132_PrecMX' id ='132_PrecMX' value='$v132_PrecMX' onChange="calcular();"/></td></tr>
<tr><td align="right">Importe total (sin iva)</td><td> <input type='text' name ='134_ImporMX' id ='134_ImporMX' value='0' /></td></tr>
</table>
</fieldset >
<br>

<fieldset class="fieldsetDerecha">
	<legend>Res&uacute;men</legend>
	<table align="right">
	<tr class="backGris">
		<td align="right">Sub total</td>
		<td> <input type='text' name ='subtotalr' id ='subtotalr' value='0' /></td>
	</tr>
	<tr>
		<td align="right">Impuesto</td>
		<td><input type='text' name ='impuestor' id ='impuestor' value='0' /></td>
	</tr>

	<tr class="backGris"><td align="right">Ret. I.V.A.</td><td> <input type='text' name ='retiva' id ='retiva' value='66.66' size="3" onChange="calcular();"/>%<input type='text' name ='impretencioniva' id ='impretencioniva' value='' disabled/></td></tr>
	<tr><td align="right">Ret. I.S.R.</td><td> <input type='text' name ='retisr' id ='retisr' value='10' size="3" onChange="calcular();"/>%<input type='text' name ='impretencionisr' id ='impretencionisr' value='' disabled/></td></tr>
	
	<tr class="backGreen">
		<td align="right"><span class="letraBlanca">Total</span></td>
		<td> <input type='text' name ='totalr' id ='totalr' value='0' /></td>
	</tr>	
	</table>
	
	
</fieldset>

</td>
</tr>
</table>
<div style="height:0px; width:0px; overflow:auto;">
<table border = "1" id="resumen">
<tr><td>Subtotal cfdi</td><td> <input type='text' name ='231_TotNeto' id ='231_TotNeto' value='0'/></td></tr>
<tr><td>Bruto cfdi</td><td> <input type='text' name ='232_TotBruto' id ='232_TotBruto' value='0'/></td></tr>
<tr><td>Total retenido cfdi</td><td> <input type='text' name ='233_TotImpR' id ='233_TotImpR' value=''/></td></tr>
<tr><td>Total trasladado cfdi</td><td> <input type='text' name ='234_TotImpT' id ='234_TotImpT' value=''/></td></tr>
<tr><td>Total Impuestos cfdi</td><td> <input type='text' name ='235_TotImp' id ='235_TotImp' value='0'/></td></tr>
<tr><td>Importe cfdi</td><td> <input type='text' name ='237_Importe' id ='237_Importe' value='0'/></td></tr>

<!-- Retenciones  -->
<tr><td>Campo</td><td> <input type='text' name ='238_TipImpR_IVA' id ='238_TipImpR_IVA'  value=' '/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='239_PorImpR_IVA' id ='239_PorImpR_IVA' value=''/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='240_MonImpR_IVA' id ='240_MonImpR_IVA' value=''/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='238_TipImpR_ISR' id ='238_TipImpR_ISR' value=' '/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='239_PorImpR_ISR' id ='239_PorImpR_ISR' value=''/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='240_MonImpR_ISR' id ='240_MonImpR_ISR' value=''/></td></tr>

<!-- Traslados 
aqui es donde hay que hacer la agrupacion de ivas y poner retenciones generales de ivas por concepto-->
<tr><td>Traslados impuesto</td><td> <input type='text' name ='241_TipImpT' id ='241_TipImpT' value='IVA'/></td></tr>
<tr><td>Traslados porcentaje</td><td> <input type='text' name ='242_PorImpT' id ='242_PorImpT' value='16'/></td></tr>
<tr><td>Traslados importe</td><td> <input type='text' name ='243_MonImpT' id ='243_MonImpT' value='0'/></td></tr>
<tr><td>Regimen</td><td> <input type='text' name ='244_Campo0' value='REGIMEN'/></td></tr>
<tr><td>Campo</td><td> <input type='text' name ='245_Campo1' value='Regimen General de Ley Personas Morales'/></td></tr>

</table>
</div>
<!--
<input type="button" value="revisar" onClick="alert(leerinputs('comp1') +leerinputs('emisor') + leerinputs('receptor') + leerinputs('conceptos') + leerinputs('resumen'));">
-->
</form>




htmlt;
*/

echo $html;


//*****

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>
