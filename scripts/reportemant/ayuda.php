<?php
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$inmueble=@$_GET['inmueble'];
$inquilino=@$_GET['inquilino'];

$prioridad="<select name=\"prioridad\"><option value=\"0\">Selecione una</option> ";
$sql="select * from prioridadmant";
$operacion = mysql_query($sql);
while($row = mysql_fetch_array($operacion))
{
	$prioridad .="<option value=\"". $row["idprioridadmant"] . "\">" . $row["prioridadmant"] . "</option>";
	
		
}
$prioridad .="</select>";

//lista de en proceso ojo... solo los correspondientes al periodo del contrato
$cerrado=0;

$cerrados ="<table border ='0'>";
$proceso = "<table border ='0'>";

$sql = "select idreportemant, fechar, cerrado from inquilino i, contrato c, reportemant r where i.idinquilino = c.idinquilino and c.idinmueble = r.idinmueble and c.concluido = false and i.idinquilino = $inquilino";
$operacion = mysql_query($sql);
while($row = mysql_fetch_array($operacion))
{
	if($row["cerrado"]==$cerrado )
	{
		$proceso .= "<tr><td><a style=\"cursor: pointer; font-size:10pt\" onclick=\"cargarSeccion('seguimiento.php','resultados', 'rmantenimiento=" . $row["idreportemant"] ."');\" >" . $row["idreportemant"] . " (" . $row["fechar"] . ")</a></td></tr>";
	}
	else
	{
		$cerrados .= "<tr><td><a style=\"cursor: pointer; font-size:10pt\" onclick=\"cargarSeccion('seguimiento.php','resultados', 'rmantenimiento=" . $row["idreportemant"] ."');\" >" . $row["idreportemant"] . " (" . $row["fechar"] . ")</a></td></tr>";
	}
	
		
}
$cerrados .="</table>";
$proceso .= "</table>";



//lista de cerrados



echo <<<formulario
<html>
<head>
<title>Reporte de Mantenimiento</title>
<link rel="stylesheet" type="text/css" href="../../estilos/estilos.css">
<script type=text/javascript src="../mijs.js"></script>
<script language="javascript" src="../ajax.js" type="text/javascript"></script>
<script languaje="javascript" type="text/javascript">
function cargarSeccion(root,loc, param)
{
	var cont;
	cont = document.getElementById(loc);
	ajax=nuevoAjax();
	ajax.open("GET", root + "?"+param ,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
  			cont.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
</script>

</head>
<body style="margin-top:0; margin-bottom:0; margin-left:0; margin-right:0">

	<table border ="0">
	<tr >	
		<td colspan="3">
			<table class="Cabecera">
			<tr>
				<td width="1"><img src="../../imagenes/logo.png" ></td>
				<td colspan="2" align="center" width="100%">
					<h1 >Reporte de Mantenimiento</h1>
				</td>
				<td align="center" valign="bottom">
					<a onClick ="document.close(); ">Terminar</a>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<table>
			<tr>
				<th>En proceso</th>	
			</tr>
			<tr>
				<td>
				<div id="proceso" class="scrollayuda">
					$proceso
				</div>
				</td>
			</tr>
			<tr>
				
				<th>Terminados</th>	
			</tr>
			<tr>
				<td>
				<div id="terminados" class="scrollayuda">
					$cerrados 
				</div>
				</td>
			</tr>			
			</table>
		
		</td>		
		<td width="100%" valign="top">
			<div id="resultados" style="margin:10px">
			<h2>
				Estamos a sus ordenes y nos place ayudarle
			</h2>
			<p>
				Si desea hacer una nueva solicitud, escriba en el marco de la derecha con su prioridad y correo presionando el botón de enviar para que nos haga llegar su solicitud.
			</p>
			<p>
				Para poder ver el seguimiento de su solicitud, presione el numero de solicitud de la lista del lado izquierdo.
			</p>
			
			</div>
		
		</td>
		<td valign="top">
			<form>
				<p>
				Correo<br>
				<input type="text" name="correo">
				</p>	
				<p>
				Prioridad<br>
				$prioridad
				</p>	
				<p>
				Solicitud<br>
				<textarea name="solicitud" rows="10" cols="20">
				
				</textarea>
				</p>	
				<input type="button" value="Enviar" onClick="cargarSeccion('enviar.php','resultados', 'correo='+ correo.value + '&prioridad=' + prioridad.value + '&solicitud=' + solicitud.value + '&inmueble=$inmueble&inquilino=$inquilino'); prioridad.value=0; solicitud.value='';correo.value=''">
			</form>
		
		</td>
	
	</tr>
	
	</table>
</body>
</html>
formulario;





?>