<?php
//Clase de las sesiones para el sitio


class sessiones
{
	var $usuario;
	var $nombre;
	var $autentificado;
	var $menu;
	var $privilegios;



	//***************************************
	//Constructor de objeto
	//***************************************
	function sessiones ()
	{
		$this->abresesion('bujalil');
		if($this->verifica_sesion()=='yes')
		{
			$this->usuario=$_SESSION['usuario'];
			$this->nombre=$_SESSION['nombre'];
			$this->autentificado=$_SESSION['autentificado'];
			$this->menu=$_SESSION['menu'];
			$this->privilegios=$_SESSION['privilegios'];


		}
		else
		{
			$this->usuario="";
			$this->nombre="";
			$this->autentificado="no";
			$this->menu="";
			$this->privilegios="";

		}

	}



	//Inicia el proceso de la seción
	function abresesion($nombres)
	{
		//session_name("pedidos"); //
		session_name($nombres);
		session_start();

	}


	//Inicia las variables de seción
	function datossesion($idusuario, $nombre, $menu,$privilegios)
	{

		$_SESSION['usuario']=$idusuario;	//Id del usuario
		$_SESSION['nombre']=$nombre;	//nombre del usuario
		$_SESSION['autentificado'] = "yes";	//para saber si esta autentificado
		$_SESSION['menu']=$menu; 		//separados por pipes los menus que serán vistos
		$_SESSION['privilegios']=$privilegios;	//separados por pipes los privilegios por submenu


		$this->usuario=$_SESSION['usuario'];
		$this->nombre=$_SESSION['nombre'];
		$this->autentificado=$_SESSION['autentificado'];
		$this->menu=$_SESSION['menu'];
		$this->privilegios=$_SESSION['privilegios'];

		//session_cache_limiter('nocache,private');
		session_cache_limiter('nocache,private_no_expire');

	}


	//Verifica si ya se ha iniciado la sesión
	function verifica_sesion()
	{
		if (@$_SESSION['autentificado'] != 'yes') //posiblemente es necesaria la @ en la sesion
		{

			/*echo '
			<SCRIPT language="JavaScript">
			<!--
			top.location="index.html";
			//-->
			</SCRIPT> ';
			*/

			return 'no';
			exit;
		}
		return $_SESSION['autentificado'];
	}


	//Verifica el usuario en la base de datos y lo firma si existe
	function firmarusuario($usuario,$pwd)
	{


		if($usuario && $pwd)
		{

			//hacer consulta al abase de datos para tomar los valores del usuario

			$sql="select * from usuario where usuario='$usuario' and pwd='$pwd' and activo = true";

			$verc = mysql_query($sql);
			//Si hay coincidencia inicia el proceso
			//echo mysql_num_rows($verc);
			//if ($verc)
			if(mysql_num_rows($verc)>0)
			{
				while ($row = mysql_fetch_array($verc))
				{
					//Toma los datos generales del usuario

					$usuario=$row["idusuario"];
					$nombre=$row["nombre"];
				}


				//Genera la consulta para obtener el menu y los privilegios de las ventanas
				//$sql="select modulo.nombre as mnombre, submodulo.nombre as snombre, submodulo.idsubmodulo as idsubm, ver, agregar,editar,borrar, ruta, archivo from privilegio, submodulo, modulo where privilegio.idsubmodulo=submodulo.idsubmodulo and submodulo.idmodulo=modulo.idmodulo and idusuario=$usuario order by modulo.orden, submodulo.idsubmodulo";
				$sql="select modulo.nombre as mnombre, submodulo.nombre as snombre, submodulo.idsubmodulo as idsubm, ver, agregar,editar,borrar, ruta, archivo, importancia.numero as ncolor, importancia.nombre as cnombrec from privilegio, submodulo, modulo, importancia where submodulo.idimportancia=importancia.idimportancia and  privilegio.idsubmodulo=submodulo.idsubmodulo and submodulo.idmodulo=modulo.idmodulo and idusuario=$usuario order by modulo.orden, importancia.numero, submodulo.idsubmodulo";
				$verc = mysql_query($sql);

				//inicia variables para generar el menu y privilegios
				$pasom="";	//Variable para verificar si cambia de menu (modulo)
				$cuenta=0;	//para contar los menus y preparar a que se oculten
				$submen="<table width=\"100%\" border=\"0\">\n";
				$menu="<table width=\"100%\">\n";
				$privilegios=""; //Variable para ir almacenando los privilegios
				$indicesbotones=0;
				$columnass=0;
				$rengloni="<tr>\n";
				$renglonf="</tr>\n";
				while ($row = mysql_fetch_array($verc))
				{
					//Verifica si es la primera vez que verifica el cambio de menu (modulo)
					if($pasom=="")
					{
						$cuenta++;
						$pasom=$row["mnombre"];
						$menu .="<tr>\n";
						$menu .="<td  class=\"MenuP\" onClick=\"a=document.getElementById('menu$cuenta').value;document.getElementById('contenido').innerHTML=a;\" OnMouseOver=\"this.className='SobreCampoM'\" OnMouseOut=\"this.className='MenuP'\">" . $row["mnombre"] . "</td>\n";
						$menu .="</tr>\n";
						$columnass=0;

					}

					//si cambia el modulo, prepara el siguiente bloque y temina con la lista del menu anterior
					if($pasom!=$row["mnombre"])
					{

						$submen .="</table>\n";
						//$menu .= "<tr><td class=\"MenuP\"><div id=\"menu$cuenta\" style=\"visibility: hidden;height: 0px; position:absolute; left:0px; top:0px;\">\n" . $submen . "\n</div></td></tr>\n";
						$menu .= "<tr><td class=\"MenuP\"><div  style=\"visibility: hidden;height: 0px; position:absolute; left:0px; top:0px;\">\n<textarea id=\"menu$cuenta\"><h2 align='center'>$pasom</h2><br>" . $submen . "</textarea>\n</div></td></tr>\n";
						$submen="<table width=\"100%\" border=\"0\">\n";
						$privilegios .=$row["idsubm"] . "&" . $row["ver"] . "*" . $row["agregar"] . "*" . $row["editar"] . "*" .$row["borrar"] . "|";


						$cuenta++;
						$pasom=$row["mnombre"];
						$menu .="<tr>\n";
						$menu .="<td class=\"MenuP\" onClick=\"a=document.getElementById('menu$cuenta').value;document.getElementById('contenido').innerHTML=a;\" OnMouseOver=\"this.className='SobreCampoM'\" OnMouseOut=\"this.className='MenuP'\">" . $row["mnombre"] . "</td>\n";
						$menu .="</tr>\n";
						$pcambio=1;
						$columnass=0;
					}


					//Acumula las opcioines del menu (submodulo)
					if($columnass==0 )
					{
						$rengloni="<tr>\n";
						$renglonf="";
						$columnass=1;


					}
					else if ( $columnass == 4)
					{
						$renglonf="</tr><tr>\n";
						$columnass=0;

					}
					else
					{
						$rengloni="";
						$renglonf="";

					}



					$submen .= $rengloni;
					$submen .= "<td  >" .  $this->boton($row["snombre"]  , $indicesbotones , $row["ncolor"], $row["cnombrec"] ,"onClick = \"cargarSeccion('". $row["ruta"] . "/" . $row["archivo"] . "', 'contenido', '');\"")  . "</td>\n";
					$submen .= $renglonf;
					$privilegios .=$row["idsubm"] . "&" . $row["ver"] . "*" . $row["agregar"] . "*" . $row["editar"] . "*" . $row["borrar"] . "|";
					$indicesbotones++;
					$columnass ++;


				}

				//Cuando termina, verifica si hay elementos del ultimo modulo pendiente si hay termina el modulo
				if($submen!="<table width=\"100%\">\n")
				{
					$submen .="</table>\n";
					$menu .= "<tr><td class=\"MenuP\"><div  style=\"visibility: hidden;height: 0px; position:absolute; left:0px; top:0px;\">\n<textarea id=\"menu$cuenta\"><h2 align='center'>$pasom</h2>" . $submen . "</textarea>\n</div></td></tr>\n";
				}

				$menu .="</table>\n";

				//Inicio los datos de sesión para los usuarios.
				$this->datossesion($usuario,$nombre, $menu,$privilegios);

				return true;
			}
			else
			{
				//Error, no se encontro usuario ni contraseña en la base
				return false;

			}





		}
		else
		{


			return false; //falta el usuario y la contraseña
		}


	}


	//Cierra la sesion y vorra las variables como las propiedades dle objeto.
	function cerrarsesion()
	{
		session_unset();
		session_destroy();
		$this->usuario=@$_SESSION['usuario'];//posible @ en session
		$this->nombre=@$_SESSION['nombre'];//posible @ en session
		$this->autentificado='no';
		$this->menu=@$_SESSION['menu'];//posible @ en session
		$this->privilegios=@$_SESSION['privilegios'];//posible @ en session
	}

	//debuelve los privilegios del modulo solicitado
	function privilegios_secion($modulo)
	{
		//echo $this->privilegios;
		$modulos = split( "\|", $this->privilegios);
		foreach($modulos as $mod)
		{

			$priv =split("&", $mod);
			//echo $priv[0] . " == " . $modulo . "<br>";
			if($priv[0]==$modulo)
			{
				//Es el modulo en cuestion
				//echo "<br>el resultado debe de ser: " .$priv[1];
				return $priv[1];
				break;
			}

		}
		return false;

	}

	//Genera botones con funciones
	function boton($etiqueta, $indice, $color,$ncolorc, $ellick)
	{

		//se geeran botones de colores los cuales pueden ser muchos del mismo color
		//es por eso que es necesario coloar un indice para diferenciarlos
		//y hacer más facil el cambio de imagen por la luz

		// patronn = color_indice_con/sincolor_PosisioDeLaImagen
		// ej: $patron = "rojo_1_a_AI"  es boto color rojo de indice uno sin luz (b=con luz) y posicion arriba izquierda

		$b="";
		$patron="";
		$idb="";
		$patron = $ncolorc;
		$idb=$ncolorc . $indice;
		/*
		switch($color)
		{
		case 2: //color 1 (amarillo)

			$idb="azulb" . $indice;
			$patron="azulb";
			break;

		case 1: //color 2 (azul)
			$idb="azul" . $indice;
			$patron="azul";
			break;

		case 3: //color 3 (verde)
			$idb="verde" . $indice;
			$patron="verde";
			break;


		};

		*/
		$b="<table  border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";
	    $b .="<tr>";
	    $b .="  <td width=\"16\"><img src=\"imagenes/" . $patron . "_a_AI.png\" width=\"20\" id=\"" .$idb . "_AI\" height=\"22\" onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$idb', 'normal','$patron');\" $ellick></td>";
	    $b .="  <td height=\"22\" background=\"imagenes/" . $patron . "_a_TAC.png\" id=\"" . $idb . "_TAC\"  onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$idb', 'normal','$patron');\" $ellick><img src=\"imagenes/" . $patron . "_a_AC.png\" width=\"10\" height=\"22\" id=\"" . $idb . "_AC\"  onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$idb', 'normal','$patron');\" ></td>";
	    $b .="  <td width=\"20\"><img src=\"imagenes/" . $patron . "_a_AD.png\" width=\"20\" height=\"22\" id=\"" . $idb . "_AD\"  onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$idb', 'normal','$patron');\"$ellick></td>";
	    $b .="</tr>";
	    $b .="<tr>";
	    $b .="  <td width=\"20\" background=\"imagenes/" . $patron . "_a_MI.png\" id=\""  . $idb . "_TMI\"  onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$idb', 'normal','$patron');\" $ellick><img src=\"imagenes/" . $patron . "_a_MI.png\" width=\"20\" height=\"10\" id=\"" .$idb . "_MI\"  onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$patron', 'normal','$patron');\" ></td>";
	    $b .="  <td align=\"center\" class=\"" . $patron . "_boton\" valign=\"middle\" id=\"" . $idb . "_MC\"    onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$idb', 'normal','$patron');\" $ellick background=\"imagenes/" . $patron . "_MC.png\">&nbsp;$etiqueta&nbsp; </td>";
	    $b .="  <td width=\"20\" background=\"imagenes/" . $patron . "_a_MD.png\" id=\"" . $idb . "_TMD\"  onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$idb', 'normal','$patron');\" $ellick><img src=\"imagenes/" . $patron . "_a_MD.png\" width=\"20\" height=\"10\" id=\"" . $idb . "_MD\"  onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$idb', 'normal','$patron');\" ></td>";
	    $b .="</tr>";
	    $b .="<tr>";
	    $b .="  <td width=\"20\" height=\"22\"><img src=\"imagenes/" . $patron . "_a_PI.png\" width=\"20\" height=\"22\" id=\"" . $idb . "_PI\"  onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$idb', 'normal','$patron');\" $ellick></td>";
	    $b .="  <td height=\"22\" background=\"imagenes/" . $patron . "_a_TPC.png\" id=\"" . $idb . "_TPC\" align=\"right\"  onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$idb', 'normal','$patron');\" $ellick><img src=\"imagenes/" . $patron . "_a_PC.png\" width=\"10\" height=\"22\" id=\"" . $idb . "_PC\"  onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$idb', 'normal','$patron');\" ></td>";
	    $b .="  <td width=\"20\" height=\"22\"><img src=\"imagenes/" . $patron . "_a_PD.png\" width=\"20\" height=\"22\" id=\"" . $idb . "_PD\" onMouseOver=\"cambiaboton('$idb', 'sobre','$patron');\" onMouseOut=\"cambiaboton('$idb', 'normal','$patron');\" $ellick></td>";
	    $b .="</tr>";
		$b .="</table>";



		return $b;

	}




}

?>