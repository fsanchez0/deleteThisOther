<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include ("cfdiclassn.php");

$id=@$_GET["id"];

$cfd =  New cfdi32class;
 
//$sql="select cont.idcontrato as idc, day(fechanaturalpago) as dia, month(fechanaturalpago) as mes, year(fechanaturalpago) as anio, fechanaturalpago, calle, numeroext, numeroint, i.colonia, delmun, i.cp, inc.nombre as nombrei, inc.nombre2 as nombre2i, inc.apaterno as apaternoi, inc.amaterno as amaternoi, rfc, estado, pais, d.nombre as nombred, d.nombre2 as nombre2d, d.apaterno as apaternod, d.amaterno as amaternod, rfcd, c.cantidad, c.iva,tipocobro,called,numeroextd,numeorintd,coloniad,cpd,delmund,estadod,paisd  from historia h, inmueble i, inquilino inc, cobros c, duenio d, contrato cont, estado e, pais p, tipocobro tc where c.idtipocobro = tc.idtipocobro and h.idcontrato = cont.idcontrato and h.idcobros = c.idcobros and cont.idinquilino = inc.idinquilino and cont.idinmueble = i.idinmueble and i.idduenio = d.idduenio and i.idpais = p.idpais and i.idestado = e.idestado and idhistoria = $id";
$sql="select cont.idcontrato as idc, day(fechanaturalpago) as dia, month(fechanaturalpago) as mes, year(fechanaturalpago) as anio, fechanaturalpago, calle, numeroext, numeroint, i.colonia, delmun, i.cp, inc.nombre as nombrei, inc.nombre2 as nombre2i, inc.apaterno as apaternoi, inc.amaterno as amaternoi, rfc, estado, pais, c.cantidad, c.iva,tipocobro, metodopago from historia h, inmueble i, inquilino inc, cobros c, contrato cont, estado e, pais p, tipocobro tc, metodopago mp where h.idmetodopago=mp.idmetodopago and c.idtipocobro = tc.idtipocobro and h.idcontrato = cont.idcontrato and h.idcobros = c.idcobros and cont.idinquilino = inc.idinquilino and cont.idinmueble = i.idinmueble and i.idpais = p.idpais and i.idestado = e.idestado and idhistoria = $id";

$operacion = mysql_query($sql);
$row = mysql_fetch_array($operacion);
$folio = "1";
$serie="A";
//echo $cfd->muestradatos($cfd->lista);
//$cfd->cadena_factura($id,$cfd->lista);


echo "<textarea cols = '60' rows='40'>" . $cfd->cadena_factura($id,$cfd->lista,$row,$folio,$serie) . "</textarea>";
//echo $cfd->muestradatos($cfd->lista);

?>