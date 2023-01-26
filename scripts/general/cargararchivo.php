<?PHP
//Es necesario tener una conexi�n activa para usar el control
class carga
{
	var $direccion_archivo;
	var $dirbase;
	var $dirtemp;
	var $resultado;
	var $archivo_destino;


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

		
		if (file_exists($this->dirbase . "/arch" . $tidt)) 
		{
			//echo $this->dirbase . "/arch" . $tidt;
			if(!file_exists($this->dirbase . "/arch" . $tidt . '/exp' . $sidts))
			{
				$this->dirbase . "/arch" . $tidt . '/exp' . $sidts;
				mkdir($this->dirbase . "/arch" . $tidt . '/exp' . $sidts);
			}
		
		}
		else
		{
			chdir ($this->dirbase);
			//echo getcwd() . "<br>";
			mkdir($this->dirbase . "/arch" . $tidt);
			mkdir($this->dirbase . "/arch" . $tidt . '/exp' . $sidts);
		
		}
		$this->direccion_archivo=$this->dirbase . "/arch" . $tidt . '/exp' . $sidts;

		
	} 
	
	
	//Proceso para la carga del archivo
	function cargar($arch, $archt,$tempd,$lf,$idt, $idst)
	{
		//$idt id del arhivo
		//$idst id del expediente
		//$lf lista de archivos
		//$tempd si va al directorio temporal
		
	
		$result="";
		$arch=$this->CambiaAcentosaTXT($arch);
		
		if($tempd)
		{
			$this->direccion_archivo=$this->dirtemp;
			
			$this->archivo_destino = $this->direccion_archivo . "/" . $arch;
			
			$this->resultado = @move_uploaded_file($archt, $this->archivo_destino);
			
			sleep(1);			

		}
		else
		{
		
			$this->verificadirectorio ($idt, $idst);
						
			$this->archivo_destino = $this->direccion_archivo . "/" . $arch;
			//echo "mover de $archt a $this->archivo_destino";
			$this->resultado = @copy($archt, $this->archivo_destino);
			
			//echo " <br>";
			sleep(1);			
			
			
			
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
