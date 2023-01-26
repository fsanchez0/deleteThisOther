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
    $duenio=$_POST['idduenio'];
 	$duenion = $_POST['anio'];
	$cuenta = $_POST['mes'];
	$periodo = $_POST['periodo'];
  // abcd
	
	//$duenio=13;
    //comprobamos si existe un directorio para subir el archivo
    //si no es así, lo creamos
    $sufijo = date("YdmHis");
    
    if($periodo<="2019-10-11")
    {
    	$ruta="contratos/$duenio";
    }
    else
    {
    	echo $ruta="contratos/" . $cuenta . "_" . $periodo;
    }
    if(!is_dir($ruta)) {
    	mkdir($ruta,0777);

        /*mkdir("12/",0777);
        mkdir("12/2017/",0777);
        mkdir("12/2017/01/",0777);
        */
    }
     
    //comprobamos si el archivo ha subido
    if ($file && move_uploaded_file($_FILES['archivo']['tmp_name'],$ruta."/"."Transferencia_" . $sufijo . ".pdf"))
    {
       sleep(3);//retrasamos la petición 3 segundos
       echo $file;//devolvemos el nombre del archivo para pintar la imagen
    }
    else
    {
       echo $file; 
        
    }
    
	//$correos= array("nc@price-center.mx");
   
  




}else{
   throw new Exception("Error Processing Request", 1);   
}
//enviamail($duenio,$file,$duenion,$periodo,$cuenta);

?>