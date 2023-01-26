<?php
include '../general/funcionesformato.php';
include '../general/sessionclase.php';
//include '../general/ftpclass.php';
include_once('../general/conexion.php');
//include ("../cfdi/cfdiclassn.php");
//require('../fpdf.php');




$id=@$_GET["id"]; //para el Id de la consulta que se requiere hacer: de arrendamiento idhistoria, de libre idfolio
$filtro=@$_GET["filtro"]; //para la especificacion del tipo re recibo inmueble=null, libre=0;
$datosl=@$_GET["datosl"]; //para recibir todos los datos para la factura segun el layaut que biene de la facturalibre


//$cfd =  New cfdi32class;
//$ftp= New ftpcfdi;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='cbb.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta= $row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}






	$hoy=date("Y-m-d");
	$sql="select * from folioscbb where vigenciacbb>='$hoy'";

	$foliosselect = "<select name=\"idfolioscbb\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		$foliosselect .= "<option value=\"" . $row["idfoliocbb"] . "\" >" . CambiaAcentosaHTML($row["contribuyentecbb"]) . " " . $row["nosicofi"] . "</option>";

	}
	$foliosselect .="</select>";

	$hoy = date("Y-m-d");
$html = <<<htmlt

<form name='cfdilibre'>


<table border="0">
<tr>
	<td>

<fieldset >
	<legend>Receptor:</legend>
	<input type="hidden" name="idreceptor" id="idreceptor" value = "">
	<div id="emisorcl"></div>

<table border = '1' id='receptor'>
	<tr><td>* RFC</td><td> <input type='text' name ='RRFC' id ='RRFC' value='' onKeyUp="cargarSeccion('$ruta/bemisorcl.php', 'emisorcl', 'patron=' + this.value);document.getElementById('idreceptor').value ='';"/></td></tr>
	<tr><td>Nombre</td><td> <input type='text' name ='RNombre' id='RNombre' value=''/></td></tr>
	<tr><td>Calle</td><td> <input type='text' name ='RCalle' id ='RCalle' value=''/></td></tr>
	<tr><td>No. exterior</td><td> <input type='text' name ='RNoext' id ='RNoext' value=''/></td></tr>
	<tr><td>No. interior</td><td> <input type='text' name ='RNoint' id ='RNoint' value=''/></td></tr>
	<tr><td>Colonia</td><td> <input type='text' name ='RColon' id ='RColon' value=''/></td></tr>
	<tr><td>Localizacion</td><td> <input type='text' name ='RLoc' id ='RLoc' value=''/></td></tr>
	<tr><td>Referencia</td><td> <input type='text' name ='RRef' id ='RRef' value=''/></td></tr>
	<tr><td>Alc./Mun.</td><td> <input type='text' name ='RMunic' id ='RMunic' value=''/></td></tr>
	<tr><td>Estado</td><td> <input type='text' name ='REdo' id ='REdo'  value=''/></td></tr>
	<tr><td>Pais</td><td> <input type='text' name ='Rpais' id ='Rpais' value=''/></td></tr>
	<tr><td>C.P.</td><td> <input type='text' name ='RCP' id ='RCP' value=''/><input type='hidden' name ='Cont' id ='Cont' value='R'/><input type='hidden' name ='TipoCont' id ='TipoCont' value='SMTP'/><input type='hidden' name ='EmailCont' id ='EmailCont' value=''/><input type='hidden' name ='TelefonoCont' id ='TelefonoCont' value=''/><input type='hidden' name ='InfoCont' id ='InfoCont' value=''/></td></tr>

	<tr><td>Correo 1</td><td> <input type='text' name ='c1' id ='c1' value='' onBlur="document.getElementById('EmailCont').value='';if(document.getElementById('c1').value != ''){document.getElementById('EmailCont').value = document.getElementById('c1').value;};if(document.getElementById('c2').value != ''){if(document.getElementById('EmailCont').value != ''){document.getElementById('EmailCont').value =document.getElementById('EmailCont').value + ',' + document.getElementById('c2').value;}else{document.getElementById('EmailCont').value = document.getElementById('c2').value;};};if(document.getElementById('c3').value != ''){if(document.getElementById('EmailCont').value != ''){document.getElementById('EmailCont').value =document.getElementById('EmailCont').value + ',' + document.getElementById('c3').value;}else{document.getElementById('EmailCont').value = document.getElementById('c3').value;};};"/></td></tr>
	<tr><td>Correo 2</td><td> <input type='text' name ='c2' id ='c2' value='' onBlur="document.getElementById('EmailCont').value='';if(document.getElementById('c1').value != ''){document.getElementById('EmailCont_119').value = document.getElementById('c1').value;};if(document.getElementById('c2').value != ''){if(document.getElementById('EmailCont').value != ''){document.getElementById('EmailCont').value =document.getElementById('EmailCont').value + ',' + document.getElementById('c2').value;}else{document.getElementById('EmailCont').value = document.getElementById('c2').value;};};if(document.getElementById('c3').value != ''){if(document.getElementById('EmailCont').value != ''){document.getElementById('EmailCont').value =document.getElementById('EmailCont').value + ',' + document.getElementById('c3').value;}else{document.getElementById('EmailCont').value = document.getElementById('c3').value;};};"/></td></tr>
	<tr><td>Correo 3</td><td> <input type='text' name ='c3' id ='c3' value='' onBlur="document.getElementById('EmailCont').value='';if(document.getElementById('c1').value != ''){document.getElementById('EmailCont_119').value = document.getElementById('c1').value;};if(document.getElementById('c2').value != ''){if(document.getElementById('EmailCont').value != ''){document.getElementById('EmailCont').value =document.getElementById('EmailCont').value + ',' + document.getElementById('c2').value;}else{document.getElementById('EmailCont').value = document.getElementById('c2').value;};};if(document.getElementById('c3').value != ''){if(document.getElementById('EmailCont').value != ''){document.getElementById('EmailCont').value =document.getElementById('EmailCont').value + ',' + document.getElementById('c3').value;}else{document.getElementById('EmailCont').value = document.getElementById('c3').value;};};"/></td></tr>
	</table>
</fieldset> 


</td>
<td valign = "top">

<fieldset >
	<legend>Concepto:</legend>

<table border = "1" id="conceptos">
<!-- información de los conceptos de la factura -->
<!-- Aqui es donde hay que poner el procedimiento para activar los conceptos con el 117 y poner 
todos los valores en la cadena formateada-->
	<tr>
		<td>* Fecha (aaaa-mm-dd):</td>
		<td> <input type='text' name ='fechacbb' value='$hoy' /></td>
	</tr>
	<tr>
		<td>* M&eacute;todo de pago:</td>
		<td> <input type='text' name ='metodopago' value='NO IDENTIFICADO' /></td>
	</tr>	
<tr><td>* Descripci&oacute;n</td><td> <input type='text' name ='Desc' value=''/></td></tr>
<tr><td>* Precio unitario</td><td> <input type='text' name ='PrecMX' id ='PrecMX' value='0' onChange="subt =this.value; iva=subt * 0.16; total = 0;subtotalr.value = subt; impuestor.value = iva; totalr.value = (subt*1) + (iva*1);"/></td></tr>


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
	<tr>
		<td>Total </td>
		<td> <input type='text' name ='totalr' id ='totalr' value='0' /></td>
	</tr>	
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
			<input type='button' value='facturar' onClick="if(document.getElementById('RRFC').value!='' && metodopago.value!='' && idfolioscbb.value>0  && totalr.value>0 && fechacbb.value!='' && Desc.value!='' ){  window.open( '$ruta/generar.php?id=' + idfolioscbb.value + '&idcl=' + idreceptor.value + '&rfc=' + RRFC.value +  '&nombre=' + RNombre.value +  '&calle=' + RCalle.value +  '&noext=' + RNoext.value +  '&noint=' + RNoint.value +  '&col=' + RColon.value +  '&loc=' + RLoc.value +  '&ref=' + RRef.value +  '&munic=' + RMunic.value +  '&edo=' + REdo.value +  '&pais=' + Rpais.value +  '&cp=' + RCP.value +  '&email=' + EmailCont.value +  '&fecha=' + fechacbb.value +  '&metodopago=' + metodopago.value +  '&concepto=' + Desc.value +  '&importe=' + subtotalr.value +  '&iva=' + impuestor.value +  '&total=' + totalr.value);this.disabled='true'}else{alert('Los campos con * son requeridos')}"/>
		</td>
	</tr>	
	</table>
	</center>

</fieldset>

</td>
</tr>



</table>

</form>



htmlt;

echo $html;

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>