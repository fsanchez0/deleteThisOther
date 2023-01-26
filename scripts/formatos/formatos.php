<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$nombre=@$_GET['nombre'];
$numero=@$_GET['numero'];
$idmargen=@$_GET['idmargen'];
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
	$sql="select * from submodulo where archivo ='formatos.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta= $row['ruta'];
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

		$sql="insert into formato (nombre,idmargen,numero) values ('$nombre',$idmargen,$numero)";
		//echo "<br>Agrego";
		$nombre="";
		$idmargen="";
		$numero="";
		
		break;

	case "1": //Borro

		//borrar también el archivo y luego borrar el registro


		$sql="delete from formato where idformato=$id";
		//echo "<br>Borro";
		$id="";
		break;



	}

	//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
	//Preparo las variables para los botÛnes
	$boton1="Limpiar";
	$boton2="Agregar";



	$sql="select * from tipoformato";
	$margen = "<select name=\"idtipoformato\" >";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		
		$margen .= "<option value=\"" . $row["idtipoformato"] . "\" $marcar  >" . CambiaAcentosaHTML($row["tipoformato"]) .  "</option>";

	}
	$margen .="</select>";



//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Formatos</h1>
<form action="$ruta/cargarformato.php" name="marcoi" method="post" enctype="multipart/form-data" target="c_archivos" onsubmit="startUpload();" >
<table border="0">
<tr>
	<td>Tipo formato:</td>
	<td>$margen</td>
</tr>
<tr>
	<td>Archivo:</td>
	<td>
		Archivo: <input name="myfile" type="file" onchange="javascript: submit()"/>
        <input type="hidden" name="accion" id="accion" value="1">
        <input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">
	</td>
	
	
</tr>

<tr>
	<td colspan="2" align="center">

			<input type="hidden" name="ids" value="$id">


	</td>
</tr>
</table>
</form>
<iframe id="c_archivos" name="c_archivos" src="scripts/formatos/cargarformato.php" style="width:600;height:800;border:0px solid #fff;"></iframe>
</center>
formulario1;

	//echo CambiaAcentosaHTML($html);
/*	
	$sql="select * from formato, tipoformato where formato.idtipoformato = tipoformato.idtipoformato order by tipoformato,idformato ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Tipo Formato</th><th>Formato</th><th>Fecha</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<tr><td>" . $row["idformato"] . "</td><td>" . $row["tipoformato"] . "</td><td>" . $row["archivo"] . "</td><td>" . $row["fechaf"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idformato"]  . "' )}\" $txtborrar>";
		$html .= "</td></tr>";
		echo CambiaAcentosaHTML($html);
	}
	echo "</table></div>";

*/



}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>