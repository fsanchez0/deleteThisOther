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
$iva=@$_GET['iva'];
$utilidad=@$_GET['utilidad'];
$total=@$_GET['total'];
$facturar=@$_GET['facturar'];

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
	$sql="select * from submodulo where archivo ='cargoduenio.php'";
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


		$sql = "select *, d.idduenio as idd  from  duenioinmueble di, duenio d where  di.idduenio = d.idduenio and idinmueble = $idinmueble";
		$result = @mysql_query ($sql);
		while ($row = mysql_fetch_array($result))
		{	
		//calcula honorario
		//$impedo = ($row["porcentaje"]/100) * $imp;
		//$impedo= $total - $iva;
		$impedo= $total - $iva;
		
		$impedo = ($row["porcentajed"]/100) * $impedo * (-1);
		$ivaedo = ($row["porcentajed"]/100) * $iva * (-1);
		$utilidadedo = ($row["porcentajed"]/100) * $utilidad ;
		$idduenioc =$row["idd"];
	
		$fechagen = date("Y") . "-" . date("m") . "-" . date("d");
		$hora = date("H") . ":" . date("i") . ":" . date("s");
			
		$sql="insert into edoduenio (idduenio,idcontrato,idinmueble,idtipocobro,reportado,liquidado,notaedo, iva, utilidad, importe,fechaedo,horaedo, referencia, facturar) values ";
		$sql .= "($idduenioc,$idcontrato,$idinmueble,0,false,false,'$descripcion',$ivaedo,$utilidadedo,$impedo,'$fechagen','$hora','m_0',$facturar)";
	
		$resulting = @mysql_query ($sql);
		
		$idedoduenio =mysql_insert_id();
		$sql = "update edoduenio set referencia = 'm_" . $idedoduenio ."' where idedoduenio = $idedoduenio";
		$resulting = @mysql_query ($sql);
		}


		$sql="";
/*
		
		$fechagen =  @date("Y-m-d");
		$hora =  @date("H:i:s");
		$total *= (-1);
		$sql="insert into edoduenio (idduenio,idcontrato,idinmueble,idtipocobro,reportado,liquidado,notaedo, iva, utilidad, importe,fechaedo,horaedo, referencia) values ";
		$sql .= "($id,$idcontrato,$idinmueble,0,false,false,'$descripcion',$iva,$utilidad,$total,'$fechagen','$hora','m_0')";
		//echo "<br>Agrego";
		*/
		$paso = 0;
		
		break;



	}

	//ejecuto la consulta si tiene algo en la variable
	//echo $sql;

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
case 10: //PASO 1 : Busqueda de los pendientes

echo <<< paso1


<script language="javascript" type="text/javascript">
//var efect;
efect=0;

</script>
<div name="cobro" id="cobro" align="center">
<h1>Cargo sobre inmueble</h1>
<form>
<table>

<tr>

<td>Inquilino </td><td colspan="3"><input type="text" name="nombreb" id="nombre"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda.php','busquedacobro', 'btn=1&dato='+nombreb.value);efect=0;"> </td>
</tr>
<tr>
<td>Propietario</td><td colspan="3"> <input type="text" name="propietario" id="propietario"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda.php','busquedacobro', 'btn=4&dato='+propietario.value);efect=0;"></td>
</tr>
<tr>
<td>Inmueble</td><td colspan="3"> <input type="text" name="inmuebleb" id="inmueble"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda.php','busquedacobro', 'btn=2&dato='+inmuebleb.value);efect=0;"></td>
</tr>
<tr>
<td>No. contrato </td><td colspan="3"><input type="text" name="nocontratob" id="nocontrato"><input type="button" value="Buscar" onClick = "cargarSeccion('$ruta/busqueda.php','busquedacobro', 'btn=3&dato='+ nocontratob.value);efect=0;"></td>
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

	$duenio="";
	$sql="select * from duenioinmueble di, duenio d where di.idduenio = d.idduenio and idinmueble = $idinmueble";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$duenio .= $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "<br>";
	}
	
	

//Genero el formulario de los submodulos
$importe = (1 * $total) - $iva - $utilidad;
$subt=$importe + $utilidad;
$facturarchecked ="";
if($facturar=="true")
{
	$facturarchecked = " checked ";
}


echo <<<formulario1
<center>
<h1>Cargo sobre inmueble</h1>
<form>
<table border="0">
<tr>
	<td>Due&ntilde;o:</td>
	<td>$duenio</td>
</tr>

<tr>
	<td>Inmueble:</td>
	<td>$inmueble</td>
</tr>
<tr>
	<td>Concepto</td>		
	<td>$descripcion</td>
</tr>
<tr>
	<td>Importe:</td>
	<td>$importe</td>
</tr>
<tr>
	<td>Utilidad:</td>
	<td>$utilidad</td>
</tr>
<tr>
	<td>Subtotal:</td>
	<td>$subt</td>
</tr>
<tr>
	<td>I.V.A.:</td>
	<td>$iva</td>
</tr>
<tr>
	<td>Total:</td>
	<td>$total</td>
</tr>
<tr>
	<td>Facturar:</td>
	<td><input type="checkbox" name="facturar"  $facturarchecked ></td>
</tr>
<tr>
	<td colspan="2" align="center">


		<br>Nota: El cargo se aplico directa al inmueble.<br>
		<input type="button" value="Cerrar" onClick="window.close();">
	</td>
</tr>
</table>
</form>
</center>

<script>window.print();</script>
formulario1;

//<script>window.print();window.close();</script>
//<input type="button" value="imprimir" onClick="window.print();">
}



}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>