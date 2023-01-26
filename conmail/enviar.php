<?php
function form_mail($sPara, $sAsunto, $sTexto, $sDe)
{
	$bHayFicheros = 0;
	$sCabeceraTexto = "";
	$sAdjuntos = "";
	
	if ($sDe)$sCabeceras = "From:".$sDe."\n";
	else $sCabeceras = "";
	
	$sCabeceras .= "MIME-version: 1.0\n";
	foreach ($_POST as $sNombre => $sValor)
		$sTexto = $sTexto."\n".$sNombre." = ".$sValor;

	foreach ($_FILES as $vAdjunto)
	{
		if ($bHayFicheros == 0)
		{
			$bHayFicheros = 1;
			$sCabeceras .= "Content-type: multipart/mixed;";
			$sCabeceras .= "boundary=\"--_Separador-de-mensajes_--\"\n";
			$sCabeceraTexto = "----_Separador-de-mensajes_--\n";
			$sCabeceraTexto .= "Content-type: text/plain;charset=iso-8859-1\n";
			$sCabeceraTexto .= "Content-transfer-encoding: 7BIT\n";
			$sTexto = $sCabeceraTexto.$sTexto;
		}
		if ($vAdjunto["size"] > 0)
		{
			$sAdjuntos .= "\n\n----_Separador-de-mensajes_--\n";
			$sAdjuntos .= "Content-type: ".$vAdjunto["type"].";name=\"".$vAdjunto["name"]."\"\n";;
			$sAdjuntos .= "Content-Transfer-Encoding: BASE64\n";
			$sAdjuntos .= "Content-disposition: attachment;filename=\"".$vAdjunto["name"]."\"\n\n";
			$oFichero = fopen($vAdjunto["tmp_name"], 'r');
			$sContenido = fread($oFichero, filesize($vAdjunto["tmp_name"]));
			$sAdjuntos .= chunk_split(base64_encode($sContenido));
			fclose($oFichero);
		}
	}
	if ($bHayFicheros)
		$sTexto .= $sAdjuntos."\n\n----_Separador-de-mensajes_----\n";

	return(mail($sPara, $sAsunto, $sTexto, $sCabeceras));
}

//cambiar aqui el email
if (form_mail("mizocotroco@hotmail.com", $_POST[asunto],
"Los datos introducidos en el formulario son:\n\n", $_POST[email]))
{
	echo "Su formulario ha sido enviado con exito";
}
else
{
	echo "no se envio el correo";
}


$to = 'lsolis@sismac.com';
$from = "yo <lsolis@sismac.com>";
$subject = 'Asunto del mensaje';
//$subject = ‘=?UTF-8?B?’ . base64_encode($subject) . ‘?=’;
$semi_rand = md5(time());
$mime_boundary = "==TecniBoundary_x{$semi_rand}x";
$headers = "From: $from";
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n";
$message = "Información del cedente";
$message = "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"utf-8\"\r\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
//$fp = fopen($file, "rb");
//$data = fread($fp, filesize($file));
//fclose($fp);
//$data = chunk_split(base64_encode($data));
//$message .= "--{$mime_boundary}\r\n";
//$message .= "Content-Type: application/xlsx; name=\"" . basename($file). "\"\r\n" . "Content-Transfer-Encoding: base64\r\n" . $data . "\r\n" . "Content-Disposition: attachment\r\n";
echo $message .= "--{$mime_boundary}--";
mail($to, $subject, $message, $headers);



?>


