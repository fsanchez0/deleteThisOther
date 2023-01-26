<?php
//Clase para generar Cinta de envio al buró de credito
//cada registro de la listaburo.php tiene 11 campos
//1: numero de registro segun buró
//2: nombre de campo
//3: exigencia para institucion fianciero
//4: exigencia para empresa cretidicio
//5: tipo de dato
//6: longitud del campo
//7: descripción y regla del campo
//8: tabla del buro que servirá de catalogo de ese campo
//9: tabla de la base o instrucción del valor del campo (ej. i,variable o instrucción)
//10: campo de al base de datos
//11: valor por defecto

//para los catalogos solo tienen 2

// requiere de que ya exista una conexio na la base de datos de bujalil
class buroclass
{

	var $hd="HD^IDENTIFICADOR^Requerido  ^Requerido  ^Texto^5^DEBE CONTENER BNCPM^^^^BNCPM|00^INSTITUCION (USUARIO)^Requerido  ^Requerido  ^Numérico^4^Se refiere a la clave de usuario otorgada por buró de crédito para identificar a la instutucion proveedora de la información^^^^1404|01^Institucion anterior^Opcional^No aplica^Numérico^4^Se utiliza para reportar fusiones, adquisiciones o copmras de cartera^^^^|02^tipo de institución^Requerido  ^Requerido  ^Numérico^3^Ver catálogo al final de tabla***^institucion^institucion^^2|03^Formato^Requerido  ^Requerido  ^Texto^1^1=Detallado para Entidades financieras; 2.= Sumarizado para Empresas comerciales^^^^1|04^Fecha ^Requerido  ^Requerido  ^F1^8^Foramto : DDMMAAAA, se refiere a la fecha de generación de la cinta^^i,date('Y-m-d');^^|5^Periodo^Requerido  ^Requerido  ^F2^6^Formato: MMAAAA. Se refiere al mes y al año al que pertenece la informacion reportada^^i,date('Y-m-d');^^|06^Filter^Requerido  ^Requerido  ^Texto ^53^Espacio reservado para el guturo.^^^^";
	var $em="EM^Identificador de seguimiento^Requerido  ^Requerido  ^Texto ^2^Debe contener EM^^^^EM|00^RFC^Requerido  ^Requerido  ^Texto  ^13^Registro federal de contribuyentes en el formato PFAE (xxxxaammdd), PM (xxxaammdd) La homoclave es opcional, si es reportada debe de ser de caracteres entre RFC y homoclave). De reportarse dcon homoclaves para Persona Física el RFC deberá tener 13 posiciones y para personas morales, 12.  El dato es opcional cuando se reporta a un acreditado con domicilio en el extranjero. Cuando se cuenta con el dato debe ser reportado, pues aplica para el proceso de búsqueda i integración de expedientes.^^^rfc^|01^Código de Ciudadano (CURP)^opcional^Opcional^Texto  ^18^Código único de registro poblacional (uso futuro).^^^^|02^Número Dun^opcional^Opcional^Numérico^10^Número asignado por Dun & Bradstreet (uso futuro).^^^^|03^Compaía^Requerido (Ver descripción)^Requerido (Ver descripción)^Texto  ^75^Racón social de la compañía. No aplica en caso de ser PFAE.^^^nombrei^|04^Nombre 1 ^Requerido (Ver descripción)^Requerido (Ver descripción)^Texto  ^75^Primer nombre de Persona Física. No aplica en caso de ser PM.^^^nombre1i^|05^Nombre 2 ^opcional^Opcional ^Texto  ^75^Segundo nombre de Persona Física.  No aplica en caso de ser PM.^^^nombre2i^|06^Apellido Paterno^Requerido  ^Requerido   ^Texto   ^25^Apellido Paterno de Persona fïsica No aplicada en caso de ser pm.^^^apaternoi^|07^Apellido Materno^Requerido^Requerido   ^Texto   ^25^Apellido Materno de Persona Física. No aplica en caso de ser PM. Cuando se trata de un hijo natural, el apellido materno se reporta como paterno y en este campo se debe integrar la leyenda NO PROPORCIONADO.^^^amaternoi^|08^Nacionalidad^Opcional^Opcional^Texto^2^El dato refiere a la nacionalidad del acreditado y la clave válida puede consultarse en el Catálogo Claves de País, en la sección de Catálogos al final de este documento.^pais^^^|09^Calificación de Cartera ^Requerido^Opcional^Texto^2^Financiero: Rechazo el registro cuando se reporta cualquier valor diferente al catálogo vigente. Comercial: Acepta blancos y rechaza el dato cuando se reporta cualquier valor diferente al catálogo vigente. Claves vigentes para Instituciones Financieras (Bancos): A1, A2, B1, B2, B3, C1, C2, D y E. EX (Cartera exceptuada) y NC (Cartera no calificada). Para Bancos Intervenidos o en liquidación: se acepta blanco o “NC”. Instituciones Comerciales no deben reportar esta información (enviar dos espacios en blanco).^cartera^^^|10^Banxico 1. ^Requerido^Requerido (ver descripción) ^Numérico^11^Catálogo de actividad económica de Banco de México en el anexo al final del documento. El código Banxico debe complementarse con cuatro ceros a la izquierda, pues la clave se compone de siete dígitos. Cuando no se tiene el dato: • Entidades Financieras reportar siete veces número nueve • Empresas Comerciales deben reportar siete veces ocho. Al reportar la clave incorrecta se rechaza el dato.^banxico^^^9999999|11^Banxico 2.^Opcional^No aplica ^Numérico^11^Catálogo de actividad económica de Banco de México en el anexo al final del documento. El código Banxico debe complementarse con cuatro ceros a la izquierda, pues la clave se compone de siete dígitos. Cuando no se tiene el dato: • Entidades Financieras reportar siete veces número nueve • Empresas Comerciales deben reportar siete veces ocho. Al reportar la clave incorrecta se rechaza el dato.^banxico^^^0|12^Banxico 3. ^Opcional^No aplica ^Numérico^11^Catálogo de actividad económica de Banco de México en el anexo al final del documento. El código Banxico debe complementarse con cuatro ceros a la izquierda, pues la clave se compone de siete dígitos. Cuando no se tiene el dato: • Entidades Financieras reportar siete veces número nueve • Empresas Comerciales deben reportar siete veces ocho. Al reportar la clave incorrecta se rechaza el dato.^banxico^^^0|13^Dirección 1. ^Requerido^Requerido^Texto^40^Calle y Número exterior.^^^calle^|14^ Dirección 2. ^Opcional^Opcional^Texto^40^Este campo se utilizará si el campo anterior excede la longitud.^^^interior^|15^Colonia/Población^Requerido^Requerido^Texto^60^Colonia o Población. Opcional en caso de que se trate de un domicilio en el extranjero.^^^colonia^|16^Delegación/Municipio^Requerido (ver descripción)^Requerido (ver descripción) ^Texto^40^Colonia o Población. Opcional en caso de que se trate de un domicilio en el extranjero.^^^delegacion^|17^Ciudad^Requerido (ver descripción)^Requerido (ver descripción) ^Texto^40^• En caso de NO reportar Delegación / Municipio: Ciudad es dato requerido. • En caso de NO reportar Ciudad: Delegación / Municipio es dato requerido. Para cada combinación, el dato reportado debe ser congruente contra Código Postal y Estado. Para domicilios en el extranjero los datos de Delegación / Municipio y Ciudad son requeridos, aun cuando no se verifica su congruencia contra Código Postal y Estado.^^^^|18^Estado para domicilios en México ^Requerido^Requerido^Texto^4^Catálogo de Estados. Ver abreviaturas en Anexo al final de este documento. ^estados^^estado^|19^Código Postal ^Requerido^Requerido^Texto^10^Para domicilios en México se validará que el dato corresponda al Estado reportado en el registro. En el caso de domicilios en el extranjero debe reportarse un dato válido pues aplica para el proceso de búsqueda e integración de expedientes.^^^cp^|20^Teléfono^Opcional^Opcional^Texto^11^El número de teléfono se debe ingresar sin caracteres especiales^^^^|21^Extensión^Opcional^Opcional^Texto^8^Numero de Extensión^^^^|22^Fax^Opcional^Opcional^Texto^11^El número de teléfono se debe ingresar sin caracteres especiales^^^^|23^Tipo de Cliente ^Requerido^Requerido^Numérico^1^1= Persona Moral. 2= Persona Física con Actividad Empresarial.^^^^2|24^Estado en el extranjero^Requerido^Requerido^Texto^40^Esta etiqueta aplica únicamente cuando se reporta un Acreditado con domicilio en el extranjero (ver etiqueta 25, segmento EM). La etiqueta es tipo texto para reportar el nombre completo del estado, provincia, distrito o población donde está el domicilio y que se asuma como equivalente al dato de Estado.^^^^|25^País de origen del domicilio^Requerido^Requerido^Texto^2^El valor predefinido es MX; aun cuando se omita reportar el dato, el sistema lo interpreta como domicilio en México. Para reportar un domicilio en el extranjero debe seleccionarse la clave correspondiente de acuerdo con el Catálogo de País, en el anexo Catálogos al final de este documento.^pais^^^MX|26^Filler^Requerido^Requerido^Texto^82^ Espacio reservado para uso futuro.^^^^";
	var $ac="AC^dentificador de Segmento^Requerido^Requerido^Texto^2^Debe contener AC^^^^AC|0^RFC^Opcional^Opcional^Texto^13^RFC del accionista cuando se reporta debe cumplir las siguientes condiciones: PFAE (LLLLAAMMDD) PM (LLLAAMMDD) La homoclave es opcional; debe ser de tres posiciones y sin caracteres entre RFC y homoclave, (ej. Guiones). El registro se rechazará si el dato de RFC es igual al del acreditado. El dato es opcional cuando se reporta a un accionista con domicilio en el extranjero. Cuando se cuenta con el dato debe ser reportado, pues aplica para el proceso de búsqueda e integración de expedientes. ^^^rfc^|1^Código de Ciudadano (CURP)^Opcional^Opcional^Texto^18^Código único de registro poblacional (uso futuro).^^^curp^|2^Número Dun^Opcional^Opcional^Numérico^10^Número asignado por Dun & Bradstreet. Fuera de uso.^^^^|3^Nombre de la Cía. Accionista^Requerido^Requerido^Texto^75^ No aplica en PFAE^^^nombre1a^|4^ Nombre 1 ^Requerido^Requerido^Texto^75^Primer nombre del Accionista si es PFAE.Una vez que se reporta PFAE, el resto de los campos referentes al nombre se hacen requeridos. ^^^nombre1a^|5^ Nombre 2 ^Opcional^Opcional^Texto^75^Segundo Nombre del Accionista^^^nombre2a^|6^Apellido Paterno^Requerido^Requerido^Texto^25^Apellido Paterno del Accionista si es Persona Física. No aplica en caso de PM. ^^^apaternoa^|7^ Apellido Materno^Requerido^Requerido^Texto^25^Apellido Materno del accionista No aplica en caso de PM. Cuando se trata de un hijo natural, el apellido materno se reporta como paterno y en esta campo se debe integrar la leyenda NO PROPORCIONADO.^^^amaternoa^|08^Porcentaje^Requerido^Requerido^Numérico^2^Porcentaje de acciones.^^^porcentaje^|09^ Dirección 1^Opcional^Opcional^Texto^40^Porcentaje de acciones.^^^direcciona^|10^ Dirección 2^Opcional^Opcional^Texto^40^Cuando se reporta una dirección, todos los campos relacionados se validan como requeridos, La longitud es de 40 posiciones. En caso de excederse podrá utilizar la etiqueta Dirección 2 para complementar el dato.^^^^|11^Colonia/Población^Opcional^Opcional^Texto^60^Colonia o Población Opcional en caso de que se trate de un domicilio en el extranjero. ^^^coloniaa^|12^ Delegación/Munic ipio^Opcional y/o Requerido^Opcional y/o Requerido^Texto^40^Colonia o Población Opcional en caso de que se trate de un domicilio en el extranjero. ^^^delegaciona^|13^Ciudad^ Opcional y/o Requerido^Opcional y/o Requerido^Texto^40^• En caso de NO reportar Delegación / Municipio: Ciudad es dato requerido. • En caso de NO reportar Ciudad: Delegación / Municipio es dato requerido. Para cada combinación, el dato reportado debe ser congruente contra Código Postal y Estado. Para domicilios en el extranjero los datos de Delegación / Municipio y Ciudad son requeridos, aun cuando no se verifica su congruencia contra Código Postal y Estado.^^^^|14^Estado para domicilios en México^Opcional^Opcional^Texto^4^Catálogo de Estados. Ver abreviaturas en Anexo al final de este documento ^estados^^estadoa^|15^ Código Postal ^Opcional^Opcional^Texto^10^Para domicilios en México se validará que el dato corresponda al Estado reportado en el registro. Para domicilios en el extranjero debe reportarse un dato válido pues se compara integro en la búsqueda de expedientes.^^^cpa^|16^Teléfono^Opcional^Opcional^Texto^11^ Número de Teléfono sin caracteres especiales^^^^|17^Extensión^Opcional^Opcional^Texto^8^ Número de Extensión^^^^|18^Fax^Opcional^Opcional^Texto^11^ Número de Fax sin caracteres especiales^^^^|19^ Tipo de Cliente ^Requerido^Requerido^Numérico^1^1= Persona Moral 2= Persona Física^^^^|20^ Estado en el extranjero^Opcional^Opcional^Texto^40^Esta etiqueta aplica únicamente cuando se reporta un Acreditado con domicilio en el extranjero (ver etiqueta 25, segmento EM). Debe reportarse el nombre completo del estado, provincia, distrito o población donde está el domicilio.^^^^|21^ País de origen del domicilio^Opcional^Opcional^Texto^2^El valor predefinido es MX; cuando se omite el dato, el sistema lo interpreta como domicilio en México. Para reportar un domicilio en el extranjero debe seleccionarse la clave correspondiente de acuerdo con el Catálogo de País, en el anexo Catálogos al final de este documento.^pais^^paisa^MX|22^Filler^Requerido^Requerido^Texto^25^ Espacio reservado para uso futuro.^^^^";
	var $cr="CR^ Identificador de Segmento^Requerido^Requerido^Texto^2^Debe contener CR^^^^CR|0^RFC^Requerido^Requerido^Texto^13^Registro Federal de Contribuyentes en el formato: PFAE (XXXXAAMMDD) PM (XXXAAMMDD) La homoclave es opcional, si es reportada debe ser de tres posiciones, no reportar caracteres entre el RFC y homoclave, (Ej.: guiones). De reportarse con homoclave para Persona Física el RFC deberá tener 13 posiciones y para Persona Moral, 12. El RFC debe ser igual al reportado en el segmento de Compañía (EM) y Detalle de Crédito (DE). El dato es opcional cuando se reporta a un acreditado con domicilio en el extranjero. Cuando se cuenta con el dato debe ser reportado, pues aplica para el proceso de búsqueda e integración de expedientes. ^^^rfc^|1^Número de Experiencias Crediticias^No aplica^Requerido^Numérico^6^Número de Experiencias Crediticias. Obligatorio si el proveedor es comercial. Se refiere al número de Facturas efectuadas en el mes. ^^^^1|2^ Número de Contrato^Requerido^ No aplica ^Texto^25^Número de Contrato con el que se identifica como único el crédito reportado. Sólo en caso de ser Entidad Financiera, debe ser igual al dato reportado en el segmento de Detalle del Crédito (DE). En caso de modificar el número de contrato se deberá notificar al área de Adquisición y Calidad de Bases de Datos. ^^^idcontrato^|3^ Número de Contrato Anterior ^Opcional^No aplica ^Texto^25^ Deberá reportarse en caso de reestructura.^^^^|4^ Fecha de apertura^Requerido^ No aplica ^F1^8^Fecha de apertura del crédito. En el formato DDMMAAAA. Una vez reportado un valor, éste no debe modificarse en subsecuentes actualizaciones cuando se trate del mismo crédito.^^^fechainicio^|5^Plazo^Requerido^No aplica^Numérico^5^Término en el que se pactó el crédito y se reporta en días. Una vez reportado un valor, éste no debe modificarse en subsecuentes actualizaciones. Para créditos activos y con saldo a pagar, el dato debe ser mayor a cero; únicamente se acepta cero para contratos de Tarjeta de Crédito Empresarial (1380), Tarjeta de Servicio (6250) y Línea de Crédito (6280).^^^diast^|6^Tipo de crédito^Requerido^  No aplica ^Numérico^4^Catálogos de Tipo de Créditos. Ver catálogo en Anexo del manual. Una vez reportado un valor, éste no debe modificarse en subsecuentes actualizaciones cuando se trate del mismo crédito. ^tipo_credito^^^1300|7^ Saldo inicial^Requerido^No aplica^Numérico^20^Saldo inicial con el que se pacto el crédito. Siempre debe ser mayor a cero, pues identifica la línea original del préstamo otorgado. Una vez reportado un valor, éste no debe modificarse en subsecuentes actualizaciones. Únicamente se acepta cero para el tipo de contrato: Tarjeta de Crédito Empresarial (1380) y Tarjeta de Servicio (6250). ^^^^0|8^Moneda^Requerido^Requerido^Numérico^3^Catálogo de Tipos de Moneda. Ver Anexo del manual. Una vez reportado un valor, éste no debe modificarse en subsecuentes actualizaciones cuando se trate del mismo crédito.^moneda^^^1|9^ Número de Pagos ^Requerido^ No aplica ^Numérico^4^Número de pagos determinado por el Otorgante para liquidar el crédito. A partir de agosto 2008 el campo se valida como filtro warning para créditos sin fecha de cierre; el Usuario debe considerar el envío del dato por cada crédito. Cuando la validación se modifique a rechazo, el crédito no podrá integrarse a la base de datos. ^^^^|10^Frecuencia de Pagos^Requerido^ No aplica^Numérico^3^Frecuencia con la que se efectúan los pagos. Reportado en días. A partir de agosto 2008 el campo se valida como filtro warning para créditos sin fecha de cierre; el Usuario debe considerar el envío del dato por cada crédito. Cuando la validación se modifique a rechazo, el crédito no podrá integrarse a la base de datos. ^^^^|11^ Importe de Pagos ^Requerido^ No aplica ^Numérico^20^Importe de pagos pactados al periodo reportado. A partir de agosto 2008 el campo se valida como filtro warning para créditos sin fecha de cierre; el Usuario debe considerar el envío del dato por cada crédito. Cuando la validación se modifique a rechazo, el crédito no podrá integrarse a la base de datos.^^^^|12^ Fecha de último pago^Requerido^No aplica^Numérico^8^Última fecha en que se efectuó un pago. En el formato DDMMAAAA. A partir de agosto 2008 el campo se valida como filtro warning para créditos sin fecha de cierre; el Usuario debe considerar el envío del dato por cada crédito. Cuando la validación se modifique a rechazo, el crédito no podrá integrarse a la base de datos. ^^^^|13^Fecha De Reestructura^Opcional^ No aplica ^Numérico^8^En casó de que el crédito haya sido reestructurado En el formato DDMMAAAA. ^^^^|14^ Pago en efectivo ^Opcional^No aplica ^Numérico^20^ Pago efectuado al cierre del crédito, en caso de que exista morosidad.^^^^|15^Fecha de Liquidación^Opcional^ No aplica ^Numérico^8^En este campo se determina que el crédito reportado ha sido cerrado. En el formato DDMMAAAA.^^^^|16^Quita^Opcional^ No aplica ^Numérico^20^Monto de la Quita^^^^|17^ Dación de Pago^Opcional^ No aplica ^Numérico^20^Monto de la Dación^^^^|18^ Quebranto o Castigo^Opcional^ No aplica ^Numérico^20^Monto del Quebranto ocasionado^^^^|19^Clave de Observación^Opcional^ No aplica^Texto^4^Refiere al listado de Claves de Observación. El detalle de la validación de estas claves debe consultarse en el apartado “Filtros para validar la Calidad de la Información” (p. 25). ^^^^|20^Especiales^Opcional^ No aplica ^Texto^1^Debe tener F para marcar a los créditos especiales. Solo aplica para instituciones financieras ^^^^|21^Filler^Requerido^Requerido^Texto^107^Espacio Reservado para uso futuro^^^^";
	var $de="DE^Identificador de Segmento^Requerido^Requerido^Texto^2^Debe contener DE^^^^DE|0^RFC^Requerido^Requerido^Texto^13^Registro Federal de Contribuyentes en el formato: PFAE (XXXXAAMMDD) PM (XXXAAMMDD) La homoclave es opcional, al reportarse debe contener tres posiciones y estra seguida del RFC. El RFC debe ser igual al reportado en el segmento de Compañía (EM) y Crédito (CR). El dato es opcional cuando se reporta a un acreditado con domicilio en el extranjero. Cuando se cuenta con el dato debe ser reportado, pues aplica para el proceso de búsqueda e integración de expedientes.  ^^^rfc^|1^ Número de Contrato^Requerido^ No aplica^Texto^25^Número de Contrato con el que se identifica como único el crédito reportado, sólo en caso de ser institución financiera. Debe ser igual al dato reportado en el segmento de Crédito (CR) ^^^idcontrato^|2^ Días de Vencimiento ^Requerido^Requerido^Numérico^3^Máximo periodo de vencimiento. Deberá reportarse en días. Se reporta cero si está al corriente.^^^diasv^|3^Cantidad^Requerido^Requerido^Numérico^20^Saldo (Cantidad más intereses) del Vigente o vencido. Cuando la cantidad es cero, se valida que días de vencimiento sea igual a cero y tenga fecha de cierre. Las únicas excepciones de créditos sin fecha de cierre que se aceptan con cantidad en cero son: Tarjeta de Crédito Empresarial (1380) Tarjeta de Servicio (6250) Línea de Crédito (6280). Cuando la cantidad es mayor a cero, se valida que se reporten datos en número de pagos (seg. CR, etiq. 09), frecuencia de pago (seg. CR, etiq. 10), importe de pago (seg. CR, etiq. 11) y fecha de último pago (seg. CR, etiq. 12). No se reportan saldos a favor del cliente. ^^^total^|4^Filler^Requerido^Requerido^Texto^75^Espacio- Reservado para uso futuro^^^^";
	var $av="AV^ Identificador de Segmento^Requerido^Requerido^Texto^2^Debe contener AV^^^^AV|0^RFC^Requerido^Requerido^Texto^13^RFC del aval: PFAE (LLLLAAMMDD) PM (LLLAAMMDD) La homoclave es opcional, si es reportada debe ser de tres posiciones, no reportar caracteres entre el RFC y homoclave, (Ej.: guiones). De reportarse con homoclave para Persona Física el RFC deberá tener 13 posiciones y para Persona Moral, 12. El registro se rechazará si el dato de RFC es igual al del acreditado, sea la Compañía o Persona Física reportada. El dato es opcional cuando se reporta a un acreditado con domicilio en el extranjero. Cuando se cuenta con el dato debe ser reportado, pues aplica para el proceso de búsqueda e integración de expedientes. ^^^rfc^|1^Código de Ciudadano (CURP)^Opcional^Opcional^Texto^18^Código único de registro poblacional (uso futuro). ^^^^|2^ Número Dun ^Opcional^Opcional^Numérico^10^Número asignado por D&B.^^^^|3^ Nombre de la Cia.(Aval)^Requerido (Ver descripción)^Requerido^Texto^75^ Aval válido sólo para PM. No aplica en PFAE.^^^nombre1o^|4^ Nombre 1 ^Requerido^Requerido^Texto^75^Primer Nombre de Aval para PFAE. Una vez que se reporta PFAE, el resto de los campos referentes al nombre se hacen requeridos. ^^^nombre1o^|5^ Nombre 2 ^Opcional^Opcional^Texto^75^ Segundo Nombre del Aval.^^^nombre2o^|6^Apellido Paterno^Requerido^Requerido^Texto^25^ Apellido Paterno del Aval se es PFAE.^^^apaternoo^|7^Apellido Materno ^Requerido^Requerido^Texto^25^Apellido Materno del Aval. Cuando se trata de un hijo natural, el apellido materno se reporta como paterno y en este campo se debe integrar la leyenda NO PROPORCIONADO. ^^^amaternoo^|8^Dirección 1^Requerido^Requerido^Texto^40^ Calle y Número^^^direccion^|9^ Dirección 2 ^Opcional^Opcional^Texto^40^Este campo se utilizará si el campo anterior excede la longitud o tiene reportada otra dirección. ^^^^|10^ Colonia / Población^Requerido^Requerido^Texto^60^Colonia o Población. Opcional en caso de que se trate de un domicilio en el extranjero. ^^^colonia^|11^Delegación/Municipio^Opcional y/o Requerido^Opcional y/o Requerido^Texto^40^• En caso de NO reportar Delegación / Municipio: Ciudad es dato requerido. • En caso de NO reportar Ciudad: Delegación / Municipio es dato requerido. Para cada combinación, el dato reportado debe ser congruente contra Código Postal y Estado. Para domicilios en el extranjero los datos de Delegación / Municipio y Ciudad son requeridos, aun cuando no se verifica su congruencia contra Código Postal y Estado. ^^^delegacion^|12^Ciudad^ Opcional y/o Requerido^Opcional y/o Requerido^Texto^40^• En caso de NO reportar Delegación / Municipio: Ciudad es dato requerido. • En caso de NO reportar Ciudad: Delegación / Municipio es dato requerido. Para cada combinación, el dato reportado debe ser congruente contra Código Postal y Estado. Para domicilios en el extranjero los datos de Delegación / Municipio y Ciudad son requeridos, aun cuando no se verifica su congruencia contra Código Postal y Estado. ^^^^|13^Estado para domicilios en México^Requerido^Requerido^Texto^4^Catálogo de Estados ver catálogo en Anexo de este manual.^estados^^^df|14^Código Postal ^Requerido^Requerido^Texto^10^Validará que corresponda al Estado. En el caso de domicilios en el extranjero debe reportarse un dato válido pues aplica para el proceso de búsqueda e integración de expedientes.^^^cp^|15^Teléfono^Opcional^Opcional^Texto^11^Número de Teléfono de 5 a 11 posiciones sin caracteres especiales ^^^^|16^Extensión^Opcional^Opcional^Texto^8^ Número de Extensión^^^^|17^Fax^Opcional^Opcional^Texto^11^Número de Fax de 5 a 11 posiciones sin caracteres especiales ^^^^|18^ Tipo de cliente^Requerido^Requerido^Numérico^1^1= Persona Moral 2= Persona Física^^^^2|19^ Estado en el extranjero ^Requerido^Requerido^Texto^40^Esta etiqueta aplica únicamente cuando se reporta un Acreditado con domicilio en el extranjero (ver etiqueta 25, segmento EM). La etiqueta es tipo texto para reportar el nombre completo del estado, provincia, distrito o población donde está el domicilio y que se asuma como equivalente al dato de Estado. ^^^^|20^País de origen del domicilio^Requerido^Requerido^Texto^2^El valor predefinido es MX; aun cuando se omita reportar el dato, el sistema lo interpreta como domicilio en México. Para reportar un domicilio en el extranjero debe seleccionarse la clave correspondiente de acuerdo con el Catálogo de País, en el anexo Catálogos al final de este documento. ^pais^^^MX|21^Filler^Requerido^Requerido^Texto^79^ Espacio reservado para uso futuro.^^^^";
	var $ts="TS^ Identificador de segmento^Requerido^Requerido^Texto^2^ Debe tener TS.^^^^TS|0^ Numero de compañías^Requerido^Requerido^Numérico^7^ Total de compañías reportadas en la cinta.^^^ninquilinos^|1^Cantidad del Segmento Detalle de Crédito^Requerido^Requerido^Numérico^30^Es la suma total del campo “cantidad” del segmento detalle de crédito de la cinta. ^^^srentas^|2^Filler^Requerido^Requerido^Texto^53^ Espacio reservado para uso futuro.^^^^";
	var $institucion="001^Banco^Banco|002^Arrendadora^Arrendadora|003^Unión de Crédito^Unión de C rédito|004^Factoraj^Factoraje|005^Otras Financieras^Otras financieras|007^Almacenadoras^Almacenadoras|008^Fondos y Fideicomisos^Fondos y Fideicomisos|009^Seguros^Seguros|010^Fianzas^Fianzas|011^Caja de Ahorro^Caja de Ahorro|012^Cobierno^Gobierno|999^Comercial^Comercial";
	var $estados="Aguascalientes^AGS|Baja California Norte^BCN|Baja California Sur ^BCS|Campeche^CAM|Chiapas^CHS|Chihuahua^CHI|Coahuila^COA|Colima^COL|Distrito Federal ^DF|Durango^DGO|Estado de México^EM|Guanajuato^GTO|Guerrero^GRO|Hidalgo^HGO|Jalisco^JAL|Michoacán^MICH|Morelos^MOR|Nayarit^NAY|Nuevo León^NL|Oaxaca^OAX|Puebla^PUE|Querétaro^QRO|Quintana Roo^QR|San Luis Potosí ^SLP|Sinaloa^SIN|Sonora^SON|Tabasco^TAB|Tamaulipas^TAM|Tlaxcala^TLAX|Veracruz^VER|Yucatán^YUC|Zacatecas^ZAC";
	var $cartera="A1^Desempeño de pago sólido. Elementos cuantitativos del deudor y rentabilidad sólidos, con flujos de efectivo operativo y proyectado, suficientes para cubrir las obligaciones de deuda. |A2^Desempeño de pago sobresaliente. Elementos cuantitativos del deudor son sobresalientes, con razones de liquidez sólidas. Flujo de efectivo operativo y proyectado, adecuados. |B1^Desempeño de pago bueno con riesgo aceptable. Elementos cuantitativos del deudor buenos, con liquidez positiva y rentabilidad sólida. Flujo de efectivo operativo en punto de equilibrio y apalancamiento adecuado.|B2^Desempeño de pago satisfactorio. Elementos cuantitativos del deudor satisfactorios, rentabilidad adecuada, flujo de efectivo operativo en punto de equilibrio y apalancamiento promedio a la industria. |B3^Desempeño de pago adecuado. Elementos cuantitativos del deudor adecuados con ciertas debilidades. Liquidez y rentabilidad adecuada, flujo de efectivo operativo en punto de equilibrio, con apalancamiento por encima del promedio de la industria. |C1^Desempeño de pago débil. Elementos cuantitativos del deudor débiles, con problemas en flujo de efectivo, liquidez, apalancamiento y/o rentabilidad. Requiere de fuentes secundarias de efectivo. |C2^Desempeño de pago insatisfactorio. Elementos cuantitativos del deudor pobres, con débil flujo de efectivo, liquidez, apalancamiento y/o rentabilidad. Clara dependencia de fuentes secundarias de reembolso para prevenir un incumplimiento. |D^Desempeño de pago insatisfactorio. Elementos cuantitativos del deudor insatisfactorios con debilidades en flujo de efectivo, liquidez, apalancamiento, y/o rentabilidad. Ya cayó en incumplimiento de pago o está en proceso de dejar de pagar. |E^Desempeño de pago insatisfactorio. No existen elementos cuantitativos del deudor. Dejó de pagar y no tiene ninguna capacidad de afrontar sus obligaciones contractuales de deuda. |EX ^Cartera Exceptuada |NC^Cartera No Calificada.";
	var $creditos="1300^ Cartera de Arrendamiento Puro y Créditos ^ARREN PURO|1301^Descuentos^ DESCUENTOS|1302^Quirografario^QUIROG|1303^ Con Colateral ^COLATERAL|1304^Prendario^PRENDAR|1305^ Créditos simples y créditos en cuenta corriente^SIMPLE|1306^ Prestamos con garantía de unidades industriales ^P.G.U.I.|1307^ Créditos de habilitación o avío ^HABILITACION|1308^ Créditos Refaccionarios ^REFACC|1309^ Prestamos Inmobil Emp Prod de Bienes o Servicios^ I.E.P.B.S.|1310^ Prestamos para la vivienda ^VIVIENDA|1311^ Otros créditos con garantía inmobiliaria^ O.C. GARANTIA INMOB|1314^ No Disponible^ NO DISPONIBLE|1316^ Otros adeudos vencidos ^O.A.V.|1317^ Créditos venidos a menos aseg. Gtias. Adicionales^ C.V.A.|1320^ Cartera de Arrendamiento Financiero Vigente ^ARREN VIGENTE|1321^ Cartera de Arrendamiento Financiero Sindicado con Aportación ^ARREN SINDICADO|1322^ Crédito de Arrendamiento ^ARREND|1323^ Créditos Reestructurados^ REESTRUCTURADOS|1324^ Créditos Renovados ^RENOVADOS|1327^ Arrendamiento Financiero Sindicado ^ARR. FINAN. SINDICADO|1340^ Cartera descontada con Inst. de Crédito^ REDESCUENTO|1341^ Redescuento otra cartera descontada ^O. REDESCUENTO|1342^ Redescuento, cartera de crédito reestructurado mediante su descuento en programas Fidec. ^RED. REESTRUCTURADOS|1350^ Prestamos con Fideicomisos de Garantía^ PRESTAMOS C/FIDEICOMISOS GARANTÍA|1380^ Tarjeta de Crédito empresarial / Tarjeta Corporativa^ T. CRED. EMPRESARIAL- CORPORATIVA|2303^ Cartas de Crédito^ CARTAS DE CREDITO|3011^ Cartera de Factoraje con Recursos ^FACTORAJE C/REC|3012^ Cartera de Factoraje sin Recursos^ FACTORAJE S/REC|3230^ Anticipo a Clientes Por Promesa de Factoraje^ ANT.A.C.P.P.FACTORAJE|3231^ Cartera de Arrendamiento Financiero Vigente ^ARREN VIGENTE|6103^ Adeudos por Aval ^ADEUDOS POR AVAL|6105^ Cartas de Créditos No Dispuestas^ CARTAS DE CRÉDITOS NO DISPUESTAS|6228^ Fideicomisos Programa de apoyo crediticio a la planta productiva Nacional en Udis ^FIDEICOMISOS PLANTA PRODUCTIVA|6229^ Fideicomisos Programa de apoyo crediticio a los Estados y Municipios ^UDIS FIDEICOMISOS EDOS|6230^ Fideicomisos Programa de apoyo para deudores de créditos de Vivienda^UDIS FIDEICOMISOS VIVIENDA|6240^ Aba Pasem II ^ABA PASEM II|6250^ Tarjeta de Servicio ^TARJETA DE SERVICIO|6260^ Crédito Fiscal ^CRÉDITO FISCAL|6270^ Crédito Automotriz ^CRÉDITO AUTOMOTRIZ|6280^ Línea de Crédito^ LÍNEA DE CRÉDITO|6290^Seguros^SEGUROS|6290^Seguros^SEGUROS|6291^Fianzas^FIANZAS|6292^ Fondos y Fideicomisos^ FONDOS Y FIDEICOMISOS";
	var $moneda="MONEDA NACIONAL / PESO MEXICANO ^1|CORONA SUECA ^2|UNIDAD DE INVERSION^3|DOLAR CANADIENSE ^4|DOLAR AMERICANO ^5|ESCUDO PORTUGUES ^6|FRANCO FRANCES ^7|FRANCO SUIZO^8|LIBRA ESTERLINA ^9|LIRA ITALIANA ^10|MARCO ALEMAN ^11|ORO ^13|PLATA ^14|RIAL CAMBOYA ^15|PESO ARGENTINO ^16|QUETZAL GUATEMALA ^17|YEN JAPONES ^18|RENIMIMBI CHINA 0^19|FLORIN HOLANDES ^20|SIN MONEDA ^21|COLON SALVADOREÑO ^22| DOLAR HONG KONG ^23|DOLAR BELICE ^24|FRANCO BELGA ^25|PESETA ESPAÑOLA ^27|REAL BRASILEÑO ^28|CORONA DANESA ^29|BAHT DE TAILANDIA ^30|PESO CHILENO^33|PESO URUGUAYO ^34|LIBRA ISRAELI ^35|WONG DE COREA ^36|FLORIN DE ARUBA ^37|BOLIVAR DE VENEZUELA ^39|CORDOVA DE NICARAGUA ^40|SOL PERUANO ^42|PESO COLOMBIANO ^43|PESO DOMINICANO ^44|LEMPIRA HONDUREÑA ^45|COLON COSTARRICENSE^48|CHELIN AUSTRIACO ^49|RINGGIT MALASIA ^52|BALBOA PANAMEÑA^53|CORONA NORUEGA^54| DOLAR SINGAPUR ^55|PESO FILIPINO^56|DOLAR NEOZOLANDES ^58|DOLAR BERMUDAS ^59|GOURDE DE HAITI ^61|PESO BOLIVIANO^62|DIRHAM DE MARRUECOS ^63|GUARANI PARAGUAYO ^65|FLORIN ANT HOLANDESAS^66|DOLAR BAHAMAS ^68|PUNT IRLANDES ^69|DOLAR FIJI ^70|RUPIA INDIONESA ^72| DOLAR GUYANA^73|DOLAR AUSTRALIANO ^74|FRANCO POLINESIA FRANCESA ^75|RAND SUDAFRICANO ^81|DINAR BAHRAIN ^84|DINAR DE LIBIA ^85|LIRA TURQUIA^86|DOLAR TAIWANES ^87|DINAR KUWAITI ^88|RIYAL DE QATAR ^89|FLORIN HUNGARO ^90|DINAR TUNES^91|MARCO FINLANDES ^92|LEVA DE BULGARIA^93|DRACHA GRIEGO ^95|FRANCO DE LUXEMBURGO^96|LIRA LIBANESA ^97|LIBRA CHIPRIOTA ^98|EURO - UE^100|RUBLO RUSO ^101|PESETA ANDORRA ^102|DIRHAM DE EMIRATOS ^103|AFGHANI DE AFGANISTAN^104|LEK DE ALBANIA ^105|DRAM DE ARMENIA ^106| KWANZA DE ANGOLA ^107|MANAT DE AZERBAYAN^108|MARCOS CONVER DE BOSNIA^109|DOLAR DE BARBADOS ^110|TAKA DE BANGLADESH ^111|FRANCO DE BURUNDI^112|DOLAR DE BRUNEI ^113|MONEDA NAL BOLIVIANA ^114|NGULTRUM DE BUTHAN ^115|PULA DE BOTSWANA ^116|RUBLO BIELORUSO ^117|FRANCO CONGOLES ^118|UNIDADES CHILENAS ^119|PESO CUBANO ^120| ESCUDO DE CABO VERDE ^121|CORONA CHECA ^122| FRANCO DE DJIBOUTI ^123|DINAR ARGELINO ^124|CORONA DE ESTONIA ^125|LIBRA EGIPCIA ^126|NAFKA DE ERITREA ^127|BIRR ETIOPE ^128|LIBRA DE LAS ISLAS MA ^129|LARI DE GEORGIA ^130|CEDI DE GHANA ^131|LIBRA DE GIBRALTAR ^132|DALASI DE GAMBIA ^133|FRANCO DE GUINEA ^134|PESO DE GUINEA BISSAU^135| KUNA CROATA ^136|RUPIA INDIA^137|DINAR IRAQUI ^138|REAL IRANI ^139|CORONA ISLANDESA ^140|DOLAR JAMAIQUINO ^141|DINAR JORDANO^142|CHELIN KENIANO ^143|SOM DE KYRGYZTAN ^144|FRANCO DE COMOROS ^145|WONG NORCOREANO ^146|DOLAR ISALAS CAIMAN^147|TENGE DE KAZAKSTAN ^148|KIP DE LAOS^149|RUPIA DE SRI LANKA ^150|DOLAR DE LIBERIA ^151|LOTI DE LESOTHO ^152|LITUS DE LITUANIA ^153|LATS DE LETONIA ^154|LEU DE MOLDOVA ^155|FRANCO MALGACHE ^156|DENAR DE MACEDONIA ^157|KYAT DE MYANMAR ^158|TUGRIK DE MONGOLIA^159|PATACA DE MACAO ^160|OUGUIYA MAURITANIA ^161|LIRA MALTESA ^162|RUPIA DE MAURICIO ^163|RUFIYA DE MALDIVAS ^164| KWACHA DE MALAWI ^165|METICAL DE MOZAMBIQUE ^166|DOLAR DE NAMIBIA ^167|NAIRA DE NIGERIA^168|RUPIA NEPALESA ^169|RIAL OMANI ^170| KINA DE PAPUA NUEVA GUINEA^171|RUPIA PAKISTAN^172|ZLOTY DE POLONIA ^173|LEU RUMANO ^174|FRANCO DE RUANDA ^175|RIAL DE ARABIA ^176|DOLAR DE LAS ISLAS SALOMON ^177|RUPIA DE SEYCHELES^178|DINAR SUDANES ^179|LIBRA DE SANTA ELENA ^180|TOLAR DE ESLOVENIA ^181|CORONA ESLOVACA ^182|LEONA DE SIERRA LEONA ^183|CHELIN SOMALI ^184|FLORIN DE SURINAM ^185|DOBLA STO TOMÁS Y PRÍNCIPE ^186|LIBRA SIRIA ^187|LILANGENI DE SUAZILANDIA ^188| SOMONI DE TAJIKISTAN ^189|MANAT DE TURKMENISTAN^190|PAANGA DE TONGA ^191|ESCUDO DE TIMOR ^192|DOLAR TRINIDAD Y TOBAGO ^193|CHELIN DE TANZANIA^194|HRYVNIA DE UCRANIA ^195|CHELIN DE UGANDA^196|SUM DE UZBEKISTAN ^197|DONG DE VIETNAM^198|VATU DE VANATU ^199|TALA DE SAMOA ^200| FRANCO COL FRANCESAS ^201|UNIDAD COMPUESTA (EURCO) ^202|U. MONETARIA (E.M.U.) ^203|U. CONTABILIDAD EUROPEA 9 ^204|U. CONTABILIDAD EUROPEA 17 ^205| DOLAR DEL CARIBE ORIENTAL^206|DERECHOS ESPECIALES GIROFMI^207|FRANCO ORO ^208|UNION INTER FERROVIARIA ^209|FRANCESAS AFRICA OCCIDENTAL^210|PALADIO^211|PLATINO ^212|CLAVE DE PRUEBAS ^213|RIAL YEMENI ^214|DINAR YUGOSLAVO ^215|KWACHA DE ZAMBIA ^216|DOLAR DE ZIMABWE^217";
	var $pais="ALBANIA AL GUINEA-BISSAU ^GW |ALEMANIA^DE|ANDORRA^AD|ANGUILA^AI|ARABIA SAUDITA ^SA |ARGELIA^DZ|ARGENTINA^AR|ARMENIA^AM|AUSTRALIA^AU|AUSTRIA^AT|AZERBAIYÁN^AZ|BAHRÁIN^BH|BANGLADESH^BD|BARBADOS^BB|BÉLGICA^BE|BERMUDAS^BM|BIELORRUSIA^BY|BOSNIA Y HERCEGOVINA^BA|BRASIL^BR|BRUNÉI^BN|BULGARIA^BG|CABO VERDE ^CV|CAMBOYA^KH|CANADÁ ^CA|CHEQUIA^CZ|CHILE^CL|CHINA^ CN |CHIPRE^ CY |CIUDAD DEL VATICANO ^VA |COREA DEL SUR ^KR |COSTA RICA ^CR |CROACIA ^HR |CUBA^ CU |DINAMARCA ^DK |ECUADOR ^EC |EGIPTO^ EG |EL SALVADOR ^SV |EMIRATOS ÁRABES UNIDOS^ AE |ESLOVAQUIA ^SK|ESLOVENIA ^SI |ESPAÑA ^ES |ESTADOS UNIDOS ^US |ESTONIA^ EE|FILIPINAS ^PH|FINLANDIA ^FI |FRANCIA^ FR |GEORGIA ^GE |GIBRALTAR ^GI |GRECIA ^GR |GROENLANDIA^ GL |GUADALUPE ^GP |GUAM ^GU |GUATEMALA ^GT |GUAYANA FRANCESA ^GF |GUINEA ^GN |GUYANA^GY|HAITÍ HT MONGOLIA^ MN|HOLANDA^NL|HONDURAS ^HN |HUNGRÍA^ HU |INDIA^IN|INDONESIA^ID|IRÁN^IR|IRAQ^IQ|IRLANDA^IE|ISLA BOUVET ^BV|ISLA CHRISTMAS^CX|ISLA NORFOLK ^NF|ISLANDIA^IS|ISLAS ALAND^AX|ISLAS CAIMÁN ^KY|ISLAS COCOS ^CC|ISLAS COOK^CK|ISLAS FEROE ^FO|ISLAS GEORGIA DEL SUR Y SANDWICH DEL SUR^GS|ISLAS HEARD Y MCDONALD HM PAQUISTÁN ^PK|ISLAS MALVINAS FK PARAGUAY ^PY|ISLAS MARIANAS DEL NORTE ^MP|ISLAS MARSHALL ^MH|ISLAS MENORES ALEJADAS DE LOSESTADOS UNIDOS^UM |ISLAS PITCAIRN PN POLONIA ^PL|ISLAS TURCAS Y CAICOS TC PORTUGAL ^PT|ISLAS VÍRGENES AMERICANAS VI PUERTO RICO ^PR|ISLAS VÍRGENES BRITÁNICAS ^VG |ISRAEL IL REUNIÓN ^RE|ITALIA^ IT |JAPÓN^ JP |JORDANIA^ JO|KAZAJSTÁN ^KZ |KENIA^ KE |KIRGUIZISTÁN^ KG |KUWAIT KW SANTA HELENA ^SH|LAOS ^LA |LESOTO ^LS |LETONIA ^LV |LÍBANO ^LB |LIBERIA ^LR|LIECHTENSTEIN ^LI |LITUANIA ^LT |LUXEMBURGO ^LU |MACAO ^MO|MACEDONIA ^MK |MADAGASCAR ^MG |MALASIA MY SUIZA ^CH|MALDIVAS ^MV |MALTA ^MT |MARRUECOS ^MA |MARTINICA ^MQ |MAYOTTE ^YT |MÉXICO^MX|MICRONESIA^ FM|MOLDAVIA^MD|MÓNACO^MC|MONTSERRAT^MS|MOZAMBIQUE^ MZ|MYANMAR^MM|NEPAL^NP|NICARAGUA^NI|NÍGER^NE|NIGERIA^NG|NIUE^NU|NORUEGA^NO|NUEVA CALEDONIA ^NC|NUEVA ZELANDA ^NZ|OMÁN^ OM|PALAOS^ PW|PALESTINA^PS|PANAMÁ^PA|PAPÚA NEW GUINEA ^PG|PERÚ^ PE|POLINESIA FRANCESA ^PF|REINO UNIDO^ GB|REPÚBLICA DOMINICANA ^DO|RUMANIA^ RO|RUSIA^ RU|SAHARA OCCIDENTAL ^EH|SAMOA AMERICANA ^AS|SAN MARINO ^SM|SAN PEDRO Y MIQUELÓN ^PM|SANTA LUCIA^ LC|SENEGAL ^SN|SERBIA Y MONTENEGRO ^CS|SEYCHELLES ^SC|SINGAPUR ^SG|SOMALIA ^SO|SRI LANKA ^LK|SUAZILANDIA ^SZ|SUDÁFRICA ^ZA|SUDÁN ^SD|SUECIA ^SE|SVALBARD Y JAN MAYEN^ SJ|TAILANDIA ^TH|TAIWÁN ^TW|TAYIKISTÁN ^TJ|TERRITORIO BRITÁNICO DEL OCÉANOÍNDICO^IO|TERRITORIOS FRANCESES DEL SUR^ TF|TIMOR ORIENTAL ^TL|TOGO ^TG|TOKELAU ^TK|TÚNEZ ^TN|TURKMENISTÁN ^TM|TURQUÍA ^TR|UCRANIA ^UA|URUGUAY ^UY|UZBEKISTÁN ^UZ|VENEZUELA ^VE|VIETNAM ^VN|WALLIS Y FUTUNA ^WF|YIBUTI ^DJ|ZAMBIA ^ZM";
	var $banxico="111013^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE ALPISTE|111021^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE ARROZ|111039^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE AVENA|111047^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE CEBADA|111055^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE LINAZA|111063^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE MAIZ|111071^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE MILO|111089^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE SORGO|111097^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE SOYA|111104^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE TRIGO|112011^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE AJO|112029^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE CALABAZA|112037^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE CAMOTE|112045^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE CEBOLLA|112053^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE CHILE|112061^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE ESPARRAGO|112079^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE FRIJOL|112087^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE GARBANZO|112095^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE JITOMATE|112102^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE LENTEJA|112110^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE NOPAL|112128^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE OTRAS HORTALIZAS|112136^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE PAPA|112144^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE REMOLACHA|112152^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE TOMATE|112160^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE YUCA|113019^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE ALFALFA|119017^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE FRESA|119025^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE MELON|119033^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE PIÑA|119041^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE SANDIA|121012^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE ALGODON|122010^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE CAÑA DE AZUCAR|123018^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE TABACO|124016^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE AJONJOLI|124024^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE CACAHUATE|124032^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE CARTAMO|124040^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE NUEZ|124058^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE OLIVO|129016^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE ESPECIAS|129024^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE OTRAS SEMILLAS|129032^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE SEMILLAS MEJORADAS|129040^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE VAINILLA|131011^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE FLORES Y PLANTAS DE ORNATO|141010^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE CAFÉ|142018^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE AHUACATE|142026^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE DURAZNO|142034^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE GUAYABA|142042^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE LIMON|142050^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE MANGO|142068^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE MANZANA|142076^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE NARANJA|142084^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE OTROS ÁRBOLES FRUTALES|142092^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE PAPAYA|142109^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO DE PLATANO|142117^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE TAMARINDO|142125^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE TORONJA|143016^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE VID|144014^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE HENEQUEN|144022^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE LINO|144030^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE OTRAS FIBRAS DURAS|145012^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE AGAVE O MEZCAL|145020^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE MAGUEY|146010^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE COPRA|147018^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CULTIVO DE CACAO|149014^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ OTROS CULTIVOS PERMANENTES|149907^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ USUARIOS MENORES AGRICULTURA|149915^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CARTERA AGRICOLA DE ESTADOS ANALITICOS|191015^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ IRRIGACION DE TIERRAS|191023^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ PREPARACION DE TIERRAS DE CULTIVO Y OTROS SERVICIOS AGRICOLAS|211011^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE GANADO VACUNO PARA CARNE|212019^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE GANADO VACUNO PARA LECHE|213017^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA DE GANADO DE LIDIA|214015^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA DE GANADO CABALLAR|219015^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA DE OTROS EQUINOS Y GANADO PARA EL TRABAJO|219908^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ USUARIOS MENORES GANADERÍA|219916^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CARTERA GANADERA DE ESTADOS ANALITICOS|221010^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE GANADO PORCINO|222018^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE GANADO OVINO|223016^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE GANADO CAPRINO|231019^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE GALLINA PARA PRODUCCION DE HUEVO|232017^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE POLLOS|233015^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE OTRAS AVES PARA ALIMENTACION|241018^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE ABEJAS|251017^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE CONEJOS Y LIEBRES|259011^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE ANIMALES DOMESTICOS PARA LABORATORIO Y OTROS FINES NO ALIMENTICIOS|259029^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE GUSANO DE SEDA|291013^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ FORMACION DE PRADERAS Y POTREROS ARTIFICIALES|291021^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ INSEMINACION ARTIFICIAL Y OTROS SERVICIOS DE GANADERIA|311019^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ PLANTACION Y REFORESTACION|312017^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ EXTRACCION DE TRONCOS PARA ASERRADEROS Y PARA PULPA INCLUSO LA MADERA TOSCAMENTE ASERRADA|313015^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ EXTRACCION DE CARBON VEGETAL|321018^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ EXTRACCION DE CHICLE|322016^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ EXPLOTACION DE CANDELILLA|322024^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ EXPLOTACION DE HULE|322032^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^EXTRACCION DE COLOFONIA|322040^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ EXTRACCION DE OTRAS RESINAS|323014^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ EXPLOTACION DE RAICES|323022^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ EXTRACCION DE ALQUITRAN VEGETAL|323030^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ EXTRACCION DE TREMENTINA|329012^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CULTIVO Y EXPLOTACION DE PALMA Y LECHUGUILLA|329020^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ EXPLOTACION DE BARBASCO ARBORESCENTES Y ARBUSTOS|329905^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ USUARIOS MENORES SILVICULTURA, PESCA Y PRESERVACIÓN DE ANIMALES SALVAJES|329913^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CARTERA SILVICOLA DE ESTADOS ANALITICOS|391011^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ SERVICIOS DE CORTADO ESTIMACION DEL VOLUMEN DE MADERA PROTECCION DE BOSQUES Y OTROS SERVICIOS RELATIVOS A LA EXPLOTACIÓN FORESTAL|411017^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CAPTURA DE ATUN BONITO BARRILETE Y SIMILARES|412015^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CAPTURA DE SARDINA Y SIMILARES|413013^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CAPTURA DE TIBURON CAZON RAYA Y SIMILARES|419011^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CAPTURA DE OTROS PECES EN ESTUARIOS COSTAS O ALTA MAR|419912^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CARTERA PESQUERA DE ESTADOS ANALITICOS|421016^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^CAPTURA DE CAMARON|422014^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CAPTURA DE OSTION|429010^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CAPTURA DE OTROS CRUSTACEOS Y MOLUSCOS MARINOS|431015^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CAPTURA DE TORTUGA Y OTROS REPTILES MARINOS|439019^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CAPTURA DE MAMIFEROS ANFIBIOS Y DIVERSOS INVERTEBRADOS DE MAR|441014^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ RECOLECCION DE CONCHAS HUEVOS CORALES ESPONJAS Y PERLAS|442012^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ RECOLECCION DE ALGAS Y OTRAS PLANTAS ACUATICAS|451013^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CAPTURA DE PECES EN RIOS LAGOS Y ESTUARIOS|452011^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CAPTURA DE CRUSTACEOS MOLUSCOS REPTILES ANFIBIOS Y OTRA FAUNA DE AGUA DULCE|459017^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE OSTRAS|459025^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CRIA Y EXPLOTACION DE PECES OTRAS ESPECIES ANIMALES Y PLANTAS ACUATICAS|491019^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ SERVICIOS DE PESQUERIAS MARITIMAS Y DE AGUA DULCE POR CONTRATO|511015^AGRICULTURA, GANADERIA, SILVICULTURA, PESCA Y CAZA^ CAPTURA Y PRESERVACION DE ESPECIES ANIMALES SALVAJES|1111012^INDUSTRIAS EXTRACTIVAS^ EXTRACCION Y BENEFICIO DE CARBON MINERAL Y GRAFITO|1211010^INDUSTRIAS EXTRACTIVAS^ EXPLORACION DE PETROLEO POR COMPAÑIAS|1211028^INDUSTRIAS EXTRACTIVAS^ EXTRACCION DE PETROLEO CRUDO Y GAS NATURAL|1211903^INDUSTRIAS EXTRACTIVAS^ USUARIOS MENORES PETRÓLEO|1211911^INDUSTRIAS EXTRACTIVAS^ CARTERA PETROLERA DE ESTADOS ANALITICOS|1311018^INDUSTRIAS EXTRACTIVAS^ EXTRACCION Y BENEFICIO DE MINERAL DE HIERRO|1321017^INDUSTRIAS EXTRACTIVAS^ EXTRACCION Y BENEFICIO DE ORO PLATA Y OTROS METALES PRECIOSOS|1322015^INDUSTRIAS EXTRACTIVAS^ EXTRACCION Y BENEFICIO DE MERCURIO Y ANTIMONIO|1329011^INDUSTRIAS EXTRACTIVAS^ EXTRACCION Y BENEFICIO DE COBRE PLOMO ZINC Y OTROS MINERALES NO FERROSOS|1329904^INDUSTRIAS EXTRACTIVAS^ USUARIOS MENORES MINERÍA|1329912^INDUSTRIAS EXTRACTIVAS^ CARTERA MINERA DE ESTADOS ANALITICOS|1411016^INDUSTRIAS EXTRACTIVAS^ EXTRACCION Y BENEFICIO DE PIEDRA|1412014^INDUSTRIAS EXTRACTIVAS^ EXTRACCION DE YESO|1413012^INDUSTRIAS EXTRACTIVAS^ EXTRACCION Y BENEFICIO DE ARENA Y GRAVA|1419010^INDUSTRIAS EXTRACTIVAS^ EXTRACCION Y BENEFICIO DE OTROS MATERIALES PARA CONSTRUCCION|1421015^INDUSTRIAS EXTRACTIVAS^ EXTRACCION Y BENEFICIO DE ARCILLAS REFRACTARIAS|1431014^INDUSTRIAS EXTRACTIVAS^EXTRACCION Y BENEFICIO DE BARITA Y ROCA FOSFORICA|1432012^INDUSTRIAS EXTRACTIVAS^ EXTRACCION Y BENEFICIO DE FLUORITA|1433010^INDUSTRIAS EXTRACTIVAS^ EXTRACCION Y BENEFICIO DE SILICE|1439018^INDUSTRIAS EXTRACTIVAS^ EXTRACCION Y BENEFICIO DE OTROS MINERALES NO METALICOS EXCEPTO SAL|1511014^INDUSTRIAS EXTRACTIVAS^ EXPLOTACION DE SAL MARINA Y DE YACIMIENTOS|2011013^INDUSTRIAS DE TRANSFORMACION^ DESHIDRATACION DE FRUTAS|2012011^INDUSTRIAS DE TRANSFORMACION^ EMPACADORA DE CONSERVAS ALIMENTICIAS|2012029^INDUSTRIAS DE TRANSFORMACION^ EMPACADORA DE FRUTAS Y LEGUMBRES|2012037^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CONCENTRADOS DE FRUTAS|2012045^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ENCURTIDOS|2013019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ATES|2013027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE QUESO Y MIEL DE TUNA|2014017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE SALSAS|2021012^INDUSTRIAS DE TRANSFORMACION^ MOLINO DE TRIGO|2022010^INDUSTRIAS DE TRANSFORMACION^ MOLINO DE MAIZ|2023018^INDUSTRIAS DE TRANSFORMACION^ MOLINO DE NIXTAMAL|2024016^INDUSTRIAS DE TRANSFORMACION^ MOLINO DE ARROZ|2025014^INDUSTRIAS DE TRANSFORMACION^BENEFICIO DE CAFE EXCEPTO MOLIENDA Y TOSTADO|2026012^INDUSTRIAS DE TRANSFORMACION^ MOLINO Y TOSTADOR DE CAFE|2027010^INDUSTRIAS DE TRANSFORMACION^ EMPACADORA DE TE|2028018^INDUSTRIAS DE TRANSFORMACION^ BENEFICIO DE ARROZ EXCEPTO MOLIENDA|2028026^INDUSTRIAS DE TRANSFORMACION^ DESCASCARADO Y TOSTADO DE CACAHUATE Y NUEZ|2028034^INDUSTRIAS DE TRANSFORMACION^ DESCASCARADORA Y TOSTADORA DE SEMILLA DE CALABAZA|2028042^INDUSTRIAS DE TRANSFORMACION^ MOLINO PARA OTROS GRANOS, EXCEPTO CEREALES|2029016^INDUSTRIAS DE TRANSFORMACION^ MOLINO DE AVENA|2029024^INDUSTRIAS DE TRANSFORMACION^ MOLINO DE CEBADA|2029032^INDUSTRIAS DE TRANSFORMACION^ MOLINO DE OTROS CEREALES|2031011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MARQUETAS Y ESTUCHADOS DE AZUCAR|2031029^INDUSTRIAS DE TRANSFORMACION^ INGENIO AZUCARERO|2032019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PILONCILLO|2033017^INDUSTRIAS DE TRANSFORMACION^ DESTILACION DE ALCOHOL ETILICO|2041010^INDUSTRIAS DE TRANSFORMACION^ RASTRO|2049014^INDUSTRIAS DE TRANSFORMACION^ EMPACADORA DE CARNE|2049022^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CARNES FRIAS Y EMBUTIDOS|2049030^INDUSTRIAS DE TRANSFORMACION^ REFRIGERACION DE CARNES|2051019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y REHIDRATACION DE LECHE|2051027^INDUSTRIAS DE TRANSFORMACION^ PASTEURIZACION HOMOGENEIZACION Y ENVASADO DE LECHE|2052017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CREMA MANTEQUILLA Y QUESO|2053015^INDUSTRIAS DE TRANSFORMACION^FABRICACIÓN DE LECHE CONDENSADA EVAPORADA Y PULVERIZADA|2054013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE GELATINAS|2054021^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE GRENETINA|2059013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CAJETAS YOGOURTS Y OTROS PRODUCTOS A BASE DE LECHE|2061018^INDUSTRIAS DE TRANSFORMACION^ CONGELADORA DE PRODUCTOS MARINOS|2061026^INDUSTRIAS DE TRANSFORMACION^ EMPACADORA DE OTROS MARISCOS|2061034^INDUSTRIAS DE TRANSFORMACION^ EMPACADORA DE PESCADO|2071017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PAN Y PASTELES|2072015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CONOS PARA NIEVE|2072023^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE GALLETAS|2072031^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PASTAS ALIMENTICIAS|2081016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CHOCOLATES|2082014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE DULCES BOMBONES Y CONFITES|2083012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE GOMA DE MASCAR|2084010^INDUSTRIAS DE TRANSFORMACION^ TRATAMIENTO Y ENVASE DE MIEL DE ABEJA|2089010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE JARABES|2091015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ACEITES VEGETALES COMESTIBLES|2091023^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MANTECAS VEGETALES COMESTIBLES|2092013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ALMIDON|2092021^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LEVADURAS|2093011^INDUSTRIAS DE TRANSFORMACION^ TORTILLERIA|2094019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE FRITURAS|2094027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS PREPARADOS ALIMENTICIOS DERIVADOS DE CEREALES|2095017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE VINAGRE|2095025^INDUSTRIAS DE TRANSFORMACION^ REFINACION DE SAL|2096015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HIELO|2097013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HELADOS NIEVES Y PALETAS|2098011^INDUSTRIAS DE TRANSFORMACION^ DESHIDRATACION DE PLANTAS PARA FORRAJES|2098029^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ALIMENTO PARA GANADO Y OTROS ANIMALES|2098037^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ALIMENTOS PARA AVES|2099019^INDUSTRIAS DE TRANSFORMACION^ EMPACADORA DE ESPECIAS|2099027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ACEITES Y MANTECAS ANIMALES COMESTIBLES|2099035^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HARINA DE PESCADO|2099043^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS PRODUCTOS ALIMENTICIOS|2099051^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PASTAS PARA GUISOS|2111011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TEQUILA Y MEZCAL|2112019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE AGUARDIENTE DE CAÑA|2113017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE SOTOL|2114015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS AGUARDIENTES NO DE CAÑA|2114023^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE VINOS Y OTROS LICORES|2115013^INDUSTRIAS DE TRANSFORMACION^ ELABORACION DE PULQUE|2119015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE SIDRA CHAMPAÑA Y OTRAS BEBIDAS FERMENTADAS EXCEPTO LAS MALTEADAS|2121010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MALTA|2122018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CERVEZA|2131019^INDUSTRIAS DE TRANSFORMACION^ EMBOTELLADO DE AGUAS MINERALES|2131027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BEBIDAS GASEOSAS|2131035^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE REFRESCOS DE FRUTAS NATURALES|2131043^INDUSTRIAS DE TRANSFORMACION^ PURIFICACIÓN DE AGUA EXCEPTO CAPTACION TRATAMIENTO CONDUCCION Y DISTRIBUCION DE AGUA POTABLE|2211019^INDUSTRIAS DE TRANSFORMACION^DESECADO DE TABACO|2212009^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CIGARROS|2212017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CIGARROS|2219013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PUROS|2219021^INDUSTRIAS DE TRANSFORMACION^PICADO DE TABACO|2311017^INDUSTRIAS DE TRANSFORMACION^ DESFIBRACION DE ALGODON|2311025^INDUSTRIAS DE TRANSFORMACION^ DESPEPITE DE ALGODON|2311033^INDUSTRIAS DE TRANSFORMACION^ COMPRESORA DE ALGODON|2312015^INDUSTRIAS DE TRANSFORMACION^ BENEFICIO DE LANAS|2312023^INDUSTRIAS DE TRANSFORMACION^ BENEFICIO DE OTRAS FIBRAS TEXTILES|2312031^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HILADOS Y TEJIDOS DE ALGODON|2312049^INDUSTRIAS DE TRANSFORMACION^FABRICACIÓN DE HILADOS Y TEJIDOS DE LANA|2313013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HILOS PARA COSER|2313021^INDUSTRIAS DE TRANSFORMACION^ ACABADO DE HILOS|2313039^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS HILADOS Y TEJIDOS NO SINTETICOS|2314011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ESTAMBRES|2315019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE SARAPES Y COBIJAS|2315027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CASIMIRES Y PAÑOS|2316017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TOALLAS|2316025^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE COLCHAS|2317015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HILADOS Y TEJIDOS DE SEDA|2317023^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE LANA|2317031^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTRAS TELAS MIXTAS DE FIBRAS BLANDAS|2318013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CINTAS AGUJETAS Y LISTONES|2318021^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ENCAJES|2318039^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TELAS ELASTICAS|2319011^INDUSTRIAS DE TRANSFORMACION^ ACABADO DE TELAS|2321016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CALCETINES|2321024^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MEDIAS|2322014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE SUETERES|2329010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HILADOS Y TEJIDOS DE OTRAS FIBRAS SINTETICAS|2331015^INDUSTRIAS DE TRANSFORMACION^ DESFIBRACION DE HENEQUEN|2332013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HILADOS Y TEJIDOS DE HENEQUEN|2333011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE PALMA Y TULE|2333029^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TEJIDOS Y TORCIDOS DE PALMA|2333037^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TEJIDOS Y TORCIDOS DE IXTLE|2339019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HILADOS Y TEJIDOS DE YUTE|2339027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HILADOS Y TEJIDOS DE FIBRA DE COCO|2391019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE LONA|2391027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LONA|2391035^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TELAS IMPERMEABLES|2392017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TAPETES Y ALFOMBRAS|2392025^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TELAS PARA TAPICERIA|2393015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ENTRETELAS Y FIELTROS|2394013^INDUSTRIAS DE TRANSFORMACION^ BENEFICIO DE PELO Y CERDA PARA LA INDUSTRIA TEXTIL|2394021^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BORRAS Y ESTOPAS|2394039^INDUSTRIAS DE TRANSFORMACION^FABRICACIÓN DE OTROS ARTICULOS DE ALGODON|2411015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE VESTIDOS Y OTRAS PRENDAS EXTERIORES DE VESTIR PARA DAMA|2411023^INDUSTRIAS DE TRANSFORMACION^ TALLER DE CONFECCION DE VESTIDOS|2412013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTRAS PRENDAS EXTERIORES DE VESTIR PARA CABALLERO|2412021^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TRAJES PARA CABALLERO|2412039^INDUSTRIAS DE TRANSFORMACION^ TALLER DE SASTRERIA|2413011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE UNIFORMES|2414019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CAMISAS|2415017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTRAS PRENDAS EXTERIORES DE VESTIR PARA NIÑO y NIÑA|2416015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CORBATAS|2416023^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE GUANTES|2416031^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PAÑUELOS PAÑOLETAS Y MASCADAS|2417013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CACHUCHAS Y GORRAS|2417021^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE SOMBREROS EXCEPTO DE PALMA|2418011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE SOMBREROS DE PALMA|2419019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CHAMARRAS Y ABRIGOS|2419027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE IMPERMEABLES|2419035^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TIRANTES|2419043^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MANTONES Y CHALINAS|2419051^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE REBOZOS Y CINTURONES TEJIDOS DE HILO|2419069^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ROPA CON PIEL|2421014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CORSETERIA Y ROPA INTERIOR PARA DAMA|2429018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ROPA INTERIOR PARA CABALLERO|2431013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE SABANAS|2431021^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CORTINAS DE TELA Y MANTELERIA|2432011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CUBREASIENTOS PARA VEHICULOS|2432029^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TAPICES PLASTICOS|2433019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ALGODON ABSORBENTE VENDAS TELA ADEHESIVA Y PRODUCTOS SIMILARES|2434017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS BORDADOS Y DESHILADOS|2434025^INDUSTRIAS DE TRANSFORMACION^ FORRADO DE BOTONES Y HEBILLAS|2439017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BANDERAS Y ADORNOS DE TELA|2439025^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE COSTALES|2511013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CALZADO PARA DEPORTE EXCEPTO DE PLASTICO Y HULE|2512011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ALPARGATAS BABUCHAS Y PANTUFLAS|2512029^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE GUARACHES Y SANDALIAS|2519017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CALZADO DE CUERO O PIEL|2521012^INDUSTRIAS DE TRANSFORMACION^ CURTIDURIA|2521020^INDUSTRIAS DE TRANSFORMACION^ PREPARACION DE VISCERAS PARA INDUSTRIAS NO ALIMENTICIAS|2529016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CEPILLOS Y PLUMEROS|2529024^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE CUERO PARA VIAJE|2529032^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE CUERO Y HUESO|2529040^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE CUERO Y PIEL PARA ZAPATERO|2529058^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS TEJIDOS DE CUERO|2529066^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BANDAS Y CORREAS DE CUERO|2529074^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BOLSAS Y CARTERAS DE CUERO|2529082^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BROCHAS Y PINCELES|2529090^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE FORNITURAS MILITARES DE CUERO|2529107^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE CUERO|2529115^INDUSTRIAS DE TRANSFORMACION^ TALABARTERIA|2611011^INDUSTRIAS DE TRANSFORMACION^ ASERRADERO|2611029^INDUSTRIAS DE TRANSFORMACION^ BENEFICIO DE MADERAS (DESFLEMADO ESTUFADO CEPILLADO ETC)|2612019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TRIPLAY Y OTROS AGLOMERADOS DE MADERA|2621010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CAJAS Y EMPAQUES DE MADERA|2621028^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TONELES Y BARRICAS DE MADERA|2622018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE PALMA VARA CARRIZO MIMBRE Y SIMILARES|2631019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ATAUDES DE MADERA|2632017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE JUNTAS Y EMPAQUES DE CORCHO|2632025^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE CORCHO|2632033^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ASBESTOS Y CORCHOS AISLANTES|2633015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE DUELAS Y OTROS MATERIALES DE MADERA PARA PISO|2633023^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PUERTAS Y VENTANAS DE MADERA|2639013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CARROCERIAS Y REDILAS DE MADERA|2639021^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MANGOS DE MADERA|2639039^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE MADERA|2711019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MUEBLES DE MADERA|2711027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MUEBLES DE MATERIAL SINTETICO|2712017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PERSIANAS DE MADERA|2713015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ALMOHADAS Y COJINES|2713023^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE COLCHONES Y COLCHONETAS|2719013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MARCOS Y MOLDURAS DE MADERA|2811017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PAPEL|2811025^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PAPEL PARA CIGARROS|2811033^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PASTA DE CELULOSA|2811041^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PASTA O PULPA PARA PAPEL|2812015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CARTON|2821016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE SACOS Y BOLSAS DE PAPEL PARA ENVASE|2822014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ENVASES DE CARTON|2829010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE CARTON|2829028^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE PAPEL|2911015^INDUSTRIAS DE TRANSFORMACION^ EDICION DE PERIODICOS Y REVISTAS|2912003^INDUSTRIAS DE TRANSFORMACION^ EDICION DE LIBROS Y SIMILARES|2912013^INDUSTRIAS DE TRANSFORMACION^ EDICION DE LIBROS|2921014^INDUSTRIAS DE TRANSFORMACION^ ENCUADERNACION|2921022^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE FORMULARIOS Y FORMAS CONTINUAS|2921030^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LIBRETAS CUADERNOS Y HOJAS PARA ENCUADERNACION|2921048^INDUSTRIAS DE TRANSFORMACION^ FOTOCOPIADO|2921056^INDUSTRIAS DE TRANSFORMACION^ IMPRENTA ( TIPOGRAFIA )|2921064^INDUSTRIAS DE TRANSFORMACION^ IMPRESION MEDIANTE CILINDROS DE CAUCHO|2921072^INDUSTRIAS DE TRANSFORMACION^ IMPRESION MEDIANTE CILINDROS (ROTOGRABADO)|2929018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CALCOMANIAS|2929026^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TIPOS PARA IMPRENTA|2929034^INDUSTRIAS DE TRANSFORMACION^ GRABADO E IMPRESION EN PIEDRA VIDRIO Y OTROS MATERIALES LITOGRAFIA Y FOTOLIT OGRAFIA|2929042^INDUSTRIAS DE TRANSFORMACION^ GRABADO E IMPRESION FOTOMECANICA MEDIANTE AGUA FUERTE(HELIOGRABADO)|2929050^INDUSTRIAS DE TRANSFORMACION^ GRABADO EN METAL (FABRICACIÓN DE CLICHES Y FOTOGRABADO)|3011012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ANIL|3011020^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ANILINAS|3011038^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTRAS MATERIAS COLORANTES|3012010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE GAS ACETILENO|3013018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ACIDOS INDUSTRIALES|3013026^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTRAS SUBSTANCIAS QUIMICAS BASICAS|3013034^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PRODUCTOS AMONIACALES|3013042^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE SOSA|3021011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ABONOS Y FERTILIZANTES QUIMICOS|3022019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE INSECTICIDAS Y PLAGUICIDAS|3031010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HULE ESPUMA|3032018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE FIBRAS SINTETICAS|3041019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PINTURAS BARNICES Y LACAS|3051018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ALGODON ESTERILIZADO GASAS Y VENDAS|3051026^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS PRODUCTOS FARMACEUTICOS Y MEDICAMENTOS|3051034^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OXIGENO MEDICINAL|3061017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE DENTIFRICO|3061025^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE JABON Y DETERGENTE|3062015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PERFUMES Y COSMETICOS|3071016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ESENCIAS|3072014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y REFINACION DE SEBO GRASAS Y ACEITES ANIMALES PARA USO INDUSTRIAL|3072022^INDUSTRIAS DE TRANSFORMACION^ FUNDICION DE SEBO|3072030^INDUSTRIAS DE TRANSFORMACION^ HIDROGENADORA DE PRODUCTOS DIVERSOS|3091014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE COLAS Y PEGAMENTOS|3091022^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE IMPERMEABILIZANTES|3092012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CERA PULIMENTOS Y ABRILLANTADORES|3092020^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE DESINFECTANTES Y DESODORIZANTES|3092038^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE GRASAS Y CREMAS LUSTRADORAS|3092046^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LANOLINA|3093010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE AGUARRAS|3094018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CERILLOS Y FOSFOROS|3095016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE CERA|3095024^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE VELAS VELADORAS Y CIRIOS|3096014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TINTAS PARA IMPRESION|3097012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE DINAMITA|3097020^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MECHAS PARA MINAS|3097038^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS EXPLOSIVOS|3097046^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE POLVORA|3097054^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PRODUCTOS PIROTECNICOS|3099018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE BAQUELITA|3099026^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE DESINCRUSTANTES|3099034^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HIELO SECO|3099042^INDUSTRIAS DE TRANSFORMACION^ INDUSTRIALIZACION DE BASURA|3111010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE GASOLINA Y OTROS PRODUCTOS DERIVADOS DE LA REFINACION DE PETROLEO|3112018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PRODUCTOS PETROQUIMICOS BASICOS|3113016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ACEITES Y LUBRICANTES|3121019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE COQUE Y OTROS DERIVADOS DEL CARBON MINERAL|3122017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MATERIALES ASFALTICOS PARA PAVIMENTACION Y TECHADO|3211018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LLANTAS Y CAMARAS PARA VEHICULOS|3212016^INDUSTRIAS DE TRANSFORMACION^ REGENERACION DE HULE|3212024^INDUSTRIAS DE TRANSFORMACION^ VULCANIZACION DE LLANTAS Y CAMARAS|3219012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS CON HULE USADO|3219020^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LINOLEO|3219038^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE HULE|3221017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LAMINAS PERFILES TUBOS Y OTROS MATERIALES SIMILARES DE PLASTICO|3222015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE POLIETILENO|3222023^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CELULOIDE Y POLIETILENO|3223013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CALZADO DE PLASTICO|3223021^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS JUGUETES DE PLASTICO|3229011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE CELULOIDE|3229029^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BALONES GLOBOS Y PELOTAS|3229037^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BOTONES DE PLASTICO|3229045^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE PLASTICO|3229053^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS MATERIALES DE PLASTICO|3229061^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PEINES PEINETAS Y CEPILLOS PARA USO PERSONAL|3229079^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PIELES ARTIFICIALES|3311016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE VAJILLAS Y OTROS PRODUCTOS DE ALFARERIA Y CERAMICA PARA EL HOGAR|3319010^INDUSTRIAS DE TRANSFORMACION^ ELABORACION DE OBJETOS ARTISTICOS DE ALFARERIA Y CERAMICA|3319028^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE AZULEJOS|3319036^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LOZA Y PORCELANA|3319044^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MUEBLES Y ARTICULOS SANITARIOS|3321015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE VIDRIO|3322013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CRISTALES PARA AUTOMOVIL|3322021^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE FIBRA DE VIDRIO|3323011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE AMPOLLETAS|3323029^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BOTELLAS|3323037^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CRISOLES|3323045^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ENVASES DE VIDRIO|3324019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE EMPLOMADOS|3324027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ESPEJOS Y LUNAS|3329019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ESFERAS DE VIDRIO|3329027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OBJETOS ARTISTICOS DE VIDRIO Y CRISTAL|3329035^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS OBJETOS DE CRISTAL|3331014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ADOBE|3331022^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LADRILLOS|3331030^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TEJA Y TUBO DE ARCILLA|3332012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MATERIALES PARA MUROS|3332020^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PRODUCTOS REFRACTARIOS|3341013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CEMENTO|3341021^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CONCRETO CIENTIFICO|3341039^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CONCRETO PARA CONSTRUCCION|3342011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BLOQUES DE CEMENTO|3342029^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE YESO|3343019^INDUSTRIAS DE TRANSFORMACION^ HORNO DE CAL|3351012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TINACOS DE ASBESTO|3352010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ABRASIVOS|3352028^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ESMERILES|3352036^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LIJA|3353018^INDUSTRIAS DE TRANSFORMACION^ TALLER DE MARMOLERIA|3354016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MOSAICOS TERRAZOS Y GRANITO|3354024^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE POSTES Y DURMIENTES DE CONCRETO|3354032^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TUBOS DE CONCRETO|3354909^INDUSTRIAS DE TRANSFORMACION^ USUARIOS MENORES INDUSTRIA PRODUCTOS DE MINERALES NO METÁLICOS|3354917^INDUSTRIAS DE TRANSFORMACION^ CARTERA DE PRODUCTOS MINERALES NO METALICOS DE EST|3411014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE FIERRO ESPONJA|3411022^INDUSTRIAS DE TRANSFORMACION^ FUNDICION DE FIERRO Y ACERO|3411030^INDUSTRIAS DE TRANSFORMACION^ PLANTA METALURGICA|3411907^INDUSTRIAS DE TRANSFORMACION^ USUARIOS MENORES DE INDUSTRIA SIDERÚRGICA Y PRODUCTOS METÁLICOS|3411915^INDUSTRIAS DE TRANSFORMACION^ CARTERA SIDERURGICA Y DE PRODUCTOS METALICOS DE ES|3412012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LAMINAS DE HIERRO Y ACERO|3412020^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE LAMINA|3413010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TUBOS DE HIERRO Y ACERO|3421013^INDUSTRIAS DE TRANSFORMACION^ FUNDICION REFINACION LAMINACION EXTRUSION Y ESTIRAJE DE COBRE Y SUS ALEACIONES|3422011^INDUSTRIAS DE TRANSFORMACION^ FUNDICION LAMINACION EXTRUSION Y ESTIRAJE DE ALUMINIO Y FABRICACIÓN DE SOLDADURAS ALUMINO TERMICAS|3423019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TUBOS DE ESTAÑO|3423027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE EQUIPOS PARA SOLDAR Y SOLDADURAS|3429017^INDUSTRIAS DE TRANSFORMACION^ FUNDICIÓN DE METALES NO FERRUGINOSOS|3511012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CUBIERTOS Y CUCHILLERIA|3512010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE UTENSILIOS AGRICOLAS Y HERRAMIENTAS DE MANO|3513018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE REMACHES|3513026^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TORNILLOS TUERCAS Y PIJAS|3514016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CLAVOS CADENAS GRAPAS Y TACHUELAS|3515014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CANDADOS|3515022^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CERRADURAS|3516012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CORTINAS DE METAL|3516020^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE JAULAS DE METAL|3516038^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE ALUMINIO|3516046^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PERFILES PUERTAS Y VENTANAS DE METAL|3516054^INDUSTRIAS DE TRANSFORMACION^ TALLER DE HERRERIA|3521011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BUTACAS DE METAL|3521029^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ESTUFAS|3521037^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS MUEBLES DE METAL|3531010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ESTRUCTURAS DE METAL|3531028^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TANQUES PARA ENVASADO DE GASES O LIQUIDOS|3532018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CALDERAS TANQUES Y TINACOS DE METAL|3532026^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CALENTADORES PARA BAÑO|3591014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ENVASES DE LAMINA|3591022^INDUSTRIAS DE TRANSFORMACION^ TALLER DE HOJALATERIA|3592012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CORCHOLATAS|3593010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ALAMBRADOS Y TELAS DE METAL|3593028^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ALAMBRE|3593036^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE ALAMBRE|3594018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE PELTRE|3595016^INDUSTRIAS DE TRANSFORMACION^ GALVANIZACION DE LAMINA|3595024^INDUSTRIAS DE TRANSFORMACION^ GALVANIZACION DE TUBERIA|3595032^INDUSTRIAS DE TRANSFORMACION^ TALLER DE COBRIZADO|3595040^INDUSTRIAS DE TRANSFORMACION^ TALLER DE CROMADO Y NIQUELADO|3595058^INDUSTRIAS DE TRANSFORMACION^ TALLER DE ESMALTADO|3596014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PIEZAS METALICAS POR FUNDICION Y MOLDEO EXCEPTO PARA MAQUINARIA EQUIPO Y MATERIAL DE|3596022^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TUBOS DE COBRE PLOMO Y ALUMINIO|3599018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ALFILERES AGUJAS Y BROCHES|3599026^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CAJAS FUERTES|3599034^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CIERRES AUTOMATICOS DE METAL|3599042^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ESPUELAS FRENOS Y ARNESES DE METAL|3599050^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HEBILLAS DE METAL|3599068^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HOJAS PARA RASURAR|3599076^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OJILLOS DE METAL|3599084^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE LATON|3599092^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE METAL NO CLASIFICADOS EN OTRA PARTE|3599109^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PASADORES Y HORQUILLAS|3599117^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PLANCHAS INDUSTRIALES|3599125^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TAPONES DE METAL|3599133^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TROQUELES|3611010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN ENSAMBLE Y REPARACION DE VEHICULOS DE USO AGROPECUARIO|3611028^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MAQUINARIA E IMPLEMENTOS AGRICOLAS|3621019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HERRAMIENTAS DE METAL|3621027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE REFACCIONES Y MAQUINARIA INDUSTRIAL|3631018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN ENSAMBLE Y REPARACION DE MAQUINARIA Y EQUIPO PARA LA INDUSTRIA DE ALIMENTOS Y BEBIDAS|3632016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN ENSAMBLE Y REPARACION DE MAQUINARIA EQUIPO Y TRACTORES PARA LAS INDUSTRIAS EXTRACTIVAS|3639012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MOLINOS PARA GRANOS|3639905^INDUSTRIAS DE TRANSFORMACION^ USUARIOS MENORES DE FABRICACION DE MAQUINARIA Y ARTICULOS ELECTRICOS|3639913^INDUSTRIAS DE TRANSFORMACION^ CARTERA DE FABRICACION DE MAQUINARIA Y ARTICULOS E|3641017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MAQUINAS CALCULADORAS REGISTRADORAS Y DE ESCRIBIR|3641025^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE EQUIPO DE PROCESAMIENTO ELECTRÓNICO DE DATOS|3691012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MAQUINAS DE COSER|3692002^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN, ENSAMBLE Y REPARACIÓN DE GRÚAS, MONTACARGAS Y OTRAS MÁQUINAS PARA TRANSPORTAR O LEVANTAR|3692010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN ENSAMBLE Y REPARACION DE GRUAS MONTACARGAS Y OTRAS MAQUIMAQUINAS PARA TRANSPORTAR O LEVANTAR|3693018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN ENSAMBLE Y REPARACION DE MOTORES NO ELECTRICOS EXCEPTO PARA VEHICULOS AUTOMOVILES|3694008^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN, ENSAMBLE Y REPARACIÓN DE BOMBAS, ROCIADORES Y EXTINGUIDORES|3694016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE EXTINGUIDORES|3694024^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BOMBAS PARA AGUA|3695014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE VALVULAS DE METAL|3696012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE FILTROS DE METAL|3697010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN E INSTALACION DE EQUIPOS Y APARATOS DE AIRE ACONDICIONADO CALEFACCION Y REFRIGERACIÓN|3699016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ENGRANES DE METAL|3699024^INDUSTRIAS DE TRANSFORMACION^ TALLER MECANICO DE FABRICACIÓN Y REPARACION DE PARTES INDUSTRIALES|3699032^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ACCESORIOS PARA LA INDUSTRIA TEXTIL|3711018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN ENSAMBLE Y REPARACION DE TRANSFORMADORES MOTORES Y MAQUINARIA Y EQUIPO PARA GENERACIÓN Y UTILIZACION DE LA ENERGIA ELÉCTRICA|3721017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE RADIOS TOCADISCOS GRABADORAS Y TELEVISORES|3722015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE DISCOS Y CINTAS PARA GRABACIONES|3722023^INDUSTRIAS DE TRANSFORMACION^ GRABACION DE DISCOS Y CINTAS|3723013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE APARATOS DE INTERCOMUNICACION|3729011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PARTES DISPOSITIVOS Y ACCESORIOS PARA EQUIPO Y APARATOS DE RADIO, TELEVISION Y COMUNICACIONES|3731016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS APARATOS ELECTRICOS PARA EL HOGAR|3731024^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE REFRIGERADORES Y EQUIPOS DE CALEFACCION DOMESTICA|3791010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ACUMULADORES|3791028^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PILAS SECAS|3792018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE FOCOS|3793016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CABLES DE METAL|3793024^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MATERIAL PARA INSTALACIONES ELECTRICAS|3793032^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE REFACCIONES PARA APARATOS ELECTRICOS|3799014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ANUNCIOS LUMINOSOS|3799022^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CANDILES Y ARBOTANTES|3799030^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LAMPARAS ELECTRICAS|3811016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y ENSAMBLE DE AUTOMOVILES Y CAMIONES|3812014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CARROCERIAS DE METAL|3812022^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE FURGONES Y VAGONES|3812030^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y ENSAMBLE DE OTROS VEHICULOS|3813012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PISTONES BIELAS ANILLOS CIGUEÑALES Y MONOBLOCKS PARA MOTORES|3814010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PARTES PARA EL SISTEMA DE TRANSMISION DE VEHICULOS AUTOMOVILES|3815018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MUELLES Y RESORTES PARA VEHICULOS|3816016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PARTES PARA EL SISTEMA DE FRENOS DE VEHICULOS AUTOMOVILES|3817014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BUJIAS|3819010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE REFACCIONES Y ACCESORIOS AUTOMOTRICES|3821015^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN ENSAMBLE Y REPARACION DE CARROS DE FERROCARRIL Y OTRO EQUIPO FERROVIARIO|3831014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y REPARACION DE BUQUES Y BARCOS|3831022^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y REPARACION DE OTROS VEHICULOS ACUATICOS|3832012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN ENSAMBLE Y REPARACION DE AERONAVES|3891018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y ENSAMBLE DE MOTOCICLETAS BICICLETAS Y OTROS VEHICULOS DE PEDAL|3892016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PARTES REFACCIONES Y ACCESORIOS PARA MOTOCICLETAS BICICLETAS Y OTROS VEHICULOS DE PEDAL|3899012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CARREOLAS Y ANDADERAS|3911014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y REPARACION DE ARTICULOS DE OPTICA|3912012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CAMARAS FOTOGRAFICAS DE CINE Y PROYECTORES|3912020^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MATERIAL FOTOGRAFICO|3921013^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE RELOJES|3931012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MEDALLAS INSIGNIAS Y PLACAS|3932010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE JOYERIA|3932028^INDUSTRIAS DE TRANSFORMACION^ ORFEBRERIA|3932036^INDUSTRIAS DE TRANSFORMACION^ TALLADO DE PIEDRAS PRECIOSAS|3933018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE QUINCALLERIA Y BISUTERIA|3941011^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CUERDAS PARA GUITARRA|3941029^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y REPARACION DE INSTRUMENTOS MUSICALES|3951010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE APARATOS PARA GIMNASIA|3951028^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MESAS DE BILLAR|3951036^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS PARA DEPORTES|3961019^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS INSTRUMENTOS DE MEDICION|3961027^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y REPARACION DE BASCULAS Y BALANZAS|3961035^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y REPARACION DE INTRUMENTOS DE FISICA Y QUIMICA|3961043^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y REPARACION DE INSTRUMENTOS DE INGENIERIA|3961051^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE APARATOS OZONIZADORES|3961069^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TAXIMETROS|3962017^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y REPARACION DE APARATOS ORTOPEDICOS|3962025^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN Y REPARACION DE INSTRUMENTOS DE CIRUGIA|3991016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE JUGUETES EXCEPTO LOS DE HULE Y DE PLASTICO MOLDEADOS|3992014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS PARA ESCRITORIO|3992022^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CANUTEROS|3992030^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CINTAS PARA MAQUINAS CALCULADORAS REGISTRADORAS Y DE ESCRIBIR|3992048^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE LAPICES|3992056^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PIZARRONES|3993012^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE SELLOS DE GOMA|3993020^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE SELLOS PARA CARROS DE FERROCARRIL|3994010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS PARA DENTISTA|3995018^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CERCOS PARA CALZADO|3995026^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE HORMAS PARA CALZADO Y TACONES|3996016^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ESCOBAS|3997014^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARMAS|3997022^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CARTUCHOS|3997030^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE CASQUILLOS|3997048^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE MUNICIONES PARA CAZA|3999010^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ABANICOS|3999028^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE CAREY|3999036^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ARTICULOS DE CONCHA|3999044^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE BASTONES PARAGUAS Y SOMBRILLAS|3999052^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE FLORES ARTIFICIALES|3999060^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE OTROS ARTICULOS DE PLUMA DE AVE|3999078^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE ROTULOS|3999086^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TALCO INDUSTRIAL|3999094^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE TRAGALUCES Y MARQUESINAS|3999101^INDUSTRIAS DE TRANSFORMACION^ FABRICACIÓN DE PRODUCTOS MANUFACTURADOS NO CLASIFICADOS EN OTRA PARTE|3999119^INDUSTRIAS DE TRANSFORMACION^ AJUSTE POR PROGRAMA VENTA DE CARTERA A FOBAPROA (USO EXCLUSIVO BANXICO)|3999127^INDUSTRIAS DE TRANSFORMACION^ AJUSTE ESTADISTICO DERIVADO DE PROGRAMAS DE APOYO A LA BANCA (USO EXCLUSIVO BANXICO)|3999903^INDUSTRIAS DE TRANSFORMACION^ USUARIOS MENORES INDUSTRIA MANUFACTURERA|3999911^INDUSTRIAS DE TRANSFORMACION^ CARTERA DE INDUSTRIA MANUFACTURERA DE ESTADOS ANAL|4111019^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION DE CASAS Y TECHOS DESARMABLES|4111027^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION DE INMUEBLES|4111035^INDUSTRIAS DE TRANSFORMACION^ PRESTAMOS PARA LA CONSTRUCCION DE VIVIENDA TANTO DE INTERES SOCIAL COMO PROVENIENTES DE LA RESERVA PARA PENSIONES DEL PERSONAL|4111043^INDUSTRIAS DE TRANSFORMACION^ PRESTAMOS PARA LA CONSTRUCCION DE VIVIENDA PARA ACREDITADOS DE INGRESOS MINIMOS|4111910^INDUSTRIAS DE TRANSFORMACION^ CARTERA DE VIVIENDA DE INTERES SOCIAL DE ESTADOS A|4111928^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION DE VIVIENDA TIPO MEDIO|4111936^INDUSTRIAS DE TRANSFORMACION^ USUARIOS MENORES CONSTRUCCIÓN DE VIVIENDA TIPO MEDIO|4111944^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION DE VIVIENDA RESIDENCIAL|4111952^INDUSTRIAS DE TRANSFORMACION^ MENORES CONSTRUCCION DE VIVIENDA RESINDENCIAL|4112017^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION DE EDIFICIOS PARA OFICINAS ESCUELAS HOSPITALES HOTELES Y OTROS NO RESIDENCIALES|4113007^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCIÓN DE EDIFICIOS INDUSTRIALES Y PARA FINES ANÁLOGOS|4113015^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION DE EDIFICIOS INDUSTRIALES Y PARA FINES ANALOGOS|4121018^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION DE VIAS DE COMUNICACION|4121026^INDUSTRIAS DE TRANSFORMACION^ PAVIMENTACION|4121034^INDUSTRIAS DE TRANSFORMACION^ URBANIZACION|4122016^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION DE PUERTOS|4123014^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION E INSTALACION DE LINEAS TELEFONICAS TORRES EMISORAS DE RADIO Y TELEVISION Y OTRAS OBRAS CONEXAS|4129012^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION DE PISTAS DE ATERRIZAJE DUCTOS Y OTRAS OBRAS VINCULADAS A LAS VIAS DE COMUNICACION|4191011^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION DE PRESAS|4192019^INDUSTRIAS DE TRANSFORMACION^ PERFORACION DE NORIAS Y POZOS PARA AGUA|4193017^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION E INSTALACION DE PLANTAS GENERADORAS Y LINEAS DE TRANSMISION Y DISTRIBUCIÓN DE ENERGIA ELECTRICA|4194015^INDUSTRIAS DE TRANSFORMACION^ PERFORACION DE POZOS PETROLEROS Y DE GAS|4199015^INDUSTRIAS DE TRANSFORMACION^ CONSTRUCCION DE ESTADIOS MONUMENTOS Y OTRAS OBRAS DE INGENIERIA|4199908^INDUSTRIAS DE TRANSFORMACION^ USUARIOS MENORES CONSTRUCCIÓN|4199916^INDUSTRIAS DE TRANSFORMACION^ CARTERA DE CONSTRUCCION DE ESTADOS ANALITICOS|4211017^INDUSTRIAS DE TRANSFORMACION^ PREMEZCLADO Y COLADO DE CONCRETO|4212015^INDUSTRIAS DE TRANSFORMACION^ DEMOLICION DE INMUEBLES|4219011^INDUSTRIAS DE TRANSFORMACION^ IMPERMEABILIZACION DE INMUEBLES|4221016^INDUSTRIAS DE TRANSFORMACION^ TALLER DE PLOMERIA|4222014^INDUSTRIAS DE TRANSFORMACION^ INSTALACIONES DEL SISTEMA ELECTRICO INCLUYE SISTEMAS DE INTERCOMUNICACION|4229010^INDUSTRIAS DE TRANSFORMACION^ INSTALACIONES DE SISTEMAS DE AIRE ACONDICIONADO Y CALEFACCION Y OTRAS INSTALACIONES ANÁLOGAS|4291019^INDUSTRIAS DE TRANSFORMACION^ SERVICIOS DE PINTADO Y TAPIZADO DE INMUEBLES|4292017^INDUSTRIAS DE TRANSFORMACION^ LABRADO Y COLOCACION DE PIEDRA|4293015^INDUSTRIAS DE TRANSFORMACION^ COLOCACION DE DUELAS PARQUET LAMBRINES Y OTROS TRABAJOS DE CARPINTERIA|4299013^INDUSTRIAS DE TRANSFORMACION^ OTROS SERVICIOS DE ACABADO PRESTADOS POR SUBCONTRATISTAS|5011010^INDUSTRIA ELECTRICA Y CAPTACION Y SUMINISTRO DE AGUA POTABLE^ GENERACION Y SUMINISTRO DE ENERGIA ELECTRICA|5011903^INDUSTRIA ELECTRICA Y CAPTACION Y SUMINISTRO DE AGUA POTABLE^ USUARIOS MENORES ENERGÍA ELÉCTRICA|5011911^INDUSTRIA ELECTRICA Y CAPTACION Y SUMINISTRO DE AGUA POTABLE^ CARTERA DE ENERGIA ELECTRICA DE ESTADOS ANALITICOS|5012018^INDUSTRIA ELECTRICA Y CAPTACION Y SUMINISTRO DE AGUA POTABLE^ DISTRIBUCION DE ENERGIA ELECTRICA|5021019^INDUSTRIA ELECTRICA Y CAPTACION Y SUMINISTRO DE AGUA POTABLE^ PIRIDEGAS ENERGÍA ELÉCTRICA|5111018^INDUSTRIA ELECTRICA Y CAPTACION Y SUMINISTRO DE AGUA POTABLE^ CAPTACION TRATAMIENTO CONDUCCION Y DISTRIBUCION DE AGUA POTABLE EXCEPTO DE RIEGO|6111017^COMERCIO^ COMPRA VENTA DE SEMILLAS Y GRANOS|6112015^COMERCIO^ COMPRA VENTA DE FRUTAS|6112023^COMERCIO^ COMPRA VENTA DE LEGUMBRES Y HORTALIZAS|6113013^COMERCIO^ COMPRA VENTA DE CHILE SECO Y ESPECIAS|6114011^COMERCIO^ COMPRA VENTA DE FORRAJES EN ESTADO NATURAL|6119011^COMERCIO^ COMPRA VENTA DE OTROS PRODUCTOS ALIMENTICIOS AGRICOLAS EN ESTADO NATURAL|6121016^COMERCIO^ COMPRA VENTA DE AVES EN PIE|6121024^COMERCIO^ COMPRA VENTA DE GANADO MAYOR EN PIE|6121032^COMERCIO^ COMPRA VENTA DE GANADO MENOR EN PIE|6122014^COMERCIO^ COMPRA VENTA DE CARNE DE RES Y OTRAS ESPECIES DE GANADO|6123012^COMERCIO^ COMPRA VENTA DE VISCERAS DE GANADO CRUDAS Y SEMICRUDAS|6124010^COMERCIO^ COMPRA VENTA DE CARNE DE AVES|6125018^COMERCIO^ COMPRA VENTA DE HUEVO|6126016^COMERCIO^ COMPRA VENTA DE MANTECA|6126024^COMERCIO^ COMPRA VENTA DE MATERIAS PRIMAS ANIMALES|6126032^COMERCIO^ COMPRA VENTA DE PESCADOS Y MARISCOS|6131015^COMERCIO^ SALCHICHONERIA|6131023^COMERCIO^ TIENDA DE ABARROTES Y MISCELANEA|6132013^COMERCIO^ COMPRA VENTA DE DULCES|6132021^COMERCIO^ COMPRA VENTA DE ESPECIAS Y ARTICULOS ALIMENTICOS DESHIDRATADOS|6132039^COMERCIO^ COMPRA VENTA DE LECHE|6132047^COMERCIO^ COMPRA VENTA DE OTROS PRODUCTOS LACTEOS|6132055^COMERCIO^ COMPRA VENTA DE PAN Y PASTELES|6132063^COMERCIO^ COMPRA VENTA DE SAL|6132071^COMERCIO^ VENTA Y DISTRIBUCION DE DESPENSAS FAMILIARES|6132089^COMERCIO^ COMPRAVENTA DE AZUCAR|6133011^COMERCIO^ COMPRAVENTA DE ALIMENTO PARA GANADO|6133029^COMERCIO^ COMPRAVENTA DE ALIMENTOS PARA AVES Y OTROS ANIMALES|6134019^COMERCIO^ COMPRA VENTA DE REFRESCOS AGUAS GASEOSAS Y AGUAS PURIFICADAS|6135017^COMERCIO^ COMPRA VENTA DE CERVEZA|6136015^COMERCIO^ COMPRA VENTA DE PULQUE|6136023^COMERCIO^ COMPRA VENTA DE VINOS Y LICORES|6139019^COMERCIO^ COMPRA VENTA DE TABACO|6139027^COMERCIO^ COMPRA VENTA DE TABACOS PUROS Y CIGARROS|6211015^COMERCIO^ COMPRA VENTA DE ARTICULOS DE LENCERIA|6211023^COMERCIO^ COMPRA VENTA DE ROPA|6212013^COMERCIO^ COMPRA VENTA DE CALZADO|6213011^COMERCIO^ COMPRA VENTA DE SOMBREROS|6214019^COMERCIO^ COMPRA VENTA DE PIELES FINAS CON PELO|6215017^COMERCIO^ COMPRA VENTA DE ARTICULOS DE MERCERIA Y SEDERIA|6215025^COMERCIO^ COMPRAVENTA DE ARTICULOS DE BONETERIA|6216015^COMERCIO^ COMPRA VENTA DE CASIMIRES|6216023^COMERCIO^ COMPRA VENTA DE OTROS PRODUCTOS TEXTILES|6216031^COMERCIO^ COMPRA VENTA DE TELAS|6221014^COMERCIO^ COMPRA VENTA DE ARTICULOS PARA DEPORTE|6222012^COMERCIO^ COMPRA VENTA DE ARTICULOS DE OPTICA|6223010^COMERCIO^ COMPRA VENTA DE JUGUETES|6224018^COMERCIO^ COMPRA VENTA DE INSTRUMENTOS Y ARTICULOS MUSICALES|6225016^COMERCIO^ COMPRA VENTA DE ARTICULOS DE PLATA|6225024^COMERCIO^ COMPRA VENTA DE OTRAS JOYAS|6225032^COMERCIO^ COMPRA VENTA DE RELOJES|6226014^COMERCIO^ COMPRA VENTA DE ARTICULOS DE TALABARTERIA|6226022^COMERCIO^ COMPRA VENTA DE OTROS ARTICULOS DE PIEL|6226030^COMERCIO^ COMPRA VENTA DE SILLAS DE MONTAR|6227012^COMERCIO^ COMPRA VENTA DE LIBROS|6228010^COMERCIO^ DISTRIBUCION Y COMPRAVENTA DE PERIODICOS Y REVISTAS|6229018^COMERCIO^ COMPRA VENTA DE ARTICULOS FOTOGRAFICOS Y CINEMATOGRAFICOS|6231013^COMERCIO^ COMPRA VENTA DE MEDICINAS|6231021^COMERCIO^ COMPRA VENTA DE PERFUMES|6232011^COMERCIO^ DISTRIBUCION DE OTROS PRODUCTOS QUIMICOS FARMACEUTICOS|6233019^COMERCIO^ COMPRA VENTA DE PAPELERIA Y ARTICULOS DE ESCRITORIO|6239017^COMERCIO^ COMPRA VENTA DE PARAGUAS SOMBRILLAS ROPA USADA Y OTROS ARTICULOS DE USO PERSONAL|6311013^COMERCIO^ COMPRA VENTA DE REFRIGERADORES LAVADORAS Y ESTUFAS|6311021^COMERCIO^ COMPRA VENTA DE TELEVISORES CONSOLAS RADIOS MODULARES Y TOCACINTAS|6311039^COMERCIO^ COMPRAVENTA DE OTROS APARATOS ELECTRICOS Y ELECTRONICOS DE USO DOMESTICO|6312011^COMERCIO^ COMPRA VENTA DE MUEBLES|6313019^COMERCIO^ COMPRA VENTA DE MAQUINAS DE COSER Y TEJEDORAS|6314017^COMERCIO^ COMPRA VENTA DE REFACCIONES Y ACCESORIOS DE MAQUINAS APARATOS E INSTRUMENTOS PARA EL HOGAR EXCEPTO|6321012^COMERCIO^ COMPRA VENTA DE ARTICULOS DE CRISTAL LOZA Y PORCELANA|6322010^COMERCIO^ COMPRA VENTA DE ARTICULOS PARA DECORACION|6322028^COMERCIO^ COMPRA VENTA DE TAPETES Y ALFOMBRAS|6323018^COMERCIO^ COMPRA VENTA DE DISCOS Y CASSETTES|6324016^COMERCIO^ COMPRA VENTA DE ARTICULOS RELIGIOSOS|6325014^COMERCIO^ COMPRA VENTA DE ANTIGUEDADES|6325022^COMERCIO^ COMPRA VENTA DE ARTICULOS DE YUTE Y HENEQUEN|6325030^COMERCIO^ COMPRA VENTA DE ARTICULOS PARA REGALO|6325048^COMERCIO^ COMPRA VENTA DE ARTICULOS REGIONALES CURIOSIDADES Y ARTESENIAS|6326012^COMERCIO^ COMPRA VENTA DE FLORES Y ADORNOS FLORALES ARTIFICIALES|6326020^COMERCIO^ COMPRA VENTA DE FLORES Y ADORNOS FLORALES NATURALES|6329016^COMERCIO^ COMPRA VENTA DE OTROS ARTICULOS PARA EL HOGAR|6411011^COMERCIO^ TIENDA DE AUTOSERVICIO|6412019^COMERCIO^ TIENDAS DE DEPARTAMENTOS ESPECIALIZADOS EXCEPTO DE COMESTIBLES|6419015^COMERCIO^ COMPRA VENTA DE OTROS APARATOS ELECTRICOS|6419023^COMERCIO^ COMPRA VENTA DE REFACCIONES PARA APARATOS ELECTRICOS|6511019^COMERCIO^ COMPRA VENTA DE FLUIDOS Y GASES|6512017^COMERCIO^ COMPRA VENTA DE GAS PARA USO DOMESTICO O COMERCIAL|6513015^COMERCIO^ COMPRA VENTA DE GASOLINA Y DIESEL|6514013^COMERCIO^ COMPRA VENTA DE PETROLEO COMBUSTIBLE|6515011^COMERCIO^ COMPRAVENTA DE LUBRICANTES|6519013^COMERCIO^ COMPRA VENTA DE CARBON Y OTROS COMBUSTIBLES|6611017^COMERCIO^ COMPRA VENTA DE ALGODON|6612015^COMERCIO^ COMPRA VENTA DE SEMILLAS PARA SIEMBRA|6613013^COMERCIO^ COMPRA VENTA DE CUEROS Y PIELES SIN CURTIR|6619011^COMERCIO^ COMPRA VENTA DE HENEQUEN E IXTLE|6619029^COMERCIO^ COMPRA VENTA DE LANA|6619037^COMERCIO^ COMPRA VENTA DE OTRAS MATERIAS PRIMAS VEGETALES|6619045^COMERCIO^ COMPRA VENTA DE OTRAS FIBRAS COMERCIALES|6619053^COMERCIO^ COMPRA VENTA DE OTRAS MATERIAS PRIMAS DE ORIGEN ANIMAL|6621016^COMERCIO^ COMPRA VENTA DE CEMENTO CAL YESO Y OTROS PRODUCTOS A BASE DE MINERA LES NO METALICOS|6622014^COMERCIO^ COMPRA VENTA DE FIERRO LAMINADO Y EN LINGOTES|6622022^COMERCIO^ COMPRAVENTA DE ARTICULOS DE FERRETERIA|6623012^COMERCIO^ COMPRA VENTA DE COLORANTES PARA LA INDUSTRIA|6623020^COMERCIO^ COMPRA VENTA DE PINTURAS BARNICES Y BROCHAS|6624010^COMERCIO^ COMPRA VENTA DE MADERA|6625018^COMERCIO^ COMPRA VENTA DE VIDRIOS CRISTALES Y EMPLOMADOS|6626016^COMERCIO^ COMPRA VENTA DE ANILINAS|6626024^COMERCIO^ COMPRA VENTA DE ARTICULOS DE TLAPALERIA|6626032^COMERCIO^ COMPRA VENTA DE COSTALES Y BOLSAS|6627014^COMERCIO^ COMPRA DE ARTICULOS SANITARIOS|6628012^COMERCIO^ COMPRA VENTA DE MATERIAL PARA INSTALACIONES ELECTRICAS Y DE SONIDO|6629010^COMERCIO^ COMPRA VENTA DE MATERIALES PARA CONSTRUCCION|6629028^COMERCIO^ COMPRA VENTA DE TAPICES|6691019^COMERCIO^ COMPRA VENTA DE FERTILIZANTES Y PLAGUICIDAS|6692017^COMERCIO^ COMPRA VENTA DE ARTICULOS DE PELETERIA|6693015^COMERCIO^ COMPRA VENTA DE DESPERDICIOS DE PAPEL|6694013^COMERCIO^ COMPRA VENTA DE FIERRO USADO|6695011^COMERCIO^ COMPRAVENTA DE SUBSTANCIAS QUIMICAS PARA LA INDUSTRIA|6699013^COMERCIO^ COMPRA VENTA DE CERAS Y PARAFINAS|6699021^COMERCIO^ COMPRA VENTA DE DESPERDICIOS INDUSTRIALES|6699039^COMERCIO^ COMPRA VENTA DE MATERIAS PRIMAS DE ORIGEN MINERAL|6699047^COMERCIO^ COMPRAVENTA DE OTROS METALES Y MINERALES LAMINADOS Y EN LINGOTES|6711015^COMERCIO^ COMPRA VENTA DE MAQUINARIA EQUIPO E IMPLEMENTOS PARA LA AGRICULTURA Y LA GANADERIA|6712013^COMERCIO^ COMPRA VENTA DE ARTICULOS PARA LA EXPLOTACION DE MINAS|6713011^COMERCIO^ COMPRA VENTA DE MAQUINARIA EQUIPO IMPLEMENTOS Y HERRAMIENTAS PARA TRABAJAR LA MADERA METALES Y OTROS MATERIALES|6714019^COMERCIO^ COMPRA VENTA DE MAQUINARIA NUEVA|6714027^COMERCIO^ COMPRA VENTA DE MAQUINARIA USADA|6714035^COMERCIO^ COMPRAVENTA DE EQUIPO DE TRABAJO Y PROTECCION INDUSTRIAL|6719019^COMERCIO^ COMPRA VENTA DE REFACCIONES PARA MAQUINARIA|6721014^COMERCIO^ COMPRA VENTA DE MAQUINAS DE ESCRIBIR|6721022^COMERCIO^ COMPRA VENTA DE MUEBLES Y ARTICULOS PARA OFICINA|6721036^COMERCIO^ COMPRAVENTA DE EQUIPO DE PROCESAMIENTO ELECTRÓNICO DE DATOS|6722012^COMERCIO^ COMPRA VENTA DE EQUIPO Y MOBILIARIO PARA HOTELES RESTAURANTES BILLA RES BOLICHES PELUQUERIAS Y SALONES DE BELLEZA|6729018^COMERCIO^ COMPRA VENTA DE EQUIPOS DE REFRIGERACION COMERCIAL|6729026^COMERCIO^ COMPRA VENTA DE ACCESORIOS PARA MUEBLES|6729034^COMERCIO^ COMPRA VENTA DE EQUIPO CONTRA INCENDIO|6731013^COMERCIO^ COMPRA VENTA DE BASCULAS BALANZAS Y APARATOS SIMILARES PARA EQUIPO PESADO|6732011^COMERCIO^ COMPRA VENTA DE ARTICULOS MEDICOS|6732029^COMERCIO^ COMPRA VENTA DE ARTICULOS Y APARATOS ORTOPEDICOS|6732037^COMERCIO^ COMPRA VENTA DE MUEBLES PARA CONSULTORIOS Y SANATORIOS|6732045^COMERCIO^ COMPRAVENTA DE EQUIPOS QUIMICOS Y PARA LABORATORIO|6739017^COMERCIO^ COMPRA VENTA DE APARATOS CIENTIFICOS Y DE PRECISION|6811013^COMERCIO^ COMPRA VENTA DE AUTOMOVILES Y CAMIONES NUEVOS|6812011^COMERCIO^ COMPRA VENTA DE AUTOMOVILES Y CAMIONES USADOS|6813019^COMERCIO^ COMPRA VENTA DE BICICLETAS Y SUS ACCESORIOS|6813027^COMERCIO^ COMPRA VENTA DE MOTOCICLETAS Y SUS ACCESORIOS|6814017^COMERCIO^ COMPRA VENTA DE LLANTAS|6815015^COMERCIO^ COMPRAVENTA DE REFACCIONES Y ACCESORIOS NUEVOS PARA AUTOS Y CAMIONES|6816013^COMERCIO^ COMPRA VENTA DE REFACCIONES Y ACCESORIOS USADOS PARA AUTOMOVILES Y CAMIONES|6819017^COMERCIO^ COMPRA VENTA DE PARTES Y REFACCIONES PARA VEHICULOS AEREOS|6819025^COMERCIO^ COMPRA VENTA DE VEHICULOS ACUATICOS Y SUS REFACCIONES|6819033^COMERCIO^ COMPRAVENTA DE VEHICULOS AEREOS|6911011^COMERCIO^ PRESTAMOS PARA LA ADQUISISION DE VIVIENDA PARA ACREDITADOS DE INGRESOS MINIMOS|6911029^COMERCIO^ COMPRA DE CASA HABITACION|6911037^COMERCIO^ PRESTAMOS PARA LA ADQUISICION DE VIVIENDA TANTO DE INTERES SOCIAL COMO PROVENIENTES DE LA RESERVA PARA PENSIONES DEL PERSONAL|6911045^COMERCIO^ COMPRA VENTA DE CASAS Y OTROS INMUEBLES|6911053^COMERCIO^ COMPRA VENTA DE TERRENOS|6911061^COMERCIO^ ACTUALIZACION MENSUAL DEL SALDO OPERACIONES DE CREDITO HIPOTECARIO|6911079^COMERCIO^ ADQUISICION DE VIVIENDA TIPO MEDIO|6911087^COMERCIO^ USUARIOS MENORES ADQUISICIÓN DE VIVIENDA TIPO MEDIO|6911095^COMERCIO^ ADQUISICION DE VIVIENDA RESIDENCIAL|6911102^COMERCIO^ USUARIOS MENORES DE ADQUISICION DE VIVIENDA RESINDENCIAL|6991013^COMERCIO^ COMPRA VENTA DE ARMAS DE FUEGO|6992011^COMERCIO^ AGENCIAS DE RIFAS Y SORTEOS (QUINIELAS Y LOTERIA)|6993019^COMERCIO^ COMPRAVENTA DE PRODUCTOS VETERINARIOS Y OTROS ARTICULOS PARA EL CUIDADO DE LOS ANIMALES|6999017^COMERCIO^ COMPRA VENTA DE DIAMANTES|6999025^COMERCIO^ COMPRA VENTA DE EQUIPOS Y APARATOS DE AIRE ACONDICIONADO|6999033^COMERCIO^ COMPRAVENTA DE JARCIERIA REATAS CANASTAS Y ARTICULOS TEJIDOS DE FIBRA|6999041^COMERCIO^ COMPRA VENTA DE OTROS ARTICULOS DE PLASTICO|6999059^COMERCIO^ USUARIOS MENORES POR OPERACIONES DE REPORTO|6999067^COMERCIO^ COMPRAVENTA DE OTROS PRODUCTOS DE HULE|6999075^COMERCIO^ COMPRA VENTA DE VELAS Y VELADORAS|6999083^COMERCIO^ CREDITOS PERSONALES AL CONSUMO|6999091^COMERCIO^ PRESTAMOS AL PERSONAL DE LA INSTITUCION|6999108^COMERCIO^ COMPRA VENTA DE ARTICULOS NO CLASIFICADOS EN OTRA PARTE|6999116^COMERCIO^ TARJETA DE CREDITO|6999124^COMERCIO^ CREDITOS PARA ADQUISICION DE BIENES DE CONSUMO DURADERO|6999900^COMERCIO^ USUARIOS MENORES COMERCIO|6999991^COMERCIO^ CARTERA DE COMERCIO DE ESTADOS ANALITICOS|6999992^COMERCIO^ CENTROS CAMBIARIOS|7111016^TRANSPORTE Y COMUNICACIONES^ TRANSPORTE EN AUTOBUS URBANO Y SUBURBANO DE PASAJEROS|7112014^TRANSPORTE Y COMUNICACIONES^ TRANSPORTE EN AUTOBUS FORANEO DE PASAJEROS|7113012^TRANSPORTE Y COMUNICACIONES^ TRANSPORTE EN AUTOMOVIL DE RULETEO|7114010^TRANSPORTE Y COMUNICACIONES^ TRANSPORTE EN AUTOMOVILES DE SITIO Y TURISMO|7115018^TRANSPORTE Y COMUNICACIONES^ TRANSPORTE EN AUTOMOVIL DE RUTA FIJA|7119010^TRANSPORTE Y COMUNICACIONES^ AUTOTRANSPORTE ESCOLAR TURISTICO EN AUTOBUS Y OTROS ESPECIALIZADOS EXCEPTO AMBULANCIAS|7121015^TRANSPORTE Y COMUNICACIONES^ AUTOTRANSPORTE DE MATERIALES DE CONSTRUCCION|7122013^TRANSPORTE Y COMUNICACIONES^ AGENCIA DE MUDANZAS|7123011^TRANSPORTE Y COMUNICACIONES^ AUTOTRANSPORTE DE CARGA DE PRODUCTOS ESPECIFICOS|7129019^TRANSPORTE Y COMUNICACIONES^ TRANSPORTE DE CARGA FORANEA|7129902^TRANSPORTE Y COMUNICACIONES^ USUARIOS MENORES TRANSPORTES|7129910^TRANSPORTE Y COMUNICACIONES^ CARTERA DE TRANSPORTES DE ESTADOS ANALITICOS|7131014^TRANSPORTE Y COMUNICACIONES^ FERROCARRILES|7132012^TRANSPORTE Y COMUNICACIONES^ TRANVIAS Y TROLEBUSES|7133010^TRANSPORTE Y COMUNICACIONES^ TRANSPORTE EN FERROCARRIL URBANO (METRO)|7191018^TRANSPORTE Y COMUNICACIONES^ TRANSPORTE POR DUCTOS|7211014^TRANSPORTE Y COMUNICACIONES^ AGENCIA DE VAPORES Y BUQUES|7212012^TRANSPORTE Y COMUNICACIONES^ TRANSPORTES MARITIMOS DE CABOTAJE|7291016^TRANSPORTE Y COMUNICACIONES^ TRANSPORTE FLUVIAL Y LACUSTRE|7299010^TRANSPORTE Y COMUNICACIONES^ CARGA Y ESTIBA PORTUARIA|7311012^TRANSPORTE Y COMUNICACIONES^ TRANSPORTACION AEREA DE PASAJEROS|7312010^TRANSPORTE Y COMUNICACIONES^ SERVICIOS RELACIONADOS CON EL TRANSPORTE EN AERONAVES CON MATRICULA EXTRANJERA|7411010^TRANSPORTE Y COMUNICACIONES^ ADMINISTRACION DE CAMINOS PUENTES Y SERVICIOS AUXILIARES|7412018^TRANSPORTE Y COMUNICACIONES^ AEROPUERTO CIVIL|7413016^TRANSPORTE Y COMUNICACIONES^ ADMINISTRACION DE PUERTOS MARITIMOS LACUSTRES Y FLUVIALES|7414014^TRANSPORTE Y COMUNICACIONES^ ADMINISTRACION DE CENTRALES CAMIONERAS Y SERVICIOS AUXILIARES|7511018^TRANSPORTE Y COMUNICACIONES^ SERVICIOS DE ALMACENAMIENTO Y REFRIGERACION|7512016^TRANSPORTE Y COMUNICACIONES^ AGENCIA DE TURISMO|7513014^TRANSPORTE Y COMUNICACIONES^ AGENCIA ADUANAL|7514012^TRANSPORTE Y COMUNICACIONES^ SERVICIOS DE BASCULA Y DE GRUA PARA VEHICULOS|7519012^TRANSPORTE Y COMUNICACIONES^ AGENCIA DE FERROCARRIL|7519020^TRANSPORTE Y COMUNICACIONES^ ALQUILER DE LANCHAS Y VELEROS|7519038^TRANSPORTE Y COMUNICACIONES^ RENTA DE VEHICULOS AEREOS|7611016^TRANSPORTE Y COMUNICACIONES^ EMPRESA DE TELEFONOS|7612014^TRANSPORTE Y COMUNICACIONES^ EMPRESA DE TELEGRAFOS|7613004^TRANSPORTE Y COMUNICACIONES^ OTROS SERVS. DE TELECOMUNICACIONES (EXCEPTO RADIO)|7613012^TRANSPORTE Y COMUNICACIONES^ OTROS SERVS. DE TELECOMUNICACIONES (EXCEPTO RADIO).|7613905^TRANSPORTE Y COMUNICACIONES^ MENORES COMUNICACIONES|7613913^TRANSPORTE Y COMUNICACIONES^ CARTERA DE COMUNICACIONES DE ESTADOS ANALITICOS|7614010^TRANSPORTE Y COMUNICACIONES^ SERVICIOS POSTALES|8113011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE LA BANCA NACIONAL|8113029^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE LA BANCA NACIONAL POR OPERACIONES DE CALL MONEY|8113128^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ MENORES BANCA DESARROLLO|8114019^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE FONDOS Y FIDEICOMISOS DE FOMENTO ECONOMICO|8123010^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE LA BANCA PRIVADA Y MIXTA MULTIPLE|8123028^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE LA BANCA PRIVADA ESPECIALIZADA|8123036^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE LA BANCA PRIVADA Y MIXTA MULTIPLE POR OPERACIONES DE CALL MONEY|8123044^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE LA BANCA PRIVADA ESPECIALIZADA POR OPERACIONES DE CALL MONEY|8123052^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SOCIEDADES DE AHORRO Y PRÉSTAMO|8123060^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SOCIEDADES D E AHORRO Y CRÉDITO POPULAR|8123078^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SOCIEDADES FINANCIERAS DE OBJETO LIMITADO|8123143^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ USUARIOS MENORES DE BANCA COMERCIAL|8123903^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ USUARIOS MENORES DE SERVICIOS BANCARIOS|8123911^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CARTERA DE SERVICIOS BANCARIOS DE ESTADOS ANALITIC|8131013^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ALMACENES DE DEPOSITO NACIONALES|8131021^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ALMACENES DE DEPOSITO PRIVADOS|8132011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ UNIONES DE CREDITO NACIONALES|8132029^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ UNIONES DE CREDITO PRIVADAS|8133019^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ COMPAÑIAS DE FIANZAS NACIONALES|8133027^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ COMPAÑIAS DE FIANZAS PRIVADAS|8133035^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ AJ. INT. FINANCIEROS NO BANCARIOS UDIS|8141012^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ BOLSA DE VALORES|8142010^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SOCIEDADES DE INVERSION|8151011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ COMPAÑIAS DE SEGUROS NACIONALES|8151029^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ COMPAÑIAS DE SEGUROS PRIVADAS|8211013^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ INVERSIONISTA|8211021^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ AGENTE DE BOLSA|8211039^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ BOLSA DE VALORES|8211047^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CASAS DE BOLSA PRIVADAS|8219017^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ AGENTE DE SEGUROS|8219025^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CASA DE CAMBIO|8219033^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CORRESPONSAL BANCARIO|8219041^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CAJA DE AHORROS|8219059^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ MONTEPIO|8219067^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ PRESTAMISTA|8219075^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ FACTORING|8219083^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ EMPRESAS CONTROLADORAS FINANCIERAS|8219091^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ USUARIOS MENORES OTROS INTERMEDIARIOS FINANCIEROS PÚBLICOS|8219108^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ USUARIOS MENORES OTRO INTERMEDIARIOS FINANCIEROS PRIVADOS|8219114^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ADMINISTRADORAS DE TARJETA DE CRÉDITO|8219122^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ EMPRESAS DE AUTOFINANCIAMIENTO AUTOMOTRIZ|8219130^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ EMPRESAS DE AUTOFINANCIAMIENTO RESIDENCIAL|8220014^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ FOBAPROA-IPAB|8221013^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ FOBAPROA-IPAB|8311011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ALQUILER DE TERRENOS LOCALES Y EDIFICIOS NO RESIDENCIALES|8312019^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ARRENDAMIENTO DE INMUEBLES RESIDENCIALES|8313017^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIO DE CORREDORES DE BIENES RAICES|8314015^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ADMINISTRACION DE INMUEBLES|8411019^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE NOTARIAS PUBLICAS|8412017^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE BUFETES JURIDICOS|8413015^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE CONTADURIA Y AUDITORIA; INCLUSO TENEDURIA DE LIBROS|8414013^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE ASESORIA Y ESTUDIOS TECNICOS DE ARQUITECTURA E INGENIERIA; INCLUSO DISEÑO INDUSTRIAL|8415011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ DESPACHO DE OTROS PROFESIONISTAS|8419013^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIO DE INVESTIGACION DE MERCADO SOLVENCIA FINANCIERA DE PATENTES Y MARCAS INDUSTRIALES Y OTROS SIMILARES|8421018^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE ANALISIS DE SISTEMAS Y PROCESAMIENTO ELECTRONICO DE DATOS|8422016^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ AGENCIA DE PUBLICIDAD|8423014^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ AGENCIA NOTICIOSA|8424012^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS ADMINISTRATIVOS DE TRAMITE Y COBRANZA INCLUSO ESCRITORIOS PUBLICOS|8425010^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE COPIAS FOTOSTATICAS XEROGRAFICAS Y SIMILARES|8426018^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ COMISIONISTA|8426026^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ REPRESENTACION DE ARTISTAS|8426034^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ REPRESENTANTE CASAS EXTRANJERAS|8426042^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ REPRESENTANTE CASAS NACIONALES|8427016^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE INSTALACION Y MANTENIMIENTO DE MAQUINARIA Y EQUIPO POR EMPRESAS ESPECIALIZADAS|8428014^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE AGENCIAS DE COLOCACION Y SELECCION DE PERSONAL|8429012^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ PRESTACION DE OTROS SERVICIOS TECNICOS|8429020^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ EMPRESAS CONTROLADORAS NO FINANCIERAS|8429038^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ EMPRESAS DE SEGURIDAD PRIVADA|8429046^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ EMPRESAS TRANSPORTADORAS DE VALORES|8511017^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ARRENDAMIENTO DE MAQUINARIA|8511025^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ARRENDADORAS FINANCIERAS NACIONALES|8511033^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ARRENDADORAS FINANCIERAS PRIVADAS|8512015^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ARRENDAMIENTO DE EQUIPO PARA PROCESAMIENTO ELECTRONICO DE DATOS|8519011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ALQUILER DE MOBILIARIO Y EQUIPO PARA COMERCIOS SERVICIOS Y OFICINAS|8521016^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SALON DE FIESTAS|8522014^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ALQUILER DE SILLAS Y VAJILLAS PARA BANQUETES|8523012^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ALQUILER DE EQUIPOS ELECTRONICOS|8524010^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ALQUILER O RENTA DE AUTOMOVILES SIN CHOFER|8529010^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ALQUILER DE ROPA|8611015^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ HOTEL|8612013^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE ALOJAMIENTO EN MOTELES|8613011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CAMPOS DE TURISMO|8619019^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CASA DE HUESPEDES|8711013^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CAFETERIA|8711021^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ RESTAURANTE|8712011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE ALIMENTOS EN LONCHERIAS TAQUERIAS Y TORTERIAS|8713019^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS EN OSTIONERIAS Y PREPARACION DE OTROS MARISCOS Y PESCADOS|8714017^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ NEVERIA Y REFRESQUERIA|8719017^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS EN MERENDEROS CENADURIAS Y PREPARACION DE ANTOJITOS Y PLATILLOS REGIONALES|8721012^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ BARES Y CANTINAS|8722010^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CERVECERIA|8723018^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS EN PULQUERIAS|8723026^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ USUARIOS MENORES TURISMO|8811011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS EN BALNEARIOS Y ALBERCAS|8812019^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SALON DE BILLAR|8812027^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SALON DE BOLICHE|8813017^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CLUB SOCIAL|8814015^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CENTRO DEPORTIVO|8819015^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS Y EXPLOTACION DE PLAYAS Y PARQUES DE DESCANSO CLUBES DE EXCURSIONISMO DE CAZA Y PESCA INCLUSO ALQUILER DE LANCHAS CABALLOS CALESA|8821010^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ESTUDIOS CINEMATOGRAFICOS|8821028^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ GRABACION DE SONIDO EN PELICULAS|8821036^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ LABORATORIOS CINEMATOGRAFICOS|8821044^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ PRODUCCION DE PELICULAS|8822018^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ DISTRIBUCION Y ALQUILER DE PELICULAS|8823016^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SALA DE CINE|8824014^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ RADIODIFUSORA|8825012^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ TELEDIFUSORA|8826010^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ TEATRO|8827018^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ACTIVIDADES DE CLUBES DEPORTIVOS PROFESIONALES|8829014^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ESTADIOS Y ARENAS|8829022^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ HIPODROMO|8829030^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ PLAZA DE TOROS|8829048^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ PROMOCION DE ESPECTACULOS DEPORTIVOS|8829056^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ PROMOCION DE ESPECTACULOS TAURINOS|8831019^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CENTRO NOCTURNO|8832017^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ GALERIAS DE ARTES GRAFICAS Y MUSEOS|8833015^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ FEDERACIONES Y ASOCIACIONES DEPORTIVAS Y OTRAS CON FINES RECREATIVOS|8839013^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ JUEGOS DE FERIA|8839906^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ USUARIOS MENORES CINEMATOGRAFÍA Y ESPARCIMIENTO|8839914^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CARTERA DE SERVICIOS DE ESPARCIMIENTO DE ESTADOS A|8911019^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ TALLER DE REPARACION GENERAL DE AUTOMOVILES Y CAMIONES|8912017^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE REPARACION DE MOTOCICLETAS Y BICICLETAS|8913015^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE REPARACION ESPECIALIZADO EN PARTES DE AUTOMOVILES EXCEPTO RECONSTRUCCION DE MOTORES Y CARROCERIAS|8914013^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE REPARACION DE CARROCERIAS PINTURA TAPICERIA HOJALATERIAY CRISTALES DE AUTOMOVILES|8915011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE LAVADO Y LUBRICACION DE AUTOMOVILES|8916019^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ESTACIONAMIENTO PRIVADO PARA VEHICULOS|8916027^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ESTACIONAMIENTO PUBLICO PARA VEHICULOS|8919013^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIO DE REPARACION DE VEHICULOS DE TRACCION ANIMAL Y PROPULSION A MANO|8921018^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ TALLER DE REPARACION DE CALZADO|8922016^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ TALLER DE REPARACION DE APARATOS ELECTRICOS Y ELECTRONICOS INDUSTRIALES Y COMERCIALES|8922024^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ TALLER DE REPARACION DE ARTICULOS ELECTRICOS Y ELECTRONICOS PARA EL HOGAR|8923014^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE REPARACION DE RELOJES Y JOYAS|8924012^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ TALLER DE TAPICERIA|8929012^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ TALLER DE REPARACION DE PARAGUAS Y SOMBRILLAS|8929020^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ TALLER DE REPARACION DE ROPA Y MEDIAS|8929038^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ TALLER DE REPARACION DE SOMBREROS|8931017^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ BA/OS|8932015^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ BARBERIA Y PELUQUERIA|8933013^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SALON DE BELLEZA|8934011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ LAVANDERIA|8934029^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ TINTORERIA Y PLANCHADURIA|8935019^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ EMPRESA DE SERVICIO DE LIMPIEZA|8936017^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE FUMIGACION DESINFECCION Y CONTROL DE PLAGAS|8939011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ SERVICIOS DE BOLERIAS MASAJISTAS SANITARIOS PUBLICOS Y OTROS SERVICIOS DE ASEO Y LIMPIEZA|8941016^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ ESTUDIO FOTOGRAFICO|8942014^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ DECORACION EN GENERAL|8943012^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ AGENCIA DE INHUMACIONES|8944010^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ PANTEON|8944028^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ USUARIOS MENORES SERVICIOS PROFESIONALES Y TÉCNICOS|8944098^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ EMPLEADO PRIVADO|8949010^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ TAXIDERMISTA|8949888^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ USUARIOS MENORES OTRAS ACTIVIDADES|8949896^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CARTERA DE OTRAS ACTIVIDADES DE ESTADOS ANALITICOS|8949903^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ USUARIOS MENORES DE SERVICIOS|8949911^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ CARTERA DE SERVICIOS DE ESTADOS ANALITICOS|8991011^SERVICIOS PARA EMPRESAS, PERSONAS, EL HOGAR Y DIVERSOS^ QUEHACERES DEL HOGAR|9001700^SERVICIOS SOCIALES Y COMUNALES^ PRUEBAS|9112012^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS DE ENSEÑANZA PREPRIMARIA Y PRIMARIA|9113010^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS DE ENSEÑANZA SECUNDARIA|9115016^SERVICIOS SOCIALES Y COMUNALES^ ESTABLECIMIENTOS PUBLICOS DE INSTRUCCION EDUCACION SUBPROFESIONAL Y PROFESIONAL CULTURA E INVESTIGACIÓN|9119018^SERVICIOS SOCIALES Y COMUNALES^ ESTABLECIMIENTOS PRIVADOS DE INSTRUCCION EDUCACION CULTURA E INVESTIGACIÓN|9121013^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS DE ENSEÑANZA COMERCIAL Y DE IDIOMA|9122011^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS DE CAPACITACION TECNICA DE OFICIOS Y ARTESANIAS|9129017^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS DE ENSEÑANZA DE MUSICA DANZA Y OTRAS ARTES CULTURA FISICA|9191016^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS DE INVESTIGACION CIENTIFICA|9199010^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS DE BIBLIOTECAS MUSEOS JARDINES BOTANICOS Y OTROS SERVICIOSDE DIFUSION CULTURAL|9211012^SERVICIOS SOCIALES Y COMUNALES^ HOSPITALES SANATORIOS CLINICAS Y MATERNIDADES|9212010^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS MEDICO GENERAL Y ESPECIALIZADO EN CONSULTORIOS|9213018^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS DE CONSULTORIOS Y CLINICAS DENTALES|9214016^SERVICIOS SOCIALES Y COMUNALES^ LABORATORIOS DE ANALISIS CLINICOS|9215014^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS DE LABORATORIOS DE RADIOLOGIA Y RADIOSCOPIA|9219016^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS DE FISIOTERAPIA BANCOS DE SANGRE Y OTROS SERVICIOS MEDICOS|9221011^SERVICIOS SOCIALES Y COMUNALES^ CENTRO DE BENEFICENCIA|9231010^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS VETERINARIOS EN CLINICAS Y CONSULTORIOS INCLUSO ZOOTECNISTAS PENSIONES DE ANIMALES Y OTROS SERVICIOS AUXILIARES|9231028^SERVICIOS SOCIALES Y COMUNALES^ USUARIOS MENORES SERVICIOS MÉDICOS|9311010^SERVICIOS SOCIALES Y COMUNALES^ ASOCIACIONES Y CONFEDERACIONES|9311028^SERVICIOS SOCIALES Y COMUNALES^ CAMARAS DE COMERCIO|9311036^SERVICIOS SOCIALES Y COMUNALES^ CAMARAS INDUSTRIALES|9311044^SERVICIOS SOCIALES Y COMUNALES^ SOCIEDADES COOPERATIVAS|9312018^SERVICIOS SOCIALES Y COMUNALES^ ORGANIZACIONES DE ABOGADOS MEDICOS INGENIEROS Y OTRAS ASOCIACIO CIONES DE PROFESIONALES|9319014^SERVICIOS SOCIALES Y COMUNALES^ ORGANIZACIONES CIVICAS|9321019^SERVICIOS SOCIALES Y COMUNALES^ ORGANIZACIONES LABORALES Y SINDICALES|9322017^SERVICIOS SOCIALES Y COMUNALES^ ORGANIZACIONES POLITICAS|9331018^SERVICIOS SOCIALES Y COMUNALES^ ORGANIZACIONES RELIGIOSAS|9411018^SERVICIOS SOCIALES Y COMUNALES^ GOBIERNO FEDERAL|9411026^SERVICIOS SOCIALES Y COMUNALES^ GOBIERNO ESTATAL|9411034^SERVICIOS SOCIALES Y COMUNALES^ GOBIERNO MUNICIPAL|9411042^SERVICIOS SOCIALES Y COMUNALES^ DEPARTAMENTO DEL DISTRITO FEDERAL|9411886^SERVICIOS SOCIALES Y COMUNALES^ USUARIOS MENORES GOBIERNO FEDERAL|9411894^SERVICIOS SOCIALES Y COMUNALES^ CARTERA DE GOBIERNO FEDERAL DE ESTADOS ANALITICOS|9411901^SERVICIOS SOCIALES Y COMUNALES^ USUARIOS MENORES GOBIERNO ESTATAL Y MUNICIPAL|9411919^SERVICIOS SOCIALES Y COMUNALES^ CARTERA DE GOBIERNO ESTATAL Y MUNICIPAL DE ESTADOS|9411998^SERVICIOS SOCIALES Y COMUNALES^ EMPLEADO PUBLICO|9471012^SERVICIOS SOCIALES Y COMUNALES^ PRESTACION DE SERVICIOS PUBLICOS Y SOCIALES|9911018^SERVICIOS SOCIALES Y COMUNALES^ INSTITUCIONES FINANCIERAS DEL EXTRANJERO|9912016^SERVICIOS SOCIALES Y COMUNALES^ CONSULADO|9912024^SERVICIOS SOCIALES Y COMUNALES^ GOBIERNO EXTRANJERO|9919012^SERVICIOS SOCIALES Y COMUNALES^ SERVICIOS DE OFICINAS Y REPRESENTACIONES DE OTROS PAISES QUE GOZAN DE EXTRATERRITORIALIDAD";

	var $aplicar=false;
	var $numempresas; 	//para numero de empresas en el reporte
	var $cantidadc;		//para el total de cantidad del detalle

	var $lhd; //1
	var $lem; //2
	var $lac; //3
	var $lcr; //4
	var $lde; //5
	var $lav; //6
	var $lts; //7
	var $linstitucion;//8
	var $lestados;//9
	var $lcartera;//10
	var $lcreditos;//11
	var $lmoneda;//12
	var $lpais;//13
	var $lbanxico;//14
	var $tempc0;
	var $tempc1;




	function buroclass()
	{
		$this->generalista($this->hd,1);
		$this->generalista($this->em,2);
		$this->generalista($this->ac,3);
		$this->generalista($this->cr,4);
		$this->generalista($this->de,5);
		$this->generalista($this->av,6);
		$this->generalista($this->ts,7);
		$this->generalista($this->institucion,8);
		$this->generalista($this->estados,9);
		$this->generalista($this->cartera,10);
		$this->generalista($this->creditos,11);
		$this->generalista($this->moneda,12);
		$this->generalista($this->pais,13);
		$this->generalista($this->banxico,14);
		$this->numempresas=0;
		$this->cantidadc=0;
		$this->cantidadc=0;
		$this->tempc0=0;
		$this->tempc1=0;

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
					$this->lhd[$id][$idr]=$campo;
					break;
				case 2:
					$this->lem[$id][$idr]=$campo;
					break;
				case 3:
					$this->lac[$id][$idr]=$campo;
					break;
				case 4:
					$this->lcr[$id][$idr]=$campo;
					break;
				case 5:
					$this->lde[$id][$idr]=$campo;
					break;
				case 6:
					$this->lav[$id][$idr]=$campo;
					break;
				case 7:
					$this->lts[$id][$idr]=$campo;
					break;
				case 8:
					$this->linstitucion[$id][$idr]=$campo;
					break;
				case 9:
					$this->lestados[$id][$idr]=$campo;
					break;
				case 10:
					$this->lcartera[$id][$idr]=$campo;
					break;
				case 11:
					$this->lcreditos[$id][$idr]=$campo;
					break;
				case 12:
					$this->lmoneda[$id][$idr]=$campo;
					break;
				case 13:
					$this->lpais[$id][$idr]=$campo;
					break;
				case 14:
					$this->lbanxico[$id][$idr]=$campo;
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
		//$cuando = mktime(0,0,0,mes,dia,año);
		$hoy = time();
		$resta = $hoy - $cuando;
		return round($resta/86400);
	}

// *************** fin de metodos para mostrar datos de cinta *****************************

// ************metodos para formato de datos de cinta ************************************

	function fechaburo($fechaval,$tipo)
	{
	//Función para transformar la fecha en el formato establecido en 2 tipos Y
	//DEBE REGRESAR TEXTO DEBE RECIBIR
	//$fecha: UNA FECHA DE TIPO AAAA-MM-DD
	//$tipo un numero que pueda distinguir que informacion regresar como sigue
	//1: DDMMAAAA   (DIA MES AÑO)
	//2: MMAAAA	(MES AÑO)

		$resultado="";
		$aux="";
		switch($tipo)
		{
		case 1:
			$aux = 	split('[/.-]', $fechaval);
			$resultado = $aux[2] . $aux[1] . $aux[0];

			break;
		case 2:
			$aux = 	split('[/.-]', $fechaval);
			$resultado =$aux[1] . $aux[0];
		}

		return $resultado;


	}

	function textoburo($dato,$longitud)
	{
	//Función que genera el texto ordenado como el buró lo solicita y rellena espacios
	//según la longitud requerida
		//VERIFICAR ANTES LOS SIMBOLOS PARA QUITAR ETIQUETAS


		//$ltexto= strlen($this->quitabasura($dato));
		//$diferencia = $longitud - $ltexto ;
		////$resultado = str_pad($dato, $diferencia, " ", STR_PAD_RIGHT);
		//$resultado = str_pad($this->quitabasura($dato), $longitud, " ", STR_PAD_RIGHT);

		$nuevot=$this->quitabasura($dato);
		$ltexto= strlen($nuevot);

		if($ltexto>$longitud)
		{
			$nuevot=substr($nuevot,0,$longitud);
			$ltexto=$longitud;
		}

		$diferencia = $longitud - $ltexto ;
		//$resultado = str_pad($dato, $diferencia, " ", STR_PAD_RIGHT);
		$resultado = str_pad($nuevot, $longitud, " ", STR_PAD_RIGHT);




		return strtoupper($resultado);

	}

	function numeroburo($dato,$longitud)
	{
	//Función que rellena coloca los datos numericos según le buró, números enteros,
	//positivos y rellena ceros a la izquierda
		//echo $dato . " " . $longitud . "<br>";
		//$dato = str_replace(",","",number_format  ( $dato ,0 ));
		//solo texto inicial hasta donde exista un punto para quitar los desimales

		$resultado = "";

		/*
		$ltexto=(int)$dato;
		$ltexto=(string)$ltexto;
		*/


		$ltexto=(string)$dato;

		$p1=strpos($ltexto,".");
		if($p1 !== false)
		{
			//$ltexto= trim(substr($ltexto,0,$p1-1));

			$ltexto=(int)$ltexto;
			$ltexto=(string)$ltexto;


		}



		$resultado = str_pad($ltexto, $longitud, "0", STR_PAD_LEFT);



		return $resultado;

	}

	function rfcburo($dato)
	{
	//PARA VERIFICAR Y DAR FORMATO CORRECTO AL RFC SEGUN EL BURÓ

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

		$residuo = str_replace("ñ", "n", $residuo);
		$residuo = str_replace("á", "a", $residuo);
		$residuo = str_replace("é", "e", $residuo);
		$residuo = str_replace("í", "i", $residuo);
		$residuo = str_replace("ó", "o", $residuo);
		$residuo = str_replace("ú", "u", $residuo);
		$residuo = str_replace("ü", "u", $residuo);
		$residuo = str_replace("Ñ", "N", $residuo);
		$residuo = str_replace("Á", "A", $residuo);
		$residuo = str_replace("É", "E", $residuo);
		$residuo = str_replace("Í", "I", $residuo);
		$residuo = str_replace("Ó", "O", $residuo);
		$residuo = str_replace("Ú", "U", $residuo);
		$residuo = str_replace("Ü", "U", $residuo);
		$residuo = str_replace(",", "", $residuo);
		$residuo = str_replace(".", "", $residuo);
		$residuo = str_replace(">", "", $residuo);
		$residuo = str_replace("*", "&", $residuo);

		return $residuo;



	}
//******************** fon de metodos de fotmato de cinta ****************************

//******************* metodos que generan la cinta por secciones COMERCIAL ********************

		function gchd()
		{//Genera para empresa Comercial la HD

			$resultado = "";
			$dato="";
			foreach($this->lhd as $idl => $aux1)
			{

			    //echo $aux1[3] . "," . $aux1[1] . "<br>";
			    $resultado .= $this->numeroburo($aux1[0],2) . "";
			    if(substr($aux1[3],0,2)=="Re")
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
			    		$dato="5470";
			    	}

			    	//echo $dato . "?";
			    	switch(substr($aux1[4],0,2))
			    	{
			    	case 'Nu':
					$resultado .= $this->numeroburo($dato,$aux1[5]) . "";
			    		break;

			    	case 'Te':
			    		$resultado .= $this->textoburo($dato,$aux1[5])  . "";
			    		break;
			    	case 'F1':
			    		$dato=date("Y-m-d");
					$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]). "";
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
			    		$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5])  . "";
			    		break;

			    	}

			    }
			    else
			    {
			    	switch(substr($aux1[4],0,2))
			    	{
			    	case 'Nu':
					$resultado .= $this->numeroburo("0",$aux1[5]) . "";
			    		break;

			    	case 'Te':
			    		$resultado .= $this->textoburo(" ",$aux1[5])  . "";
			    		break;
			    	}

			    }

			    $dato="";
			}
			return $resultado;
		}



		function gcem()
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
			//$sqlem = "select idinquilino, nombre, nombre2, apaterno, amaterno, nombrenegocio, rfc from (select idinquilino as idi, count(idcontrato) as num from contrato where concluido = false   group by idinquilino) c, inquilino p where c.idi = p.idinquilino" ;
			$sqlem = "select idinquilino, nombre, nombre2, apaterno, amaterno, nombrenegocio, rfc from (select idinquilino as idi, count(idcontrato) as num from contrato where concluido = false and idcontrato in(211,278)   group by idinquilino) c, inquilino p where c.idi = p.idinquilino" ;
			$resultado = "";
			$dato="";
			$operacion = mysql_query($sqlem);
			$this->numempresas=mysql_num_rows($operacion);
			while($row = mysql_fetch_array($operacion))
			{

				//$sqldirec="select idcontrato, calle, numeroext, numeroint, colonia, delmun, cp, clavee, clavep from contrato c, inmueble i, pais p, estado e where c.concluido = false and c.litigio = false and c.idinmueble = i.idinmueble and i.idestado = e.idestado and i.idpais=p.idpais and idinquilino = " . $row["idinquilino"];
				$sqldirec="select idcontrato, direccionf, inq.colonia, delegacion, inq.cp, clavee, clavep from contrato c, inmueble i, pais p, estado e, inquilino inq where c.concluido = false and c.idinmueble = i.idinmueble and inq.idestado = e.idestado and i.idpais=p.idpais and inq.idinquilino = " . $row["idinquilino"];
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

				//verificar el RFC para determinar los nombres a mostrar



				foreach($this->lem as $idl => $aux1)
				{

				    $resultado .= $this->numeroburo($aux1[0],2);// . "|";
				    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
				    if(substr($aux1[3],0,2)=="Re")
				    {

			    	//hacer la distinción para el campo 23 verificar el rfc para colocar 1 o 2 para PM o PFAE como en accionistas

				    	if ($idl=="24")
				    	{
				    		if(strlen($rfc)==12)
				    		{
						    	$resultado .= $this->numeroburo("1" ,$aux1[5]);// . "|";
						}
						else
						{
							$resultado .= $this->numeroburo("2" ,$aux1[5]);// . "|";
						}

				    	}
				    	else
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
							$resultado .= $this->numeroburo($dato ,$aux1[5]);// . "|";
				    			break;

				    		case 'Te':
				    			if(strlen($dato)>$aux1[5])
				    			{
				    				$dato=substr($dato,0,(integer)$aux1[5]);

				    			}
				    			$resultado .= $this->textoburo($dato,$aux1[5]) ;// . "|";
				    			break;
				    		case 'F1':
				    			$dato=date("Y-m-d");
							$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]); //. "|";
				    			break;

				    		case 'F2':
				    			$dato=date("Y-m-d");
				    			$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
				    			break;

				    		}
				    	}

				    }
				    else
				    {
				    	switch(substr($aux1[4],0,2))
				    	{
				    	case 'Nu':

						$resultado .= $this->numeroburo("0",$aux1[5]);// . "|";
				    		break;

				    	case 'Te':
				    		$resultado .= $this->textoburo(" ",$aux1[5]) ;// . "|";
				    		break;


				    	}

				    }

				    $dato="";

				}

				$resultado .= $this->gcac($idinquilino) ;
				$resultado .=  $this->gccr($idinquilino);


			}



			return $resultado ;

		}


		//faltan los accionistas, por ahora no estan en la base de datos
		function gcac($id)
		{//Genera para empresa Comercial la ac (accionistas)
		 //aqui se toma los datos del os obligados solidarios del credito en cuestion
		 //aún no esta listo para su operacion


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

			$sqlac = "select rfc, curp, nombreac1, nombreac2, apaternoac, amaterno, porcentaje, direccionac, coloniaac, delegmunac, cpac, telac, clavee, clavep from accionista a, estado e, pais p   where a.idestado = e.idestado and a.idpais = p.idpais and  a.idinquilino = $id ";
			$resultado = "";
			$dato="";
			$operacioncr = mysql_query($sqlac);
			while($row = mysql_fetch_array($operacioncr))
			{

				$rfc=$row["rfc"];
				$curp=$row["curp"];
				$nombre1a=$row["nombreac1"];
				$nombre2a=$row["nombreac2"];
				$apaternoa=$row["apaternoac"];
				$amaternoa=$row["amaterno"];
				$direcciona=$row["direccionac"];
				$coloniaa=$row["coloniaac"];
				$delegaciona=$row["delegmunac"];
				$cpa=$row["cpac"];
				$paisa=$row["clavep"];
				$estadoa=$row["clavee"];
				$porcentaje=$row["porcentaje"];

				//verificar el RFC para determinar los nombres a mostrar



				foreach($this->lac as $idl => $aux1)
				{

				    $resultado .= $this->numeroburo($aux1[0],2);// . "|";
				    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
				    if(substr($aux1[3],0,2)=="Re")//requerido
				    {


				    	if ($idl=="20")
				    	{
				    		if(strlen($rfc)==12)
				    		{
						    	$resultado .= $this->numeroburo("1" ,$aux1[5]);// . "|";
						}
						else
						{
							$resultado .= $this->numeroburo("2" ,$aux1[5]);// . "|";
						}

				    	}
				    	else
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
							$resultado .= $this->numeroburo($dato ,$aux1[5]);// . "|";
				    			break;

				    		case 'Te':
				    			if(strlen($dato)>$aux1[5])
				    			{
				    				$dato=substr($dato,0,(integer)$aux1[5]);

				    			}
				    			$resultado .= $this->textoburo($dato,$aux1[5]);//  . "|";
				    			break;
				    		case 'F1':
				    			$dato=date("Y-m-d");
							$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]);// . "|";
				    			break;

				    		case 'F2':
				    			$dato=date("Y-m-d");
				    			$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
				    			break;
				    		}
				    	}
				    }
				    else
				    {
				    	switch(substr($aux1[4],0,2))
				    	{
				    	case 'Nu':
						$resultado .= $this->numeroburo("0",$aux1[5]);// . "|";
				    		break;

				    	case 'Te':
				    		$resultado .= $this->textoburo(" ",$aux1[5]) ;// . "|";
				    		break;


				    	}

				    }





				    $dato="";

				}
				$resultado .= ""; //detalle

			}
			return $resultado;

		}


		function gccr($id)
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
				    if(substr($aux1[3],0,2)=="Re")
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
						$resultado .= $this->numeroburo($dato ,$aux1[5]);// . "|";
				    		break;

				    	case 'Te':
				    		if(strlen($dato)>$aux1[5])
				    		{
				    			$dato=substr($dato,0,(integer)$aux1[5]);

				    		}
				    		$resultado .= $this->textoburo($dato,$aux1[5]);//  . "|";
				    		break;
				    	case 'F1':
				    		$dato=date("Y-m-d");
						$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]);//. "|";
				    		break;

				    	case 'F2':
				    		$dato=date("Y-m-d");
				    		$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
				    		break;

				    	}


				    }
				    else
				    {
				    	switch(substr($aux1[4],0,2))
				    	{
				    	case 'Nu':

						$resultado .= $this->numeroburo("0",$aux1[5]);// . "|";
				    		break;

				    	case 'Te':
				    		$resultado .= $this->textoburo(" ",$aux1[5]);//  . "|";
				    		break;


				    	}

				    }

				    $dato="";

				}
				$resultado .= $this->gcde($idcontrato); //detalle
				//$resultado .= "<br><br>" . $this->gcav($idcontrato) . "<br><br>"; //avales es opcional, por ahora no lo colocare, falta modificar la tabla

			}
			return $resultado;

		}


		function gcde($id)
		{//Genera para empresa Comercial la de


		//Cambios: hay que hacer la consulta para que verifique los grupos de dias y enviar el txto a cr


			$rfc="";
			$fecha="";
			$vencido="";
			$idcontrato="";
			$aplicado ="";
			$diasv=0;
			$total =0;
			$hoy=date("Y-m-d");

			$sqldea[0]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 150 and 999 order by c.idcontrato ";
			$sqldea[1]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 120 and 149 order by c.idcontrato ";
			$sqldea[2]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 90 and 119 order by c.idcontrato ";
			$sqldea[3]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 60 and 89 order by c.idcontrato ";
			$sqldea[4]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 30 and 59 order by c.idcontrato ";
			$sqldea[5]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 1 and 29 order by c.idcontrato ";
			$sqldea[6]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) <= 0 order by c.idcontrato";//"
			$resultado = "";
			foreach($sqldea as $key=>$sqlde)
			{

				if($key==6)
				{
					$diasv=0;
				}


				$dato="";
				$total =0;
				$diasv=0;
				$operacionde = mysql_query($sqlde);
				while($row = mysql_fetch_array($operacionde))
				{
					$rfc=$row["rfc"];
					$fecha=$row["fechanaturalpago"];
					//$vencido=$row["cvencido"];
					$idcontrato=$row["idcontrato"];
					$aplicado = $row["aplicado"];
					if($key==6)
					{
						$diasv=0;
					}
					else
					{
						if($diasv<$row["diasv"])
						{
							$diasv = $row["diasv"];
						}
					}
					$total += $row["cantidad"];
					$this->cantidadc +=$total;
					//verificar el RFC para determinar los nombres a mostrar
				}

				if(mysql_num_rows($operacionde)>0)
				{

					foreach($this->lde as $idl => $aux1)
					{

					    $resultado .= $this->numeroburo($aux1[0],2);// . "|";
					    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
					    if(substr($aux1[3],0,2)=="Re")
					    {

					    	//hay que colocar la parte relacionada a los dias de vencimiento campo 3


				    		switch($idl)
				    		{


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




					    		//echo $dato . "?";
					    		switch(substr($aux1[4],0,2))
					    		{
					    		case 'Nu':
								$resultado .= $this->numeroburo($dato ,$aux1[5]);// . "|";
					    			break;

					    		case 'Te':
					    			if(strlen($dato)>$aux1[5])
					    			{
					    				$dato=substr($dato,0,(integer)$aux1[5]);

					    			}
					    			$resultado .= $this->textoburo($dato,$aux1[5]);//  . "|";
					    			break;
					    		case 'F1':
					    			$dato=date("Y-m-d");
								$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]);// . "|";
					    			break;

					    		case 'F2':
					    			$dato=date("Y-m-d");
					    			$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
					    			break;

					    		}


					    	}

					    }
					    else
					    {
					    	switch(substr($aux1[4],0,2))
					    	{
					    	case 'Nu':

							$resultado .= $this->numeroburo("0",$aux1[5]);// . "|";
					    		break;

					    	case 'Te':
					    		$resultado .= $this->textoburo(" ",$aux1[5]);// . "|";
					    		break;


					    	}

					    }

					    $dato="";

					}
					$resultado .= ""; //detalle
				}



			}

			return $resultado ;

		}


		function gcav($id)
		{//Genera para empresa Comercial la av (avales)
		 //aqui se toma los datos del os obligados solidarios del credito en cuestion


			$rfc="";
			$nombre1o="";
			$nombre2o="";
			$apaternoo="";
			$amaternoo="";
			$direccion="";
			$colonia="";
			$delegacion="";
			$cp="";

			$sqlcr = "select * from fiador f, contrato c where f.idfiador = c.idfiador and  c.idcontrato = $id ";
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



				foreach($this->lav as $idl => $aux1)
				{

				    $resultado .= $this->numeroburo($aux1[0],2);// . "|";
				    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
				    if(substr($aux1[3],0,2)=="Re")
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
						$resultado .= $this->numeroburo($dato ,$aux1[5]);// . "|";
				    		break;

				    	case 'Te':
				    		if(strlen($dato)>$aux1[5])
				    		{
				    			$dato=substr($dato,0,(integer)$aux1[5]);

				    		}
				    		$resultado .= $this->textoburo($dato,$aux1[5]);//  . "|";
				    		break;
				    	case 'F1':
				    		$dato=date("Y-m-d");
						$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]);// . "|";
				    		break;

				    	case 'F2':
				    		$dato=date("Y-m-d");
				    		$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
				    		break;

				    	}


				    }
				    else
				    {
				    	switch(substr($aux1[4],0,2))
				    	{
				    	case 'Nu':

						$resultado .= $this->numeroburo("0",$aux1[5]);// . "|";
				    		break;

				    	case 'Te':
				    		$resultado .= $this->textoburo(" ",$aux1[5]);//  . "|";
				    		break;


				    	}

				    }

				    $dato="";

				}
				$resultado .= ""; //detalle

			}
			return $resultado;

		}

		function gcts($id)
		{//Genera para empresa Comercial la ts (fin de archivo)


			$ninquilinos=$this->numempresas;
			$srentas=$this->cantidadc;
			$resultado = "";


			foreach($this->lts as $idl => $aux1)
			{

			    $resultado .= $this->numeroburo($aux1[0],2);// . "|";
			    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
			    if(substr($aux1[3],0,2)=="Re")
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
					$resultado .= $this->numeroburo($dato ,$aux1[5]);// . "|";
			    		break;

			    	case 'Te':
			    		if(strlen($dato)>$aux1[5])
			    		{
			    			$dato=substr($dato,0,(integer)$aux1[5]);

			    		}
			    		$resultado .= $this->textoburo($dato,$aux1[5]) ;// . "|";
			    		break;
			    	case 'F1':
			    		$dato=date("Y-m-d");
					$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]);// . "|";
			    		break;

			    	case 'F2':
			    		$dato=date("Y-m-d");
			    		$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
			    		break;

			    	}


			    }
			    else
			    {
			    	switch(substr($aux1[4],0,2))
			    	{
			    	case 'Nu':

					$resultado .= $this->numeroburo("0",$aux1[5]);// . "|";
			    		break;

			    	case 'Te':
			    		$resultado .= $this->textoburo(" ",$aux1[5]);//  . "|";
			    		break;


			    	}

			    }

			    $dato="";

			}
			$resultado .= "";


			return $resultado;

		}





//******************* metodos que generan la cinta por secciones FINANCIERO ********************

		function gfhd()
		{//Genera para empresa Comercial la HD

			$resultado = "";
			$dato="";
			foreach($this->lhd as $idl => $aux1)
			{

			    //echo $aux1[3] . "," . $aux1[1] . "<br>";
			    $resultado .= $this->numeroburo($aux1[0],2) . "";
			    if(substr(trim($aux1[2]),0,2)=="Re")
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
			    		$dato="5470";
			    	}

			    	//echo $dato . "?";
			    	switch(substr($aux1[4],0,2))
			    	{
			    	case 'Nu':
					$resultado .= $this->numeroburo($dato,$aux1[5]) . "";
			    		break;

			    	case 'Te':
			    		$resultado .= $this->textoburo($dato,$aux1[5])  . "";
			    		break;
			    	case 'F1':
			    		$dato=date("Y-m-d");
					$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]). "";
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
			    		$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5])  . "";
			    		break;

			    	}

			    }
			    else
			    {
			    	switch(substr($aux1[4],0,2))
			    	{
			    	case 'Nu':
					$resultado .= $this->numeroburo("0",$aux1[5]) . "";
			    		break;

			    	case 'Te':
			    		$resultado .= $this->textoburo(" ",$aux1[5])  . "";
			    		break;
			    	}

			    }

			    $dato="";
			}
			return $resultado;
		}



		function gfem()
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
			$sqlem = "select idinquilino, nombre, nombre2, apaterno, amaterno, nombrenegocio, rfc from (select idinquilino as idi, count(idcontrato) as num from contrato where concluido = false   group by idinquilino) c, inquilino p where c.idi = p.idinquilino" ;
			//$sqlem = "select idinquilino, nombre, nombre2, apaterno, amaterno, nombrenegocio, rfc from (select idinquilino as idi, count(idcontrato) as num from contrato where concluido = false and idcontrato in(211,214)   group by idinquilino) c, inquilino p where c.idi = p.idinquilino" ;
			$sqlem = "select idinquilino, nombre, nombre2, apaterno, amaterno, nombrenegocio, rfc from (select idinquilino as idi, count(idcontrato) as num from contrato where concluido = false   group by idinquilino) c, inquilino p where c.idi = p.idinquilino and length(rfc)=12" ;
			$resultado = "";

			$dato="";
			$operacion = mysql_query($sqlem);
			//$this->numempresas=mysql_num_rows($operacion);
			$this->numempresas=0;
			$this->numempresas=0;
			while($row = mysql_fetch_array($operacion))
			{
				$resultadopre ="";
				//$sqldirec="select idcontrato, calle, numeroext, numeroint, colonia, delmun, cp, clavee, clavep from contrato c, inmueble i, pais p, estado e where c.concluido = false and c.litigio = false and c.idinmueble = i.idinmueble and i.idestado = e.idestado and i.idpais=p.idpais and idinquilino = " . $row["idinquilino"];
				$sqldirec="select idcontrato, direccionf, inq.colonia, delegacion, inq.cp, clavee, clavep from contrato c, inmueble i, pais p, estado e, inquilino inq where c.concluido = false and c.idinmueble = i.idinmueble and inq.idestado = e.idestado and i.idpais=p.idpais and inq.idinquilino = " . $row["idinquilino"];
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

				$moral=0;
				//verificar el RFC para determinar los nombres a mostrar
				if(strlen($rfc)==12)
				{
					$moral=1;

				}


				foreach($this->lem as $idl => $aux1)
				{

				    $resultadopre .= $this->numeroburo($aux1[0],2);// . "|";
				    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
				    if(substr(trim($aux1[2]),0,2)=="Re")
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



			    	//hacer la distinción para el campo 23 verificar el rfc para colocar 1 o 2 para PM o PFAE como en accionistas
					switch($idl)
			    		{
					case 24:

				    		if(strlen($rfc)==12)
				    		{
						    	//$resultado .= $this->numeroburo("1" ,$aux1[5]) . "|";
						    	$dato = $this->numeroburo("1" ,$aux1[5]);// . "|";
						}
						else
						{
							//$resultado .= $this->numeroburo("2" ,$aux1[5]);// . "|";
							$dato = $this->numeroburo("2" ,$aux1[5]) ;//. "|";
						}



				    		break;

				    	case 4:
				    		if($moral==0)
				    		{

				    			$dato="";
				    		}

				    		break;

				    	case 5:
				    		if($moral==1)
				    		{

				    			$dato="";
				    		}

				    		break;
				    	default:

					}
				    		//echo $dato . "?";
				    	switch(substr($aux1[4],0,2))
				    	{
				    	case 'Nu':
						//$resultado .= $this->numeroburo($dato ,$aux1[5]);// . "|";
							$resultadopre .= $this->numeroburo($dato ,$aux1[5]) ;//. "|";
				    		break;

				    	case 'Te':
				    		if(strlen($dato)>$aux1[5])
				    		{
				    			$dato=substr($dato,0,(integer)$aux1[5]);
				    		}
				    		//$resultado .= $this->textoburo($dato,$aux1[5]) ;// . "|";
				    		$resultadopre .= $this->textoburo($dato,$aux1[5]);//  . "|";
				    		break;
				    	case 'F1':
				    		$dato=date("Y-m-d");
						//$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]); //. "|";
						$resultadopre .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]);// . "|";
				    		break;

				    	case 'F2':
				    		$dato=date("Y-m-d");
				    		//$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
				    		$resultadopre .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//   . "|";
				    		break;
				    	}


				    }
				    else
				    {
				    	switch(substr($aux1[4],0,2))
				    	{
				    	case 'Nu':

						//$resultado .= $this->numeroburo("0",$aux1[5]);// . "|";
						$resultadopre .= $this->numeroburo("0",$aux1[5]);// . "|";
				    		break;

				    	case 'Te':
				    		//$resultado .= $this->textoburo(" ",$aux1[5]) ;// . "|";
				    		$resultadopre .= $this->textoburo(" ",$aux1[5]);//  . "|";
				    		break;


				    	}

				    }

				    $dato="";

				}

				//$resultado .= $this->gfac($idinquilino) ;
				//$resultado .=  $this->gfcr($idinquilino);

				if($this->gfcr($idinquilino) != "")
				{
					$this->tempc1=0;
					$this->numempresas += 1;
					$resultado .= $resultadopre . $this->gfac($idinquilino) ;
					$resultado .=  $this->gfcr($idinquilino);
					$this->cantidadc += $this->tempc1;
				}


			}



			return $resultado;

		}


		//faltan los accionistas, por ahora no estan en la base de datos
		function gfac($id)
		{//Genera para empresa Comercial la ac (accionistas)
		 //aqui se toma los datos del os obligados solidarios del credito en cuestion
		 //aún no esta listo para su operacion


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

			$sqlac = "select rfc, curp, nombreac1, nombreac2, apaternoac, amaterno, porcentaje, direccionac, coloniaac, delegmunac, cpac, telac, clavee, clavep from accionista a, estado e, pais p   where a.idestado = e.idestado and a.idpais = p.idpais and  a.idinquilino = $id ";
			$resultado = "";
			$dato="";
			$operacioncr = mysql_query($sqlac);
			while($row = mysql_fetch_array($operacioncr))
			{

				$rfc=$row["rfc"];
				$curp=$row["curp"];
				$nombre1a=$row["nombreac1"];
				$nombre2a=$row["nombreac2"];
				$apaternoa=$row["apaternoac"];
				$amaternoa=$row["amaterno"];
				$direcciona=$row["direccionac"];
				$coloniaa=$row["coloniaac"];
				$delegaciona=$row["delegmunac"];
				$cpa=$row["cpac"];
				$paisa=$row["clavep"];
				$estadoa=$row["clavee"];
				$porcentaje=$row["porcentaje"];

				//verificar el RFC para determinar los nombres a mostrar



				foreach($this->lac as $idl => $aux1)
				{

				    $resultado .= $this->numeroburo($aux1[0],2);// . "|";
				    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
				    if(substr(trim($aux1[2]),0,2)=="Re")//requerido
				    {


				    	if ($idl=="20")
				    	{
				    		if(strlen($rfc)==12)
				    		{
						    	$resultado .= $this->numeroburo("1" ,$aux1[5]);// . "|";
						}
						else
						{
							$resultado .= $this->numeroburo("2" ,$aux1[5]);// . "|";
						}

				    	}
				    	else
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
							$resultado .= $this->numeroburo($dato ,$aux1[5]);// . "|";
				    			break;

				    		case 'Te':
				    			if(strlen($dato)>$aux1[5])
				    			{
				    				$dato=substr($dato,0,(integer)$aux1[5]);

				    			}
				    			$resultado .= $this->textoburo($dato,$aux1[5]);//  . "|";
				    			break;
				    		case 'F1':
				    			$dato=date("Y-m-d");
								$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]);// . "|";
				    			break;

				    		case 'F2':
				    			$dato=date("Y-m-d");
				    			$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
				    			break;
				    		}
				    	}
				    }
				    else
				    {
				    	switch(substr($aux1[4],0,2))
				    	{
				    	case 'Nu':
						$resultado .= $this->numeroburo("0",$aux1[5]);// . "|";
				    		break;

				    	case 'Te':
				    		$resultado .= $this->textoburo(" ",$aux1[5]) ;// . "|";
				    		break;


				    	}

				    }





				    $dato="";

				}
				$resultado .= ""; //detalle

			}
			return $resultado;

		}


		function gfcr($id)
		{//Genera para empresa Comercial la cr


			$rfc="";
			$fechainicio="";
			$fechatermino="";
			$idcontrato="";
			
			$hoycr=date("Y-m") . "-01";
			
			//$sqlcr = "select idcontrato, rfc, fechainicio, fechatermino from contrato c, inquilino p where c.idinquilino = p.idinquilino and  c.concluido = false and c.idcontrato = $id ";
			//$sqlcr = "select idcontrato, rfc, fechainicio, fechatermino, datediff(fechatermino,fechainicio) as diast from contrato c, inquilino p where c.idinquilino = p.idinquilino and  c.concluido = false and c.litigio=false and p.idinquilino= $id ";
			$sqlcr = "select idcontrato, rfc, fechainicio, fechatermino, datediff(fechatermino,fechainicio) as diast from contrato c, inquilino p where c.idinquilino = p.idinquilino and  c.concluido = false and c.litigio=false and p.idinquilino= $id and fechainicio < '$hoycr' ";

			$resultado = "";
			$resultadoc="";
			$dato="";
			$operacioncr = mysql_query($sqlcr);
			while($row = mysql_fetch_array($operacioncr))
			{
				$rfc=$row["rfc"];
				$fechainicio=$row["fechainicio"];
				$fechatermino=$row["fechatermino"];
				$idcontrato=$row["idcontrato"];
				$diast = $row["diast"];

				//verificar el RFC para determinar los nombres a mostrar



				foreach($this->lcr as $idl => $aux1)
				{

				    $resultadoc .= $this->numeroburo($aux1[0],2);// . "|";
				    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
				    if(substr(trim($aux1[2]),0,2)=="Re")
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


					switch($idl)
			    		{
					case 8://07: calcula siempre un año de todos los cobros periodicos (no inmediatos) y sin interes para generar la suma

						//toma el margen y toma el total de dias "diast" y segun el margen hay que hacer la division
						//para sabar cuantos peridos caben (enteros) en esos dias
//*********************

//** agregar a la consulta la restricci—n de los importes de cuotas condominales
						//$sqlauxcr = "select (cantidad*(iva/100))+cantidad as total, c.idperiodo,numero,  idmargen from cobros c, periodo p  where c.idperiodo = p.idperiodo and p.idperiodo<>1 and  idcontrato = $idcontrato";
						$sqlauxcr = "select (cantidad*(iva/100))+cantidad as total, c.idperiodo,numero,  idmargen from cobros c, periodo p, tipocobro tc  where  c.idtipocobro = tc.idtipocobro and tc.idtipocobro <>40 and c.idperiodo = p.idperiodo and p.idperiodo<>1 and  idcontrato = $idcontrato";
						$operacioncr07 = mysql_query($sqlauxcr);
						$dato = 0;
						$veces = 0;
						while($row07 = mysql_fetch_array($operacioncr07))
						{


							switch ($row07["idmargen"])
							{
							case 1://años
								$veces = (int) ($diast / (365 * $row07["numero"]));
								break;
							case 2://meses
								$veces = (int) ($diast / (30 * $row07["numero"]));
								break;
							case 3://dias
								$vedes = (int) ($diast / (1 * $row07["numero"]));
								break;
							}

							$dato += $row07["total"] * $veces;
							$veces=0;

						}
						break;
					case 10:// 09 num, pagos

						$sqlauxcr = "select  c.idperiodo, numero,  idmargen from cobros c, periodo p  where c.idperiodo = p.idperiodo and p.idperiodo<>1 and  idcontrato = $idcontrato";
						$operacioncr07 = mysql_query($sqlauxcr);
						$dato = 0;
						$veces = 0;
						$row07 = mysql_fetch_array($operacioncr07);



							switch ($row07["idmargen"])
							{
							case 1://años
								$veces = (int) ($diast / (365 * $row07["numero"]));
								break;
							case 2://meses
								$veces = (int) ($diast / (30 * $row07["numero"]));
								break;
							case 3://dias
								$veces = (int) ($diast / (1 * $row07["numero"]));
								break;
							}

							$dato = $veces;






						break;
					case 11://10:frecuencia de pagos en dias

						$sqlauxcr = "select  c.idperiodo,numero,  idmargen from cobros c, periodo p  where c.idperiodo = p.idperiodo and p.idperiodo<>1 and  idcontrato = $idcontrato";
						$operacioncr07 = mysql_query($sqlauxcr);
						$dato = 0;
						$veces = 0;
						$row07 = mysql_fetch_array($operacioncr07);



							switch ($row07["idmargen"])
							{
							case 1://años
								$veces =  (365 * $row07["numero"]);
								break;
							case 2://meses
								$veces =  (30 * $row07["numero"]);
								break;
							case 3://dias
								$veces =  (1 * $row07["numero"]);
								break;
							}

							$dato = $veces;


						break;
					case 12://11:importe de pagos del periodo
//******************************	

//** agregar a la consulta la restricci—n de los importes de cuotas condominales				
						//$sqlauxcr = "select (cantidad*(iva/100))+cantidad as total, c.idperiodo,numero,  idmargen from cobros c, periodo p  where c.idperiodo = p.idperiodo and p.idperiodo<>1 and  idcontrato = $idcontrato";
						$sqlauxcr = "select (cantidad*(iva/100))+cantidad as total, c.idperiodo,numero,  idmargen from cobros c, periodo p, tipocobro tc  where c.idtipocobro = tc.idtipocobro and tc.idtipocobros <> 40 and c.idperiodo = p.idperiodo and p.idperiodo<>1 and  idcontrato = $idcontrato";
						
						$operacioncr07 = mysql_query($sqlauxcr);
						$dato = 0;

						while($row07 = mysql_fetch_array($operacioncr07))
						{



							$dato += $row07["total"];


						}



						break;
					case 13://12:fecha del ultimo pago

						$hoy = date("Y-m") . "-01";

						$sqlauxcr = "select max(fechapago) as fecha from historia  where  idcontrato = $idcontrato and fechapago<'$hoy'";//"
						$operacioncr07 = mysql_query($sqlauxcr);
						$dato = 0;

						$row07 = mysql_fetch_array($operacioncr07);
						$dato = substr($row07["fecha"],8,2) . substr($row07["fecha"],5,2) . substr($row07["fecha"],0,4);


						break;
				    	default:


				    	}





				    	//echo $dato . "?";
				    	switch(substr($aux1[4],0,2))
				    	{
				    	case 'Nu':
							$resultadoc .= $this->numeroburo($dato ,$aux1[5]);// . "|";
				    		break;

				    	case 'Te':
				    		if(strlen($dato)>$aux1[5])
				    		{
				    			$dato=substr($dato,0,(integer)$aux1[5]);

				    		}
				    		$resultadoc .= $this->textoburo($dato,$aux1[5]);//  . "|";
				    		break;
				    	case 'F1':
				    		//$dato=date("Y-m-d");
							$resultadoc .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]);//. "|";
				    		break;

				    	case 'F2':
				    		//$dato=date("Y-m-d");
				    		$resultadoc .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
				    		break;

				    	}


				    }
				    else
				    {






				    	switch(substr($aux1[4],0,2))
				    	{
				    	case 'Nu':

							$resultadoc .= $this->numeroburo("0",$aux1[5]);// . "|";
				    		break;

				    	case 'Te':
				    		$resultadoc .= $this->textoburo(" ",$aux1[5]);//  . "|";
				    		break;


				    	}

				    }

				    $dato="";

				}

				if ($this->gfde($idcontrato) != "")
				{
					$this->tempc0=0;
					$resultadoc .= $this->gfde($idcontrato); //detalle
					//$resultado .= "<br><br>" . $this->gcav($idcontrato) . "<br><br>"; //avales es opcional, por ahora no lo colocare, falta modificar la tabla
					$this->tempc1 += $this->tempc0;
				}
				else
				{
					$resultadoc = "";
				}
				$resultado .= $resultadoc;
				$resultadoc = "";

			}
			return $resultado;

		}


		function gfde($id)
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
			//calcular los dias vencidos hasta final de mes
/*
			if(date(m) = "12")
			{
				$anio = date("Y")+1;
				$mes = 1;

			}
			else
			{
				$mes = date("m") +1;
				$anio = date("Y");
			}
*/
			$hoy=date("Y-m") . "-01";

//cambiar la consulta a una consulta simple para el fianciero, no se requiere agrupar por cantidades, solo deuda por periodo o factura
			//$sqldea[0]="select c.idcontrato, aplicado, rfc,  fechanaturalpago, SUM(cantidad) as cantidadc, datediff('$hoy',fechanaturalpago) as diasv  from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id  group by c.idcontrato, aplicado, rfc,  fechanaturalpago";

			//normal todo
			//$sqldea[0]="select c.idcontrato, aplicado, rfc,  fechanaturalpago, SUM(cantidad + iva) as cantidadc, datediff('$hoy',fechanaturalpago) as diasv  from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id  group by c.idcontrato, aplicado, rfc,  fechanaturalpago";
			//hasta el mes inmediato anterior
			
//**************************			
			//$sqldea[0]="select c.idcontrato, aplicado, rfc,  fechanaturalpago, SUM(cantidad + iva) as cantidadc, datediff('$hoy',fechanaturalpago) as diasv  from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id group by c.idcontrato, aplicado, rfc,  fechanaturalpago";
			$sqldea[0]="select c.idcontrato, aplicado, rfc,  fechanaturalpago, SUM(cantidad + iva) as cantidadc, datediff('$hoy',fechanaturalpago) as diasv  from historia h, contrato c, inquilino p, cobros co, tipocobro tc  where co.idcontrato = c.idcontrato and co.idtipocobro and tc.idtipocobro and tc.idtipocobro <>40 and c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id group by c.idcontrato, aplicado, rfc,  fechanaturalpago";

/*			$sqldea[1]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 120 and 149 order by c.idcontrato ";
			$sqldea[2]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 90 and 119 order by c.idcontrato ";
			$sqldea[3]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 60 and 89 order by c.idcontrato ";
			$sqldea[4]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 30 and 59 order by c.idcontrato ";
			$sqldea[5]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) between 1 and 29 order by c.idcontrato ";
			$sqldea[6]="select c.idcontrato, cantidad, aplicado, rfc, fechanaturalpago,datediff('$hoy',fechanaturalpago) as diasv from historia h, contrato c, inquilino p  where c.idinquilino = p.idinquilino and h.idcontrato = c.idcontrato  and aplicado =false and c.idcontrato = $id having datediff('$hoy',fechanaturalpago) <= 0 order by c.idcontrato";//"
*/
			$resultado = "";
			foreach($sqldea as $key=>$sqlde)
			{

				if($key==6)
				{
					$diasv=0;
				}


				$dato="";
				$total =0;
				$diasv=0;
				$totalvigente=0;
				$operacionde = mysql_query($sqlde);
				while($row = mysql_fetch_array($operacionde))
				{
					$rfc=$row["rfc"];
					$fecha=$row["fechanaturalpago"];
					//$vencido=$row["cvencido"];
					$idcontrato=$row["idcontrato"];
					$aplicado = $row["aplicado"];
					$total = (int)$row["cantidadc"];
					//$this->cantidadc += $total;
					$this->tempc0 += $total;
					$diasv=0;
					if($key==6)
					{
						$diasv=0;
					}
					else
					{
						if($diasv<$row["diasv"])
						{
							$diasv = $row["diasv"];
						}
					}

					//verificar el RFC para determinar los nombres a mostrar


				   if($diasv==0)
				   {
					$totalvigente +=$total;
				   }
				   else
				   {



					foreach($this->lde as $idl => $aux1)
					{

					    $resultado .= $this->numeroburo($aux1[0],2);// . "|";
					    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
					    if(substr(trim($aux1[2]),0,2)=="Re")
					    {

					    	//hay que colocar la parte relacionada a los dias de vencimiento campo 3








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


					    		switch($idl)
					    		{
							case 3://dias de vencimiento
								if($dato <0)
								{
									$dato =0;
								}

								break;
						    	default:

						    	}


					    		//echo $dato . "?";
					    		switch(substr($aux1[4],0,2))
					    		{
					    		case 'Nu':
								$resultado .= $this->numeroburo($dato ,$aux1[5]);// . "|";
					    			break;

					    		case 'Te':
					    			if(strlen($dato)>$aux1[5])
					    			{
					    				$dato=substr($dato,0,(integer)$aux1[5]);

					    			}
					    			$resultado .= $this->textoburo($dato,$aux1[5]);//  . "|";
					    			break;
					    		case 'F1':
					    			$dato=date("Y-m-d");
								$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]);// . "|";
					    			break;

					    		case 'F2':
					    			$dato=date("Y-m-d");
					    			$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
					    			break;

					    		}




					    }
					    else
					    {
					    	switch(substr($aux1[4],0,2))
					    	{
					    	case 'Nu':

							$resultado .= $this->numeroburo("0",$aux1[5]);// . "|";
					    		break;

					    	case 'Te':
					    		$resultado .= $this->textoburo(" ",$aux1[5]);// . "|";
					    		break;


					    	}

					    }

					    $dato="";

					}
				    }



					$resultado .= ""; //detalle

				}




				if($totalvigente!=0)
				{
					$total=$totalvigente;
					foreach($this->lde as $idl => $aux1)
					{

					    $resultado .= $this->numeroburo($aux1[0],2);// . "|";
					    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
					    if(substr(trim($aux1[2]),0,2)=="Re")
					    {

					    	//hay que colocar la parte relacionada a los dias de vencimiento campo 3








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


					    		switch($idl)
					    		{
							case 3://dias de vencimiento
								if($dato <0)
								{
									$dato =0;
								}

								break;
						    	default:

						    	}


					    		//echo $dato . "?";
					    		switch(substr($aux1[4],0,2))
					    		{
					    		case 'Nu':
								$resultado .= $this->numeroburo($dato ,$aux1[5]);// . "|";
					    			break;

					    		case 'Te':
					    			if(strlen($dato)>$aux1[5])
					    			{
					    				$dato=substr($dato,0,(integer)$aux1[5]);

					    			}
					    			$resultado .= $this->textoburo($dato,$aux1[5]);//  . "|";
					    			break;
					    		case 'F1':
					    			$dato=date("Y-m-d");
								$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]);// . "|";
					    			break;

					    		case 'F2':
					    			$dato=date("Y-m-d");
					    			$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
					    			break;

					    		}




					    }
					    else
					    {
					    	switch(substr($aux1[4],0,2))
					    	{
					    	case 'Nu':

							$resultado .= $this->numeroburo("0",$aux1[5]);// . "|";
					    		break;

					    	case 'Te':
					    		$resultado .= $this->textoburo(" ",$aux1[5]);// . "|";
					    		break;


					    	}

					    }

					    $dato="";

					}





				}




			}

			return $resultado ;

		}


		function gfav($id)
		{//Genera para empresa Comercial la av (avales)
		 //aqui se toma los datos del os obligados solidarios del credito en cuestion


			$rfc="";
			$nombre1o="";
			$nombre2o="";
			$apaternoo="";
			$amaternoo="";
			$direccion="";
			$colonia="";
			$delegacion="";
			$cp="";

			$sqlcr = "select * from fiador f, contrato c where f.idfiador = c.idfiador and  c.idcontrato = $id ";
			$resultado = "";
			$dato="";
			$operacioncr = mysql_query($sqlcr);
			while($row = mysql_fetch_array($operacioncr))
			{

				if($row["nombre"]!="SIN OBLIGADO SOLIDARIO")
				{
				$rfc=$row["rfc"];
				$fechainicio=$row["fechainicio"];
				$fechatermino=$row["fechatermino"];
				$idcontrato=$row["idcontrato"];

				//verificar el RFC para determinar los nombres a mostrar



				foreach($this->lav as $idl => $aux1)
				{

				    $resultado .= $this->numeroburo($aux1[0],2);// . "|";
				    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
				    if(substr(trim($aux1[2]),0,2)=="Re")
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
						$resultado .= $this->numeroburo($dato ,$aux1[5]);// . "|";
				    		break;

				    	case 'Te':
				    		if(strlen($dato)>$aux1[5])
				    		{
				    			$dato=substr($dato,0,(integer)$aux1[5]);

				    		}
				    		$resultado .= $this->textoburo($dato,$aux1[5]);//  . "|";
				    		break;
				    	case 'F1':
				    		//$dato=date("Y-m-d");
						$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]);// . "|";
				    		break;

				    	case 'F2':
				    		//$dato=date("Y-m-d");
				    		$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
				    		break;

				    	}


				    }
				    else
				    {
				    	switch(substr($aux1[4],0,2))
				    	{
				    	case 'Nu':

						$resultado .= $this->numeroburo("0",$aux1[5]);// . "|";
				    		break;

				    	case 'Te':
				    		$resultado .= $this->textoburo(" ",$aux1[5]);//  . "|";
				    		break;


				    	}

				    }

				    $dato="";

				}
				$resultado .= ""; //detalle
				}
			}
			return $resultado;

		}

		function gfts($id)
		{//Genera para empresa Comercial la ts (fin de archivo)


			$ninquilinos=$this->numempresas;
			$srentas=$this->cantidadc;
			$resultado = "";


			foreach($this->lts as $idl => $aux1)
			{

			    $resultado .= $this->numeroburo($aux1[0],2);// . "|";
			    //echo $aux1[0] . ", " . $aux1[3] . "," . $aux1[1] . "<br>";
			    if(substr(trim($aux1[2]),0,2)=="Re")
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
					$resultado .= $this->numeroburo($dato ,$aux1[5]);// . "|";
			    		break;

			    	case 'Te':
			    		if(strlen($dato)>$aux1[5])
			    		{
			    			$dato=substr($dato,0,(integer)$aux1[5]);

			    		}
			    		$resultado .= $this->textoburo($dato,$aux1[5]) ;// . "|";
			    		break;
			    	case 'F1':
			    		$dato=date("Y-m-d");
					$resultado .= $this->numeroburo($this->fechaburo($dato,1),$aux1[5]);// . "|";
			    		break;

			    	case 'F2':
			    		$dato=date("Y-m-d");
			    		$resultado .= $this->numeroburo($this->fechaburo($dato,2),$aux1[5]);//  . "|";
			    		break;

			    	}


			    }
			    else
			    {
			    	switch(substr($aux1[4],0,2))
			    	{
			    	case 'Nu':

					$resultado .= $this->numeroburo("0",$aux1[5]);// . "|";
			    		break;

			    	case 'Te':
			    		$resultado .= $this->textoburo(" ",$aux1[5]);//  . "|";
			    		break;


			    	}

			    }

			    $dato="";

			}
			$resultado .= "";


			return $resultado;

		}



}
?>