<?php
//Clase para generar Cinta de envio al burÛ de credito
//cada registro de la listaburo.php tiene 11 campos
//1: numero de registro segun burÛ
//2: nombre de campo
//3: exigencia para institucion fianciero
//4: exigencia para empresa cretidicio
//5: tipo de dato
//6: longitud del campo
//7: descripciÛn y regla del campo
//8: tabla del buro que servir· de catalogo de ese campo
//9: tabla de la base o instrucciÛn del valor del campo (ej. i,variable o instrucciÛn)
//10: campo de al base de datos
//11: valor por defecto

//para los catalogos solo tienen 2

// requiere de que ya exista una conexio na la base de datos de bujalil
class buroclasspf
{

	var $cavecera="^Etiqueta del Segmento^Requerido  ^f^Texto^4^Debe contener las letras INTF.^^^^INTF|^Versión^Requerido  ^f^Numérico^2^Debe contener el número 11.^^^^11|^Clave del Usuario^Requerido^f^Texto^10^Debe contener la clave de 10 posiciones (member code) asignada al Usuario por Buró de Crédito. <br>* Las primeras dos posiciones son alfabéticas y corresponden a la clave de tipo de negocio (KOB) de la Entidad Financiera o la Empresa Comercial usuaria del servicio de Buró de Crédito. La abreviatura del KOB refiere al sector de negocio de la Institución usuaria. Ver tabla de Tipo de Negocio al final de esta sección.<br>* La primera serie de cuatro números es el prefijo e identifica al Usuario o el Institución. La segunda serie es el sufijo y puede identificar: producto y sucursales o área de la institución.^^^^SS10340001|^Nombre del Usuario^Requerido  ^f^Texto^16^Debe contener el nombre del Usuario que reporta la información. Los bytes no utilizados en este campo deben llenarse con espacios. El nombre de la Institución es acordado con Buró de Crédito, por lo que debe consultar al Área de Adquisición de Base de Datos (5449 4923)..^^^^PRUEBA|^Número de Ciclo^Requerido  ^f^Texto^2^El Usuario puede ingresar letras o números para identificar la información reportada. De no utilizarse, la etiqueta se llena con espacios.^^^^|^Fecha de Reporte^Requerido  ^f^F1^8^Debe contener la fecha del último día del periodo reportado para las entregas Mensuales. Para una actualización parcial, la fecha de reporte será la de modificación del registro. Esta fecha también se utilizará en el segmento de cuenta (TL), sólo cuando el dato no fuese reportado por el Usuario. El formato es DDMMAAAA:<br>* DD: número entre 01 y 31 (día del fin de periodo reportado);<br>* MM: número entre 01-12 (mes del periodo reportado); y,<br>* AAAA: año (cuatro dígitos).<br>La fecha no debe ser igual o posterior a la fecha del computador de BC.^^i,date('Y-m-d');^^|^Uso Futuro^Requerido  ^f^Numérico^10^Se debe llenar con ceros^^^^|^Información Adicional del Usuario^Requerido  ^f^Texto ^98^El Usuario lo puede utilizar para incluir información adicional. Las posiciones que no se utilicen se deben llenar con espacios.^^^^|";
	var $nombre="PN^Apellido Paterno^Requerido  ^v^Texto ^26^Los campos de datos principales del Cliente (apellidos,nombres, RFC o fecha de nacimiento), una vez reportados no deben modificarse en su composición u orden en subsecuentes actualizaciones, pues se daría pauta a la creación de fragmentación de expedientes. (Fragmentación de expediente: Refiere a que en la Base de Datos existe más de un expediente correspondiente a la misma persona, pero por abreviaturas o datos incompletos están separados en y la carencia o parcialidad de datos impide su integración en uno solo.) <br>En caso de requerir modificaciones o complementación a estos datos, es necesario comunicarse con el Área de Adquisición y Calidad de BD previo a la carga de la cinta.^^^apaternoi^|00^Apellido Materno^Requerido  ^v^Texto  ^26^En caso de ser hijo natural, el único apellido utilizado se ingresa en el campo del apellido Paterno.^^^amaternoi^|01^Apellido Adicional^Requerido^v^Texto  ^26^Se utiliza para reportar el apellido de casada.^^^^|02^Primer Nombre^Requerido^v^Texto^26^Cuando el Cliente tiene más de un nombre, se utiliza la etiqueta 03.^^^nombre1i^|03^Segundo Nombre^Requerido^v^Texto  ^26^Cuando el Cliente tiene hasta un tercer nombre, en esta etiqueta se ingresan los datos completos sin abreviaturas.^^^nombre2i^|04^Fecha de Nacimiento^Requerido^f^F1^8^^^^^|05^Número de RFC^Requerido^v^Texto  ^13^Para cuentas con fecha de apertura posterior a enero de 1998, reportar RFC es requerido.<br>La homoclave, podrá ser reportada en las últimas tres posiciones (alfanuméricas).^^^rfc^|06^Prefijo Personal o Profesional^Requerido  ^v^Texto   ^4^Se refiere a valores tales como: Sr, Sra, Srta, Lic, Dr, etcétera^^^^|07^Sufijo^Requerido^v^Texto   ^4^Algunos valores posibles son:<br>JR = Junior<br>II = Segundo<br>III = Tercero^^^^|8^Nacionalidad^Requerido^f^Texto^2^Ver tabla con claves permitidas en el Apéndice B. Es un campos de longitud fija.^^^^MX|9^Residencia^Requerido^f^Numérico^1^Los valores permitidos son:<br>1 = Propietario.<br>2 = Renta.<br>3 = Pensión / Vive con familiares.^^^^2|10^Número de Licencia de Conducir^Requerido^v^Texto ^20^^^^^|11^Estado Civil^Requerido  ^f^Texto  ^1^Los valores permitidos son:<br>D = Divorciado<br>F = Unión Libre<br>M = Casado<br>S = Soltero<br>W = Viudo^^^^|12^Sexo^Requerido^f^Texto^1^Los valores permitidos son:<br>F = Femenino<br>M = Masculino^^^^|13^Número de Cédula Profesional^Requerido^v^Texto^20^^^^^|14^Número de Registro Electoral (IFE)^Requerido^v^Texto^20^^^^^|15^Clave para impuestos en otro País^Requerido^v^Texto^20^Este campo se utiliza en conjunto con el siguiente (Clave de otro País). De proporcionarse sólo uno de ellos, ambos serán ignorados^^^^|16^Clave de otro País^Requerido^f^Texto^2^Ver tabla con claves permitidas en el Apéndice B.^^^^|17^Número de Dependientes^Requerido (ver descripción)^f^Numérico^2^Observar que es de longitud fija^^^^|18^Edades de los Dependientes^Requerido^v^Numérico^30^Es posible reportar las edades de hasta 15 dependientes, en dos posiciones cada una.<br>Si se introdujo 02 en el campo de Número de Dependientes y se ingresa el valor 0413 en este campo, las edades serían de 4 y 13.<br>El valor para niños menores de un año es 01.^^^^|20^Fecha de Defunción^Requerido^f^Texto^8^Si se conoce, ingresar la fecha en que el Cliente falleció.^^^^|21^Indicador de Defunción^Requerido^f^Texto^1^Si la fecha de defunción no fue reportada, pero este campo contiene una “Y”, el sistema llenará el campo de fecha de defunción con la fecha de reporte.^^^^|";
	var $direccion="PA^Dirección (Línea 1)^Requerido^v^Texto^40^Se refiere al nombre de la calle y número exterior e interior cuando estos existan.^^^direcciona^|0^Dirección (Línea 2)^Requerido  ^v^Texto^40^Cuando el dato referente a calle y número excede 40 posiciones en la etiqueta “Dirección (Línea 1)”, se utiliza ese campo para completar la información.^^^rfc^|1^Colonia o Población^Requerido^v^Texto^40^* En caso de no reportar Delegación, el campo de Ciudad se hace requerido.<br>* En caso de no reportar Ciudad, el campo de Delegación se hace requerido.<br>Consultar criterios de validación^^^coloniaa^|2^Delegación o Municipio^Requerido^v^Texto^40^^^^delegaciona^|3^Ciudad^Requerido^v^Texto^40^^^^^|4^Estado^Requerido^v^Texto^4^Ver lista de abreviaturas autorizadas al final de esta sección.^^^estadoa^|5^05 Código Postal^Requerido^v^Numérico^5^Debe ser exactamente de 5 posiciones numéricas^^^cpa^|6^Fecha de Residencia^Requerido^f^F1^8^^^^^|7^Número de Teléfono en esta Dirección^Requerido^v^Numérico^11^Sólo deben incluirse los 10 dígitos que comprende el número telefónico iniciando por el número de identificación regional, omitiendo el 01. Ejemplo: Para un número telefónico de la CD. de México se reporta: 5554494949.^^^^|8^Ext. Telefónica^Requerido^v^Numérico^8^^^^^|9^Número de Fax en esta Dirección^Requerido^v^Numérico^11^Ver explicación en etiqueta 07 de este segmento.^^^^|10^Tipo de Domicilio^Requerido^f^Texto^1^Los valores permitidos son:<br>B = Negocio<br>C = Domicilio del Otorgante<br>H = Casa<br>P = Apartado Postal^^^^|11^Indicador Especial de Domicilio^Requerido^f^Texto^1^Los valores permitidos son:<br>M = Militar<br>R = Rural<br>K = Domicilio conocido^^^^|";
	var $empleo="PE^ Identificador de Segmento^Requerido^v^Texto^40^Si no está disponible la razón social, pero existe información complementaria relacionada con el empleo se ingresa la leyenda NO PROPORCIONADO.^^^^NO PROPORCIONADO|0^Dirección (Línea 1)^Requerido^v^Texto^40^Cuando se reporta domicilio se valida todo el segmento al igual que el Segmento de Dirección (PA).^^^^|1^Dirección (Línea 2)^Requerido^v^Texto^40^Cuando el dato referente a calle y número excede 40 posiciones en la etiqueta “Dirección (Línea 1)”, se utiliza ese campo para completar.^^^^|2^Colonia o Población^Requerido^v^Texto^40^^^^^|3^Delegación o Municipio^Requerido^v^Texto^40^* En caso de no reportar Delegación, el campo de Ciudad se hace requerido.<br>* En caso de no reportar Ciudad, el campo de Delegación<br>se hace requerido.<br>Consultar criterios de validación al final de la sección anterior (de Dirección – PA).^^^^|4^Ciudad^Requerido^v^Texto^40^^^^^|5^Estado^Requerido^v^Texto^4^Abreviaturas autorizadas aparecen al final de la sección de Dirección – PA.^^^^|6^Código Postal^Requerido^f^Numérico^5^Debe ser exactamente de 5 posiciones numéricas.^^^^|7^Número de Teléfono en esta Dirección^Requerido^v^Numérico^11^Ver explicación en etiqueta 07 del segmento de dirección particular (PA).^^^^|8^Extensión Telefónica^Requerido^v^Numérico^8^^^^^|9^Número de Fax en esta Dirección^Requerido^v^Numérico^11^Ver explicación en etiqueta 07 del segmento de dirección particular (PA).^^^^|10^Cargo^Requerido^v^Texto^30^^^^^|11^Fecha de Contratación^Requerido^f^F1^8^^^^^|12^Clave de la Moneda del Salario^Requerido^f^Texto^2^Capturar en este campo la clave del país que corresponde al tip de Moneda del Salario del acreditado.^^^^|13^Salario^Requerido^v^Numérico^9^El valor numérico representa el salario del consumidor basado en el período especificado en el campo siguiente: Base Salarial.^^^^|14^Base Salarial de Tiempo^Requerido^f^Texto^10^Al reportar Clave de la Moneda del Salario, este campo es requerido. Valores posibles:<br>B = Bimestral<br>D = Diario<br>H = Por Hora<br>K = Catorcenal<br>M = Mensual<br>S = Quincenal<br>W = Semanal<br>Y = annual^^^^|15^Número de Empleado^Requerido^v^Texto^15^Se refiere al número de nómina.^^^^|16^Fecha de Último Día en Empleo^Requerido^f^Numérico^8^^^^^|17^Fecha de Verificación de Empleo^Requerido^f^Numérico^8^Fecha cuando el Usuario verificó el dato empleo del Cliente.^^^^|";
	var $cuentas="TL^Etiqueta del Segmento^Requerido^f^Texto^2^Debe contener las letras TL solamente.^^^^TL|1^Clave del Otorgante (Member Code)^Requerido^f^Texto^10^Contiene la clave del Usuario que reporta la cuenta.^^^^SS10340001|2^Nombre del Usuario^Requerido^v^Texto^16^El nombre aparecerá en la sección de detalle de créditos en el Reporte de Crédito:<br>* Sólo cuando sea un Reporte Especial<br>* Se trate de una cuenta propia de la Institución.^^^^PRUEBA|4^Número de Cuenta Actual^Requerido^v^Texto^25^Se refiere al número de crédito, asignado por el Usuario.^^^id^|5^Indicador de Tipo de Responsabilidad de la Cuenta^Requerido^f^Texto^1^Se refiere al número de crédito, asignado por el Usuario.<br>Para las etiquetas 04, 05, 06, 07 y 08 un vez reportado un valor, éste no debe modificarse en subsecuentes actualizaciones cuando se trate del mismo crédito.<br>Modificar alguno de los datos mencionados, aun cuando se trate del mismo crédito, generará un nuevo registro en el expediente del Cliente (fragmentación de la historia crediticia).<br>Antes de efectuar modificaciones se recomienda consultar al Analista del área de Adquisición de Base de Datos.^^^^|6^Tipo de Cuenta^Requerido^f^Texto^1^^^^^I |7^Tipo de Contrato (producto crediticio)^Requerido^f^Texto^2^Si se ingresa un tipo de contrato no válido, se rechazará la totalidad del registro.<br>Se deben seguir las mismas instrucciones indicadas para las etiquetas 04, 05 y 06.^^^^LS|8^Clave de Unidad Monetaria^Requerido^f^Texto^2^Cuando ocurre un cambio en la clave de unidad monetaria y no se reportan nuevos valores en los importes, el sistema sustituye los importes antiguos con ceros.<br>Se deben seguir las mismas instrucciones indicadas para las etiquetas 04, 05, 06 y 07.^^^^MX|9^Importe del Avalúo^Requerido^v^Numérico^9^Si el crédito es de Pagos Fijos (I) o Hipoteca (M), el dato se refiere al valor total del activo para propósitos de valuación o recuperación.^^^^|10^Número de Pagos^Requerido^v^Numérico^4^Número total de pagos (plazos) estipulados en la apertura del crédito.^^^^|11^Frecuencia de Pagos^Requerido^f^Texto^1^Frecuencia con que el Cliente debe realizar sus pagos. Ver tabla 4 de Frecuencias de Pago al final de esta sección.^^^^|12^Monto a pagar^Requerido^v^Numérico^9^Cantidad que el Cliente debe pagar, de acuerdo con la frecuencia estipulada en el contrato.<br>Para crédito del tipo Revolvente se debe interpretar como el mínimo a pagar en el periodo reportado.^^^^|13^Fecha de Apertura de la Cuenta^Requerido^f^F1^8^Fecha en que el otorgante realizó la apertura del crédito al Cliente.^^^fcontrato^|14^Fecha de Último Pago^Requerido^f^F1^8^Fecha más reciente cuando el Cliente efectuó un pago.<br>En caso de no contar con la fecha de último pago, es requerido reportar el dato de fecha de última disposición.^^^^|15^Fecha de Última Compra (disposición)^Requerido^f^Numérico^8^Fecha más reciente cuando el Cliente efectuó una disposición de crédito.<br>En caso de no contar con la fecha de última disposición, es requerido reportar fecha de último pago.^^^^|16^Fecha de Cierre del Crédito^Requerido^f^Numérico^8^Fecha de cierre de la disposición del crédito.<br>De acuerdo con los criterios de algunas Claves de Observación, el sistema puede aplicar fecha de cierre.^^^^|17^Fecha de Reporte^Requerido^f^F2^8^Debe contener la fecha cuando el Usuario extrae la información de su base de datos para reportarla a Buró de Crédito (la fecha del último día del periodo reportado).<br>Esta fecha debe corresponder con el periodo de actualización de la información del Usuario en su propio sistema.<br>Ver segmento de encabezado posición 35.^^^^|20^Garantía^Requerido^v^Texto^40^Contiene una descripción alfanumérica de la garantía utilizada para asegurar el crédito otorgado.^^^^|21^Crédito Máximo^Requerido^v^Numérico^9^Debe reportarse el importe más alto de crédito utilizado por el Cliente.^^^^|22^Saldo Actual^Requerido^v^Numérico^10^Es el importe total del adeudo (capital + intereses) contraído por el Cliente en relación con el periodo reportado por el Usuario.<br>El dato es requerido siempre que se trate de un crédito activo (sin fecha de cierre).^^^^|23^Límite de Crédito^Requerido^v^Numérico^9^Es la línea del crédito que el Usuario extiende al Cliente.^^^^|24^Saldo Vencido^Requerido^v^Numérico^9^La cantidad es un número entero y positivo y refiere al monto del saldo vencido a la fecha de reporte.<br>En el caso de cuentas del tipo Revolvente, el dato puede seguirse reportando aun cuando exista fecha de cierre.<br>Este criterio es válido en casos específicos de morosidad.^^^^|25^Número de Pagos Vencidos^Requerido^v^Numérico^4^El dato se valida que sea reportado consistentemente, en caso de ser inconsistente o no reportado, se advierte al Usuario; pero no afecta el porcentaje final de calidad de la información reportada en el periodo.<br>La etiqueta se valida conjuntamente con el campo de Forma de Pago (MOP) – etiqueta 26:<br>* Cuando el MOP reportado sea = 00, UR ó 99<br>* Número de pagos vencidos = 0<br>* Cuando el MOP reportado sea = ó > 02<br>* Número de pagos vencidos = ó > 1^^^^|26^Forma de Pago (MOP) Actual^Requerido^f^Numérico^2^El MOP reportado puede cambiar si no corresponde con la Clave de Observación reportada (ver tabla de Claves de Observación).^^^^|27^Histórico de Pagos^Requerido^v^Texto^24^El Usuario no deberá enviar ningún dato o valor en esta etiqueta.<br>Buró de Crédito integra automáticamente la historia con base en la forma de pago (MOP) que mensualmente se ha reportado en la cinta.^^^^|30^Clave de Observación^Requerido^f^Texto^2^^^^^|31^Total de Pagos Reportados^Requerido^f^Numérico^3^Estadística del comportamiento crediticio del Cliente *<br>(ver nota al final de la tabla).^^^^|32^Total de Pagos Calificados MOP 02^Requerido^f^Numérico^2^Número de veces en que históricamente la cuenta se encontró entre 01 y 29 días de atraso.^^^^|33^Total de Pagos Calificados con MOP 03^Requerido^f^Numérico^2^Número de veces en que históricamente la cuenta se encontró entre 30 y 59 días de atraso.^^^^|34^Total de Pagos Calificados con MOP 04^Requerido^f^Numérico^2^Número de veces en que históricamente la cuenta se encontró entre 60 y 89 días de atraso.^^^^|35^Total de Pagos Calificados con MOP 05 o mayor^Requerido^f^Numérico^2^Número de veces en que históricamente la cuenta se encontró entre 90 y 119 días de atraso.^^^^|39^Clave Anterior del Otorgante^Requerido^f^Texto^10^Los campos para las etiquetas 39, 40 y 41 presentan características comunes:<br>* El dato se reporta cuando hay un cambio de Entidad Financiera o Empresa Comercial acreedora (ejemplos: fusión de cartera de instituciones, migración de datos).<br>* Es necesario consultar el tema referente a reestructuras; sustituciones de deudor y daciones en renta; cambio en el número de crédito; transferencia a nueva Entidad Financiera o Empresa Comercial acreedora; o, conversión de tarjetas de crédito garantizadas a regulares.v* Antes de efectuar modificaciones a la información es necesario contactar al Área de Adquisición de Base de Datos (5449 4923).^^^^|40^Nombre Anterior del Otorgante^Requerido^v^Texto^16^^^^^|41^Número de Cuenta Anterior^Requerido^v^Texto^25^^^^^|43^Fecha de primer incumplimiento^Requerido^v^Texto^8^Fecha en que el cliente por primera vez incumplió en su crédito. El formato es DDMMAAAA:<br>* DD: número entre 01 y 31 (día del fin de periodo reportado);<br>* MM: número entre 01-12 (mes del periodo reportado); y,<br>* AAAA: año (cuatro dígitos).<br>En caso de no contar con la fecha es requerido reportar 01/01/1900.<br>El dato se valida que sea reportado consistentemente, en caso de ser inconsistente o no reportado, se rechaza el registro.<br>Solo aplica para la versión INTL11.^^^^01011900|99^Indicador de Fin del Segmento TL^Requerido^f^Texto^3^Debe contener FIN o END.^^^^FIN|";
	var $control="TR^Etiqueta del Segmento^Requerido^f^Texto^4^Si se reporta el segmento de cierre, este campo debe contener TRLR.^^^^TRLR|0^Total de Saldos Actuales^Requerido^f^Numérico^14^Contiene la suma de los importes de Saldo Actual (segmento de cuentas TL) de todos los registros reportados.<br>Los bytes que no se utilicen en este campo se deben llenar con ceros a la izquierda.^^^saldosactuales^|1^Total de Saldos Vencidos^Requerido^f^Numérico^14^Contiene la suma de los importes de todos los campos de Saldos Vencidos (segmento de cuentas TL) reportados.^^^saldosvencidos^|2^Total de Segmentos de encabezados del INTF Reportados^Requerido^f^Numérico^3^Contiene el número total de los segmentos de INTF reportados.^^^encabezados^|3^Total de Segmentos de PN (segmento de nombre) Reportados^Requerido^f^Numérico^9^Contiene el número total de los segmentos PN reportados.^^^pns^|4^Total de Segmentos de PA (segmento de dirección) Reportados^Requerido^f^Numérico^9^Contiene el número total de los segmentos PA reportados.^^^pas^|5^Total de Segmentos de PE (segmento de empleo) Reportados^Requerido^f^Numérico^9^Contiene el número total de los segmentos PE reportados.^^^pes^|6^Total de Segmentos de TL (segmento de cuentas) reportados^Requerido^f^Numérico^9^Contiene el número total de los segmentos TL reportados.^^^tls^|7^Contador de Bloques^Requerido^f^Numérico^6^Contiene el número de bloques en este archivo.<br>Si el contador de bloques no se encuentra disponible, llenar este campo con ceros.<br>En equipos del tipo Mainframe se utiliza para agrupar información en bloques de bytes (a su vez, estos conforman grupos de registros crediticios). Este campo es sólo informativo y muestra la cantidad de bloques de información enviados por el Proveedor en su cinta.^^^^|8^Nombre del Otorgante^Requerido^f^Texto^16^Contiene el nombre de la Entidad Financiera o Empresa Comercial otorgante del crédito a quien se devolverán las cintas después de ser procesadas.^^^^|9^Domicilio para devolución^Requerido^f^Texto^160^Contiene la dirección donde las cintas deben ser devueltas después de ser procesadas.^^^^|";

	var $aplicar=false;
	//var $numempresas; 	//para numero de empresas en el reporte
	//var $cantidadc;		//para el total de cantidad del detalle

	var $lcavecera; //1
	var $lnombre; //2
	var $ldireccion; //3
	var $lempleo; //4
	var $lcuentas; //5
	var $lcontrol; //6
	
	var $SaldosActuales;		//suma de saldos vigentes
	var $SaldosVencidos;		//suma de saldos vencidos
	var $TotalEncabezadosINTF;	//total de INTF reportados, será uno siempre
	var $TotalPN;			//Cuenta de las etiquetas PN que se reportan
	var $TotalPA;			//cuenta de los PA que se reportan
	var $TotalPE;			//Cuenta de los PE que se reportan
	var $TotalTL;			//Cuenta de los TL que se reportan
	var $Contador;			//Contador
	





	function buroclasspf()
	{
		$this->generalista($this->cavecera,1);
		$this->generalista($this->nombre,2);
		$this->generalista($this->direccion,3);
		$this->generalista($this->empleo,4);
		$this->generalista($this->cuentas,5);
		$this->generalista($this->control,6);
		
		$this->SaldosActuales=0;
		$this->SaldosVencidos=0;
		$this->TotalEncabezadosINTF=0;
		$this->TotalPN=0;
		$this->TotalPA=0;
		$this->TotalPE=0;
		$this->TotalTL=0;
		$this->Contador=0;

	}

//***************** metodos para mostrar datos generales de la cinta ************************
	function generaLista($listah,$ld)
	{
		$lista=split('[|]',$listah);
		$l[0]="";

		foreach ($lista as $id => $registro)
		{

			$reg=split('\^',$registro);
			$l[1]=count($reg);
			foreach ($reg as $idr => $campo)
			{
				switch($ld)
				{
				case 1:
					$this->lcavecera[$id][$idr]=$campo;
					break;
				case 2:
					$this->lnombre[$id][$idr]=$campo;
					break;
				case 3:
					$this->ldireccion[$id][$idr]=$campo;
					break;
				case 4:
					$this->lempleo[$id][$idr]=$campo;
					break;
				case 5:
					$this->lcuentas[$id][$idr]=$campo;
					break;
				case 6:
					$this->lcontrol[$id][$idr]=$campo;
					break;
				}
			}
		}
	}//fin de metodo generalista

	function muetradatos($cat)
	{

		$resultado =  "<table border = \"1\">\n";
		foreach($cat as $idl => $aux1){
		    ///inicio fila
		    $resultado .=  "\t<tr>\n";
		    foreach($aux1 as $idr=>$valor){
		      ///dibujar celda
		      $resultado .= "\t\t<td>$valor&nbsp;</td>\n";
		    }
		    $resultado .= "\t</tr>\n";
		    //fin de la fila
		}
		$resultado .= "</table><br><br>\n";

		return $resultado;
	}


	function cuentadias($fechacu)
	{

		$cuando = mktime(0,0,0,substr($fechacu,5,2),substr($fechacu,-2),substr($fechacu,0,4));
		//$cuando = mktime(0,0,0,mes,dia,aÒo);
		$hoy = time();
		$resta = $hoy - $cuando;
		return round($resta/86400);
	}

// *************** fin de metodos para mostrar datos de cinta *****************************

// ************metodos para formato de datos de cinta ************************************

function quitabasura($dato)
	{
	//PARA QUITAR LAS ETIQUETAS HTML Y CARACTERES ESPECIALES
		$residuo=$dato;

		$i=1;
		$p1=strpos($residuo,"<");
		$p2=strpos($residuo,">");

		//$p='\<.*\>';
		//$p='\<(\s|.|[0-9])*\>';
		//@preg_replace ($p ,  "" , $residuo);


		$residuo = substr($residuo,0,$p1) . substr($residuo,$p2);

		$residuo = str_replace("Ò", "n", $residuo);
		$residuo = str_replace("·", "a", $residuo);
		$residuo = str_replace("È", "e", $residuo);
		$residuo = str_replace("Ì", "i", $residuo);
		$residuo = str_replace("Û", "o", $residuo);
		$residuo = str_replace("?", "u", $residuo);
		$residuo = str_replace("¸", "u", $residuo);
		$residuo = str_replace("—", "N", $residuo);
		$residuo = str_replace("¡", "A", $residuo);
		$residuo = str_replace("…", "E", $residuo);
		$residuo = str_replace("Õ", "I", $residuo);
		$residuo = str_replace("”", "O", $residuo);
		$residuo = str_replace("?", "U", $residuo);
		$residuo = str_replace("‹", "U", $residuo);
		$residuo = str_replace(",", "", $residuo);
		$residuo = str_replace(".", "", $residuo);
		$residuo = str_replace(">", "", $residuo);
		$residuo = str_replace("*", "&", $residuo);
		$residuo = str_replace("Ñ", "N", $residuo);
		$residuo = str_replace("ñ", "n", $residuo);


		$residuo = str_replace("á", "a", $residuo);
		$residuo = str_replace("é", "e", $residuo);
		$residuo = str_replace("í", "i", $residuo);
		$residuo = str_replace("ó", "o", $residuo);
		$residuo = str_replace("ú", "u", $residuo);
		$residuo = str_replace("ü", "u", $residuo);
		$residuo = str_replace("Á", "A", $residuo);
		$residuo = str_replace("É", "E", $residuo);
		$residuo = str_replace("Í", "I", $residuo);
		$residuo = str_replace("Ó", "O", $residuo);
		$residuo = str_replace("Ú", "U", $residuo);

		

		return $residuo;



	}


	function fechaburo($fechaval,$tipo)
	{
	//FunciÛn para transformar la fecha en el formato establecido en 2 tipos Y
	//DEBE REGRESAR TEXTO DEBE RECIBIR
	//$fecha: UNA FECHA DE TIPO AAAA-MM-DD
	//$tipo un numero que pueda distinguir que informacion regresar como sigue
	//1: DDMMAAAA   (DIA MES A—O)
	//2: MMAAAA	(MES A—O)

		$resultado="";
		$aux="";
		switch($tipo)
		{
		case 1:
			$aux = 	split('[/.-]', $fechaval);
			$resultado = @$aux[2] . @$aux[1] . @$aux[0];

			break;
		case 2:
			$aux = 	split('[/.-]', $fechaval);
			$resultado =@$aux[1] . @$aux[0];
		}

		return $resultado;


	}

	function textoburo($dato,$longitud,$t,$c)
	{
	//FunciÛn que genera el texto ordenado como el burÛ lo solicita y rellena espacios
	//seg?n la longitud requerida
	// $dato: dato que se analizara y procesara para regresarlo listo para ser usado
	// $longitud: longitud que debe de tener el dato de resultado
	// $t: tipo de dato, f para fijo y v para variable
	// $c: para saber si se debe de colocar la longitud delante del dato
		//VERIFICAR ANTES LOS SIMBOLOS PARA QUITAR ETIQUETAS


		$nuevot=$this->quitabasura($dato);
		$ltexto= strlen($nuevot);

		if($ltexto>$longitud)
		{
			$nuevot=substr($nuevot,0,$longitud);
			$ltexto=$longitud;
		}

		$resultado="";
		if ($t =='f'  )
		{
			$diferencia = $longitud - $ltexto ;
			//$resultado = str_pad($dato, $diferencia, " ", STR_PAD_RIGHT);
			$resultado = str_pad($nuevot, $longitud, " ", STR_PAD_RIGHT);
			if ($c == 1 )
			{
			
				if(strlen(trim($nuevot))>0)
				{
					$resultado =  sprintf("%02s" ,strlen($resultado)) . $resultado;
				}
				else
				{
					$resultado ="";
				}
			}
		}
		else
		{
			if( strlen(trim($nuevot)) !=0)
			{
				$resultado =  sprintf("%02s" ,strlen(trim($nuevot))) . trim($nuevot);
			}
			else
			{
				$resultado = "";// . strlen($ltexto);
			}
		}

		return strtoupper($resultado);

	}
	
	function numeroburo($dato,$longitud,$t,$c)
	{
	//FunciÛn que rellena coloca los datos numericos seg?n le burÛ, n?meros enteros,
	//positivos y rellena ceros a la izquierda
		//echo $dato . " " . $longitud . "<br>";
		//$dato = str_replace(",","",number_format  ( $dato ,0 ));
		//solo texto inicial hasta donde exista un punto para quitar los desimales

		$resultado = "";

		/*
		$ltexto=(int)$dato;
		$ltexto=(string)$ltexto;
		*/

		$ltexto=$this->quitabasura($dato);
		//$ltexto=(string)$dato;

		$p1=strpos($ltexto,".");
		if($p1 !== false)
		{
			//$ltexto= trim(substr($ltexto,0,$p1-1));

			$ltexto=(int)$ltexto;
			$ltexto=(string)$ltexto;


		}

		if($t =='f' )
		{
			$resultado = str_pad($ltexto, $longitud, "0", STR_PAD_LEFT);
			if ($c == 1)
			{
				if(strlen(trim($ltexto))>0)
				{
					$resultado =  sprintf("%02s" ,strlen($resultado)) . $resultado;
				}
				else
				{
					$resultado = "";
				}
			}
		}
		else
		{
			if( strlen($ltexto) !=0)
			{
				$resultado =  sprintf("%02s" ,strlen($ltexto)) . $ltexto;
			}
			else
			{
				$resultado = "";
			}
			
		}

		return $resultado;

	}


	function rfcburo($dato)
	{
	//PARA VERIFICAR Y DAR FORMATO CORRECTO AL RFC SEGUN EL BUR”

		$aux=substr($dato,4,1);
		if(is_numeric($aux))
		{//RFC moral

			if(strlen($dato)==9 or strlen($dato)==12)
			{
				return textoburo($dato,13);
			}
			else
			{
				return textoburo("",13);
			}


		}
		else
		{//RFC fisica


			if(strlen($dato)==10 or strlen($dato)==13)
			{
				return textoburo($dato,13);
			}
			else
			{
				return textoburo("",13);
			}


		}


	}

	//******************** fon de metodos de fotmato de cinta ****************************

//******************* metodos que generan la cinta por secciones COMERCIAL ********************

		function gcabecera()
		{//Genera para empresa Comercial la 

			$resultado = "";
			$dato="";
			foreach($this->lcavecera as $idl => $aux1)
			{

			    //echo $aux1[0] . "," . $aux1[2] . "," . $aux1[3]  . "<br>";
			    //$resultado .= $this->numeroburo($aux1[0],2,$aux1[3]) . "";
			    if(substr(@$aux1[2],0,2)=="Re")
			    {

			    	if($aux1[9]=="")
			    	{
			    		if($aux1[10]!="")
			    		{
			    			$dato=$aux1[10];

			    		}

			    	}
			    	else
			    	{
			    		$dato="";
			    	}

			    	//echo $dato . "?";
			    	switch(substr($aux1[4],0,2))
			    	{
			    	case 'Nu':
						$resultado .= $this->numeroburo($dato,$aux1[5],$aux1[3],0) . "";
			    		break;

			    	case 'Te':
			    		$resultado .= $this->textoburo($dato,$aux1[5],$aux1[3],0)  . "";
			    		break;
			    	case 'F1':
			    		//$dato=date("Y-m-d");
						$fecha = date('Y') . "-" . date('m') . "-1";
						$dias= 	1; // los días a restar
						$dato = date("Y-m-d", strtotime("$fecha -$dias day"));			    		
			    		
						$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5],$aux1[3],0) . "";
			    		break;

			    	case 'F2':

			    		$auxhd=date("m");
			    		if ($auxhd==1)
			    		{
			    			$auxhd=date("Y")-1;
			    			$dato = $auxhd . "-12-01";
			    		}
			    		else
			    		{
			    			$auxhd=date("m")-1;
			    			$dato = date("Y") . "-" . $auxhd . "-01";
			    		}

//			    		$dato=date("Y-m-d");
			    		$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5],$aux1[3],0)  . "";
			    		break;

			    	}

			    }
			    else
			    {
			    	switch(substr(@$aux1[4],0,2))
			    	{
			    	case 'Nu':
						$resultado .= $this->numeroburo("0",$aux1[5],$aux1[3],0) . "";
			    		break;

			    	case 'Te':
			    		$resultado .= $this->textoburo(" ",$aux1[5],$aux1[3],0)  . "";
			    		break;
			    	}

			    }

			    $dato="";
			    //$resultado .= "|";
			}
			$this->TotalEncabezadosINTF++;
			return $resultado;
		}



		function gnombre()
		{//Genera para empresa Comercial la EM


			//Ordenarlos por inquilino, y por contrato
			//preparar las variables del segmento de empresas en la actual
			//rfc : de inquilino, datos fiscales
			//nombrei: nombre del inquilino si es empresa de lo contrario vacio (verificar con el RFC)
			//nombre1i: nombre del inquilino Persona fisica (vacio si es empresa)
			//nombre2i: nombre del inquilino Persona fisica (vacio si es empresa)
			//apaternoi: apellido paterno del inquilino
			//amaternoi: apellido materno del inquilino
			//calle: calle del inmueble y numero exterior (longitud maxima 40)
			//colonia: colonia del inmueble
			//delegacion: delegacion del inmueble
			//cp: codigo postal del inmueble


			//falta la mecanica de marcado y lo relativo en la consulta
			//donde podamos ver con claridad cuales registros se deben
			//de enviar.

			$idinquilino=0;
			$rfc="rfc";
			$nombrei="nombrei";
			$nombre1i="nombre1i";
			$nombre2i="nombre2i";
			$apaternoi="apaternoi";
			$amaternoi="amaternoi";
			$calle="calle";
			$interior ="interior";
			$colonia="colonia";
			$delegacion="delegacion";
			$cp="cp";
			$estado="estado";
			$pais="pais";
			$idcontrato="idcontrato"; // para poder obtener a los obligados solidarios AVALES y para el detalle del credito (historia)


			//corregir la consulta, para que sea por inquilino y no por contrato, de tal suerte que podamos
			//hacer que cuando sea el momento de ver los creditos que tiene cada inquilino, podamos
			//mostrar todos los contratos que tiene el inquilino, uno o muchos
			//mismos que investigaremos al momento de describir los creditos
			//$sqlem = "select idcontrato, nombre, nombre2, apaterno, amaterno, nombrenegocio, rfc, calle, numeroext, numeroint, colonia, delmun, clavee, clavep, cp from contrato c, inquilino p, inmueble i, estado e, pais pa where c.idinquilino = p.idinquilino and c.idinmueble = i.idinmueble and c.concluido = false and i.idestado = e.idestado and pa.idpais=i.idpais ";
			//$sqlem = "select idinquilino, nombre, nombre2, apaterno, amaterno, nombrenegocio, rfc from (select idinquilino as idi, count(idcontrato) as num from contrato where concluido = false   group by idinquilino) c, inquilino p where c.idi = p.idinquilino  and (length(rfc)=13 or length(rfc)=10) " ;
			//$sqlem = "select idinquilino, nombre, nombre2, apaterno, amaterno, nombrenegocio, rfc from (select idinquilino as idi, count(idcontrato) as num from contrato where concluido = false and idcontrato in(345,278)   group by idinquilino) c, inquilino p where c.idi = p.idinquilino and (length(rfc)=13 or length(rfc)=10) " ;
			$sqlem = "select idinquilino, nombre, nombre2, apaterno, amaterno, nombrenegocio, rfc from (select idinquilino as idi, count(idcontrato) as num from contrato where concluido = false   group by idinquilino) c, inquilino p where c.idi = p.idinquilino  and length(rfc)<>12  " ;
			$resultado = "";
			$dato="";
			$operacion = mysql_query($sqlem);
			$this->numempresas=mysql_num_rows($operacion);
			while($row = mysql_fetch_array($operacion))
			{

				//$sqldirec="select idcontrato, calle, numeroext, numeroint, colonia, delmun, cp, clavee, clavep from contrato c, inmueble i, pais p, estado e where c.concluido = false and c.litigio = false and c.idinmueble = i.idinmueble and i.idestado = e.idestado and i.idpais=p.idpais and idinquilino = " . $row["idinquilino"];
				$sqldirec="select idcontrato, direccionf, inq.colonia, delegacion, inq.cp, clavee, clavep from contrato c, inmueble i, pais p, estado e, inquilino inq where c.concluido = false and c.idinmueble = i.idinmueble and inq.idestado = e.idestado and i.idpais=p.idpais and concluido = false and inq.idinquilino = " . $row["idinquilino"];
				$operaciond = mysql_query($sqldirec);
				$rowd= mysql_fetch_array($operaciond);
				$idinquilino=$row["idinquilino"];
				$rfc=$row["rfc"];
				$nombrei=$row["nombre"];
				$nombre1i=$row["nombre"];
				$nombre2i=$row["nombre2"];
				$apaternoi=$row["apaterno"];
				$amaternoi=$row["amaterno"];
				//$calle=trim($rowd["calle"]) . " " . trim($rowd["numeroext"]);// . " " . trim($row["numeroint"]);
				$calle=trim($rowd["direccionf"]);// . " " . trim($row["numeroint"]);
				//$interior = trim($rowd["numeroint"]);
				$colonia=$rowd["colonia"];
				//$delegacion=$rowd["delmun"];
				$delegacion=$rowd["delegacion"];
				$cp=$rowd["cp"];
				$idcontrato=$rowd["idcontrato"];
				$estado=$rowd["clavee"];
				$pais=$rowd["clavep"];
				$idcontrato=$rowd["idcontrato"];

				//verificar el RFC para determinar los nombres a mostrar


				$dr1="";
				$dr2="";
				foreach($this->lnombre as $idl => $aux1)
				{

				    //$resultado .= $this->numeroburo($aux1[0],2,"f",0);// . "|";
				    $dr1 = $this->numeroburo($aux1[0],2,"f",0);
				    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
				    if(substr(@$aux1[2],0,2)=="Re")
				    {

			    	//hacer la distinciÛn para el campo 23 verificar el rfc para colocar 1 o 2 para PM o PFAE como en accionistas

	

				    		if($aux1[9]=="")
				    		{
				    			if($aux1[10]!="")
				    			{
				    				$dato=$aux1[10];

				    			}

				    		}
				    		else
				    		{
				    			eval("\$dato=\$" . $aux1[9] . ";");
				    		}




				    		//echo $dato . "?";
				    		switch(substr($aux1[4],0,2))
				    		{
				    		case 'Nu':
								//$resultado .= $this->numeroburo($dato ,$aux1[5],$aux1[3],1);// . "|";
								$dr2 = $this->numeroburo($dato ,$aux1[5],$aux1[3],1);// . "|";
				    			break;

				    		case 'Te':
				    			if(strlen($dato)>$aux1[5])
				    			{
				    				$dato=substr($dato,0,(integer)$aux1[5]);

				    			}
				    			//$resultado .= $this->textoburo($dato,$aux1[5],$aux1[3],1) ;// . "|";
				    			$dr2 = $this->textoburo($dato,$aux1[5],$aux1[3],1) ;// . "|";
				    			break;
				    		case 'F1':
				    			$dato=date("Y-m-d");
								//$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5],$aux1[3],1); //. "|";
								$dr2 = $this->numeroburo($this->fechaburo($dato,1),$aux1[5],$aux1[3],1); //. "|";
				    			break;

				    		case 'F2':
				    			$dato=date("Y-m-d");
				    			//$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5],$aux1[3],1);//  . "|";
				    			$dr2 = $this->numeroburo($this->fechaburo($dato,2),$aux1[5],$aux1[3],1);//  . "|";
				    			break;

				    		}
				    	

				    }
				    else
				    {
				    	switch(substr(@$aux1[4],0,2))
				    	{
				    	case 'Nu':

							//$resultado .= $this->numeroburo("0",$aux1[5],$aux1[3],1);// . "|";
							$dr2 .= $this->numeroburo("0",$aux1[5],$aux1[3],1);// . "|";
				    		break;

				    	case 'Te':
				    		//$resultado .= $this->textoburo(" ",$aux1[5],$aux1[3],1) ;// . "|";
				    		$dr2 .= $this->textoburo(" ",$aux1[5],$aux1[3],1) ;// . "|";
				    		break;


				    	}

				    }

				    $dato="";
				    if($dr2 !="")
				    {
				    	$resultado .= $dr1 . $dr2 ;
				    }
				    //$resultado .= "|";
				    $dr1="";
					$dr2="";

				}
				$this->TotalPN++;
				$resultado .=  $this->gdireccion($idinquilino) ;
				//$resultado .= "\n\n" .  $this->gempleo($idinquilino);
				$resultado .=  $this->gcuentas($idinquilino);


			}

			return $resultado ;

		}


		//faltan los accionistas, por ahora no estan en la base de datos
		function gdireccion($id)
		{//Genera para empresa Comercial la ac (accionistas)
		 //aqui se toma los datos del os obligados solidarios del credito en cuestion
		 //a?n no esta listo para su operacion


			$rfc="";
			$curp="";
			$nombre1a="";
			$nombre2a="";
			$apaternoa="";
			$amaternoa="";
			$direcciona="";
			$coloniaa="";
			$delegaciona="";
			$cpa="";
			$paisa="";
			$estadoa="";
			$porcentaje="";

			$sqlac = "select rfc, nombre, nombre2, apaterno, amaterno, direccionf, colonia, delegacion, cp, tel, clavee from inquilino a, estado e   where a.idestado = e.idestado  and  a.idinquilino = $id ";
			$resultado = "";
			$dato="";
			$operacioncr = mysql_query($sqlac);
			while($row = mysql_fetch_array($operacioncr))
			{

				$rfc=$row["rfc"];
				$nombre1a=$row["nombre"];
				$nombre2a=$row["nombre2"];
				$apaternoa=$row["apaterno"];
				$amaternoa=$row["amaterno"];
				$direcciona=trim($row["direccionf"]);
				$coloniaa=$row["colonia"];
				$delegaciona=$row["delegacion"];
				$cpa=trim($row["cp"]);
				$estadoa=$row["clavee"];


				//verificar el RFC para determinar los nombres a mostrar


				$dr1="";
				$dr2="";
				foreach($this->ldireccion as $idl => $aux1)
				{

				    //$resultado .= $this->numeroburo($aux1[0],2,"f",0);// . "|";
				    $dr1 = $this->numeroburo($aux1[0],2,"f",0);// . "|";
				    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
				    if(substr(@$aux1[2],0,2)=="Re")//requerido
				    {



				    		if(trim($aux1[9])=="")
				    		{
				    			if(trim($aux1[10])!="")
				    			{
				    				$dato=$aux1[10];

				    			}

				    		}
				    		else
				    		{
				    			//echo "$idl|" . $aux1["1"] . "| \$dato=\$" . $aux1[9] . ";";
				    			eval("\$dato=\$" . $aux1[9] . ";");
				    		}




				    		//echo $dato . "?";
				    		switch(substr($aux1[4],0,2))
				    		{
				    		case 'Nu':
								$dr2= $this->numeroburo($dato ,$aux1[5],$aux1[3],1);// . "|";
				    			break;

				    		case 'Te':
				    			if(strlen($dato)>$aux1[5])
				    			{
				    				$dato=substr($dato,0,(integer)$aux1[5]);

				    			}
				    			$dr2= $this->textoburo($dato,$aux1[5],$aux1[3],1);//  . "|";
				    			break;
				    		case 'F1':
				    			$dato="";
								$dr2= $this->numeroburo($this->fechaburo($dato,1),$aux1[5],$aux1[3],1);// . "|";
				    			break;

				    		case 'F2':
				    			$dato="";
				    			$dr2= $this->numeroburo($this->fechaburo($dato,2),$aux1[5],$aux1[3],1);//  . "|";
				    			break;
				    		}
				    	
				    }
				    else
				    {
				    	switch(substr(@$aux1[4],0,2))
				    	{
				    	case 'Nu':
						$dr2= $this->numeroburo("0",$aux1[5],$aux1[3],1);// . "|";
				    		break;

				    	case 'Te':
				    		$dr2= $this->textoburo(" ",$aux1[5],$aux1[3],1) ;// . "|";
				    		break;


				    	}

				    }

				    if($dr2 !="")
				    {
				    	$resultado .= $dr1 . $dr2 ;
				    }
				    //$resultado .= "|";
				    $dr1="";
					$dr2="";


					//$resultado .= "|";
				    $dato="";

				}
				$resultado .= ""; //detalle

			}
			$this->TotalPA++;
			return $resultado;

		}


		function gempleo($id)
		{//Genera para empresa Comercial la cr


			$rfc="";
			$fechainicio="";
			$fechatermino="";
			$idcontrato="";

			//$sqlcr = "select idcontrato, rfc, fechainicio, fechatermino from contrato c, inquilino p where c.idinquilino = p.idinquilino and  c.concluido = false and c.idcontrato = $id ";
			$sqlcr = "select idcontrato, rfc, fechainicio, fechatermino from contrato c, inquilino p where c.idinquilino = p.idinquilino and  c.concluido = false and c.litigio=false and p.idinquilino= $id ";
			$resultado = "";
			$dato="";
			$operacioncr = mysql_query($sqlcr);
			while($row = mysql_fetch_array($operacioncr))
			{
				$rfc=$row["rfc"];
				$fechainicio=$row["fechainicio"];
				$fechatermino=$row["fechatermino"];
				$idcontrato=$row["idcontrato"];

				//verificar el RFC para determinar los nombres a mostrar



				foreach($this->lcr as $idl => $aux1)
				{

				    $resultado .= $this->numeroburo($aux1[0],2);// . "|";
				    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
				    if(substr($aux1[2],0,2)=="Re")
				    {

				    	if($aux1[9]=="")
				    	{
				    		if($aux1[10]!="")
				    		{
				    			$dato=$aux1[10];

				    		}

				    	}
				    	else
				    	{
				    		eval("\$dato=\$" . $aux1[9] . ";");
				    	}




				    	//echo $dato . "?";
				    	switch(substr($aux1[4],0,2))
				    	{
				    	case 'Nu':
						$resultado .= $this->numeroburo($dato ,$aux1[5],$aux1[3]);// . "|";
				    		break;

				    	case 'Te':
				    		if(strlen($dato)>$aux1[5])
				    		{
				    			$dato=substr($dato,0,(integer)$aux1[5]);

				    		}
				    		$resultado .= $this->textoburo($dato,$aux1[5],$aux1[3]);//  . "|";
				    		break;
				    	case 'F1':
				    		$dato=date("Y-m-d");
						$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5],$aux1[3]);//. "|";
				    		break;

				    	case 'F2':
				    		$dato=date("Y-m-d");
				    		$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5],$aux1[3]);//  . "|";
				    		break;

				    	}


				    }
				    else
				    {
				    	switch(substr($aux1[4],0,2))
				    	{
				    	case 'Nu':

						$resultado .= $this->numeroburo("0",$aux1[5],$aux1[3]);// . "|";
				    		break;

				    	case 'Te':
				    		$resultado .= $this->textoburo(" ",$aux1[5],$aux1[3]);//  . "|";
				    		break;


				    	}

				    }

				    $dato="";

				}
				
				//$resultado .= "|";
				//$resultado .= $this->gcde($idcontrato); //detalle
				//$resultado .= "<br><br>" . $this->gcav($idcontrato) . "<br><br>"; //avales es opcional, por ahora no lo colocare, falta modificar la tabla

			}
			$this->TotalPE++;
			return $resultado;

		}


		function gcuentas($id)
		{//Genera para empresa Comercial la de


		//Cambios: hay que hacer la consulta para que verifique los grupos de dias y enviar el txto a cr


			$rfc="";
			$fecha="";
			$vencido="";
			$idcontrato="";
			$aplicado ="";
			$diasv=0;
			$total =0;
			//$hoy=date("Y-m-d");
			$fecha = date('Y') . "-" . date('m') . "-1";
			$dias= 	1; // los días a restar
			$hoy= date("Y-m-d", strtotime("$fecha -$dias day"));			

			$sqldea[0]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 150 and 999 order by c.idcontrato ";
			$sqldea[1]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 120 and 149 order by c.idcontrato ";
			$sqldea[2]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 90 and 119 order by c.idcontrato ";
			$sqldea[3]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 60 and 89 order by c.idcontrato ";
			$sqldea[4]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 30 and 59 order by c.idcontrato ";
			$sqldea[5]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 1 and 29 order by c.idcontrato ";
			$sqldea[6]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) <= 0 order by c.idcontrato";//"
			

			$resultado = "";
			$sqltl = "select idcontrato, fechainicio as fcontrato from contrato where idinquilino = $id and concluido = false";
			$operacioncr = mysql_query($sqltl);


			while($row = mysql_fetch_array($operacioncr))
			{
				$id=$row["idcontrato"];
				$fcontrato =$row["fcontrato"];
				//if(mysql_num_rows($operacionde)>0)
				//{
				$dr1="";
				$dr2="";
				$dato="";
				foreach($this->lcuentas as $idl => $aux1)
				{

				    //$resultado .= $this->numeroburo($aux1[0],2,$aux1[3]);// . "|";
				    //echo @$aux1[0] . ", " . @$aux1[3] . "," . @$aux1[9] . "\n";
				    $dr1 = $this->numeroburo($aux1[0],2,"f",0);
				    if(substr(@$aux1[2],0,2)=="Re")
				    {
	
				    	//Para determinar el campo que se debe de tratar en forma especial
		    			switch($aux1[0])
					{
					case '10': //Numero de pagos
						$sql10="select DISTINCT fechanaturalpago from historia where idcontrato = $id and idcobros in (select idcobros from cobros where idcontrato = $id and idperiodo <>1) group by fechanaturalpago ";
						$operacion10 = mysql_query($sql10);
						$dato = mysql_num_rows($operacion10);
						
						break;
					case '11': //frecuencia de pagos
					
						$sql11= "select DISTINCT nombre from cobros c, periodo p where idcontrato = $id and c.idperiodo <>1 and c.idperiodo = p.idperiodo group by nombre";
						$operacion11 = mysql_query($sql11);
						$row11 = mysql_fetch_array($operacion11);
						switch ($row11["nombre"])
						{
						case 'Semanal':
							$dato="W";
							break;
						case 'Quincenal':
							$dato="S";
							break;						
						case 'Mensual':
							$dato="M";
							break;					
						case 'Bimestral':
							$dato="B";
							break;					
						case 'Anual':
							$dato="Y";
							break;
						case 'Semestral':
							$dato="H";			
							break;
						}
					
						break;
					case '12': //Monto a pagar por periodo
						$sql12="select sum(cantidad + iva) as total from cobros where idcontrato = $id and idperiodo <>1 group by idperiodo";
						$operacion12 = mysql_query($sql12);
						$row12 = mysql_fetch_array($operacion12);
						$dato = $row12["total"];
						break;					
						
					case '14': //Fecha ultima de pago
					
						$sql14="select max(fechapago) as fp from historia where idcontrato = $id";
						$operacion14 = mysql_query($sql14);
						$row14 = mysql_fetch_array($operacion14);
						$dato = $row14["fp"];					
						break;
					case '22': //Saldo actual
						$sql22="select format(sum(cantidad + iva),0) as c from historia where idcontrato = $id and aplicado = false group by idcontrato";
						$operacion22 = mysql_query($sql22);
						$row22 = mysql_fetch_array($operacion22);
						$dato = $row22["c"];
						$this->SaldosActuales +=$dato;
						break;
					case '24': //saldo vencido
						$dato=date("Y-m-d");
						$sql24="select format(sum(cantidad + iva),0) as c from historia where idcontrato = $id and aplicado = false and fechanaturalpago<'$dato' group by idcontrato";
						$operacion24 = mysql_query($sql24);
						$row24 = mysql_fetch_array($operacion24);
						$dato = $row24["c"];					
						$this->SaldosVencidos +=$dato;
						break;
					case '26': //CLABE DE OBSERVACION PARA MOP'S
						$dato=date("Y-m-d");
						$sql24="select format(sum(cantidad + iva),0) as c from historia where idcontrato = $id and aplicado = false and fechanaturalpago<'$dato' group by idcontrato";
						$operacion24 = mysql_query($sql24);
						$row24 = mysql_fetch_array($operacion24);
						if($row24["c"] >0)
						{
							$dato = "CV";
						}
						else
						{
							$dato = "CA";
						}
						$this->SaldosVencidos +=$dato;
						break;
					case '32': //numero de veces retrazos de 1 - 29 dias
						$sql32="select * , datediff(fechapago,fechanaturalpago)from historia where idcontrato = $id having datediff(fechapago,fechanaturalpago) between 1 and 29 order by idcontrato";
						$operacion32 = mysql_query($sql32);
						$dato = mysql_num_rows($operacion32);					
						break;
						
					case '33': ////numero de veces retrazos de 30 - 59 dias
						$sql33="select * , datediff(fechapago,fechanaturalpago)from historia where idcontrato = $id having datediff(fechapago,fechanaturalpago) between 30 and 59 order by idcontrato";
						$operacion33 = mysql_query($sql33);
						$dato = mysql_num_rows($operacion33);
						break;
					case '34': ////numero de veces retrazos de 60 - 89 dias
						$sql34="select * , datediff(fechapago,fechanaturalpago)from historia where idcontrato = $id having datediff(fechapago,fechanaturalpago) between 60 and 89 order by idcontrato";
						$operacion34 = mysql_query($sql34);
						$dato = mysql_num_rows($operacion34);
						break;
					case '35': ////numero de veces retrazos de 90 - 119 dias
						$sql35="select * , datediff(fechapago,fechanaturalpago)from historia where idcontrato = $id having datediff(fechapago,fechanaturalpago) >=90 order by idcontrato";
						$operacion35 = mysql_query($sql35);
						$dato = mysql_num_rows($operacion35);
						break;
						
				    default:
	
	
	
	
	
						if($aux1[9]=="")
						{
							if($aux1[10]!="")
							{
								$dato=$aux1[10];
	
							}
	
						}
						else
						{
							eval("\$dato=\$" . $aux1[9] . ";");
						}
	
	
					}
	
					//echo "\n" . $dato . "?\n";
					switch(substr($aux1[4],0,2))
					{
					case 'Nu':
						$dr2= $this->numeroburo($dato ,$aux1[5],$aux1[3],1);// . "|";
						break;
	
					case 'Te':
						if(strlen($dato)>$aux1[5])
						{
							$dato=substr($dato,0,(integer)$aux1[5]);
						}
						$dr2= $this->textoburo($dato,$aux1[5],$aux1[3],1);//  . "|";
						break;
					case 'F1':
						//$dato=date("Y-m-d");
						$dr2= $this->numeroburo($this->fechaburo($dato,1),$aux1[5],$aux1[3],1);// . "|";
						break;
					case 'F2':
						$dato=date("Y-m-d");
						$dr2= $this->numeroburo($this->fechaburo($dato,1),$aux1[5],$aux1[3],1);//  . "|";
						break;
	
					}
	
	
				    	
	
				    }
				    else
				    {
				    	switch(substr(@$aux1[4],0,2))
				    	{
				    	case 'Nu':
						$dr2= $this->numeroburo("0",$aux1[5],$aux1[3],1);// . "|";
				    		break;
				    	case 'Te':
				    		$dr2= $this->textoburo(" ",$aux1[5],$aux1[3],1);// . "|";
				    		break;
	
	
				    	}
	
				    }

				    $dato="";
					//echo "\n$dr1=$dr2\n";
					if($dr2 !="")
					{
						$resultado .= $dr1 . $dr2 ;
					}
					$dr1="";
					$dr2="";				    

				}
			
				$this->TotalTL++;
				//$resultado .= "|";
		
			}


			

			return $resultado ;

		}


		function gcontrol($id)
		{//Genera para empresa Comercial la ts (fin de archivo)

			$resultado="";
			$saldosactuales=$this->SaldosActuales;
			$saldosvencidos=$this->SaldosVencidos;
			$encabezados = $this->TotalEncabezadosINTF;
			$pns=$this->TotalPN;
			$pas=$this->TotalPA;
			$pes=$this->TotalPE;
			$tls=$this->TotalTL;

			foreach($this->lcontrol as $idl => $aux1)
			{

			    //$resultado .= $this->numeroburo($aux1[0],2,$aux1[3]);// . "|";
			    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
			    if(substr(@$aux1[2],0,2)=="Re")
			    {

			    	if($aux1[9]=="")
			    	{
			    		if($aux1[10]!="")
			    		{
			    			$dato=$aux1[10];

			    		}

			    	}
			    	else
			    	{
			    		eval("\$dato=\$" . $aux1[9] . ";");
			    	}




			    	//echo $dato . "?";
			    	switch(substr($aux1[4],0,2))
			    	{
			    	case 'Nu':
					$resultado .= $this->numeroburo($dato ,$aux1[5],$aux1[3],0);// . "|";
			    		break;

			    	case 'Te':

			    		$resultado .= $this->textoburo($dato,$aux1[5],$aux1[3],0) ;// . "|";
			    		break;
			    	case 'F1':
			    		$dato=date("Y-m-d");
					$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5],$aux1[3],0);// . "|";
			    		break;

			    	case 'F2':
			    		$dato=date("Y-m-d");
			    		$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5],$aux1[3],0);//  . "|";
			    		break;

			    	}


			    }
			    else
			    {
			    	switch(substr(@$aux1[4],0,2))
			    	{
			    	case 'Nu':

					$resultado .= $this->numeroburo("0",$aux1[5],$aux1[3],0);// . "|";
			    		break;

			    	case 'Te':
			    		$resultado .= $this->textoburo(" ",$aux1[5],$aux1[3],0);//  . "|";
			    		break;


			    	}

			    }

			    $dato="";
			    //$resultado .= "|";

			}
			$resultado .= "";


			return $resultado;

		}


}
?>