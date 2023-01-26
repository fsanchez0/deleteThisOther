<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

include 'general/sessionclase.php';
include_once('general/conexion.php');



/*
//date_default_timezone_set('America/Mexico_City');
$tiempo="";
if (date_default_timezone_get()) {
    $teimpo .= 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
}

if (ini_get('date.timezone')) {
    $teimpo .=  'date.timezone: ' . ini_get('date.timezone') . '<br />';
}

$teimpo .=  date("Y-m-d H:i:s");
*/

//mysql_select_db('bujalil',$enlace) ;

//$misesion = new sessiones;
//if($misesion->verifica_sesion()=="yes")
//{
echo <<<formulario
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Reportes del Test</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
$teimpo
<form action="prueba.php" method="post">
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
			echo $result = mysql_query ($consulta );
			

		}
		else
		{
			echo $result = mysql_query ($consulta );
			
			if (!$result)
			{
				echo "<br> La consulta:<br><br><strong>" . $consulta . "</strong><br><br> Es incorrecta, corregir";
			}
			else
			{

				$no_campos = mysql_num_fields($result);
				
				echo "<br><br>La consulta realizada es:<br><br>" . $consulta . "<br><br>";
    
				
				
				
				echo "<table border=1><tr>";
				for ($i = 0; $i< $no_campos; $i++)
				{
					echo "<th>" . mysql_field_name($result, $i) . "</th>";
				}
				echo "</tr>\n";

				while ($row = mysql_fetch_array($result))
				{
					echo "<tr>";
					for ($i = 0; $i< $no_campos; $i++)
					{
						echo "<td>" .  $row[$i] . "</td>";
					}
					echo "</tr>\n";
		  		}
				echo "</table>";
				
			}
		}
	}
/*	
}
else
{

	echo "A&uacute;n no se ha firmado con el servidor";

}
*/
?>

</body>
</html>
