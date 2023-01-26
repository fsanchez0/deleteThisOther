<?php
//Este modulo consta de 3 archivos:
//privilegios.php (El script principal)
//submodulopriv.php (El que se encarga de actualizar al submodulo para la asignación)
//listapriv.php (El que hace las operaicones de ABC de la tabla)
//Los últimos 2, deben de estar en la misma ruta que el principal, ya que las referencias se
//hacen partiendo de la ruta original, de otr aforma no se pueden acceder y funcionará mal el modulo

include '../general/sessionclase.php';
include_once('../general/conexion.php');

//Modulo
$accion = @$_GET["accion"];
$idt=@$_GET["idt"];
$idrecibo=@$_GET["idrecibo"];
$idcategoria=@$_GET["idcategoria"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='reciboscobros.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$rutaf=$row['ruta'] ;
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}


	if ($priv[0]!='1')
	{
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}

	if ($priv[1]=='1')
	{
		$txtagregar = "";
	}
	else
	{
		$txtagregar = " DISABLED ";
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
	
		$sql="insert into cobrorecibo (idtipocobro,idrecibo,idcategoria) values ($idt,$idrecibo,$idcategoria)";
			//echo "<br>Agrego";
			//$nombremodulo="";
		break;
	
	case "1": //Borro
	
			$sql="delete from cobrorecibo where idtipocobro=$idt and idrecibo=$idrecibo and idcategoria=$idcategoria";
			//echo "<br>Borro";
		$id="";
		break;
	
	case "3": //Actualizo
	
		$sql = "update modulo set nombre='$nombremodulo' where idmodulo=$id";
			///echo "<br>Actualizo";
		$nombremodulo="";
	
	}
	if($sql!="")
	{
	
		$operacion = mysql_query($sql);
	
	}
	$boton1="Limpiar";
	$boton2="Agregar";




	//preparo la lista del select para los tipo de cobros
	$sql="select * from tipocobro";
	$tipocobro = "<select name=\"idt\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		
		$tipocobro .= "<option value=\"" . $row["idtipocobro"] . "\"   >" . $row["tipocobro"] . "</option>";

	}
	//$usuarios .="</select>";
	$tipocobro .="</select>";

	//preparo la lista del select para los recibos
	$sql="select * from recibo,rgrupo where recibo.idrgrupo=rgrupo.idrgrupo";
	$recibos = "<select name=\"idrecibo\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		
		$recibos .= "<option value=\"" . $row["idrecibo"] . "\" >" . $row["nrecibo"] . "(" . $row["rgrupo"] .  ")</option>";

	}
	//	$modulos .="</select>";
	$recibos .="</select>";
	
	//preparo la lista del select para las categorias
	$sql="select * from categoria";
	$categorias = "<select name=\"idcategoria\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		
		$categorias .= "<option value=\"" . $row["idcategoria"] . "\" >" . $row["categoria"] .  "</option>";

	}
	//$modulos .="</select>";	
	$categorias .="</select>";	




echo <<<formulario1
<center>
<h1>Privilegios</h1>
<form>
<table border="0">
<tr>
	<td valign="top"><b>Tipo Cobro:</b></td>
	<td>

		$tipocobro

	</td>
</tr>
<tr>
	<td valign="top"><b>Recibo</b></td>
	<td>
		$recibos
	</td>
</tr>
<tr>
	<td valign="top"><b>Categor&iacute;a</b></td>
	<td>
		$categorias 
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="button" value="$boton1" onClick="this.value='Limpiar';idt.value=0;idrecibo.value=0;idcategoria.value=0;" />&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" $txtagregar onClick="if(idt.value>0 && idrecibo.value>0 && idcategoria.value>0){  cargarSeccion('$dirscript','contenido','accion=0&idt=' + idt.value + '&idrecibo=' + idrecibo.value + '&idcategoria=' + idcategoria.value )};" />
	</td>
</tr>
</table>
</form>
</center>
formulario1;

	$sql="select tipocobro, categoria, nrecibo, rgrupo, tipocobro.idtipocobro as idtc, categoria.idcategoria as idca, recibo.idrecibo as idre from tipocobro,cobrorecibo,categoria,recibo,rgrupo where tipocobro.idtipocobro=cobrorecibo.idtipocobro and cobrorecibo.idcategoria = categoria.idcategoria and cobrorecibo.idrecibo = recibo.idrecibo and recibo.idrgrupo = rgrupo.idrgrupo order by tipocobro,categoria,rgrupo,nrecibo";
	$datos=mysql_query($sql);
	echo "<div name=\"reciboslista\" id=\"reciboslista\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Tipo Cobro</th><th>Categor&iacute;a</th><th>G. recibos</th><th>Recibo</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		echo "<tr><td>" . $row["tipocobro"] . "</td><td>" . $row["categoria"] . "</td><td>" . $row["rgrupo"] . "</td><td>" . $row["nrecibo"] . "</td><td>";
		echo "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&idt=" .  $row["idtc"]  . "&idcategoria=" .  $row["idca"]  . "&idrecibo=" .  $row["idre"]  . "' )}\" $txtborrar>";
		//echo "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idmodulo"]  . "&nombremodulo=" . $row["nombre"] . "' )\" $txteditar>";
		//echo "<input type=\"hidden\" name=\"id\" value=\"" . $row["idmodulo"] . "\">";
		echo "</td></tr>";
	}
	echo "</table></div>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>