<?php
include("../../general/conexion.php");
include("../../general/correoclassd.php");
include '../../general/funcionesformato.php';
$servicio=$_POST["servicio"];
$contra=$_POST["idcontrato"];
$sql=mysql_query("SELECT inquilino.email as correo, CONCAT( inquilino.nombre, ' ', IF(inquilino.nombre2='', '', inquilino.nombre2), inquilino.apaterno, ' ', inquilino.amaterno) as nomc FROM inquilino,contrato WHERE contrato.idcontrato='$contra' AND contrato.idinquilino=inquilino.idinquilino");
while ($reg=mysql_fetch_array($sql)) {
  extract($reg);
}

$sql3=mysql_query("SELECT * FROM datoservicios WHERE idcontrato='$contra' AND servicio='$servicio'");
while ($reg3=mysql_fetch_array($sql3)) {
	$periodo=$reg3[2];
	$cantidad=$reg3[4];
	$status=$reg3[5];
}
enviarservicios($correo,$nomc,$servicio,$contra,$periodo,$cantidad,$status);

function enviarservicios($correo,$nomc,$servicio,$contrato,$periodo,$cantidad,$status)
{
  $msg = null;
  //$correos=array("laura@padilla-bujalil.com.mx");
  $correos=array($correo,"cobranza@padilla-bujalil.com.mx");
  //$nombre = "Price Center";

  $asunto ="NotificaciÃ³n de Servicios";
  $body='

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

body{
text-align:center;
margin:auto;
font-family:Arial, Helvetica, sans-serif;
font-size:12px;
line-height:20px;
  width:500px;
}

img{
border:none;
align:center;}
.table{
  width:80%;
}
#content {
  text-align:center;
  margin:auto;
  width:300px;
  }
  #head {
  text-align:center;
  margin:auto;
  }
  #tablas {
  text-align:center;
  margin:auto;
  width:300px;
  }
    #tablas h1{
  font-size:20px;
  color:#333;
  margin:0px;
  padding:10px 5px;
  border-bottom: 1px dotted #666;
  text-align: center;
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    }
    .contrato{
  text-align:center;
  font-size:12px;
  color:#063;
  background-color:#009660;
  color:#FFFFFF;
    }
    .fila1{
  font-size:12px;
  color:#063;
  background-color:#D8E2E7;
  color:#000000;
    }
    .fila2{
  font-size:12px;
  background-color:#ffffff;
  color:#000000;
    }
    .fila3{
  font-size:12px;
  background-color:#F30;
  color:#FFF;
  padding: 5px 0px;
    }
    .contrato2{
  font-size:12px;
  color:#000;
  background-color:#CCC;
    }
    .fila4{
  font-size:12px;
  color:#063;
  background-color:#EEE;
  color:#000000;
  height:24px;
    }
    .nota{
  color:#333;
  padding:0px;
  font-size:15px;
  text-align: center;
  line-height: 25px;
  background-color: #CFC;
  width: 300px;
  margin: 0px auto;
    }
#fecha{
  margin:10px 0px;
  font-weight:bold;
}
#texto{
  background-color:#9CF;
  border:2px solid #039;
  padding:10px;
  text-align:center;
}
#destinatario{
  margin:10px 0px;
  text-align:center;
}
#destinatario h3{
  border-bottom:1px dotted #666;
}
#textofooter{
  text-align:center;
  padding:10px;
  background-color:#EEE;
}
#textofooter{
  text-align:center;
  padding:10px;
  background-color:#EEE;
  margin-top:20px;
}
#textofooter p{
  padding:0px;
  margin:0px;
}
#textofooter h2{
  margin:10px;
}
#textofooter h2{
  font-size:30px;
  font-style:italic;
}
#pie{
  background-color:#009660;
  color:#FFF;
  font-size:14px;
  font-weight:bold;
  text-align: center;
  margin-top: 50px;
  padding-bottom: 5px;
}
#pie a{
  color:#FFF;
  text-decoration:none;
}
#pie img{
  margin-bottom:5px;
}
#tablas{
  padding-top:30px;
}

  </style>
  </head>
    <body>
      <div id="content" >
        <div id="tablas">
        <div id="head"><img src="cid:head" alt="Padilla Bujalil. Pendientes por pagar" />
        </div>
          <h1 align>Notificación de servicios</h1>
          <p>Estimado '.$nomc.':<br><br></p>

          <div class="nota">
           <p> Esperando se encuentre bien, por este medio le hacemos llegar<br />
            el estado que presentan los servicios del inmueble que se encuentra <br />
            arrendando, a la presente fecha.
            </p>
          </div>
          <p>En caso de existir duda en los mismos, le solicitamos se comunique<br /> con nuestro personal de Facturación y Cobranza al número 5592-8816 extensión 119,<br /> quien con gusto lo atenderá. </p>

          <table border="0" align="center" cellpadding="5" cellspacing="0">
            <tr align="center">
              <td class="fila3">Contrato</td>
              <td class="fila3">Periodo</td>
              <td class="fila3">Servicio</td>
              <td class="fila3">Cantidad</td>
              <td class="fila3">Estatus</td>
            </tr>
            <tr class="fila2" align="center">
              <td class="contrato2">'.$contrato.'</td>
              <td>'.$periodo.'</td>
              <td>'.$servicio.'</td>
              <td>$'.number_format($cantidad,2).'</td>
              <td>'.$status.'</td>
            </tr>
          </table>
          <p>Para que continúe gozando de los beneficios de un buen historial crediticio <br /> es <b>muy importante</b> que realice sus <b>pagos puntualmente</b>.
          </p>

          <br />
        </div>
        <div id="pie">
          <img src="cid:piei" alt="Padilla Bujalil. Pendientes por pagar" /><br />
          <a href="http://www.padilla-bujalil.com.mx">www.padilla-bujalil.com.mx</a>
        </div>
      </div>
    </body>
  '; 
  $mail = new PHPMailer;
  //indico a la clase que use SMTP
  $mail->IsSMTP();

  //permite modo debug para ver mensajes de las cosas que van ocurriendo
  //$mail->SMTPDebug = 2;

  //Debo de hacer autenticaciÃ³n SMTP
  $mail->SMTPAuth = true;
  $mail->SMTPSecure = "ssl";

  //indico el servidor de Gmail para SMTP
  $mail->Host = "smtpout.secureserver.net";

  //indico el puerto que usa Gmail
  $mail->Port = 465;

  //indico un usuario / clave de un usuario de gmail
  $mail->Username = "mensajeria@padilla-bujalil.com.mx";
  $mail->Password = "padbuj";
  $mail->Timeout  = 60;  
  $mail->AddEmbeddedImage("images/head2.jpg", "head", "head2.jpg");
  $mail->AddEmbeddedImage("images/footer2.jpg", "piei", "footer2.jpg");

  $mail->SetFrom('cobranza@padilla-bujalil.com.mx', 'cobranza@padilla-bujalil.com.mx');
  $mail->AddReplyTo('cobranza@padilla-bujalil.com.mx', 'Responder a cobranza');
  $mail->FromName = "Padilla & Bujalil";

  $mail->Subject = $asunto;          
  foreach($correos as $opciones) {
  $mail->addAddress($opciones);
  // $mail->addBcc($opciones,$nombre);
  }
  //$mail->MsgHTML($body);
  // $mail->addAddress($email, $nombre);

  $mail->Body=CambiaAcentosaHTML($body);


  //$mail->AddEmbeddedImage("http://www.price-center.mx/nota-credito/images/fondo.jpg");
  //$mail->AddAttachment("fondo.jpg");
  $mail->IsHTML(true);
  $mail->CharSet = 'UTF-8';
  if($mail->Send())
  {
  ?>
  <div class="alert alert-success"><strong>Â¡Se han enviado los correos por los servicios de <?php echo $servicio;?></strong></div>
  <?php
    }
        else
        {
        	?>
<div class="alert alert-danger"><strong>Â¡Ha ocurrido un error!</strong></div>
<?php

    }




	}

?>