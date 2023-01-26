<?php
//Clase para generar información para intermediario del buro

//Solo falta agregar la restricción para el tipocategoria (principal, accesorio, accesioro nf)
// requiere de que ya exista una conexio na la base de datos de bujalil
class burodatosclass
{
	var $PF;
	var $PM;
	var $fecharp;
	
	//para persona fisica

	
	//personas morales
	var $rfcpm;
	var $compania;
	var $nombre1pm;
	var $nombre2pm;
	var $apaternopm;
	var $amaternopm;
	var $direccion;
	var $colonia;
	var $fechaaperturapm;
	var $plazo;
	var $saldoinicial;
	var $fechaliquidacion;
	var $diasvencimiento;
	var $cantidadpm;
	
	

	function burodatosclass()
	{
	
		//$this->PF="FECHA DE REPORTE|PATERNO|MATERNO|PRIMER NOMBRE|SEGUNDO NOMBRE|RFC|FECHA DE NACIMIENTO|CALLE Y NUMERO|Codigo Postal|Numerro de Crédito|RESPONSABILIDAD|NUM PAGOS|FRECUENCIA DE PAGO|IMPORTE|FECHA APERTURA|FECHA DE ULTIMO PAGO|CREDITO OTORGADO|SALDO ACTUAL|SALDO VENCIDO|DIAS VENCIDOS\n";
		$this->PF="NOMBRE COMPLETO|FECHA DE NACIMIENTO|RFC|ESTADO CIVIL|SEXO|PREFEDION U OCUPACION|TELEFONO|DOMICILIO COMPLETO|NOMBRE O RAZON SOCIAL DEL EMPLEADOR|DOMICILIO DE EMPLEADOR|SALARIO|NUMERO DE CREDITO O CONTRATO|TIPODE RESPONSABILIDAD|TIPO DE CUENTA|TIPO DE CONTRATO|MONEDA|NUMERO DE PAGOS|FRECUENCIA DE PAGO|IMPORTE O MONTO A PAGAR|FECHA APERTURA DEL CREDITO|FECHA DE ULTIMO PAGO|FECHA DE ULTIMA COMPRA|FECHA DE CIERRE|CREDITO MAXIMO|SALDO ACTUAL|LIMITE DE CREDITO|SALDO VENCIDO|NUMERO DE PAGOS VENCIDOS|FECHA DE PRIMER INCUMPLIMIENTO|SALDO INSOLUTO|MONTO DE ULTIMO PAGO|OBSERVACION\n";
		//$this->PM="RFC|Compañía|Nombre 1|Nombre 2|Paterno|Materno|Direccion 1|Colonia/Poblacion|C.P.|Contrato|Fecha Apertura|Plazo|Saldo Inicial|Fecha Liquidacion|Dias Vencimiento|Cantidad|NUM PAGOS\n";
		$this->PM="RAZON SOCIAL|RFC|NACIONALIDAD|DOMICILIO|TELEFONO|CONTRATO|FECHA APERTURA|PLAZO|TIPO CONTRATO|CREDITO MAXIMO|MONEDA|NUMERO DE PAGO|FRECUENCIA DE PAGO|IMPORTE A APGAR|FECHA ULTIMO PAGO|FECHA LIQUIDACION|FECHA DE PRIMER INCUMPLIMIENTO|SALDO INSOLUTO|DIAS DE VENCIMIENTO|SALDO ACTUAL|INTERES|SALDO VENCIDO|OBSARVACION\n";
		//$this->fecharp=date('Y-m-') . "01";

	}


	function quitabasura($dato)
	{
	//PARA QUITAR LAS ETIQUETAS HTML Y CARACTERES ESPECIALES
		$residuo=$dato;

		$i=1;
		$p1=strpos($residuo,"<");
		$p2=strpos($residuo,">");

		$residuo = substr($residuo,0,$p1) . substr($residuo,$p2);

		$residuo = str_replace("*", "&", $residuo);
		$residuo = str_replace(">", "", $residuo);
		

		return $residuo;

	}
	
	function construyedatos()
	{
		//consuslta para comenzar con la extracción de datos
		$sql = "select idinquilino, nombre, nombre2, apaterno, amaterno, nombrenegocio, rfc from (select idinquilino as idi, count(idcontrato) as num from contrato where concluido = false and fechainicio<'$this->fecharp'   group by idinquilino) c, inquilino p where c.idi = p.idinquilino ";
		//$this->PM .=$this->fecharp . "\n";
		//$this->PF .=$this->fecharp . "\n";
		$operacion = mysql_query($sql);
		$this->numempresas=mysql_num_rows($operacion);
		while($row = mysql_fetch_array($operacion))
		{
		
		
			switch (strlen(trim($row["rfc"])))
			{
				case 12:
					
					
					$this->PM .=$this->Moral($row["idinquilino"]);
					
					break;
					
				default:
					
					$this->PF .=$this->Persona($row["idinquilino"]);
					
			}

		}
		
	}
	
	function Persona($id)
		{
			//para la extracción de datos y acomodo de información segun persona fisica

			$p1_nombre=""; //ok
			$p2_fechanacimiento="";//ok
			$p3_rfc=""; //ok
			$p4_estado_civil="";
			$p5_sexo="";//ok
			$p6_ocupacion="";
			$p7_telefono="";//ok
			$p8_domicilio=""; //ok;
			$p9_empleador="";
			$p10_dom_empleador="";
			$p11_salario="";
			$p12_contrato="";//ok
			$p13_tipo_responsabilidad=""; //ok
			$p14_tipo_cuenta=""; //ok
			$p15_tipo_contrato=""; // ok Arrendamiento, casa habitacion, local comercial
			$p16_moneda="MX"; //MX
			$p17_numeropago="";//ok
			$p18_frecuenciapago="";//ok
			$p19_importe_a_pagar="";//ok
			$p20_fecha_apertura="";//ok
			$p21_fecha_ultimopago="";//ok
			$p22_fecha_ultimacompra="";
			$p23_fecha_cierre="";//ok
			$p24_credito_maximo="";//ok
			$p25_saldo_actual="";//ok
			$p26_limite_credito="";//ok
			$p27_saldo_vencido="";//ok
			$p28_numero_pagos_vencidos=""; //ok
			$p29_fecha_primer_incumplimiento="";
			$p30_saldo_insoluto=""; //ok
			$p31_monto_ultimopago="";//ok	
			$p32_observacion="";//ok		
			
			
			
			
			
			
			$lineas="";
			
			$lineas = "";
			$sqldirec="select direccionf, colonia, delegacion, cp,  nombre, nombre2,apaterno,amaterno, rfc, curp, fechanacimiento, tel from inquilino where  idinquilino = $id";
			$operacioncr = mysql_query($sqldirec);
			$rowp = mysql_fetch_array($operacioncr);
			
			$apaterno=$this->quitabasura($rowp["apaterno"]);
			$amaterno=$this->quitabasura($rowp["amaterno"]);
			$nombre1=$this->quitabasura($rowp["nombre"]);
			$nombre2=$this->quitabasura($rowp["nombre2"]);
			
			if(is_null($rowp["fechanacimiento"])==false)
			{
				$p2_fechanacimiento=$rowp["fechanacimiento"];
			}
			
			$curp= $this->quitabasura($rowp["curp"]);
			
			$p7_telefono=$this->quitabasura($rowp["tel"]);
			
			$p14_tipo_cuenta="Pagos fijos";
			
						
			$p1_nombre= $nombre1 . " " . $nombre2 . " " . $apaterno . " " . $amaterno;
			$p3_rfc = $this->quitabasura($rowp["rfc"]);
			$calleynumero = $this->quitabasura($rowp["direccionf"]);
			$cp=$this->quitabasura($rowp["cp"]);
			$p8_domicilio=$this->quitabasura($rowp["direccionf"])  . " " . $this->quitabasura($rowp["colonia"]) . " "  . $this->quitabasura($rowp["delegacion"]) . " "  . $this->quitabasura($rowp["cp"]) . " " ;
			
			//$responsabilidad = "individual";
			$p13_tipo_responsabilidad="individual";
			
			
			$sqlp = "select * from contrato where idinquilino = $id and concluido = false";
						
			$operacionp = mysql_query($sqlp);
			while($rowp = mysql_fetch_array($operacionp))
			{			
			
				$p12_contrato=$rowp["idcontrato"];
				$p20_fecha_apertura=$rowp["fechainicio"];
				$litigio=$rowp["litigio"];
				$concluido=$rowp["concluido"];
				$fechatermino=$rowp["fechatermino"];
				$p32_observacion="";
				
			
				
				
				//ultimo pago
				$sql14="select *, fechapago as fp from historia where idcontrato = $p12_contrato and fechapago<'$this->fecharp' and idcategoriat=1 and condonado = 0 and (isnull(notacredito)= true or notacredito=0) and cantidad>0 order by fechapago DESC, idhistoria DESC";
				$operacion14 = mysql_query($sql14);
				$row14 = mysql_fetch_array($operacion14);
				$p21_fecha_ultimopago=$row14["fp"];
				
				//monto ultimo pago
				$p31_monto_ultimopago=$row14["cantidad"];
				
				
				//numero de pagos
				$sql10="select DISTINCT fechanaturalpago from historia where idcontrato = $p12_contrato and idcobros in (select idcobros from cobros where idcontrato = $p12_contrato and idperiodo <>1)  and idcategoriat=1 group by fechanaturalpago ";
				$operacion10 = mysql_query($sql10);
				$p17_numeropago=mysql_num_rows($operacion10);
				
				//calculo del total del credito
					//de todos los conceptos
				$sql14="select SUM(cantidad+iva) as total from contrato c, cobros cb, periodo p where c.idcontrato = cb.idcontrato and cb.idperiodo = p.idperiodo and p.idperiodo <>1 and c.idcontrato = $p12_contrato ";
				$operacion14 = mysql_query($sql14);
				$row14 = mysql_fetch_array($operacion14);
				$p19_importe_a_pagar=$row14["total"];		
				$p24_credito_maximo=$row14["total"] * $p17_numeropago;		
				$p26_limite_credito=$p24_credito_maximo;	
				
				
				//frecuencia de pagos
				$sql11= "select DISTINCT nombre from cobros c, periodo p where idcontrato = $p12_contrato and c.idperiodo <>1 and c.idperiodo = p.idperiodo group by nombre";
				$operacion11 = mysql_query($sql11);
				$row11 = mysql_fetch_array($operacion11);
				$p18_frecuenciapago=$row11["nombre"];
				
				//saldo actual
				//$sql22="select sum(cantidad + iva) as c from historia where idcontrato = $p12_contrato and aplicado = false  and idcategoriat=1 group by idcontrato";
				$sql22="select sum(cantidad + iva) as c from historia where idcontrato = $p12_contrato and aplicado = false  and idcategoriat=1 and aprobado = 1 group by idcontrato";
				$operacion22 = mysql_query($sql22);
				$row22 = mysql_fetch_array($operacion22);
				$p25_saldo_actual = $row22["c"];
				//$sql221="select sum(cantidad) as c from historia where idcontrato = $p12_contrato and aplicado = true  and fechapago>='$this->fecharp'  and idcategoriat=1  group by idcontrato";
				$sql221="select sum(cantidad) as c from historia where idcontrato = $p12_contrato and aplicado = true  and fechapago>='$this->fecharp'  and idcategoriat=1 and aprobado = 1  group by idcontrato";
				$operacion221 = mysql_query($sql221);
				$row221 = mysql_fetch_array($operacion221);
				$p25_saldo_actual	+= $row221["c"];			
				$p25_saldo_actual= number_format($p25_saldo_actual,0);
				
		
				
				//Sexo
				//$p5_sexo=substr($curp,11,1);	
				
				//tipo contrato
				$p15_tipo_contrato="LS";	
				
					
				
				
				//aqui son pagos vencidos no dias vencidos
				//restriccion 3 o mas se reportan
				
				
				//contar dias vencidos
				//$sql10="select DISTINCT fechanaturalpago from historia where idcontrato = $p12_contrato and idcobros in (select idcobros from cobros where idcontrato = $p12_contrato and idperiodo <>1)  and idcategoriat=1 and aplicado=false and fechanaturalpago<'$this->fecharp' group by fechanaturalpago";
				$sql10="select DISTINCT fechanaturalpago from historia where idcontrato = $p12_contrato and idcobros in (select idcobros from cobros where idcontrato = $p12_contrato and idperiodo <>1)  and idcategoriat=1 and aplicado=false and fechanaturalpago<'$this->fecharp' and aprobado = 1 group by fechanaturalpago";
				$operacion10 = mysql_query($sql10);
				$p28_numero_pagos_vencidos=mysql_num_rows($operacion10);

				if($p28_numero_pagos_vencidos<4)
				{
					$p28_numero_pagos_vencidos=0;
				}

				if ($p18_frecuenciapago == "Mensual")
				{
					if($p28_numero_pagos_vencidos>11)
					{
						$p32_observacion="UP";
					}
				}elseif ($p18_frecuenciapago == "Semanal")
				{
					if($p28_numero_pagos_vencidos>47)
					{
						$p32_observacion="UP";
					}
				}



				/*
				$sql0 = "select idcontrato,  datediff('$this->fecharp',min(fechanaturalpago)) as dias  from historia where  vencido = true and aplicado = false and idcontrato = $p12_contrato and fechanaturalpago<'$this->fecharp'  and idcategoriat=1 group by idcontrato" ;
				$operacion24 = mysql_query($sql0);
				$row24 = mysql_fetch_array($operacion24);
				//$diasvencidos = $row24["dias"];	
				$p28_numero_pagos_vencidos=$row24["dias"];	
				
				if ($p18_frecuenciapago == "Mensual")
				{
					if($p28_numero_pagos_vencidos<90)
					{
						$p28_numero_pagos_vencidos=0;
					}
					else
					{
						$p28_numero_pagos_vencidos=intval($p28_numero_pagos_vencidos/30);
						
						if($p28_numero_pagos_vencidos>11)
						{
							$p32_observacion="UP";
						}						
												
					}
					
				}
				elseif ($p18_frecuenciapago == "Semanal")
				{
					if($p28_numero_pagos_vencidos<21)
					{
						$p28_numero_pagos_vencidos=0;
					}
					else
					{
						$p28_numero_pagos_vencidos=intval($p28_numero_pagos_vencidos/7);
						
						if($p28_numero_pagos_vencidos>47)
						{
							$p32_observacion="UP";
						}							
						
					}
				}
				*/
				if($p28_numero_pagos_vencidos>0)
				{
					//suamr saldo vencido
					$dato=$this->fecharp;
					//nota: tomar en cuenta la fecha de gracia y no la fecha natural de pago
					//$sql24="select sum(cantidad + iva) as c from historia where idcontrato = $p12_contrato and aplicado = false and fechanaturalpago<'$dato'  and idcategoriat=1 group by idcontrato";
					$sql24="select sum(cantidad + iva) as c from historia where idcontrato = $p12_contrato and aplicado = false and fechanaturalpago<'$dato'  and idcategoriat=1 and aprobado = 1 group by idcontrato";
					$operacion24 = mysql_query($sql24);
					$row24 = mysql_fetch_array($operacion24);					
					$p27_saldo_vencido=$row24["c"];	
					
					
					//$sql24="select sum(cantidad + iva) as c from historia where idcontrato = $p12_contrato and aplicado = false and fechanaturalpago<'$dato'  and idcategoriat=1 and interes = 0  and idcategoriat=1 group by idcontrato";
					$sql24="select sum(cantidad + iva) as c from historia where idcontrato = $p12_contrato and aplicado = false and fechanaturalpago<'$dato'  and idcategoriat=1 and interes = 0  and idcategoriat=1 and aprobado = 1 group by idcontrato";
					$operacion24 = mysql_query($sql24);
					$row24 = mysql_fetch_array($operacion24);					
					$p30_saldo_insoluto=$row24["c"];	
					
					if($p32_observacion=="")
					{
						$p32_observacion="PC";					
					}
				}
				

				
				if($ligigio==1)
				{
					$p32_observacion="SG";
				}
				
				
				//fecha cierre
				
				$lineas .=  $p1_nombre . "|" . $p2_fechanacimiento . "|" . $p3_rfc . "|" . $p4_estado_civil . "|" . $p5_sexo . "|" . $p6_ocupacion . "|" . $p7_telefono . "|" . $p8_domicilio . "|" . $p9_empleador . "|" . $p10_dom_empleador . "|" . $p11_salario . "|" . $p12_contrato . "|" . $p13_tipo_responsabilidad . "|" . $p14_tipo_cuenta . "|" . $p15_tipo_contrato . "|" . $p16_moneda . "|" . $p17_numeropago . "|" . $p18_frecuenciapago . "|" . $p19_importe_a_pagar . "|" . $p20_fecha_apertura . "|" . $p21_fecha_ultimopago . "|" . $p22_fecha_ultimacompra . "|" . $p23_fecha_cierre . "|" . $p24_credito_maximo . "|" . $p25_saldo_actual . "|" .  $p26_limite_credito . "|" . $p27_saldo_vencido . "|" . $p28_numero_pagos_vencidos . "|" . $p29_fecha_primer_incumplimiento . "|" . $p30_saldo_insoluto . "|" . $p31_monto_ultimopago  . "|" . $p32_observacion  . "\n";
			
			}
			return $lineas;
		
		}

		function Moral($id)
		{
		
			$p1_nombre=""; //ok
			$p2_rfc=""; //ok
			$p3_nacionalidad="";
			$p4_domicilio ="";//ok
			$p5_telefono="";//ok
			$p6_contrato="";//ok
			$p7_fecha_apertura="";//ok
			$p8_plazo_credito="";//ok
			$p9_tipo_contrato="";//  ok  Arrendamiento, casa habitacin, localcomercial
			$p10_credito_max=""; //OK
			$p11_moneda="MX";//MX
			$p12_numero_pagos=0; //ok
			$p13_frecuencia_pagos="";//ok
			$p14_importe_a_pagar=0;//ok
			$p15_fecha_ultimopago="";//ok
			$p16_fecha_liquidacion="";//ok
			$p17_fecha_primer_incumplimiento="";
			$p18_saldo_insoluto=0; //ok
			$p19_dias_vencimiento="";//ok
			$p20_saldo_actual=0;//ok
			$p21_interes=0;//ok
			$p22_saldo_vencido=0;//ok
			$p23_observacion="";//ok
			
			
			
			
			
			
			
			$lineas="";
			
			$sqldirec="select nombrenegocio, direccionf, colonia, delegacion, cp,  nombre, nombre2,apaterno,amaterno, rfc from inquilino where  idinquilino = $id";
			$operacioncr = mysql_query($sqldirec);
			$rowm = mysql_fetch_array($operacioncr);
			$p1_nombre=$this->quitabasura($rowm["nombre"]);
			$p2_rfc=$this->quitabasura($rowm["rfc"]);
			$p4_domicilio =$this->quitabasura($rowm["direccionf"]) . " " . $this->quitabasura($rowm["colonia"]) . " "  . $this->quitabasura($rowm["delegacion"]) . " " . $this->quitabasura($rowm["cp"]);
			$p5_telefono=$this->quitabasura($rowm["tel"]);
			$p9_tipo_contrato="LS";

			$sqlp = "select * from contrato where idinquilino = $id and concluido = false";			
			$operacionp = mysql_query($sqlp);
			while($rowp = mysql_fetch_array($operacionp))
			{			
			
				$p6_contrato=$rowp["idcontrato"];
				$p7_fecha_apertura=$rowp["fechainicio"];
				$sql1 = "select idcontrato, datediff(fechatermino,fechainicio) as dias  from contrato where idcontrato = $p6_contrato"; 
				$operacion14 = mysql_query($sql1);
				$row14 = mysql_fetch_array($operacion14);
				$p8_plazo_credito = $row14["dias"];	
				$litigio=$rowp["litigio"];	
				$concluido=$rowp["concluido"];
				$fechatermino=$rowp["fechatermino"];
				$p23_observacion="";
				
				
				//ultimo pago
				$sql14="select *, fechapago as fp from historia where idcontrato = $p6_contrato and fechapago<'$this->fecharp' and idcategoriat=1 and condonado = 0 and isnull(notacredito)= true order by fechapago DESC";;
				$operacion14 = mysql_query($sql14);
				$row14 = mysql_fetch_array($operacion14);
				$p15_fecha_ultimopago= $row14["fp"];
				
				//numero de pagos
				$sql10="select DISTINCT fechanaturalpago from historia where idcontrato = $p6_contrato and idcobros in (select idcobros from cobros where idcontrato = $p6_contrato and idperiodo <>1)  and idcategoriat=1 group by fechanaturalpago ";
				$operacion10 = mysql_query($sql10);
				$p12_numero_pagos=mysql_num_rows($operacion10);
				
				//calculo del total del credito, saldo inicial
					//de todos los conceptos
				$sql14="select SUM(cantidad+iva) as total from contrato c, cobros cb, periodo p where c.idcontrato = cb.idcontrato and cb.idperiodo = p.idperiodo and p.idperiodo <>1 and c.idcontrato = $p6_contrato  ";
				$operacion14 = mysql_query($sql14);
				$row14 = mysql_fetch_array($operacion14);
				$p14_importe_a_pagar=$row14["total"];
				$p10_credito_max=$p14_importe_a_pagar * $p12_numero_pagos ;
						
				//frecuencia de pagos
				$sql11= "select DISTINCT nombre from cobros c, periodo p where idcontrato = $p6_contrato and c.idperiodo <>1 and c.idperiodo = p.idperiodo group by nombre";
				$operacion11 = mysql_query($sql11);
				$row11 = mysql_fetch_array($operacion11);
				$p13_frecuencia_pagos=$row11["nombre"];
				
				//saldo actual
				//$sql22="select sum(cantidad + iva) as c from historia where idcontrato = $p6_contrato and aplicado = false  and idcategoriat=1  group by idcontrato";
				$sql22="select sum(cantidad + iva) as c from historia where idcontrato = $p6_contrato and aplicado = false  and idcategoriat=1 and aprobado = 1  group by idcontrato";
				$operacion22 = mysql_query($sql22);
				$row22 = mysql_fetch_array($operacion22);
				$p20_saldo_actual=$row22["c"];		
				/*
				$sql221="select sum(cantidad) as c  from historia where idcontrato = " . $rowp["idcontrato"] . " and condonado = false and aplicado = true and fechapago>='$this->fecharp'  and idcategoriat=1  group by idcontrato";
				$operacion221 = mysql_query($sql221);
				$row221 = mysql_fetch_array($operacion221);
				$p20_saldo_actual += $row221["c"];
				$p20_saldo_actual= number_format($p20_saldo_actual,0);
						*/
				
				//contar dias vencidos
				//$sql24 = "select idcontrato,  datediff('$this->fecharp',min(fechanaturalpago)) as dias from historia where aplicado = false and idcontrato = $p6_contrato and fechanaturalpago<'$this->fecharp' and idcategoriat=1  group by idcontrato" ;
				$sql24 = "select idcontrato,  datediff('$this->fecharp',min(fechanaturalpago)) as dias from historia where aplicado = false and idcontrato = $p6_contrato and fechanaturalpago<'$this->fecharp' and idcategoriat=1 and aprobado = 1 group by idcontrato" ;
				$operacion24 = mysql_query($sql24);
				$row24 = mysql_fetch_array($operacion24);
				$diasvencimiento = $row24["dias"];				
				$p19_dias_vencimiento= $row24["dias"];
				
					if( mysql_num_rows($operacion24)==0 )
					{
						$p19_dias_vencimiento=0;
					}

					if($p19_dias_vencimiento<90)
					{
						$p19_dias_vencimiento=0;
					}
				//echo $p19_dias_vencimiento." cont: ".$p6_contrato ."<br>";
				if($p19_dias_vencimiento>0)
				{
					
				//suamr saldo vencido
				$dato=$this->fecharp;
				//$sql24="select format(sum(cantidad + iva),0) as c from historia where idcontrato = $p6_contrato and aplicado = false and fechanaturalpago<'$dato'  and idcategoriat=1 group by idcontrato";
				$sql24="select format(sum(cantidad + iva),0) as c from historia where idcontrato = $p6_contrato and aplicado = false and fechanaturalpago<'$dato'  and idcategoriat=1 and aprobado = 1 group by idcontrato";
				$operacion24 = mysql_query($sql24);
				$row24 = mysql_fetch_array($operacion24);			
				$p22_saldo_vencido = $row24["c"];	
				
				//suamr saldo insoluto
				$dato=$this->fecharp;
				//$sql24="select format(sum(cantidad + iva),0) as c from historia where idcontrato = $p6_contrato and aplicado = false and fechanaturalpago<'$dato' and interes = 0  and idcategoriat=1 group by idcontrato";
				$sql24="select format(sum(cantidad + iva),0) as c from historia where idcontrato = $p6_contrato and aplicado = false and fechanaturalpago<'$dato' and interes = 0  and idcategoriat=1 and aprobado = 1 group by idcontrato";
				$operacion24 = mysql_query($sql24);
				$row24 = mysql_fetch_array($operacion24);			
				$p18_saldo_insoluto = $row24["c"];	
				
				//suamr saldo interes
				$dato=$this->fecharp;
				//$sql24="select format(sum(cantidad + iva),0) as c from historia where idcontrato = $p6_contrato and aplicado = false and fechanaturalpago<'$dato' and interes = 1  and idcategoriat=1 group by idcontrato";
				$sql24="select format(sum(cantidad + iva),0) as c from historia where idcontrato = $p6_contrato and aplicado = false and fechanaturalpago<'$dato' and interes = 1  and idcategoriat=1 and aprobado = 1 group by idcontrato";				
				$operacion24 = mysql_query($sql24);
				$row24 = mysql_fetch_array($operacion24);			
				$p21_interes = $row24["c"];				
				
				$p23_observacion="PC";				
								
				}
				else
				{
					$p22_saldo_vencido = 0;	
					$p18_saldo_insoluto = 0;	
					$p21_interes = 0;
					$p23_observacion="";	
				}
				
				if($p19_dias_vencimiento>359)
				{
					$p23_observacion="UP";
				}
								
				if($ligigio==1)
				{
					$p23_observacion="SG";
				}
				
				//fecha liquidacion
				if($concluido == 1)
				{
					$p16_fecha_liquidacion=date('Y-m-d', $fechatermino);
				}
												
				
				//$lineas .= $rfc . "|" . $compania . "|" .  $nombre1 . "|" . $nombre2 . "|" . $apaterno . "|" . $amaterno . "|" .  $direccion . "|" . $colonia . "|" . $cp . "|" . $contrato . "|" . $fechaaperturapm . "|" . $plazo . "|" . $saldoinicial . "|" . $fechaliquidacion . "|" . $diasvencimiento . "|" . $cantidadpm . "|" . $nopagos . "\n";
				$lineas .= $p1_nombre . "|" . $p2_rfc . "|" .  $p3_nacionalidad . "|" . $p4_domicilio . "|" . $p5_telefono . "|" . $p6_contrato . "|" .  $p7_fecha_apertura . "|" . $p8_plazo_credito . "|" . $p9_tipo_contrato . "|" . $p10_credito_max . "|" . $p11_moneda . "|" . $p12_numero_pagos . "|" . $p13_frecuencia_pagos . "|" . $p14_importe_a_pagar . "|" . $p15_fecha_ultimopago . "|" . $p16_fecha_liquidacion . "|" . $p17_fecha_primer_incumplimiento . "|" . $p18_saldo_insoluto . "|" . $p19_dias_vencimiento . "|" . $p20_saldo_actual . "|" . $p21_interes . "|"  . $p22_saldo_vencido . "|"  . $p23_observacion . "\n";
			
			}
			return $lineas;
		
		
		
		}

		
	
	


}
