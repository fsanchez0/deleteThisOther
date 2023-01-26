<?php
//include '../general/cargadescarga.php';
include '../general/sessionclase.php';
include 'cargarclass.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$accion=@$_POST["accion"];
$idtipoformato=@$_POST["idtipoformato"];
$archvo=@$_POST["archivo"];
$id=@$_GET["id"];

$gestor= new carga;

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='formatos.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta= $row['ruta'];
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
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

	//para el privilegio de editar, si es negado deshabilida el botÛn
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el botÛn
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}





//echo "Accion=" . $accion . " <br>Listaa= " . $listaa . " <br>Archivo= " . $archvo . " <br>listaarhivos= " . $larchivos . " <br> " ;

if($accion=="1")
{
	//subir arcchivo
	//Verificar si fue exitoso para agregarlo a la base de datos
	if(  $gestor->cargar(basename( $_FILES['myfile']['name']), $_FILES['myfile']['tmp_name']))
	{
		//agregar a la base
		//echo $gestor->direccion_archivo;
		
		$sql="insert into formato (idtipoformato,archivo,hubicacion,fechaf,idusuario) values ('$idtipoformato','" . basename( $_FILES['myfile']['name']) . "','" . $gestor->direccion_archivo . "','" . date('Y-m-d') . " '," . $misesion->usuario . " )";
		//echo "<br>Agrego";
		$operacion = mysql_query($sql);	
		
		
		
	}
	else
	{
		//mostrar mensaje de error
	}
}
else
{
	if($id){
	//borrar y quitar archivo
	//verificar si fue exitoso para quitarlo de labase de datos
		$sql = "select * from formato where idformato = $id";
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
	
	
		if($gestor->quitar_arhivo($row['hubicacion']))
		{
			//borrar de la base
			$sql = "delete from formato where idformato = $id";
			$operacion = mysql_query($sql);
		
		}
		else
		{
			//mostrar mensaje de error
		}
	
	}
}



	$sql="select * from formato, tipoformato where formato.idtipoformato = tipoformato.idtipoformato order by tipoformato,idformato ";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	//form para poder lanzar el post a este archivo	
	echo "<table border=\"1\"><tr><th>Id</th><th>Tipo Formato</th><th>Formato</th><th>Fecha</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$html = "<tr><td>" . $row["idformato"] . "</td><td>" . $row["tipoformato"] . "</td><td>" . $row["archivo"] . "</td><td>" . $row["fechaf"] . "</td><td>";
		$html .= "<a href=\"cargarformato.php?id=" .  $row["idformato"] . "\" target=\"c_archivos\">X</a>";
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