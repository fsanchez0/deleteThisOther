<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/ftpclass.php';
include ("../cfdi/cfdiclassn.php");

$accion = @$_GET["contrato"];
$prueba = New Calendario;
$fondo=" class='Fondo' ";
$fondot1=" class='Fondot1' ";
$fondot2=" class='Fondot2' ";
$clasef="";
//$clasef=$fondo;
$cambio = "";

$cfd =  New cfdi32class;
$ftp= New ftpcfdi;
$misesion = new sessiones;



if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='edoscuentainm.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta']  . "/" . $row['archivo'];
		$ruta= $row['ruta'] ;
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}


	if ($priv[0]!='1')
	{
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}

	if ($priv[1]=='1')
	{
		$txtagregar = "";
	}
	else
	{
		$txtagregar = " DISABLED ";
	}

	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}



if ($accion){

	//$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno,inquilino.tel as telinq,tipocobro, historia.fechagenerado, historia.fechanaturalpago, historia.fechagracia, historia.fechapago, historia.cantidad, aplicado, historia.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, inmueble.estado, inmueble.pais, inmueble.tel as telinm, historia.iva as ivah,aplicado, condonado FROM contrato, cobros, inquilino, inmueble, tipocobro,historia, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $accion and inmueble.idestado =estado.idestado and inmueble.idpais=pais.idpais order by fechanaturalpago, fechapago, aplicado";

	$sql="select tipocobro, (b.cantidad + b.iva) suma,(b.interes *100) elinteres from contrato c, cobros b, tipocobro t where c.idcontrato = b.idcontrato and b.idtipocobro = t.idtipocobro and b.idperiodo<>1 and c.idcontrato = $accion";

	$listacobros="<table border='1' ><tr class='Cabecera'><th>Concepto</th><th>Cantidad</th><th>Interes</th></tr>";

	$result1 = @mysql_query ($sql);
	while ($row = mysql_fetch_array($result1))
	{
		$listacobros .="<tr><td class='Cabecera'>" . $row["tipocobro"]  ."</td><td align='right'>$" .  $row["suma"]  . "</td><td align='right'>" .  $row["elinteres"]  . "%</td></tr>";

	}
	$listacobros .="</table>";

	//$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as telinq,tipocobro, historia.fechagenerado, historia.fechanaturalpago, historia.fechagracia, historia.fechapago, historia.cantidad, aplicado, historia.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, inmueble.estado, inmueble.pais, inmueble.tel as telinm, historia.iva as ivah,aplicado, condonado, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel FROM contrato, cobros, inquilino, inmueble, tipocobro,historia, fiador WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $accion and contrato.idfiador=fiador.idfiador order by fechanaturalpago, tipocobro,interes,fechagenerado,fechapago, aplicado";
	//$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as telinq,tipocobro, historia.fechagenerado, historia.fechanaturalpago, historia.fechagracia, historia.fechapago, historia.cantidad, aplicado, historia.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, estado, pais, inmueble.tel as telinm, historia.iva as ivah,aplicado, condonado, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, historia.notas as hnotas, fiador.email as emailf, notanc, inquilino.email as emaili, observaciones FROM contrato, cobros, inquilino, inmueble, tipocobro,historia, fiador, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $accion and contrato.idfiador=fiador.idfiador and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais order by fechanaturalpago, tipocobro,interes,fechagenerado,fechapago, aplicado";
	 $sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as telinq,tipocobro, historia.fechagenerado, historia.fechanaturalpago, historia.fechagracia, historia.fechapago, historia.cantidad, aplicado, historia.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, estado, pais, inmueble.tel as telinm, historia.iva as ivah,aplicado, condonado, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, historia.notas as hnotas, fiador.email as emailf, notanc, inquilino.email as emaili, observaciones, archivotxt, archivopdf,archivoxml,archivopdfn,txtok,pdfok,xmlok,pdfnok, historia.idhistoria as idh, hfacturacfdi FROM contrato, cobros, inquilino, inmueble, tipocobro,historia, fiador, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $accion and contrato.idfiador=fiador.idfiador and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais order by fechanaturalpago, tipocobro,interes,fechagenerado,fechapago, aplicado";



	$result = @mysql_query ($sql);
	$Datos = 0;
	$cabecera="";
	$historia = "";
	$suma = 0;
	$grupoint=0;
	$tablainterna="";
	$tablalisto="";
	$concepto="";
	$operacion="";
	$auxt=0;
	$principio="";
	$principioa="";
	$fechaa="";
	$fechab="";
	$partea="";
	$parteb="";
	$conceptob="";
	$vcambio=0;

	$claseft=$fondot1;
	setlocale(LC_MONETARY, 'en_US');

	while ($row = mysql_fetch_array($result))
	{
		//echo $auxt;
		
		
		

		if($cambio=="")
		{
			$cambio=$row["fechanaturalpago"];
			$clasef=$fondo;
			$claseft=$fondot1;

		}
		if($cambio!=$row["fechanaturalpago"])
		{
			$cambio=$row["fechanaturalpago"];
			$vcambio=1;
			if($clasef==$fondo)
			{
				$clasef="";
				$claseft=$fondot2;
			}
			else
			{
				$clasef=$fondo;
				$claseft=$fondot1;
			}

		}




		if($Datos==0)
		{
			//$cabecera = "<center><h2>Estado de cuenta contrato No. $accion</h2>";
			$cabecera .= "<table border = \"1\">";
			$cabecera .= "<tr><td class='Cabecera'>Inquilino</td><td>" .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "(Tels. " . $row["telinq"] . ", " . $row["telinm"] . " email: " . $row["emaili"] . ")</td></tr>\n";
			$direccion =$row["calle"] . " No. " . $row["numeroext"] . " " . $row["numeroint"] . " COL." . $row["colonia"] . " Del./Mun." . $row["delmun"] . " C.P." . $row["cp"] . " " . $row["pais"] . " " . $row["estado"];
			$cabecera .= "<tr><td class='Cabecera'>Direcci&oacute;n</td><td>$direccion </td></tr>\n";
			$elfiadorh = $row["fnombre"] . " " . $row["fnombre2"] . " " . $row["fapaterno"] . " " . $row["famaterno"]  . " (Tel. " . $row["ftel"] . ", email: " . $row["emailf"] . ")";
			$cabecera .= "<tr><td class='Cabecera'>Obligado solidario</td><td>$elfiadorh </td></tr>\n";

			$Datos=1;


		}

		$estado="PENDIENTE";


		if (is_null($row["vencido"])==false and $row["vencido"]==1)
		{

			$estado="PENDIENTE";

		}

		if (is_null($row["aplicado"])==false and $row["aplicado"]==1)
		{

			//$estado="ABONO";
			//$estado=$row["hnotas"];

			$accionbotonfact="";
			$ligapdf="";
			$ligaxml="";
			$ligapdfn="";
			$reciboscfdi="";
			if(	$row["fechapago"]>='2012-02-01')
			{
			
				$sqlcfdi="select * from historiacfdi h, facturacfdi f where h.idfacturacfdi=f.idfacturacfdi and idhistoria = " . $row["idh"];
				$operacioncfdi = mysql_query($sqlcfdi);
				//$r= mysql_fetch_array($operacioncfdi);
				//$idcfdi = $r["idfacturacfdi"];	
				//$reciboscfdi="";
			  if(mysql_num_rows($operacioncfdi)>0)
			  {
				while($r= mysql_fetch_array($operacioncfdi))
				{
					$canceladacfdi="";		
					if($r["cancelada"]==1)
					{
						$canceladacfdi="(CANCELADA)";	
					}
					$notacreditocfdi = "";
					/*
					if($r["notacredito"]==1)
					{
						$notacreditocfdi="N.C.";	
					}
					else
					{
						$notacreditocfdi="";
					}
				*/
					//if($row["txtok"]==0)
					if($r["txtok"]==0)
					{

							
					
						if(is_null($r["archivotxt"])==true)
						{
							//$edocuenta="window.open( '../reporte2.php?id=" . $row["idh"] . "');this.disabled=true";
							$edocuenta="nuevaVP(" . $row["idh"] . ",'');this.disabled=true;";
							$accionbotonfact="<br>" . '<form name="frm_' . $row["idh"] .  '" id="frm_' . $row["idh"] .  '" method="POST" action="../reporte2.php" target="trg_' . $row["idh"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value ="">' . "<input type =\"button\" value=\"Facturar $notacreditocfdi\" onClick=\"$edocuenta\" $txtagregar  /></form>";
						}
						else
						{
							//$edocuenta="window.open( '../reporte2.php?id=" . $row["idh"] . "&archivo=". substr($r["archivotxt"],0,-4) ."');this.disabled=true";
							$edocuenta="nuevaVP(" . $row["idh"] . ",'');this.disabled=true;";
							$accionbotonfact="<br>" . '<form name="frm_' . $row["idh"] .  '" id="frm_' . $row["idh"] .  '" method="POST" action="../reporte2.php" target="trg_' . $row["idh"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""><input type="hidden" name="archivo" id="archivo" value ="'. substr($r["archivotxt"],0,-4) . '">' . "<input type =\"button\" value=\"Facturar $notacreditocfdi\" onClick=\"$edocuenta\" $txtagregar /></form>";	
						}
							
					}
			
					//if($row["pdfok"]==0 and $accionbotonfact=="" )
					if($r["pdfok"]==0 && $accionbotonfact=="" )
					{
						$ligapdf="<br><input type=\"button\" value='Recuperar PDF $notacreditocfdi' onClick=\"document.getElementById('cfdi" . $row["idh"] . "').innerHTML='Recuperando...';cargarSeccion('../recuperar.php','cfdi" . $row["idh"] . "', 'id=" . $row["idh"] . "&filtro=');\" $txteditar>";

				
					}
					else
					{
						//if($accionbotonfact=="" )
						//{
							//$ligapdf="<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivopdf"] . "\"  target=\"_blank\" >" .  $row["archivopdf"] . "\n</a>";
							$ligapdf="<br><a href=\"../general/descargarcfdi.php?f=" .  $r["archivopdf"] . "\"  target=\"_blank\" >" .  $r["archivopdf"] . "$notacreditocfdi$canceladacfdi\n</a>";
						//}	
					}

					//if($row["xmlok"]==0 and $accionbotonfact=="")
					if($r["xmlok"]==0 && $accionbotonfact=="")
					{

						$ligaxml="<br><input type=\"button\" value='Recuperar XML $notacreditocfdi' onClick=\"document.getElementById('cfdi" . $row["idh"] . "').innerHTML='Recuperando...';cargarSeccion('../recuperar.php','cfdi" . $row["idh"] . "', 'id=" . $row["idh"] . "&filtro=');\"  $txteditar>";

					}
					else
					{
						//if($accionbotonfact=="" )
						//{
							//$ligaxml="<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivoxml"] . "\"  target=\"_blank\" >" .  $row["archivoxml"] . "\n</a>";
							$ligaxml="<br><a href=\"../general/descargarcfdi.php?f=" .  $r["archivoxml"] . "\"  target=\"_blank\" >" .  $r["archivoxml"] . "$notacreditocfdi$canceladacfdi\n</a>";
						//}
					}
			
					//if($row["pdfnok"]==0 and $accionbotonfact=="")
					if($r["pdfn"]==0 && $accionbotonfact=="")
					{
						$edocuenta="";
						$ligapdfn="<br>PDF PyB$notacreditocfdi$canceladacfdi";
					}			
					else
					{
						$ligapdfn="<br>PDF PyB$notacreditocfdi$canceladacfdi";	
					}
					$reciboscfdi .= $accionbotonfact . $ligapdf . $ligaxml . $ligapdfn;
				}
			  
			}
			 
			else 
			  
			{//cuando no genero registro en tabla de facturacfdi
			     
			    
				if($txteditar=="")
			    
				{ 
			  	 	
					//$edocuenta="window.open( '../reporte2.php?id=" . $row["idh"] . "');this.disabled=true";
					$edocuenta="nuevaVP(" . $row["idh"] . ",'');this.disabled=true;";
					$accionbotonfact="<br>" . '<form name="frm_' . $row["idh"] .  '" id="frm_' . $row["idh"] .  '" method="POST" action="../reporte2.php" target="trg_' . $row["idh"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value ="">' . "<input type =\"button\" value=\"Facturar\" onClick=\"$edocuenta\" $txtagregar /></form>";
					
					$reciboscfdi=$accionbotonfact;
				
				}
				
				//$reciboscfdi .= $txteditar . "...";
			  
			}			
			
			}
			
			if(is_null($row["hnotas"])==true)
			{
				//$estado="PAGADO <div id='cfdi" . $row["idh"] . "'>" . $accionbotonfact . $ligapdf . $ligaxml . $ligapdfn . "</div>";
				$estado="PAGADO <div id='cfdi" . $row["idh"] . "'>" . $reciboscfdi . "</div>";
			}
			else
			{
				$acc="";
				//if($row["hnotas"]=="LIQUIDADO")
				if(substr($row["hnotas"],0,9)=="LIQUIDADO")
				{
					//$acc="<div id='cfdi" . $row["idh"] . "'>" . $accionbotonfact . $ligapdf . $ligaxml . $ligapdfn . "</div>";
					$acc="<div id='cfdi" . $row["idh"] . "'>" . $reciboscfdi . "</div>";
				}	
				$estado=$row["hnotas"] . $acc;
				
				
				
			}

			if($row["cantidad"] <0)
			{
				$estado="CONDONADO";
			}
		}
		else
		{

			$suma += $row["cantidad"] + $row["ivah"] ;
		}

		if (is_null($row["condonado"])==false and $row["condonado"]==1)
		{

			$estado="CARGO GENERADO";

		}



		$conceptob= $row["tipocobro"] ;//  . "<br><strong>" . $row["observaciones"] . "</strong>";
		$fechaa=$prueba->formatofecha($row["fechanaturalpago"]);
		$fechaa2=$row["fechanaturalpago"];
		$fechab=$prueba->formatofecha($row["fechagracia"]);
		$partea="<tr $clasef>";
		$parteb="<td>\n<table border ='1' $claseft >";
		
		$notacreditot="";
		
		if($row["notanc"])
		{
			$notacreditot= "  (N.C.:" . $row["notanc"] . ")";
		}

		if ($concepto!=$row["tipocobro"] || $vcambio==1)
		{
			if($vcambio==1)
			{
				$vcambio=0;
			}
			$concepto = $row["tipocobro"] ;
			$conceptoa=$concepto;// . "<br><strong>" . $row["observaciones"] . "</strong>" ;
			//$concepto = $row["tipocobro"];
			$operacion = "PAGO" . $notacreditot ;


			//$principioa=$principio;
			//$principio="<tr $clasef><td align='center'>" . $prueba->formatofecha($row["fechanaturalpago"])  . "</td><td align='center'>" . $prueba->formatofecha($row["fechagracia"]) . "</td><td>$conceptoa</td>";

			
//para calcular el mes correspondiente en cada renglon	
		
		$mesren = substr($row["fechanaturalpago"],5,2);
		switch ((int)$mesren)
		//switch ($d)
		{
		case 1:
			
			$mesren = "Enero";
			
			break;

		case 2:
			
			$mesren  = "Febrero";

			break;

		case 3:
			
			$mesren  = "Marzo";
			
			break;

		case 4:
			
			$mesren = "Abril";
			
			break;

		case 5:
			
			$mesren = "Mayo";
			
			break;

		case 6:
			
			$mesren = "Junio";
			
			break;

		case 7:
			
			$mesren = "Julio";
			
			break;

		case 8:
			
			$mesren = "Agosto";
			
			break;

		case 9:
			
			$mesren = "Septiembre";
			
			break;

		case 10:
			
			$mesren = "Octubre";
			
			break;

		case 11:
			
			$mesren = "Noviembre";
			
			break;

		case 12:
			
			$mesren = "Diciembre";
			
			break;
		}
			
//****************************************			
			
			
			
			if($auxt==0)
			{


				//$conceptoa=$row["tipocobro"] . "<br><strong>" . $row["observaciones"] . "</strong>"   . $notacreditot;
				$conceptoa .= $notacreditot;
				
				$principio="<tr $clasef><td align='center'>" . $prueba->formatofecha($row["fechanaturalpago"])  . "</td><td align='center'>" . $prueba->formatofecha($row["fechagracia"]) . "</td><td>$conceptoa ($mesren) </td><td>\n<table border ='1' $claseft >";
				$auxt=1;
				$tablainterna="";
			}
			else
			{



				$principioa=$principio;


				//echo $clasef . " :: " . $claseft . "<br>";
				$tablalisto ="$tablainterna";
				//$tablalisto ="\n<table border ='1' $claseft >$tablainterna";
				$principio="<tr $clasef><td align='center'>" . $prueba->formatofecha($row["fechanaturalpago"])  . "</td><td align='center'>" . $prueba->formatofecha($row["fechagracia"]) . "</td><td>$conceptoa ($mesren) </td><td>\n<table border ='1' $claseft >";
				/*
				$fechaa=$prueba->formatofecha($row["fechanaturalpago"]);
				$fechab=$prueba->formatofecha($row["fechagracia"]);
				$partea="<tr $clasef>";
				$parteb="<td>\n<table border ='1' $claseft >";
		*/
			}


		}
		else
		{
			$operacion = "PAGO" . $notacreditot ;

		}


		if (is_null($row["interes"])==false and $row["interes"]==1)
		{

			$operacion = "INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . "(" . $row["tipocobro"] . ")" . $notacreditot ;


		}
		if(is_null($row["fechapago"]))
		{
			$fechapago="&nbsp;";

		}
		else
		{
			$fechapago=$prueba->formatofecha($row["fechapago"]);
		}




		if ($row["aplicado"]==false )
		{
			$Pagado=($row["cantidad"] + $row["ivah"]);

		}
		else
		{
			$Pagado=$row["cantidad"] ;
		}





		if($tablalisto!="")
		{


			//$tablalisto .="</table>\n";

			$historia .= "$principioa $tablalisto</table> </td></tr>\n";
			//$historia .= "<tr $clasef><td align='center'>" . $prueba->formatofecha($row["fechanaturalpago"])  . "</td><td align='center'>" . $prueba->formatofecha($row["fechagracia"]) . "</td><td>$conceptoa</td><td >$tablalisto</td>\n";
			$tablalisto ="";

			$tablainterna = "<tr><td width='300'>$operacion<br><strong>" . $row["observaciones"] . "</strong></td><td align='right' width='100'>$ " . number_format($Pagado,2)  . "</td><td align='center' width='100'> $fechapago</td><td width='100'> $estado</td></tr>\n";



		}
		else
		{
			$tablainterna .= "<tr><td width='300'>$operacion<br><strong>" . $row["observaciones"] . "</strong></td><td align='right' width='100'>$ " . number_format($Pagado,2)  . "</td><td align='center' width='100'> $fechapago</td><td width='100'>$estado</td></tr>\n";
		}





	}

	if($tablainterna!="")
	{

	
	
	
//para calcular el mes correspondiente en cada renglon	
		
		$mesren = substr($fechaa2,5,2);
		switch ((int)$mesren)
		//switch ($d)
		{
		case 1:
			
			$mesren = "Enero";
			
			break;

		case 2:
			
			$mesren  = "Febrero";

			break;

		case 3:
			
			$mesren  = "Marzo";
			
			break;

		case 4:
			
			$mesren = "Abril";
			
			break;

		case 5:
			
			$mesren = "Mayo";
			
			break;

		case 6:
			
			$mesren = "Junio";
			
			break;

		case 7:
			
			$mesren = "Julio";
			
			break;

		case 8:
			
			$mesren = "Agosto";
			
			break;

		case 9:
			
			$mesren = "Septiembre";
			
			break;

		case 10:
			
			$mesren = "Octubre";
			
			break;

		case 11:
			
			$mesren = "Noviembre";
			
			break;

		case 12:
			
			$mesren = "Diciembre";
			
			break;
		}
			
//****************************************	


		$principio="$partea<td align='center'>$fechaa</td><td align='center'>$fechab</td><td>$conceptob  ($mesren) </td>$parteb";


		$historia .= "$principio $tablainterna</table> </td></tr>\n";

	}


	$historia .= "</table>";
	$cabecera .= "<tr><td class='Cabecera'>Adeudo pendiente</td><td>$ " . number_format($suma,2) . "</td></tr>";
	$cabecera .= "</table></center>\n";
	$cabecera = "<center><h2>Estado de cuenta contrato No. $accion</h2>\n<table border='0'> <tr><td>$cabecera</td><td>&nbsp;&nbsp;</td><td valign='top'>$listacobros</td></tr></table>\n";
	$cabecera .= "<br>\n<table border = \"1\" width=\"100%\">\n\t<tr class='Cabecera'><th>Fecha Pago</th><th>Fecha Gracia</th><th>Concepto</th><th width='600'>Detalle<br>\n<table border ='1'  class='Cabecera'><tr><th width='300'>Operaci&oacute;n</th><th width='100'>Cantidad</th><th width='100'>F.Pagado</th><th width='100'>Estado</th></tr></table>\n</th></tr>\n";
	//$cabecera .= "<tr class='Cabecera'><th>Fecha Pago</th><th>Fecha Gracia</th><th>Concepto</th><th>Cantidad</th><th>Fecha Pagado</th><th>Estado</th></tr>\n";
	//echo $cabecera . $historia;

echo <<< elhtml
<html>
<head><title>Estado de cuenta</title></head>

<script type=text/javascript src="../mijs.js"></script>
<script language="javascript" src="../ajax.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../estilos/estilos.css">
<script language="javascript" type="text/javascript">
//cargarCalendario(0); mes=0;cargarMenu('','');
var mes;



function cargarSeccion(root,loc, param)
{
	var cont;
	cont = document.getElementById(loc);
	ajax=nuevoAjax();
	ajax.open("GET", root + "?"+param ,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
  			cont.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

</script>
<body>
<table border="0" width="100%" >
<tr>
	<td><img src="../../imagenes/logo.png" ></td>
	<td align='center'>
	&nbsp;
	</td>
</tr>
</table>



elhtml;


	echo CambiaAcentosaHTML($cabecera . $historia);

echo "</body></html>";

}


}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}

?>
