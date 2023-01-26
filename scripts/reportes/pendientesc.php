<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
$id=@$_GET["contrato"];
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
$conref=mysql_query("SELECT apartado.referencia FROM apartado,contrato WHERE contrato.idcontrato='$id' AND contrato.idapartado=apartado.id");
while($ref=mysql_fetch_array($conref)){
$referenciass=$ref[0];
}
$datoss=mysql_query("SELECT inquilino.nombre,inquilino.nombre2,inquilino.apaterno,inquilino.amaterno,inmueble.calle,inmueble.numeroext,inmueble.numeroint,inmueble.colonia,inmueble.delmun,inmueble.cp,fiador.nombre,fiador.nombre2,fiador.apaterno,fiador.amaterno FROM contrato,inmueble,inquilino,fiador WHERE contrato.idcontrato='$id' AND contrato.idinquilino=inquilino.idinquilino AND contrato.idfiador=fiador.idfiador AND contrato.idinmueble=inmueble.idinmueble");
while($dats=mysql_fetch_array($datoss)){
$nominq=$dats[0]." ".$dats[1]." ".$dats[2]." ".$dats[3];
$nomfiador=$dats[10]." ".$dats[11]." ".$dats[12]." ".$dats[13];
$calleinm=$dats[4];
$numextinm=$dats[5];
$numintinm=$dats[6];
$coloniainm=$dats[7];
$delmuninm=$dats[8];
$cpinm=$dats[9];
}
$hoy=date('Y') . "-" . date('m') . "-" . date('d');
  $sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as inqtel,tipocobro, fechagenerado , historia.fechanaturalpago, historia.cantidad, aplicado, historia.interes, historia.iva as ivah, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, calle, numeroext, numeroint, inmueble.colonia, delmun, estado, pais, inmueble.cp, inmueble.tel as itel, inquilino.email as emaili, fiador.email as emailf, observaciones FROM contrato, cobros, inquilino,tipocobro, historia, fiador, inmueble, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and historia.aplicado=false and contrato.idfiador=fiador.idfiador and contrato.idinmueble = inmueble.idinmueble and historia.fechanaturalpago <= '$hoy' and contrato.idcontrato = $id and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais order by inquilino.idinquilino, fechanaturalpago, historia.idhistoria";
  //$html .= "<h1>Pendientes por cobrar</h1>\n";

  $operacion = mysql_query($sql);


  $dia=date("d");
$anio= date("Y");
$mes=date("m");

switch($mes)
{
case 1:
  $mes="Enero";
  break;
case 2:
  $mes="Febrero";
  break;
case 3:
  $mes="Marzo";
  break;
case 4:
  $mes="Abril";
  break;
case 5:
  $mes="Mayo";
  break;
case 6:
  $mes="Junio";
  break;
case 7:
  $mes="Julio";
  break;
case 8:
  $mes="Agosto";
  break;
case 9:
  $mes="Septiembre";
  break;
case 10:
  $mes="Octubre";
  break;
case 11:
  $mes="Noviembre";
  break;
case 12:
  $mes="Diciembre";
  break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Pendientes de inmuebles por cobrar</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="content">
  <div id="head"><img src="images/headp.jpg" alt="Padilla Bujalil. Pendientes por pagar" />
  </div>
  <div id="tablas">
  <div id="fecha">México D.F. a <?php echo $dia; ?> de <?php echo $mes; ?> de <?php echo $anio; ?></div>
  <div id="destinatario">
    <h3>A nuestro Inquilino:</h3>
    <p>Se le hace una cordial invitación para poner al corriente sus pagos de renta. <br />
      Debe hacer su pago en la sucursal de <strong>Banorte</strong> más cercana.</p>
<div id="texto">Deposito a la empresa No. 149454<br>Referencia: <?php echo $nominq ?> </div>
</div>
  <table border="0" width="100%">
    <tbody>
      <tr>
        <td  class="contrato" id="tabla_encabezadoinquilino">Contrato:
          </th></td>
        <td  class="contrato" id="tabla_datosinquilino" colspan="2"><?php echo $id; ?></td>
      </tr>
      <tr>
        <td class="fila1" id="tabla_encabezadoinquilino">Inquilino:
          </th></td>
        <td class="fila1" id="tabla_datosinquilino" colspan="2"><?php echo $nominq; ?></td>
      </tr>
      <tr>
        <td class="fila2" id="tabla_encabezadoinquilino">Inmueble:
          </th></td>
        <td class="fila2" id="tabla_datosinquilino" colspan="2"><?php echo $calleinm; ?> No.<?php echo $numextinm; ?> Int.<?php echo $numintinm; ?> Col.<?php echo $coloniainm; ?> Alc/Mun. <?php echo $delmuninm; ?> C.P. <?php echo $cpinm; ?></td>
      </tr>
      <tr>
        <td class="fila1" id="tabla_encabezadoinquilino">Obligado Solidario:
          </th></td>
        <td class="fila1" id="tabla_datosinquilino" colspan="2"><?php echo $nomfiador; ?></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
      <tr class="fila3"id="tabla_fecha">
        <th>Fecha nat. pago</th>
        <th>Concepto</th>
        <th>Cantidad</th>
      </tr>
      <?php
                        while($row=mysql_fetch_array($operacion)){
                        if($ccontrato!=$row["elidcontrato"])
    {
                        if($ccontrato!=0)
      {

        $grentas +=$rentas;
        $gotros +=$otros;
        $ginteres +=$interes;
        $rentas=0;
        $otros=0;
        $interes=0;

        //Saltos de lineas
        //echo "<br><br><br><br>";
      }
      $ccontrato=$row["elidcontrato"];
      }
      
      if (is_null($row["interes"])==false and $row["interes"]==1)
    {

      $concepto = "INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . "(" . $row["tipocobro"] . ")";
      $interes += $row["cantidad"] + $row["ivah"];
      $Pagado=$row["cantidad"] + $row["ivah"] ;

    }
    else
    {
      $concepto = $row["tipocobro"];
      if(strtoupper($row["tipocobro"])=="RENTA")
      {

        if ($row["aplicado"]==false )
        {
          $rentas +=($row["cantidad"] + $row["ivah"]);
          $Pagado=($row["cantidad"] + $row["ivah"]);

        }
        else
        {
          $rentas +=$row["cantidad"] ;
          $Pagado=$row["cantidad"] ;
        }



        //$rentas +=$row["cantidad"] + $row["ivah"];

      }
      else
      {
        if ($row["aplicado"]==false )
        {
          $otros +=($row["cantidad"] + $row["ivah"]);
          $Pagado=($row["cantidad"] + $row["ivah"]);

        }
        else
        {
          $otros +=$row["cantidad"] ;
          $Pagado=$row["cantidad"] ;
        }


        //$otros +=$row["cantidad"] + $row["ivah"];
      }


    }
                        ?>
      <tr>
        <td id="tabla_fecha"><?php echo $row["fechanaturalpago"]; ?></td>
        <td><?php echo $concepto." "; ?><strong><?php echo $row["observaciones"]; ?></strong><br /></td>
        <td align="right">$ <?php echo number_format($Pagado,2); ?></td>
      </tr>
      <?php
                        }
                        ?>
      
      <tr>
        <td colspan="5" align="right" class="tablainferior"><table border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <tr class="contrato">
              <th width="100" align="center">T. Renta</th>
              <th align="center"> </th>
              <th width="100" align="center">T. Mantenimiento</th>
              <th align="center"> </th>
              <th width="100" align="center">T. Interés</th>
              <th align="center"> </th>
              <th width="100" align="center">Total</th>
            </tr>
            <tr>
              <td align="center">$ <?php echo number_format($rentas,2); ?></td>
              <td align="center">+</td>
              <td align="center">$ <?php echo number_format($otros,2); ?></td>
              <td align="center">+</td>
              <td align="center">$ <?php echo number_format($interes,2); ?></td>
              <td align="center">=</td>
              <td align="center"><strong>$ <?php echo number_format(($rentas+$otros+$interes),2); ?></strong></td>
            </tr>
          </tbody>
        </table></td>
      </tr>
    </tbody>
  </table>
<div id="textofooter">
                        <p>
    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KKEQPJYZAPJYL" target="_blank"><img src="images/paypalimg.jpg"  alt="Ahora puedes pagar con tarjeta sin salir de casa! PayPal" height='150'> </a>
    <a href="https://www.banorte.com" target="_blank"><img src="images/banorte.gif"  alt="Tambien puede hacer su pago directamente a una sucursal banorte sin ninguncosto adicional" height='150'> </a>
    </p>
                    <p>Le recordamos nuestro teléfono para cualquier información:</p>
                    <h2>5592-8816</h2>
                    <h2>Ext. 119</h2>
                    <p>Por su atención, gracias.</p>
                  </div>
  </div>
  <div id="pie"><a href="http://www.padilla-bujalil.com.mx">www.padilla-bujalil.com.mx</a></div>
</div>
</body>
</html>
<?php
}
else{
  echo "Su sesión ha expirado o no tiene permisos para ver este módulo";
}
?>