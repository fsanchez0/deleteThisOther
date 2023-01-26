<?php
include '../general/funcionesformato.php';
include '../general/sessionclase.php';
include '../general/ftpclass.php';
include_once('../general/conexion.php');
include ("../cfdi/cfdiclassn.php");
require('../fpdf.php');




$id=@$_GET["id"]; //para el Id de la consulta que se requiere hacer: de arrendamiento idhistoria, de libre idfolio
$filtro=@$_GET["filtro"]; //para la especificacion del tipo re recibo inmueble=null, libre=0;
$datosl=@$_GET["datosl"]; //para recibir todos los datos para la factura segun el layaut que biene de la facturalibre
$anio=@$_GET["anio"];
$mes=@$_GET["mes"];

$cfd =  New cfdi32class;
$ftp= New ftpcfdi;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='verfacturaslibres.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];		
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



	$m1='';
	$m2='';
	$m3='';
	$m4='';
	$m5='';
	$m6='';
	$m7='';
	$m8='';
	$m9='';
	$m10='';
	$m11='';
	$m12='';
	
	if(!$anio!='')
	{
		$anio=date('Y');
	}
	if(!$mes!='')
	{
		$mes=date('m');
	}
	switch($mes)
	{
	case 1:
		$m1=' selected ';
		break;
	case 2:
		$m2=' selected ';
		break;
	case 3:	
		$m3=' selected ';
		break;
	case 4:
		$m4=' selected ';
		break;
	case 5:
		$m5=' selected ';
		break;
	case 6:
		$m6=' selected ';
		break;
	case 7:
		$m7=' selected ';
		break;
	case 8:
		$m8=' selected ';
		break;
	case 9:
		$m9=' selected ';
		break;
	case 10:
		$m10=' selected ';
		break;
	case 11:		
		$m11=' selected ';
		break;
	case 12:
		$m12= ' selected ';
	}



	$htmlr = "<center><h1>Lista de facturas libres</h1>";
	
	$htmlr .="<form>Mes <select name='mes' onChange=\"cargarSeccion_new('$dirscript','contenido','mes=' + mes.value + '&anio=' + anio.value )\"><option value='01' $m1>Enero</option>";	
	$htmlr .="<option value='02' $m2>Febrero</option>";	
	$htmlr .="<option value='03' $m3>Marzo</option>";	
	$htmlr .="<option value='04' $m4>Abril</option>";
	$htmlr .="<option value='05' $m5>Mayo</option>";		
	$htmlr .="<option value='06' $m6>Junio</option>";	
	$htmlr .="<option value='07' $m7>Julio</option>";	
	$htmlr .="<option value='08' $m8>Agosto</option>";	
	$htmlr .="<option value='09' $m9>Septiembre</option>";	
	$htmlr .="<option value='10' $m10>Octubre</option>";	
	$htmlr .="<option value='11' $m11>Noviembre</option>";	
	$htmlr .="<option value='12' $m12>Diciembre</option>";	
	$htmlr .="</select>&nbsp;&nbsp;";	
	$htmlr .="A&ntilde;o: <input type='text' name='anio' value='$anio' onChange=\"cargarSeccion_new('$dirscript','contenido','mes=' + mes.value + '&anio=' + anio.value )\"></center></form>";
		
	
	

	$htmlr .=" <table border=\"1\"><tr><th>ID</th><th>Fecha</th><th>Factura</th><th>Concepto</th><th>Tipo</th><th>Total</th><th>Acciones</th></tr>";
	

	//$sql="select * from cfdilibre";
	$sql="select * from cfdilibre where month(fechal)=$mes and year(fechal)=$anio";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		
		$tipodecfdi=$row["notacreditol"];
		if($tipodecfdi == "1")
		{
			$tipodecfdi="Egreso";
		}
		else
		{
			$tipodecfdi="Ingreso";
		}
		
		
		$htmlr .= "<tr><td>" . $row["idcfdilibre"] . "</td><td>" . $row["fechal"] . "</td><td>" . $row["seriel"] . $row["foliol"] . "</td><td>" . $row["conceptol"] . "</td><td>" . number_format($row["totall"],2,".",",") . "</td><td>" . $tipodecfdi. "</td>";
		
		//verificar la factura
		$sqlcfdi="select * from flibrecfdi fl, facturacfdi f where fl.idfacturacfdi=f.idfacturacfdi and fl.idcfdilibre = " . $row["idcfdilibre"];
		$operacionr = mysql_query($sqlcfdi);
		$r = mysql_fetch_array($operacionr);
		
			$accionbotonfact="";
			$ligapdf="";
			$ligaxml="";
			$ligapdfn="";
		
		if($r["txtok"]==0)
		{
			//$edocuenta="window.open( 'scripts/reporte2.php?id=" . $row["idfolios"] . "&filtro=1');this.disabled=true";
			//$accionbotonfact="<br><input type =\"button\" value=\"Facturar\" onClick=\"$edocuenta\" disabled='true' />";
		}
//		else
//		{
				//if($row["pdfok"]==0 and $accionbotonfact=="" )
				if($r["pdfok"]==0 and $accionbotonfact=="" )
				{
					$ligapdf="<br><input type=\"button\" value='Recuperar PDF' onClick=\"document.getElementById('cfdi" . $row["idcfdilibre"] . "').innerHTML='Recuperando...';cargarSeccion('scripts/recuperar.php','cfdi" . $row["idcfdilibre"] . "', 'id=" . $row["idcfdilibre"] . "&filtro=1');\">";

				
				}
				else
				{
					//$ligapdf="<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivopdf"] . "\"  target=\"_blank\" >" .  $row["archivopdf"] . "\n</a>";
					//<img src='imagenes/PDF.png' width='50'>
					$ligapdf="<a href=\"scripts/general/descargarcfdi.php?f=" .  $r["archivopdf"] . "\"  target=\"_blank\" > <img src='imagenes/PDF.png' width='30'></a>";
				}

				//if($row["xmlok"]==0 and $accionbotonfact=="")
				if($r["xmlok"]==0 and $accionbotonfact=="")
				{

					$ligaxml="<input type=\"button\" value='Recuperar XML' onClick=\"document.getElementById('cfdi" . $row["idcfdilibre"] . "').innerHTML='Recuperando...';cargarSeccion('scripts/recuperar.php','cfdi" . $row["idcfdilibre"] . "', 'id=" . $row["idcfdilibre"] . "&filtro=1');\">";

				}
				else
				{
					//$ligaxml="<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivoxml"] . "\"  target=\"_blank\" >" .  $row["archivoxml"] . "\n</a>";
					$ligaxml="<a href=\"scripts/general/descargarcfdi.php?f=" .  $r["archivoxml"] . "\"  target=\"_blank\" ><img src='imagenes/XML.png' width='35'></a>";
				}
			
				//if($row["pdfnok"]==0 and $accionbotonfact=="")
				if($r["pdfn"]==0 and $accionbotonfact=="")
				{
					$edocuenta="";
					$ligapdfn="<br>PDF PyB";
					$ligapdfn="";
				}			
				else
				{
					$ligapdfn="<br>PDF PyB";
					$ligapdfn="";
				}
			
//			}	
		
		
		$htmlr .= "<td><div id='cfdi" . $row["idcfdilibre"] . "'> $ligapdf  $ligaxml  $ligapdfn </div></td></tr>";
		
		
		//preparar los botones o ligas

		
	}
	$htmlr .="</table>";





	echo $htmlr;

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}

?>
