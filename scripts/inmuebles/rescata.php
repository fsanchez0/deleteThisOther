<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

$patron=@$_GET["patron"];
$cat=@$_GET["cat"];
$apartado=@$_GET["apartado"];

$papartado="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	if($apartado=='1' or $apartado=='true')
	{
		$papartado=" and ( idinmueble in (select idinmueble from apartado where cancelado=0) ) ";
	
	}
	else
	{
		$papartado=" and not( idinmueble in (select idinmueble from apartado where cancelado=0) ) ";
	}

	if(!$patron=="")
	{
	
		switch ($cat)
		{
		case 0: //Inquilino
			$sql="select * from inquilino where CONCAT(nombre, ' ', nombre2, ' ', apaterno, ' ', amaterno ) like '%$patron%' ";
	
			$datos=mysql_query($sql);	
			echo "<table border=\"1\" width=\"100%\">";
			while($row = mysql_fetch_array($datos))
			{
				extract($row);
				$html = "<tr><td><a  style=\"font-size:10;\" onClick=\"document.getElementById('inquilino').value=$idinquilino;document.getElementById('inquilinon').value ='$nombre $nombre2 $apaterno $amaterno';\">$nombre $nombre2 $apaterno $amaterno </a></td></tr> ";
				echo CambiaAcentosaHTML($html);
	
			}
			echo "</table>";
		
			break;
		
		case 1: //fiador

			$sql="select * from fiador where CONCAT(nombre, ' ', apaterno, ' ', amaterno ) like '%$patron%' ";
	
			$datos=mysql_query($sql);	
			echo "<table border=\"1\" width=\"100%\">";
			while($row = mysql_fetch_array($datos))
			{
				extract($row);
				$html= "<tr><td><a style=\"font-size:10;\" onClick=\"document.getElementById('fiador').value=$idfiador;document.getElementById('fiadorn').value='$nombre $nombre2 $apaterno $amaterno';\"> $nombre $nombre2 $apaterno $amaterno</a></td></tr> ";
				echo CambiaAcentosaHTML($html);
	
			}
			echo "</table>";

		
			break;
		
		case 2: // inmueble
            
            $papartado=" and ( i.idinmueble in (select idinmueble from apartado where cancelado=0 and estatus='Act') ) AND (a.cancelado = 0 and estatus='Act')";

			//$sql="select * from inmueble where CONCAT(calle, ' ', numeroint , ' ', colonia, ' ', delmun, ' ', cp ) like '%$patron%' ";
			//$sql = "select * from inmueble where  not( idinmueble in (select idinmueble from contrato where activo=true) )  and CONCAT(calle, ' ', numeroint , ' ', colonia, ' ', delmun, ' ', cp ) like '%$patron%' $papartado";
			$sql = "select * from inmueble i, apartado a where i.idinmueble = a.idinmueble and not( i.idinmueble in (select idinmueble from contrato where activo=true) )  and CONCAT(calle, ' ', numeroint , ' ', colonia, ' ', delmun, ' ', cp ) like '%$patron%' $papartado";
			$datos=mysql_query($sql);	
			echo "<table border=\"1\" width=\"100%\">";
			while($row = mysql_fetch_array($datos))
			{
				extract($row);
				$html = "<tr><td><a  style=\"font-size:10;\" onClick=\"document.getElementById('apartado').value=$idapartado;document.getElementById('inmueble').value=$idinmueble; document.getElementById('inmueblen').value='$calle No. $numeroext Int. $numeroint Col. $colonia Alc. $delmun C.P. $cp';\">$calle No. $numeroext Int. $numeroint Col. $colonia Deleg. $delmun C.P. $cp (ID Apartado: $idapartado)</a></td></tr> ";
				echo CambiaAcentosaHTML($html);
	
			}
			echo "</table>";		
			break;
		
		case 3: // inmueble

            
			//$sql="select * from inmueble where CONCAT(calle, ' ', numeroint , ' ', colonia, ' ', delmun, ' ', cp ) like '%$patron%' ";
			$sql = "select * from inmueble where  not( idinmueble in (select idinmueble from contrato where activo=true) )  and CONCAT(calle, ' ', numeroint , ' ', colonia, ' ', delmun, ' ', cp ) like '%$patron%' $papartado";
			
			$datos=mysql_query($sql);	
			echo "<table border=\"1\" width=\"100%\">";
			while($row = mysql_fetch_array($datos))
			{
				extract($row);
				$html = "<tr><td><a  style=\"font-size:10;\" onClick=\"document.getElementById('inmueble').value=$idinmueble; document.getElementById('inmueblen').value='$calle No. $numeroext Int. $numeroint Col. $colonia Alc. $delmun C.P. $cp';\">$calle No. $numeroext Int. $numeroint Col. $colonia Deleg. $delmun C.P. $cp </a></td></tr> ";
				echo CambiaAcentosaHTML($html);
	
			}
			echo "</table>";			
		
		}
	
		
	}



}

?>