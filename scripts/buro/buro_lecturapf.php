<?php
//lectura de cinta para bases del envío al buró
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include ("buroclasspf.php");
header('Content-Type: text/html; charset=iso-8859-1');

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

$buro =  New buroclasspf;
/*
echo $buro->muetradatos($buro->lcavecera);

echo $buro->muetradatos($buro->lnombre);

echo $buro->muetradatos($buro->ldireccion);

echo $buro->muetradatos($buro->lempleo);
*/
//echo $buro->muetradatos($buro->lcuentas);
/*
echo $buro->muetradatos($buro->lcontrol);

echo $buro->muetradatos($buro->linstitucion);
echo $buro->muetradatos($buro->lestados);
echo $buro->muetradatos($buro->lcartera);
echo $buro->muetradatos($buro->lcreditos);
echo $buro->muetradatos($buro->lmoneda);
echo $buro->muetradatos($buro->lpais);
echo $buro->muetradatos($buro->lbanxico);
*/




//echo  gcem($lem) . "<br>";
/*
//Comercial
echo $buro->gchd($buro->lhd);// . "<br><br>";
echo $buro->gcem($buro->lem);// . "<br><br>";
echo $buro->gcts($buro->lts);// . "<br><br>";
*/

echo "<textarea rows=\"30\" cols=\"100\">";
echo $buro->gcabecera($buro->lcavecera) ;
echo $buro->gnombre($buro->lnombre) ;
echo $buro->gcontrol($buro->lcontrol);// . "\n\n";
echo "</textarea>";
//echo "<code>";
//echo $buro->gcabecera($buro->lcavecera) ;
//echo $buro->gnombre($buro->lnombre) ;
//echo $buro->gcontrol($buro->lcontrol);// . "\n\n";
//echo "</code>";
/*
echo $fecha = date('Y') . "-" . "01-1";
$dias= 	1; // los d’as a restar
echo date("Y-m-d", strtotime("$fecha -$dias day")); 
*/
}

?>