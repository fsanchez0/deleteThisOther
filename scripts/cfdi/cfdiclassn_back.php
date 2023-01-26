<?php
//Menu dinamico
include "../insertcfdi/lxml3.php";

class cfdi32class
{
	//Version 3.2
	/*
	var $listacfdi="Nº^Nombre Campo^Descripción^Opc/Req^Tipo^Longitud^SAT^DATOS FACTURA^Valor|1^CFD^Tipo Comprobante^R^C^3^R^380^Valor|2^Version^Version Layout Seres (ejemplo: 1.0)^O^C^5^O^3.2^Valor|3^TipoEnvio^Tipo de envío^O^C^1^O^^Valor|4^TipoCliente^Tipo de Cliente^O^C^35^O^^Valor|5^EfectoCFD^Efecto del Comprobante Fiscal^R^C^8^R^ingreso^Valor|6^Certificado^Inclusión o no del certificado en la factura^O^C^1^O^^Valor|7^Serie^Serie del Comprobante^O^C^10^O^MDX^Valor|8^Folio^Folio del Comprobante^R^N^10^R^136^Valor|9^Funcion^Función Mensaje^O^C^3^O^^Valor|10^Fecha^Fecha Factura^R^C^19^R^2012-01-02T18:24:36^Valor|11^AnoApro^Año en que el SAT esta autorizando el rango de folios^N^C^150^N^MDX^Valor|12^NoAprob^Número de Aprobación de el SAT^N^N^10^N^^Valor|13^NoRecep^Folio de Recibo de Mercancias^O^C^17^O^^Valor|14^FecNoRecep^Fecha Folio de Recibo de Mercancias^O^C^10^O^2011-12-16^Valor|15^OrdComp^Número de Pedido^O^C^17^O^12312^Valor|16^FecOrdComp^Fecha Pedido^O^C^19^O^2011-12-16T10:10:14^Valor|17^NoDocInt^Número asignado por los proveedores para identificar a un CFD dentro de su empresa^N^C^19^N^^Valor|18^NoRem^Es el número de remisión con el que se entregó la mercancía^N^C^19^N^^Valor|19^Pago^Observaciones Condiciones de Pago^R^C^150^R^Pago Bancario^Valor|20^MetPago^Texto libre para expresar el método de pago de los bienes o servicios amparados por el CFD^R^C^150^R^Pago Bancario^Valor|21^CondPago^Condiciones comerciales aplicables para el pago del CFD. (Efectivo, crédito…)^O^C^150^O^^Valor|22^LugarExp^El lugar de expedición del comprobante^R^C^150^O^Teotihuacan^Valor|23^NoCta^Número de Cuenta Bancaria ó No. Cta cliente^O^C^150^O^^Valor|24^FechaCancel^Representa la fecha de cancelación o vencimiento en la cual vencen los días de crédito^O^C^10^N^^Valor|25^Notas^Información de compras, Obervaciones sobre la factura^O^C^150^O^^Valor|26^NotasImp^Información de impuestos (Pedimentos)^O^C^150^O^^Valor|27^ImpLetra^Importe Con Letra^O^C^150^O^( CERO PESOS 00/100 M.N. )^Valor|28^TermsFlete^Embarque de la Mercancía ó Términos del Flete^O^C^150^O^^Valor|29^NotasEmp^Información sobre el emisor de la factura^O^C^150^N^^Valor|30^ERFC^RFC del Emisor de la Factura^R^C^17^R^JSO031009HY8^Valor|31^ENombre^Razón social Emisor de la Factura^O^C^70^O^JWM Solutions, S.C^Valor|32^ENoProv^Emisor numero de proveedor^O^N^35^O^234234^Valor|33^EGLN^Código GLN del emisor de la factura^O^C^35^O^^Valor|34^ECalle^Nombre de la calle del Emisor de la Factura^~R^C^100^~R^^Valor|35^ENoext^Numero Exterior de la calle del Emisor de la Factura^O^C^30^O^^Valor|36^ENoint^Numero adicional de la calle del Emisor de la Factura^O^C^30^O^^Valor|37^EColon^Colonia del Emisor de la Factura^O^C^100^O^^Valor|38^ELoc^Localidad del Emisor de la Factura^O^C^35^O^^Valor|39^ERef^Referencia de ubicación adicional del Emisor Factura^O^C^35^O^^Valor|40^EMunic^Municipio del Emisor de la Factura^~R^C^100^~R^^Valor|41^EEdo^Estado del Emisor de la Factura^~R^C^35^~R^^Valor|42^Epais^Pais del Emisor de la Factura^~R^C^35^~R^^Valor|43^ECP^Código Postal del Emisor de la Factura^~R^C^5^~R^^Valor|44^Eemail^Email del emisor factura^~R^C^75^~R^^Valor|45^ENoArea^Número de área del emisor del CFD^~R^C^17^~R^^Valor|46^ExNombre^Nombre de la Sucursal donde es Expedido el CFD^O^C^70^N^^Valor|47^ExCalle^Nombre de la calle donde es Expedido el CFD^O^C^70^O^^Valor|48^ExNoext^Numero Exterior de la calle de la Ubicación donde es expedido el CFD^O^C^30^O^^Valor|49^ExNoint^Numero adicional de la calle de la ubicación donde es expedido el CFD^O^C^30^O^^Valor|50^ExColon^Colonia de la ubicación donde es expedido el CFD^O^C^35^O^^Valor|51^ExLoc^Localidad de la ubicación donde es expedido el CFD^O^C^35^O^^Valor|52^ExRef^Referencia de ubicación adicional de donde es expedido el CFD^O^C^35^O^^Valor|53^ExMunic^Municipo de la ubicación donde es expedido el CFD^O^C^35^O^^Valor|54^ExEdo^Estado de la Ubicación  donde es expedido el CFD^O^C^35^O^^Valor|55^Expais^Pais de la ubicación donde es expedido el CFD^~R^C^35^~R^^Valor|56^ExCP^Código de la ubicación donde es expedido el CFD^O^C^9^O^^Valor|57^RRFC^RFC del Receptor de la Factura^R^C^17^R^TCH850701RM1^Valor|58^RNombre^Razón social Receptor de la Factura^O^C^150^O^TIENDAS CHEDRAUI S.A. DE C.V.^Valor|59^RGLN^Código GLN del receptor de la factura^O^C^35^O^7507001800019^Valor|60^RContac^Persona de Contacto^O^C^35^O^^Valor|61^RIEPS^Identificación IEPS del receptor de la factura^O^C^35^O^^Valor|62^RCalle^Nombre de la calle del Receptor de la Fact.^O^C^100^O^AV. CONSTITUYENTES^Valor|63^RNoext^Numero Exterior de la calle del Receptor de la Fact.^O^C^30^O^1150^Valor|64^RNoint^Numero adicional de la calle del Receptor de la Fact.^O^C^30^O^0^Valor|65^RColon^Colonia del Receptor de la Factura^O^C^100^O^LOMAS ALTAS^Valor|66^RLoc^Localidad del Receptor de la Factura^O^C^50^O^D.F.^Valor|67^RRef^Referencia de ubicación adicional del Receptor Fact.^O^C^35^O^^Valor|68^RMunic^Municipio del Receptor de la Factura^O^C^100^O^MIGUEL HIDALGO^Valor|69^REdo^Estado  del Receptor de la Factura^O^C^100^O^D.F.^Valor|70^Rpais^Pais del Receptor de la Factura^~R^C^35^~R^MEXICO^Valor|71^RCP^Código Postal del Receptor de la Factura^O^C^5^O^1^Valor|72^CoGLN^Código GLN del Comprador^O^C^35^O^7507001800019^Valor|73^CoContac^Persona de Contacto de Compras^O^C^35^O^402^Valor|74^PrNoProv^Número del Proveedor^O^N^35^O^025786^Valor|75^PrIEPS^Identificación IEPS del proveedor de la factura^O^C^35^O^^Valor|76^PrGLN^Código GLN del proveedor de la Mercancía^O^C^35^O^7504003871009^Valor|77^RmNombre^Razón social Receptor de la Mercancía^O^C^70^O^^Valor|78^RmGLN^Código GLN del receptor de la Mercancía^O^C^35^O^^Valor|79^RmCalle^Nombre de la calle del Receptor de la Mercancía^O^C^70^O^^Valor|80^RmNoext^Numero Exterior de la calle del Receptor de la Merc.^O^C^30^O^^Valor|81^RmNoint^Numero adicional de la calle del Receptor de la Fact.^O^C^30^O^^Valor|82^RmColon^Colonia del Receptor de la Factura^O^C^50^O^^Valor|83^RmLoc^Localidad del Receptor de la Factura^O^C^50^O^^Valor|84^RmRef^Referencia de ubicación adicional del Receptor Fact.^O^C^35^O^^Valor|85^RmMunic^Municipio del Receptor de la Mercancía^O^C^35^O^^Valor|86^RmEdo^Estado  del Receptor de la Mercancía^O^C^35^O^^Valor|87^Rmpais^Pais del Receptor de la Mercancía^O^C^35^O^^Valor|88^RmCP^Código Postal del Receptor de la Mercancía^O^C^9^O^^Valor|89^DivisOp^Código de Moneda, divisa opcional^R^C^3^O^MXN^Valor|90^DivisFn^Función de divisa^N^C^20^N^^Valor|91^TC^Tipo de cambio^O^N(6,2)^9^O^1^Valor|92^RelTiempo^Relación de tiempo^O^C^3^O^^Valor|93^TipoPeriodo^Tipo de período^O^C^3^O^^Valor|94^NumPeriodos^Número de períodos^O^N^3^O^45^Valor|95^TipoDescPago^Tipo descuento pago^O^C^35^O^^Valor|96^PorDescPago^Porcentaje descuento pago^O^N(4,2)^7^O^^Valor|97^FechaIni^Inicio periodo de liquidación^N^C^19^N^^Valor|98^FechaVen^Fin periodo de liquidación^N^C^19^N^^Valor|99^TpNoSP^Tipo Número para identificadores especiales^O^C^150^N^AREA^Valor|100^NoSP^Número del Identificador especificado^O^C^150^N^^Valor|101^IdNoSP^Identificador del Número especificado^O^C^150^N^^Valor|102^FechaNoSP^Fecha Identificador especificado^O^C^10^N^^Valor|103^TxtNoSP^Cualquier Referencia Identificador especificado^O^C^10^N^^Valor|104^Cont^Contacto Emisor o Receptor^~R^C^1^N^^Valor|105^TipoCont^Tipo de Contacto^~R^C^40^N^^Valor|106^NombreCont^Nombre del Contacto^O^C^60^N^^Valor|107^EmailCont^Email del contacto^O^C^35^N^^Valor|108^TelefonoCont^Teléfono del Contacto^O^N(4,2)^35^N^^Valor|109^DcIndDC^Indicador Descuento o Cargo^~R^C^35^N^^Valor|110^DcImputacion^Imputación Descuento/Cargo^O^C^35^N^^Valor|111^DcTipo^Tipo Descuento/Cargo^O^C^3^N^^Valor|112^DcBase^Base del porcentaje que se aplicará^O^C^35^N^^Valor|113^DcPorcentaje^Porcentaje Descuento/Cargo^O^N(4,2)^7^N^^Valor|114^DcImporte^Importe Descuento/Cargo^O^N(12,2)^15^N^^Valor|115^IndicadorR^Indicador de Retención de Impuestos^N^C^1^N^^Valor|116^BaseR^Base de retención^N^C^18^N^^Valor|117^Cont^calificador para tipo de contacto Emisor o Receptor^^^^^R^Valor|118^TipoCont^indicador para el env’o SMTP^^^^^SMTP^Valor|119^EmailCont^hasta 3 mails para direccionar PDFÕs y xml, separados por , (comas)^^^^^^Valor|120^TelefonoCont^campo informativo para telefonos^^^^^^Valor|121^InfoCont^campo informativo para texto en el cuerpo del correo^^^^^^Valor|122^Numlin^Número de Línea (autonumérico)^R^N^6^R^^Valor|123^EAN^Código EAN del artículo solicitado^O^C^35^O^^Valor|124^CodigoArt^Código Artículo del Comprador^O^C^35^O^^Valor|125^CodArtPro^Código Artículo del Proveedor^O^C^35^O^^Valor|126^NumSerie^Número de Serie^O^C^35^O^^Valor|127^Cant^Cantidad Facturada^R^N(12,2)^15^R^^Valor|128^CantGratis^Cantidad gratis de mercancía^O^N(12,0)^15^O^^Valor|129^UM^Unidad de Medida^R^C^6^R^^Valor|130^UnidCom^Nº de unidades de consumo en unidad comercializada^O^N(12,3)^15^O^^Valor|131^Desc^Descripción del Articulo^R^C^300^R^^Valor|132^PrecMX^Precio Neto Unitario^R^N(12,2)^15^R^^Valor|133^PrecBruto^Precio Bruto Unitario^O^N(12,2)^15^O^^Valor|134^ImporMX^Importe Total de la Línea de Articulo^R^N(15,2)^18^R^^Valor|135^Precop^Precio neto unitario en divisa opcional^O^N^8^O^^Valor|136^ImporOp^Importe en divisa opcional^O^N^10^O^^Valor|137^ImporBruto^Importe Bruto de la Línea de Articulo^O^N(15,2)^18^O^150^Valor|138^ImporNeto^Importe Neto de la Línea con Desc./Cargos con Impuestos^O^N(15,2)^18^O^172.5^Valor|139^LinOrdComp^Número de órden de compra^O^C^17^O^^Valor|140^NivOrdComp^Nivel de órden de compra^O^C^17^N^^Valor|141^TpNotaslin^Tipo Observaciones^O^C^35^N^^Valor|142^Notaslin^Observaciones de Línea^O^C^35^N^^Valor|143^TpoPrecio^Código Seriado de Unidad de Envío^O^C^35^N^^Valor|144^PrecList^xxx^O^C^35^N^^Valor|145^UlCodSeriado^Código Seriado de Unidad de Envío^O^C^35^N^^Valor|146^UlSRV^Número global de unidades de  comercialización^O^C^35^N^^Valor|147^IENumPalet^Numero de paquetes ^N^N(15)^15^N^^Valor|148^IEDescPalet^Descripción del  empaquetado^N^C^70^N^^Valor|149^IETipoPalet^Tipo de empaquetado^N^C^35^N^^Valor|150^IEPagoTrans^Pago de transporte de embalaje^N^C^35^N^^Valor|151^ILNumLote^Número de lote^N^C^35^N^^Valor|152^ILFechaLote^Fecha de producción^N^C^10^N^^Valor|153^NoReleased^Numero de Release^N^C^10^N^^Valor|154^DtIndDC^Indicador Descuento o Cargo^~R^C^35^N^^Valor|155^DtImputacion^Imputación Descuento/Cargo^~R^C^35^N^^Valor|156^DtSecuencia^Indicador de secuencia de cálculo^N^N^3^N^^Valor|157^DtTipo^Tipo Descuento/Cargo^O^C^3^N^^Valor|158^DtPorcentaje^Porcentaje Descuento/Cargo^O^N(4,2)^7^N^^Valor|159^DtImpUnidad^Importe Descuento/Cargo por unidad^O^N(15,2)^18^N^^Valor|160^DtImp^Importe Descuento/Cargo^N^N(15,2)^18^O^^Valor|161^DescTipoImp^Tipo de Impuesto de línea^O^C^4^O^^Valor|162^PorImp^Porcentaje Impuesto^O^N(4,2)^7^O^^Valor|163^CategImp^Categoría del impuesto^O^C^1^O^^Valor|164^NumRefImp^Numero de identificación del impuesto^O^C^20^O^^Valor|165^ImporImp^Importe del impuesto^O^N(15,2)^18^O^22.5^Valor|166^Aduana^Aduana por la que se efectuó la importación del bien.^O^C^13^O^^Valor|167^Pedimen^Número Información Aduanera^~R^C^17^~R^^Valor|168^FechaP^Fecha de expedición del documento aduanero ^~R^C^10^~R^^Valor|169^NumCPred^Número Cuenta Predial^~R^C^17^~R^^Valor|170^CampoDet0^Campo Detalle Específico (Clave)^N^C^4^N^^Valor|171^CampoDet1^Campo Detalle Específico 1^N^C^35^N^^Valor|172^CampoDet2^Campo Detalle Específico 2^N^C^35^N^^Valor|173^CampoDet3^Campo Detalle Específico 3^N^C^35^N^^Valor|174^CampoDet4^Campo Detalle Específico 4^N^C^35^N^^Valor|175^CampoDet5^Campo Detalle Específico 5^N^C^35^N^^Valor|176^CampoDet6^Campo Detalle Específico 6^N^C^35^N^^Valor|177^CampoDet7^Campo Detalle Específico 7^N^C^35^N^^Valor|178^CampoDet8^Campo Detalle Específico 8^N^C^35^N^^Valor|179^CampoDet9^Campo Detalle Específico 9^N^C^35^N^^Valor|180^CampoDet10^Campo Detalle Específico 10^N^C^35^N^^Valor|181^CampoDet11^Campo Detalle Específico 11^N^C^35^N^^Valor|182^CampoDet12^Campo Detalle Específico 12^N^C^35^N^^Valor|183^CampoDet13^Campo Detalle Específico 13^N^C^35^N^^Valor|184^CampoDet14^Campo Detalle Específico 14^N^C^35^N^^Valor|185^CampoDet15^Campo Detalle Específico 15^N^C^35^N^^Valor|186^CampoDet16^Campo Detalle Específico 16^N^C^35^N^^Valor|187^Campo0^Campo Específico (Clave)^O^C^16^O^TERCERO^Valor|188^Campo1^Campo Específico 1^R^C^35^O^RFC del arrendador^Valor|189^Campo2^Campo Específico 2^O^C^35^O^razón social o nombre^Valor|190^Campo3^Campo Específico 3^O^C^200^O^calle^Valor|191^Campo4^Campo Específico 4^O^C^35^O^numero interior^Valor|192^Campo5^Campo Específico 5^O^C^35^O^numero exterior^Valor|193^Campo6^Campo Específico 6^O^C^35^O^colonia^Valor|194^Campo7^Campo Específico 7^N^C^35^N^localidad^Valor|195^Campo8^Campo Específico 8^N^C^35^N^referencia^Valor|196^Campo9^Campo Específico 9^N^C^35^N^delegacion o municipio^Valor|197^Campo10^Campo Específico 10^N^C^35^N^estado^Valor|198^Campo11^Campo Específico 11^N^C^35^N^pais^Valor|199^Campo12^Campo Específico 12^N^C^35^N^codigo postal^Valor|200^Campo13^Campo Específico 13^N^C^35^N^numero de pedimento aduanero^Valor|201^Campo14^Campo Específico 14^N^C^35^N^fecha de aduana^Valor|202^Campo15^Campo Específico 15^N^C^35^N^nombre de aduana^Valor|203^Campo16^Campo Específico 16^N^C^35^N^cuenta predial^Valor|204^Campo17^Campo Específico 17^N^C^35^N^^Valor|205^Campo18^Campo Específico 18^N^C^35^N^^Valor|206^Campo19^Campo Específico 19^N^C^35^N^^Valor|207^Campo20^Campo Específico 20^N^C^35^N^^Valor|208^Campo21^Campo Específico 21^N^C^35^N^^Valor|209^Campo22^Campo Específico 22^N^C^35^N^^Valor|210^Campo23^Campo Específico 23^N^C^35^N^^Valor|211^Campo24^Campo Específico 24^N^C^35^N^^Valor|212^Campo25^Campo Específico 25^N^C^35^N^^Valor|213^Campo26^Campo Específico 26^N^C^35^N^^Valor|214^Campo27^Campo Específico 27^N^C^35^N^^Valor|215^Campo28^Campo Específico 28^N^C^35^N^^Valor|216^Campo29^Campo Específico 29^N^C^35^N^^Valor|217^Campo30^Campo Específico 30^N^C^35^N^^Valor|218^Campo31^Campo Específico 31^N^C^35^N^^Valor|219^Campo32^Campo Específico 32^N^C^35^N^^Valor|220^Campo33^Campo Específico 33^N^C^35^N^^Valor|221^Campo34^Campo Específico 34^N^C^35^N^^Valor|222^Campo35^Campo Específico 35^N^C^35^N^^Valor|223^Campo36^Campo Específico 36^N^C^35^N^^Valor|224^Campo37^Campo Específico 37^N^C^35^N^^Valor|225^TpMonto^Tipo de Monto Especial^~R^C^18^O^TERCERO^Valor|226^PorMonto^Porcentaje del Monto especial^O^N(15,2)^18^O^tasa de impuesto^Valor|227^Monto^Monto ó importe especial^~R^N(15,2)^18^O^importe del impuesto^Valor|228^IDMonto^Identificador del Monto especial^~R^C^18^O^TRAS ó RET^Valor|229^ClsMonto^Clase del importe especial^O^C^18^O^clase de impuesto (IVA, ISR, IEPS)^Valor|230^FechaMonto^Fecha correspondiente al Monto especial si la hubiera^~R^C^19^O^^Valor|231^TotNeto^Monto total de las líneas de artículos^R^N(15,2)^18^O^0^Valor|232^TotBruto^Importe antes de Impuestos^R^N(15,2)^18^O^0^Valor|233^TotImpR^Monto total del impuesto retenido ^O^N(15,2)^18^O^^Valor|234^TotImpT^Monto total del impuesto trasladado^O^N(15,2)^18^O^^Valor|235^TotImp^Importe Total de Impuestos^N^N(15,2)^18^O^0^Valor|236^TotCargDesc^Total Cargos - Descuentos^N^N(15,2)^18^O^0^Valor|237^Importe^Importe Total a Pagar^R^N(15,2)^18^R^0^Valor|238^TipImpR^Tipo de impuesto Retenido^~R^C^4^~R^ ^Valor|239^PorImpR^% Impuesto Retenido^~R^N(4,2)^7^~R^^Valor|240^MonImpR^Importe Impuesto Retenido^~R^N(15,2)^18^~R^^Valor|241^TipImpT^Tipo de impuesto Trasladado^~R^C^4^~R^IVA^Valor|242^PorImpT^% Impuesto Trasladado^~R^N(4,2)^7^~R^15^Valor|243^MonImpT^Importe Impuesto Trasladado^~R^N(15,2)^18^~R^0^Valor|244^Campo0^Tipo de Campo^O^C^8^O^REGIMEN^Valor|245^Campo1^Campo 1^O^c^40^O^Folio fiscal de la factura fiscal original^Valor|246^Campo2^Campo 2^O^c^40^O^Serie de la factura original^Valor|247^Campo3^Campo 3^O^C^40^O^fecha de la factura original^Valor|248^Campo4^Campo 4^O^C^40^O^monto de la fcatura original^Valor|249^Campo5^Campo 5^O^C^40^O^^Valor|250^Campo6^Campo 6^O^C^40^O^^Valor|251^Campo7^Campo 7^O^c^40^O^^Valor|252^Campo8^Campo 8^O^C^40^O^^Valor|253^Campo9^Campo 9^O^C^40^O^^Valor|254^Campo10^Campo 10^O^C^40^O^^Valor|255^Campo11^Campo 11^O^C^40^O^^Valor";	
	*/

	//Version 3.3 datos

	/*

	var $listacfdi="No^USO^Campos de archivo origen Layout e-Factura^Descripción^Opc/Req^Tipo/Ocurrencias^Longitud
	1^SAT^CFD^Tipo Comprobante^R^C^3
	2^SAT^Version^Version Anexo 20^R^string^
	3^ADD^TipoEnvio^Tipo de envío^EF^C^2
	4^ADD^TipoCliente^Tipo de Cliente^EF^C^35
	5^SAT^Serie^Serie del Comprobante^O^string^25
	6^SAT^Folio^Folio del Comprobante^O^string^40
	7^ADD^Funcion^^O^^1
	8^SAT^Fecha^Atributo requerido para la expresión de la fecha y hora de expedición del Comprobante Fiscal Digital por Internet. Se expresa en la forma AAAA-MM-DDThh:mm:ss y debe corresponder con la hora local donde se expide el comprobante.^R^t_FechaH^19
	9^SAT^Sello^sello digital del comprobante fiscal, al que hacen referencia las reglas de Resolución Miscelánea aplicable. El sello debe ser expresado como una cadena de texto en formato Base 64^R^string^/
	10^SAT^FormaPago^Atributo condicional para expresar la clave de la forma de pago de los bienes o servicios amparados por el comprobante. Si no se conoce la forma de pago este atributo se debe omitir.^O^c_FormaPago^
	11^SAT^NoCertificado^Atributo requerido para expresar el número de serie del certificado de sello digital que ampara al comprobante, de acuerdo con el acuse correspondiente a 20 posiciones otorgado por el sistema del SAT^R^string^20
	12^SAT^Certificado^Atributo requerido que sirve para incorporar el certificado que ampara al comprobante, como texto en formato base 64^R^string^
	13^ADD^NoRecep^Folio de Recibo de Mercancias^O^C^17
	14^ADD^FecNoRecep^Fecha Folio de Recibo de Mercancias^O^C^10
	15^ADD^OrdComp^Número de Pedido^O^C^17
	16^ADD^FecOrdComp^Fecha Pedido^O^C^19
	17^ADD^DepSenderId^Permite crear departamentos^O^C^3
	18^ADD^Complemento^Complemento OAYA^O^C^10
	19^ADD^ComplementoTest^Complemento para datos fijos^O^C^10
	20^ADD^NoDocInt^Número asignado por los proveedores para identificar a un CFD dentro de su empresa^N^C^19
	21^ADD^NoRem^Es el número de remisión con el que se entregó la mercancía^N^C^19
	22^SAT^CondPago^Atributo condicional para expresar las condiciones comerciales aplicables para el pago del comprobante fiscal digital por Internet^C^string^1000
	23^SAT^TotNeto^Atributo requerido para representar la suma de los importes de los conceptos antes de descuentos e impuesto. No se permiten valoresnegativos^R^t_Importe^N(12,6)
	24^ADD^TotBruto^Importe antes de Impuestos^R^N(15,2)^18
	25^SAT^TotCargDesc^Atributo condicional para representar el importe total de los descuentos aplicables antes de impuestos. No se permiten valores negativos. Se debe registrar cuando existan conceptos con descuento.^C^t_Importe^N(12,6)
	26^SAT^DivisOp^Atributo requerido para identificar la clave de la moneda utilizada para expresar los montos, cuando se usa moneda nacional se registra MXN. Conforme con la especificación ISO 4217.^R^c_Moneda^
	27^SAT^TC^Atributo condicional para representar el tipo de cambio conforme con la moneda usada. El valor debe reflejar el número de pesos mexicanos que equivalen a una unidad de la divisa señalada en el atributo moneda. Si el valor está fuera del porcentaje aplicable a la moneda tomado del catálogo c_Moneda, el emisor debe obtener del PAC que vaya a timbrar el CFDI, de manera no automática, una clave de confirmación para ratificar que el valor es correcto e integrar dicha clave en el atributo Confirmacion.^O^Valor mínimo incluyente 0.000001^N(2,6)
	28^SAT^Importe^Atributo requerido para representar la suma del subtotal, menos los descuentos aplicables, más las contribuciones recibidas (impuestos trasladados - federales o locales, derechos, productos, aprovechamientos, aportaciones de seguridad social, contribuciones de mejoras) menos los impuestos retenidos. Si el valor es superior al límite que establezca el SAT en la Resolución Miscelánea Fiscal vigente, el emisor debe obtener del PAC que vaya a timbrar el CFDI, de manera no automática, una clave de confirmación para ratificar que el valor es correcto e integrar dicha clave en el atributo Confirmacion. No se permiten valores negativos.^R^t_Importe^N(12,6)
	29^SAT^EfectoCFD^Atributo requerido para expresar la clave del efecto del comprobante fiscal para el contribuyente emisor.^R^c_TipoDeComprobante^2
	30^SAT^MetPago^Atributo condicional para precisar la clave del método de pago que aplica para este comprobante fiscal digital por Internet, conforme al Artículo 29-A fracción VII incisos a y b del CFF^O^c_MetodoPago^3
	31^SAT^LugarExp^Atributo requerido para incorporar el código postal del lugar de expedición del comprobante (domicilio de la matriz o de la sucursal).^R^c_CatCP^5
	32^SAT^Confirmacion^Atributo condicional para registrar la clave de confirmación que entregue el PAC para expedir el comprobante con importes grandes, con un tipo de cambio fuera del rango establecido o con ambos casos. Es requerido cuando se registra un tipo de cambio o un total fuera del rango establecido.^C^C[0-9a-zA-Z]{5}^5
	33^ADD^FechaCancel^Representa la fecha de cancelación o vencimiento en la cual vencen los días de crédito^O^C^10
	34^ADD^Notas^Información de compras, Obervaciones sobre la factura^O^C^700
	35^ADD^NotasImp^Información de impuestos (Pedimentos)^O^C^150
	36^ADD^ImpLetra^Importe Con Letra^O^C^150
	37^ADD^TermsFlete^Embarque de la Mercancía ó Términos del Flete^O^C^150
	38^ADD^NotasEmp^Información sobre el emisor de la factura^O^C^150
	39^SAT^Campo0 REL Campo1^Atributo requerido para indicar la clave de la relación que existe entre éste que se esta generando y el o los CFDI previos.^R^c_TipoRelacion^36
	40^SAT^Campo0 REL Campo2^Atributo opcional para registrar el folio fiscal (UUID) de un CFDI relacionado con el presente comprobante, por ejemplo: Si el CFDI relacionado es un comprobante de traslado que sirve para registrar el movimiento de la mercancía. Si este comprobante se usa como nota de crédito o nota de débito del comprobante relacionado. Si este comprobante es una devolución sobre el comprobante relacionado. Si éste sustituye a una factura cancelada.^O^string^36
	41^SAT^ERFC^Atributo requerido para registrar la Clave del Registro Federal de Contribuyentes correspondiente al contribuyente emisor del comprobante^R^t_RFC^13
	42^SAT^ENombre^Atributo opcional para registrar el nombre, denominación o razón social del contribuyente emisor del comprobante.^O^string^254
	43^SAT^RegFiscal^Atributo condicional para incorporar el régimen en el que tributa el contribuyente emisor. El catálogo se publicará en el Portal del SAT. Es requerido cuando el contribuyente emisor tenga más de un régimen fiscal registrado en el SAT^R^c_RegimenFiscal^2
	44^ADD^ENoProv^Emisor numero de proveedor^O^N^35
	45^ADD^EGLN^Código GLN del emisor de la factura^O^C^35
	46^ADD^ECalle^Nombre de la calle del Emisor de la Factura^~R^C^100
	47^ADD^EColon^Colonia del Emisor de la Factura^O^C^100
	48^ADD^EMunic^Municipio del Emisor de la Factura^~R^C^100
	49^ADD^EEdo^Estado del Emisor de la Factura^~R^C^35
	50^ADD^Epais^Pais del Emisor de la Factura^~R^C^35
	51^ADD^ECP^Código Postal del Emisor de la Factura^~R^C^5
	52^ADD^Eemail^Email del emisor factura^~R^C^75
	53^ADD^ENoArea^Número de área del emisor del CFD^~R^C^17
	54^ADD^ExNombre^Nombre de la Sucursal donde es Expedido el CFD^O^C^70
	55^ADD^ExCalle^Nombre de la calle donde es Expedido el CFD^O^C^70
	56^ADD^ExColon^Colonia de la ubicación donde es expedido el CFD^O^C^35
	57^ADD^ExMunic^Municipo de la ubicación donde es expedido el CFD^O^C^35
	58^ADD^ExEdo^Estado de la Ubicación  donde es expedido el CFD^O^C^35
	59^ADD^Expais^Pais de la ubicación donde es expedido el CFD^~R^C^35
	60^ADD^ExCP^Código de la ubicación donde es expedido el CFD^O^C^9
	61^SAT^RRFC^Atributo requerido para precisar la Clave del Registro Federal de Contribuyentes correspondiente al contribuyente receptor del comprobante^R^t_RFC^13
	62^SAT^RNombre^Razón social Receptor de la Factura^O^string^254
	63^SAT^RResFiscal^Atributo condicional para registrar la clave del país de residencia para efectos fiscales del receptor del comprobante, cuando se trate de un extranjero, y que es conforme con la especificación ISO 3166-1 alpha-3. Es requerido cuando se incluya el complemento de comercio exterior o se registre el atributo NumRegIdTrib.^C^c_Pais^3
	64^SAT^RNumRegTrib OR If TipoCliente=EXTRANJERO Use RRFC^Atributo condicional para expresar el número de registro de identidad fiscal del receptor cuando sea residente en el extranjero. Es requerido cuando se incluya el complemento de comercio exterior.^C^string^40
	65^SAT^RUsoCFDI^Atributo requerido para expresar la clave del uso que dará a esta factura el receptor del CFDI.^R^c_UsoCFDI^/
	66^ADD^RGLN^Código GLN del receptor de la factura^O^C^35
	67^ADD^RContac^Persona de Contacto^O^C^35
	68^ADD^RIEPS^Identificación IEPS del receptor de la factura^O^C^35
	69^ADD^Rcalle^Nombre de la calle del Receptor de la Fact.^O^C^100
	70^ADD^RColon^Colonia del Receptor de la Factura^O^C^100
	71^ADD^RMunic^Municipio del Receptor de la Factura^O^C^100
	72^ADD^REdo^Estado  del Receptor de la Factura^O^C^100
	73^ADD^Rpais^Pais del Receptor de la Factura^~R^C^35
	74^ADD^RCP^Código Postal del Receptor de la Factura^O^C^5
	75^ADD^CoGLN^Código GLN del Comprador^O^C^35
	76^ADD^CoContac^Persona de Contacto de Compras^O^C^35
	77^ADD^PrNoProv^Número del Proveedor^O^N^35
	78^ADD^PrGLN^Código GLN del proveedor de la Mercancía^O^C^35
	79^ADD^PrIEPS^Codigo IEPS del proveedor^^^
	80^ADD^RmNombre^Razón social Receptor de la Mercancía^O^C^70
	81^ADD^RmGLN^Código GLN del receptor de la Mercancía^O^C^35
	82^ADD^RmCalle^Nombre de la calle del Receptor de la Mercancía^O^C^70
	83^ADD^RmNoext^Numero Exterior de la calle del Receptor de la Merc.^O^C^30
	84^ADD^RmNoint^Numero adicional de la calle del Receptor de la Fact.^O^C^30
	85^ADD^RmColon^Colonia del Receptor de la Factura^O^C^50
	86^ADD^RmLoc^Localidad del Receptor de la Factura^O^C^50
	87^ADD^RmRef^Referencia de ubicación adicional del Receptor Fact.^O^C^35
	88^ADD^RmMunic^Municipio del Receptor de la Mercancía^O^C^35
	89^ADD^RmEdo^Estado  del Receptor de la Mercancía^O^C^35
	90^ADD^Rmpais^Pais del Receptor de la Mercancía^O^C^35
	91^ADD^RmCP^Código Postal del Receptor de la Mercancía^O^C^9
	92^ADD^DivisFn^Función de divisa^N^C^20
	93^ADD^RelTiempo^Relación de tiempo^O^C^3
	94^ADD^RefTiempo^Referencia del tiempo de pago^^^
	95^ADD^TipoPeriodo^Tipo de período^O^C^3
	96^ADD^NumPeriodos^Número de períodos^O^C^50
	97^ADD^TipoDescPago^Tipo descuento pago^O^C^35
	98^ADD^PorDescPago^Porcentaje descuento pago^O^N(4,2)^7
	99^ADD^FechaIni^Inicio periodo de liquidación^N^C^19
	100^ADD^FechaVen^Fin periodo de liquidación^N^C^19
	101^ADD^TpNoSP^Tipo Número para identificadores especiales^O^C^150
	102^ADD^NoSP^Número del Identificador especificado^O^C^150
	103^ADD^IdNoSP^Identificador del Número especificado^O^C^150
	104^ADD^FechaNoSP^Fecha Identificador especificado^O^C^10
	105^ADD^TxtNoSP^Cualquier Referencia Identificador especificado^O^C^10
	106^ADD^Cont^Contacto Emisor o Receptor^~R^C^1
	107^ADD^TipoCont^Tipo de Contacto^~R^C^40
	108^ADD^NombreCont^Nombre del Contacto^O^C^60
	109^ADD^EmailCont^Email del contacto^O^C^35
	110^ADD^TelefonoCont^Teléfono del Contacto^O^N(4,2)^35
	111^ADD^DcIndDC^Indicador Descuento o Cargo^~R^C^35
	112^ADD^DcImputacion^Imputación Descuento/Cargo^O^C^35
	113^ADD^DcTipo^Tipo Descuento/Cargo^O^string^50
	114^ADD^DcBase^Base del porcentaje que se aplicará^O^C^35
	115^ADD^DcPorcentaje^Porcentaje Descuento/Cargo^O^N(4,2)^7
	116^ADD^DcImporte^Importe Descuento/Cargo^O^N(12,2)^15
	117^ADD^IndicadorR^Indicador de Retención de Impuestos^N^C^1
	118^ADD^BaseR^Base de retención^N^C^18
	119^SAT^NumLin^Número de Línea (autonumérico)^R^N^6
	120^ADD^EAN^Código EAN del artículo solicitado^O^C^35
	121^SAT^ClaveProdServ^Atributo requerido para expresar la clave del producto o del servicio amparado por el presente concepto. Es requerido y deben utilizar las claves del catálogo de productos y servicios, cuando los conceptos que registren por sus actividades correspondan con dichos conceptos^R^c_ClaveProdServ^
	122^ADD^CodigoArt^Código Artículo del Comprador^O^C^50
	123^SAT^CodArtPro^Atributo opcional para expresar el número de parte, identificador del producto o del servicio, la clave de producto o servicio, SKU o equivalente, propia de la operación del emisor, amparado por el presente concepto. Opcionalmente se puede utilizar claves del estándar GTIN.^O^string^100
	124^ADD^NumSerie^Número de Serie^O^C^35
	125^SAT^Cant^Cantidad FacturadaAtributo requerido para precisar la cantidad de bienes o servicios del tipo particular definido por el presente concepto^R^Valor mínimo incluyente 0.000001^N(12,6)
	126^ADD^CantGratis^Cantidad gratis de mercancía^O^N(12,0)^15
	127^SAT^ClaveUnidad^Atributo requerido para precisar la clave de unidad de medida estandarizada aplicable para la cantidad expresada en el concepto. La unidad debe corresponder con la descripción del concepto.^R^c_ClaveUnidad^2
	128^SAT^UM^Atributo requerido para precisar la unidad de medida aplicable para la cantidad expresada en el concepto. La unidad debe corresponder con la descripción del concepto.Opcionalmente se pueden usar claves del catálogo de unidades especificado por la recomendación 20 de la UNECE.Cuando se genere una factura global:• No se debe registrar información en el elemento Parte.• Se debe registrar por cada comprobante de operaciones con público en general con serie y/o folio, un concepto con el valor “COP” en el atributo Unidad y en el atributo NoIdentificacion el número de serie y folio.• Para las operaciones con público en general sin comprobante, se debe registrar un concepto con el valor “COP” en el atributo Unidad, en la cantidad el número de operaciones, en el valor unitario el valor promedio de las operaciones y en los impuestos el valor sumarizado de impuestos.^O^string^20
	129^ADD^UnidCom^Nº de unidades de consumo en unidad comercializada^O^N(12,3)^15
	130^SAT^Desc^Atributo requerido para precisar la descripción del bien o servicio cubierto por el presente concepto.^R^string^1000
	131^SAT^PrecMX^Atributo requerido para precisar el valor o precio unitario del bien o servicio cubierto por el presente concepto.^R^t_Importe^18
	132^SAT^ImporMX^Atributo requerido para precisar el importe total de los bienes o servicios del presente concepto. Debe ser equivalente al resultado de multiplicar la cantidad por el valor unitario expresado en el concepto. No se permiten valores negativos.^R^t_Importe^18
	133^SAT^DescuentoArt^Atributo opcional para representar el importe de los descuentos aplicables al concepto. No se permiten valores negativos^O^t_Importe^18
	134^ADD^Precop^Precio neto unitario en divisa opcional^O^N^8
	135^ADD^PrecBruto^^^^
	136^ADD^ImporOp^Importe en divisa opcional^O^N^10
	137^ADD^ImporBruto^Importe Bruto de la Línea de Articulo^O^N(15,2)^18
	138^ADD^ImporNeto^Importe Neto de la Línea con Desc./Cargos con Impuestos^O^N(15,2)^18
	139^ADD^LinOrdComp^Número de órden de compra^O^C^17
	140^ADD^NivOrdComp^Nivel de órden de compra^O^C^17
	141^ADD^TpNotaslin^Tipo Observaciones^O^C^35
	142^ADD^Notaslin^Observaciones de Línea^O^C^35
	143^ADD^TpoPrecio^Código Seriado de Unidad de Envío^O^C^35
	144^ADD^PrecList^xxx^O^C^35
	145^ADD^UlCodSeriado^Código Seriado de Unidad de Envío^O^C^35
	146^ADD^UlSRV^Número global de unidades de  comercialización^O^C^35
	147^ADD^IENumPalet^Numero de paquetes ^N^N(15)^15
	148^ADD^IEDescPalet^Descripción del  empaquetado^N^C^70
	149^ADD^IETipoPalet^Tipo de empaquetado^N^C^35
	150^ADD^IEPagoTrans^Pago de transporte de embalaje^N^C^35
	151^ADD^ILNumLote^Número de lote^N^C^35
	152^ADD^ILFechaLote^Fecha de producción^N^C^10
	153^ADD^NoReleased^Numero de Release^N^C^10
	154^ADD^DtIndDC^Indicador Descuento o Cargo^~R^C^35
	155^ADD^DtImputacion^Imputación Descuento/Cargo^~R^C^35
	156^ADD^DtSecuencia^Indicador de secuencia de cálculo^N^N^3
	157^ADD^DtTipo^Tipo Descuento/Cargo^O^C^3
	158^ADD^DtPorcentaje^Porcentaje Descuento/Cargo^O^N(4,2)^7
	159^ADD^DtImpUnidad^Importe Descuento/Cargo por unidad^O^N(15,2)^18

	160^SAT^DescTipoImp^Atributo requerido para señalar la clave del tipo de impuesto trasladado aplicable al concepto.^R^c_Impuesto^2
	161^SAT^BaseImp^Atributo requerido para señalar la base para el cálculo del impuesto, la determinación de la base se realiza de acuerdo con las disposiciones fiscales vigentes. No se permiten valores negativos.^R^t_Importe^N(12,6)
	162^^CategImp^Identificador del nodo Traslado^R^^1
	163^SAT^TipoFactor^Atributo requerido para señalar la clave del tipo de factor que se aplica a la base del impuesto.^R^c_TipoFactor^2
	164^SAT^PorImp^Atributo condicional para señalar el valor de la tasa o cuota del impuesto que se traslada para el presente concepto. Es requerido cuando el atributo TipoFactor tenga un valor que corresponda a Tasa o Cuota.^C^c_TasaOCuota^1	


	165^SAT^ImporImp^Atributo condicional para señalar el importe del impuesto trasladado que aplica al concepto. No se permiten valores negativos. Es requerido cuando TipoFactor sea Tasa o Cuota^C^t_Importe^18
	166^SAT^BaseImp^Atributo requerido para señalar la base para el cálculo del impuesto, la determinación de la base se realiza de acuerdo con las disposiciones fiscales vigentes. No se permiten valores negativos.^R^t_Importe^N(12,6)
	167^SAT^DescTipoImp^Atributo requerido para señalar la clave del tipo de impuesto trasladado aplicable al concepto.^R^c_Impuesto^2
	168^SAT^TipoFactor^Atributo requerido para señalar la clave del tipo de factor que se aplica a la base del impuesto.^R^c_TipoFactor^2
	169^SAT^PorImp^Atributo condicional para señalar el valor de la tasa o cuota del impuesto que se traslada para el presente concepto. Es requerido cuando el atributo TipoFactor tenga un valor que corresponda a Tasa o Cuota.^R^decimal^1
	170^^CategImp^Identificador del nodo Retenido^R^^1
	171^SAT^ImporImp^Atributo condicional para señalar el importe del impuesto trasladado que aplica al concepto. No se permiten valores negativos. Es requerido cuando TipoFactor sea Tasa o Cuota^R^t_Importe^18
	172^SAT^Pedimen^Atributo requerido para expresar el número del pedimento que ampara la importación del bien que se expresa en el siguiente formato: últimos 2 dígitos del año de validación seguidos por dos espacios, 2 dígitos de la aduana de despacho seguidos por dos espacios, 4 dígitos del número de la patente seguidos por dos espacios, 1 dígito que corresponde al último dígito del año en curso, salvo que se trate de un pedimento consolidado iniciado en el año inmediato anterior o del pedimento original de una rectificación, seguido de 6 dígitos de la numeración progresiva por aduana.^R^string^21
	173^SAT^NumCPred^Atributo requerido para precisar el número de la cuenta predial del inmueble cubierto por el presente concepto, o bien para incorporar los datos de identificación del certificado de participación inmobiliaria no amortizable, tratándose de arrendamiento.^R^string^150
	174^SAT^CampoDet0^Campo Detalle Específico (Clave)^N^C^4
	175^SAT^CampoDet1^Campo Detalle Específico 1^N^C^35
	176^SAT^PPClaveProdServ^Atributo requerido para expresar la clave del producto o del servicio amparado por el presente concepto. Es requerido y deben utilizar las claves del catálogo de productos y servicios, cuando los conceptos que registren por sus actividades correspondan con dichos conceptos^R^c_ClaveProdServ^
	177^SAT^PPCodArtPro^Atributo opcional para expresar el número de parte, identificador del producto o del servicio, la clave de producto o servicio, SKU o equivalente, propia de la operación del emisor, amparado por el presente concepto. Opcionalmente se puede utilizar claves del estándar GTIN.^O^string^100
	178^SAT^PPcant^Atributo requerido para precisar la cantidad de bienes o servicios del tipo particular definido por la presente parte^R^Valor mínimo incluyente 0.000001^N(12,6)
	179^SAT^PPUM^Atributo opcional para precisar la unidad de medida propia de la operación del emisor, aplicable para la cantidad expresada en la parte. La unidad debe corresponder con la descripción de la parte.^O^string^20
	180^SAT^PDesc^Atributo requerido para precisar la descripción del bien o servicio cubierto por la presente parte.^R^string^1000
	181^SAT^PPrecMX^Atributo opcional para precisar el valor o precio unitario del bien o servicio cubierto por la presente parte. No se permiten valores negativos.^O^t_Importe^18
	182^SAT^PPImporMx^Atributo opcional para precisar el importe total de los bienes o servicios de la presente parte. Debe ser equivalente al resultado de multiplicar la cantidad por el valor unitario expresado en la parte. No se permiten valores negativos.^^^
	183^SAT^PPedimen^Atributo requerido para expresar el número del pedimento que ampara la importación del bien que se expresa en el siguiente formato: últimos 2 dígitos del año de validación seguidos por dos espacios, 2 dígitos de la aduana de despacho seguidos por dos espacios, 4 dígitos del número de la patente seguidos por dos espacios, 1 dígito que corresponde al último dígito del año en curso, salvo que se trate de un pedimento consolidado iniciado en el año inmediato anterior o del pedimento original de una rectificación, seguido de 6 dígitos de la numeración progresiva por aduana.^R^string^21
	184^ADD^TpMonto^Tipo de Monto Especial^~R^C^18
	185^ADD^PorMonto^Porcentaje del Monto especial^O^N(15,2)^18
	186^ADD^Monto^Monto ó importe especial^~R^N(15,2)^18
	187^ADD^IDMonto^Identificador del Monto especial^~R^C^18
	188^ADD^ClsMonto^Clase del importe especial^O^C^18

	189^SAT^TotImpR^Atributo condicional para expresar el total de los impuestos retenidos que se desprenden de los conceptos expresados en el comprobante fiscal digital por Internet. No se permiten valores negativos. Es requerido cuando en los conceptos se registren impuestos retenidos^C^t_Importe^18

	189^SAT^TotImpT^Atributo condicional para expresar el total de los impuestos trasladados que se desprenden de los conceptos expresados en el comprobante fiscal digital por Internet. No se permiten valores negativos. Es requerido cuando en los conceptos se registren impuestos trasladados^C^t_Importe^18   *****Lista 190
	190^ADD^TotImp^Importe Total de Impuestos^O^N(15,2)^18				*****Lista 191
	191^SAT^TotNeto^Atributo requerido para representar la suma de los importes de los conceptos antes de descuentos e impuesto. No se permiten valoresnegativos^R^t_Importe^N(12,6) *****Lista 23
	192^ADD^TotBruto^Importe antes de Impuestos^R^N(15,2)^18		*****Lista 24
	193^SAT^Importe^Atributo requerido para representar la suma del subtotal, menos los descuentos aplicables, más las contribuciones recibidas (impuestos trasladados - federales o locales, derechos, productos, aprovechamientos, aportaciones de seguridad social, contribuciones de mejoras) menos los impuestos retenidos. Si el valor es superior al límite que establezca el SAT en la Resolución Miscelánea Fiscal vigente, el emisor debe obtener del PAC que vaya a timbrar el CFDI, de manera no automática, una clave de confirmación para ratificar que el valor es correcto e integrar dicha clave en el atributo Confirmacion. No se permiten valores negativos.^R^t_Importe^N(12,6) *****Lista 28
	
	192^SAT^TipImpR^Atributo requerido para señalar la clave del tipo de impuesto retenido^R^c_Impuesto^2 
	193^SAT^MonImpR^Atributo requerido para señalar el monto del impuesto retenido. No se permiten valores negativos.^~R^t_Importe^18
	
	194^SAT^TipImpT^Atributo requerido para señalar la clave del tipo de impuesto trasladado.^R^c_Impuesto^4
	195^SAT^TipFactT^Atributo requerido para señalar el tipo de factor que se aplica a la base del impuesto^R^c_TipoFactor^5
	196^SAT^PorImpT^Atributo requerido para señalar el valor de la tasa o cuota del impuesto que se traslada por los conceptos amparados en el comprobante.^R^c_TasaOCuota^18
	197^SAT^MonImpT^Atributo requerido para señalar la suma del importe del impuesto trasladado, agrupado por impuesto, TipoFactor y TasaOCuota. No se permiten valores negativos.^R^t_Importe^18
	198^^Campo0^TERCERO^O^C^16
	199^^Campo1^RFC del arrendador^R^C^35
	200^^Campo2^razón social o nombre^O^C^35
	201^^Campo3^calle^O^C^200
	202^^Campo4^numero interior^O^C^35
	203^^Campo5^numero exterior^O^C^35
	204^^Campo6^colonia^O^C^35
	205^^Campo7^localidad^N^C^35
	206^^Campo8^referencia^N^C^35
	207^^Campo9^delegacion o municipio^N^C^35
	208^^Campo10^estado^N^C^35
	209^^Campo11^pais^N^C^35
	210^^Campo12^codigo postal^N^C^35
	211^^Campo13^numero de pedimento aduanero^N^C^35
	212^^Campo14^fecha de aduana^N^C^35
	213^^Campo15^nombre de aduana^N^C^35
	214^^Campo16^cuenta predial^N^C^35
	215^^Campo17^^N^C^35
	216^^Campo18^^N^C^35
	217^^Campo19^^N^C^35
	218^^Campo20^^N^C^35
	219^^Campo21^^N^C^35
	220^^Campo22^^N^C^35
	221^^Campo23^^N^C^35
	222^^Campo24^^N^C^35
	223^^Campo25^^N^C^35
	224^^Campo26^^N^C^35
	225^^Campo27^^N^C^35
	226^^Campo28^^N^C^35
	227^^Campo29^^N^C^35
	228^^Campo30^^N^C^35
	229^^Campo31^^N^C^35
	230^^Campo32^^N^C^35
	231^^Campo33^^N^C^35
	232^^Campo34^^N^C^35
	233^^Campo35^^N^C^35
	234^^Campo36^^N^C^35
	235^^Campo37^^N^C^35
	236^^TpMonto^TERCERO^~R^C^18
	237^^PorMonto^tasa de impuesto^O^N(15,2)^18
	238^^Monto^importe del impuesto^~R^N(15,2)^18
	239^^IDMonto^TRAS ó RET^~R^C^18
	240^^ClsMonto^clase de impuesto (IVA, ISR, IEPS)^O^C^18
	241^^FechaMonto^^~R^C^19";


	*/


 var $listacfdi="No^USO^Campos de archivo origen Layout e-Factura^Descripción^Opc/Req^Tipo/Ocurrencias^Longitud|1^SAT^CFD^Tipo Comprobante^R^C^3|2^SAT^Version^Version Anexo 20^R^string^|3^ADD^TipoEnvio^Tipo de envío^EF^C^2|4^ADD^TipoCliente^Tipo de Cliente^EF^C^35|5^SAT^Serie^Serie del Comprobante^O^string^25|6^SAT^Folio^Folio del Comprobante^O^string^40|7^ADD^Funcion^^O^^1|8^SAT^Fecha^Atributo requerido para la expresión de la fecha y hora de expedición del Comprobante Fiscal Digital por Internet. Se expresa en la forma AAAA-MM-DDThh:mm:ss y debe corresponder con la hora local donde se expide el comprobante.^R^t_FechaH^19|9^SAT^Sello^sello digital del comprobante fiscal, al que hacen referencia las reglas de Resolución Miscelánea aplicable. El sello debe ser expresado como una cadena de texto en formato Base 64^R^string^/|10^SAT^FormaPago^Atributo condicional para expresar la clave de la forma de pago de los bienes o servicios amparados por el comprobante. Si no se conoce la forma de pago este atributo se debe omitir.^O^c_FormaPago^|11^SAT^NoCertificado^Atributo requerido para expresar el número de serie del certificado de sello digital que ampara al comprobante, de acuerdo con el acuse correspondiente a 20 posiciones otorgado por el sistema del SAT^R^string^20|12^SAT^Certificado^Atributo requerido que sirve para incorporar el certificado que ampara al comprobante, como texto en formato base 64^R^string^|13^ADD^NoRecep^Folio de Recibo de Mercancias^O^C^17|14^ADD^FecNoRecep^Fecha Folio de Recibo de Mercancias^O^C^10|15^ADD^OrdComp^Número de Pedido^O^C^17|16^ADD^FecOrdComp^Fecha Pedido^O^C^19|17^ADD^DepSenderId^Permite crear departamentos^O^C^3|18^ADD^Complemento^Complemento OAYA^O^C^10|19^ADD^ComplementoTest^Complemento para datos fijos^O^C^10|20^ADD^NoDocInt^Número asignado por los proveedores para identificar a un CFD dentro de su empresa^N^C^19|21^ADD^NoRem^Es el número de remisión con el que se entregó la mercancía^N^C^19|22^SAT^CondPago^Atributo condicional para expresar las condiciones comerciales aplicables para el pago del comprobante fiscal digital por Internet^C^string^1000|23^SAT^TotNeto^Atributo requerido para representar la suma de los importes de los conceptos antes de descuentos e impuesto. No se permiten valores negativos^R^t_Importe^N(12,6)|24^ADD^TotBruto^Importe antes de Impuestos^R^N(15,2)^18|25^SAT^TotCargDesc^Atributo condicional para representar el importe total de los descuentos aplicables antes de impuestos. No se permiten valores negativos. Se debe registrar cuando existan conceptos con descuento.^C^t_Importe^N(12,6)|26^SAT^DivisOp^Atributo requerido para identificar la clave de la moneda utilizada para expresar los montos, cuando se usa moneda nacional se registra MXN. Conforme con la especificación ISO 4217.^R^c_Moneda^|27^SAT^TC^Atributo condicional para representar el tipo de cambio conforme con la moneda usada. El valor debe reflejar el número de pesos mexicanos que equivalen a una unidad de la divisa señalada en el atributo moneda. Si el valor está fuera del porcentaje aplicable a la moneda tomado del catálogo c_Moneda, el emisor debe obtener del PAC que vaya a timbrar el CFDI, de manera no automática, una clave de confirmación para ratificar que el valor es correcto e integrar dicha clave en el atributo Confirmacion.^O^Valor mínimo incluyente 0.000001^N(2,6)|28^SAT^Importe^Atributo requerido para representar la suma del subtotal, menos los descuentos aplicables, más las contribuciones recibidas (impuestos trasladados - federales o locales, derechos, productos, aprovechamientos, aportaciones de seguridad social, contribuciones de mejoras) menos los impuestos retenidos. Si el valor es superior al límite que establezca el SAT en la Resolución Miscelánea Fiscal vigente, el emisor debe obtener del PAC que vaya a timbrar el CFDI, de manera no automática, una clave de confirmación para ratificar que el valor es correcto e integrar dicha clave en el atributo Confirmacion. No se permiten valores negativos.^R^t_Importe^N(12,6)|29^SAT^EfectoCFD^Atributo requerido para expresar la clave del efecto del comprobante fiscal para el contribuyente emisor.^R^c_TipoDeComprobante^2|30^SAT^MetPago^Atributo condicional para precisar la clave del método de pago que aplica para este comprobante fiscal digital por Internet, conforme al Artículo 29-A fracción VII incisos a y b del CFF^O^c_MetodoPago^3|31^SAT^LugarExp^Atributo requerido para incorporar el código postal del lugar de expedición del comprobante (domicilio de la matriz o de la sucursal).^R^c_CatCP^5|32^SAT^Confirmacion^Atributo condicional para registrar la clave de confirmación que entregue el PAC para expedir el comprobante con importes grandes, con un tipo de cambio fuera del rango establecido o con ambos casos. Es requerido cuando se registra un tipo de cambio o un total fuera del rango establecido.^C^C[0-9a-zA-Z]{5}^5|33^ADD^FechaCancel^Representa la fecha de cancelación o vencimiento en la cual vencen los días de crédito^O^C^10|34^ADD^Notas^Información de compras, Obervaciones sobre la factura^O^C^700|35^ADD^NotasImp^Información de impuestos (Pedimentos)^O^C^150|36^ADD^ImpLetra^Importe Con Letra^O^C^150|37^ADD^TermsFlete^Embarque de la Mercancía ó Términos del Flete^O^C^150|38^ADD^NotasEmp^Información sobre el emisor de la factura^O^C^150|39^SAT^Campo0 REL Campo1^Atributo requerido para indicar la clave de la relación que existe entre éste que se esta generando y el o los CFDI previos.^R^c_TipoRelacion^36|40^SAT^Campo0 REL Campo2^Atributo opcional para registrar el folio fiscal (UUID) de un CFDI relacionado con el presente comprobante, por ejemplo: Si el CFDI relacionado es un comprobante de traslado que sirve para registrar el movimiento de la mercancía. Si este comprobante se usa como nota de crédito o nota de débito del comprobante relacionado. Si este comprobante es una devolución sobre el comprobante relacionado. Si éste sustituye a una factura cancelada.^O^string^36|41^SAT^ERFC^Atributo requerido para registrar la Clave del Registro Federal de Contribuyentes correspondiente al contribuyente emisor del comprobante^R^t_RFC^13|42^SAT^ENombre^Atributo opcional para registrar el nombre, denominación o razón social del contribuyente emisor del comprobante.^O^string^254|43^SAT^RegFiscal^Atributo condicional para incorporar el régimen en el que tributa el contribuyente emisor. El catálogo se publicará en el Portal del SAT. Es requerido cuando el contribuyente emisor tenga más de un régimen fiscal registrado en el SAT^R^c_RegimenFiscal^2|44^ADD^ENoProv^Emisor numero de proveedor^O^N^35|45^ADD^EGLN^Código GLN del emisor de la factura^O^C^35|46^ADD^ECalle^Nombre de la calle del Emisor de la Factura^~R^C^100|47^ADD^EColon^Colonia del Emisor de la Factura^O^C^100|48^ADD^EMunic^Municipio del Emisor de la Factura^~R^C^100|49^ADD^EEdo^Estado del Emisor de la Factura^~R^C^35|50^ADD^Epais^Pais del Emisor de la Factura^~R^C^35|51^ADD^ECP^Código Postal del Emisor de la Factura^~R^C^5|52^ADD^Eemail^Email del emisor factura^~R^C^75|53^ADD^ENoArea^Número de área del emisor del CFD^~R^C^17|54^ADD^ExNombre^Nombre de la Sucursal donde es Expedido el CFD^O^C^70|55^ADD^ExCalle^Nombre de la calle donde es Expedido el CFD^O^C^70|56^ADD^ExColon^Colonia de la ubicación donde es expedido el CFD^O^C^35|57^ADD^ExMunic^Municipo de la ubicación donde es expedido el CFD^O^C^35|58^ADD^ExEdo^Estado de la Ubicación  donde es expedido el CFD^O^C^35|59^ADD^Expais^Pais de la ubicación donde es expedido el CFD^~R^C^35|60^ADD^ExCP^Código de la ubicación donde es expedido el CFD^O^C^9|61^SAT^RRFC^Atributo requerido para precisar la Clave del Registro Federal de Contribuyentes correspondiente al contribuyente receptor del comprobante^R^t_RFC^13|62^SAT^RNombre^Razón social Receptor de la Factura^O^string^254|63^SAT^RResFiscal^Atributo condicional para registrar la clave del país de residencia para efectos fiscales del receptor del comprobante, cuando se trate de un extranjero, y que es conforme con la especificación ISO 3166-1 alpha-3. Es requerido cuando se incluya el complemento de comercio exterior o se registre el atributo NumRegIdTrib.^C^c_Pais^3|64^SAT^RNumRegTrib OR If TipoCliente=EXTRANJERO Use RRFC^Atributo condicional para expresar el número de registro de identidad fiscal del receptor cuando sea residente en el extranjero. Es requerido cuando se incluya el complemento de comercio exterior.^C^string^40|65^SAT^RUsoCFDI^Atributo requerido para expresar la clave del uso que dará a esta factura el receptor del CFDI.^R^c_UsoCFDI^/|66^ADD^RGLN^Código GLN del receptor de la factura^O^C^35|67^ADD^RContac^Persona de Contacto^O^C^35|68^ADD^RIEPS^Identificación IEPS del receptor de la factura^O^C^35|69^ADD^Rcalle^Nombre de la calle del Receptor de la Fact.^O^C^100|70^ADD^RColon^Colonia del Receptor de la Factura^O^C^100|71^ADD^RMunic^Municipio del Receptor de la Factura^O^C^100|72^ADD^REdo^Estado  del Receptor de la Factura^O^C^100|73^ADD^Rpais^Pais del Receptor de la Factura^~R^C^35|74^ADD^RCP^Código Postal del Receptor de la Factura^O^C^5|75^ADD^CoGLN^Código GLN del Comprador^O^C^35|76^ADD^CoContac^Persona de Contacto de Compras^O^C^35|77^ADD^PrNoProv^Número del Proveedor^O^N^35|78^ADD^PrGLN^Código GLN del proveedor de la Mercancía^O^C^35|79^ADD^PrIEPS^Codigo IEPS del proveedor^^^|80^ADD^RmNombre^Razón social Receptor de la Mercancía^O^C^70|81^ADD^RmGLN^Código GLN del receptor de la Mercancía^O^C^35|82^ADD^RmCalle^Nombre de la calle del Receptor de la Mercancía^O^C^70|83^ADD^RmNoext^Numero Exterior de la calle del Receptor de la Merc.^O^C^30|84^ADD^RmNoint^Numero adicional de la calle del Receptor de la Fact.^O^C^30|85^ADD^RmColon^Colonia del Receptor de la Factura^O^C^50|86^ADD^RmLoc^Localidad del Receptor de la Factura^O^C^50|87^ADD^RmRef^Referencia de ubicación adicional del Receptor Fact.^O^C^35|88^ADD^RmMunic^Municipio del Receptor de la Mercancía^O^C^35|89^ADD^RmEdo^Estado  del Receptor de la Mercancía^O^C^35|90^ADD^Rmpais^Pais del Receptor de la Mercancía^O^C^35|91^ADD^RmCP^Código Postal del Receptor de la Mercancía^O^C^9|92^ADD^DivisFn^Función de divisa^N^C^20|93^ADD^RelTiempo^Relación de tiempo^O^C^3|94^ADD^RefTiempo^Referencia del tiempo de pago^^^|95^ADD^TipoPeriodo^Tipo de período^O^C^3|96^ADD^NumPeriodos^Número de períodos^O^C^50|97^ADD^TipoDescPago^Tipo descuento pago^O^C^35|98^ADD^PorDescPago^Porcentaje descuento pago^O^N(4,2)^7|99^ADD^FechaIni^Inicio periodo de liquidación^N^C^19|100^ADD^FechaVen^Fin periodo de liquidación^N^C^19|101^ADD^TpNoSP^Tipo Número para identificadores especiales^O^C^150|102^ADD^NoSP^Número del Identificador especificado^O^C^150|103^ADD^IdNoSP^Identificador del Número especificado^O^C^150|104^ADD^FechaNoSP^Fecha Identificador especificado^O^C^10|105^ADD^TxtNoSP^Cualquier Referencia Identificador especificado^O^C^10|106^ADD^Cont^Contacto Emisor o Receptor^~R^C^1|107^ADD^TipoCont^Tipo de Contacto^~R^C^40|108^ADD^NombreCont^Nombre del Contacto^O^C^60|109^ADD^EmailCont^Email del contacto^O^C^35|110^ADD^TelefonoCont^Teléfono del Contacto^O^N(4,2)^35|111^ADD^DcIndDC^Indicador Descuento o Cargo^~R^C^35|112^ADD^DcImputacion^Imputación Descuento/Cargo^O^C^35|113^ADD^DcTipo^Tipo Descuento/Cargo^O^string^50|114^ADD^DcBase^Base del porcentaje que se aplicará^O^C^35|115^ADD^DcPorcentaje^Porcentaje Descuento/Cargo^O^N(4,2)^7|116^ADD^DcImporte^Importe Descuento/Cargo^O^N(12,2)^15|117^ADD^IndicadorR^Indicador de Retención de Impuestos^N^C^1|118^ADD^BaseR^Base de retención^N^C^18|119^SAT^NumLin^Número de Línea (autonumérico)^R^N^6|120^ADD^EAN^Código EAN del artículo solicitado^O^C^35|121^SAT^ClaveProdServ^Atributo requerido para expresar la clave del producto o del servicio amparado por el presente concepto. Es requerido y deben utilizar las claves del catálogo de productos y servicios, cuando los conceptos que registren por sus actividades correspondan con dichos conceptos^R^c_ClaveProdServ^|122^ADD^CodigoArt^Código Artículo del Comprador^O^C^50|123^SAT^CodArtPro^Atributo opcional para expresar el número de parte, identificador del producto o del servicio, la clave de producto o servicio, SKU o equivalente, propia de la operación del emisor, amparado por el presente concepto. Opcionalmente se puede utilizar claves del estándar GTIN.^O^string^100|124^ADD^NumSerie^Número de Serie^O^C^35|125^SAT^Cant^Cantidad FacturadaAtributo requerido para precisar la cantidad de bienes o servicios del tipo particular definido por el presente concepto^R^Valor mínimo incluyente 0.000001^N(12,6)|126^ADD^CantGratis^Cantidad gratis de mercancía^O^N(12,0)^15|127^SAT^ClaveUnidad^Atributo requerido para precisar la clave de unidad de medida estandarizada aplicable para la cantidad expresada en el concepto. La unidad debe corresponder con la descripción del concepto.^R^c_ClaveUnidad^2|128^SAT^UM^Atributo requerido para precisar la unidad de medida aplicable para la cantidad expresada en el concepto. La unidad debe corresponder con la descripción del concepto.Opcionalmente se pueden usar claves del catálogo de unidades especificado por la recomendación 20 de la UNECE.Cuando se genere una factura global:• No se debe registrar información en el elemento Parte.• Se debe registrar por cada comprobante de operaciones con público en general con serie y/o folio, un concepto con el valor “COP” en el atributo Unidad y en el atributo NoIdentificacion el número de serie y folio.• Para las operaciones con público en general sin comprobante, se debe registrar un concepto con el valor “COP” en el atributo Unidad, en la cantidad el número de operaciones, en el valor unitario el valor promedio de las operaciones y en los impuestos el valor sumarizado de impuestos.^O^string^20|129^ADD^UnidCom^Nº de unidades de consumo en unidad comercializada^O^N(12,3)^15|130^SAT^Desc^Atributo requerido para precisar la descripción del bien o servicio cubierto por el presente concepto.^R^string^1000|131^SAT^PrecMX^Atributo requerido para precisar el valor o precio unitario del bien o servicio cubierto por el presente concepto.^R^t_Importe^18|132^SAT^ImporMX^Atributo requerido para precisar el importe total de los bienes o servicios del presente concepto. Debe ser equivalente al resultado de multiplicar la cantidad por el valor unitario expresado en el concepto. No se permiten valores negativos.^R^t_Importe^18|133^SAT^DescuentoArt^Atributo opcional para representar el importe de los descuentos aplicables al concepto. No se permiten valores negativos^O^t_Importe^18|134^ADD^Precop^Precio neto unitario en divisa opcional^O^N^8|135^ADD^PrecBruto^^^^|136^ADD^ImporOp^Importe en divisa opcional^O^N^10|137^ADD^ImporBruto^Importe Bruto de la Línea de Articulo^O^N(15,2)^18|138^ADD^ImporNeto^Importe Neto de la Línea con Desc./Cargos con Impuestos^O^N(15,2)^18|139^ADD^LinOrdComp^Número de órden de compra^O^C^17|140^ADD^NivOrdComp^Nivel de órden de compra^O^C^17|141^ADD^TpNotaslin^Tipo Observaciones^O^C^35|142^ADD^Notaslin^Observaciones de Línea^O^C^35|143^ADD^TpoPrecio^Código Seriado de Unidad de Envío^O^C^35|144^ADD^PrecList^xxx^O^C^35|145^ADD^UlCodSeriado^Código Seriado de Unidad de Envío^O^C^35|146^ADD^UlSRV^Número global de unidades de  comercialización^O^C^35|147^ADD^IENumPalet^Numero de paquetes ^N^N(15)^15|148^ADD^IEDescPalet^Descripción del  empaquetado^N^C^70|149^ADD^IETipoPalet^Tipo de empaquetado^N^C^35|150^ADD^IEPagoTrans^Pago de transporte de embalaje^N^C^35|151^ADD^ILNumLote^Número de lote^N^C^35|152^ADD^ILFechaLote^Fecha de producción^N^C^10|153^ADD^NoReleased^Numero de Release^N^C^10|154^ADD^DtIndDC^Indicador Descuento o Cargo^~R^C^35|155^ADD^DtImputacion^Imputación Descuento/Cargo^~R^C^35|156^ADD^DtSecuencia^Indicador de secuencia de cálculo^N^N^3|157^ADD^DtTipo^Tipo Descuento/Cargo^O^C^3|158^ADD^DtPorcentaje^Porcentaje Descuento/Cargo^O^N(4,2)^7|159^ADD^DtImpUnidad^Importe Descuento/Cargo por unidad^O^N(15,2)^18|160^SAT^DescTipoImp^Atributo requerido para señalar la clave del tipo de impuesto trasladado aplicable al concepto.^R^c_Impuesto^2|161^SAT^BaseImp^Atributo requerido para señalar la base para el cálculo del impuesto, la determinación de la base se realiza de acuerdo con las disposiciones fiscales vigentes. No se permiten valores negativos.^R^t_Importe^N(12,6)|162^^CategImp^Identificador del nodo Traslado^R^^1|163^SAT^TipoFactor^Atributo requerido para señalar la clave del tipo de factor que se aplica a la base del impuesto.^R^c_TipoFactor^2|164^SAT^PorImp^Atributo condicional para señalar el valor de la tasa o cuota del impuesto que se traslada para el presente concepto. Es requerido cuando el atributo TipoFactor tenga un valor que corresponda a Tasa o Cuota.^C^c_TasaOCuota^1|165^SAT^ImporImp^Atributo condicional para señalar el importe del impuesto trasladado que aplica al concepto. No se permiten valores negativos. Es requerido cuando TipoFactor sea Tasa o Cuota^C^t_Importe^18|166^SAT^BaseImp^Atributo requerido para señalar la base para el cálculo del impuesto, la determinación de la base se realiza de acuerdo con las disposiciones fiscales vigentes. No se permiten valores negativos.^R^t_Importe^N(12,6)|167^SAT^DescTipoImp^Atributo requerido para señalar la clave del tipo de impuesto trasladado aplicable al concepto.^R^c_Impuesto^2|168^SAT^TipoFactor^Atributo requerido para señalar la clave del tipo de factor que se aplica a la base del impuesto.^R^c_TipoFactor^2|169^SAT^PorImp^Atributo condicional para señalar el valor de la tasa o cuota del impuesto que se traslada para el presente concepto. Es requerido cuando el atributo TipoFactor tenga un valor que corresponda a Tasa o Cuota.^R^decimal^1|170^^CategImp^Identificador del nodo Retenido^R^^1|171^SAT^ImporImp^Atributo condicional para señalar el importe del impuesto trasladado que aplica al concepto. No se permiten valores negativos. Es requerido cuando TipoFactor sea Tasa o Cuota^R^t_Importe^18|172^SAT^Pedimen^Atributo requerido para expresar el número del pedimento que ampara la importación del bien que se expresa en el siguiente formato: últimos 2 dígitos del año de validación seguidos por dos espacios, 2 dígitos de la aduana de despacho seguidos por dos espacios, 4 dígitos del número de la patente seguidos por dos espacios, 1 dígito que corresponde al último dígito del año en curso, salvo que se trate de un pedimento consolidado iniciado en el año inmediato anterior o del pedimento original de una rectificación, seguido de 6 dígitos de la numeración progresiva por aduana.^R^string^21|173^SAT^NumCPred^Atributo requerido para precisar el número de la cuenta predial del inmueble cubierto por el presente concepto, o bien para incorporar los datos de identificación del certificado de participación inmobiliaria no amortizable, tratándose de arrendamiento.^R^string^150|174^SAT^CampoDet0^Campo Detalle Específico (Clave)^N^C^4|175^SAT^CampoDet1^Campo Detalle Específico 1^N^C^35|176^SAT^PPClaveProdServ^Atributo requerido para expresar la clave del producto o del servicio amparado por el presente concepto. Es requerido y deben utilizar las claves del catálogo de productos y servicios, cuando los conceptos que registren por sus actividades correspondan con dichos conceptos^R^c_ClaveProdServ^|177^SAT^PPCodArtPro^Atributo opcional para expresar el número de parte, identificador del producto o del servicio, la clave de producto o servicio, SKU o equivalente, propia de la operación del emisor, amparado por el presente concepto. Opcionalmente se puede utilizar claves del estándar GTIN.^O^string^100|178^SAT^PPcant^Atributo requerido para precisar la cantidad de bienes o servicios del tipo particular definido por la presente parte^R^Valor mínimo incluyente 0.000001^N(12,6)|179^SAT^PPUM^Atributo opcional para precisar la unidad de medida propia de la operación del emisor, aplicable para la cantidad expresada en la parte. La unidad debe corresponder con la descripción de la parte.^O^string^20|180^SAT^PDesc^Atributo requerido para precisar la descripción del bien o servicio cubierto por la presente parte.^R^string^1000|181^SAT^PPrecMX^Atributo opcional para precisar el valor o precio unitario del bien o servicio cubierto por la presente parte. No se permiten valores negativos.^O^t_Importe^18|182^SAT^PPImporMx^Atributo opcional para precisar el importe total de los bienes o servicios de la presente parte. Debe ser equivalente al resultado de multiplicar la cantidad por el valor unitario expresado en la parte. No se permiten valores negativos.^^^|183^SAT^PPedimen^Atributo requerido para expresar el número del pedimento que ampara la importación del bien que se expresa en el siguiente formato: últimos 2 dígitos del año de validación seguidos por dos espacios, 2 dígitos de la aduana de despacho seguidos por dos espacios, 4 dígitos del número de la patente seguidos por dos espacios, 1 dígito que corresponde al último dígito del año en curso, salvo que se trate de un pedimento consolidado iniciado en el año inmediato anterior o del pedimento original de una rectificación, seguido de 6 dígitos de la numeración progresiva por aduana.^R^string^21|184^ADD^TpMonto^Tipo de Monto Especial^~R^C^18|185^ADD^PorMonto^Porcentaje del Monto especial^O^N(15,2)^18|186^ADD^Monto^Monto ó importe especial^~R^N(15,2)^18|187^ADD^IDMonto^Identificador del Monto especial^~R^C^18|188^ADD^ClsMonto^Clase del importe especial^O^C^18|189^SAT^TotImpT^Atributo condicional para expresar el total de los impuestos trasladados que se desprenden de los conceptos expresados en el comprobante fiscal digital por Internet. No se permiten valores negativos. Es requerido cuando en los conceptos se registren impuestos trasladados^C^t_Importe^18|190^ADD^TotImp^Importe Total de Impuestos^O^N(15,2)^18|191^SAT^TotNeto^Atributo requerido para representar la suma de los importes de los conceptos antes de descuentos e impuesto. No se permiten valoresnegativos^R^t_Importe^N(12,6)|192^ADD^TotBruto^Importe antes de Impuestos^R^N(15,2)^18|193^SAT^Importe^Atributo requerido para representar la suma del subtotal, menos los descuentos aplicables, más las contribuciones recibidas (impuestos trasladados - federales o locales, derechos, productos, aprovechamientos, aportaciones de seguridad social, contribuciones de mejoras) menos los impuestos retenidos. Si el valor es superior al límite que establezca el SAT en la Resolución Miscelánea Fiscal vigente, el emisor debe obtener del PAC que vaya a timbrar el CFDI, de manera no automática, una clave de confirmación para ratificar que el valor es correcto e integrar dicha clave en el atributo Confirmacion. No se permiten valores negativos.^R^t_Importe^N(12,6)|194^SAT^TipImpT^Atributo requerido para señalar la clave del tipo de impuesto trasladado.^R^c_Impuesto^4|195^SAT^TipFactT^Atributo requerido para señalar el tipo de factor que se aplica a la base del impuesto^R^c_TipoFactor^5|196^SAT^PorImpT^Atributo requerido para señalar el valor de la tasa o cuota del impuesto que se traslada por los conceptos amparados en el comprobante.^R^c_TasaOCuota^18|197^SAT^MonImpT^Atributo requerido para señalar la suma del importe del impuesto trasladado, agrupado por impuesto, TipoFactor y TasaOCuota. No se permiten valores negativos.^R^t_Importe^18|198^^Campo0^TERCERO^O^C^16|199^^Campo1^RFC del arrendador^R^C^35|200^^Campo2^razón social o nombre^O^C^35|201^^Campo3^calle^O^C^200|202^^Campo4^numero interior^O^C^35|203^^Campo5^numero exterior^O^C^35|204^^Campo6^colonia^O^C^35|205^^Campo7^localidad^N^C^35|206^^Campo8^referencia^N^C^35|207^^Campo9^delegacion o municipio^N^C^35|208^^Campo10^estado^N^C^35|209^^Campo11^pais^N^C^35|210^^Campo12^codigo postal^N^C^35|211^^Campo13^numero de pedimento aduanero^N^C^35|212^^Campo14^fecha de aduana^N^C^35|213^^Campo15^nombre de aduana^N^C^35|214^^Campo16^cuenta predial^N^C^35|215^^Campo17^^N^C^35|216^^Campo18^^N^C^35|217^^Campo19^^N^C^35|218^^Campo20^^N^C^35|219^^Campo21^^N^C^35|220^^Campo22^^N^C^35|221^^Campo23^^N^C^35|222^^Campo24^^N^C^35|223^^Campo25^^N^C^35|224^^Campo26^^N^C^35|225^^Campo27^^N^C^35|226^^Campo28^^N^C^35|227^^Campo29^^N^C^35|228^^Campo30^^N^C^35|229^^Campo31^^N^C^35|230^^Campo32^^N^C^35|231^^Campo33^^N^C^35|232^^Campo34^^N^C^35|233^^Campo35^^N^C^35|234^^Campo36^^N^C^35|235^^Campo37^^N^C^35|236^^TpMonto^TERCERO^~R^C^18|237^^PorMonto^tasa de impuesto^O^N(15,2)^18|238^^Monto^importe del impuesto^~R^N(15,2)^18|239^^IDMonto^TRAS ó RET^~R^C^18|240^^ClsMonto^clase de impuesto (IVA, ISR, IEPS)^O^C^18|241^^FechaMonto^^~R^C^19";

/*

 	var $listacfdiPago="1^SAT^CFD^^R^|
		2^SAT^Complemento^^R^|
		3^SAT^Version^Debe ser el valor 3.3^R^|
		4^SAT^Serie^^O^string|
		5^SAT^Folio^^O^string|
		6^SAT^Fecha^AAA-MM-DDThh:mm:ss^R^t_Fecha|
		7^SAT^FormaPago^Este campo no debe existir^^|
		8^SAT^CondPago^Este campo no debe existir^^|
		9^SAT^TotNeto^Valor por defecto 0. No ingresar en caso de ser traductor TXT.^R^t_Importe|
		10^SAT^Descuento^Este campo no debe existir^^|
		11^SAT^Moneda^Debe ser el valor XXX. No ingresar en caso de ser traductor TXT.^R^c_Moneda|
		12^SAT^TC^Este campo no debe existir^^|
		13^SAT^Importe^Valor por defecto 0. No ingresar en caso de ser traductor TXT.^R^t_Importe|
		14^SAT^EfectoCFD^Valor por defecto P.^R^c_TipoDeComprobante|
		15^SAT^MetPago^Este campo no debe existir^^|
		16^SAT^LugarExp^Debe ser un valor del catálogo c_CodigoPostal^R^c_CodigoPostal|
		17^SAT^Confirmacion^Se deben registrar valores alfanuméricos de 5 posiciones. Solicitar a soporte SERES la clave.^C^string|
		18^SAT^Nodo: CfdiRelacionados^^O^|
		19^SAT^Campo0^Valor Por defecto REL^R^|
		20^SAT^Campo1^Debe ser un valor del catálogo c_TipoRelacion^R^c_TipoRelacion|
		21^SAT^Nodo: CfdiRelacionado^^^|
		22^SAT^Campo2^En caso de ser más de un UUID relacionado debe ir separado por un coma (,)^R^string|
		23^SAT^Nodo: Emisor^^R^|
		24^SAT^ERFC^^R^t_RFC|
		25^SAT^ENombre^^O^string|
		26^SAT^RegFiscal^Debe ser un valor del catálogo c_RegimenFiscal^R^c_RegimenFiscal|
		27^SAT^Nodo: Receptor^^^|
		28^SAT^RRFC^El RFC debe estar contenido en la lista de RFC (l_RFC) inscritos no cancelados en el SAT en caso de que sea diferente del RFC genérico (Nacional - Extranjero).^R^t_RFC|
		29^SAT^RNombre^^O^string|
		30^SAT^RResFiscal^Cuando el receptor del comprobante sea un residente en el extranjero, se debe registrar la clave del país de residencia para efectos fiscales del receptor del comprobante.Este campo es obligatorio cuando se registre una clave en el RFC genérica extranjera.^C^c_Pais|
		31^SAT^RNumRegTrib^Se captura el número de registro de identidad fiscal del receptor del comprobante fiscal cuando éste sea residente en el extranjero.* Puede conformarse desde 1 hasta 40 caracteres.* Si no existe el campo ResidenciaFiscal, este campo puede no existir.* La residencia fiscal debe corresponder con el valor especificado en la columna Formato de Registro de Identidad Tributaria del catálogo c_Pais.^C^c_Pais|
		32^SAT^RUsoCfdi^Valor por defecto P01. No ingresar en caso de ser traductor TXT^R^c_UsoCFDI|
		33^SAT^Nodo: Conceptos^^R^|
		34^SAT^Nodo: Concepto^Solo debe existir un solo nodo/concepto^R^|
		35^SAT^NumLin^Valor predeterminado 1^R^|
		36^SAT^ClaveProdServ^Valor por defecto 84111506. No ingresar en caso de ser traductor TXT^R^c_ClaveProdServ|
		37^SAT^CodArtPro^Este campo no debe existir^^|
		38^SAT^Cant^Valor por defecto 1. No Ingresar en caso de ser traductor TXT.^R^decimal|
		39^SAT^ClaveUnidad^Valor por defecto ACT. No ingresar en caso de ser traductor TXT.^R^c_ClaveUnidad|
		40^SAT^UM^Este campo no debe existir^^|
		41^SAT^Desc^Valor por defecto Pago. No ingresar en caso de ser traductor TXT.^R^string|
		42^SAT^PrecMx^Valor por defecto 0. No ingresar en caso de ser traductor TXT.^R^t_Importe|
		43^SAT^ImporMx^Valor por defecto 0. No ingresar en caso de ser traductor TXT.^R^t_Importe|
		44^SAT^DescuentoArt^Este campo no debe existir^^|
		45^SAT^Nodo: Impuestos^^^|
		46^SAT^Nodo: InformacionAduanera^^^|
		47^SAT^Nodo: CuentaPredial^^^|
		48^SAT^Nodo: ComplementoConcepto^^^|
		49^SAT^Nodo: Parte^^^|
		50^SAT^Nodo: Impuestos^^^|
		51^SAT^Complemento de Pagos^^^|
		52^SAT^Pagos^El nodo Pagos se debe registrar como un nodo hijo del nodo Complemento en el CFDI.	En el CFDI solo debe existir un nodo de Pagos.Si el atributo CFDI:versión tiene valor 3.3 entonces:	->Si el tipo de comprobante es un traslado o T no debe existir este complemento.->Si el tipo de comprobante es ingreso, I, E o egreso el complemento para recepción de pagos puede coexistir con todos los complementos de CFDI excepto con los complementos SPEI de tercero a tercero y Nomina.Si el atributo CFDI:versión tiene valor 3.3 entonces:Si el tipo de comprobante es {P} el complemento para recepción de pagos puede coexistir con los complementos Timbre fiscal digital y CFDI Registro fiscal. Si el complemento para recepción de pagos tiene el elemento impuestos, entonces también puede coexistir con el complemento de otros derechos e impuestos.^R^|
		53^SAT^CmPgVersion^Valor prefijado 1.0^R^string|
		54^SAT^CmPgPago^se debe configurar un número como NumLin pues uede haber N ocurrencias:Pago 1…Pago2…PagoN^R^1…N|
		55^SAT^CmPgFechaPago^Debe ser menor o igual al atributo CFDI:Fecha Si la FechaPago es menor que CFDI:Fecha entonces: ● El valor año-mes de Pago:FechaPago debe ser igual al valor año-mes de CFDI:Fecha, o ● El valor año-mes de Pago:FechaPago debe ser igual al valor año-mes de CFDI:Fecha menos un mes, y el día del atributo CFDI:Fecha debe ser menor o igual a 0.^R^t_FechaH|
		56^SAT^CmPgFormaDePago^El valor registrado debe ser diferente de 99.Con base en el valor registrado en este campo, se debe verificar si los campos definidos en el catálogo son opcionales, obligatorios o no se deben registrar; si el catálogo tiene un patrón para el campo se debe verificar que éste se cumpla, si el campo tiene una regla para obligar el registro del campo se debe evaluar la regla para determinar si es obligatorio, opcional o no se incluye^R^c_FormaPago|
		57^SAT^CmPgDivisOp^Si es diferente de MXN o XXX, debe existir información en el atributo TipoCambioP.Si es MXN o XXX, no debe existir información en el atributo TipoCambioP.Tomar del catálogo de monedas la cantidad de decimales que acepta la divisa y el importe del campo Pagos:Pago:Monto y los atributos TotalImpuestosRetenidos, TotalImpuestosTraslados, raslados:Traslado:Importe y Retenciones:Retencion:Importe del nodo Pago:Impuestos, deben ser registrados hasta esa cantidad de decimales (cero y hasta cuatro decimales).^R^c_Moneda|
		58^SAT^CmPgTC^Si el atributo CFDI:versión tiene valor 3.3 entonces:El tipo de cambio debe tener un valor que se encuentre entre el valor publicado para la fecha de la operación más el límite superior y el valor publicado para la fecha de la operación menos el límite inferior.El SAT publica los límites en la Resolución Miscelánea Fiscal - Obligaciones de los proveedores en el proceso de certificación de CFDI.Cuando el valor de este atributo se encuentre fuera de los límites establecidos, el emisor debe obtener de manera no automática una clave de confirmación para ratificar que el valor es correcto e integrarla al CFDI en el atributo CFDI:Confirmacion. La clave de confirmación la asigna el PAC.^C^decimal|
		59^SAT^CmPgMonto^Que la suma de los valores registrados en el nodo DoctoRelacionado, atributo ImpPagado, sea menor o igual que el valor de este atributo.Se debe considerar la conversión a la moneda del pago registrada en el atributo MonedaP y el margen de variación por efecto de redondeo.Debe ser mayor a 0.Considerar que para el monto, se debe registrar el número de decimales de acuerdo al tipo de moneda expresado en el atributo MonedaP, esto de acuerdo con la publicación del catálogo que se encuentra en la página de internet del SAT, en su caso, las cantidades deben ser redondeadas para cumplir con el número de decimales establecidos.Si el atributo CFDI:Version tiene valor 3.3 entonces:El SAT publica el límite para el valor máximo de este atributo en la Resolución Miscelánea Fiscal - Obligaciones de los proveedores en el proceso de certificación de CFDI.Cuando el valor equivalente en MXN de este atributo exceda el límite establecido, el emisor debe obtener de manera no automática una clave de confirmación para ratificar que el importe es correcto e integrarla al CFDI en el atributo CFDI:Confirmacion. La clave de confirmación la asigna el PAC.^R^t_Importe|
		60^SAT^CmPgNumOperacion^Patrón:[^|]{1,100}^C^string|
		61^SAT^CmPgERfcCtaOrd^Patrón:[XEXX010101000]|[A-Z&amp;Ñ]{3}[0-9]{2}(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[01])[A-Z0-9]{2}[0-9A]Si el atributo CFDI:Version tiene valor 3.3 entonces:Cuando no se utilice el RFC genérico XEXX010101000, el RFC debe estar en la lista de RFC inscritos en el SAT^C^string|
		62^SAT^CmPgBancoOrdExt^Patrón:([A-Z]|[a-z]|[0-9]| |Ñ|ñ|!|&quot;|%|&amp;|&apos;|´|-|:|;|&gt;|=|&lt;|@|_|,|\{|\}|`|~|á|é|í|ó|ú|Á|É|Í|Ó|Ú|ü|Ü){1,300}^C^string|
		63^SAT^CmPgCtaOrd^Patrón[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}^C^string|
		64^SAT^CmPgERfcCtaBen^^C^t_RFC_PM|
		65^SAT^CmPgCtaBen^Patrón:[0-9]{10,11}|[0-9]{15,16}|[0-9]{18}|[A-Z0-9_]{10,50}^C^string|
		66^SAT^CmPgTipoCadPago^^C^c_TipoCadenaPago|
		67^SAT^CmPgCertPago^^C^base64Binary|
		68^SAT^CmPgCadPago^Patrón([A-Z]|[a-z]|[0-9]| |Ñ|ñ|!|&quot;|%|&amp;|&apos;|´|-|:|;|&gt;|=|&lt;|@|_|,|\{|\}|`|~|á|é|í|ó|ú|Á|É|Í|Ó|Ú|ü|Ü){1,8192}^C^string|
		69^SAT^CmPgSelloPago^^O^base64Binary|
		70^SAT^CmPgDctoRel^se debe configurar un número como NumLin pues uede haber N ocurrencias:Documento 1…Documento 2…Documento N^C^1…N|
		71^SAT^CmPgIdDoc^Patrón:([a-f0-9A-F]{8}-[a-f0-9A-F]{4}-[a-f0-9A-F]{4}-[a-f0-9A-F]{4}-[a-f0-9A-F]{12})|([0-9]{3}-[0-9]{2}-[0-9]{9})^R^string|
		72^SAT^CmPgSerieP^Patrón:([A-Z]|[a-z]|[0-9]| |Ñ|ñ|!|&quot;|%|&amp;|&apos;|´|-|:|;|&gt;|=|&lt;|@|_|,|\{|\}|`|~|á|é|í|ó|ú|Á|É|Í|Ó|Ú|ü|Ü){1,25}^O^string|
		73^SAT^CmPgFolioP^Patrón:([A-Z]|[a-z]|[0-9]| |Ñ|ñ|!|&quot;|%|&amp;|&apos;|´|-|:|;|&gt;|=|&lt;|@|_|,|\{|\}|`|~|á|é|í|ó|ú|Á|É|Í|Ó|Ú|ü|Ü){1,40}^O^string|
		74^SAT^CmPgDivisOpDR^No debe contener el valor “XXX”Si el valor de este atributo es diferente al valor registrado en el atributo MonedaP, se debe registrar información en el atributo TipoCambioDR, en otro caso, no se debe registrar un valor en el atributo TipoCambioDR.Considerar que para los importes registrados en los atributos “ImpSaldoAnt”,“ImpPagado” e “ImpSaldoInsoluto” de éste nodo, deben corresponder a esta moneda y ser redondeados hasta la cantidad de decimales que soporte^R^c_Moneda|
		75^SAT^CmPgTCDR^Si el atributo CFDI:versión tiene valor 3.3 entonces:El tipo de cambio debe tener un valor que se encuentre entre el valor publicado para la fecha de la operación más el límite superior y el valor publicado para la fecha de la operación menos el límite inferior.	El SAT publica los límites en la Resolución Miscelánea Fiscal - Obligaciones de los proveedores en el proceso de certificación de CFDI.		Cuando el valor de este atributo se encuentre fuera de los límites establecidos, el emisor debe obtener de manera no automática una clave de confirmación para ratificar que el valor es correcto e integrarla al CFDI en el atributo CFDI:Confirmacion. La clave de confirmación la asigna el PAC.^C^4,6|
		76^SAT^CmPgMetPagoDR^Si el valor de este campo es “Pago en parcialidades o diferido” o “Pago inicial y parcialidades” se deben registrar los atributos “NumParcialidad”, “ImpSaldoAnt” e “ImpSaldoInsoluto”.^R^c_MetodoPago|
		77^SAT^CmPgNumParcialidad^Patrón:[1-9][0-9]{0,2}^C^int|
		78^SAT^CmPgImpSaldoAnt^Debe ser mayor a 0^C^t_Importe|
		79^SAT^CmPgImpPagado^Si existe más de un documento relacionado o existe un documento relacionado y el TipoCambioDR tiene un valor es obligatorio este atributo.
		Si existe solo un documento relacionado es opcional.Debe ser mayor a 0 Considerar que este importe debe corresponder al tipo de moneda registrado en el atributo: MonedaDR del documento relacionado.^O^t_Importe|
		80^SAT^CmPgImpSaldoInsoluto^Debe ser mayor o igual a 0 y debe calcularse de los atributos: ImpSaldoAnt menos el ImpPagado, si el atributo ImpPagado no existe, usar el atributo Monto considerando la conversión a MonedaDR.Considerar que este importe debe corresponder al tipo de moneda registrado en el atributo: MonedaDR del documento relacionado.^O^t_Importe|
		81^SAT^Campos para el complemento de pagos versión 1.1^^^|
		82^SAT^CmPgImpuestos^Debe existir al menos un elemento hijo con una retención o traslado^C^1…N|
		83^SAT^CmPgTotImpR^El valor de este atributo debe tener hasta la cantidad de decimales que soporte la moneda especificada en MonedaP.El valor de este atributo debe ser igual a la suma de los importes registrados en el elemento hijo Retenciones.Si no existen elementos hijo del nodo Retenciones, este atributo no debe existir^C^t_Importe|
		84^SAT^CmPgTotImpT^El valor de este atributo debe tener hasta la cantidad de decimales que soporte la moneda especificada en MonedaP.El valor de este atributo debe ser igual a la suma de los importes registrados en el elemento hijo Traslados.Si no existen elementos hijo del nodo Traslados, este atributo no debe existir^C^t_Importe|
		85^SAT^^^^|
		86^SAT^Retenciones^^C^|
		87^SAT^CmPgRetencion^^R^1…N|
		88^SAT^CmPgTipImpR^Debe haber sólo un registro por cada tipo de impuesto retenido^R^c_Impuesto|
		89^SAT^CmPgMonImpR^Debe existir el atributo TotalImpuestosRetenidos El valor de este atributo debe tener hasta la cantidad de decimales que soporte la moneda especificada en MonedaP^R^t_Importe|
		90^SAT^^^^|
		91^SAT^Traslados^^C^|
		92^SAT^CmPgTraslado^^C^1…N|
		93^SAT^CmPgTipImpT^Debe haber sólo un registro con la misma combinación de impuesto, factor y tasa por cada traslado^R^c_Impuesto|
		94^SAT^CmPgTipoFactor^^R^c_TipoFactor|
		95^SAT^CmPgPorImpT^El valor seleccionado debe corresponder con un registro del catálogo catCFDI:c_TasaOCuota donde la columna impuesto corresponda con el atributo impuesto y la columna factor corresponda con el atributo TipoFactor^R^c_TasaOCuota|
		96^SAT^CmPgMonImpT^Debe existir el atributo TotalImpuestosTrasladados El valor de este atributo debe tener hasta la cantidad de decimales que soporte la moneda registrada en el atributo MonedaP.^R^t_Importe";

*/

 	var $listacfdiPago="No^USO^Campos de archivo origen Layout e-Factura^Descripción^Opc/Req^Tipo/Ocurrencias|1^SAT^CFD^^R^|2^SAT^EfectoCFD^Valor por defecto P.^R^c_TipoDeComprobante|3^SAT^Version^Debe ser el valor 3.3^R^|4^SAT^Serie^^O^string|5^SAT^Folio^^O^string|6^SAT^Fecha^AAA-MM-DDThh:mm:ss^R^t_Fecha|7^SAT^Complemento^^R^|8^SAT^CondPago^Este campo no debe existir^^|9^SAT^TotNeto^Valor por defecto 0. No ingresar en caso de ser traductor TXT.^R^t_Importe|10^SAT^Descuento^Este campo no debe existir^^|11^SAT^Moneda^Debe ser el valor XXX. No ingresar en caso de ser traductor TXT.^R^c_Moneda|12^SAT^TC^Este campo no debe existir^^|13^SAT^Importe^Valor por defecto 0. No ingresar en caso de ser traductor TXT.^R^t_Importe|14^SAT^EfectoCFD^Valor por defecto P.^R^c_TipoDeComprobante|15^SAT^MetPago^Este campo no debe existir^^|16^SAT^LugarExp^Debe ser un valor del catálogo c_CodigoPostal^R^c_CodigoPostal|17^SAT^Confirmacion^Se deben registrar valores alfanuméricos de 5 posiciones. Solicitar a soporte SERES la clave.^C^string|18^SAT^Nodo: CfdiRelacionados^^O^|19^SAT^Campo0^Valor Por defecto REL^R^|20^SAT^Campo1^Debe ser un valor del catálogo c_TipoRelacion^R^c_TipoRelacion|21^SAT^Nodo: CfdiRelacionado^^^|22^SAT^Campo2^En caso de ser más de un UUID relacionado debe ir separado por un coma (,)^R^string|23^SAT^Nodo: Emisor^^R^|24^SAT^ERFC^^R^t_RFC|25^SAT^RegFiscal^Debe ser un valor del catálogo c_RegimenFiscal^R^c_RegimenFiscal|26^SAT^ENombre^^O^string|27^SAT^Nodo: Receptor^^^|28^SAT^RRFC^El RFC debe estar contenido en la lista de RFC inscritos no cancelados en el SAT en caso de que sea diferente del RFC genérico (Nacional Extranjero).^R^t_RFC|29^SAT^RNombre^^O^string|30^SAT^RResFiscal^Cuando el receptor del comprobante sea un residente en el extranjero, se debe registrar la clave del país de residencia para efectos fiscales del receptor del comprobante.Este campo es obligatorio cuando se registre una clave en el RFC genérica extranjera.^C^c_Pais|31^SAT^RNumRegTrib^Se captura el número de registro de identidad fiscal del receptor del comprobante fiscal cuando éste sea residente en el extranjero.* Puede conformarse desde 1 hasta 40 caracteres.* Si no existe el campo ResidenciaFiscal, este campo puede no existir.* La residencia fiscal debe corresponder con el valor especificado en la columna Formato de Registro de Identidad Tributaria del catálogo c_Pais.^C^c_Pais|32^SAT^RUsoCfdi^Valor por defecto P01. No ingresar en caso de ser traductor TXT^R^c_UsoCFDI|33^SAT^Nodo: Conceptos^^R^|34^SAT^Nodo: Concepto^Solo debe existir un solo nodo/concepto^R^|35^SAT^NumLin^Valor predeterminado 1^R^|36^SAT^ClaveProdServ^Valor por defecto 84111506. No ingresar en caso de ser traductor TXT^R^c_ClaveProdServ|37^SAT^CodArtPro^Este campo no debe existir^^|38^SAT^Cant^Valor por defecto 1. No Ingresar en caso de ser traductor TXT.^R^decimal|39^SAT^ClaveUnidad^Valor por defecto ACT. No ingresar en caso de ser traductor TXT.^R^c_ClaveUnidad|40^SAT^UM^Este campo no debe existir^^|41^SAT^Desc^Valor por defecto Pago. No ingresar en caso de ser traductor TXT.^R^string|42^SAT^PrecMx^Valor por defecto 0. No ingresar en caso de ser traductor TXT.^R^t_Importe|43^SAT^ImporMx^Valor por defecto 0. No ingresar en caso de ser traductor TXT.^R^t_Importe|44^SAT^DescuentoArt^Este campo no debe existir^^|45^SAT^Nodo: Impuestos^^^|46^SAT^Nodo: InformacionAduanera^^^|47^SAT^Nodo: CuentaPredial^^^|48^SAT^Nodo: ComplementoConcepto^^^|49^SAT^Nodo: Parte^^^|50^SAT^Nodo: Impuestos^^^|51^SAT^Complemento de Pagos^^^|52^SAT^Pagos^El nodo Pagos se debe registrar como un nodo hijo del nodo Complemento en el CFDI.	En el CFDI solo debe existir un nodo de Pagos.Si el atributo CFDI:versión tiene valor 3.3 entonces:	->Si el tipo de comprobante es un traslado o T no debe existir este complemento.->Si el tipo de comprobante es ingreso, I, E o egreso el complemento para recepción de pagos puede coexistir con todos los complementos de CFDI excepto con los complementos SPEI de tercero a tercero y Nomina.Si el atributo CFDI:versión tiene valor 3.3 entonces:Si el tipo de comprobante es {P} el complemento para recepción de pagos puede coexistir con los complementos Timbre fiscal digital y CFDI Registro fiscal. Si el complemento para recepción de pagos tiene el elemento impuestos, entonces también puede coexistir con el complemento de otros derechos e impuestos.^R^|53^SAT^CmPgVersion^Valor prefijado 1.0^R^string|54^SAT^CmPgPago^se debe configurar un número como NumLin pues uede haber N ocurrencias:Pago 1…Pago2…PagoN^R^1…N|55^SAT^CmPgFechaPago^Debe ser menor o igual al atributo CFDI:Fecha Si la FechaPago es menor que CFDI:Fecha entonces: ● El valor año-mes de Pago:FechaPago debe ser igual al valor año-mes de CFDI:Fecha, o ● El valor año-mes de Pago:FechaPago debe ser igual al valor año-mes de CFDI:Fecha menos un mes, y el día del atributo CFDI:Fecha debe ser menor o igual a 0.^R^t_FechaH|56^SAT^CmPgFormaDePago^El valor registrado debe ser diferente de 99.Con base en el valor registrado en este campo, se debe verificar si los campos definidos en el catálogo son opcionales, obligatorios o no se deben registrar; si el catálogo tiene un patrón para el campo se debe verificar que éste se cumpla, si el campo tiene una regla para obligar el registro del campo se debe evaluar la regla para determinar si es obligatorio, opcional o no se incluye^R^c_FormaPago|57^SAT^CmPgDivisOp^Si es diferente de MXN o XXX, debe existir información en el atributo TipoCambioP.Si es MXN o XXX, no debe existir información en el atributo TipoCambioP.Tomar del catálogo de monedas la cantidad de decimales que acepta la divisa y el importe del campo Pagos:Pago:Monto y los atributos TotalImpuestosRetenidos, TotalImpuestosTraslados, raslados:Traslado:Importe y Retenciones:Retencion:Importe del nodo Pago:Impuestos, deben ser registrados hasta esa cantidad de decimales (cero y hasta cuatro decimales).^R^c_Moneda|58^SAT^CmPgTC^Si el atributo CFDI:versión tiene valor 3.3 entonces:El tipo de cambio debe tener un valor que se encuentre entre el valor publicado para la fecha de la operación más el límite superior y el valor publicado para la fecha de la operación menos el límite inferior.El SAT publica los límites en la Resolución Miscelánea Fiscal - Obligaciones de los proveedores en el proceso de certificación de CFDI.Cuando el valor de este atributo se encuentre fuera de los límites establecidos, el emisor debe obtener de manera no automática una clave de confirmación para ratificar que el valor es correcto e integrarla al CFDI en el atributo CFDI:Confirmacion. La clave de confirmación la asigna el PAC.^C^decimal|59^SAT^CmPgMonto^Que la suma de los valores registrados en el nodo DoctoRelacionado, atributo ImpPagado, sea menor o igual que el valor de este atributo.Se debe considerar la conversión a la moneda del pago registrada en el atributo MonedaP y el margen de variación por efecto de redondeo.Debe ser mayor a 0.Considerar que para el monto, se debe registrar el número de decimales de acuerdo al tipo de moneda expresado en el atributo MonedaP, esto de acuerdo con la publicación del catálogo que se encuentra en la página de internet del SAT, en su caso, las cantidades deben ser redondeadas para cumplir con el número de decimales establecidos.Si el atributo CFDI:Version tiene valor 3.3 entonces:El SAT publica el límite para el valor máximo de este atributo en la Resolución Miscelánea Fiscal - Obligaciones de los proveedores en el proceso de certificación de CFDI.Cuando el valor equivalente en MXN de este atributo exceda el límite establecido, el emisor debe obtener de manera no automática una clave de confirmación para ratificar que el importe es correcto e integrarla al CFDI en el atributo CFDI:Confirmacion. La clave de confirmación la asigna el PAC.^R^t_Importe|60^SAT^CmPgNumOperacion^Patrón:[^]{1,100}^C^string|61^SAT^CmPgERfcCtaOrd^Patrón:[XEXX010101000] [A-Z&amp;Ñ]{3}[0-9]{2}(0[1-9] 1[012])(0[1-9] [12][0-9] 3[01])[A-Z0-9]{2}[0-9A]Si el atributo CFDI:Version tiene valor 3.3 entonces:Cuando no se utilice el RFC genérico XEXX010101000, el RFC debe estar en la lista de RFC inscritos en el SAT^C^string|62^SAT^CmPgBancoOrdExt^Patrón:([A-Z][a-z][0-9] Ññ!&quot;%&amp;&apos;´-:;&gt;=&lt;@_,\{\}`~áéíóúÁÉÍÓÚüÜ){1,300}^C^string|63^SAT^CmPgCtaOrd^Patrón[0-9]{10,11}[0-9]{15,16}[0-9]{18}[A-Z0-9_]{10,50}^C^string|64^SAT^CmPgERfcCtaBen^^C^t_RFC_PM|65^SAT^CmPgCtaBen^Patrón:[0-9]{10,11}[0-9]{15,16}[0-9]{18}[A-Z0-9_]{10,50}^C^string|66^SAT^CmPgTipoCadPago^^C^c_TipoCadenaPago|67^SAT^CmPgCertPago^^C^base64Binary|68^SAT^CmPgCadPago^Patrón([A-Z][a-z][0-9] Ññ!&quot;%&amp;&apos;´-:;&gt;=&lt;@_,\{\}`~áéíóúÁÉÍÓÚüÜ){1,8192}^C^string|69^SAT^CmPgSelloPago^^O^base64Binary|70^SAT^CmPgDctoRel^se debe configurar un número como NumLin pues uede haber N ocurrencias:Documento 1…Documento 2…Documento N^C^1…N|71^SAT^CmPgIdDoc^Patrón:([a-f0-9A-F]{8}-[a-f0-9A-F]{4}-[a-f0-9A-F]{4}-[a-f0-9A-F]{4}-[a-f0-9A-F]{12})([0-9]{3}-[0-9]{2}-[0-9]{9})^R^string|72^SAT^CmPgSerieP^Patrón:([A-Z][a-z][0-9] Ññ!&quot;%&amp;&apos;´-:;&gt;=&lt;@_,\{\}`~áéíóúÁÉÍÓÚüÜ){1,25}^O^string|73^SAT^CmPgFolioP^Patrón:([A-Z][a-z][0-9] Ññ!&quot;%&amp;&apos;´-:;&gt;=&lt;@_,\{\}`~áéíóúÁÉÍÓÚüÜ){1,40}^O^string|74^SAT^CmPgDivisOpDR^No debe contener el valor “XXX”Si el valor de este atributo es diferente al valor registrado en el atributo MonedaP, se debe registrar información en el atributo TipoCambioDR, en otro caso, no se debe registrar un valor en el atributo TipoCambioDR.Considerar que para los importes registrados en los atributos “ImpSaldoAnt”,“ImpPagado” e “ImpSaldoInsoluto” de éste nodo, deben corresponder a esta moneda y ser redondeados hasta la cantidad de decimales que soporte^R^c_Moneda|75^SAT^CmPgTCDR^Si el atributo CFDI:versión tiene valor 3.3 entonces:El tipo de cambio debe tener un valor que se encuentre entre el valor publicado para la fecha de la operación más el límite superior y el valor publicado para la fecha de la operación menos el límite inferior.	El SAT publica los límites en la Resolución Miscelánea Fiscal - Obligaciones de los proveedores en el proceso de certificación de CFDI.		Cuando el valor de este atributo se encuentre fuera de los límites establecidos, el emisor debe obtener de manera no automática una clave de confirmación para ratificar que el valor es correcto e integrarla al CFDI en el atributo CFDI:Confirmacion. La clave de confirmación la asigna el PAC.^C^4,6|76^SAT^CmPgMetPagoDR^Si el valor de este campo es “Pago en parcialidades o diferido” o “Pago inicial y parcialidades” se deben registrar los atributos “NumParcialidad”, “ImpSaldoAnt” e “ImpSaldoInsoluto”.^R^c_MetodoPago|77^SAT^CmPgNumParcialidad^Patrón:[1-9][0-9]{0,2}^C^int|78^SAT^CmPgImpSaldoAnt^Debe ser mayor a 0^C^t_Importe|79^SAT^CmPgImpPagado^Si existe más de un documento relacionado o existe un documento relacionado y el TipoCambioDR tiene un valor es obligatorio este atributo.Si existe solo un documento relacionado es opcional.Debe ser mayor a 0 Considerar que este importe debe corresponder al tipo de moneda registrado en el atributo: MonedaDR del documento relacionado.^O^t_Importe|80^SAT^CmPgImpSaldoInsoluto^Debe ser mayor o igual a 0 y debe calcularse de los atributos: ImpSaldoAnt menos el ImpPagado, si el atributo ImpPagado no existe, usar el atributo Monto considerando la conversión a MonedaDR.Considerar que este importe debe corresponder al tipo de moneda registrado en el atributo: MonedaDR del documento relacionado.^O^t_Importe|81^SAT^Campos para el complemento de pagos versión 1.1^^^|82^SAT^CmPgImpuestos^Debe existir al menos un elemento hijo con una retención o traslado^C^1…N|83^SAT^CmPgTotImpR^El valor de este atributo debe tener hasta la cantidad de decimales que soporte la moneda especificada en MonedaP.El valor de este atributo debe ser igual a la suma de los importes registrados en el elemento hijo Retenciones.Si no existen elementos hijo del nodo Retenciones, este atributo no debe existir^C^t_Importe|84^SAT^CmPgTotImpT^El valor de este atributo debe tener hasta la cantidad de decimales que soporte la moneda especificada en MonedaP.El valor de este atributo debe ser igual a la suma de los importes registrados en el elemento hijo Traslados.Si no existen elementos hijo del nodo Traslados, este atributo no debe existir^C^t_Importe|85^SAT^^^^|86^SAT^Retenciones^^C^|87^SAT^CmPgRetencion^^R^1…N|88^SAT^CmPgTipImpR^Debe haber sólo un registro por cada tipo de impuesto retenido^R^c_Impuesto|89^SAT^CmPgMonImpR^Debe existir el atributo TotalImpuestosRetenidos El valor de este atributo debe tener hasta la cantidad de decimales que soporte la moneda especificada en MonedaP^R^t_Importe|90^SAT^^^^|91^SAT^Traslados^^C^|92^SAT^CmPgTraslado^^C^1…N|93^SAT^CmPgTipImpT^Debe haber sólo un registro con la misma combinación de impuesto, factor y tasa por cada traslado^R^c_Impuesto|94^SAT^CmPgTipoFactor^^R^c_TipoFactor|95^SAT^CmPgPorImpT^El valor seleccionado debe corresponder con un registro del catálogo catCFDI:c_TasaOCuota donde la columna impuesto corresponda con el atributo impuesto y la columna factor corresponda con el atributo TipoFactor^R^c_TasaOCuota|96^SAT^CmPgMonImpT^Debe existir el atributo TotalImpuestosTrasladados El valor de este atributo debe tener hasta la cantidad de decimales que soporte la moneda registrada en el atributo MonedaP.^R^t_Importe";

	
	var $emisor;
	var $receptor;
	var $concepto;
	var $tercero;
	var $impuesto;
	var $retencines;
	var $tipocfd;//ingreso, egreso, traslado
	
	var $lista;
	
	function cfdi32class()
	{
		$this->generalista($this->listacfdi);
	}
	
	function pagocfdi32class()
	{
		$this->lista=array();
		$this->generalista($this->listacfdiPago);
	}
	
	function generaLista($listah)
	{
		//echo $listah;
		$lst=split('[|]',$listah);
		//$l[0]="";

		foreach ($lst as $id => $registro)
		{

			$reg=split('\^',$registro);
			//$l[1]=count($reg);
			foreach ($reg as $idr => $campo)
			{
					$this->lista[$id][$idr]=$campo;
			}
		}
	}//fin de metodo generalista	


	function muestradatos($cat)
	{
		echo $cat;
		$resultado =  "<table border = \"1\">\n";
		foreach($cat as $idl => $aux1)
		{
		    ///inicio fila
		   
		    $resultado .=  "\t<tr> \n";
		    foreach($aux1 as $idr=>$valor)
		    {
		      ///dibujar celda
		      $resultado .= "\t\t<td>$valor &nbsp;</td>\n";
		    }
		    $resultado .= "\t</tr>\n";
		    //fin de la fila
		}
		$resultado .= "</table><br><br>\n";

		return $resultado;
	}

//funcion para mostrar datos de facttura
	function cadena_factura($id,$cat,$row,$folio,$serie,$terceros,$int,$nc)
	{
		//$id = id del historial
		//$cat = catalogo de la lista requerida por el PAC
		//$row = recordset de la consulta de la base de datos
		//$folio = folio de la factura
		//$serie = serie de la factura
		//$terceros = boleano para saber si es de terceros
		//$int = para ver si es interes
		//$nt = si es de nota de credito
		
		$notanc="";
		
		
		//obtengo los importes reales para facturar
		if($row["tipofactura"]=='PPD' && $row["aplicado"]==0){
			$sqlcal = "SELECT idcontrato, SUM(cantidad + iva) as totalp, SUM(iva) as ivap FROM historia WHERE idhistoria=$id GROUP BY idcontrato";
		}else{
			$sqlcal = "select idcontrato, SUM(cantidad) as totalp, SUM(iva) as ivap from historia where parcialde = " . $row['parcialde'] . " group by idcontrato";
		}
		$operaciontcal = mysql_query($sqlcal);
		$rcal =mysql_fetch_array($operaciontcal);
		
		$cantidadp = $rcal["totalp"];
		$ivap = $rcal["ivap"];		
		
		if($nc>0)
		{
			$aux = $ivap / ($cantidadp - $ivap) ;

			$sqlcal = "select idcontrato,notanc, SUM(cantidad) as totalp, SUM(iva) as ivap from historia where notacredito = 1 and idhistoria = $nc and parcialde = " . $row['parcialde'] . " group by idcontrato,notanc";
			$operaciontcal = mysql_query($sqlcal);
			$rcal =mysql_fetch_array($operaciontcal);			 
			
			$cantidadp = $rcal["totalp"];
			$ivap = $cantidadp - ($cantidadp / (1+$aux)) ;
			$notanc=$rcal["notanc"];
			
		}

		

		
		$resultado = "";
		//Cabecera de factura

		//echo $rfci= $row["rfc"];
		$Tercerosbloques="";
		if($terceros==1){
			//terceros
			$sqlt = "select * from duenioinmueble di, contrato c, inmueble i, duenio d where di.idduenio = d.idduenio and di.idinmueble=i.idinmueble and c.idinmueble = i.idinmueble and c.idcontrato = " . $row["idc"];
			$operaciont = mysql_query($sqlt);
			//$Tercerosbloques="";
			
			while($rowt = mysql_fetch_array($operaciont))
			{

				$ivav=0;
				foreach($this->lista as $idl => $aux1)
				{

					switch($aux1[0])
					{
					case 198: //Campo0	
						$Tercerosbloques .= $aux1[2] . "\t" . $aux1[3] . "\n";
						break;
					case 199: //campo1 (RFC Arrendador, dueño)	
						$rf =$rowt["rfcd"];
						if (strlen($rf)<12)
						{
							$rf="XAXX010101000";
						}
						
						$Tercerosbloques .= $aux1[2] . "\t" . $this->limpiar($rf) . "\n";
						break;
					case 200: //campo2	(nombre arrendador)	
						$Tercerosbloques .= $aux1[2] . "\t" . $this->limpiar($rowt["nombre"]) . " " . $this->limpiar($rowt["nombre2"]) . " " . $this->limpiar($rowt["apaterno"]) . " " . $this->limpiar($rowt["amaterno"]) . "\n";
						break;
					case 201: //campo3 (calle)
						$Tercerosbloques .= $aux1[2] . "\t" . $this->limpiar($rowt["called"]) . "\n";
						break;
					case 202:  //campo4 (numero exterior)	
						$Tercerosbloques .= $aux1[2] . "\t" . $this->limpiar($rowt["noexteriord"]) . "\n";
						break;

					case 203: //campo 5 (numero interior)	
						$Tercerosbloques .= $aux1[2] . "\t" . $this->limpiar($rowt["nointeriord"]) . "\n";
						break;
					case 204://campo 6 (colonia)	
						$Tercerosbloques .= $aux1[2] . "\t" . $this->limpiar($rowt["coloniad"]) . "\n";
						break;
					case 205:  //campo 7 (localidad)		
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 206: //campo 8 (referencia)
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 207: //campo 9	(delegacion)	
						$Tercerosbloques.= $aux1[2] . "\t" . $this->limpiar($rowt["delmund"]) . "\n";
						break;
					case 208: //campo 10 (estado)	
						$Tercerosbloques .= $aux1[2] . "\t" . $this->limpiar($rowt["estadod"]) . "\n";
						break;
					case 209: //campo 11 (pais)	
						$Tercerosbloques .= $aux1[2] . "\t" . $this->limpiar($rowt["paisd"]) . "\n";
						break;
					case 210:  //campo 12 (cp)
						$Tercerosbloques .= $aux1[2] . "\t" . $this->limpiar($rowt["cpd"]) . "\n";
						break;
	/*
					case 211: //campo 13 (No pedimiento aduanero)	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 212:  //campo 14 (fecha aduana)
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 213: //campo 15 (nombre aduana)
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
	*/
					case 214: //campo 16 (cuenta predial)
						$Tercerosbloques.= $aux1[2] . "\t" . $rowt["predial"] . "\n";
						break;
	/*					
					case 215: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 216: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 217: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 218: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 219: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 220: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 221: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 222: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 223: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 224: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 225: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 226: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 227: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 228: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 229: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 230: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 231: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 232: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 233: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 234: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
					case 235: //	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						break;
	*/					

					case 236: //TpMonto    Numlin
						$Tercerosbloques .= $aux1[2] . "\t" . $aux1[3] . "\n";
						break;
					case 237: //PorMonto     EAN	
						//$Tercerosbloques .= $aux1[2] . "\t" . number_format((($row["iva"]/$row["cantidad"])*100),0) . "\n";
						//$Tercerosbloques .= $aux1[2] . "\t" . number_format((($row["iiva"]/($row["csuma"]-$row["iiva"]))*100),0) . "\n";
						$Tercerosbloques .= $aux1[2] . "\t" . number_format((($ivap/($cantidadp-$ivap))*100),0) . "\n";
						
						
						break;
					case 238: //Monto    CodigoArt	  importe del impuesto
						//$Tercerosbloques .= $aux1[2] . "\t" .  number_format($row["iva"],2,".","") . "\n";
						$partet=0;
						if(is_null($rowt["porcentaje"])==true || $rowt["porcentaje"]==0)
						{
							$partet=1;
						}
						else
						{
							$partet=$rowt["porcentaje"]/100;	
						}
						
						//if($row["iva"]>0)
						//if($row["iiva"]>0)
						if($ivap>0)
						{
							$ivav=1;
						}
						//$Tercerosbloques .= $aux1[2] . "\t" .  number_format(($row["iva"]*$partet),2,".","") . "\n";
						//$Tercerosbloques .= $aux1[2] . "\t" .  number_format(($row["iiva"]*$partet),2,".","") . "\n";
						$Tercerosbloques .= $aux1[2] . "\t" .  number_format(($ivap*$partet),2,".","") . "\n";
						break;
					case 239: //IDMonto    CodArtPro
						if($ivav==1)
						{
							$Tercerosbloques .= $aux1[2] . "\tTRAS\n";
						}
						break; 
					case 240: //ClsMonto     NumSerie
						$Tercerosbloques .= $aux1[2] . "\tIVA\n";
						break;
					case 241: //FechaMonto    Cant	
						//$Tercerosbloques .= $aux1[2] . "\t" . $rowt[""] . "\n";
						
						break;
						
					}
				}

			}		
			//fin while tercero

			
		}
		
		
		
		//$resultado="";
		
		//echo $cat;
		foreach($this->lista as $idl => $aux1)
		{

			switch($aux1[0])
			{
				case 1:	//cfd, clave
					switch($this->tipocfd)
					{
					case 2:
						$resultado .= $aux1[2] . "\t381\n";
						break;
					default:
						$resultado .= $aux1[2] . "\t" . "380" . "\n";
					}
					break;
					
				case 2:	// version
					$resultado .= $aux1[2] . "\t" . "3.3" . "\n";
					if($terceros==1){
						$resultado .= "Complemento" . "\t" . "TERCEROS" . "\n";
					}
					break;
				case 3:	//tipoenvio
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 4:	//Tipocliente
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 5: //serie	
					$resultado .= $aux1[2] . "\t" . $serie . "\n";
					break;
				case 6:	//folio
					$resultado .= $aux1[2] . "\t" . $folio . "\n";
					break;
				case 7: //funcion	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 8: //fecha	
					$resultado .= $aux1[2] . "\t" . date("Y-m-d") . "T" . date("H:m:s") . "\n";
					break;
				case 9: //Sello	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 10: //Forma Pago	
					$formaPago="";
					if($this->tipocfd==2 || $this->tipocfd=="2")
					{					
						$formaPago = "15";
					}elseif(@$row["tipofactura"]=='PUE'){
						$sqlmp="select * from historia h, metodopago m where h.idmetodopago = m.idmetodopago and idhistoria = " . $row["parcialde"];
						$operacionmp = mysql_query($sqlmp);
						while ($rowmp = mysql_fetch_array($operacionmp)){
							$formaPago =$rowmp["clavefpagosat"];
						}
					}elseif(@$row["tipofactura"]=='PPD'){
						$formaPago = "99";
					}else{
						$formaPago = "01";
					}				
					$resultado .= $aux1[2] . "\t" . $formaPago . "\n";
					break;

				case 11: //No Certificado
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 12: //Certificado
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 13: //certificado
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 14: //FecNoRecep
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 15: //OrdComp
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 16: //FecOrdComp
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 17: //DepSenderId
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 18: //Complemento
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 19: //ComplementoTest
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 20: //NoDocInt
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 21: //NoRem
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;				
				case 22: //CondPago	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				/*
				case 23: //TotNeto  **************************************************************************************	
					$resultado .= $aux1[2] . "\t" . number_format( ($cantidadp-$ivap),2,".","") . "\n";
					break;
				case 24: //TotBruto
					$resultado .= $aux1[2] . "\t" . number_format( ($cantidadp-$ivap),2,".","") . "\n";
					break;				
				*/
				case 25: //TotCargDesc	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 26: //DivisOp	
					$resultado .= $aux1[2] . "\t" . "MXN" . "\n";
					break;
				case 27: //TC	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				/*
				case 28: //Importe	
					$resultado .= $aux1[2] . "\t" . number_format($cantidadp,2,".","") . "\n";
					break;
				*/
				case 29: //EfectoCFD(I,E) defecto "ingreso";
					switch($this->tipocfd)
					{
					case '1': //ingreso
						$resultado .= $aux1[2] . "\tI\n";
						break;
					case '2'://egreso
						$resultado .= $aux1[2] . "\tE\n";
						break;
					default:
						$resultado .= $aux1[2] . "\t" . "" . "\n";
					}
					break;
				case 30: //MetPago
					$metPagoClave="";
					if($this->tipocfd==2 || $this->tipocfd=='2'){
						$metPagoClave="PUE";
					}elseif(@$row["tipofactura"]=='PUE'){
						$metPagoClave="PUE";
					}elseif(@$row["tipofactura"]=='PPD'){
						$metPagoClave="PPD";
					}else{
						$metPagoClave="PUE";
					}
					$resultado .= $aux1[2] . "\t" . $metPagoClave . "\n";
					break;
				case 31: //LugarExp: Codigo Postal	
					$resultado .=  $aux1[2] . "\t" . "06470" . "\n";
					break;
				case 32: //Confirmacion	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 33: //FechaCancel	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 34: //Notas
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 35: //NotasImp	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 36: //ImpLetras	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 37: //TermsFlete
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 38: //NotasEmp
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 39: //Campo0 del REL Campo1
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 40: //Campo0 del REL Campo2
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 41: //ERFC emisor	*********************************************
					$resultado .=  $aux1[2] . "\tPAB0802225K4\n";
					break;
				case 42: //ENombre	
					$resultado .= $aux1[2] . "\tPADILLA & BUJALIL S.C.\n";
					break;
				case 43: //RegFiscal
					$resultado .= $aux1[2] . "\t" . "601" . "\n";
					break;
				case 44: //ENoProv	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 45: //EGLN	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 46: //ECalle	
					$resultado .= $aux1[2] . "\tAV. INSURGENTES CENTRO\n";
					break;
				case 47: //EColon	
					$resultado .= $aux1[2] . "\tSAN RAFAEL\n";
					break;
				case 48: //EMunic	
					$resultado .= $aux1[2] . "\tCUAUHTEMOC\n";
					break;
				case 49: //EEdo	
					$resultado .= $aux1[2] . "\tCIUDAD DE MEXICO\n";
					break;
				case 50: //Epais	
					$resultado .= $aux1[2] . "\tMEXICO\n";
					break;
				case 51: //ECP	
					$resultado .= $aux1[2] . "\t06470\n";
					break;
				case 52: //Eemail	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 53: //ENoArea	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 54: //ExNombre	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 55: //ExCalle	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 56: //ExColon	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 57: //ExMunic	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 58: //ExEdo	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 59: //Expais	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 60: //ExCP	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;				
				case 61: //RRFC	******************************************
					$rf =$row["rfc"];
					if (strlen($rf)<12)
					{
						$rf="XAXX010101000";
					}
					
					$resultado .= $aux1[2] . "\t" . $this->limpiar($rf) . "\n";
					break;
				case 62: //RNombre	
					$resultado .= $aux1[2] . "\t" . $this->limpiar($row["nombrei"]) . " " . $this->limpiar($row["nombre2i"]) . " " . $this->limpiar($row["apaternoi"]) . " " . $this->limpiar($row["amaternoi"]) . "\n";
					break;
				case 63: //RResFiscal	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 64: //"RNumRegTrib OR If TipoCliente=EXTRANJERO Use RRFC
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 65: //RUsoCFDI	
					$resultado .= $aux1[2] . "\t" . $row["claveucfdi"] . "\n";
					break;
				case 66: //RGLN	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 67: //RContact	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 68: //RIEPS	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 69: //RCalle	
					$resultado .= $aux1[2] . "\t" . $this->limpiar($row["calle"]) . "\n";
					break;
				case 70: //	RColon
					$resultado .= $aux1[2] . "\t" . $this->limpiar($row["colonia"]) . "\n";
					break;
				case 71: //RMunic	
					$resultado .= $aux1[2] . "\t" . $this->limpiar($row["delmun"]) . "\n";
					break;
				case 72: //REdo	
					$resultado .= $aux1[2] . "\t" . $this->limpiar($row["estado"]) . "\n";
					break;
				case 73: //Rpais	
					$resultado .= $aux1[2] . "\t" . $this->limpiar($row["pais"]) . "\n";
					break;
				case 74: //RCP	
					$resultado .= $aux1[2] . "\t" . $this->limpiar($row["cp"]) . "\n";
					break;
/*				
				case 75: //CoGLN	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 76: //CoContac	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 77: //PrNoProv	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 78: //PrGLN	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 79: //PrIEPS	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 80: //RmNombre	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 81: //RmGLN	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 82: //RmCalle	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 83: //RmNoext	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 84: //RmNoint	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 85: //RmColon	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 86: //RmLoc	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 87: //RmRef	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 88: //RmMunic	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 89: //RmEdo	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 90: //Rmpais	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 91: //RmCP	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 92: //DivisFn	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 93: //RelTiempo	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 94: //RefTiempo	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 95: //TipoPeriodo	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 96: //NumPeriodos	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 97: //TipoDescPago	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 98: //PorDescPago	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 99: //FechaIni	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 100: //FechaVen	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 101: //TpNoSP	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 102: //NoSP	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 103: //idNoSP	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 104: //FechaNoSP	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 105: //TxtNoSP	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
*/
//para envio de correo
				case 106: //Cont
					$resultado .= $aux1[2] . "\t" . "R"  . "\n";
					break;
				case 107: //TipoCont
					$resultado .= $aux1[2] . "\t" . "SMTP"  . "\n";
					break;
				case 108: //NombreCont	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 109: //EmailCont
					//correos de los inquilinos.
				
					if(is_null($row["email"])==true && is_null($row["email1"])==true && is_null($row["email2"])==true)
					{
						//$correoei=	"contabilidad@padilla-bujalil.com.mx";
						$correoei=	"estadosdecuentapropietarios@padillabujalil.com";
					}
					else
					{
						$correoei =	"";
						if(is_null($row["email"])==false && $row["email"]!="")
						{
							$correoei=	$row["email"];
						}
						
						if(is_null($row["email1"])==false  && $row["email1"]!="")
						{
							if($correoei!="")
							{
								$correoei .=	"," . $row["email1"];
							}
							else
							{							
								$correoei=	$row["email1"];
							}
						}
						if(is_null($row["email2"])==false  && $row["email2"]!="")
						{
							if($correoei!="")
							{
								$correoei .=	"," . $row["email2"];
							}
							else
							{							
								$correoei=	$row["email2"];
							}
						}
						
						
					}
					//$correoei=	"contabilidad@padilla-bujalil.com.mx";
					$resultado .= $aux1[2] . "\t" . $correoei . "\n";
					break;
				case 110: //TelefonoCont
					$resultado .= $aux1[2] . "\tTel: 5592.8816\n";
					break;	
//para envio de correo fin

				case 111: //DcIndDC	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 112: //DcImputacion
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 113: //DcTipo	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 114: //DcBase
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 115: //DcPorcentaje	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 116: //DcImporte	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 117: //IndicadorR	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 118: //BaseR	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 119: //Numlin      CantGratis	
					$resultado .= $aux1[2] . "\t1\n";
					break;
				case 120: //EAN      UM	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 121: //ClaveProdServ
					$resultado .= $aux1[2] . "\t" . $row["claveps"] . "\n";
					break;
				case 122: //CodigoArt
					$resultado .= $aux1[2] . "\t" . $row["idc"] . "\n";
					break;
				case 123: //CodArtPro 
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 124: //NumSerie  
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 125: //Cant  
					$resultado .= $aux1[2] . "\t1\n";
					break;
				case 126: //CantGratis
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 127: //ClaveUnidad
					$resultado .= $aux1[2] . "\t" . $row["claveum"] . "\n";
					break;
				case 128: //UM      Precop
					//Colocar para todos NO APLICA
					$tc="NO APLICA";
					/*switch($row["idtipocontrato"])
					{
					case 1://casa habitacion
						$tc="CH";
						break;
					case 2://local comercial
						$tc="LC";
						break;
					}
					*/					
					$resultado .= $aux1[2] . "\t$tc\n";
					break;
				case 129: //UnidCom     ImporOp	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 130: //Desc     ImporBruto
				
					$mes = "";
					switch($row["mes"])
					{
					case 1: //enero
						$mes = "ENERO";
						break;
					case 2:
						$mes = "FEBRERO";
						break;
					case 3:
						$mes = "MARZO";
						break;
					case 4:
						$mes = "ABRIL";
						break;
					case 5:
						$mes = "MAYO";
						break;
					case 6:
						$mes = "JUNIO";
						break;
					case 7:
						$mes = "JULIO";
						break;
					case 8:
						$mes = "AGOSTO";
						break;
					case 9:
						$mes = "SEPTIEMBRE";
						break;
					case 10:
						$mes = "OCTUBRE";
						break;
					case 11:
						$mes = "NOVIEMBRE";
						break;
					case 12:
						$mes = "DICIEMBRE";
						break;						
					}
				
					$txtinteres="";
					if($int==1)
					{
						$txtinteres=" INTERESES DE ";
					}
				
					switch($this->tipocfd)
					{
					case 2:
						$nc = "($notanc)";
						break;
					default:
						$nc = "";
					}
															
					
					//$resultado .= $aux1[2] . "\t" . $row["idc"] . ":$txtinteres" .  $row["tipocobro"] . " CORRESPONDIENTE AL " . $row["dia"] . " DE $mes DE " .  $row["anio"]    . " de " .  $this->limpiar( $row["callein"]    . " " .  $row["noextin"]    . " " .  $row["nointin"])    . " $nc\n";
					$resultado .= $aux1[2] . "\t" . $row["idc"] . ":$txtinteres" .  $row["tipocobro"] . " CORRESPONDIENTE AL " . $row["dia"] . " DE $mes DE " .  $row["anio"]    . " de " .  $this->limpiar( $row["callein"]    . " " .  $row["noextin"]    . " " .  $row["nointin"])    . " $nc. " . $row["observaciones"] . "\n";

					break;
				case 131:	//PrecMX     ImporNeto
					//$resultado .= $aux1[2] . "\t" . number_format( $row["cantidad"],2,".","") . "\n";
					//$resultado .= $aux1[2] . "\t" . number_format( ($row["csuma"]-$row["iiva"]),2,".","") . "\n";
					$resultado .= $aux1[2] . "\t" . number_format( ($cantidadp-$ivap),2,".","") . "\n";
					break;
				case 132:	//ImporMX     NivOrdComp
					//$resultado .= $aux1[2] . "\t" . number_format( $row["cantidad"],2,".","") . "\n";
					//$resultado .= $aux1[2] . "\t" . number_format( ($row["csuma"]-$row["iiva"]),2,".","") . "\n";
					$resultado .= $aux1[2] . "\t" . number_format( ($cantidadp-$ivap),2,".","") . "\n";
					break;
				case 133: //DescuentoArt 
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 134: //Precop     
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 135: //PrecBruto      LinOrdComp
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 136:	//ImporOp    Notaslin
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 137:	//ImporBruto   TpoPrecio
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 138:	//ImporNeto    PrecList
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 139:	//LinOrdComp      UICodSeriado
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 140:	//NivOrdComp      uisrv
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 141:	//TpNotaslin    IENumPalet
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 142: 	//Notaslin      IEDescPalet
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 143: //TpoPrecio     IEDTipoPalet	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 144: //Preclist      IEPagoTrans
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 145: //UICodSeriado     ILNumLote
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 146: //UISRV    ILFechaLote	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 147: //IENumPalet     NoReleased
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 148: //IEDescPalet     DtIndDC	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;

				case 149: //IETipoPalet  DtImputacion
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 150: //IEPagoTrans    DtSecuencia	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 151: //ILNumLote   DtTipo	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 152: //ILFechaLote    DtPorcentaje	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 153: //NoReleased    DtImpUnidad	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 154: //DtIndDC    DtImp	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 155: //DtImputacion    DescTipoImp	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 156: //DtSecuencia    PorImp	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 157: //DtTipo    CategImp	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 158: //DtPorcentaje     NumRefimp	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 159: //DtImpUnidad     ImporImp	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;

				case 160: //DescTipoImp
					$resultado .= $aux1[2] . "\t" . "002"  . "\n";
					break;
				case 161: //BaseImp			TRANSLADO ******************
					$resultado .= $aux1[2] . "\t" . number_format( ($cantidadp-$ivap),2,".","")  . "\n";
					break;
				case 162: //CategImp
					$resultado .= $aux1[2] . "\t" . "T"  . "\n";
					break;

				case 163: //TipoFactor
					$resultado .= $aux1[2] . "\t" . "Tasa"  . "\n";
					break;
				case 164: //PorImp
					$resultado .= $aux1[2] . "\t" . number_format(round(($ivap/($cantidadp-$ivap)),2),6) . "\n";
					break;
				

				case 165: //ImporImp
					$resultado .= $aux1[2] . "\t" .  number_format($ivap,2,".","") . "\n";
					break;
				case 166: //BaseImp       RETENIDO ******************
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 167: //DescTipoImp
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 168: //TipoFactor
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 169: //PorImp
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 170: //CategImp
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 171: //ImporImp
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 171: //ImporImp
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 172: //Pedimen
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 173: //NumCPred
					$resultado .= $aux1[2] . "\t" . $row["predial"] . "\n";
					break;
				case 174: //CampoDet0
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 175: //CampoDet1
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 176: //PPClaveProdServ
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 177: //PPCodArtPro
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 178: //PPcant
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 179: //PPUM
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 180: //PDesc
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 181: //PPrecMX
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 182: //PPImporMX
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 183: //PPedimen
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 184: //TpMonto
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 185: //PorMonto
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 186: //Monto
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 187: //IDMonto
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 188: //ClsMonto
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				/*
				case 189: //TotImpR
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				*/
				case 189: //TotImpT
					$resultado .= $aux1[2] . "\t" . number_format($ivap,2,".","") . "\n";
					break;
				case 190: //TotImp
					$resultado .= $aux1[2] . "\t" . number_format($ivap,2,".","") . "\n";
					break;
				/*
				case 192: //TipImpR
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 193: //MonImpR
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				*/

				case 191: //TotNeto
					$resultado .= $aux1[2] . "\t" . number_format( ($cantidadp-$ivap),2,".","") . "\n";
					break;
				case 192: //TotBruto
					$resultado .= $aux1[2] . "\t" . number_format( ($cantidadp-$ivap),2,".","") . "\n";
					break;
				case 193: //Importe	
					$resultado .= $aux1[2] . "\t" . number_format($cantidadp,2,".","") . "\n";
					break;
				case 194: //TipImpT
					$resultado .= $aux1[2] . "\t" . "002"  . "\n";
					break;
				case 195: //TipFactT
					$resultado .= $aux1[2] . "\t" . "Tasa"  . "\n";
					break;
				case 196: //PorImpT
					$resultado .= $aux1[2] . "\t" . number_format(round(($ivap/($cantidadp-$ivap)),2),6) . "\n";
					break;
				case 197: //MonImpt
					$resultado .= $aux1[2] . "\t" .  number_format($ivap,2,".","") . "\n";
					break;
				case 198: //Seccion de Terceros	
					$resultado .= $Tercerosbloques;
					break;					
			}
		}

		return $resultado;
	
	}
	
/*Datos con  posibilidad de uso
case 23: //No cuenta
					//hacer un bucle para hacer el concatenado de las cuentas en el orden
					//de pago
					$cuentap="";
					$sqlmp="select * from historia h, metodopago m where h.idmetodopago = m.idmetodopago and parcialde = " . $row["parcialde"];
					$operacionmp = mysql_query($sqlmp);
					while ($rowmp = mysql_fetch_array($operacionmp)) 
					{
						$cuentap .=$rowmp["cuentapago"] . ",";
				
					}
					$cuentap=substr($cuentap,0,-1);
					
						switch($this->tipocfd)
						{
					
						case '2'://egreso
							$metodop = "NOTA DE CREDITO";
							break;
					
						default://ingreso
							//if(is_null($rowmp["metodopago"]))
							if($cuentap=="" || strlen($cuentap)<4)
							{
								$cuentap = "";
							}
						
							break;

						}
						
					if(strlen($cuentap)>3)
					{
						$resultado .= $aux1[2] . "\t" . $cuentap . "\n";
					}
					break;					

*/

	
//funcion para mostrar datos de facttura libre
	function cadena_factura_libre($serie, $folio, $listacfdi)
	{
		
		//$serie = Serie aplicable a la factura
		//$folio = folio aplicabla a la factura
		//$listacfdi = cadena que llevará a los terceros

		
		//los conceptos de terceros, receptor, conceptos,impuestos, van a ser cadenas separadas por "|" en los datos y
		//los registros separados por *
		
		
		
		$listacfdi=substr($listacfdi,1,-1);
		$separado = split("[*]",$listacfdi);
		//var_dump($separado);
		$resultado="";
		$resultado0="";
		$resultado1="";
		$resultado2="";
		$resultado3="";
		$CampoTercero="";
		$listater[0][0]="";
		$listaidter=0;
		$MontImpT=0;
		$ImpTer=0;
		foreach($separado as $key => $datos)
		{
			
			$d = split("[|]",$datos);
			$idd = split("[_]",$d[0]);
			switch($idd[0])
			{
			
//+++++++++++++++++++++
			
			case 5: //serie	
				$resultado1 .= $idd[1] . "\t" . $serie . "\n";
				break;
			case 6:	//folio
				$resultado1 .= $idd[1] . "\t" . $folio . "\n";
				break;	

			case 29:	//EfectoCFD(ingreso, egreso, trasaldo) defecto "ingreso";
				switch($this->tipocfd)
				{
				case '1': //ingreso
					$resultado1 .= $idd[1] . "\tI\n";
					break;
				case '2'://egreso
					$resultado1 .= $idd[1] . "\tE\n";
					break;
				default:
					$resultado1 .= $idd[1] . "\t" . "I" . "\n";
				}
				//$resultado .= $aux1[1] . "\t" . $aux1[7] . "\n";
				break;				
						
				
			case 61: //RRFC	
				$rf =$d[1];
				if (strlen($rf)<12)
				{
				
					$rf="XAXX010101000";
				}
				
				$resultado1 .= $idd[1] . "\t" . $this->limpiar($rf) . "\n";
				
				break;	
			
			/*
			case 20: //MetPago: metodo de pago (	cheque tranferencia efectivo)
			
				//uso de llave parcialde para poder obtener todas los metodos de pago
				//hacer consulta

				 
					
				$metodop="";
				if(trim($d[1])=='')
				{
					$metodop = "NO IDENTIFICADO";
				}
				else
				{
					$metodop = $d[1];
				}
			
				$resultado1 .= $idd[1] . "\t" . $metodop . "\n";
				break;
				
			case 23: //No cuenta
				
				$resultado1 .= $idd[1] . "\t" . $d[1] . "\n";
				break;
			*/
			case 106: //Cont      CantGratis	
				$resultado1 .= $idd[1] . "\t" . $d[1] . "\n";
				break;
			case 107: //TipoCont
				$resultado1 .= $idd[1] . "\t" . $d[1] . "\n";
				break;	
			case 108: //EmailCont
					//correos de los inquilinos.
				
					$resultado1 .= $idd[1] . "\t" . $d[1] . "\n";
				break;
			case 110: //TelefonoCont
				$resultado1 .= $idd[1] . "\tTel: 5592.8816\n";
				break;					
			
			/*
			case 121: //InfoCont
				$resultado1 .= $idd[1] . "\tServicio de envio automatico de su factura electronica. Tienes una propiedad? Rentala!\n";
				//$resultado .= $aux1[1] . "\t" . number_format( $row["cantidad"],2,".","") . "\n";
				break;				
//ojo, estan desfazados estos numeros en el layout
			*/
			case 187: //terceros	
				//leer el siguiente dato y acomodar los datos viene separado por "["
				//ej "id[predial[porcentaje
				//$d[1]: es el dato a evaluar
				
				if($d[1]!=""){ 
					$listater[$listaidter][0]=$idd[1] ;
					$listater[$listaidter][1]=$d[1] ;
					$listaidter +=1;
					$CampoTercero="Complemento\t" ." TERCEROS\n";
				}

				break;

			case 131:	//PrecMX
	
				$resultado1 .= $idd[1] . "\t" . number_format( $d[1],2,".","")  . "\n";
				break;

			case 132:	//ImporMX
	
				$resultado1 .= $idd[1] . "\t" . number_format( $d[1],2,".","")  . "\n";
				break;

			case 161:	//BaseImp

				$resultado1 .= $idd[1] . "\t" . number_format($d[1],2,".","") . "\n";
				break;

			case 165:	//ImporImp

				$resultado1 .= $idd[1] . "\t" . number_format($d[1],2,".","") . "\n";
				break;

			case 189:	//TotImpT

				$resultado3 .= $idd[1] . "\t" . number_format($d[1],2,".","") . "\n";
				break;
			case 190:	//TotImp

				$resultado3 .= $idd[1] . "\t" . number_format($d[1],2,".","") . "\n";
				break;

			case 191:	//TotNeto
	
				$resultado3 .= $idd[1] . "\t" . number_format( $d[1],2,".","")  . "\n";
				break;
			case 192:	//TotBruto

				$resultado3 .= $idd[1] . "\t" . number_format( $d[1],2,".","")  . "\n";
				break;

			case 193:	//Importe

				$resultado3 .= $idd[1] . "\t" . number_format( $d[1] ,2,".","")  . "\n";
				break;

			case 194: //TipImpT
				$resultado3 .= $idd[1] . "\t" . $d[1] . "\n";
				break;

			case 195: //TipFactT
				$resultado3 .= $idd[1] . "\t" . $d[1] . "\n";
				break;					
			
			case 196:	//PorImpT

				$resultado3 .= $idd[1] . "\t" . $d[1] . "\n";	
				$ImpTer=number_format(($d[1]*100),0);			
				break;
			case 197:	//MonImpT

				$resultado3 .= $idd[1] . "\t" .  number_format($d[1],2,".","") . "\n";
				$MontImpT=number_format($d[1],6,".","");
				break;				

			default:
				if($idd[0]<198)
				{
					$resultado1 .= $idd[1] . "\t" . $this->limpiar($d[1]) . "\n";
				}
				elseif($idd[0]<231)
				{
					$resultado2 .= $idd[1] . "\t" . $this->limpiar($d[1]) . "\n";
				}
				else
				{
					$resultado3 .= $idd[1] . "\t" . $this->limpiar($d[1]) . "\n";
				}
				//$resultado .= $idd[1] . "\t" . $this->limpiar($d[1]) . "\n";
					
			}
		}
		
		if($listater[0][0]!="")
		{
			for($jidter=0;$jidter<=$listaidter-1;++$jidter)
			{

				$valter = split("[~]",$listater[$jidter][1]);
				//terceros
				$sqlter = "select * from duenio  where idduenio = ". $valter[0];
				$operacionter = mysql_query($sqlter);
				//$Tercerosbloques="";
				$rowt = mysql_fetch_array($operacionter);
				
				$resultado2 .= "Campo0" . "\tTERCERO\n";
				$rf =$rowt["rfcd"];
				if (strlen($rf)<12)
				{
					$rf="XAXX010101000";
				}
				$resultado2 .= "Campo1" . "\t" . $this->limpiar($rf) . "\n";//RFC TErcero
				$resultado2 .= "Campo2" . "\t"  . $this->limpiar($rowt["nombre"]) . " " . $this->limpiar($rowt["nombre2"]) . " " . $this->limpiar($rowt["apaterno"]) . " " . $this->limpiar($rowt["amaterno"]) . "\n"; //nombre tercero
				$resultado2 .= "Campo3" . "\t" . $this->limpiar($rowt["called"]) . "\n"; //calle Tercero
				$resultado2 .= "Campo4" . "\t" . $this->limpiar($rowt["noexteriord"]) . "\n"; //no exteriro tercero
				$resultado2 .= "Campo5" . "\t" . $this->limpiar($rowt["nointeriord"]) .  "\n";//no interior tercero
				$resultado2 .= "Campo6" . "\t" . $this->limpiar($rowt["coloniad"]) . "\n";//col. tercero
				$resultado2 .= "Campo9" . "\t" . $this->limpiar($rowt["delmund"]) . "\n";//delmun tercero
				$resultado2 .= "Campo10" . "\t" . $this->limpiar($rowt["estadod"]) . "\n";//estado tercero
				$resultado2 .= "Campo11" . "\t" . $this->limpiar($rowt["paisd"]) . "\n";//pais tercero
				$resultado2 .= "Campo12" . "\t" . $this->limpiar($rowt["cpd"]) . "\n";//cp tercero
				$resultado2 .= "Campo16" . "\t"  . $valter[1] .  "\n"; //predial
				$resultado2 .= "TpMonto" . "\tTERCERO\n";//
				$resultado2 .= "PorMonto" . "\t$ImpTer\n";//TASA IMPUESTO TER

				$partet=0;
				if(is_null($valter[2])==true || $valter[2]=='')
					{
						$partet=1;
					}
					else
					{
						$partet=$valter[2]/100;	
					}

				$resultado2 .= "Monto" . "\t" .  number_format(($MontImpT*$partet),2,".","") . "\n";

				$resultado2 .= "IDMonto" . "\tTRAS\n";
				$resultado2 .= "ClsMonto" . "\tIVA\n";			
			}
			

		}			
			
//+++++++++++++++++++++			
		$resultado = $resultado1 . $resultado3 . $resultado2; 	
			

		return $resultado;
	
	}	



//funcion para mostrar datos de Pagos
	function cadena_pagos($id,$cat,$row,$folio,$serie){ 

		//$id = id del historial
		//$cat = catalogo de la lista requerida por el PAC simpre 1
		//$row = recordset de la consulta de la base de datos
		//$folio = folio de la factura
		//$serie = serie de la factura

		//obtengo los importes reales para facturar
		$sqlcal = "SELECT * FROM historia WHERE idhistoria =$id";
		$operaciontcal = mysql_query($sqlcal);
		$rcal =mysql_fetch_array($operaciontcal);
		
		$cantidadp = $rcal["cantidad"];
		if($row["idtipocontrato"]==2 || $row["inth"]==1){
			$subtotalp = ($cantidadp / 1.16);	
			$ivap = ($subtotalp * 0.16);	
		}else{
			$ivap =0;	
		}

		$sqlDocRel = "SELECT * FROM facturacfdi f, historiacfdi h WHERE f.idfacturacfdi=h.idfacturacfdi AND h.idhistoria=".$row["parcialde"];
		$operacionDocRel = mysql_query($sqlDocRel);
		$rowDocRel = mysql_fetch_array($operacionDocRel);
		$archivoxml = $rowDocRel["archivoxml"];

		$xmlcfdi = new xmlcfd_cfdi;
		
		$dir="/home/wwwarchivos/cfdi/";
		$dir .= $archivoxml;
		
		$xmlcfdi->leerXML($dir);

		$xmlAll=$xmlcfdi->comprobante;
		$xmlUUID = $xmlAll["Complemento"]["TimbreFiscalDigital"]["UUID"]["valor"];
		$textUUID = $xmlUUID->__toString();
		
		$serieDocRel = $xmlcfdi->comprobante["Serie"]["valor"];
		$folioDocRel = $xmlcfdi->comprobante["Folio"]["valor"];
		$uuidDocRel = $textUUID;
		
		if(($serieDocRel=='' || $serieDocRel==NULL) && ($folioDocRel=='' || $folioDocRel==NULL) && ($uuidDocRel=='' || $uuidDocRel==NULL)){
			exit();
		}

		//Numero de pago, saldo anterior facturar
		$sqlNumPagos = "SELECT * FROM historia WHERE idhistoria<=$id AND parcialde =".$row["parcialde"]. " ORDER BY idhistoria";
		$operacionNumPagos = mysql_query($sqlNumPagos);
		$numPagos=0;
		$cantidadAnterior=0;
		while ($rowNumPagos =mysql_fetch_array($operacionNumPagos)){
			$numPagos ++;
			if($rowNumPagos["idhistoria"]==$id){
				if(substr($rowNumPagos["notas"],0,9)=="LIQUIDADO"){
					$saldoAnt=$rowNumPagos["cantidad"];
					$impPagado=$rowNumPagos["cantidad"];
					$saldoInsoluto=0.00;
				}else{
					$pesitos=strpos($rowNumPagos["notas"], "\$");
					$saldoInsoluto=(substr($rowNumPagos["notas"],($pesitos+1)))*1;
					$impPagado=$rowNumPagos["cantidad"];
					$saldoAnt= $impPagado +	$saldoInsoluto;
				}
			}
		}

		//Cabecera de factura			
		$resultado = "";
		
		//echo $cat;
		foreach($this->lista as $idl => $aux1)
		{

			switch($aux1[0])
			{
				case 1:	//cfd
					$resultado .= $aux1[2] . "\t" . "380" . "\n";
					break;
				/*Posicion original
				case 2: //Complemento	
					$resultado .= $aux1[2] . "\t" . "PAGOS" . "\n";
					break;
				*/
				case 2: //EfectoCFD
					$resultado .= $aux1[2] . "\t" . "P" . "\n";
					break;
				case 3:	//version
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 4:	//Serie
					$resultado .= $aux1[2] . "\t" . $serie . "\n";
					break;
				case 5: //Folio	
					$resultado .= $aux1[2] . "\t" . $folio . "\n";
					break;
				case 6:	//Fecha	
					$resultado .= $aux1[2] . "\t" . date("Y-m-d") . "T" . date("H:m:s") . "\n";
					break;
				case 7: //Complemento	
					$resultado .= $aux1[2] . "\t" . "PAGOS" . "\n";
					break;
				/*
				case 7: //FormaPago
					//$resultado .= $aux1[2] . "\t" . "	xxx" . "\n";
					break;
				*/
				case 8: //CondPago
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 9: //TotNeto
					//$resultado .=  $aux1[2] . "\t" . "" . "\n";
				case 10: //Descuento
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;				
				case 11: //Moneda
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 12: //TC
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 13: //Importe
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				/*Posicion original
				case 14: //EfectoCFD
					$resultado .= $aux1[2] . "\t" . "P" . "\n";
					break;
				*/
				case 15: //MetPago
					//$resultado .=  $aux1[2] . "\t" . "XXXX" . "\n";
					break;	
				case 16: //LugarExp
					$resultado .=  $aux1[2] . "\t" . "06470" . "\n";
					break;
				case 18: //Nodo: CfdiRelacionados
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 19: //Campo0
					//$resultado .= $aux1[2] . "\t" . "REL" . "\n";
					break;
				case 20: //Campo1  c_TipoRelacion 
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 21: //Nodo: CfdiRelacionados
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 22: //Campo1  UUID
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 23: //Nodo: Emisor
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 24: //Emisor,Receptor
					//ERFC emisor
					$resultado .=  $aux1[2] . "\tPAB0802225K4\n";
					break;
				case 25: //RegFiscal
					$resultado .= $aux1[2] . "\t" . "601" . "\n";
					break;
				case 26: //ENombre	
					$resultado .= $aux1[2] . "\tPADILLA & BUJALIL S.C.\n";
					break;
				/*Posicion Original
				case 26: //RegFiscal
					$resultado .= $aux1[2] . "\t" . "601" . "\n";
					break;
				*/
				case 27: //Nodo: Receptor
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;					
				case 28: //RRFC
					$rf =$row["rfc"];
					if (strlen($rf)<12)
					{
						$rf="XAXX010101000";
					}					
					$resultado .= $aux1[2] . "\t" . $this->limpiar($rf) . "\n";
					break;
				case 29: //RNombre	
					$resultado .= $aux1[2] . "\t" . $this->limpiar($row["nombrei"]) . " " . $this->limpiar($row["nombre2i"]) . " " . $this->limpiar($row["apaternoi"]) . " " . $this->limpiar($row["amaternoi"]) . "\n";
					break;
				case 30: //RResFiscal
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 31: //RNumRegTrib
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 32: //RUsoCFDI	
					//$resultado .= $aux1[2] . "\t" . $row["claveucfdi"] . "\n";
					break;
				case 33: //Nodo: Conceptos
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 34: //Nodo: Concepto
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 35: //Numlin
					$resultado .= $aux1[2] . "\t1\n";
					break;
				case 36: //ClaveProdServ
					//$resultado .= $aux1[2] . "\t" . "84111506" . "\n";
					break;
				case 37: //CodArtPro
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;				
				case 38: //Cant
					//$resultado .= $aux1[2] . "\t" . "1" . "\n";
					break;
				case 39: //ClaveUnidad
					//$resultado .= $aux1[2] . "\t" . "ACT" . "\n";
					break;
				case 40: //UM			
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 41: //Desc			
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 42: //PrecMX		
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 43: //ImporMX		
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 44: //DescuentoArt		
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 45: //Nodo: Impuestos		
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 46: //Nodo: InformacionAduanera		
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 47: //Nodo: CuentaPredial
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 48: //Nodo: ComplementoConcepto
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 49: //Nodo: Parte		
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 50: //Nodo: Impuestos		
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 51: //Complemento de Pagos
					//$resultado .= $aux1[2] . "\t" . "XXX" . "\n";
					break;
				case 52: //Pagos
					//$resultado .= $aux1[2] . "\t" . "" . "\n";
					break;
				case 53: //CmPgVersion
					$resultado .= $aux1[2] . "\t" . "1.0" . "\n";
					break;
				case 54: //CmPgPago
					$resultado .= $aux1[2] . "\t" . "1" . "\n";
					break;
				case 55: //CmPgFechaPago
					$resultado .= $aux1[2] . "\t" . $row["fechapago"] . "T" . date("H:m:s") . "\n";
					break;
				case 56: //CmPgFormaDePago
					$metodop="";
					echo $sqlmp="select * from historia h, metodopago m where h.idmetodopago = m.idmetodopago and idhistoria=$id";
					$operacionmp = mysql_query($sqlmp);
					while ($rowmp = mysql_fetch_array($operacionmp)) 
					{
						$metodop =$rowmp["clavefpagosat"];
				
					}
					if($metodop==""){
						$metodop = "01";
					}
					$resultado .= $aux1[2] . "\t" . $metodop . "\n";
					break;
				case 57: //CmPgDivisOp
					$resultado .= $aux1[2] . "\t" . "MXN" . "\n";
					break;
				case 58: //CmPgTC
					//$resultado .= $aux1[2] . "\t" . "1.00" . "\n";
					break;
				case 59: //CmPgMonto	
					$resultado .= $aux1[2] . "\t" . number_format($cantidadp,2,".","") . "\n";
					break;
				case 60: //CmPgNumOperacion
					if($row["idinquilino"]==179){
						$resultado .= $aux1[2] . "\t" . "1" . "\n";
					}
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 61: //CmPgERfcCtaOrd 
					if($row["idinquilino"]==179){
						$resultado .= $aux1[2] . "\t" . "BSM970519DU8" . "\n";
					}
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 62: //CmPgBancoOrdExt 
					if($row["idinquilino"]==179){
						$resultado .= $aux1[2] . "\t" . "Banco Santander (México), S.A. Institución de Banca Múltiple" . "\n";
					}
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 63: //CmPgCtaOrd 
					if($row["idinquilino"]==179){
						$resultado .= $aux1[2] . "\t" . "014180655022597687" . "\n";
					}
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 64: //CmPgERfcCtaBen
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 65: //CmPgCtaBen	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 66: //CmPgTipoCadPago
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 67: //CmPgCertPago
					//$resultado .= $aux1[2] . "\t" . "1.00" . "\n";
					break;
				case 68: //CmPgCadPago	
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 69: //CmPgSelloPago
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 70: //CmPgDctoRel 
					$resultado .= $aux1[2] . "\t" . "1" . "\n";
					break;
				case 71: //CmPgIdDoc UUID 
					$resultado .= $aux1[2] . "\t" . $uuidDocRel . "\n";
					//$resultado .= $aux1[2] . "\t" . $row["uuid"] . "\n";
					break;
				case 72: //CmPgSerieP 
					$resultado .= $aux1[2] . "\t" . $serieDocRel . "\n";
					//$resultado .= $aux1[2] . "\t" . $row["serie"] . "\n";
					break;
				case 73: //CmPgFolioP 
					$resultado .= $aux1[2] . "\t" . $folioDocRel . "\n";
					//$resultado .= $aux1[2] . "\t" . $row["folio"] . "\n";
					break;
				case 74: //CmPgDivisOpDR	
					$resultado .= $aux1[2] . "\t" . "MXN" . "\n";
					break;
				case 75: //CmPgTCDR
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 76: //CmPgMetPagoDR
					$resultado .= $aux1[2] . "\t" . "PPD" . "\n";
					break;
				case 77: //CmPgNumParcialidad 
					$resultado .= $aux1[2] . "\t" . $numPagos . "\n";
					break;
				case 78: //CmPgImpSaldoAnt
					$resultado .= $aux1[2] . "\t" . number_format($saldoAnt,2,".","") . "\n";
					break;
				case 79: //CmPgImpPagado
					$resultado .= $aux1[2] . "\t" . number_format($impPagado,2,".","") . "\n";
					break;
				case 80: //CmPgImpSaldoInsoluto
					$resultado .= $aux1[2] . "\t" . number_format($saldoInsoluto,2,".","") . "\n";
					break;
				case 81: //Campos para el complemento de pagos versión 1.1
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 82: //CmPgImpuestos
					//$resultado .= $aux1[2] . "\t" . "1" . "\n";
					break;
				case 83: //CmPgTotImpR
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 84: //CmPgTotImpT 
					//$resultado .= $aux1[2] . "\t" . $ivap. "\n";
					break;
				case 85: //
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 86: //Retenciones
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 87: //CmPgRetencion
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 88: //CmPgTipImpR
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 89: //CmPgMonImpR
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 90: //
					//$resultado .= $aux1[2] . "\t" . $row[""] . "\n";
					break;
				case 91: //Traslados
					//$resultado .= $aux1[2] . "\t" . "1" . "\n";
					break;
				case 92: //CmPgTraslado
					//$resultado .= $aux1[2] . "\t" . "1" . "\n";
					break;
				case 93: //CmPgTipImpT
					//$resultado .= $aux1[2] . "\t" . "002". "\n";
					break;
				case 94: //CmPgTipoFactor
					//$resultado .= $aux1[2] . "\t" . "Tasa" . "\n";
					break;
				case 95: //CmPgPorImpT
					//$resultado .= $aux1[2] . "\t" . "0.160000". "\n";
					break;
				case 96: //CmPgMonImpT 
					//$resultado .= $aux1[2] . "\t" . $ivap. "\n";
					break;
			}
		}

		return $resultado;
	
	}
	
	

	function limpiar ($txt)
	{
		$r = trim($txt);
		$r=str_replace(",", "",$r);
		$r=str_replace("<br>", "",$r) ;
		$r=str_replace("<b>", "",$r) ;
		$r=str_replace("<BR>", "",$r) ;
		$r=str_replace("<B>", "",$r) ;
		$r=str_replace("*", "&",$r) ;
		$r=str_replace("/", "&",$r) ;
		
		//$r=utf8_decode($r);
		return $r;
	
	}
}


?>
