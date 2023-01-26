<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$citat=@$_GET["citat"]; 	//texto a veririficar
$opt=@$_GET["filtro"];		// si es vigente o no
$act=@$_GET["act"];		// si solo actualizados 
//$idp=@$_GET["idp"];		//id del proyecto
$creador="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='citas.php'";
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
	if($citat!="")
	{
		$ellike = " and (nombrec like '%$citat%' or nota like '%$citat%') ";
	
	}
	
	$listatareasd="No nay tareas";
	$numrt0=0;
	
		
	switch($opt)
	{
	case 'false':
		
		$sql="select nombrec,  telc,fechac,horac,atiende, calle, numeroext, numeroint, idcita from cita c, inmueble i where c.idinmueble = i.idinmueble and atendida = false $ellike  order by fechac, horac";
		break;
	case 'true':		
		
		$sql="select nombrec,  telc,fechac,horac,atiende, calle, numeroext, numeroint, idcita from cita c, inmueble i where c.idinmueble = i.idinmueble and atendida = true $ellike  order by fechac, horac";
	
	}
	//echo "<br>" . $sql;
	//$sql="select t.idtarea as idtt,tarea, t.idasunto idt, t.notatarea as tdesc,terminado,vencimiento, count(idusurio) from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " $ellike group by t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento order by t.vencimiento";
	//$sql="select t.idtarea as idtt,tarea,  t.notatarea as tdesc,vencimiento,idusuario, count(idusurio) from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " and idproyecto = $idp group by t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento ,idusuario order by t.vencimiento";
	$datos=mysql_query($sql);
	$hoy=date("Y-m-d");
	$num="";
	$listatareasd .= "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>Telefono(s)</th><th>Fecha</th><th>Hora</th><th>Atiende</th><th>Inmueble</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		
		$elidt=$row["idcita"];
		$nombre=CambiaAcentosaHTML($row["nombrec"]);
		$tel=CambiaAcentosaHTML($row["telc"]);
		$fecha=$row["fechac"];
		$hora=$row["horac"];
		$atiende=$row["atiende"];
		$inmueble=$row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"];
		$fondo="";		
			/*
			if($fechalimite<$hoy)
			{
				$fondo=" bgcolor='#b1c228' ";
					
			}
			*/
			
				
		$listatareasd .="<tr $fondo>";
			
		$listatareasd .="<td>$elidt </td><td> $nombre</td><td>$tel </td><td>$fecha </td><td>$hora </td><td>$atiende </td><td>$inmueble </td><td>";
			
				
			
		$listatareasd .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$ruta/frmcitas.php','frmcita','accion=1&id=$elidt' )}\" $txtborrar><br>";
		$listatareasd .= "<input type=\"button\" value=\"Terminar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea terminar &eacute;ste elemento?')){cargarSeccion('$ruta/frmcitas.php','frmcita','accion=4&id=$elidt' )}\" $txtborrar><br>";
		$listatareasd .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/frmcitas.php','frmcita','accion=2&id=$elidt' )\" $txteditar><br>";
		
		$listatareasd .="<input type=\"button\" value=\"Ver\" onClick=\"cargaTareas('$ruta/vercita.php','contenido','id=$elidt');\" ></td>";									
		$listatareasd .="</tr>";
	
		
		$listatareasd= CambiaAcentosaHTML($listatareasd);
		
			
			
	}
	echo $listatareasd .= "</table>";
	


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