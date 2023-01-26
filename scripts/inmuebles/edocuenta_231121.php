<head>
  <meta charset="gb18030">
  <title>Estado de Cuenta</title>
  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
<?php
include "../general/calendarioclass.php";
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/ftpclass.php';
include ("../cfdi/cfdiclassn.php");
// Cambio 29/06/2021
// Se cambió $contrato por $contrato
$contrato = @$_GET["contrato"];
//Cambio 29/06/2021
// Ahora accion nos permite eliminar un archivo
// y se agregó archivoPorBorrar que indica la ruta y el nombre del archivo a borrar
$accion = @$_GET["accion"];
$archivoPorBorrar = @$_GET["archivo"];
$prueba = New Calendario;
$fondo=" class='Fondo' ";
$fondot1=" class='Fondot1' ";
$fondot2=" class='Fondot2' ";
$clasef="";
//$clasef=$fondo;
$cambio = "";

$cfd =  New cfdi32class;
$ftp= New ftpcfdi;
$misesion = new sessiones;

if($misesion->verifica_sesion()=="yes")
{


	$sql="select * from submodulo where archivo ='edoscuentainm.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta']  . "/" . $row['archivo'];
		$ruta= $row['ruta'] ;
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}
		$usuarios=$_SESSION['usuario'];

	if ($priv[0]!='1')
	{
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

	$users=mysql_query("SELECT * FROM privilegiofile WHERE idusuario='$usuarios' AND ver='1'");
	$totalreg=mysql_num_rows($users);
	if ($totalreg==1) {
		$botonluz="<iframe src=\"http://rentascdmx.com//scripts/inmuebles/cargaPDF.php?idcontrato=".$contrato."+&servicio=luz\" style=\"border:0px; height:45px;width:600px;\"></iframe><button type=\"button\" id=\"btnluz\" class=\"btn btn-success btn-sm \" data-toggle=\"modal\" data-target=\"#myModal\">Agregar</button>&nbsp;<button type=\"button\" class=\"btn btn-success btn-sm \" id=\"correoluz\" data-toggle=\"modal\" data-target=\"#myModal3\">Enviar Correo</button>&nbsp;<button type=\"button\" class=\"btn btn-success btn-sm \" id=\"verluz\" data-toggle=\"modal\" data-target=\"#myModal4\">Ver</button>&nbsp;<button type=\"button\" class=\"btn btn-success btn-sm \" id=\"btnluz2\" data-toggle=\"modal\" data-target=\"#myModal2\">Todos</button>";
		$botonagua="<iframe src=\"http://rentascdmx.com//scripts/inmuebles/cargaPDF.php?idcontrato=".$contrato."+&servicio=agua\" style=\"border:0px; height:45px;width:600px;\"></iframe><button type=\"button\" id=\"btnagua\" class=\"btn btn-success btn-sm \" data-toggle=\"modal\" data-target=\"#myModal\">Agregar</button>&nbsp;<button type=\"button\" class=\"btn btn-success btn-sm \" id=\"correoagua\" data-toggle=\"modal\" data-target=\"#myModal3\">Enviar Correo</button>&nbsp;<button type=\"button\" class=\"btn btn-success btn-sm \" id=\"veragua\" data-toggle=\"modal\" data-target=\"#myModal4\">Ver</button>";
		$botongas="<iframe src=\"http://rentascdmx.com//scripts/inmuebles/cargaPDF.php?idcontrato=".$contrato."+&servicio=gas\" style=\"border:0px; height:45px;width:600px;\"></iframe><button type=\"button\" id=\"btngas\" class=\"btn btn-success btn-sm \" data-toggle=\"modal\" data-target=\"#myModal\">Agregar</button>&nbsp;<button type=\"button\" class=\"btn btn-success btn-sm \" id=\"correogas\" data-toggle=\"modal\" data-target=\"#myModal3\">Enviar Correo</button>&nbsp;<button type=\"button\" class=\"btn btn-success btn-sm \" id=\"vergas\" data-toggle=\"modal\" data-target=\"#myModal4\">Ver</button>";
		$botoncondominio="<iframe src=\"http://rentascdmx.com//scripts/inmuebles/cargaPDF.php?idcontrato=".$contrato."+&servicio=condominio\" style=\"border:0px; height:45px;width:600px;\"></iframe><button type=\"button\" id=\"btncondominio\" class=\"btn btn-success btn-sm \" data-toggle=\"modal\" data-target=\"#myModal\">Agregar</button>&nbsp;<button type=\"button\" class=\"btn btn-success btn-sm \" id=\"correocondominio\" data-toggle=\"modal\" data-target=\"#myModal3\">Enviar Correo</button>&nbsp;<button type=\"button\" class=\"btn btn-success btn-sm \" id=\"vercondominio\" data-toggle=\"modal\" data-target=\"#myModal4\">Ver</button>";
		$botonpredial="<iframe src=\"http://rentascdmx.com//scripts/inmuebles/cargaPDF.php?idcontrato=".$contrato."+&servicio=predial\" style=\"border:0px; height:45px;width:600px;\"></iframe><button type=\"button\" id=\"btnpredial\" class=\"btn btn-success btn-sm \" data-toggle=\"modal\" data-target=\"#myModal\">Agregar</button>&nbsp;<button type=\"button\" class=\"btn btn-success btn-sm \" id=\"correopredial\" data-toggle=\"modal\" data-target=\"#myModal3\">Enviar Correo</button>&nbsp;<button type=\"button\" class=\"btn btn-success btn-sm \" id=\"verpredial\" data-toggle=\"modal\" data-target=\"#myModal4\">Ver</button>";	}
	else{
		$botonluz="";
		$botonagua="";
		$botongas="";
		$botoncondominio="";
		$botonpredial="";
	}
	//Cambio 29/06/2021
	// Se agregó un if para capturar la acción cuando se desea eliminar un archivo
	if($accion == 2){
		//echo "Es acción 2";
		//echo $archivoPorBorrar;
		if(unlink($archivoPorBorrar)){
			echo "Se borró el archivo";
		}

	}
if ($contrato){

	//$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno,inquilino.tel as telinq,tipocobro, historia.fechagenerado, historia.fechanaturalpago, historia.fechagracia, historia.fechapago, historia.cantidad, aplicado, historia.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, inmueble.estado, inmueble.pais, inmueble.tel as telinm, historia.iva as ivah,aplicado, condonado FROM contrato, cobros, inquilino, inmueble, tipocobro,historia, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $contrato and inmueble.idestado =estado.idestado and inmueble.idpais=pais.idpais order by fechanaturalpago, fechapago, aplicado";

	$sql="select tipocobro, (b.cantidad * (1+ (b.iva)/100)) suma,(b.interes *100) elinteres from contrato c, cobros b, tipocobro t where c.idcontrato = b.idcontrato and b.idtipocobro = t.idtipocobro and b.idperiodo<>1 and c.idcontrato = $contrato";

	$listacobros="<table border='1' ><tr class='Cabecera'><th>Concepto</th><th>Cantidad</th><th>Interes</th></tr>";

	$result1 = @mysql_query ($sql);
	while ($row = mysql_fetch_array($result1))
	{
		$listacobros .="<tr><td class='Cabecera'>" . $row["tipocobro"]  ."</td><td align='right'>$" .  $row["suma"]  . "</td><td align='right'>" .  $row["elinteres"]  . "%</td></tr>";

	}	 
	

	/* subir pdf*/

	error_reporting(0);
$fecha = date("Y-m-d");
$nombre_archivo = $_FILES['file01']['name']; 
$tipo_archivo = $_FILES['file01']['type'];      
$tamano_archivo = $_FILES['file01']['size']; 
if (move_uploaded_file($_FILES['file01']['tmp_name'], $nombre_archivo)) 
{ 
    $vol_archi = round($tamano_archivo / 1024, 2);     
    $extension = substr(strrchr($nombre_archivo, "."), 1);    

    $volumen_min = "5120";           
    $volumen_max = "5120000";        
   
    $archivo_permitido = "application/pdf";        
      
    if (($tipo_archivo == $archivo_permitido))   
    {   
         
    $carpeta = "contratos/$contrato/pdf/";   
         if (!file_exists($carpeta)) {
         	mkdir("contratos/",0755);
         	mkdir("contratos/$contrato/",0755);
         	mkdir("contratos/$contrato/pdf/",0755);
			}	
        $nuevo_nombre = preg_replace('/\\.[^.\\s]{3,4}$/', '', $nombre_archivo)."_".$fecha.".pdf";
        //$nuevo_nombre = preg_replace("/[^a-zA-Z0-9\_\-]+/", "", $nuevo_nombre);	    
		rename($nombre_archivo, $nuevo_nombre);
         
            if (copy("$nuevo_nombre", "$carpeta/$nuevo_nombre"))    
            {   
                echo "El fichero ha sido copiado con exito. <br />";   
            } else {   
                echo "El fichero NO se ha podido copiar.";   
            }  
        
        unlink($nuevo_nombre); 
    } else { 
    echo "El archivo no es del tipo ($tipo_archivo) o volumen permitido ($vol_archi Kb)"; 

    
    unlink($nombre_archivo); 
    } 

} else { 
   
} 
$documentos = "Documentos: <br>";
$directorio = opendir("contratos/$contrato/pdf/"); //ruta actual
//Cambio 29/06/2021
// Se copió el código que consulta la información para poder tomar el enlace actual
// despues se hace una separación por el símbolo ? para no hacerle caso a los parámetros
$enlace_actual = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$enlace_actual = explode("?",$enlace_actual)[0];
while ($archivo1 = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
    if (!is_dir($archivo1))//verificamos si es o no un directorio
    {
         $documentos.="- <a href='contratos/$contrato/pdf/".$archivo1."' target='_bank'> ".$archivo1."</a>";
		 //Cambio 29/06/2021
		// Se agregó un link que permite eliminar el archivo existente
		// se carga de nuevo esta página con otros parámetros con accion = 2 y el nombre del
		// archivo a eliminar solo si tiene permiso para borrar en esta página
		if($txtborrar != " DISABLED ")
			$documentos .= " <a href='#' style='color:red;' onclick='if(confirm(\"¿Estas segur@ de eliminar este archivo? Una vez eliminado no se podrá recuperar.\")){window.open(\"".$enlace_actual."?contrato=".$contrato."&accion=2&archivo=contratos/$contrato/pdf/" . $archivo1 . "\",\"_self\");}'> X </a><br />";
    }
    
}

$enlace_actual = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//Cambio 29/06/2021
// Se hace una division del texto de enlace actual por el símbolo &, esto nos permite 
// eliminar todos los parámetros para eliminar un archivo en caso de existir
$enlace_actual = explode("&",$enlace_actual)[0];
$form_pdf = "<form action='$enlace_actual' method='post' enctype='multipart/form-data'>     
     	<p>Subir PDF <input type='file' name='file01' size='15'></p>     
		<p><input type='reset' value='Limpiar'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
		<input type='submit' value='Subir archivo'  $txtagregar></p>  
		</form> <br>
		<i class='fa fa-download'>$documentos</i>";


/*  fin subir pdf */	



	//Cambio 29/06/2021
	// Se agrego mi usuario para poder visualizar los archivos														
	if ($usuarios == 19 || $usuarios == 1 || $usuarios == 53 || $usuarios == 60 || $usuarios == 13 || $usuarios == 14 || $usuarios == 15 || $usuarios == 62 || $usuarios == 63 || $usuarios == 51 || $usuarios == 50 || $usuarios == 63 || $usuarios == 14 || $usuarios == 64 || $usuarios == 70 || $usuarios == 71 || $usuarios == 3) {													
		$hi=" <span align='center' class='col-sm-4 download'><br>
	        <button class='btn btn-success btn-sm' id='from' name='from' value='".$contrato."'
	            <i class='fa fa-download'></i><i class='fa fa-file-excel-o'>&nbsp;</i>&nbsp;Descargar Estado de Cuenta</span><div class='col-md-5'>
	        </button>
		</span><br><br><br><br>
		$form_pdf
        
		";
		



		$botonexcel="<iframe src=\"http://rentascdmx.com/scripts/inmuebles/descargarEdoCuenta.php?idContrato=".$contrato." style=\"border:0px; height:45px;width:600px;\"></iframe><button type=\"button\" id=\"btnexcel\" class=\"btn btn-success btn-sm \" data-toggle=\"modal\" data-target=\"#myModal\">Descargar Estado de Cuenta
		</button>";	
	}

	$listacobros .="</table>";
	$listacobros .= $hi;

	//$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as telinq,tipocobro, historia.fechagenerado, historia.fechanaturalpago, historia.fechagracia, historia.fechapago, historia.cantidad, aplicado, historia.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, inmueble.estado, inmueble.pais, inmueble.tel as telinm, historia.iva as ivah,aplicado, condonado, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel FROM contrato, cobros, inquilino, inmueble, tipocobro,historia, fiador WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $contrato and contrato.idfiador=fiador.idfiador order by fechanaturalpago, tipocobro,interes,fechagenerado,fechapago, aplicado";
	//$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as telinq,tipocobro, historia.fechagenerado, historia.fechanaturalpago, historia.fechagracia, historia.fechapago, historia.cantidad, aplicado, historia.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, estado, pais, inmueble.tel as telinm, historia.iva as ivah,aplicado, condonado, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, historia.notas as hnotas, fiador.email as emailf, notanc, inquilino.email as emaili, observaciones FROM contrato, cobros, inquilino, inmueble, tipocobro,historia, fiador, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $contrato and contrato.idfiador=fiador.idfiador and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais order by fechanaturalpago, tipocobro,interes,fechagenerado,fechapago, aplicado";
	 //$sql= "SELECT contrato.idcontrato as elidcontrato, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as telinq,tipocobro, historia.fechagenerado, historia.fechanaturalpago, historia.fechagracia, historia.fechapago, historia.cantidad, aplicado, historia.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, estado, pais, inmueble.tel as telinm, historia.iva as ivah,aplicado, condonado, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, historia.notas as hnotas, fiador.email as emailf, notanc, inquilino.email as emaili, observaciones, archivotxt, archivopdf,archivoxml,archivopdfn,txtok,pdfok,xmlok,pdfnok, historia.idhistoria as idh, hfacturacfdi,inmueble.idinmueble as idinm, aprobado, inquilino.tipofactura FROM contrato, cobros, inquilino, inmueble, tipocobro,historia, fiador, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $contrato and contrato.idfiador=fiador.idfiador and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais order by fechanaturalpago, tipocobro,interes,fechagenerado,fechapago, aplicado";
	// Cambio 06/09/21
	// Se agregó a la consulta la información del email1 y el email2 que se guardan en la tabal de inquilino 
	$sql = "SELECT contrato.idcontrato as elidcontrato , contrato.observacionesc, inquilino.nombre, inquilino.nombre2, inquilino.apaterno, inquilino.amaterno, inquilino.tel as telinq,tipocobro, historia.fechagenerado, historia.fechanaturalpago, historia.fechagracia, historia.fechapago, historia.cantidad, aplicado, historia.interes, vencido,inmueble.calle, inmueble.numeroext, inmueble.numeroint, inmueble.colonia, inmueble.delmun, inmueble.cp, estado, pais, inmueble.tel as telinm, historia.iva as ivah,aplicado, condonado, fiador.nombre as fnombre, fiador.nombre2 as fnombre2, fiador.apaterno as fapaterno, fiador.amaterno as famaterno, fiador.direccion as fdireccion, fiador.tel as ftel, historia.notas as hnotas, fiador.email as emailf, notanc, inquilino.email as emaili, inquilino.email1 AS emaili1,inquilino.email2 AS emaili2, observaciones, archivotxt, archivopdf,archivoxml,archivopdfn,txtok,pdfok,xmlok,pdfnok, historia.idhistoria as idh, hfacturacfdi,inmueble.idinmueble as idinm, aprobado, inquilino.tipofactura, historia.idcategoriat as idct, facturaprevia  FROM contrato, cobros, inquilino, inmueble, tipocobro,historia, fiador, estado, pais WHERE cobros.idtipocobro=tipocobro.idtipocobro and contrato.idcontrato=historia.idcontrato and historia.idcobros=cobros.idcobros and contrato.idinquilino=inquilino.idinquilino and contrato.idinmueble=inmueble.idinmueble and contrato.idcontrato = $contrato and contrato.idfiador=fiador.idfiador and inmueble.idestado = estado.idestado and inmueble.idpais = pais.idpais order by fechanaturalpago, tipocobro,interes,fechagenerado,fechapago, aplicado";
	// Fin Cambio 06/09/21


	$result = @mysql_query ($sql);
	$Datos = 0;
	$cabecera="";
	$historia = "";
	$suma = 0;
	$grupoint=0;
	$tablainterna="";
	$tablalisto="";
	$concepto="";
	$operacion="";
	$auxt=0;
	$principio="";
	$principioa="";
	$fechaa="";
	$fechab="";
	$partea="";
	$parteb="";
	$conceptob="";
	$vcambio=0;

	$claseft=$fondot1;
	setlocale(LC_MONETARY, 'en_US');

	while ($row = mysql_fetch_array($result))
	{
		//echo $auxt;
		
		$idinm=$row["idinm"];
		//Consulta para tomar el registro de la cuenta predial 08/03/2017
		$sql2=mysql_query("SELECT cuentainmueble FROM cuentainmueble WHERE idinmueble='$idinm' AND idtipocuentai=1");
		while ($reg=mysql_fetch_array($sql2)) {
			$cuentapredial=$reg[0];
		}
		//consulta para tomar el valor del servicio de luz 08/03/2017
		$sql3=mysql_query("SELECT cuentainmueble FROM cuentainmueble WHERE idinmueble='$idinm' AND idtipocuentai=3");
		while ($reg2=mysql_fetch_array($sql3)) {
			$servicioluz=$reg2[0];
		}
		//consulta para tomar el valor del servicio de agua 08/03/2017
		$sql4=mysql_query("SELECT cuentainmueble FROM cuentainmueble WHERE idinmueble='$idinm' AND idtipocuentai=2");
		while ($reg3=mysql_fetch_array($sql4)) {
			$servicioagua=$reg3[0];
		}
		//consulta para tomar el valor del servicio de gas 08/03/2017
		$sql5=mysql_query("SELECT cuentainmueble FROM cuentainmueble WHERE idinmueble='$idinm' AND idtipocuentai=4");
		while ($reg4=mysql_fetch_array($sql5)) {
			$serviciogas=$reg4[0];
		}
		//consulta para tomar el valor del servicio de condominio 08/03/2017
		$sql6=mysql_query("SELECT cuentainmueble FROM cuentainmueble WHERE idinmueble='$idinm' AND idtipocuentai=5");
		while ($reg5=mysql_fetch_array($sql6)) {
			$serviciocondominio=$reg5[0];
		}

		if($cambio=="")
		{
			$cambio=$row["fechanaturalpago"];
			$clasef=$fondo;
			$claseft=$fondot1;

		}
		if($cambio!=$row["fechanaturalpago"])
		{
			$cambio=$row["fechanaturalpago"];
			$vcambio=1;
			if($clasef==$fondo)
			{
				$clasef="";
				$claseft=$fondot2;
			}
			else
			{
				$clasef=$fondo;
				$claseft=$fondot1;
			}

		}




		if($Datos==0)
		{
		    
		    $sqlref = "select * From contrato c, apartado a where c.idapartado = a.idapartado and c.idcontrato = " . $contrato;
		    $resultref = @mysql_query ($sqlref);
		    $rowref = mysql_fetch_array($resultref);
		    
		    $referencia = $rowref["referencia"];
		    
			//$cabecera = "<center><h2>Estado de cuenta contrato No. $contrato</h2>";
			$cabecera .= "<table border = \"1\">";
			// Cambio 06/09/21
			// Se agregó al código que muestra la informacion el código para mostrar los correos en caso de existir
			$cabecera .= "<tr><td class='Cabecera'>Inquilino</td><td colspan='3'>" .  $row["nombre"] . " " . $row["nombre2"] . " " . $row["apaterno"] . " " . $row["amaterno"] . "(Tels. " . $row["telinq"] . ", " . $row["telinm"] . " email: " . $row["emaili"] . (empty($row["emaili1"])?"":(", " . $row["emaili1"])) . (empty($row["emaili2"])?"":(", " . $row["emaili2"])). ")</td></tr>\n";
			// Fin Cambio 06/09/21 
			$direccion =$row["calle"] . " No. " . $row["numeroext"] . " " . $row["numeroint"] . " COL." . $row["colonia"] . " Del./Mun." . $row["delmun"] . " C.P." . $row["cp"] . " " . $row["pais"] . " " . $row["estado"];
			$cabecera .= "<tr><td class='Cabecera'>Direcci&oacute;n</td><td colspan='3'>$direccion </td></tr>\n";
			$elfiadorh = $row["fnombre"] . " " . $row["fnombre2"] . " " . $row["fapaterno"] . " " . $row["famaterno"]  . " (Tel. " . $row["ftel"] . ", email: " . $row["emailf"] . ")";
			$cabecera .= "<tr><td class='Cabecera'>Obligado solidario</td><td colspan='3'>$elfiadorh </td></tr>\n";
			$cabecera .= "<tr><td class='Cabecera'>Referencia</td><td colspan='3'>$referencia </td></tr>\n";
            $cabecera .= "<tr><td class='Cabecera'>Observaciones</td><td colspan='3'> " . $row["observacionesc"] . "</td></tr>\n";
			$Datos=1;


		}

		$estado="PENDIENTE";


		if (is_null($row["vencido"])==false and $row["vencido"]==1)
		{

			$estado="PENDIENTE";

		}

		if ((is_null($row["aplicado"])==false and $row["aplicado"]==1) || $row["tipofactura"]=="PPD")
		{
			//$estado="ABONO";
			//$estado=$row["hnotas"];

			$contratobotonfact="";
			$ligapdf="";
			$ligaxml="";
			$ligapdfn="";
			$reciboscfdi="";
			if(	$row["fechapago"]>='2012-02-01' || $row["tipofactura"]=="PPD")
			{
			if($row["idct"]<>2)
			{

				$sqlcfdi="select * from historiacfdi h, facturacfdi f where h.idfacturacfdi=f.idfacturacfdi and idhistoria = " . $row["idh"];
				$operacioncfdi = mysql_query($sqlcfdi);
				//$r= mysql_fetch_array($operacioncfdi);
				//$idcfdi = $r["idfacturacfdi"];	
				//$reciboscfdi="";
				if(mysql_num_rows($operacioncfdi)>0)
				{
					while($r= mysql_fetch_array($operacioncfdi))
					{
						$canceladacfdi="";		
						if($r["cancelada"]==1)
						{
							$canceladacfdi="(CANCELADA)";	
						}
						$notacreditocfdi = "";
						/*
						if($r["notacredito"]==1)
						{
							$notacreditocfdi="N.C.";	
						}
						else
						{
							$notacreditocfdi="";
						}
						*/
						//if($row["txtok"]==0)
						if($r["txtok"]==0 )
						{	
					
							if(is_null($r["archivotxt"])==true)
							{
								//$edocuenta="window.open( '../reporte2.php?id=" . $row["idh"] . "');this.disabled=true";
								$edocuenta="nuevaVP(" . $row["idh"] . ",'');this.disabled=true;";
								$contratobotonfact="<br>" . '<form name="frm_' . $row["idh"] .  '" id="frm_' . $row["idh"] .  '" method="POST" action="../reporte2.php" target="trg_' . $row["idh"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value ="">' . "<input type =\"button\" value=\"Facturar $notacreditocfdi\" onClick=\"$edocuenta\" $txtagregar  /></form>";
							}
							else
							{
								//$edocuenta="window.open( '../reporte2.php?id=" . $row["idh"] . "&archivo=". substr($r["archivotxt"],0,-4) ."');this.disabled=true";
								$edocuenta="nuevaVP(" . $row["idh"] . ",'');this.disabled=true;";
								$contratobotonfact="<br>" . '<form name="frm_' . $row["idh"] .  '" id="frm_' . $row["idh"] .  '" method="POST" action="../reporte2.php" target="trg_' . $row["idh"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""><input type="hidden" name="archivo" id="archivo" value ="'. substr($r["archivotxt"],0,-4) . '">' . "<input type =\"button\" value=\"Facturar $notacreditocfdi\" onClick=\"$edocuenta\" $txtagregar /></form>";	
							}
							
						}
			
						//if($row["pdfok"]==0 and $contratobotonfact=="" )
						if($r["pdfok"]==0)
						{
							if($contratobotonfact=="" )
							{
								$ligapdf="<br><input type=\"button\" value='Recuperar PDF $notacreditocfdi' onClick=\"document.getElementById('cfdi" . $row["idh"] . "').innerHTML='Recuperando...';cargarSeccion('../recuperar.php','cfdi" . $row["idh"] . "', 'id=" . $row["idh"] . "&filtro=');\" $txteditar>";
							}
							elseif($r["tipofactura"]=='PPD')
							{
								$ligapdf="<br><input type=\"button\" value='Recuperar PDF $notacreditocfdi' onClick=\"document.getElementById('cfdi" . $row["idh"] . "').innerHTML='Recuperando...';cargarSeccion('../recuperar.php','cfdi" . $row["idh"] . "', 'id=" . $row["idh"] . "&filtro=');\" $txteditar>";
							}				
						}
						else
						{
							//if($contratobotonfact=="" )
							//{
								//$ligapdf="<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivopdf"] . "\"  target=\"_blank\" >" .  $row["archivopdf"] . "\n</a>";
							$ligapdf="<br><a href=\"../general/descargarcfdi.php?f=" .  $r["archivopdf"] . "\"  target=\"_blank\" ><img src='../../imagenes/PDF.png' width='30'>$notacreditocfdi$canceladacfdi\n</a>";
							//}	
						}

						//if($row["xmlok"]==0 and $contratobotonfact=="")
						if($r["xmlok"]==0)
						{
							if($contratobotonfact=="" )
							{
								$ligaxml="<br><input type=\"button\" value='Recuperar XML $notacreditocfdi' onClick=\"document.getElementById('cfdi" . $row["idh"] . "').innerHTML='Recuperando...';cargarSeccion('../recuperar.php','cfdi" . $row["idh"] . "', 'id=" . $row["idh"] . "&filtro=');\"  $txteditar>";
							}
							elseif($r["tipofactura"]=='PPD')
							{
								$ligaxml="<br><input type=\"button\" value='Recuperar XML $notacreditocfdi' onClick=\"document.getElementById('cfdi" . $row["idh"] . "').innerHTML='Recuperando...';cargarSeccion('../recuperar.php','cfdi" . $row["idh"] . "', 'id=" . $row["idh"] . "&filtro=');\"  $txteditar>";
							}

						}
						else
						{
							//if($contratobotonfact=="" )
							//{
								//$ligaxml="<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivoxml"] . "\"  target=\"_blank\" >" .  $row["archivoxml"] . "\n</a>";
							$ligaxml="<br><a href=\"../general/descargarcfdi.php?f=" .  $r["archivoxml"] . "\"  target=\"_blank\" ><img src='../../imagenes/XML.png' width='30'>$notacreditocfdi$canceladacfdi\n</a>";
							//}

							if($r["tipofactura"]=='PPD' && ($row["aplicado"]==1 && is_null($row["aplicado"])==false) && mysql_num_rows($operacioncfdi)<2)
							{
								//$edocuenta="window.open( '../reporte2.php?id=" . $row["idh"] . "');this.disabled=true";
								$edocuenta="nuevaVP(" . $row["idh"] . ",'');this.disabled=true;";
								$contratobotonfact="<br>" . '<form name="frm_' . $row["idh"] .  '" id="frm_' . $row["idh"] .  '" method="POST" action="../reporte2.php" target="trg_' . $row["idh"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value ="">' . "<input type =\"button\" value=\"Facturar $notacreditocfdi\" onClick=\"$edocuenta\" $txtagregar  /></form>";
							}

						}
			
						//if($row["pdfnok"]==0 and $contratobotonfact=="")
						if($r["pdfn"]==0 && $contratobotonfact=="")
						{
							$edocuenta="";
							$ligapdfn="<br>PDF PyB$notacreditocfdi$canceladacfdi";
						}			
						else
						{
							$ligapdfn="<br>PDF PyB$notacreditocfdi$canceladacfdi";	
						}
						$reciboscfdi .= $contratobotonfact . $ligapdf . $ligaxml . $ligapdfn;
					}
					
			  
				}			 
				else 			 
				{//cuando no genero registro en tabla de facturacfdi
			     
					if($txteditar=="")			    
					{ 
			  	 	
						//$edocuenta="window.open( '../reporte2.php?id=" . $row["idh"] . "');this.disabled=true";
						$edocuenta="nuevaVP(" . $row["idh"] . ",'');this.disabled=true;";
						$contratobotonfact="<br>" . '<form name="frm_' . $row["idh"] .  '" id="frm_' . $row["idh"] .  '" method="POST" action="../reporte2.php" target="trg_' . $row["idh"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value ="">' . "<input type =\"button\" value=\"Facturar\" onClick=\"$edocuenta\" $txtagregar /></form>";
					
						$reciboscfdi=$contratobotonfact;
				
					}
				
					//$reciboscfdi .= $txteditar . "...";
			  
				}			
			}
			}
			
			if(is_null($row["hnotas"])==true)
			{
				//$estado="PAGADO <div id='cfdi" . $row["idh"] . "'>" . $contratobotonfact . $ligapdf . $ligaxml . $ligapdfn . "</div>";
				if(is_null($row["aplicado"])==false and $row["aplicado"]==1)
				{
					$estado="PAGADO <div id='cfdi" . $row["idh"] . "'>" . $reciboscfdi . "</div>";
				}
				elseif($row["tipofactura"]=="PPD")
				{
					$estado=$estado."<div id='cfdi" . $row["idh"] . "'>" . $reciboscfdi . "</div>";
				}	
			}
			else
			{
				$acc="";
				//if($row["hnotas"]=="LIQUIDADO")
				if(substr($row["hnotas"],0,9)=="LIQUIDADO")
				{
					//$acc="<div id='cfdi" . $row["idh"] . "'>" . $contratobotonfact . $ligapdf . $ligaxml . $ligapdfn . "</div>";
					$acc="<div id='cfdi" . $row["idh"] . "'>" . $reciboscfdi . "</div>";
				}
				elseif($row["tipofactura"]=="PPD")
				{
					$acc="<div id='cfdi" . $row["idh"] . "'>" . $reciboscfdi . "</div>";
				}	
				$estado=$row["hnotas"] . $acc;
			}

			if($row["cantidad"] <0)
			{
				$estado="CONDONADO";
			}

			if($row["tipofactura"]=="PPD" && ($row["aplicado"]==0 || is_null($row["aplicado"])==true))
			{
				//$suma += $row["cantidad"] + $row["ivah"] ;	
				$suma += $row["cantidad"]  ;	
			}
	
		}
		else
		{
			//$suma += $row["cantidad"] + $row["ivah"] ;
			$suma += $row["cantidad"]  ;
		}

		// Cambio 10/08/20
		// Se agrega codigo que permite mostrar en cada cobro la opcion de 
		// emitir la factura previa al pago unicamente si se indica en la creación del contrato 
		// se realizara la facturacion previa

		if($row["facturaprevia"]=="1" || $row["facturaprevia"]==1 || $row["facturaprevia"]==true){
			if ($row["idct"] <> 2) {

				$sqlcfdi = "select * from historiacfdi h, facturacfdi f where h.idfacturacfdi=f.idfacturacfdi and idhistoria = " . $row["idh"];
				$operacioncfdi = mysql_query($sqlcfdi);
				//$r= mysql_fetch_array($operacioncfdi);
				//$idcfdi = $r["idfacturacfdi"];	
				//$reciboscfdi="";
				if (mysql_num_rows($operacioncfdi) > 0) {
					while ($r = mysql_fetch_array($operacioncfdi)) {
						$canceladacfdi = "";
						if ($r["cancelada"] == 1) {
							$canceladacfdi = "(CANCELADA)";
						}
						$notacreditocfdi = "";
						/*
				if($r["notacredito"]==1)
				{
					$notacreditocfdi="N.C.";	
				}
				else
				{
					$notacreditocfdi="";
				}
				*/
						//if($row["txtok"]==0)
						if ($r["txtok"] == 0) {
			
							if (is_null($r["archivotxt"]) == true) {
								//$edocuenta="window.open( '../reporte2.php?id=" . $row["idh"] . "');this.disabled=true";
								$edocuenta = "nuevaVP(" . $row["idh"] . ",'');this.disabled=true;";
								$contratobotonfact = "<br>" . '<form name="frm_' . $row["idh"] .  '" id="frm_' . $row["idh"] .  '" method="POST" action="../reporte2.php" target="trg_' . $row["idh"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value ="">' . "<input type =\"button\" value=\"Facturar antes del Pago $notacreditocfdi\" onClick=\"$edocuenta\" $txtagregar  /></form>";
							} else {
								//$edocuenta="window.open( '../reporte2.php?id=" . $row["idh"] . "&archivo=". substr($r["archivotxt"],0,-4) ."');this.disabled=true";
								$edocuenta = "nuevaVP(" . $row["idh"] . ",'');this.disabled=true;";
								$contratobotonfact = "<br>" . '<form name="frm_' . $row["idh"] .  '" id="frm_' . $row["idh"] .  '" method="POST" action="../reporte2.php" target="trg_' . $row["idh"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value =""><input type="hidden" name="archivo" id="archivo" value ="' . substr($r["archivotxt"], 0, -4) . '">' . "<input type =\"button\" value=\"Facturar antes del Pago $notacreditocfdi\" onClick=\"$edocuenta\" $txtagregar /></form>";
							}
						}
			
						//if($row["pdfok"]==0 and $contratobotonfact=="" )
						if ($r["pdfok"] == 0) {
							if ($contratobotonfact == "") {
								$ligapdf = "<br><input type=\"button\" value='Recuperar PDF $notacreditocfdi' onClick=\"document.getElementById('cfdi" . $row["idh"] . "').innerHTML='Recuperando...';cargarSeccion('../recuperar.php','cfdi" . $row["idh"] . "', 'id=" . $row["idh"] . "&filtro=');\" $txteditar>";
							} elseif ($r["tipofactura"] == 'PPD') {
								$ligapdf = "<br><input type=\"button\" value='Recuperar PDF $notacreditocfdi' onClick=\"document.getElementById('cfdi" . $row["idh"] . "').innerHTML='Recuperando...';cargarSeccion('../recuperar.php','cfdi" . $row["idh"] . "', 'id=" . $row["idh"] . "&filtro=');\" $txteditar>";
							}
						} else {
							//if($contratobotonfact=="" )
							//{
							//$ligapdf="<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivopdf"] . "\"  target=\"_blank\" >" .  $row["archivopdf"] . "\n</a>";
							$ligapdf = "<br><a href=\"../general/descargarcfdi.php?f=" .  $r["archivopdf"] . "\"  target=\"_blank\" ><img src='../../imagenes/PDF.png' width='30'>$notacreditocfdi$canceladacfdi\n</a>";
							//}	
						}
			
						//if($row["xmlok"]==0 and $contratobotonfact=="")
						if ($r["xmlok"] == 0) {
							if ($contratobotonfact == "") {
								$ligaxml = "<br><input type=\"button\" value='Recuperar XML $notacreditocfdi' onClick=\"document.getElementById('cfdi" . $row["idh"] . "').innerHTML='Recuperando...';cargarSeccion('../recuperar.php','cfdi" . $row["idh"] . "', 'id=" . $row["idh"] . "&filtro=');\"  $txteditar>";
							} elseif ($r["tipofactura"] == 'PPD') {
								$ligaxml = "<br><input type=\"button\" value='Recuperar XML $notacreditocfdi' onClick=\"document.getElementById('cfdi" . $row["idh"] . "').innerHTML='Recuperando...';cargarSeccion('../recuperar.php','cfdi" . $row["idh"] . "', 'id=" . $row["idh"] . "&filtro=');\"  $txteditar>";
							}
						} else {
							//if($contratobotonfact=="" )
							//{
							//$ligaxml="<br><a href=\"../general/descargarcfdi.php?f=" .  $row["archivoxml"] . "\"  target=\"_blank\" >" .  $row["archivoxml"] . "\n</a>";
							$ligaxml = "<br><a href=\"../general/descargarcfdi.php?f=" .  $r["archivoxml"] . "\"  target=\"_blank\" ><img src='../../imagenes/XML.png' width='30'>$notacreditocfdi$canceladacfdi\n</a>";
							//}
			
							if ($r["tipofactura"] == 'PPD' && ($row["aplicado"] == 1 && is_null($row["aplicado"]) == false) && mysql_num_rows($operacioncfdi) < 2) {
								//$edocuenta="window.open( '../reporte2.php?id=" . $row["idh"] . "');this.disabled=true";
								$edocuenta = "nuevaVP(" . $row["idh"] . ",'');this.disabled=true;";
								$contratobotonfact = "<br>" . '<form name="frm_' . $row["idh"] .  '" id="frm_' . $row["idh"] .  '" method="POST" action="../reporte2.php" target="trg_' . $row["idh"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value ="">' . "<input type =\"button\" value=\Facturar antes del Pago $notacreditocfdi\" onClick=\"$edocuenta\" $txtagregar  /></form>";
							}
						}
			
						// Se agrega leyenda al 
						//if($row["pdfnok"]==0 and $contratobotonfact=="")
						if ($r["pdfn"] == 0 && $contratobotonfact == "") {
							$edocuenta = "";
							$ligapdfn = "<br>PDF PyB$notacreditocfdi$canceladacfdi";
						} else {
							$ligapdfn = "<br>PDF PyB$notacreditocfdi$canceladacfdi";
						}
			
						// Se concatenan todos los botones creados antes
						$reciboscfdi .= $contratobotonfact . $ligapdf . $ligaxml . $ligapdfn;
					}
				} else { //cuando no genero registro en tabla de facturacfdi
			
					if ($txteditar == "") {
			
						//$edocuenta="window.open( '../reporte2.php?id=" . $row["idh"] . "');this.disabled=true";
						$edocuenta = "nuevaVP(" . $row["idh"] . ",'');this.disabled=true;";
						$contratobotonfact = "<br>" . '<form name="frm_' . $row["idh"] .  '" id="frm_' . $row["idh"] .  '" method="POST" action="../reporte2.php" target="trg_' . $row["idh"] . '"><input type="hidden" name="idc" id="idc" value =""><input type="hidden" name="filtro" id="filtro" value ="">' . "<input type =\"button\" value=\"Facturar antes del Pago\" onClick=\"$edocuenta\" $txtagregar /></form>";
			
						$reciboscfdi = $contratobotonfact;
					}
			
					//$reciboscfdi .= $txteditar . "...";
			
				}
			}
			$acc = "<div id='cfdi" . $row["idh"] . "'>" . $reciboscfdi . "</div>";
			$estado = $row["hnotas"] . $acc;
		}
		// Cambio 10/08/20

		if (is_null($row["condonado"])==false and $row["condonado"]==1)
		{

			$estado="CARGO GENERADO";

		}



		$conceptob= $row["tipocobro"] ;//  . "<br><strong>" . $row["observaciones"] . "</strong>";
		$fechaa=$prueba->formatofecha($row["fechanaturalpago"]);
		$fechaa2=$row["fechanaturalpago"];
		$fechab=$prueba->formatofecha($row["fechagracia"]);
		$partea="<tr $clasef>";
		$parteb="<td>\n<table border ='1' $claseft >";
		
		$notacreditot="";
		
		if($row["notanc"] && $row["aprobado"]==1 )
		{
			$notacreditot= "  (N.C.:" . $row["notanc"] . ")";
		}

		if ($concepto!=$row["tipocobro"] || $vcambio==1)
		{
			if($vcambio==1)
			{
				$vcambio=0;
			}
			$concepto = $row["tipocobro"] ;
			$conceptoa=$concepto;// . "<br><strong>" . $row["observaciones"] . "</strong>" ;
			//$concepto = $row["tipocobro"];
			$operacion = "PAGO" . $notacreditot ;


			//$principioa=$principio;
			//$principio="<tr $clasef><td align='center'>" . $prueba->formatofecha($row["fechanaturalpago"])  . "</td><td align='center'>" . $prueba->formatofecha($row["fechagracia"]) . "</td><td>$conceptoa</td>";

			
//para calcular el mes correspondiente en cada renglon	
		
		$mesren = substr($row["fechanaturalpago"],5,2);
		switch ((int)$mesren)
		//switch ($d)
		{
		case 1:
			
			$mesren = "Enero";
			
			break;

		case 2:
			
			$mesren  = "Febrero";

			break;

		case 3:
			
			$mesren  = "Marzo";
			
			break;

		case 4:
			
			$mesren = "Abril";
			
			break;

		case 5:
			
			$mesren = "Mayo";
			
			break;

		case 6:
			
			$mesren = "Junio";
			
			break;

		case 7:
			
			$mesren = "Julio";
			
			break;

		case 8:
			
			$mesren = "Agosto";
			
			break;

		case 9:
			
			$mesren = "Septiembre";
			
			break;

		case 10:
			
			$mesren = "Octubre";
			
			break;

		case 11:
			
			$mesren = "Noviembre";
			
			break;

		case 12:
			
			$mesren = "Diciembre";
			
			break;
		}
			
//****************************************			
			
			
			
			if($auxt==0)
			{


				//$conceptoa=$row["tipocobro"] . "<br><strong>" . $row["observaciones"] . "</strong>"   . $notacreditot;
				$conceptoa .= $notacreditot;
				
				$principio="<tr $clasef><td align='center'>" . $prueba->formatofecha($row["fechanaturalpago"])  . "</td><td align='center'>" . $prueba->formatofecha($row["fechagracia"]) . "</td><td>$conceptoa ($mesren) </td><td>\n<table border ='1' $claseft >";
				$auxt=1;
				$tablainterna="";
			}
			else
			{



				$principioa=$principio;


				//echo $clasef . " :: " . $claseft . "<br>";
				$tablalisto ="$tablainterna";
				//$tablalisto ="\n<table border ='1' $claseft >$tablainterna";
				$principio="<tr $clasef><td align='center'>" . $prueba->formatofecha($row["fechanaturalpago"])  . "</td><td align='center'>" . $prueba->formatofecha($row["fechagracia"]) . "</td><td>$conceptoa ($mesren) </td><td>\n<table border ='1' $claseft >";
				/*
				$fechaa=$prueba->formatofecha($row["fechanaturalpago"]);
				$fechab=$prueba->formatofecha($row["fechagracia"]);
				$partea="<tr $clasef>";
				$parteb="<td>\n<table border ='1' $claseft >";
		*/
			}


		}
		else
		{
			$operacion = "PAGO" . $notacreditot ;

		}


		if (is_null($row["interes"])==false and $row["interes"]==1)
		{

			$operacion = "INT. 10% SOBRE ADEUDO GENERADO EL " . $row["fechagenerado"] . "(" . $row["tipocobro"] . ")" . $notacreditot ;


		}

		$fechapago="";
		$botonPago="";
		if(is_null($row["fechapago"]))
		{
			$fechapago="&nbsp;";

		}
		else
		{
				if($row["aprobado"]==1)
				{
					$fechapago=$prueba->formatofecha($row["fechapago"]);
					if($row["cantidad"]>0 ){ 
						$verPago="window.open('verpago.php?historia=".$row["idh"]."');";
						$botonPago="<br><input type=\"button\" value='Ver Pago' onClick=\"$verPago\">";
					}
					
				}
		}




		if ($row["aplicado"]==false )
		{
			//$Pagado=($row["cantidad"] + $row["ivah"]);
			$Pagado=($row["cantidad"] );

		}
		else
		{
			$Pagado=$row["cantidad"] ;
		}





		if($tablalisto!="")
		{


			//$tablalisto .="</table>\n";

			$historia .= "$principioa $tablalisto</table> </td></tr>\n";
			//$historia .= "<tr $clasef><td align='center'>" . $prueba->formatofecha($row["fechanaturalpago"])  . "</td><td align='center'>" . $prueba->formatofecha($row["fechagracia"]) . "</td><td>$conceptoa</td><td >$tablalisto</td>\n";
			$tablalisto ="";

			$tablainterna = "<tr><td width='300'>$operacion<br><strong>" . $row["observaciones"] . "</strong></td><td align='right' width='100'>$ " . number_format($Pagado,2)  . "</td><td align='center' width='100'> ".$fechapago." ". $botonPago." </td><td width='100'> $estado</td></tr>\n";



		}
		else
		{
			$tablainterna .= "<tr><td width='300'>$operacion<br><strong>" . $row["observaciones"] . "</strong></td><td align='right' width='100'>$ " . number_format($Pagado,2)  . "</td><td align='center' width='100'> ".$fechapago." ". $botonPago." </td><td width='100'>$estado</td></tr>\n";
		}





	}

	if($tablainterna!="")
	{

	
	
	
//para calcular el mes correspondiente en cada renglon	
		
		$mesren = substr($fechaa2,5,2);
		switch ((int)$mesren)
		//switch ($d)
		{
		case 1:
			
			$mesren = "Enero";
			
			break;

		case 2:
			
			$mesren  = "Febrero";

			break;

		case 3:
			
			$mesren  = "Marzo";
			
			break;

		case 4:
			
			$mesren = "Abril";
			
			break;

		case 5:
			
			$mesren = "Mayo";
			
			break;

		case 6:
			
			$mesren = "Junio";
			
			break;

		case 7:
			
			$mesren = "Julio";
			
			break;

		case 8:
			
			$mesren = "Agosto";
			
			break;

		case 9:
			
			$mesren = "Septiembre";
			
			break;

		case 10:
			
			$mesren = "Octubre";
			
			break;

		case 11:
			
			$mesren = "Noviembre";
			
			break;

		case 12:
			
			$mesren = "Diciembre";
			
			break;
		}
			
//****************************************	


		$principio="$partea<td align='center'>$fechaa</td><td align='center'>$fechab</td><td>$conceptob  ($mesren) </td>$parteb";


		$historia .= "$principio $tablainterna</table> </td></tr>\n";

	}


	$historia .= "</table>";
	$cabecera .= "<tr><td class='Cabecera'>Adeudo pendiente</td><td colspan='3'>$ " . number_format($suma,2) . "</td></tr>";
	$cabecera .= "<tr><td class='Cabecera' colspan='4' align='center'>Servicios</td></tr>";
	$cabecera .= "<tr><td class='Cabecera'  width='180'>Servicio de Luz</td><td width='180'>" . $servicioluz. "</td><td>$botonluz</td><td><a href='http://rentascdmx.com//scripts/inmuebles/contratos/$contrato/luz.pdf' target='_blank'><img src='images/icon_pdf.png' width='80'></a></td></tr>";
	$cabecera .= "<tr><td class='Cabecera'>Servicio de Agua</td><td> " . $servicioagua . "</td><td>$botonagua</td><td><a href='http://rentascdmx.com//scripts/inmuebles/contratos/$contrato/agua.pdf' target='_blank'><img src='images/icon_pdf.png' width='80'></a></td></tr>";
	$cabecera .= "<tr><td class='Cabecera'>Servicio de Gas</td><td> " . $serviciogas . "</td><td>$botongas</td><td><a href='http://rentascdmx.com//scripts/inmuebles/contratos/$contrato/gas.pdf' target='_blank'><img src='images/icon_pdf.png' width='80'></a></td></tr>";
	$cabecera .= "<tr><td class='Cabecera'>Pagos de Condominio</td><td> " . $serviciocondominio. "</td><td>$botoncondominio</td><td><a href='http://rentascdmx.com//scripts/inmuebles/contratos/$contrato/condominio.pdf' target='_blank'><img src='images/icon_pdf.png' width='80'></a></td></tr>";
	$cabecera .= "<tr><td class='Cabecera'>Cuenta Predial</td><td> " .$cuentapredial . "</td><td>$botonpredial</td><td><a href='http://rentascdmx.com//scripts/inmuebles/contratos/$contrato/predial.pdf' target='_blank'><img src='images/icon_pdf.png' width='80'></a></td></tr>";
	$cabecera .= "</table></center>\n";
	$cabecera = "<center><h2>Estado de cuenta contrato No. $contrato</h2>\n<table border='0'> <tr><td>$cabecera</td><td>&nbsp;&nbsp;</td><td valign='top'>$listacobros</td></tr></table>\n";
	$cabecera .= "<br>\n<table border = \"1\" width=\"100%\">\n\t<tr class='Cabecera'><th>Fecha Pago</th><th>Fecha Gracia</th><th>Concepto</th><th width='600'>Detalle<br>\n<table border ='1'  class='Cabecera'><tr><th width='300'>Operaci&oacute;n</th><th width='100'>Cantidad</th><th width='100'>F.Pagado</th><th width='100'>Estado</th></tr></table>\n</th></tr>\n";
	//$cabecera .= "<tr class='Cabecera'><th>Fecha Pago</th><th>Fecha Gracia</th><th>Concepto</th><th>Cantidad</th><th>Fecha Pagado</th><th>Estado</th></tr>\n";
	//echo $cabecera . $historia;
		
echo <<< elhtml
<html>
<head><title>Estado de cuenta</title></head>

<script type=text/javascript src="../mijs.js"></script>
<script language="javascript" src="../ajax.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../estilos/estilos.css">
<script language="javascript" type="text/javascript">
//cargarCalendario(0); mes=0;cargarMenu('','');
var mes;



function cargarSeccion(root,loc, param)
{
	var cont;
	cont = document.getElementById(loc);
	ajax=nuevoAjax();
	ajax.open("GET", root + "?"+param ,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
  			cont.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

</script>
<body>
<table border="0" width="100%" >
<tr>
	<td><img src="../../imagenes/logo.png" ></td>
	<td align='center'>
	&nbsp;
	</td>
</tr>
</table>



elhtml;
	echo CambiaAcentosaHTML($cabecera . $historia);

echo "</body></html>";

?>
<div id="myModal2" class="modal fade" role="dialog">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Visualizar Servicios</h4>
      </div>
      <div class="modal-body">
      <iframe src="https://rentascdmx.com//scripts/inmuebles/datosajax/datos.php?idcontrato=<?php echo $contrato ?>" width="100%" height="400" style="border:0px;"></iframe>

   
         <div class="form-group">
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        
      </div>
  </div>
</div>
</div>
</div>

<div id="myModal3" class="modal fade" role="dialog">
<script type="text/javascript">
	$("#correoluz").click(function(){
		document.getElementById("serviciocorreo").value='luz';
	});
$("#correoagua").click(function(){
		document.getElementById("serviciocorreo").value='agua';
	});
$("#correogas").click(function(){
		document.getElementById("serviciocorreo").value='gas';
	});
$("#correocondominio").click(function(){
		document.getElementById("serviciocorreo").value='condominio';
	});
$("#correopredial").click(function(){
		document.getElementById("serviciocorreo").value='predial';
	});

</script>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Envio de Correos Contrato No. <?php echo $contrato; ?></h4>
      </div>
      <div class="modal-body">
     <div id="muestracorreoss"></div>
         <div class="form-group">
        <input type="hidden" name="serviciocorreo" id="serviciocorreo" value="">
    </div>
     <input type="button" onclick="enviarcorreo()" value="Enviar correo">
     </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        
      
  </div>
</div>
</div>
</div>

<div id="myModal4" class="modal fade" role="dialog">
<script type="text/javascript">
	$("#verluz").click(function(){
		var service="luz";
		var contrat='<?php echo $contrato; ?>';
document.getElementById("enlace").src="http://192.168.1.250/scripts/inmuebles/datosajax/datos2.php?idcontrato="+contrat+"&servicio="+service;
	});
$("#veragua").click(function(){
		var service="agua";
		var contrat='<?php echo $contrato; ?>';
document.getElementById("enlace").src="http://192.168.1.250/scripts/inmuebles/datosajax/datos2.php?idcontrato="+contrat+"&servicio="+service;
	});
$("#vergas").click(function(){
		var service="gas";
		var contrat='<?php echo $contrato; ?>';
document.getElementById("enlace").src="http://192.168.1.250/scripts/inmuebles/datosajax/datos2.php?idcontrato="+contrat+"&servicio="+service;
	});
$("#vercondominio").click(function(){
		var service="condominio";
		var contrat='<?php echo $contrato; ?>';
document.getElementById("enlace").src="http://192.168.1.250/scripts/inmuebles/datosajax/datos2.php?idcontrato="+contrat+"&servicio="+service;
	});
$("#verpredial").click(function(){
		var service="predial";
		var contrat='<?php echo $contrato; ?>';
document.getElementById("enlace").src="http://192.168.1.250/scripts/inmuebles/datosajax/datos2.php?idcontrato="+contrat+"&servicio="+service;
	});

</script>
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Servicios del Contrato No. <?php echo $contrato; ?></h4>
      </div>
      <div class="modal-body">
     <iframe src="" id="enlace" width="100%" height="400" style="border:0px;"></iframe>

     <div class="form-group">

    </div>
   
     </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        
      
  </div>
</div>
</div>
</div>

<div id="myModal" class="modal fade" role="dialog">
<script type="text/javascript">
$("#btnluz").click(function(){
		document.getElementById("servicio0").value='luz';
	});
$("#btnagua").click(function(){
		document.getElementById("servicio0").value='agua';
	});
$("#btngas").click(function(){
		document.getElementById("servicio0").value='gas';
	});
$("#btncondominio").click(function(){
		document.getElementById("servicio0").value='condominio';
	});
$("#btnpredial").click(function(){
		document.getElementById("servicio0").value='predial';
	});
	var myDNI = $(this).data('id');
	//document.getElementById('servicio0').value=myDNI;
</script>
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Agregar Montos de Servicio</h4>
      </div>
      <div class="modal-body">
       <div id="muestramsj"></div>
      <div class="form-group">
            <label for="contrato0" class="control-label">Contrato:</label>
            <input type="text" class="form-control" id="contrato0" name="contrato" maxlength="30" value="<?php echo $contrato; ?>" disabled> 
          </div>
           <div class="form-group">
            <label for="contrato0" class="control-label">Periodo:</label>
            <select name="idperiodo" id="periodo0" class="selectpicker">
            	<option>Enero</option>
            	<option>Febrero</option>
            	<option>Marzo</option>
            	<option>Abril</option>
            	<option>Mayo</option>
            	<option>Junio</option>
            	<option>Julio</option>
            	<option>Agosto</option>
            	<option>Septiembre</option>
            	<option>Octubre</option>
            	<option>Noviembre</option>
            	<option>Diciembre</option>
            </select> 
          </div>
         <div class="form-group">
        
             <label for="servicio0" class="control-label">Servicio:</label>
            <input type="text" class="form-control" id="servicio0" name="servicio" required maxlength="30" value="" disabled=" "> 
          </div>
      <div class="form-group">
            <label for="monto0" class="control-label">Monto:</label>
            <input type="text" class="form-control" id="monto0" name="monto" required maxlength="30" value="$"> 
          </div>
           <div class="form-group">
            <label for="estatus0" class="control-label">Estatus:</label>
            <textarea  rows="4" cols="50" class="form-control" id="estatus0" name="estatus" required maxlength="300" value="" ></textarea>
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" id="guardadatos" class="btn btn-primary" onclick="guardar()">Guardar datos</button>
      </div>
    </div>

  </div>
</div>
</div>
<script type="text/javascript">
	function guardar(){
		var contra=document.getElementById("contrato0").value;
		var periodo=document.getElementById("periodo0").value; 

		var services=document.getElementById("servicio0").value;
		var montoS=document.getElementById("monto0").value;
		var monto = montoS.substring(montoS.lastIndexOf('$') + 1)
		var estatus=document.getElementById("estatus0").value;
		$.ajax({
					type: "POST",
					url: "datosajax/agrega.php",
					data: {idcontra:contra,idperiodo:periodo,idservicio:services,total:monto,status:estatus},
					 beforeSend: function(data){
						$("#muestramsj").html("Mensaje: Cargando...");
					  },
					success: function(data){
					$("#muestramsj").html(data);	
					location.reload();
				  }
			});

	}

	function enviarcorreo(){
		var servicioss=document.getElementById("serviciocorreo").value;
$.ajax({
					type: "POST",
					url: "datosajax/enviocorreo.php",
					data: {servicio:servicioss,idcontrato:<?php echo $contrato ?>},
					 beforeSend: function(data){
						$("#muestracorreoss").html("Mensaje: Cargando...");
					  },
					success: function(data){
					$("#muestracorreoss").html(data);	
					//location.reload();
					
				  }
			});
	}
</script>
 <iframe id="ifr" name="loadcsv" style="display: none"></iframe>
    <script>
	    $(".download") .click(function(){
		    if($("#from").val()!=""){
		    	var inicio=document.getElementById("from").value;
          		window.open('descargarEdoCuenta.php?idContrato='+inicio);

			}		  
	    });
    </script>
<!--
<form id="myModal">
<div class="modal fade" id="dataRegister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">CANCELAR FACTURA</h4>
      </div>
      <div class="modal-body">
			<div id="datos_ajax_register"></div>
         <div class="form-group">
            <input type="hidden" class="form-control" id="historia0" name="historia" required maxlength="30" value=""> 
          </div>
		  <div class="form-group">
            <label for="nombre0" class="control-label">Motivo de Cancelación:</label>
            <textarea class="form-control" id="motivo0" name="motivo" required maxlength="500"></textarea>
          </div>
		
          
        
      </div>
      <div class="modal-footer">
        <button type="button" id="guardamotivo" class="btn btn-primary" onclick="this.disabled=true;">Guardar datos</button>
      </div>
    </div>
  </div>
</div>
</form>
-->
<?php

}

}
else
{
	echo "A&uacute;n no se ha firmado con el servidor";
}

?>
