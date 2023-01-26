<?PHP
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

//include "../insertcfdi/lxml3.php";
//Es necesario tener una conexión activa para usar el control
//ob_end_clean();
//include "phpqrcode-master/qrlib.php";   
//require('fpdf/fpdf.php');
//require('numero_a_letra.php');
//require('conexion.php');

class cfdi33class 
{
	protected $col = 0;
	protected $y0;
	//comprobante
	var $version;
	var $serie;
	var $folio;
	var $fecha;
	var $formapago;
	var $metodopago;
	var $moneda;
	var $tipocambio;
	var $nocertificado;
	var $tipocomprobante;
	var $lugarexpedicion;
	var $subtotal;
	var $total;
	var $sello;
	var $certificado;
	var $descuentog;

	//emisor
	var $emisor;
	var $emisor_rfc;
	var $emisor_nombre;
	var $emisor_regimenfiscal;

	//Receptor
	var $receptor;
	var $receptor_rfc;
	var $receptor_nombre;
	var $receptor_usocfdi;

	//relacionado
	var $cfdirelacionado;
	var $tiporelacion;
	var $cfdirelacionado_uuid;

	//Concepto
	var $conceptos;
	var $lconceptos;	
	var $cocnepto;
	var $claveprodserv;
	var $cantidad;
	var $claveunidad;
	var $unidad;
	var $descripcion;
	var $valorunitario;
	var $importe;
	var $concepto_traslados;
	var $concepto_retenciones;
	var $concepto_traslado;
	var $cocnepto_retencion;
	var $cuentapredial;
	var $descuento;

	//impuestos
	var $impuestos;
	var $totalimpuestosretenidos;
	var $totalimpuestostrasladados;
	var $impuestos_retenciones;
	var $impuestos_traslados;
	var $impuesto_retencion;
	var $impuesto_trasaldo;

	//complementoPago
	var $pago_version;
	var $pagos;
	var $pago_fechapago;
	var $pago_formadepago;
	var $pago_monedapago;
	var $pago_tipocambio;
	var $pago_monto;
	var $pago_relacionados;
	var $pago_ralacionado;
	

	//terceros
	var $estercero;
	var $terceros;
	var $tercero;
	/*
	var $terceros_version;
	var $terceros_nombre;
	var $terceros_rfc;
	var $terceros_calle;
	var $terceros_noexterior;
	var $terceros_nointerior;
	var $terceros_colonia;
	var $terceros_localida;
	var $terceros_referencia;
	var $terceros_municipio;
	var $terceros_estado;
	var $terceros_pais;
	var $terceros_codigopostal;
	var $terceros_cuentapredial;
	var $terceros_importe;
	var $terceros_iva;
	var $terceros_tasa;
*/

	var $qr;
	var $imgqr;
	
	var $xml;
	//Constructor de la clase
	function __construct ()
	{
		//$this->dirbase="/srv/wwwarchivos";

		$this->lconceptos=array();
		$this->concepto = array('claveprodserv'=>"",'cantidad'=>"",'claveunidad'=>"",'unidad'=>"",'descripcion'=>"",'valorunitario'=>"",'importe'=>"",'tra'=>array(),'ret'=>array(),'predial'=>array(),'descuento'=>0,'noidentificacion'=>"");
		$this->concepto_traslados = array();
		$this->concepto_retenciones = array();
		$this->concepto_traslado = array('base'=>"",'impuesto'=>"",'tipofactor'=>"",'tasaocuota'=>"",'importe'=>"");
		$this->concepto_retencion = array('base'=>"",'impuesto'=>"",'tipofactor'=>"",'tasaocuota'=>"",'importe'=>"");

		$this->impuesto_traslados = array();
		$this->impuesto_retenciones = array();
		$this->impuesto_traslado = array('impuesto'=>"", 'tipofactor'=>"", 'tasaocuota'=>"", 'importe'=>"");
		$this->impuesto_retencion = array('impuesto'=>"",'importe'=>"");

		$this->pago_relacionados = array();
		$this->pago_relacionado = array('folio'=>"", 'iddocumento'=>"", 'impagado'=>"",'impsaldoant'=>"",'impsaldoinsoluto'=>"", 'metodopagodr'=>"",'numparcialidad'=>"",'serie'=>"",'numerooperacion'=>"", 'rfcemisorctaord'=>"",'nombancoordext'=>"",'ctaordenante'=>"", 'rfcemisorctaben'=>"",'ctabeneficiario'=>"");

        $this->terceros=array();
        $this->tercero = array('version'=>"", 'nombre'=>"", 'rfc'=>"",'calle'=>"",'noexterior'=>"",'nointerior'=>"",'colonia'=>"",'localida'=>"",'referencia'=>"",'municipio'=>"",'estado'=>"",'pais'=>"",'codigopostal'=>"",'cuentapredial'=>"",'importe'=>"",'iva'=>"",'tasa'=>"");

		$this->totalimpuestostrasladados=0;
		$this->totalimpuestosretenidos = 0;
		$this->total = 0;
		$this->subtotal=0;

		$this->estercero=0;

		$this->emisor_rfc="DPA190408D43";
        $this->emisor_nombre="DESPACHO PADILLA &amp; BUJALIL SC";
        $this->emisor_regimenfiscal="601";		
		
		// construir todos los conceptos construyendo un arreglo
		/*
		esto agrega el traslado como un elemento de arreglo a la lista de traslados
		array_push($this->concepto_traslados, $this->concepto_traslado);

		*/
		
	}

	function emisor()
	{
	    
	    //$this->emisor_nombre = str_replace("&", "&amp;", $this->emisor_nombre );
		$this->emisor = "<cfdi:Emisor Rfc=\"" . $this->emisor_rfc ."\" Nombre=\"" . $this->emisor_nombre . "\" RegimenFiscal=\"" . $this->emisor_regimenfiscal . "\"></cfdi:Emisor>";
	}
		
	function receptor()
	{
	    $this->receptor_nombre = str_replace("&", "&amp;", $this->receptor_nombre);
		$this->receptor = "<cfdi:Receptor Rfc=\"" . $this->receptor_rfc . "\" Nombre=\"" . $this->receptor_nombre . "\" UsoCFDI=\"" . $this->receptor_usocfdi . "\"></cfdi:Receptor>";
	}	

	function cfdirelacionado()
	{
		$this->cfdirelacionado ="";
		
		if($this->cfdirelacionado_uuid!="")
		{			
			$this->cfdirelacioando ="<cfdi:CfdiRelacionados TipoRelacion=\"". $this->tiporelacion . "\"><cfdi:CfdiRelacionado UUID=\"". $this->cfdirelacionado_uuid . "\"></cfdi:CfdiRelacionado></cfdi:CfdiRelacionados>";
		}
		else
		{
			$this->cfdirelacioando ="";
		}
	}


	function conceptos()
	{
		$this->TotalImpuestosTrasladados=0;
		$this->TotalImpuestosRetenidos=0;
		$this->subtotal=0;
		$this->descuentog=0;

		$this->conceptos="<cfdi:Conceptos>";

		foreach($this->lconceptos as $d)
		{

			$importe_local = number_format($d["cantidad"] * $d["valorunitario"],2,".","");
			//$importe_local = round($d["cantidad"] * $d["valorunitario"],6);

			$desc=0;
			$descatr="";
			if($d["descuento"]>0)
			{
				$desc=$d["descuento"];
				$this->descuentog +=$d["descuento"];
				$descatr = " Descuento=\"" . number_format($d["descuento"],2,".","") . "\"";
				//$descatr = " Descuento=\"" . round($d["descuento"],6) . "\"";

			}

			$this->conceptos .="<cfdi:Concepto ClaveProdServ=\"" . $d['claveprodserv'] . "\" Cantidad=\"" . $d["cantidad"] . "\" ClaveUnidad=\"" . $d["claveunidad"] . "\" Descripcion=\"" . $d["descripcion"] . "\" ValorUnitario=\"" . number_format($d["valorunitario"],2,".","") . "\" Importe=\"" . $importe_local  . "\" $descatr>";
			//$this->conceptos .="<cfdi:Concepto ClaveProdServ=\"" . $d['claveprodserv'] . "\" Cantidad=\"" . $d["cantidad"] . "\" ClaveUnidad=\"" . $d["claveunidad"] . "\" Descripcion=\"" . $d["descripcion"] . "\" ValorUnitario=\"" . round($d["valorunitario"],6) . "\" Importe=\"" . $importe_local  . "\" $descatr>";

			$this->subtotal += $importe_local ;


			$ptraslados="";//<cfdi:Traslados>
			if(sizeof($d['tra'])>0)
			{
				$ptraslados="<cfdi:Traslados>";
				foreach($d['tra'] as $t)
				{
					if($t["tipofactor"]=="Tasa" && $t["impuesto"]="002")//tasa e iva
					{
						$t["base"] = number_format($importe_local - $desc,2,".","");
						$t["importe"] = number_format($t["base"] * (double)$t["tasaocuota"],2,".","");
						//$t["base"] =round($importe_local - $desc,6);
						//$t["importe"] = round($t["base"] * (double)$t["tasaocuota"],6);						

					}

					$ptraslados .="<cfdi:Traslado Base=\"" . $t["base"] . "\" Impuesto=\"" . $t["impuesto"] . "\" TipoFactor=\"" . $t["tipofactor"] . "\" TasaOCuota=\"" . $t["tasaocuota"] . "\" Importe=\"" . $t["importe"] . "\"></cfdi:Traslado>";

					$this->impuesto_traslado["impuesto"] =$t["impuesto"];
					$this->impuesto_traslado["tipofactor"]=$t["tipofactor"];
					$this->impuesto_traslado["tasaocuota"] =$t["tasaocuota"];
					$this->impuesto_traslado["importe"] =number_format($t["importe"],2,".","");
					//$this->impuesto_traslado["importe"] =round($t["importe"],6);
					array_push($this->impuesto_traslados, $this->impuesto_traslado);					
					$this->totalimpuestostrasladados +=number_format($t["importe"],2,".","");
					//$this->totalimpuestostrasladados +=round($t["importe"],6);
					//echo "la suma de traslados va en:$this->totalimpuestostrasladados <br>";

				}
				$ptraslados .="</cfdi:Traslados>";

			}
			$pretenciones="";//<cfdi:Traslados>

			if(sizeof($d['ret'])>0)
			{
				//var_dump($d["ret"]);
				$this->titulo=utf8_decode("Recibo de Arrendamiento");
				$pretenciones="<cfdi:Retenciones>";
				foreach($d['ret'] as $t)
				{


					if($t["tipofactor"]=="Tasa" && $t["impuesto"]=="002")//tasa e iva
					{
						//echo "para iva<br>";
						$t["base"] =  number_format($importe_local  - $desc,2,".","");
						//$t["base"] =  round($importe_local  - $desc,6);
						$t["tasaocuota"] = number_format(0.106667,6,".","");
						$t["importe"] = number_format($t["base"] * $t["tasaocuota"],2,".","");
						//$t["importe"] = round($t["base"] * $t["tasaocuota"],6);
						//echo "<br>retcion para iva base " . $t["base"] . " tasacuota: " . $t["tasaocuota"] . " importe: " . $t["importe"] . "<br>";

					}


					if($t["impuesto"]=="001")//tasa e iva
					{
						
						$t["base"] =number_format($importe_local  - $desc,2,".","") ;
						//$t["base"] =round($importe_local  - $desc,6) ;
						$t["tasaocuota"] =number_format(0.100000,6,".","");
						$t["importe"] = number_format($t["base"] * $t["tasaocuota"],2,".","");
						//$t["importe"] = round($t["base"] * $t["tasaocuota"],6);
						//echo "Retencion para para isr base " . $t["base"] . " tasacuota: " . $t["tasaocuota"] . " importe: " . $t["importe"] . "<br>";

					}					


					$pretenciones .="<cfdi:Retencion Base=\"" . $t["base"] . "\" Impuesto=\"" . $t["impuesto"] . "\" TipoFactor=\"" . $t["tipofactor"] . "\" TasaOCuota=\"" . $t["tasaocuota"] . "\" Importe=\"" . $t["importe"] . "\"></cfdi:Retencion>";


					$this->impuesto_retencion["impuesto"]=$t["impuesto"];
					$this->impuesto_retencion["importe"]=$t["importe"];
					array_push($this->impuesto_retenciones, $this->impuesto_retencion);
					$this->totalimpuestosretenidos +=$t["importe"];
					//echo "la suma de retenciones  en " . $this->impuesto_retencion["impuesto"] . " va en:$this->totalimpuestosretenidos <br>";


				}
				$pretenciones .="</cfdi:Retenciones>";
			}

			$pimpuestos="";
			if($ptraslados!="" || $pretenciones1="")
			{

				$pimpuestos .="<cfdi:Impuestos>";
				if($ptraslados!="")
				{
					$pimpuestos .=$ptraslados;
				}

				if($pretenciones!="")
				{
					$pimpuestos .=$pretenciones;
				}


				$pimpuestos .="</cfdi:Impuestos>";
			}

			$ppredial="";
			if($this->estercero == 0)
			{
				
				$ppredial="";
				if($d["predial"]!="")
				{
						$prl = preg_replace('/[a-zA-Z-\/ _]/','',$d["predial"]); //retira letras, "-", "/", " ", "_", solo deja los numeros
						$ppredial .="<cfdi:CuentaPredial Numero=\"". $prl . "\"></cfdi:CuentaPredial>";
				}
			}
			else
			{
				$nointeriort ="";


                $ppredial ="<cfdi:ComplementoConcepto>";

                foreach($this->terceros as $t)
                {

    				if($t["nointerior"]!="")
    				{
    					$nointeriort ="noInterior=\"". $t["nointerior"] ."\"";
    				}


				    $ppredial .="<terceros:PorCuentadeTerceros nombre=\"". $t["nombre"] . "\" rfc=\"". $t["rfc"] ."\" version=\"". $t["version"]."\">
               <terceros:InformacionFiscalTercero calle=\"". $t["calle"] . "\" codigoPostal=\"". $t["codigopostal"] . "\" colonia=\"". $t["colonia"] . "\" estado=\"" . $t["estado"] . "\" municipio=\"". $t["municipio"] . "\" noExterior=\"". $t["noexterior"] ."\" $nointeriort pais=\"". $t["pais"] ."\"/>
               <terceros:CuentaPredial numero=\"". 	preg_replace('/[a-zA-Z-\/ _]/','',$t["cuentapredial"])  ."\"/>
               <terceros:Impuestos>
                  <terceros:Traslados>
                     <terceros:Traslado importe=\"". $t["importe"] ."\" impuesto=\"". $t["iva"] ."\" tasa=\"". $t["tasa"] ."\"/>
                  </terceros:Traslados>
               </terceros:Impuestos>
            </terceros:PorCuentadeTerceros>";

                    
                    
                }
                $ppredial .="</cfdi:ComplementoConcepto>";
                //$this->estercero =1;
         		$pimpuestos="";




			}
/*
			$ppredial="";
			if($d["predial"]!="")
			{
				$ppredial ="<cfdi:CuentaPredial numero=\"". $d["predial"] . "\"></cfdi:CuentaPredial>";
			}
*/

			$this->conceptos .= $pimpuestos . $ppredial . "</cfdi:Concepto>";
			
		}
		$this->conceptos .="</cfdi:Conceptos>";
		$this->total = $this->subtotal + $this->totalimpuestostrasladados - $this->totalimpuestosretenidos - $this->descuentog;
		
	}


	function impuestos()
	{
		$this->impuestos="";

		//echo $this->totalimpuestostrasladados . ":" . $this->totalimpuestosretenidos . ".";
		if($this->totalimpuestostrasladados>0 || $this->totalimpuestosretenidos>0)
		{
			$listatras="";
			$listaret="";
			$itras="";
			if($this->totalimpuestostrasladados>0)
			{
				$itras=" TotalImpuestosTrasladados=\"" . number_format($this->totalimpuestostrasladados,2,".","") . "\" ";
				//$itras=" TotalImpuestosTrasladados=\"" . round($this->totalimpuestostrasladados,6) . "\" ";
				$listatras="<cfdi:Traslados>";
				//var_dump($this->impuesto_traslados);
				foreach($this->impuesto_traslados as $d)
				{
					$listatras .="<cfdi:Traslado Impuesto=\"" . $d["impuesto"] . "\" TipoFactor=\"" . $d["tipofactor"] . "\" TasaOCuota=\"" . $d["tasaocuota"] . "\" Importe=\"" . $d["importe"] . "\"></cfdi:Traslado>";

				}
				$listatras.="</cfdi:Traslados>";

			}
			$iret="";
			if($this->totalimpuestosretenidos>0)
			{
				$iret=" TotalImpuestosRetenidos=\"" . number_format($this->totalimpuestosretenidos,2,".","") . "\" ";
				//$iret=" TotalImpuestosRetenidos=\"" . round($this->totalimpuestosretenidos,6) . "\" ";


				$listaret="<cfdi:Retenciones>";
				//var_dump($this->impuesto_retenciones);
				foreach($this->impuesto_retenciones as $d)
				{
					$listaret .="<cfdi:Retencion Impuesto=\"" . $d["impuesto"] . "\"  Importe=\"" . $d["importe"] . "\"></cfdi:Retencion> ";

				}
				$listaret.="</cfdi:Retenciones>";

			}			
			$this->impuestos .="<cfdi:Impuestos $iret $itras> $listaret $listatras </cfdi:Impuestos>";




		}
	}

	function pago()
	{
		$this->pagos="";
		if($this->pago_monto>0)
		{
		    
			$tc="";
			if($this->pago_tipocambio<>"")
			{
				$tc = " TipoCambioP=\"" . $this->pago_tipocambio . "\" ";

			}

			$this->pagos = "<cfdi:Complemento><pago10:Pagos Version=\"1.0\">";
			$this->pagos .= "<pago10:Pago FechaPago=\"" . $this->pago_fechapago . "\" FormaDePagoP=\"" . $this->pago_formadepago . "\" MonedaP=\"". $this->pago_monedapago . "\"$tc Monto=\"" . number_format($this->pago_monto,2,".","") . "\">";
			//$this->pagos .= "<pago10:Pago FechaPago=\"" . $this->pago_fechapago . "\" FormaDePagoP=\"" . $this->pago_formadepago . "\" MonedaP=\"". $this->pago_monedapago . "\"$tc Monto=\"" . round($this->pago_monto,6) . "\">";

			foreach($this->pago_relacionados as $d)
			{
				$tc="";
				if($d["tc"]<>"")
				{
					$tc = " TipoCambioDR=\"" . $d["tc"] . "\" ";
				}

				$this->pagos .= "<pago10:DoctoRelacionado Serie=\"" . $d["serie"] . "\" Folio=\"" . $d["folio"] . "\" IdDocumento=\"" . $d["iddocumento"] . "\" ImpPagado=\"" .  number_format($d["impagado"],2,".","") . "\" ImpSaldoAnt=\"" . number_format($d["impsaldoant"],2,".","") . "\" ImpSaldoInsoluto=\"" . number_format($d["impsaldoinsoluto"],2,".","") . "\" MetodoDePagoDR=\"" . $d["metodopagodr"] . "\" MonedaDR=\"" . $d["moneda"] . "\"$tc NumParcialidad=\"" . $d["numparcialidad"] . "\" />";
				//$this->pagos .= "<pago10:DoctoRelacionado Serie=\"" . $d["serie"] . "\" Folio=\"" . $d["folio"] . "\" IdDocumento=\"" . $d["iddocumento"] . "\" ImpPagado=\"" .  round($d["impagado"],6) . "\" ImpSaldoAnt=\"" . round($d["impsaldoant"],6) . "\" ImpSaldoInsoluto=\"" . round($d["impsaldoinsoluto"],6) . "\" MetodoDePagoDR=\"" . $d["metodopagodr"] . "\" MonedaDR=\"" . $d["moneda"] . "\"$tc NumParcialidad=\"" . $d["numparcialidad"] . "\" />";
			}

			$this->pagos .="</pago10:Pago></pago10:Pagos></cfdi:Complemento>";

		}

	}



	function comprobante()
	{

		$this->emisor();
		$this->receptor();
		$this->cfdirelacionado();
		$this->conceptos();
		$this->impuestos();
		$this->pago();

		$this->xml ="";
		$dirpago = "";
		
		$locesquemapago ="";
		$snpago ="";
		$locequematercero = "";
		$sntercero  ="";
		
		$metodoyformadepago = "MetodoPago=\"" . $this->metodopago . "\" FormaPago=\"" . $this->formapago . "\"";
		

		
		//if($this->estercero>0)
		//echo sizeof($this->terceros);
		if(sizeof($this->terceros)>0)
		{
		    $locequematercero = " http://www.sat.gob.mx/terceros http://www.sat.gob.mx/sitio_internet/cfd/terceros/terceros11.xsd ";
			$sntercero  = " xmlns:terceros=\"http://www.sat.gob.mx/terceros\" ";
		}		

		$tc ="";
		if($this->tipocambio <>"")
		{
			$tc = " TipoCambio=\"" . $this->tipocambio . "\" ";
		}

		if($this->pago_monto>0)
		{
		    $locesquemapago = " http://http://www.sat.gob.mx/Pagos http://www.sat.gob.mx/sitio_internet/cfd/catalogos/Pagos10.xsd \" ";
			$snpago = " xmlns:pago10=\"http://www.sat.gob.mx/Pagos\"  ";
            $metodoyformadepago ="";
            $tc ="";
		}

		$descgral ="";
		if($this->descuentog >0)
		{
			$descgral =" Descuento=\"" . number_format($this->descuentog,2,".","") . "\" ";
			//$descgral =" Descuento=\"" . round($this->descuentog,6) . "\" ";
		}

		$this->subtotal=number_format((double)$this->subtotal,2,".","");
		$this->total=number_format($this->total,2,".","");
		//$this->subtotal=round((double)$this->subtotal,6);
		//$this->total=round($this->total,6);		
		$this->xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><cfdi:Comprobante xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:cfdi=\"http://www.sat.gob.mx/cfd/3\" $snpago $sntercero  xsi:schemaLocation=\"http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd $locesquemapago $locequematercero\"  LugarExpedicion=\"" . $this->lugarexpedicion . "\" $metodoyformadepago TipoDeComprobante=\"" . $this->tipocomprobante . "\" SubTotal = \"" . $this->subtotal . "\" $descgral Total=\"" . $this->total . "\" Moneda=\"" . $this->moneda . "\"$tc NoCertificado =\"" . $this->nocertificado . "\" Certificado=\"\"  Sello=\"\" Fecha=\"" . $this->fecha . "\" Folio=\"" . $this->folio . "\" Serie=\"" . $this->serie . "\" Version=\"3.3\" >\n";
		$this->xml .=$this->emisor . "\n";
		$this->xml .=$this->receptor  . "\n";
		$this->xml .=$this->cfdirelacioando  . "\n";
		$this->xml .=$this->conceptos  . "\n";
		$this->xml .=$this->impuestos  . "\n";
		$this->xml .=$this->pagos  . "\n";
		$this->xml .="</cfdi:Comprobante>";
		$this->xml = utf8_encode($this->xml);
		//echo "<textarea>$this->xml</textarea>";

	}

	

    function limpiar_objeto()
    {


		$this->version="";
		$this->serie="";
		$this->folio="1";
		$this->fecha="";
		$this->formapago="";
		$this->metodopago="";
		$this->moneda="";
		$this->tipocambio = "";
		$this->nocertificado="";
		$this->tipocomprobante="";
		$this->lugarexpedicion="";
		$this->subtotal="";
		$this->total="";
		$this->sello="";
		$this->certificado="";
		$this->descuentog="";

		$this->emisor_rfc="";
		$this->emisor_nombre="";
		$this->emisor_regimenfiscal="";

		$this->receptor_rfc="";
		$this->receptor_nombre="";
		$this->receptor_usocfdi="";

		//para la relacion cuando es nota de credto
		$this->cfdirelacionado_uuid="";
		$this->tiporelacion="";

		//arreglo para los traslados del concepto
		
		$this->concepto_traslados = array();
		$this->concepto_retenciones = array();

		$this->concepto_traslado = array('base'=>"",'impuesto'=>"",'tipofactor'=>"",'tasaocuota'=>"",'importe'=>"");
		$this->concepto_retencion = array('base'=>"",'impuesto'=>"",'tipofactor'=>"",'tasaocuota'=>"",'importe'=>"");

		//Arreglo para los conceptos, aqui se integran los arreglso anteriores de traslado y de retenciones
		$this->concepto = array('claveprodserv'=>"",'cantidad'=>"",'claveunidad'=>"",'unidad'=>"",'descripcion'=>"",'valorunitario'=>"",'importe'=>"",'tra'=>array(),'ret'=>array(),'predial'=>array(),'descuento'=>0);
		$this->lconceptos=array();


		$this->impuesto_traslados = array();
		$this->impuesto_retenciones = array();
		$this->impuesto_traslado = array('impuesto'=>"", 'tipofactor'=>"", 'tasaocuota'=>"", 'importe'=>"");
		$this->impuesto_retencion = array('impuesto'=>"",'importe'=>"");
		$this->totalimpuestostrasladados=0;
		$this->totalimpuestosretenidos=0;
		$this->descuentog="";

		//información genral del pago
		$this->pago_fechapago = "";
		$this->pago_monto = 0;
		$this->pago_monedapago	="";
		$this->pago_tipocambio	="";
		$this->pago_formadepago	="";

		//Arreglo para los elementos derelación del pago
		$this->pago_relacionado = array('folio'=>"", 'iddocumento'=>"", 'impagado'=>"",'impsaldoant'=>"",'impsaldoinsoluto'=>"", 'metodopagodr'=>"",'numparcialidad'=>"",'serie'=>"");
		$this->pago_relacionados = array();
    }		



    function preparar_ingreso($id,$cat,$row,$folio,$serie,$terceros,$int,$nc)
    {
		//$id = id del historial
		//$cat = catalogo de la lista requerida por el PAC.. ya no se usara, era para verificar lista de conceptos
		//$row = recordset de la consulta de la base de datos
		//$folio = folio de la factura
		//$serie = serie de la factura
		//$terceros = boleano para saber si es de terceros
		//$int = para ver si es interes
		//$nc = si es de nota de credito
        
        $notanc="";
        
        //obtengo los importes reales para facturar
		if($row["tipofactura"]=='PPD' && $row["aplicado"]==0){
			$sqlcal = "SELECT idcontrato, SUM(cantidad) as totalp, iva as ivap FROM historia WHERE idhistoria=$id GROUP BY idcontrato";
		}else{
			$sqlcal = "select idcontrato, SUM(cantidad) as totalp, iva as ivap from historia where parcialde = " . $row['parcialde'] . " group by idcontrato";
		}
		//echo $sqlcal;
		
		$operaciontcal = mysql_query($sqlcal);
		$rcal =mysql_fetch_array($operaciontcal);
		
		$cantidadp = $rcal["totalp"];
		$ivatasa = $rcal["ivap"] /100;
		//$ivap = $rcal["ivap"]; //es concepto de importe de iva.
		//$ivap = $cantidadp * $ivatasa; //es el iva en entero, hay que pasarlo a taza.
		$cantidadp = $cantidadp /(1 + $ivatasa ); // valor ya sin iva
		$ivap = $rcal["totalp"] - $cantidadp; //el importe del iva.
		
		//si es nota de credito
		if($nc>0)
		{
			//hace falta recuperar eldato de uuid relacionado y el tipo de relacion

			$sqlcal = "select idcontrato,notanc, SUM(cantidad) as totalp, iva as ivap from historia where notacredito = 1 and idhistoria = $nc and parcialde = " . $row['parcialde'] . " group by idcontrato,notanc";
			$operaciontcal = mysql_query($sqlcal);
			$rcal =mysql_fetch_array($operaciontcal);			 
			
			//$cantidadp = $rcal["totalp"];
			$cantidadp = ($rcal["totalp"] / (1+$ivatasa));
			$ivap = $rcal["totalp"] - $cantidadp ;
			$notanc=$rcal["notanc"];
			
		}
		
		$resultado = "";
		$Tercerosbloques="";
		
        if($terceros==1)
        {
			//terceros
			$this->terceros=array();
			$sqlt = "select * from duenioinmueble di, contrato c, inmueble i, duenio d where di.idduenio = d.idduenio and di.idinmueble=i.idinmueble and c.idinmueble = i.idinmueble and c.idcontrato = " . $row["idc"];
			$operaciont = mysql_query($sqlt);
			while($rowt = mysql_fetch_array($operaciont))
			{
			    $this->tercero["rfc"]=$this->limpiar($rowt["rfcd"]);
			    $this->tercero["nombre"] = $this->limpiar($rowt["nombre"]) . " " . $this->limpiar($rowt["nombre2"]) . " " . $this->limpiar($rowt["apaterno"]) . " " . $this->limpiar($rowt["amaterno"]);
			    $this->tercero["call"] =  $this->limpiar($rowt["called"]);
			    $this->tercero["noexterior"] = $this->limpiar($rowt["noexteriord"]);
			    $this->tercero["nointerior"]= $this->limpiar($rowt["nointeriord"]) ;
			    $this->tercero["colonia"]=$this->limpiar($rowt["coloniad"]);
			    $this->tercero["municipio"] =$this->limpiar($rowt["delmund"]);
			    $this->tercero["estado"]=$this->limpiar($rowt["estadod"]);
			    $this->tercero["pais"]=$this->limpiar($rowt["paisd"]);
			    $this->tercero["codigopostal"]= $this->limpiar($rowt["cpd"]);
			    $this->tercero["predial"]=$rowt["predial"];
			    //$this->tercero["tasa"] = number_format(($ivap/100),6,".","");
			    $this->tercero["tasa"] = number_format(($ivatasa),6,".","");
			    $this->tercero["iva"]="IVA";
			    
                $partet=0;
				if(is_null($rowt["porcentaje"])==true || $rowt["porcentaje"]==0)
				{
					$partet=1;
				}
				else
				{
				    $partet=$rowt["porcentaje"]/100;	
				}
    
                if($ivap>0)
				{
					$ivav=1;
				}
				
                 $this->tercero["importe"] =  number_format(($ivap*$partet),2,".","");
                 array_push($this->terceros,  $this->tercero);
			    
			}
        }
        
        
        //$this->tipocomprobante = //se agrega desde el archivo de reporte2.php
        $this->version = "3.3";
        $this->serie = $serie;
        $this->folio = $folio;
        $this->fecha = date("Y-m-d") . "T" . date("H:m:s");
        $this->moneda = "MXN";
        $this->lugarexpedicion = "06470";
	    switch($this->tipocomprobante)
		{
		case '1': //ingreso
			$this->tipocomprobante= "I";
			break;
		case '2'://egreso
				$this->tipocomprobante="E";
		    break;
        case '3'://pago
				$this->tipocomprobante= "P";
		    break;		    
		 default:
				$this->tipocomprobante="" ;
		}        
        
        if($this->tipocfd==2 || $this->tipocfd=="2")
		{
		    $this->metodopago = "PUE";
		    $this->formapago = 15;
		}
		elseif(@$row["tipofactura"]=='PUE')
		{
		    $this->metodopago = "PUE";
            $sqlmp="select * from historia h, metodopago m where h.idmetodopago = m.idmetodopago and idhistoria = " . $row["parcialde"];
			$operacionmp = mysql_query($sqlmp);
			while ($rowmp = mysql_fetch_array($operacionmp))
			{
			    $this->formapago = $rowmp["clavefpagosat"];
			}		    
		    
		}
        elseif(@$row["tipofactura"]=='PPD')
        {
            $this->metodopago = "PPD";
            $this->formapago = "99";
        }
        else
        {
            $this->metodopago = "PUE";
			$this->formapago = "01";
		}
		
        $rf =$row["rfc"];
        if (strlen($row["rfc"])<12)
		{
		    $rf="XAXX010101000";
		}

        $this->receptor_rfc=$this->limpiar($rf);
        $this->receptor_nombre=$this->limpiar($row["nombrei"]) . " " . $this->limpiar($row["nombre2i"]) . " " . $this->limpiar($row["apaternoi"]) . " " . $this->limpiar($row["amaternoi"]);
        $this->receptor_usocfdi=$row["claveucfdi"];
        

		$mes = "";
		switch($row["mes"])
		{
		case 1: //enero
			$mes = "ENERO";
			break;
		case 2:
			$mes = "FEBRERO";
			break;
		case 3:
			$mes = "MARZO";
			break;
		case 4:
			$mes = "ABRIL";
			break;
		case 5:
			$mes = "MAYO";
			break;
		case 6:
			$mes = "JUNIO";
			break;
		case 7:
			$mes = "JULIO";
			break;
		case 8:
			$mes = "AGOSTO";
			break;
		case 9:
			$mes = "SEPTIEMBRE";
			break;
		case 10:
			$mes = "OCTUBRE";
			break;
		case 11:
			$mes = "NOVIEMBRE";
			break;
		case 12:
			$mes = "DICIEMBRE";
			break;						
		}
	
		$txtinteres="";
		if($int==1)
		{
			$txtinteres=" INTERESES DE ";
		}
	
		switch($this->tipocfd)
		{
		case 2:
			$nc = "($notanc)";
			break;
		default:
			$nc = "";
		}




        if($ivap>0)
        {
            //arreglo para los traslados del concepto
            //$this->concepto_traslado["base"] = number_format( ($cantidadp-$ivap),2,".","");
            $this->concepto_traslado["base"] = number_format( ($cantidadp),2,".","");
            $this->concepto_traslado["impuesto"] = "002";
            $this->concepto_traslado["tipofactor"] = "Tasa";
            $this->concepto_traslado["tasaocuota"] = number_format($ivatasa,6,".","" );
            $this->concepto_traslado["impordte"] = round((double)$cfdi->concepto_traslado["base"] * (double)$cfdi->concepto_traslado["tasaocuota"] ,2);
            
            array_push($this->concepto_traslados, $this->concepto_traslado);
        }
        
        //Arreglo para los conceptos, aqui se integran los arreglso anteriores de traslado y de retenciones
        $this->concepto["claveprodserv"] = $row["claveps"];
        $this->concepto["cantidad"] =1;
        $this->concepto["claveunidad"] =$row["claveum"];
        //$cfdi->concepto["unidad"] ="pieza";
        $this->concepto["descripcion"] =$row["idc"] . ":$txtinteres" .  $row["tipocobro"] . " CORRESPONDIENTE AL " . $row["dia"] . " DE $mes DE " .  $row["anio"]    . " de " .  $this->limpiar( $row["callein"]    . " " .  $row["noextin"]    . " " .  $row["nointin"])    . " $nc. " . $row["observaciones"];
        //$this->concepto["valorunitario"] =number_format( ($cantidadp-$ivap),2,".","");
        $this->concepto["valorunitario"] =number_format( ($cantidadp),2,".","");
        $this->concepto["noidentificacion"] =$row["idc"];
        $this->concepto["importe"] =$this->concepto["cantidad"]*$this->concepto["valorunitario"];
        $this->concepto["descuento"] =0;
        $this->concepto["predial"] =$row["predial"];
        if(sizeof($this->concepto_traslados)>0)
        {
            $this->concepto["tra"] =$this->concepto_traslados;
        }
        //$cfdi->concepto["ret"] =$cfdi->concepto_retenciones;
        array_push($this->lconceptos, $this->concepto);



    }
    
    
    function preparar_pago($id,$cat,$row,$folio,$serie)
    {
    	//$id = id del historial
    	//$cat = catalogo de la lista requerida por el PAC simpre 1 //ya no se usara, puede ir en blanco
    	//$row = recordset de la consulta de la base de datos
    	//$folio = folio de la factura
    	//$serie = serie de la factura   
    	
    	//obtengo los importes reales para facturar
		$sqlcal = "SELECT * FROM historia WHERE idhistoria =$id";
		$operaciontcal = mysql_query($sqlcal);
		$rcal =mysql_fetch_array($operaciontcal);
    	
		$cantidadp = $rcal["cantidad"];
		if($row["idtipocontrato"]==2 || $row["inth"]==1)//local comercial o interes
		{
			$subtotalp = ($cantidadp / 1.16);	
			$ivap = ($subtotalp * 0.16);	
		}
		else
		{
			$ivap =0;	
		}    
		
		
        $sqlDocRel = "SELECT * FROM facturacfdi f, historiacfdi h WHERE f.idfacturacfdi=h.idfacturacfdi AND h.idhistoria=".$row["parcialde"];
		$operacionDocRel = mysql_query($sqlDocRel);
		$rowDocRel = mysql_fetch_array($operacionDocRel);
		$archivoxml = $rowDocRel["archivoxml"];		

        $xmlcfdi = new xmlcfd_cfdi;
		
		$dir="/home/wwwarchivos/cfdi/"; //hubicacion donde se encuentra la carpeta de las facturas  hay que cambiarla
		$dir .= $archivoxml;
		
		$xmlcfdi->leerXML($dir);

		$xmlAll=$xmlcfdi->comprobante;
		$xmlUUID = $xmlAll["Complemento"]["TimbreFiscalDigital"]["UUID"]["valor"];
		$textUUID = $xmlUUID->__toString();
		
		$serieDocRel = $xmlcfdi->comprobante["Serie"]["valor"];
		$folioDocRel = $xmlcfdi->comprobante["Folio"]["valor"];
		$uuidDocRel = $textUUID;		
		
        if(($serieDocRel=='' || $serieDocRel==NULL) && ($folioDocRel=='' || $folioDocRel==NULL) && ($uuidDocRel=='' || $uuidDocRel==NULL))
        {
			exit();
		}
		
		//Numero de pago, saldo anterior facturar lo obtiene del concepto de notas, es el ultimo dato de la nota
		$sqlNumPagos = "SELECT * FROM historia WHERE idhistoria<=$id AND parcialde =".$row["parcialde"]. " ORDER BY idhistoria";
		$operacionNumPagos = mysql_query($sqlNumPagos);
		$numPagos=0;
		$cantidadAnterior=0;
		while ($rowNumPagos =mysql_fetch_array($operacionNumPagos))
		{
			$numPagos ++;
			if($rowNumPagos["idhistoria"]==$id)
			{
				if(substr($rowNumPagos["notas"],0,9)=="LIQUIDADO")
				{
					$saldoAnt=$rowNumPagos["cantidad"];
					$impPagado=$rowNumPagos["cantidad"];
					$saldoInsoluto=0.00;
				}
				else
				{
					$pesitos=strpos($rowNumPagos["notas"], "\$");
					$saldoInsoluto=(substr($rowNumPagos["notas"],($pesitos+1)))*1;
					$impPagado=$rowNumPagos["cantidad"];
					$saldoAnt= $impPagado +	$saldoInsoluto;
				}
			}
		}
		
		if($this->tipocomprobante==3)
		{
		    $this->tipocomprobante="P";
		}
		
        $this->version = "3.3";
        $this->serie = $serie;
        $this->folio = $folio;
        $this->fecha = date("Y-m-d") . "T" . date("H:m:s");		
		$this->lugarexpedicion = "06470";
		$this->moneda = "XXX";

        $rf =$row["rfc"];
        if (strlen($row["rfc"])<12)
		{
		    $rf="XAXX010101000";
		}    

        $this->receptor_rfc=$this->limpiar($rf);
        $this->receptor_nombre=$this->limpiar($row["nombrei"]) . " " . $this->limpiar($row["nombre2i"]) . " " . $this->limpiar($row["apaternoi"]) . " " . $this->limpiar($row["amaternoi"]);
        $this->receptor_usocfdi="P01";

        
        $this->version = "1.0";
        $this->pago_fechapago =  $row["fechapago"] . "T" . date("H:m:s");
        $this->pago_monto = number_format($cantidadp,2,".","");
        $this->pago_monedapago	="MXN";
        $this->pago_tipocambio	="1";
        
        $formap="";
		$sqlmp="select * from historia h, metodopago m where h.idmetodopago = m.idmetodopago and idhistoria=$id";
		$operacionmp = mysql_query($sqlmp);
		while ($rowmp = mysql_fetch_array($operacionmp)) 
		{
			$formap =$rowmp["clavefpagosat"];
		}
		if($formap=="")
		{
			$formap = "01";
		}
      
        
        $this->pago_formadepago	=$formap;
        
        //Arreglo para los elementos derelación del pago
        $this->pago_relacionado["folio"]=$folioDocRel;
        $this->pago_relacionado["iddocumento"]=$uuidDocRel;
        $this->pago_relacionado["impagado"]=number_format($impPagado,2,".","");
        $this->pago_relacionado["impsaldoant"]=number_format($saldoAnt,2,".","");
        $this->pago_relacionado["impsaldoinsoluto"]=number_format($saldoInsoluto,2,".","");
        $this->pago_relacionado["metodopagodr"]="PPD";
        $this->pago_relacionado["numparcialidad"]=$numPagos;
        $this->pago_relacionado["serie"]=$serieDocRel;
        $this->pago_relacionado["moneda"]="MXN";
        $this->pago_relacionado["tc"]="1";
        
        array_push($this->pago_relacionados, $this->pago_relacionado);



        $this->concepto["claveprodserv"] ="84111506";
        $this->concepto["cantidad"] =1;
        $this->concepto["claveunidad"] ="ACT";
        $this->concepto["unidad"] ="";
        $this->concepto["descripcion"] ="Pago";
        $this->concepto["valorunitario"] =0;
        //$cfdi->concepto["importe"] =1;
        $this->concepto["importe"] =$this->concepto["cantidad"]*$this->concepto["valorunitario"];
        $this->concepto["descuento"] =0;
        $this->concepto["predial"] ="";
        array_push($this->lconceptos, $this->concepto);    


		
        
    }
    
    
    
    
	function limpiar ($txt)
	{
		$r = trim($txt);
		$r=str_replace(",", "",$r);
		$r=str_replace("<br>", "",$r) ;
		$r=str_replace("<b>", "",$r) ;
		$r=str_replace("<BR>", "",$r) ;
		$r=str_replace("<B>", "",$r) ;
		$r=str_replace("*", "&",$r) ;
		$r=str_replace("/", "&",$r) ;
		
		//$r=utf8_decode($r);
		return $r;
	
	}  
	
	function timbrar($filtro, $id)
	{
	    
	    
	    try
		{		
            $jso = base64_encode(trim($this->xml));
			//Prepara el xml para el envio al webservices

          
          $xml ='<soapenv:Envelope xmlns:get="http://ws.seres.com/wsdl/20150301/GetCFDi/" xmlns:soapenv="http://www.w3.org/2003/05/soap-envelope">
   <soapenv:Header>
   <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
   <wsse:UsernameToken wsu:Id="UsernameToken-102">
   <wsse:Username>usr.wsDPA190408D43</wsse:Username>
   <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">40-2O0Op</wsse:Password>
   </wsse:UsernameToken>
   </wsse:Security>
   </soapenv:Header>

   <soapenv:Body>
      <get:publishDocument>
         <parameters>
            <EFacturaService>MX</EFacturaService>
            <TaxIdentification>
               <TaxIdCountry>MX</TaxIdCountry>
               <TaxIdNumber>DPA190408D43</TaxIdNumber>
            </TaxIdentification>
            <InputFile>';
          	$xml.=str_replace(" ","+",$jso);
          	$xml.='</InputFile>
            <PDFFile>S</PDFFile>
         </parameters>
      </get:publishDocument>
   </soapenv:Body>
</soapenv:Envelope>';
          
          
          

            //echo "<textarea cols='200' rows='50'>$xml</textarea>";
  			//crea el objeto soap para la transferencis del webservices
			$soap_do = curl_init(); 
			//https://mexico.e-factura.net/SeresWS-MX-GetCFDi/services/GetCFDi?wsdl
  			curl_setopt($soap_do, CURLOPT_URL, "https://mexico.e-factura.net/SeresWS-MX-GetCFDi/services/GetCFDi?wsdl");   
  			//curl_setopt($soap_do, CURLOPT_URL, "https://test-mexico.e-factura.net:82/SeresWS-MX-GetCFDi/services/GetCFDi?wsdl"); 
  			curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 100); 
  			curl_setopt($soap_do, CURLOPT_TIMEOUT,        100); 
  			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true );
  			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);  
  			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); 
  			curl_setopt($soap_do, CURLOPT_POST,           true ); 
  			curl_setopt($soap_do, CURLOPT_POSTFIELDS,    $xml); 
  			curl_setopt($soap_do, CURLOPT_HTTPHEADER,     array('Content-Type: text/xml; charset=utf-8', 'Content-Length: '.strlen($xml) )); 
  			///curl_setopt($soap_do, CURLOPT_USERPWD, $user . ":" . $password);
  			
  			//Ejecuta la transmision del xml y lo entrega en result
  			$result = curl_exec($soap_do);
  			
  			//Ojo, podemos mejorar la resputa si capturamos en $result el estado
  			//del xml regresado y todo loque sea diferente de estado 1 es un error
  			//en el timbrado el cual no es un error de comunicacion
  			
  			//Este obtiene un error grabe en la conexión
  			$err = curl_error($soap_do); 
  			
  			
            if(strrpos($result,"<OutputFile>")>0) 
            {// timbre satisfactorio
            	$s="1|";
            	$estatus="Act";
            	
            	//procesa los archivos y los guarda
            	$string=$result;
                $pattern = "/<OutputFile>(.*?)<\/OutputFile>/";                
                preg_match($pattern, $string, $matches);
                $xml=$matches[1];
    		    $pattern = "/<OutputPDFFile>(.*?)<\/OutputPDFFile>/";                
                preg_match($pattern, $string, $matches);
                $pdf=$matches[1];  			
      			
                //decodifica el xml para poder guardarlo
    	        $xml_decoded = base64_decode ($xml);
                $ruta_origen = "/home/rentaspb/contenedor/cfdi"; //carpeta privada para los documentos
    		    if(!is_dir($ruta_origen))
    			    mkdir( $ruta_origen,0777);
    		    $xml_archivo = $ruta_origen ."/".$this->serie.$this->folio.".xml";
    		    $xml = fopen ( $xml_archivo,'w');
    		    fwrite ($xml,$xml_decoded);
    		    fclose ($xml);
    		    //guarda el pdf
    	        $pdf_decoded = base64_decode ($pdf);
    		    $pdf_archivo = $ruta_origen ."/".$this->serie.$this->folio.".pdf";
    		    $pdf = fopen ( $pdf_archivo,'w');
    		    fwrite ($pdf,$pdf_decoded);
    		    fclose ($pdf);
    		    //guarda el resultado en txt
    		    $txt_archivo = $ruta_origen ."/".$this->serie.$this->folio.".txt";
    		    $txt = fopen ( $txt_archivo,'w');
    		    fwrite ($txt,$result);
    		    fclose ($txt);            	
            	
            	//regresa los nombres de los archivos apra ser procesados al guardar los en la base de datos
            	
           	
                switch($filtro)
        		{
        		case 1://factura libre
        			echo "libre:<br>";
        			//registros de la factura libre
        			//crear registro en facturacfdi
        			$sqlftp="insert into facturacfdi (archivotxt, txtok ,archivopdf , pdfok, archivoxml, xmlok, tipofactura, serie, folio, fecha, subtotal, retenciones, traslados, total)values('$txt_archivo',1, '$pdf_archivo',1,'$xml_archivo',1,'$this->tipocomprobante','$this->serie',$this->folio,'". date("Y-m-d", $this->fecha) ."',$this->subtotal, $this->totalimpuestosretenidos,$this->totalimpuestostrasladados,$this->total)"; 
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
        			$sqlftp="insert into facturacfdi (archivotxt, txtok ,archivopdf , pdfok, archivoxml, xmlok, tipofactura, serie, folio, fecha, subtotal, retenciones, traslados, total)values('$txt_archivo',1, '$pdf_archivo',1,'$xml_archivo',1,'$this->tipocomprobante','$this->serie',$this->folio,'". date("Y-m-d", $this->fecha) ."',$this->subtotal, $this->totalimpuestosretenidos,$this->totalimpuestostrasladados,$this->total)"; 
        			$operacion = mysql_query($sqlftp);
        		
        			//tomar idgenerado
        			$idcfdi=mysql_insert_id();
        				
        		
        			//crear la relación de historiacfdi
        			$sqlftp="insert into facturacfdid (idcfdiedoduenio, idfacturacfdi) values ($id,$idcfdi)"; 
        			$operacion = mysql_query($sqlftp);				
        		
        		
        			break;
        
        		
        		default:
        
        			//crear registro en facturacfdi
        			//$sqlftp="insert into facturacfdi (archivotxt, txtok ,archivopdf , pdfok, archivoxml, xmlok, tipofactura, serie, folio, fecha, subtotal, retenciones, traslados, total)values('$txt_archivo',1, '$pdf_archivo',1,'$xml_archivo',1,'$this->tipocomprobante','$this->serie',$this->folio,'". date("Y-m-d", $this->fecha) ."',$this->subtotal, $this->totalimpuestosretenidos,$this->totalimpuestostrasladados,$this->total)"; 
        			$sqlftp="insert into facturacfdi (archivotxt, txtok ,archivopdf , pdfok, archivoxml, xmlok, tipofactura, serie, folio, fecha, subtotal, retenciones, traslados, total)values('$txt_archivo',1, '$pdf_archivo',1,'$xml_archivo',1,'$this->tipocomprobante','$this->serie',$this->folio,'". date_format(date_create($this->fecha),"Y/m/d H:i:s") ."',$this->subtotal, $this->totalimpuestosretenidos,$this->totalimpuestostrasladados,$this->total)"; 
        			
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
        		
        		$s="$pdf_archivo|$xml_archivo";
		            	
            	
            	
            	
            }
            elseif($err!='')
            {//error externo
                $s="c|".$err;
               
            }
            else
            {//resultado de error en timbrado
                $inicioCode=strrpos($result,"<ResultMessage>") + 15;
                $finalCode=strrpos($result,"</ResultMessage>");
                $cError=str_replace("'", "-",substr($result,$inicioCode,($finalCode - $inicioCode)));
                //$s="f|".str_replace('"', '-',$cError);
                $s="f|".str_replace('"', '-',$result);
                
            }  	  			
  			
  			
  			

		    return $s;
		    
		    
		}
		catch(Exception $e)
		{
			echo "Ocurrio el siguiente error: $e";
		}	    
	    
	    
	    
	    
	}
    


}







//+++++++ppara el ejercicio de generacion+++++++
/*
$cfdi = New cfdi33class;


$cfdi->version="3.3";
$cfdi->serie="A";
$cfdi->folio="1";
$cfdi->fecha="2019-12-15T10:00:00";
$cfdi->formapago="03";
$cfdi->metodopago="PUE";
$cfdi->moneda="MXN";
$cfdi->tipocambio = "1";
$cfdi->nocertificado="00001000000414169529";
$cfdi->tipocomprobante="I";
$cfdi->lugarexpedicion="03630";
$cfdi->subtotal="1";
$cfdi->total="1.16";
$cfdi->sello="";
$cfdi->certificado="";


$cfdi->emisor_rfc="SOSL7806164K2";
$cfdi->emisor_nombre="LUIS ANTONIO SOLIS SCHROEDER";
$cfdi->emisor_regimenfiscal="621";

$cfdi->receptor_rfc="XAXX010101000";
$cfdi->receptor_nombre="PUBLICO EN GENERAL";
$cfdi->receptor_usocfdi="G03";


//para la relacion cuando es nota de credto
$cfdi->cfdirelacionado_uuid="eluuidrelacionado";
$cfdi->tiporelacion="01";


//arreglo para los traslados del concepto
$cfdi->concepto_traslado["base"] = 1;
$cfdi->concepto_traslado["impuesto"] = "002";
$cfdi->concepto_traslado["tipofactor"] = "Tasa";
$cfdi->concepto_traslado["tasaocuota"] = number_format((16/100),6 );
$cfdi->concepto_traslado["impordte"] = $cfdi->concepto_traslado["base"] * $cfdi->concepto_traslado["tasaocuota"] ;
array_push($cfdi->concepto_traslados, $cfdi->concepto_traslado);


//arreglo para las retenciones del concepto este es el caso para iva
$cfdi->concepto_retencion["base"] =100;
$cfdi->concepto_retencion["impuesto"] ="002";
$cfdi->concepto_retencion["tipofactor"] ="Tasa";
$cfdi->concepto_retencion["tasaocuota"] =number_format(((.16/3)*2),6);
$cfdi->concepto_retencion["importe"] =$cfdi->concepto_retencion["base"]*$cfdi->concepto_retencion["tasaocuota"];
array_push($cfdi->concepto_retenciones, $cfdi->concepto_retencion);

//arreglo para la retención del concepto, este es el caso para isr
$cfdi->concepto_retencion["base"] = 100;
$cfdi->concepto_retencion["impuesto"] ="001";
$cfdi->concepto_retencion["tipofactor"] ="Tasa";
$cfdi->concepto_retencion["tasaocuota"] =number_format(0.1,6);
$cfdi->concepto_retencion["importe"] =$cfdi->concepto_retencion["base"] * $cfdi->concepto_retencion["tasaocuota"];
array_push($cfdi->concepto_retenciones, $cfdi->concepto_retencion);


//Arreglo para los conceptos, aqui se integran los arreglso anteriores de traslado y de retenciones
$cfdi->concepto["claveprodserv"] ="80131500";
$cfdi->concepto["cantidad"] =1;
$cfdi->concepto["claveunidad"] ="E48";
$cfdi->concepto["unidad"] ="pieza";
$cfdi->concepto["descripcion"] ="prueba";
$cfdi->concepto["valorunitario"] =1;
//$cfdi->concepto["importe"] =1;
$cfdi->concepto["importe"] =$cfdi->concepto["cantidad"]*$cfdi->concepto["valorunitario"];
$cfdi->concepto["descuento"] =0;
$cfdi->concepto["predial"] ="abc123890";
$cfdi->concepto["tra"] =$cfdi->concepto_traslados;
//$cfdi->concepto["ret"] =$cfdi->concepto_retenciones;
array_push($cfdi->lconceptos, $cfdi->concepto);


$cfdi->estercero = 1;
$cfdi->terceros_version="1.1";
$cfdi->terceros_nombre="nombre del tercero";
$cfdi->terceros_rfc="rfctercero";
$cfdi->terceros_calle="calle tercero";
$cfdi->terceros_noexterir="noexteriort";
$cfdi->terceros_nointerior="nointeriort";
$cfdi->terceros_colonia="coloniatercero";
//$cfdi->terceros_localida=""
//$cfdi->terceros_referencia;
$cfdi->terceros_municipio="municipio tercero";
$cfdi->terceros_estado="ciudad de mexico";
$cfdi->terceros_pais="pais";
$cfdi->terceros_codigopostal="03300";
$cfdi->terceros_cuentapredial="65asd654";
$cfdi->terceros_importe=16;
$cfdi->terceros_iva="IVA";
$cfdi->terceros_tasa=0.16;




//información genral del pago
$cfdi->pago_fechapago = "2019-00-01T01:01:00";
$cfdi->pago_monto = 1.16;
$cfdi->pago_monedapago	="MXN";
$cfdi->pago_tipocambio	="1";
$cfdi->pago_formadepago	="03";

//Arreglo para los elementos derelación del pago
$cfdi->pago_relacionado["folio"]="2";
$cfdi->pago_relacionado["iddocumento"]="uuid del documento";
$cfdi->pago_relacionado["impagado"]="1.16";
$cfdi->pago_relacionado["impsaldoant"]="1.16";
$cfdi->pago_relacionado["impsaldoinsoluto"]="0";
$cfdi->pago_relacionado["metodopagodr"]="PPD";
$cfdi->pago_relacionado["numparcialidad"]="1";
$cfdi->pago_relacionado["serie"]="A";
$cfdi->pago_relacionado["moneda"]="MXN";
$cfdi->pago_relacionado["tc"]="1";

array_push($cfdi->pago_relacionados, $cfdi->pago_relacionado);



$cfdi->comprobante();

echo "<textarea cols='200' rows='50'>$cfdi->xml</textarea>";

$verificar = $cfdi->timbrar('',0);
echo "<textarea cols='200' rows='50'>$verificar</textarea>";
//+++++++++++++++++++++++++++++
*/

?>


