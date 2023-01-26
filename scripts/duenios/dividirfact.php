<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo

$ida = @$_GET["ida"];
$idb = @$_GET["idb"];
$la=@$_GET["la"];
$lb=@$_GET["lb"];
//$idduenio =@$_GET["idd"];
//$fechagen =@$_GET["fechagen"];

/*
$ida = @$_POST["ida"];
$ida = @$_POST["idb"];
$la = @$_POST["idb"];
$lb = @$_POST["idb"];
*/


$btncta = " disabled ";
$listac="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='edosduenio.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$ruta= $row['ruta'] ;
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
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

	//para el privilegio de editar, si es negado deshabilida el botÛn
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el botÛn
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}






	//$ida=110;
	$selectla="";
	$sql = "select *,c.idduenio as idd from cfdiedoduenio c, cfdipartidas p, edoduenio e where c.idcfdiedoduenio = p.idcfdiedoduenio and p.idedoduenio = e.idedoduenio and c.idcfdiedoduenio = $ida";
	$operacion = mysql_query($sql);
	$row = mysql_fetch_array($operacion);
	
	//obtengo todos los elementos para poder colocar todas las instrucciones no facturadas en ambas listas
	$idd = $row["idd"];
	$fechagen = $row["fechagen"];
	
	$optsa="";
	$optsb="";
	$sel="";
	$sqllista = "select c.idcfdiedoduenio as idf, count(c.idcfdiedoduenio) from cfdiedoduenio c, cfdipartidas cp, edoduenio e where not(c.idcfdiedoduenio in(select idcfdiedoduenio from facturacfdid)) and c.idcfdiedoduenio = cp.idcfdiedoduenio and cp.idedoduenio = e.idedoduenio and fechagen = '$fechagen' and c.idduenio = $idd   group by c.idcfdiedoduenio";
	$operacionlista = mysql_query($sqllista);
	while($rowlista = mysql_fetch_array($operacionlista))
	{
		if($rowlista["idf"]==$ida)
		{
			$sel = " selected ";
		}
		$optsa .= "<option value='" . $rowlista["idf"] . "' $sel>" . $rowlista["idf"] . "</option>";	
		$optsb .= "<option value='" . $rowlista["idf"] . "'>" . $rowlista["idf"] . "</option>";
		$sel = "";
	}

	
	$selectla="<select  name='ida' id='ida0' onChange=\"document.getElementById('ida').value = this.value; cargarSeccion('cambiolista.php','diva','ida=' + this.value + '&s=0' ); \" >$optsa</select>";
	$selectlb="<select  name='idb' id='idb0' onChange=\"document.getElementById('idb').value = this.value;cargarSeccion('cambiolista.php','divb','ida=' + this.value  + '&s=1'); \" ><option value='0'>Nueva Orden</option>$optsb</select>";	


	//lista de partidas de la primer orden
	/*
	$sqlp = "select *,c.idedoduenio as idf from cfdipartidas c, edoduenio d where c.idcfdiedoduenio = $ida and c.idedoduenio = d.idedoduenio";
	$optp="";
	$operacionp = mysql_query($sqlp);
	while($rowp = mysql_fetch_array($operacionp))
	{	
		$optp .= "<option value='" . $rowp["idf"] . "'>" . $rowp["notaedo"] . "</option>";

	}
	*/
	$sqlp = "select *,c.idedoduenio as idf, ((importe + iva)*(-1)) as imp from cfdipartidas c, edoduenio d where c.idcfdiedoduenio = $ida and c.idedoduenio = d.idedoduenio";
	$optp="";
	$operacionp = mysql_query($sqlp);
	while($rowp = mysql_fetch_array($operacionp))
	{	
		$inm="";
		$sqlinm = "select * from inmueble where idinmueble = " . $rowp["idinmueble"];
		$operacioninm = mysql_query($sqlinm);
		//echo mysql_num_rows($operacioninm);
		if(mysql_num_rows($operacioninm)>0)
		{
			$rowinm = mysql_fetch_array($operacioninm);
			$inm = 	utf8_encode(" " . $rowinm["calle"] . " " . $rowinm["numeroext"]  . " " . $rowinm["numeroint"]);
		}
		
		
		
		$optp .= "<option value='" . $rowp["idf"] . "'>$" . number_format($rowp["imp"], 2, '.', ',') . " " . $rowp["notaedo"] . "$inm</option>";

	}
	
	
	$sella="<select  name='la[]' id='la' size='10' multiple >$optp</select>";
	
//verifico si vienen todos los balores de ida e idb juntos
	//echo "ida: $ida <br>idb: $idb";
	if($idb<>'')
	{//se cambian los registros de las selecciones o se carga uno nuevo para poder relaizar el cambio
	
		//echo "info completa";
		
		//Leo la lista de lb y preparo el where (lista de elementos para el in)
		$pwhere = "(";
		for ($i=0;$i<count($lb);$i++)    
		{     
			$pwhere .= $lb[$i] . ", ";    
		} 
		$pwhere = substr($pwhere,0,strlen($pwhere)-2) . ") ";
		
		
		//verifico si vienee idb = 0 para crear una nueva orden
		//echo $idb;
		if($idb==0)
		{
			$sqlf = "select sum(importe) as imp, sum(iva) as iv from edoduenio e  where idedoduenio in $pwhere ";
		
		
			$operacionf = mysql_query($sqlf);
			$rowf = mysql_fetch_array($operacionf);
			$importe = $rowf["imp"] * ( -1);
			$iva = $rowf["iv"] * ( -1);
		

			$sqlf="insert into cfdiedoduenio ( subtotald, impuestos, totald, notacredito, idduenio, reportadad) values ";
			$sqlf .="($importe,$iva," . ($importe + $iva) . ",false,$idd,true);";


			$operacionf = mysql_query($sqlf);
			
			$idb=mysql_insert_id();

		}
		
		//busco los de idedoduenio y el ida y los actualizao con el idb
		
		$sqlap = "update cfdipartidas set idcfdiedoduenio = $idb where idedoduenio in $pwhere and idcfdiedoduenio = $ida";
		$operacionap = mysql_query($sqlap);
		
		$sqlap = "select * from cfdipartidas where idcfdiedoduenio = $ida";
		$operacionap = mysql_query($sqlap);
		if(mysql_num_rows($operacionap)==0)
		{
			$sqlap = "delete from cfdiedoduenio where idcfdiedoduenio = $ida";
			$operacionap = mysql_query($sqlap);
			
		}
		
		echo "Aplicado, cerrar la ventana";
		
		
		
	}
	else
	{//estan inocmpletos, se muestra el formulario
	

//Genero el formulario de los submodulos

echo  <<<formulario1
<html>
<head>

<link rel="stylesheet" type="text/css" href="../../estilos/estilos.css">
<script type=text/javascript src="../mijs.js"></script>
<script language="javascript" src="../ajax.js" type="text/javascript"></script>

</head>
<script language="javascript" type="text/javascript">

 
function mover(de, para)
{
    select = document.getElementById(de);
    select1 = document.getElementById(para);
    select1.appendChild(select.options[select.selectedIndex]);
    select.remove(select.selectedIndex);
    //marcartodos(para); 
}
    
function marcartodos(para)
{
	select1 = document.getElementById(para);
	for (var i = 0; i < select1.options.length; i++) { 
    	select1.options[i].selected = true; 
    	//alert('marco el : ' + i  + ' de:' + para);
    } 
}     

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
<center>
<h1>Separar conceptos</h1>
<form method ="GET">

<table border = '1' >
<tr>
	<td valign ='top'>
	

		$selectla<br>
		<div id='diva'>
		$sella
		</div>
		
		
	</td>
	<td valign = 'center'>
		<input type="button" value='>>' OnClick = "ida0.disabled = true;idb0.disabled = true; mover('la','lb');"><br><br><br>
		<input type="button" value='<<' OnClick = "ida0.disabled = true;idb0.disabled = true; mover('lb','la');">
	</td>
	<td valign ='top'>

		$selectlb<br>
		
		<div id='divb'>
			<select name='lb[]' id='lb' size='9' multiple>
			
			</select>
		</div>
	</td>
</tr>
<tr>
	<td colspan = 3  align = "center">
		<input type="hidden" name="ida" id="ida" value="$ida">
		<input type="hidden" name="idb" id="idb" value="0">
		<input type="submit" value="Confirmar" OnClick="marcartodos('la');marcartodos('lb');">
	</td>
</tr>
</table>


</form>
</center>
formulario1;

}
/*

<script >
    function add1()
{
    select = document.getElementById('selectElementId');
    var opt = document.createElement('option');
    opt.value = 'a';
    opt.innerHTML = 'AA';
    select.appendChild(opt);
    
}
 
   function eliminar()
{
    select = document.getElementById('selectElementId');
    select1 = document.getElementById('selectElementId1');
    select1.appendChild(select.options[select.selectedIndex]);
    //alert(select.selectedIndex);
    
    select.remove(select.selectedIndex);
    
}
    
    
function marcartodos()
{
	select1 = document.getElementById('selectElementId1');
	for (var i = 0; i < select1.options.length; i++) { 
    	select1.options[i].selected = true; 
    } 
}    
    
</script>

<select id="selectElementId" size='10' multiple></select>
<input type='button' value = 'uno mas' OnClick ='add1();'>
<input type='button' value = 'XX' OnClick ='eliminar();'>
<select id="selectElementId1" size='10' multiple></select>
    

*/



}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>