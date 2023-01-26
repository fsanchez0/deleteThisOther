<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$accion=@$_GET["accion"];
$tareat=@$_GET["tareat"];
$filtro=@$_GET["filtro"];

$misesion = new sessiones;
$renglones="";
if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='tareas.php'";
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





	$ellike="";
	if($tareat!="")
	{
		$ellike = " and (tarea like '%$tareat%' 	or t.notatarea like '%$tareat%') ";
	
	}


	if($filtro=="true")
	{	
		$sql="select idtarea,tarea, t.idasunto idt, t.notatarea as tdesc,terminado from tarea t where idusuario = " . $misesion->usuario  . " and terminado = 1 $ellike order by terminado,vencimiento ";
	}
	else
	{
		$sql="select idtarea,tarea, t.idasunto idt, t.notatarea as tdesc,terminado from tarea t where idusuario = " . $misesion->usuario  . " and terminado = 0 $ellike order by terminado,vencimiento ";
	}
		
	
	$asunto="";
	$datos=mysql_query($sql);
	echo "<div name=\"ltareas\" id=\"ltareas\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Tarea</th><th>Asunto</th><th>Descripci&oacute;n</th><th>Terminado</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		if($row["idt"]!=0)
		{
			$sql1="select * from asunto a,directorio d where a.iddirectorio=d.iddirectorio and idasunto=" . $row["idt"] ;
			$datos1=mysql_query($sql1);
			$row1 = mysql_fetch_array($datos1);
			$asunto = "<b>" . $row1["nombre"] . " " . $row1["apaterno"] . " " . $row1["amaterno"] . "</b><br>" . $row1["descripcion"];			
			
		}
		else
		{
			$asunto="";
		}
		$html = "<tr><td>" . $row["idtarea"] . "</td><td>" . $row["tarea"] . "</td><td>$asunto</td><td>" . $row["tdesc"] . "</td><td>" . $row["terminado"] . "</td><td>";
		$html .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idtarea"]  . "' )}\" $txtborrar>";

		$html .= "<input type=\"button\" value=\"Terminar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea terminar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=4&id=" .  $row["idtarea"]  . "' )}\" $txtborrar>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idtarea"]   . "' )\" $txteditar>";
		$html .= "</td></tr>";
		echo CambiaAcentosaHTML($html);
	}
	echo "</table></div>";





	




}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}
?>