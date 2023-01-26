<?php
include("../general/conexion.php");
ini_set("display_errors",0);
$fechas=date("d-m-Y");

 // echo $total;
  
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Servicios_$fechas.xls");
header("Pragma: no-cache");
header("Expires: 0");
echo "<center><h1>Reporte Servicios por Contratos</h1></center>";
echo "<table border=1> ";
echo "<tr style='background-color:#5C9CCF; font-color:white;'>";
echo  "<th>ID CONTRATO</th> ";
echo  "<th>PERIODO</th> ";
echo  "<th>CANTIDAD</th> ";
echo  "<th>ESTATUS</th> ";
echo  "<th>SERVICIO</th> ";

echo "</tr> ";
$sql=mysql_query("SELECT * FROM datoservicios ORDER BY idcontrato");
while ($reg=mysql_fetch_array($sql)) {
 echo "<tr>";
 echo "<td>".$reg[1]."</td>";
 echo "<td>".$reg[2]."</td>";
 echo "<td>".$reg[4]."</td>";
 echo "<td>".$reg[5]."</td>";
 echo "<td>".$reg[6]."</td>";
 echo "</tr>";
}
echo "</table>";

?>