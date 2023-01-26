<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$id=@$_GET["id"];
$opt=@$_GET["opcion"];

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

/*


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

*/
	//inicia la variable que contendrá la consulta


	$asunto="";
	
	$sql="select * from tarea where idtarea = $id";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$titulo=CambiaAcentosaHTML($row["tarea"]);
		$idasunto=$row["idasunto"];
		$fechalimite=$row["vencimiento"];
		$descripciont=CambiaAcentosaHTML($row["notatarea"]);
		
		$sql1="select * from usuario where idusuario =" . $row["idusuario"];
		$datos1=mysql_query($sql1);
		$row1 = mysql_fetch_array($datos1);
		$creador = "<b>Generada por: " . $row1["nombre"] . " " . $row1["apaterno"] . " " . $row1["amaterno"] . "</b>";
		
		
	}
	
	
	
	
	if($idasunto>0)
	{
		$sql="select * from asunto where idasunto = $idasunto";
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
		$asunto = CambiaAcentosaHTML("(" . $row["expediente"] . ")" . $row["descripcion"] );
	
	}
	
	
	
	$sql="select nombre,apaterno, amaterno, fechaavance, ts.hora as thora, notaavancet,ts.idtseguimiento as idts from tseguimiento ts,usuario u where ts.idusuario = u.idusuario and idtarea=$id order by fechaavance desc, idtseguimiento desc";
	$listas= "<table border =\"1\"><tr><th>Usuario</th><th>Fecha</th><th>Hora</th><th>Seguimiento</th></tr>";

	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$descargas="";
		$archivo="";
		$sqlarch="select * from archivoseguimiento where idtseguimiento=" . $row["idts"];
		$operacionarch = mysql_query($sqlarch);
		//echo $row["idts"] . " tiene " .mysql_num_rows($operacionarch) . " archivos<br>";
		if(@mysql_num_rows($operacionarch)>0)
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
	$hoy=date("Y-m-d");
	$sql = "update tareausuario set notificado = true, fechanotificado='$hoy' where idtarea =$id  and idusurio = " . $misesion->usuario;
	$operacion = mysql_query($sql);
	
	$bseguimiento="<br>";
	if($opt==0)
	{
		$bseguimiento = "<input type=\"button\" value=\"Seguimiento\" onClick=\"cargarSeccion('$ruta/tareaseguimiento.php','contenido','id=$id' )\"><br>";
	}

echo <<<formulario1
<center>
<h1>($id) $titulo<br><b style="font-size:10;font-weight:bold;text-align:center;">$creador<br>(vence el $fechalimite)</b></h1>
        <input type="button" value="Regresar a tareas" onClick="cargaTareas('$ruta/tareasasignadas.php','contenido','');" >
	$bseguimiento
<b>Asunto</b>
<p>
$asunto
</p>
<b>Descripci&oacute;n</b>
<p>
$descripciont
</p>
$listas
</center>
formulario1;


	

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>