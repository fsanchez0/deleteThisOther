<?php
include 'general/funcionesformato.php';
include 'general/sessionclase.php';
include 'general/ftpclass.php';
include_once('general/conexion.php');
include ("cfdi/cfdiclassn.php");
require('fpdf.php');




$id=@$_GET["id"]; //para el Id de la consulta que se requiere hacer
$filtro=@$_GET["filtro"]; //para la especificacion del tipo re recibo "categoria";

$cfd =  New cfdi32class;
$ftp= New ftpcfdi;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{


	echo "<table border='1'><tr><td>contrato</td><td>idhistoria</td><td>Fecha Nat. Pago</td><td>Inquilino</td><td>Inmueble</td><td>RFC</td><td>Total</td><td>IVA</td>";
	echo "<td>Interes</td><td>Acciones</td></tr>\n";

echo $sqlg="select idhistoria, idcobros, idcontrato, fechagenerado, fechanaturalpago, fechapago, cantidad, iva, aplicado, notas,txtok, archivopdf, pdfok, archivoxml, pdfnok, archivopdfn from historia where fechapago between '2012-02-01' and '2012-02-29' and notas = 'LIQUIDADO' order by idcontrato, fechanaturalpago, idcobros";
$operaciong = mysql_query($sqlg);
while ($rowg = mysql_fetch_array($operaciong))
{




	


	$sql="select * from historia where idhistoria=" . $rowg["idhistoria"];
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);


	$idc0=$row["idcontrato"];
	$fechanp=$row["fechanaturalpago"];
	$idcon=$row["idcobros"];
	$int=$row["interes"];


	//filtro = 1: fiscal  3:interes
	//obtengo que serie y folio de la factura que se va a usar
	//el dato del folio depende del tipo de cobro y ahi estael vinculo para poder obtener el folio
	if ($int==1)
	{
		$sql="select folios.idfolios as idf, serie, folios, secuencia, terceros from folios where idfolios = 1";// and idcategoria = $filtro";

	}
	else
	{
		$sql="select folios.idfolios as idf, serie, folios, secuencia, terceros from historia, cobros,tipocobro,folios where historia.idcobros=cobros.idcobros and cobros.idtipocobro = tipocobro.idtipocobro and tipocobro.idfolios=folios.idfolios and idhistoria = " . $rowg["idhistoria"];// and idcategoria = $filtro";
	}
	
	//$sql="select folios.idfolios as idf, serie, folios, secuencia, terceros from historia, cobros,tipocobro,folios where historia.idcobros=cobros.idcobros and cobros.idtipocobro = tipocobro.idtipocobro and tipocobro.idfolios=folios.idfolios and idhistoria = $id";// and idcategoria = $filtro";
	
	$operacion = mysql_query($sql);	
	$row = mysql_fetch_array($operacion);
	$idfolios= $row["idf"];
	$serie =$row["serie"];
	$folios = $row["folios"];
	$terceros = $row["terceros"];
	$secuencia = $row["secuencia"];
	
	$folio = ++$secuencia;
	
	$sql = "update folios set secuencia = $folio where idfolios = $idfolios";
	//$operacion = mysql_query($sql);	
	
	
	
	//ya tengo el folio y ya actualice la secuencia del folio en cuestion
	
	//$sql="select cont.idcontrato as idc, day(fechanaturalpago) as dia, month(fechanaturalpago) as mes, year(fechanaturalpago) as anio, fechanaturalpago, calle, numeroext, numeroint, i.colonia, delmun, i.cp, inc.nombre as nombrei, inc.nombre2 as nombre2i, inc.apaterno as apaternoi, inc.amaterno as amaternoi, rfc, estado, pais, d.nombre as nombred, d.nombre2 as nombre2d, d.apaterno as apaternod, d.amaterno as amaternod, rfcd, c.cantidad, c.iva,tipocobro,called,numeroextd,numeorintd,coloniad,cpd,delmund,estadod,paisd, metodopago  from historia h, inmueble i, inquilino inc, cobros c, duenio d, contrato cont, estado e, pais p, tipocobro tc, metodopago mp where h.idmetodopago = mp.idmetodopago and c.idtipocobro = tc.idtipocobro and h.idcontrato = cont.idcontrato and h.idcobros = c.idcobros and cont.idinquilino = inc.idinquilino and cont.idinmueble = i.idinmueble and i.idduenio = d.idduenio and i.idpais = p.idpais and i.idestado = e.idestado and idhistoria = $id";
	//$sql="select cont.idcontrato as idc, day(fechanaturalpago) as dia, month(fechanaturalpago) as mes, year(fechanaturalpago) as anio, fechanaturalpago, calle, numeroext, numeroint, i.colonia, delmun, i.cp, inc.nombre as nombrei, inc.nombre2 as nombre2i, inc.apaterno as apaternoi, inc.amaterno as amaternoi, rfc, estado, pais, c.cantidad, c.iva,tipocobro, metodopago from historia h, inmueble i, inquilino inc, cobros c, contrato cont, estado e, pais p, tipocobro tc, metodopago mp where h.idmetodopago=mp.idmetodopago and c.idtipocobro = tc.idtipocobro and h.idcontrato = cont.idcontrato and h.idcobros = c.idcobros and cont.idinquilino = inc.idinquilino and cont.idinmueble = i.idinmueble and i.idpais = p.idpais and i.idestado = e.idestado and idhistoria = $id";

$intf="";
if($int==1)
{
	$intf=" and h.interes=1 ";
}


	$filtro=	" and fechanaturalpago='$fechanp' and h.idcobros=$idcon and h.idcontrato=$idc0 $intf group by cont.idcontrato, fechanaturalpago, calle, numeroext, numeroint, i.colonia, delmun, i.cp, inc.nombre, inc.nombre2 , inc.apaterno , inc.amaterno , rfc, estado, pais ,tipocobro, metodopago, txtok, archivopdf, archivoxml, archivopdfn ";

	 $sql="select cont.idcontrato as idc, day(fechanaturalpago) as dia, month(fechanaturalpago) as mes, year(fechanaturalpago) as anio, fechanaturalpago, calle, numeroext, numeroint, i.colonia, delmun, i.cp, inc.nombre as nombrei, inc.nombre2 as nombre2i, inc.apaterno as apaternoi, inc.amaterno as amaternoi, rfc, estado, pais ,tipocobro, metodopago, 	SUM(h.cantidad) as csuma, SUM(h.iva) as iiva, txtok, archivopdf, archivoxml, archivopdfn  from historia h, inmueble i, inquilino inc, cobros c, contrato cont, estado e, pais p, tipocobro tc, metodopago mp where h.idmetodopago=mp.idmetodopago and c.idtipocobro = tc.idtipocobro and h.idcontrato = cont.idcontrato and h.idcobros = c.idcobros and cont.idinquilino = inc.idinquilino and cont.idinmueble = i.idinmueble and i.idpais = p.idpais and i.idestado = e.idestado $filtro";




	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	
		$edocuenta="window.open( 'inmuebles/edocuenta.php?contrato=" . $row["idc"] . "');";
		$accionbotonedo="<input type =\"button\" value=\"Estado de cuenta\" onClick=\"$edocuenta\"  />";
		
	$accionbotonfact="";
		$pdf ="";
		$xml ="";
		$pdfn ="";
	
	if($rowg["txtok"] == 0)
	{
	
		
		$edocuenta="window.open( 'reporte2.php?id=" . $rowg["idhistoria"] . "');";
		$accionbotonfact="<input type =\"button\" value=\"Facturar\" onClick=\"$edocuenta this.disabled=true\" />" . $row["txtok"];

	}
	else
	{
		
		if($rowg["xmlok"]==0)
		{
			$ftp->archivopdf=$rowg["archivotxt"];
			$ftp->archivoxml=$rowg["archivoxml"];
			$ftp->archivopdf=$rowg["archivopdf"];
			$ftp->archivoxml=$rowg["archivoxml"];
			echo $ac = $ftp->recoger($rowg["idhistoria"]);	
		

		}
		$pdf ="[<a href=\"general/descargarcfdi.php?f=" .  $rowg["archivopdf"] . "\"  target=\"_blank\" >" .  $rowg["archivopdf"] . "\n</a>]";
		$xml ="[<a href=\"general/descargarcfdi.php?f=" .  $rowg["archivoxml"] . "\"  target=\"_blank\" >" .  $rowg["archivoxml"] . "\n</a>]";
		$pdfn ="[<a href=\"general/descargarcfdi.php?f=" .  $rowg["archivopdfn"] . "\"  target=\"_blank\" >" .  $rowg["archivopdfn"] . "\n</a>]";
	
	}


	echo "<tr><td>" . $row["idc"] . "</td><td>" . $rowg["idhistoria"] . "</td><td>" . $row["fechanaturalpago"] . "</td><td>" . $row["nombrei"] . " " . $row["nombrei"] . " " . $row["apaternoi"] . "</td><td>" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . "</td><td>" . $row["rfc"] . "</td><td>" . $row["csuma"] . "</td><td>" . $row["iiva"] . "</td>";
	echo "<td>interes $int</td><td>$accionbotonedo $accionbotonfact $pdf $xml $pdfn</td></tr>\n";

	//echo $cfd->muestradatos($cfd->lista);
	//$cfd->cadena_factura($id,$cfd->lista);
	//$solicitud = utf8_decode($cfd->cadena_factura($id,$cfd->lista,$row,$folio,$serie,$terceros));
	//echo "<textarea cols = '60' rows='40'>$solicitud </textarea>";	
	//$aa = $ftp->creararchivo($solicitud,$serie,$folio,$id);
	//$ab = $ftp->enviar($id);
	//$ac = $ftp->recoger($id);
	
	//preparar los archivos para descarga
	
	
	
	//metodos para almacenado de factura en la base de datos
	
	
	
	
}
	echo "</table>";

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}


?>