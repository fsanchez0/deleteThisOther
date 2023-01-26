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
$idcl=@$_GET["idcl"]; 
$rfc=@$_GET["rfc"]; 
$nombre=@$_GET["nombre"];
$calle=@$_GET["calle"];
$noext = @$_GET["noext"];
$noint = @$_GET["noint"];
$col = @$_GET["col"];
$loc = @$_GET["loc"];
$ref = @$_GET["ref"];
$munic = @$_GET["munic"];
$edo = @$_GET["edo"];
$pais = @$_GET["pais"];
$cp = @$_GET["cp"];
$email = @$_GET["email"];
$fecha = @$_GET["fecha"];
$metodopago = @$_GET["metodopago"];
$concepto = @$_GET["concepto"];
$importe = @$_GET["importe"];
$iva = @$_GET["iva"];
$total = @$_GET["total"];

$dirbase="/home/wwwarchivos";
//$dirbase="/Library/WebServer/Documents/bujalil/scripts/cbb";
$direccion_archivo=$dirbase . "/cbbf";

$c1="";
$c2="";
$c3="";

$sicofi="";
$fechacbb="";
$seriecbb="";
$secuenciacbb="";
$foliiofcbb="";
$emisor="";
$rfce="";
$regimen="";
$dom="";
$cbbjpg="";
$logo="";

$domr="";




$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

//Verificación de la fecha para que no se genere un recibo con fecha anterior
	$sql = "select idfacturacbb, fechacbb from facturacbb r, folioscbb f where r.idfoliocbb = f.idfoliocbb and r.idfoliocbb = $id having MAX(fechacbb)>'$fecha' ";
	$operacion = mysql_query($sql);
	if(mysql_num_rows($operacion)>0)
	{
		echo "La fecha introducida es anterior y no puede ser usada, no se ha generado la factura";
		exit();
	}

//validacion para la vigencia
	$sql = "select * from folioscbb  where idfoliocbb=$id and vigenciacbb>='$fecha'";
	$operacion = mysql_query($sql);
	if( mysql_num_rows($operacion) == 0)
	{
		echo "Ya caducaron estos folios, no se ha generado la factura.";
		//exit();
	}	

//validacion para el numero de folios
	$sql = "select * from folioscbb  where idfoliocbb=$id and foliofcbb>( secuenciacbb + 1)";
	$operacion = mysql_query($sql);
	if(mysql_num_rows($operacion)==0)
	{
		echo "Ya se terminaron los folios, no se ha generado la factura.";
		exit();
	}

	

//Verificación del cliente para usar sus datos o para incorporarlo a la base
	$sql ="select * from clientecbb where idclientecbb =$idcl";
	$operacion = mysql_query($sql);
	if(mysql_num_rows($operacion)>0)
	{
		$row =mysql_fetch_array($operacion);
		$rfc=$row["rfccbb"]; 
		$nombre=$row["nombrecbb"];
		$calle=$row["callecbb"];
		$noext = $row["noextcbb"];
		$noint = $row["nointcbb"];
		$col = $row["colcbb"];
		$loc = $row["loccbb"];
		$ref = $row["refcbb"];
		$munic = $row["delmuncbb"];
		$edo =$row["estadocbb"];
		$pais = $row["paiscbb"];
		$cp = $row["cpcbb"];	
		$c1=$row["correo1"];
		$c2=$row["correo2"];
		$c3=$row["correo3"];
		$email = "$c1,$c2,$c3";	
	
	}
	else
	{
		$c0 = split("[,]",$email);
		
		for($i=0;$i<count($c0);++$i)
		{
			switch($i)
			{
				case 0:
					$c1=$c0[0];
					break;
				case 1:
					$c2=$c0[1];
					break;
				case 2:
					$c3=$c0[2];
					break;
			}
		
		}
		
		
		$sql ="insert into clientecbb (rfccbb,nombrecbb,callecbb,noextcbb,nointcbb,colcbb,loccbb,refcbb,delmuncbb,estadocbb,paiscbb,cpcbb,correo1,correo2,correo3) values ";
		$sql .="('$rfc','$nombre','$calle','$noext','$noint','$col','$loc','$ref','$munic','$edo','$pais','$cp','" . $c1. "','" . $c2 . "','" . $c3 . "')";
		$operacion = mysql_query($sql);
		$idcl = mysql_insert_id();
		
	}
	if($calle!="")
	{
		$domr="$calle $noext $noint, Col. $col, Alc./Mun. $munic, $edo, $pais, C.P. $cp";
	}


//obtención del folio y datos del CBB



	$sql="select * from folioscbb where idfoliocbb = $id";
	$operacion = mysql_query($sql);
	$row =mysql_fetch_array($operacion);
	
	$sicofi= $row["nosicofi"];
	$fechacbb= $row["fechaacbb"];
	$seriecbb= $row["seriecbb"];
	$secuenciacbb= $row["secuenciacbb"];
	$emisor= $row["contribuyentecbb"];
	$rfce= $row["rfccbb"];
	$regimen= $row["regimen"];
	$dom= $row["direccioncbb"];
	$cbbjpg= $row["cbbjpg"];
	$logo= $row["logo"];	
	
	$secuenciacbb +=1;
	
	$sql = "update folioscbb set secuenciacbb = $secuenciacbb where idfoliocbb=$id";
	$operacion = mysql_query($sql);

//Generación del recibo

	
	$pdf = new FPDF();
	$pdf->AddPage('P','letter');
	$pdf->SetFont('Arial','B',8);
	//$pdf->SetXY(10, 10);
	$yb=65;
	$alto_base = 45;
	list($ancho, $alto, $tipo, $atributos) = getimagesize("$logo");
	$ancho = ($ancho * $alto_base)/$alto;
	$alto = $alto_base;
	
	$pdf->Image($logo, 10 , 15,$ancho,$alto);
	$pdf->Image($cbbjpg, 140 , 20,27.54,27.54);
	
	//$pdf->Image('/Library/WebServer/Documents/bujalil/scripts/cbb/cbbf/logo.jpg', 80 , 10,27.56,27.56 );
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(5, $yb);
	$pdf->MultiCell(100,5,$emisor,0,'R');
	$yb = $pdf->GetY();	
	//$yb +=5;	
	$pdf->SetXY(5, $yb);
	$pdf->MultiCell(100,5,"$rfce",0,'R');
	$yb = $pdf->GetY();	
	//$yb +=5;	
	$pdf->SetXY(5, $yb);
	$pdf->MultiCell(100,5,utf8_decode("$dom"),0,'R');
	$yb = $pdf->GetY();
	//$yb +=20;	
	$pdf->SetXY(5, $yb);
	$pdf->MultiCell(100,5,utf8_decode("Régimen fiscal: $regimen"),0,'R');
	$yb = $pdf->GetY();
	//$yb +=20;	
	$pdf->SetFont('Arial','B',8);
	$yaux=$yb;
	$pdf->SetXY(5, $yb);
	$pdf->MultiCell(50,5,'Factura No.','LTR');
	$yb = $pdf->GetY();	
	//$yb +=5;
	$pdf->SetXY(5, $yb);
	$pdf->SetFont('Arial','B',14);
	$pdf->MultiCell(50,10,"$seriecbb $secuenciacbb",'LBR','C');
	$pdf->SetFont('Arial','B',8);
	$yb =$yaux;	
	$pdf->SetXY(55, $yb);
	$pdf->MultiCell(50,5,utf8_decode('Expedido en México D.F. A'),'LTR');
	$yb = $pdf->GetY();	
	//$yb +=5;
	$pdf->SetXY(55, $yb);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(50,10,"$fecha",'LBR','C');
	$pdf->SetFont('Arial','B',8);
	$yb = $pdf->GetY();	
	//$yb +=10;
	$pdf->SetXY(5, $yb);
	$pdf->MultiCell(100,5,utf8_decode('Recibí de:'),'LTR');
	$yb = $pdf->GetY();	
	//$yb +=5;
	$pdf->SetXY(5, $yb);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(100,5,utf8_decode("$nombre"),'LR','C');
	$pdf->SetFont('Arial','B',8);
	$yb = calculoy($pdf->GetY(), 15,100,$pdf);
	//$yb = $pdf->GetY();		
	//$yb +=10;
	$pdf->SetXY(5, $yb);
	$pdf->MultiCell(100,5,'RFC','LTR');
	$yb = $pdf->GetY();	
	//$yb +=5;
	$pdf->SetXY(5, $yb);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(100,5,"$rfc",'LR','C');
	$pdf->SetFont('Arial','B',8);
	$yb = calculoy($pdf->GetY(), 0,100,$pdf);
	$ysello = $yb;
	//$yb = $pdf->GetY();	
	//$yb +=10;	
	$pdf->SetXY(5, $yb);
	$pdf->MultiCell(200,5,'Domicilio Fiscal','LTR');
	$yb = $pdf->GetY();	
	//$yb +=5;
	$pdf->SetXY(5, $yb);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(200,5,utf8_decode("$domr"),'LR','C');
	$pdf->SetFont('Arial','B',8);
	$yb = calculoy($pdf->GetY(), 20,200,$pdf);
	//$yb = $pdf->GetY();	
	//$yb +=10;			
	$pdf->SetXY(5, $yb);
	$pdf->MultiCell(200,5,'Concepto','LTR');
	$yb = $pdf->GetY();	
	//$yb +=5;
	$pdf->SetXY(5, $yb);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(200,5,utf8_decode("$concepto"),'LR','C');
	$pdf->SetFont('Arial','B',8);
	$yb = calculoy($pdf->GetY(), 10,200,$pdf);
	//$yb = $pdf->GetY();	
	//$yb +=10;
	
	$yaux = $yb;
	$pdf->SetXY(5, $yb);
	$pdf->MultiCell(150,5,utf8_decode('Método de Pago'),'LTR');
	$yb = $pdf->GetY();	
	//$yb +=5;
	$pdf->SetXY(5, $yb);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(150,5,utf8_decode("$metodopago"),'LR','C');
	$pdf->SetFont('Arial','B',8);
	$yb = calculoy($pdf->GetY(), 10,150,$pdf);
	//$yb = $pdf->GetY();	
	//$yb +=10;		
	$pdf->SetXY(5, $yb);
	$pdf->MultiCell(150,5,'Importe con letra','LTR');
	$yb = $pdf->GetY();	
	//$yb +=5;
	
	
    $letras=utf8_decode(num2letras($total,0,0)." pesos  ");

	$total = number_format($total, 2, '.', ',');

	$ultimo = substr (strrchr ($total, "."), 1 ); //recupero lo que este despues del decimal

	$letras = $letras." ".$ultimo."/100 M. N.";	
	
	$pdf->SetXY(5, $yb);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(150,5,"($letras)",'LBR','C');
	$pdf->SetFont('Arial','B',8);
	$yb = $pdf->GetY();	
	//$yb +=10;	
	$yb=$yaux;
	$pdf->SetXY(155, $yb);
	$pdf->MultiCell(50,5,'Importe','LTR');
	$yb = $pdf->GetY();	
	//$yb +=5;
	$pdf->SetXY(155, $yb);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(50,20,number_format($importe, 2, '.', ','),'LBR','R');
	$pdf->SetFont('Arial','B',8);
	$yb = $pdf->GetY();	
	//$yb +=10;		
	$pdf->SetXY(155, $yb);
	$pdf->MultiCell(50,5,'IVA (16%)','LTR');
	$yb = $pdf->GetY();	
	//$yb +=5;
	$pdf->SetXY(155, $yb);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(50,20,number_format($iva, 2, '.', ','),'LBR','R');
	$pdf->SetFont('Arial','B',8);
	$yb = $pdf->GetY();	
	//$yb +=10;
	$pdf->SetXY(155, $yb);
	$pdf->MultiCell(50,5,'Total','LTR');
	$yb = $pdf->GetY();	
	//$yb +=5;
	$pdf->SetXY(155, $yb);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell(50,20,$total,'LBR','R');
	$pdf->SetFont('Arial','B',8);
	$yb = $pdf->GetY();	
	//$yb +=10;	
	$yb=55;										
	$pdf->SetXY(110, $yb);			
	$pdf->MultiCell(90,5,utf8_decode("La reproducción apocrifa de este comprobante constituye un delito en los terminos de las disposiciones fiscales. Este comporbante tengrá uan visgencia de dos años contados a partir de la fecha de apboración de la asignación de folios la cual es: " .  substr($fechacbb,8,2) . "/" . substr($fechacbb,5,2) . "/" . substr($fechacbb,0,4) .  ". Pago en una sola exhibición. No. de apbaricón SICOFI $sicofi"),0,"C");
	$yb = $pdf->GetY();
	$altosello = $ysello-$yb-5;
	$pdf->SetXY(105, $yb+5);
	$pdf->SetFont('Arial','B',16);
	$pdf->SetTextColor(200);
	$pdf->MultiCell(100,$altosello,'SELLO','LBTR','C');	

	$pdf->Output($direccion_archivo . "/$rfce-$sicofi-$secuenciacbb.pdf","F");
	$pdf->Output();

	$sql = "insert into facturacbb (idfoliocbb,idclientecbb,fechacbb,seriecbb,foliocbb,metodopagocbb,conceptocbb,importecbb,ivacbb,totalcbb,cancelada,facturacbb) values ";
	//$sql .= "($id,$idcl,'$fecha','$seriecbb','$secuenciacbb','$metodopago','$concepto','$importe','$iva','$total',0,'" . $direccion_archivo . "/$rfce-$sicofi-$secuenciacbb.pdf" . "')";
	$sql .= "($id,$idcl,'$fecha','$seriecbb','$secuenciacbb','$metodopago','$concepto','$importe','$iva','$total',0,'" . "$rfce-$sicofi-$secuenciacbb.pdf" . "')";
	$operacion = mysql_query($sql);
	
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

function calculoy($yc, $mas,$a,$p)
{
	$ys=$yc+$mas;
	if($yc>=$ys)
	{
		
		$ys=$yc;
	
	}
	else
	{
		$h=$ys-$yc;
		$p->SetX(5);
		$p->MultiCell($a,$h,'','LR','C');
		
	}
	return $ys;	
}
