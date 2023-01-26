<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclass.php';
include 'banorte.php';


$paso=@$_GET["paso"];


$inmueble=@$_GET["inmueble"];
$inmueblen=@$_GET["inmueblen"];

$deposito=@$_GET["deposito"];

$medida=@$_GET["medida"];
$cantidad=@$_GET["cantidad"];

$nombre=@$_GET["nombre"];
$notas=@$_GET["notas"];
$referencia=@$_GET["referencia"];

$enviocorreo = New correo;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$idusuario = $misesion->usuario;
	$sql="select * from submodulo where archivo ='apartado.php'";
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

	//para el privilegio de editar, si es negado deshabilida el bot�n
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el bot�n
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
	
case 0: // para crear el cuestionario
	//accion = 0 Nuevo
	//accion = 1 editar existente

	$direccion="";

	$idApartadoOld = @$_GET["idApartadoOld"];
	$referenciaHTML = '<input type="hidden" name="referencia" >';
	if($idApartadoOld){
		$sql="select idapartado, nombre, calle, numeroint, numeroext, colonia, delmun, cp, a.idinmueble, deposito, vencimiento, a.notas, cancelado, devolucion,estatus,referencia  from apartado a, inmueble i where a.idinmueble=i.idinmueble and idapartado  = '$idApartadoOld'";
		$datos=mysql_query($sql);
		$row = mysql_fetch_array($datos);
		$inmueblen = "$row[2] No. $row[4] Int. $row[3] Col. $row[5] Alc. $row[6] C.P. $row[7]";
		$inmueble = $row[8];
		$referencia = $row["referencia"];
		$referenciaHTML = '
		<tr>
			<td>Referencia</td>
			<td>'.$referencia.'<input type="hidden" name="referencia" value="'.$referencia.'" ></td>
		</tr>
		';
		$nombre = $row["nombre"];
		$deposito = $row["deposito"];
		$notas = "Registro generado a partir del apartado con id: $idApartadoOld";
	}

$html = <<<paso1
<form>
<h3 align="center">Apartado</h3>
<center>
<table border="1">
<tr>
	<td valign="top">
		<table border="1">		
		<tr>
			<td>Inmueble</td>
			<td>
				<textarea rows="4" cols="30" name="inmueblen" id="inmueblen" disabled>$inmueblen</textarea><a  onClick="cargarSeccion('$ruta/bdatocontapa.php','bdato','cat=3')"><img src="imagenes/lupa0.png" height="13"></a>
				<input type="hidden" name="inmueble" value="$inmueble" id="inmueble">
			</td>
		</tr>
		<tr>
			<td>Nombre</td>
			<td>
				<input type="text" name="nombre" value="$nombre">
			</td>
		</tr>
		<tr>
			<td>Dep&oacute;sito</td>
			<td><input type="text" name="deposito" value="$deposito"></td>
		</tr>
		$referenciaHTML
		<tr>
			<td>Vigencia</td>
			<td>
				Medida
				<select name="medida" >
					<option value="0">Seleccione uno de la lista</option>
					<option value="3">Dias</option>
					<option value="2">Meses</option>				
				</select><br>
				Cantidad<input type="text" name="cantidad" value="">
			</td>
		</tr>
		<tr>
			<td>Notas</td>
			<td>
				<textarea rows="4" cols="30" name="notas" id="notas">$notas</textarea>
				
			</td>
		</tr>
		
		</table>
	</td>
	<td>
		<div id="bdato"></div>
	</td>
	
</tr>
<tr>
	<td colspan="2" align="center"><input type="button" name="siguietne" value="Confirmar" onClick="cargarSeccion('$dirscript','contenido','paso=1&nombre=' + nombre.value + '&inmueble=' + inmueble.value + '&medida=' + medida.value + '&cantidad=' + cantidad.value + '&notas=' + notas.value + '&deposito=' + deposito.value + '&inmueblen=' + inmueblen.value+ '&referencia=' + referencia.value)"></td>
</tr>
</table>


</form>
</center>
paso1;
	echo CambiaAcentosaHTML($html);
	break;
//************ fin paso 1 de la creacion del contrato ***************

case 1: // paso para la asignaci�n de cobros

	//$hoy=date("Y-m-d");
	$fechas = New Calendario;
	//tomar la media y la antidad y hacer la suam pertinente para el cambio de fecha
	//y aplicar el vencimiento
	$hoy=mktime(0,0,0,date("m"),date("d"),date("Y"));
	$vencimiento = $fechas->calculafecha($hoy, $cantidad, $medida);
	
	
	$today = date("mdy");
    $number = $today.sprintf("%'.05d",$inmueble);
    $referencia = ($referencia)?$referencia:referenciabanorte($number);
	
	$sql = "insert into apartado (idinmueble, nombre, deposito, notas, vencimiento,cancelado,devolucion,referencia,estatus) values ($inmueble, '$nombre', $deposito, '$notas','$vencimiento',false,false,'$referencia','Act')";
	
	$operacion = mysql_query($sql);
	//echo "se aplico la consulta $operacion.";
	$idApartado = mysql_insert_id();
	//echo "<br>el Id resultante fue $idApartado";
//hacer la consutla para la direcci�n del inmueble y mostrarla

	
	
$html = <<<paso2
<form>
<h3 align="center">Apartado</h3>
<center>
<table border="1">
<tr>
	<td valign="top">
		<table border="1">		
		<tr>
			<td>Inmueble</td>
			<td>
				<textarea rows="4" cols="30" name="inmueblen" id="inmueblen">$inmueblen</textarea>
				<input type="hidden" name="inmueble" value="$inmueble" id="inmueble">
			</td>
		</tr>
		<tr>
			<td>Nombre</td>
			<td>
				$nombre
			</td>
		</tr>
		<tr>
			<td>Dep&oacute;sito</td>
			<td>$deposito</td>
		</tr>
		<tr>
			<td>Vencimiento</td>
			<td>
				$vencimiento
			</td>
		</tr>
		<tr>
			<td>Notas</td>
			<td>
				<textarea rows="4" cols="30" name="notas" id="notas">$notas</textarea>
				
			</td>
		</tr>
		
		</table>
	</td>
	<td>
		<div id="bdato"></div>
	</td>
	
</tr>
<tr>
	<td colspan="2" align="center"><input type="button" name="siguietne" value="Generar Recibo" onClick="cargarSeccion('$dirscript','contenido','paso=2&nombre=' + nombre.value + '&inmueble=' + inmueble.value + '&medida=' + medida.value + '&cantidad=' + cantidad.value + '&notas=' + notas.value + '&deposito=' + deposito.value)"></td>
</tr>
</table>


</form>
</center>
paso2;
	echo CambiaAcentosaHTML($html);
	
		$mensaje = "Se ha realizado un apartado a las " . date("H:i") . " Hrs. del d�a " . date("d-M-Y") . " con las siguietes caracter&iacute;sticas\r\n\r\n<br><br>" . CambiaAcentosaHTML($html);;
		$enviocorreo->enviar("asanchez@padillabujalil.com", "Apartado de inmueble", $mensaje);
		//$enviocorreo->enviar("miguelmp@prodigy.net.mx,cemaj@prodigy.net.mx,lucero_cuevas@prodigy.net.mx,miguel_padilla@nextel.mx.blackberry.com", "Apartado de inmueble", $mensaje);
	
	break;


//******************* fin de la cofirmaci�n ***********************
}

// Fin del proceso firmado como usuario valido
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}


?>