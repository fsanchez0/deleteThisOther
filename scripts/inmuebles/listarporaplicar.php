<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
$poraplicar=@$_GET['poraplicar'];
$idcobros=@$_GET['idcobros'];
$agregarcobro=@$_GET['agregarcobro'];
$idcontrato=@$_GET['idcontrato'];
$borrar=@$_GET['borrar'];

$cabecera = "";

//modificacion, agregar campos para la generacion automatica de conceptos y que genere los cargos periodicos
//en la fecha siguiente natural de pago para todos los  cargos, incluyendo los inmediatos, donde la fecha natural de pago
//depende del la fecha de inicio del contrato.
$fechas = New Calendario;
$misesion = new sessiones;

if($misesion->verifica_sesion()=="yes")
{	
	$idusuario = $misesion->usuario;
	$sql="select * from submodulo where archivo ='listarporaplicar.php'";
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

	$HoyFecha = date('Y') . "-" . date('m') . "-" . date('d');
	if($agregarcobro==true)
	{
		$sql="select * from cargos_por_aplicar where idcobros=".$idcobros;
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			extract($row);
			 $sqli="INSERT INTO historia (idcobros,idcontrato,idusuario,idprioridad,fechagenerado,fechanaturalpago,fechagracia,fechapago,
			 fechavencimiento,cantidad,vencido,aplicado,notas,interes,iva,condonado,notacredito,notanc,observaciones,idmetodopago,archivotxt,txtok,archivopdf,pdfok,archivoxml,xmlok,archivopdfn,pdfnok,
				parcialde,hfacturacfdi,cuentapago,horaaplicado,impaplicado,idcategoriat) VALUES ('$idcobros','$idcontrato','$idusuario','$idprioridad','$fechagenerado','$fechanaturalpago','$fechagracia','$fechapago','$fechavencimiento',($cantidad*(1+($iva/100))),'$vencido','$aplicado','$notas','$interes','$iva','$condonado','$notacredito','$notanc','$observaciones','$idmetodopago','$archivotxt','$txtok','$archivopdf','$pdfok','$archivoxml','$xmlok','$archivopdfn','$pdfnok','$parcialde','$hfacturacfdi','$cuentapago','$horaaplicado','$impaplicado','$idcategoriat'); ";

	    	$sqli=str_replace("''", 'null', $sqli);
			$operacioni = mysql_query($sqli);
			$rowi = mysql_fetch_array($operacioni);	
		}
		 $sql="delete from  cargos_por_aplicar where idcobros=$idcobros AND idcontrato=$idcontrato";
			$operacion = mysql_query($sql);
			$row = mysql_fetch_array($operacion);	
	}
	if($borrar==true)
	{
		 $sql="delete from  cobros where idcobros=".$idcobros;
			$operacion = mysql_query($sql);
			$row = mysql_fetch_array($operacion);		
	}


		$listacobros="<table border=\"1\"><tr><th>Id</th><th>Contrato</th><th>Inquilino</th><th>Inmueble</th><th>T. Cobro</th><th>Fecha</th><th>Periodo</th><th>Prioridad</th><th>Cantidad</th><th>Interes</th><th>IVA</th><th colspan=2>Acciones</th></tr>";
		$sql="select *, (select fechanaturalpago from cargos_por_aplicar where idcobros=cobros.idcobros) as fechanaturalpago from cobros, tipocobro, periodo,prioridad where cobros.idprioridad=prioridad.idprioridad and cobros.idperiodo = periodo.idperiodo and cobros.idtipocobro = tipocobro.idtipocobro and cobros.idcobros in (select distinct idcobros from cargos_por_aplicar) ";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			extract($row);
			$sqlh="SELECT CONCAT( inquilino.nombre, ' ', IF(inquilino.nombre2='', '', inquilino.nombre2),' ', inquilino.apaterno, ' ', inquilino.amaterno) as inquilino, contrato.idinmueble as idinmueble
				FROM inquilino, contrato WHERE contrato.idinquilino=inquilino.idinquilino AND contrato.idcontrato='".$idcontrato."' ";
			$inquilinox=mysql_query($sqlh);

			while ($inquilinoxr=mysql_fetch_array($inquilinox)) 
			{
				extract($inquilinoxr);
			}
			$sqlh="SELECT CONCAT( calle, ' ', IF(numeroext='', '', numeroext),' ', IF(numeroint='', '', numeroint),' ',  colonia, ' ', delmun) as inmueble 
				FROM inmueble WHERE idinmueble='".$idinmueble."' ";
			$inmueblex=mysql_query($sqlh);

			while ($inmueblexr=mysql_fetch_array($inmueblex)) {
				extract($inmueblexr);
			}
			$listacobros .= "<tr><td>" . $idcobros . "</td>";
			$listacobros .= "<td>" . $idcontrato . "</td>";
			$listacobros .= "<td>" . $inquilino . "</td>";
			$listacobros .= "<td>" . $inmueble . "</td>";
			$listacobros .= "<td>" . $tipocobro . "</td>";
			$listacobros .= "<td>" . $fechanaturalpago . "</td>";
			$listacobros .= "<td>" . $nombre . "</td>";
			$listacobros .= "<td>" . $prioridad . "</td>";
			$listacobros .= "<td>" . $cantidad . "</td>";
			$listacobros .= "<td>" . $interes . "</td>";
			$listacobros .= "<td>" . $iva. "</td>";
			$listacobros .= "<td>
			<input type='button' name='borrar' value='Borrar' onClick=\"cargarSeccion('$dirscript/listarporaplicar.php','contenido','idcontrato=$idcontrato&idcobros=$idcobros&borrar=true')\"></td>";
			$listacobros .= "<td>
			<input type='button' name='agregar' value='Agregar' onClick=\"cargarSeccion('$dirscript/listarporaplicar.php','contenido','idcontrato=$idcontrato&agregarcobro=true&idcobros=$idcobros')\"></td>
			</tr>";		
		}
		$listacobros .="</table>";
$html = <<<fin
<form>
<h3 align="center">Configuraci&oacute;n de cobros Pendientes por aplicar</h3>
<center>

<table border="1">
<tr>
	<td valign="top">
		$listacobros
	</td>
</tr>
		<tr>
			<td colspan="2" align="center">
				
			</td>
		</tr>
</table>
</center>
</form>
fin;

	echo CambiaAcentosaHTML($html);


}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}



?>