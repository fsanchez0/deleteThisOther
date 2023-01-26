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
$idasesor =@$_GET["idasesor"];
$apartado =@$_GET["apartado"];
$observacionesc =@$_GET["observacionesc"];
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
	$sql="select * from submodulo where archivo ='contratonuevo.php'";
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
			$listacontratos .= "<input type=\"button\" value=\"Continuar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=1&paso=1&idc=" . $row["idcontrato"] . "')\">	</td></tr>";
		
		}


$html = <<<paso0
<h2 align="center">Contratos Nuevos</h2>
<center>
<form>
<input type="button" value="Nuevo" onClick="cargarSeccion('$dirscript','contenido','accion=0&paso=1')">
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

case 1: // para crear el cuestionario
	//accion = 0 Nuevo
	//accion = 1 editar existente

	$direccion="";
	if($accion=="1")
	{

		$sql="select * from contrato where idcontrato=$idc";
		$operacion = mysql_query($sql);
		extract( mysql_fetch_array($operacion));
		$asesor=$idasesor;

		$sql="select * from inquilino where idinquilino=$idinquilino";
		$operacion = mysql_query($sql);
		extract( mysql_fetch_array($operacion));
	
		$sql="select idfiador as idfi, nombre as nombref, nombre2 as nombre2f,apaterno as apaternof,amaterno as amaternof from fiador where idfiador=$idfiador";
		$operacion = mysql_query($sql);
		extract( mysql_fetch_array($operacion));
		$fiador=$idfi;

		$sql="select * from inmueble where idinmueble=$idinmueble";
		$operacion = mysql_query($sql);
		extract( mysql_fetch_array($operacion));		
		$direccion="$calle No. $numeroext Int. $numeroint Col. $colonia Alc. $delmun C.P. $cp";
		
	}
	
	$sql="select * from tipocontrato";
	$tipocontratoselect= "<select name=\"tipocontrato\"><option value=\"0\">Seleccione uno de la lista</option>";;
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if (@$idtipocontrato==$row["idtipocontrato"])
		{
			$seleccionopt=" SELECTED ";
		}
		$tipocontratoselect .= "<option value=\"" . $row["idtipocontrato"] . "\" $seleccionopt>" . $row["tipocontrato"] . "</option>";

	}
	$tipocontratoselect .="</select>";

	$sql="select asesor.idasesor, asesor.nombre, asesor.apellido from asesor, asesorcategoria where asesor.idasesor=asesorcategoria.idasesor and asesorcategoria.idcategoriaas=2 order by idasesor";
	$asesorSelect= "<select name=\"idasesor\">";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion)) {
		$seleccionAsesor="";
		if (@$asesor==$row["idasesor"]) {
			$seleccionAsesor=" SELECTED ";
		}
		$asesorSelect .= "<option value=\"" . $row["idasesor"] . "\" $seleccionAsesor>" . $row["nombre"]." ".$row["apellido"] . "</option>";
	}
	$asesorSelect .="</select>";


$html = <<<paso1
<form>
<h3 align="center">Datos Contrato</h3>
<center>
<table border="1">
<tr>
	<td valign="top">
		<table border="1">
		<tr>
			<td>Inquilino</td>
			<td>
				<input type="text" name="inquilinon" value="$nombre $nombre2 $apaterno $amaterno" id="inquilinon"><a  onClick="cargarSeccion('$ruta/bdatocont.php','bdato','cat=0')"><img src="imagenes/lupa0.png" height="13"></a>
				<input type="hidden" name="inquilino" value="$idinquilino" id="inquilino">
			</td>
		</tr>
		<tr>
			<td>Obligado Solidario</td>
			<td>
				<input type="text" name="fiadorn" value="$nombref $nombre2f $apaternof $amaternof" id="fiadorn"><a  onClick="cargarSeccion('$ruta/bdatocont.php','bdato','cat=1')"><img src="imagenes/lupa0.png" height="13"></a>
				<input type="hidden" name="fiador" value="$fiador" id="fiador">
			</td>
		</tr>
		<tr>
			<td>Inmueble</td>
			<td>
				<textarea rows="4" cols="30" name="inmueblen" id="inmueblen">$direccion</textarea><a  onClick="cargarSeccion('$ruta/bdatocont.php','bdato','cat=2')"><img src="imagenes/lupa0.png" height="13"></a></a>
				<input type="hidden" name="inmueble" value="$idinmueble" id="inmueble">
				<input type="hidden" name="apartado" value="$idapartado" id="apartado">
			</td>
		</tr>
		<tr>
			<td>Tipo Contrato</td>
			<td>
				$tipocontratoselect
			</td>
		</tr>
		<tr>
			<td>Fecha Inicio (aaaa-mm-dd)</td>
			<td><input type="date" name="fechainicio" value="$fechainicio"></td>
		</tr>
		<tr>
			<td>Feha Termino (aaaa-mm-dd)</td>
			<td><input type="date" name="fechatermino" value="$fechatermino"></td>
		</tr>
		<tr>
			<td>Observaciones </td>
			<td><textarea name="observacionesc" cols = "30" rows="6">$observacionesc</textarea></td>
		</tr>


		<tr>
			<td>Asesor </td>
			<td>$asesorSelect</td>
		</tr>
		<tr>
			<td>Dep&oacute;sito</td>
			<td><input type="text" name="deposito" value="$deposito"></td>
		</tr>		
		
		</table>
	</td>
	<td>
		<div id="bdato"></div>
	</td>
	
</tr>
<tr>
	<td colspan="2" align="center"><input type="button" name="siguietne" value="Siguiente >" onClick="if(validafechaphp(fechainicio.value,fechatermino.value)==true){cargarSeccion('$dirscript','contenido','accion=0&paso=2&idc=$idc&inquilino=' + inquilino.value + '&fiador=' + fiador.value + '&inmueble=' + inmueble.value + '&tipocontrato=' + tipocontrato.value + '&fechainicio=' + fechainicio.value + '&fechatermino=' + fechatermino.value + '&deposito=' + deposito.value + '&idasesor=' + idasesor.value  + '&apartado=' + document.getElementById('apartado').value)}else{alert('Las fechas de inicio y termino est&aacute;n mal o son incoerentes')}"></td>
</tr>
</table>


</form>
</center>
paso1;
	echo CambiaAcentosaHTML($html);
	break;
//************ fin paso 1 de la creacion del contrato ***************

case 2: // paso para la asignacin de cobros

	if(!$idc)
	{
	    $sql = "insert into contrato (idusuario, idtipocontrato, idinmueble, idinquilino, idfiador, fechainicio, fechatermino, deposito, concluido, litigio, enedicion,activo,idasesor,idapartado, observacionesc) values ($idusuario,$tipocontrato, $inmueble, $inquilino, $fiador, '$fechainicio', '$fechatermino', $deposito, false,false, true,true,$idasesor,$apartado,'$observacionesc')";
		//$sql = "insert into contrato (idusuario, idtipocontrato, idinmueble, idinquilino, idfiador, fechainicio, fechatermino, deposito, concluido, litigio, enedicion,idtipodocumento) values ($idusuario,$tipocontrato, $inmueble, $inquilino, $fiador, '$fechainicio', '$fechatermino', $deposito, false,false, true,1)";
	
		$operacion = mysql_query($sql);
		$idc = mysql_insert_id();
	}
	else
	{
		switch ($accion)
		{
		case 0://siguiente del paso 1 para actualizar
			//echo $fiador;
			$sql = "update contrato set idtipocontrato=$tipocontrato, idinmueble=$inmueble, idinquilino=$inquilino, idfiador=$fiador, fechainicio='$fechainicio', fechatermino='$fechatermino', deposito=" . number_format($deposito, 2, '.', '') . ", concluido=false, litigio=false, enedicion=true, idasesor=$idasesor , idapartado=$apartado, observacionesc='$observacionesc' where idcontrato =$idc ";
			$operacion = mysql_query($sql);
			break;
		case 1: //Agregar cobro
			
			//$sql="insert into cobros (idcontrato, idperiodo, idtipocobro, idprioridad, cantidad, interes, iva, idrgrupo) values ($idc, $periodo, $tipocobro, $prioridad,$cantidad, $interes, $iva,$recibo)";
			$sql="insert into cobros (idcontrato, idperiodo, idtipocobro, idprioridad, cantidad, interes, iva, idrgrupo) values ($idc, $periodo, $tipocobro, $prioridad," . number_format($cantidad, 2, '.', '') . "," . number_format($interes, 2, '.', '') . " ," . number_format($iva, 2, '.', '') . " ,$recibo)";
			$operacion = mysql_query($sql);
			$periodo="";
			$tipocobro="";
			$prioridad="";
			$cantidad="";
			$interes="";
			$iva="";
			$recibo="";
			break;
		
		case 2: //Borrar cobro
			$sql="delete from cobros where idcobros=$idcobro";
			$operacion = mysql_query($sql);
		}
	}
	
	$sql="select * from periodo";
	$periodoselect= "<select name=\"periodo\"><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($periodo==$row["idperiodo"])
		{
			$seleccionopt=" SELECTED ";
		}
		$periodoselect .= "<option value=\"" . $row["idperiodo"] . "\" $seleccionopt>" . $row["nombre"] . "</option>";

	}
	$periodoselect .="</select>";
	
	$sql="select * from tipocobro";
	$tipocontratoselect= "<select name=\"tipocobro\"><option value=\"0\">Seleccione uno de la lista</option>";;
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($tipocobro==$row["idtipocobro"])
		{
			$seleccionopt=" SELECTED ";
		}
		$tipocontratoselect .= "<option value=\"" . $row["idtipocobro"] . "\" $seleccionopt>" . $row["tipocobro"] . "</option>";

	}
	$tipocontratoselect .="</select>";
	
	
	$sql="select * from prioridad";
	$prioridadselect= "<select name=\"prioridad\"><option value=\"0\">Seleccione uno de la lista</option>";;
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($prioridad==$row["idprioridad"])
		{
			$seleccionopt=" SELECTED ";
		}
		$prioridadselect .= "<option value=\"" . $row["idprioridad"] . "\" $seleccionopt>" . $row["prioridad"] . "</option>";

	}
	$prioridadselect .="</select>";
	
	
	$sql="select * from rgrupo";
	$rgruposelect= "<select name=\"recibo\"><option value=\"0\">Seleccione uno de la lista</option>";;
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$seleccionopt="";
		if ($recibo==$row["idrgrupo"])
		{
			$seleccionopt=" SELECTED ";
		}
		$rgruposelect .= "<option value=\"" . $row["idrgrupo"] . "\" $seleccionopt>" . $row["rgrupo"] . "</option>";

	}
	$rgruposelect .="</select>";
	

	$listacobros="<table border=\"1\"><tr><th>Id</th><th>T. Cobro</th><th>Periodo</th><th>Prioridad</th><th>Cantidad</th><th>Interes</th><th>IVA</th><th>Acciones</th></tr>";
		$sql="select * from cobros, tipocobro, periodo,prioridad where cobros.idprioridad=prioridad.idprioridad and cobros.idperiodo = periodo.idperiodo and cobros.idtipocobro = tipocobro.idtipocobro and cobros.idcontrato = $idc";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$listacobros .= "<tr><td>" . $row["idcobros"] . "</td>";
			$listacobros .= "<td>" . $row["tipocobro"]  . "</td>";
			$listacobros .= "<td>" . $row["nombre"] . "</td>";
			$listacobros .= "<td>" . $row["prioridad"] . "</td>";
			$listacobros .= "<td>" . $row["cantidad"] . "</td>";
			$listacobros .= "<td>" . $row["interes"] . "</td>";
			$listacobros .= "<td>" . $row["iva"] . "</td>";
			$listacobros .= "<td><input type=\"button\" value=\"Borrar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&paso=2&idc=$idc&idcobro=" . $row["idcobros"] . "')\"></td></tr>";		
		}
		$listacobros .="</table>";


$html = <<<paso2
<form>
<h3 align="center">Configuraci&oacute;n de pagos periodicos para el contrato $idc</h3>
<center>
<table border="1">
<tr>
	<td valign="top">
		<table border="1">
		<tr>
			<td>Tipo Cobro</td>
			<td>
				$tipocontratoselect
			</td>
		</tr>			
		<tr>
			<td>Periodo</td>
			<td>
				$periodoselect
			</td>
		</tr>
			
		<tr>
			<td>Prioridad</td>
			<td>
				$prioridadselect
			</td>
		</tr>	
		<tr>
			<td>Cantidad $</td>
			<td><input type="text" name="cantidad" value="$cantidad"></td>
		</tr>
		<tr>
			<td>IVA </td>
			<td><input type="text" name="iva" value="$iva"></td>
		</tr>
		<tr>
			<td>Interes (n/100)</td>
			<td><input type="text" name="interes" value="$interes"></td>
		</tr>
		
		<tr>
			<td>Recibos de</td>
			<td>
				$rgruposelect
			</td>
		</tr>	
		
		
		</table>
	</td>
	<td valign="top">
		$listacobros
	</td>
	
</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="button" name="agregar" value="Agregar" onClick="cargarSeccion('$dirscript','contenido','accion=1&paso=2&idc=$idc&periodo='+periodo.value+'&tipocobro='+ tipocobro.value +'&prioridad='+prioridad.value+'&cantidad='+cantidad.value + '&interes='+ interes.value + '&iva=' + iva.value + '&recibo='+ recibo.value)">&nbsp;&nbsp;
				<input type="button" name="siguiente" value="Siguiente >" onClick="cargarSeccion('$dirscript','contenido','accion=0&paso=5&idc=$idc')">
			</td>
		</tr>

</table>


</form>
</center>
paso2;
	echo CambiaAcentosaHTML($html);
	break;
//***************** fin de la asignacin de cobros

case 4: // Confirmacin del contrato y asignacin de cobros en el historial para los primeros cobros
	//echo $seleccion;
	$idinmueble="";
	if($accion=="0") // para mostrar lo que se captur
	{
		$sql = "select inquilino.nombre as nombrei, inquilino.nombre2 as nombre2i, inquilino.apaterno as apaternoi, inquilino.amaterno as amaternoi, fiador.nombre as nombref, fiador.nombre2 as nombre2f, fiador.apaterno as apaternof, fiador.amaterno as amaternof, tipocontrato, fechainicio, fechatermino, contrato.deposito,observacionesc, inmueble.idinmueble as idinm, calle, numeroext, numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp,prioridad, periodo.nombre as nombrep, tipocobro, rgrupo, cantidad, interes, iva, cobros.idcobros as idcob, contrato.idasesor as asesor, referencia, apartado.idapartado from contrato, inmueble, inquilino, fiador, tipocontrato, cobros, prioridad, periodo, tipocobro, rgrupo, apartado  where contrato.idinmueble = inmueble.idinmueble and contrato.idinquilino = inquilino.idinquilino and contrato.idfiador = fiador.idfiador and contrato.idtipocontrato = tipocontrato.idtipocontrato and contrato.idcontrato = cobros.idcontrato and cobros.idprioridad = prioridad.idprioridad and cobros.idperiodo = periodo.idperiodo and cobros.idtipocobro = tipocobro.idtipocobro and cobros.idrgrupo = rgrupo.idrgrupo and contrato.idapartado = apartado.idapartado and contrato.idcontrato = $idc";
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
				$referencia =$row["referencia"];

				$sqlasesor="SELECT * FROM asesor WHERE idasesor=".$row["asesor"];
				$asesorOp = mysql_query($sqlasesor);
				$asesorRow = mysql_fetch_array($asesorOp);
				$asesorSel = $asesorRow["nombre"]." ". $asesorRow["apellido"];

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
		
        //para los mantenimentos seleccionados
		$sqlidc = "update contrato set seleccion = '$seleccion' where idcontrato = $idc";
		$operacion = mysql_query($sqlidc);
		//para bloquear el apartado
        //echo $sqlidc = "update apartado set estatus = 'Fir' where idapartado = " . $row["idapartado"];
		//$operacion = mysql_query($sqlidc);		
		
		
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
		
				
		
	if ($idusuario==19 ||$idusuario==51) {
	$botonaplicar="<input type=\"button\" name=\"aplicar\" value=\"Aplicar\" onClick=\"this.disabled=true;cargarSeccion('$dirscript','contenido','accion=1&paso=4&idc=$idc&inmueble=$idinmueble&seleccion=$seleccion')\" $txtagregar>";
}
else{
	$botonaplicar="<input type=\"button\" name=\"aplicar\" value=\"Aplicar\" onClick=\"this.disabled=true;cargarSeccion('$dirscript','contenido','accion=1&paso=4&idc=$idc&inmueble=$idinmueble&seleccion=$seleccion')\" disabled>";
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
	<td><b>Observaciones</b></td>
	<td>$observacionesc</td>
</tr>
<tr>
	<td><b>Referencia</b></td>
	<td>$referencia</td>
</tr>
<tr>
	<td><b>Asesor</b></td>
	<td>$asesorSel</td>
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
		$botonaplicar
	</td>
</tr>

</table>


</form>
paso3;
		echo CambiaAcentosaHTML($html);
	}
	else//para aplicar el contrato en el historial y cambiar de en edicion a falso
	{
	
		//verificar los datos para ver si es apartado y aplicar los cargos
		$sql = "select * from apartado where idinmueble = $inmueble and cancelado = 0";
		$operacion = mysql_query($sql);
		$cantidadapa=0;
		$adicional = ".  Se aplic&oacute; el dep&oacute;sito ";
		if(mysql_num_rows($operacion)>0)
		{
			$row=mysql_fetch_array($operacion);
			$cantidadapa=$row["deposito"];
			$idapa =$row["idapartado"];
			
		}
		
		
	
		$fechas = New Calendario;
		//$fechas->DatosConexion('localhost','root','','bujalil');
		//actualizar contrato y colocarlo como enedicion = false
		
		$sql="update contrato set enedicion=false where idcontrato = $idc";
		$operacion = mysql_query($sql);
	
		//tomar el dato de inicio de contrato para la aplicain del historial
		$sql="select * from contrato where idcontrato =$idc";
		$operacion = mysql_query($sql);
		extract( mysql_fetch_array($operacion));
		
		//Crear en el historial los cobros que fueron asociados al contrato
		
		
		//agrego la direccin del inmueble rentado a los datos del inquilino
		$sql="select * from inmueble i, pais p where i.idpais=p.idpais and i.idinmueble = $idinmueble";
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
		
		
		//verifico si el inquilino ya tiene direccion fiscal si no la tiene, se asigna si la tiene no hace nada
		
		$sqld = "select * from inquilino where idinquilino = $idinquilino";
		$operaciond = mysql_query($sqld);
		$rowd = mysql_fetch_array($operaciond);
		
		if(is_null($rowd["direccionf"])==true || trim($rowd["direccionf"])=='' )
		{
			$sql = "update inquilino set direccionf= '" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . "',idestado = " . $row["idestado"] . ", colonia = '" . $row["colonia"] . "',cp = '" . $row["cp"] . "',delegacion = '" . $row["delmun"] . "',callei = '" . $row["calle"] . "',noexteriori = '" . $row["numeroext"] . "',nointeriori = '" . $row["numeroint"] . "',coloniai = '" . $row["colonia"] . "',cpi = '" . $row["cp"] . "',delmuni = '" . $row["delmun"] . "',paisi = '" . $row["pais"] . "',idestadoi = " . $row["idestado"] . " where idinquilino = $idinquilino";
			$operacion = mysql_query($sql);
		}
		
		//echo $sql = "update inquilino set direccionf= '" . $row["calle"] . " " . $row["numeroext"] . " " . $row["numeroint"] . "',idestado = " . $row["idestado"] . ", colonia = '" . $row["colonia"] . "',cp = '" . $row["cp"] . "',delegacion = '" . $row["delmun"] . "',callei = '" . $row["calle"] . "',noexteriori = '" . $row["numeroext"] . "',nointeriori = '" . $row["numeroint"] . "',coloniai = '" . $row["colonia"] . "',cpi = '" . $row["cp"] . "',delmuni = '" . $row["delmun"] . "',paisi = '" . $row["pais"] . "',idestadoi = " . $row["idestado"] . " where idinquilino = $idinquilino";
		//$operacion = mysql_query($sql);
		
		
		
		$final="<table border='1'>";
		$final2="<table border='1'>";
		$va=0;
		$fechagenerado = date("Y") . "-" . date("m") . "-" . date("d");
		$sql="select * from cobros,periodo, tipocobro where cobros.idtipocobro=tipocobro.idtipocobro and cobros.idperiodo=periodo.idperiodo and idcontrato =$idc order by cobros.idprioridad";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$fechagsistema =mktime(0,0,0,substr($fechainicio, 5, 2),substr($fechainicio, 8, 2),substr($fechainicio, 0, 4));
			$fechanaturalpago = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
			//verificar aqui si el tipo de prioridad, todas se generan para pago inmediato e iniciar con el proceso
			//de generacion de proximos vencimientos, para el INMEDIATO, poner fecha de vencimiento automatico de
			//un mes para fecha de vencimiento, aunque se deje la fecha de gracia el dia de la generacion

			$fechagracia = $fechas->fechagracia($fechanaturalpago);
			
			
			if($cantidadapa>0)
			{
				//$aux = $cantidadapa - ($row["cantidad"] + $row["iva"] );
				$aux = $cantidadapa - ($row["cantidad"] * (1 + ($row["iva"]/100)) );
				//echo "<br>$aux<br>";
				if($aux>0)
				{
					//$cantidadapa = $cantidadapa - ($row["cantidad"] + $row["iva"] );
					$cantidadapa = $cantidadapa - ($row["cantidad"] * (1 + ($row["iva"]/100)) );
					//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechagenerado . "'," . ($row["cantidad"] + $row["iva"] ) . "," . $row["iva"] . ",false, true ,false,false,'Aplicado del apartado',1)";
					//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechagenerado . "'," .  number_format(($row["cantidad"] + $row["iva"] ), 2, '.', '')  . "," .  number_format($row["iva"], 2, '.', '') . ",false, true ,false,false,'Aplicado del apartado',1,".$row["idcategoriat"].")";
					$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechagenerado . "'," .  number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '')  . "," .  number_format($row["iva"], 2, '.', '') . ",false, true ,false,false,'Aplicado del apartado',1,".$row["idcategoriat"].")";
					$va=1;
					
									
					
					if($row["idperiodo"]!=1)
					{
						//echo "<br>$sql1";
						$operacion1 = mysql_query($sql1);
						
						$idhistoriaf=mysql_insert_id();						
						$funcionrecibo="nuevaVP(" . $idhistoriaf . ",1)";
						//Crear el nuevo pago si se pago por completo						
						$final .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . ($row["cantidad"] * (1 + ($row["iva"]/100))  ) . "</td><td>Pagado</td><td>";
						$final .= '<p><input type="button" value="' . CambiaAcentosaHTML($row["tipocobro"]) . $addtext . ' (Recibo F)" onClick="' . $funcionrecibo . '"> </p>';
						$final .="</td></tr>";
						$final2 .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . ($row["cantidad"] * (1 + ($row["iva"]/100))  ) . "</td><td>Pagado</td><td>";
						$final2 .= '<p>Aplicado del apartado</p>';
						$final2 .="</td></tr>";
						$va=0;							
						//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "','" . $fechagracia . "'," . $row["cantidad"] . "," . $row["iva"] . ",false, false,false,false,1)";
						//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "','" . $fechagracia . "'," . number_format($row["cantidad"], 2, '.', '') . "," . number_format($row["iva"], 2, '.', '')  . ",false, false,false,false,1,".$row["idcategoriat"].")";
						$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "','" . $fechagracia . "'," . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "," . number_format($row["iva"], 2, '.', '')  . ",false, false,false,false,1,".$row["idcategoriat"].")";
					}
				}
				else
				{
					if($aux ==0)
					{
						$cantidadapa = $cantidadapa - ($row["cantidad"] * (1 + ($row["iva"]/100)) );
						//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . ($row["cantidad"] + $row["iva"] ) . "," . $row["iva"] . ",false, true ,false,false,'Aplicado del apartado',1)";
						//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . number_format(($row["cantidad"] + $row["iva"] ), 2, '.', '')  . "," . number_format($row["iva"], 2, '.', '')  . ",false, true ,false,false,'Aplicado del apartado',1,".$row["idcategoriat"].")";
						$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '')  . "," . number_format($row["iva"], 2, '.', '')  . ",false, true ,false,false,'Aplicado del apartado',1,".$row["idcategoriat"].")";
						$va=1;
						if($row["idperiodo"]!=1)
						{
							//echo "<br>$sql1";
							$operacion1 = mysql_query($sql1);
							
						$idhistoriaf=mysql_insert_id();						
						$funcionrecibo="nuevaVP(" . $idhistoriaf . ",1)";
						//Crear el nuevo pago si se pago por completo						
						$final .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "</td><td>Pagado</td><td>";
						$final .= '<p><input type="button" value="' . CambiaAcentosaHTML($row["tipocobro"]) . $addtext . ' (Recibo F)" onClick="' . $funcionrecibo . '"> </p>';
						$final .="</td></tr>";	
						$final2 .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "</td><td>Pagado</td><td>";
						$final2 .= '<p>Aplicado del apartado</p>';
						$final2 .="</td></tr>";
						
							$va=0;
							//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "','" . $fechagracia . "'," . $row["cantidad"] . "," . $row["iva"] . ",false, false,false,false,1,".$row["idcategoriat"].")";
							$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechanaturalpago . "','" . $fechagracia . "','" . $fechagracia . "'," . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "," . $row["iva"] . ",false, false,false,false,1,".$row["idcategoriat"].")";
						}
						
					
					}
					else
					{
						
						$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, fechapago, cantidad, iva, vencido, aplicado, interes, condonado,notas,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . number_format( $cantidadapa, 2, '.', '') . "," . number_format($row["iva"], 2, '.', '') . ",false, true ,false,false,'Aplicado del apartado',1,".$row["idcategoriat"].")";
						//echo "<br>$sql1";
						$operacion1 = mysql_query($sql1);
						
						
						$idhistoriaf=mysql_insert_id();						
						$funcionrecibo="nuevaVP(" . $idhistoriaf . ",2)";
						//Crear el nuevo pago si se pago por completo						
						$final .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . $cantidadapa . "</td><td>Pagado</td><td>";
						$final .= '<p><input type="button" value="' . CambiaAcentosaHTML($row["tipocobro"]) . $addtext . ' (Recibo NF)" onClick="' . $funcionrecibo . '"> </p>';
						$final .="</td></tr>";							
						$final2 .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . $cantidadapa . "</td><td>Pagado</td><td>";
						$final2 .= '<p>Aplicado del apartado</p>';
						$final2 .="</td></tr>";
						
						
						
						$va=0;
						$aux = $aux * (-1);
						//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . $aux . ",0,false, false ,false,false,1)";
						$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . number_format($aux, 2, '.', '') . ",0,false, false ,false,false,1,".$row["idcategoriat"].")";
						$cantidadapa = 0;
					}
				
				}
			
				//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . $row["cantidad"] . "," . $row["iva"] . ",false, false,false,false)";
			}						
			else
			{
				//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . $row["cantidad"] . "," . $row["iva"] . ",false, false,false,false,1)";
				//$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . number_format($row["cantidad"], 2, '.', '') . "," . number_format($row["iva"], 2, '.', '') . ",false, false,false,false,1,".$row["idcategoriat"].")";
				$sql1="insert into historia (idcobros, idcontrato, idprioridad, idusuario, fechagenerado, fechanaturalpago, fechagracia, fechavencimiento, cantidad, iva, vencido, aplicado, interes, condonado,idmetodopago,idcategoriat) values (" . $row["idcobros"] . ",$idc, " . $row["idprioridad"] . ",$idusuario,'" . $fechagenerado . "','" . $fechainicio . "','" . $fechainicio . "','" . $fechainicio . "'," . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "," . number_format($row["iva"], 2, '.', '') . ",false, false,false,false,1,".$row["idcategoriat"].")";
			
			
			}
			//echo "<br>$sql1";
			$operacion1 = mysql_query($sql1);
			
			
//*********************************introducir la generacin del resto de los pagos*********************************
$fechaa="";
$fechat="";
$idco="";
$idcc="";
//$lista="";
$e="";
$i=0;
$HoyFecha = date('Y') . "-" . date('m') . "-" . date('d');


	$sqlh="select c.idcontrato as idc, cb.idcobros as idcc, numero, idmargen, cb.cantidad as cantidadc, cb.iva as ivac, cb.idprioridad as idprioridadc,  fechatermino, max(fechanaturalpago) as fechau, idcategoriat from historia h, contrato c, cobros cb, periodo where  h.idcontrato = c.idcontrato and concluido=false and cb.idcobros = h.idcobros and cb.idperiodo=periodo.idperiodo and numero >=1 group by c.idcontrato, cb.idcobros, fechatermino, cb.cantidad , cb.iva, cb.idprioridad ";
	$operacionh = mysql_query($sqlh);
	while($rowh = mysql_fetch_array($operacionh))
	{
		
		if ($idco != $rowh["idc"])
		{
			$idco=$rowh["idc"];
			$fechat = $rowh["fechatermino"];
		}
		$fechaa = $rowh["fechau"];
		//$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));		
		//$lista .= "<p> Se generarn parael contrato $idc, el cobro $idcc en las fechas partiendo de $fechaa a la fecha de termino $fechat:</p>";
		//$fechaa = $fechas->calculafecha($fechagsistema, $row["numero"], $row["idmargen"]);
//		$lista .= "<ol>";
		$i=0;
		$sqlh2="";
		while ($fechaa < $fechat )
		{
			$i++;
			if($sqlh2 != "")
			{
				//$lista .= $e;
				//$lista .= $sql2 . "<br>";
				//ejecuta el sql
				$operacionh2 = mysql_query($sqlh2);
			}
			
			$idcc=$rowh["idcc"];
			
			$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));
			$fechaa = $fechas->calculafecha($fechagsistema, $rowh["numero"], $rowh["idmargen"]);
			$ProxVencimiento=$fechas->fechagracia($fechaa);
			
			//$e = "<li>$i  $fechaa   y fecha de gracia $ProxVencimiento </li>";
			
			//$sqlh2="insert into historia (idcontrato,idcobros,fechagenerado,fechanaturalpago,fechagracia,cantidad,iva,interes,vencido,aplicado, idusuario,idprioridad,fechavencimiento,condonado,idmetodopago,idcategoriat) values(";			
			//$sqlh2 .= $idco . "," . $idcc . ",'" . $HoyFecha . "', '" . $fechaa  . "', '" . $ProxVencimiento . "'," .  number_format($rowh["cantidadc"],2,".","") . "," . number_format($rowh["ivac"],2,".","") . ",false,false,false," . $misesion->usuario . "," . $rowh["idprioridadc"] . " , '" . $ProxVencimiento . "',false,1,".$rowh["idcategoriat"].");";
			
            $sqlh2="insert into historia (idcontrato,idcobros,fechagenerado,fechanaturalpago,fechagracia,cantidad,iva,interes,vencido,aplicado, idusuario,idprioridad,fechavencimiento,condonado,idmetodopago,idcategoriat) values(";			
			$sqlh2 .= $idco . "," . $idcc . ",'" . $HoyFecha . "', '" . $fechaa  . "', '" . $ProxVencimiento . "'," .  number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "," . number_format($rowh["ivac"],2,".","") . ",false,false,false," . $misesion->usuario . "," . $rowh["idprioridadc"] . " , '" . $ProxVencimiento . "',false,1,".$rowh["idcategoriat"].");";
			//echo "<br>";
				
		}
		$e="";
//		$lista .= "</ol>";
		//$lista .= "<br>";
	}			
			
			
//*******************************************************************************************************************
			
			if($va==1)
			{
						$idhistoriaf=mysql_insert_id();						
						$funcionrecibo="nuevaVP(" . $idhistoriaf . ",1)";
						//Crear el nuevo pago si se pago por completo						
						$final .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "</td><td>Pagado</td><td>";
						$final .= '<p><input type="button" value="' . CambiaAcentosaHTML($row["tipocobro"]) . $addtext . ' (Recibo F)" onClick="' . $funcionrecibo . '"> </p>';
						$final .="</td></tr>";
						$final2 .="<tr><td>$fechagenerado</td>" . CambiaAcentosaHTML($row["tipocobro"]) . " </td><td>" . number_format(($row["cantidad"] * (1 + ($row["iva"]/100)) ), 2, '.', '') . "</td><td>Pagado</td><td>";
						$final2 .= '<p>Aplicado del apartado</p>';
						$final2 .="</td></tr>";						
						$va=0;				
			
			
			}
			
	
	
		}
		$final .= "</table>";
		$final2 .= "</table>";
	
//insersion de registros para manteinmientos***********
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
				
			$sqlh="insert into mantenimiento (idcontrato,idtiposervicio,mantenimiento,fechamant,terminadom) values ($idc,". $b[1] . ",'Generado automaticamente','$HoyFecha',false)";
			$operacionh = mysql_query($sqlh);
		
			$idmantemiento = mysql_insert_id();	
			//$enviocorreo->enviar("miguel@padilla-bujalil.com.mx, contabilidad@padilla-bujalil.com.mx ", "Cobro realizado", "Se asigno el mantenimiento al contrato $idc");			
				
			$sqlh="select c.idcontrato as idc, numero, idmargen,  fechatermino, fechainicio from mantenimiento m, contrato c, tiposervicio ts, periodo where  m.idcontrato = c.idcontrato and concluido=false and m.idtiposervicio = ts.idtiposervicio and ts.idperiodo=periodo.idperiodo and numero >=1 and m.idmantenimiento =$idmantemiento";
			$operacionh = mysql_query($sqlh);
			while($rowh = mysql_fetch_array($operacionh))
			{

				$idco=$rowh["idc"];
				$fechat = $rowh["fechatermino"];

				$fechaa = $rowh["fechainicio"];

				$i=0;
				$sqlh2="";
				while ($fechaa < $fechat )
				{
					$i++;
					if($sqlh2 != "")
					{
						//$lista .= $e;
						//$lista .= $sql2 . "<br>";
						//ejecuta el sql
						$operacionh2 = mysql_query($sqlh2);
					}
				
					//$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));
					//$fechaa = $fechas->calculafecha($fechagsistema, $rowh["numero"], $rowh["idmargen"]);
					$ProxVencimiento=$fechas->fechagracia($fechaa);

					$sqlh2 = "insert into mantenimientoseg (idmantenimiento, fechams, horams, fechacita,horacita,cambiofecha,cerrado) values "; 
					$sqlh2 .= "($idmantemiento,'$ProxVencimiento','$horam','$ProxVencimiento','$horam',0,0)";

					$fechagsistema =mktime(0,0,0,substr($fechaa, 5, 2),substr($fechaa, 8, 2),substr($fechaa, 0, 4));
					$fechaa = $fechas->calculafecha($fechagsistema, $rowh["numero"], $rowh["idmargen"]);
				
				}
					if($sqlh2 != "")
					{
						//$lista .= $e;
						//$lista .= $sql2 . "<br>";
						//ejecuta el sql
						//echo $sqlh2;
						$operacionh2 = mysql_query($sqlh2);
					}	


			}

			$sqlh="select * from mantenimientoseg where idmantenimiento = $idmantemiento order by fechams,idmantenimientoseg";
			$operacionh = mysql_query($sqlh);
			$totalr = mysql_num_rows($operacionh);
			$i=0;
			while($rowh = mysql_fetch_array($operacionh))
			{
					$i++;
					$sqlh2="update mantenimientoseg set novisita ='$i de $totalr' where idmantenimientoseg=" . $rowh["idmantenimientoseg"];
					$operacionh2 = mysql_query($sqlh2);
			}					
				
				
				
							
			}
		}
				
		
		
		if($cantidadapa>0)
		{
			$adicional .= " y sobro la antidad de $ $cantidadapa";
			//$sql = "update apartado set cantidad = $cantidadapa, cancelado = true where idapartado = $idapa";
			$sql = "update apartado set cantidad =" . number_format($cantidadapa,2,".","") . " , cancelado = true, estatus='Fir' where idapartado = $idapa";
			$operacion = mysql_query($sql);
		
		}
		else
		{
			
			$adicional .= " por completo";
			$sql = "update apartado set devolucion=true, cancelado = true, estatus='Fir' where idapartado = $idapa";
			$operacion = mysql_query($sql);
			
		}
		$edocuenta="window.open( '$ruta/edocuenta.php?contrato=$idc');";
		$enviomail="window.open( '$ruta/enviocontrato.php?idc=$idc&correo='+correo.value);";
		
		$accionboton="<input type =\"button\" value=\"Ver\" onClick=\"$edocuenta\"  />";
		
		$enviomail =  file_get_contents("http://rentascdmx.com/".$ruta."/enviocontrato.php?idc=$idc&correo=bancos@padillabujalil.com");
		
		echo "<br> Contrato $idc Generado satisfactoriamente $accionboton<br><br>$final<br>$enviomail";

		$sqlSaldo = "SELECT * FROM diferencia WHERE idinquilino=$idinquilino AND idinmueble=$idinmueble AND aplicado IS NULL";
		$operacionSaldo = mysql_query($sqlSaldo);
		$rowSaldo = mysql_fetch_array($operacionSaldo);
		if(is_null($rowSaldo)==false && sizeof($rowSaldo)>1)
		{	
			$saldoRuta="cargarSeccion('$ruta/cobro.php','contenido','paso=1&idcontrato=$idcontrato&efectivo=".($rowSaldo["importe"] * 1)."&idmetodopago=".$rowSaldo["metodopagod"]."&fechapago=".$rowSaldo["fecha"]."&iddiferencia=".$rowSaldo["iddiferencia"]."');";
			$botonSaldo="<br><strong>Cuenta con saldo a Favor. </strong><input type =\"button\" value=\"Aplicar Saldo\" onClick=\"$saldoRuta\"  />";
		}else{
			$botonSaldo="";
		}
		echo $botonSaldo;
		
		
		
		//$enviocorreo->enviar("mizocotroco@hotmail.com", "Cobro realizado", "Contrato Generado satisfactoriamente $adicional<br><br>$final2");
		//$enviocorreo->enviar("miguel@padilla-bujalil.com.mx, contabilidad@padilla-bujalil.com.mx ", "Cobro realizado", "Contrato Generado satisfactoriamente $adicional<br><br>$final2");
		
	
	}


	break;
//******************* fin de la cofirmacin ***********************
	case 5:

		$sql5 = "select * from tiposervicio";
		$operacion = mysql_query($sql5);
		$tsvariable = "";
		$ts="";
		while($row = mysql_fetch_array($operacion))
		{		
			$ts .="<tr><td><input type='checkbox' name = 'cts" . $row["idtiposervicio"] . "'>"  . $row["tiposervicio"] ;
			$tsvariable .= "ts_" . $row["idtiposervicio"] . "*' + cts" . $row["idtiposervicio"] . ".checked + '|" ;
		}

		//ts = "<table border = '0'>$ts</table>";

$html = <<<paso5
<form>
<h3>Mantenimientos</h3>
<table border="1">
$ts
<tr>
	<td align="center">
		<input type="button" name="aplicar" value="Siguiente" onClick="seleccion = '$tsvariable';cargarSeccion('$dirscript','contenido','accion=0&paso=4&idc=$idc&seleccion=' + seleccion)">
	</td>
</tr>
</table>



</form>
paso5;

	echo $html;
		
	
}

// Fin del proceso firmado como usuario valido
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}


?>