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


$cfd =  New cfdi32class;
$ftp= New ftpcfdi;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{


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

<tr><td>Tipo de comprobante (ingreso, egreso, traslado)</td><td> <input type='text' name ='29_EfectoCFD' value='I'/></td></tr>
<tr><td>Forma de pago</td><td> <input type='text' name ='30_MetPago' id ='30_MetPago'  value='PPD'/></td></tr>

<tr><td>Lugar de expedicion</td><td> <input type='text' name ='31_LugarExp' value='06470'/></td></tr>

</table>
</div>


<div style="height:0px; width:0px; overflow:auto;">
<table border = "0" id="emisor">
<tr><td>RFC emisor</td><td> <input type='text' name ='41_ERFC' value='PAB0802225K4'/></td></tr>
<tr><td>Nombre emisor</td><td> <input type='text' name ='42_ENombre' value='PADILLA & BUJALIL S.C.'/></td></tr>
<tr><td>Regimen Fiscal</td><td> <input type='text' name ='43_RegFiscal' value='601'/></td></tr>

<tr><td>Calle emisor</td><td> <input type='text' name ='46_ECalle' value='AV. INSURGENTES CENTRO'/></td></tr>
<tr><td>Colonia emisor</td><td> <input type='text' name ='47_EColon' value='SAN RAFAEL'/></td></tr>
<tr><td>Deleg. emisor</td><td> <input type='text' name ='48_EMunic' value='CUAUHTEMOC'/></td></tr>
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
	<input type="hidden" name="idreceptor" id="idreceptor" value = "">
	<div id="emisorcl"></div>

<table border = '1' id='receptor'>
	<tr><td>* RFC</td><td> <input type='text' name ='61_RRFC' id ='RRFC_61' value='' onKeyUp="cargarSeccion('scripts/cfdilibre/bemisorcl.php', 'emisorcl', 'patron=' + this.value);document.getElementById('idreceptor').value ='';"/></td></tr>
	<tr><td>Nombre</td><td> <input type='text' name ='62_RNombre' id='RNombre_62' value=''/><input type=hidden name ='65_RUsoCFDI' id='RUsoCFDI_65' value='G03'/></td></tr>

	<tr><td>Calle</td><td> <input type='text' name ='69_Rcalle' id ='Rcalle_69' value=''/></td></tr>
	<tr><td>Colonia</td><td> <input type='text' name ='70_RColon' id ='RColon_70' value=''/></td></tr>
	<tr><td>Deleg./Mun.</td><td> <input type='text' name ='71_RMunic' id ='RMunic_71' value=''/></td></tr>
	<tr><td>Estado</td><td> <input type='text' name ='72_REdo' id ='REdo_72'  value=''/></td></tr>
	<tr><td>Pais</td><td> <input type='text' name ='73_Rpais' id ='Rpais_73' value=''/></td></tr>
	<tr><td>C.P.</td><td> <input type='text' name ='74_RCP' id ='RCP_74' value=''/><input type='hidden' name ='106_Cont' id ='Cont_106' value='R'/><input type='hidden' name ='107_TipoCont' id ='TipoCont_107' value='SMTP'/><input type='hidden' name ='109_EmailCont' id ='EmailCont_109' value=''/><input type='hidden' name ='110_TelefonoCont' id ='TelefonoCont_110' value=''/></td></tr>

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




<fieldset >
	<legend>Concepto:</legend>

<table border = "1" id="conceptos">
<!-- información de los conceptos de la factura -->
<!-- Aqui es donde hay que poner el procedimiento para activar los conceptos con el 117 y poner 
todos los valores en la cadena formateada-->
<tr><td>No. de linea concepto</td><td> <input type='text' name ='119_Numlin' value='1' disabled = 'true'/><input type=hidden name ='121_ClaveProdServ' id='ClaveProdServ_121' value='80131500'/></td></tr>

<tr><td>* Cantidad (No.) </td><td> <input type='text' name ='125_Cant' id ='125_Cant' value='1' onChange="calcular();"/><input type=hidden name ='127_ClaveUnidad' id='ClaveUnidad_127' value='E48'/></td></tr>

<tr><td>Unidad de Medida</td><td> <input type='text' name ='128_UM' value='SRV'/></td></tr>


<tr><td>Descripci&oacute;n</td><td> <input type='text' name ='130_Desc' value=''/></td></tr>
<tr><td>* Precio unitario</td><td> <input type='text' name ='131_PrecMX' id ='131_PrecMX' value='0' onChange="calcular();"/></td></tr>


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

echo $html;

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>
