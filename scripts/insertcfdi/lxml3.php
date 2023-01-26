<?php


class xmlcfd_cfdi
{
	var $cfdixmls;
	var $comprobante;
	var $emisor;
	var $receptor;
	var $conceptos;
	var $impuestos;
	var $complementos;
	var $adendas;
	
	var $expedidoen;
	var $regimenfiscal;
	
	var $concepto;
	
	var $aduanas;
	var $predial;
	var $complementoc;
	var $parte;
	
	var $terceros;
	var $infotercero;
	var $partet;
	
	var $retenciones;
	var $traslados;
	
	var $timbrefiscal;
	
	
	
	
	
	var $base;
	var $dom;
	
	var $cuentas;

	function xmlcfd_cfdi()
	{
		$this->base = array(
					"uso" =>"",
					"defecto" =>"",
					"tipo" => "",
					"max" => "",
					"espacio" => "",
					"valor" =>""
					);
		
		$this->dom = array(
					"calle" =>$this->base,
					"noExterior" =>$this->base,
					"noINterior" =>$this->base,
					"colonia" =>$this->base,
					"localidad" =>$this->base,
					"referencia" =>$this->base,
					"municipio" =>$this->base,
					"estado" =>$this->base,
					"pais" =>$this->base,
					"codigoPostal" =>$this->base
					);
	
		$this->regimenfiscal[] =array("Regimen" =>$this->base);
		
		$this->emisor = array(
					"rfc" =>$this->base,
					"nombre" =>$this->base,
					"DomicilioFiscal" => $this->dom,
					"ExpedidoEn" =>$this->dom,
					"RegimenFiscal" =>$this->regimenfiscal
					);
		
		$this->receptor = array(
						"rfc" =>$this->base,
						"nombre" =>$this->base,
						"Domicilio" => $this->dom
						);	

		$this->aduanas = array(
						"numero" =>$this->base,
						"fecha" =>$this->base,
						"aduana" => $this->base
						);
		$this->predial[] = array("numero" =>$this->base);
		
		
		$this->retenciones[] = array (
							"impuesto" =>$this->base,
							"importe" =>$this->base
							);
		$this->traslados[] = array (
							"impuesto" => $this->base,
							"tasa" => $this->base,
							"importe" => $this->base
							);	
		$this->impuestos = array (
							"totalImpuestosRetenidos" => $this->base,
							"totalImpuestosTrasladados" => $this->base,
							"Retenciones" => $this->retenciones,
							"Traslados" => $this->traslados
							);
							
		$this->partet[]= array(
						  "cantidad" => $this->base,
						  "unidad" => $this->base,
						  "noIdentificacion" => $this->base,
						  "descripcion" => $this->base,
						  "valorUnitario" => $this->base,
						  "importe" => $this->base
						  );
						
		
		
		$this->terceros[]= array(
							"version" => $this->base,
							"rfc" => $this->base,
							"nombre" => $this->base,
							"InformacionFiscalTercero" => $this->dom,
							"Impuestos" => $this->impuestos,
							"InformacionAduanera" => $this->aduanas,
							"CuentaPredial" =>$this->predial,
							"Parte" => $this->parte
							);
		
		$this->complementoc[] = array("PorCuentadeTerceros" => $this->terceros);
							
							
		

		$this->concepto[] = array(
							 "cantidad" => $this->base,
							 "unidad" => $this->base,
							 "noIdentificacion" => $this->base,
							 "descripcion" => $this->base,
							 "valorUnitario" => $this->base,
							 "importe" => $this->base,
							 "InformacionAduanera" => $this->aduanas,
							 "CuentaPredial" => $this->predial,
							 "ComplementoConcepto" => $this->complementoc,
							);
		

		$this->conceptos = array("Concepto" => $this->concepto);
		
		$this->timbrefiscal = array(
							 "version" => $this->base,
							 "UUID" => $this->base,
							 "FechaTimbrado" => $this->base,
							 "selloCFD" => $this->base,
							 "noCeratificadoSAT" => $this->base,
							 "selloSAT" => $this->base
							 );
		
		$this->complementos = array("TimbreFiscalDigital" => $this->timbrefiscal);
		
		$this->comprobante = array(
							"version" => $this->base,
							"serie" => $this->base,
							"folio" => $this->base,
							"fecha" => $this->base,
							"sello" => $this->base,
							"noAprobacion" => $this->base,
							"anoAprobacion" => $this->base,
							"formaDePago" => $this->base,
							"noCertificado" => $this->base,
							"certificado" => $this->base,
							"condicionesDePago" => $this->base,
							"subTotal" => $this->base,
							"descuento" => $this->base,
							"motivoDescuento" => $this->base,
							"TipoCambio" => $this->base,
							"Moneda" => $this->base,
							"total" => $this->base,
							"tipoDeComprobante" => $this->base,
							"metodoDePago" => $this->base,
							"LugarExpedicion" => $this->base,
							"NumCtaPago" => $this->base,
							"FolioFiscalOrig" => $this->base,
							"SerieFolioFiscalOrig" => $this->base,
							"FechaFoioFiscalOrig" => $this->base,
							"MontoFolioFiscalOrig" => $this->base,
							"Emisor" => $this->emisor,
							"Receptor" => $this->receptor,
							"Conceptos" => $this->conceptos,
							"Impuetos" => $this->impuestos,
							"Complemento" => $this->complementos,
							"Adenta" => $this->adendas
						);
							
		$this->cuentas = array(
				"concepto" =>-1, 
				"cuentapredialc" =>-1, 
				"complementoconcepto" =>-1, 
				"retencionest" =>-1, 
				"trasladost" => -1, 
				"cuentapredialt" => -1, 
				"partet" =>-1, 
				"retenciones" => -1, 
				"traslados" =>-1 
				);
	
	
	
	}
	
	function asignacion($prop,$val,$pro)
	{
	/*
	$arr = arreglo donde será depositado el dato (valor por referencia)
	$val = Valor que será asignado
	$pro = Procedencia para identificar el dato a asignar formato separado por "|" que incluye el indice

	Se construye la cadena de la direccion completa de lo que se asignará donde siempre se comienza con "|"
	y cada "|" incica la contrucción de cadena "[dato]" para terminar con "['valor'] = $val"
	*/
		//var_dump($arr);
		$aux = split("[|]",$pro);
		//var_dump($aux);
		$d="";
		for($i=1;$i<count($aux);$i++)
		{
			if(is_numeric($aux[$i])==true)
			{
				$d .= "[" . $aux[$i] . "]";
			}
			else
			{
				$d .= "[\"" . $aux[$i] . "\"]";
			}
	
		}
		$d .= "[\"$prop\"][\"valor\"]"; 
		//echo "<br>\$this->comprobante" . $d . "=$val<br>";
		eval ("\$this->comprobante" . $d . "=\$val;");
		//eval ("var_dump(\$this->comprobante" . $d . ");");
	
	}	


	function atributos($nombre,$lista,&$id,$pro)
	{
/*
$nombre = nombre del objeto
$lista = atributos del objeto
$a = arreglo donde se depositaran los valores
$i = Arreglo donde estan las variables de los indices de los arreglos internos
$pro = Procedencia que sera definida y arrastrada seprada por "|"


Se leeran las variables y se usara la función de asignación para asignar la variable
dependiendo de su procedencia, aqui es donde se calcula el indice, mismo que se incluirá en la 
procedencia para que la asignación esté completa

*/

		//Verifico el nombre de la procedencia para saber si requiere indice y aumentarlo
		//cuando se repita algun concepto hay que identificar de que nivel es al contar la procedencia
		//dependiendo la longitud sería el incremento de variable
		//var_dump($a);
		$i=0;
		switch($nombre)
		{
		case "Concepto":

			$id["concepto"] +=1;
			$i=$id["concepto"];
			//echo "concepto......";
			break;
		case "CuentaPredial":
			$aux = split("[|]",$pro);
			if(count($aux)==4)
			{//el del concepto y es cuatro por el indice del concepto
				$id["cuentapredialc"] +=1;
				$i=$id["cuentapredialc"];	
			}
			else
			{//cuando es del tercero
				$id["cuentapredialt"] +=1;
				$i=$id["cuentapredialt"];			
			}
		
			break;
		case "ComplementoConcepto":

			$id["complementoconcepto"] +=1;
			$i=$id["complementoconcepto"];
			break;
		case "Retenciones":
			$aux = split("[|]",$pro);
			if(count($aux)==3)
			{//el del Impuesto general
				$id["retenciones"] +=1;
				$i=$id["retenciones"];	
			}
			else
			{//cuando es del tercero
				$id["retencionest"] +=1;
				$i=$id["retencionest"];			
			}		
			break;
		case "Traslados":

			$aux = split("[|]",$pro);
			//echo "<br><br>Traslados =" . count($aux) . "<br><br>";
			if(count($aux)==2)
			{//el del Impuesto general
				$id["traslados"] +=1;
				$i=$id["traslados"];	
			}
			else
			{//cuando es del tercero
				$id["trasladost"] +=1;
				$i=$id["trasladost"];			
			}		
			break;
/*		
		case "Parte":
			$aux = split("[|]",$pro);
			if(count($aux)==2)
			{//el del Impuesto general
				$a["traslados"] +=1;
				$i=$a["traslados"];	
			}
			else
			{//cuando es del tercero
				$a["trasladost"] +=1;
				$i=$a["trasladost"];			
			}		
			break;	
*/	
		}
	
		//preparo procedencia nueva
		$proint = $pro;
		//$i -=1;
		if($i!=0)
		{
			$proint .=  "|$i";
		}
	
		//investigo todos los atributos para su asignación
	
		foreach ($lista as $prop => $valor)
		{
		//	echo "<br>$nombre:$prop = $valor <br>";
			$this->asignacion($prop,$valor,$proint);
		}
		//return $proint;


	}


	function verificaIndice($nombre,$pro0,&$ctas)
	{
		$r="";
				switch($nombre)
				{
				case "Concepto":
					$r = "|" . $ctas["concepto"] ;// . "]";
					break;
				case "CuentaPredial":
					$aux = split("[|]",$pro0);
					if(count($aux)==4)
					{//el del concepto y es cuatro por el indice del concepto
						$r = "|" . $ctas["cuentapredialc"];// . "]";

					}
					else
					{//cuando es del tercero
						$r = "|" . $ctas["cuentapredialt"] ;// . "]";
		
					}
				
					break;
				case "ComplementoConcepto":
					$r = "|" . $ctas["complementoconcepto"] ;// . "]";

					break;
				case "Retenciones":
					$aux = split("[|]",$pro0);
					if(count($aux)==2)
					{//el del Impuesto general
						$r = "|" . $ctas["retenciones"];// . "]";

					}
					else
					{//cuando es del tercero
						$r = "|" . $ctas["retencionest"] ;// . "]";
	
					}		
					break;
				case "Traslados":
					$aux = split("[|]",$pro0);
					if(count($aux)==2)
					{//el del Impuesto general
						$r = "|" . $ctas["traslados"];// . "]";
	
					}
					else
					{//cuando es del tercero
						$r = "|" . $ctas["trasladost"] ;// . "]";
		
					}		
					break;
/*				
				case "Parte":
					$aux = split("[|]",$pro);
					if(count($aux)==2)
					{//el del Impuesto general
						$a["traslados"] +=1;
						$i=$a["traslados"];	
					}
					else
					{//cuando es del tercero
						$a["trasladost"] +=1;
						$i=$a["trasladost"];			
					}		
					break;	
*/			
				}

		return $r;
	
	}


	function cargarDatos ($c,&$ctas,$procedencia)
	{
	
		
		foreach($c as $I => $v)
		{
			//echo "<br><br>HIJO: " . $I . " => " . $v->count()	 . "<br>";
  			//var_dump($v);
	  		//echo "<br>Datos brutos: <br>";
			//var_dump($v->attributes());	
			//echo "<br>Asignacion de propiedades de $I:<br>";
			$pro0 = $procedencia . "|$I";
			//var_dump($ar);
			$this->atributos($I,$v->attributes(),$ctas,$pro0);
			$pro0 .= $this->verificaIndice($I,$pro0,$ctas);
			switch($I)
			{
			case "ComplementoConcepto":
				$xmlt = $v->children('http://www.sat.gob.mx/terceros');
			//	var_dump($xmlt);
				//$pro0 .= "|" . $ctas["complementoconcepto"];// . "]";
				//$pro0 .= verificaIndice($I,$pro0)
				$this->cargarDatos($xmlt,$ctas,$pro0);
				break;
			case "Complemento":
				$xmlt = $v->children('http://www.sat.gob.mx/TimbreFiscalDigital');
				//var_dump($xmlt);
				$this->cargarDatos($xmlt,$ctas,$pro0);
				break;
			default:
				
			

		
				//echo "<br>Desendencia de $I <br>";
				
				//echo $pro = $procedencia . "|$I";
				foreach($v as $I2 => $v2)
				{		
				//	echo "<br>$I => $I2:" . $v2->count() . "<br>";
				//	echo "Asignacion de propiedades de $I2<br>";
				//	var_dump($v2->attributes());
					$pro1=$pro0 . "|$I2";
				//	echo "<br>";
					$this->atributos($I2,$v2->attributes(),$ctas,$pro1);
					$pro1 .= $this->verificaIndice($I2,$pro1,$ctas);
				
					//$this->cargarDatos ($I2,$ar,$ctas,$pro);
					//identifico los namespaces correspondientes para poder leer los hijos que hacen falta

				//	echo "<br>default $I $I2<br>";
					$this->cargarDatos($v2,$ctas,$pro1);
	

			
				}
			}
					
	
		}


	}
	
	
	function leerarreglo (&$comp,$proc)
	{//recorre todo el arreglo de los valores obtenidos en la lectura
	//$comp= segmento del arreglo que viene de la iteración
	//$proc= Procedencia de la lectura en curso

		foreach($comp as $key => $valor)
		{
			//echo is_array($valor) . " hijos " . count($valor);
			if(is_array($valor))
			{
				$proc0= $proc . "[$key]";
				//echo "[$key]";
				$this->leerarreglo($comp[$key],$proc0);
				
			}
			else
			{
				if($valor!="")
				{
					echo $proc . "[$key] = $valor<br>";
				}
			}	

		}
	}	
	
	
	
	function leerXML($archivo)
	{
	
	/*Lectura del archivo y el hijo principal que es el COMPROBANTE
	se leen su propiedades y luego se recorren los hijos por el espacio de nombres 
	en el orden en el que fueron escritos.
	*/
	
		$url = $archivo;
		//$url = "xsdcfd.xml";
		$foo = "";
		if($d = fopen($url, "r"))
		{
 			while ($aux= fgets($d, 1024))
 		 	{
   				 $foo .= $aux;
  			}
  			fclose($d);
		}
		else
		{
  			echo "No se pudo abrir el XML";
		}

		$xml = simplexml_load_string($foo);

		//get an array of all namespaces	
		$namespaces = $xml->getNameSpaces(true); 
		//var_dump($namespaces);
		//echo "<br><br>";
		foreach($xml->attributes() as $prop => $asig)
		{
			//echo "$prop = $asig <br>\n";
			//echo "comprobante[$prop] = $asig <br><br>";
			$this->comprobante[$prop]["valor"]=$asig;
			//eval("\$this->comprobante[\$prop][\"valor\"]=\$asig;");
				
		}	

		foreach($xml->getNamespaces(true) as $key => $valor)
		{
			//echo "<br>$key -> $valor <br><br>";
	
			//lee los elementos que tienen el mismo espacio de nombres
			//echo "$key = ". $namespaces[$key] . " = $valor <br>";
			//$xml_abc = $xml->children($namespaces[$key]); 
			$xml_abc = $xml->children($valor); 	
	
			//revisarhijo($xml_abc,$prueba->comprobante);
	
			//cargarDatos ($xml_abc,$prueba->comprobante,$cuentas,"");
			
			$this->cargarDatos ($xml_abc,$this->cuentas,"");
	
			//echo "<br> etiquetas de $key ... <br>";
	/*
			foreach ($xml_abc as $item => $valor) 
			{  
  				echo "<br>$item -> ";
  				var_dump($valor);
		  		echo "<br>" . $valor->count() . "";
			} 	
	*/
	//		echo "<br>++++++++++++ fin de principal ++++++";
		}
	//	echo "<br><br> datos asignados <br>";

		//var_dump($this->comprobante);	

	//	echo "<br>";

		//$this->leerarreglo ($this->comprobante,"");
	
	}




}




//$prueba = new xmlcfd_cfdi;
//$prueba->leerXML("XCTATerREN110.xml");
//var_dump($prueba->comprobante);
//$prueba->xmlcfd_cfdi();
//var_dump($prueba->comprobante);




?>