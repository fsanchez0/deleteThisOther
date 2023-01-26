<?php
	include("../general/conexion.php");
	include '../general/calendarioclass.php';
	$fechas = New Calendario;
	$hoy = date("Y") . "-" . date("m") . "-" . date("d");
	$fechagsistema =mktime(0,0,0,substr($hoy, 5, 2),substr($hoy, 8, 2),substr($hoy, 0, 4));
                   $periodo = $fechas->calculafecha($fechagsistema, 7, 3);
?>
<!DOCTYPE html>
<html>
	<head>
    	<!-- EN EL META VA EL CODIGO PARA REFRESCAR UNA PAGINA CADA CUERTO TIEPO-->
		<!--<meta http-equiv="refresh" content="30;url=Requisiciones.php" />-->
        <meta charset="utf-8">
		<link rel="shortcut icon" type="image/ico" href="http://www.datatables.net/media/images/favicon.ico" />
        <link rel="shortcut icon" type="image/x-ico" href="images/imss.jpg" />
		
		<title>Consulta de los Proximos Vencimientos</title>
		<style type="text/css" title="currentStyle">
			@import "css/demo_page.css";
			@import "css/demo_table.css";
			@import "css/estilo.css"; 		
		</style>
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$('#example').dataTable( {
					"sPaginationType": "full_numbers"
				} );
			} );

			function confirmar(q){
			c=confirm("¿Realmente desea eliminar el registro?");
			if(c){
			eliminar(q);
			}
			else
			{
				return false;}
			
			}
			
			function edocuenta(q){
				miPopup = window.open("../inmuebles/edocuenta.php?contrato="+q);
			}
		</script>
        
	</head>
	<body id="dt_example" class="example_alt_pagination">
    <center>
		<br>
        <h3>LISTA DE PROXIMOS VENCIMIENTOS</h3>
        </center>
        <div id="container">
        
        	<div id="demo">
            
            	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                    <thead>
                        <tr>
							<th class="dani">CONTRATO</th>
                            <th>INQUILINO</th>
                            <th>DIRECCION</th>
                            <th>ADEUDO A LA FECHA</th>
                            <th>FECHA INICIO</th>
                            <th>FECHA VENCIMIENTO</th>
                            <th>TELEFONO</th>
                            
                            <th>EDO CUENTAS</th>
                        </tr>
                    </thead>
                    <tbody>
                   <?php 
						//$registros=mysql_query("select departamentos.id_depto,departamentos.nombre,areas.nombre,departamentos.nombrejefe,departamentos.apepaterno,departamentos.apematerno,
						//departamentos.titulo from departamentos,areas where departamentos.area=areas.id_area ORDER BY departamentos.area",$conexion);
                   		$sql2="select c.idcontrato,nombre,nombre2, apaterno, amaterno,direccionf,fechainicio,fechatermino,tel, SUM(cantidad + iva) as csuma from historia h,contrato c, inquilino i where h.idcontrato = c.idcontrato and c.idinquilino=i.idinquilino and aplicado = false  and fechavencimiento between '$hoy' and '$periodo' group by idcontrato, apaterno";
					$operacion = mysql_query($sql2);
					while($row = mysql_fetch_array($operacion))
					{

						?>
						<tr class="gradeA">
                           <th >
                             <?php echo $row["idcontrato"]?>
                        </td>
                        <td ><?php echo utf8_encode($row["nombre"]." ".$row["nombre2"]." ".$row["apaterno"]." ".$row["amaterno"]);?></td>
                        <td ><?php echo utf8_encode($row["direccionf"]);?></td>
                        <td ><?php echo utf8_encode("$".$row["csuma"]);?></td>
                        <td ><?php echo utf8_encode($row["fechainicio"]);?></td>
                        <td ><?php echo utf8_encode($row["fechatermino"]);?></td>
                        <td ><?php echo utf8_encode($row["tel"]);?></td>
                        <td><img src="images/financial.png" width="35px" heigth="35px" onClick="edocuenta(<?php echo $row["idcontrato"]?>)" style="cursor:pointer;" title="Estado de Cuenta"></td>
                            </tr>

                      <?php

					}
						$registros=mysql_query("select * from contrato c, inquilino i where c.idinquilino = i.idinquilino and concluido <>true and fechatermino <= '$periodo'");
						
						while($reg=mysql_fetch_array($registros)){
						$sql=mysql_query("SELECT SUM(cantidad) AS totalsuma,SUM(iva) AS totaliva FROM historia WHERE idcontrato='$reg[0]' AND aplicado=0");
						while ($reg2=mysql_fetch_array($sql)) {
							$pendiente=$reg2["totalsuma"];
							$ivat=$reg2["totaliva"];
							$montototal=($pendiente+$ivat);
						}
					?>
						<tr class="gradeA" style="background-color:#FFB6C1;">
                           <th >
                             <?php echo $reg["idcontrato"]?>
                        </td>
                        <td ><?php echo utf8_encode($reg["nombre"]." ".$reg["nombre2"]." ".$reg["apaterno"]." ".$reg["amaterno"]);?></td>
                        <td ><?php echo utf8_encode($reg["direccionf"]);?></td>
                        <td ><?php echo utf8_encode("$".$montototal);?></td>
                        <td ><?php echo utf8_encode($reg["fechainicio"]);?></td>
                        <td ><?php echo utf8_encode($reg["fechatermino"]);?></td>
                        <td ><?php echo utf8_encode($reg["tel"]);?></td>
                        <td><img src="images/financial.png" width="35px" heigth="35px" onClick="edocuenta(<?php echo $reg["idcontrato"]?>)" style="cursor:pointer;" title="Estado de Cuenta"></td>
                            </tr>
                        <?php
                            
						}
                        ?>
					</tbody>
                    <tfoot>
                    </tfoot>
				</table>
			</div>
			<div class="spacer"></div>
		</div>
	</body>
</html>