<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$movimiento = @$_GET["movimiento"];
$id=@$_GET["id"];
$creador="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='tareas.php'";
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
			
			
			
			$listatareasd .="<tr $fondo>";
			$listatareasd .="<td>$elidt</td><td> $eltitulot</td>";
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
<div class="scroll" >
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