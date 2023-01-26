<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclass.php';


$paso=@$_GET["paso"];
$idc=@$_GET["idc"];
$inquilino=@$_GET["inquilino"];
$fiador=@$_GET["fiador"];
$inmueble=@$_GET["inmueble"];
$tipocontrato=@$_GET["tipocontrato"];
$fechainicio=@$_GET["fechainicio"];
$fechatermino=@$_GET["fechatermino"];
$deposito=@$_GET["deposito"];
$accion=@$_GET["accion"];
$periodo=@$_GET["periodo"];
$tipocobro=@$_GET["tipocobro"];
$prioridad=@$_GET["prioridad"];
$cantidad=@$_GET["cantidad"];
$interes=@$_GET["interes"];
$iva=@$_GET["iva"];
$recibo=@$_GET["recibo"];
$idcobro=@$_GET["idcobro"];
$seleccion =@$_GET["seleccion"];
$nombre="";
$nombre2="";
$apaterno="";
$amaterno="";
$idinquilino="";
$nombref="";
$nombre2f="";
$apaternof="";
$amaternof="";
$idinquilinof="";
$idinmueble="";
$idapa=0;


$addtext="";

$enviocorreo = New correo;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$idusuario = $misesion->usuario;
	$sql="select * from submodulo where archivo ='autorizar_contratos.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}



	
	if ($priv[0]!='1')
	{
		//Es privilegio para poder ver eset modulo, y es negado
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}

	if ($priv[1]=='1')
	{
		$txtagregar = "";
	}
	else
	{
		$txtagregar = " DISABLED ";
	}

	//para el privilegio de editar, si es negado deshabilida el botn
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el botn
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}


	switch ($paso)
	{
	case 0: // cuando muestra los contratos en edicin
	
		if($accion="1") // si se va a borrar el contrato
		{
			//Borrar todas las asignaciones de cobros y sus 
			$sql="delete from cobros where idcontrato =$idc";
			$operacion=mysql_query($sql);
			
			//Borrar el contrato
			$sql="delete from contrato where idcontrato = $idc";
			$operacion=mysql_query($sql);		
		}
		$listacontratos="";
		$sql="select * from contrato, inquilino, inmueble where contrato.idinquilino = inquilino.idinquilino and contrato.idinmueble = inmueble.idinmueble and enedicion=true";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$listacontratos .= "<tr><td>" . $row["idcontrato"] . "</td>";
			$listacontratos .= "<td>" . $row["nombre"] . " " .  $row["nombre2"] . " "  .  $row["apaterno"] .  $row["amaterno"] . "</td>";
			$listacontratos .= "<td>" . $row["calle"] . " No. " . $row["numeroext"] . " Int. " . $row["numeroint"] . " Col. " . $row["colonia"] .  " C.P. " . $row["cp"] ."</td>";
			$listacontratos .= "<td><input type=\"button\" value=\"Borrar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=1&paso=0&idc=" . $row["idcontrato"] . "')\">&nbsp;";
			$listacontratos .= "<input type=\"button\" value=\"Ver\" onClick=\"cargarSeccion('$dirscript','contenido','accion=0&paso=2&idc=" . $row["idcontrato"] . "')\">	</td></tr>";
		
		}


$html = <<<paso0
<h2 align="center">Contratos Nuevos</h2>
<center>
<form>

<h3>En edici&oacute;n</h3>
<table border="1">
<tr>
	<th>#</th>
	<th>Inquilino</th>
	<th>Inmueble</th>
	<th>Acci&oacute;n</th>
</tr>
	$listacontratos
</table>

</form>
</center>
paso0;
	echo CambiaAcentosaHTML($html);
	break;
// ******* fin de paso 0 *******************************************


case 2: // Confirmacin del contrato y asignacin de cobros en el historial para los primeros cobros
	//echo $seleccion;
		$sql = "select inquilino.nombre as nombrei, inquilino.nombre2 as nombre2i, inquilino.apaterno as apaternoi, inquilino.amaterno as amaternoi, fiador.nombre as nombref, fiador.nombre2 as nombre2f, fiador.apaterno as apaternof, fiador.amaterno as amaternof, tipocontrato, fechainicio, fechatermino, deposito, inmueble.idinmueble as idinm, calle, numeroext, numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp,prioridad, periodo.nombre as nombrep, tipocobro, rgrupo, cantidad, interes, iva, cobros.idcobros as idcob from contrato, inmueble, inquilino, fiador, tipocontrato, cobros, prioridad, periodo, tipocobro, rgrupo where contrato.idinmueble = inmueble.idinmueble and contrato.idinquilino = inquilino.idinquilino and contrato.idfiador = fiador.idfiador and contrato.idtipocontrato = tipocontrato.idtipocontrato and contrato.idcontrato = cobros.idcontrato and cobros.idprioridad = prioridad.idprioridad and cobros.idperiodo = periodo.idperiodo and cobros.idtipocobro = tipocobro.idtipocobro and cobros.idrgrupo = rgrupo.idrgrupo and contrato.idcontrato = $idc";
		$inicial=0;
		$listacobros="<table border=\"1\" width=\"100%\"><tr><th>Id</th><th>T. cobro</th><th>Periodo</th><th>Prioridad</th><th>Cantidad</th><th>Interes</th><th>IVA</th><th>Recibos de</th>	</tr>";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			if($inicial==0)//para asignar los valores de las variables para el formulario
			{
				$inquilino=$row["nombrei"] . " " . $row["nombre2i"]  . " " . $row["apaternoi"] . " " . $row["amaternoi"];
				$fiador=$row["nombref"] . " " . $row["nombre2f"]  . " " . $row["apaternof"] . " " . $row["amaternof"];			
				$inmueble=$row["calle"] . " No. " . $row["numeroext"]  . " Int. " . $row["numeroint"] . " Col. " . $row["colonia"]  . " Alc/Mun. " . $row["delmun"]  . " C.P. " . $row["cp"];		
				$tipocontrato = $row["tipocontrato"];
				$fechainicio = $row["fechainicio"];		
				$fechatermino = $row["fechatermino"];	
				$deposito = $row["deposito"];	
				$idinmueble =$row["idinm"];
				$inicial=1;
			}
			$listacobros.="<tr><td>" . $row["idcob"] . "</td>";
			$listacobros.="<td>" . $row["tipocobro"] . "</td>";			
			$listacobros.="<td>" . $row["nombrep"] . "</td>";						
			$listacobros.="<td>" . $row["prioridad"] . "</td>";
			$listacobros.="<td>" . $row["cantidad"] . "</td>";
			$listacobros.="<td>" . $row["interes"] . "</td>";
			$listacobros.="<td>" . $row["iva"] . "</td>";
			$listacobros.="<td>" . $row["rgrupo"] . "</td></tr>";
			
			
		
		}
		$listacobros .="</table>";
		
		
		$sqlidc = "select seleccion from contrato where idcontrato = $idc";
		$operacion = mysql_query($sqlidc);
		$rowidc = mysql_fetch_array($operacion);
		$seleccion = $rowidc["seleccion"];
		
//para los mantenimentos seleccionados
		$mant=split("[|]",$seleccion);
		$listamant="";
		foreach($mant as $dato)
		{
			$a = split("\*",$dato);
			
			if($a[1]=='true')
			{
				$b = split("[_]",$a[0]);
				$sqlm = "select * from tiposervicio where idtiposervicio =" . $b[1];
				
				$operacionm = mysql_query($sqlm);
				$rowm = mysql_fetch_array($operacionm);
				$listamant .=  $rowm["tiposervicio"] . "<br>";				
			}
		}
		
				
		
		
		


$html = <<<paso3
<form>
<h3>Confirmar contrato</h3>
<table border="1">
<tr>
	<th colspan="2">Datos del contrato</th>
</tr>
<tr>
	<td ><b>Inquilino</b></td>
	<td>$inquilino</td>
</tr>
<tr>
	<td ><b>Obligado Solidario</b></td>
	<td>$fiador</td>
</tr>
<tr>
	<td ><b>Inmueble</b></td>
	<td>$inmueble</td>
</tr>
<tr>
	<td ><b>Tipo Contrato</b></td>
	<td>$tipocontrato</td>
</tr>
<tr>
	<td ><b>Fecha Inicio (aaaa-mm-dd)</b></td>
	<td>$fechainicio</td>
</tr>
<tr>
	<td ><b>Fecha Termino (aaaa-mm-dd)</b></td>
	<td>$fechatermino</td>
</tr>
<tr>
	<td ><b>Deposito</b></td>
	<td>$deposito</td>
</tr>
<tr>
	<th colspan="2" ><b>Cobros configurados</b></th>
</tr>
<tr>
	<td colspan="2">
		$listacobros
	</td>
	
</tr>
<tr>
	<th colspan="2" ><b>Mantenimientos asignados</b></th>
</tr>
<tr>
	<td colspan="2">
		$listamant 
	</td>
	
</tr>
<tr>
	<td colspan="2" align="center">
		<input type="button" name="aplicar" value="Aplicar" onClick="this.disabled=true;cargarSeccion('$ruta/contratonuevo.php','contenido','accion=1&paso=4&idc=$idc&inmueble=$idinmueble&seleccion=$seleccion')" $txtagregar>
		<input type="button" name="regresar" value="Regresar" onClick="this.disabled=true;cargarSeccion('$dirscript','contenido','accion=0&paso=0')" $txtagregar>
		<input type="button" value="Editar" onClick="cargarSeccion('$ruta/contratonuevo.php','contenido','accion=1&paso=1&idc=$idc')">
	</td>
</tr>

</table>


</form>
paso3;
		echo CambiaAcentosaHTML($html);


	break;
//******************* fin de la cofirmacin ***********************
		
	
}

// Fin del proceso firmado como usuario valido
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}


?>