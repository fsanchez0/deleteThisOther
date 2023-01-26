<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


//Modulo

$filtro=@$_GET["filtro"];
$paso=@$_GET["paso"];
$idduenio=@$_GET["id"];
$importe=@$_GET["importe"];
$condepto=@$_GET["concepto"];
$idedoduenio=@$_GET["idedoduenio"];

$miwhere="";

$titulo="";
$reporte="";

$color = "#009B7B";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='reportedetallado.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta= $row['ruta'];
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}


	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and e.idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	


	switch($paso)
	{
	case 0://agregar
		
		$importereal = $importe / (1.16);
		$ivareal = $importe - $importereal;
	
		$fechaedod = @date("Y") . "-" . @date("m") . "-" . @date("d");
		$horaedod = @date("H") . ":" . @date("i") . ":" . @date("s");	
	
		$sql="insert into edoduenio (idduenio,idcontrato,idinmueble,idtipocobro,reportado,liquidado,notaedo, iva, utilidad, importe,fechaedo,horaedo, referencia, notacredito) values ";
		$sql .= "($idduenio,0,0,0,true,0,'$condepto', $ivareal, 0, $importereal,'$fechaedod','$horaedod', '', 1)";
	
	
	
		break;
	case 1:// borrar
		$sql="delete from edoduenio where idedoduenio = $idedoduenio";
		break;
	}
	//echo $sql;
	if($sql!="")
	{

		$operacion = mysql_query($sql);
		//$mensaje="Operacion exitosa";

	}
	
	
	
	
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e where d.idduenio = e.idduenio and importe<>0  and isnull(fechagen)=true and reportado = 1 and d.idduenio=$idduenio and notacredito = true ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
	
	$operacion = mysql_query($sql);

	$controlesjava="";
	$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	$reporte="";
	$total=0;
	$duenio="";
	$idtipocobro="";
	$clase = "";
	//echo "<form>";
	$reporte .= "<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";

	while($row = mysql_fetch_array($operacion))	
	{
	
	
	
		$suma = $row["importe"] + $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= "disabled "; 
					$mensaje=" Relacionado con otro estado de cuenta ";
				}
				else
				{
					$deshabilitar= " ";
				}	
			}
		}
		else
		{
			$ver = split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado con otro estado de cuenta ";
					}
				}	
			}		
		}	
			
		$relaciones = $row["referencia"];
		$ver1 = split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = " checked ";
		}
		
		$habilitado = "";
		
		
		$boton = "<input type='button' value='Quitar' onclick=\"cargarSeccion('$ruta/edonotacredito.php','edonotasc', 'paso=1&filtro=$filtro&id=$idduenio&idedoduenio="  . $row["idedoduenio"] . "'); \" $habilitado />";;

		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='$suma' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controles ="<td>" . $row["notaedo"]  . "</td><td align='center'>$suma </td><td align='center'>$boton $mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
		$reporte .= "<tr $clase><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}
	
	echo $reporte .="<tr><td colspan='2' align='center'>Total notas de credito</td><td colspan='2'><input type='text' size='10' name='total0' id='total0' value='$total' onClick=\"calculaedoduenio('total0', '$sumatotal' , '$verificalista')\"/></td></tr></table>";
			
	
	
	
	
}	
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>

</body>
</html>
