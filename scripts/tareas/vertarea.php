<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$id=@$_GET["id"];
$opt=@$_GET["opcion"];
$idst=@$_GET["idst"];
$desubtarea=@$_GET["desubtarea"];

$diasp="";
$essub="";
$sql1="";
$psubtareas="";
$creacion1="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='tarea.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta= $row['ruta'];
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


	//inicia la variable que contendrá la consulta

/*
	$sql="select proyecto, notaproyecto, fechaip, grupoproyecto from proyecto p, grupoproyecto gp where idproyecto = $idp and p.idgrupoproyecto = gp.idgrupoproyecto";
	$datos=mysql_query($sql);
	$row=mysql_fetch_array($datos);
	
	$grupop = CambiaAcentosaHTML($row["grupoproyecto"]);
	$proyecto = CambiaAcentosaHTML($row["proyecto"]);
	$desc = CambiaAcentosaHTML($row["notaproyecto"]);
	$fechap = $row["fechaip"];
*/	

	
	$hoy=date("Y-m-d");
	$sql="select * , datediff('$hoy',fechat) as diast from tarea where idtarea = $id";
	
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
	
		$titulo=CambiaAcentosaHTML($row["tarea"]);
		$fechalimite=$row["vencimiento"];
		$descripciont=CambiaAcentosaHTML($row["notatarea"]);
		$essub=$row["subtarea"];
		$creacion1=$row["fechat"];
		
		
		if(is_null($row["fechat"]))
		{
			$diasp="";
		}
		else
		{
			$diasp=$row["diast"];
		}			
		
		
		
		$idr=$row["idsubde"];
		
		if ($idr)
		{
			$desubtarea="1";		
		}
		
		$sql1="select * from usuario where idusuario =" . $row["idusuario"];
		$datos1=mysql_query($sql1);
		$row1 = mysql_fetch_array($datos1);
		$creador = "<b>Generada por: " . $row1["nombre"] . " " . $row1["apaterno"] . " " . $row1["amaterno"] . "</b>";
	
	}
	
	
	$sql= "select nombre,apaterno, amaterno, fechaavance, ts.hora as thora, notaavancet, ts.idtseguimiento as idts from tseguimiento ts, usuario u where ts.idusuario = u.idusuario and idtarea=$id order by fechaavance desc, idtseguimiento desc";
	$listas= "<table border =\"1\"><tr><th>Usuario</th><th>Fecha</th><th>Hora</th><th>Seguimiento</th></tr>";
	
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$descargas="";
		$archivo="";
		$sqlarch="select * from archivoseguimiento where idtseguimiento=" . $row["idts"];
		$operacionarch = mysql_query($sqlarch);
		//echo $row["idts"] . " tiene " .mysql_num_rows($operacionarch) . " archivos<br>";
		if(mysql_num_rows($operacionarch)>0)
		{
			while($rowarc = mysql_fetch_array($operacionarch))
			{
				$archivo=$rowarc["archivo"];
				$descargas .="[<a href=\"scripts/general/descargar.php?f=$archivo&idt=$id&idts=" . $row["idts"] . "\"  target=\"_blank\" >$archivo\n</a>]<br>";
			
			}
			
		}
		
		
		$listas .= "<tr><td>" . CambiaAcentosaHTML($row["nombre"] . " ". $row["apaterno"] . " " . $row["amaterno"]) . "</td><td>" . $row["fechaavance"] . "</td><td>" . $row["thora"] . "</td><td>" . CambiaAcentosaHTML($row["notaavancet"]) . "<br>$descargas</td></tr>";
	}	
	$listas .= "</table>";
	
	$sql = "update tareausuario set notificado = true, fechanotificado='$hoy' where idtarea =$id  and idusurio = " . $misesion->usuario;
	$operacion = mysql_query($sql);
	
	$bseguimiento="<br>";
	if($opt==0)
	{
	
		if($desubtarea=="1")
		{
			$bregresar="<input type=\"button\" value=\"Regresar\" onClick=\"cargaTareas('$ruta/vertarea.php','contenido','id=$idr');\" >";
			//$idt=$idst;
		}
		else
		{
			$bregresar="<input type=\"button\" value=\"Regresar a tareas\" onClick=\"cargaTareas('$ruta/tarea.php','contenido','id=$id');\" >";
			//$idt=$id;
		}	
	
		$bseguimiento = "<input type=\"button\" value=\"Seguimiento\" onClick=\"cargarSeccion_new('$ruta/tareaseguimiento.php','contenido','id=$id&idst=$idst' )\"><br>";
	}
	
	$listasub="";
	
	
	
	
	//ver lista de subtareas de la tabla subtareas
	
	
	
		
	

	$hoy=date("Y-m-d");
	$sql="select t.idtarea as idtt,idsubde,tarea,  t.notatarea as tdesc,vencimiento,idusuario, count(idusurio),fechat, datediff('$hoy',fechat) as diast from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " and t.idtarea in (select idsub from subtareas where idtarea=$id)  group by fechat, t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento ,idusuario order by t.vencimiento";
	$datos=mysql_query($sql);
	$num="";

	$listasub = "<table border=\"1\" class=\"tbsubt\"><tr><th>Id</th><th>Tarea</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
	/*
		$sql1 ="select count(*) as num from tseguimiento ";
		$datos1=mysql_query($sql1);
		$row1 = mysql_fetch_array($datos1);
		$num=$row1["num"];
	*/	
		$elidt=$row["idtt"];
		$eltitulot=CambiaAcentosaHTML($row["tarea"]);
		$ladescripcion=CambiaAcentosaHTML($row["tdesc"]);
		$fechalimite=$row["vencimiento"];
		$creacion1=$row["fechat"];
		
		
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

			
			
			
		$listasub .="<tr $fondo>";
		//$listasub .="<td>$elidt </td><td> $eltitulot($num)$actualizado<br>";
		$listasub .="<td>$elidt </td><td> $eltitulot $actualizado<br>";
		$listasub .="<h10 class='descripcionp'>$ladescripcion</h10></td><td>";
			
		if($row["idusuario"]==$misesion->usuario)
		{
			$listasub .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion_new('$ruta/tarea.php','contenido','accion=1&id=" .   $row["idtt"]   . "' )}\" $txtborrar><br>";
			$listasub .= "<input type=\"button\" value=\"Terminar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea terminar &eacute;ste elemento?')){cargarSeccion_new('$ruta/tarea.php','contenido','accion=4&id=" .   $row["idtt"]   . "' )}\" $txtborrar><br>";
			$listasub .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/ftareast.php','frmtareas','accion=2&idst=" .  $row["idtt"]   . "&id=$id' )\" $txteditar><br>";
		}
		$listasub .="<input type=\"button\" value=\"Ver\" onClick=\"cargaTareas('$ruta/vertarea.php','contenido','id=" .  $row["idtt"]  . "&idst=$id&desubtarea=1');\" ></td>";									
		$listasub .="</tr>";

	
		$listasub= CambiaAcentosaHTML($listasub);
	
		
		
	}
	$listasub .= "</table></center>";
	
	
	
	
	$parte ="<td>&nbsp;&nbsp;</td><td valign=\"top\"><table><tr><td><b>Subtareas</b><form>";
	$parte .="<input type=\"button\" id=\"btnpnuevo\" value=\"Nueva\" onClick=\"cargarSeccion('$ruta/ftareast.php','frmtareas','id=$id')\">";
	$parte .= "<input type=\"button\" id=\"btnactualizar\" value=\"Actualizar\" onClick=\"cargarSeccion('$ruta/listasub.php','listasub','id=$id')\">";
	$parte .= "</form></td>	</tr>	<tr>	<td>	<div id=\"frmtareas\"></div><div id=\"listasub\">$listasub</div></td></tr>	</table></td>";
	
	
	if($essub==1)
	{
		$listasub="";
	}
	else
	{
		$listasub=$parte;
	}
	
	
	
	
echo <<<formulario1
<center>
<table border="0">
<tr>
<td valign="top">
<h2>($id) $titulo<br><b style="font-size:10;font-weight:bold;text-align:center;">($creador; vence el $fechalimite, d&iacute;as transcurridos $diasp a partir del $creacion1)</b></h2>
<b>Descripci&oacute;n</b>
<br>
$descripciont

</td>
$listasub
</tr>
</table>
        $bregresar
	$bseguimiento


$listas
</center>
formulario1;


	

}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}


/*
<td>
&nbsp;&nbsp;
</td>
<td valign="top">
	<table>
	<tr>
		<td>
			<b>Subtareas</b>
<form>	
	<input type="button" id="btnpnuevo" value="Nueva" onClick="cargarSeccion('$ruta/ftareast.php','frmtareas','id=$id')">
	<input type="button" id="btnactualizar" value="Actualizar" onClick="cargarSeccion('$ruta/listasub.php','listasub','id=$id')">
</form			
		</td>
	</tr>
	
	<tr>
		<td>
			<div id="frmtareas"></div>
			<div id="listasub">$listasub</div>
		</td>
	</tr>
	</table>
</td>
*/



?>