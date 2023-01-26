<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Modulo
$movimiento = @$_GET["movimiento"];
$id=@$_GET["id"];
$pausa=@$_GET["pausa"];
$creador="";

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	//Verifica los privilegios de este modulo
	//$sql="select * from submodulo where archivo ='tareas.php'";
	$sql="select * from submodulo where archivo ='tarea.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
		$priv = $misesion->privilegios_secion($row['idsubmodulo']);
		$priv=split("\*",$priv);

	}

/*


	if ($priv[0]!='1')
	{
		//Es privilegio para poder ver eset modulo, y es negado
		$txtver = "";
		echo "<p class=\"error\">No tiene permiso para ver este m&oacute;ulo</p>";
		exit();
	}

	//para el privilegio de editar, si es negado deshabilida el botón
	if ($priv[2]=='1')
	{
		$txteditar = "";
	}
	else
	{
		$txteditar = " DISABLED ";
	}

	//para el privilegio de borrar, si es negado deshabilida el botón
	if ($priv[3]=='1')
	{
		$txtborrar = "";
	}
	else
	{
		$txtborrar = " DISABLED ";
	}
*/
	$filtro="";
	$orden ="";
		
	
/*	
	switch($movimeinto)
	{
	case '+':
		$filtro = " and idtarea>$id ";
		$orden=" asc ";

	case '-':
	
		$filtro = " and idtarea<$id ";
		$orden=" desc ";
	default:
	
		$orden=" asc ";
	
	}
*/	
	$ajuste=0;
	if($movimiento=='-')
	{
		$filtro = " and t.idtarea<$id ";
		$orden=" desc ";
		$ajuste=0;
	}
	else
	{

		$filtro = " and t.idtarea>$id ";
		$orden=" asc ";	
		$ajuste=-1;		
	}
	
	if(!$id)
	{
	
		$filtro="";
		
	}
	$hoy=date('Y-m-d');
	$listatareasd="No nay tareas";
	$numrt0=0;
	$sql="select t.idtarea as idtt,tarea, t.idasunto idt, t.notatarea as tdesc,terminado,vencimiento, count(idusurio),fechat,datediff('$hoy',fechat) as diast from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " group by fechat, t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento order by t.idtarea";
	$datos=mysql_query($sql);
	$numrt0=mysql_num_rows($datos);
	
		
	$numrt1=0;
	if($numrt0>0)
	{
	  /*
		$hoy=date("Y-m-d");
		$listatareasd="<table border\"1\" style=\"font-size:10\">";
		while($row = mysql_fetch_array($datos))
		{
			$elidt=$row["idtt"];
			$eltitulot=CambiaAcentosaHTML($row["tarea"]);
			$fechalimite=$row["vencimiento"];
			$fondo="";		
			if($fechalimite<$hoy)
			{
				$fondo=" bgcolor='#b1c228' ";
				
			}
			
			
			
			$listatareasd .="<tr $fondo>";			
			$listatareasd .="<td><a href=\"javascript:cargaTareas('$ruta/vertarea.php','contenido','id=$elidt');\" style=\"text-decoration:none;color:#000000;font-size:10;\"><b>Ver</b></a><br><a href=\"javascript:cargarSeccion('$ruta/tareaseguimiento.php','contenido','id=$elidt' );\" style=\"text-decoration:none;color:#000000;font-size:10;\"><b>Seg.</b></a></td>";			
			$listatareasd .="<td>($elidt) $eltitulot</td>";			
			
			$listatareasd .="</tr>";
		}
		$listatareasd .="</table>";
	
	  */
	
	//inicia la variable que contendrá la consulta
	
	
	  $sql="select t.idtarea as idtt,tarea, t.idasunto idt, t.notatarea as tdesc,terminado,vencimiento, count(idusurio), t.idusuario as idusuu,fechat,datediff('$hoy',fechat) as diast from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " $filtro group by fechat, t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento,t.idusuario order by t.idtarea $orden";

	  //$sql="select idtarea,tarea, t.idasunto idt,  t.notatarea as tdesc,terminado,vencimiento from tarea t where terminado = 0 $filtro order by idtarea $orden";
	
		$datos=mysql_query($sql);
		$numrt1=mysql_num_rows($datos);
		if(mysql_num_rows($datos)<=0)
		{

		      $sql="select t.idtarea as idtt,tarea, t.idasunto idt, t.notatarea as tdesc,terminado,vencimiento, count(idusurio), t.idusuario as idusuu,fechat,datediff('$hoy',fechat) as diast from tarea t, tareausuario tu where terminado = 0 and t.idtarea=tu.idtarea and tu.idusurio=" . $misesion->usuario  . " group by fechat, t.idtarea, tarea, t.idasunto, t.notatarea, terminado, vencimiento, t.idusuario order by t.idtarea";

		      //$sql="select idtarea,tarea, t.idasunto idt, t.notatarea as tdesc,terminado,vencimiento from tarea t where terminado = 0  order by idtarea";
			$datos=mysql_query($sql);		
			$numrt1=mysql_num_rows($datos);	
			if($movimiento=="-")
			{
				$numrt1=1;
			}
		}
		
		if($movimiento!="-")
		{
	
			if($filtro=="")
			{
				$numrt1=1;	
			}
			elseif(($numrt0-($numrt1+$ajuste))==0)
			{
				$numrt1=$numrt0;	
			}
			else
			{
				$numrt1=$numrt0-($numrt1+$ajuste);	
			}
		
		}
		
		$row = mysql_fetch_array($datos);
		
		$id=$row["idtt"];
		$titulo=CambiaAcentosaHTML($row["tarea"]);
		$fechalimite=$row["vencimiento"];
		$creacion=$row["fechat"];
		if(is_null($row["fechat"]))
		{
			$diast="";
		}
		else
		{
			$diast=$row["diast"];
		}	
		
		
		//$asunto=$row["adesc"];
		$descripcion=CambiaAcentosaHTML($row["tdesc"]);
		$asunto="";
		$fondo="";
		$vencido="";
		$hoy=date("Y-m-d");
		if($fechalimite<$hoy)
		{
		  $fondo=" bgcolor='#b1c228' ";
		  $vencido="<FONT COLOR='#FF0000'> VENCIDO</FONT> ";
		}


		if($row["idt"]!=0)
		{
			$sql1="select * from asunto a,directorio d where a.iddirectorio=d.iddirectorio and idasunto=" . $row["idt"] ;
			$datos1=mysql_query($sql1);
			$row1 = mysql_fetch_array($datos1);
			$asunto = "<b> " . $row1["nombre"] . " " . $row1["apaterno"] . " " . $row1["amaterno"] . "</b><br>" . CambiaAcentosaHTML($row1["descripcion"]);			
			
		}
		else
		{
			$asunto="";
		}
		
		$sql1="select * from usuario where idusuario =" . $row["idusuu"];
		$datos1=mysql_query($sql1);
		$row1 = mysql_fetch_array($datos1);
		$creador = "<b style='font-size:11;'>Creado por: " . $row1["nombre"] . " " . $row1["apaterno"] . " " . $row1["amaterno"] . "</b>";

		$actualizado = "";
		$sql1="select * from tareausuario where notificado = false and idtarea =$id  and idusurio = " . $misesion->usuario;
		$datos=mysql_query($sql1);
		$totalr=mysql_num_rows($datos);
		if($totalr>0)
		{
			$actualizado="<b style='color:FF0000' >Actualizado</b>";
		}
		else
		{
			$actualizado="";
		}

	}
	else
	{
	
		$id="";
		$titulo="No hay tareas";
		$fechalimite="pendiente";
		$asunto="";
		$descripcion="";		
	
	}
	
	



echo <<<formulario1
<table border="1" style="font-size:10px" width="700">

<tr>
<td colspan="3">
	<table border="0" width="700" $fondo >
 	<tr>
      		<td>
         		$creador<br>
         		<b><a href="javascript:cargaTareas('$ruta/vertarea.php','contenido','id=$id');" style="text-decoration:none;color:#FF0000;font-size:14;">($id) $titulo</a></b> <br>
         		<b style="font-size:10;font-weight:bold;text-align:center;">(vence el $fechalimite $vencido, d&iacute;as transcurridos $diast a partir del $creacion)</b><br>


         	</td>
         	
		<td>

			<b>Descripci&oacute;n de la tarea</b>
			<div style="overflow:auto; height:70px;width:450px;font-size:10px">
				$descripcion
			</div>

		</td>
	</tr>
	</table>
</td>
</tr>
<tr>
	<td width="10"> <a href="javascript:cargaTareas('$ruta/listatareap.php','tareasdiv', 'id=$id&movimiento=-&pausa=' + pausacarrusel);" style="text-decoration:none;color:#505046"><<</a> </tD>
	<td align="center" style="text-decoration:none;color:#505046">$numrt1 / $numrt0 $actualizado<input type="hidden" id="idtareac" value="$id"><input type="checkbox" onclick="if(this.checked==0){pausacarrusel='';}else{pausacarrusel='checked'};" $pausa>Pausa</td>
	<td width="10"><a href="javascript:cargaTareas('$ruta/listatareap.php','tareasdiv', 'id=$id&movimiento=*&pausa=' + pausacarrusel);" style="text-decoration:none;color:#505046"> >> </a></td>
		
</tr>
</table>
formulario1;


}
else
{

	echo "";

}




?>