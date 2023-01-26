<?php
$imagenes[0]="";
$n=1;
//$path = "/srv/www/htdocs/imagenes/inicio";
$path = "/var/www/html/imagenes/inicio";
//$path = "C:\Program Files\Apache Software Foundation\Apache2.2\htdocs\bujalil\imagenes\inicio";
$dir=dir($path); 


while ($elemento = $dir->read())
{
	if ( ($elemento != '.') and ($elemento != '..'))
	{ 
		$imagenes[$n]=$elemento;
		$n++;
	}
}
$n--;
$src = rand(1,$n);

echo "<img src=\"imagenes/inicio/$imagenes[$src]\">";

?>