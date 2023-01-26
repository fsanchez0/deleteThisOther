<?php
include "lxml3.php";

//header("Content-type: text/xml");
//$dir="ruta/de/la/carpeta/a/explorar";

$prueba = new xmlcfd_cfdi;



$lista="";
$renglones="";
$funcion="";
//$dir="/Library/WebServer/Documents/pruebas/cfdi/";
$dir="/home/wwwarchivos/cfdi/";
//$dir="Users/luisantoniosolisschroeder/Desktop/bujalil/cfdi/";
$dr=@opendir($dir);
if(!$dr)
{
	echo "<error/>";
	exit;
} 
else 
{
	//echo "<exploracion>";
	// recorremos todos los elementos de la carpeta
	$i=-1;
	while (($archivo = readdir($dr)) !== false) 
	{
		// comprobamos que sean archivos y no otras carpetas
		//if(filetype($dir . $archivo)!="dir")
		//{
			$tam=round(filesize($dir . $archivo)/1024,2);
			//echo "<br><br><font color='blue'>nombre='$archivo' tam='$tam'</font><br>";
			if(substr($archivo,-3)=="xml")
			{
				$i++;
				//$prueba->leerXML("cfdi/" . $archivo);
				//echo "<font color='blue'>nombre='$archivo' tam='$tam'</font><br>";
				$renglones .= "<tr><td>$archivo</td><td><div id='d$i'></div></td></tr>\n";
				
				$funcion .=<<<f

function cargarSeccion$i(root,loc, param)
{
	var cont;
	var a;
	cont = document.getElementById(loc);
	ajax$i=nuevoAjax();
	ajax$i.open("GET", root + "?"+param ,true);
	ajax$i.onreadystatechange=function() {
		if (ajax$i.readyState==4) {
  			cont.innerHTML = ajax$i.responseText;
  			a=cont.innerHTML;
		}
	}
	ajax$i.send(null);
	
	if(a=="OK")
		{
			document.getElementById(id).innerHTML =  "<font color='gren'>OK</font>";
		
		}
		else
		{
			document.getElementById(id).innerHTML =  "<font color='red'>" + a + " </font>";
	
		}
}				

				
f;
				
				$lista .="$archivo|";
			}
			else
			{
				//echo "<font color='green'>nombre='$archivo' tam='$tam'</font><br>";
			}
			
		//}
	}
	echo "</exploracion>";
	closedir($dr);
}

echo <<<html1

<html>
<head>

</head>
<script language="javascript" src="../ajax.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
$funcion

function cargarSeccion(root,loc, param)
{
	var cont;
	var a;
	cont = document.getElementById(loc);
	ajax=nuevoAjax();
	//alert(root + "?"+param )
	ajax.open("GET", root + "?"+param ,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
  			cont.innerHTML = ajax.responseText;
  			a=cont.innerHTML;
		}
	}
	ajax.send(null);
	return a;
}


function recorrer()
{
	var lista='$lista';
	
	l=lista.split("|");
	
	for(i=0;i<l.length;i++)
	{
		id = "d" + i;

		document.getElementById(id).innerHTML = "<font color='blue'>Procesando...</font>";
		//alert("r=cargarSeccion" + i + "('insertar.php','" + id + "','archivo='+l[" + i + "]);");
		eval("r=cargarSeccion" + i + "('insertar.php','" + id + "','archivo='+l[" + i + "]);");
		
		//if(confirm("Detener"))
		//{
		//		break;
		//}
	
	}
	
}

</script>
<body>
<input type="button" value = "Comenzar" onClick="recorrer()">
<table border ="1">
$renglones
</table>


</body>
</html>
html1;



?>