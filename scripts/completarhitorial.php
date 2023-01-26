<?php
include "general/calendarioclass.php";
include_once('general/conexion.php');

$fechas = New Calendario;
$fechaa="";
$fechat="";
$idc="";
$idcc="";
$lista="";
$e="";
$i=0;
$HoyFecha = date('Y') . "-" . date('m') . "-" . date('d');

	$sql="select c.idcontrato as idc, cb.idcobros as idcc, numero, idmargen, cb.cantidad as cantidadc, cb.iva as ivac, cb.idprioridad as idprioridadc,  fechatermino, max(fechanaturalpago) as fechau from historia h, contrato c, cobros cb, periodo where  h.idcontrato = c.idcontrato and concluido=false and cb.idcobros = h.idcobros and cb.idperiodo=periodo.idperiodo and numero >=1 group by c.idcontrato, cb.idcobros, fechatermino, cb.cantidad , cb.iva, cb.idprioridad ";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		
		if ($idc != $row["idc"])
		{
			$idc=$row["idc"];
			$fechat = $row["fechatermino"];
		}
		$fechaa = $row["fechau"];
		//$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));		
		$lista .= "<p> Se generarán parael contrato $idc, el cobro $idcc en las fechas partiendo de $fechaa a la fecha de termino $fechat:</p>";
		//$fechaa = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
//		$lista .= "<ol>";
		$i=0;
		while ($fechaa < $fechat )
		{
			$i++;
			if($e != "")
			{
				//$lista .= $e;
				$lista .= $sql2 . "<br>";
			}
			
			$idcc=$row["idcc"];
			
			$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));
			$fechaa = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
			$ProxVencimiento=$fechas->fechagracia($fechaa);
			
			$e = "<li>$i  $fechaa   y fecha de gracia $ProxVencimiento </li>";
			
			$sql2="insert into historia (idcontrato,idcobros,fechagenerado,fechanaturalpago,fechagracia,cantidad,iva,interes,vencido,aplicado, idusuario,idprioridad,fechavencimiento,condonado) values(";
			//echo $sql2 .= $idc . "," . $idcc . ",'" . $HoyFecha . "', '" . $fechaa  . "', '" . $ProxVencimiento . "'," .  number_format($row["cantidadc"],2,".","") . "," . number_format($row["ivac"],2,".","") . ",false,false,false," . $misesion->usuario . "," . $row["idprioridadc"] . " , '" . $ProxVencimiento . "',false)";
			$sql2 .= $idc . "," . $idcc . ",'" . $HoyFecha . "', '" . $fechaa  . "', '" . $ProxVencimiento . "'," .  number_format($row["cantidadc"],2,".","") . "," . number_format($row["ivac"],2,".","") . ",false,false,false,1," . $row["idprioridadc"] . " , '" . $ProxVencimiento . "',false);";
			//echo "<br>";
				
		}
		$e="";
//		$lista .= "</ol>";
		$lista .= "<br>";
	}	
	echo $lista;

?>