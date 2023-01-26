<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$rfc=@$_GET['rfc'];
$regimen=@$_GET['regimen'];
$domicilio=@$_GET['domicilio'];
$nosicofi=@$_GET['nosicofi'];
$fechaacbb=@$_GET['fechaacbb'];
$serie=@$_GET['serie'];
$folioi=@$_GET['folioi'];
$foliof=@$_GET['foliof'];
$cbb=@$_GET['cbb'];
$logo=@$_GET['logo'];
$secuencia=@$_GET['secuencia'];
$contribuyente=@$_GET['contribuyentecbb'];


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='folios.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta= $row['ruta'];
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

	$sql="";
	switch($accion)
	{
	case "0": // Agrego

		$sql="insert into folios (serie,folios,secuencia, terceros) values ('$serie',$folios,$secuencia, $tercero)";
		//echo "<br>Agrego";
		$serie="";
		$tercero="";
		$folios="";
		break;

	case "1": //Borro

		 $sql="delete from folios where idfolios=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update folios set serie='$serie', folios=$folios, secuencia=$secuencia, terceros=$tercero where idfolios=$id";
		///echo "<br>Actualizo";
		$serie="";
		$folios="";
		$secuencia="";

	}
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
	$boton1="Limpiar";
	$boton2="Agregar";

	if($accion=="2")
	{

		$sql="select * from folioscbb where idfoliocbb =$id";
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
		$id=$row["idfoliocbb"];
		$contribuyente=$row['contribuyentecbb'];
		$rfc=$row['rfccbb'];
		$regimen=$row['regimen'];
		$domicilio=$row['direccioncbb'];
		$nosicofi=$row['nosicofi'];
		$fechaacbb=$row['fechaacbb'];
		$serie=$row['seriecbb'];
		$folioi=$row['folioicbb'];
		$foliof=$row['foliofcbb'];
		$cbb=$row['cbb'];;
		$logo=$row['logo'];;
	
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
	}
	else
	{
		$accion="0";
	}
		
	


echo <<<formulario1
<center>
<h1>Folios para facturas</h1>
<form action="$ruta/cargarcbb.php" name="formulario" method="post" enctype="multipart/form-data" target="listacbb" onsubmit="startUpload();">
<table border="0">
<tr>
	<td>Emisor:</td>
	<td><input type="text" name="contribuyente" value="$contribuyente"></td>
</tr>
<tr>
	<td>RFC:</td>
	<td><input type="text" name="rfc" value="$rfc"></td>
</tr>
<tr>
	<td>Regimen Fiscal:</td>
	<td><input type="text" name="regimen" value="$regimen"></td>
</tr>
<tr>
	<td>Domicilio:</td>
	<td><input type="text" name="domicilio" value="$domicilio"></td>
</tr>
<tr>
	<td>No. SICOFI:</td>
	<td><input type="text" name="nosicofi" value="$nosicofi"></td>
</tr>
<tr>
	<td>Fecha alta:</td>
	<td><input type="text" name="fechaacbb" value="$fechaacbb"></td>
</tr>
<tr>
	<td>Serie:</td>
	<td><input type="text" name="serie" value="$serie"></td>
</tr>
<tr>
	<td>Folio inicial:</td>
	<td><input type="text" name="folioi" value="$folioi"></td>
</tr>
<tr>
	<td>Folio final:</td>
	<td><input type="text" name="foliof" value="$foliof"></td>
</tr>
<tr>
	<td>CBB:</td>
	<td><input type="file" name="cbb" value="$cbb"></td>
</tr>
<tr>
	<td>Logo:</td>
	<td><input type="file" name="logo" value="$logo"></td>
</tr>
<tr>
	<td>Secuencia:</td>
	<td><input type="text" name="secuencia" value="$secuencia"></td>
</tr>
<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="idmodulo.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';rfc.value='';regimen.value='';domicilio.value='';nosicofi.value='';serie.value='';folioi.value='';foliof.value='';cbb.value='';logo.value='';secuencia.value='';fechaacbb.value='';contribuyente.value='';">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="submit" value="$boton2" name="agregar" onClick="if(rfc.value!='' && regimen.value!=''  && domicilio.value!='' && fechaacbb.value!='' && folioi.value!='' && foliof.value!='' && nosicofi.value!='' && secuencia.value!=''){   if(this.value=='Actualizar'&&priveditar.value==1){ return true};if(this.value=='Agregar'&&privagregar.value==1){ return true;}}else{alert('cancelado');return false};" >

		<input type="hidden" name="idmodulo" value="$id">
		<input type="hidden" name="accion" value="$accion">
		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">

	</td>
</tr>
</table>
</form>

<iframe id="listacbb" name="listacbb" src="$ruta/cargarcbb.php" style="width:600;height:400;border:0px solid #fff;"></iframe>
</center>
formulario1;


	//echo CambiaAcentosaHTML($html);
	
	
	$sql="select * from folioscbb";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>RFC</th><th>Serie</th><th>Folio. I.</th><th>Folio. F.</th><th>Secuencia</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
	
		$html = "<tr><td>" . $row["idfoliocbb"] . "</td><td>" . $row["rfccbb"] . "</td><td>" . $row["seriecbb"] . "</td><td>" . $row["folioicbb"] . "</td><td>" . $row["foliofcbb"] . "</td><td>" . $row["secuencia"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idfoliocbb"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idfoliocbb"]  . "' )\" $txteditar>";
		//echo "<input type=\"hidden\" name=\"id\" value=\"" . $row["idmodulo"] . "\">";
		$html .= "</td></tr>";
		echo CambiaAcentosaHTML($html);
	}
	echo "</table></div>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>