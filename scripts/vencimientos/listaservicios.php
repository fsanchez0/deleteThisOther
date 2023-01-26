<?php
	include("../general/conexion.php");
	include '../general/calendarioclass.php';
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
			function eliminar(q){
				window.location.href = "delete.php?codigo="+q; //página web a la que te redirecciona si confirmas la eliminación

			}
			function descarga(){
				window.open("serviciosexcel.php");
			}
		</script>
  
	</head>
	<body id="dt_example" class="example_alt_pagination">
    <center>
		<br>
        <h3>LISTA DE SERVICIOS POR CONTRATO</h3>
        <br><br>
     <input type="button" name="descargar" value="Descargar Excel" onclick="descarga()">
        </center>
        <div id="container">
        
        	<div id="demo">
            
            	<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                    <thead>
                        <tr>
							<th class="dani">ID</th>
                            <th>CONTRATO</th>
                            <th>PERIODO</th>
                            <th>CANTIDAD</th>
                            <th>ESTATUS</th>
                            <th>SERVICIO</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                   <?php 
						//$registros=mysql_query("select departamentos.id_depto,departamentos.nombre,areas.nombre,departamentos.nombrejefe,departamentos.apepaterno,departamentos.apematerno,
						//departamentos.titulo from departamentos,areas where departamentos.area=areas.id_area ORDER BY departamentos.area",$conexion);
                   		$sql2="SELECT * FROM datoservicios ORDER BY idcontrato";
					$operacion = mysql_query($sql2);
					while($row = mysql_fetch_array($operacion))
					{
						$services=$row["servicio"];
						if($services=='condominio'){
							$services='Pagos de Condominio';
						}
						$service=strtoupper($services);
						?>
						<tr class="gradeA">
                           <th >
                             <?php echo $row["iddato"]?>
                        </td>
                        <td ><?php echo utf8_encode($row["idcontrato"]);?></td>
                        <td ><?php echo utf8_encode($row["periodo"]);?></td>
                        <td ><?php echo utf8_encode("$".$row["cantidad"]);?></td>
                        <td ><?php echo utf8_encode($row["estatus"]);?></td>
                        <td ><?php echo utf8_encode($service);?></td>
                        <td><img src="images/recycle.png" width="35px" heigth="35px" onClick="confirmar(<?php echo $row["iddato"]?>)" style="cursor:pointer;" title="Eliminar Registro"></td>
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