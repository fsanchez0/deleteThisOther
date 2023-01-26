<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include '../general/correoclass.php';
include '../general/cargadescarga.php';

/*
$accion = @$_GET["acciont"];
$id=@$_GET["id"];
$idst=@$_GET["idst"];
$descripcion=@$_GET['descripcion'];
$lista=@$_GET['lista'];
$listaa1=@$_GET['listaa'];
*/

$accion = @$_POST["acciont"];
$id=@$_POST["id"];
$idst=@$_POST["idst"];
$descripcion=@$_POST['descripcion'];
$lista=@$_POST['lista'];
$listaa1=@$_POST['listaa'];

$descripciona=$descripcion;

$gestor= new carga;
$enviocorreo = New correo;
$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

	//Verifica los privilegios de este modulo
	$sql="select * from submodulo where archivo ='tarea.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta= $row['ruta'];
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
	//inicia la variable que contendrá la consulta
	$hoy=date("Y-m-d");
	$sql = "update tareausuario set notificado = true, fechanotificado='$hoy' where idtarea = $id  and idusurio = " . $misesion->usuario;
	$operacion = mysql_query($sql);

	$sql="";

	//Segun la acción que se tomó, se procederá a editar el sql
	$confirmacion=0;
	//echo $accion;
	switch($accion)
	{
	case "0": // Agrego
		$fecha=date("Y-m-d");
		$hora=date("H:m:s");
		
		/*if($idst!=0)
		{
			echo $id=$idst;
		}
		else
		{
			$idst=$id;
		}
		*/
		
		$sql="insert into tseguimiento (idusuario, idtarea, fechaavance,hora, notaavancet) values (" . $misesion->usuario. ",$id ,'$fecha','$hora','$descripcion')";
	
		
		//$sql = "delete from tareausuario where idtarea=$id";
                     //echo $lista;
		     $listaa = split('[*]',$lista);
		     $sqls="";
		     $idua="";
		    foreach ($listaa as $idua)
			{
				
			 $sqls="select * from tareausuario where idtarea=$id and idusurio=$idua";
			 // echo ">>$idu<<<br>";
			  if($sqls!="")
			  {  
			    //echo "ejecuto $sql";
			    $operacion = mysql_query($sqls);
			    if(mysql_num_rows($operacion)>0)
			      {
				 $sqls="update tareausuario set notificado = 0 where idtarea=$id and idusurio=$idua";
				$operacion = mysql_query($sqls);
				 $sqls="update tareausuario set notificado = 0 where idtarea=$idst and idusurio=$idua";
				$operacion = mysql_query($sqls);
			      }
			    else
			      {  
				 $sqls = "insert into tareausuario (idtarea,idusurio,notificado) values ($id,$idua,0)";
				$operacion = mysql_query($sqls);  
				 $sqls = "insert into tareausuario (idtarea,idusurio,notificado) values ($idst,$idua,0)";
				$operacion = mysql_query($sqls);
			      }
			   }
			  $idua=$idu;
			
			}

		$sqls = "update tareausuario set notificado = 0 where idtarea =$id ";
			$operacion0 = mysql_query($sqls);
		$sqls = "update tareausuario set notificado = 0 where idtarea =$idst ";
			$operacion0 = mysql_query($sqls);			
		$sqls="select * from tareausuario where idtarea in ($id, $idst) or and idusurio=$idu";

		//echo "<br>Agrego";
		$titulo="";
		$fechalimite="";
		$descripcion="";
		$idasunto=0;
		
		break;

	case "1": //Borro

		$sql="delete from tseguimiento where idtseguimiento=$id";
		//echo "<br>Borro";
		$id="";
		break;

	case "3": //Actualizo

		$sql = "update tseguimiento set notaavancet='$descripcion' where idtseguimiento=$id";
		///echo "<br>Actualizo";
		$titulo="";
		$fechalimite="";
		$descripcion="";
		$idasunto=0;
		break;
	
	}
	
	//echo $sql;
	//ejecuto la consulta si tiene algo en la variable
	
	if($sql!="")
	{

		$operacion = mysql_query($sql);
		$confirmacion=$operacion;
		$idst=mysql_insert_id();
		
		if($listaa1!="")
		{
		//aqui va la inserción de la lista de los archivos
			//echo $listaa1;
			$gestor->colocar_arhivos($listaa1,$id, $idst);
			$listaa = split('[|]',$listaa1);
			
			foreach ($listaa as $arch)
			{
				$sqlaa="insert into archivoseguimiento (idtseguimiento, archivo) values ($idst,'$arch')";		
				$operacionaa = mysql_query($sqlaa);
					
					
			}
		}
		

	}
	//Preparo las variables para los botónes
	$boton1="Limpiar";
	$boton2="Agregar";

	//En caso de ser accion 2, cambiar los valores de los nombres de los botones
	//y la acción a realizar para la siguiente presión del botón agregar
	//en su defecto, sera accón agregar
	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from tarea where idtarea = $id";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$titulo=CambiaAcentosaHTML($row["tarea"]);
			$idasunto=$row["idasunto"];
			$fechalimite=$row["vencimiento"];
			$descripcion=$row["notatarea"];
		}



	}
	else
	{
		$accion="0";
	}
	//echo $accion;
	$asunto="";
	$terminar="";
	$creador="";
	$hoy=date('Y-m-d');
	$sql="select *,datediff('$hoy',fechat) as diast from tarea where idtarea = $id";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$titulo=CambiaAcentosaHTML($row["tarea"]);
		$idasunto=$row["idasunto"];
		$fechalimite=$row["vencimiento"];
		$creacion=$row["fechat"];
		$diast=$row["diast"];
		$descripciont=$row["notatarea"];
		
		if($row["idusuario"]==$misesion->usuario)
		{
			//$terminar="<input type=\"button\" value=\"Terminar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea terminar &eacute;ste elemento?')){cargarSeccion('$ruta/tareasasignadas.php','contenido','acciont=4&id=$id' )}\" >";
			$terminar="<input type=\"button\" value=\"Terminar\" onClick=\"if(confirm('&iquest;Est&aacute; seguro que desea terminar &eacute;ste elemento?')){cargarSeccion_new('$ruta/tarea.php','contenido','accion=4&id=$id')}\" >";
		
		}

		$sql1="select * from usuario where idusuario =" . $row["idusuario"];
		$datos1=mysql_query($sql1);
		$row1 = mysql_fetch_array($datos1);
		$creador = "<b>Generada por: " . $row1["nombre"] . " " . $row1["apaterno"] . " " . $row1["amaterno"] . "</b>";

	}
	
	
	
/*	
	if($idasunto>0)
	{
		$sql="select * from asunto where idasunto = $idasunto";
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
		$asunto = CambiaAcentosaHTML("(" . $row["expediente"] . ")" . $row["descripcion"] );
	
	}

	$sql="select * from asunto";
	$asunto1 = "<select name=\"idasunto1\" ><option value=\"0\">Seleccione uno de la lista</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$marcar ="";		
		if($idasunto==$row["idasunto"])
		{
			$marcar=" selected ";
		}
		$asunto1 .= "<option value=\"" . $row["idasunto"] . "\" $marcar  >" . CambiaAcentosaHTML("(" . $row["expediente"] . ")" . $row["descripcion"] ) .  "</option>";

	}
	$asunto1 .="</select>";
*/        
        $selectusuarios="";
	$sql="select * from usuario where activo = 1";
	$selectusuarios = "<select name=\"idusuario\" id=\"idusuario\" ><option value=\"0\">Seleccione uno de la lista</option><option value=\"T\">Todos</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
	
		$selectusuarios .= "<option value=\"" . $row["idusuario"] . "\"  >" . CambiaAcentosaHTML( $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"]  ) .  "</option>";

	}
	$selectusuarios .="</select>";
	
	$notificaciones="";
	$sql="select nombre, apaterno, amaterno from usuario u, tareausuario t where u.idusuario = t.idusurio and idtarea=$id ";
	$notificaciones = "<ul>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
	
		$notificaciones .= "<li>" . CambiaAcentosaHTML( $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"]  ) .  "</li>";

	}
	$notificaciones .="</ul>";	


//Genero el formulario de los submodulos

if($confirmacion==0)
{

echo <<<formulario1
<center>
<h1>Seguimiento</h1>


<b>($id) $titulo</b> <br>
<b style="font-size:10;font-weight:bold;text-align:center;">$creador<br>(vence el $fechalimite, d&iacute;as transcurridos $diast a partir del $creacion)</b><br>
<input type="button" value="<< Regresar a tarea" onClick="cargaTareas('$ruta/vertarea.php','contenido','id=$id');" >
$terminar<br>

<!--
<b>Asunto</b>
<p>
$asunto
</p>
-->

<b>Descripci&oacute;n</b>
<p>
$descripciont
</p>
<table border="0">
<tr>
	<td>El seguimiento se notifica a:</td>
</tr>
<tr>
	<td>$notificaciones</td>
</tr>
<tr>
	<td>Descripci&oacute;n</td>
</tr>
<tr>
	<td>
		<textarea name="descripcion" id="descripcion" rows="3" cols="60"  onKeyUp="conteoletras(this.value, 'nocaracteres')">$descripcion</textarea><div id="nocaracteres" align="right">Max. 1000 caracteres</div>
		<center><input type="button" value="$boton2" name="agregar" onClick="if(descripcion.value!='' ){ if(this.value=='Actualizar'&&document.getElementById('priveditar').value==1){ cargarSeccion_new('$ruta/tareaseguimiento.php','contenido','acciont=' + document.getElementById('acciont').value + '&id=' + document.getElementById('ids').value + '&descripcion=' + document.getElementById('descripcion').value + '&lista=' + document.getElementById('lista').value + '&listaa=' + document.getElementById('listaa').value)}; if(this.value=='Agregar'&&document.getElementById('privagregar').value==1){this.disabled=true;  cargarSeccion_new('$ruta/tareaseguimiento.php','contenido','acciont=' + document.getElementById('acciont').value + '&id=' + document.getElementById('ids').value + '&descripcion=' + document.getElementById('descripcion').value + '&lista=' + document.getElementById('lista').value  + '&listaa=' + document.getElementById('listaa').value + '&idst=$idst')}};" ></center>
	</td>
</tr>
</table>

	      <form action="$ruta/cargar.php" name="marcoi" method="post" enctype="multipart/form-data" target="c_archivos" onsubmit="startUpload();" >

    	            Archivo: <input name="myfile" type="file" onchange="javascript: submit()"/>
                    <input name="listaa" id="listaa" type="hidden" value=""/>
                    <input type="hidden" name="accion" id="accion" value="1">

              </form>
              <iframe id="c_archivos" name="c_archivos" src="#" style="width:300;height:100;border:0px solid #fff;"></iframe>


<form>

<table border="0">

<tr>
	<td>

	            Notificar a:
		    $selectusuarios
		    <input type="button" value="+" onClick="cargarSeccion('$ruta/listausu.php','listadeusu','accion=0&id=' + idusuario.value + '&lista=' + document.getElementById('lista').value );">
		
		    <div id="listadeusu" style="hieght:100px;overflow:auto">
		        <input type="hidden" name="lista" id="lista" value="">
		    </div>
	</td>
</tr>
<tr>
	<td  align="center">

		<input type="button" value="$boton1" onClick="ids.value='';accion.value='0';agregar.value='Agregar';this.value='Limpiar';;descripcion.value='';">&nbsp;&nbsp;&nbsp;&nbsp;		
		<input type="hidden" name="ids" id="ids" value="$id">
		<input type="hidden" name="acciont" id="acciont"  value="$accion">
		<input type="hidden" name="privagregar" id="privagregar" value="1">
		<input type="hidden" name="priveditar"  id="priveditar"value ="0">

	</td>
</tr>
</table>
</form>
</center>
formulario1;
}
else
{
	echo $mensaje ="<h1>Se ha agregado satisfactoriamente su seguimiento el dia $fecha a las $hora para la tarea >>$titulo<< </h1><input type=\"button\" value=\"Regresar a tarea\" onClick=\"cargaTareas('$ruta/vertarea.php','contenido','id=$id&idp=$idp');\" >";
	//echo "<h1>Se ha agregado satisfactoriamente su seguimiento el dia $fecha a las $hora para la tarea >>$titulo<< </h1>";
        $correo="";
	$inicio=true;
	//foreach ($listaa as $idu)
	//{	  	  
  	//  if($idu!="")
	//  {
	  	$sqls="select email from usuario, tareausuario where tareausuario.idusurio=usuario.idusuario and idtarea=$id";
	  	$operacion0 = mysql_query($sqls);
	  	if(mysql_num_rows($operacion0)>0)
	  	{

	  		while ($row0=mysql_fetch_array($operacion0))
			{
	  		if($row0["email"])
	  		{
			  if($inicio==true)
			    {
			      $correo=$row0["email"];
			      $inicio=false;	
			    }
			  else
			    {
			      $correo .="," .$row0["email"];
			    }  
			  //	$mensaje = $row0["nombre"] . ":<br> Ha sido notificado en el seguimiento de la tarea >>$titulo<< el d&iacute;a $fecha a las $hora para su consulta.<br><br>Descripci&oacute;n: <br>$descripcion";
			  //$enviocorreo->enviar($row0["email"], "Notificaci&iacute;n de la tarea >>$titulo<<", $mensaje);		
	  			//$enviocorreo->enviar("miguelmp@prodigy.net.mx,cemaj@prodigy.net.mx,lucero_cuevas@prodigy.net.mx,miguel_padilla@nextel.mx.blackberry.com", "Cobro realizado", $mensaje);
	  		
	  		}
			}
	  
	  	}
		$mensaje = utf8_decode("Ha sido notificado en el seguimiento de la tarea >>$titulo<< el d&iacute;a $fecha a las $hora para su consulta.<br>($descripciont)<br><br>Descripci&oacute;n: <br>" . $misesion->nombre . "->  $descripciona");
		$enviocorreo->enviar($correo, "Notificaci&iacute;n de la tarea >>$titulo<<", $mensaje);
		//echo "notificacion enviada a $correo";
		//    }
		//}
	
	
	
}
	

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}




?>