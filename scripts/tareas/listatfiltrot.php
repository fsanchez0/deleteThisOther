<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$tareat=@$_GET["tareat"]; 	//texto a veririficar
$opt=@$_GET["filtro"];		// si es vigente o no
$act=@$_GET["act"];		// si solo actualizados 
//$idp=@$_GET["idp"];		//id del proyecto
$creador="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='tarea.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
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
	$hoy=date("Y-m-d");
	switch($opt)
	{
	case 'false':
		//$sql="select t.idtarea as idtt,tarea, t.idasunto idt, t.notatarea as tdesc,terminado,vencimiento, count(idusurio) from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " $ellike $act group by t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento order by t.vencimiento";
		$sql="select t.idtarea as idtt,tarea, t.notatarea as tdesc,vencimiento,idusuario, count(idusurio),fechat,datediff('$hoy',fechat) as diast  from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . "  $ellike $act and  (isnull(subtarea)=1 or subtarea=0) group by fechat, t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento ,idusuario order by t.vencimiento";
		break;
	case 'true':
		//$sql="select t.idtarea as idtt,tarea, t.idasunto idt, t.notatarea as tdesc,terminado,vencimiento, count(idusurio) from tarea t, tareausuario tu where terminado = 1 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " $ellike $act group by t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento order by t.vencimiento";
		$sql="select t.idtarea as idtt,tarea, t.notatarea as tdesc,vencimiento,idusuario, count(idusurio),fechat,datediff('$hoy',fechat) as diast  from tarea t, tareausuario tu where terminado = 1 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . "  $ellike $act and  (isnull(subtarea)=1 or subtarea=0) group by fechat, t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento ,idusuario order by t.vencimiento";
	
	}
	//echo $act . "<br>" . $sql;
	//$sql="select t.idtarea as idtt,tarea, t.idasunto idt, t.notatarea as tdesc,terminado,vencimiento, count(idusurio) from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " $ellike group by t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento order by t.vencimiento";
	//$sql="select t.idtarea as idtt,tarea,  t.notatarea as tdesc,vencimiento,idusuario, count(idusurio) from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " and idproyecto = $idp group by t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento ,idusuario order by t.vencimiento";
	$datos=mysql_query($sql);
	
	$num="";
	$listatareasd = "<div name=\"ltareas\" id=\"ltareas\" class=\"scroll\">";
	$listatareasd .= "<table border=\"1\"><tr><th>Id</th><th>Tarea</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
	/*
		$sql1="select count(*) as num from tseguimiento where idtarea = " . $row["idtt"];
		$datos1=mysql_query($sql1);
		$row1 = mysql_fetch_array($datos1);
		$num=$row1["num"];
	*/	
		$elidt=$row["idtt"];
		$eltitulot=CambiaAcentosaHTML($row["tarea"]);
		$ladescripcion=CambiaAcentosaHTML($row["tdesc"]);
		$fechalimite=$row["vencimiento"];
		$creacion=$row["fechat"];
		
		if(is_null($row["fechat"]))
		{
			$diast="";
		}
		else
		{
			$diast=$row["diast"];
		}			
		
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
		//$listatareasd .="<td>$elidt </td><td> $eltitulot($num)$actualizado<br>";
		$listatareasd .="<td>$elidt </td><td> $eltitulot $actualizado<br>";
		$listatareasd .="<h10 class='descripcionp'>$ladescripcion<br>(d&iacute;as transcurridos $diast a partir del $creacion)</h10></td><td>";
			
		if(($row["idusuario"]==$misesion->usuario) && $opt=='false')
		{
			$listatareasd .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/tarea.php','contenido','accion=1&id=" .  $row["idtt"]  . "' )}\" $txtborrar><br>";
			$listatareasd .= "<input type=\"button\" value=\"Terminar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea terminar &eacute;ste elemento?')){cargarSeccion('$ruta/tarea.php','contenido','accion=4&id=" .  $row["idtt"]  . "' )}\" $txtborrar><br>";
			$listatareasd .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/ftarea.php','frmtareas','accion=2&id=" .  $row["idtt"]   . "&st=0' )\" $txteditar><br>";
		}
		$listatareasd .="<input type=\"button\" value=\"Ver\" onClick=\"cargaTareas('$ruta/vertarea.php','contenido','id=$elidt');\" ></td>";									
		$listatareasd .="</tr>";

	
		$listatareasd= CambiaAcentosaHTML($listatareasd);
	
		
		
	}
	echo $listatareasd .= "</table></div>";
	


//echo $listatareasd;
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