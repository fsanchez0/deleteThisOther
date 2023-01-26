<?php
//lectura de cinta para bases del envío al buró
include '../general/sessionclase.php';
include_once('../general/conexion.php');
include '../general/funcionesformato.php';
include ("buroclass.php");


$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{

$buro =  New buroclass;
/*
echo $buro->muetradatos($buro->lhd);

echo $buro->muetradatos($buro->lem);
echo $buro->muetradatos($buro->lac);
echo $buro->muetradatos($buro->lcr);
echo $buro->muetradatos($buro->lde);
echo $buro->muetradatos($buro->lav);
echo $buro->muetradatos($buro->lts);
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
echo $buro->gfhd($buro->lhd);// . "<br><br>";
echo $buro->gfem($buro->lem);// . "<br><br>";
echo $buro->gfts($buro->lts);// . "<br><br>";
echo "</textarea>";

}

?>