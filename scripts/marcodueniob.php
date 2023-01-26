<?php

//es el formulario para preparar la busqueda en la herramienta
//lateral de la ventana principal requiere "resultadomarcobusqueda.php"

include 'general/sessionclase.php';
include_once('general/conexion.php');


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{


$sql = "select * from privilegio p, submodulo s where idusuario=" . $misesion->usuario . " and p.idsubmodulo=s.idsubmodulo and s.archivo='duenios.php'";
$operacion = mysql_query($sql);
if(mysql_num_rows($operacion)>0)
{


echo <<<formulariocl
<center>

<form>
<table border="1">
<tr>
	<td> 
		Nombre <input type="radio" name="filtrod" value="1" onclick="filtrodu.value = 1;" checked/>&nbsp;&nbsp;&nbsp;&nbsp;
		Inmueble<input type="radio" name="filtrod" value="2" onclick="filtrodu.value = 2;"/>&nbsp;&nbsp;&nbsp;&nbsp;
		Apoderado <input type="radio" name="filtrod" value="3" onclick="filtrodu.value = 3;"/> 
		<input type="hidden" name="filtrodu" value="1"/> <br>
<b class="">Buscar Propietario</b><br><input type="text" name="nombred" value="" onKeyUp="cargarSeccion('scripts/resdueniob.php', 'duebusqueda', 'patron=' + this.value + '&filtro=' + filtrodu.value)"></td>
</tr>
<tr>
	<td>
	<div id="duebusqueda" style="height:50px; width:400; overflow:auto;">
	
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