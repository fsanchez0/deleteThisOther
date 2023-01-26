<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$titulo=@$_GET['titulo'];
$fechalimite=@$_GET['fechalimite'];
$idasunto=@$_GET['idasunto'];
$descripcion=@$_GET['descripcion'];
$listaasignados=@$_GET['lista'];

$lista="";
$listausutarea="";
$idnuevo="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='tareas.php'";
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

	if(!$idasunto)
	{
		$idasunto=0;
	}

	//inicia la variable que contendrá la consulta
	$sql="";

	//Segun la acción que se tomó, se procederá a editar el sql
	switch($accion)
	{
	case "0": // Agrego


		$sql="insert into tarea (tarea,idasunto,idusuario,vencimiento,notatarea) values ('$titulo',$idasunto," . $misesion->usuario . ",'$fechalimite','$descripcion')";



		//echo "<br>Agrego";
		$titulo="";
		$fechalimite="";
		$descripcion="";
		$idasunto=0;

		break;

	case "1": //Borro

		$sql="delete from tarea where idtarea=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update tarea set tarea='$titulo',idasunto=$idasunto,vencimiento='$fechalimite', notatarea='$descripcion' where idtarea=$id";
		///echo "<br>Actualizo";
		$titulo="";
		$fechalimite="";
		$descripcion="";
		$idasunto=0;
		break;

	case "4": //Terminar
	         $sql = "update tarea set terminado=true where idtarea=$id";
		 $id="";
	}
	//echo $sql;
	//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{

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
			$enviocorreo->enviar($correo, "Notificaci&iacute;n de la nueva tarea >>$titulo<<", $mensaje);



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
	//Preparo las variables para los botónes
	$boton1="Limpiar";
	$boton2="Agregar";

	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la acción a realizar para la siguiente presión del botón agregar
	//en su defecto, sera accón agregar
	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from tarea where idtarea = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$titulo=CambiaAcentosaHTML($row["tarea"]);
			$idasunto=$row["idasunto"];
			$fechalimite=$row["vencimiento"];
			$descripcion=CambiaAcentosaHTML($row["notatarea"]);
		}

		//hacer la lista de los participantes
		$listausutarea="";


		$sql="select t.idusurio as idu, nombre, apaterno, amaterno from tareausuario t, usuario u where idtarea=$id and t.idusurio = u.idusuario";
		$listausutarea = "<table border ='1'><tr><th>Usuario</th><th>Acci&oacute;n</th></tr>";
		$lista="";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		  {
		    $lista .= $row["idu"] . "*";

		   $listausutarea .= "<tr><td>" . CambiaAcentosaHTML( $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"] ) . "</td>";
		   $listausutarea .= "<td><input type=\"button\" value=\"-\" name=\"agregar\" onClick=\"cargarSeccion('$ruta/listausu.php','listadeusu','accion=1&id=" . $row["idu"] . "&lista=' + lista.value );\" ></td></tr>";

		  }
		$listausutarea .="</table>";






	}
	else
	{
		$accion="0";
	}
	$asunto="";
	if($idasunto>0)
	{
		$sql="select * from asunto where idasunto = $idasunto";
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
		$asunto = CambiaAcentosaHTML("(" . $row["expediente"] . ")" . $row["descripcion"] );

	}

	$sql="select * from asunto";
	$asunto1 = "<select name=\"idasunto1\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$marcar ="";
		if($idasunto==$row["idasunto"])
		{
			$marcar=" selected ";
		}
		$asunto1 .= "<option value=\"" . $row["idasunto"] . "\" $marcar  >" . CambiaAcentosaHTML("(" . $row["expediente"] . ")" . $row["descripcion"] ) .  "</option>";

	}
	$asunto1 .="</select>";

	$selectusuarios="";
	$sql="select * from usuario where activo = 1";
	$selectusuarios = "<select name=\"idusuario\" ><option value=\"0\">Seleccione uno de la lista</option><option value=\"T\">Todos</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		$selectusuarios .= "<option value=\"" . $row["idusuario"] . "\"  >" . CambiaAcentosaHTML( $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"]  ) .  "</option>";

	}
	$selectusuarios .="</select>";



//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>Tareas</h1>
<form>
<table border="0">
<tr>
	<td>Titulo</td>
	<td><input type="text" name="titulo" id="titulo" value="$titulo" ></td>
</tr>
<tr>
	<td valign="top">Asunto</td>
	<td>
		<textarea id="asunto" disabled rows="3" cols="40">$asunto</textarea><a  onClick="getElementById('selectidasunto').style.height=100;cargarSeccion('$ruta/selectasunto.php','selectidasunto','')"><img src="imagenes/lupa0.png" height="13"></a>
		<input type="hidden" id="idasunto" name="idasunto" value="$idasunto">

		<div id="selectidasunto" style="width:350;height:0;overflow:auto;font-size:10pt"></div>

	</td>
</tr>
<tr>
	<td>Fecha l&iacute;mite <br>(aaaa-mm-dd)</td>
	<td><input type="text" name="fechalimite" id="fechalimite" value="$fechalimite" ></td>
</tr>
<tr>
	<td>Descripci&oacute;n</td>
	<td>
		<textarea name="descripcion" id="descripcion" rows="3" cols="40">$descripcion</textarea>
	</td>
</tr>

<tr>
	<td>Asignaciones</td>
	<td>
		$selectusuarios
		<input type="button" value="+" onClick="cargarSeccion('$ruta/listausu.php','listadeusu','accion=0&id=' + idusuario.value + '&lista=' + lista.value );">

		<div id="listadeusu" style="hieght:100px;overflow:auto">
		     <input type="hidden" name="lista" id="lista" value="$lista">
		     $listausutarea
		</div>
	</td>
</tr>


<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';titulo.value='';idasunto.value='';asunto.value='';fechalimite.value='';descripcion.value='';lista.value='';cargarSeccion('$dirscript','contenido','')">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="agregar" onClick="if(titulo.value!='' && fechalimite.value!='' && descripcion.value!='' && lista.value!=''  ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&titulo=' + titulo.value + '&idasunto=' + idasunto.value + '&fechalimite=' + fechalimite.value + '&descripcion=' + descripcion.value + '&lista=' + lista.value )};if(this.value=='Agregar'&&privagregar.value==1){  cargarSeccion('$dirscript','contenido','accion=' + accion.value + '&id=' + ids.value + '&titulo=' + titulo.value + '&idasunto=' + idasunto.value + '&fechalimite=' + fechalimite.value + '&descripcion=' + descripcion.value + '&lista=' + lista.value)}};" >
		<input type="hidden" name="ids" value="$id">
		<input type="hidden" name="accion" value="$accion">
		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">

	</td>
</tr>
</table>
</form>


<table border = "0">
<tr>
	<td> <input type = "radio" checked name="filtro1" value="false" onClick="document.getElementById('selefiltro').value='false';cargarSeccion('$ruta/listatfiltro.php', 'ltareas', 'filtro=' + this.value)">Tareas vigentes&nbsp;&nbsp;</td>
	<td>
		<input type = "radio" name="filtro1" value="true" onClick="document.getElementById('selefiltro').value='true';cargarSeccion('$ruta/listatfiltro.php', 'ltareas', 'filtro=' + this.value)">Tareas terminadas
		<input type="hidden" name="selefiltro" id="selefiltro" value="false">
	</td>
</tr>
<tr>
	<td colspan="2" align="center">
		Tarea <input type="text" name="ftarea" id="ftarea" value="" onKeyUp="cargarSeccion('$ruta/listatfiltro.php', 'ltareas', 'tareat=' + this.value + '&filtro=' + document.getElementById('selefiltro').value );" >
	</td>
</tr>
</table>


</center>

formulario1;

	//echo CambiaAcentosaHTML($html);

	$sql="select idtarea,tarea, t.idasunto idt, t.notatarea as tdesc,terminado from tarea t where idusuario = " . $misesion->usuario  . " and terminado = 0 order by terminado,vencimiento ";
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