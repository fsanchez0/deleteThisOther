<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$grupo=@$_GET['grupo'];
$pgrupo=@$_GET['pgrupo'];
$proyecto=@$_GET['proyecto'];
$desc=@$_GET['desc'];


$listagrupos="";


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='proyecto.php'";
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

	$sql="";
	$mensaje="";
	switch($accion)
	{
	case "0": // Agrego

		$sql="insert into proyecto (proyecto, notaproyecto, fechaip, idusuario, idgrupoproyecto) values ('$proyecto', '$desc','" . date('Y-m-d') . "'," . $misesion->usuario  . ", $grupo)";
		//echo "<br>Agrego";

		break;

	case "1": //Borro
	
		$sql="select count(*) as num from tarea where idproyecto=$id";
		$datos=mysql_query($sql);
		$row=mysql_fetch_array($datos);
		if($row["num"]!=0)		
		{
			$sql="";
			$mensaje="<font color='red'>No se puede borrar el proyecto porque tiene tareas en el</font>";
		}
		else
		{
		 	$sql="delete from proyecto where idproyecto=$id";
			//echo "<br>Borro";
		}
		break;

	case "3": //Actualizo

		$sql = "update proyecto set proyecto='$proyecto', notaproyecto='$desc', idgrupoproyecto=$grupo where idproyecto=$id";
		///echo "<br>Actualizo";

	case "4": //terminar proyecto
	
		$sql="update proyecto set fechatp='" . date('Y-m-d') . "' where idproyecto = $id";	

	}
	if($sql!="")
	{

		$operacion = mysql_query($sql);

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
	$sql="select * from grupoproyecto";
	$datos=mysql_query($sql);
	$marcar="";
	$listagrupos="<select name='sgrupo' id='sgrupo' onChange=\"cargarSeccion('$dirscript','contenido','pgrupo=' +this.value )\"><option value='0'>Todos</option>";
	while($row = mysql_fetch_array($datos))
	{
		
		if($pgrupo==$row["idgrupoproyecto"])
		{
			$marcar="selected";
		}
		
		$listagrupos .= "<option value='" . $row["idgrupoproyecto"] . "' $marcar>" . $row["grupoproyecto"] . "</option>";		
		$marcar="";
	}
	$listagrupos .="</select>";
	


echo <<<formulario1
<center>
<h1>Proyectos</h1>
<form>
	<input type="button" id="btnpnuevo" value="Nuevo" onClick="cargarSeccion('$ruta/fproyecto.php','frmproyectonuevo','')">
</form>
<div id="frmproyectonuevo">$mensaje</div>

</center>
formulario1;

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
		if ($row["idusuario"]==$misesion->usuario)
		{
			echo "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idproyecto"]  . "' )}\" $txtborrar><br>";
			echo "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$ruta/fproyecto.php','frmproyectonuevo','accion=2&id=" .  $row["idgrupoproyecto"]  .  "' )\" $txteditar><br>";
			echo "<input type=\"button\" name=\"id\" value=\"Terminar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=4&id=" .  $row["idproyecto"]  . "' )\" ><br>";
		}
		echo "<input type=\"button\" name=\"ver\" value=\"Ver\" onClick=\"cargarSeccion('$ruta/tarea.php','contenido','accion=&idp=" .  $row["idproyecto"]  . "' )\" ><br>";
		echo "</td></tr>";
	}
	echo "</table></div></center>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>