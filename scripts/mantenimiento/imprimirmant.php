<?php
include '../general/funcionesformato.php';
include '../general/sessionclase.php';
//include '../general/ftpclass.php';

//include "insertcfdi/lxml3.php";
include_once('../general/conexion.php');
require('../fpdf.php');
require('../general/numero_a_letra.php');

header('Content-Type: text/html; charset=iso-8859-1');




$id=@$_GET["id"]; //para el Id de la consulta que se requiere hacer: de arrendamiento idhistoria, de libre idfolio
$accion=@$_GET["accion"];
$recibe=@$_GET["recibe"];
$fechacita=@$_GET["fechacita"];
$horacita=@$_GET["horacita"];
$trabajo=@$_GET["trabajo"];
$personal=@$_GET["idpersonal"];






$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='listapendientesmant.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta =$row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}


	if($accion==1)
	{
		$sql = "update mantenimientoseg set recibe = '$recibe', fechacita='$fechacita', horacita='$horacita', cambiofecha=true, observacionesm='$trabajo', idpersonal=$personal where idmantenimientoseg = $id ";
		$operacion = mysql_query($sql);
	}
	if($accion==2)
	{
		$sql = "update mantenimientoseg set recibe = '$recibe', fechacita='$fechacita', horacita='$horacita', cambiofecha=true, observacionesm='$trabajo', idpersonal=$personal, reagendado=true where idmantenimientoseg = $id ";
		$operacion = mysql_query($sql);
	}	

	$sql = "select *, inq.nombre as inqnombre, inq.nombre2 as inqnombre2, inq.apaterno as inqapaterno, inq.amaterno as inqamaterno, d.nombre as dnombre, d.nombre2 as dnombre2, d.apaterno as dapaterno, d.amaterno as damaterno, m.idmantenimiento as idm from mantenimientoseg ms, mantenimiento m, contrato c, inmueble inm, inquilino inq, duenioinmueble di, duenio d, tipoinmueble ti, tiposervicio ts  where ";
	$sql .="ms.idmantenimiento = m.idmantenimiento and m.idcontrato = c.idcontrato and c.idinquilino = inq.idinquilino and m.idtiposervicio = ts.idtiposervicio and ";
	$sql .=" c.idinmueble = inm.idinmueble and inm.idinmueble = di.idinmueble and di.idduenio = d.idduenio and inm.idtipoinmueble = ti.idtipoinmueble and  idmantenimientoseg = $id";
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);

	$tiposervicio = $row["tiposervicio"];
	$tipoinmueble = $row["tipoinmueble"];
	$inquilino = $row["inqnombre"] . " " . $row["inqnombre2"] . " " . $row["inqapaterno"]  . " " . $row["inqamaterno"];
	$direccion = $row["calle"] . " " . $row["numeroext"]  . " " . $row["numeroint"]  . ", Col. " . $row["colonia"]  . " Alc/Mun. " . $row["delmun"]  . ", C.P. " . $row["cp"];
	$propietario = $row["dnombre"] . " " . $row["dnombre2"] . " " . $row["dapaterno"]  . " " . $row["damaterno"];
	$fechap = $row["fechams"];
	$horap =$row["horams"];
	$noaccion=$row["novisita"];
	$idpersonal = $row["idpersonal"];
	$idsupervisor = $row["idsupervisor"];
	$trabajo = $row["observacionesm"];
	
	$fechacita = $row["fechacita"];
	$horacita =$row["horacita"];
	
	
	$productosutilizados=$row["productosutilizados"];
	$equiposutilizados=$row["equiposutilizados"];
	$reportepersonal=$row["reportepersonal"];
	$comentariocliente=$row["comentariocliente"];
	
	if($row["malo"]==1)
	{
		$malo=" Generación de servicio por mala calificación ";	
	}
	else 
	{
		$malo="";
	}	
	if($row["responsabilidadinquilino"]==1)
	{
		$responsabilidadinquilino=" checked ";
	}
	else
	{
		$responsabilidadinquilino="";
	}
	$evaluacion =$row["evaluacion"];


	$frente="formato-frente.jpg";
	$reverso="formato-reverso.jpg";
	$a="a.jpg";
	$b="b.jpg";
	$c="c.jpg";
	$logo="logo.jpg";
	$reporte="reporte.jpg";
	

	

//GeneraciÃ³n del recibo

	
	$pdf = new FPDF();
	$pdf->AddPage('P','letter');
	$pdf->SetFont('Arial','B',8);
	//$pdf->SetXY(10, 10);
	$yb=65;
	$alto_base = 45;
//	list($ancho, $alto, $tipo, $atributos) = getimagesize("$logo");
//	$ancho = ($ancho * $alto_base)/$alto;
//	$alto = $alto_base;
	
	
	//$pdf->Image($cbbjpg, 140 , 20,27.54,27.54);
	$pdf->SetAutoPageBreak(true,1);
	$pdf->SetFillColor(239,248,247);
	$pdf->SetDrawColor(229,229,227);
	
	//$pdf->Image($frente, 0 , 0,210,270);
	$pdf->Image($logo, 14 , 13);
	$pdf->SetFont('Arial','B',12);
	
	$pdf->SetXY(170, 13);	
	$pdf->MultiCell(35,10,'Visita ' . $noaccion,0,'L',false);
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY(130, 17);	
	$pdf->MultiCell(100,10, $malo,0,'L',false);
	
	$sqlfa="select max(fechacita) as fc from mantenimientoseg where cerrado=true and idmantenimiento = " . $row["idmantenimiento"];
	$operacionfa = mysql_query($sqlfa);
	$rowfa = mysql_fetch_array($operacionfa);	
	$fa="";
	if(is_null($rowfa["fc"])==false)
	{
			$fa=$rowfa["fc"];
	}
	
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY(110, 25);	
	$pdf->MultiCell(95,8,'' ,0,'L',true);
	$pdf->SetXY(110, 25);	
	$pdf->MultiCell(50,6,'Fecha de visita anterior: ' ,0,'L',true);	
	$pdf->SetXY(110, 25);
	$pdf->MultiCell(95,8,'' ,0,'L',false);	
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY(175, 25);
	$pdf->MultiCell(35,8,$fa ,0,'L');		
		

	if(strlen($tiposervicio)<18)
	{
		$pdf->SetFont('Arial','B',18);	
	}
	
	if(strlen($tiposervicio)>=18 && strlen($tiposervicio)<=25)
	{
		$pdf->SetFont('Arial','B',16);	
	}	
	
	if(strlen($tiposervicio)>25 && strlen($tiposervicio)<=30)
	{
		$pdf->SetFont('Arial','B',14);	
	}	
	
	if(strlen($tiposervicio)>30 && strlen($tiposervicio)<=40)
	{
		$pdf->SetFont('Arial','B',10);	
	}	
	
	if(strlen($tiposervicio)>40 )
	{
		$pdf->SetFont('Arial','B',8);	
	}	
	
	$pdf->SetFillColor(240,240,240);
	$pdf->SetTextColor(85,191,177);
	$pdf->SetXY(0, 55);	
	$pdf->Image($reporte, 0 , 55);
	$pdf->MultiCell(90,9,strtoupper($tiposervicio),0,'R',false);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFillColor(239,248,247);
	
	
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY(110, 36);
	$pdf->MultiCell(95,8,'' ,0,'L',true);
	$pdf->SetXY(110, 36);	
	$pdf->MultiCell(70,6,'Mantenimiento programado para el:' ,0,'L',false);
	$pdf->SetFont('Arial','B',12);			
	$pdf->SetXY(175, 36);	
	$pdf->MultiCell(30,8,$fechap,0,'L');
	
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY(110, 47);
	$pdf->MultiCell(50,8,'Servicio programado para:',0,'L');	
	
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY(110, 55);
	$pdf->MultiCell(45,8,'' ,0,'L',true);		
	$pdf->SetXY(110, 55);	
	$pdf->MultiCell(45,6,'Fecha' ,0,'L',false);	
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY(130, 55);	
	$pdf->MultiCell(25,8,$fechacita,0,'L');


	
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY(158, 55);
	$pdf->MultiCell(46,8,'' ,0,'L',true);		
	$pdf->SetXY(158, 55);	
	$pdf->MultiCell(45,6,'Hora' ,0,'L',false);	
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY(175, 55);	
	$pdf->MultiCell(25,8,date('g:i A',strtotime($horacita)),0,'L');	
	
	
	$pdf->SetXY(16, 70);	
	
	$pdf->Image($a, 16 , 70,188,5);
	$pdf->Image($c, 16 , 75,188,60);	
	
	$pdf->SetFillColor(250,250,250);	
	//$pdf->SetXY(16, 70);	
	//$pdf->MultiCell(188,67,'',1,'L',true);
	$pdf->SetFillColor(239,248,247);
	
	$pdf->SetFont('Arial','B',9);	
	$pdf->SetXY(19, 75);
	$pdf->MultiCell(44,8,'Nombre del inquilino:',0,'L');		
	
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY(57, 75);
	$pdf->MultiCell(140,8,'' ,0,'L',true);		
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY(60, 75);	
	$pdf->MultiCell(140,8,$inquilino,0,'L');
	
	
	$pdf->SetFont('Arial','B',9);	
	$pdf->SetXY(19, 85);
	$pdf->MultiCell(44,8,'Nombre de quien recibe:',0,'L');	
	
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY(60, 85);
	$pdf->MultiCell(137,8,'' ,0,'L',true);		
	$pdf->SetFont('Arial','B',12);		
	$pdf->SetXY(60, 85);	
	$pdf->MultiCell(137,8,$recibe,0,'L');
	
	
	$pdf->SetFont('Arial','B',9);	
	$pdf->SetXY(19, 95);
	$pdf->MultiCell(44,8,'Dirección del inmueble:',0,'L');	
	$pdf->SetXY(60, 96);
	$pdf->MultiCell(137,8,'' ,0,'L',true);	
	$pdf->SetXY(20, 107);
	$pdf->MultiCell(177,8,'' ,0,'L',true);				
	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(60, 95);	
	$pdf->MultiCell(140,10,$direccion,0,'L');
	
	$pdf->SetFont('Arial','B',9);	
	$pdf->SetXY(19, 118);
	$pdf->MultiCell(20,8,'Propietario:',0,'L');
	$pdf->SetXY(40, 118);
	$pdf->MultiCell(157,8,'' ,0,'L',true);			
	
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY(60, 118);	
	$pdf->MultiCell(137,8,$propietario,0,'L');	
	
	
	
	$pdf->SetFillColor(250,250,250);
	$pdf->SetFont('Arial','B',9);	
	$pdf->SetXY(19, 127);
	$pdf->MultiCell(178,8,'Tipo de inmueble:',0,'L',false);
	
	$pdf->SetFillColor(255,255,255);
//	case "CASA":
		$pdf->SetXY(55, 129);	
		$pdf->MultiCell(5,5,"",'TBLR','L',true);
		$pdf->SetXY(61, 129);	
		$pdf->MultiCell(18,5,"Casa",0,'L');		
		//break;
//	case "DEPARTAMENTO":
		$pdf->SetXY(81, 129);	
		$pdf->MultiCell(5,5,"",'TBLR','L',true);
		$pdf->SetXY(87, 129);	
		$pdf->MultiCell(25,5,"Departamento",0,'L');				
//		break;
//	case "LOCAL":
		$pdf->SetXY(123, 129);	
		$pdf->MultiCell(5,5,"",'TBLR','L',true);
		$pdf->SetXY(129, 129);	
		$pdf->MultiCell(18,5,"Local",0,'L');			
//		break;	
//	case "OFICINA":
		$pdf->SetXY(149, 129);	
		$pdf->MultiCell(5,5,"",'TBLR','L',true);
		$pdf->SetXY(155, 129);	
		$pdf->MultiCell(18,5,"Oficina",0,'L');			
//		break;			
//	case "CUARTO":
		$pdf->SetXY(178, 129);	
		$pdf->MultiCell(5,5,"",'TBLR','L',true);
		$pdf->SetXY(184, 129);	
		$pdf->MultiCell(18,5,"Cuarto",0,'L');			
//		break;
//	}	
	
	
	
	
	$pdf->SetFont('Arial','B',14);

	switch ($tipoinmueble)
	{
	case "CASA":
		$pdf->SetXY(55, 129);	
		$pdf->MultiCell(5,5,"X",0,'L');
		break;
	case "DEPARTAMENTO":
		$pdf->SetXY(81, 129);	
		$pdf->MultiCell(5,5,"X",0,'L');	
		break;
	case "LOCAL":
		$pdf->SetXY(123, 129);	
		$pdf->MultiCell(5,5,"X",0,'L');
		break;	
	case "OFICINA":
		$pdf->SetXY(149, 129);	
		$pdf->MultiCell(5,5,"X",0,'L');
		break;			
	case "CUARTO":
		$pdf->SetXY(178, 129);	
		$pdf->MultiCell(5,5,"X",0,'L');
		break;
	}		
	
	$ny = $pdf->GetY();	
	$pdf->Image($b, 16 , 135,188,4);
	
	
	


	
	$pdf->SetFillColor(250,250,250);
	$pdf->SetFont('Arial','B',9);	
	$pdf->SetXY(19, 141);
	//$pdf->SetXY(19, $t);
	$pdf->MultiCell(60,8,'Trabajo de mantenimiento a realizar:',0,'L');	
	
	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);	
	$pdf->Image($a, 16 , $ny,188,5);
	$ny = $pdf->GetY()+5;
	$t=$ny;	
		
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(16, $t);	
	$pdf->MultiCell(188,5,$trabajo,0,'L',false);
	
	$ny = $pdf->GetY();
	$tb=$ny;
	$pdf->Image($c, 16 , $t,188,$tb-$t);

		
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(16, $t);	
	$pdf->MultiCell(188,5,$trabajo,0,'L',false);
	
		
	$pdf->Image($b, 16 , $tb,188,4);
	$pdf->SetXY(16, $tb);
	
	
	
	
	
	
	

	
	
	
	$ny = $pdf->GetY()+4;
	$pdf->SetFont('Arial','B',9);	
	$pdf->SetXY(19, $ny);
	$pdf->MultiCell(80,8,'Realizó mantenimiento (personal designado):',0,'L');		
	
	$sqlp="select * from personal where idpersonal = $idpersonal";
	$operacionp = mysql_query($sqlp);
	$rowp = mysql_fetch_array($operacionp);

		$pdf->SetFont('Arial','B',10);	
	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);	
	$pdf->Image($a, 16 , $ny,188,5);
	$ny = $pdf->GetY()+5;
	$t=$ny;	
			
	//$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);	
//	$pdf->SetXY(20, 167);	
	$pdf->MultiCell(188,5,$rowp["personal"],0,'L',false);
	
	$ny = $pdf->GetY();
	$tb=$ny;
	$pdf->Image($c, 16 , $t,188,$tb-$t);	
	
	//$ny = $pdf->GetY();
	$pdf->SetXY(16, $t-5);	
//	$pdf->SetXY(20, 167);	
	$pdf->MultiCell(188,10,$rowp["personal"],0,'L',false);
	
	$pdf->Image($b, 16 , $tb,188,4);
	$pdf->SetXY(16, $tb+4);
		
	
	

	
	
	$ny = $pdf->GetY()+4;
	$pdf->SetXY(16, $ny);	
	$pdf->Image($a, 16 , $ny,188,5);
	$ny = $pdf->GetY()+5;
	$t=$ny;	
	$pdf->SetXY(16, $ny);	
	$pdf->SetFont('Arial','B',10);

//	$pdf->SetXY(20, 188);
	$pdf->MultiCell(188,5,"Productos utilizados para el mantenimiento:",0,'L',FALSE);
	$pdf->SetFont('Arial','B',8);
	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);
	$pdf->MultiCell(188,5,"Favor de describir los productos utilzados, por ejemplo: jabón en polvo, 1 tapa de desengrasante, medio litro de fabuloso.",0,'L',FALSE);
	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);
	$pdf->SetFont('Arial','B',10);
	
	if(strlen($productosutilizados)>0)
	{
		$pdf->MultiCell(188,5,$productosutilizados ,'BLR','L',false);
	}
	else 
	{
		$pdf->MultiCell(188,18,$productosutilizados ,'BLR','L',false);
	}	
	
	$ny = $pdf->GetY();
	$tb=$ny;
	$pdf->Image($c, 16 , $t,188,$tb-$t);	
	
	$pdf->SetXY(16, $t-5);	
	
	$pdf->SetFont('Arial','B',10);

	//$pdf->SetXY(20, 188);

	$pdf->MultiCell(188,5,"Productos utilizados para el mantenimiento:",0,'L',FALSE);
	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(195,196,168);
	$pdf->MultiCell(188,5,"Favor de describir los productos utilzados, por ejemplo: jabón en polvo, 1 tapa de desengrasante, medio litro de fabuloso.",0,'L',FALSE);
	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',10);
	
	if(strlen($productosutilizados)>0)
	{
		$pdf->MultiCell(188,5,$productosutilizados  ,0,'L',false);
	}
	else 
	{
		$pdf->MultiCell(188,18,$productosutilizados ,0,'L',false);
	}	
	
	$ny = $pdf->GetY();
	$pdf->Image($b, 16 , $tb,188,4);
	//$pdf->Image($b, 16 , $ny,188,4);
	$pdf->SetXY(16, $tb+4);
	
	

	
	
	

	$ny = $pdf->GetY()+4;

	$pdf->SetXY(16, $ny);	
	$t=$ny + 5;	
	altura($t,$pdf);
	$pdf->Image($a, 16 , $t-5,188,5);
	//$ny = $pdf->GetY()+5;

	$pdf->SetXY(16, $t);
	
//	$pdf->SetXY(20, 188);
	$pdf->MultiCell(188,5,"Equipos para realizar el mantenimiento:",0,'L',false);
	$pdf->SetFont('Arial','B',8);
	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);
	$pdf->MultiCell(188,5,"Favor de describir los equipos utilzados, por ejemplo: aspiradora, escoba, cubeta, escalera, etc.",0,'L',false);
	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);	
	$pdf->SetFont('Arial','B',10);
	//$pdf->SetXY(20, 230);	

	if(strlen($equiposutilizados)>0)
	{
		$pdf->MultiCell(188,5,$equiposutilizados,0,'L',false);
	}
	else 
	{	
		$pdf->MultiCell(188,18,$equiposutilizados,0,'L',false);
	}	
	
	$ny = $pdf->GetY();
	$tb=$ny;
	$pdf->Image($c, 16 , $t,188,$tb-$t);	
	
	$pdf->SetXY(16, $t-5);	
	
	$pdf->SetFont('Arial','B',10);

	$pdf->MultiCell(188,5,"Equipos para realizar el mantenimiento:",0,'L',false);
	$pdf->SetFont('Arial','B',8);
	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetTextColor(195,196,168);
	$pdf->MultiCell(188,5,"Favor de describir los equipos utilzados, por ejemplo: aspiradora, escoba, cubeta, escalera, etc.",0,'L',false);
	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);	
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',10);
	//$pdf->SetXY(20, 230);	

	if(strlen($equiposutilizados)>0)
	{
		$pdf->MultiCell(188,5,$equiposutilizados,0,'L',false);
	}
	else 
	{	
		$pdf->MultiCell(188,18,$equiposutilizados,0,'L',false);
	}	


	$ny = $pdf->GetY();
	$pdf->Image($b, 16 , $tb,188,4);
	//$pdf->Image($b, 16 , $ny,188,4);
	$pdf->SetXY(16, $tb+4);

	
	$pdf->SetFont('Arial','B',10);
	
	//$pdf->AddPage();
	//$pdf->Image($reverso, 0 , 0,210,270);
	
	
	//$pdf->SetXY(10, 8);	
	
	
	
	
	
//	$ny = $pdf->GetY()+4;
//	$pdf->SetXY(16, $ny);	
//	$pdf->SetXY(20, 188);

	$ny = $pdf->GetY()+4;
	
	
	$pdf->SetXY(16, $ny);	
	$t=$ny + 5;	
	altura($t,$pdf);
	$pdf->Image($a, 16 , $t-5,188,5);
	//$ny = $pdf->GetY()+5;

	$pdf->SetXY(16, $t);	
	

	$pdf->MultiCell(188,5,"Reporte del personal:",0,'L',false);
	$pdf->SetFont('Arial','B',8);

	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);	
	$pdf->SetFont('Arial','B',10);	
	
	
	if(strlen($reportepersonal)>0)
	{
		$pdf->MultiCell(188,5,$reportepersonal,0,'L',false);
	}
	else 
	{	
		$pdf->MultiCell(188,38,$reportepersonal,0,'L',false);
	}	


	$ny = $pdf->GetY();
	$tb=$ny;
	$pdf->Image($c, 16 , $t,188,$tb-$t);	
	
	$pdf->SetXY(16, $t-5);	
	
	
	$pdf->MultiCell(188,5,"Reporte del personal:",0,'L',false);
	$pdf->SetFont('Arial','B',8);

	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);	
	$pdf->SetFont('Arial','B',10);	
	
	
	if(strlen($reportepersonal)>0)
	{
		$pdf->MultiCell(188,5,$reportepersonal,0,'L',false);
	}
	else 
	{	
		$pdf->MultiCell(188,38,$reportepersonal,0,'L',false);
	}	
	
	
	$ny = $pdf->GetY();
	$pdf->Image($b, 16 , $tb,188,4);
	//$pdf->Image($b, 16 , $ny,188,4);
	$pdf->SetXY(16, $tb+4);
	
	
	
	
		
	
	
	
	
	
	



	$ny = $pdf->GetY()+4;
	
	
	
	$pdf->SetXY(16, $ny);	
//	$t=$ny + 5;	
	$t=$ny +5;
	altura($t,$pdf);	
	
	$pdf->Image($a, 16 , $ny,188,5);
//	$ny = $pdf->GetY()+5;
//	$t=$ny;	
//	altura($t,$pdf);
	$pdf->SetXY(16, $t);
	
//	$ny = $pdf->GetY()+4;
//	$pdf->SetXY(16, $ny);	
//	$pdf->SetXY(20, 188);
	$pdf->MultiCell(188,10,"Evaluación del servicio realizado",0,'L',false);
	$pdf->SetFont('Arial','B',10);	
	$pdf->SetXY(90, $t);
	$pdf->MultiCell(20,10,"Bueno",0,'L');
	$pdf->SetXY(129, $t);
	$pdf->MultiCell(20,10,"Regular",0,'L');
	$pdf->SetXY(170, $t);
	$pdf->MultiCell(20,10,"Malo",0,'L');

	$pdf->SetFont('Arial','B',30);
	
	switch ($evaluacion)
	{
	case 1://bueno
		$pdf->SetXY(81, $t );	
		$pdf->MultiCell(10,10,"X",0,'L');
		break;
	case 2://regular
		$pdf->SetXY(118, $t );	
		$pdf->MultiCell(10,10,"X",0,'L');	
		break;
	case 3://malo
		$pdf->SetXY(158, 70);	
		$pdf->MultiCell(10,10,"X",0,'L');
		break;	

	}		
	$pdf->SetFont('Arial','B',10);	
	

	
	$ny = $pdf->GetY();
	$tb=$ny;
	$pdf->Image($c, 16 , $t,188,$tb-$t);	
	
	$pdf->SetXY(16, $t-5);		


	$pdf->MultiCell(188,10,"Evaluación del servicio realizado",0,'L',false);
	$pdf->SetFont('Arial','B',10);	
	$pdf->SetXY(90, $t);
	$pdf->Image("ok.jpg", 80 , $t,10,10);
	$pdf->MultiCell(20,10,"Bueno",0,'L');
	$pdf->SetXY(129, $t);
	$pdf->Image("m.jpg", 119 , $t,10,10);
	$pdf->MultiCell(20,10,"Regular",0,'L');
	$pdf->SetXY(170, $t);
		$pdf->Image("x.jpg", 160 , $t,10,10);
	$pdf->MultiCell(20,10,"Malo",0,'L');

	$pdf->SetFont('Arial','B',30);
	
	switch ($evaluacion)
	{
	case 1://bueno
		$pdf->SetXY(81, $t );	
		$pdf->MultiCell(10,10,"X",0,'L');
		break;
	case 2://regular
		$pdf->SetXY(118, $t );	
		$pdf->MultiCell(10,10,"X",0,'L');	
		break;
	case 3://malo
		$pdf->SetXY(158, $t);	
		$pdf->MultiCell(10,10,"X",0,'L');
		break;	

	}		
	$pdf->SetFont('Arial','B',10);	
	
	
	$ny = $pdf->GetY();
	$pdf->Image($b, 16 , $tb,188,4);
	//$pdf->Image($b, 16 , $ny,188,4);
	$pdf->SetXY(16, $tb+4);





	
	
	
	
	
	
	
	
	$ny = $pdf->GetY()+4;
	$pdf->SetXY(16, $ny);
	
	
	$t=$ny + 5;	
	altura($t,$pdf);	
		
//	$pdf->SetXY(20, 188);
	$pdf->Image($a, 16 , $ny,188,5);
//	$ny = $pdf->GetY()+5;
//	$t=$ny;
	altura($t,$pdf);
	$pdf->SetXY(16, $t);
	$pdf->MultiCell(188,5,"Comentarios quejas y/o sugerencias del cliente:",0,'L',false);	
	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);	
	$pdf->SetFont('Arial','B',10);	
	
	//$pdf->SetXY(10, 86);	
	if(strlen($comentariocliente)>0)
	{
		$pdf->MultiCell(188,5,$comentariocliente,0,'L',false);	
	}
	else 
	{	
		$pdf->MultiCell(188,25,$comentariocliente,0,'L',false);
	}
	$ny = $pdf->GetY();
	$tb=$ny;
	$pdf->Image($c, 16 , $t,188,$tb-$t);
	
	$ny=$t-5;
	$pdf->SetXY(16, $ny);
	$pdf->MultiCell(188,5,"Comentarios quejas y/o sugerencias del cliente:",0,'L',false);	
	$ny = $pdf->GetY();
	$pdf->SetXY(16, $ny);	
	$pdf->SetFont('Arial','B',10);	
		
	//$pdf->SetXY(10, 86);	
	if(strlen($comentariocliente)>0)
	{
		$pdf->MultiCell(188,5,$comentariocliente,0,'L',false);	
	}
	else 
	{	
		$pdf->MultiCell(188,25,$comentariocliente,0,'L',false);
	}	
	
	
	$pdf->Image($b, 16 , $tb,188,4);
	$pdf->SetXY(16, $tb);	
	

	$ny = $pdf->GetY()+4;
	$pdf->SetXY(16, $ny+2);
	$pdf->SetMargins(16,5,5);
	$pdf->MultiCell(188,4,"Las presentes obras de mantenimiento y/o reparaciones necesarias que se realizan, son efectuadas para la conservación, funcionalidad, higiene y seguridad de la localidad arrendada, otorgándose así y a entera satisfacción del inquilino las condiciones que garantizan el adecuado uso y disfrute del bien inmueble objeto de arrendamiento, cumpliéndose así y de manera cabal las obligaciones de la parte arrendadora, contenidas en las disposiciones civiles y contractuales aplicables al caso.",0,"J");
	//$pdf->write(4,"Las presentes obras de mantenimiento y/o reparaciones necesarias que se realizan, son efectuadas para la conservaciï¿½n, funcionalidad, higiene y seguridad de la localidad arrendada, otorgï¿½ndose asï¿½ y a entera satisfacciï¿½n del inquilino las condiciones que garantizan el adecuado uso y disfrute del bien inmueble objeto de arrendamiento, cumpliï¿½ndose asï¿½ y de manera cabal las obligaciones de la parte arrendadora, contenidas en las disposiciones civiles y contractuales aplicables al caso.");	
	
	
	$ny = $pdf->GetY()+26;
	$pdf->SetDrawColor(177,199,196);
	$pdf->SetLineWidth(.5);	
	
	$pdf->line(19,$ny,100,$ny);
	$pdf->line(115,$ny,190,$ny);
	$pdf->SetXY(35, $ny);
	$pdf->MultiCell(50,5,"Nombre y firma del personal",0,'C');
	$pdf->SetXY(35, $ny+5);
	$pdf->MultiCell(50,5,"de Padilla & Bujalil S.C.",0,'C');
	
	$pdf->SetXY(125, $ny);
	$pdf->MultiCell(60,5,"Nombre y firma de quien recibe",0,'C');	
	
	
	$ny = $pdf->GetY()+26;
	$pdf->SetXY(70, $ny);
	$pdf->MultiCell(80,5,"Nombre y firma del supervisor","T",'C');	
	$pdf->SetXY(70, $ny+5);
	$pdf->MultiCell(80,5,"Padilla & Bujalil S.C.",0,'C');	
	
	
	
	
	
	
	
	
	
	$ny = $pdf->GetY()+4;
	
	$pdf->SetXY(16, $ny);	
	$t=$ny + 5;	
	altura($t,$pdf);	
	
	
	$pdf->Image($a, 16 , $t-5,188,5);
//	$ny = $pdf->GetY()+5;
//	$t=$ny;	
//	altura($t,$pdf);
	$pdf->SetXY(16, $t);	
	
//	$ny = $pdf->GetY()+5;
//	$pdf->SetXY(16, $ny);
//	$pdf->MultiCell(188,56,"",'TBLR','TL',true);	
	$pdf->SetXY(16, $t+1);	
	$pdf->write(4,"Reparación y/o desperfecto causado por el inquilino. Se agregarí el costo del mismo concargo a su estado de cuenta.");
	
	$pdf->SetXY(16, $t+45);
	$pdf->line(19,$t+45,100,$t+45);
	$pdf->line(115,$t+45,190,$t+45);
	$pdf->SetXY(35, $t+45);
	$pdf->MultiCell(50,5,"Nombre y firma del personal",0,'C');
	$pdf->SetXY(35, $t+5+45);
	$pdf->MultiCell(50,5,"de Padilla & Bujalil S.C.",0,'C');
	
	$pdf->SetXY(125, $t+45);
	$pdf->MultiCell(60,5,"Nombre y firma de quien recibe",0,'C');		
	
	$pdf->SetFillColor(255,255,255);	
	$pdf->SetXY(129, $t+11);	
	$pdf->MultiCell(5,5,"",'TBLR','L',true);
	$pdf->SetXY(135, $t+11);	
	$pdf->MultiCell(18,5,"No aplica",0,'L');
	
		
	$pdf->SetXY(56, $t+11);	
	$pdf->MultiCell(5,5,"",'TBLR','L',true);		
	$pdf->SetXY(62, $t+11);	
	$pdf->MultiCell(18,5,"Aplica",0,'L');		
	
	$pdf->SetFont('Arial','B',18);	
	if($evaluacion>0)
	{
		if($responsabilidadinquilino=="")
		{
			$pdf->SetXY(128, $t+9);	
			$pdf->MultiCell(1,10,"X",0,'L');
		}
		else
		{
			$pdf->SetXY(55, $t+9);	
			$pdf->MultiCell(1,10,"X",0,'L');
		}
	}
	
	$ny = $pdf->GetY();
	$tb=$ny + 35;
	$pdf->Image($c, 16 , $t,188,$tb-$t);	
	
	//$pdf->SetXY(16, $t-5);		
	
	$ny=$t;	
	
	$pdf->SetFont('Arial','B',10);	
	//	$pdf->MultiCell(188,56,"",'TBLR','TL',true);	
	$pdf->SetXY(16, $t+1);		
	
	$pdf->write(4,"Reparación y/o desperfecto causado por el inquilino. Se agregará el costo del mismo concargo a su estado de cuenta.");
	$pdf->SetXY(16, $t+45);
	$pdf->line(19,$t+45,100,$t+45);
	$pdf->line(115,$t+45,190,$t+45);
	$pdf->SetXY(35, $t+45);
	$pdf->MultiCell(50,5,"Nombre y firma del personal",0,'C');
	$pdf->SetXY(35, $t+5+45);
	$pdf->MultiCell(50,5,"de Padilla & Bujalil S.C.",0,'C');
	
	$pdf->SetXY(125, $t+45);
	$pdf->MultiCell(60,5,"Nombre y firma de quien recibe",0,'C');		
	
	$pdf->SetFillColor(255,255,255);	
	$pdf->SetXY(129, $t+11);	
	$pdf->MultiCell(5,5,"",'TBLR','L',true);
	$pdf->SetXY(135, $t+11);	
	$pdf->MultiCell(18,5,"No aplica",0,'L');
	
		
	$pdf->SetXY(56, $t+11);	
	$pdf->MultiCell(5,5,"",'TBLR','L',true);		
	$pdf->SetXY(62, $t+11);	
	$pdf->MultiCell(18,5,"Aplica",0,'L');		
	
	$pdf->SetFont('Arial','B',18);	
	if($evaluacion>0)
	{
		if($responsabilidadinquilino=="")
		{
			$pdf->SetXY(128, $t+9);	
			$pdf->MultiCell(1,10,"X",0,'L');
		}
		else
		{
			$pdf->SetXY(55, $t+9);	
			$pdf->MultiCell(1,10,"X",0,'L');
		}
	}


	$ny = $pdf->GetY();
	$pdf->Image($b, 16 , $tb,188,4);
	//$pdf->Image($b, 16 , $ny,188,4);
	//$pdf->SetXY(16, $tb+4);	
	
//	$pdf->Output($direccion_archivo . "/$rfce-$sicofi-$secuenciacbb.pdf","F");

	$pdf->Output();

	
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

function altura(&$t,&$elp)
{
	
	
	if($t>=240)
	{
		$elp->AddPage();
		$t=15;
	}	
	
}


