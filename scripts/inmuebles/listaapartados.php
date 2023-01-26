<?PHP
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

echo <<<style
<style>
	input[type=button]{
		margin:5px 0;
	}
</style>
style;

//Modulo
$accion = @$_GET["accion"];
$id=@$_GET["id"];

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='listaapartados.php'";
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


	$sql2="select * from submodulo where archivo ='apartado.php'";
	$operacion2 = mysql_query($sql2);
	while($row2 = mysql_fetch_array($operacion2))
	{
		$dirscript2= $row2['ruta'] . "/" . $row2['archivo'];
		$ruta2=$row2['ruta'];
		$priv2 = $misesion->privilegios_secion($row2['idsubmodulo']);
		$priv2=split("\*",$priv2);

	}
	
	if ($priv2[2]=='1'){
		$txteditar2 = "";
	}else{
		$txteditar2 = " DISABLED ";
	}

	$sql="";
	switch($accion)
	{
	case "1": // Cancelar

		$sql="update apartado set cancelado=1 where idapartado=$id";
		//echo "<br>Agrego";
		$nombremodulo="";
		$orden="";
		break;

	case "2": //Devolver

		$sql="update apartado set devolucion=1 where idapartado=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Borrar

		$sql = "delete from apartado where idapartado=$id";
		///echo "<br>Actualizo";
		$nombremodulo="";
		$orden="";
		break;
	case "4": // Duplicar
		$fechas = New Calendario;
		$hoy=mktime(0,0,0,date("m"),date("d"),date("Y"));
		$vencimiento = $fechas->calculafecha($hoy, $cantidad, $medida);
		
		
		$today = date("mdy");
		$number = $today.sprintf("%'.05d",$inmueble);
		$referencia = referenciabanorte($number);
		
		$sql = "insert into apartado (idinmueble, nombre, deposito, notas, vencimiento,cancelado,devolucion,referencia,estatus) values ($inmueble, '$nombre', $deposito, '$notas','$vencimiento',false,false,'$referencia','Act')";
		
		$operacion = mysql_query($sql);
		//echo "se aplico la consulta $operacion.";
		$idApartado = mysql_insert_id();
		///echo "<br>Actualizo";
		$nombremodulo="";
		$orden="";
		break;

	case "5":
		$sql="update apartado set devolucion=0, cancelado=0 where idapartado=$id";
		//echo "<br>Borro";
		$id="";
		break;
	}
	if($sql!="")
	{

		$operacion = mysql_query($sql);

	}
	


echo <<<formulario1
<center>
<h1>Lista de Apartado</h1>
</center>
formulario1;

	$sql="select idapartado, nombre, calle, numeroint, numeroext, deposito, vencimiento, a.notas, cancelado, devolucion, estatus, referencia, a.idinmueble as idinmueble  from apartado a, inmueble i where a.idinmueble=i.idinmueble order by vencimiento";
	$datos=mysql_query($sql);
	echo "<div name=\"busquedacobro\" id=\"busquedacobro\" class=\"scroll\">";
	echo "<table border=\"1\"><tr><th>#</th><th>Nombre</th><th>Inmueble</th><th>Deposito</th><th>Vencimiento</th><th>Estatus</th><th>Referencia</th><th>Notas</th><th>Acciones</th></tr>";
	while($row = mysql_fetch_array($datos))
	{
		$sql2="select * from apartado where idinmueble = '".$row["idinmueble"]."' and (cancelado = 0 or devolucion = 0)  ";
		$datos2=mysql_query($sql2);
		$count = mysql_num_rows($datos2);
		
		if($count > 0){
			$nuevo_apartado_disabled = true;
		}else{
			$nuevo_apartado_disabled = false;
		}
		
		//$row2 = mysql_fetch_array($datos2);

		echo "<tr><td>" . $row["idapartado"] . "</td><td>" . CambiaAcentosaHTML($row["nombre"]) . "</td><td>" . CambiaAcentosaHTML($row["calle"] . " No. " . $row["numeroext"] . " " . $row["numeroint"] ) . "</td><td>" . CambiaAcentosaHTML($row["deposito"]) . "</td><td>" . CambiaAcentosaHTML($row["vencimiento"]) . "</td><td>" . CambiaAcentosaHTML($row["estatus"]) . "</td><td>" . CambiaAcentosaHTML($row["referencia"]) . "</td><td>" . CambiaAcentosaHTML($row["notas"]) . "</td><td>";

		if ($row["cancelado"]==0 )
		{
			//para cuando hay que poner el boton canclear
			echo "<input type=\"button\" value=\"Cancelar\" onClick=\"cargarSeccion('$dirscript','contenido','accion=1&id=" .  $row["idapartado"] . "' )\" $txteditar>";
		}
		elseif ($row["cancelado"]==1 && $row["devolucion"]==0)
		{
			//para cuando hay que poner devolver
			echo "<input type=\"button\" value=\"Devolver\" onClick=\"cargarSeccion('$dirscript','contenido','accion=2&id=" .  $row["idapartado"] . "' )\" $txteditar>";
			if($row["estatus"] === 'Fir') echo "<input type=\"button\" value=\"Nuevo Apartado\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea generar un nuevo apartado?')){cargarSeccion('$dirscript2','contenido','idApartadoOld=" .  $row["idapartado"]  . "' )}\" $txteditar2>";
			if($row["estatus"] === 'Act') echo "<input type=\"button\" value=\"Poner Disponible\" onClick=\"cargarSeccion('$dirscript','contenido','accion=5&id=" .  $row["idapartado"] . "' )\" $txteditar>";
		}
		else
		{
			//para cuando hay que poner borrar
			echo "<input type=\"button\" value=\"Borrar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea borrar &eacute;ste elemento?')){cargarSeccion('$dirscript','contenido','accion=3&id=" .  $row["idapartado"]  . "' )}\" $txtborrar>";
			if($row["estatus"] === 'Fir') echo "<input type=\"button\" value=\"Nuevo Apartado\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea generar un nuevo apartado?')){cargarSeccion('$dirscript2','contenido','idApartadoOld=" .  $row["idapartado"]  . "' )}\" $txteditar2>";
			if($row["estatus"] === 'Act') echo "<input type=\"button\" value=\"Poner Disponible\" onClick=\"cargarSeccion('$dirscript','contenido','accion=5&id=" .  $row["idapartado"] . "' )\" $txteditar>";
		}

		//echo "<input type=\"hidden\" name=\"id\" value=\"" . $row["idmodulo"] . "\">";
		echo "</td></tr>";
	}
	echo "</table></div>";


}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>