<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$nombre=@$_GET['nombre'];
$apaterno=@$_GET['apaterno'];
$amaterno=@$_GET['amaterno'];
$telparticular=@$_GET['telparticular'];
$teloficina=@$_GET['teloficina'];
$telmovil=@$_GET['telmovil'];
$telotros=@$_GET['telotros'];
$direccion=@$_GET['direccion'];
$email=@$_GET['email'];
$denominacionf=@$_GET['denominacionf'];
$rfc=@$_GET['rfc'];
$direccionf=@$_GET['direccionf'];
$notas=@$_GET['notas'];
$pagina=@$_GET['pagina'];

$elemento="";
$exp="";
$fcreacion="";
$ubicacion="";
$descripcion="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$idusuario = $misesion->usuario;
	$sql="select * from submodulo where archivo ='expseguimientop.php'";
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


	$sql="select archivo, a.idarchivo as ida, idexpediente, numero, fechacreacion,ubicacion,fechacambio, descripcione from archivo a,expediente e where a.idarchivo=e.idarchivo and e.idexpediente = $id order by archivo";
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	
	$elemento = $row["archivo"];
	$exp = $row["ida"] . "." . $row["numero"];
	$fcreacion = $row["fechacreacion"];
	$ubicacion = $row["ubicacion"] . "(" . $row["fechacambio"] . ")";
	$descripcion= $row["descripcione"];


echo <<<formulario

<center>
<h1>Seguimiento de expedientes<br><input type="button" value="Regresar a busqueda" onClick="cargaTareas('$ruta/expseguimientop.php','contenido','');" ></h1>


<table border ="0">
<tr>
	<td valign="top">
		<table border="0">
		<tr>
			<td><b>Elemento:</b></td>
			<td>$elemento</td>				
		<tr>
		<tr>
			<td><b>Expediente:</b></td>
			<td>$exp</td>				
		<tr>
		<tr>
			<td><b>Creado:</b></td>
			<td>$fcreacion</td>				
		<tr>
		<tr>
			<td><b>Ubicaci&oacute;n:</b></td>
			<td>$ubicacion</td>				
		<tr>
		<tr>
			<td><b>Descripci&oacute;n:</b></td>
			<td>$descripcion</td>				
		<tr>		
		</table>
	
	</td>
</tr>
<tr>
	<td>
		<form action="$ruta/contenidoarchivo.php" name="marcoi" method="post" enctype="multipart/form-data" target="c_archivos" onsubmit="startUpload();">
		<table border="0">
		<tr>
			<td>
			Nota <br> <textarea cols="50" rows="6" name="nota" id="nota" ></textarea>
			<input type="hidden" name="idp" value="$id">
			<input type="hidden" name="accion" value="1">
			</td>
			
		<tr>
		<tr>
			<td align="center">Archivo: <input name="myfile" type="file" /></td>
					
		<tr>
		<tr>
			<td align="center"><input type ="reset" value="Limpiar">&nbsp;&nbsp;&nbsp;<input type ="submit" value="Archivar"></td>
				
		<tr>
		
		</table>	
		</form>
	</td>

</tr>
</table>
<iframe id="c_archivos" name="c_archivos" src="$ruta/contenidoarchivo.php?id=$id&accion=0" style="height:500;width:500;border:0px solid #bfbdae;"></iframe>


</center>





formulario;


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>