<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Notificación de servicios</title>
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
<?php
 $asunto ="NotificaciÃ³n de Servicios";
      $body='  
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
    <body>
      <div id="content">
        <div id="head"><img src="images/head2.jpg" alt="Padilla Bujalil. Pendientes por pagar" />
        </div>
        <div id="tablas">
          <h1>Notificación de servicios</h1>
          <p>Estimado '.$nomc.':<br><br></p>

          <div class="nota">
            Esperando se encuentre bien, por este medio le hacemos llegar<br />
            el estado que presentan los servicios del inmueble que se encuentra <br />
            arrendando, a la presente fecha.
          </div>
          <p>En caso de existir duda en los mismos, le solicitamos se comunique con nuestro personal de Facturación y Cobranza al número 5592-8816 extensión 119, quien con gusto lo atenderá. </p>

          <table width="95%" border="0" align="center" cellpadding="5" cellspacing="0">
            <tr align="center">
              <td class="fila3">Contrato</td>
              <td class="fila3">Periodo</td>
              <td class="fila3">Servicio</td>
              <td class="fila3">Cantidad</td>
              <td class="fila3">Estatus</td>
            </tr>
            <tr class="fila2" align="center">
              <td class="contrato2" align="center">'.$contrato.'</td>
              <td align="center">'.$periodo.'</td>
              <td align="center">'.$servicio.'</td>
              <td align="center">$'.number_format($cantidad,2).'</td>
              <td align="center">'.$status.'</td>
            </tr>
          </table>
          <p>Para que continúe gozando de los beneficios de un buen historial crediticio <br />es <b>muy importante</b> que realice sus <b>pagos puntualmente</b>.
          </p>

          <br />
        </div>
        <div id="pie">
          <img src="images/footer2.jpg" alt="Padilla Bujalil. Pendientes por pagar" />
          <a href="http://www.padilla-bujalil.com.mx">www.padilla-bujalil.com.mx</a>
        </div>
      </div>
    </body>
      '; 
      echo $asunto;
      echo $body;

?>
</html>