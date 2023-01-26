<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];



//$idcontrato=@$_GET["idcontrato"];
//$idinmueble=@$_GET["idinmueble"];


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='reportedetallado.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta=$row['ruta'];
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


	//inicia la variable que contendr· la consulta
	$sql="";

	//Segun la acciÛn que se tomÛ, se proceder· a editar el sql
	switch($accion)
	{
	case "0": // Agrego

		$sql="select * from edoduenio where idedoduenio = $id";
		
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
	
		$idduenio = $row["idduenio"];
		$idcontrato =$row["idcontrato"];
		$idinmueble =$row["idinmueble"];
		$idtipocobro =$row["idtipocobro"];
		$reportado ="true";
		$liquidado =$row["liquidado"];
		$notaedo ="Condonación de " . $row["notaedo"];
		$iva =$row["iva"] * (-1);
		$utilidad =$row["utilidad"] * (1);
		$importe =$row["importe"] * (-1);
		$fechaedo =$row["fechaedo"];
		$horaedo =$row["horaedo"];
		$referencia =$row["referencia"];
		$condonado =$row["condonado"];				

		$sql1="select * from edoduenio where condonado = $id";
		$operacion = mysql_query($sql1);
		if (mysql_num_rows($operacion) == 0)
		{

		$sql="insert into edoduenio (idduenio,idcontrato,idinmueble,idtipocobro,reportado,liquidado,notaedo, iva, utilidad, importe,fechaedo,horaedo, referencia, condonado, facturar) values ";
		$sql .= "($idduenio,$idcontrato,$idinmueble,$idtipocobro,$reportado,$liquidado,'$notaedo', $iva, $utilidad, $importe,'$fechaedo','$horaedo', '$referencia', $id,false)";
		//echo "<br>Agrego";
		}
		else
		{
			$sql="";
		}
		$paso = 0;
		
		break;

	case "1": //Borro

		$sql="delete from edoduenio where idedoduenio = $id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		//$sql = "update periodo set nombre='$nombre',idmargen=$idmargen,numero=$numero where idperiodo=$id";
		///echo "<br>Actualizo";
		$nombre="";
		$idmargen="";
		$numero="";

	}

	$mensaje="No se pudo realizar la operaci&oacute;n";
	//ejecuto la consulta si tiene algo en la variable
	//echo $sql;
	if($sql!="")
	{

		$operacion = mysql_query($sql);
		$mensaje="Operacion exitosa";

	}
	//Preparo las variables para los botÛnes
	$boton1="Limpiar";
	$boton2="Cargar";

	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la acciÛn a realizar para la siguiente presiÛn del botÛn agregar
	//en su defecto, sera accÛn agregar
	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from periodo where idperiodo = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$nombre=CambiaAcentosaHTML($row["nombre"]);
			$idmargen=$row["idmargen"];
			$numero=$row["numero"];
		}



	}
	else
	{
		$accion="0";
	}



/*

	$sql="select * from edoduenio where idedoduenio = $id";
	
	
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	
	$idduenio = $row["idduenio"];
	$idinmueble =$row["idinmueble"];
	$importe =$row["importe"];
	$iva =$row["iva"];
	$notad =$row["notad"];
	
	$total = $importe + $iva;
	
	$sql="select * from duenio where ididuenio = $idduenio";	
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	$duenio = $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"];

	
	$sql="select * from inmueble where idinmueble = $idinmueble";	
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	$inmueble = $row["calle"] . "No." . $row["numeroext"] . " Int." . $row["numeroint"] . "<br> Col." . $row["colonia"] . " Deleg/Mun. ". $row["delmun"] . " C.P." . $row["cp"];
	
*/	

//Genero el formulario de los submodulos

echo <<<formulario1
<center>
<h1>$mensaje</h1>
<input type="button" value="Cerrar" onClick="window.close();">
</center>
formulario1;






}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>