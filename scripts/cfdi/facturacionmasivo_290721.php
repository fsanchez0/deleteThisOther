<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/ftpclass.php';
include ("../cfdi/cfdiclassn.php");

$cfd =  new cfdi32class;
$misesion = new sessiones;

if($misesion->verifica_sesion()=="yes")
{
	$sql="SELECT * FROM submodulo WHERE archivo ='facturacionmasivo.php'";
	$operacion = mysqli_query($connection,$sql);
	while($row = mysqli_fetch_array($operacion))
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

	$renglones='';
	$masivotxt='';
	$recuMasivo='';

	$sqlRecuperar="SELECT * FROM historia WHERE idhistoria IN (SELECT idhistoria FROM historiacfdi WHERE idfacturacfdi IN (SELECT idfacturacfdi FROM facturacfdi WHERE (pdfok=0 OR pdfok IS NULL) AND txtok=1 AND (cancelada=0 OR cancelada IS NULL) AND idfacturacfdi>=32000)) ORDER BY idcontrato DESC, fechanaturalpago DESC";

	$operacionRecuperar = mysqli_query($connection,$sqlRecuperar);	
	while ($rowRecuperar = mysqli_fetch_array($operacionRecuperar)) {

		$idhistoriaR=$rowRecuperar["idhistoria"];
		$recuMasivo .= $idhistoriaR."|";
	}

	$sql="SELECT h.idhistoria, h.cantidad AS cantidadh, h.iva AS ivah, h.fechapago, h.fechanaturalpago, h.fechagenerado, h.interes, h.parcialde, h.idcontrato, cb.cantidad AS cantidadc, cb.iva AS ivac, t.tipocobro, inm.calle, inm.numeroext, inm.numeroint, inm.colonia, inm.delmun, inm.cp, inq.nombre, inq.nombre2, inq.apaterno, inq.amaterno FROM historia h, contrato c, inmueble inm, inquilino inq, cobros cb, tipocobro t WHERE h.idcontrato=c.idcontrato AND h.idcobros=cb.idcobros AND c.idinmueble=inm.idinmueble AND c.idinquilino=inq.idinquilino AND cb.idtipocobro=t.idtipocobro AND h.notas LIKE '%LIQUIDADO%' AND h.aplicado=1 AND (h.hfacturacfdi=0 OR h.hfacturacfdi IS NULL) AND h.fechanaturalpago>='2017-01-01' ORDER BY h.idcontrato";

	$operacion = mysqli_query($connection,$sql);	
	while ($row = mysqli_fetch_array($operacion)) {
			
		$sqlcfdi="SELECT * FROM historiacfdi h, facturacfdi f WHERE h.idfacturacfdi=f.idfacturacfdi AND idhistoria = " . $row["idhistoria"];

		$operacioncfdi = mysqli_query($connection,$sqlcfdi);
		if(mysqli_num_rows($operacioncfdi)<1)
		{	
			$idcontrato=$row["idcontrato"];
			$fechapago=$row["fechapago"];
			$fechanaturalpago=$row["fechanaturalpago"];
			$concepto=$row["tipocobro"];
			$inquilino=$row["nombre"]." ".$row["nombre2"]." ".$row["apaterno"]." ".$row["amaterno"];
			$inmueble=$row["calle"]." ".$row["numeroext"]." ".$row["numeroint"]." ".$row["colonia"]." ".$row["cp"]." ".$row["delmun"];

			//$edocuenta="nuevaVP(" . $row["idhistoria"] . ",'');this.disabled=true;";
			//$accionbotonfact="<br>" . '<form name="frm_' . $row["idhistoria"] .  '" id="frm_' . $row["idhistoria"] .  '" method="POST" action="../scripts/reporte2.php" target="trg_' . $row["idhistoria"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value ="">' . "<input type =\"button\" value=\"Facturar\" onClick=\"$edocuenta\"/></form>";
				
			$masivotxt.=$row["idhistoria"]."|";
			$accionbotonfact="<br>" . '<form name="frm_' . $row["idhistoria"] .  '" id="frm_' . $row["idhistoria"] .  '" method="POST" action="../scripts/reporte2.php" target="trg_' . $row["idhistoria"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""></form> Facturar <input type="checkbox" value="'.$row["idhistoria"].'" name="h_'.$row["idhistoria"].'" checked="" onchange="ok=1;if(this.checked==true){ok=1}else{ok=0};actualizamailsp(this.name,ok)">';
						
			$estado="PAGADO <div id='cfdi" . $row["idhistoria"] . "'>" . $accionbotonfact . "</div>";

			$operacionDetalles = "PAGO";
			$Pagado = ($row["cantidadc"] + $row["ivac"]);

			if($row["cantidadh"]<0){
				$operacionDetalles .= " (Nota de Credito)";
				$Pagado = ($row["cantidadh"] + $row["ivah"]);
			}
			
			if (is_null($row["interes"])==false and $row["interes"]==1){
					
				if($row["cantidadh"]<0){
					$operacionDetalles = "INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . "(" . $row["tipocobro"] . ") (Nota de Credito)";
					$Pagado = ($row["cantidadh"] + $row["ivah"]);
				}else{
					$operacionDetalles = "INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . "(" . $row["tipocobro"] . ")";

					$sqlInteres="SELECT SUM(cantida) AS cantidadt, SUM(iva) AS ivat FROM historia WHERE parcialde=" . $row["parcialde"];
					$operacionInteres = mysqli_query($connection,$sqlInteres);
					$rowInteres = mysqli_fetch_array($operacionInteres);

					$Pagado = ($rowInteres["cantidadt"] + $rowInteres["ivat"]);
				}
			}

			$tablainterna = "<tr><td width='300'>$operacionDetalles<br><strong>" . $row["observaciones"] . "</strong></td><td align='right' width='100'>$ " . number_format($Pagado,2)  . "</td><td align='center' width='100'> ".$fechapago." </td><td width='100'> $estado</td></tr>\n";

			$mesren = substr($fechanaturalpago,5,2);
			switch ((int)$mesren){
				case 1:
					$mesren = "Enero";
					break;
				case 2:
					$mesren  = "Febrero";
					break;
				case 3:					
					$mesren  = "Marzo";					
					break;
				case 4:					
					$mesren = "Abril";					
					break;
				case 5:					
					$mesren = "Mayo";					
					break;
				case 6:					
					$mesren = "Junio";					
					break;
				case 7:					
					$mesren = "Julio";					
					break;
				case 8:					
					$mesren = "Agosto";					
					break;
				case 9:					
					$mesren = "Septiembre";					
					break;
				case 10:					
					$mesren = "Octubre";					
					break;
				case 11:					
					$mesren = "Noviembre";					
					break;
				case 12:					
					$mesren = "Diciembre";					
					break;
			}

			$contador++;
    		if($contador==1){
       			$color="style='background-color:#FFFFFF;'";
		    }else{
		    	$color="style='background-color:#CCCCCC;'";
				$contador=0;
		    }

			$renglones .="<tr $color><td><string><p onclick=\"window.open('/scripts/inmuebles/edocuenta.php?contrato=$idcontrato');\">$idcontrato</p></string></td><td>$inquilino</td><td>$inmueble</td><td>$concepto del ".substr($fechanaturalpago,8,2)."-".$mesren."-".substr($fechanaturalpago,0,4)."</td><td><table border='1'><tr style='background-color:#9C0;'><th>Operacion</th><th>Cantidad</th><th>Fecha pago</th><th>Estado</th></tr> $tablainterna </table></td><td><iframe name='trg_" . $row["idhistoria"] . "' id='trg_" . $row["idhistoria"] . "'></iframe></td></tr>\n";
		}
	}

	$html = "<center><h2>Faturacion Masiva</h2><br>
			
		<input id='recuboton' name='recuboton' type =\"button\" value=\"Recuperar Facturas\" onClick=\"this.disabled=true;alert('NO CIERRE LA VENTANA, DE CLIC EN ACEPTAR PARA COMENZAR');listaer=document.getElementById('masivoRecu').value; lr = listaer.split('|');for(j=0;j<lr.length-1;j++){elidrr = lr[j];cargarSeccion('../scripts/recuperar.php','recuperacion', 'id=' + elidrr + '&filtro=');}\" style='font-size:25px;' />
		<p> Solo se recuperaran las facturas creadas antes de cargar esta pagina</p>
		<input type='hidden' id='masivoRecu' name='masivoRecu' value='$recuMasivo'/>
		<div id='recuperacion' style='visibility: hidden'>
		</div>
		<br>
		<table border='1'>
			<tr style='background-color:#9C0;'>
				<th>Contrato</th>
				<th>Inquilino</th>
				<th>Inmueble</th>
				<th>Concepto</th>
				<th>Detalles</th>
			</tr>".$renglones."
		</table>
		<br>
		<input type =\"button\" value=\"Facturar\" onClick=\"this.disabled=true;recuboton.disabled=true;alert('NO CIERRE LA VENTANA, DE CLIC EN ACEPTAR PARA COMENZAR');listae=document.getElementById('masivo').value; l = listae.split('|');for(i=0;i<l.length-1;i++){elidr = l[i];var f = document.getElementById('frm_' + elidr);f.idc.value = elidr;f.filtro.value= '';f.submit();}\" style='font-size:25px;'/>			
		<input type='hidden' id='masivo' name='masivo' value='$masivotxt'/>
		</center>";

		echo $html;

}else{
	echo "A&uacute;n no se ha firmado con el servidor";
}
?>