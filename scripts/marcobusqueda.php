<?php

//es el formulario para preparar la busqueda en la herramienta
//lateral de la ventana principal requiere "resultadomarcobusqueda.php"

include 'general/sessionclase.php';
include_once('general/conexion.php');


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
/*
	$sql="select * from submodulo where archivo ='periodos.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}
*/

$sql = "select * from privilegio p, submodulo s where idusuario=" . $misesion->usuario . " and p.idsubmodulo=s.idsubmodulo and s.archivo='clientes.php'";
$operacion = mysql_query($sql);
if(mysql_num_rows($operacion)>0)
{


echo <<<formulariocl
<center>

<form>
<table border="0">
<tr>
	<td><b class="letrasn">Buscar en directorio</b><br><input type="text" name="nombre" value="" onKeyUp="cargarSeccion('scripts/resultadomarcobusqueda.php', 'clibusqueda', 'patron=' + this.value)"></td>
</tr>
<tr>
	<td>
	<div id="clibusqueda" style="height:100px; width:180; overflow:auto;">
	
	</div>
	</td>
</tr>
</table>


</form>
</center>

formulariocl;
}
else
{
	echo "";
}
}

?>