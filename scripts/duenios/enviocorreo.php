<?php
include('../general/class.phpmailer.php');
include('../general/conexion.php');
$hoy=date('Y-m-d');
function enviamail($idduenio,$files,$duenion,$periodo,$cuenta){
  $diahoy=date('d');
  $meshoyy=date('n');
  $aniohoy=date('Y');
  if ($meshoyy==1) {
    $meshoy="Enero";
  }
  if ($meshoyy==2) {
    $meshoy="Febrero";
  }
  if ($meshoyy==3) {
    $meshoy="Marzo";
  }
  if ($meshoyy==4) {
    $meshoy="Abril";
  }
  if ($meshoyy==5) {
    $meshoy="Mayo";
  }
  if ($meshoyy==6) {
    $meshoy="Junio";
  }
  if ($meshoyy==7) {
    $meshoy="Julio";
  }
  if ($meshoyy==8) {
    $meshoy="Agosto";
  }
  if ($meshoyy==9) {
    $meshoy="Septiembre";
  }
  if ($meshoyy==10) {
    $meshoy="Octubre";
  }
  if ($meshoyy==11) {
    $meshoy="Noviembre";
  }
  if ($meshoyy==12) {
    $meshoy="Diciembre";
  }
$sql=mysql_query("SELECT contacto FROM contacto WHERE idduenio='$cuenta' AND idtipocontacto=2");
while ($rgd=mysql_fetch_array($sql)) {
 $emaild=$rgd[0];
}
	//ini_set("display_errors",0);
$mensaje ="$cuenta<p style='text-align:right;'>M&eacute;xico D.F. a " . $diahoy . " de $meshoy de " . $aniohoy . "</p><p style='font-weight:bold;'>  $duenion <br> Presente:</p>";
    $mensaje .= "<p>Por este conducto y conforme a lo pactado en el contrato de administraci&oacute;n que tenemos celebrado, le hacemos llegar el estado de cuenta correspondiente al periodo $periodo, as&iacute; como las facturas correspondientes. </p><p> Cualquier aclaraci&oacute;n o comentario, por favor, h&aacute;gannoslo llegar a la direcci&oacute;n de correo <a href='mailto:ayuda@padilla-bujalil.com.mx?Subject=Aclaracion del estado de cuenta $periodo de $duenion' target='_top'>ayuda@padilla-bujalil.com.mx</a> </p><p>Atentamente:<br>PADILLA & BUJALIL S.C.</p><br>"; 
$msg = null;
	//$correos= array("nc@price-center.mx");
	$correos=array("ayuda@padilla-bujalil.com.mx","$emaild");
    $nombre = "Price Center";
   
    $asunto ="Aclaracion del estado de cuenta";
    //$adjunto = $_FILES["adjunto"];
        
       // require "../../general/class.phpmailer.php";
		  
		 $mail = new PHPMailer;
		  //indico a la clase que use SMTP
          $mail->IsSMTP();
		  
          //permite modo debug para ver mensajes de las cosas que van ocurriendo
          //$mail->SMTPDebug = 2;

		  //Debo de hacer autenticación SMTP
          $mail->SMTPAuth = true;
          $mail->SMTPSecure = "ssl";

		  //indico el servidor de Gmail para SMTP
          $mail->Host = "smtpout.secureserver.net";

		  //indico el puerto que usa Gmail
          $mail->Port = 465;

		  //indico un usuario / clave de un usuario de gmail
          $mail->Username = "mensajeria@padilla-bujalil.com.mx";
          $mail->Password = "padbuj";
       
          $mail->From = "mensajeria@padilla-bujalil.com.mx";
        
          $mail->FromName = "Padilla & Bujalil";

          $mail->Subject = $asunto;
          
          foreach($correos as $opciones) {
      	  $mail->addAddress($opciones);
         // $mail->addBcc($opciones,$nombre);
          }
        	//$mail->MsgHTML($body);
         // $mail->addAddress($email, $nombre);
          
          $mail->Body=$mensaje;
          $mail->AddAttachment("contratos/$idduenio/".$files);
         
         //$mail->AddEmbeddedImage("http://www.price-center.mx/nota-credito/images/fondo.jpg");
   		//$mail->AddAttachment("fondo.jpg");
    		$mail->IsHTML(true);
       	  $mail->CharSet = 'UTF-8';
          if($mail->Send())
        {
    echo "En hora buena el mensaje ha sido enviado con exito <br>";
    }
        else
        {
    echo "Lo siento, ha habido un error al enviar el mensaje";
    echo "<br>";
    }
}//fin del metodo de envio de correo
?>