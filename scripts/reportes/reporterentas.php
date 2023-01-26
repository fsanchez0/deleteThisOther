<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$inicio=$_GET["inicio"];
$final=$_GET["final"];
$descargar=@$_GET["descargar"];
$generar=@$_GET["generar"];

$fechas=date("d-m-Y");

$misesion = new sessiones;
if($misesion->verifica_sesion()=="no")
{
  echo "A&uacute;n no se ha firmado con el servidor";
  exit;
}

$sql="select * from submodulo where archivo ='reporterentas.php'";
$operacion = mysqli_query($connection,$sql);
while($row = mysqli_fetch_array($operacion))
{
  $dirscript= $row['ruta'] . "/" . $row['archivo'];
  $priv = $misesion->privilegios_secion($row['idsubmodulo']);
  $priv=split("\*",$priv);
}

if ($priv[0]!='1')
{
   $txtver = "";
   echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
   exit();
}

$reporte="";
$titulo="";
$reporteFinal='';

$totalpag1General=0;
$totalpag2General=0;
$totalpag3General=0;

$totalpagAnterior=0;
$totalpagActual=0;
$totalpagPosterior=0;



if($descargar==1 || $generar==1){

  //Mes Actual
  $sql="SELECT contrato.idcontrato,cobros.iva as ivac,cobros.cantidad as cantidadc,inquilino.idinquilino, inquilino.nombre,inquilino.nombre2,inquilino.apaterno,inquilino.amaterno,tipocobro.tipocobro,tipocobro.idtipocobro,historia.idhistoria,historia.fechagenerado, historia.fechanaturalpago,historia.cantidad as cantidadh,historia.iva as ivah, historia.parcialde,historia.aplicado,historia.condonado,historia.interes, inmueble.calle,inmueble.numeroext,inmueble.numeroint,inmueble.colonia,inmueble.delmun,inmueble.cp,estado.estado,duenio.nombre as nombred,duenio.nombre2 as nombre2d,duenio.apaterno as apaternod,duenio.amaterno as amaternod FROM contrato,cobros,inquilino,tipocobro,historia,inmueble,estado,duenio,duenioinmueble WHERE historia.idcontrato=contrato.idcontrato AND historia.idcobros=cobros.idcobros AND tipocobro.idtipocobro=cobros.idtipocobro AND inquilino.idinquilino=contrato.idinquilino AND contrato.idinmueble=inmueble.idinmueble AND duenioinmueble.idinmueble=inmueble.idinmueble AND duenio.idduenio=duenioinmueble.idduenio AND inmueble.idestado=estado.idestado AND (contrato.litigio=0 OR contrato.litigio IS NULL) AND (historia.parcialde IS NULL OR historia.parcialde=historia.idhistoria) AND historia.cantidad>0 AND (historia.condonado=0 OR historia.condonado IS NULL) AND historia.fechanaturalpago BETWEEN '$inicio 00:00:00' AND '$final 23:59:59' ORDER BY historia.idcontrato, historia.idhistoria";
  $ejecuta=mysqli_query($connection,$sql);
  $total=mysqli_num_rows($ejecuta);

  $titulo = "<h3>Exigibilidad del $inicio al $final</h3><br>";
  $reporte = "<h3>Exigibilidad Mes Actual</h3>";
  $reporte .= " <table border=1> 
   <tr style='background-color:#9C0;'>
    <th colspan='6'></th>
    <th colspan='3' aling='center'>EXIGIBLE</th>
    <th colspan='4' align='center'>COBRADO EN MES</th>
    <th colspan='5' align='center'>COBRADO TOTAL</th>
   </tr>
   <tr style='background-color:#9C0;'> 
    <th>DUEÑO</th> 
    <th>CONTRATO</th> 
    <th>INQUILINO</th> 
    <th>INMUEBLE</th>
    <th>CONCEPTO</th> 
    <th>STATUS</th> 
    <th>IMPORTE</th> 
    <th>IVA</th> 
    <th>TOTAL</th> 
    <th>IMPORTE</th> 
    <th>IVA</th>
    <th>TOTAL</th>
    <th>PORCENTAJE</th>
    <th>IMPORTE</th> 
    <th>IVA</th>
    <th>TOTAL</th>
    <th>PORCENTAJE</th>
    <th>FECHA PAGO</th>
   </tr> ";

  $totalcob1=0;
  $totalcob2=0;
  $totalcob3=0;

  $totalpag1=0;
  $totalpag2=0;
  $totalpag3=0;

  $totalpagT1=0;
  $totalpagT2=0;
  $totalpagT3=0;

  $tabla="";

  while ($row=mysqli_fetch_array($ejecuta)) {

    $inquilino=$row["nombre"]." ".$row["nombre2"]." ".$row["apaterno"]." ".$row["amaterno"];
    $duenio=$row["nombred"]." ".$row["nombre2d"]." ".$row["apaternod"]." ".$row["amaternod"];
    $direccion=$row["calle"]." ".$row["numeroext"]." ".$row["numeroint"]." ".$row["colonia"].$row["cp"]." ".$row["delmun"];
        
    if($row["interes"]==1){

      $sqlInteres="SELECT SUM(iva) as ivaI, SUM(cantidad) as cantidadI FROM historia WHERE (parcialde=".$row["idhistoria"]." OR idhistoria=".$row["idhistoria"].") GROUP BY idcobros";
      $resultadoInteres=mysqli_query($connection,$sqlInteres);
      $rowInteres=mysqli_fetch_array($resultadoInteres);

      if($row["aplicado"]==1){
        $cantidadCobro = ($rowInteres["cantidadI"] - $rowInteres["ivaI"]);
        $ivaCobro = $rowInteres["ivaI"];
        $totalCobro = $cantidadCobro + $ivaCobro;    
      }else{
        $cantidadCobro = $rowInteres["cantidadI"];
        $ivaCobro = $rowInteres["ivaI"];
        $totalCobro = $cantidadCobro + $ivaCobro;    
      }
      
      $txtInteres=" INTERES DE ";
    }else{
      $cantidadCobro = $row["cantidadc"];
      $ivaCobro = $row["ivac"];
      $totalCobro = $cantidadCobro + $ivaCobro;
      $txtInteres="";
    }

    $concepto = $txtInteres.$row["tipocobro"]." correcpondiente al ".$row["fechanaturalpago"];  

    $sql0="SELECT SUM(iva) as ivaP, SUM(cantidad) as cantidadP FROM historia WHERE fechapago BETWEEN '$inicio 00:00:00' AND '$final 23:59:59' AND aplicado=1 AND (condonado=0 OR condonado IS NULL) AND (parcialde=".$row["idhistoria"]." OR idhistoria=".$row["idhistoria"].") GROUP BY idcobros";
    $resultado=mysqli_query($connection,$sql0);
    $rowParcial=mysqli_fetch_array($resultado); 

    if(mysqli_num_rows($resultado)>0){
      if($rowParcial["ivaP"]>0){
        $porIva=0.16;
      }else{
        $porIva=0;
      }

      $cantidadPagado = ($rowParcial["cantidadP"] / (1 + $porIva));
      $ivaPagado = $cantidadPagado * $porIva;
      $totalPagado = $cantidadPagado + $ivaPagado;

    }else{ 
      $cantidadPagado =0;
      $ivaPagado = 0;
      $totalPagado = 0;
    }

    $sqlTot="SELECT SUM(iva) as ivaP, SUM(cantidad) as cantidadP, fechapago FROM historia WHERE aplicado=1 AND (condonado=0 OR condonado IS NULL) AND (parcialde=".$row["idhistoria"]." OR idhistoria=".$row["idhistoria"].") GROUP BY idcobros";
    $resultadoTot=mysqli_query($connection,$sqlTot);
    $rowTotal=mysqli_fetch_array($resultadoTot); 

    if(mysqli_num_rows($resultadoTot)>0){
      if($rowTotal["ivaP"]>0){
        $porIvaTot=0.16;
      }else{
        $porIvaTot=0;
      }

      $cantidadTot = ($rowTotal["cantidadP"] / (1 + $porIvaTot));
      $ivaTot = $cantidadTot * $porIvaTot;
      $totalPagadoTot = $cantidadTot + $ivaTot;
      $fechapago=$rowTotal["fechapago"];

    }else{ 
      $cantidadTot =0;
      $ivaTot = 0;
      $totalPagadoTot = 0;
      $fechapago='';
    }

    if($totalCobro <= $totalPagadoTot){
      $status = "LIQUIDADO";
    }else{
      $status = "PENDIENTE";
    }

    $porcentaje = ((100 / $totalCobro)* $totalPagado);
    $porcentajeTot = ((100 / $totalCobro)* $totalPagadoTot);

    $contador++;
    if($contador==1){
       $color="style='background-color:#FFFFFF;'";
    }else{
      $color="style='background-color:#CCCCCC;'";
      $contador=0;
    }

    $tabla .= "<tr $color><td>".$duenio."</td><td>".$row["idcontrato"]."</td><td>".$inquilino."</td><td>".$direccion."</td><td>".$concepto."</td><td>".$status."</td><td style='background-color:#9e5210;'>$".number_format($cantidadCobro,2,".",",")."</td><td style='background-color:#9e5210;'>$".number_format($ivaCobro,2,".",",")."</td><td style='background-color:#9e5210;'>$".number_format($totalCobro,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($cantidadPagado,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($ivaPagado,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($totalPagado,2,".",",")."</td><td style='background-color:#3f9822;'>".number_format($porcentaje,2,".","")."%</td><td>$".number_format($cantidadTot,2,".",",")."</td><td>$".number_format($ivaTot,2,".",",")."</td><td>$".number_format($totalPagadoTot,2,".",",")."</td><td>".number_format($porcentajeTot,2,".","")."%</td><td>".$fechapago."</td></tr>";

    $totalcob1 += $cantidadCobro;
    $totalcob2 += $ivaCobro;
    $totalcob3 += $totalCobro;

    $totalpag1 += $cantidadPagado;
    $totalpag2 += $ivaPagado;
    $totalpag3 += $totalPagado;

    $totalpagT1 += $cantidadTot;
    $totalpagT2 += $ivaTot;
    $totalpagT3 += $totalPagadoTot;

  }

  $porcentajeTotal = ((100 / $totalcob3)* $totalpag3);
  $porcentajeTotalPagos = ((100 / $totalcob3)* $totalpagT3);

  $reporte .= $tabla;

  $reporte .= "<tr style='background-color:#9C0;'>
      <td colspan='6' align='left'><strong>MONTOS TOTALES</strong></td>
      <td><strong>$". number_format($totalcob1,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalcob2,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalcob3,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpag1,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpag2,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpag3,2,".",",")."</strong></td> 
      <td><strong>". number_format($porcentajeTotal,2,".",",")."%</strong></td>
      <td><strong>$". number_format($totalpagT1,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpagT2,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpagT3,2,".",",")."</strong></td> 
      <td><strong>". number_format($porcentajeTotalPagos,2,".",",")."%</strong></td> 
      </tr>
    </table>";

  $totalpagActual = $totalpag3;

  $totalpag1General += $totalpag1;
  $totalpag2General += $totalpag2;
  $totalpag3General += $totalpag3;

  $reporteFinal .= $reporte . "<br>";


  //Meses Anteriores 

  $sql="SELECT contrato.idcontrato,cobros.iva as ivac,cobros.cantidad as cantidadc,inquilino.idinquilino, inquilino.nombre,inquilino.nombre2,inquilino.apaterno,inquilino.amaterno,tipocobro.tipocobro,tipocobro.idtipocobro,historia.idhistoria,historia.fechagenerado, historia.fechanaturalpago,historia.cantidad as cantidadh,historia.iva as ivah, historia.parcialde,historia.aplicado,historia.condonado,historia.interes, inmueble.calle,inmueble.numeroext,inmueble.numeroint,inmueble.colonia,inmueble.delmun,inmueble.cp,estado.estado,duenio.nombre as nombred,duenio.nombre2 as nombre2d,duenio.apaterno as apaternod,duenio.amaterno as amaternod FROM contrato,cobros,inquilino,tipocobro,historia,inmueble,estado,duenio,duenioinmueble WHERE historia.idcontrato=contrato.idcontrato AND historia.idcobros=cobros.idcobros AND tipocobro.idtipocobro=cobros.idtipocobro AND inquilino.idinquilino=contrato.idinquilino AND contrato.idinmueble=inmueble.idinmueble AND duenioinmueble.idinmueble=inmueble.idinmueble AND duenio.idduenio=duenioinmueble.idduenio AND inmueble.idestado=estado.idestado AND historia.cantidad>0 AND (historia.condonado=0 OR historia.condonado IS NULL) AND historia.aplicado=1 AND historia.fechapago BETWEEN '$inicio 00:00:00' AND '$final 23:59:59' AND historia.fechanaturalpago<'$inicio 00:00:00' ORDER BY historia.idcontrato, historia.idhistoria";
  $ejecuta=mysqli_query($connection,$sql);
  $total=mysqli_num_rows($ejecuta);

  $reporte = "<h3>Exigibilidad Meses Pasados</h3>";
  $reporte .= " <table border=1> 
   <tr style='background-color:#9C0;'>
    <th colspan='6'></th>
    <th colspan='3' aling='center'>COBRANZA EXIGIBLE</th>
    <th colspan='3' align='center'>COBRADO</th>
    <th></th>
   </tr>
   <tr style='background-color:#9C0;'> 
    <th>DUEÑO</th> 
    <th>CONTRATO</th> 
    <th>INQUILINO</th> 
    <th>INMUEBLE</th>
    <th>CONCEPTO</th> 
    <th>STATUS</th> 
    <th>IMPORTE</th> 
    <th>IVA</th> 
    <th>TOTAL</th> 
    <th>IMPORTE</th> 
    <th>IVA</th>
    <th>TOTAL</th>
    <th>PORCENTAJE</th>
   </tr> ";

  $totalcob1=0;
  $totalcob2=0;
  $totalcob3=0;

  $totalpag1=0;
  $totalpag2=0;
  $totalpag3=0;

  $tabla="";

  while ($row=mysqli_fetch_array($ejecuta)) {

    $inquilino=$row["nombre"]." ".$row["nombre2"]." ".$row["apaterno"]." ".$row["amaterno"];
    $duenio=$row["nombred"]." ".$row["nombre2d"]." ".$row["apaternod"]." ".$row["amaternod"];
    $direccion=$row["calle"]." ".$row["numeroext"]." ".$row["numeroint"]." ".$row["colonia"].$row["cp"]." ".$row["delmun"];
    
    $sql0="SELECT SUM(iva) as ivaP, SUM(cantidad) as cantidadP FROM historia WHERE aplicado=1 AND (condonado=0 OR condonado IS NULL) AND parcialde=".$row["parcialde"]." GROUP BY idcobros";
    $resultado=mysqli_query($connection,$sql0);
    $rowParcial=mysqli_fetch_array($resultado);
    
    if($row["interes"]==1){

      $sqlInteres="SELECT SUM(iva) as ivaI, SUM(cantidad) as cantidadI FROM historia WHERE parcialde=".$row["parcialde"]." GROUP BY idcobros";
      $resultadoInteres=mysqli_query($connection,$sqlInteres);
      $rowInteres=mysqli_fetch_array($resultadoInteres);

      if($row["aplicado"]==1){
        $cantidadCobro = ($rowInteres["cantidadI"] - $rowInteres["ivaI"]);
        $ivaCobro = $rowInteres["ivaI"];
        $totalCobro = $cantidadCobro + $ivaCobro;    
      }else{
        $cantidadCobro = $rowInteres["cantidadI"];
        $ivaCobro = $rowInteres["ivaI"];
        $totalCobro = $cantidadCobro + $ivaCobro;    
      }
      
      $txtInteres="INTERES DE ";
    }else{
      $cantidadCobro = $row["cantidadc"];
      $ivaCobro = $row["ivac"];
      $totalCobro = $cantidadCobro + $ivaCobro;
      $txtInteres="";
    }

    $concepto = $txtInteres.$row["tipocobro"]." correcpondiente al ".$row["fechanaturalpago"];   

    if(mysqli_num_rows($resultado)>0){
      if($rowParcial["ivaP"]>0){
        $porIva=0.16;
      }else{
        $porIva=0;
      }

      $cantidadPagado = ($row["cantidadh"] / (1 + $porIva));
      $ivaPagado = $cantidadPagado * $porIva;
      $totalPagado = $cantidadPagado + $ivaPagado;

    }else{ 
      $cantidadPagado =0;
      $ivaPagado = 0;
      $totalPagado = 0;
    }

    if($totalCobro <= $totalPagado){
      $status = "LIQUIDADO";
    }else{
      $status = "PENDIENTE";
    }

    $porcentaje = ((100 / $totalCobro)* $totalPagado);

    $contador++;
    if($contador==1){
       $color="style='background-color:#FFFFFF;'";
    }else{
      $color="style='background-color:#CCCCCC;'";
      $contador=0;
    }

    $tabla .= "<tr $color><td>".$duenio."</td><td>".$row["idcontrato"]."</td><td>".$inquilino."</td><td>".$direccion."</td><td>".$concepto."</td><td>".$status."</td><td style='background-color:#9e5210;'>$".number_format($cantidadCobro,2,".",",")."</td><td style='background-color:#9e5210;'>$".number_format($ivaCobro,2,".",",")."</td><td style='background-color:#9e5210;'>$".number_format($totalCobro,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($cantidadPagado,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($ivaPagado,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($totalPagado,2,".",",")."</td><td>".number_format($porcentaje,2,".","")."%</td></tr>";

    $totalcob1 += $cantidadCobro;
    $totalcob2 += $ivaCobro;
    $totalcob3 += $totalCobro;

    $totalpag1 += $cantidadPagado;
    $totalpag2 += $ivaPagado;
    $totalpag3 += $totalPagado;

  }

  $porcentajeTotal = ((100 / $totalcob3)* $totalpag3);

  $reporte .= $tabla;

  $reporte .= "<tr style='background-color:#9C0;'>
      <td colspan='6' align='left'><strong>MONTOS TOTALES</strong></td>
      <td><strong>$". number_format($totalcob1,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalcob2,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalcob3,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpag1,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpag2,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpag3,2,".",",")."</strong></td> 
      <td><strong>". number_format($porcentajeTotal,2,".",",")."%</strong></td> 
      </tr>
    </table>";

  $totalpagAnterior = $totalpag3;

  $totalpag1General += $totalpag1;
  $totalpag2General += $totalpag2;
  $totalpag3General += $totalpag3;

  $reporteFinal .= $reporte . "<br>";

  
   //Meses Posteriores 

  $sql="SELECT contrato.idcontrato,cobros.iva as ivac,cobros.cantidad as cantidadc,inquilino.idinquilino, inquilino.nombre,inquilino.nombre2,inquilino.apaterno,inquilino.amaterno,tipocobro.tipocobro,tipocobro.idtipocobro,historia.idhistoria,historia.fechagenerado, historia.fechanaturalpago,historia.cantidad as cantidadh,historia.iva as ivah, historia.parcialde,historia.aplicado,historia.condonado,historia.interes, inmueble.calle,inmueble.numeroext,inmueble.numeroint,inmueble.colonia,inmueble.delmun,inmueble.cp,estado.estado,duenio.nombre as nombred,duenio.nombre2 as nombre2d,duenio.apaterno as apaternod,duenio.amaterno as amaternod FROM contrato,cobros,inquilino,tipocobro,historia,inmueble,estado,duenio,duenioinmueble WHERE historia.idcontrato=contrato.idcontrato AND historia.idcobros=cobros.idcobros AND tipocobro.idtipocobro=cobros.idtipocobro AND inquilino.idinquilino=contrato.idinquilino AND contrato.idinmueble=inmueble.idinmueble AND duenioinmueble.idinmueble=inmueble.idinmueble AND duenio.idduenio=duenioinmueble.idduenio AND inmueble.idestado=estado.idestado AND historia.cantidad>0 AND (historia.condonado=0 OR historia.condonado IS NULL) AND historia.aplicado=1 AND historia.fechapago BETWEEN '$inicio 00:00:00' AND '$final 23:59:59' AND historia.fechanaturalpago>'$final 23:59:59' ORDER BY historia.idcontrato, historia.idhistoria";
  $ejecuta=mysqli_query($connection,$sql);
  $total=mysqli_num_rows($ejecuta);

  $reporte = "<h3>Exigibilidad Meses Posteriores</h3>";
  $reporte .= " <table border=1> 
   <tr style='background-color:#9C0;'>
    <th colspan='6'></th>
    <th colspan='3' aling='center'>COBRANZA EXIGIBLE</th>
    <th colspan='3' align='center'>COBRADO</th>
    <th></th>
   </tr>
   <tr style='background-color:#9C0;'> 
    <th>DUEÑO</th> 
    <th>CONTRATO</th> 
    <th>INQUILINO</th> 
    <th>INMUEBLE</th>
    <th>CONCEPTO</th> 
    <th>STATUS</th> 
    <th>IMPORTE</th> 
    <th>IVA</th> 
    <th>TOTAL</th> 
    <th>IMPORTE</th> 
    <th>IVA</th>
    <th>TOTAL</th>
    <th>PORCENTAJE</th>
   </tr> ";

  $totalcob1=0;
  $totalcob2=0;
  $totalcob3=0;

  $totalpag1=0;
  $totalpag2=0;
  $totalpag3=0;

  $tabla="";

  while ($row=mysqli_fetch_array($ejecuta)) {

    $inquilino=$row["nombre"]." ".$row["nombre2"]." ".$row["apaterno"]." ".$row["amaterno"];
    $duenio=$row["nombred"]." ".$row["nombre2d"]." ".$row["apaternod"]." ".$row["amaternod"];
    $direccion=$row["calle"]." ".$row["numeroext"]." ".$row["numeroint"]." ".$row["colonia"].$row["cp"]." ".$row["delmun"];
    
    $sql0="SELECT SUM(iva) as ivaP, SUM(cantidad) as cantidadP FROM historia WHERE aplicado=1 AND (condonado=0 OR condonado IS NULL) AND parcialde=".$row["parcialde"]." GROUP BY idcobros";
    $resultado=mysqli_query($connection,$sql0);
    $rowParcial=mysqli_fetch_array($resultado);
    
    if($row["interes"]==1){

      $sqlInteres="SELECT SUM(iva) as ivaI, SUM(cantidad) as cantidadI FROM historia WHERE parcialde=".$row["parcialde"]." GROUP BY idcobros";
      $resultadoInteres=mysqli_query($connection,$sqlInteres);
      $rowInteres=mysqli_fetch_array($resultadoInteres);

      if($row["aplicado"]==1){
        $cantidadCobro = ($rowInteres["cantidadI"] - $rowInteres["ivaI"]);
        $ivaCobro = $rowInteres["ivaI"];
        $totalCobro = $cantidadCobro + $ivaCobro;    
      }else{
        $cantidadCobro = $rowInteres["cantidadI"];
        $ivaCobro = $rowInteres["ivaI"];
        $totalCobro = $cantidadCobro + $ivaCobro;    
      }
      
      $txtInteres="INTERES DE ";
    }else{
      $cantidadCobro = $row["cantidadc"];
      $ivaCobro = $row["ivac"];
      $totalCobro = $cantidadCobro + $ivaCobro;
      $txtInteres="";
    }

    $concepto = $txtInteres.$row["tipocobro"]." correcpondiente al ".$row["fechanaturalpago"];   

    if(mysqli_num_rows($resultado)>0){
      if($rowParcial["ivaP"]>0){
        $porIva=0.16;
      }else{
        $porIva=0;
      }

      $cantidadPagado = ($row["cantidadh"] / (1 + $porIva));
      $ivaPagado = $cantidadPagado * $porIva;
      $totalPagado = $cantidadPagado + $ivaPagado;

    }else{ 
      $cantidadPagado =0;
      $ivaPagado = 0;
      $totalPagado = 0;
    }

    if($totalCobro <= $totalPagado){
      $status = "LIQUIDADO";
    }else{
      $status = "PENDIENTE";
    }

    $porcentaje = ((100 / $totalCobro)* $totalPagado);

    $contador++;
    if($contador==1){
       $color="style='background-color:#FFFFFF;'";
    }else{
      $color="style='background-color:#CCCCCC;'";
      $contador=0;
    }

    $tabla .= "<tr $color><td>".$duenio."</td><td>".$row["idcontrato"]."</td><td>".$inquilino."</td><td>".$direccion."</td><td>".$concepto."</td><td>".$status."</td><td style='background-color:#9e5210;'>$".number_format($cantidadCobro,2,".",",")."</td><td style='background-color:#9e5210;'>$".number_format($ivaCobro,2,".",",")."</td><td style='background-color:#9e5210;'>$".number_format($totalCobro,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($cantidadPagado,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($ivaPagado,2,".",",")."</td><td style='background-color:#3f9822;'>$".number_format($totalPagado,2,".",",")."</td><td>".number_format($porcentaje,2,".","")."%</td></tr>";

    $totalcob1 += $cantidadCobro;
    $totalcob2 += $ivaCobro;
    $totalcob3 += $totalCobro;

    $totalpag1 += $cantidadPagado;
    $totalpag2 += $ivaPagado;
    $totalpag3 += $totalPagado;

  }

  $porcentajeTotal = ((100 / $totalcob3)* $totalpag3);

  $reporte .= $tabla;

  $reporte .= "<tr style='background-color:#9C0;'>
      <td colspan='6' align='left'><strong>MONTOS TOTALES</strong></td>
      <td><strong>$". number_format($totalcob1,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalcob2,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalcob3,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpag1,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpag2,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpag3,2,".",",")."</strong></td> 
      <td><strong>". number_format($porcentajeTotal,2,".",",")."%</strong></td> 
      </tr>
    </table>";

  $totalpagPosterior = $totalpag3;

  $totalpag1General += $totalpag1;
  $totalpag2General += $totalpag2;
  $totalpag3General += $totalpag3;

  $reporteFinal .= $reporte . "<br>";

  $reporteFinal .= "<table border=1> 
  <tr style='background-color:#9C0;'>
      <td colspan='3' align='left'><strong>MONTOS TOTALES</strong></td>
      <td>MESES ANTERIORES</td> 
      <td><strong>$". number_format($totalpagAnterior,2,".",",")."</strong></td> 
      <td>MES ACTUAL</td> 
      <td><strong>$". number_format($totalpagActual,2,".",",")."</strong></td> 
      <td>MESES POSTERIORES</td> 
      <td><strong>$". number_format($totalpagPosterior,2,".",",")."</strong></td> 
      <td><strong>TOTAL PAGADO</strong></td>
      <td><strong>$". number_format($totalpag1General,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpag2General,2,".",",")."</strong></td> 
      <td><strong>$". number_format($totalpag3General,2,".",",")."</strong></td> 
      </tr>
    </table>";

  if($descargar==1){
    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=ReporteXExigibilidad_$fechas.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
  }

  echo "<center>";
  echo $titulo;
  echo "<br>";
  echo $reporteFinal;
  echo "</center>";


}else{

  $html = <<<formulario

  <center>
  <h1>Reporte por Exigibilidad</h1>
  <form >
  <table border="1">
  <tr>
    <td>Fecha Inicial</td><td> <input type="date" name="fechai" value='$inicio'></td>
  </tr>
  <tr>
    <td>Fecha Final</td><td> <input type="date" name ="fechaf" value='$final'></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input type="button" value="Limpiar" onClick="fechai.value='';fechaf.value=''">
      <input type="button" value="Generar" onClick="cargarSeccion('$dirscript','reportediv','inicio=' + fechai.value + '&final='+ fechaf.value + '&descargar=0&generar=1');document.getElementById('reportediv').innerHTML='<h1>Procesando...</h1>';">
      <input type="button" value="Descargar" onClick="window.open('$dirscript?inicio=' + fechai.value + '&final='+ fechaf.value + '&descargar=1')">
    </td>
  </tr>
  </table>
  </form>
  <div id="reportediv">
  </div>
  </center>
formulario;

  echo $html;  

}

?>