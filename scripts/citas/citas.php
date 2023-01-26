<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
//$idp=@$_GET["idp"];
$grupo=@$_GET['grupo'];
$vigencia=@$_GET['vigencia'];
$tarea=@$_GET['tarea'];
$desc=@$_GET['desc'];
$desct=@$_GET['desct'];
$listaasignados=@$_GET['lista'];


$listagrupos="";


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
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


/*
	$sql="select proyecto, notaproyecto, fechaip, grupoproyecto from proyecto p, grupoproyecto gp where idproyecto = $idp and p.idgrupoproyecto = gp.idgrupoproyecto";
	$datos=mysql_query($sql);
	$row=mysql_fetch_array($datos);
	
	$grupop = $row["grupoproyecto"];
	$proyecto = $row["proyecto"];
	$desc = $row["notaproyecto"];
	$fechap = $row["fechaip"];
*/
	$mensaje="";
	$sql="";
	
	switch($accion)
	{
	case "0": // Agrego

		
		$sql="insert into tarea (tarea,idusuario,vencimiento,notatarea) values ('$tarea'," . $misesion->usuario . ",'$vigencia','$desct')";

		//echo "<br>Agrego";		
		break;

	case "1": //Borro

		$sql="select count(*) as num from tseguimiento where idtarea=$id";
		$datos=mysql_query($sql);
		$row=mysql_fetch_array($datos);
		if($row["num"]!=0)
		{
			$sql="";
			$mensaje="<font color='red'>No se borro la tarea porque tiene seguimiento</font>";
		}
		else
		{
			$sql="delete from tareausuario where idtarea=$id";
			$datos=mysql_query($sql);
			$sql="delete from tarea where idtarea=$id";		
			//echo "<br>Borro";
			$id="";
		}
		break;

	case "3": //Actualizo

		$sql = "update tarea set tarea='$tarea',vencimiento='$vigencia', notatarea='$desct' where idtarea=$id";
		///echo "<br>Actualizo";
		break;
		
	case "4": //terminar tarea
	
		$sql="update tarea set terminado=1 where idtarea = $id";	

	}
//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{
		$sql;
		$operacion = mysql_query($sql);
		//echo $listaasignados . $accion;
		switch($accion)
		  {
		  case "0": // Agrego

		    $idnuevo=mysql_insert_id();
		    $listaa = split('[*]',$listaasignados);
		    
		    $sql = "insert into tareausuario (idtarea,idusurio,notificado) values ($idnuevo," . $misesion->usuario . ",0)";
		    $operacion = mysql_query($sql);
		    
		    foreach ($listaa as $idu)
			{
			  $sql = "insert into tareausuario (idtarea,idusurio,notificado) values ($idnuevo,$idu,0)";
			  $operacion = mysql_query($sql);
			}
		    
		    break;

		  case "3": //actualizo

		    $sql = "delete from tareausuario where idtarea=$id";
		    $operacion = mysql_query($sql);
		     $listaa = split('[*]',$listaasignados);

		    foreach ($listaa as $idu)
			{
			 $sql = "insert into tareausuario (idtarea,idusurio,notificado) values ($id,$idu,0)";
			  $operacion = mysql_query($sql);
			}
		    
		    break;
	 	 case "1": //Borro

		    $sql = "delete from tareausuario where idtarea=$id";
		    $operacion = mysql_query($sql);
		    
		    break;
	       
		
		  }
	}

	/*
	$boton1="Limpiar";
	$boton2="Agregar";

	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
	}
	else
	{
		$accion="0";
	}

*/

echo <<<formulario1
<center>
<h1>Citas</h1>

<form>	
	<input type="button" id="btnpnuevo" value="Nueva" onClick="cargarSeccion('$ruta/frmcitas.php','frmcita','')">
</form>
<div id="frmcita">$mensaje</div>

<form>
<table border = "0">
<tr>
	<td> <input type = "radio" checked name="filtro1" value="false" onClick="document.getElementById('selefiltro').value='false';cargarSeccion('$ruta/listafiltroc.php', 'ltareas', 'filtro=' + this.value )">Citas Programadas&nbsp;&nbsp;</td>
	<td>
		<input type = "radio" name="filtro1" value="true" onClick="document.getElementById('selefiltro').value='true';cargarSeccion('$ruta/listafiltroc.php', 'ltareas', 'filtro=' + this.value )">Citas Atendidas
		<input type="hidden" name="selefiltro" id="selefiltro" value="false">
	</td>	
</tr>
<tr>
	<td colspan="2" align="center">
		Cita <input type="text" name="fcita" id="fcita" value="" onKeyUp="cargarSeccion('$ruta/listafiltroc.php', 'lcitas', 'citat=' + this.value + '&filtro=' + document.getElementById('selefiltro').value   );" >
		
	</td>
</tr>
</table>

</form>
</center>
formulario1;


	$sql="select nombrec,  telc,fechac,horac,atiende, calle, numeroext, numeroint, idcita from cita c, inmueble i where c.idinmueble = i.idinmueble and atendida = false order by fechac, horac";
	$datos=mysql_query($sql);	
	$num="";
	$listatareasd = "<center><div name=\"lcitas\" id=\"lcitas\" class=\"scroll\">";
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
	echo $listatareasd .= "</table></div></center>";















/*
	if( !$pgrupo )
	{
		$sql="select * from proyecto p,grupoproyecto gp where p.idgrupoproyecto = gp.idgrupoproyecto and isnull(fechatp)=true";
	}
	else
	{
		
		$sql="select * from proyecto p,grupoproyecto gp where p.idgrupoproyecto = gp.idgrupoproyecto and gp.idgrupoproyecto = $pgrupo and isnull(fechatp)=true";	
	}
	
	$datos=mysql_query($sql);
	echo "<center>$listagrupos<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Grupo</th><th>Proyecto</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
	
		$sql1="select count(*) as num from proyecto p, tarea t where p.idproyecto = t.idproyecto";
		$datos1=mysql_query($sql1);
		$row1 = mysql_fetch_array($datos1);
		$num = $row1["num"];
		echo "<tr><td>" . $row["idproyecto"] . "</td><td>" . $row["grupoproyecto"] . "</td><td>" . CambiaAcentosaHTML($row["proyecto"] . "($num)"  ) . "<br><h10 class='descripcionp'>" . $row["notaproyecto"] . "</h10></td><td>";
		echo "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idproyecto"]  . "' )}\" $txtborrar><br>";
		echo "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/fproyecto.php','frmproyectonuevo','accion=2&id=" .  $row["idgrupoproyecto"]  .  "' )\" $txteditar><br>";
		echo "<input type=\"button\" name=\"id\" value=\"Terminar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=4&id=" .  $row["idproyecto"]  . "' )\" ><br>";
		echo "</td></tr>";
	}
	echo "</table></div></center>";
*/	


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>