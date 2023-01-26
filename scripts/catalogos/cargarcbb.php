<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$direccion_archivo ="";
$dirbase="/home/wwwarchivos";
//$dirbase="/Library/WebServer/Documents/bujalil/scripts/cbb";
$dirtemp=$dirbase . "/dirtemp";


$accion = @$_POST["accion"];
$id=@$_POST["id"];
$contribuyente=@$_POST['contribuyente'];
$rfc=@$_POST['rfc'];
$regimen=@$_POST['regimen'];
$domicilio=@$_POST['domicilio'];
$nosicofi=@$_POST['nosicofi'];
$fechaacbb=@$_POST['fechaacbb'];
$serie=@$_POST['serie'];
$folioi=@$_POST['folioi'];
$foliof=@$_POST['foliof'];
$cbb="";
$logo="";
$secuencia=@$_POST['secuencia'];




//echo "Accion=" . $accion . " <br>Listaa= " . $listaa . " <br>Archivo= " . $archvo . " <br>listaarhivos= " . $larchivos . " <br> " ;
//echo basename( $_FILES['logo']['name']) . "<br>";
//echo $_FILES['logo']['tmp_name'];
//echo basename( $_FILES['cbb']['name']) . "<br>";
//echo $_FILES['cbb']['tmp_name'];

//if($accion=="1")
//{//subir arcchivo
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='folios.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta= $row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}
	if ($priv[0]!='1')
	{
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}



	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}
	

/*
		if (!file_exists($dirbase . "/cbbf" )) 
		{
			chdir ($dirbase);
			//echo getcwd() . "<br>";
			mkdir($dirbase . "/cbbf");
		}
		$direccion_archivo=$dirbase . "/cbbf";
*/
		if (!file_exists($dirbase . "/cbb" )) 
		{
			chdir ($dirbase);
			//echo getcwd() . "<br>";
			mkdir($dirbase . "/cbb");
		}
		$direccion_archivo=$dirbase . "/cbb";		

		//echo "<br> $accion";
		if($accion == 0 || $accion == 3)
		{
			$arch=CambiaAcentosaTXT(basename( @$_FILES['cbb']['name']));	
			$archivo_destino = $direccion_archivo . "/" . $arch;
			$cbb = $archivo_destino;
			//echo "<br>mover de " . $_FILES['cbb']['tmp_name'] . "  a $archivo_destino";
			if(@copy($_FILES['cbb']['tmp_name'], $archivo_destino)) 
			{
				unlink ($_FILES['cbb']['tmp_name']);	

			}
			//echo " <br>";
			$imagen = imagecreatefrompng($cbb);
			$cbbjpg= substr($cbb,0,-3) . "jpg";
			imagejpeg($imagen,$cbbjpg,100);
			
			sleep(1);	
		
			
			
			$arch=CambiaAcentosaTXT(basename( @$_FILES['logo']['name']));	
			$archivo_destino = $direccion_archivo . "/" . $arch;
			$logo = $archivo_destino;
			//echo "mover de $archt a $archivo_destino";
			if(@copy($_FILES['logo']['tmp_name'], $archivo_destino)) 
			{
				unlink ($_FILES['logo']['tmp_name']);

			}
			//echo " <br>";
			sleep(1);
		}
	$sql="";
	//echo $accion;
	switch($accion)
	{
	case "0": // Agrego

		$vigencia = (substr($fechaacbb,0,4) + 1) . substr($fechaacbb,4);

		$sql="insert into folioscbb (nosicofi,fechaacbb,seriecbb,folioicbb,foliofcbb,vigenciacbb,secuenciacbb,contribuyentecbb,rfccbb,regimen,direccioncbb,cbb,logo,cbbjpg) values ($nosicofi,'$fechaacbb','$serie',$folioi,$foliof,'$vigencia',$secuencia,'$contribuyente','$rfc','$regimen','$domicilio','$cbb','$logo','$cbbjpg')";
		//echo "<br>Agrego";
		$serie="";
		$tercero="";
		$folios="";
		break;

	case "1": //Borro
		
		$sql = "select  * from folioscbb where idfoliocbb = $id";
		$datos=mysql_query($sql);
		$row = mysql_fetch_array($datos);
		unlink ($row["cbb"]);
		unlink ($row["logo"]);
		unlink ($row["cbbjpg"]);

		 echo $sql="delete from folioscbb where idfoliocbb=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$vigencia = (substr($fechaacbb,0,4) + 1) . substr($fechaacbb,4);

		$sql="update folioscbb set nosicofi=$nosicofi, fechaacbb='$fechaacbb', seriecbb='$serie',folioicbb=$folioi, foliofcbb=$foliof, vigenciacbb='$vigencia',secuenciacbb=$secuencia,contribuyentecbb='$contribuyente',rfccbb='$rfc',regimen='$regimen',direccioncbb='$domicilio',cbb='$cbb',logo='$logo', cbbjpg='$cbbjpg' where idfoliocbb=$id";
		///echo "<br>Actualizo";
		$serie="";
		$folios="";
		$secuencia="";

	}
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
					
					






	$sql="select * from folioscbb";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>Id</th><th>Nombre</th><th>RFC</th><th>Serie</th><th>Folio. I.</th><th>Folio. F.</th><th>Secuencia</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
	
		$html = "<tr><td>" . $row["idfoliocbb"] . "</td><td>" . $row["contribuyentecbb"] . "</td><td>" . $row["rfccbb"] . "</td><td>" . $row["seriecbb"] . "</td><td>" . $row["folioicbb"] . "</td><td>" . $row["foliofcbb"] . "</td><td>" . $row["secuenciacbb"] . "</td><td>";
		$html .= "<form action=\"cargarcbb.php\" name=\"formulario\" method=\"post\" target=\"listacbb\" ><input type='hidden' name='id' value='" .  $row["idfoliocbb"]  . "'/><input type='hidden' name='accion' value='1'/><input type=\"submit\" value=\"Borrar\" onClick=\"if(!confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){return false; }\" $txtborrar></form>";
		$html .= "<input type=\"button\" value=\"Editar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idfoliocbb"]  . "' )\" $txteditar>";
		//echo "<input type=\"hidden\" name=\"id\" value=\"" . $row["idmodulo"] . "\">";
		$html .= "</td></tr>";
		echo CambiaAcentosaHTML($html);
	}
	echo "</table></div>";					
					
		
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

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

?>