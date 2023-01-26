<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$serie=@$_GET['serie'];
$folios=@$_GET['folios'];
$secuencia=@$_GET['secuencia'];
$tercero=@$_GET['tercero'];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='folios.php'";
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

		$sql="select * from folios where idfolios =$id";
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
		$serie = $row["serie"];
		$folios = $row["folios"];
		$secuencia=$row["secuencia"];
		$tercero=$row["terceros"];
	
	
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
	}
	else
	{
		$accion="0";
	}
	
	$tercerosl="<select name='terceros'><option value=''>Seleccione uno</option>";
	switch($tercero)
	{
	case 0:
		$tercerosl .="<option value='0' selected>Propios</option>";
		$tercerosl .="<option value='1'>Terceros</option>";
		break;
	case 1:
		$tercerosl .="<option value='0'>Propios</option>";
		$tercerosl .="<option value='1' selected>Terceros</option>";	
		break;
	default:
		$tercerosl .="<option value='0'>Propios</option>";
		$tercerosl .="<option value='1'>Terceros</option>";	
	}
	$tercerosl .="</selected>";	
	


echo <<<formulario1
<center>
<h1>Folios para facturas</h1>
<form>
<table border="0">
<tr>
	<td>Serie:</td>
	<td><input type="text" name="serie" value="$serie"></td>
</tr>
<tr>
	<td>Folios:</td>
	<td><input type="text" name="folios" value="$folios"><br><font size="1">(Si coloca cero se reconocera como n&uacute;mero infinito de folios)</font></td>
</tr>
<tr>
	<td>Secuencia:</td>
	<td><input type="text" name="secuencia" value="$secuencia"><br><font size="1">(Coloque cero para comenzar en uno o el n&uacute;mero anterior al que se desea iniciar a asignar)</font></td>
</tr>
<tr>
	<td>Tipo Folio:</td>
	<td>$tercerosl</td>
</tr>

<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="idmodulo.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';folios.value='';serie.value='';secuencia.value='';terceros.value=''">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(folios.value!='' && secuencia.value!=''){   if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + idmodulo.value + '&serie=' + serie.value + '&folios=' + folios.value + '&secuencia=' + secuencia.value + '&tercero=' + terceros.value )};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + idmodulo.value + '&serie=' + serie.value + '&folios=' + folios.value + '&secuencia=' + secuencia.value + '&tercero=' + terceros.value)}};" >
		<input type="hidden" name="idmodulo" value="$id">
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
	
	
	$sql="select * from folios";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Serie</th><th>Folios</th><th>Secuencia</th><th>Tipo</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$tipos="";
		if($row["terceros"]=="1")
		{
			$tipos="Terceros";
		}
		else
		{
			$tipos="Propios";
		}
	
		$html = "<tr><td>" . $row["idfolios"] . "</td><td>" . $row["serie"] . "</td><td>" . $row["folios"] . "</td><td>" . $row["secuencia"] . "</td><td>$tipos</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idfolios"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idfolios"]  . "' )\" $txteditar>";
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