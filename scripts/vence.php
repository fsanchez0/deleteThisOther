
<?php

include 'general/sessionclase.php';
include 'general/calendarioclass.php';
include_once('general/conexion.php');
include 'general/funcionesformato.php';

$fechas = New Calendario;

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql = "select * from privilegio p, submodulo s where idusuario=" . $misesion->usuario . " and p.idsubmodulo=s.idsubmodulo and s.archivo='cobro.php'";
	$operacion = mysql_query($sql);
if(mysql_num_rows($operacion)>0)
{
	$sql="SELECT idcontrato,nombre,apaterno,fechatermino FROM contrato,inquilino 
              WHERE concluido<> 1 AND fechatermino BETWEEN '2015-09-12' AND '2015-11-12' 
	      AND contrato.idinquilino = inquilino.idinquilino ORDER BY fechatermino ASC";
	
	$res=mysql_query($sql);
		
	while($row = mysql_fetch_array($res))
	{
		echo $row['idcontrato']."\t".$row['nombre']."\t".$row['apaterno']."\t".$row['fechatermino']."\n<br>";

	}


	//$sql="select * from contrato c, inquilino i where c.idinquilino = i.idinquilino and fechatermino between '$hoy' and '$periodo' not in (select idinquilino from contrato)";
	//$sql="select C.idcontrato, I.idinquilino, concat(I.nombre,"",I.apaterno,"",I.amaterno)nombreinquilino,INM.idinmueble,concat(INM.calle, INM.numeroext,INM.numeroint, INM.colonia) direccioninmueble,C.fechatermino FROM 'contrato' C left join inquilino I on I.idinquilino=C.idinquilino lef join inmueble INM on INM.idinmueble=C.idinmueble where C.fechatermino <= DATE_ADD (NOW(), INTERVAL 2 month) and C.fechatermino>=NOW() ORDER BY 'fechatermino' DESC";
	//$sql="select * from contrato c, inquilino i where c.idinquilino = i.idinquilino and concluido <>true and activo=true and fechatermino <= '$periodo'";
	
$sql="select * from contrato where idcontrato  not in (1)";
$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

	//	$lista .= "<tr><td><b><a style=\"font-size:10;color:red;cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/inmuebles/edocuenta.php','contenido', 'contrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " (" . CambiaAcentosaHTML($row["apaterno"]) . ")" . " " . $row["fechatermino"] . "</a><b></td></tr> ";
		//$lista .= "<tr><td><a style=\"font-size:10;\" onClick=\"jabascript:cargarSeccion('scripts/inmuebles/datoscontrato.php','contenido', 'idcontrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " " . $row["fechatermino"] . "</a></td></tr> ";
          print_r($row);      

	}
}
else
{
	echo "";
}

}

?>
