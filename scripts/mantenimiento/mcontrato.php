<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include "../general/calendarioclass.php";

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];



//$accion = @$_GET["accion"];
//$id=@$_GET["id"];
$idtiposervicio=@$_GET['idtiposervicio'];
$idcontrato=@$_GET['idcontrato'];
//$nombrer=@$_GET['nombrer'];
//$fecham=@$_GET['fecham'];
//$horam=@$_GET['horam'];
$reporte=@$_GET['reporte'];
$subsecuentes=@$_GET['subsecuentes'];




/*
if(!$activo)
{
	$activo=0;
}
*/
$fechas = New Calendario;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='mantenimiento.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
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
	$hoy = date('Y-m-d');
	//Segun la acciÛn que se tomÛ, se proceder· a editar el sql
	switch($accion)
	{
	case "0": // Agrego

		$sqlm="insert into mantenimiento (idcontrato,idtiposervicio,mantenimiento,fechamant,terminadom) values ($idcontrato,$idtiposervicio,'$reporte','$hoy',false)";
		$operacion = mysql_query($sqlm);
		
		$idmantemiento = mysql_insert_id();
		
		if($subsecuentes=='false')
		{
			$sql = "insert into mantenimientoseg (idmantenimiento, fechams, horams, fechacita,horacita,cambiofecha,cerrado, observacionesm, novisita) values ($idmantemiento,'$hoy','12:00:00','$hoy','12:00:00',0,0,'$reporte','1 de 1')";
			$operacion = mysql_query($sql);
		}
		else
		{

//**********************

			$sqlh="select c.idcontrato as idc, numero, idmargen,  fechatermino, fechainicio from mantenimiento m, contrato c, tiposervicio ts, periodo where  m.idcontrato = c.idcontrato and concluido=false and m.idtiposervicio = ts.idtiposervicio and ts.idperiodo=periodo.idperiodo and numero >=1 and m.idmantenimiento =$idmantemiento";
			$operacionh = mysql_query($sqlh);
			while($rowh = mysql_fetch_array($operacionh))
			{

				$idco=$rowh["idc"];
				$fechat = $rowh["fechatermino"];

				$fechaa = $rowh["fechainicio"];

				$i=0;
				$sqlh2="";
				while ($fechaa < $fechat )
				{
					$i++;
					if($sqlh2 != "")
					{
						//$lista .= $e;
						//$lista .= $sql2 . "<br>";
						//ejecuta el sql
						//echo $sqlh2;
						$operacionh2 = mysql_query($sqlh2);
					}
				
					//$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));
					//$fechaa = $fechas->calculafecha($fechagsistema, $rowh["numero"], $rowh["idmargen"]);
					$ProxVencimiento=$fechas->fechagracia($fechaa);

					$sqlh2 = "insert into mantenimientoseg (idmantenimiento, fechams, horams, fechacita,horacita,cambiofecha,cerrado,observacionesm) values "; 
					$sqlh2 .= "($idmantemiento,'$ProxVencimiento','12:00','$ProxVencimiento','12:00',0,0,'$reporte')";

					$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));
					$fechaa = $fechas->calculafecha($fechagsistema, $rowh["numero"], $rowh["idmargen"]);
				
				}
					if($sqlh2 != "")
					{
						//$lista .= $e;
						//$lista .= $sql2 . "<br>";
						//ejecuta el sql
						//echo $sqlh2;
					    $operacionh2 = mysql_query($sqlh2);
					}				


			}
			$sqlh="select * from mantenimientoseg where idmantenimiento = $idmantemiento order by fechams,idmantenimientoseg";
			$operacionh = mysql_query($sqlh);
			$totalr = mysql_num_rows($operacionh);
			$i=0;
			while($rowh = mysql_fetch_array($operacionh))
			{
					$i++;
					$sqlh2="update mantenimientoseg set novisita ='$i de $totalr' where idmantenimientoseg=" . $rowh["idmantenimientoseg"];
					$operacionh2 = mysql_query($sqlh2);
			}				
			
//***********************		
		
		}
		
		$idtiposervicio="";
		//$idcontrato="";
		$nombrer="";
		$fecham="";
		$horam="";
		$reporte="";
		$subsecuentes="";
		$sql="";
		//$enviocorreo->enviar("miguel@padilla-bujalil.com.mx, contabilidad@padilla-bujalil.com.mx ", "Cobro realizado", "Se asigno el mantenimiento al contrato $idco");

		
		
		break;

	case "1": //Borro

		$sql="delete from mantenimientoseg where idmantenimiento=$id";
		$operacion = mysql_query($sql);
		$sql="delete from mantenimiento where idmantenimiento=$id";
		//echo "<br>Borro";
		
		$idtiposervicio="";
		//$idcontrato="";
		$nombrer="";
		$fecham="";
		$horam="";
		$reporte="";
		$subsecuentes="";
		break;

	case "3": //Actualizo

		$sql = "update mantenimiento set idtiposervicio=$idtiposervicio,idcontrato=$idcontrato,mantenimiento='$nombrer' where idmantenimiento=$id";
		///echo "<br>Actualizo";
		
		$idtiposervicio="";
		//$idcontrato="";
		$nombrer="";
		$fecham="";
		$horam="";
		$reporte="";
		$subsecuentes="";

	}

	//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{

		echo $operacion = mysql_query($sql);

	}


















	//inicia la variable que contendr· la consulta

	$sql = "select m.idmantenimiento, mantenimiento, tiposervicio, count(idmantenimientoseg) as cantidad from mantenimiento m, mantenimientoseg ms, tiposervicio ts  where m.idmantenimiento = ms.idmantenimiento and m.idtiposervicio = ts.idtiposervicio and idcontrato = $idcontrato group by m.idmantenimiento, tiposervicio ";
	$operacion = mysql_query($sql);
	//ejecuto la consulta si tiene algo en la variable
	echo "<center><table border=\"1\"><tr><th>Id</th><th>Tipo servicio</th><th>Reporte</th><th>Agendados</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($operacion))
	{
		echo "<tr><td>" . $row["idmantenimiento"] . "</td><td>" . CambiaAcentosaHTML($row["tiposervicio"]) . "</td><td>" . CambiaAcentosaHTML($row["mantenimiento"]) . "</td><td>" . CambiaAcentosaHTML($row["cantidad"]) . "</td><td>";
		echo "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idmantenimiento"]  . "' )}\" $txtborrar>";
		echo "<input type=\"button\" value=\"Actualizar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idmantenimiento"]  . " )\" $txteditar>";
		//echo "<input type=\"hidden\" name=\"id\" value=\"" . $row["idmodulo"] . "\">";
		echo "</td></tr>";
	}
	echo "</table></center>";
//Genero el formulario de los submodulos
}