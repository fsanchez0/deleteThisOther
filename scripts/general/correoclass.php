<?php
error_reporting(E_STRICT);

date_default_timezone_set('America/Mexico_City');

require_once('class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded


class correo
{
	var $asunto;
	var $mensaje;
	var $para;
	var $de;
	var $ccopia;
	var $ccipiao;
	var $cabeceras;

	function correo ()
	{
		$this->asunto = "Notificacion";
		$this->mensaje = "Prueba del mensaje";
		$this->para = "";
		$this->de = "mensajeria@rentascdmx.com";
		$this->ccopia = "";
		$this->ccopiao= "";
		$this->cabeceras= 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	}

	function enviar($parac, $asuntoc, $mensajec)
	{

		//$cabeceracorreo="To: $this->para \r\n";
		//$cabeceracorreo .="From: $this->de \r\n";
		//$cabeceracorreo .="Cc: $this->ccopia \r\n";
		//$cabeceracorreo.="Bcc: $this->ccopiao \r\n";
		

		//mail($parac, $asuntoc, $mensajec, $this->cabeceras . $cabeceracorreo);
		
		
		$lmail = split(",", $parac);
/*	
		$mail = new PHPMailer();
		
		
		//$body             = file_get_contents('contents.html');
		$body             = $mensajec;
		//$body             = eregi_replace("[\]",'',$body);
		
		$mail->IsSMTP(); // telling the class to use SMTP
		//$mail->Host       = "mail.padilla-bujalil.com.mx"; // SMTP server
		$mail->Host       = "smtpout.secureserver.net"; // SMTP server
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing) 1 off, 2 on
		                                           // 1 = errors and messages
		                                           // 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		//$mail->SMTPSecure   = "ssl";                  // seguridad ssl
		//$mail->Host       = "mail.padilla-bujalil.com.mx"; // sets the SMTP server
		$mail->Host       = "smtpout.secureserver.net"; // sets the SMTP server
		$mail->Port       =  80;                    // set the SMTP port for the GMAIL server
		$mail->Username   = "mensajeria@padilla-bujalil.com.mx"; // SMTP account username
		$mail->Password   = "padbuj";        // SMTP account password
		$mail->Timeout  = 10; 
		*/
		
		$mail             = new PHPMailer();
		
		//$body             = file_get_contents('contents.html');
		$body             = $mensajec;
		//$body             = eregi_replace("[\]",'',$body);
		
		$mail->IsSMTP(); // telling the class to use SMTP
		//$mail->Host       = "mail.padilla-bujalil.com.mx"; // SMTP server
		$mail->Host       = "mail.rentascdmx.com"; // SMTP server
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing) 1 off, 2 on
		                                           // 1 = errors and messages
		                                           // 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure   = "ssl";                  // seguridad ssl
		//$mail->Host       = "mail.padilla-bujalil.com.mx"; // sets the SMTP server
		$mail->Host       = "mail.rentascdmx.com"; // sets the SMTP server
		$mail->Port       =465; // 80;//  465;                  // set the SMTP port for the GMAIL server
		$mail->Username   = "mensajeria@rentascdmx.com"; // SMTP account username
		$mail->Password   = "M3nsaj3r1a!";        // SMTP account password
		$mail->Timeout  = 60; 		
		
		
		
		
		
		$mail->SetFrom($this->de, $this->de);
		
		$mail->AddReplyTo($this->de,$this->de);
				
		$mail->Subject    = $asuntoc;
		
		$mail->AltBody    = $mensajec;
		
		$mail->MsgHTML($body);
		
		echo "verifico";
		
		foreach($lmail as $valor)
		{
			$address = $valor;
			$mail->AddAddress($address, "");
		}
		
		//$address = "lsolis@sismac.com";
		//$mail->AddAddress($address, "John Doe");
		
		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment   
		
		if(!$mail->Send()) 
		{
		  echo "<p>Error al enviar: " . $mail->ErrorInfo . "</p>$parac<br>" . $mail->Username;
		} 
		else 
		{
		  echo "<p>Mensaje enviado</p>";
		  $log = date("Y-m-d") . "|" . date("H:i:s") . "|" . count($lmail) . "|" . $parac . "\n" ;
		  $archivo = fopen("/home/wwwarchivos/logcorreo.log","a");
		  fwrite($archivo, $log);
		  fclose($archivo);
		  
    	}
		

	}



}





