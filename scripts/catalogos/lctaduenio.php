<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion =@$_GET["accion"];
$banco = @$_GET["banco"]; //lista de los contactos del duenio
$idd=@$_GET["id"]; //id del duenio a operar
$idc=@$_GET['idc'];//id de la cuenta
$titular = @$_GET['titular']; //contacto en si, (osea el telefono o el correo)
$notacta = @$_GET['notas']; // notas para el contacto
$rfcc = @$_GET['rfcc']; //bool para ver si se utilizara  el contacto
$cuenta = @$_GET['cuenta'];//si es telefono:1 o mail:2
$clabe = @$_GET['clabe'];//si es telefono:1 o mail:2
$porcentaje = @$_GET['porcentaje'];//si es telefono:1 o mail:2

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='duenios.php'";
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



	$boton1="Cancelar";
	$boton2="Actualizar";
	$accion="3";
	$sql="select * from dueniodistribucion  where iddueniodistribucion = $idc";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
			$banco=CambiaAcentosaHTML($row["banco"]);
			$titularbanco=CambiaAcentosaHTML($row["nombre"]);
 			$rfcc=CambiaAcentosaHTML($row["rfc"]);
	 		$cuenta=CambiaAcentosaHTML($row["cuenta"]);
 			$clabe=CambiaAcentosaHTML($row["clabe"]);
	 		$idbanco=CambiaAcentosaHTML($row["idbanco"]);
		 	$notacta=CambiaAcentosaHTML($row["notas"]);
		 	$porcentaje=CambiaAcentosaHTML($row["porcentaje"]);
			
	}

echo  <<<formulario2
<form>
<table border = "0">

<tr>
	<td>Banco:</td>
	<td><input type="text" name="banco" value="$banco"></td>
</tr>
<tr>
	<td>Titular en el banco:</td>
	<td><input type="text" name="titularbanco" value="$titularbanco"></td>
</tr>
<tr>
	<td>RFC:</td>
	<td><input type="text" name="rfcc" value="$rfcc"></td>
</tr>
<tr>
	<td>Cuenta:</td>
	<td><input type="text" name="cuenta" value="$cuenta"></td>
</tr>
<tr>
	<td>CLABE:</td>
	<td><input type="text" name="clabe" value="$clabe"></td>
</tr>
<tr>
	<td>Porcentaje(%):</td>
	<td><input type="text" name="porcentaje" value="$porcentaje"></td>
</tr>
<tr>
	<td>ID Banorte:</td>
	<td><input type="text" name="idbanco" value="$idbanco"></td>
</tr>
<tr>
	<td>Notas:</td>
	<td><textarea name="notac" cols="20" rows="4">$notacta</textarea></td>
</tr>
<tr>
	<td colspan="2" align="center">
	<input type="button" value="Actualizar" name="ctabtn" $btncta onClick="if(titularbanco.value!='' ){ if(this.value=='Actualizar'&&priveditarb.value==1){ cargarSeccion('$ruta/ctasduenio.php','listacuentas','accion=' + accioncb.value + '&id=$idd&idc=$idc&banco=' + banco.value + '&titular=' + titularbanco.value + '&rfcc=' + rfcc.value + '&cuenta=' + cuenta.value +  '&clabe=' + clabe.value + '&porcentaje=' + porcentaje.value   + '&notas=' + notac.value + '&idbanco=' + idbanco.value );};if(this.value=='Agregar'&&privagregarb.value==1){ cargarSeccion('$ruta/ctasduenio.php','listacuentas','accion=' + accionc.value + '&id=$idd&idc=$idc&banco=' + banco.value + '&titular=' + titularbanco.value + '&rfcc=' + rfcc.value + '&cuenta=' + cuenta.value +  '&clabe=' + clabe.value + '&porcentaje=' + porcentaje.value   + '&notas=' + notac.value + '&idbanco=' + idbanco.value )}};accionc.value=1;this.value = 'Agregar';banco.value='';titularbanco.value='';rfcc.value='';cuenta.value='';clabe.value='';porcentaje.value='';notac.value='';idbanco.value='';">
	<input type="hidden" name="accioncb" value="3">
		<input type="hidden" name="privagregarb" value="$priv[1]">
		<input type="hidden" name="priveditarb" value ="$priv[2]">	
	
	</td>
</tr>
	</table>
</form>
formulario2;
	

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>