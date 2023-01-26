<?PHP
//Es necesario tener una conexiÛn activa para usar el control
class carga
{
	var $direccion_archivo;
	var $dirbase;
	var $dirtemp;


	//Constructor de la clase
	function carga ()
	{
				
		//$this->dirbase="/home/wwwarchivos/formatos";
		$this->dirbase="/home/rentaspb/contenedor/formatos";
		//$this->dirbase='/Library/WebServer/Documents/bujalil/scripts/duenios';
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
	
	
	
	//Proceso para la carga del archivo
	function cargar($arch, $archt)
	{
		$result="";
		$arch=$this->CambiaAcentosaTXT($arch);
		
			$this->direccion_archivo=$this->dirbase;
			
			$this->direccion_archivo = $this->direccion_archivo . "/" . $arch;//agregar fecha y hora al final
			
			return @move_uploaded_file($archt, $this->direccion_archivo);

			
			sleep(1);			


		//$tabla="";
		//echo $result;
/*		
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
		*/

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
	function quitar_arhivo($elarch)
	{
	
		return unlink   ($elarch);
			
				
		
	}
	
	function CambiaAcentosaTXT($cadena)
	{
	
		$acentos = array("!","√°","√©",	"√≠","√≥",	"√∫","√Å","√â","√ç","√ì","√ö","√±","√ë");
		$acentosHTML = array("_", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U",  "n", "N");
		
		$cadena = str_replace($acentos, $acentosHTML , $cadena, $enontro);
		
		
		$acentos = array("·", "¡", "È", "…", "Ì", "Õ", "Û", "”", "˙", "⁄", "¸", "‹", "ø", "Ò", "—");
		$acentosHTML = array("a", "A", "e", "E", "i", "I", "o", "O", "u", "U", "u", "U", "_", "n", "N");
		
	
		$A =str_replace($acentos, $acentosHTML , $cadena, $enontro);
	
		return  $A;
		
	}	



}





?>
