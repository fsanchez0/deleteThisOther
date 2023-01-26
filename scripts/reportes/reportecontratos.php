<?php
include_once('../general/conexion.php');
$inicio=$_GET["inicio"];
$final=$_GET["final"];
$user=$_GET["iduser"];
$sql=mysql_query("SELECT historia.idusuario,historia.idcontrato,historia.fechagenerado FROM historia,contrato WHERE historia.idusuario='$user' AND historia.fechagenerado BETWEEN  '$inicio' AND '$final' AND contrato.idcontrato=historia.idcontrato AND contrato.idusuario='$user' GROUP BY idcontrato");
$sql2=mysql_query("SELECT * FROM usuario WHERE idusuario='$user'");
while ($row=mysql_fetch_array($sql2)) {
	$nombre=$row["nombre"]." ".$row["nombre2"]." ".$row["apaterno"]." ".$row["amaterno"];
}
$total=mysql_num_rows($sql);
if ($total>0) {
$fechas=date("d-m-Y");
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=ReporteAltasContratos_$fechas.xls");
header("Pragma: no-cache");
header("Expires: 0");
echo "<center><h1>Reporte de Contratos dados de Alta</h1></center>";
echo "<table border=1> ";
echo "<tr style='background-color:#5C9CCF; font-color:white;'>";
echo  "<th>USUARIO</th> ";
echo  "<th>NUMERO DE CONTRATO</th> ";
echo  "<th>FECHA GENERACION</th> ";
echo "</tr> ";
while ($reg=mysql_fetch_array($sql)) {
	$sql3=mysql_query("SELECT * FROM historia WHERE idusuario='$user' AND fechagenerado<'$inicio' AND idcontrato='$reg[1]'");
	$totals=mysql_num_rows($sql3);
	if($totals==0){
	$totalss++;
	echo "<tr>";
	echo "<td>$nombre</td>";
	echo "<td>$reg[1]</td>";
	echo "<td>$reg[2]</td>";
	echo "</tr>";
}
}
echo "<tr><td colspan='2'><strong>Total de Contratos</strong></td><td><strong>$totalss</strong></td></tr>";
}
else{
}
echo "</table>";
?>