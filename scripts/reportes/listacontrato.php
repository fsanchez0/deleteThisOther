<?php
include_once('../general/conexion.php');
$inicio=$_POST["inicio"];
$final=$_POST["final"];
$user=$_POST["idusuario"];

$sql=mysql_query("SELECT historia.idusuario,historia.idcontrato,historia.fechagenerado FROM historia,contrato WHERE historia.idusuario='$user' AND historia.fechagenerado BETWEEN  '$inicio' AND '$final' AND contrato.idcontrato=historia.idcontrato AND contrato.idusuario='$user' GROUP BY idcontrato");
$sql2=mysql_query("SELECT * FROM usuario WHERE idusuario='$user'");
while ($row=mysql_fetch_array($sql2)) {
	$nombre=$row["nombre"]." ".$row["nombre2"]." ".$row["apaterno"]." ".$row["amaterno"];
}
$total=mysql_num_rows($sql);
if ($total>0) {
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<table border="1">
	<tr>
		<th>Usuario</th>
		<th>Numero de Contrato</th>
		<th>Fecha de Generacion</th>
	</tr>
	<?php
	while ($reg=mysql_fetch_array($sql)) {
	$sql3=mysql_query("SELECT * FROM historia WHERE idusuario='$user' AND fechagenerado<'$inicio' AND idcontrato='$reg[1]'");
	$totals=mysql_num_rows($sql3);
	if($totals==0){
	$totalss++;
	?>
	<tr>
		<td><?php echo $nombre; ?></td>
		<td><?php echo $reg[1]; ?></td>
		<td><?php echo $reg[2]; ?></td>
	</tr>
	<?php
}
}
?>
<tr>
	<td colspan="2"><strong>Total de Contrato</strong></td>
	<td><strong><?php echo $totalss; ?></strong></td>
</tr>
</table>
</body>
</html>
<?php
}
else
{
	echo "Este usuario no ha dado de alta ningun contrato o cambie el rango de fechas";
}
?>