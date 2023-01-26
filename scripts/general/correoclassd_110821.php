<?php
error_reporting(E_STRICT);

date_default_timezone_set('America/Mexico_City');

require_once('class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded


class correo2
{
	var $asunto;
	var $mensaje;
	var $para;
	var $de;
	var $ccopia;
	var $ccipiao;
	var $cabeceras;

	function correo2 ()
	{
		
		$this->asunto = "Notificacion";
		$this->mensaje = "Prueba del mensaje";
		$this->para = "";
		$this->de = "mensajeria@rentascdmx.com";
		$this->ccopia = "";
		$this->ccopiao= "";
		$this->cabeceras= 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	}

	function enviar($parac, $asuntoc, $mensajec,$estado,$facturas,$idedoduenio)
	{
		
		$directorio="../duenios/contratos/$idedoduenio";
		while ($archivoss = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
		{
    		if (is_dir($archivoss))//verificamos si es o no un directorio
    		{
        		//echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
    		}
    		else
    		{
        		$archivoss=$archivoss;
    		}
		}
		//$cabeceracorreo="To: $this->para \r\n";
		//$cabeceracorreo .="From: $this->de \r\n";
		//$cabeceracorreo .="Cc: $this->ccopia \r\n";
		//$cabeceracorreo.="Bcc: $this->ccopiao \r\n";
		//echo "entro";
		$ok=false;
		//mail($parac, $asuntoc, $mensajec, $this->cabeceras . $cabeceracorreo);
		
		
		$lmail = split(",", $parac);
		
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
		/*
				$body             = $mensajec;
				$mail->IsSMTP(); // telling the class to use SMTP
				$mail->Host       = "mail.price-center.mx"; // SMTP server
				$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing) 1 off, 2 on
														// 1 = errors and messages
														// 2 = messages only
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				$mail->SMTPSecure   = "ssl";                  // seguridad ssl

				$mail->Host       = "mail.price-center.mx"; // sets the SMTP server
				$mail->Port       =465; // 80;//  465;                  // set the SMTP port for the GMAIL server

				$mail->Username   = "mensajeria@price-center.mx"; // SMTP account username
				$mail->Password   = "m3ns4j3r14!";        // SMTP account password
				$mail->Timeout  = 60; 	
		
		*/

		
  		$mail->AddEmbeddedImage("../../imagenes/lona.png", "my-attach", "../../imagenes/lona.png");
	
		
		
		
		$mail->SetFrom($this->de, $this->de);
		
		$mail->AddReplyTo($this->de,$this->de);
				
		$mail->Subject    = $asuntoc;
		
		$mail->AltBody    = $mensajec;
		$mail->CharSet="UTF-8";
		$mail->MsgHTML($body);
		
		$mail->IsHTML(true);
		
		foreach($lmail as $valor)
		{
			$address = $valor;
			$mail->AddAddress($address, "");
		}
		
		//$address = "lsolis@sismac.com";
		//$mail->AddAddress($address, "John Doe");
		
		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment   
		
		
		
		$mail->AddAttachment("$estado"); // attachment  
		
		$rutas="../duenios/contratos/$idedoduenio";
		
		$directorio = opendir($rutas); //ruta actual
		
		while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
		{
			//echo $rutas . "/" . $archivo;
			if(is_file($rutas . "/" . $archivo))
        	{
        		
        		//$renglones.= "<a href='scripts/duenios/$rutas/$archivo' target='_blank'>".$archivo . "</a>$btnborrar<br />";
        		$auxf=$rutas . "/" . $archivo;
        		$mail->AddAttachment("$auxf");
        		//exit();
        	}	
        	
    	}		
		
		
		
		 
		//$mail->AddAttachment("../duenios/contratos/$idedoduenio/Transferencia.pdf");
		
		
		
		//facturas
		
		//++++++++++++++++++++++++++++		
		/* Deshabilitado por ahora el envio de facturas
		$fact = split("[|]", $facturas);
		foreach($fact as $valor)
		{
			$mail->AddAttachment($valor);
		}		
		
		*/
			//+++++++++++++++++++++++++++		
		/*
		//echo "por mandar";
		if(!$mail->Send()) 
		{
		  echo "<p>Error al enviar: " . $mail->ErrorInfo . "</p>$parac<br>" . $mail->Username;
		  $ok=false;
		} 
		else 
		{
		  echo "<p>Mensaje enviado</p>";
		  $log = date("Y-m-d") . "|" . date("H:i:s") . "|" . count($lmail) . "|" . $parac . "\n" ;
		  $archivo = fopen("/home/wwwarchivos/logcorreo.log","a");
		  fwrite($archivo, $log);
		  fclose($archivo);
		  $ok=true;
    	}
		echo $ok;
		*/
		
		
		$intento=0;
		while ($intento <3)
		{

			if(!$mail->Send()) 
			{
			  $intento++;
			  $ok=false;
			} 
			else 
			{
			  echo "<p>Mensaje enviado, intento: $intento</p>";
			  $log = date("Y-m-d") . "|" . date("H:i:s") . "|" . count($lmail) . "|" . $parac . "\n" ;
			  //$archivo = fopen("/home/wwwarchivos/logcorreo.log","a");
			  $archivo = fopen("/home/rentaspb/contenedor/logcorreo.log","a");
			  fwrite($archivo, $log);
			  fclose($archivo);
			  $ok=true;
			  break;
    		}
		}
		
		if($ok==false)
		{
			echo "<p>Error en $intento intentos al enviar: " . $mail->ErrorInfo . "</p>$parac<br>" . $mail->Username;
		  	$ok=false;
		}
		
		return $ok;
	}

	// Cambio 16/06/21 Se creo la funcion enviarFromPadilla, para que el correo de envie 
	// por medio del servidor SMTP de padilla-bujalil.com y que las respuestas 
	// se envien a noemi@padillabijalil.com y miguel@padillabujalil.com	
	function enviarFromPadilla($parac, $asuntoc, $mensajec,$estado,$facturas,$idedoduenio)
	{
		
		$directorio="../duenios/contratos/$idedoduenio";
		while ($archivoss = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
		{
    		if (is_dir($archivoss))//verificamos si es o no un directorio
    		{
        		//echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
    		}
    		else
    		{
        		$archivoss=$archivoss;
    		}
		}
		//$cabeceracorreo="To: $this->para \r\n";
		//$cabeceracorreo .="From: $this->de \r\n";
		//$cabeceracorreo .="Cc: $this->ccopia \r\n";
		//$cabeceracorreo.="Bcc: $this->ccopiao \r\n";
		//echo "entro";
		$ok=false;
		//mail($parac, $asuntoc, $mensajec, $this->cabeceras . $cabeceracorreo);
		
		
		$lmail = split(",", $parac);
		
		$mail             = new PHPMailer();
		
		//$body             = file_get_contents('contents.html');
		$body             = $mensajec;
		//$body             = eregi_replace("[\]",'',$body);
		
		$mail->IsSMTP(); // telling the class to use SMTP
		//$mail->Host       = "mail.padilla-bujalil.com.mx"; // SMTP server
		//$mail->Host       = "mail.rentascdmx.com"; // SMTP server
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing) 1 off, 2 on
		                                           // 1 = errors and messages
		                                           // 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure   = "ssl";                  // seguridad ssl
		$mail->Host       = "padilla-bujalil.com.mx"; // sets the SMTP server
		//$mail->Host     = "mail.rentascdmx.com"; // sets the SMTP server
		$mail->Port       = 465; // 80;//  465;                  // set the SMTP port for the GMAIL server
		$mail->Username   = "mensajeria@padilla-bujalil.com.mx"; // SMTP account username
		$mail->Password   = "m3ns4j3r14!";        // SMTP account password
		$mail->Timeout  = 60;

		
  		$mail->AddEmbeddedImage("../../imagenes/lona.png", "my-attach", "../../imagenes/lona.png");
	
		
		
		
		$mail->SetFrom("mensajeria@padilla-bujalil.com.mx", "mensajeria@padilla-bujalil.com.mx");
		
		$mail->AddReplyTo("noemi@padillabujalil.com","noemi@padillabujalil.com");
		$mail->AddReplyTo("miguel@padillabujalil.com","miguel@padillabujalil.com");
				
		$mail->Subject    = $asuntoc;
		
		$mail->AltBody    = $mensajec;
		$mail->CharSet="UTF-8";
		$mail->MsgHTML($body);
		
		$mail->IsHTML(true);
		
		foreach($lmail as $valor)
		{
			$address = $valor;
			$mail->AddAddress($address, "");
		}
		
		//$address = "lsolis@sismac.com";
		//$mail->AddAddress($address, "John Doe");
		
		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment   
		
		
		
		$mail->AddAttachment("$estado"); // attachment  
		
		$rutas="../duenios/contratos/$idedoduenio";
		
		$directorio = opendir($rutas); //ruta actual
		
		while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
		{
			//echo $rutas . "/" . $archivo;
			if(is_file($rutas . "/" . $archivo))
        	{
        		
        		//$renglones.= "<a href='scripts/duenios/$rutas/$archivo' target='_blank'>".$archivo . "</a>$btnborrar<br />";
        		$auxf=$rutas . "/" . $archivo;
        		$mail->AddAttachment("$auxf");
        		//exit();
        	}	
        	
    	}		
		
		
		
		 
		//$mail->AddAttachment("../duenios/contratos/$idedoduenio/Transferencia.pdf");
		
		
		
		//facturas
		
		//++++++++++++++++++++++++++++		
		/* Deshabilitado por ahora el envio de facturas
		$fact = split("[|]", $facturas);
		foreach($fact as $valor)
		{
			$mail->AddAttachment($valor);
		}		
		
		*/
			//+++++++++++++++++++++++++++		
		/*
		//echo "por mandar";
		if(!$mail->Send()) 
		{
		  echo "<p>Error al enviar: " . $mail->ErrorInfo . "</p>$parac<br>" . $mail->Username;
		  $ok=false;
		} 
		else 
		{
		  echo "<p>Mensaje enviado</p>";
		  $log = date("Y-m-d") . "|" . date("H:i:s") . "|" . count($lmail) . "|" . $parac . "\n" ;
		  $archivo = fopen("/home/wwwarchivos/logcorreo.log","a");
		  fwrite($archivo, $log);
		  fclose($archivo);
		  $ok=true;
    	}
		echo $ok;
		*/
		
		
		$intento=0;
		while ($intento <3)
		{

			if(!$mail->Send()) 
			{
			  $intento++;
			  $ok=false;
			} 
			else 
			{
			  echo "<p>Mensaje enviado, intento: $intento</p>";
			  $log = date("Y-m-d") . "|" . date("H:i:s") . "|" . count($lmail) . "|" . $parac . "\n" ;
			  //$archivo = fopen("/home/wwwarchivos/logcorreo.log","a");
			  $archivo = fopen("/home/rentaspb/contenedor/logcorreo.log","a");
			  fwrite($archivo, $log);
			  fclose($archivo);
			  $ok=true;
			  break;
    		}
		}
		
		if($ok==false)
		{
			echo "<p>Error en $intento intentos al enviar: " . $mail->ErrorInfo . "</p>$parac<br>" . $mail->Username;
		  	$ok=false;
		}
		
		return $ok;
	}
	
	function enviarp($parac, $asuntoc, $mensajec)
	{

		//$cabeceracorreo="To: $this->para \r\n";
		//$cabeceracorreo .="From: $this->de \r\n";
		//$cabeceracorreo .="Cc: $this->ccopia \r\n";
		//$cabeceracorreo.="Bcc: $this->ccopiao \r\n";
		//echo "entro";
		$ok=false;
		//mail($parac, $asuntoc, $mensajec, $this->cabeceras . $cabeceracorreo);
		
		
		$lmail = split(",", $parac);
		
		$mail             = new PHPMailer();
		/*		
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
		$mail->SMTPSecure   = "ssl";                  // seguridad ssl
		//$mail->Host       = "mail.padilla-bujalil.com.mx"; // sets the SMTP server
		$mail->Host       = "smtpout.secureserver.net"; // sets the SMTP server
		$mail->Port       =465; // 80;//  465;                  // set the SMTP port for the GMAIL server
		$mail->Username   = "cobranza@padilla-bujalil.com.mx"; // SMTP account username
		$mail->Password   = "c0br4nz4";        // SMTP account password
		$mail->Timeout  = 60;  
		
		*/
		$body             = $mensajec;
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host       = "mail.rentascdmx.com"; // SMTP server
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing) 1 off, 2 on
		                                           // 1 = errors and messages
		                                           // 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure   = "ssl";                  // seguridad ssl

		$mail->Host       = "mail.rentascdmx.com"; // sets the SMTP server
		$mail->Port       =465; // 80;//  465;                  // set the SMTP port for the GMAIL server

		$mail->Username   = "mensajeria@rentascdmx.com"; // SMTP account username
		$mail->Password   = "M3nsaj3r1a!";        // SMTP account password
		$mail->Timeout  = 60; 		
		
		
		
		
  		//$mail->AddEmbeddedImage("../../imagenes/lona.png", "my-attach", "../../imagenes/lona.png");
		//$mail->AddEmbeddedImage("../reportes/images/header.jpg", "header", "../reportes/images/header.jpg");
		//$mail->AddEmbeddedImage("../reportes/images/telefono.png", "telefono", "../reportes/images/telefono.png");
		//$mail->AddEmbeddedImage("../reportes/images/footer0.jpg", "footer", "../reportes/images/footer0.jpg");
	    $mail->AddEmbeddedImage("../reportes/images/paypalimg.jpg", "paypalimg", "../reportes/images/paypalimg.jpg");
		$mail->AddEmbeddedImage("../reportes/images/banorte.gif", "banorte", "../reportes/images/banorte.gif");
		
		// Cambio 12/07/21
		// Se cambió el remitente del correo en donde se envian los reportes masivos
		// También se cambiarón los correos a los que se envian respuestas, se pusieron:
		// noemi@padillabujalil.com, miguel@padillabujalil.com y rtrujillo@padillabujalil.com
		//$mail->SetFrom($this->de, $this->de);
		$mail->SetFrom('cobranza@padilla-bujalil.com.mx', 'cobranza@padilla-bujalil.com.mx');
		
		//$mail->AddReplyTo($this->de,$this->de);
		$mail->AddReplyTo("noemi@padillabujalil.com","noemi@padillabujalil.com");
		$mail->AddReplyTo("miguel@padillabujalil.com","miguel@padillabujalil.com");
		$mail->AddReplyTo("rtrujillo@padillabujalil.com","rtrujillo@padillabujalil.com");
		// Fin Cambio 12/07/21
				
		$mail->Subject    = $asuntoc;
		
		$mail->AltBody    = $mensajec;
		$mail->CharSet="UTF-8";
		$mail->MsgHTML($body);
		
		$mail->IsHTML(true);
		
		
		
		foreach($lmail as $valor)
		{
			$address = $valor;
			$mail->AddAddress($address, "");
		}
		
		//$mail->AddAddress("lsolis@sismac.com", "");
		
		
		$intento=0;
		while ($intento <3)
		{

			if(!$mail->Send()) 
			{
			  $intento++;
			  $ok=false;
			} 
			else 
			{
			  echo "<p>Mensaje enviado, intento: $intento</p>";
			  //$log = date("Y-m-d") . "|" . date("H:i:s") . "|" . count($lmail) . "|" . $parac . "\n" ;
			  //$archivo = fopen("/home/wwwarchivos/logcorreo.log","a");
			  //fwrite($archivo, $log);
			  //fclose($archivo);
			  $ok=true;
			  break;
    		}
		}
		
		if($ok==false)
		{
			echo "<p>Error en $intento intentos al enviar: " . $mail->ErrorInfo . "</p>$parac<br>" . $mail->Username;
		  	$ok=false;
		}
		
		return $ok;
	}	
	
	
    function enviaf($parac, $asuntoc, $mensajec,$facturas)
	{
		
		$directorio = "/home/rentaspb/contenedor/cfdi";
		/*
		while ($archivoss = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
		{
    		if (is_dir($archivoss))//verificamos si es o no un directorio
    		{
        		//echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
    		}
    		else
    		{
        		$archivoss=$archivoss;
    		}
		}
		*/
		//$cabeceracorreo="To: $this->para \r\n";
		//$cabeceracorreo .="From: $this->de \r\n";
		//$cabeceracorreo .="Cc: $this->ccopia \r\n";
		//$cabeceracorreo.="Bcc: $this->ccopiao \r\n";
		//echo "entro";
		$ok=false;
	
		
		
		$lmail = split(",", $parac);
		
		$mail             = new PHPMailer();
		
		//$body             = file_get_contents('contents.html');
		$body             = $mensajec;
		//$body             = eregi_replace("[\]",'',$body);
		
		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host       = "mail.rentascdmx.com"; // SMTP server
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing) 1 off, 2 on
		                                           // 1 = errors and messages
		                                           // 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure   = "ssl";                  // seguridad ssl
		$mail->Host       = "mail.rentascdmx.com"; // sets the SMTP server
		$mail->Port       =465; // 80;//  465;                  // set the SMTP port for the GMAIL server
		$mail->Username   = "mensajeria@rentascdmx.com"; // SMTP account username
		$mail->Password   = "M3nsaj3r1a!";        // SMTP account password
		$mail->Timeout  = 60;  
		/*
				$body             = $mensajec;
				$mail->IsSMTP(); // telling the class to use SMTP
				$mail->Host       = "mail.price-center.mx"; // SMTP server
				$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing) 1 off, 2 on
														// 1 = errors and messages
														// 2 = messages only
				$mail->SMTPAuth   = true;                  // enable SMTP authentication
				$mail->SMTPSecure   = "ssl";                  // seguridad ssl

				$mail->Host       = "mail.price-center.mx"; // sets the SMTP server
				$mail->Port       =465; // 80;//  465;                  // set the SMTP port for the GMAIL server

				$mail->Username   = "mensajeria@price-center.mx"; // SMTP account username
				$mail->Password   = "m3ns4j3r14!";        // SMTP account password
				$mail->Timeout  = 60; 	
		
		*/

		
  		$mail->AddEmbeddedImage("../../imagenes/lona.png", "my-attach", "../../imagenes/lona.png");
	
		
		
		
		$mail->SetFrom($this->de, $this->de);
		
		$mail->AddReplyTo($this->de,$this->de);
				
		$mail->Subject    = $asuntoc;
		
		$mail->AltBody    = $mensajec;
		$mail->CharSet="UTF-8";
		$mail->MsgHTML($body);
		
		$mail->IsHTML(true);
		
		foreach($lmail as $valor)
		{
			$address = $valor;
			$mail->AddAddress($address, "");
		}
		
		//$address = "lsolis@sismac.com";
		//$mail->AddAddress($address, "John Doe");
		
		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment   
		
		$lfact=split("[|]",$facturas);
		$f1=$lfact[0];
		$mail->AddAttachment("$f1");
		$f1=$lfact[1];
		$mail->AddAttachment("$f1");
		/*
		$mail->AddAttachment("$estado"); // attachment  
		
		$rutas="../duenios/contratos/$idedoduenio";
		
		$directorio = opendir($rutas); //ruta actual
		
		while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
		{
			//echo $rutas . "/" . $archivo;
			if(is_file($rutas . "/" . $archivo))
        	{
        		
        		//$renglones.= "<a href='scripts/duenios/$rutas/$archivo' target='_blank'>".$archivo . "</a>$btnborrar<br />";
        		$auxf=$rutas . "/" . $archivo;
        		$mail->AddAttachment("$auxf");
        		//exit();
        	}	
        	
    	}		
		*/
		
		
		 
		//$mail->AddAttachment("../duenios/contratos/$idedoduenio/Transferencia.pdf");
		
		
		
		//facturas
		
		//++++++++++++++++++++++++++++		
		/* Deshabilitado por ahora el envio de facturas
		$fact = split("[|]", $facturas);
		foreach($fact as $valor)
		{
			$mail->AddAttachment($valor);
		}		
		
		*/
		//+++++++++++++++++++++++++++		
		/*
		//echo "por mandar";
		if(!$mail->Send()) 
		{
		  echo "<p>Error al enviar: " . $mail->ErrorInfo . "</p>$parac<br>" . $mail->Username;
		  $ok=false;
		} 
		else 
		{
		  echo "<p>Mensaje enviado</p>";
		  $log = date("Y-m-d") . "|" . date("H:i:s") . "|" . count($lmail) . "|" . $parac . "\n" ;
		  $archivo = fopen("/home/wwwarchivos/logcorreo.log","a");
		  fwrite($archivo, $log);
		  fclose($archivo);
		  $ok=true;
    	}
		echo $ok;
		*/
		
		
		$intento=0;
		while ($intento <3)
		{

			if(!$mail->Send()) 
			{
			  $intento++;
			  $ok=false;
			} 
			else 
			{
			  echo "<p>Mensaje enviado, intento: $intento</p>";
			  $log = date("Y-m-d") . "|" . date("H:i:s") . "|" . count($lmail) . "|" . $parac . "\n" ;
			  //$archivo = fopen("/home/wwwarchivos/logcorreo.log","a");
			  $archivo = fopen("/home/rentaspb/contenedor/logcorreo.log","a");
			  fwrite($archivo, $log);
			  fclose($archivo);
			  $ok=true;
			  break;
    		}
		}
		
		if($ok==false)
		{
			echo "<p>Error en $intento intentos al enviar: " . $mail->ErrorInfo . "</p>$parac<br>" . $mail->Username;
		  	$ok=false;
		}
		
		return $ok;
	}	

	//Cambio 23/06/2021
	// Se agrega copia de la funcion enviarf para que ahora se envien los correos desde 
	// padilla-bujalil.com
	function enviafFromPadilla($parac, $asuntoc, $mensajec,$facturas)
	{
		
		$directorio = "/home/rentaspb/contenedor/cfdi";
		/*
		while ($archivoss = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
		{
    		if (is_dir($archivoss))//verificamos si es o no un directorio
    		{
        		//echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
    		}
    		else
    		{
        		$archivoss=$archivoss;
    		}
		}
		*/
		//$cabeceracorreo="To: $this->para \r\n";
		//$cabeceracorreo .="From: $this->de \r\n";
		//$cabeceracorreo .="Cc: $this->ccopia \r\n";
		//$cabeceracorreo.="Bcc: $this->ccopiao \r\n";
		//echo "entro";
		$ok=false;
	
		
		
		$lmail = split(",", $parac);
		
		$mail             = new PHPMailer();
		
		//$body             = file_get_contents('contents.html');
		$body             = $mensajec;
		//$body             = eregi_replace("[\]",'',$body);
		
		$mail->IsSMTP(); // telling the class to use SMTP
		//$mail->Host       = "mail.padilla-bujalil.com.mx"; // SMTP server
		//$mail->Host       = "mail.rentascdmx.com"; // SMTP server
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing) 1 off, 2 on
		                                           // 1 = errors and messages
		                                           // 2 = messages only
		$mail->SMTPAuth   = true;                  // enable SMTP authentication
		$mail->SMTPSecure   = "ssl";                  // seguridad ssl
		$mail->Host       = "padilla-bujalil.com.mx"; // sets the SMTP server
		//$mail->Host     = "mail.rentascdmx.com"; // sets the SMTP server
		$mail->Port       = 465; // 80;//  465;                  // set the SMTP port for the GMAIL server
		$mail->Username   = "mensajeria@padilla-bujalil.com.mx"; // SMTP account username
		$mail->Password   = "m3ns4j3r14!";        // SMTP account password
		$mail->Timeout  = 60;

		
  		$mail->AddEmbeddedImage("../../imagenes/lona.png", "my-attach", "../../imagenes/lona.png");
	
		
		
		
		$mail->SetFrom("mensajeria@padilla-bujalil.com.mx", "mensajeria@padilla-bujalil.com.mx");
		
		$mail->AddReplyTo("noemi@padillabujalil.com","noemi@padillabujalil.com");
		$mail->AddReplyTo("miguel@padillabujalil.com","miguel@padillabujalil.com");

		$mail->Subject    = $asuntoc;
		
		$mail->AltBody    = $mensajec;
		$mail->CharSet="UTF-8";
		$mail->MsgHTML($body);
		
		$mail->IsHTML(true);
		
		foreach($lmail as $valor)
		{
			$address = $valor;
			$mail->AddAddress($address, "");
		}
		
		//$address = "lsolis@sismac.com";
		//$mail->AddAddress($address, "John Doe");
		
		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment   
		
		$lfact=split("[|]",$facturas);
		$f1=$lfact[0];
		$mail->AddAttachment("$f1");
		$f1=$lfact[1];
		$mail->AddAttachment("$f1");
		/*
		$mail->AddAttachment("$estado"); // attachment  
		
		$rutas="../duenios/contratos/$idedoduenio";
		
		$directorio = opendir($rutas); //ruta actual
		
		while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
		{
			//echo $rutas . "/" . $archivo;
			if(is_file($rutas . "/" . $archivo))
        	{
        		
        		//$renglones.= "<a href='scripts/duenios/$rutas/$archivo' target='_blank'>".$archivo . "</a>$btnborrar<br />";
        		$auxf=$rutas . "/" . $archivo;
        		$mail->AddAttachment("$auxf");
        		//exit();
        	}	
        	
    	}		
		*/
		
		
		 
		//$mail->AddAttachment("../duenios/contratos/$idedoduenio/Transferencia.pdf");
		
		
		
		//facturas
		
		//++++++++++++++++++++++++++++		
		/* Deshabilitado por ahora el envio de facturas
		$fact = split("[|]", $facturas);
		foreach($fact as $valor)
		{
			$mail->AddAttachment($valor);
		}		
		
		*/
		//+++++++++++++++++++++++++++		
		/*
		//echo "por mandar";
		if(!$mail->Send()) 
		{
		  echo "<p>Error al enviar: " . $mail->ErrorInfo . "</p>$parac<br>" . $mail->Username;
		  $ok=false;
		} 
		else 
		{
		  echo "<p>Mensaje enviado</p>";
		  $log = date("Y-m-d") . "|" . date("H:i:s") . "|" . count($lmail) . "|" . $parac . "\n" ;
		  $archivo = fopen("/home/wwwarchivos/logcorreo.log","a");
		  fwrite($archivo, $log);
		  fclose($archivo);
		  $ok=true;
    	}
		echo $ok;
		*/
		
		
		$intento=0;
		while ($intento <3)
		{

			if(!$mail->Send()) 
			{
			  $intento++;
			  $ok=false;
			} 
			else 
			{
			  echo "<p>Mensaje enviado, intento: $intento</p>";
			  $log = date("Y-m-d") . "|" . date("H:i:s") . "|" . count($lmail) . "|" . $parac . "\n" ;
			  //$archivo = fopen("/home/wwwarchivos/logcorreo.log","a");
			  $archivo = fopen("/home/rentaspb/contenedor/logcorreo.log","a");
			  fwrite($archivo, $log);
			  fclose($archivo);
			  $ok=true;
			  break;
    		}
		}
		
		if($ok==false)
		{
			echo "<p>Error en $intento intentos al enviar: " . $mail->ErrorInfo . "</p>$parac<br>" . $mail->Username;
		  	$ok=false;
		}
		
		return $ok;
	}	

}





