<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';


$accion=@$_GET["accion"];
$idcontrato=@$_GET["idcontrato"];
$filtro=@$_GET["filtro"];
$nombrei=@$_GET["nombrei"];
$contratoi=@$_GET["contratoi"];

$misesion = new sessiones;
$renglones="";
if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='cancelaciones.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] ;// . "/" . $row['archivo'];
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





	
	switch($accion)
	{
	case 1: //no concluir
		$sql="update contrato set concluido = false where idcontrato=$idcontrato ";
		$result = mysql_query ($sql);
		
        $sql="select * from  contrato  where idcontrato=$idcontrato ";
		$result0 = mysql_query ($sql);
		$row0 = mysql_fetch_array($result0);
		
        $sql="update apartado set estatus = 'Can', cancelado = 1 where idapartado=" . $rowo["idapartado"];
		$result = mysql_query ($sql);		
		
		
		break;
	case 2: //concluir
		$sql="update contrato set concluido = true, activo = false where idcontrato=$idcontrato ";
		$result = mysql_query ($sql);
		
        $sql="select * from  contrato  where idcontrato=$idcontrato ";
		$result0 = mysql_query ($sql);
		$row0 = mysql_fetch_array($result0);
		
        $sql="update apartado set estatus = 'Fir' , cancelado = 0 where idapartado=" . $rowo["idapartado"];
		$result = mysql_query ($sql);		
		
		break;
	case 3: //desactivar
		$sql="update contrato set activo = false where idcontrato=$idcontrato ";
		$result = mysql_query ($sql);
		break;
	case 4: //activar
		$sql="update contrato set activo = true where idcontrato=$idcontrato ";
		$result = mysql_query ($sql);
		break;
				
	}
	
	//echo $sql;
	$filtronombre="";
	if($nombrei!="")
	{
	
		$filtronombre=" and (CONCAT(nombre, ' ', apaterno, ' ', amaterno ) like '%$nombrei%' or CONCAT(calle, ' ', numeroext ) like '%$nombrei%')";
	
	}
	if ($contratoi!="") {
		$filtronombre=" and contrato.idcontrato LIKE '%$contratoi%'";
	}
	
	
	
	$sql="select contrato.idcontrato as idcontra,fechainicio, fechatermino, nombre, nombre2, apaterno, amaterno, calle, numeroext, numeroint, inmueble.colonia, delmun, estado, pais, activo from contrato, inquilino,inmueble, estado, pais  where contrato.idinmueble=inmueble.idinmueble and contrato.idinquilino= inquilino.idinquilino and inmueble.idestado = estado.idestado and	inmueble.idpais = pais.idpais and concluido = $filtro $filtronombre order by contrato.idcontrato";
	$result = mysql_query ($sql);

	while ($row = @mysql_fetch_array($result))
	{
	  //$idhist=$row["idhistoria"];
		if($filtro=="true")
		{
			$accionboton="<input type =\"button\" value=\"A Vigentes\" onClick=\"cargarSeccion ('$dirscript/listacc.php','listacc','filtro=true&idcontrato=" . $row["idcontra"] . "&accion=1');\"  />";
		}
		else
		{
			$accionboton="<input type =\"button\" value=\"Concluir (Terminar)\" onClick=\"cargarSeccion ('$dirscript/listacc.php','listacc','filtro=false&idcontrato=" . $row["idcontra"] . "&accion=2');\"  />";
		
		}
		
		//$concepto = $row["tipocobro"];
		
		if($row['activo']==1)
		{
			$accionboton .="<input type =\"button\" value=\"Desactivar\" onClick=\"cargarSeccion ('$dirscript/listacc.php','listacc','filtro=false&idcontrato=" . $row["idcontra"] . "&accion=3');\"  />";
		
		}
		else
		{
			$accionboton .="<input type =\"button\" value=\"Activar\" onClick=\"cargarSeccion ('$dirscript/listacc.php','listacc','filtro=false&idcontrato=" . $row["idcontra"] . "&accion=4');\"  />";
		}

		$renglones .= "<tr><td>" . $row["idcontra"] . "</td><td>" . $row["nombre"] . " " .  $row["nombre2"] . " " .  $row["apaterno"] . " " . $row["amaterno"] . " " . "</td><td>" . $row["calle"] . " " .  $row["numeroext"] . " - " .  $row["numeroint"] . "</td><td>" . $row["fechainicio"] . "</td><td>" . $row["fechatermino"] . "</td><td>$accionboton</td></tr>";

	}


$html = <<<fin

<table border=1 id="tlista">
<tr>
<th>Contrato</th><th>Inquilino</th><th>Inmueble</th><th>F. Inicio</th><th>F. T&eacute;rmino</th><th>Acci&oacute;n</th>
</tr>
$renglones
</table>

fin;
	echo CambiaAcentosaHTML($html);



}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}
?>