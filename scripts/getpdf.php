<?php$f=$_GET['f'];
//echo $f;
//Comprobar el fichero (Áno lo pase por alto!)
if(substr($f,0,3)!='tmp' or strpos($f,'/') or strpos($f,'\\'))
die('Nombre $f incorrecto de fichero');
if(!file_exists($f))die('El fichero no existe');
//Gestionar peticiones especiales de IE si es necesario
if($HTTP_ENV_VARS['USER_AGENT']=='contype')
{
    Header('Content-Type: application/pdf');
    exit;
}
//Devolver el PDFHeader('Content-Type: application/pdf');
Header('Content-Length: '.filesize($f));
readfile($f);
//Eliminar el ficherounlink($f);
exit;
?>