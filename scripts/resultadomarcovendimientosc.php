<?php

//es el formulario para preparar la busqueda en la herramienta
//lateral de la ventana principal requiere "resultadomarcobusqueda.php"

include 'general/sessionclase.php';
include 'general/calendarioclass.php';
include_once('general/conexion.php');
include 'general/funcionesformato.php';


$periodo=@$_GET["periodo"];
$descargar=@$_GET["descargar"];

if($periodo==null || $periodo==''){
	$periodo = 0;
}

if($descargar==null || $descargar==''){
	$descargar = 0;
}

$fechas = New Calendario;

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql0 = "select * from privilegio p, submodulo s where idusuario=" . $misesion->usuario . " and p.idsubmodulo=s.idsubmodulo and s.archivo='marcovcontratos.php'";
	$operacion0 = mysql_query($sql0);
	if(mysql_num_rows($operacion0)>0){

		$lista ="<table border = \"1\"><tr style='background-color:#9C0;'><th>Contrato</th><th>Inquilino</th><th>Inmueble</th><th>Fecha Inicio</th><th>Fecha Termino</th><th>Cobros</th></tr> ";

		$hoy = date("Y") . "-" . date("m") . "-" . date("d");
		$fechagsistema =mktime(0,0,0,substr($hoy, 5, 2),substr($hoy, 8, 2),substr($hoy, 0, 4));

		switch($periodo)
		{
			case 1: //Hoy
				$sql="SELECT  * FROM contrato c, inquilino i, inmueble inm WHERE c.idinquilino = i.idinquilino AND c.idinmueble = inm.idinmueble AND concluido <>true AND activo=true AND fechatermino <= '$hoy' ORDER BY fechatermino";
				$tipoRep=" al dia del $hoy";
				break;

			case 2: //durante la siguiente semana
				$periodoCosulta = $fechas->calculafecha($fechagsistema, 7, 3);		
				$sql="SELECT  * FROM contrato c, inquilino i, inmueble inm WHERE c.idinquilino = i.idinquilino AND c.idinmueble = inm.idinmueble AND concluido <>true AND activo=true AND fechatermino <=  '$periodoCosulta' ORDER BY fechatermino";
				$tipoRep=" al dia de $periodoCosulta";
				break;

			case 3: //durante el siguiente mes
				$periodoCosulta = $fechas->calculafecha($fechagsistema, 2, 2);
				$sql="SELECT  * FROM contrato c, inquilino i, inmueble inm WHERE c.idinquilino = i.idinquilino AND c.idinmueble = inm.idinmueble AND concluido <>true AND activo=true AND fechatermino <=  '$periodoCosulta' ORDER BY fechatermino";
				$tipoRep=" al dia de $periodoCosulta";
				break;
			default:
				$periodoCosulta = $fechas->calculafecha($fechagsistema, 7, 3);		
				$sql="SELECT  * FROM contrato c, inquilino i, inmueble inm WHERE c.idinquilino = i.idinquilino AND c.idinmueble = inm.idinmueble AND concluido <>true AND activo=true AND fechatermino <=  '$periodoCosulta' ORDER BY fechatermino";
				$tipoRep=" al dia de $periodoCosulta";
				break;
		}

		$titulo = "<center><h2>Reporte de Contratos por Vencer $tipoRep</h2></center>";

		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$idcontrato=$row["idcontrato"];
			$inquilino=$row["nombre"]." ".$row["nombre2"]." ".$row["apaterno"]." ".$row["amaterno"];
			$inmueble=$row["calle"]." ".$row["numeroext"]." ".$row["numeroint"]." ".$row["colonia"]." ".$row["cp"]." ".$row["delmun"];
			$fechaInicio=$row["fechainicio"];
			$fechaTermino=$row["fechatermino"];

			$tablaCb="<table border='1'><tr style='background-color:#9C0;'><th>Tipocobro</th><th>Importe</th><th>Iva</th><th>Total</th></tr>";
			$sqlCb = "SELECT * FROM cobros cb, tipocobro tc WHERE cb.idtipocobro=tc.idtipocobro AND idcontrato=$idcontrato";
			$operacionCb = mysql_query($sqlCb);
			while ($rowCb = mysql_fetch_array($operacionCb)) {
				$contadorInterno++;
				if($contadorInterno==1){
					$color="style='background-color:#FFFFFF;'";
				}else{
					$color="style='background-color:#CCCCCC;'";
					$contadorInterno=0;
				}
				$tablaCb .= "<tr $color><td>".$rowCb["tipocobro"]."</td><td>$".number_format($rowCb["cantidad"],2,".",",")."</td><td>$".number_format($rowCb["iva"],2,".",",")."</td><td>$".number_format(($rowCb["cantidad"] + $rowCb["iva"]),2,".",",")."</td></tr>";
			}
			$tablaCb .= "</table>";

			$contador++;
			if($contador==1){
				$color="style='background-color:#FFFFFF;'";
			}else{
				$color="style='background-color:#CCCCCC;'";
				$contador=0;
			}

			$lista .= "<tr $color>
					<td><a href='scripts/inmuebles/edocuenta.php?contrato=$idcontrato' target='_blank'>$idcontrato</a></td>
					<td>$inquilino</td>
					<td>$inmueble</td>
					<td>$fechaInicio</td>
					<td>$fechaTermino</td>
					<td>$tablaCb</td>
				</tr>";
		}

		$lista .="</table>";

		if($descargar==0){
			echo $titulo;
			echo "<br>";
			echo $lista;
		}else{
			header("Content-type: application/vnd.ms-excel");
			header("Content-type: application/x-msexcel"); 
			header("Content-Disposition: attachment; filename=Renovacion_Contratos.xls");
			header("Pragma: no-cache");
			header("Expires: 0");

			echo $titulo;
			echo $lista;
		}

	}
	else
	{
		echo "";
	}

}
?>