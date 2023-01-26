<?php
//include("enviocorreo.php");
$hoy = date('Y-m-d');	
	/*En php recibimos las variables de nuestro formulario 
	Como lo haríamos normalmente, en este caso solo es una. */
//comprobamos que sea una petición ajax
	//define('BASE_DIR','/home/wwwarchivos/cfdi');
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
{
 
    //obtenemos el archivo a subir
    $file = $_FILES['archivo']['name'];
    $contrato1=$_POST["idcontrato"];
 	$servicio = $_POST["servicio"];
	$contrato=trim($contrato1);
    //comprobamos si existe un directorio para subir el archivo
    //si no es así, lo creamos
    $name="$servicio.pdf";
    $ruta="contratos/$contrato";
    if(!is_dir($ruta)) {
    	mkdir($ruta,0777);
    }
     
    //comprobamos si el archivo ha subido
    if ($file && move_uploaded_file($_FILES['archivo']['tmp_name'],$ruta."/".$name))
    {
       sleep(3);//retrasamos la petición 3 segundos
       echo $file;//devolvemos el nombre del archivo para pintar la imagen
    }
    
	//$correos= array("nc@price-center.mx");
   
  




}else{
   throw new Exception("Error Processing Request", 1);   
}

//enviamail($duenio,$file,$duenion,$periodo,$cuenta);

?>