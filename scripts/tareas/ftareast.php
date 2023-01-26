<?php
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';

//Formulario para el script de tarea.php
//Es para mostrar los datos para editar o para agregar un elemento nuevo
//interactua con el otro script en las acciones de editar y nuevo
$accion = @$_GET["accion"];
$id=@$_GET["id"];
$idst=@$_GET["idst"];
$tipot=@$_GET["tt"];


$grupo=@$_GET['grupo'];
$vigencia=@$_GET['vigencia'];
$tarea=@$_GET['tarea'];
$desc=@$_GET['desc'];
$desct=@$_GET['desct'];
$listaasignados=@$_GET['lista'];


$listagrupos="";

//$desc="";
//$grupo="";
//$tarea="";
//$vencimiento="";
$lista="";
$listausutarea="";


$mostrarformulario=1;



$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
	$sql="select * from submodulo where archivo ='tarea.php'";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
		$dirscript= $row['ruta'] . "/" . $row['archivo'];
		$ruta=$row['ruta'];
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
	
	
	
//***************************	
	
	
	
	$mensaje="";
	$sql="";
	
	switch($accion)
	{
	case "0": // Agrego

		
		$sql="insert into tarea (tarea,idusuario,vencimiento,notatarea,idsubde,subtarea) values ('$tarea'," . $misesion->usuario . ",'$vigencia','$desct',$id,1)";
		$mostrarformulario=0;
		//echo "<br>Agrego";		
		break;

	case "1": //Borro


		$sql = "delet from sutareas where idsub=$idst";
		$datos=mysql_query($sql);


		$sql="select count(*) as num from tseguimiento where idtarea=$idst";
		$datos=mysql_query($sql);
		$row=mysql_fetch_array($datos);
		if($row["num"]!=0)
		{
			$sql="";
			$mensaje="<font color='red'>No se borro la tarea porque tiene seguimiento</font>";
		}
		else
		{
			$sql="delete from tareausuario where idtarea=$idst";
			$datos=mysql_query($sql);
			$sql="delete from tarea where idtarea=$idst";		
			//echo "<br>Borro";
			$id="";
		}
		$mostrarformulario=0;
		break;

	case "3": //Actualizo

		$sql = "update tarea set tarea='$tarea',vencimiento='$vigencia', notatarea='$desct' where idtarea=$idst";
		///echo "<br>Actualizo";
		$mostrarformulario=0;
		break;
		
	case "4": //terminar tarea
	
		$sql="update tarea set terminado=1 where idtarea = $idst";
		$mostrarformulario=0;

	}
//ejecuto la consulta si tiene algo en la variable
	if($sql!="")
	{
		//echo $sql . "<br>";
		$operacion = mysql_query($sql);
		//$idst=mysql_insert_id();
		//echo $listaasignados . $accion;
		switch($accion)
		  {
		  case "0": // Agrego

		    $idst=mysql_insert_id();
		    
		    //genero el registro de la lista de la tarea original
		    $sql="insert into subtareas (idtarea,idsub) values ($id,$idst)";
		    $operacion = mysql_query($sql);
		    
		    
		    $listaa = split('[*]',$listaasignados);
		    
		    $sql = "insert into tareausuario (idtarea,idusurio,notificado) values ($idst," . $misesion->usuario . ",0)";
		    $operacion = mysql_query($sql);
		    
		    foreach ($listaa as $idu)
			{
			  $sql = "insert into tareausuario (idtarea,idusurio,notificado) values ($idst,$idu,0)";
			  $operacion = mysql_query($sql);
			}
		    
		    break;

		  case "3": //actualizo

		    $sql = "delete from tareausuario where idtarea=$idst";
		    $operacion = mysql_query($sql);
		    $listaa = split('[*]',$listaasignados);
		    
		    $sql = "insert into tareausuario (idtarea,idusurio,notificado) values ($idst," . $misesion->usuario . ",0)";
		    $operacion = mysql_query($sql);

		    foreach ($listaa as $idu)
			{
			 $sql = "insert into tareausuario (idtarea,idusurio,notificado) values ($idst,$idu,0)";
			  $operacion = mysql_query($sql);
			}
		    
		    break;
	 	 case "1": //Borro

		    $sql = "delete from tareausuario where idtarea=$id";
		    $operacion = mysql_query($sql);
		    
		    break;
	       
		
		  }
	}	
	
	
//**********************************	
	
	
	
	$boton1="Cancelar";
	$boton2="Agregar";

	if($accion=="2")
	{
		$boton1="Cancelar";
		$boton2="Actualizar";
		$accion="3";
		$sql="select * from tarea where idtarea = $idst";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		{
			$tarea=CambiaAcentosaHTML($row["tarea"]);
			$vigencia=$row["vencimiento"];
			$desc=CambiaAcentosaHTML($row["notatarea"]);
		}

		//hacer la lista de los participantes
		$listausutarea="";
		

		$sql="select t.idusurio as idu, nombre, apaterno, amaterno from tareausuario t, usuario u where idtarea=$idst and t.idusurio = u.idusuario";
		$listausutarea = "<table border ='1'><tr><th>Usuario</th><th>Acci&oacute;n</th></tr>";
		$lista="";
		$operacion = mysql_query($sql);
		while($row = mysql_fetch_array($operacion))
		  {
		    $lista .= $row["idu"] . "*";

		   $listausutarea .= "<tr><td>" . CambiaAcentosaHTML( $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"] ) . "</td>";
		   $listausutarea .= "<td><input type=\"button\" value=\"-\" name=\"agregar\" onClick=\"cargarSeccion('$ruta/listausu.php','listadeusu','accion=1&id=" . $row["idu"] . "&lista=' + lista.value );\" ></td></tr>";

		  }
		$listausutarea .="</table>";

		
		
	}
	else
	{
		$accion="0";
	}	
	

	
	$selectusuarios="";
	$sql="select * from usuario where activo = 1";
	$selectusuarios = "<select name=\"idusuario\" ><option value=\"0\">Seleccione uno de la lista</option><option value=\"T\">Todos</option>";
	$operacion = mysql_query($sql);
	while($row = mysql_fetch_array($operacion))
	{
	
		$selectusuarios .= "<option value=\"" . $row["idusuario"] . "\"  >" . CambiaAcentosaHTML( $row["nombre"] . " " . $row["apaterno"] . " " . $row["amaterno"]  ) .  "</option>";

	}
	$selectusuarios .="</select>";
	
	
	
	if($mostrarformulario==1)
	{
	

echo <<<formularioA
<center>

<form>
<table border="0">
<tr>
	<td>Titulo</td>
	<td><input type="text" name="titulo" id="titulo" value="$tarea" ></td>
</tr>
<tr>
	<td>Fecha l&iacute;mite <br>(aaaa-mm-dd)</td>
	<td><input type="text" name="fechalimite" id="fechalimite" value="$vigencia" ></td>
</tr>
<tr>
	<td>Descripci&oacute;n</td>
	<td>
		<textarea name="descripcion" id="descripcion" rows="3" cols="40">$desc</textarea>
	</td>
</tr>

<tr>
	<td>Asignaciones</td>
	<td>
		$selectusuarios
		<input type="button" value="+" onClick="cargarSeccion('$ruta/listausu.php','listadeusu','accion=0&id=' + idusuario.value + '&lista=' + lista.value );">
		
		<div id="listadeusu" style="hieght:100px;overflow:auto">
		     <input type="hidden" name="lista" id="lista" value="$lista">
		     $listausutarea
		</div>
	</td>
</tr>


<tr>
	<td colspan="2" align="center">

		<input type="button" value="$boton1" onClick="document.getElementById('frmtareas').innerHTML=''">&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="$boton2" name="Agregar" onClick="if(titulo.value!='' && fechalimite.value!='' && descripcion.value!='' && lista.value!=''  ){ if(this.value=='Actualizar'&&priveditar.value==1){ cargarSeccion('$ruta/ftareast.php','frmtareas','accion=' + accion.value + '&id=' + ids.value + '&tarea=' + titulo.value  + '&vigencia=' + fechalimite.value + '&desct=' + descripcion.value + '&lista=' + lista.value + '&idst=$idst' )};if(this.value=='Agregar'&&privagregar.value==1){  cargarSeccion('$ruta/ftareast.php','frmtareas','accion=' + accion.value + '&id=' + ids.value + '&tarea=' + titulo.value +  '&vigencia=' + fechalimite.value + '&desct=' + descripcion.value + '&lista=' + lista.value + '&idst=$idst')}}else{alert('fallo');};" >
		<input type="hidden" name="ids" value="$id">
		<input type="hidden" name="accion" value="$accion">
		<input type="hidden" name="privagregar" value="$priv[1]">
		<input type="hidden" name="priveditar" value ="$priv[2]">

	</td>
</tr>
</table>
</form>

formularioA;
	}
	else
	{
		echo "";
	}
	
	

}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}



?>	