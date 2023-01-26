<?php
  include_once('../general/conexion.php');
  include '../general/funcionesformato.php';
  setlocale(LC_MONETARY, 'en_US');
  ini_set("display_errors",0);
  $arrayMeses=array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic","Ene");
  @$idContrato=$_REQUEST["idContrato"];
$arregloColores=array("red","#EEF440","blue","gray","teal","maroon","purple","#F76663","#3EB8B2","#96D856","#FFC4FF","#0B6121","#BCA9F5","navy","fuchsia","aqua");


	
  header('Content-type: application/vnd.ms-excel');
  header("Content-Disposition: attachment; filename=EdoCuenta$idContrato.xls");
  header("Pragma: no-cache");
  header("Expires: 0");
  
  echo "<center><h1>Estado de cuenta Contrato No. $idContrato</h1></center>";
  echo "<table border=1> ";
         
  $sql="SELECT contrato.idcontrato as elidcontrato,  i.nombre,i.nombre2,i.apaterno,i.amaterno,i.tel as telinq,tipocobro,t.idtipocobro as idtipocobro,fechagenerado,fechanaturalpago,fechagracia,
fechapago,parcialde,h.cantidad as cantidad,aplicado,h.interes as interes,h.vencido as vencido,h.notacredito as notacredito,inm.calle,inm.numeroext,inm.numeroint,inm.colonia,inm.delmun,inm.cp,e.estado,p.pais
,inm.tel as telinm,h.iva as iva,aplicado,condonado,f.nombre as fnombre,f.nombre2 as fnombre2,f.apaterno as fapaterno,f.amaterno as famaterno, f.direccion as fdireccion, f.tel as ftel,h.notas as hnotas,
f.email as emailf,notanc,i.email as emaili,observaciones,h.idhistoria as idhistoria,contrato.idcontrato,h.idhistoria as idh,
h.notanc as notanc, h.parcialde as parcialde  FROM contrato , cobros c, tipocobro t, inquilino i, inmueble inm, historia h, 
fiador f, estado e, pais p WHERE c.idcontrato=contrato.idcontrato  and contrato.idcontrato=h.idcontrato AND t.idtipocobro=c.idtipocobro 
 AND i.idinquilino=contrato.idinquilino AND contrato.idinmueble=inm.idinmueble AND h.idcobros=c.idcobros  AND contrato.idfiador=f.idfiador AND inm.idestado = e.idestado 
AND inm.idpais = p.idpais AND contrato.idcontrato='".$idContrato."' AND condonado=false  order by h.fechanaturalpago asc, h.idhistoria asc, h.fechapago asc";
		$accionbotonfact="";
		$reciboscfdi="";
		$estado="";
		$primero=true;
		$suma=0;
  		$ejecuta=mysqli_query($connection,$sql);
		//Creacion de Tabla 
		echo "<tr style='background-color:#5C9CCF; font-color:white;'>";
			echo "<th >Fecha Pago</th>";
			echo "<th  aling='center'>Descripci&oacute;n</th>";
			echo "<th align='center'>Factura</th>";
			echo "<th >Cargo</th>";
			echo "<th >Abono</th>";
			echo "<th >Saldo</th>";
		echo "</tr>";
					$ulink='</p>';
			  	while ($row=mysqli_fetch_array($ejecuta)) {
			  		$factura="";
			  		$idCondonado=0;
			    	extract($row);
				    	$sqlh="SELECT serie, folio from historiacfdi hc, facturacfdi f WHERE hc.idfacturacfdi=f.idfacturacfdi and hc.idhistoria='".$idh."' AND cancelada is null";
				    	$historicoxml=mysqli_query($connection,$sqlh);

				  		while ($rowh=mysqli_fetch_array($historicoxml)) 
				  		{
							extract($rowh);
							$factura=$serie.$folio;
						}
						$sqlh="SELECT sum(historia.cantidad) as sumacantidad FROM contrato, cobros, tipocobro,historia WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato 
							and historia.idcobros=cobros.idcobros and historia.idcontrato = '$elidcontrato' and tipocobro ='$tipocobro' and fechanaturalpago='$fechanaturalpago' and historia.cantidad>0 and (historia.interes=0 OR historia.interes is null) group by   fechanaturalpago, tipocobro";
				    	  $sCantidad=mysqli_query($connection,$sqlh);

				  		while ($rowc=mysqli_fetch_array($sCantidad)) {
								extract($rowc);
							$cantExtra=$sumacantidad;
						}
							$first++;
							$notacreditot="";
							$reciboscfdi="";
							$estado="";
							$operacion="";
							$siAbono="";
							$rest=0;
							
							$link='<p>';
							$newDate=explode("-",date("d-m-Y",strtotime($fechanaturalpago)));
     						$days=$newDate["0"]."-".$arrayMeses[$newDate[1]*1-1]."-".$newDate["2"];
							$newDateGenerado=explode("-",date("d-m-Y",strtotime($fechagenerado)));
     						$daysGenerado=$newDateGenerado["0"]."-".$arrayMeses[$newDateGenerado[1]*1-1]."-".$newDateGenerado["2"];
							$pos = strpos($hnotas, "$");
							if (!$pos === false)
							{
							 	$rest = (double)substr($hnotas, $pos+1, 20);
							}
							if($estado==""&&!is_null($hnotas)) {
								$estado=$hnotas." ";
							}else {
								$estado="CANCELADO ";
							} 
							$concepto = $tipocobro;
							if($interes==1)
							{
								$operacion="$days INT 10% SOBRE ADEUDO GENERADO EL " . $daysGenerado . " (" . $tipocobro . ") "  ;
							}else{
								$operacion="" . $tipocobro . " ";
							}
							if($notanc!="")
							{
								$notacreditot= " $days (N.C.:" .$notanc. ") ";
							}
							//$operacion.=$notacreditot." ";
							$comparar=$operacion.' ('.$days.')';
							if ($sumaoperacion == $comparar)
							{
							 	$duplicada=true;
							}
							$sumaoperacion=$comparar;
							if($cantidad <0)
							{
								$estado="CANCELACIÓN DE LA OBLIGACIÓN "; 
							 	$duplicada=false;
							
							}else if ($condonado==1)
							{
								$estado="CARGO GENERADO ";
								
							} 
							//$Saldo+=(-$Cargo+$Abono);
							if($aplicado==false)
							{
								$Pagado=($cantidad*1)/*+($iva*1)*/;
								$Cargo=$Pagado;
								$Abono=0;
									if($Cargo<0)
										$Cargo*=-1;
								
							}
							else
							{
								$Pagado=($cantidad*1)+$rest;
								$Cargo=$Pagado;
							}
							if($Cargo<0){

							}else
							if(is_null($fechapago)){
							     if(!is_null($parcialde)){
							     }else {
    								if($Operaciontexto!='<td> Cargo '.$operacion.' ('.$days.')</td>'){}

    								$Saldo+=$Cargo;
    								$Operaciontexto='<td> Cargo '.$operacion.' ('.$days.')</td>';
    								$siCargo= '
    									<tr>
    										<td>'.$days.'</td>
    										<td> Cargo '.$operacion.' ('.$days.')</td>
    										<td>'.$factura.'
    										</td>
    										<td>$'.number_format($Cargo,2).'</td>
    										<td></td>';
									$arregloFechas[strtotime($fechanaturalpago)][$Operaciontexto]=array("cargo"=>$Cargo,"abono"=>null,"html"=>$siCargo);
							     }
							}else{

									$newDatePago=explode("-",date("d-m-Y",strtotime($fechapago)));
          							$daysPago=$newDatePago["0"]."-".$arrayMeses[$newDatePago[1]*1-1]."-".$newDatePago["2"];

									$Abono=$cantidad;
									if($Abono<0)
										$Abono*=-1;
							    
								if(is_null($parcialde)){
							    
    									if($Operaciontexto!='<td> Cargo '.$operacion.' ('.$days.')</td>'){}

    									$Saldo+=$Cargo;
    									$Operaciontexto='<td> Cargo '.$operacion.' ('.$days.')</td>';
        								$siCargo= '
    									<tr>
    										<td>'.$days.'</td>
    										<td> Cargo '.$operacion.' ('.$days.')</td>
    										<td>'.$factura.'
    										</td>
    										<td>$'.number_format($Cargo,2).'</td>
    										<td></td>';
										$arregloFechas[strtotime($fechanaturalpago)][$Operaciontexto]=array("cargo"=>$Cargo,"abono"=>null,"html"=>$siCargo);
    							}else if($parcialde==$idhistoria){
    								if(!$interes==1)
									$Cargo=$cantExtra;
    									if($Operaciontexto!='<td> Cargo '.$operacion.' ('.$days.')</td>'){}

    									$Saldo+=$Cargo;
    									$Operaciontexto='<td> Cargo '.$operacion.' ('.$days.')</td>';
        								$siCargo= '
    									<tr>
    										<td>'.$days.'</td>
    										<td> Cargo '.$operacion.' ('.$days.')</td>
    										<td>'.$factura.'
    										</td>
    										<td>$'.number_format($Cargo,2).'</td>
    										<td></td>';
										$arregloFechas[strtotime($fechanaturalpago)][$Operaciontexto]=array("cargo"=>$Cargo,"abono"=>null,"html"=>$siCargo);

    							}
    							$Saldo=$Saldo-$Abono;

    								$siAbono= '
    									<tr>
    										<td style="background-color:-color-; font-color:white;">'.$daysPago.'</td>
											<td > Pago '.$operacion.' ('.$days.')'." ".$estado.'</td>
    										<td>'.$factura.'
    										</td>
    										<td></td>
    										<td style="background-color:-color-; font-color:white;">$'.number_format($Abono,2).'</td>';

    									//echo $siCargo.$siAbono;
									$arregloFechas[strtotime($fechapago)][]=array("cargo"=>null,"abono"=>$Abono,"html"=>$siAbono);
    							
							}
							$duplicada=false;
							$Abono=0;
							$Cargo=0;
							$siAbono=null;
							$siCargo=null;
							$suma=$Saldo;							
							$antesfp=$fechapago;
				}


/*

	    		$color=$arregloColores[$cont];
	    		$color=$arregloColores[$cont];
	    		$cont++;*/

ksort($arregloFechas);

$cont=0;
$saldo=0;
foreach($arregloFechas as $key=>$valorFecha){
	foreach($valorFecha as $key1=>$valor){
		extract($valor);
	    $saldo=$saldo+$cargo-$abono;
	    if($key!=$memkey&&is_null($Abono)==false)
	    	$color = cambiarColor();
	    
    	//$color=$arregloColores[$cont];
    	$html=str_replace("-color-", $color, $html);
		echo  CambiaAcentosaHTML($html).'
			<td>$'.number_format($saldo,2).'</td>
		';
		echo  '
		</tr>';
		$abono=0;
		$cargo=0;
		$html=null;
		$memkey=$key;
	}
}


  echo "</tr>";
  echo "</table>";
	
	
function cambiarColor() {

    $colorazul = dechex(rand( 0 , 255 ));//)rand( 0 , 255 );
    if(strlen(trim($colorazul . " "))==1)
    {
        $colorazul = "0" .$colorazul;
    }
    
    $colorrojo =dechex(rand( 0 , 255 ));//rand( 0 , 255 );
    if(strlen(trim($colorrojo . " "))==1)
    {
        $colorrojo = "0" .$colorrojo;
    }
        
    $colorverde =dechex(rand( 0 , 255 ));//rand( 0 , 255 );
    if(strlen(trim($colorverde . " "))==1)
    {
        $colorverde = "0" .$colorverde;
    }    

   	return '#'.$colorrojo.$colorverde.$colorazul;
   
}	
				
?>