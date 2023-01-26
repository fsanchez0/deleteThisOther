<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


//Modulo

if(array_key_exists("filtro",$_POST)){
	$filtro = @$_POST["filtro"];
	$paso = @$_POST["paso"];
	$idduenio = @$_POST["id"];
	
	$periodo = @$_POST["periodo"]; //fecha del mes que se solicita
}else{

	$filtro = @$_GET["filtro"];
	$paso = @$_GET["paso"];
	$idduenio = @$_GET["id"];

	$periodo = @$_GET["periodo"]; //fecha del mes que se solicita
}

if(!$periodo)
{
	$fechamenos = date('Y-m-') . "01";
	
}
else
{
	$fechamenos = substr($periodo,0,8) . "01";
}
//echo $fechamenos = "2014-12-01";

$fechahoy = date('Y-m-d');

$miwhere="";

$titulo="";
$reporte="";

$color = "#009B7B";



$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql="select * from submodulo where archivo ='reportedetallado.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta= $row['ruta'];
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=@split("\*",$priv);

	}


	if($filtro!=1)
	{//anteriores
		//$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		//$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and e.idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	

	$sumatotal="";
	$verificalista="";
	switch($paso)
	{
	case 1://aplico seleccion ingresos y marco las referncias

	//marcar todas las selecciones en reportado así como las asociaciones escritas en referencia
	
	foreach($_GET as $key => $val)
	{
		if(substr($key,0,1)=="n")
		{
			$ver = @split("[_]",$key);

				
				// c = confirmado
				// cr = confirmado por relacion
				// m = marcado
				// mr = marcado por relación	
			
				//Consulto, 		
				

	
				//obtengo la información requerida del elemento para relacionarlo con 
				//los elementos que también se deben seleccionar
				$sqlm = "select * from edoduenio where idedoduenio = " . $ver[1];
				$operacionm = mysql_query($sqlm);
				$rowm = mysql_fetch_array($operacionm);
				
				$relaciones = $rowm["referencia"];
				$idduenio =$rowm["idduenio"];
				
				$idedoduenio = $rowm["idedoduenio"];
				
				$ver1 = @split("[_]",$relaciones);
				if (count($ver1)>2)
				{//ya fueron marcados antes
					//echo "marcado antes $relaciones<br>";
					if($ver1[2]=="m")
					{
						
					$check = "c_" . $ver[1];
					$marcar =$_GET[$check];
/*					
					$sqlm = "update edoduenio set notaedo= '$val', reportado=$marcar, tocado=1 where idduenio = $idduenio and referencia = '$relaciones' ";
					$operacionm = mysql_query($sqlm);
				
					$sqlm = "update edoduenio set notaedo= '$val', reportado=$marcar, tocado=1 where idduenio = $idduenio and referencia = '$relaciones" . "r" . "' ";
					$operacionm = mysql_query($sqlm);				
				
					$sqlm = "update edoduenio set notaedo= '$val' reportado=$marcar , tocado=1 where idduenio <> $idduenio and referencia = '$relaciones" . "r" . "'";
					$operacionm = mysql_query($sqlm);							
*/

					$sqlm = "update edoduenio set  reportado=$marcar, tocado=1 where idduenio = $idduenio and referencia = '$relaciones' ";
					$operacionm = mysql_query($sqlm);
				
					$sqlm = "update edoduenio set  reportado=$marcar, tocado=1 where idduenio = $idduenio and referencia = '$relaciones" . "r" . "' ";
					$operacionm = mysql_query($sqlm);				
				
					$sqlm = "update edoduenio set  reportado=$marcar , tocado=1 where idduenio <> $idduenio and referencia = '$relaciones" . "r" . "'";
					$operacionm = mysql_query($sqlm);
			
					$sqlm = "update edoduenio set notaedo= '$val' where idedoduenio = $idedoduenio";
					$operacionm = mysql_query($sqlm);						
						
					}
					
					
				}
				else
				{
					
					$check = "c_" . $ver[1];
					$marcar =$_GET[$check];
/*					
					$sqlm = "update edoduenio set notaedo= '$val', reportado=$marcar, referencia = '" . $relaciones . "_m', tocado=1 where idduenio = $idduenio and referencia = '$relaciones' and importe>0";
					$operacionm = mysql_query($sqlm);

					$sqlm = "update edoduenio set notaedo= '$val', reportado=$marcar, referencia = '" . $relaciones . "_mr', tocado=1 where idduenio = $idduenio and referencia = '$relaciones' and importe<0";
					$operacionm = mysql_query($sqlm);
				
					$sqlm = "update edoduenio set notaedo= '$val', reportado=$marcar, referencia = '" . $relaciones . "_mr', tocado=1 where idduenio <> $idduenio and referencia = '$relaciones'";
					$operacionm = mysql_query($sqlm);
*/					
					$sqlm = "update edoduenio set  reportado=$marcar, referencia = '" . $relaciones . "_m', tocado=1 where idduenio = $idduenio and referencia = '$relaciones' and importe>0";
					$operacionm = mysql_query($sqlm);

					$sqlm = "update edoduenio set  reportado=$marcar, referencia = '" . $relaciones . "_mr', tocado=1 where idduenio = $idduenio and referencia = '$relaciones' and importe<0";
					$operacionm = mysql_query($sqlm);
				
					$sqlm = "update edoduenio set  reportado=$marcar, referencia = '" . $relaciones . "_mr', tocado=1 where idduenio <> $idduenio and referencia = '$relaciones'";
					$operacionm = mysql_query($sqlm);	
					
					$sqlm = "update edoduenio set notaedo= '$val' where idedoduenio = $idedoduenio";
					$operacionm = mysql_query($sqlm);					
									
				}
		
		}
	
	}



	//$sql = "select * from edoduenio e, inmueble i, contrato c, inquilino iq where e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	//$sql .= $miwhere . " order by idduenio, e.idcontrato,idedoduenio";
	
	
	
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<0 and fechaedo<'$fechamenos'  and isnull(fechagen)=true  and e.idtipocobro>0  and d.idduenio=$idduenio and traspaso = 0  ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato ,idtipocobro,idedoduenio";		

	
	$operacion = mysql_query($sql);

	$controlesjava="";
	$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	$reporte="";
	$total=0;
	$duenio="";
	$idtipocobro="";
	$clase = "";
	
	echo "<form>";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idcontrato"] != $idcontrato)
		{
			if($idcontrato !="")
			{
				$reporte .= "</table><br><br>";
			}
			$idcontrato = $row["idcontrato"];
			//$reporte .= "</table><br><br>";
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Origen calculo</th><th>Importe</th><th>Reportar</th></tr>";
	    	//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
		}
	
		if($row["idtipocobro"] != $idtipocobro)
		{
			if($idtipocobro !="")
			{
				//$reporte .= "</table><td></tr>";
				if($clase == "")
				{
					$clase = " style =\"background-color:$color;\"";
				}
				else
				{
					$clase = "";
				}
			}
			$verificalista .="*";
			$idtipocobro = $row["idtipocobro"];
			
			//$reporte .= "<tr><td><table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	
		}		
	
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"] ;//+ $row["iva"];
		//$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= " disabled "; 
					//$mensaje=" Relacionado con otro estado de cuenta ";
					$mensaje=" Relacionado";
				}
				else
				{
					$deshabilitar= " ";
				}	
			}
		}
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= " disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= " disabled "; 
						//$mensaje=" Relacionado con otro estado de cuenta ";
						$mensaje=" Relacionado";
					}
				}	
			}		
		}	
			
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		$mostrar =0;
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
				$mostrar = 1;
			}

		}
		else
		{
			$marcarcheck = " checked ";
			$mostrar = 1;
		}
		
		$habilitado = "";
		
		$sqlco = "select * from edoduenio where condonado = " . $row["idedoduenio"];
		$operacionco = mysql_query($sqlco);
		if (mysql_num_rows($operacionco) > 0)
		{
		
			$habilitado = " disabled ";
		
		}
		
		if($mostrar == 1)
		{
		$total +=$suma;
		$boton = "<input type='button' value='Condonar' onclick=\"window.open('$ruta/condonar_notacredito.php?accion=0&id=" . $row["idedoduenio"] . "','_blank','width=300,height=200'); \" $habilitado />";;

		
//*******para el calculo del importe y %		
$origencalculo = obtenerorigen($row["idhistoria"],$row["referencia"],$idduenio,$row["idinmueble"],$row["idedoduenio"]);
//*******		
		
		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>---" . $row["notaedo"]  . "</textarea></td>$origencalculo<td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td>$origencalculo<td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	//str_replace("%"," porcentaje ",  $row["notaedo"]) 
	
		$reporte .= "<tr $clase><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		}
		
	}
//manuales
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<0   and isnull(fechagen)=true  and e.idtipocobro=0  and d.idduenio=$idduenio and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	//echo $sql .= $miwhere . " order by e.idduenio, e.idcontrato,idtipocobro,idedoduenio";	
	//$sql .=  " order by e.idduenio, e.idcontrato ,idtipocobro,idedoduenio";		
	$sql .=  " order by e.idduenio, e.idcontrato ,idtipocobro,idedoduenio";		
	
	
	$operacion = mysql_query($sql);

	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idcontrato"] != $idcontrato)
		{
			if($idcontrato !="")
			{
				$reporte .= "</table><br><br>";
			}
			$idcontrato = $row["idcontrato"];
			//$reporte .= "</table><br><br>";
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	    	//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
		}
	
		if($row["idtipocobro"] != $idtipocobro)
		{
			if($idtipocobro !="")
			{
				//$reporte .= "</table><td></tr>";
				if($clase == "")
				{
					$clase = " style =\"background-color:$color;\"";
				}
				else
				{
					$clase = "";
				}
			}
			$verificalista .="*";
			$idtipocobro = $row["idtipocobro"];
			
			//$reporte .= "<tr><td><table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	
		}		
	
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"] ;//+ $row["iva"];
		//$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= " disabled "; 
					//$mensaje=" Relacionado con otro estado de cuenta ";
					$mensaje=" Relacionado";
				}
				else
				{
					$deshabilitar= " ";
				}	
			}
		}
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= " disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= " disabled "; 
						//$mensaje=" Relacionado con otro estado de cuenta ";
						$mensaje=" Relacionado";
					}
				}	
			}		
		}	
			
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		$mostrar =0;
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
				$mostrar = 1;
			}
			if($ver1[0]=="m")
			{
				$marcarcheck = " checked ";
				$mostrar = 1;
			}

		}
		else
		{
			$marcarcheck = " checked ";
			$mostrar = 1;
		}
		
		$habilitado = "";
		
		$sqlco = "select * from edoduenio where condonado = " . $row["idedoduenio"];
		$operacionco = mysql_query($sqlco);
		if (mysql_num_rows($operacionco) > 0)
		{
		
			$habilitado = " disabled ";
		
		}
		
		if($mostrar == 1)
		{
		$total +=$suma;
		$boton = "<input type='button' value='Condonar' onclick=\"window.open('$ruta/condonar_notacredito.php?accion=0&id=" . $row["idedoduenio"] . "','_blank','width=300,height=200'); \" $habilitado />";;

//*******para el calculo del importe y %		
$origencalculo = obtenerorigen($row["idhistoria"],$row["referencia"],$idduenio,$row["idinmueble"],$row["idedoduenio"]);
//*******		
		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td>$origencalculo<td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td>$origencalculo<td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	//str_replace("%"," porcentaje ",  $row["notaedo"]) 
		$reporte .= "<tr $clase><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		}
		
	}




	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and importe<0  and isnull(fechagen)=true and idcontrato = 0  and d.idduenio=$idduenio and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idtipocobro,fechaedo,idedoduenio";		
	
	
	$operacion = mysql_query($sql);

	//$controlesjava="";
	//$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	//$reporte="";
	//$total=0;
	///$duenio="";
	//$idtipocobro="0";
	//$clase = "";
	$header=0;
	echo "<form>";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($header == 0)
		{
	
			//$reporte .= "</table><br><br>";
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			//$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br><table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	    	//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
	    	$header = 1;
		}
		
	
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"] ;//+ $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= " disabled "; 
					//$mensaje=" Relacionado con otro estado de cuenta ";
					$mensaje=" Relacionado";
				}
				else
				{
					$deshabilitar= " ";
				}	
			}
		}
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= " disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= " disabled "; 
						//$mensaje=" Relacionado con otro estado de cuenta ";
						$mensaje=" Relacionado";
					}
				}	
			}		
		}	
			
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		$mostrar =0;
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
				$mostrar = 1;
			}

		}
		else
		{
			$marcarcheck = " checked ";
			$mostrar = 1;
		}
		
		$habilitado = "";
		
		$sqlco = "select * from edoduenio where condonado = " . $row["idedoduenio"];
		$operacionco = mysql_query($sqlco);
		if (mysql_num_rows($operacionco) > 0)
		{
		
			$habilitado = " disabled ";
		
		}
		
	
		$boton = "<input type='button' value='Condonar' onclick=\"window.open('$ruta/condonar_notacredito.php?accion=0&id=" . $row["idedoduenio"] . "','_blank','width=300,height=200'); \" $habilitado />";;

//*******para el calculo del importe y %		
$origencalculo = obtenerorigen($row["idhistoria"],$row["referencia"],$idduenio,$row["idinmueble"],$row["idedoduenio"]);
//*******		
		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td>$origencalculo<td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td>$origencalculo<td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	
	//str_replace("%"," porcentaje ",  $row["notaedo"]) 
		$reporte .= "<tr $clase><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
	
		
	}
	
	
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e where d.idduenio = e.idduenio and e.idinmueble = 0 and importe<0  and isnull(fechagen)=true and idcontrato = 0  and d.idduenio=$idduenio and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idtipocobro,idedoduenio";		
	
	
	$operacion = mysql_query($sql);

	//$controlesjava="";
	//$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	//$reporte="";
	//$total=0;
	///$duenio="";
	//$idtipocobro="0";
	//$clase = "";
	$header=0;
	echo "<form>";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($header == 0)
		{
	
			//$reporte .= "</table><br><br>";
			//$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			//$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			//$reporte .= "$inmueble<br><table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	    	//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
			$reporte .= "<br><table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	    		    	
	    	
	    	$header = 1;
		}
		
	
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"] ;//+ $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= " disabled "; 
					//$mensaje=" Relacionado con otro estado de cuenta ";
					$mensaje=" Relacionado";
				}
				else
				{
					$deshabilitar= " ";
				}	
			}
		}
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= " disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= " disabled "; 
						//$mensaje=" Relacionado con otro estado de cuenta ";
						$mensaje=" Relacionado";
					}
				}	
			}		
		}	
			
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		$mostrar =0;
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
				$mostrar = 1;
			}

		}
		else
		{
			$marcarcheck = " checked ";
			$mostrar = 1;
		}
		
		$habilitado = "";
		
		$sqlco = "select * from edoduenio where condonado = " . $row["idedoduenio"];
		$operacionco = mysql_query($sqlco);
		if (mysql_num_rows($operacionco) > 0)
		{
		
			$habilitado = " disabled ";
		
		}
		
	
		$boton = "<input type='button' value='Condonar' onclick=\"window.open('$ruta/condonar_notacredito.php?accion=0&id=" . $row["idedoduenio"] . "','_blank','width=300,height=200'); \" $habilitado />";;

//*******para el calculo del importe y %		
$origencalculo = obtenerorigen($row["idhistoria"],$row["referencia"],$idduenio,$row["idinmueble"],$row["idedoduenio"]);
//*******		
		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td>$origencalculo<td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])  . "</textarea></td>$origencalculo<td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
		$reporte .= "<tr $clase><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
	
		
	}
	
	
	
	
	
	
	
	
	
	
	


/*
//para los confirmados que no fueron seleccionados
	//$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble   and fechaedo<'$fechamenos' and importe>0  and isnull(fechagen)=true and idcontrato = 0  and d.idduenio=$idduenio ";
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble    and importe<0  and isnull(fechagen)=false and reportado = false  and d.idduenio=$idduenio ";	
 	$sql .= $miwhere . " order by e.idduenio, e.idcontrato, idtipocobro, fechaedo";
		
	
	
	$operacion = mysql_query($sql);

	//$controlesjava="";
	//$sumatotal = "";
	//$verificalista ="";
	//$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	//$reporte="";
	//$total=0;
	//$duenio="";
	//$idtipocobro="";
	//$clase = "";
	$header = 0;
	//echo "<form>";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($header==0)
		{
			
			$verificalista .="*";
			//$idcontrato = $row["idcontrato"];
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$reporte .= "</table><br><br>";
			$clase = "";
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br><table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
			//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
			$header="1";
	
		}
	
			
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"];// + $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado ";
					}
				}	
			}
		}
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}
		
		
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==1)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = " checked ";
		}
		$marcarcheck = " checked ";
		$boton ="";
		if($row["condonado"]>0)
		{
			$boton = "<input type='button' value='Quitar' onclick=\"window.open('$ruta/condonar_notacredito.php?accion=1&id=" . $row["idedoduenio"] . "','_blank','width=300,height=200'); \" />";;
			
		}

				
		
		
		//$controles ="<td><input type=\"text\" name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\"  value=\"" . $row["notaedo"]  . "\" size='40' $deshabilitar></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' value='$suma' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar>$mensaje</td>";
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
		$reporte .= "<tr $clase ><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}

*/




	
	$reporte .="<tr><td colspan='3' align='center'>Total egresos a reportar</td><td colspan='2'><input type='text' size='10' name='total0' id='total0' value='" . number_format($total,2,".","") . "' onClick=\"calculaedoduenio('total0', '$sumatotal' , '$verificalista')\"/></td></tr></table>";
	$reporte = CambiaAcentosaHTML($reporte);
	echo "<center><input type=\"button\" value=\"<<Regresar\" onClick = \"cargarSeccion('$ruta/reportedetallado.php','contenido', '');\"><br>$duenio<br><input type=\"button\" value=\"atras\" onClick = \"cargarSeccion('$ruta/edoduenioseleccion.php','contenido', 'paso=0&filtro=$filtro&periodo=$periodo&id=$idduenio$controlesjava');this.disabled =true\"><input type=\"button\" value=\"Siguiente\" onClick = \"cargarSeccion('$ruta/edoduenioseleccion.php','contenido', 'paso=2&filtro=$filtro&periodo=$periodo&id=$idduenio$controlesjava');this.disabled =true\"></center> $reporte ";
	echo "</form>";
	
	
		break;
	case 2://marco las selecciones negativas y muestro todo lo seleccionado para validar
	
	
	// de la seleccion marco los negativos y así como los asociados
	
	foreach($_GET as $key => $val)
	{
		if(substr($key,0,1)=="n")
		{
			$ver = @split("[_]",$key);

				
				// c = confirmado
				// cr = confirmado por relacion
				// m = marcado
				// mr = marcado por relación	
			
				//Consulto, 		
				

	
				//obtengo la información requerida del elemento para relacionarlo con 
				//los elementos que también se deben seleccionar
				$sqlm = "select * from edoduenio where idedoduenio = " . $ver[1];
				$operacionm = mysql_query($sqlm);
				$rowm = mysql_fetch_array($operacionm);
				
				$relaciones = $rowm["referencia"];
				$idduenio =$rowm["idduenio"];
				
				$idedoduenio = $rowm["idedoduenio"];
				
				$ver1 = @split("[_]",$relaciones);
				if (count($ver1)>2)
				{//ya fueron marcados antes
					if($ver1[2]=="m")
					{
						
					$check = "c_" . $ver[1];
					$marcar =$_GET[$check];
/*					
					$sqlm = "update edoduenio set notaedo= '$val', reportado=$marcar, tocado=1 where idduenio = $idduenio and referencia = '$relaciones'";
					$operacionm = mysql_query($sqlm);
			
					$sqlm = "update edoduenio set notaedo= '$val' reportado=$marcar, tocado=1 where idduenio <> $idduenio and referencia = '$relaciones" . "r" . "'";
					$operacionm = mysql_query($sqlm);							
*/
					$sqlm = "update edoduenio set  reportado=$marcar, tocado=1 where idduenio = $idduenio and referencia = '$relaciones'";
					$operacionm = mysql_query($sqlm);
			
					$sqlm = "update edoduenio set  reportado=$marcar, tocado=1 where idduenio <> $idduenio and referencia = '$relaciones" . "r" . "'";
					$operacionm = mysql_query($sqlm);	
					
					$sqlm = "update edoduenio set notaedo= '$val' where idedoduenio = $idedoduenio";
					$operacionm = mysql_query($sqlm);						
						
					}
					
					
					
				}
				else
				{
					
					$check = "c_" . $ver[1];
					$marcar =$_GET[$check];
					
/*					
					$sqlm = "update edoduenio set notaedo= '$val', reportado=$marcar, referencia = '" . $relaciones . "_m', tocado=1 where idduenio = $idduenio and referencia = '$relaciones'";
					$operacionm = mysql_query($sqlm);
				
				
					$sqlm = "update edoduenio set notaedo= '$val', reportado=$marcar, referencia = '" . $relaciones . "_mr', tocado=1 where idduenio <> $idduenio and referencia = '$relaciones'";
					$operacionm = mysql_query($sqlm);				
*/				
					
					$sqlm = "update edoduenio set  reportado=$marcar, referencia = '" . $relaciones . "_m', tocado=1 where idduenio = $idduenio and referencia = '$relaciones'";
					$operacionm = mysql_query($sqlm);
				
				
					$sqlm = "update edoduenio set  reportado=$marcar, referencia = '" . $relaciones . "_mr', tocado=1 where idduenio <> $idduenio and referencia = '$relaciones'";
					$operacionm = mysql_query($sqlm);
				
					$sqlm = "update edoduenio set notaedo= '$val' where idedoduenio = $idedoduenio";
					$operacionm = mysql_query($sqlm);				
				
				}		
		}
	
	}
	
	
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e where d.idduenio = e.idduenio and importe<>0  and isnull(fechagen)=true  and reportado = 1 and d.idduenio=$idduenio and notacredito = true and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
	
	$operacion = mysql_query($sql);

	$controlesjava="";
	$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	$reporte="";
	$total=0;
	$duenio="";
	$idtipocobro="";
	$clase = "";
	echo "<form>";
	$reporte .= "<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";

	while($row = mysql_fetch_array($operacion))	
	{
	
	
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"] ;//+ $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= "disabled "; 
					$mensaje=" Relacionado  ";
				}
				else
				{
					$deshabilitar= " ";
				}	
			}
		}
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}	
			
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = " checked ";
		}
		
		$habilitado = "";
		
		
		$boton = "<input type='button' value='Quitar' onclick=\"cargarSeccion('$ruta/edonotacredito.php','edonotasc', 'paso=1&filtro=$filtro&id=$idduenio&idedoduenio="  . $row["idedoduenio"] . "'); \" $habilitado />";;

		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='$suma' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controles ="<td>" . $row["notaedo"]  . "</td><td align='center'>$suma </td><td align='center'>$boton $mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
		$reporte .= "<tr $clase><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}

	
	
	
	
	
	$reporte .="<tr><td colspan='2' align='center'>Total notas de credito</td><td colspan='2'><input type='text' size='10' name='total0' id='total0' value='$total' onClick=\"calculaedoduenio('total0', '$sumatotal' , '$verificalista')\"/></td></tr></table>";
	
	
	
		//forma para agregar lo que sera como nota de credito
		
		$reporte0 = "Notas de credito<table border = '1'><tr> <td>Concepto  </td><td><input type='text' name='concepto' id='concepto'> </td> </tr>";
		$reporte0 .= "<tr> <td>Importe  </td><td><input type='text' name='importe' id='importe'> </td> </tr>";
		$reporte0 .= "<tr> <td colspan='2' align='center'><input type='button' value='Agregar' onclick=\" cargarSeccion('$ruta/edonotacredito.php','edonotasc', 'paso=0&filtro=$filtro&id=$idduenio&importe=' + importe.value + '&concepto=' + concepto.value);\" > </td> </tr></table>";
		$reporte0 .= "<div id='edonotasc'>$reporte</div> ";
		
		
	$reporte0 = CambiaAcentosaHTML($reporte0);
		echo "<center><input type=\"button\" value=\"<<Regresar\" onClick = \"cargarSeccion('$ruta/reportedetallado.php','contenido', '');\"><br>$duenio<br><input type=\"button\" value=\"atras\" onClick = \"cargarSeccion('$ruta/edoduenioseleccion.php','contenido', 'paso=1&filtro=$filtro&periodo=$periodo&id=$idduenio');this.disabled =true\"><input type=\"button\" value=\"Siguiente\" onClick = \"cargarSeccion('$ruta/edoduenioseleccion.php','contenido', 'paso=3&filtro=$filtro&periodo=$periodo&id=$idduenio');this.disabled =true\"></center> $reporte0 ";
		echo "</form>";
	
		break;
	case 3:
	
	
	$duenio="";
	//$sql = "select * from edoduenio e, inmueble i, contrato c, inquilino iq where e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and reportado = 1 ";//reportado=true ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	//$sql .= $miwhere . " order by idduenio, e.idcontrato,importe desc,idedoduenio";

	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true  and reportado = 1  and e.idtipocobro>0  and d.idduenio=$idduenio and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";		
	
	
	
	
	$operacion = mysql_query($sql);

	$controlesjava="";
	$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	$reporte="";
	$total=0;
	$total=0;
	$duenio="";
	echo "<form>";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idcontrato"] != $idcontrato)
		{
			if($idcontrato !="")
			{
				$reporte .= "</table><br><br>";
			}
			$idcontrato = $row["idcontrato"];
			//$reporte .= "</table><br><br>";
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	
		}
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"] ;//+ $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= " disabled "; 
					$mensaje=" Relacionado  ";
				}
				else
				{
					$deshabilitar= " disabled ";
				}	
			}
		}	
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}		
		
		$deshabilitar= " disabled ";
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = " checked ";
		}		
		
		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		$deshabilitar = " disabled ";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	//str_replace("%"," porcentaje ",  $row["notaedo"]) 
	
		$reporte .= "<tr><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}
	
	
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true  and reportado = 1  and e.idtipocobro=0  and d.idduenio=$idduenio  and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";		
	
	
	
	
	$operacion = mysql_query($sql);

	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idcontrato"] != $idcontrato)
		{
			if($idcontrato !="")
			{
				$reporte .= "</table><br><br>";
			}
			$idcontrato = $row["idcontrato"];
			//$reporte .= "</table><br><br>";
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	
		}
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"] ;//+ $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= " disabled "; 
					$mensaje=" Relacionado  ";
				}
				else
				{
					$deshabilitar= " disabled ";
				}	
			}
		}	
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}		
		
		$deshabilitar= " disabled ";
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = " checked ";
		}		
		
		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		$deshabilitar = " disabled ";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	//str_replace("%"," porciento ",  $row["notaedo"]) 
	
		$reporte .= "<tr><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}
		
	
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, i.idinmueble as idi from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble  and importe<>0  and isnull(fechagen)=true  and reportado = 1 and idcontrato =0 and d.idduenio=$idduenio and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";		
	
	
	
	
	$operacion = mysql_query($sql);

	//$controlesjava="";
	//$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	//$reporte="";
	//$total=0;
	//$total=0;
	//$duenio="";
	//echo "<form>";
	$header="";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idi"] != $header)
		{
			if($header !="")
			{
				$reporte .= "</table><br><br>";
			}
			$header = $row["idi"];
			//$reporte .= "</table><br><br>";
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			//$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	
		}
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"] ;//+ $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= " disabled "; 
					$mensaje=" Relacionado  ";
				}
				else
				{
					$deshabilitar= " disabled ";
				}	
			}
		}	
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}		
		
		$deshabilitar= " disabled ";
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = " checked ";
		}		
		
		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		$deshabilitar = " disabled ";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	//str_replace("%"," porciento ",  $row["notaedo"]) 
	
		$reporte .= "<tr><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}	
	
	
	
$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, e.idinmueble as idi from duenio d, edoduenio e where d.idduenio = e.idduenio and e.idinmueble = 0  and importe<>0  and isnull(fechagen)=true  and reportado = 1 and idcontrato =0 and d.idduenio=$idduenio and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";		
	
	
	
	
	$operacion = mysql_query($sql);

	//$controlesjava="";
	//$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	//$reporte="";
	//$total=0;
	//$total=0;
	//$duenio="";
	//echo "<form>";
	$header="";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idi"] != $header)
		{
			if($header !="")
			{
				$reporte .= "</table><br><br>";
			}
			$header = $row["idi"];
			//$reporte .= "</table><br><br>";
			//$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			//$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			//$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
			$reporte .= "<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	
		}
	
	
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"] ;//+ $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= " disabled "; 
					$mensaje=" Relacionado  ";
				}
				else
				{
					$deshabilitar= " disabled ";
				}	
			}
		}	
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}		
		
		$deshabilitar= " disabled ";
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = " checked ";
		}		
		
		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		$deshabilitar = " disabled ";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	//str_replace("%"," porciento ",  $row["notaedo"]) 
	
		$reporte .= "<tr><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}	
		
	
	
	
	
	
	
	
	
	
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e where d.idduenio = e.idduenio and importe<>0  and isnull(fechagen)=true  and reportado = 1 and d.idduenio=$idduenio and notacredito = true  and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
	
	$operacion = mysql_query($sql);

	//$controlesjava="";
	$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	//$reporte="";
	//$total=0;
	$duenio="";
	$idtipocobro="";
	$clase = "";
	//echo "<form>";
	//$reporte .= "<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";

	while($row = mysql_fetch_array($operacion))	
	{
	
	
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"];// + $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= "disabled "; 
					$mensaje=" Relacionado  ";
				}
				else
				{
					$deshabilitar= " ";
				}	
			}
		}
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}	
			
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = " checked ";
		}
		
		$habilitado = "";
		
		
		//$boton = "<input type='button' value='Quitar' onclick=\"cargarSeccion('$ruta/edonotacredito.php','edonotasc', 'paso=1&filtro=$filtro&id=$idduenio&idedoduenio="  . $row["idedoduenio"] . "'); \" $habilitado />";;

		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='$suma' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controles ="<td>(Nota de credito) " . $row["notaedo"]  . "</td><td align='center'>$suma </td><td align='center'>$boton $mensaje</td>";
		//$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	
	
	
		$reporte .= "<tr $clase><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}
	
	
	
	
	
	
	
	
	//$reporte .="<tr><td colspan='2' align='center'>Total notas de credito</td><td colspan='2'><input type='text' size='10' name='total0' id='total0' value='$total' onClick=\"calculaedoduenio('total0', '$sumatotal' , '$verificalista')\"/></td></tr></table>";
	
	
	
	
	
//****************************************************	
	//verificar si hay traspaso programado (unico o no) y notificarlo justo antes del total realizando la actualizacion
	//del importe para ser reportado y pagado
	//ojo, también se pone el renglon del traspaso con los textos correspondientes
	
//****************************************************	
	$reporte .="<tr><td colspan='2' align='center'>Importe a pagar         </td><td colspan='2'><input type='text' size='10' name='total0' id='total0' value='" . number_format($total,2,".","") . "' onClick=\"calculaedoduenio('total0', '$sumatotal' , '$verificalista')\"/></td></tr></table>";
	
	
	
	
	
	
	
	
	
	
	

//$sql = "select * from edoduenio e, inmueble i, contrato c, inquilino iq where e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0 and reportado = 0 ";//reportado=true ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	//$sql .= $miwhere . " order by idduenio, e.idcontrato,importe desc,idedoduenio";
	
	//$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true  and reportado = 0 and d.idduenio=$idduenio ";
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true and fechaedo<'$fechamenos'  and reportado = 0  and e.idtipocobro>0  and d.idduenio=$idduenio  and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
	
	
	
	$operacion = mysql_query($sql);

	//$controlesjava="";
	$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	$reporte .="<br><br>";
	$total=0;
	$duenio="";

	//echo "<form>";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idcontrato"] != $idcontrato)
		{
			if($idcontrato !="")
			{
				$reporte .= "</table><br><br>";
			}
			$idcontrato = $row["idcontrato"];
			//$reporte .= "</table><br><br>";
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	
		}
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"];// + $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= " disabled "; 
					$mensaje=" Relacionado ";
				}
				else
				{
					$deshabilitar= " disabled ";
				}	
			}
		}	
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}		
		
		$deshabilitar= " disabled ";
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = "  ";
		}		
		
		$deshabilitar = " disabled ";
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";		
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\"  $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\"  $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	//str_replace("%"," porciento ",  $row["notaedo"]) 
	
		$reporte .= "<tr><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}


	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true   and reportado = 0  and e.idtipocobro=0  and d.idduenio=$idduenio and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
	
	
	
	$operacion = mysql_query($sql);

	//$controlesjava="";
	$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	//$reporte .="<br><br>";
	//$total=0;
	$duenio="";

	//echo "<form>";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idcontrato"] != $idcontrato)
		{
			if($idcontrato !="")
			{
				$reporte .= "</table><br><br>";
			}
			$idcontrato = $row["idcontrato"];
			//$reporte .= "</table><br><br>";
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	
		}
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"];// + $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= " disabled "; 
					$mensaje=" Relacionado ";
				}
				else
				{
					$deshabilitar= " disabled ";
				}	
			}
		}	
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}		
		
		$deshabilitar= " disabled ";
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = "  ";
		}		
		
		$deshabilitar = " disabled ";
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";		
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\"  $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\"  $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	//str_replace("%"," porciento ",  $row["notaedo"]) 
	
		$reporte .= "<tr><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}



	
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i  where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = 0 and  importe<>0  and isnull(fechagen)=true   and reportado = 0 and d.idduenio=$idduenio and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
	
	
	
	$operacion = mysql_query($sql);

	//$controlesjava="";
	$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	//$reporte .="<br><br>";
	//$total=0;
	$duenio="";

	//echo "<form>";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idcontrato"] != $idcontrato)
		{
			if($idcontrato !="")
			{
				$reporte .= "</table><br><br>";
			}
			$idcontrato = $row["idcontrato"];
			//$reporte .= "</table><br><br>";
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	
		}
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"];// + $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= " disabled "; 
					$mensaje=" Relacionado ";
				}
				else
				{
					$deshabilitar= " disabled ";
				}	
			}
		}	
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}		
		
		$deshabilitar= " disabled ";
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = "  ";
		}		
		
		$deshabilitar = " disabled ";
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";		
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\"  $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\"  $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	//str_replace("%"," porciento ",  $row["notaedo"]) 
	
		$reporte .= "<tr><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}
	
	

	
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e  where d.idduenio = e.idduenio and e.idinmueble = 0 and e.idcontrato = 0 and  importe<>0  and isnull(fechagen)=true   and reportado = 0 and d.idduenio=$idduenio and traspaso = 0 ";
	
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
	
	
	
	$operacion = mysql_query($sql);

	//$controlesjava="";
	$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	//$reporte .="<br><br>";
	//$total=0;
	$duenio="";

	//echo "<form>";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idcontrato"] != $idcontrato)
		{
			if($idcontrato !="")
			{
				$reporte .= "</table><br><br>";
			}
			$idcontrato = $row["idcontrato"];
			//$reporte .= "</table><br><br>";
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
	
		}
	
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"];// + $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
					$deshabilitar= " disabled "; 
					$mensaje=" Relacionado ";
				}
				else
				{
					$deshabilitar= " disabled ";
				}	
			}
		}	
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}		
		
		$deshabilitar= " disabled ";
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==true)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = "  ";
		}		
		
		$deshabilitar = " disabled ";
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";		
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\"  $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "'  id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\"  $marcarcheck $deshabilitar onClick=\"verifica('total0');\">$mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	//str_replace("%"," porciento ",  $row["notaedo"]) 
	
		$reporte .= "<tr><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}

	
	
	$reporte .="<tr><td colspan='2' align='center'>Importe no reportado</td><td colspan='2'><input type='text' size='10' name='total' value='" . number_format($total,2,".","") . "' disabled/></td><td></td></tr></table>";
		
	$reporte = CambiaAcentosaHTML($reporte);
//+++++++++++++++++++++++++++++++++++++++++++++	
	//verificacion para impedir su confirmación en caso de que requiera tener traspaso
	
	//verifico si es sujeto de traspaso
	$sqltrs = "select * from traspasodepara where para = $idduenio";
	$operaciontrs = mysql_query($sqltrs);
	
	//si lo es, verifico si ya existe traspaso para este periodo
	$confirmarb = "";
	if(mysql_num_rows($operaciontrs)>0)
	{
		//Si no hay traspaso bloquear botón de confirmación
		
		$sqltrs = "select * from edoduenio where idduenio = $idduenio and isnull(fechagen)=true and traspaso = 1";
		$operaciontrs = mysql_query($sqltrs);
		if(mysql_num_rows($operaciontrs)==0)
		{
			$confirmarb = " disabled ";
		}
	}
//++++++++++++++++++++++++++++++++++++++++++++++	
	echo "<center><input type=\"button\" value=\"<<Regresar\" onClick = \"cargarSeccion('$ruta/reportedetallado.php','contenido', '');\"><br>$duenio<br><input type=\"button\" value=\"atras\" onClick = \"cargarSeccion('$ruta/edoduenioseleccion.php','contenido', 'paso=2&filtro=$filtro&id=$idduenio&periodo=$periodo');this.disabled =true\"><input type=\"button\" value=\"Confirmar\" $confirmarb onClick = \"if(confirm('Esta seguro de confirmar la seleccion?')==true){cargarSeccion_new('$ruta/edoduenioseleccion.php','contenido', 'paso=4&filtro=$filtro&periodo=$periodo&id=$idduenio$controlesjava');this.disabled =true}\"><input type=\"button\" value=\"Imprimir\" onClick = \"window.open('$ruta/reported.php?idduenio=$idduenio&periodo=$periodo','_blank');\"></center> $reporte ";
	
	echo "</form>";
	
	
	
	
	
		break;
	case 4://muestra la opción del estado de duenta para los dueños
	
		
	
		//coloco la fecha de generaión del estado de cuenta para todos los marcados y no marcados
		//el real y el que será para los dueños para generación de facturas.
		
		//falta proceso de factura
	
	foreach($_POST as $key => $val)
	{
		if(substr($key,0,1)=="n")
		{
			$ver = @split("[_]",$key);

				
				// c = confirmado
				// cr = confirmado por relacion
				// m = marcado
				// mr = marcado por relación	
			
				//Consulto, 		
				

	
				//obtengo la información requerida del elemento para relacionarlo con 
				//los elementos que también se deben seleccionar
				$sqlm = "select * from edoduenio where idedoduenio = " . $ver[1];
				$operacionm = mysql_query($sqlm);
				$rowm = mysql_fetch_array($operacionm);
				
				$relaciones = $rowm["referencia"];
				$idduenio =$rowm["idduenio"];
				
				$ver1 = @split("[_]",$relaciones);
				if (count($ver1)>2)
				{//ya fueron marcados antes
					//if($ver1[2]=="m")
					//{
						
					$check = "c_" . $ver[1];
					$marcar =$_POST[$check];
/*					
					$sqlm = "update edoduenio set notaedo= '$val', reportado=$marcar, referencia = '" . $ver1[0] . "_" . $ver1[1] . "_c' where idduenio = $idduenio and referencia = '$relaciones'";
					$operacionm = mysql_query($sqlm);
								
				
					$sqlm = "update edoduenio set notaedo= '$val', reportado=$marcar, referencia = '" . $ver1[0] . "_" . $ver1[1] .  "_cr' where idduenio <> $idduenio and referencia = '$relaciones'";
					$operacionm = mysql_query($sqlm);							
*/						
					
					
					
/*					
					$sqlm = "update edoduenio set fechagen = '$fechahoy',  reportado=$marcar, referencia = '" . $ver1[0] . "_" . $ver1[1] . "_c' where idduenio = $idduenio and referencia = '$relaciones'";
					$operacionm = mysql_query($sqlm);
								
				
					$sqlm = "update edoduenio set fechagen = '$fechahoy',  reportado=$marcar, referencia = '" . $ver1[0] . "_" . $ver1[1] .  "_cr' where idduenio <> $idduenio and referencia = '$relaciones'";
					$operacionm = mysql_query($sqlm);							
*/								
					
					//$check = "c_" . $ver[1];
					//$marcar =$_POST[$check];
					
					if($marcar=='true')
					{
					
						$sqlm = "update edoduenio set fechagen = '$fechahoy',  reportado=$marcar, referencia = '" . $ver1[0] . "_" . $ver1[1] . "_c' where idduenio = $idduenio and referencia = '$relaciones'";
						$operacionm = mysql_query($sqlm);
				
						$sqlm = "update edoduenio set fechagen = '$fechahoy',  reportado=$marcar, referencia = '" . $ver1[0] . "_" . $ver1[1] .  "_cr' where idduenio <> $idduenio and referencia = '$relaciones'";
						$operacionm = mysql_query($sqlm);	
					
					}
					else
					{
						$sqlm = "update edoduenio set fechagen = '$fechahoy',  reportado=$marcar where idduenio = $idduenio and referencia = '$relaciones'";
						$operacionm = mysql_query($sqlm);
				
						$sqlm = "update edoduenio set fechagen = '$fechahoy',  reportado=$marcar where idduenio <> $idduenio and referencia = '$relaciones'";
						$operacionm = mysql_query($sqlm);					
					}
						
				
				
				
				
				
				
						
					//}

					
					
					
				}
				else
				{
					
					$check = "c_" . $ver[1];
					$marcar =$_POST[$check];
					
					if($marcar=='true')
					{
					
						$sqlm = "update edoduenio set fechagen = '$fechahoy',  reportado=$marcar, referencia = '" . $ver1[0] . "_" . $ver1[1] . "_c' where idduenio = $idduenio and referencia = '$relaciones'";
						$operacionm = mysql_query($sqlm);
				
						$sqlm = "update edoduenio set fechagen = '$fechahoy',  reportado=$marcar, referencia = '" . $ver1[0] . "_" . $ver1[1] .  "_cr' where idduenio <> $idduenio and referencia = '$relaciones'";
						$operacionm = mysql_query($sqlm);	
					
					}
					else
					{
						$sqlm = "update edoduenio set fechagen = '$fechahoy',  reportado=$marcar where idduenio = $idduenio and referencia = '$relaciones'";
						$operacionm = mysql_query($sqlm);
				
						$sqlm = "update edoduenio set fechagen = '$fechahoy',  reportado=$marcar where idduenio <> $idduenio and referencia = '$relaciones'";
						$operacionm = mysql_query($sqlm);					
					}
							
				}	
					
		}
	
	}
	
	
	//lo condonado que este marcado, se le quita la marca de facturar
	$sqlcon = "select * from edoduenio where idduenio = $idduenio and fechagen = '$fechahoy' and condonado >0";
	$operacioncon = mysql_query($sqlcon);
	$listacon="";
	
	while($rowcon = mysql_fetch_array($operacioncon))
	{
		$listacon .=$rowcon["condonado"] . " ,";
	
	}
	$listacon = substr($listacon,0,-1);
	$sqlcon="update edoduenio set facturar=false where idedoduenio in($listacon)";
	$operacionm = mysql_query($sqlcon);
	
	
	
	
	//para las notas de credto
	$sqlm = "update edoduenio set fechagen = '$fechahoy',  reportado=true  where idduenio = $idduenio and isnull(fechagen)=true and notacredito = true";
	$operacionm = mysql_query($sqlm);
	

		//proceso de facturas tomando como base al dueño y la $fechahoy para el filtro con marcados y no marcados
		//así comienzo a realizar los archivos para ser enviados a seres.
		
		
	//consulta de solo lo negativo, reportado  (verificar consulta) para obtener, suma sin iva e iva
	// listafactura ($idduenio, $concepto, $imp, $iva)
	//$sqlf = "select sum(importe) as imp, sum(iva) as iv from edoduenio e  where  importe<0  and fechagen='$fechahoy'  and reportado = 1  and e.idduenio=$idduenio and facturar = true";
		
	$sqlf = "select sum(ROUND(importe,2)) as imp, sum(ROUND(iva,2)) as iv from edoduenio e  where  importe<0  and fechagen='$fechahoy'  and reportado = 1  and e.idduenio=$idduenio and facturar = true and not(idedoduenio in (select e.idedoduenio as ided from edoduenio e, cfdipartidas p  where e.idedoduenio = p.idedoduenio and importe<0  and fechagen='$fechahoy'  and reportado = 1  and e.idduenio=$idduenio and facturar = true)) and traspaso = 0 ";
		
		
	$operacionf = mysql_query($sqlf);
	$rowf = mysql_fetch_array($operacionf);
	$importe = $rowf["imp"] * ( -1);
	$iva = $rowf["iv"] * ( -1);
		
	if($importe >0 )
	{
		//$lista = listafactura ($idduenio,"Honorarios del mes de ... 2014",$importe, $iva,'ingreso');
		
		//$sqlf="insert into cfdiedoduenio (conceptod, subtotald, impuestos, totald, notacredito, idduenio, reportadad, listaf) values ";
		//$sqlf .="('Honorarios del mes de ... 2014',$importe,$iva," . ($importe + $iva) . ",false,$idduenio,true,'$lista');";

		$sqlf="insert into cfdiedoduenio ( subtotald, impuestos, totald, notacredito, idduenio, reportadad) values ";
		$sqlf .="($importe,$iva," . ($importe + $iva) . ",false,$idduenio,true);";


		$operacionf = mysql_query($sqlf);
		//$rowf = mysql_fetch_array($operacionf);
		
		$idf=mysql_insert_id();
		
		$listafactura = "";
		$conceptoscfdi = "";
		//$sqlf = "select idedoduenio, notaedo, importe, idinmueble from edoduenio e  where  importe<0  and fechagen='$fechahoy'  and reportado = 1  and e.idduenio=$idduenio   and facturar = true";
		$sqlf = "select idedoduenio, notaedo, importe, idinmueble, iva from edoduenio e  where  importe<0  and fechagen='$fechahoy'  and reportado = 1  and e.idduenio=$idduenio   and facturar = true and not(idedoduenio in (select e.idedoduenio as ided from edoduenio e, cfdipartidas p  where e.idedoduenio = p.idedoduenio and importe<0  and fechagen='$fechahoy'  and reportado = 1  and e.idduenio=$idduenio and facturar = true)) and traspaso = 0 ";
		$operacionf = mysql_query($sqlf);
		while($rowf = mysql_fetch_array($operacionf))
		{
			$pre = "";
			if($rowf["idinmueble"]!=0)
			{
				$sqlin = "select * from inmueble where idinmueble = " . $rowf["idinmueble"];
				$operacionin = mysql_query($sqlin);
				$rowin= mysql_fetch_array($operacionin);
				$pre = "(" . $rowin["calle"] . " no. " . $rowin["numeroext"] . " - " . $rowin["numeroint"] . ") ";
			}
		
		
		
			//hay que confiramr el caso de que no tenga una factura asociada con anteriorirdad este concepto
			//antes de ser agregado a la lista de conceptos.
			
			//$sqlfa = "select count(*) as hay from cfdipartidas cp where idedoduenio = " . $rowf["idedoduenio"];		
			//$operacionfa = mysql_query($sqlfa);
			//$rowfa = mysql_fetch_array($operacionfa);
			
			//if($rowfa["hay"]==0)
			//{
				$listafactura .= "($idf," . $rowf["idedoduenio"] . "),";
				$conceptoscfdi .= $pre . $rowf["notaedo"] . "|" . ($rowf["importe"] * (-1)) . "|" . ($rowf["iva"] * (-1)) . "*";
			//}
		}
		$sqlf="insert into cfdipartidas (idcfdiedoduenio, idedoduenio) values " . substr($listafactura,0,-1);
		$operacionf = mysql_query($sqlf);
		
		$lista = listafactura ($idduenio,$conceptoscfdi,$importe, $iva,'I');
		
		$sqlf = "update cfdiedoduenio set conceptod ='$conceptoscfdi', listaf = '$lista' where idcfdiedoduenio = $idf";
		$operacionf = mysql_query($sqlf);		
				
		//consulta para inteegrar la información al abase de datos, en cfdiedoduenio y su
	
		//crear boton con post y colocar en tipoefecto 1= ingreso y de la serie HonProfPyB
		$edocuenta="nuevaVP('" . $idf . "','2');this.disabled=true;";
		// Cambio 02/09/2021
		// Se agrega el parametro fechagen para determinar la fecha en que se generaron los conceptos a facturar
		echo $accionbotonfact="<br>" . '<form name="frm_' . $idf.  '" id="frm_' . $idf .  '" method="POST" action="scripts/reporte2.php" target="trg_' . $idf . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""><input type="hidden" name="fechagen" id="fechagen" value ="'.$fechahoy.'"><input type="hidden" name="archivo" id="archivo" value ="'. substr($r["archivotxt"],0,-4) . '">' . "<input type =\"button\" value=\"Facturar lo Reportado\" onClick=\"$edocuenta\" $txtagregar /></form>";	
		// Fin Cambio 02/09/2021
		
	}
//lo nuevo para que registre el estado de cuenta en cero al confirmar	
	else
	{
		$sqli="insert into edoduenio (idduenio, idinmueble, idtipocobro, idcontrato, idhistoria, fechaedo, horaedo, importe, reportado, referencia,notaedo,iva,liquidado,facturar,fechagen) values ";
		$sqli .="($idduenio,0,0,0,0,'$fechahoy','0',0,1,'m_0','En ceros',0,0,0,'$fechahoy') ";
		
		$resultini = @mysql_query ($sqli);
		
		$idedoduenio =mysql_insert_id();
		$sqli = "update edoduenio set referencia = 'm_" . $idedoduenio ."' where idedoduenio = $idedoduenio";
		$resultini = @mysql_query ($sqli);			
		
	}		

				
	//cosulta de solo lo negativo, no reportado (verificar consulta)
	//$sqlf = "select sum(importe) as imp, sum(iva) as iv from edoduenio e  where  importe<0  and fechagen='$fechahoy'  and reportado = 0  and e.idduenio=$idduenio  and facturar = true ";
	$sqlf = "select sum(ROUND(importe,2)) as imp, sum(ROUND(iva,2)) as iv from edoduenio e  where  importe<0  and fechagen='$fechahoy'  and reportado = 0  and e.idduenio=$idduenio  and facturar = true and not(idedoduenio in (select e.idedoduenio as ided from edoduenio e, cfdipartidas p  where e.idedoduenio = p.idedoduenio and importe<0  and fechagen='$fechahoy'  and reportado = 0  and e.idduenio=$idduenio and facturar = true)) and traspaso = 0 ";
		
	$operacionf = mysql_query($sqlf);
	$rowf = mysql_fetch_array($operacionf);
	$importe = $rowf["imp"] * ( -1);
	$iva = $rowf["iv"] * ( -1);
		
		
	if($importe >0 )
	{
		//$lista = listafactura ($idduenio,"Honorarios del mes de ... 2014",$importe, $iva,'ingreso')	;	
		
		//$sqlf="insert into cfdiedoduenio (conceptod, subtotald, impuestos, totald, notacredito, idduenio, reportadad, listaf) values ";
		//$sqlf .="('Honorarios del mes de ... 2014',$importe,$iva," . ($importe + $iva) . ",false,$idduenio,true,'$lista');";
		
		$sqlf="insert into cfdiedoduenio (subtotald, impuestos, totald, notacredito, idduenio, reportadad) values ";
		$sqlf .="($importe,$iva," . ($importe + $iva) . ",false,$idduenio,true);";
		
		
		$operacionf = mysql_query($sqlf);
		$rowf = mysql_fetch_array($operacionf);
		
		$idf=mysql_insert_id();
		
		$listafactura = "";
		$conceptoscfdi = "";
		//$sqlf = "select idedoduenio, notaedo, importe, idinmueble from edoduenio e  where  importe<0  and fechagen='$fechahoy'  and reportado = 0  and e.idduenio=$idduenio  and facturar = true";
		$sqlf = "select idedoduenio, notaedo, importe, idinmueble, iva from edoduenio e  where  importe<0  and fechagen='$fechahoy'  and reportado = 0  and e.idduenio=$idduenio  and facturar = true and not(idedoduenio in (select e.idedoduenio as ided from edoduenio e, cfdipartidas p  where e.idedoduenio = p.idedoduenio and importe<0  and fechagen='$fechahoy'  and reportado = 0  and e.idduenio=$idduenio and facturar = true)) and traspaso = 0";
		$operacionf = mysql_query($sqlf);
		while($rowf = mysql_fetch_array($operacionf))
		{
		
			$pre = "";
			if($rowf["idinmueble"]!=0)
			{
				$sqlin = "select * from inmueble where idinmueble = " . $rowf["idinmueble"];
				$operacionin = mysql_query($sqlin);
				$rowin= mysql_fetch_array($operacionin);
				$pre = "(" . $rowin["calle"] . " no. " . $rowin["numeroext"] . " - " . $rowin["numeroint"] . ") ";
			}
			
			
			//$sqlfa = "select count(*) as hay from cfdipartidas cp where idedoduenio = " . $rowf["idedoduenio"];		
			//$operacionfa = mysql_query($sqlfa);
			//$rowfa = mysql_fetch_array($operacionfa);
			
			//if($rowfa["hay"]==0)
			//{			
		
				$listafactura .= "($idf," . $rowf["idedoduenio"] . "),";
				$conceptoscfdi .=  $pre . $rowf["notaedo"] . "|" . ($rowf["importe"] * (-1)) . "|" . ($rowf["iva"] * (-1)) . "*";
			//}
		}
		$sqlf="insert into cfdipartidas (idcfdiedoduenio, idedoduenio) values " . substr($listafactura,0,-1);
		$operacionf = mysql_query($sqlf);
		
		$lista = listafactura ($idduenio,$conceptoscfdi,$importe, $iva,'I');
		
		$sqlf = "update cfdiedoduenio set conceptod ='$conceptoscfdi', listaf = '$lista' where idcfdiedoduenio = $idf";
		$operacionf = mysql_query($sqlf);
				
		//consulta para inteegrar la información al abase de datos, en cfdiedoduenio y su
	
		//crear boton con post y colocar en tipoefecto 1= ingreso y de la serie HonProfPyB
		$edocuenta="nuevaVP('" . $idf . "','2');this.disabled=true;";
		echo $accionbotonfact="<br>" . '<form name="frm_' . $idf.  '" id="frm_' . $idf .  '" method="POST" action="scripts/reporte2.php" target="trg_' . $idf . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""><input type="hidden" name="archivo" id="archivo" value ="'. substr($r["archivotxt"],0,-4) . '">' . "<input type =\"button\" value=\"Facturar lo No reportado\" onClick=\"$edocuenta\" $txtagregar /></form>";	
					
	}			
		
		
		
	//consulta de notas de crdito + para facturar (verificar consulta)
	$sqlf = "select sum(ROUND(importe,2)) as imp, sum(ROUND(iva,2)) as iv from edoduenio e  where   fechagen='$fechahoy'  and reportado = 1  and e.idduenio=$idduenio  and notacredito = true and traspaso = 0 ";

	$operacionf = mysql_query($sqlf);
	$rowf = mysql_fetch_array($operacionf);
	$importe = $rowf["imp"] ;
	$iva = $rowf["iv"] ;
		
		
	if($importe >0 )
	{		
		
		//$lista = listafactura ($idduenio,"Honorarios del mes de ... 2014",$importe, $iva,'egreso');	
		//crear boton con post y colocar en tipoefecto 2= egreso
		
		//$sqlf="insert into cfdiedoduenio (conceptod, subtotald, impuestos, totald, notacredito, idduenio, reportadad, listaf) values ";
		//$sqlf .="('Honorarios del mes de ... 2014',$importe,$iva," . ($importe + $iva) . ",true,$idduenio,true,'$lista');";
		
		$sqlf="insert into cfdiedoduenio (subtotald, impuestos, totald, notacredito, idduenio, reportadad) values ";
		$sqlf .="($importe,$iva," . ($importe + $iva) . ",true,$idduenio,true);";
		
		
		
		$operacionf = mysql_query($sqlf);
		$rowf = mysql_fetch_array($operacionf);
		
		$idf=mysql_insert_id();
		
		$listafactura = "";
		$conceptoscfdi = "";
		$sqlf = "select idedoduenio, notaedo, importe,idinmueble,iva from edoduenio e  where   fechagen='$fechahoy'  and reportado = 1  and e.idduenio=$idduenio  and notacredito = true and traspaso = 0 ";
		$operacionf = mysql_query($sqlf);
		while($rowf = mysql_fetch_array($operacionf))
		{
			$pre = "";
			if($rowf["idinmueble"]!=0)
			{
				$sqlin = "select * from inmueble where idinmueble = " . $rowf["idinmueble"];
				$operacionin = mysql_query($sqlin);
				$rowin= mysql_fetch_array($operacionin);
				$pre = "(" . $rowin["calle"] . " no. " . $rowin["numeroext"] . " - " . $rowin["numeroint"] . ") ";
			}
		
			$listafactura .= "($idf," . $rowf["idedoduenio"] . "),";
			$conceptoscfdi .= $pre . $rowf["notaedo"] . "|" . $rowf["importe"]  . "|" . $rowf["iva"] . "*";
		}
		$sqlf="insert into cfdipartidas (idcfdiedoduenio, idedoduenio) values " . substr($listafactura,0,-1);
		$operacionf = mysql_query($sqlf);
		
		$lista = listafactura ($idduenio,$conceptoscfdi,$importe, $iva,'E');	
		
		$sqlf = "update cfdiedoduenio set conceptod ='$conceptoscfdi', listaf = '$lista' where idcfdiedoduenio = $idf";
		$operacionf = mysql_query($sqlf);		
				
		//consulta para inteegrar la información al abase de datos, en cfdiedoduenio y su
	
		//crear boton con post y colocar en tipoefecto 1= ingreso y de la serie HonProfPyB
		$edocuenta="nuevaVP('" . $idf . "','2');this.disabled=true;";
		echo $accionbotonfact="<br>" . '<form name="frm_' . $idf.  '" id="frm_' . $idf .  '" method="POST" action="scripts/reporte2.php" target="trg_' . $idf . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""><input type="hidden" name="archivo" id="archivo" value ="'. substr($r["archivotxt"],0,-4) . '">' . "<input type =\"button\" value=\"Facturar Notas de credito\" onClick=\"$edocuenta\" $txtagregar /></form>";	
	}	
		
				
	echo "<center><input type=\"button\" value=\"<<Regresar\" onClick = \"cargarSeccion('$ruta/reportedetallado.php','contenido', '');\"><br>Estado de cuenta de dueño confirmado. (mostrar liga PDF definitivo)<center>";
	
	//++++++++++++++++++++++++++++++++++++++++++++++++
	//aqui va si hay traspasos  agregar ambos y marco como confirmado si este dueño recibió un traspaso para 
	//confirmarlo colocando la fechagen y confirmando con la referencia.
	
	//Agregar el de $de con la marca de confirmación al final "_c" y el de para sin la confirmación para que
	//se confirme con la fecha de confirmación del estado del dueño PARA
	
	//verifico si este dueño requiere realiar traspaso
	$sqltrs = "select * from traspasodepara where de = $idduenio";
	$operaciontrs = mysql_query($sqltrs);
	if(mysql_num_rows($operaciontrs)>0)
	{
		
		//obtengo los datos del traspaso
		
		$rowtrs = mysql_fetch_array($operaciontrs);
		
		$para = $rowtrs["para"];
		$justificacion = $rowtrs["justificacion"];
		$importestr = 0;
		$periodo="";
		
		//nombre del dueño a transferir
		$sqlp="select concat(nombre,' ', nombre2, ' ', apaterno,' ', amaterno) as nom, fechagen, sum(importe + iva) as importe from edoduenio ed, duenio d where ed.idduenio = d.idduenio and d.idduenio =$para and reportado = true  group by nombre, nombre2, apaterno, amaterno, fechagen";
		$operacionp = mysql_query($sqlp);
		$rowp = mysql_fetch_array($operacionp);
		$nompara = $rowp["nom"];
		
		
		//verifico el importe para el trasapso si es menor o igual a cero, colocar cero en traspaso y 
		//la leyenda de "Importe insuficiente para traspaso"
		//$sqlstrs2 = "select idduenio, sum(importe + iva) as saldo from edoduenio where fechagen='$fechahoy' and idduenio = $idduenio and referencia like '%_c%'  group by idduenio";
		$sqlmstr2 = "select concat(nombre,' ', nombre2, ' ', apaterno,' ', amaterno) as nom, sum(importe + iva) as importe from edoduenio ed, duenio d where ed.idduenio = d.idduenio and d.idduenio =$idduenio and reportado = true and isnull(fechagen)=false and fechagen = '$fechahoy' and referencia like '%_c%' group by nombre, nombre2, apaterno, amaterno";

//verificar cuando esto sucede y ver si un importe cero que calcular o si no tiene registros que mostrar

		$operaciontrs2 = mysql_query($sqlmstr2);
		$rowstrs2 = mysql_fetch_array($operaciontrs2);
		if($rowstrs2["importe"]<=0)
		{
			//para el saldo insuficiente
			$justificacion = "Importe insuficiente para traspaso ";
			
		
		}
		else
		{	
			//para colocar el importe
			$importestr=$rowstrs2["importe"];
			
			$mes=$fechahoy;
			switch (substr($mes ,5,2))
			{
				case 1:
					$anio = (int)substr($mes,0,4);
				
					$periodo = "DICIEMBRE " . ($anio - 1) . " - ENERO $anio";
					break;
				case 2:
					$anio = (int)substr($mes,0,4);
					$periodo = "ENERO - FEBRERO $anio";			
					break;
				case 3:
					$anio = (int)substr($mes,0,4);
					$periodo = "FEBRERO - MARZO $anio";				
					break;
				case 4:
					$anio = (int)substr($mes,0,4);
					$periodo = "MARZO - ABRIL $anio";				
					break;	
				case 5:
					$anio = (int)substr($mes,0,4);
					$periodo = "ABRIL - MAYO $anio";				
					break;
				case 6:
					$anio = (int)substr($mes,0,4);
					$periodo = "MAYO - JUNIO $anio";				
					break;
				case 7:
					$anio = (int)substr($mes,0,4);
					$periodo = "JUNIO - JULIO $anio";				
					break;
				case 8:
					$anio = (int)substr($mes,0,4);
					$periodo = "JULIO - AGOSTO $anio";				
					break;
				case 9:
					$anio = (int)substr($mes,0,4);
					$periodo = "AGOSTO - SEPTIEMBRE $anio";				
					break;
				case 10:
					$anio = (int)substr($mes,0,4);
					$periodo = "SEPTIEMBRE - OCTUBRE $anio";				
					break;
				case 11:
					$anio = (int)substr($mes,0,4);
					$periodo = "OCTUBRE - NOVIEMBRE $anio";				
					break;
				case 12:
					$anio = (int)substr($mes,0,4);
					$periodo = "NOVIEMBRE - DICIEMBRE $anio";				
					break;	
			}	
			
		}
		
		//inserción para el DE
		$descripcion="Traspaso para " . $nompara . " $justificacion $periodo $fechahoy";
		
		if($rowstrs2["importe"]>=0)
		{
			$descripcion="Traspaso para " . $nompara . " $justificacion $periodo $fechahoy";
			$total = ($rowstrs2["importe"]) *(-1);
		}
		else
		{
			$descripcion="$justificacion";
			$total = 0;
		}
			
		$sqldi="insert into edoduenio (idduenio,idcontrato,idinmueble,idtipocobro,reportado,liquidado,notaedo, iva, utilidad, importe,fechaedo,fechagen,horaedo, referencia,traspaso) values ";
		$sqldi .= "($idduenio,0,0,0,true,false,'$descripcion',0,0,$total,'$fechahoy','$fechahoy','0','m_0',1)";
		$operaciondi = mysql_query($sqldi);		
		
			//obtengo el id para crear la referencia
			$idedoduenio =mysql_insert_id();
			$sqlidd = "update edoduenio set referencia = 'm_" . $idedoduenio ."_c' where idedoduenio = $idedoduenio";
			$operacionidd = mysql_query($sqlidd);		
		
	
		//$descripcion="Traspaso de " . $rowstrs2["nom"] . " $justificacion $periodo $fechahoy";
		//$total = $rowstrs2["importe"];
		$total = 0;
		if($rowstrs2["importe"]>=0)
		{
			
			$descripcion="Traspaso de " . $rowstrs2["nom"] . " $justificacion $periodo $fechahoy";
			if($rowstrs2["importe"]==0)
			{
				$descripcion=$justificacion;
				$total = 0;
			}
			else
			{
				$total = $rowstrs2["importe"];
			}
		}
		else
		{
			$descripcion="$justificacion";
			$total = 0;
		}		
		
			
		$sqldi="insert into edoduenio (idduenio,idcontrato,idinmueble,idtipocobro,reportado,liquidado,notaedo, iva, utilidad, importe,fechaedo, referencia,traspaso) values ";
		$sqldi .= "($para,0,0,0,true,false,'$descripcion',0,0,$total,'$fechahoy','m_0',1)";
		$operaciondi = mysql_query($sqldi);
			
			//obtengo el id para crear la referencia
			$idedoduenio =mysql_insert_id();
			$sqlidd = "update edoduenio set referencia = 'm_" . $idedoduenio ."' where idedoduenio = $idedoduenio";
			$operacionidd = mysql_query($sqlidd);		
	
	
	}
	
	//confirmo si este dueño se le realizó algun traspaso
	$sqltrs3 = "update edoduenio set fechagen = '$fechahoy',  referencia=concat(referencia, '_c') where idduenio = $idduenio and isnull(fechagen)=true and traspaso = 1";
	$operaciontrs3 = mysql_query($sqltrs3);
	
	//elimino el traspaso si es unico
	$sqltrs3 = "delete from traspasodepara where de = $idduenio and unico=1";
	$operaciontrs3 = mysql_query($sqltrs3);
	
	
	
	//+++++++++++++++++++++++++++++++++++++++++++++++++	
	// para el adeudo del mes anterior
	$sqls = "select idduenio, sum(importe + iva) as saldo from edoduenio where fechagen='$fechahoy' and idduenio = $idduenio and referencia like '%_c%'  group by idduenio";
	$operacions = mysql_query($sqls);
	$rows = mysql_fetch_array($operacions);
	if($rows["saldo"]<0)
	{
		$sqli="insert into edoduenio (idduenio, idinmueble, idtipocobro, idcontrato, idhistoria, fechaedo, horaedo, importe, reportado, referencia,notaedo,iva,liquidado,facturar) values ";
		$sqli .="($idduenio,0,0,0,0,'$fechahoy','0'," . $rows["saldo"] . ",1,'m_0','Adeudo del mes anterior',0,0,0) ";
		
		
		$resultini = @mysql_query ($sqli);
		
		$idedoduenio =mysql_insert_id();
		$sqli = "update edoduenio set referencia = 'm_" . $idedoduenio ."' where idedoduenio = $idedoduenio";
		$resultini = @mysql_query ($sqli);		
		
		
		
	}	
		
		
	break;
	
	
	default: //cuando se carga por primera vez
	
/*	
	if($filtro!=1)
	{//anteriores
		$miwhere .= " and isnull(fechagen)=false ";
	}
	else
	{//pendientes
		$miwhere .=  " and isnull(fechagen)=true ";
	}
	

	if($idduenio!=0)
	{
		$titulo .= " del due&ntilde;o $idduenio" ;
		$miwhere .= " and idduenio = $idduenio ";
	}
	else
	{
		$titulo .= " del todos los due&ntilde;os" ;
		$miwhere .= "";
	}	
	
		*/	
		
	$sql = "update edoduenio set fechagen=null, reportado = true where isnull(fechagen)=false and reportado = false and idduenio=$idduenio";
	$operacion = mysql_query($sql);	
		
		
		
		
		
	//$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino   and importe>0  and isnull(fechagen)=true  and d.idduenio=$idduenio ";
	if ($idduenio==221) {
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino  and fechaedo<'$fechamenos' and importe>0  and isnull(fechagen)=true  and e.idtipocobro>0  and d.idduenio=$idduenio and traspaso = 0 ";
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato, idtipocobro , fechaedo , horaedo";
	
	}
	else{
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino  and fechaedo<'$fechamenos' and importe>0  and isnull(fechagen)=true  and e.idtipocobro>0  and d.idduenio=$idduenio and traspaso = 0 ";
	//$sql .= $miwhere . " order by e.idduenio, e.idcontrato, idtipocobro, fechaedo , horaedo";
	$sql .= $miwhere .  "order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
	}
	
	$operacion = mysql_query($sql);

	$controlesjava="";
	$sumatotal = "";
	$verificalista ="";
	$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	$reporte="";
	$total=0;
	$duenio="";
	$idtipocobro="";
	$clase = "";
	echo "<form>";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idcontrato"] != $idcontrato)
		{
			if($idcontrato !="")
			{
				$reporte .= "</table><br><br>";
			}
			$verificalista .="*";
			$idcontrato = $row["idcontrato"];
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$reporte .= "</table><br><br>";
			$clase = "";
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Origen calculo</th><th>Importe</th><th>Reportar</th></tr>";
			//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
		
	
		}
	
		if($row["idtipocobro"] != $idtipocobro)
		{
			if($idtipocobro !="")
			{
				//$reporte .= "</table><td></tr>";
				if($clase == "")
				{
					$clase = " style =\"background-color:$color;\"";
				}
				else
				{
					$clase = "";
				}
			}
			$verificalista .="*";
			$idtipocobro = $row["idtipocobro"];
			
			//$reporte .= "<tr><td><table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
			
		}	
		
		
			
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"];// + $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado ";
					}
				}	
			}
		}
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}
		
		
		$relaciones = $row["referencia"];
		
		
		
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==1)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = " checked ";
		}
		
		$boton ="";
		if($row["condonado"]>0)
		{
			$boton = "<input type='button' value='Quitar' onclick=\"window.open('$ruta/condonar_notacredito.php?accion=1&id=" . $row["idedoduenio"] . "','_blank','width=300,height=200'); \" />";;
			
		}

				
		
		
		//$controles ="<td><input type=\"text\" name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\"  value=\"" . $row["notaedo"]  . "\" size='40' $deshabilitar></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' value='$suma' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar>$mensaje</td>";
//*******para el calculo del importe y %		
$origencalculo = obtenerorigen($row["idhistoria"],$row["referencia"],$idduenio,$row["idinmueble"],$row["idedoduenio"]);
//*******		
		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td> $origencalculo  <td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td> $origencalculo  <td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	//str_replace("%"," porciento ",  $row["notaedo"]) 
	
		$reporte .= "<tr $clase ><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}

//manuales
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino   and importe>0  and isnull(fechagen)=true  and e.idtipocobro=0  and d.idduenio=$idduenio and traspaso = 0 ";
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato, idtipocobro, fechaedo";
	
	
	$operacion = mysql_query($sql);

	
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($row["idcontrato"] != $idcontrato)
		{
			if($idcontrato !="")
			{
				$reporte .= "</table><br><br>";
			}
			$verificalista .="*";
			$idcontrato = $row["idcontrato"];
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$reporte .= "</table><br><br>";
			$clase = "";
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br>$inaquilino<table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
			//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
		
	
		}
	
		if($row["idtipocobro"] != $idtipocobro)
		{
			if($idtipocobro !="")
			{
				//$reporte .= "</table><td></tr>";
				if($clase == "")
				{
					$clase = " style =\"background-color:$color;\"";
				}
				else
				{
					$clase = "";
				}
			}
			$verificalista .="*";
			$idtipocobro = $row["idtipocobro"];
			
			//$reporte .= "<tr><td><table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
			
		}	
		
		
			
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"];// + $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado ";
					}
				}	
			}
		}
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}
		
		
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==1)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = " checked ";
		}
		
		$boton ="";
		if($row["condonado"]>0)
		{
			$boton = "<input type='button' value='Quitar' onclick=\"window.open('$ruta/condonar_notacredito.php?accion=1&id=" . $row["idedoduenio"] . "','_blank','width=300,height=200'); \" />";;
			
		}

				
		
		
		//$controles ="<td><input type=\"text\" name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\"  value=\"" . $row["notaedo"]  . "\" size='40' $deshabilitar></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' value='$suma' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar>$mensaje</td>";
		
$origencalculo = obtenerorigen($row["idhistoria"],$row["referencia"],$idduenio,$row["idinmueble"],$row["idedoduenio"]);
		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td> $origencalculo <td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td> $origencalculo <td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	//str_replace("%"," porciento ",  $row["notaedo"]) 
	
		$reporte .= "<tr $clase ><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}





//para los abonos
	//$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble   and fechaedo<'$fechamenos' and importe>0  and isnull(fechagen)=true and idcontrato = 0  and d.idduenio=$idduenio ";
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble    and importe>0  and isnull(fechagen)=true and idcontrato = 0  and d.idduenio=$idduenio and traspaso = 0 ";	
	$sql .= $miwhere . " order by e.idduenio, e.idcontrato, idtipocobro, fechaedo";
	
	
	$operacion = mysql_query($sql);

	//$controlesjava="";
	//$sumatotal = "";
	//$verificalista ="";
	//$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	//$reporte="";
	//$total=0;
	//$duenio="";
	//$idtipocobro="";
	//$clase = "";
	$header = 0;
	//echo "<form>";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($header==0)
		{
			
			$verificalista .="*";
			//$idcontrato = $row["idcontrato"];
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			//$reporte .= "</table><br><br>";
			$clase = "";
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br><table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
			//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
			$header="1";
	
		}
	
			
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"];// + $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado ";
					}
				}	
			}
		}
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}
		
		
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==1)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = " checked ";
		}
		
		$boton ="";
		if($row["condonado"]>0)
		{
			$boton = "<input type='button' value='Quitar' onclick=\"window.open('$ruta/condonar_notacredito.php?accion=1&id=" . $row["idedoduenio"] . "','_blank','width=300,height=200'); \" />";;
			
		}

				
		
		
		//$controles ="<td><input type=\"text\" name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\"  value=\"" . $row["notaedo"]  . "\" size='40' $deshabilitar></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' value='$suma' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar>$mensaje</td>";
$origencalculo = obtenerorigen($row["idhistoria"],$row["referencia"],$idduenio,$row["idinmueble"],$row["idedoduenio"]);
		
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		//$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td> $origencalculo <td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . str_replace("%"," porciento ",  $row["notaedo"])   . "</textarea></td> $origencalculo <td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
	//str_replace("%"," porciento ",  $row["notaedo"]) 
	
		$reporte .= "<tr $clase ><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}

/*
//para los confirmados que no fueron seleccionados
	//$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble   and fechaedo<'$fechamenos' and importe>0  and isnull(fechagen)=true and idcontrato = 0  and d.idduenio=$idduenio ";
	$sql = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble    and importe>0  and isnull(fechagen)=false and reportado = false  and d.idduenio=$idduenio ";	
 	$sql .= $miwhere . " order by e.idduenio, e.idcontrato, idtipocobro, fechaedo";
		
	
	
	$operacion = mysql_query($sql);

	//$controlesjava="";
	//$sumatotal = "";
	//$verificalista ="";
	//$idcontrato = "";
	//$reporte = "<table border='1'><tr><th>Contrato</th><th>Inmueble</th><th>Importe</th><th>iva</th><th>Total</th><th>Nota</th><th>Reportar</th></tr>";
	//$reporte="";
	//$total=0;
	//$duenio="";
	//$idtipocobro="";
	//$clase = "";
	$header = 0;
	//echo "<form>";
	while($row = mysql_fetch_array($operacion))	
	{
	
		if($header==0)
		{
			
			$verificalista .="*";
			//$idcontrato = $row["idcontrato"];
			$duenio=$row["nd"] . " " . $row["n2d"]  . " " . $row["apd"]  . " " . $row["amd"];
			$reporte .= "</table><br><br>";
			$clase = "";
			$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroint"];
			$inaquilino = $row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"];
			$reporte .= "$inmueble<br><table border='1'><tr><th>Cargado</th><th>Nota</th><th>Importe</th><th>Reportar</th></tr>";
			//$reporte .= "$inmueble<br>$inaquilino<table border='1'>";
			$header="1";
	
		}
	
			
		$suma = $row["importe"] + $row["iva"];
		//$suma = $row["importe"];// + $row["iva"];
		$total +=$suma;
		//$inmueble = $row["calle"] . " No." . $row["numeroext"]  . " " . $row["numeroext"];
		$deshabilitar ="";
		$mensaje="";
		if($row["reportado"]==true)
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado ";
					}
				}	
			}
		}
		else
		{
			$ver = @split("[_]",$row["referencia"]);
			
			if(count($ver)>2)
			{
				if($ver[2]!="m")
				{
				
					if($ver[2]=="c")
					{
						$deshabilitar= "disabled "; 
						//$mensaje=" Confirmado ";
					}
					else
					{
						$deshabilitar= "disabled "; 
						$mensaje=" Relacionado  ";
					}
				}	
			}		
		}
		
		
		$relaciones = $row["referencia"];
		$ver1 = @split("[_]",$relaciones);
		$marcarcheck = "";
		if (count($ver1)>2)	
		{
			if($row["reportado"]==1)
			{
				$marcarcheck = " checked ";
			
			}

		}
		else
		{
			$marcarcheck = " checked ";
		}
		$marcarcheck = " checked ";
		$boton ="";
		if($row["condonado"]>0)
		{
			$boton = "<input type='button' value='Quitar' onclick=\"window.open('$ruta/condonar_notacredito.php?accion=1&id=" . $row["idedoduenio"] . "','_blank','width=300,height=200'); \" />";;
			
		}

				
		
		
		//$controles ="<td><input type=\"text\" name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\"  value=\"" . $row["notaedo"]  . "\" size='40' $deshabilitar></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' value='$suma' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar>$mensaje</td>";
		$sumatotal .= "i_"  . $row["idedoduenio"] . "|";
		$verificalista .="i_"  . $row["idedoduenio"] . "|";
		$controles ="<td><textarea rows='2' cols='40' name=\"n_" . $row["idedoduenio"]  . "\" id=\"n_"  . $row["idedoduenio"]  . "\" $deshabilitar>" . $row["notaedo"]  . "</textarea></td><td align='center'><input type='text' size='10' name='i_"  . $row["idedoduenio"] . "' id='i_"  . $row["idedoduenio"] . "' value='" . number_format($suma,2,".","") . "' disabled/></td><td align='center'><input type=\"checkbox\" name=\"c_"  . $row["idedoduenio"]  . "\" id=\"c_"  . $row["idedoduenio"]  . "\" $marcarcheck $deshabilitar  onClick=\"verifica('total0');\">$boton $mensaje</td>";
		$controlesjava .= "&n_" . $row["idedoduenio"] . "=' + n_"  . $row["idedoduenio"] . ".value + '&c_"  . $row["idedoduenio"] . "=' + c_"  . $row["idedoduenio"] . ".checked + '";
	
		$reporte .= "<tr $clase ><td>" . $row["fechaedo"] .  "</td>$controles</tr>";
		$desabilitarv="";
		
	}

*/




	
	
	
	
    //echo $verificalista;
	$reporte .="<tr><td colspan='3' align='center'>Total Ingresos a reportar</td><td colspan='2'><input type='text' size='10' name='total0' id='total0' value='" . number_format($total,2,".","") . "'  onClick=\"calculaedoduenio('total0', '$sumatotal' , '$verificalista')\" /></td></tr></table>";
	$reporte = CambiaAcentosaHTML($reporte);
	echo "<center><input type=\"button\" value=\"<<Regresar\" onClick = \"cargarSeccion('$ruta/reportedetallado.php','contenido', '');\"><br>$duenio<br><input type=\"button\" value=\"Siguiente\" onClick = \"cargarSeccion('$ruta/edoduenioseleccion.php','contenido', 'paso=1&filtro=$filtro&periodo=$periodo&id=$idduenio$controlesjava');this.disabled =true\"></center> $reporte ";
	
	echo "</form>";
	}	
	
	
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}


function listafactura ($idduenio, $concepto, $imp, $iva,$tipor)
{


// se obtienen los datos de losdueños

$sqlf = "select * from duenio where idduenio = $idduenio";
$operacionf = mysql_query($sqlf);
$rowf = mysql_fetch_array($operacionf);

//datos cabecera del cfdi
$datosl = '1_CFD|380*';
$datosl .= '2_Version|3.3*';

$datosl .= '5_Serie|*';
$datosl .= '6_Folio|*';

$datosl .= '8_Fecha|'. date("Y-m-d") . "T" . date("H:m:s") . '*';

$datosl .= '10_FormaPago|99*';

$datosl .= '26_DivisOp|MXN*';



$datosl .= '29_EfectoCFD|' . $tipor . '*';
$datosl .= '30_MetPago|PUE*';
$datosl .= '31_LugarExp|06470*';

$cabecera = $datosl;

//Datos del emisor
$datosl = '41_ERFC|PAB0802225K4*';
$datosl .= '42_ENombre|PADILLA & BUJALIL S.C.*';
$datosl .= '43_RegFiscal|601*';

$datosl .= '46_ECalle|AV. INSURGENTES CENTRO*';
$datosl .= '47_EColon|SAN RAFAEL*';
$datosl .= '48_EMunic|CUAUHTEMOC*';
$datosl .= '49_EEdo|CIUDAD DE MEXICO*';
$datosl .= '50_Epais|MEXICO*';
$datosl .= '51_ECP|06470*';

$emisor = $datosl;

//receptor
$rfcduenio =$rowf["rfcd"];
if (strlen($rfcduenio)<12)
{
	$rfcduenio="XAXX010101000";
}

$datosl = '61_RRFC|' . $rfcduenio . '*';
$datosl .= '62_RNombre|' . $rowf["nombre"] . " " . $rowf["nombre2"] . " " . $rowf["apaterno"] . " " . $rowf["amaterno"] . '*';
$datosl .= '65_RUsoCFDI|P01*';

$datosl .= '69_Rcalle|' . $rowf["called"] . '*';
$datosl .= '70_RColon|' . $rowf["coloniad"] . '*';
$datosl .= '71_RMunic|' . $rowf["delmund"] . '*';
$datosl .= '72_REdo|' . $rowf["estadod"] . '*';
$datosl .= '73_Rpais|' . $rowf["paisd"] . '*';
$datosl .= '74_RCP|' . $rowf["cpd"] . '*';

$receptor = $datosl;

//concepto

//crear arreglo para los conceptos

$listac = @split("[*]",$concepto);
$datosl ="";
for($i=0; $i<(count($listac)-1); $i++)
{

	$s = @split("[|]",$listac[$i]);
	

	//$datosl .= '122_Numlin|1*';
$datosl .= '119_Numlin|' . ($i+1) . '*';

$datosl .= '121_ClaveProdServ|80131800*';

$datosl .= '125_Cant|1*';

$datosl .= '127_ClaveUnidad|E48*';

$datosl .= '128_UM|SRV*';
$datosl .= '130_Desc|' . $s[0] . ' *';
$datosl .= '131_PrecMX|' . number_format($s[1],2,".","")  . '*';
$datosl .= '132_ImporMX|' . number_format($s[1],2,".","") . '*';

$datosl .= '160_DescTipoImp|002*';
$datosl .= '161_BaseImp|' . number_format($s[1],2,".","") . '*';
$datosl .= '162_CategImp|T*';
$datosl .= '163_TipoFactor|Tasa*';
$datosl .= '164_PorImp|' . number_format(($s[2]/$s[1]),6,".","") . '*';
$datosl .= '165_ImporImp|' . number_format($s[2],2,".","") . '*';


}

$concepto = $datosl;


/* Aqui estan los terceros
$datosl .= '187_Campo0' value='TERCERO'/></td></tr>
$datosl .= '188_Campo1' value='RFC del arrendador'/></td></tr>
$datosl .= '189_Campo2' value='razón social o nombre'/></td></tr>
$datosl .= '190_Campo3' value='calle'/></td></tr>
$datosl .= '191_Campo4' value='numero interior'/></td></tr>
$datosl .= '192_Campo5' value='numero exterior'/></td></tr>
$datosl .= '193_Campo6' value='colonia'/></td></tr>
$datosl .= '194_Campo7' value='localidad'/></td></tr>
$datosl .= '195_Campo8' value='referencia'/></td></tr>
$datosl .= '196_Campo9' value='delegacion o municipio'/></td></tr>
$datosl .= '197_Campo10' value='estado'/></td></tr>
$datosl .= '198_Campo11' value='pais'/></td></tr>
$datosl .= '199_Campo12' value='codigo postal'/></td></tr>
$datosl .= '200_Campo13' value='numero de pedimento aduanero'/></td></tr>
$datosl .= '201_Campo14' value='fecha de aduana'/></td></tr>
$datosl .= '202_Campo15' value='nombre de aduana'/></td></tr>
$datosl .= '203_Campo16' value='cuenta predial'/></td></tr>
$datosl .= '204_Campo17' value=''/></td></tr>
$datosl .= '205_Campo18' value=''/></td></tr>
$datosl .= '206_Campo19' value=''/></td></tr>
$datosl .= '207_Campo20' value=''/></td></tr>
$datosl .= '208_Campo21' value=''/></td></tr>
$datosl .= '209_Campo22' value=''/></td></tr>
$datosl .= '210_Campo23' value=''/></td></tr>
$datosl .= '211_Campo24' value=''/></td></tr>
$datosl .= '212_Campo25' value=''/></td></tr>
$datosl .= '213_Campo26' value=''/></td></tr>
$datosl .= '214_Campo27' value=''/></td></tr>
$datosl .= '215_Campo28' value=''/></td></tr>
$datosl .= '216_Campo29' value=''/></td></tr>
$datosl .= '217_Campo30' value=''/></td></tr>
$datosl .= '218_Campo31' value=''/></td></tr>
$datosl .= '219_Campo32' value=''/></td></tr>
$datosl .= '220_Campo33' value=''/></td></tr>
$datosl .= '221_Campo34' value=''/></td></tr>
$datosl .= '222_Campo35' value=''/></td></tr>
$datosl .= '223_Campo36' value=''/></td></tr>
$datosl .= '224_Campo37' value=''/></td></tr>

$datosl .= '225_TpMonto' value='TERCERO'/></td></tr>
$datosl .= '226_PorMonto' value='tasa de impuesto'/></td></tr>
$datosl .= '227_Monto' value='importe del impuesto'/></td></tr>
$datosl .= '228_IDMonto' value='TRAS ó RET'/></td></tr>
$datosl .= '229_ClsMonto' value='clase de impuesto (IVA, ISR, IEPS)'/></td></tr>
$datosl .= '230_FechaMonto' value=''/></td></tr>
*/

$datosl = '189_TotImpT|' . number_format($iva,2,".","") . '*';
$datosl .= '190_TotImp|' . number_format($iva,2,".","") . '*';

$datosl .= '191_TotNeto|' . number_format($imp,2,".","") . '*';
$datosl .= '192_TotBruto|' . number_format($imp,2,".","") . '*';
$datosl .= '193_Importe|' . number_format(($imp+$iva),2,".","") . '*';

$datosl .= '194_TipImpT|002*';
$datosl .= '195_TipFactT|Tasa*';
$datosl .= '196_PorImpT|' . number_format(($iva/$imp),6,".","") . '*';
$datosl .= '197_MonImpT|' . number_format($iva,2,".","") . '*';

$totales = $datosl;


/*
$datosl .= '246_Campo2' value='Serie de la factura original'/></td></tr>
$datosl .= '247_Campo3' value='fecha de la factura original'/></td></tr>
$datosl .= '248_Campo4' value='monto de la fcatura original'/></td></tr>
$datosl .= '249_Campo5' value=''/></td></tr>
$datosl .= '250_Campo6' value=''/></td></tr>
$datosl .= '251_Campo7' value=''/></td></tr>
$datosl .= '252_Campo8' value=''/></td></tr>
$datosl .= '253_Campo9' value=''/></td></tr>
$datosl .= '254_Campo10' value=''/></td></tr>
$datosl .= '255_Campo11' value=''/></td></tr>
*/
	return $datosl = $cabecera . $emisor . $receptor . $concepto . $totales;

};

function obtenerorigen($idhistoria,$referencia,$idduenio,$idinmueble,$idedoduenio)
{
	if(substr($referencia,0,1) == "h")
	{//es de un elemento de los estados de cuenta
		//Leo el importe con ivA
		$sqlh = "select * from historia where idhistoria = $idhistoria";
		//echo "<br>$idedoduenio<br>";
		$oph = mysql_query($sqlh);	
		$rowh = @mysql_fetch_array($oph);
		$ih = $rowh["cantidad"];
		$numero=2;
		
	
	}
	else
	{//es manual, asi que se verifica si es de un inmueble
		
		//echo "<br>$idedoduenio<br>";
				
		$sqlh = "select * from edoduenio where idedoduenio = $idedoduenio";
		$oph = mysql_query($sqlh);	
		$rowh = mysql_fetch_array($oph);
		$fecha = $rowh["fechaedo"];
		$hora = $rowh["horaedo"];	
		$idcontrato = $rowh["idcontrato"];
		
		$sqlh = "select idcontrato, sum((importe + iva)) as cantidad, count(idcontrato) as numero from edoduenio where idcontrato = $idcontrato and idinmueble = $idinmueble and fechaedo = '$fecha' and horaedo = '$hora' and SUBSTRING(referencia,1,1) ='m' order by idcontrato";
		$oph = mysql_query($sqlh);	
		$rowh = mysql_fetch_array($oph);
		$ih = $rowh["cantidad"];
		$numero=$rowh["numero"];
		
			
	
	}

	//porcentaje del inmueble y del dueño
	$sqld = "select * from duenioinmueble where idinmueble = $idinmueble and idduenio = $idduenio";
	$opd = mysql_query($sqld);	
	$rowd = mysql_fetch_array($opd);
	if($numero>1)
	{
		
		$p = $rowd["porcentajed"];
		
	}
	else
	{
		$p = 100;		
	}
		
	$html = "<td>$ih ($p%)</td>";


	return $html;
}
?>	
