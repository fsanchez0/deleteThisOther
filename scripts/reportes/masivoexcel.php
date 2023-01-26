<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclassd.php';
//Modulo

$id=@$_GET["contrato"];


$enviocorreo = New correo2;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
/*
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='privilegios.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}
*/

//identifico si es $tipo osea individual o multiple
//$id=substr($id,0,-1);
$ids = preg_split("/[|]/", $id);
//print_r ($ids);
//echo "lista de $id:(" . $ids[0] . ")" . count($ids);

$idcontratoss="";

foreach ($ids as $id)
{
$idcontratoss .= " " . $id .",";

}



//$idcontratoss="(". subtr($idcontratoss,0,strlen($idcontratoss)-1).")";

$idcontratoss="(" .  substr($idcontratoss, 0, strlen($idcontratoss)-3) . ")";


$hoy=date('Y') . "-" . date('m') . "-" . date('d');

	$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as inqtel,tipocobro, fechagenerado , historia.fechanaturalpago, historia.cantidad, aplicado, historia.interes, historia.iva as ivah, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, calle, numeroext, numeroint, inmueble.colonia, delmun, estado, pais, inmueble.cp, inmueble.tel as itel, inquilino.email as emaili, fiador.email as emailf, observaciones, DATEDIFF('$hoy',fechanaturalpago) as atraso FROM contrato, cobros, inquilino,tipocobro, historia, fiador, inmueble, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and historia.aplicado=false and contrato.idfiador=fiador.idfiador and contrato.idinmueble = inmueble.idinmueble and historia.fechanaturalpago <= '$hoy' and contrato.idcontrato in $idcontratoss and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais order by inquilino.idinquilino, fechanaturalpago, historia.idhistoria";


$fecha = date("d-m-Y");
$resultado= mysql_query($sql);
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Pendientes_$fecha.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border=1> ";
echo "<tr style='background-color:#5C9CCF; font-color:white;'> ";
echo     "<th>Contrato</th> ";
echo 	"<th>Inquilino</th> ";
echo 	"<th>Telefono</th> ";
echo 	"<th>Correo Electronico</th> ";
echo 	"<th>Inmueble</th> ";
echo 	utf8_decode("<th>Delagación y/o Municipio</th> ");
echo 	"<th>Codigo Postal</th> ";
echo 	"<th>Telefono</th> ";
echo 	"<th>Obligado Solidario</th> ";
echo 	"<th>Telefono</th> ";
echo 	"<th>Correo Electronico</th> ";
echo 	"<th>Fecha Natural Pago</th> ";
echo 	"<th>Concepto</th> ";
echo 	"<th>Cantidad</th> ";
echo "</tr> ";

while($row = mysql_fetch_array($resultado)){	

	$inquilinos = utf8_decode($row["nombre"]." ".$row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"]);
	$telinquilino=$row["inqtel"];
	$emailinquilino=$row["emaili"];
	$inmuebles = utf8_decode($row["calle"] . " No." . $row["numeroext"] . " Int." . $row["numeroint"] . " Col." . $row["colonia"]);
	$delagacion=utf8_decode($row["delmun"]);
	$codigop=$row["cp"];
	$telinmueble=$row["itel"];
	$obligados= utf8_decode($row["fnombre"] . " " . $row["fnombre2"] . " " . $row["fapaterno"] . " " . $row["famaterno"]);
	$telobligado=$row["ftel"];
	$emailobligado=$row["emailf"];
	$fechanatpago=$row["fechanaturalpago"];
	$conceptos=utf8_decode($row["tipocobro"]);
	$cantida=$row["cantidad"];
	$contratoid=$row["elidcontrato"];
	$ivah=$row["ivah"];
	$total=($cantida+$ivah);

	$contador++;
	if ($contador==1) {
		$color="style=background-color:#BCD8F0;";
	}
	if ($contador==2) {
		$color="style=background-color:#DEEBF3;";
		$contador=0;
	}
	echo "<tr $color> ";
	echo 	"<td>".$contratoid."</td> "; 
	echo 	"<td>".$inquilinos."</td> "; 
	echo 	"<td>".$telinquilino."</td> "; 
	echo 	"<td>".$emailinquilino."</td> "; 
	echo 	"<td>".$inmuebles."</td> "; 
	echo 	"<td>".$delagacion."</td> ";
	echo 	"<td>".$codigop."</td> ";
	echo 	"<td>".$telinmueble."</td> ";
	echo 	"<td>".$obligados."</td> ";
	echo 	"<td>".$telobligado."</td> ";
	echo 	"<td>".$emailobligado."</td> ";
	echo 	"<td>".$fechanatpago."</td> "; 
	echo 	"<td>".$conceptos."</td> "; 
	echo 	"<td>$".$total."</td> "; 
	echo "</tr> ";

}
echo "</table> "; 

}

else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>
