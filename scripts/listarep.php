<?php
include 'general/sessionclase.php';
include_once('general/conexion.php');

$accion= @$_GET["accion"];
$info = @$_GET["info"];
$id=@$_GET["id"];
$scriptjs="";
$idrgrupo="";
$nombre="";
$consulta="";

//echo "prueba";
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='reciboscobros.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}




	if ($priv[0]!='1')
	{
		//Es privilegio para poder ver eset modulo, y es negado
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}

	//para el privilegio de editar, si es negado deshabilida el botón
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el botón
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}





//echo $accion . "<br>";
//echo $info . "<br>";
/*
$info =str_ireplace("\\", " ", $info );
$info =str_ireplace("!", "\n", $info );
*/
	$info =str_ireplace("Ã¡", "á", $info );
	$info =str_ireplace("Ã©", "é", $info );
	$info =str_ireplace("Ã­", "í", $info );
	$info =str_ireplace("Ã³", "ó",$info );
	$info =str_ireplace("Ãº", "ú", $info );

	$info =str_ireplace("Ã", "Á", $info );
	$info =str_ireplace("Ã‰", "É", $info );
	$info =str_ireplace("Ã", "Í", $info );
	$info =str_ireplace("Ã“", "Ó", $info );
	$info =str_ireplace("Ãš", "Ú", $info );
	
	$info =str_ireplace("Ã±", "ñ", $info );
	$info =str_ireplace("Ã‘", "Ñ", $info );


	$botona="Agregar";
	switch ($accion)
	{
	case '0': // muestra campos para inserción
		$accion=1;
		break;
	case '1': // realiza la inserción de los campos en la base
	//echo $info;
	
	//nombre!consulta!idrgrupo!campos(a#b#c#..|n)  a=d1|d2|d3|..|dn
		$info=split("[!]", $info);
		$sql="insert into recibo (idrgrupo,nrecibo,consulta,filtro) values (" . $info[2] . ",'" . $info[0] . "','" . $info[1] . "','" . $info[3] . "');";
		$operacion = mysql_query($sql);
		$idnew=mysql_insert_id();

		$info=split("[~]", $info[4]);
		//conte|posX|PosY|ancho|alto|enmarco|alineacion
		foreach( $info as $key => $value )
		{
			$aux=split("[|]", $value);
			$sql="insert into configrecibo (idrecibo,campo,dimencionhw,posicionxy,marco, alineacion) values (";
			$sql.=  $idnew . ",'" . $aux[0] . "','" . $aux[4] . "|" . $aux[3] . "','" . $aux[1] . "|" . $aux[2] . "', " . $aux[5] . ",'" . $aux[6] ."');";
			$operacion = mysql_query($sql);
		}
		$accion=1;

		break;
	case '2': //Editar
		$sql="select * from recibo where idrecibo=$id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$idrgrupo=$row["idrgrupo"];
			$nombre=$row["nrecibo"];
			$consulta=$row["consulta"];
			$filtro=$row["filtro"];
	
		}
		//$scriptjs="<script languaje=\"javascript\">";
		$scriptjs="";
		//$scriptjs.="function generardivs(){";
		//$scriptjs.="nodivs=0;";
		//$scriptjs .= "cargacampoed(lx,ly,lan,lal,cont,lid,mar,alin)";
		//$scriptjs .= "cargacampoed('";
		$cuentalocal=0;
		$sql="select * from configrecibo where idrecibo=$id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
		
			$aux1=split("[|]", $row["posicionxy"]);
			
			$aux2=split("[|]", $row["dimencionhw"]);
			//$scriptjs .= "cargacampoed(" . $aux1[0] . "," . $aux1[1] . "," . $aux2[1] . "," . $aux2[0] . ",'" .  $row["campo"] . "'," . $cuentalocal . "," . $row["marco"] . ",'" . $row["alineacion"] . "');";
			$scriptjs .=  $aux1[0] . "|" . $aux1[1] . "|" . $aux2[1] . "|" . $aux2[0] . "|" .  $row["campo"] . "|" . $cuentalocal . "|" . $row["marco"] . "|" . $row["alineacion"] . "~";
			$cuentalocal++;
		}
		//$scriptjs;
		//$scriptjs =  str_ireplace("\$","\\\$",  str_ireplace("\"", "\\\" ", $scriptjs));
		//$scriptjs .= "}</script>";
		//$scriptjs .= "');";
		$botona="Actualizar";
		$accion=4;
	

		break;
	case '3': //Borrar reporte
		$sql="delete from configrecibo where idrecibo=$id";
		$operacion = mysql_query($sql);
	
		$sql="delete from recibo where idrecibo=$id";
		$operacion = mysql_query($sql);
		$accion=1;
	
		break;
	
	case '4': //Actualizar
		//nombre!consulta!idrgrupo!campos(a#b#c#..|n)  a=d1|d2|d3|..|dn
		$info;
		$info=split("[!]", $info);
		echo $sql="update recibo set idrgrupo=" . $info[2] . ", nrecibo='" . $info[0] . "', consulta='" . $info[1] . "', filtro='" . $info[3] . "' where idrecibo=$id;";
		$operacion = mysql_query($sql);
	

		$sql="delete from configrecibo where idrecibo=$id";
		$operacion = mysql_query($sql);
	
		$info=split("[~]", $info[4]);
		//conte|posX|PosY|ancho|alto|enmarco|alineacion
		foreach( $info as $key => $value )
		{
			$aux=split("[|]", $value);
			$sql="insert into configrecibo (idrecibo,campo,dimencionhw,posicionxy,marco, alineacion) values (";
			echo $sql.=  $id . ",'" . $aux[0] . "','" . $aux[4] . "|" . $aux[3] . "','" . $aux[1] . "|" . $aux[2] . "', " . $aux[5] . ",'" . $aux[6] ."');";
			$operacion = mysql_query($sql);
		}
		$accion=1;

		break;


	}

	$rgrupo="";
	$selected="";
	$rgrupo="<select name=\"idrgrupo\">\n<option value=\"0\">Seleccionar uno de la lista</option>\n";
	
	$sql = "select * from rgrupo";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		if($idrgrupo==$row["idrgrupo"])
		{
			$selected="selected";
		}
		$rgrupo .= "<option value=\"" . $row["idrgrupo"] . "\" $selected>" . $row["rgrupo"] . "</option>\n";
		$selected="";
	}
	$rgrupo .="</select>";
	
	if($scriptjs!="")
	{
		//$scriptjs = "<input type =\"button\" value=\"Mostrar Campos\" onClick=\"generardivs();\">" . $scriptjs;
		$scriptjs;
		$scriptjs = "<br><textarea cols=\"60\" rows=\"6\" name=\"txtalista\">$scriptjs</textarea>";
		$scriptjs = "<input type =\"button\" value=\"Mostrar Campos\" onClick=\"cargacampoed(txtalista.value);\">" . $scriptjs;
	
		//echo  html_entity_decode($scriptjs);
	
	}


echo <<<formulario1
<form>
<table border="1">
<tr>
	<td>Grupo de reporte</td>
	<td>$rgrupo</td>
</tr>
<tr>
	<td>Nombre</td>
	<td><input type="text" name="nombrer" value="$nombre"></tr>
</tr>
<tr>
	<td>Filtro</td>
	<td><textarea name="filtror" cols="60" rows="10">$filtro</textarea></tr>
</tr>

<tr>
	<td>Consulta</td>
	<td><textarea name="conslutar" cols="60" rows="10">$consulta</textarea></td>
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="button" value="Cerrar" onClick="document.getElementById('listarecibos').innerHTML='';this.value='Mostrar Lista'">
		<input type="button" value="$botona" onClick="if(accionr.value==1){cargarSeccion('scripts/listarep.php','listarecibos',parametrosdatoscampos(1,0,idrgrupo.value,nombrer.value,conslutar.value,filtror.value))}else{cargarSeccion('scripts/listarep.php','listarecibos',parametrosdatoscampos(4,ids.value,idrgrupo.value,nombrer.value,conslutar.value,filtror.value))};">		
		<input type="hidden" name="ids" value="$id">
		<input type="hidden" name="accionr" value="$accion">

		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">

		$scriptjs
	</td>
</tr>
</table>
</form>
formulario1;


echo <<<inilista
<center>
<table border="1">
<tr>
	<th>id</th><th>Reporte</th><th>Grupo reportes</th><th>Filtro</th><th>Consulta</th><th>Accion</th>
</tr>
inilista;

	$sql = "select recibo.idrecibo as idrec,nrecibo as nombre, consulta, rgrupo,  filtro from recibo, rgrupo  where recibo.idrgrupo=rgrupo.idrgrupo  ";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		echo "<tr><td>" . $row["idrec"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["rgrupo"] . "</td><td>" . $row["filtro"] . "</td><td>" . $row["consulta"] . "</td>";
		echo "<td><input type=\"button\" value=\"Borrar\" $txtborrar onClick=\"cargarSeccion('scripts/listarep.php','listarecibos', 'accion=3&id=" . $row["idrec"] . "') \">";
		echo "<input type=\"button\" value=\"Editar\" $txteditar onClick=\"cargarSeccion('scripts/listarep.php','listarecibos', 'accion=2&id=" . $row["idrec"] . "') \">";
		echo "<input type=\"button\" value=\"Probar\" onClick=\"nuevaVP(" . $row["idrec"] . ");\"></td></tr>";
	}

	echo "</table></center>";
	

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}


?>