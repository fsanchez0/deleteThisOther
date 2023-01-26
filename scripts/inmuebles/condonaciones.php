<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='condonaciones.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] ;// . "/" . $row['archivo'];
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





$html = <<<fin
<center>
<h1>Condonaciones</h1>
<table border="1">
<tr>
	<td> Inquilino</td><td><input type = "text" name="inquilino" value="" onKeyUp="cargarSeccion('$dirscript/listacondonaciones.php', 'adeudos', 'accion=1&inquilino=' + this.value)"></td>
</tr>

<tr>
	<td> Inmueble</td><td><input type = "text" name="inmueble" value="" onKeyUp="cargarSeccion('$dirscript/listacondonaciones.php', 'adeudos', 'accion=2&inmueble=' + this.value)"></td>
</tr>
<tr>
	<td> contrato</td><td><input type = "text" name="contrato" value="" onKeyUp="cargarSeccion('$dirscript/listacondonaciones.php', 'adeudos', 'accion=3&contrato=' + this.value)"></td>
</tr>

</table>
</center><br><br>
<div id="adeudos" class="scroll"></div>

fin;
	echo CambiaAcentosaHTML($html);

}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}

?>