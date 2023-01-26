<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


//$prueba = New Calendario;
$contrato = @$_GET["contrato"];
$impaplicado = @$_GET["impaplicado"];
$fechapago = @$_GET["fechapago"];
$horaaplicado = @$_GET["horaaplicado"];



$misesion = new sessiones;

if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='cobro.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta']  . "/" . $row['archivo'];
		$ruta= $row['ruta'] ;
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


$sql = "select *, i.nombre as inombre, i.nombre2 as inombre2, i.apaterno as iapaterno, i.amaterno as iamaterno, cr.iva as criva, h.idhistoria as idhistoriah, inm.idinmueble as idin from historia h, contrato c, inquilino i, cobros cr, inmueble inm, tipocobro tc where ";
$sql .="c.idcontrato = h.idcontrato ";
$sql .="and h.idcobros = cr.idcobros ";
$sql .="and tc.idtipocobro = cr.idtipocobro ";
$sql .="and c.idinquilino = i.idinquilino ";
$sql .="and c.idinmueble = inm.idinmueble ";
$sql .="and h.cantidad>0 ";
$sql .="and c.idcontrato = $contrato ";
$sql .="and h.fechapago = '$fechapago' ";
$sql .="and h.horaaplicado = '$horaaplicado' ";
$sql .="and h.impaplicado = $impaplicado ";

$linea = "";
$fh=""; //para fecha y hora
$inf=""; //para informacion general
$ff="";  //para folio de factura si la hay
$if="";  //para informacion de factura importes
$ing=""; //para informacion de ingresos

//echo $sql;
$operacion = mysql_query($sql);
while($row = mysql_fetch_array($operacion))
{
	
	$fh = $row["fechapago"] . "," . $row["horaaplicado"];
	
	$inf = "," . $row["inombre"] . " " . $row["inombre2"]  . " " . $row["iapaterno"]  . " " . $row["iamaterno"];
	$inf .= "," . $row["calle"] . " " . $row["numeroext"]  . "  " . $row["numeroint"] ;
	if($row["interes"])
	{
		$inf .= "," . $row["tipocobro"] . "(interes)";
	}
	else
	{	

		$nota = "";
		if($row["notacredito"]==1)
		{
			$nota="(Nota de credito)";
		}	
	
		$inf .= "," . $row["tipocobro"] . $nota;
	}
	
	
	$sqld = "select * from duenioinmueble di, duenio d where ";
	$sqld .= "di.idinmueble = " . $row["idin"];	
	$sqld .= " and di.idduenio = d.idduenio ";
	$du="";
	//echo $sqld;
	$operaciond = mysql_query($sqld);
	while($rowd = mysql_fetch_array($operaciond))
	{		
		$du .=  $rowd["nombre"] . " " . $rowd["nombre2"]  . " " . $rowd["apaterno"]  . " " . $rowd["amaterno"] . " - ";
	}	

	$inf = ", $du";	
	
	if($row["interes"])
	{
	
			
			$importe = $row["cantidad"] / (1.16);
			$ivai = $row["cantidad"] - $importe;
			$ing = ",$importe , $ivai ," . $row["cantidad"];
		
	
	}
	else
	{	
		$prod=1;
		if($row["notacredito"]==1)
		{
			$prod= -1;
		}			
	
		if($row["criva"]>0)
		{
			$importe = $row["cantidad"] / ($row["criva"] + 1);
			$ivai = $row["cantidad"] - $importe;
			$ing = "," . ($prod * $importe) . "," . ($prod * $ivai) . "," . ($prod * $row["cantidad"]); 	
	
		}
		else
		{
			$ing =",,," + ($prod * $row["cantidad"]);	
		} 
	}
	
	$sqlf = "select * from historiacfdi hcfdi, facturacfdi f where";
	$sqlf .= " hcfdi.idhistoria = " . $row["idhistoriah"];
	$sqlf .= " and hcfdi.idfacturacfdi = f.idfacturacfdi ";
	
	$ff=",";
	$if=",,,";
	if($row["hfacturacfdi"]==1)
	{
		//multiplicar por menos uno en el resultado de la factura
	
		$operacionf = mysql_query($sqlf);
		
		$ok=0;
		while($rowf = mysql_fetch_array($operacionf))
		{
			if($ok==0)
			{
				$if= "," . $rowf["subtotal"] . "," . $rowf["traslados"] . "," . $rowf["total"];
				
				$ok=1;
				$ff= "," . $rowf["serie"] . $rowf["folio"];
			}
			else
			{
				$ff= "," . $rowf["serie"] . $rowf["folio"] . "(NC)";
			}
		}
	
	}

	$linea .= $fh . $ff . $inf . $if . $ing . "\n";
	
	
	
	
}
echo "<textarea rows=40 cols=100>$linea</textarea>";

}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}

?>