<?php
//Es necesario tener una conexión activa para usar el control
class Calendario
{
	var $dia;
	var $mes;
	var $anio;
	var $cdia;
	var $cmes;
	var $canio;
	var $nodias;
	var $txtmes;
	var $nummes;
	var $servidor;
	var $usuario;
	var $pwd;
	var $base;
	var $L=0;
	var $M=0;
	var $W=0;
	var $J=0;
	var $V=0;
	var $S=0;
	var $D=0;

	//***************************************
	//Constructor de objeto
	//***************************************
	function Calendario ()
	{
		$this->dia = date('d');
		$this->mes = date('m');
		$this->anio = date('Y');
		$this->cdia = date('d');
		$this->cmes = date('m');
		$this->canio = date('Y');
		$this->DatosMes(date('U'));
	}

	//*************************************
	//Para confirmar si el año es visiesto
	//*************************************
	function Visiesto($vanio)
	{

		if ($vanio % 4 == 0)
		{
			return true;
		}
		else
		{
			return false;
		}

	}

	//************************************************************
	//Para obtener el dia de la semana 1 para lunes 7 para domingo
	//************************************************************
	function Semana ($Sfecha)
	{
		return date('w',$Sfecha);
	}

	//******************************************
	//Datos del mes
	//******************************************
	function DatosMes ($Mfecha)
	{
		//echo date('r',$Mfecha);
		switch (date('m',$Mfecha))
		{
		case 1:
			$this->nodias = 31;
			$this->txtmes = "Enero";
			$this->nummes = 1;
			break;

		case 2:
			$this->nodias = 28;
			$this->txtmes = "Febrero";
			$this->nummes = 2;

			if ($this->Visiesto($this->canio) == true)
			{
				$this->nodias = 29;
			}
			break;

		case 3:
			$this->nodias = 31;
			$this->txtmes = "Marzo";
			$this->nummes = 3;
			break;

		case 4:
			$this->nodias = 30;
			$this->txtmes = "Abril";
			$this->nummes = 4;
			break;

		case 5:
			$this->nodias = 31;
			$this->txtmes = "Mayo";
			$this->nummes = 5;
			break;

		case 6:
			$this->nodias = 30;
			$this->txtmes = "Junio";
			$this->nummes = 6;
			break;

		case 7:
			$this->nodias = 31;
			$this->txtmes = "Julio";
			$this->nummes = 7;
			break;

		case 8:
			$this->nodias = 31;
			$this->txtmes = "Agosto";
			$this->nummes = 8;
			break;

		case 9:
			$this->nodias = 30;
			$this->txtmes = "Septiembre";
			$this->nummes = 9;
			break;

		case 10:
			$this->nodias = 31;
			$this->txtmes = "Octubre";
			$this->nummes = 10;
			break;

		case 11:
			$this->nodias = 30;
			$this->txtmes = "Noviembre";
			$this->nummes = 11;
			break;

		case 12:
			$this->nodias = 31;
			$this->txtmes = "Diciembre";
			$this->nummes = 12;
			break;
		}

		//echo $this->nodias;
		//echo $this->txtmes;
		//echo $this->nummes;

	}


	//*************************************************
	//
	//*************************************************
	function BuscarMes ($Bnum)
	{
		// $Bnum es el numero que corresponde al que hay que
		// alterar (+ o -) para llegar al mes desado

		$Rmes=0;	// Variable del mes encontrado
		$Ranio=0;	// Variable del año al que pertenece el mes


		if ($Bnum != 0)
		{
			// Cuando hay cambio de mes, pone el mes resultante
			// en el numero de mes que corresponde
			$Rmes = $this->mes + $Bnum;

			if ($Rmes<$this->mes)
			{
				// Cuando la diferencia es menor que el mes actual

				// Verifica si la diferencia es menor que cero
				if ($Rmes <= 0)
				{
					// cambia a años atras del mes en cuestion

					if ($Rmes == 0)
					{
						// cuando es dic del año anterior
						$Ranio = $this->anio-1;
						$Rmes = 12;
					}
					else
					{
						// Cuando hay mas meses atras


						if ($Rmes % 12 != 0 )
						{
							// cuando es el mismo mes en años anteriores
							$Ranio = $this->anio + (int)($Rmes/12)-1;

						}
						else
						{
							// Cuando varia de mes en años anteriores

							if (((int)($Rmes/12))<0)
							{

								$Ranio = $this->anio + (int)($Rmes/12) -1;
								$Rmes = 12;
								//echo "es menos de -1 <br>";

							}
							else
							{
								$Ranio = $this->anio - 1;
								$Rmes = 1;
								//echo " no es menos de -1 <br>";
							}

						}


					}

				}


				if ($Rmes<0)
				{
					if ($Rmes<-12)
					{

						$Rmes = $Rmes - ((int)($Rmes/12)*12);
						$Rmes = 12 + $Rmes;
					}
					else
					{
						$Rmes = 12 + $Rmes;
					}

				}



			}
			else
			{
				if ($Rmes % 12 != 0 )
				{
					$Ranio = $this->anio + (int)($Rmes/12);
				}
				else
				{
					if (((int)($Rmes/12))>1)
					{
						$Ranio = $this->anio + (int)($Rmes/12) -1;
						$Rmes = 12;
					}
					else
					{
						$Ranio = $this->anio;
						$Rmes = 12;
					}

				}



				if ($Rmes  > 12)
				{

					$Rmes = $Rmes - ((int)($Rmes/12)*12);


				}

			}

			$this->cmes = $Rmes;
			if ($Ranio != 0) $this->canio = $Ranio;
		}
		//echo $this->cmes;
		//echo $this->canio;
	}


	//**********************************************
	//Obtener los dias de dias inabilas de la semana
	//**********************************************
	function GrupoSemana(){

		$this->L=0;
		$this->M=0;
		$this->W=0;
		$this->J=0;
		$this->V=0;
		$this->S=0;
		$this->D=0;

		//$this->ConectarBase();
		$SQL = "select * from dgrupo where idmotivo = 2";

		$result = @mysql_query ($SQL );
		if (!$result)
		{
			echo "<br> La consulta:<br><br><strong>" . $consulta . "</strong><br><br> Es incorrecta, corregir";
		}
		else
		{

			while ($row = mysql_fetch_array($result))
			{

				switch ($row["iddgrupo"])
				{
				case 1:
					$this->L=1;
					break;

				case 2:
					$this->M=1;
					break;

				case 3:
					$this->W=1;
					break;

				case 4:
					$this->J=1;
					break;

				case 5:
					$this->V=1;
					break;

				case 6:
					$this->S=1;
					break;

				case 7:
					$this->D=1;
					break;

				default:

				}
			}
		}

		mysql_free_result($result);

	}


	//***************************************************
	//Esta clase, regresa la clase para el HTML que se debe
	//de aplicar al dia que se le pasa
	//***************************************************
	function DiaClase($diad,$mesd, $aniod, $defecto){

		$DiaVacio = 'class="DiaVacio"'; //1
		$DiaOcupado = 'class="DiaOcupado"'; //2
		$DiaHoyOcupado = 'class="DiaHoyOcupado"'; //2
		$DiaHoy = 'class="DiaHoy"'; //3
		$MesAntSig = 'class="MesAnSig"'; //4
		$DiaInhabil = 'calss="DiaInabil"'; //5

		$def=1;

		//$this->ConectarBase();
		//echo "Mes " . $mesd;
		$SQL = "select * from calendario where fecha='" . $aniod . '-' . $mesd . '-' . $diad . "'";

		$result = @mysql_query ($SQL );
		while ($row = mysql_fetch_array($result))
		{

			switch ($row["idmotivo"])
			{
			case 1: //comun
				if ($DiaVacio != $defecto)
				{
					return $defecto;
				}
				else
				{
					return $DiaVacio;
					$def=0;
				};
				//return $DiaVacio;
				break;

			case 2: //inhabil
				return $DiaInabil;
				$def=0;
				break;

			case 3: //vacaciones
				return $DiaInahil;
				$def=0;
				break;

			case 4: //cita
				return $DiaOcupado;
				$def=0;
				break;

			case 5: //cumpleaños
				return $DiaOcuapdo;
				$def=0;
				break;

			default:

				$def=1;

			}

		}

		if ($def==1)
		{

			return $defecto;

		}
		mysql_free_result($result);

	}

	//******************************************
	//Genera el calendari ocompleto
	//******************************************
	function Gmes($Cambio)
	{

		$DiaVacio = 'class="DiaVacio"';
		$DiaOcupado = 'class="DiaOcupado"';
		$DiaHoyOcupado = 'class="DiaHoyOcupado"';
		$DiaHoy = 'class="DiaHoy"';
		$MesAntSig = 'class="MesAnSig"';
		$CalseHML = "";



		//Obtengo el mes a generar
		$this->BuscarMes($Cambio);
		$this->DatosMes(mktime(0,0,0,$this->cmes,1,$this->canio));
		$this->GrupoSemana();


echo <<<CabeceraC
	<table border = "1">
	<!--
	<tr class="DiaSemana">
			<td colspan="7" align="center" class="">$this->canio</td>
	</tr>
	-->
	<tr class="DiaSemana">
<!--		<td align="center"><a href="javascript:mes--;cargarCalendario(mes);" class="AvanceMes">&lt;&lt;</a></td><td colspan="5" align="center">$this->canio<br/>$this->txtmes</td><td align="center"><a href="javascript:mes++;cargarCalendario(mes);" class="AvanceMes">&gt;&gt;</a></td>-->
		<td align="center"><a href="javascript:mes--;cargarSeccion('scripts/calendario.php','calendario', 'num='+mes);" class="AvanceMes">&lt;&lt;</a></td><td colspan="5" align="center">$this->canio<br/>$this->txtmes</td><td align="center"><a href="javascript:mes++;cargarSeccion('scripts/calendario.php','calendario', 'num='+mes);" class="AvanceMes">&gt;&gt;</a></td>
	</tr>
	<tr class="DiaSemana">
		<th>D</th>
		<th>L</th>
		<th>M</th>
		<th>W</th>
		<th>J</th>
		<th>V</th>
		<th>S</th>
	</tr>
CabeceraC;



		//Obtengo los dias del mes anteriro
		$numMes=$this->cmes-1;
		if($numMes != 0)
		{

			$this->DatosMes(mktime(0,0,0,$numMes,1,$this->canio));
		}
		else
		{
			$this->DatosMes(mktime(0,0,0,12,1,$this->canio-1));
		}

		$MaxAntM = $this->nodias;

		//Regreso por los datos del mes a generar
		$this->DatosMes(mktime(0,0,0,$this->cmes,1,$this->canio));



		$i=$this->semana(mktime(0,0,0,$this->cmes,1,$this->canio))-1;
		$Ri = "";

		$tanio = $this->canio;
		$tmes = $this->cmes;
		for ($i ; $i>-1; $i--)
		{
			//echo $i;

			if ($tmes - 1 == 0)
			{
				$tmes = 12;
				$tanio = $tanio -1;
			}

			//if (date('d') == $cuenta && date('m') == $tmes && date('Y') == $tanio)
			if (date('d') == $i && date('m') == $tmes && date('Y') == $tanio)
			{
				//echo mktime(0,0,0,$tmes,$cuenta,$tanio);
				$ClaseHML =  $this->DiaClase($i,$tmes,$tanio, 'class="DiaHoy"');//$DiaHoy; //
			}
			else
			{


				$ClaseHML = $this->DiaClase($i,$tmes,$tanio, 'class="MesAnSig"');//$MesAntSig;
			}


			$Ri = "<td align=\"center\" ". $ClaseHML . ">$MaxAntM</td>\n" . $Ri;
			$MaxAntM--;
		}

		$RT = "";

		$i=$this->semana(mktime(0,0,0,$this->cmes,1,$this->canio));

		for ($j=1 ; $j<=$this->nodias; )
		{

			for ($i ; $i<7 ; $i++)
			{
				if($j>$this->nodias)
				{
					$cuenta = $j - $this->nodias;
					if ($tmes + 1 == 13)
					{
						$tmes = 0;
						$tanio = $tanio +1;
					}


					if (date('d') == $cuenta && date('m') == ($tmes +1 ) && date('Y') == $tanio)
					{
						$ClaseHML = $DiaHoy;//$this->DiaClase($cuenta,$tmes +1 ,$tanio, 'class="DiaHoy"');
					}
					else
					{
						$ClaseHML = $MesAntSig;//$this->DiaClase($cuenta,$tmes +1 ,$tanio, 'class="MesAntSig"');
					}
					$Ri = $Ri . "<td align=\"center\" ". $ClaseHML . ">$cuenta</td>\n";
				}
				else
				{
					if (date('d') == $j && date('m') == $this->cmes && date('Y') == $this->canio)
					{

						$ClaseHML = $this->DiaClase($j,$tmes,$tanio, 'class="DiaHoy"');//$DiaHoy;
					}
					else
					{
						$ClaseHML = $this->DiaClase($j,$tmes,$tanio, 'class="DiaVacio"');//$DiaVacio;
					}

					$Ri = $Ri . "<td align=\"center\" " . $ClaseHML . ">$j</td>\n";
				}
				$Ri;
				$j++;

			}
			$RT .= "<tr>" . $Ri . "</tr>";
			$Ri="";
			$i=0;

		}
		echo $RT .= "</table>";


	}



	//************************************
	// Conexión a l abase de datos
	//************************************
	/*
	function ConectarBase(){

		//$enlace = mysql_connect('localhost', 'root', '');
		//mysql_select_db('bujalil',$enlace) ;
		$enlace = mysql_connect($this->servidor,$this->usuario, $this->pwd);
		mysql_select_db($this->base,$enlace) ;

	}
	*/
	//**************************************
	// Asigna datos de conexión
	//**************************************
	/*function DatosConexion($s, $u, $p, $b){
		$this->servidor = $s;
		$this->usuario = $u;
		$this->pwd = $p;
		$this->base = $b;

	}
	*/

	//********************************************
	//Obtiene fecha partiendo de una fecha inicial, tomando los parametros de incremento
	//segun $n y $s
	//$f = fecha inicial
	//$n = numero a increnentar
	//$s = lugar en la fecha a incrementar $n (dia, mes, año)
	//********************************************
	function calculafecha($f,$n,$s)
	{
		//echo "fecha: " . $f ." ; " . $n . " ; " . $s . "<br>";
		//echo "<br>";
		$diacc = date('d',$f);
		//echo "<br>";
		$mescc = date('m',$f);
		//echo "<br>";
		$aniocc =date('Y',$f);
		//echo "<br>";
		$aux = $this->canio;
		$this->canio=$aniocc;

	if($n==0)
	{
		//Es uno geenrado en forma instantanea, un solo pago, no se genera otro más
		return "$aniocc-$mescc-$diacc";

	}
	else
	{

		switch($s)
		{
		case 1://años

			$aniocc +=$n;
			//Si al cambiar de año y de casualidad el año es visiesto
			//al cambiar a un año no visiesto, debe de poner el fin de mes
			//para el mes de febrero.
			if ($this->Visiesto($aniocc)!=true)
			{
				if($mescc==2)
				{
					if($diacc==29)
					{
						$diacc=28;

					}
				}

			}

			break;

		case 2://meses

			$mescc +=$n;
			while($mescc>12)
			{
				$mescc = $mescc - 12;
				$aniocc ++;
			}

			if ($mescc == 4 or $mescc == 6 or $mescc == 9 or $mescc == 11)
			{


				if ($diacc==31)
				{
					$diacc=30;
				}
			}
			elseif ($mescc == 2)
			{


				if ($this->Visiesto($aniocc)!=true)
				{
					if($diacc>=29)
					{
						$diacc=28;

					}
				}
				else
				{
					if($diacc>=29)
					{
						$diacc=29;

					}

				}
			}




			break;

		case 3://dias

			$diacc +=$n;

			$this->DatosMes(mktime(0,0,0,$mescc,1,$aniocc));

			while($diacc>$this->nodias)
			{

				$diacc = $diacc - $this->nodias;
				$mescc++;
				if ($mescc>12)
				{
					$mescc=$mescc - 12;
					$aniocc++;
				}
				$this->DatosMes(mktime(0,0,0,$mescc,1,$aniocc));

			}
			break;

		}
		$this->canio=$aux;
		if(strlen($mescc)==1)
		{
			$mescc="0" . $mescc;
		}
		if(strlen($diacc)==1)
		{
			$diacc="0" . $diacc;
		}
		return $aniocc . "-" . $mescc . "-" .$diacc;

	}

	}


	//********************************************
	//Obtiene fecha partiendo de otra, verificando los dias hinabiles
	//regresando el siguiente laboral segun el calendario
	//********************************************
	function fechagracia($f)
	{

		$ocupado=0;
		$listo=0;
		$listog=0;
		//Pongo el primer dia que pasan para analizar si es habil
		$fpivote = mktime(0,0,0,substr($f, 5, 2),substr($f, 8, 2),substr($f, 0, 4));
		// Cargo los dias de grupo de la semana para averiguar si alguno
		// esta marcado como no laboral
		$this->GrupoSemana();

		//inicio la busqueda de los sias habiles
	      
		while($listo==0 && $listog==0)
		{
			//Compruebo si el dia de la semaa es inhabil
			switch (date("w",$fpivote))
			{
			case 0: //Domingo
				if($this->D==1)
				{
					$ocupado=1;
				}
				break;

			case 1://Lunes
				if($this->L==1)
				{
					echo $ocupado=1;
				}
				break;

			case 2://Martes
				if($this->M==1)
				{
					$ocupado=1;
				}

				break;

			case 3://Miercoles
				if($this->W==1)
				{
					$ocupado=1;
				}

				break;

			case 4://Jueves
				if($this->J==1)
				{
					$ocupado=1;
				}

				break;

			case 5://Viernes
				if($this->V==1)
				{
					$ocupado=1;
				}


				break;

			case 6://Sabados
				//echo "entre al sabado";
				if($this->S==1)
				{
					$ocupado=1;
				}

			};


			//Verifica si es dia habil
			if ($ocupado==0)
			{
				//Genero la consulta para ver si es un dia inhabil o son vacaciones
				$sql="select count(idcalendario) as numero from calendario where year(fecha)=" . date('Y',$fpivote) . " and month(fecha)=" . date('m',$fpivote) . " and day(fecha)=" . date('d',$fpivote) . " and idmotivo=2 or idmotivo=3" ;
				$verifica = mysql_query($sql);
				$ver = mysql_fetch_array($verifica);
				//verifico si cuenta cero registros, es dia habil
				if ($ver["numero"]==0)
				{
					$listo=1;
				}

			}

			//Si es un dia de grupo habil y un dia habil en el calendario
			if ($ocupado==0  && $listo==1)
			{
				//Regreso la fecha encontrada como habil
				return date("Y",$fpivote) . "-" . date("m", $fpivote) . "-" . date("d",$fpivote);
			}
			else
			{
				//Agrego un dia más a la fecha para verificar si es un dia habil
				$aux =$this->calculafecha($fpivote,1,3);
				$fpivote=mktime(0,0,0,substr($aux , 5, 2),substr($aux , 8, 2),substr($aux , 0, 4));
				$ocupado=0;
				$listo=0;
			}


		}

	}

	//********************************************
	//Imprime los datos de als propiedades
	//********************************************
	function formatofecha($fecha)
	{
		$inimes['01']="ENE";
		$inimes['02']="FEB";
		$inimes['03']="MAR";
		$inimes['04']="APR";
		$inimes['05']="MAY";
		$inimes['06']="JUN";
		$inimes['07']="JUL";
		$inimes['08']="AGO";
		$inimes['09']="SEP";
		$inimes['10']="OCT";
		$inimes['11']="NOV";
		$inimes['12']="DIC";

		return substr($fecha,8,2) . "-" . $inimes[substr($fecha,5,2)] . "-" . substr($fecha,0,4);

	}


	//********************************************
	//Imprime los datos de als propiedades
	//********************************************
	function imprimir()
	{
		echo $this->dia;
		echo $this->mes;
		echo $this->anio;

		echo $this->nodias;
		echo $this->txtmes;
		echo $this->nummes;

	}

}


?>