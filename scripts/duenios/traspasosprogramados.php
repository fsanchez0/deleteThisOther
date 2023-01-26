<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$de=@$_GET['de'];
$para=@$_GET['para'];
$justificacion=@$_GET['justificacion'];
$unico=@$_GET['unico'];
/*
if(!$activo)
{
	$activo=0;
}
*/
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='traspasosprogramados.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta= $row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=@split("\*",$priv);

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


	//inicia la variable que contendrá la consulta
	$sql="";

	//Segun la acción que se tomó, se procederá a editar el sql
	switch($accion)
	{
	case "0": // Agrego

		$sql="insert into traspasodepara (de,para,justificacion,unico) values ($de,$para,'$justificacion',$unico)";
		//echo "<br>Agrego";
		$de="";
		$para="";
		$justificacion="";
		
		break;

	case "1": //Borro

		$sql="delete from traspasodepara where idtraspasodepara=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update traspasodepara set justificacion='$justificacion',de=$de,para=$para,unico = $unico where idtraspasodepara=$id";
		///echo "<br>Actualizo";
		$de="";
		$para="";
		$justificacion="";

	}

	//ejecuto la consulta si tiene algo en la variable
	//echo $sql;
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
	//Preparo las variables para los botónes
	$boton1="Limpiar";
	$boton2="Agregar";

	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la acción a realizar para la siguiente presión del botón agregar
	//en su defecto, sera accón agregar
	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from traspasodepara where idtraspasodepara = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$justificacion=CambiaAcentosaHTML($row["justificacion"]);
			$de=$row["de"];
			$para=$row["para"];
			$unico = $row["unico"];
			
		}



	}
	else
	{
		$accion="0";
	}


	$mes = date("Y-m") . "-01";
//cargarSeccion('$ruta/infotraspaso.php','propietario','id=' + this.value )
	$sql="select *, idduenio as id from duenio";//restringir a los due–os que tienen
	//$sql="select d.idduenio as id, nombre, nombre2, apaterno, amaterno, count(idedoduenio) from edoduenio ed, duenio d where ed.idduenio = d.idduenio  and reportado = true and isnull(fechagen)=false and fechagen >= '$mes' group by nombre, nombre2, apaterno, amaterno";
	$selectde = "<select name=\"de\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$selectpara = "<select name=\"para\"  onChange=\"if(de.value==para.value){alert('La seleccion no es aceptada'); this.value='';}\"><option value=\"\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$marcarde ="";	
		$marcarpara ="";		
		if($de==$row["id"])
		{
			$marcarde=" selected ";
		}
		if($para==$row["id"])
		{
			$marcarpara=" selected ";
		}		
		$selectde .= "<option value=\"" . $row["id"] . "\" $marcarde  >" . CambiaAcentosaHTML($row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] ) .  "</option>";
		$selectpara .= "<option value=\"" . $row["id"] . "\" $marcarpara  >" . CambiaAcentosaHTML($row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] ) .  "</option>";


	}
	$selectde .="</select>";
	$selectpara .="</select>";
	$marcadounico = "";
	if($unico == 1)
	{
		$marcadounico = " checked ";
	
	}
	

//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Traspasos programados</h1>
<form>
<table border="0">
<tr>
	<td>
	Del propietario:<br>
	$selectde<br>
	<div id="propietariode"> </div>
	</td>
	
	<td>
	Para el propietario:<br>
	$selectpara<br>
	<div id="propietariopara"> </div>
	</td>
	
</tr>
<tr>
	<td colspan="2">
	Justificaci&oacute;n<br>
	<textarea name="justificacion" cols="90" rows="5">$justificacion</textarea>
	</td>
</tr>
<tr>
	<td colspan="2">
	Unico:<input type="checkbox" name="unico" $marcadounico>
	</td>
</tr>

<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';de.value='0';para.value='';justificacion.value='';">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(de.value!='0' && para.value!='0' && justificacion.value!='' ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&de=' + de.value + '&para=' + para.value + '&justificacion=' + justificacion.value + '&unico=' + unico.checked )};if(this.value=='Agregar'&&privagregar.value==1){  cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&de=' + de.value + '&para=' + para.value + '&justificacion=' + justificacion.value + '&unico=' + unico.checked)}};" >
		<input type="hidden" name="ids" value="$id">
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
	
	$sql="select * from traspasodepara ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>De</th><th>Para</th><th>unico</th><th>Justificaci&oacute;n</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
	
		$sqld="select * from duenio where idduenio = " . $row["de"];
		$infod=mysql_query($sqld);
		$rowd = mysql_fetch_array($infod);
		$nd=CambiaAcentosaHTML($rowd["nombre"] . " " . $rowd["nombre2"] . " " . $rowd["apaterno"] . " " . $rowd["amaterno"] );
		
		$sqld="select * from duenio where idduenio = " . $row["para"];
		$infod=mysql_query($sqld);
		$rowd = mysql_fetch_array($infod);
		$np=CambiaAcentosaHTML($rowd["nombre"] . " " . $rowd["nombre2"] . " " . $rowd["apaterno"] . " " . $rowd["amaterno"] );
				
		
		
		$html = "<tr><td>" . $row["idtraspasodepara"] . "</td><td>$nd</td><td>$np</td><td>" . $row["unico"] . "</td><td>" . $row["justificacion"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idtraspasodepara"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idtraspasodepara"]   . "' )\" $txteditar>";
		
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