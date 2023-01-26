<?php

//$palabra =$_GET["ref"];

function referenciabanorte($palabra)
{
	//Conversion de letras a numeros segun banorte, junto con ls sigitos
	$abc = array ('A'=>2,'D'=>3,'G'=>4,'J'=>5,'M'=>6,'P'=>7,'S'=>8,'V'=>9,'Y'=>0,'B'=>2,'E'=>3,'H'=>4,'K'=>5,'N'=>6,'Q'=>7,'T'=>8,'W'=>9,'Z'=>0,'C'=>2,'F'=>3,'I'=>4,'L'=>5,'O'=>6,'R'=>7,'U'=>8,'X'=>9,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9,'0'=>0);

	$resultado = array();
	//multiplo de todos los que corresponden a la palabra
	$pivote = 2;
	for($i=0;$i<strlen($palabra);$i++)
	{
		$resultado[$i] = $abc[substr($palabra,$i,1)] * $pivote;
		if($pivote == 1)
		{
			$pivote = 2;
		}
		else
		{
			$pivote = 1;
		}
		
	}
	//transformando resultados de 2 digitos a un solo digito y suma de valores
	$suma =0;
	for($i=0;$i<sizeof($resultado);$i++)
	{	
		if( strlen((string)$resultado[$i])>1)
		{
			$a = substr((string)$resultado[$i],0,1);
			$b = substr((string)$resultado[$i],1,1);
			$resultado[$i] = (int)$a + (int)$b;
		}
		$suma +=$resultado[$i];
	}
	//modulo de la divicion
	$digito = $suma % 10;

	if($digito == 10)
	{
		$digito = 0;
	}
	else
	{
		$digito = 10 - $digito;
	}
	return $palabra . $digito;
}


?>