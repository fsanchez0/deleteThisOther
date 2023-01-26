<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$idcontrato=@$_GET["idcontrato"];
$idinmueble=@$_GET["idinmueble"];

$descripcion=@$_GET["descripcion"];
//$iva=@$_GET['iva'];
//$utilidad=@$_GET['utilidad'];
$total=@$_GET['total'];

$paso=@$_GET['paso'];
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
	$sql="select * from submodulo where archivo ='abonoduenio.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta=$row['ruta'];
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

	//para el privilegio de editar, si es negado deshabilida el botÛn
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el botÛn
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}


	//inicia la variable que contendr· la consulta
	$sql="";

	//Segun la acciÛn que se tomÛ, se proceder· a editar el sql
	switch($accion)
	{
	case "0": // Agrego


		//dividir el importe
		$fechagen =  @date("Y-m-d");
		$hora =  @date("H:i:s");
		//$total *= (-1);
		$sql="insert into edoduenio (idduenio,idcontrato,idinmueble,idtipocobro,reportado,liquidado,notaedo, iva, utilidad, importe,fechaedo,horaedo, referencia) values ";
		$sql .= "($id,$idcontrato,$idinmueble,0,false,false,'$descripcion',0,0,$total,'$fechagen','$hora','m_0')";
		//echo "<br>Agrego";
		$paso = 0;
		
		break;

	case "1": //Borro

		//$sql="delete from periodo where idperiodo=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		//$sql = "update periodo set nombre='$nombre',idmargen=$idmargen,numero=$numero where idperiodo=$id";
		///echo "<br>Actualizo";
		$nombre="";
		$idmargen="";
		$numero="";

	}

	//ejecuto la consulta si tiene algo en la variable
	//echo $sql;
	if($sql!="")
	{

		$operacion = mysql_query($sql);
		$idedoduenio =mysql_insert_id();
		$sql = "update edoduenio set referencia = 'm_" . $idedoduenio ."' where idedoduenio = $idedoduenio";
		$operacion = mysql_query($sql);

	}
	//Preparo las variables para los botÛnes
	$boton1="Limpiar";
	$boton2="Cargar";

	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la acciÛn a realizar para la siguiente presiÛn del botÛn agregar
	//en su defecto, sera accÛn agregar
	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from periodo where idperiodo = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"]);
			$idmargen=$row["idmargen"];
			$numero=$row["numero"];
		}



	}
	else
	{
		$accion="0";
	}


switch ($paso)
{
//**************************** PASO 1 ***************************
case 0: //PASO 1 : Busqueda de los pendientes

echo <<< paso1


<script language="javascript" type="text/javascript">
//var efect;
efect=0;

</script>
<div name="cobro" id="cobro" align="center">
<h1>Abono a due&ntilde;o</h1>
<form>
<table>
<tr>

<td>Due&ntilde;o </td><td colspan="3"><input type="text" name="duenio" id="duenio"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busquedaa.php','busquedacobro', 'btn=4&dato='+duenio.value);efect=0;"> </td>
</tr>
<tr>

<td>Inquilino </td><td colspan="3"><input type="text" name="nombreb" id="nombre"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busquedaa.php','busquedacobro', 'btn=1&dato='+nombreb.value);efect=0;"> </td>
</tr>
<tr>
<td>Inmueble</td><td colspan="3"> <input type="text" name="inmuebleb" id="inmueble"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busquedaa.php','busquedacobro', 'btn=2&dato='+inmuebleb.value);efect=0;"></td>
</tr>
<tr>
<td>No. contrato </td><td colspan="3"><input type="text" name="nocontratob" id="nocontrato"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busquedaa.php','busquedacobro', 'btn=3&dato='+ nocontratob.value);efect=0;"></td>
</tr>
</table>
</form>
<div name="busquedacobro" id="busquedacobro" class="scroll">

</div>
</div>
paso1;
	break;

default:


	$sql="select * from duenio where idduenio = $id";
	
	
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	$duenio = $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"];

	$sql="select * from inmueble where idinmueble = $idinmueble";
	
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	$inmueble = $row["calle"] . "No." . $row["numeroext"] . " Int." . $row["numeroint"] . "<br> Col." . $row["colonia"] . " Alc/Mun. ". $row["delmun"] . " C.P." . $row["cp"];
/*
	$duenio="";
	$sql="select * from duenioinmueble di, duenio d where di.idduenio = d.idduenio and idinmueble = $idinmueble";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$duenio .= $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "<br>";
	}
	
	*/

//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Abono a due&ntilde;o</h1>
<form>
<table border="0">
<tr>
	<td>Due&ntilde;o:</td>
	<td>$duenio</td>
</tr>

<tr>
	<td>Inmueble:</td>
	<td>$inmueble</td>
<tr>
	<td>Concepto</td>		
	<td><textarea name="descripcion" cols="40" rows="5"></textarea></td>
</tr>
<tr>
	<td>Importe:</td>
	<td><input type="text" name="total" value="" ></td>
</tr>

<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';nombre.value='';idmargen.value=0;numero.value='';">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(descripcion.value!=''  && total.value!='' ){   cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&paso=0&id=' + ids.value + '&idcontrato=$idcontrato&idinmueble=$idinmueble&total=' + total.value + '&descripcion=' + descripcion.value  )};" >
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


}



}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>