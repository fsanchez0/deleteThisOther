<?php

function fmoneda($numm)
{
	if($numm)
	{
	
		return "$ " . number_format($numm,2,".",",");
	
	}
	else
	{
		return "";
	}


}


//FunciÛn que da formato a la fecha y regresa los datos como necesitamos
// la fecha que recibe es en formato aaaa/mm/dd y manipula sus valores para
//regresar con el parametro $c dependiendo lo que necesitems de la fecha.
function diacompleto($fecha,$c)
{
	if($fecha)
	{
	$dia=substr($fecha,8,2);
	$mes=substr($fecha,5,2);
	$anio=substr($fecha,0,4);
	switch($c)
		{
		case 0: //Mes y aÒo
		
			return  mesesp($mes) . " " . $anio;
			break;
		
		case 1: //Dia completo en palabra
			
			return $dia . " DE " . mesesp($mes) . " DE " . $anio;
			break;
			
		case 2: //dia
		
			return $dia;
			break;
			
		case 3: //mes
			
			return mesesp($mes);
			break;
			
		case 4: //aÒo
	
			return $anio;
			break;
			
		case 5: //aÒo en letra
		
			return letra($anio,0);
			break;
			
		default:
			return "Sin Fecha " . $fecha . " ..";
		}
	
	/*
	if ($c==0)
	{
		return  mesesp($mes) . " " . $anio;
	}
	else
	{
		return $dia . " DE " . mesesp($mes) . " DE " . $anio;
	}
	*/
	}
	else
	{
		return "Sin Fecha " . $fecha . " ..";
	}
	

}


//funcion que nos da el nombre en espaÒol del mes.
function mesesp($numero)
{
	$resultado="";
	switch ($numero)
	{
		case "01":
			$resultado = "ENERO";
			break;
		case "02":
			$resultado = "FEBRERO";
			break;
		case "03":
			$resultado = "MARZO";
			break;
		case "04":
			$resultado = "ABRIL";
			break;
		case "05":
			$resultado = "MAYO";
			break;
		case "06":
			$resultado = "JUNIO";
			break;
		case "07":
			$resultado = "JULIO";
			break;
		case "08":
			$resultado = "AGOSTO";
			break;
		case "09":
			$resultado = "SEPTIEMBRE";
			break;
		case "10":
			$resultado = "OCTUBRE";
			break;
		case "11":
			$resultado = "NOVIEMBRE";
			break;
		case "12":
			$resultado = "DICIEMBRE";
		
	}


	return $resultado;


}



//***** funciÛn para sumar 2 valures*****
//puede usarse en forma recursiva
function suma($dato1, $dato2)
{
	return $dato1 + $dato2;
}
//***** fin funciÛn para sumar 2 valures*****


//***** funciÛn para restar 2 valures*****
//puede usarse en forma recursiva
function resta($dato1, $dato2)
{
	return $dato1 - $dato2;
}
//***** fin funciÛn para restar 2 valures*****


//***** funciÛn para multiplicar 2 valures*****
//puede usarse en forma recursiva

function producto($dato1, $dato2)
{
	return $dato1 * $dato2;
}

//***** fin funciÛn para multiplicar 2 valures*****


//***** funciÛn para dividir 2 valures*****
//puede usarse en forma recursiva
function division($dato1, $dato2)
{
	if($dato2==0)
	{
		return 0;
	}
	else
	{
		return $dato1 / $dato2;
	}
}
//***** fin funciÛn para sumar 2 valures*****


//***** funciÛn para escribir datos corridos en los recibos dentro del pdf *****
//puede usarse en forma recursiva
//recibe $cadena = cadena a ser impresa
//$estilo = la cadena que verifica para el estilo solo adminte por lo pronto 'B' lo demas es normal
//$ojb= es el objeto $pdf que ser· usado para realizar la impresiÛn de $cadena
function escribir($cadena,$estilo,$ojb)
{
	
	if (trim($estilo)=="B")
	{	
		$ojb->SetFont('Arial','B',12);
	}
	else
	{
		$ojb->SetFont('Arial','',12);
	}

	$ojb->write(5,$cadena);
	$ojb->SetFont('Arial','',12);
}

//***** fin funciÛn para escribir datos corridos *****


//************** Cambia los acentos a cadena HTML ***********************
	//FunciÛn que convierte las letras con acentos, Ò's y simbolo de iniciodepregunta
	//a su cadena de html
	//$cadena = cadena que ser· cambiada para su presentaciÛn como html
	function CambiaAcentosaHTML($cadena)
	{
	
		$acentos = array("!","√°","√©",	"√≠","√≥",	"√∫","√Å","√â","√ç","√ì","√ö","√±","√ë");
		$acentosHTML = array("<br>", "&aacute;", "&eacute;", "&iacute;", "&oacute;", "&uacute;", "&Aacute;", "&Eacute;", "&Iacute;", "&Oacute;", "&Uacute;",  "&ntilde;", "&Ntilde;");
		
		$cadena = str_replace($acentos, $acentosHTML , $cadena, $enontro);
		
		
		$acentos = array("·", "¡", "È", "…", "Ì", "Õ", "Û", "”", "˙", "⁄", "¸", "‹", "ø", "Ò", "—");
		$acentosHTML = array("&aacute;", "&Aacute;", "&eacute;", "&Eacute;", "&iacute;", "&Iacute;", "&oacute;", "&Oacute;", "&uacute;", "&Uacute;", "&uuml;", "&Uuml;", "&iquest;", "&ntilde;", "&Ntilde;");
		
	
		$A =str_replace($acentos, $acentosHTML , $cadena, $enontro);
	
		return  $A;
		
	}
	
	//******** fin de funcion que cambia acentos a HTML ****************
	



//************************* Numero a letras ********************************************
//FunciÛn que convierte numeros en letra para el estandar mexicano
//destinado a ser escrito para facturas que funciona en forma recursiva
//-----------------------------------------------------------------------------------
//---------------  FunciÛn principal ----------------------------------------------
//su capasidad maxima de converciÛn es 999,999,999,999.99 si se requiere algo mayor
//ser· necesario agreagr la funciÛn de billones, trillones, cuatrillones, etc.
//esta funciÛn recibe un numero doble con o sin decimales
function letra($numero,$f)
{
	$lnumero= "";
	$decimales ="";
	If ($numero <> "" )
	{
    	$lnumero = number_format($numero,  2, '.', '');
    	$decimales = substr($lnumero, strlen($lnumero) - 2);
    	if($f==1)
    	{    		
    		return "(" . millones(substr($lnumero, 0, strlen($lnumero) - 3)) . " PESOS " . $decimales . "/100 M.N.)";
    	}
    	else
    	{
    		return millones(substr($lnumero, 0, strlen($lnumero) - 3));
    	}
    }
	else
	{
    	return "";
	}

}

//------     funciÛn de millones ---- -------------------
function millones($mill) 
{
	$ongitud=0;
    $mill = "" . (double)$mill;
    $longitud = strlen($mill);
    if ($longitud < 13 && $longitud > 6)
    {
        if ($longitud == 7 && substr($mill, 0, 1) == "1")
        {
            return miles(substr($mill, 0, 1)) . " MILLON " . miles(substr($mill, 1));
        }
        else
        {
            return miles(substr($mill, 0, strlen($mill) - 6)) . " MILLONES " . miles(substr($mill, (strlen($mill) - 6) + 1 -1));
        }
    }
    else
    {
        return miles($mill);
    }
}


//------     funciÛn de miles ---- -------------------
function miles($mil )
{
    $mil = "" . (double)$mil;    
    if ($mil == "1000")
    {
        return "MIL";
    }
    else
    {
        switch (strlen($mil))
        {
        case 4:
            return unidad(substr($mil, 0, 1)) . " MIL " . cientos(substr($mil, 1));
            break;
        Case 5:
            return decenas(substr($mil, 0, 2)) . " MIL " . cientos(substr($mil, 2));
            break;
        Case 6:
            return cientos(substr($mil, 0, 3)) . " MIL " . cientos(substr($mil, 3));
            break;
        default:
            return cientos($mil);
        }
    }
    
}

//------     funciÛn de cientos ---- -------------------
function cientos($cie)
{
    $cie=(double)$cie;
    if ((double)$cie == 100)
    {
        return "CIEN";
    }    
    elseif ((double)$cie > 100 && (double)$cie < 200)
    {
        return "CIENTO " . decenas(substr($cie, 1));
    }    
    elseif ((double)$cie >= 200 && (double)$cie < 300)
    {
        return "DOSCIENTOS " . decenas(substr($cie, 1));
    }    
    elseif ((double)$cie >= 300 && (double)$cie < 400)
    {
        return "TRESCIENTOS " . decenas(substr($cie, 1));
    }    
    elseif ((double)$cie >= 400 && (double)$cie < 500)
    {
        return "CUATROCIENTOS " . decenas(substr($cie, 1));
    }    
    elseif ((double)$cie >= 500 && (double)$cie < 600)
    {
        return "QUINIENTOS " . decenas(substr($cie, 1));
	}
    elseif ((double)$cie >= 600 && (double)$cie < 700)
    {
        return "SEISCIENTOS " . decenas(substr($cie, 1));
    }    
    elseif ((double)$cie >= 700 && (double)$cie < 800)
    {
        return "SETECIENTOS " . decenas(substr($cie, 1));
    }    
    elseif ((double)$cie >= 800 && (double)$cie < 900)
    {
        return "OCHOCIENTOS " . decenas(substr($cie, 1));
    }    
    elseif ((double)$cie >= 900 && (double)$cie <= 999)
    {
        return "NOVECIENTOS " . decenas(substr($cie, 1));
    }
    else
    {
    	//echo (double)$cie;
        return decenas( (double)$cie);
        
    }
    
}

//------     funciÛn de decenas ---- -------------------
function decenas($dec) 
{
	$dec = "" . (double)$dec;
	switch ($dec)
	{
	Case "10":
	    return "DIEZ";
	    break;
	Case "11":
	    return "ONCE";
	    break;
	Case "12":
	    return "DOCE";
	    break;
	Case "13":
	    return "TRECE";
	    break;
	Case "14":
	    return "CATORCE";
	    break;
	Case "15":
	    return "QUINCE";
	    break;
	Case "20":
	    return "VEINTE";
	    break;
	Case "30":
	    return "TREINTA";
	    break;
	Case "40":
	    return "CUARENTA";
	    break;
	Case "50":
	    return "CINCUENTA";
	    break;
	Case "60":
	    return "SESENTA";
	    break;
	Case "70":
	    return "SETENTA";
	    break;
	Case "80":
	    return "OCHENTA";
	    break;
	Case "90":
	    return "NOVENTA";
	    break;
	default:
    
	    if ((double)$dec > 15 && (double)$dec < 20)
	    {
	        return "DIECI" . unidad(substr($dec, 1));
	    }    
	    elseif ((double)$dec > 20 && (double)$dec < 30)
	    {
	        return "VEINTI" . unidad(substr($dec, 1));
	    }    
	    elseif ((double)$dec > 30 && (double)$dec < 40)
	    {
	        return "TREINTA Y " . unidad(substr($dec, 1));
	    }    
	    elseif ((double)$dec> 40 && (double)$dec < 50)
	    {
	        return "CUARENTA Y " . unidad(substr($dec, 1));
	    }    
	    elseif ((double)$dec > 50 && (double)$dec < 60)
	    {
	        return "CINCUENTA Y " . unidad(substr($dec, 1));
		}
	    elseif ((double)$dec > 60 && (double)$dec< 70)
	    {
	        return "SESENTA Y " . unidad(substr($dec, 1));
	    }    
	    elseif ((double)$dec > 70 && (double)$dec < 80)
	    {
	        return "SETENTA Y " . unidad(substr($dec, 1));
	    }    
	    elseif ((double)$dec > 80 && (double)$dec < 90)
	    {
	        return "OCHENTA Y " . unidad(substr($dec, 1));
	    }    
	    elseif ((double)$dec > 90 && (double)$dec <= 99)
	    {
	        return "NOVENTA Y " . unidad(substr($dec, 1));
	    }    
	    else
	    {    
	        return unidad($dec);
	    }
	    
	}


}


//------     funciÛn de unidades ---- -------------------

function unidad($uni)
{
	$uni = "" . (double)$uni;
	switch ($uni)
	{
	Case "1":
	    return "UN";
	    break;
	Case "2":
	    return "DOS";
	    break;
	Case "3":
	    return "TRES";
	    break;
	Case "4":
	    return "CUATRO";
	    break;
	Case "5":
	    return "CINCO";
	    break;
	Case "6":
	    return "SEIS";
	    break;
	Case "7":
	    return "SIETE";
	    break;
	Case "8":
	    return "OCHO";
	    break;
	Case "9":
	    return "NUEVE";
	    break;
	default:
	    return "";
	}

}

//**********************fin numero en letra***************************************************

?>