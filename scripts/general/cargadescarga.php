<?PHP
//Es necesario tener una conexi�n activa para usar el control
class carga
{
	var $direccion_archivo;
	var $dirbase;
	var $dirtemp;


	//Constructor de la clase
	function carga ()
	{
		//$this->dirbase="/srv/wwwarchivos";
		//$this->dirbase="/home/wwwarchivos";
		$this->dirbase="/home/rentaspb/contenedor";
		$this->dirtemp=$this->dirbase . "/dirtemp";
		//chdir ($this->dirbase);
		set_time_limit(0);
		
	}
		
	//verifica si hay valor en el nombre del archivo
	function verificadato($f)
	{
		if (!isset($f) || empty($f)) 
		{
		  die("Debe de selecionar un archivo a descargar.");
		}

		// Toma el nombre real.
		// remueve todo tipo de estructura de archivos para evitar posibles intrucinoes
		$this->nombre_archivo = basename($f);
	}
	
	// verifica si existe el directorio y los crea si es necesario (Tidt = T + el id de la tarea, Sidts= S + id del seguimiento: tidt)	
	function verificadirectorio ($tidt, $sidts) 
	{

		
		if (file_exists($this->dirbase . "/t" . $tidt)) 
		{
			//echo $this->dirbase . "/T" . $tidt;
			if(!file_exists($this->dirbase . "/t" . $tidt . '/s' . $sidts))
			{
				$this->dirbase . "/t" . $tidt . '/s' . $sidts;
				mkdir($this->dirbase . "/t" . $tidt . '/s' . $sidts);
			}
		
		}
		else
		{
			chdir ($this->dirbase);
			//echo getcwd() . "<br>";
			mkdir($this->dirbase . "/t" . $tidt);
			mkdir($this->dirbase . "/t" . $tidt . '/s' . $sidts);
		
		}
		$this->direccion_archivo=$this->dirbase . "/t" . $tidt . '/s' . $sidts;

		
	} 
	
	
	//Proceso para la carga del archivo
	function cargar($arch, $archt,$tempd,$lf,$idt, $idst)
	{
		$result="";
		$arch=$this->CambiaAcentosaTXT($arch);
		
		if($tempd)
		{
			$this->direccion_archivo=$this->dirtemp;
			
			$archivo_destino = $this->direccion_archivo . "/" . $arch;
			
			if(@move_uploaded_file($archt, $archivo_destino)) 
			{
				if($lf!="")
				{
					$result = $lf . "|" . $arch;			
				}
				else
				{
					$result =  $arch;
			
				}
				
	
			}
			
			sleep(1);			

		}
		else
		{
		
			$this->verificadirectorio ($idt, $idst);
						
			$archivo_destino = $this->direccion_archivo . "/" . $arch;
			//echo "mover de $archt a $archivo_destino";
			if(@copy($archt, $archivo_destino)) 
			{
				unlink ($archt);
				if($lf!="")
				{
					$result = $lf . "|" . $arch;			
				}
				else
				{
					$result =  $arch;
			
				}
				//echo "  OK <br>";
	
			}
			//echo " <br>";
			sleep(1);			
			
			
			
		}

		$tabla="";
		//echo $result;
		if($tempd)
		{
			$lista = split("[|]",$result);
			$tabla="<table border=\"1\" style=\"border-collapse: collapse;\">";
			
			foreach  ($lista as $archi)
			{
					
				$tabla .= "<tr><td>$archi</td><td><a href=\"cargar.php?archivo=$archi&larchivos=$result\" target=\"c_archivos\">X</a></tr>";
					
				
			}
			$tabla .="</table>";
		

echo <<<a
<script language="javascript" type="text/javascript">

	window.top.window.actualizaarchivos('$result','listaa');
	

</script> 

<center>$tabla</center> 
a;
		}

	}
	

	//verifica si hay valor en el nombre del archivo
	function colocar_arhivos($lf,$idt0, $idst0)
	{
		
		$lista = split("[|]",$lf);
		
		
		foreach ($lista as $arch)
		{

			$this->cargar($arch, $this->dirtemp . "/" . $arch, false,"",$idt0, $idst0);
			//unlink   ($this->dirtemp . "/" . $arch);
		
		
		}
		
	}
	
	//Quitar archivo del temp
	function quitar_arhivo($lf,$elarch)
	{
		
		$lista = split("[|]",$lf);
		$rlista="";
		
		foreach ($lista as $arch)
		{
			
			if($arch!=$elarch)
			{
				if($rlista=="")
				{
					$rlista=$arch;	
				
				}
				else
				{
					$rlista .= "|" . $arch;
				}
			}
			else
			{
				unlink   ($this->dirtemp . "/" . $arch);
			
			}
		}
		$tabla="";
		if($rlista!="")
		{
			$lista = split("[|]",$rlista);
			$tabla="<table border=\"1\" style=\"border-collapse: collapse;\">";
					
			foreach ($lista as $archi)
			{
							
				$tabla .= "<tr><td>$archi</td><td><a href=\"cargar.php?accion=2&archivo=$archi&larchivos=$rlista\" target=\"c_archivos\">X</a></tr>";
							
						
			}
			$tabla .="</table>";
		
		}
		echo $salida =  "<script language=\"javascript\" type=\"text/javascript\">window.top.window.actualizaarchivos('$rlista','listaa');</script> <center>$tabla</center> ";

		
		
		
	}
	
	function CambiaAcentosaTXT($cadena)
	{
	
		$acentos = array("!","á","é",	"í","ó",	"ú","Á","É","Í","Ó","Ú","ñ","Ñ");
		$acentosHTML = array("_", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U",  "n", "N");
		
		$cadena = str_replace($acentos, $acentosHTML , $cadena, $enontro);
		
		
		$acentos = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�");
		$acentosHTML = array("a", "A", "e", "E", "i", "I", "o", "O", "u", "U", "u", "U", "_", "n", "N");
		
	
		$A =str_replace($acentos, $acentosHTML , $cadena, $enontro);
	
		return  $A;
		
	}	



}





?>
