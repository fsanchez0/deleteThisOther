<?php
//ob_end_clean();
//include "phpqrcode-master/qrlib.php";   
require('../fpdf.php'); //para crear el pdf

//require('numero_a_letra.php'); // funcion para convertir el total a letra



class PDF_Rotate extends FPDF
{
var $angle=0;

function Rotate($angle,$x=-1,$y=-1)
{
    if($x==-1)
        $x=$this->x;
    if($y==-1)
        $y=$this->y;
    if($this->angle!=0)
        $this->_out('Q');
    $this->angle=$angle;
    if($angle!=0)
    {
        $angle*=M_PI/180;
        $c=cos($angle);
        $s=sin($angle);
        $cx=$x*$this->k;
        $cy=($this->h-$y)*$this->k;
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    }
}

function _endpage()
{
    if($this->angle!=0)
    {
        $this->angle=0;
        $this->_out('Q');
    }
    parent::_endpage();
}
}



//class PDF extends FPDF
class PDF extends PDF_Rotate
{

    //Encabezado de página
   

    function Header()

    {   

			$this->SetFillColor(255,255,255);
    		$this->Image('../../imagenes/logo-rentas.jpg',10,5,65);

			//$this->Image('imgs/cedula.jpg',10,192,39);

			$this->SetFont('Arial','B',12);
			$this->Cell(100,4,"",0,0,'C');
			$this->Cell(95,4,utf8_decode("PADILLA & BUJALIL S. C."),0,1,'C');
			$this->SetFont('Arial','B',8);
			//$this->setTextColor(255,255,255);
			//$this->Cell(50,4,"FACTURA",1,1,'C',true);
			//$this->setTextColor(0,0,0);
			$this->SetFont('Arial','B',9);
			$this->Cell(100,4,"",0,0,'C');
			$this->Cell(95,4,utf8_decode(""),0,1,'C');
			$this->SetFont('Arial','',8);

			//$this->Cell(25,4,"Serie: a ",1,0,'C');
			//$this->Cell(25,4,"Folio:1 ",1,1,'C');

			$this->SetFont('Arial','B',8);
			$this->Cell(100,4,"",0,0,'C');
			$this->Cell(95,4,utf8_decode("Av. Insurgentes Centro 23 Despacho 102"),0,1,'C');
			$this->Cell(100,4,"",0,0,'C');
			$this->Cell(95,4,utf8_decode('Col. San Rafael Deleg. Cuauhtémoc C.P. 06470 México D.F.'),0,1,'C');
			$this->Cell(100,4,"",0,0,'R');
			$this->Cell(95,4,utf8_decode('Hoja ' . $this->PageNo() . ' de {nb}'),0,1,'C');				
			$this->Ln(6);
			
			$this->SetFont('Arial','B',50);
    		$this->SetTextColor(255,192,203);
    		$this->RotatedText(35,190,'P r o v i s i o n a l',45);

    } 
    



function RotatedText($x, $y, $txt, $angle)
{
    //Text rotated around its origin
    $this->Rotate($angle,$x,$y);
    $this->Text($x,$y,$txt);
    $this->Rotate(0);
}
 
    

}



//Clase de xml para cdf 

class duenioreporte

{

	var $pdf;
	var $idduenio;
	var $fechamenos;

	//***************************************
	//Constructor de objeto
	//***************************************

	function duenioreporte ()

	{

		$this->fechamenos = @date('Y-m-') . "01";
		//$pdfd=new PDF('P','mm','Letter');
		

	}
	
	
	function generaPDF()	
	{
	
   		$pdf=new PDF('P','mm','Letter');
   		$pdf->AliasNbPages();
   		$pdf->SetTopMargin(5);
   	   	
   		$pdf->AddPage();    
   		$pdf->Ln(1);
		$posy1=$pdf->GetY();   	
		
		$reporte ="";
		//modificar para que sea de la fecha seleccionada para un mes atras
		//por ahora esta la fecha del primero del mes en cuestion para todo lo anterior
		$fechamenos = @date('Y-m-') . "01";
		//$fechamenos = "2014-09-01";
		//$fechamenos = $this->fechamenos;
		//info del dueño
		$periodo = "";
		//Calculo del periodo para colocarlo en  el titulo
		//echo substr($fechamenos,5,2);
		switch (substr($fechamenos,5,2))
		{
			case 1:
				$anio = (int)substr($fechamenos,0,4);
				
				$periodo = "DICIEMBRE " . ($anio - 1) . " - ENERO $anio";
				break;
			case 2:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "ENERO - FEBRERO $anio";			
				break;
			case 3:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "FEBRERO - MARZO $anio";				
				break;
			case 4:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "MARZO - ABRIL $anio";				
				break;	
			case 5:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "ABRIL - MAYO $anio";				
				break;
			case 6:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "MAYO - JUNIO $anio";				
				break;
			case 7:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "JUNIO - JULIO $anio";				
				break;
			case 8:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "JULIO - AGOSTO $anio";				
				break;
			case 9:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "AGOSTO - SEPTIEMBRE $anio";				
				break;
			case 10:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "SEPTIEMBRE - OCTUBRE $anio";				
				break;
			case 11:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "OCTUBRE - NOVIEMBRE $anio";				
				break;
			case 12:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "NOVIEMBRE - DICIEMBRE $anio";				
				break;	
		}
		

		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(240,4,utf8_decode("Periodo $periodo."),0,1,'C');
		$pdf->Ln(4);

		$sql = "select * from duenio where idduenio = " . $this->idduenio;
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
		

		
   	
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(110,4,"CLIENTE",1,0,'C',false);
		$pdf->setTextColor(0,0,0);
		$pdf->Ln(4);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(110,4,utf8_decode($row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"]),"LR",0,'L');	
   		$pdf->Ln(4);
   		$pdf->SetFont('Arial','',6);
   		$pdf->Cell(110,4,"RFC: " . utf8_decode($row["rfcd"]) ,"LR",0,'L');
   		$pdf->Ln(4);   	
		$pdf->Cell(110,4,utf8_decode("Calle " . $row["called"] . " No." . $row["noexteriord"] . " " . $row["nointeriord"] . " " ),"LR",0,'L');
   		$pdf->Ln(4);
		$pdf->Cell(110,4,utf8_decode("Col." . $row["coloniad"] . ", " . $row["delmund"] . " " . $row["estadod"] . " C.P. " . $row["cpd"] ),"LBR",0,'L');
   		$pdf->Ln(10);
   		$pdf->SetFont('Arial','B',8);
		$fant="";	
	
//$pdf->AddPage('P');

    	$pdf->SetFont('Arial','B',8);
    	$pdf->Cell(200,5,utf8_decode('A reportar'),1,0,'C',false);
    	$pdf->Ln(5);
    	$pdf->Cell(150,5,utf8_decode('DESCRIPCIÓN'),1,0,'C',false);
    	

    	$pdf->Cell(50,5,'IMPORTE',1,1,'C',false);
    	$pdf->SetFont('Arial','B',8);

		
		
//consulta para marcados y generados de esta ocación
		//$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true and reportado = 1  and d.idduenio=" . $this->idduenio . " ";
		$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe>0  and isnull(fechagen)=true  and fechaedo<'$fechamenos'  and reportado = 1 and e.idtipocobro>0  and d.idduenio=" . $this->idduenio . " and traspaso = 0 ";
		$sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
		$operacionc = mysql_query($sqlc);
		
		
		
		$np=0;
		$renp=0;
		$hf=1;
		$pdf->SetFont('Arial','',7);
		$dif_y=3;
		$idcontrato =0;
		while($rowc = mysql_fetch_array($operacionc))	
		{
		
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idcontrato"] != $idcontrato)
			{
				
				$idcontrato = $rowc["idcontrato"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode($inmueble ),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}


		
			//$np +=(double)$c[0];
			$np +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			

			
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion

    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(1);

		}


		$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe>0  and isnull(fechagen)=true    and reportado = 1 and e.idtipocobro=0  and d.idduenio=" . $this->idduenio . " and traspaso = 0 ";
		$sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
		$operacionc = mysql_query($sqlc);
		
		while($rowc = mysql_fetch_array($operacionc))	
		{
		
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idcontrato"] != $idcontrato)
			{
			
				
				$idcontrato = $rowc["idcontrato"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode($inmueble ),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}


		
			//$np +=(double)$c[0];
			$np +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			$posy1=$pdf->GetY();//posición antes de escribir concepto
						
			
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion


    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(1);

		}
		



		
		
	$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, i.idinmueble as idi from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble  and importe>0  and isnull(fechagen)=true  and reportado = 1 and idcontrato =0 and d.idduenio=" . $this->idduenio . " and traspaso = 0 ";
	//$sqlc .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
	$sqlc .=" order by e.idduenio, e.idcontrato,idedoduenio";	

	$operacionc = mysql_query($sqlc);	
	
		$idi = 0;
		while($rowc = mysql_fetch_array($operacionc))	
		{
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idi"] != $idi)
			{
								
				$idi = $rowc["idi"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				//$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode("Cargos directos" . $inmueble),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}

		
			//$np +=(double)$c[0];
			$np +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			$posy1=$pdf->GetY();//posición antes de escribir concepto
					
			
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion

			

    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(4);

		}		
		
	

		
		
		
		//notas de credito
		$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e where d.idduenio = e.idduenio and importe<>0  and isnull(fechagen)=true  and reportado = 1 and d.idduenio=" . $this->idduenio . " and notacredito = true ";
		$sqlc .= " order by e.idduenio, e.idcontrato,idedoduenio";		
		
		//$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true and reportado = 1  and d.idduenio=" . $this->idduenio . " ";
		//$sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
		$operacionc = mysql_query($sqlc);
		

		$idcontrato =0;
		while($rowc = mysql_fetch_array($operacionc))	
		{

			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idcontrato"] != $idcontrato)
			{
					
				
				$idcontrato = 1;
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode("Notas de crédito"),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}

		
			//$np +=(double)$c[0];
			$np +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ) ,0,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ),1 ,1,'L');//descripcion


    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),0,1,'R');//precio
    		//$pdf->Ln(4);

		}		
		
//---------------------------------------		
// Aqui verifico si hay algun traspaso para este dueño por aplicar y realizo la consulta de todos los
// reportados hasta el momento y lo agrego al total de los abonos		
		
		//Verifico si ya tengo un traspaso mayor que cero
		$sqltrs = "select * from edoduenio where idduenio = " . $this->idduenio . " and isnull(fechagen)=true  and reportado = 1 and traspaso =1" ;
		$operaciontrs= mysql_query($sqltrs);
		$rowtrs = mysql_fetch_array($operaciontrs);
		if(mysql_num_rows($operaciontrs)>0)
		{//Ya existe traspaso y agrego los datos del traspaso

			if($rowtrs["importe"]>0)
			{
				if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
				$dif_y=3;
								
				
				$idcontrato = 1;
		
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode("Traspaso"),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	

				//$np +=(double)$c[0];
				$np +=$rowtrs["importe"] + $rowtrs["iva"];
				$sub = $rowtrs["importe"] + $rowtrs["iva"];
			
				//$np +=$rowc["importe"];// + $rowc["iva"];
				//$sub = $rowc["importe"];// + $rowc["iva"];			
				$renp +=1;
			 	
			
				$posy1=$pdf->GetY();//posición antes de escribir concepto
				$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
				//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
				$pdf->MultiCell(150,$dif_y, utf8_decode($rowtrs["notaedo"] ) ,0,'L');//descripcion
				//$pdf->Cell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ),1 ,1,'L');//descripcion


   		 		$posy2=$pdf->GetY();
   		 		$posX2=$pdf->GetX();//posicion despues de escribir concepto

	    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas			

    			$pdf->SetY($posy1);    		
    			$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    			$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),0,1,'R');//precio
	    		//$pdf->Ln(4);
			}

		}		
		else //si no hay traspaso verifico si tiene algun traspaso pendiente por ser aplicado
		{
			
			$sqltrs = "select * from traspasodepara where para = " . $this->idduenio;
			$operaciontrs= mysql_query($sqltrs);
			$rowtrs = mysql_fetch_array($operaciontrs);
			if(mysql_num_rows($operaciontrs)>0)
			{//este propietario recibirá un traspaso
				
				$de = $rowtrs["de"];
				$justificacion = $rowtrs["justificacion"];
				
				
				
				$mes = date("Y-m") . "-01";
				$sqlde="select concat(nombre,' ', nombre2, ' ', apaterno,' ', amaterno) as nombre, sum(importe + iva) as importe from edoduenio ed, duenio d where ed.idduenio = d.idduenio and d.idduenio =$de and reportado = true and isnull(fechagen)=true  group by nombre, nombre2, apaterno, amaterno";
				$operacionde= mysql_query($sqlde);
				$rowde = mysql_fetch_array($operacionde);
				
				$descripcion="Traspaso de " . $rowde["nombre"] . " $justificacion $periodo";
				
				if(mysql_num_rows($operacionde)>0)
				{//existen marcados y colocar el nombre del propietario y el importe
					
					
					
					
					if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
					$dif_y=3;
								
				
					$idcontrato = 1;
		
					$pdf->SetFont('Arial','B',9);
					$pdf->Ln(3);
					$pdf->MultiCell(240,$dif_y, utf8_decode("Traspaso"),0 ,1,'L');//descripcion
					$pdf->SetFont('Arial','',7);
					$pdf->Ln(1);
	
				
					if($rowde["importe"]>0)
					{
					
						$np +=$rowde["importe"]; //+ $rowtrs["iva"];
						$sub = $rowde["importe"]; //+ $rowtrs["iva"];
					}
					else
					{
						$np +=0; //+ $rowtrs["iva"];
						$sub = 0; //+ $rowtrs["iva"];					
					}
			
		
					$renp +=1;
			 	
			
					$posy1=$pdf->GetY();//posición antes de escribir concepto
					$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
				//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
					
					if($sub ==0)
					{
						$pdf->MultiCell(150,$dif_y, utf8_decode("Importe insuficiente para traspaso") ,1,'L');//descripcion
					}
					else
					{
						$pdf->MultiCell(150,$dif_y, utf8_decode($descripcion ) ,1,'L');//descripcion
					}
				//$pdf->Cell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ),1 ,1,'L');//descripcion


   			 		$posy2=$pdf->GetY();
   			 		$posX2=$pdf->GetX();//posicion despues de escribir concepto

	   		 		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas			

   		 			$pdf->SetY($posy1);    		
   		 			$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
   		 			$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
	    		//$pdf->Ln(4);					
					
				}
				else
				{//Aun no marca nada del estado de cuenta, colocar el texto de no hay elementos seleccionados a reportar
					
					if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
					$dif_y=3;
								
				
					$idcontrato = 1;
		
					$pdf->SetFont('Arial','B',9);
					$pdf->Ln(3);
					$pdf->MultiCell(240,$dif_y, utf8_decode("Traspaso"),0 ,1,'L');//descripcion
					$pdf->SetFont('Arial','',7);
					$pdf->Ln(1);
	


					$np +=0; //+ $rowtrs["iva"];
					$sub = 0; //+ $rowtrs["iva"];
			
		
					$renp +=1;
			 	
			
					$posy1=$pdf->GetY();//posición antes de escribir concepto
					$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
				//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
					$pdf->MultiCell(150,$dif_y, utf8_decode("No existen elementos reportados a ser transferidos" ) ,1,'L');//descripcion
				//$pdf->Cell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ),1 ,1,'L');//descripcion


   			 		$posy2=$pdf->GetY();
   			 		$posX2=$pdf->GetX();//posicion despues de escribir concepto

	   		 		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas			

   		 			$pdf->SetY($posy1);    		
   		 			$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
   		 			$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
	    		//$pdf->Ln(4);						
					
					
					
					
					
				}
			
				
		
			}			
			
			
		}
				

//------------------------------------------------------		
		$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
		$pdf->MultiCell(150,$dif_y, utf8_decode('Total de abonos:') ,0,'R');//descripcion

    	$posy2=$pdf->GetY();
    	$posX2=$pdf->GetX();//posicion despues de escribir concepto

    	$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    	$pdf->SetY($posy1);    		
    	$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    	$pdf->Cell(50,$dif_y,"$ ".number_format($np, 2, '.', ','),0,1,'R');//precio	
//*********************************************************************************************
		$pdf->Ln(5);

//consulta para marcados y generados de esta ocación
		//$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true and reportado = 1  and d.idduenio=" . $this->idduenio . " ";
		$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<0  and isnull(fechagen)=true  and fechaedo<'$fechamenos' and  reportado = 1 and e.idtipocobro>0  and d.idduenio=" . $this->idduenio . " and traspaso = 0 ";
		$sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
		$operacionc = mysql_query($sqlc);
		
		
		
		$npm=0;
		$renp=0;
		$hf=1;
		$pdf->SetFont('Arial','',7);
		$dif_y=3;
		while($rowc = mysql_fetch_array($operacionc))	
		{
		
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idcontrato"] != $idcontrato)
			{
				
				
				$idcontrato = $rowc["idcontrato"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode($inmueble ),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}

		
			//$np +=(double)$c[0];
			$npm +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode( $rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion


    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(4);

		}


		$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<0  and isnull(fechagen)=true  and  reportado = 1 and e.idtipocobro=0  and d.idduenio=" . $this->idduenio . " and traspaso = 0 ";
		$sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
		$operacionc = mysql_query($sqlc);
		
		
		while($rowc = mysql_fetch_array($operacionc))	
		{
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idcontrato"] != $idcontrato)
			{

				$idcontrato = $rowc["idcontrato"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode($inmueble ),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}

		
			//$np +=(double)$c[0];
			$npm +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode( $rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion


    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(4);

		}


		
	$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, i.idinmueble as idi from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble  and importe<0  and isnull(fechagen)=true  and reportado = 1 and idcontrato =0 and d.idduenio=" . $this->idduenio . " and traspaso = 0 ";
	//$sqlc .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";
	$sqlc .=" order by e.idduenio, e.idcontrato,idedoduenio";	

	$operacionc = mysql_query($sqlc);	
	
		$idi = 0;
		while($rowc = mysql_fetch_array($operacionc))	
		{
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idi"] != $idi)
			{
	
				
				$idi = $rowc["idi"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				//$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br><table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode("Cargos directos" . $inmueble),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}

		
			//$np +=(double)$c[0];
			$npm +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion


    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(4);

		}	

	$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, e.idinmueble as idi from duenio d, edoduenio e where d.idduenio = e.idduenio and e.idinmueble = 0  and importe<0  and isnull(fechagen)=true  and reportado = 1 and idcontrato =0 and d.idduenio=" . $this->idduenio . " and traspaso = 0	 ";
	//$sqlc .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
	$sqlc .=  " order by e.idduenio, e.idcontrato,idedoduenio";	

	$operacionc = mysql_query($sqlc);	
	
		$idi = 0;
		while($rowc = mysql_fetch_array($operacionc))	
		{
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idi"] != $idi)
			{

				
				$idi = $rowc["idi"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				//$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode("Cargos directos" . $inmueble),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}

		
			//$np +=(double)$c[0];
			$npm +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
			
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion


    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(4);

		}				
		
		
//-------------------------------------
//para mostrar lo que se transferirá
		

			$sqltrs = "select * from traspasodepara where de = " . $this->idduenio;
			$operaciontrs= mysql_query($sqltrs);
			$rowtrs = mysql_fetch_array($operaciontrs);
			if(mysql_num_rows($operaciontrs)>0)
			{//este propietario recibirá un traspaso
				
				$para= $rowtrs["para"];
				$justificacion = $rowtrs["justificacion"];
				
				
				
				$mes = date("Y-m") . "-01";
				$sqlde="select concat(nombre,' ', nombre2, ' ', apaterno,' ', amaterno) as nombre from  duenio d where  d.idduenio =$para";
				$operacionde= mysql_query($sqlde);
				$rowde = mysql_fetch_array($operacionde);
				
				$descripcion="Traspaso para " . $rowde["nombre"] . " $justificacion $periodo";
				
				if(($np + $npm)>0)
				{//Hay que transferir así que el importe podria ser este
					
					
					if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
					$dif_y=3;
								
				
					//$idcontrato = 1;
		
					$pdf->SetFont('Arial','B',9);
					$pdf->Ln(3);
					$pdf->MultiCell(240,$dif_y, utf8_decode("Traspaso"),0 ,1,'L');//descripcion
					$pdf->SetFont('Arial','',7);
					$pdf->Ln(1);
	

					$sub = ($np + $npm)*(-1);
					$npm +=($np + $npm)*(-1);
					//$sub = ($np + $npm)*(-1);
			
		
					$renp +=1;
			 	
			
					$posy1=$pdf->GetY();//posición antes de escribir concepto
					$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
				//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
					$pdf->MultiCell(150,$dif_y, utf8_decode($descripcion ) ,1,'L');//descripcion
				//$pdf->Cell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ),1 ,1,'L');//descripcion


   			 		$posy2=$pdf->GetY();
   			 		$posX2=$pdf->GetX();//posicion despues de escribir concepto

	   		 		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas			

   		 			$pdf->SetY($posy1);    		
   		 			$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
   		 			$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
	    		//$pdf->Ln(4);					
					
				}
				else
				{//Aun no marca nada del estado de cuenta, colocar el texto de no hay elementos seleccionados a reportar
					
					if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
					$dif_y=3;
								
				
					//$idcontrato = 1;
		
					$pdf->SetFont('Arial','B',9);
					$pdf->Ln(3);
					$pdf->MultiCell(240,$dif_y, utf8_decode("Traspaso"),0 ,1,'L');//descripcion
					$pdf->SetFont('Arial','',7);
					$pdf->Ln(1);
	


					$np +=0 ;//+ $rowtrs["iva"];
					$sub = 0 ;//+ $rowtrs["iva"];
			
		
					$renp +=1;
			 	
			
					$posy1=$pdf->GetY();//posición antes de escribir concepto
					$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
				//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
					$pdf->MultiCell(150,$dif_y, utf8_decode("Importe insuficiente para traspaso" ) ,1,'L');//descripcion
				//$pdf->Cell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ),1 ,1,'L');//descripcion


   			 		$posy2=$pdf->GetY();
   			 		$posX2=$pdf->GetX();//posicion despues de escribir concepto

	   		 		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas			

   		 			$pdf->SetY($posy1);    		
   		 			$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
   		 			$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
	    		//$pdf->Ln(4);						
					
					
					
					
					
				}
			
				
		
			}			
	

//-------------------------------------		
		
		
		
		
		$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
		$pdf->MultiCell(150,$dif_y, utf8_decode('Total a cobrar:'),0,1,'R');//descripcion

    	$posy2=$pdf->GetY();
    	$posX2=$pdf->GetX();//posicion despues de escribir concepto

    	$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    	$pdf->SetY($posy1);    		
    	$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    	$pdf->Cell(50,$dif_y,"$ ".number_format($npm, 2, '.', ','),0,1,'R');//precio	
		
		//$pdf->Ln(1);
		$pdf->Ln(3);


//para traspaso

	//traspasos
	//traspasos en cero
	$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, e.idinmueble as idi from duenio d, edoduenio e where d.idduenio = e.idduenio and e.idinmueble = 0  and  isnull(fechagen)=true  and reportado = 1 and idcontrato =0 and d.idduenio=" . $this->idduenio . " and traspaso = 1 and importe=0";
	$sqlc .=  " order by e.idduenio, e.idcontrato,idedoduenio";	

	$operacionc = mysql_query($sqlc);	
	$trs=0;
	if(mysql_num_rows($operacionc)>0)
	{
		$idi = 0;
		while($rowc = mysql_fetch_array($operacionc))	
		{
		
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
								
				
			//$idcontrato = 1;
		
			$pdf->SetFont('Arial','B',9);
			$pdf->Ln(3);
			$pdf->MultiCell(240,$dif_y, utf8_decode("Traspaso"),0 ,1,'L');//descripcion
			$pdf->SetFont('Arial','',7);
			$pdf->Ln(1);
		
			//$np +=(double)$c[0];
			//$trs +=$rowc["importe"] + $rowc["iva"];
			$sub = 0;
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			
			
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,0,'L');//descripcion

    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		$dif_y=3;
    		$pdf->Ln(4);

		}					
		
			
	}

//****







		$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
		$pdf->SetFont('Arial','',9);
		$pdf->MultiCell(150,$dif_y, utf8_decode('Total a pagar:') ,0,'R');//descripcion

    	$posy2=$pdf->GetY();
    	$posX2=$pdf->GetX();//posicion despues de escribir concepto

    	$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    	$pdf->SetY($posy1);    		
    	$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    	$pdf->Cell(50,$dif_y,"$ ".number_format(($np+$npm), 2, '.', ','),0,1,'R');//precio	
    	$pdf->SetFont('Arial','',7);
	$totalPagar=$np+$npm;

//**************************************************************************************
		$pdf->Ln(10);

    	$pdf->SetFont('Arial','B',8);
    	$pdf->Cell(200,5,utf8_decode('No se reportan'),1,0,'C',false);
    	$pdf->Ln(5);
    	$pdf->Cell(150,5,utf8_decode('DESCRIPCIÓN'),1,0,'C',false);
    	
    	//$pdf->Cell(15,5,"UNIDAD",1,0,'C',false);
    	//$pdf->SetFont('Arial','B',7);    	
    	//$pdf->Cell(10,5,"UNIDAD",1,0,'C',false);
    	
    	$pdf->Cell(50,5,'IMPORTE',1,1,'C',false);
    	$pdf->SetFont('Arial','B',8);

		$pdf->Ln(4);




//consulta para marcados y generados de esta ocación

		
		$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true  and fechaedo<'$fechamenos'  and   reportado = 0  and d.idduenio=" . $this->idduenio . " ";
		$sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";		
		
		$operacionc = mysql_query($sqlc);
		
		
		
		$np=0;
		$renp=0;
		$hf=1;
		$pdf->SetFont('Arial','',7);
		
		while($rowc = mysql_fetch_array($operacionc))	
		{
		
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}		
			$dif_y=3;
		
				//$np +=(double)$c[0];
			$np +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
						
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			//$pdf->MultiCell(100,$dif_y, utf8_decode($posy1 .  $rowc["notaedo"] ) ,0,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			

    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas


    		
    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(4);
		}

		$posy1=$pdf->GetY();//posición antes de escribir concepto
		
			
		//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
		$pdf->MultiCell(150,$dif_y, utf8_decode('Total no reportado:') ,0,'R');//descripcion

    	$posy2=$pdf->GetY();
    	$posX2=$pdf->GetX();//posicion despues de escribir concepto

    	$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    	$pdf->SetY($posy1);    		
    	$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    	$pdf->Cell(50,$dif_y,"$ ".number_format($np, 2, '.', ','),0,1,'R');//precio	


  //PARA LA INSTRUCCION DE LA DISPERSION DE LOS PAGOS
		//$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true and reportado = 1  and d.idduenio=" . $this->idduenio . " ";
		$sqlc = "select * from dueniodistribucion where idduenio=" . $this->idduenio . " ";
		$operacionc = mysql_query($sqlc); 		
   		
   		//verificar si se integra o no para no mostrar el recuadro si no hay dispersion
   		if(mysql_num_rows($operacionc)>0)
   		{
   		
   		$pdf->AddPage('P');
   		$pdf->SetFont('Arial','B',8);
    	$pdf->Cell(200,5,utf8_decode('INSTRUCCIÓN DE PAGO'),1,1,'C',false);
    	//$pdf->Ln(5);
    	$pdf->Cell(150,5,utf8_decode('DESCRIPCIÓN'),1,0,'C',false);
    	
    	
    	$pdf->Cell(50,5,'IMPORTE',1,1,'C',false);
    	$pdf->SetFont('Arial','B',8);

				
		$np=0;
		$renp=0;
		$hf=1;
		$pdf->SetFont('Arial','',9);
		$dif_y=4;
		$idcontrato =0;
		while($rowc = mysql_fetch_array($operacionc))	
		{
		
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			
			$descripcion = $rowc["nombre"]  . " RFC: " .  $rowc["rfc"] . " Banco: " .  $rowc["banco"]  . " Cuenta: " .  $rowc["cuenta"]  . " Clabe: " .  $rowc["clabe"]  . " IDBanco: " .  $rowc["idbanco"];
			$sub = $totalPagar * ($rowc["porcentaje"]/100);
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
			$pdf->MultiCell(150,$dif_y, utf8_decode($descripcion) ,1,'L');//descripcion
			
			

    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		$dif_y=4;
    		//$pdf->Ln(1);

		
		}
		
		}


	
////  Ultima hoja.. la hoja nueva con coopropiedades		

	 $sql = "select idinmueble, count(idduenio) from duenioinmueble where idinmueble in (select idinmueble from edoduenio where idduenio =" . $this->idduenio . " group by idinmueble) having count(idduenio)>1 ";	
	$operacion = mysql_query($sql);

	$sqldif = "select idduenio, count(idinmueble) from duenioinmueble where idinmueble in (select idinmueble from edoduenio where idduenio =" . $this->idduenio . " group by idinmueble) group by idduenio";
	$operaciondif = mysql_query($sqldif);

	if(mysql_num_rows($operacion)>=1 && mysql_num_rows($operaciondif)>1)
	{
	
	$pdf->AddPage('P');
	
	//cabecera
	$posy1=$pdf->GetY();   	
		
	$reporte ="";
	

	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(240,4,utf8_decode("Periodo $periodo."),0,1,'C');
	$pdf->Ln(4);

	//todos los dueños involucrados y captoro todos los inmuebles asociados
	$listainmuebles = "";
	$sql = "select idinmueble, count(idduenio) from duenioinmueble where idinmueble in (select idinmueble from edoduenio where idduenio =" . $this->idduenio . " group by idinmueble) having count(idduenio)>1 ";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$listainmuebles .= $row["idinmueble"] . " ,";
	}
	$listainmuebles = substr($listainmuebles,0,-1);
	
	
	$sql = "select *, d.idduenio as idd from duenio d, duenioinmueble e where d.idduenio = e.idduenio and idinmueble in ($listainmuebles)";
	//sql = 
	$operacion = mysql_query($sql);
	$listaduenios ="";
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(110,4,"CLIENTE",1,0,'C',false);
	while($row = mysql_fetch_array($operacion))
   	{
		$listaduenios .= $row["idd"] . " ,";
		//$pdf->SetFont('Arial','B',8);
		//$pdf->Cell(110,4,"CLIENTE",1,0,'C',false);
		$pdf->setTextColor(0,0,0);
		$pdf->Ln(4);
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(110,4,utf8_decode($row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"] . " (" . $row["porcentaje"] . "%)"),"LR",0,'L');	
		//$pdf->Ln(4);
		//$pdf->SetFont('Arial','',6);
		//$pdf->Cell(110,4,"RFC: " . utf8_decode($row["rfcd"]) ,"LR",0,'L');
		//$pdf->Ln(4);   	
		//$pdf->Cell(110,4,utf8_decode("Calle " . $row["called"] . " No." . $row["noexteriord"] . " " . $row["nointeriord"] . " " ),"LR",0,'L');
		//$pdf->Ln(4);
		//$pdf->Cell(110,4,utf8_decode("Col." . $row["coloniad"] . ", " . $row["delmund"] . " " . $row["estadod"] . " C.P. " . $row["cpd"] ),"LBR",0,'L');
		
		$fant="";	
	}
	$listaduenios = substr($listaduenios,0,-1);
	$pdf->Ln(4);
	$pdf->Cell(110,4," ","LBR",0,'L');
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',8);
	
//$pdf->AddPage('P');

    	$pdf->SetFont('Arial','B',8);
    	$pdf->Cell(200,5,utf8_decode('A reportar'),1,0,'C',false);
    	$pdf->Ln(5);
    	$pdf->Cell(150,5,utf8_decode('DESCRIPCIÓN'),1,0,'C',false);
    	

    	$pdf->Cell(50,5,'IMPORTE',1,1,'C',false);
    	$pdf->SetFont('Arial','B',8);	
	

//consulta para marcados y generados de esta ocación
		//$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true and reportado = 1  and d.idduenio=" . $this->idduenio . " ";
		$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe>0  and isnull(fechagen)=true  and fechaedo<'$fechamenos'  and reportado = 1 and e.idtipocobro>0  and d.idduenio in ($listaduenios ) and e.idinmueble in ($listainmuebles)  and traspaso = 0 ";
		$sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
		$operacionc = mysql_query($sqlc);
		
		
		
		$np=0;
		$renp=0;
		$hf=1;
		$pdf->SetFont('Arial','',7);
		$dif_y=3;
		$idcontrato =0;
		while($rowc = mysql_fetch_array($operacionc))	
		{
		
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idcontrato"] != $idcontrato)
			{
				
				$idcontrato = $rowc["idcontrato"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode($inmueble ),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}


		
			//$np +=(double)$c[0];
			$np +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			

			
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion

    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(1);

		}


		$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe>0  and isnull(fechagen)=true    and reportado = 1 and e.idtipocobro=0  and d.idduenio in ($listaduenios ) and e.idinmueble in ($listainmuebles) and traspaso = 0 ";
		$sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
		$operacionc = mysql_query($sqlc);
		
		while($rowc = mysql_fetch_array($operacionc))	
		{
		
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idcontrato"] != $idcontrato)
			{
			
				
				$idcontrato = $rowc["idcontrato"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode($inmueble ),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}


		
			//$np +=(double)$c[0];
			$np +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			$posy1=$pdf->GetY();//posición antes de escribir concepto
						
			
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion


    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(1);

		}
		



		
		
	$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, i.idinmueble as idi from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble  and importe>0  and isnull(fechagen)=true  and reportado = 1 and idcontrato =0 and d.idduenio in ($listaduenios ) and e.idinmueble in ($listainmuebles) and traspaso = 0 ";
	//$sqlc .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
	$sqlc .=" order by e.idduenio, e.idcontrato,idedoduenio";	

	$operacionc = mysql_query($sqlc);	
	
		$idi = 0;
		while($rowc = mysql_fetch_array($operacionc))	
		{
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idi"] != $idi)
			{
								
				$idi = $rowc["idi"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				//$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode("Cargos directos" . $inmueble),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}

		
			//$np +=(double)$c[0];
			$np +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			$posy1=$pdf->GetY();//posición antes de escribir concepto
					
			
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion

			

    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(4);

		}		
		
	

		
		
		
		//notas de credito
		$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e where d.idduenio = e.idduenio and importe<>0  and isnull(fechagen)=true  and reportado = 1 and d.idduenio in ($listaduenios ) and e.idinmueble in ($listainmuebles) and notacredito = true ";
		$sqlc .= " order by e.idduenio, e.idcontrato,idedoduenio";		
		
		//$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true and reportado = 1  and d.idduenio=" . $this->idduenio . " ";
		//$sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
		$operacionc = mysql_query($sqlc);
		

		$idcontrato =0;
		while($rowc = mysql_fetch_array($operacionc))	
		{

			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idcontrato"] != $idcontrato)
			{
					
				
				$idcontrato = 1;
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode("Notas de crédito"),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}

		
			//$np +=(double)$c[0];
			$np +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ) ,0,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ),1 ,1,'L');//descripcion


    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),0,1,'R');//precio
    		//$pdf->Ln(4);

		}		
		
//---------------------------------------		
// Aqui verifico si hay algun traspaso para este dueño por aplicar y realizo la consulta de todos los
// reportados hasta el momento y lo agrego al total de los abonos		
		
		//Verifico si ya tengo un traspaso mayor que cero
		$sqltrs = "select * from edoduenio where idduenio in ($listaduenios ) and idinmueble in ($listainmuebles) and isnull(fechagen)=true  and reportado = 1 and traspaso =1" ;
		$operaciontrs= mysql_query($sqltrs);
		$rowtrs = mysql_fetch_array($operaciontrs);
		if(mysql_num_rows($operaciontrs)>0)
		{//Ya existe traspaso y agrego los datos del traspaso

			if($rowtrs["importe"]>0)
			{
				if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
				$dif_y=3;
								
				
				$idcontrato = 1;
		
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode("Traspaso"),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	

				//$np +=(double)$c[0];
				$np +=$rowtrs["importe"] + $rowtrs["iva"];
				$sub = $rowtrs["importe"] + $rowtrs["iva"];
			
				//$np +=$rowc["importe"];// + $rowc["iva"];
				//$sub = $rowc["importe"];// + $rowc["iva"];			
				$renp +=1;
			 	
			
				$posy1=$pdf->GetY();//posición antes de escribir concepto
				$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
				//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
				$pdf->MultiCell(150,$dif_y, utf8_decode($rowtrs["notaedo"] ) ,0,'L');//descripcion
				//$pdf->Cell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ),1 ,1,'L');//descripcion


   		 		$posy2=$pdf->GetY();
   		 		$posX2=$pdf->GetX();//posicion despues de escribir concepto

	    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas			

    			$pdf->SetY($posy1);    		
    			$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    			$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),0,1,'R');//precio
	    		//$pdf->Ln(4);
			}

		}		
		else //si no hay traspaso verifico si tiene algun traspaso pendiente por ser aplicado
		{
			
			$sqltrs = "select * from traspasodepara where para = " . $this->idduenio;
			$operaciontrs= mysql_query($sqltrs);
			$rowtrs = mysql_fetch_array($operaciontrs);
			if(mysql_num_rows($operaciontrs)>0)
			{//este propietario recibirá un traspaso
				
				$de = $rowtrs["de"];
				$justificacion = $rowtrs["justificacion"];
				
				
				
				$mes = date("Y-m") . "-01";
				$sqlde="select concat(nombre,' ', nombre2, ' ', apaterno,' ', amaterno) as nombre, sum(importe + iva) as importe from edoduenio ed, duenio d where ed.idduenio = d.idduenio and d.idduenio =$de and reportado = true and isnull(fechagen)=true  group by nombre, nombre2, apaterno, amaterno";
				$operacionde= mysql_query($sqlde);
				$rowde = mysql_fetch_array($operacionde);
				
				$descripcion="Traspaso de " . $rowde["nombre"] . " $justificacion $periodo";
				
				if(mysql_num_rows($operacionde)>0)
				{//existen marcados y colocar el nombre del propietario y el importe
					
					
					
					
					if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
					$dif_y=3;
								
				
					$idcontrato = 1;
		
					$pdf->SetFont('Arial','B',9);
					$pdf->Ln(3);
					$pdf->MultiCell(240,$dif_y, utf8_decode("Traspaso"),0 ,1,'L');//descripcion
					$pdf->SetFont('Arial','',7);
					$pdf->Ln(1);
	
				
					if($rowde["importe"]>0)
					{
					
						$np +=$rowde["importe"]; //+ $rowtrs["iva"];
						$sub = $rowde["importe"]; //+ $rowtrs["iva"];
					}
					else
					{
						$np +=0; //+ $rowtrs["iva"];
						$sub = 0; //+ $rowtrs["iva"];					
					}
			
		
					$renp +=1;
			 	
			
					$posy1=$pdf->GetY();//posición antes de escribir concepto
					$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
				//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
					
					if($sub ==0)
					{
						$pdf->MultiCell(150,$dif_y, utf8_decode("Importe insuficiente para traspaso") ,1,'L');//descripcion
					}
					else
					{
						$pdf->MultiCell(150,$dif_y, utf8_decode($descripcion ) ,1,'L');//descripcion
					}
				//$pdf->Cell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ),1 ,1,'L');//descripcion


   			 		$posy2=$pdf->GetY();
   			 		$posX2=$pdf->GetX();//posicion despues de escribir concepto

	   		 		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas			

   		 			$pdf->SetY($posy1);    		
   		 			$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
   		 			$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
	    		//$pdf->Ln(4);					
					
				}
				else
				{//Aun no marca nada del estado de cuenta, colocar el texto de no hay elementos seleccionados a reportar
					
					if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
					$dif_y=3;
								
				
					$idcontrato = 1;
		
					$pdf->SetFont('Arial','B',9);
					$pdf->Ln(3);
					$pdf->MultiCell(240,$dif_y, utf8_decode("Traspaso"),0 ,1,'L');//descripcion
					$pdf->SetFont('Arial','',7);
					$pdf->Ln(1);
	


					$np +=0; //+ $rowtrs["iva"];
					$sub = 0; //+ $rowtrs["iva"];
			
		
					$renp +=1;
			 	
			
					$posy1=$pdf->GetY();//posición antes de escribir concepto
					$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
				//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
					$pdf->MultiCell(150,$dif_y, utf8_decode("No existen elementos reportados a ser transferidos" ) ,1,'L');//descripcion
				//$pdf->Cell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ),1 ,1,'L');//descripcion


   			 		$posy2=$pdf->GetY();
   			 		$posX2=$pdf->GetX();//posicion despues de escribir concepto

	   		 		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas			

   		 			$pdf->SetY($posy1);    		
   		 			$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
   		 			$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
	    		//$pdf->Ln(4);						
					
					
					
					
					
				}
			
				
		
			}			
			
			
		}
				

//------------------------------------------------------		
		$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
		$pdf->MultiCell(150,$dif_y, utf8_decode('Total de abonos:') ,0,'R');//descripcion

    	$posy2=$pdf->GetY();
    	$posX2=$pdf->GetX();//posicion despues de escribir concepto

    	$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    	$pdf->SetY($posy1);    		
    	$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    	$pdf->Cell(50,$dif_y,"$ ".number_format($np, 2, '.', ','),0,1,'R');//precio	
//*********************************************************************************************
		$pdf->Ln(5);

//consulta para marcados y generados de esta ocación
		//$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true and reportado = 1  and d.idduenio=" . $this->idduenio . " ";
		$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<0  and isnull(fechagen)=true  and fechaedo<'$fechamenos' and  reportado = 1 and e.idtipocobro>0  and d.idduenio in ($listaduenios ) and e.idinmueble in ($listainmuebles) and traspaso = 0 ";
		$sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
		$operacionc = mysql_query($sqlc);
		
		
		
		$npm=0;
		$renp=0;
		$hf=1;
		$pdf->SetFont('Arial','',7);
		$dif_y=3;
		while($rowc = mysql_fetch_array($operacionc))	
		{
		
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idcontrato"] != $idcontrato)
			{
				
				
				$idcontrato = $rowc["idcontrato"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode($inmueble ),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}

		
			//$np +=(double)$c[0];
			$npm +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode( $rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion


    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(4);

		}


		$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<0  and isnull(fechagen)=true  and  reportado = 1 and e.idtipocobro=0  and d.idduenio in ($listaduenios ) and e.idinmueble in ($listainmuebles) and traspaso = 0 ";
		$sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
		$operacionc = mysql_query($sqlc);
		
		
		while($rowc = mysql_fetch_array($operacionc))	
		{
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idcontrato"] != $idcontrato)
			{

				$idcontrato = $rowc["idcontrato"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode($inmueble ),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}

		
			//$np +=(double)$c[0];
			$npm +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode( $rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion


    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(4);

		}


		
	$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, i.idinmueble as idi from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble  and importe<0  and isnull(fechagen)=true  and reportado = 1 and idcontrato =0 and d.idduenio in ($listaduenios ) and e.idinmueble in ($listainmuebles) and traspaso = 0 ";
	//$sqlc .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";
	$sqlc .=" order by e.idduenio, e.idcontrato,idedoduenio";	

	$operacionc = mysql_query($sqlc);	
	
		$idi = 0;
		while($rowc = mysql_fetch_array($operacionc))	
		{
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idi"] != $idi)
			{
	
				
				$idi = $rowc["idi"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				//$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br><table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode("Cargos directos" . $inmueble),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}

		
			//$np +=(double)$c[0];
			$npm +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion


    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(4);

		}	

	$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, e.idinmueble as idi from duenio d, edoduenio e where d.idduenio = e.idduenio and e.idinmueble = 0  and importe<0  and isnull(fechagen)=true  and reportado = 1 and idcontrato =0 and d.idduenio in ($listaduenios ) and e.idinmueble in ($listainmuebles) and traspaso = 0	 ";
	//$sqlc .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
	$sqlc .=  " order by e.idduenio, e.idcontrato,idedoduenio";	

	$operacionc = mysql_query($sqlc);	
	
		$idi = 0;
		while($rowc = mysql_fetch_array($operacionc))	
		{
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
			if($rowc["idi"] != $idi)
			{

				
				$idi = $rowc["idi"];
				$duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
				//$reporte .= "</table><br><br>";
				$clase = "";
				$inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
				//$inaquilino = $rowc["nombre"] . " " . $rowc["nombre2"]  . " " . $rowc["apaterno"]  . " " . $rowc["amaterno"];
				$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
				//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
				$pdf->SetFont('Arial','B',9);
				$pdf->Ln(3);
				$pdf->MultiCell(240,$dif_y, utf8_decode("Cargos directos" . $inmueble),0 ,1,'L');//descripcion
				$pdf->SetFont('Arial','',7);
				$pdf->Ln(1);
	
			}

		
			//$np +=(double)$c[0];
			$npm +=$rowc["importe"] + $rowc["iva"];
			$sub = $rowc["importe"] + $rowc["iva"];
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
			
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,1,'L');//descripcion


    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		//$pdf->Ln(4);

		}				
		
		
//-------------------------------------
//para mostrar lo que se transferirá
		

			$sqltrs = "select * from traspasodepara where de = " . $this->idduenio;
			$operaciontrs= mysql_query($sqltrs);
			$rowtrs = mysql_fetch_array($operaciontrs);
			if(mysql_num_rows($operaciontrs)>0)
			{//este propietario recibirá un traspaso
				
				$para= $rowtrs["para"];
				$justificacion = $rowtrs["justificacion"];
				
				
				
				$mes = date("Y-m") . "-01";
				$sqlde="select concat(nombre,' ', nombre2, ' ', apaterno,' ', amaterno) as nombre from  duenio d where  d.idduenio =$para";
				$operacionde= mysql_query($sqlde);
				$rowde = mysql_fetch_array($operacionde);
				
				$descripcion="Traspaso para " . $rowde["nombre"] . " $justificacion $periodo";
				
				if(($np + $npm)>0)
				{//Hay que transferir así que el importe podria ser este
					
					
					if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
					$dif_y=3;
								
				
					//$idcontrato = 1;
		
					$pdf->SetFont('Arial','B',9);
					$pdf->Ln(3);
					$pdf->MultiCell(240,$dif_y, utf8_decode("Traspaso"),0 ,1,'L');//descripcion
					$pdf->SetFont('Arial','',7);
					$pdf->Ln(1);
	

					$sub = ($np + $npm)*(-1);
					$npm +=($np + $npm)*(-1);
					//$sub = ($np + $npm)*(-1);
			
		
					$renp +=1;
			 	
			
					$posy1=$pdf->GetY();//posición antes de escribir concepto
					$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
				//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
					$pdf->MultiCell(150,$dif_y, utf8_decode($descripcion ) ,1,'L');//descripcion
				//$pdf->Cell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ),1 ,1,'L');//descripcion


   			 		$posy2=$pdf->GetY();
   			 		$posX2=$pdf->GetX();//posicion despues de escribir concepto

	   		 		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas			

   		 			$pdf->SetY($posy1);    		
   		 			$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
   		 			$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
	    		//$pdf->Ln(4);					
					
				}
				else
				{//Aun no marca nada del estado de cuenta, colocar el texto de no hay elementos seleccionados a reportar
					
					if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
					$dif_y=3;
								
				
					//$idcontrato = 1;
		
					$pdf->SetFont('Arial','B',9);
					$pdf->Ln(3);
					$pdf->MultiCell(240,$dif_y, utf8_decode("Traspaso"),0 ,1,'L');//descripcion
					$pdf->SetFont('Arial','',7);
					$pdf->Ln(1);
	


					$np +=0 ;//+ $rowtrs["iva"];
					$sub = 0 ;//+ $rowtrs["iva"];
			
		
					$renp +=1;
			 	
			
					$posy1=$pdf->GetY();//posición antes de escribir concepto
					$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		
				//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
					$pdf->MultiCell(150,$dif_y, utf8_decode("Importe insuficiente para traspaso" ) ,1,'L');//descripcion
				//$pdf->Cell(150,$dif_y, utf8_decode("(Nota de credto) " . $rowc["notaedo"] ),1 ,1,'L');//descripcion


   			 		$posy2=$pdf->GetY();
   			 		$posX2=$pdf->GetX();//posicion despues de escribir concepto

	   		 		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas			

   		 			$pdf->SetY($posy1);    		
   		 			$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
   		 			$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
	    		//$pdf->Ln(4);						
					
					
					
					
					
				}
			
				
		
			}			
	

//-------------------------------------		
		
		
		
		
		$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
		$pdf->MultiCell(150,$dif_y, utf8_decode('Total a cobrar:'),0,1,'R');//descripcion

    	$posy2=$pdf->GetY();
    	$posX2=$pdf->GetX();//posicion despues de escribir concepto

    	$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    	$pdf->SetY($posy1);    		
    	$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    	$pdf->Cell(50,$dif_y,"$ ".number_format($npm, 2, '.', ','),0,1,'R');//precio	
		
		//$pdf->Ln(1);
		$pdf->Ln(3);


//para traspaso

	//traspasos
	//traspasos en cero
	$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, e.idinmueble as idi from duenio d, edoduenio e where d.idduenio = e.idduenio and e.idinmueble = 0  and  isnull(fechagen)=true  and reportado = 1 and idcontrato =0 and d.idduenio in ($listaduenios ) and e.idinmueble in ($listainmuebles) and traspaso = 1 and importe=0";
	$sqlc .=  " order by e.idduenio, e.idcontrato,idedoduenio";	

	$operacionc = mysql_query($sqlc);	
	$trs=0;
	if(mysql_num_rows($operacionc)>0)
	{
		$idi = 0;
		while($rowc = mysql_fetch_array($operacionc))	
		{
		
			if ($pdf->GetY() >=240){$pdf->AddPage('P');	}	
			$dif_y=3;
								
				
			//$idcontrato = 1;
		
			$pdf->SetFont('Arial','B',9);
			$pdf->Ln(3);
			$pdf->MultiCell(240,$dif_y, utf8_decode("Traspaso"),0 ,1,'L');//descripcion
			$pdf->SetFont('Arial','',7);
			$pdf->Ln(1);
		
			//$np +=(double)$c[0];
			//$trs +=$rowc["importe"] + $rowc["iva"];
			$sub = 0;
			
			//$np +=$rowc["importe"];// + $rowc["iva"];
			//$sub = $rowc["importe"];// + $rowc["iva"];			
			$renp +=1;
			 	
			
			$posy1=$pdf->GetY();//posición antes de escribir concepto
			
			//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
			$pdf->MultiCell(150,$dif_y, utf8_decode($rowc["notaedo"] ) ,1,'L');//descripcion
			
			
			//$pdf->Cell(150,$dif_y, utf8_decode($rowc["notaedo"] ),1 ,0,'L');//descripcion

    		$posy2=$pdf->GetY();
    		$posX2=$pdf->GetX();//posicion despues de escribir concepto

    		$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    		$pdf->SetY($posy1);    		
    		$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    		$pdf->Cell(50,$dif_y,"$ ".number_format($sub, 2, '.', ','),1,1,'R');//precio
    		$dif_y=3;
    		$pdf->Ln(4);

		}					
		
			
	}

//****







		$posy1=$pdf->GetY();//posición antes de escribir concepto
			
		//$pdf->MultiCell(10,1.5,"\n".utf8_decode($c[0]),"LR",'C'); //cantidad de unidades
		$pdf->SetFont('Arial','',9);
		$pdf->MultiCell(150,$dif_y, utf8_decode('Total a pagar:') ,0,'R');//descripcion

    	$posy2=$pdf->GetY();
    	$posX2=$pdf->GetX();//posicion despues de escribir concepto

    	$dif_y = $posy2-$posy1;//obtengo alto de las siguientes celdas

    	$pdf->SetY($posy1);    		
    	$pdf->SetX(160);//reposiciono Y y X despues del concepto, 10 de margen en x
    	$pdf->Cell(50,$dif_y,"$ ".number_format(($np+$npm), 2, '.', ','),0,1,'R');//precio	
    	$pdf->SetFont('Arial','',7);

	
	}
	
	
	
	
		//$this->pdf="reporte_0.pdf";
		//$pdf->Output($this->pdf,"F");  //guardo en disco
    	@$pdf->Output();//muestro el pdf


	}
	
	



}



?>
