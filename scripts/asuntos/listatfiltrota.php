<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$tareat=@$_GET["tareat"];
$opt=@$_GET["opcion"];
$act=@$_GET["act"];
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
	$ajuste=0;
	

	$ellike="";
	if($tareat!="")
	{
		$ellike = " and (tarea like '%$tareat%' or t.notatarea like '%$tareat%') ";
	
	}
	
	$listatareasd="No nay tareas";
	$numrt0=0;
	
	if($act=="true")
	{
		$act = " and notificado = false ";
	}
	else
	{
		$act = "";
	}
	
	switch($opt)
	{
	case 0:
		$sql="select t.idtarea as idtt,tarea, t.idasunto idt, t.notatarea as tdesc,terminado,vencimiento, count(idusurio) from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " $ellike $act group by t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento order by t.vencimiento";
		break;
	case 1:
		$sql="select t.idtarea as idtt,tarea, t.idasunto idt, t.notatarea as tdesc,terminado,vencimiento, count(idusurio) from tarea t, tareausuario tu where terminado = 1 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " $ellike $act group by t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento order by t.vencimiento";
	
	}
	//echo $act . "<br>" . $sql;
	//$sql="select t.idtarea as idtt,tarea, t.idasunto idt, t.notatarea as tdesc,terminado,vencimiento, count(idusurio) from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " $ellike group by t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento order by t.vencimiento";
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
			$listatareasd .="<td>$elidt</td><td> $actualizado $eltitulot</td>";
			$listatareasd .="<td>$ladescripcion</td>";			
			$listatareasd .="<td><input type=\"button\" value=\"Ver\" onClick=\"cargaTareas('$ruta/vertarea.php','contenido','id=$elidt&opcion=$opt');\" ></td>";						
			
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
	
	


echo $listatareasd;
/*
echo <<<formulario1
<center>
<h1>Tareas Asignadas</h1>
<div class="scroll" >
$listatareasd
</div>
</center>
formulario1;
*/

}
else
{

	echo "";

}




?>