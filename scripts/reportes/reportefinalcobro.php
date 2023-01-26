<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$fechai=@$_GET["fechai"];
$fechaf=@$_GET["fechaf"];
$prop=@$_GET["prop"];
$accion=@$_GET["accion"];
$propff="";
$ff="";

$misesion = new sessiones;

if($misesion->verifica_sesion()=="yes")
{

	$sql="SELECT * FROM submodulo WHERE archivo ='reportefinalcobro.php'";
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

	$titulo = '';
	$reporte ='';	

	if($accion==1 || $accion==2){



		if($fechai){
			if($fechaf){
				$ff = "AND h.fechapago BETWEEN '$fechai 00:00:00' AND '$fechaf 23:59:59' ";	
			}else{
				$ff = "AND h.fechapago = '$fechai'";
			}
		}
		 
		 if($prop == "0"){
		 	//$sql = "SELECT *, i.nombre AS inombre, i.nombre2 AS inombre2, i.apaterno AS iapaterno, i.amaterno AS iamaterno, cr.iva AS criva, h.idhistoria AS idhistoriah, inm.idinmueble AS idin, h.cantidad AS cantidadh, h.interes AS inth FROM historia h, contrato c, inquilino i, cobros cr, inmueble inm, tipocobro tc WHERE c.idcontrato = h.idcontrato AND h.idcobros = cr.idcobros AND tc.idtipocobro = cr.idtipocobro AND c.idinquilino = i.idinquilino AND c.idinmueble = inm.idinmueble AND h.cantidad>0 AND (h.impaplicado>0 or notacredito=1) " . $ff . "ORDER BY c.idcontrato, h.fechapago, h.impaplicado, h.horaaplicado ";
		 	$sql = "SELECT *, i.nombre AS inombre, i.nombre2 AS inombre2, i.apaterno AS iapaterno, i.amaterno AS iamaterno, cr.iva AS criva, h.idhistoria AS idhistoriah, inm.idinmueble AS idin, h.cantidad AS cantidadh, h.interes AS inth, a.referencia as refc FROM historia h, contrato c, inquilino i, cobros cr, inmueble inm, tipocobro tc, apartado a WHERE c.idapartado = a.idapartado and  c.idcontrato = h.idcontrato AND h.idcobros = cr.idcobros AND tc.idtipocobro = cr.idtipocobro AND c.idinquilino = i.idinquilino AND c.idinmueble = inm.idinmueble AND h.cantidad>0 AND (h.impaplicado>0 or notacredito=1) " . $ff . "ORDER BY c.idcontrato, h.fechapago, h.impaplicado, h.horaaplicado ";
		 	
		 }
        if($prop == "1"){

        	//$sql = "SELECT *, i.nombre AS inombre, i.nombre2 AS inombre2, i.apaterno AS iapaterno, i.amaterno AS iamaterno, cr.iva AS criva, h.idhistoria AS idhistoriah, inm.idinmueble AS idin, h.cantidad AS cantidadh, h.interes AS inth FROM historia h, contrato c, inquilino i, cobros cr, inmueble inm, tipocobro tc WHERE c.idcontrato = h.idcontrato AND h.idcobros = cr.idcobros AND tc.idtipocobro = cr.idtipocobro AND c.idinquilino = i.idinquilino AND c.idinmueble = inm.idinmueble AND h.cantidad>0 AND (tc.idfolios >= 1 AND tc.idfolios <= 3 OR h.interes = 1 ) AND (h.impaplicado>0 or notacredito=1) " . $ff . "ORDER BY c.idcontrato, h.fechapago, h.impaplicado, h.horaaplicado";
        	$sql = "SELECT *, i.nombre AS inombre, i.nombre2 AS inombre2, i.apaterno AS iapaterno, i.amaterno AS iamaterno, cr.iva AS criva, h.idhistoria AS idhistoriah, inm.idinmueble AS idin, h.cantidad AS cantidadh, h.interes AS inth, a.referencia as refc  FROM historia h, contrato c, inquilino i, cobros cr, inmueble inm, tipocobro tc , apartado a WHERE c.idapartado = a.idapartado and  c.idcontrato = h.idcontrato AND h.idcobros = cr.idcobros AND tc.idtipocobro = cr.idtipocobro AND c.idinquilino = i.idinquilino AND c.idinmueble = inm.idinmueble AND h.cantidad>0 AND (tc.idfolios >= 1 AND tc.idfolios <= 3 OR h.interes = 1 ) AND (h.impaplicado>0 or notacredito=1) " . $ff . "ORDER BY c.idcontrato, h.fechapago, h.impaplicado, h.horaaplicado";
        	
        }
        if($prop == "2"){
        	 
        	//$sql = "SELECT *, i.nombre AS inombre, i.nombre2 AS inombre2, i.apaterno AS iapaterno, i.amaterno AS iamaterno, cr.iva AS criva, h.idhistoria AS idhistoriah, inm.idinmueble AS idin, h.cantidad AS cantidadh, h.interes AS inth FROM historia h, contrato c, inquilino i, cobros cr, inmueble inm, tipocobro tc WHERE c.idcontrato = h.idcontrato AND h.idcobros = cr.idcobros AND tc.idtipocobro = cr.idtipocobro AND c.idinquilino = i.idinquilino AND c.idinmueble = inm.idinmueble AND h.cantidad>0 AND tc.idfolios >= 4 and h.interes = 0   AND (h.impaplicado>0 or notacredito=1) " . $ff . "ORDER BY c.idcontrato, h.fechapago, h.impaplicado, h.horaaplicado";
        	$sql = "SELECT *, i.nombre AS inombre, i.nombre2 AS inombre2, i.apaterno AS iapaterno, i.amaterno AS iamaterno, cr.iva AS criva, h.idhistoria AS idhistoriah, inm.idinmueble AS idin, h.cantidad AS cantidadh, h.interes AS inth, a.referencia as refc  FROM historia h, contrato c, inquilino i, cobros cr, inmueble inm, tipocobro tc , apartado a WHERE c.idapartado = a.idapartado and  c.idcontrato = h.idcontrato AND h.idcobros = cr.idcobros AND tc.idtipocobro = cr.idtipocobro AND c.idinquilino = i.idinquilino AND c.idinmueble = inm.idinmueble AND h.cantidad>0 AND tc.idfolios >= 4 and h.interes = 0   AND (h.impaplicado>0 or notacredito=1) " . $ff . "ORDER BY c.idcontrato, h.fechapago, h.impaplicado, h.horaaplicado";
        	 
        }
        
		

		$titulo = "<h3>Reporte Facturacion del $fechai al $fechaf</h3>";
  		$reporte = " <table border=1> 
   			<tr style='background-color:#9C0;'>
    		<th colspan='8'></th>
    		<th colspan='3' aling='center'>FACTURACION</th>
    		<th colspan='3' align='center'>INGRESO</th>
    		<th></th>
   		</tr>
	   	<tr style='background-color:#9C0;'> 
		    <th>FECHA</th> 
    		<th>HORA</th> 
    		<th>SERIE/FOLIO</th> 
    		<th>REFERENCIA</th> 
    		<th>INQUILINO</th>
    		<th>DIRECCION</th> 
    		<th>CONCEPTO</th> 
    		<th>PROPIETARIO</th> 
    		<th>IMPORTE</th> 
    		<th>IVA</th> 
    		<th>TOTAL</th> 
    		<th>IMPORTE</th> 
    		<th>IVA</th>
    		<th>TOTAL</th>
    		<th>PAGO TOTAL</th>
   		</tr> ";

   		
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion)){
			$pyb=0;
			$fechaConcepto='';
			$horaConcepto='';
			$serieFolio='';
			$inquilino='';
			$direccio='';
			$concepto='';
			$duenio='';
			$referencia = '';
			$importeFac=0;
			$ivaFac=0;
			$totalFac=0;
			$importeIng=0;
			$ivaIng=0;
			$totalIng=0;
			$totalPagado=0;

			$fechaConcepto = $row["fechapago"];
			$horaConcepto = $row["horaaplicado"];
			$inquilino = $row["inombre"] . " " . $row["inombre2"]  . " " . $row["iapaterno"]  . " " . $row["iamaterno"];		
			$direccio =$row["calle"] . " " . $row["numeroext"]  . "  " . $row["numeroint"];
			$direccion = str_replace("<br>", "", $direccio);
			$referencia = $row["refc"];
			
			
			if($row["inth"]==1){
				$concepto = $row["tipocobro"]." del ".$row["fechanaturalpago"] . " (interes)";
			}else{
				$nota = "";
				
				if($row["notacredito"]==1){
					$nota="(Nota de credito)";
				}	

				$concepto = $row["tipocobro"]." del ".$row["fechanaturalpago"]." " .  $nota;		
			}
						
			$sqld = "SELECT * FROM duenioinmueble di, duenio d WHERE di.idinmueble = " . $row["idin"]." AND di.idduenio = d.idduenio $propff ";

			$operaciond = mysql_query($sqld);
			while($rowd = mysql_fetch_array($operaciond)){
				$duenio .= $rowd["nombre"] . " " . $rowd["nombre2"]  . " " . $rowd["apaterno"]  . " " . $rowd["amaterno"] . " - ";
			}

			if($row["idfolios"]>=1 && $row["idfolios"]<=3 ){
				$duenio = "Padilla & Bujalil S.C.";
				
			}

			if($row["inth"]==1){
				$duenio = "Padilla & Bujalil S.C.";
				
			}
			
			if($row["inth"]==1){

				$importe = $row["cantidadh"] / (1.16);
				$ivai = $row["cantidadh"] - $importe;

				$importeIng = $importe;
				$ivaIng = $ivai;
				$totalIng = ($row["cantidadh"] *1);

			}else{
				
				$prod = (1);
				
				if($row["notacredito"]==1){
					$prod = (-1);
				}
			
				if($row["criva"]>0){
					
					$importe = $row["cantidadh"] / (($row["criva"]/$row["cantidad"]) + 1);
					$ivai = $row["cantidadh"] - $importe;

					$importeIng = ($prod * $importe);
					$ivaIng = ($prod * $ivai);
					$totalIng = ($prod * $row["cantidadh"]);

				}else{
					$importeIng = ($prod * $row["cantidadh"]);
					$ivaIng = 0;
					$totalIng = ($prod * $row["cantidadh"]);
				}
			}
			
			$sqlf = "SELECT * FROM historiacfdi hcfdi, facturacfdi f WHERE hcfdi.idhistoria=" . $row["idhistoriah"]." AND hcfdi.idfacturacfdi = f.idfacturacfdi $propff";
			
		/*	$contador++;
			if($contador==1){
				$color="style='background-color:#FFFFFF;'";
			}else{
				$color="style='background-color:#CCCCCC;'";
				$contador=0;
			}   CONTADOR ESTA DECLARADO PERO NO ESTA SIENDO UTILIZADO   */

			$serieFolio ='';
			$importeFac=0;
			$ivaFac=0;
			$totalFac=0;
			
			if($row["hfacturacfdi"]==1){
				
				//multiplicar por menos uno en el resultado de la factura
				$operacionf = mysql_query($sqlf);
				
				$ok=0;
					
				$prod = (1);
				while($rowf = mysql_fetch_array($operacionf)){
					
					if($ok==0){

						$ok=1;
						$serieFolio = $rowf["serie"] . $rowf["folio"];

					}else{
						
						$serieFolio = $rowf["serie"] . $rowf["folio"] . "(NC)";
						$importeIng = 0;
						$ivaIng = 0;
						$totalIng = 0;

						$prod = (-1);
					}

					$importeFac=( $prod * $rowf["subtotal"]);
					$ivaFac=( $prod * $rowf["traslados"]);
					$totalFac=( $prod * $rowf["total"]);
					
					$totalPagado=($row["impaplicado"] *1);
					$reporte .= "<tr $color><td>$fechaConcepto</td><td>$horaConcepto</td><td>$serieFolio</td><td>$referencia</td><td>$inquilino</td><td>$direccion</td><td>$concepto</td><td>$duenio</td><td style='background-color:#9e5210;'>$".number_format($importeFac,2,".",",")."</td><td style='background-color:#9e5210;'>$".number_format($ivaFac,2,".",",")."</td><td style='background-color:#9e5210;'>$".number_format($totalFac,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($importeIng,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($ivaIng,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($totalIng,2,".",",")."</td><td>$".number_format($totalPagado,2,".",",")."</td></tr>";
				}
			}else{

					$totalPagado=($row["impaplicado"] *1);
					$reporte .= "<tr $color><td>$fechaConcepto</td><td>$horaConcepto</td><td>$serieFolio</td><td>$referencia</td><td>$inquilino</td><td>$direccion</td><td>$concepto</td><td>$duenio</td><td style='background-color:#9e5210;'>$".number_format($importeFac,2,".",",")."</td><td style='background-color:#9e5210;'>$".number_format($ivaFac,2,".",",")."</td><td style='background-color:#9e5210;'>$".number_format($totalFac,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($importeIng,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($ivaIng,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($totalIng,2,".",",")."</td><td>$".number_format($totalPagado,2,".",",")."</td></tr>";
				}

		}

		$reporte .="</table>";

		if($accion==2){
			header('Content-type: application/vnd.ms-excel');
    		header("Content-Disposition: attachment; filename=ReporteFacturacion_$fechas.xls");
    		header("Pragma: no-cache");
    		header("Expires: 0");
		}

		echo "<center>";
  		echo $titulo;
  		echo "<br>";
  		echo $reporte;
  		echo "</center>";

	}else{
		$formularioHtml =  <<<formulario1
<center>
<h1>Reporte de facturaci&oacute;n</h1>
<form>
	<table border="0">
	<tr>
		<td>Fecha I.(aaaa-mm-dd):</td>
		<td><input type="date" name="fechai" ></td>
	</tr>
	<tr>
		<td>Fecha F.(aaaa-mm-dd):</td>
		<td><input type="date" name="fechaf" ></td>
	</tr>
	<tr>
		<td>Propietario:</td>
		<td><select name="prop"><option value="0">Todos</option><option value="1">Padilla Bujalil</option><option value="2">Propietarios</option></select></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="button" value="Limpiar" onClick="fechai.value='';fechaf.value=''">
			<input type="button" value="Generar" onClick="if(fechai.value!='' ){ cargarSeccion('$dirscript','reportediv','accion=1&fechai=' + fechai.value + '&fechaf=' + fechaf.value + '&prop=' + prop.value)};" >
			<input type="button" value="Descargar" onClick="window.open('$dirscript?accion=2&fechai=' + fechai.value + '&fechaf=' + fechaf.value + '&prop=' + prop.value)">
		</td>
	</tr>
	</table>
</form>
<div id="reportediv">
</div>
</center>
formulario1;
	echo $formularioHtml;

	}
}else{
	echo "A&uacute;n no se ha firmado con el servidor";
}
?>