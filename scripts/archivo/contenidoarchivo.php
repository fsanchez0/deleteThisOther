<?php
include '../general/cargararchivo.php';

include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_POST["accion"];
$id=@$_GET["id"];
$nota=@$_POST["nota"];
$idp=@$_POST["idp"];

if(!$id)
{
	$id=$idp;
}


$elemento="";
$exp="";
$fcreacion="";
$ubicacion="";

$gestor= new carga;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$idusuario = $misesion->usuario;
	$sql="select * from submodulo where archivo ='expseguimientop.php'";
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



//echo "Accion=" . $accion . " <br>Listaa= " . $listaa . " <br>Archivo= " . $archvo . " <br>listaarhivos= " . $larchivos . " <br> " ;

	if($accion=="1")
	{//subir arcchivo
	
		$sql = "select * from expediente where idexpediente = $id";
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
		$gestor->cargar(basename( $_FILES['myfile']['name']), $_FILES['myfile']['tmp_name'],false,'',$row["idarchivo"], $id);
		$hoy=date("Y-m-d");
		$narchivo = basename( $_FILES['myfile']['name']);
		$sql = "insert into documento (idexpediente, fechadocumento, archivo,notadocumento)values($id,'$hoy','$narchivo','$nota')"; 		
		$operacion = mysql_query($sql);
		
	}

	$descargas="";
	$archivo="";
	 $sql = "select * from documento d,expediente e where d.idexpediente = e.idexpediente and  d.idexpediente = $id order by fechadocumento desc,iddocumento desc";
	$operacion = mysql_query($sql);
	$lista="<table border =\"1\"> <tr><th>Id</th><th>Feha</th><th>Documento</th></tr> ";
	while($row = mysql_fetch_array($operacion))
	{
		$archivo=$row["archivo"] ;
		$idt=$row["idarchivo"];
		if($row["notadocumento"])
		{
			$notadoc="" . $row["notadocumento"] . "<br>";
		}
		else
		{
			$notadoc="";
		}
		
		if($archivo)
		{
			$descargas ="[<a href=\"../../scripts/general/descargara.php?f=$archivo&idt=$idt&idts=$id\"  target=\"_blank\" >$archivo\n</a>]<br>";
		}
		else
		{
			$descargas="";
		}
		//$descargas ="[<a href=\"../../scripts/general/descargara.php?f=$archivo&idt=$idt&idts=$id\"  target=\"_blank\" >$archivo\n</a>]<br>";
		$lista .="<tr><td>" . $row["iddocumento"] . "</td><td>" . $row["fechadocumento"] . "</td><td>$notadoc $descargas</td>";
		//$lista .="<input type=\"button\">";
		//$lista .="</td>";
		
	}
	
	echo $lista .="</table>";

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}


?>