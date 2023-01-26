<?php
date_default_timezone_set('America/Mexico_City');
include 'general/funcionesformato.php';
include 'general/sessionclase.php';
//include 'general/ftpclass.php';
include "insertcfdi/lxml3.php";
include_once('general/conexion.php');
//include ("cfdi/cfdiclassn.php");
include ("cfdi/cfdiclass33pyb.php");
include ("general/correoclassd.php");
require('fpdf.php');



header('Content-Type: text/html; charset=iso-8859-1');

/*
$id=@$_GET["id"]; //para el Id de la consulta que se requiere hacer: de arrendamiento idhistoria, de libre idfolio
$filtro=@$_GET["filtro"]; //para la especificacion del tipo re recibo inmueble=null, libre=0;
$datosl=@$_GET["datosl"]; //para recibir todos los datos para la factura segun el layaut que biene de la facturalibre
$tipocfd=@$_GET["tipocfd"];//para ver el tipo de recibo 1:ingreso, 2:egreso, 3:traslado, diferente o nulo: ingreso
$archivo=@$_GET["archivo"]; //para ponerel nombre del archivo si existe y enviar de nuevo la factura
$idcl = @$_GET["idcl"];//id del cliente apra la facturalibre
//$dir="../../../../home/wwwarchivos/cfdi/";
*/

$id=@$_POST["idc"]; //para el Id de la consulta que se requiere hacer: de arrendamiento idhistoria, de libre idfolio, idduenio de due�o
$filtro=@$_POST["filtro"]; //para la especificacion del tipo re recibo inmueble=null, libre=1, duenio = 2;
$datosl=@$_POST["datosl"]; //para recibir todos los datos para la factura segun el layaut que biene de la facturalibre
$tipocfd=@$_POST["tipocfd"];//para ver el tipo de recibo 1:ingreso, 2:egreso, 3:traslado, diferente o nulo: ingreso
$archivo=@$_POST["archivo"]; //para ponerel nombre del archivo si existe y enviar de nuevo la factura
$idcl = @$_POST["idcl"];//id del cliente apra la facturalibre o duenio
// Cambio 02/09/2021
// Se agrega una nueva variable para capturar le fecha en que se generaron los conceptos a facturar
$fechagen = @$_POST["fechagen"];
// Fin Cambio 02/09/2021

//Cambio 23/06/2021
// Se agrega la lectura de los correos de la peticion POST
$correoAdicional_1 = @$_POST["c1"];
$correoAdicional_2 = @$_POST["c2"];
$correoAdicional_3 = @$_POST["c3"];




$xmlcfdi = new xmlcfd_cfdi;

function datoscfdi($archivocfdi,&$xmlcfdi,$id)

{
		
	$dir="/home/rentaspb/contenedor/cfdi/";
		
	$dir .= $archivocfdi;
		
	$xmlcfdi->leerXML($dir);
		
		
	//hacer el update de los datos de la factura
		
	$sql = "update facturacfdi set ";
		
	$sql .= " serie= '" . $xmlcfdi->comprobante["Serie"]["valor"] . "',";
		
	$sql .= " folio =" . $xmlcfdi->comprobante["Folio"]["valor"] . ",";
		
	$fecha =substr($xmlcfdi->comprobante["Fecha"]["valor"],0,10); 
		
	$hora =substr($xmlcfdi->comprobante["Fecha"]["valor"],12); 
		
	$sql .= " fecha= '$fecha',";
		
	$sql .= " hora = '$hora',";
		
	//$sql .= " noaprobacion = '" . $xmlcfdi->comprobante["noAprobacion"]["valor"] . "',";
		
	//$sql .= " anioaprobacion = '" . $xmlcfdi->comprobante["anoAprobacion"]["valor"] . "',";
		
	$sql .= " subtotal = " . $xmlcfdi->comprobante["SubTotal"]["valor"] . ",";
		
	if(@$xmlcfdi->comprobante["Impuestos"]["TotalImpuestosRetenidos"]["valor"]=='')
		
	{
			
		$sql .= " retenciones=0 , ";	
		
	}
		
	else 
		
	{
			
		$sql .= " retenciones= " . @$xmlcfdi->comprobante["Impuestos"]["TotalImpuestosRetenidos"]["valor"] . ",";
		
	}
		
	//$sql .= " retenciones= " . @$xmlcfdi->comprobante["Impuestos"]["totalImpuestosRetenidos"]["valor"] . ",";
		
	$sql .= " traslados = " . $xmlcfdi->comprobante["Impuestos"]["TotalImpuestosTrasladados"]["valor"] . ",";
		
	$sql .= " total = " . $xmlcfdi->comprobante["Total"]["valor"] . ",";
		
	$sql .= " concepto = '" . $xmlcfdi->comprobante["Conceptos"]["Concepto"][0]["Descripcion"]["valor"] . "' ";
		
	$sql .= " where idfacturacfdi = $id ";
		
	$operacionfn = mysql_query($sql);

		
	

}



$mail = New correo2;
$cfd =  New cfdi33class;
//$ftp= New ftpcfdi;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
/*
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='fiador.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}




	if ($priv[0]!='1')
	{
		//Es privilegio para poder ver eset modulo, y es negado
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}

	//para el privilegio de editar, si es negado deshabilida el bot�n
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el bot�n
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}

*/



$cfd->tipocomprobante= $tipocfd;


if(!$archivo)
{

//echo $idcl . " ..... " . $datosl . $filtro;

switch($filtro)
{
case 1://libre (factura libre)
	//echo "libre";
	//sql para obtener el folio
	
	//echo $idcl . " " . $datosl;
	//exit();
	if(!$idcl)
	{
		//echo $datosl;
		$listacfdi=substr($datosl,1,-1);
		$separado = split("[*]",$listacfdi);
		$resultado="";
		foreach($separado as $key => $datos)
		{
			
			$d = split("[|]",$datos);
			$idd = split("[_]",$d[0]);
			switch($idd[0])
			{
			case 109: //correos
				$mailscl = split("[,]",$d[1]);
				for($icl=0;$icl<count($mailscl);$icl++)
				{
					switch($icl)
					{
					case 0:
						$emailcl=$mailscl[$icl];
						break;
					case 1:
						$emailcl1=$mailscl[$icl];
						break;
					case 2:
						$emailcl2=$mailscl[$icl];
						break;					
					}
				
				}	
				
				//$rfccl = $emailcl2;
				
				break;

			case 61: //RRFC
				$rfccl = $d[1];
				break;
			case 62://RNombre
				$nombrecl = $d[1];
				break;
			case 69://Rcalle
				$callecl =$d[1];
				break;
			/*
			case 70://noext
				$noextcl = $d[1];
				break;				
			case 64://noint
				$nointcl = $d[1];
				break;
			*/
			case 70://RColon
				$colcl = $d[1];
				break;
			/*	
			case 66://localizacion
				$loccl = $d[1];
				break;
			case 67://ref
				$refcl = $d[1];
				break;
			*/

			case 71://RMunic
				$delmuncl = $d[1];
				break;
			case 72://REdo
				$edocl = $d[1];
				break;
			case 73://Rpais
				$paiscl = $d[1];
				break;
			case 74://RCP
				$cpcl = $d[1];
				break;
			}
		}		
		$sql ="insert into clientelibre (nombrecl, rfccl, callecl, noexteriorcl,nointeriorcl, coloniacl,delmuncl, estadocl,paiscl,cpcl,loccl,refcl,emailcl,emailcl1,emailcl2) values (";
		$sql .="'$nombrecl',";
		$sql .="'$rfccl',";
		$sql .="'$callecl',";
		$sql .="'$noextcl',";
		$sql .="'$nointcl',";
		$sql .="'$colcl',";
		$sql .="'$delmuncl',";
		$sql .="'$edocl',";
		$sql .="'$paiscl',";
		$sql .="'$cpcl',";
		$sql .="'$loccl',";
		$sql .="'$refcl',";
		$sql .="'$emailcl',";
		$sql .="'$emailcl1',";
		$sql .="'$emailcl2')";
		$operacion = mysql_query($sql);
		$idcl=mysql_insert_id();
			
	}
	
	$sql = "select * from folios  where idfolios = $id";
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	
	$idfolios= $row["idfolios"];
	$serie =$row["serie"];
	$folios = $row["folios"];
	$terceros = $row["terceros"];
	$secuencia = $row["secuencia"];
	
	$folio = ++$secuencia;
	//$folio = 1;
	
	$sql = "update folios set secuencia = $folio where idfolios = $idfolios";
	$operacion = mysql_query($sql);		
	
	//crear el registro del contenedor base del de la lista de facturas libres
	//para poder enviar la variable $id
	$fechal=date("Y-m-d");
	$conceptol="";
	$subtotall=0;
	$impuestol=0;
	$totall=0;
	$notacreditol=0;
	$notacreditol=0;
	if ($tipocfd=='2')
	{
		$notacreditol=1;
	}	
	
		$listacfdi=substr($datosl,1,-1);
		$separado = split("[*]",$listacfdi);
		$resultado="";
		foreach($separado as $key => $datos)
		{
			
			$d = split("[|]",$datos);
			$idd = split("[_]",$d[0]);
			switch($idd[0])
			{
			
//+++++++++++++++++++++
			case 130:
				$condeptol = $d[1];
				break;
			case 191:	//TotNeto
	
				$subtotall = $d[1];
				break;
				
			case 190:	//TotImp

				$impuestol =$d[1];
				break;

			case 193:	//Importe

				$totall = $d[1];
				break;				
				
	
			}
		}		
	
	
	$sql = "insert into cfdilibre (seriel, foliol,fechal,conceptol,subtotall,impuestol,totall,notacreditol,idclientelibre)values('$serie',$folio,'$fechal','$condeptol',$subtotall,$impuestol,$totall,$notacreditol,$idcl)";
	$operacion = mysql_query($sql);
	
	$idfl=mysql_insert_id();
	
	//preparo y genero el envio
	$solicitud = cadena_factura_libre($serie,$folio,$datosl,$cfd);
	//echo "<textarea cols = '60' rows='40'>$solicitud </textarea>";
	$cfd->comprobante();
	echo "Se envi&oacute;:<br><textarea cols = '60' rows='40'>" . $cfd->xml . "</textarea><br>";
	

	$resultado = $cfd->timbrar($filtro, $idfl);
	
	
	//verificar el resultado apra saber si fue exitoso con los nombres de los archivos. $idfl es el actual, tomar del idclientelibre el 
	// o los correos electronicos a enviar el mensaje con las facturas.
	
	//echo "Resultado: $resultado";
	//exit();
	// si el resultado tiene la primera letra diferente a "c" o "f", significa que ya trae los nombres de los archivos y se puede env�ar la factura
    //$mail->enviaf($parac, $asuntoc, $mensajec,$facturas,$nombre)
    //$para = consulta para extraer los correos del $idfl o recucperarlos de las variables iniciales de correos
    //$asuntoc = Asunto del correo, por definir ej. Envio de factura.
    //$mensaje = Por definir, ej. Se env�a correo de la factura como documento ajnuto, conserve el documento.
    //$facturas = debe de ser $resultado ya que el valor que debe de tener es pdf_archivo|xml_archivo con toda la ruta fisica 
    //$nombre = nombre del receptor de la factura. que se puede omitir, ya que el mensaje se puede editar con todo y el nombre.
    if(substr($resultado,0,1) <> 'c' &&  substr($resultado,0,1) <> 'f')
	{	
        //echo "enviar correo";
		$sqlmail = "select * From cfdilibre f, clientelibre c where f.idclientelibre = c.idclientelibre and f.idcfdilibre = $idfl";
		//obtener informaci�n del cliente

		$operacionmail = mysql_query($sqlmail);
		$rowmail = mysql_fetch_array($operacionmail);

		$para ="";
		if($rowmail["emailcl"]!="" )
		{
			$para .=$rowmail["emailcl"] . ",";
		}
		if($rowmail["emailcl1"]!="" )
		{
			$para .=$rowmail["emailcl1"] . ",";
		}	
		if($rowmail["emailcl2"]!="" )
		{
			$para .=$rowmail["emailcl2"] . ",";
		}
		// Cambio 23/06/2021
		// Se agregan los correos obtenidos de la petición POST a la lista de destinatarios
		if (!$correoAdicional_1 && $correoAdicional_1 != ""){
			$para .= $correoAdicional_1 . ",";
		}
		if (!$correoAdicional_2 && $correoAdicional_2 != ""){
			$para .= $correoAdicional_2 . ",";
		}
		if (!$correoAdicional_3 && $correoAdicional_3 != ""){
			$para .= $correoAdicional_3 . ",";
		}

		$para = substr($para,0,-1);
       

		$asunto = "Factura";
		$mensaje = "Apreciad@ " . $rowmail["nombrecl"] . ", se adjunta en este correo su factura.";
        // Cambio 23/06/2021
		// Se cambia la función que realiza el envio del correo para que se envie desde 
		// mensajeria@padilla-bujalil.com
		// $malresultado = $mail->enviaf($para, $asunto, $mensaje, $resultado);
		$malresultado = $mail->enviafFromPadilla($para, $asunto, $mensaje, $resultado);

	}
	else
	{
		echo "Error en el comprobante:<b> $resultado </b>";
	}
    
    
    
    
    
    
	break;

case 2://due�os 
	//echo "duenios";
	//sql para obtener el folio
	
	//exit();
	//separa
	
	//$sql = "select * from folios  where idfolios = 3";
	$sql = "select * from folios  where idfolios = 1";
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	
	$idfolios= $row["idfolios"];
	$serie =$row["serie"];
	$folios = $row["folios"];
	$terceros = $row["terceros"];
	$secuencia = $row["secuencia"];
	
	$folio = ++$secuencia;
	
	$sql = "update folios set secuencia = $folio where idfolios = $idfolios";
	$operacion = mysql_query($sql);		
	
	//crear el registro del contenedor base del de la lista de facturas libres
	//para poder enviar la variable $id
	$fechal=date("Y-m-d");
	$conceptol="";
	$subtotall=0;
	$impuestol=0;
	$totall=0;
	$notacreditol=0;
	
	//$sql = "select * from cfdiedoduenio where idcfdiedoduenio = $id";
	// Cambio 02/09/2021
	// Al realizar la factura se verifica que el concepto se haya marcado como reportado y la fecha que se generó el reporte
	// en caso de que la peticion no llegue con la fecha se entiende que se van a facturar los conceptos no reportados
	if(empty($fechagen))
		$sql = "select * From cfdiedoduenio df, cfdipartidas pf, edoduenio e where df.idcfdiedoduenio = pf.idcfdiedoduenio and pf.idedoduenio = e.idedoduenio and e.reportado = 0 and e.facturar = 1 and df.idcfdiedoduenio = $id ";
	else
		$sql = "select * From cfdiedoduenio df, cfdipartidas pf, edoduenio e where df.idcfdiedoduenio = pf.idcfdiedoduenio and pf.idedoduenio = e.idedoduenio and e.fechagen = '$fechagen' and e.reportado = 1 and e.facturar = 1 and df.idcfdiedoduenio = $id ";
	// Fin Cambio 02/09/2021
	
	$operacion = mysql_query($sql);
	//$row = mysql_fetch_array($operacion);
	
	//$solicitud = $cfd->preparar_ingreso($id,'',$row,$folio,$serie,$terceros,$int,0);	
	$solicitud = $cfd->preparar_duenio($id,'',$operacion,$folio,$serie);
	
	$cfd->comprobante();
	
    //echo "<textarea cols = '60' rows='40'>$cfd->xml </textarea>"; 
    //exit();
    $resultado = $cfd->timbrar($filtro, $id);
    
	echo "<textarea cols = '60' rows='40'>$resultado </textarea>";
	echo "<textarea cols = '60' rows='40'>$cfd->xml </textarea>";    
	
	/*
	
	//echo " verifico si esta nulo de $id :" . $row["listaf"] . " ... <br>";
	if(is_null($row["listaf"])== false )
	{
		$datosl = $row["listaf"];
		
	}
	else
	{
		//obtener de nuevo los importes
		//echo "nuevos importes...<br>";
		$sqlf = "select sum(ROUND(importe,2)) as imp, sum(ROUND(iva,2)) as iv from cfdipartidas p, edoduenio  e  where p.idedoduenio = e.idedoduenio and idcfdiedoduenio = $id";
		
		$operacionf = mysql_query($sqlf);
		$rowf = mysql_fetch_array($operacionf);
		$importe = $rowf["imp"] * ( -1);
		$iva = $rowf["iv"] * ( -1);		
		
		
		//regenerar conceptos 
		//echo "recponstruyo conceptos...<br>";
		//$listafactura = "";
		$conceptoscfdi = "";
		$sqlf = "select e.idedoduenio as ided, notaedo, importe, idinmueble, iva from cfdipartidas p, edoduenio e   where p.idedoduenio = e.idedoduenio and idcfdiedoduenio = $id";
		$operacionf = mysql_query($sqlf);
		while($rowf = mysql_fetch_array($operacionf))
		{
			$pre = "";
			if($rowf["idinmueble"]!=0)
			{
				$sqlin = "select * from inmueble where idinmueble = " . $rowf["idinmueble"];
				$operacionin = mysql_query($sqlin);
				$rowin= mysql_fetch_array($operacionin);
				$pre = "(" . $rowin["calle"] . " no. " . $rowin["numeroext"] . " - " . $rowin["numeroint"] . ") ";
			}
		
		
		
			//hay que confiramr el caso de que no tenga una factura asociada con anteriorirdad este concepto
			//antes de ser agregado a la lista de conceptos.
			
			//$sqlfa = "select count(*) as hay from cfdipartidas cp where idedoduenio = " . $rowf["ided"];		
			//$operacionfa = mysql_query($sqlfa);
			//$rowfa = mysql_fetch_array($operacionfa);
			
			//if($rowfa["hay"]<2)
			//{
				//$listafactura .= "($idf," . $rowf["idedoduenio"] . "),";
				$conceptoscfdi .= $pre . $rowf["notaedo"] . "|" . ($rowf["importe"] * (-1)) . "|" . ($rowf["iva"] * (-1)) . "*";
			//}
		}		
	
		
		//echo "regenero lista para factura... $conceptoscfdi<br>";
		//regenerar texto de factura
		$datosl = listafactura ($row["idduenio"], $conceptoscfdi, $importe, $iva,'ingreso');
		
		//actualizar todo
		//echo "Actualizo registro con nuevos valores...<br>";
		$sqlu = "update cfdiedoduenio set subtotald = $importe, impuesto = $iva, totald=" . ($importe + $iva) . " conceptod='$conceptoscfdi' listaf ='$datosl' where idcfdiedoduenio = $id" ;
		$operacionu = mysql_query($sqlu);
	}
	
		$listacfdi=substr($datosl,1,-1);
		$separado = split("[*]",$listacfdi);
		$resultado="";
		foreach($separado as $key => $datos)
		{
			
			$d = split("[|]",$datos);
			$idd = split("[_]",$d[0]);
			switch($idd[0])
			{
			
//+++++++++++++++++++++
			case 130:
				$condeptol = $d[1];
				break;
			case 191:	//TotNeto
	
				$subtotall = $d[1];
				break;
				
			case 190:	//TotImp

				$impuestol =$d[1];
				break;

			case 193:	//Importe

				$totall = $d[1];
				break;				
				
	
			}
		}		
	
	
	$sql = "update cfdiedoduenio set seried='$serie', foliod='$folio', fechad='$fechal', horad='' where idcfdiedoduenio = $id";
	$operacion = mysql_query($sql);
	
	$idfl=$id;
	
	//preparo y genero el envio
	$solicitud = $cfd->cadena_factura_libre($serie,$folio,$datosl);
	echo "<textarea cols = '60' rows='40'>$solicitud </textarea>";	
	
	
	$aa = $ftp->creararchivo($solicitud,$serie,$folio,$idfl,$filtro,'PUE');
	//echo "envio factura";


	
	$ab = $ftp->enviar($idfl,2);
	if($ab==0)
	{
		echo "Error en el env&iacute;o";
	}
	$ac = $ftp->recoger($idfl,2);	
	
*/

	break;



default: //arrendamiento (inmuebles)

	$sql="select * from historia where idhistoria=$id";
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);


	$idc0=$row["idcontrato"];
	$fechanp=$row["fechanaturalpago"];
	$idcon=$row["idcobros"];
	$int=$row["interes"];
	$fechagen=$row["fechagenerado"];
	$parcialde=$row["parcialde"];
	$idcategriat=$row["idcategoriat"];

	if($idcategriat==2)
	{
		exit();
	}
	//filtro = 1: fiscal  3:interes
	//obtengo que serie y folio de la factura que se va a usar
	//el dato del folio depende del tipo de cobro y ahi estael vinculo para poder obtener el folio
	if ($int==1)
	{
		$sql= "select folios.idfolios as idf, serie, folios, secuencia, terceros from folios where idfolios = 1";	

	}
	else
	{
		$sql="select folios.idfolios as idf, serie, folios, secuencia, terceros from historia, cobros,tipocobro,folios where historia.idcobros=cobros.idcobros and cobros.idtipocobro = tipocobro.idtipocobro and tipocobro.idfolios=folios.idfolios and idhistoria = $id";
	}
	
	//$sql="select folios.idfolios as idf, serie, folios, secuencia, terceros from historia, cobros,tipocobro,folios where historia.idcobros=cobros.idcobros and cobros.idtipocobro = tipocobro.idtipocobro and tipocobro.idfolios=folios.idfolios and idhistoria = $id";// and idcategoria = $filtro";
	
	$operacion = mysql_query($sql);	
	$row = mysql_fetch_array($operacion);
	$idfolios= $row["idf"];
	$serie =$row["serie"];
	$folios = $row["folios"];
	$terceros = $row["terceros"];
	$secuencia = $row["secuencia"];
	
	$folio = ++$secuencia;
	
	$sql = "update folios set secuencia = $folio where idfolios = $idfolios";
	$operacion = mysql_query($sql);	
	
	//ya tengo el folio y ya actualice la secuencia del folio en cuestion
	
	//$sql="select cont.idcontrato as idc, day(fechanaturalpago) as dia, month(fechanaturalpago) as mes, year(fechanaturalpago) as anio, fechanaturalpago, calle, numeroext, numeroint, i.colonia, delmun, i.cp, inc.nombre as nombrei, inc.nombre2 as nombre2i, inc.apaterno as apaternoi, inc.amaterno as amaternoi, rfc, estado, pais, d.nombre as nombred, d.nombre2 as nombre2d, d.apaterno as apaternod, d.amaterno as amaternod, rfcd, c.cantidad, c.iva,tipocobro,called,numeroextd,numeorintd,coloniad,cpd,delmund,estadod,paisd, metodopago  from historia h, inmueble i, inquilino inc, cobros c, duenio d, contrato cont, estado e, pais p, tipocobro tc, metodopago mp where h.idmetodopago = mp.idmetodopago and c.idtipocobro = tc.idtipocobro and h.idcontrato = cont.idcontrato and h.idcobros = c.idcobros and cont.idinquilino = inc.idinquilino and cont.idinmueble = i.idinmueble and i.idduenio = d.idduenio and i.idpais = p.idpais and i.idestado = e.idestado and idhistoria = $id";
	//$sql="select cont.idcontrato as idc, day(fechanaturalpago) as dia, month(fechanaturalpago) as mes, year(fechanaturalpago) as anio, fechanaturalpago, calle, numeroext, numeroint, i.colonia, delmun, i.cp, inc.nombre as nombrei, inc.nombre2 as nombre2i, inc.apaterno as apaternoi, inc.amaterno as amaternoi, rfc, estado, pais, c.cantidad, c.iva,tipocobro, metodopago from historia h, inmueble i, inquilino inc, cobros c, contrato cont, estado e, pais p, tipocobro tc, metodopago mp where h.idmetodopago=mp.idmetodopago and c.idtipocobro = tc.idtipocobro and h.idcontrato = cont.idcontrato and h.idcobros = c.idcobros and cont.idinquilino = inc.idinquilino and cont.idinmueble = i.idinmueble and i.idpais = p.idpais and i.idestado = e.idestado and idhistoria = $id";

	$intf="";
	if($int==1)
	{
		$intf=" AND h.interes=1  AND fechagenerado ='$fechagen' ";
	}
	else
	{
		$intf=" AND h.interes=0 ";
	}



	//$filtro=	" and fechanaturalpago='$fechanp' and h.idcobros=$idcon and h.idcontrato=$idc0  $intf group by cont.idcontrato, fechanaturalpago, calle, numeroext, numeroint, i.colonia, delmun, i.cp, inc.nombre, inc.nombre2 , inc.apaterno , inc.amaterno , rfc, estado, pais ,tipocobro, h.interes";
	//$filtro=	" and fechanaturalpago='$fechanp' and h.idcobros=$idcon and h.idcontrato=$idc0  $intf group by cont.idcontrato, fechanaturalpago, callei, noexteriori, nointeriori, coloniai, delmuni, cpi, inc.nombre, inc.nombre2 , inc.apaterno , inc.amaterno , rfc, estado, pais ,tipocobro, h.interes";
	//$filtro=	" and fechanaturalpago='$fechanp' and h.idcobros=$idcon and h.idcontrato=$idc0  $intf group by cont.idcontrato, fechanaturalpago, callei, noexteriori, nointeriori, coloniai, delmuni, cpi, inc.nombre, inc.nombre2 , inc.apaterno , inc.amaterno , rfc, estado, pais ,tipocobro, h.interes, idtipocontrato, calle, numeroext, numeroint, parcialde";

	$filtro=	" AND fechanaturalpago='$fechanp' AND h.idcobros=$idcon AND h.idcontrato=$idc0  $intf GROUP BY cont.idcontrato, fechanaturalpago, callei, noexteriori, nointeriori, coloniai, delmuni, cpi, inc.nombre, inc.nombre2 , inc.apaterno , inc.amaterno , rfc, estado, pais ,tipocobro, h.interes, idtipocontrato, calle, numeroext, numeroint, parcialde, observaciones";


	//$sql="select cont.idcontrato as idc, day(fechanaturalpago) as dia, month(fechanaturalpago) as mes, year(fechanaturalpago) as anio, fechanaturalpago, calle, numeroext, numeroint, i.colonia, delmun, i.cp, inc.nombre as nombrei, inc.nombre2 as nombre2i, inc.apaterno as apaternoi, inc.amaterno as amaternoi, rfc, estado, pais ,tipocobro, h.interes as inth, 	SUM(h.cantidad) as csuma, SUM(h.iva) as iiva  from historia h, inmueble i, inquilino inc, cobros c, contrato cont, estado e, pais p, tipocobro tc, metodopago mp where h.idmetodopago=mp.idmetodopago and c.idtipocobro = tc.idtipocobro and h.idcontrato = cont.idcontrato and h.idcobros = c.idcobros and cont.idinquilino = inc.idinquilino and cont.idinmueble = i.idinmueble and i.idpais = p.idpais and i.idestado = e.idestado $filtro";

	//$sql="select cont.idcontrato as idc, day(fechanaturalpago) as dia, month(fechanaturalpago) as mes, year(fechanaturalpago) as anio, fechanaturalpago, callei as calle, noexteriori as numeroext, nointeriori as numeroint, coloniai as colonia, delmuni as delmun, cpi as cp, inc.nombre as nombrei, inc.nombre2 as nombre2i, inc.apaterno as apaternoi, inc.amaterno as amaternoi, rfc, estado, paisi as pais ,tipocobro, h.interes as inth, 	SUM(h.cantidad) as csuma, SUM(h.iva) as iiva  from historia h, inmueble i, inquilino inc, cobros c, contrato cont, estado e, pais p, tipocobro tc, metodopago mp where h.idmetodopago=mp.idmetodopago and c.idtipocobro = tc.idtipocobro and h.idcontrato = cont.idcontrato and h.idcobros = c.idcobros and cont.idinquilino = inc.idinquilino and cont.idinmueble = i.idinmueble and i.idpais = p.idpais and inc.idestadoi = e.idestado $filtro";
	//$sql="select cont.idcontrato as idc, day(fechanaturalpago) as dia, month(fechanaturalpago) as mes, year(fechanaturalpago) as anio, fechanaturalpago, callei as calle, noexteriori as numeroext, nointeriori as numeroint, coloniai as colonia, delmuni as delmun, cpi as cp, inc.nombre as nombrei, inc.nombre2 as nombre2i, inc.apaterno as apaternoi, inc.amaterno as amaternoi, rfc, estado, paisi as pais ,tipocobro, h.interes as inth, 	SUM(h.cantidad) as csuma, SUM(h.iva) as iiva ,idtipocontrato from historia h, inmueble i, inquilino inc, cobros c, contrato cont, estado e, pais p, tipocobro tc, metodopago mp where h.idmetodopago=mp.idmetodopago and c.idtipocobro = tc.idtipocobro and h.idcontrato = cont.idcontrato and h.idcobros = c.idcobros and cont.idinquilino = inc.idinquilino and cont.idinmueble = i.idinmueble and i.idpais = p.idpais and inc.idestadoi = e.idestado $filtro";
	//$sql="select cont.idcontrato as idc, day(fechanaturalpago) as dia, month(fechanaturalpago) as mes, year(fechanaturalpago) as anio, fechanaturalpago, callei as calle, noexteriori as numeroext, nointeriori as numeroint, coloniai as colonia, delmuni as delmun, cpi as cp, inc.nombre as nombrei, inc.nombre2 as nombre2i, inc.apaterno as apaternoi, inc.amaterno as amaternoi, rfc, estado, paisi as pais ,tipocobro, h.interes as inth, 	SUM(h.cantidad) as csuma, SUM(h.iva) as iiva ,idtipocontrato, calle as callein, numeroext as noextin, numeroint as nointin, parcialde from historia h, inmueble i, inquilino inc, cobros c, contrato cont, estado e, pais p, tipocobro tc, metodopago mp where h.idmetodopago=mp.idmetodopago and c.idtipocobro = tc.idtipocobro and h.idcontrato = cont.idcontrato and h.idcobros = c.idcobros and cont.idinquilino = inc.idinquilino and cont.idinmueble = i.idinmueble and i.idpais = p.idpais and inc.idestadoi = e.idestado and idhistoria = $id  $filtro";

	$sql="SELECT cont.idcontrato AS idc, day(fechanaturalpago) AS dia, month(fechanaturalpago) AS mes, year(fechanaturalpago) AS anio, fechanaturalpago, callei AS calle, noexteriori AS numeroext, nointeriori AS numeroint, coloniai AS colonia, delmuni AS delmun, cpi AS cp, inc.nombre AS nombrei, inc.nombre2 AS nombre2i, inc.apaterno AS apaternoi, inc.amaterno AS amaternoi,email,email1,email2, rfc, estado, paisi AS pais ,tipocobro, h.interes AS inth,observaciones, 	SUM(h.cantidad) AS csuma, SUM(h.iva) AS iiva ,idtipocontrato, calle AS callein, numeroext AS noextin, numeroint AS nointin, parcialde,cuentapago, predial,claveucfdi,claveps,claveum,inc.tipofactura,h.fechapago,inc.idinquilino,cont.idtipocontrato FROM historia h, inmueble i, inquilino inc, cobros c, contrato cont, estado e, pais p, tipocobro tc, metodopago mp, c_usocfdi cuso, c_unidadmed cunid, c_prodserv cprod WHERE h.idmetodopago=mp.idmetodopago AND c.idtipocobro = tc.idtipocobro AND h.idcontrato = cont.idcontrato AND h.idcobros = c.idcobros AND cont.idinquilino = inc.idinquilino AND cont.idinmueble = i.idinmueble AND i.idpais = p.idpais AND inc.idestadoi = e.idestado AND inc.idc_usocfdi=cuso.idc_usocfdi AND tc.idc_prodserv=cprod.idc_prodserv AND tc.idc_unidadmed=cunid.idc_unidadmed AND idhistoria = $id  $filtro";


	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	
	//echo $cfd->muestradatos($cfd->lista);
	//$cfd->cadena_factura($id,$cfd->lista);

	//$solicitud = $cfd->cadena_factura($id,$cfd->lista,$row,$folio,$serie,$terceros,$int);
	$cfd->tipocomprobante=1;
	$pago=false;

	$sqlcfdi="SELECT * FROM historiacfdi hc, facturacfdi f WHERE hc.idfacturacfdi=f.idfacturacfdi AND hc.idhistoria=$parcialde";
	$operacioncfdi = mysql_query($sqlcfdi);	
	if(mysql_num_rows($operacioncfdi)>0)
	{
		while($rowcfdi= mysql_fetch_array($operacioncfdi))
		{
			if($rowcfdi["tipofactura"]=='PPD')
			{
				$pago=true;
				//$cfd->pagocfdi32class();
				$solicitud = $cfd->preparar_pago($id,'',$row,$folio,$serie,$int);
			}
		}
	}
	else
	{		
		$solicitud = $cfd->preparar_ingreso($id,'',$row,$folio,$serie,$terceros,$int,0);	
	}
    $cfd->comprobante();
    $resultado = $cfd->timbrar($filtro, $id);
    
    //verificar si el retulstado es un arreglo de 2 con sepraraci�n de "|", y que ademas que las ultimas 3 letras de 0 sean pdf y las e 1 sean xml
    //eso dignifica que fue exitoso y son los nombres de los archivos que env�ar�n por correo buz�n que se indico en el formulario.
    
	echo "<textarea cols = '60' rows='40'>$resultado </textarea>";
	echo "<textarea cols = '60' rows='40'>$cfd->xml </textarea>";
	//$solicitud = utf8_decode($solicitud );
	
	/*
	if($pago==true){
		$aa = $ftp->creararchivo($solicitud,$serie,$folio,$id,$filtro,"PAGO");
	}else{
		$aa = $ftp->creararchivo($solicitud,$serie,$folio,$id,$filtro,$row["tipofactura"]);
	}

	
	$ab = $ftp->enviar($aa,"");
	if($ab==0)
	{
		echo "Error en el env&iacute;o";
	}	
	$ac = $ftp->recoger($aa,"");

	
	if($ac==1)
	
	{				
		
		datoscfdi($ftp->archivoxml,$xmlcfdi,$aa);	
	
	}	
	*/
	
	$sqlnc = "select * from historia where notacredito = 1 and parcialde =" . $row["parcialde"];
	$operacionnc = mysql_query($sqlnc);
	
	if(mysql_num_rows($operacionnc)>0)
	{
		//hay notas de credito
		//Generarlas
		
		while ($rownc = mysql_fetch_array($operacionnc))
		{
		
		
			if ($int==1)
			{
				$sqlfo="select folios.idfolios as idf, serie, folios, secuencia, terceros from folios where idfolios = 1";// and idcategoria = $filtro";

			}
			else
			{
				$sqlfo="select folios.idfolios as idf, serie, folios, secuencia, terceros from historia, cobros,tipocobro,folios where historia.idcobros=cobros.idcobros and cobros.idtipocobro = tipocobro.idtipocobro and tipocobro.idfolios=folios.idfolios and idhistoria = $id";// and idcategoria = $filtro";
			}
	
			//$sql="select folios.idfolios as idf, serie, folios, secuencia, terceros from historia, cobros,tipocobro,folios where historia.idcobros=cobros.idcobros and cobros.idtipocobro = tipocobro.idtipocobro and tipocobro.idfolios=folios.idfolios and idhistoria = $id";// and idcategoria = $filtro";
	
			$operacionfo = mysql_query($sqlfo);	
			$rowfo = mysql_fetch_array($operacionfo);
			$idfolios= $rowfo["idf"];
			$serie =$rowfo["serie"];
			$folios = $rowfo["folios"];
			$terceros = $rowfo["terceros"];
			$secuencia = $rowfo["secuencia"];
	
			$folio = ++$secuencia;
	
			$sqlfo = "update folios set secuencia = $folio where idfolios = $idfolios";
			$operacionfo = mysql_query($sqlfo);			
		
		
		
		
		
		
			$cfd->tipocomprobante=2;
			$solicitud = $cfd->preparar_ingreso($id,'',$row,$folio,$serie,$terceros,$int,$rownc['idhistoria']);
			$cfd->comprobante();
            $resultado = $cfd->timbrar($filtro, $id);
			
			//$solicitud = $cfd->cadena_factura($id,$cfd->lista,$row,$folio,$serie,$terceros,$int,$rownc['idhistoria']);
			echo "<textarea cols = '60' rows='40'>$resultado </textarea>";	
			
			
			/*
			$aa = $ftp->creararchivo($solicitud,$serie,$folio,$id,$filtro,'PUE');
			
			
			
			$ab = $ftp->enviar($aa,"");
	
			if($ab==0)
			{
				echo "Error en el env&iacute;o";
			}			
	
			$ac = $ftp->recoger($aa,"");			
			
			
			if($ac==1)
			
			{				
				
			datoscfdi($ftp->archivoxml,$xmlcfdi,$aa);	
			
			}			
			*/
		
		
		}
	
	
	}
	
}



}
else
{//el archivo tiene algo
	
	//hacer la revision del archivo que no se envi� correctamente para hacer de nuevo el intento
	
	if($filtro == 2)
	{
/*	
	$sql = "select *, f.idfacturacfdi as idcfdi from facturacfdi f where   f.archivotxt like '%$archivo%'";
	$operacion = mysql_query($sql);
	while ($rownc = mysql_fetch_array($operacion))
	{
		$ftp->archivotxt = $archivo . ".txt";
		$ftp->archivopdf=$archivo . ".pdf";
		$ftp->archivoxml =$archivo . ".xml";
		$ftp->archivopdfn="pyb_" . $archivo . ".pdf";
		
		$idcfdi = $rownc["idcfdi"];
		
		$ab = $ftp->enviar($idcfdi,"");
		$ac = $ftp->recoger($idcfdi,"");		
	

		
		if($ab==1)
		
		{
			
			
			echo "Facturacion concluida $archivo. ";	
			
			if($ac==1)
			
			{ 
				
				datoscfdi($ftp->archivoxml,$xmlcfdi,$idcfdi);
			
			}
		
		}
		
		else 
		
		{
			
			echo "Error en el envio de la cadena generada.";				
		
		}
	
	
	}
	 
	echo "<br>favor de cerrar la ventana..";	
	
	*/
	
	
	}
	else
	{
	
	/*
	
	
	$sql = "select *, f.idfacturacfdi as idcfdi from historia h, historiacfdi hcfdi, facturacfdi f where h.idhistoria = $id and h.idhistoria = hcfdi.idhistoria and hcfdi.idfacturacfdi = f.idfacturacfdi and   f.archivotxt like '%$archivo%'";
	$operacion = mysql_query($sql);
	while ($rownc = mysql_fetch_array($operacion))
	{
		$ftp->archivotxt = $archivo . ".txt";
		$ftp->archivopdf=$archivo . ".pdf";
		$ftp->archivoxml =$archivo . ".xml";
		$ftp->archivopdfn="pyb_" . $archivo . ".pdf";
		
		$idcfdi = $rownc["idcfdi"];

		
		$ab = $ftp->enviar($idcfdi,"");

		$ac = $ftp->recoger($idcfdi,"");		
		

		if($ab==1)
		
		{
			
			
			echo "Facturacion concluida $archivo. ";	
			
			if($ac==1)
			
			{ 
				
				datoscfdi($ftp->archivoxml,$xmlcfdi,$idcfdi);
			
			}
		
		}
		
		else 
		
		{
			
			echo "Error en el envio de la cadena generada.";				
		
		}
	
	
	}
	 
	echo "<br>favor de cerrar la ventana..";
	*/
	}
	

}
	
	
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}


function cadena_factura_libre($serie, $folio, $listacfdi,&$cfdi)
	{
		
		//$serie = Serie aplicable a la factura
		//$folio = folio aplicabla a la factura
		//$listacfdi = cadena que llevar� a los terceros

		
		//los conceptos de terceros, receptor, conceptos,impuestos, van a ser cadenas separadas por "|" en los datos y
		//los registros separados por *
		
		
		
		$listacfdi=substr($listacfdi,1,-1);
		$separado = split("[*]",$listacfdi);
		//var_dump($separado);
		$resultado="";
		$resultado0="";
		$resultado1="";
		$resultado2="";
		$resultado3="";
		$CampoTercero="";
		$listater[0][0]="";
		$listaidter=0;
		$MontImpT=0;
		$ImpTer=0;
		foreach($separado as $key => $datos)
		{
			
			$d = split("[|]",$datos);
			$idd = split("[_]",$d[0]);
			switch($idd[0])
			{
			
//+++++++++++++++++++++

// datos del comprobante

			case 5: //serie	
				$resultado1 .= $idd[1] . "\t" . $serie . "\n";
				$cfdi->serie=$serie ;
				break;
			case 6:	//folio
				$resultado1 .= $idd[1] . "\t" . $folio . "\n";
				$cfdi->folio=$folio;
				break;	
				
				
			case 8:	//fecha comprobante
				
				$cfdi->fecha=$d[1];
				break;	

			case 10:	//forma de pago
				
				$cfdi->formapago=$d[1];
				break;	

			case 26:	//moneda
				
				$cfdi->moneda=$d[1];
				break;	
			
			case 29:	//tipo de comprobante
				$cfdi->tipocomprobante=$d[1];
				
				break;

			case 30:	//metodo de pago
				
				$cfdi->metodopago=$d[1];
				break;				
				
			case 31:	//lugar de expedicion
				
				$cfdi->lugarexpedicion=$d[1];
				break;				
				
//datos de receptor						
				
			case 61: //RRFC	
				$rf =$d[1];
				if (strlen($rf)<12)
				{
				
					$rf="XAXX010101000";
				}
				
				$cfdi->receptor_rfc=trim($rf);
				
				break;	

			case 62:	//nombre receptor
				$nombrer= str_replace("&", "&amp;", $d[1]);
				$cfdi->receptor_nombre=$d[1];
				break;			

			case 65:	//uso del cfid
				
				$cfdi->receptor_usocfdi=$d[1];
				break;	
	
//Datos emisor	
	
			case 41: //rfc emisor	
				$rf =$d[1];
				//$cfdi->emisor_rfc=trim($rf);
				
				break;	

			case 42:	//nombre receptor
				
				//$cfdi->emisor_nombre=$d[1];
				break;			

			case 43:	//uso del cfid
				
				//$cfdi->emisor_regimenfiscal=$d[1];
				break;		
	
// datos del concepto

			case 125: //cantidad	
				$cfdi->concepto["cantidad"]=$d[1];
				break;	

			case 121:	//clave producto
				
				$cfdi->concepto["claveprodserv"]=$d[1];
				break;			

			case 127:	//calve unidad de medida
				
				$cfdi->concepto["claveunidad"]=$d[1];
				break;

			case 128: //texto unidad de medida	
				$cfdi->concepto["unidad"]=$d[1];
				break;	

			case 130:	//descripcion
				
				$cfdi->concepto["descripcion"]=$d[1];
				break;			

			case 131:	//valor unitario
				
				$cfdi->concepto["valorunitario"]=$d[1];
				break;

			case 173:	//predial
				
				$cfdi->concepto["predial"]=$d[1];
				break;

			case 161:	//base calculo
				
				$cfdi->concepto_traslado["base"] =$d[1];
				break;


			case 160:	//base calculo
				
				$cfdi->concepto_traslado["impuesto"]  =$d[1];
				break;

			case 163:	//base calculo
				
				$cfdi->concepto_traslado["tipofactor"]  =$d[1];
				break;

			case 164:	//base calculo
				
				$cfdi->concepto_traslado["tasaocuota"] =$d[1];
				break;

			case 165:	//base calculo
				
				$cfdi->concepto_traslado["impordte"]  =$d[1];
				break;

			case 109: //EmailCont
					//correos de los inquilinos.
				
					$correos = $idd[1] . "\t" . $d[1] . "\n";
				break;
					
			case 187: //terceros	
				//leer el siguiente dato y acomodar los datos viene separado por "["
				//ej "id[predial[porcentaje
				//$d[1]: es el dato a evaluar
				
				if($d[1]!="")
				{ 
					$listater[$listaidter][0]=$idd[1] ;
					$listater[$listaidter][1]=$d[1] ;
					$listaidter +=1;
					$CampoTercero="Complemento\t" ." TERCEROS\n";
				}

				break;
			case 196:	//PorImpT impuesto para tercero

				//$ImpTer=number_format(($d[1]*100),0);
				$ImpTer=$d[1];
				break;	
				
			case 197:	//MonImpT

				$MontImpT=number_format($d[1],6,".","");
				break;				
					
			}
		}
		
		if($listater[0][0]!="")
		{
			for($jidter=0;$jidter<=$listaidter-1;++$jidter)
			{

				$valter = split("[~]",$listater[$jidter][1]);
				//terceros
				$sqlter = "select * from duenio  where idduenio = ". $valter[0];
				$operacionter = mysql_query($sqlter);
				//$Tercerosbloques="";
				$rowt = mysql_fetch_array($operacionter);
				
				$resultado2 .= "Campo0" . "\tTERCERO\n";
				$rf =$rowt["rfcd"];
				if (strlen($rf)<12)
				{
					$rf="XAXX010101000";
				}
				
//version'=>"", 'nombre'=>"", 'rfc'=>"",'calle'=>"",'noexterior'=>"",'nointerior'=>"",'colonia'=>"",'localida'=>"",'referencia'=>"",'municipio'=>"",'estado'=>"",'pais'=>"",'codigopostal'=>"",'cuentapredial'=>"",'importe'=>"",'iva'=>"",'tasa'=>""				
				
				$cfdi->tercero["version"]="1";
				$cfdi->tercero["nombre"]=trim($rowt["nombre"] . " " . $rowt["nombre2"] . " " . $rowt["apaterno"] . " " . $rowt["amaterno"]) ;
				$cfdi->tercero["rfc"]=$rf;
				$cfdi->tercero["calle"]=$rowt["called"];
				$cfdi->tercero["noexterior"]=$rowt["noexteriord"];
				$cfdi->tercero["nointerior"]=$rowt["nointeriord"];
				$cfdi->tercero["colonia"]=$rowt["coloniad"];
				$cfdi->tercero["localida"]="1";
				$cfdi->tercero["referencia"]="1";
				$cfdi->tercero["municipio"]=$rowt["delmund"];
				$cfdi->tercero["estado"]=$rowt["estadod"];
				$cfdi->tercero["pais"]=$rowt["paisd"];
				$cfdi->tercero["codigopostal"]=$rowt["cpd"];
				$cfdi->tercero["cuentapredial"]=$cfdi->concepto["predial"];
				//$cfdi->tercero["importe"]="1";
				$cfdi->tercero["iva"]="IVA";
				$cfdi->tercero["tasa"]=$ImpTer;

				$partet=0;
				if(is_null($valter[2])==true || $valter[2]=='')
					{
						$partet=1;
					}
					else
					{
						$partet=$valter[2]/100;	
					}
                $cfdi->tercero["importe"]=number_format(($MontImpT*$partet),2,".","");
			    $cfdi->estercero++;
			    array_push($cfdi->terceros, $cfdi->tercero);
			
					
			}
			

		}
		
		$cfdi->nocertificado="00001000000502052217";
		$cfdi->version="3.3";
		$cfdi->sello="";
        $cfdi->certificado="";
		//$cfdi->estercero=$listaidter;
		//echo "el total de terceros es:" . $cfdi->estercero . "<br>";
		$cfdi->concepto_traslado["importe"]=$cfdi->concepto_traslado["base"] * $cfdi->concepto_traslado["tasaocuota"] ;
		array_push($cfdi->concepto_traslados, $cfdi->concepto_traslado);
		//var_dump($cfdi->concepto_traslados);
		$cfdi->concepto["tra"] =$cfdi->concepto_traslados;
		//var_dump($cfdi->concepto["tra"] );
		array_push($cfdi->lconceptos, $cfdi->concepto);
			
//+++++++++++++++++++++			
		
			
        return 1;
		//return $resultado;
	
	}	







function listafactura ($idduenio, $concepto, $imp, $iva,$tipor)
{


// se obtienen los datos de losdue�os

$sqlf = "select * from duenio where idduenio = $idduenio";
$operacionf = mysql_query($sqlf);
$rowf = mysql_fetch_array($operacionf);

//datos cabecera del cfdi
$datosl = '1_CFD|380*';
$datosl .= '2_Version|3.3*';

$datosl .= '5_Serie|*';
$datosl .= '6_Folio|*';

$datosl .= '8_Fecha|'. date("Y-m-d") . "T" . date("H:m:s") . '*';

$datosl .= '10_FormaPago|99*';

$datosl .= '26_DivisOp|MXN*';



$datosl .= '29_EfectoCFD|' . $tipor . '*';
$datosl .= '30_MetPago|PUE*';
$datosl .= '31_LugarExp|06470*';

$cabecera = $datosl;

//Datos del emisor
$datosl = '41_ERFC|PAB0802225K4*';
$datosl .= '42_ENombre|PADILLA & BUJALIL S.C.*';
$datosl .= '43_RegFiscal|601*';

$datosl .= '46_ECalle|AV. INSURGENTES CENTRO*';
$datosl .= '47_EColon|SAN RAFAEL*';
$datosl .= '48_EMunic|CUAUHTEMOC*';
$datosl .= '49_EEdo|CIUDAD DE MEXICO*';
$datosl .= '50_Epais|MEXICO*';
$datosl .= '51_ECP|06470*';

$emisor = $datosl;

//receptor
$rfcduenio =$rowf["rfcd"];
if (strlen($rfcduenio)<12)
{
	$rfcduenio="XAXX010101000";
}

$datosl = '61_RRFC|' . $rfcduenio . '*';
$datosl .= '62_RNombre|' . $rowf["nombre"] . " " . $rowf["nombre2"] . " " . $rowf["apaterno"] . " " . $rowf["amaterno"] . '*';
$datosl .= '65_RUsoCFDI|P01*';

$datosl .= '69_Rcalle|' . $rowf["called"] . '*';
$datosl .= '70_RColon|' . $rowf["coloniad"] . '*';
$datosl .= '71_RMunic|' . $rowf["delmund"] . '*';
$datosl .= '72_REdo|' . $rowf["estadod"] . '*';
$datosl .= '73_Rpais|' . $rowf["paisd"] . '*';
$datosl .= '74_RCP|' . $rowf["cpd"] . '*';

$receptor = $datosl;

//concepto

//crear arreglo para los conceptos

$listac = split("[*]",$concepto);
$datosl ="";
$acimp=0;
$aciva=0;
for($i=0; $i<(count($listac)-1); $i++)
{

	$s = split("[|]",$listac[$i]);
	

//$datosl .= '122_Numlin|1*';
$datosl .= '119_Numlin|' . ($i+1) . '*';

$datosl .= '121_ClaveProdServ|80131800*';

$datosl .= '125_Cant|1*';

$datosl .= '127_ClaveUnidad|E48*';

$datosl .= '128_UM|SRV*';
$datosl .= '130_Desc|' . $s[0] . ' *';
$datosl .= '131_PrecMX|' . number_format($s[1],2,".","")  . '*';
$datosl .= '132_ImporMX|' . number_format($s[1],2,".","") . '*';

$datosl .= '160_DescTipoImp|002*';
$datosl .= '161_BaseImp|' . number_format($s[1],2,".","") . '*';
$datosl .= '162_CategImp|T*';
$datosl .= '163_TipoFactor|Tasa*';

$acimp +=round((1)*$s[1],2);
$porimp = number_format(round(($s[2]/$s[1]),2),6,".","");
$aciva +=round((1)*$s[2],2);
if ($porimp < 0.160000 || $porimp > 0.160000) 
{
	$porimp = number_format(0.16, 4);
}
else
{
	$porimp = number_format(round(($s[2]/$s[1]),2),6,".","") ;
}

$datosl .= '164_PorImp|' . $porimp . '*';
$datosl .= '165_ImporImp|' . number_format($s[2],2,".","") . '*';


}

$concepto = $datosl;


/* Aqui estan los terceros
$datosl .= '187_Campo0' value='TERCERO'/></td></tr>
$datosl .= '188_Campo1' value='RFC del arrendador'/></td></tr>
$datosl .= '189_Campo2' value='raz�n social o nombre'/></td></tr>
$datosl .= '190_Campo3' value='calle'/></td></tr>
$datosl .= '191_Campo4' value='numero interior'/></td></tr>
$datosl .= '192_Campo5' value='numero exterior'/></td></tr>
$datosl .= '193_Campo6' value='colonia'/></td></tr>
$datosl .= '194_Campo7' value='localidad'/></td></tr>
$datosl .= '195_Campo8' value='referencia'/></td></tr>
$datosl .= '196_Campo9' value='delegacion o municipio'/></td></tr>
$datosl .= '197_Campo10' value='estado'/></td></tr>
$datosl .= '198_Campo11' value='pais'/></td></tr>
$datosl .= '199_Campo12' value='codigo postal'/></td></tr>
$datosl .= '200_Campo13' value='numero de pedimento aduanero'/></td></tr>
$datosl .= '201_Campo14' value='fecha de aduana'/></td></tr>
$datosl .= '202_Campo15' value='nombre de aduana'/></td></tr>
$datosl .= '203_Campo16' value='cuenta predial'/></td></tr>
$datosl .= '204_Campo17' value=''/></td></tr>
$datosl .= '205_Campo18' value=''/></td></tr>
$datosl .= '206_Campo19' value=''/></td></tr>
$datosl .= '207_Campo20' value=''/></td></tr>
$datosl .= '208_Campo21' value=''/></td></tr>
$datosl .= '209_Campo22' value=''/></td></tr>
$datosl .= '210_Campo23' value=''/></td></tr>
$datosl .= '211_Campo24' value=''/></td></tr>
$datosl .= '212_Campo25' value=''/></td></tr>
$datosl .= '213_Campo26' value=''/></td></tr>
$datosl .= '214_Campo27' value=''/></td></tr>
$datosl .= '215_Campo28' value=''/></td></tr>
$datosl .= '216_Campo29' value=''/></td></tr>
$datosl .= '217_Campo30' value=''/></td></tr>
$datosl .= '218_Campo31' value=''/></td></tr>
$datosl .= '219_Campo32' value=''/></td></tr>
$datosl .= '220_Campo33' value=''/></td></tr>
$datosl .= '221_Campo34' value=''/></td></tr>
$datosl .= '222_Campo35' value=''/></td></tr>
$datosl .= '223_Campo36' value=''/></td></tr>
$datosl .= '224_Campo37' value=''/></td></tr>

$datosl .= '225_TpMonto' value='TERCERO'/></td></tr>
$datosl .= '226_PorMonto' value='tasa de impuesto'/></td></tr>
$datosl .= '227_Monto' value='importe del impuesto'/></td></tr>
$datosl .= '228_IDMonto' value='TRAS � RET'/></td></tr>
$datosl .= '229_ClsMonto' value='clase de impuesto (IVA, ISR, IEPS)'/></td></tr>
$datosl .= '230_FechaMonto' value=''/></td></tr>
*/



/*
$datosl = '189_TotImpT|' . number_format($iva,2,".","") . '*';
$datosl .= '190_TotImp|' . number_format($iva,2,".","") . '*';

$datosl .= '191_TotNeto|' . number_format($imp,2,".","") . '*';
$datosl .= '192_TotBruto|' . number_format($imp,2,".","") . '*';
$datosl .= '193_Importe|' . number_format(($imp+$iva),2,".","") . '*';

$datosl .= '194_TipImpT|002*';
$datosl .= '195_TipFactT|Tasa*';

$porimp1 = number_format(round(($iva/$imp),2),6,".","");
if ($porimp1 < 0.160000 || $porimp1 > 0.160000) 
{
	$porimp1 = number_format(0.16, 4);
}
else
{
	$porimp1 = number_format(round(($iva/$imp),2),6,".","")  ;
}



$datosl .= '196_PorImpT|' . $porimp1 . '*';
$datosl .= '197_MonImpT|' . number_format($iva,2,".","") . '*';
*/


$datosl = '189_TotImpT|' . number_format($aciva,2,".","") . '*';
$datosl .= '190_TotImp|' . number_format($aciva,2,".","") . '*';

$datosl .= '191_TotNeto|' . number_format($acimp,2,".","") . '*';
$datosl .= '192_TotBruto|' . number_format($acimp,2,".","") . '*';
$datosl .= '193_Importe|' . number_format(($acimp+$aciva),2,".","") . '*';


$porimp1 = number_format(round(($aciva/$acimp),2),6,".","");
if ($porimp1 < 0.160000 || $porimp1 > 0.160000) 
{
	$porimp1 = number_format(0.16, 4);
}
else
{
	$porimp1 = number_format(round(($aciva/$acimp),2),6,".","")  ;
}
	
$datosl .= '194_TipImpT|002*';
$datosl .= '195_TipFactT|Tasa*';
$datosl .= '196_PorImpT|' . $porimp1 . '*';
$datosl .= '197_MonImpT|' . number_format($aciva,2,".","") . '*';


$totales = $datosl;

/*
$datosl .= '246_Campo2' value='Serie de la factura original'/></td></tr>
$datosl .= '247_Campo3' value='fecha de la factura original'/></td></tr>
$datosl .= '248_Campo4' value='monto de la fcatura original'/></td></tr>
$datosl .= '249_Campo5' value=''/></td></tr>
$datosl .= '250_Campo6' value=''/></td></tr>
$datosl .= '251_Campo7' value=''/></td></tr>
$datosl .= '252_Campo8' value=''/></td></tr>
$datosl .= '253_Campo9' value=''/></td></tr>
$datosl .= '254_Campo10' value=''/></td></tr>
$datosl .= '255_Campo11' value=''/></td></tr>
*/

	return $datosl = $cabecera . $emisor . $receptor . $concepto . $totales;

}


?>
