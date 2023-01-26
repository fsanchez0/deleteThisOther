<?php
include 'general/sessionclase.php';
include_once('general/conexion.php');
//$enlace = mysql_connect('localhost', 'root', '');


//mysql_select_db('bujalil',$enlace) ;

$misesion = new sessiones;
if($misesion->verifica_sesion()=="yes")
{
echo <<<formulario
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reportes del Test</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>

<form action="pruebaInsert.php" method="post">
Consulta <br>

<textarea rows="4" cols="50" name="SQL"></textarea><br>
<input type="submit" value="Realizar Consulta">
</form>
formulario;

	if (@$_POST["SQL"] != "")
	{

		$consulta  = stripslashes($_POST["SQL"]);
		//echo stripslashes($consulta);

		if (strtoupper (substr(trim($consulta),0,6))!="SELECT")
		{
			echo "<br><br><strong>S&oacute;lo se pueden hacer consultas, corrija su consulta</strong><br>$consulta";
			echo $result = @mysql_query ($consulta );

		}
		else
		{
			$result = @mysql_query ($consulta );
			if (!$result)
			{
				echo "<br> La consulta:<br><br><strong>" . $consulta . "</strong><br><br> Es incorrecta, corregir";
			}
			else
			{

				$no_campos = mysql_num_fields($result);
				echo "<br><br>La consulta realizada es:<br><br>" . $consulta . "<br><br>";
				$consulta= "INSERT INTO $tabla (";
				for ($i = 0; $i< $no_campos; $i++)
				{
					$consulta.= "" . mysql_field_name($result, $i) . ",";
				}
				$consulta.= ") VALUES \n";

				while ($row = mysql_fetch_array($result))
				{
					$consulta.= "('";
					for ($i = 0; $i< $no_campos; $i++)
					{
						$consulta.= "" .  $row[$i] . "','";
					}
					$consulta.= "), ";
		  		}
				$consulta.= ";";
			}
		}
	}
$consulta = str_replace(",)", ")", $consulta);
$consulta = str_replace(",')", ")", $consulta);
$consulta = str_replace("), ;", ");", $consulta);
	echo $consulta;
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
?>

</body>
</html>
