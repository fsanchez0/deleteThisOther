<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$movimiento = @$_GET["movimiento"];
$id=@$_GET["id"];
$accion=@$_GET["accion"];
$creador="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='tareasasignadas.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

/*


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
*/
	$filtro="";
	$orden ="";
		
	
/*	
	switch($movimeinto)
	{
	case '+':
		$filtro = " and idtarea>$id ";
		$orden=" asc ";

	case '-':
	
		$filtro = " and idtarea<$id ";
		$orden=" desc ";
	default:
	
		$orden=" asc ";
	
	}
*/	
if($accion == "4")// //Terminar
	{
	         $sql = "update tarea set terminado=true where idtarea=$id";
		 $id="";
                 $operacion = mysql_query($sql);        
        }
	$ajuste=0;
	if($movimiento=='-')
	{
		$filtro = " and t.idtarea<$id ";
		$orden=" desc ";
		$ajuste=0;
	}
	else
	{

		$filtro = " and t.idtarea>$id ";
		$orden=" asc ";	
		$ajuste=-1;		
	}
	
	if(!$id)
	{
	
		$filtro="";
		
	}
	
	$listatareasd="No nay tareas";
	$numrt0=0;
	$sql="select t.idtarea as idtt,tarea, t.idasunto idt, t.notatarea as tdesc,terminado,vencimiento, count(idusurio) from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " group by t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento order by t.vencimiento";
	$datos=mysql_query($sql);
	$numrt0=mysql_num_rows($datos);
	
		
	$numrt1=0;
	if($numrt0>0)
	{
		$hoy=date("Y-m-d");
		$listatareasd="<table border=\"1\" >";
		$listatareasd .="<th>Id</th><th>Tarea</th><th>Descripcion</th><th></th><th></th>";
		while($row = mysql_fetch_array($datos))
		{
			$elidt=$row["idtt"];
			$eltitulot=CambiaAcentosaHTML($row["tarea"]);
			$ladescripcion=CambiaAcentosaHTML($row["tdesc"]);
			$fechalimite=$row["vencimiento"];
			$fondo="";		
			if($fechalimite<$hoy)
			{
				$fondo=" bgcolor='#b1c228' ";
				
			}

			$actualizado = "";
			$sql1="select * from tareausuario where notificado = false and idtarea =$elidt  and idusurio = " . $misesion->usuario;
			$datos2=mysql_query($sql1);
			$totalr=mysql_num_rows($datos2);
			if($totalr>0)
			  {
			    $actualizado="<b style='color:FF0000;font-size:10;' >Actualizado</b><br>";
			  }
			else
			  {
			    $actualizado="";
			  }

			
			
			
			$listatareasd .="<tr $fondo>";
			$listatareasd .="<td>$elidt </td><td>$actualizado $eltitulot</td>";
			$listatareasd .="<td>$ladescripcion</td>";			
			$listatareasd .="<td><input type=\"button\" value=\"Ver\" onClick=\"cargaTareas('$ruta/vertarea.php','contenido','id=$elidt');\" ></td>";						
			
			$listatareasd .="</tr>";
		}
		$listatareasd .="</table>";
	
	
	
	}
	else
	{
	
		$id="";
		$titulo="No hay tareas";
		$fechalimite="pendiente";
		$asunto="";
		$descripcion="";		
	
	}
	
	



echo <<<formulario1
<center>
<h1>Tareas Asignadas</h1>
<table border = "0">
<tr>
	<td colspan="2" align="center">
		<input type="radio" name="tipotarea" checked value="0" onClick="document.getElementById('tipotareav').value=this.value;document.getElementById('ftarea').value='';"> Vigentes &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="tipotarea"  value="1" onClick="document.getElementById('tipotareav').value=this.value;document.getElementById('ftarea').value='';"> Terminadas
		<input type="hidden" name="tipotareav" id="tipotareav"  value="0">
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		Tarea <input type="text" name="ftarea" id="ftarea" value="" onKeyUp="act=document.getElementById('tactualizada').checked;cargarSeccion('$ruta/listatfiltrota.php', 'listatareas', 'tareat=' + this.value + '&opcion=' + document.getElementById('tipotareav').value + '&act=' + act);" >
		Actualizadas <input type="checkbox" name="tactualizada" id="tactualizada" onClick="act=this.checked;cargarSeccion('$ruta/listatfiltrota.php', 'listatareas', 'tareat=' + document.getElementById('ftarea').value + '&opcion=' + document.getElementById('tipotareav').value + '&act=' + act);">
	</td>
</tr>
</table>

<div class="scroll" id="listatareas" >
$listatareasd
</div>
</center>
formulario1;


}
else
{

	echo "";

}




?>