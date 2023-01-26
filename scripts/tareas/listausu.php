<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$lista=@$_GET['lista'];

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


	//echo $id;
	//inicia la variable que contendrá la consulta
	$sql="";
	$plistda="";
	//Segun la acción que se tomó, se procederá a editar el sql
	switch($accion)
	{
	case "0": // Agrego

	

		if($id!="0")
		{
			//$lista .= $id . "*";
		
		
			$listaa = split('[*]',$lista);

			foreach ($listaa as $idu)
			{

				if($id==$idu)
				{
					$id="";
				}


			}
			
			if($id!="")
			{
				if($id=="T")
				{
					$lista="T";
					$sql1="select * from usuario where activo=1";
					$datos1=mysql_query($sql1);
					$lista = "";
					while($row1 = mysql_fetch_array($datos1))
					{
						$lista .= $row1["idusuario"] . "*";
								
					}					
				}
				else
				{
				
			        	$lista .= $id . "*";
			        }
			}
		
		}




		break;

	case "1": //Borro

	     	$listaa = split('[*]',$lista);
		$lista="";
		$aux="";
		foreach ($listaa as $idu)
		{
			$lista .=  $aux;
			$aux="";
			if($id!=$idu)
			{
				$aux = $idu . "*";
			}

		}

		break;

	}

	//echo ">>" . $lista . "<<<";
	$tabla="<input type='hidden' name='lista' id='lista' value='$lista'><table border ='1'><tr><th>Usuario</th><th>Acci&oacute;n</th></tr>";
	if($lista!="")
	{
		$listaa = split('[*]',$lista);
		$aux="";
		/*
		if($lista=="T")
		{

			$sql1="select * from usuario where activo=1";
			$datos1=mysql_query($sql1);
			$lista = "";
			while($row1 = mysql_fetch_array($datos1))
			{
				
				$tabla .= "<tr><td>" .  $row1["nombre"] . " " . $row1["apaterno"] . " " . $row1["amaterno"]  . "</td>";
				$tabla .= "<td><input type=\"button\" value=\"-\" name=\"agregar\" onClick=\"cargarSeccion('$ruta/listausu.php','listadeusu','accion=1&id=" . $row1["idusuario"] . "&lista=' + lista.value );\" ></td></tr>";
			}			
		
		
		}
		else
		{*/
			foreach ($listaa as $idu)
			{
				$tabla .=  $aux;
				$aux="";
				if($idu>0)
		  		{
			  	  	$sql1="select * from usuario where idusuario=$idu";
					$datos1=mysql_query($sql1);
					$row1 = mysql_fetch_array($datos1);
		
					$tabla .= "<tr><td>" .  $row1["nombre"] . " " . $row1["apaterno"] . " " . $row1["amaterno"]  . "</td>";
					$tabla .= "<td><input type=\"button\" value=\"-\" name=\"agregar\" onClick=\"cargarSeccion('$ruta/listausu.php','listadeusu','accion=1&id=" . $row1["idusuario"] . "&lista=' + lista.value );\" ></td></tr>";
				
				}
			}
		//}
	}
	echo $tabla .="</table>";



}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>