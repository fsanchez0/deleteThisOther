<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$tipocobro=@$_GET['tipocobro'];
$idfolios=@$_GET['idfolios'];
$idcategoriat=@$_GET['idcategoriat'];
$idc_prodserv=@$_GET['idc_prodserv'];
$idc_unidadmed=@$_GET['idc_unidadmed'];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='tipocobros.php'";
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
	$sql1="";
	switch($accion)
	{
	case "0": // Agrego

		$sql="insert into tipocobro (tipocobro,idfolios,idcategoriat,idc_prodserv,idc_unidadmed) values ('$tipocobro',$idfolios,$idcategoriat,$idc_prodserv,$idc_unidadmed)";
		//echo "<br>Agrego";
		$tipocobro="";
		break;

	case "1": //Borro

		 $sql="delete from tipocobro where idtipocobro=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo
		$sql = "update tipocobro set tipocobro='$tipocobro', idfolios = $idfolios, idcategoriat = $idcategoriat, idc_prodserv= $idc_prodserv, idc_unidadmed= $idc_unidadmed where idtipocobro=$id";
		$sql1 = "update historia set idcategoriat = $idcategoriat where idcobros in (select idcobros from cobros, tipocobro where cobros.idtipocobro=tipocobro.idtipocobro and idtipocobro =$id) and interes=false";
		//echo "<br>Actualizo";
		$tipocobro="";
		$idfolios="";
		$idcategoriat="";
		$idc_prodserv="";
		$idc_unidadmed="";

	}
	if($sql!="")
	{

		$operacion = mysql_query($sql);
		if($sql1!=""){
			$operacion = mysql_query($sql1);
		}
	}
	$boton1="Limpiar";
	$boton2="Agregar";

	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
	}
	else
	{
		$accion="0";
	}


	//preparo la lista del select para los folios
	$sql="select * from folios";

	$foliosselect = "<select name=\"idfolios\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($idfolios==$row["idfolios"])
		{
			$seleccionopt=" SELECTED ";
		}
		$foliosselect .= "<option value=\"" . $row["idfolios"] . "\" $seleccionopt>" . CambiaAcentosaHTML($row["serie"]) . "</option>";

	}
	$foliosselect .="</select>";

	//preparo la lista del select para las categoriat
	$sql="select * from categoriat";

	$categoriaSelect = "<select name=\"idcategoriat\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionCat="";
		if ($idcategoriat==$row["idcategoriat"])
		{
			$seleccionCat=" SELECTED ";
		}

		$categoriaSelect .= "<option value=\"" . $row["idcategoriat"] . "\" $seleccionCat>" . CambiaAcentosaHTML($row["categoria"]) . "</option>";

	}
	$categoriaSelect .="</select>";

	//preparo la lista del select para la clave del producto o servicio
	$sql="select * from c_prodserv";

	$prodSelect = "<select name=\"idc_prodserv\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionProd="";
		if ($idc_prodserv==$row["idc_prodserv"])
		{
			$seleccionProd=" SELECTED ";
		}

		$prodSelect .= "<option value=\"" . $row["idc_prodserv"] . "\" $seleccionProd>" . CambiaAcentosaHTML($row["claveps"]).": " .CambiaAcentosaHTML($row["descripcionps"]) . "</option>";

	}
	$prodSelect .="</select>";

	//preparo la lista del select para la unidad de medicion para el producto o servicio
	$sql="select * from c_unidadmed";

	$unidadSelect = "<select name=\"idc_unidadmed\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionUnidad="";
		if ($idc_unidadmed==$row["idc_unidadmed"])
		{
			$seleccionUnidad=" SELECTED ";
		}
		$unidadSelect .= "<option value=\"" . $row["idc_unidadmed"] . "\" $seleccionUnidad>" . CambiaAcentosaHTML($row["claveum"]).": " . CambiaAcentosaHTML($row["nombreum"]) . "</option>";

	}
	$unidadSelect .="</select>";

echo <<<formulario1
<center>
<h1>Tipocobro</h1>
<form>
<table border="0">
<tr>
	<td>Tipocobro:</td>
	<td><input type="text" name="tipocobro" value="$tipocobro"></td>
</tr>
<tr>
	<td>Folios a usar:</td>
	<td>
		$foliosselect
	</td>
</tr>
<tr>
	<td>Categoria a usar:</td>
	<td>
		$categoriaSelect
	</td>
</tr>
<tr>
	<td>Prod. serv. SAT:</td>
	<td>
		$prodSelect
	</td>
</tr>
<tr>
	<td>Unidad de medidad el Prod. serv. SAT:</td>
	<td>
		$unidadSelect
	</td>
</tr>

<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="idmodulo.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';tipocobro.value='';idfolios.value='0';
			idcategoriat.value='0';idc_prodserv.value='0';idc_unidadmed.value='0';">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(tipocobro.value!=''&&idfolios!='0'&&idcategoriat!='0'&&idc_prodserv!='0'&&idc_unidadmed!='0'){   if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + idmodulo.value + '&tipocobro=' + tipocobro.value + '&idfolios=' + idfolios.value + '&idcategoriat=' + idcategoriat.value + '&idc_prodserv=' + idc_prodserv.value + '&idc_unidadmed=' + idc_unidadmed.value)};if(this.value=='Agregar'&&privagregar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + idmodulo.value + '&tipocobro=' + tipocobro.value + '&idfolios=' + idfolios.value + '&idcategoriat=' + idcategoriat.value + '&idc_prodserv=' + idc_prodserv.value + '&idc_unidadmed=' + idc_unidadmed.value)}};" >
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
	
	
	$sql="select * from tipocobro t, folios f, categoriat c, c_prodserv cps, c_unidadmed cum where t.idfolios=f.idfolios and t.idcategoriat=c.idcategoriat and t.idc_prodserv=cps.idc_prodserv and t.idc_unidadmed=cum.idc_unidadmed order by tipocobro";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Tipocobro</th><th>Folios</th><th>Categoria</th><th>Prod. Serv. SAT</th><th>U. Medicion</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<tr><td>" . $row["idtipocobro"] . "</td><td>" . $row["tipocobro"] . "</td><td>" . $row["serie"] . "</td><td>" . $row["categoria"] . "</td><td>" . $row["claveps"].": ".$row["descripcionps"] . "</td><td>" . $row["claveum"] .": " .$row["nombreum"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idtipocobro"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idtipocobro"]  . "&tipocobro=" . $row["tipocobro"] . "&idfolios=" . $row["idfolios"] . "&idcategoriat=" . $row["idcategoriat"] . "&idc_prodserv=" . $row["idc_prodserv"] . "&idc_unidadmed=" . $row["idc_unidadmed"] . "' )\" $txteditar>";
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