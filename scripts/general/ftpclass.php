<?php
//Menu dinamico

class ftpcfdi
{

	var $servidor; //servidor del cfdi
	var $usuario; //usuario del ftp del cfdi
	var $pwdc;  //contraseña del ftp del cfdi
	var $servidorl; //servidor del servidor local
	var $usuariol; //usuario del ftp del servidor local
	var $pwdcl;  //contraseña del ftp del servidor local	
	var $archivo_salida; //donde hay que enviar el archivo de texto
	var $archivo_entrada; //donde hay que recojer el archivo de texto
	var $ruta_acumulado;  //donde se acumulan los archivos
	var $archivotxt;  //nombre del archivo de texto
	var $archivopdf;  //nombre del archivo pdf
	var $archivoxml;  //nombre del archivo xml
	var $archivopdfn;  //nombre del archivo personalizado pdf
	var $rutaf;  //Ruta fisica de donde esta la carpeta acumulada  
	var $ftp;  //variable para el enlace ftp
	
	//constructor
	function ftpcfdi()
	{
/*
		$this->servidor = "sismac.com";
		$this->usuario = "lsolis@sismac.com";
		$this->pwdc = "novato";
		
		$this->servidorl = "sismac.com";
		$this->usuariol = "lsolis@sismac.com";
		$this->pwdcl = "novato";	
		
		$this->archivo_salida="/public_ftp/incoming/";
		$this->archivo_entrada="/public_ftp/incoming/cfdi/";	
		
		$this->ruta_acumulado="paso/";//directo
		$this->ruta_acumulador="cfdi/paso/";//relativo	
		
		$this->rutaf="/var/www/bujalil/scripts/cfdi/";	
*/	
		

		$this->servidor = "mexico.e-factura.net";
		$this->usuario = "PAB0802225K4";
		$this->pwdc = "4zcerNGuT";

		$this->servidorl = "192.168.1.250";
		$this->usuariol = "cfdi";
		$this->pwdcl = "cfftdri9";
		
		$this->archivo_salida="/";
		
		
		//$this->archivo_entrada="/CFD/";
		//$this->archivo_entrada="/";
		$this->archivo_entrada="/home/cfdi/";
		
		
		//$this->ruta_acumulado="/home/wwwarchvivos/"; //absoluto
		$this->ruta_acumulado="paso/";//directo
		$this->ruta_acumulador="cfdi/paso/";//relativo
		//$this->rutaf="/srv/www/htdocs/scripts/cfdi/";
		$this->rutaf="/var/www/html/scripts/cfdi/";
		
	}
	
	
	function accesoftp($s)
	{
		switch($s)
		{
		case 1://servidor cfdi
			//echo "por conectar<br>";
			$this->ftp=ftp_connect($this->servidor) or die ("no puedo conectar");
			ftp_login($this->ftp,$this->usuario,$this->pwdc) or die ("Conexion rechazada");
			//echo "conexion ok";
			break;
		case 2:
			$this->ftp=ftp_connect($this->servidorl) or die ("no puedo conectar");
			ftp_login($this->ftp,$this->usuariol,$this->pwdcl) or die ("Conexion rechazada");
		}
	}
	
	function cerrarftp()
	{
		$quit = ftp_quit($this->ftp);
	}	
	
	//enviar archivo al PAC
	function enviar($id,$filtro)
	{
		//$filtro = para distingir de cobros de inmuebles y otros, 0=facturalibre
		//conexión de servidor
		$this->accesoftp(1);
		
		//Seleccionar la carpeta donde se debe de colocar el archivo
		@ftp_chdir($this->ftp,"$this->archivo_salida");
		
		//directorio actual del FTP
		//echo $carepta = ftp_pwd($this->ftp);
		
		//Envio del archivo al ftp
		// ftp_put(conexion, archivo_como_se_guardara, archivo_que_se_enviara, metodo_de_envio)
		//echo "<br>$this->rutaf$this->ruta_acumulado$this->archivotxt<br>";
		//$result1 = ftp_put($this->ftp, $this->archivotxt, "$this->rutaf$this->ruta_acumulado$this->archivotxt", FTP_BINARY);
		 //echo $this->archivo_salida . $this->archivotxt . "<br>$this->rutaf$this->ruta_acumulado$this->archivotxt<br>" ; 
		
		
		$result1 = ftp_put($this->ftp, $this->archivo_salida . $this->archivotxt, "$this->rutaf$this->ruta_acumulado$this->archivotxt", FTP_BINARY);
		
		$mensaje="";
		if($result1)
		{
			$mensaje = 1; //"OK";
			
			switch($filtro)
			{
			case 1://facturalibre
				$sqlcfdi="select idfacturacfdi from flibrecfdi where idcfdilibre = $id";
				$operacion = mysql_query($sqlcfdi);
				$r= mysql_fetch_array($operacion);
				$idcfdi = $r["idfacturacfdi"];			
			
				break;
			case 2://factura duenio
				$sqlcfdi="select idfacturacfdi from facturacfdid where idcfdiedoduenio = $id";
				$operacion = mysql_query($sqlcfdi);
				$r= mysql_fetch_array($operacion);
				$idcfdi = $r["idfacturacfdi"];			
			
				break;				
			default:
				//$sqlcfdi="select idfacturacfdi from historiacfdi where idhistoria = $id";
				$sqlcfdi="select idfacturacfdi from facturacfdi where idfacturacfdi = $id";
				$operacion = mysql_query($sqlcfdi);
				$r= mysql_fetch_array($operacion);
				$idcfdi = $r["idfacturacfdi"];			
			
			}
			//$sqlftp="update historia set txtok=1 where idhistoria = $id"; 
			$sqlftp="update facturacfdi set txtok=1 where idfacturacfdi = $idcfdi"; 
			$operacion = mysql_query($sqlftp);
		}
		else
		{
			$mensaje = 0; //"Error";
		}
		$this->cerrarftp();
		return $mensaje;
		
	}//	
	

	//enviar archivo al PAC
	function recoger($id,$filtro)
	{
		//id=id del hisotrial, se cambia al idfacturacfdi para poder hacer todo correctamente
		//$filtro = para distingir de cobros de inmuebles y otros, 0=facturalibre
		//conexión de servidor
		//echo "conecto con servidor local<br>";
		$this->accesoftp(2);
		
		//Seleccionar la carpeta donde se debe de colocar el archivo
		//@ftp_chdir($this->ftp,"$this->archivo_entrada");
		
		//directorio actual del FTP
		//echo $carepta = ftp_pwd($ftp1);
		
		//Envio del archivo al ftp
		// ftp_get(conexion, donde_se_guardara_local, archivo_a_copiar_remoto, metodo_de_envio)
		//echo "<br>$this->rutaf$this->ruta_acumulado$this->archivopdf <br> $this->rutaf$this->ruta_acumulado$this->archivoxml<br> ";
		
		
		//recuparar el id de la factura
		switch($filtro)
		{
		case 1://factura cfdi libre
			$sqlcfdi="select idfacturacfdi from flibrecfdi where idfacturacfdi = $id";
			$operacion = mysql_query($sqlcfdi);
			$r= mysql_fetch_array($operacion);
			$idcfdi = $r["idfacturacfdi"];		
		
			break;
		case 2://factura duenio
				/*
				echo $sqlcfdi="select idfacturacfdi from facturacfdid where idcfdiedoduenio = $id";
				$operacion = mysql_query($sqlcfdi);
				$r= mysql_fetch_array($operacion);
				$idcfdi = $r["idfacturacfdi"];			
				*/			
				$idcfdi = $id;
			break;			
		default:
			//$sqlcfdi="select idfacturacfdi from historiacfdi where idhistoria = $id";
			$sqlcfdi="select idfacturacfdi from facturacfdi where idfacturacfdi = $id";
			$operacion = mysql_query($sqlcfdi);
			$r= mysql_fetch_array($operacion);
			$idcfdi = $r["idfacturacfdi"];
		}
		
		$mensaje="";
		for($i=1;$i<3;$i++)
		{
			//echo "$this->rutaf$this->ruta_acumulado$this->archivoxml";
			//$result = ftp_get($this->ftp, "$this->rutaf$this->ruta_acumulado$this->archivoxml", $this->archivoxml, FTP_BINARY); 		
			//echo "obtengo archivo del servidor y lo pongo en la carpeta de paso<br>";
			
			//echo "guardar en: $this->rutaf$this->ruta_acumulado$this->archivoxml||<br>mover a:$this->archivo_entrada$this->archivoxml";
			@$result = ftp_get($this->ftp, "$this->rutaf$this->ruta_acumulado$this->archivoxml", $this->archivo_entrada . $this->archivoxml, FTP_BINARY);
			if($result)
			{
				$mensaje = 1;
				//echo "$this->rutaf$this->ruta_acumulado$this->archivoxml<br>";
				@copy("$this->rutaf$this->ruta_acumulado$this->archivoxml", "/home/wwwarchivos/cfdi/$this->archivoxml");
				unlink ("$this->rutaf$this->ruta_acumulado$this->archivoxml");
				//$sqlftp="update historia set xmlok=1 where idhistoria = $id"; 
				$sqlftp="update facturacfdi set xmlok=1 where idfacturacfdi = $idcfdi"; 
				$operacion = mysql_query($sqlftp);
				
				break;
			}
			else
			{				
				$mensaje = 0;
				sleep(10);
			}
		}
		
		if($mensaje==0)
		{
			return $mensaje;
			exit;
		}
		
		for($i=1;$i<3;$i++)
		{	
			//$result = ftp_get($this->ftp, "$this->rutaf$this->ruta_acumulado$this->archivopdf", $this->archivopdf, FTP_BINARY); 	
			@$result = ftp_get($this->ftp, "$this->rutaf$this->ruta_acumulado$this->archivopdf", $this->archivo_entrada . $this->archivopdf, FTP_BINARY);
			if($result)
			{
				$mensaje = 1;
				//echo "$this->rutaf$this->ruta_acumulado$this->archivopdf<br>";
				@copy("$this->rutaf$this->ruta_acumulado$this->archivopdf", "/home/wwwarchivos/cfdi/$this->archivopdf");
				unlink ("$this->rutaf$this->ruta_acumulado$this->archivopdf");
				//$sqlftp="update historia set pdfok=1 where idhistoria = $id"; 
				$sqlftp="update facturacfdi set pdfok=1 where idfacturacfdi = $idcfdi"; 
				$operacion = mysql_query($sqlftp);
				
				break;
			}
			else
			{				
				$mensaje = 0;
				sleep(10);
			}
		}
		
		if($mensaje==1)
		{
			//@copy("$this->rutaf$this->ruta_acumulado$this->archivotxt", "/home/wwwarchivos/cfdi/$this->archivotxt");
			//unlink ("$this->rutaf$this->ruta_acumulado$this->archivotxt");	
		}
		$this->cerrarftp();
		return $mensaje;
		
	}//	
	
	
	function creararchivo($datos,$serie,$folio,$id,$filtro,$tipofactura)
	{
	
		//$this->arhcivotxt = "/Library/WebServer/Documents/bujalil/scripts/cfdi/preuba/$serie$folio.txt";
		$archivo = "$serie$folio";
		$archivo =str_replace(":", "",$archivo) ;
		$archivo =str_replace(".", "",$archivo) ;
		$archivo =str_replace(" ", "",$archivo) ;
		$this->archivotxt = "$archivo.txt";
		$this->archivopdf = "$archivo.pdf";
		$this->archivoxml = "$archivo.xml";
		$this->archivopdfn = "pyb_" . $archivo . ".pdf";
		$idcfdi=0;
		
//nuevo		

		switch($filtro)
		{
		case 1://factura libre
			echo "libre";
			//registros de la factura libre
			//crear registro en facturacfdi
			$sqlftp="insert into facturacfdi (archivotxt,archivopdf, archivoxml,tipofactura)values('$this->archivotxt', '$this->archivopdf','$this->archivoxml','$tipofactura')"; 
			$operacion = mysql_query($sqlftp);
		
			//tomar idgenerado
			$idcfdi=mysql_insert_id();
				
		
			//crear la relación de historiacfdi
			$sqlftp="insert into flibrecfdi (idcfdilibre, idfacturacfdi) values ($id,$idcfdi)"; 
			$operacion = mysql_query($sqlftp);				
		
		
			break;

		case 2://factura propietario
			echo "propietario";
			//registros de la factura libre
			//crear registro en facturacfdi
			$sqlftp="insert into facturacfdi (archivotxt,archivopdf, archivoxml,tipofactura)values('$this->archivotxt', '$this->archivopdf','$this->archivoxml','$tipofactura')"; 
			$operacion = mysql_query($sqlftp);
		
			//tomar idgenerado
			$idcfdi=mysql_insert_id();
				
		
			//crear la relación de historiacfdi
			$sqlftp="insert into facturacfdid (idcfdiedoduenio, idfacturacfdi) values ($id,$idcfdi)"; 
			$operacion = mysql_query($sqlftp);				
		
		
			break;

		
		default:

			//crear registro en facturacfdi
			$sqlftp="insert into facturacfdi (archivotxt,archivopdf, archivoxml,tipofactura)values('$this->archivotxt', '$this->archivopdf','$this->archivoxml','$tipofactura')"; 
			$operacion = mysql_query($sqlftp);
		
			//tomar idgenerado
			$idcfdi=mysql_insert_id();
		
			//actualizar historia en factura ok
			$sqlftp="update historia set hfacturacfdi=1 where idhistoria = $id"; 
			$operacion = mysql_query($sqlftp);		
		
			//crear la relación de historiacfdi
			$sqlftp="insert into historiacfdi (idhistoria, idfacturacfdi) values ($id,$idcfdi)"; 
			$operacion = mysql_query($sqlftp);		
		}	
		
//fin de lo neuvo		
		//$sqlftp="update historia set archivotxt='$this->archivotxt', archivopdf='$this->archivopdf',archivoxml='$this->archivoxml' where idhistoria = $id"; 
		//$operacion = mysql_query($sqlftp);
		
		// nos aseguramos q el archivo sea writable
		//echo "<br>$this->ruta_acumulador$this->archivotxt<br>";
		if (is_writable("$this->ruta_acumulador$this->archivotxt")) 
		{
			//borrar el archivo
			//unlink ($this->arhcivotxt);
			echo "ok";
			return $idcfdi;
		}

			// abrimos el archivo a modo "append" ('a') para hacer escritura en el mismo
			// el puntero del cursor comenzará a escribir al final del archivo
			// ahi mismo en el archivo se escribirá el contenido $somecontent
			if (!$handle = fopen("$this->ruta_acumulador$this->archivotxt", 'a')) 
			{
				//echo "no se puede abrir el archivo ($this->arhcivotxt)";
				return 0;
				exit;
			}

			// escribimos $somecontent en el archivo abierto.
			if (fwrite($handle, $datos) === FALSE) 
			{
				//echo "no se puede escribir en el archivo ($this->arhcivotxt)";
				return 0;
				exit;
			}

			//echo "se escribió:<br> $datos<br> en el archivo ($this->arhcivotxt)";
			fclose($handle);			
			return $idcfdi;

	
	
	}
	

}


?>