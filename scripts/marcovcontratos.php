<?php

//es el formulario para preparar la busqueda en la herramienta
//lateral de la ventana principal requiere "resultadomarcobusqueda.php"

include 'general/sessionclase.php';
include 'general/calendarioclass.php';
include_once('general/conexion.php');
include 'general/funcionesformato.php';


$periodo=@$_GET["periodo"];

if($periodo==null || $periodo==''){
	$periodo = 0;
}

$fechas = New Calendario;

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	$sql0 = "SELECT * FROM privilegio p, submodulo s WHERE idusuario=" . $misesion->usuario . " and p.idsubmodulo=s.idsubmodulo and s.archivo='marcovcontratos.php'";
	$operacion0 = mysql_query($sql0);
	if(mysql_num_rows($operacion0)>0){

		$lista ="<table border = \"1\" class=\"letrasn\"> ";

		$hoy = date("Y") . "-" . date("m") . "-" . date("d");
		$fechagsistema =mktime(0,0,0,substr($hoy, 5, 2),substr($hoy, 8, 2),substr($hoy, 0, 4));
		
		switch($periodo)
		{
			case 1: //Hoy
				$sql="SELECT * FROM contrato c, inquilino i WHERE c.idinquilino = i.idinquilino AND concluido <>true AND activo=true AND fechatermino <= '$hoy' ORDER BY fechatermino";
				break;

			case 2: //durante la siguiente semana
				$periodoCosulta = $fechas->calculafecha($fechagsistema, 7, 3);		
				$sql="SELECT * FROM contrato c, inquilino i WHERE c.idinquilino = i.idinquilino AND concluido <>true AND activo=true AND fechatermino <= '$periodoCosulta' ORDER BY fechatermino";
				break;

			case 3: //durante el siguiente mes
				$periodoCosulta = $fechas->calculafecha($fechagsistema, 2, 2);
				$sql="SELECT * FROM contrato c, inquilino i WHERE c.idinquilino = i.idinquilino AND concluido <>true AND activo=true AND fechatermino <= '$periodoCosulta' ORDER BY fechatermino";
				break;
			default:
				$periodoCosulta = $fechas->calculafecha($fechagsistema, 7, 3);		
				$sql="SELECT * FROM contrato c, inquilino i WHERE c.idinquilino = i.idinquilino AND concluido <>true AND activo=true AND fechatermino <= '$periodoCosulta' ORDER BY fechatermino";
				break;
		}

		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{

			$lista .= "<tr><td><b><a style=\"font-size:10;color:red;cursor: pointer\" onClick=\"jabascript:cargarSeccion('scripts/inmuebles/edocuenta.php','contenido', 'contrato=". $row["idcontrato"] . "');\">Contrato " . $row["idcontrato"] . " (" . CambiaAcentosaHTML($row["apaterno"]) . ")" . " " . $row["fechatermino"] . "</a><b></td></tr> ";

		}

		$lista .="</table>";

		if($periodo==0){
			echo <<<formulariocl
	<center>

	<form>
	<table border="0">
	<tr>
		<td><b class="letrasn">Contratos P/vencer</b><br>
		<select name="periodo2" onChange="cargarSeccion('scripts/marcovcontratos.php', 'busvenc', 'periodo=' + this.value);" value="$periodo" >
			<option value="1">D&iacute;a de hoy</option>
			<option value="2" selected>Durante la sig. Semana</option>
			<option value="3">Durante los dos sig. Mes</option>
		</select>
		<input type='button' value='Generar' onClick="cargarSeccion('scripts/resultadomarcovendimientosc.php', 'contenido', 'descargar=0&periodo=' + periodo2.value);">
		<input type='button' value='Descargar' onClick="window.open('scripts/resultadomarcovendimientosc.php?descargar=1&periodo=' + periodo2.value);">	
	</tr>
	<tr>
		<td>
		<div id="busvenc" style="height:100px; width:180; overflow:auto;">
		$lista
		</div>
		</td>
	</tr>
	</table>


	</form>
	</center>

formulariocl;
		}else{
			echo $lista;
		}

	}
	else
	{
		echo "";
	}

}
?>