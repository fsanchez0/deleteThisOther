<?php

include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclassd.php';


$correo=@$_GET["correo"];
$idc=@$_GET["idc"];


$enviocorreo = New correo2;
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


	$sql ="select i.nombre as inombre, i.nombre2 as inombre2, i.apaterno as iapaterno, i.amaterno as iamaterno, i.tel as telinq, inm.tel as telinm, i.email as emaili, calle, numeroext, numeroint, inm.colonia, delmun, inm.cp, f.nombre as fnombre, f.nombre2 as fnombre2, f.tel as ftel, f.email as femail, f.apaterno as fapaterno, f.amaterno as famaterno, tipocobro, cob.iva as ivac, cob.cantidad as cobcantidad, cob.interes as cobinteres from contrato c, inquilino i, fiador f, cobros cob, inmueble inm, tipocobro tc where cob.idtipocobro = tc.idtipocobro and c.idinmueble = inm.idinmueble and c.idinquilino = i.idinquilino and c.idfiador = f.idfiador and c.idcontrato = cob.idcontrato and c.idcontrato = $idc ";

	$html = "";
	$cabecera ="";
	$cobros="<table border='1' ><tr class='Cabecera'><th>Concepto</th><th>Cantidad</th><th>IVA</th><th>Importe</th><th>Interes</th></tr>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{

		if($cabecera=="")
		{
			//datos de inqilino
			//datos de fiador
			//datos del contrato
			$cabecera .= "<table border = \"1\">";
			$cabecera .= "<tr><td class='Cabecera'>Inquilino</td><td colspan='3'>" .  $row["inombre"] . " " . $row["inombre2"] . " " . $row["iapaterno"] . " " . $row["iamaterno"] . "(Tels. " . $row["telinq"] . ", " . $row["telinm"] . " email: " . $row["emaili"] . ")</td></tr>\n";
			$direccion =$row["calle"] . " No. " . $row["numeroext"] . " " . $row["numeroint"] . " COL." . $row["colonia"] . " Alc./Mun." . $row["delmun"] . " C.P." . $row["cp"] . " " . $row["pais"] . " " . $row["estado"];
			$cabecera .= "<tr><td class='Cabecera'>Direcci&oacute;n</td><td colspan='3'>$direccion </td></tr>\n";
			$elfiadorh = $row["fnombre"] . " " . $row["fnombre2"] . " " . $row["fapaterno"] . " " . $row["famaterno"]  . " (Tel. " . $row["ftel"] . ", email: " . $row["femail"] . ")";
			$cabecera .= "<tr><td class='Cabecera'>Obligado solidario</td><td colspan='3'>$elfiadorh </td></tr>\n";
			
			$cabecera .="<tr><td>Propietario</td><td>";
			
			$sqld="select nombre, nombre2, apaterno, amaterno, rfcd, porcentajed From contrato c, inmueble i, duenioinmueble di, duenio d where c.idinmueble = i.idinmueble and i.idinmueble = di.idinmueble and di.idduenio = d.idduenio and c.idcontrato = $idc";
			$operaciond = mysql_query($sqld);
	        while($rowd = mysql_fetch_array($operaciond))
	        {
                $cabecera .= $rowd["nombre"] . " " . $rowd["nombre2"] . " " . $rowd["apaterno"] . " " . $rowd["amaterno"] . " (" . $rowd["rfcd"] . ")<br>";
		    }
            $cabecera .="</td></tr>";

		    
	    }
	    
	    $cobros .="<tr><td class='Cabecera'>" . $row["tipocobro"]  ."</td><td align='right'>$" .  $row["cobcantidad"]  . "</td><td align='right'>" .  $row["ivac"]  . "%</td>td align='right'>" .  ($row["cobcantidad"] * (1+($row["ivac"]/100)) )  . "%</td><td align='right'>" .  $row["cobinteres"]  . "%</td></tr>";

	   
	}
	
		$cobros .="</table>";
	    $cabecera.="<tr><td colspan='2'>$cobros</td></tr>";
	
	
	    $html = "<html><header><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></header><body> " . $cabecera;
	    $html .= "<tr>";
    	$html .= "    <td><div id=\"textofooter\"><br><br>Le recordamos nuestro telfono para cualquier informacin: <br />";
	    $html .= "    <img src='cid:telefono'><br/>";
	    $html .= "      Por su atencin, gracias.</div>";
	    $html .= "      <div id=\"firma\">Atentamente<br>";
	    $html .= "    Desacho Padilla &amp; Bujalil <br><img src='cid:footer'></div><br>";

	    $html .= "  </tr>";
	    $html .= "</table>";
	    $html .= "</body>";
	    echo $html .= "</html>";
    
	

    $mensaje = CambiaAcentosaHTML($html);
	
	
    $correoe=$correo;
    /*
    if ($fmail != "")
    {
	    $correoe .= "," . $fmail;
    }
    */
    //echo $correoe;
    $esok=$enviocorreo->enviarp($correoe, "Envio de datos de contrato", $mensaje);
    //echo "se envio correo: " .  $esok;
    if($esok)
    {

	    echo "Se envío el correo al buzón $correoe";
    }
    else
    {
	    echo "No se envío el correo";
    }

    
    
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}


?>