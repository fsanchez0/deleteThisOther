<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclass.php';

/*
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
*/
$accion = @$_POST["accion"];
$id=@$_POST["id"];
//$idp=@$_POST["idp"];
$grupo=@$_POST['grupo'];
$vigencia=@$_POST['vigencia'];
$tarea=@$_POST['tarea'];
$desc=@$_POST['desc'];
$desct=@$_POST['desct'];
$listaasignados=@$_POST['lista'];

$listagrupos="";

$enviocorreo = New correo;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
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

		$hoy = date("Y-m-d");
		$sql="insert into tarea (tarea,idusuario,vencimiento,notatarea,fechat) values ('$tarea'," . $misesion->usuario . ",'$vigencia','$desct','$hoy')";

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

			$correo="";
			$inicio==true;
			$sqls="select email from usuario, tareausuario where tareausuario.idusurio=usuario.idusuario and idtarea=$idnuevo";
	  		$operacion0 = mysql_query($sqls);
	  		if(mysql_num_rows($operacion0)>0)
	  		{

	  			while ($row0=mysql_fetch_array($operacion0))
				{
	  			if($row0["email"])
	  			{
				  if($inicio==true)
				    {
				      $correo=$row0["email"];
				      $inicio=false;
				    }
			  else
				    {
				      $correo .="," .$row0["email"];
				    }

	  			}
				}

	  		}
	  		$fecha=date("Y-m-d");
	  		$mensaje ="<h1>Se ha creado el dia $fecha la tarea >>$tarea<< para su seguimiento con la descripcion:<br><br>" . $misesion->nombre . "$desct.";
	  		//echo $correo;
			$enviocorreo->enviar($correo, "Notificaci&iacute;n de la nueva tarea >>$tarea<<", $mensaje);

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
<h1>Tareas</h1>

<form>
	<input type="button" id="btnpnuevo" value="Nueva" onClick="cargarSeccion('$ruta/ftarea.php','frmtareas','')">
</form>
<div id="frmtareas">$mensaje</div>

<form>
<table border = "0">
<tr>
	<td> <input type = "radio" checked name="filtro1" value="false" onClick="document.getElementById('selefiltro').value='false';cargarSeccion('$ruta/listatfiltrot.php', 'ltareas', 'filtro=' + this.value )">Tareas vigentes&nbsp;&nbsp;</td>
	<td>
		<input type = "radio" name="filtro1" value="true" onClick="document.getElementById('selefiltro').value='true';cargarSeccion('$ruta/listatfiltrot.php', 'ltareas', 'filtro=' + this.value )">Tareas terminadas
		<input type="hidden" name="selefiltro" id="selefiltro" value="false">
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		Tarea <input type="text" name="ftarea" id="ftarea" value="" onKeyUp="act=document.getElementById('tactualizada').checked;cargarSeccion('$ruta/listatfiltrot.php', 'ltareas', 'tareat=' + this.value + '&filtro=' + document.getElementById('selefiltro').value  + '&act=' + act );" >
		Actualizadas <input type="checkbox" name="tactualizada" id="tactualizada" onClick="act=this.checked;cargarSeccion('$ruta/listatfiltrot.php', 'ltareas', 'tareat=' + document.getElementById('ftarea').value + '&filtro=' + document.getElementById('selefiltro').value + '&act=' + act );">
	</td>
</tr>
</table>

</form>
</center>
formulario1;

	$hoy=date('Y-m-d');
	$sql="select t.idtarea as idtt,tarea,  t.notatarea as tdesc,vencimiento,idusuario, count(idusurio),fechat,datediff('$hoy',fechat) as diast  from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " and  (isnull(subtarea)=1 or subtarea=0) group by fechat,t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento ,idusuario order by t.vencimiento";
	$datos=mysql_query($sql);
	$diast="";
	$num="";
	$listatareasd = "<center><div name=\"ltareas\" id=\"ltareas\" class=\"scroll\">";
	$listatareasd .= "<table border=\"1\"><tr><th>Id</th><th>Tarea</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
	/*
		$sql1="select count(*) as num from tseguimiento where idtarea=" . $row["idtt"];
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
		$listatareasd .="<td>$elidt </td><td> $eltitulot $actualizado <br>";
		$listatareasd .="<h10 class='descripcionp'>$ladescripcion<br>(d&iacute;as transcurridos $diast a partir del $creacion)</h10></td><td>";

		if($row["idusuario"]==$misesion->usuario)
		{
			$listatareasd .= "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion_new('$ruta/tarea.php','contenido','accion=1&id=" .  $row["idtt"]  . "' )}\" $txtborrar><br>";
			$listatareasd .= "<input type=\"button\" value=\"Terminar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea terminar &eacute;ste elemento?')){cargarSeccion_new('$ruta/tarea.php','contenido','accion=4&id=" .  $row["idtt"]  . "' )}\" $txtborrar><br>";
			$listatareasd .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/ftarea.php','frmtareas','accion=2&id=" .  $row["idtt"]   . "' )\" $txteditar><br>";
		}
		$listatareasd .="<input type=\"button\" value=\"Ver\" onClick=\"cargaTareas('$ruta/vertarea.php','contenido','id=" .  $row["idtt"]  . "');\" ></td>";
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