var Visible = true;
var Nomenus = 10;
var ArrMenus = new Array(10);
var larchvosb;
var pausacarrusel='';
var  area2,area1;


function cambiahtml(elemento,cambio)
{
var MenNosotros,Eventos;
var ElDiv = document.getElementById(elemento);

Eventos="onMouseOver=\"this.className='SubMenu2'\" onMouseOut=\"this.className='SubMenu1'\"";


Inicio = "<h1>EN CONSTRUCCION</h1>";

QuienesSomos ="<h1>EN CONSTRUCCION</h1>";

MisionyVision ="<h1>EN CONSTRUCCION</h1>";

Servicios = "<h1>EN CONSTRUCCION</h1>";

Contactanos = "<h1>EN CONSTRUCCION</h1>";


while (ElDiv.hasChildNodes())  
	ElDiv.removeChild(ElDiv.firstChild);
	
switch (cambio){
case 1://Para Inicio
	ElDiv.innerHTML=Inicio;
	
	break;
case 2://Para el Contenido de Sal y pimienta
	ElDiv.innerHTML=QuienesSomos;
	
	break;
case 3://Para el Contenido de Negros
	ElDiv.innerHTML=MisionyVision;
	
	break;
case 4://Para el contenido de Contactanos
	ElDiv.innerHTML=Servicios;
	
	break;
case 5://Para el contenido de Galeria
	ElDiv.innerHTML= Contactanos;
	break;

default:
	ElDiv.innerHTML=Inicio;
};
//alert(ElDiv.firstChild.src );
}



function Ocultar(elemento,no)
{

	var ElDiv = document.getElementById(elemento);


	if (ElDiv.innerHTML == "")
	{
		ElDiv.innerHTML= ArrMenus[no];
	}
	else
	{
		ArrMenus[no]=ElDiv.innerHTML;
		ElDiv.innerHTML= "";
	}



}

function Condonar (tbl,objt,id,ruta)
{
//cnd es la variable que llevará el id de la tabla de historia que se deberá actualizar
//Para quitar un renglon de una tabla y aplicar una condonación en el cierre del dia

//primero llama o contruye el objeto ajax


//luego evalua el resultado

//si el resultado es "1" borra el renglon de la tabla de lo contrario no hace nada
	
	
  	var i=objt.parentNode.parentNode.rowIndex
	
 	ajax=nuevoAjax();
 	
 	ajax.open("GET", ruta +"/condonar.php" + "?id="+id ,true);
 	ajax.onreadystatechange=function() 
 	{
  		if (ajax.readyState==4) 
  		{
  			
			if (ajax.responseText=="1")
			{
				
				document.getElementById(tbl).deleteRow(i)
			
			}
     			//cont.innerHTML = ajax.responseText
     			alert("Condonacin exitosa");
  		}
  	}
  	ajax.send(null)
  	




}

function imprimir(elid)
{
	
	var a = window.open('','','width=300,height=300');
	
	a.document.open("text/html");
	a.document.write("<link rel='stylesheet' type='text/css' href='estilos/estilos.css'>");
	a.document.write(document.getElementById(elid).innerHTML);
	
	a.document.close();
	a.print();
	a.close();
}

function nuevaVP(elidr,cate)
{


	var texto2="id=" + elidr + "&filtro=" + cate;
	var f = document.getElementById('frm_' + elidr);
	//alert(f.name);
	f.idc.value = elidr;
	f.filtro.value= cate;
	console.log('trg_'+ elidr);
	//window.open('scripts/reporte2.php?'+texto2,'reporte','directories=yes,menubar=yes,location=yes');
	window.open('','trg_'+ elidr,'directories=yes,menubar=yes,location=yes');
	f.submit();
}



function valoropt(grupo){

    inputs = document.getElementsByTagName("input");

    for(i=0,tope=inputs.length;i<tope;i++){

        e=inputs[i];

        if(e.name == grupo && e.checked == true){

            //alert("el elemento es: " +e.value);

            return e.value;

	}

  }
  return false; //si no encuentra ningún elemento activo devuelve false.

}


function verificanumeros(numerov)
{
	//Verifica que sea un formato de numero valido con un solo punto
	//quita las comas si es que existen, solo formatos de ##0.00
	var patron=/(^\d*)([.]?)(\d*$)/gi;
	return numerov.match(patron);


}

function verificafecha(fecha)
{
	//Verifica que sea un formato de fecha valido con un - o / de separación	
	var patron=/(^\d{4})([-\/])(\d{2})([-\/])(\d{2}$)/gi;
	return fecha.match(patron);

}

function compruevasuma(nombrediv, foco, total)
{
	
	var divcontinputs = document.getElementById(nombrediv);
	var ninputs = divcontinputs.getElementsByTagName('input');
	var aux=0;
	var acumulado= 0;
	var browser = navigator.appName;
	
	//hace la suma de los campos selecionados menos el del foco
	
	for(i=0;i<ninputs.length;i++)
	{
		if(ninputs[i].name!= ("c_" + foco) )
		{
	
			a=ninputs[i].name;
			try
			{
				
				if(a.split("_").length>1)
				{
					acumulado +=  Number(ninputs[i].value);
				}
			}
			catch(err)
			{
					//alert (a);
			}
		}	
		
		
	}
	
			
	var campo = document.getElementById(foco);
	var residuo = document.getElementById('vuelto');
	var global = document.getElementById(total);
	
	if(verificanumeros(campo.value)!=null)
	{
		if(Number(global.value)>(acumulado + Number(campo.value)))
		{
			//Verifica que no exceda el máximo por ese concepto
			a=campo.name
			//a=a.split("_");			
			a=rehacervalor(a);			
			//if(Number(a[1])>=Number(campo.value))
			if(Number(a)>=Number(campo.value))
			{
				acumulado += Number(campo.value);
				residuo.value= Number(global.value) - acumulado;
			}
			else
			{
				//acumulado +=Number(a[1]);
				acumulado +=Number(a);
				residuo.value= Number(global.value) - acumulado;
				//campo.value=Number(a[1]);
				campo.value=Number(a);
			}
		
		}
		else
		{
			//falta verificar si el resultante es mayor del máximo permitido por el campo
			//si excede, colocar la diferencia en el residuo
			a=campo.name
			//a=a.split("_");	
			a=rehacervalor(a);
			//alert("valor cuando el acumulado es mayor :" +a);
			//if(Number(a[1])>=(Number(global.value) - acumulado))
			if(Number(a)>=(Number(global.value) - acumulado))
			{
				
				campo.value = Number(global.value) - acumulado;				
				residuo.value= 0;
			}
			else
			{
				//campo.value = Number(a[1]);
				campo.value = Number(a);
				residuo.value= Number(global.value) - acumulado;
			}
			
			//campo.value = Number(global.value) - acumulado;			
			//residuo.value=0;			
		}
	
	}
	else
	{
		
		//coloca la diferencia en residuo
		residuo.value= Number(global.value) - acumulado;		
		//muestra mensaje de error
		alert('El número que colocó no es válido');
		//quito el valor que tenia el campo
		campo.value=0;
	
	}
	//alert ("el residuo es: " + residuo.value);

}

function rehacervalor(elnombre)
{
	//regenera un numero que fue puesto como nombre
	datos=elnombre.split("_");
	//alert("la longitud del arreglo para el texto " + elnombre  + " es: " + datos.length);
	if(datos.length>3)
	{
		datos= datos[2] + "." + datos[3];
	}
	else
	{
		datos = datos[2];
	}
	
	return datos;

}

function cambiaboton(elemento, modo, patron)
{
	var objeto;

	switch (modo)
	{
	case 'sobre':
		//alert("imagenes/" + patron + "_b_AI.png");
		objeto = elemento + "_AI";
		document.getElementById(objeto).src="imagenes/" + patron + "_b_AI.png";
		objeto = elemento + "_AC";
		//document.getElementById(objeto).src="imagenes/" + patron + "_b_AC.png";
		objeto = elemento + "_AD";
		document.getElementById(objeto).src="imagenes/" + patron + "_b_AD.png";
		objeto = elemento + "_MI";
		//document.getElementById(objeto).src="imagenes/" + patron + "_b_MI.png";
		objeto = elemento + "_MD";
		//document.getElementById(objeto).src="imagenes/" + patron + "_b_MD.png";
		objeto = elemento + "_PI";
		document.getElementById(objeto).src="imagenes/" + patron + "_b_PI.png";
		objeto = elemento + "_PC";
		//document.getElementById(objeto).src="imagenes/" + patron + "_b_PC.png";
		objeto = elemento + "_PD";
		document.getElementById(objeto).src="imagenes/" + patron + "_b_PD.png";
		objeto = elemento + "_MC";
		document.getElementById(objeto).className = patron + "_botons";
		objeto = elemento + "_TAC";
		document.getElementById(objeto).style.backgroundImage="url(imagenes/" + patron + "_b_TAC.png)";
		objeto = elemento + "_TMI";
		document.getElementById(objeto).style.backgroundImage="url(imagenes/" + patron + "_b_MI.png)";
		objeto = elemento + "_TMD";
		document.getElementById(objeto).style.backgroundImage="url(imagenes/" + patron + "_b_MD.png)";
		objeto = elemento + "_TPC";
		document.getElementById(objeto).style.backgroundImage="url(imagenes/" + patron + "_b_TPC.png)";


		break;

	case 'click':
		objeto = elemento + "_AI";
		document.getElementById(objeto).src="imagenes/" + patron + "_c_AI.png";
		objeto = elemento + "_AC";
		//document.getElementById(objeto).src="imagenes/" + patron + "_c_AC.png";
		objeto = elemento + "_AD";
		document.getElementById(objeto).src="imagenes/" + patron + "_c_AD.png";
		objeto = elemento + "_MI";
		//document.getElementById(objeto).src="imagenes/" + patron + "_c_MI.png";
		objeto = elemento + "_MD";
		//document.getElementById(objeto).src="imagenes/" + patron + "_c_MD.png";
		objeto = elemento + "_PI";
		document.getElementById(objeto).src="imagenes/" + patron + "_c_PI.png";
		objeto = elemento + "_PC";
		//document.getElementById(objeto).src="imagenes/" + patron + "_c_PC.png";
		objeto = elemento + "_PD";
		document.getElementById(objeto).src="imagenes/" + patron + "_c_PD.png";
		objeto = elemento + "_MC";
		document.getElementById(objeto).className = patron + "_botons";
		objeto = elemento + "_TAC";
		document.getElementById(objeto).style.backgroundImage="url(imagenes/" + patron + "_c_TAC.png)";
		objeto = elemento + "_TMI";
		document.getElementById(objeto).style.backgroundImage="url(imagenes/" + patron + "_c_MI.png)";
		objeto = elemento + "_TMD";
		document.getElementById(objeto).style.backgroundImage="url(imagenes/" + patron + "_c_MD.png)";
		objeto = elemento + "_TPC";
		document.getElementById(objeto).style.backgroundImage="url(imagenes/" + patron + "_c_TPC.png)";

		break;

	default:



		objeto = elemento + "_AI";
		//alert (objeto);
		document.getElementById(objeto).src="imagenes/" + patron + "_a_AI.png";
		objeto = elemento + "_AC";
		document.getElementById(objeto).src="imagenes/" + patron + "_a_AC.png";
		objeto = elemento + "_AD";
		document.getElementById(objeto).src="imagenes/" + patron + "_a_AD.png";
		objeto = elemento + "_MI";
		document.getElementById(objeto).src="imagenes/" + patron + "_a_MI.png";
		objeto = elemento + "_MD";
		document.getElementById(objeto).src="imagenes/" + patron + "_a_MD.png";
		objeto = elemento + "_PI";
		document.getElementById(objeto).src="imagenes/" + patron + "_a_PI.png";
		objeto = elemento + "_PC";
		document.getElementById(objeto).src="imagenes/" + patron + "_a_PC.png";
		objeto = elemento + "_PD";
		document.getElementById(objeto).src="imagenes/" + patron + "_a_PD.png";
		objeto = elemento + "_MC";
		document.getElementById(objeto).className = patron + "_boton";
		objeto = elemento + "_TAC";
		document.getElementById(objeto).style.backgroundImage="url(imagenes/" + patron + "_a_TAC.png)";
		objeto = elemento + "_TMI";
		document.getElementById(objeto).style.backgroundImage="url(imagenes/" + patron + "_a_MI.png)";
		objeto = elemento + "_TMD";
		document.getElementById(objeto).style.backgroundImage="url(imagenes/" + patron + "_a_MD.png)";
		objeto = elemento + "_TPC";
		document.getElementById(objeto).style.backgroundImage="url(imagenes/" + patron + "_a_TPC.png)";




		break;

	}
}

//******* RSS **************
var contador=0;
var actualizav;
var ajaxrss= new Array();
var rss = new Array();



//*******ajax del rss*****************
function nuevoAjaxrss(){
  var xmlhttp=false;
  try {
   // Creacin del objeto ajax para navegadores diferentes a Explorer
   xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
   // o bien
   try {
     // Creacin del objet ajax para Explorer
     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) {
     xmlhttp = false;
   }
  }

  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
   xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}

	function ActualizaRSS(root,loc,i)
	{
		var cont1;
		cont1 = document.getElementById(loc);
		ajaxrss[i]=nuevoAjaxrss();
		ajaxrss[i].open("GET", root, true);
		ajaxrss[i].onreadystatechange=function() {
		if (ajaxrss[i].readyState==4) {
			contador +=1;
		   cont1.innerHTML = "<br>" + ajaxrss[i].responseText
		}
		}
		ajaxrss[i].send(null);

	}

	function actualizap1(a)
	{
		//alert('poractualizar');
		roota="datosRSS.php";
		divr="RSS1";
		ActualizaRSS(roota ,divr,a);

		aztualizav = setTimeout('actualizap1(' + a + ')',10000);
	}

	function actualizap2(a)
	{
		//alert('poractualizar');
		roota="datosRSS.php";
		divr="RSS2";
		ActualizaRSS(roota + '?rss=http://sports-ak.espn.go.com/espn/rss/news' ,divr,a);

		aztualizav = setTimeout('actualizap2(' + a + ')',10000);
	}

	function actualizap3(a)
	{
		//alert('poractualizar');
		roota="datosRSS.php";
		divr="RSS3";
		ActualizaRSS(roota + '?rss=http://www.gee.com.mx/multimedia/podcast/cnnexpansion.xml' ,divr,a);

		aztualizav = setTimeout('actualizap3(' + a + ')',10000);
	}
	function actualizap4(a)
	{
		//alert('poractualizar');
		roota="datosRSS.php";
		divr="RSS4";
		ActualizaRSS(roota + '?rss=http://www.reforma.com/rss/cultura.xml' ,divr,a);

		aztualizav = setTimeout('actualizap4(' + a + ')',10000);
	}
//*****************************

//******** del scroll Objeto***************


function jsScroller (o, w, h) {
	var self = this;
	var list = o.getElementsByTagName("div");
	alert("Hay " + list.length + " <p> elementos en este documento");
	for (var i = 0; i < list.length; i++) {
		if (list[i].className.indexOf("Scroller-Container") > -1) {
			o = list[i];
		}
	}

	//Private methods
	this._setPos = function (x, y) {
		if (x < this.viewableWidth - this.totalWidth)
			x = this.viewableWidth - this.totalWidth;
		if (x > 0) x = 0;
		if (y < this.viewableHeight - this.totalHeight)
			y = 0;
		if (y > 0) y = 0;
		this._x = x;
		this._y = y;
		with (o.style) {
			left = this._x +"px";
			top  = this._y +"px";
		}
	};

	//Public Methods
	this.reset = function () {
		this.content = o;
		this.totalHeight = o.offsetHeight+100;
		this.totalWidth	 = o.offsetWidth;
		this._x = 0;
		this._y = 0;
		with (o.style) {
			left = "0px";
			top  = "0px";
		}
	};
	this.scrollBy = function (x, y) {
		this._setPos(this._x + x, this._y + y);
	};
	this.scrollTo = function (x, y) {
		this._setPos(-x, -y);
	};
	this.stopScroll = function () {
		if (this.scrollTimer) window.clearInterval(this.scrollTimer);
	};
	this.startScroll = function (x, y) {
		this.stopScroll();
		this.scrollTimer = window.setInterval(
			function(){self.scrollBy(x, y); }, 40
		);
	};
	this.swapContent = function (c, w, h) {
		o = c;
		var list = o.getElementsByTagName("div");
		for (var i = 0; i < list.length; i++) {
			if (list[i].className.indexOf("Scroller-Container") > -1) {
				o = list[i];
			}
		}
		if (w) this.viewableWidth  = w;
		if (h) this.viewableHeight = h;
		this.reset();
	};

	this.actualizap = function(a){
		//alert('poractualizar');
		roota="datosRSS.php";
		divr="RSS"+ a + a;
		//alert(roota + '?rss='+ rss[a].hxml);
		ActualizaRSS(roota + '?rss='+ this.hxml,divr,a);
		this.reset();
		aztualizav = setTimeout('rss[' + a + '].actualizap(' + a + ')',60000);
	};




	//variables
	this.content = o;
	this.viewableWidth  = w;
	this.viewableHeight = h;
	this.totalWidth	 = o.offsetWidth;
	this.totalHeight = o.offsetHeight+100;
	this.scrollTimer = null;
	this.hxml="";
	this.reset();
};

//******************************************
//********************acciones scroll ************
var scroller = null;
//window.onload = function () {
function lecturarss(){
	var el;
	for (i=1;i<=1;i++)
	{
  		el = document.getElementById("RSS" + i);
  		rss[i] = new jsScroller(el, 400, 200);
		//movertexto(i);
				switch(i)
				{
					case 1:
						rss[i].hxml="";
						break;
					case 2:
						rss[i].hxml="http://newsrss.bbc.co.uk/rss/spanish/news/rss.xml";
						break;
					case 3:
						rss[i].hxml="http://www.gee.com.mx/multimedia/podcast/cnnexpansion.xml";
						break;
					case 4:
						rss[i].hxml="http://www.reforma.com/rss/cultura.xml";
						break;

				}
				//alert(rss[i].hxml);
		movertexto(i);
  	}
/*
	for (i=1;i<=4;i++)
	{
		switch(i)
		{
			case '1':
				rss[i].hxml="";
				break;
			case '2':
				rss[i].hxml="http://newsrss.bbc.co.uk/rss/spanish/news/rss.xml";
				break;
			case '3':
				rss[i].hxml="http://www.gee.com.mx/multimedia/podcast/cnnexpansion.xml";
				break;
			case '4':
				rss[i].hxml="http://www.reforma.com/rss/cultura.xml";
				break;

		}
		movertexto(i);
  	}
*/
  	//actualizap1(1);
  	//actualizap2(2);actualizap3(3);actualizap4(4);
}

function movertexto(r)
{
	rss[r].actualizap(r);
	rss[r].startScroll(0,-1);
}
//********************************************


function conteoletras(txn, lugar)
{
	var ElDivC = document.getElementById(lugar);
	//	alert (txn.length);
	ElDivC.innerHTML = txn.length + " de 1000";

}


function validafechaphp(f1,f2)
{
//valida que la fecha f1 sea menor que la f2 en el formato de php aaaa-mm-dd
	validado=false;
	a1=f1.split("-");
	a2=f2.split("-");
        
        
	if( a1[0] < a2[0])
	{
		validado=true;	
	}
	else if(a1[0] = a2[0])
	{
		if( a1[1]<a2[1] )
		{		
			validado=true;
		}
		else if(a1[1]=a2[1])
		{
			
			if( a1[2]<a2[2] )
			{
						
				validado=true;			
						
			}
			
		}
		
	}
	



	return validado;
}





function actualizaarchivos(lista,campo)
{
  document.getElementById(campo).value=lista;
  larchivosb=lista;

}


function quitard(l,id)
	{
		var rehacer;
		switch(l)
		{
		case 1:
			listain = document.getElementById("lsti").value;			
			break;
		case 2:
			listain = document.getElementById("lsto").value;
					
			break;
		}

		rehacer = listain.split("|");
		listain="";
		for(d in rehacer)
		{
			//alert (rehacer[d] + "!=" + id);
			if(rehacer[d]!=id )
			{
				//alert (rehacer[d] + "!=" + id);
				if(rehacer[d]!="")
				{
					listain += rehacer[d] + "|";
			}	}		
		}
		
		//alert(listain);
		switch(l)
		{
		case 1:
			document.getElementById("lsti").value = listain;
			//alert(document.getElementById("lsti").value);
			break;
		case 2:
			document.getElementById("lsto").value = listain;
			//alert(document.getElementById("lsto").value);					
			break;
		}		
		
	
	}
	
//para imprimir texto de opciones
function imprimirv(aimprimir) 
{
 var v;
 var aux;
 aux="<html><head><title>Padilla & Bujalil S.C.</title><head><link rel='stylesheet' type='text/css' href='estilos/estilos.css'></head><body>" + document.getElementById(aimprimir).innerHTML + "</body></html>";
 
  v = window.open('');
  v.document.write(aux);
  v.print();
  //v.close();
  
}	


//para factura libre, le tura de inputs dentro de tablas
function leerinputs(idt)
{

	var envio="";
	//var frm = document.cfdilibre;
	var frm = document.getElementById(idt);
	if(idt=='')
	{
		//alert(frm.tagName);
		//alert(frm.childNodes.length);
		alert(idt);
	}
	for(i=0; i< frm.childNodes.length; ++i)
	{
	
		var obj = frm.childNodes[i];
		//alert(obj.tagName);
		
		if(obj.tagName=='TBODY')
		{
			//alert('ok');
			
			//alert(obj.childNodes.length);
			
			//alert('inicio for');
			for(j=0; j< obj.childNodes.length; ++j)
			{
				var ttr = obj.childNodes[j];
				if(idt=='')
				{				
					alert("longitud tr: " + ttr.childNodes.length + " iteracion "  + j);
				}
				if(ttr.childNodes.length > 0)
				{
					//alert("es mayor ");
					var ttd = ttr.childNodes[1];
					if(idt=='')
					{
						alert('longitud td[1]:' + ttd.childNodes.length);
						//alert(navigator.appName);
					}
					if(ttd.childNodes.length>1)
					{
						for(x=1; x< ttd.childNodes.length; ++x)
						{

							if(navigator.appName=='Microsoft Internet Explorer')
							{
								var inp = ttd.childNodes[x-1];
							}
							else
							{
								var inp = ttd.childNodes[x];
							}
						envio = envio + inp.name + "|" + inp.value + "*";
							
						
						}

					}
					else
					{
						if(navigator.appName=='Microsoft Internet Explorer')
						{
							var inp = ttd.childNodes[0];
						}
						else
						{
							var inp = ttd.childNodes[1];
						}
						envio = envio + inp.name + "|" + inp.value + "*";
					}
					//var inp = ttd.childNodes[1];
					if(idt=='')
					{
						alert(inp.name + " = " + inp.value);
					}	
					//envio = envio + inp.name + "|" + inp.value + "*";
					
				}
				
				//alert(ttr.tagName + " " + j);
				//var ttd = ttr.childNodes[1];
				//var inp = ttd.childNodes[0];
				//alert(inp.tagName);
				//alert('fin de ' + j);
				
			
			}
			//alert('termino for');

		}
		
	

	}
	return envio;
}
function calcular()
	{
		//alert('calculo');
		subtotal = document.getElementById('125_Cant').value * document.getElementById('131_PrecMX').value;
		
		document.getElementById('132_ImporMX').value = subtotal;
		document.getElementById('161_BaseImp').value = subtotal;
		document.getElementById('191_TotNeto').value = subtotal;
		document.getElementById('192_TotBruto').value = subtotal;		
		document.getElementById('subtotalr').value = subtotal;
		
		ivac= (document.getElementById('iva').value/100) * subtotal;
		//alert(ivac);
		
		document.getElementById('165_ImporImp').value = ivac;
		document.getElementById('189_TotImpT').value = ivac;
		document.getElementById('190_TotImp').value = ivac;
		document.getElementById('197_MonImpT').value = ivac;		
		document.getElementById('impuestor').value = ivac;
		
		document.getElementById('193_Importe').value = subtotal + ivac;
		document.getElementById('totalr').value = subtotal + ivac;
		
	
	}

function MostrarTer(lista)
{
	//alert(lista.options[lista.selectedIndex].text.substring(0,3));
	if(lista.options[lista.selectedIndex].text.substring(0,3)=="XCT")
	{
		//alert("cumple");
		document.getElementById('divpredial').style.display='';
		document.getElementById('tercerosshow').style.display='';		
	}
	else
	{
		//alert("no cumple");
		document.getElementById('divpredial').style.display='none';
		document.getElementById('tercerosshow').style.display='none';
		document.getElementById('predial').value='';
	}
}	

function addTer(lista, predial,accion,ren)
{
//idter: id del tercero a listar
//nomter: nombre del tercero
//predial: cuenta precial a colocar
//accion: que hacer en la tabla, agregar:1, borrar: 2: borrar todos:3
//ren: renglon independiente a borrar



	switch(accion)
	{
	case 1:

		idter= lista.options[lista.selectedIndex].value;
		nomter=lista.options[lista.selectedIndex].text;
		
		tr=document.createElement("tr");
		td1=document.createElement("td"); 		
		td2=document.createElement("td");
		td3=document.createElement("td");
		
		td1.innerHTML = nomter;
		td2.innerHTML = " <input type='text' name='" + idter + "' id='" + idter + "' onChange=\"document.getElementById('187_Campo0_" + idter + "').value='" + idter + "~' + document.getElementById('predial').value + '~' + this.value;\"><input type='hidden' name='187_Campo0_" + idter + "' id='187_Campo0_" + idter + "' value='" + idter + "~' + document.getElementById('predial').value + '~'>";
		td3.innerHTML = " <input type='button' value='X' onClick=\"addTer(0, 0,2,this.parentNode.parentNode.rowIndex);if(document.getElementById('tterceros').rows.length<=1){document.getElementById('3_Complemento').value='';};\">";
		
		tr.appendChild(td1);  
		tr.appendChild(td2);
		tr.appendChild(td3); 
		
		document.getElementById('tterceros').appendChild(tr);

		break;
	case 2:
		document.getElementById('tterceros').deleteRow(ren);
		break;
	case 3:

		break;
	}


}	

function leerinputster(idt)
{

	var envio="";
	//var frm = document.cfdilibre;
	var frm = document.getElementById(idt);
	if(idt=='')
	{
		//alert(frm.tagName);
		alert(frm.childNodes.length);
		alert(idt);
	}
	for(i=0; i< frm.childNodes.length; ++i)
	{
	
		var obj = frm.childNodes[i];
		//alert(obj.tagName);
		
		if(obj.tagName=='TR')
		{
			
			
			alert(obj.childNodes.length);
			
			//alert('inicio for');
			for(j=0; j< obj.childNodes.length; j++)
			{
				var ttd = obj.childNodes[j];
				//alert('td no.' + j + ', longitud:' + ttd.childNodes.length);
				if(ttd.childNodes.length == 3)
				{

							x=2;
							//alert(ttd.childNodes[x].tagName);
							if(navigator.appName=='Microsoft Internet Explorer')
							{
								var inp = ttd.childNodes[x-1];
							}
							else
							{
								var inp = ttd.childNodes[x];
							}
							envio = envio + inp.name + "|" + inp.value + "*";
							//alert(envio);
						
					

				}


			}
				

			
		}

	}
		
	

	
	return envio;
}

function addArea2(id1,id2) {
	//alert('prueba');
	area1 = new nicEditor({fullPanel : true}).panelInstance(id1);
	area2 = new nicEditor({fullPanel : true}).panelInstance(id2);
}
function removeArea2(id1,id2) {
	area1.removeInstance(id1);
	area2.removeInstance(id2);
}


//
function ocultardivmail(id)
{
	document.getElementById(id).style.display="none";
	document.getElementById(id).innerHTML="Enviando...";	

}

function mostrarrdivmail(id,event)
{
	//alert("x:" + event.clientX + " y:" + event.clientY);
	document.getElementById(id).style.display="";
	document.getElementById(id).innerHTML="Enviando...";	
	document.getElementById(id).style.top=event.clientY + document.body.scrollTop-50;
	document.getElementById(id).style.left=event.clientX-150;
}

function actualizamailsp(c,ac)
{
	ln="";
	listae=document.getElementById('masivo').value;
	a=c.split("_");
	//alert(a[1]);
	
	if(ac==0)
	{
		
		l = listae.split("|");	
		for(i=0;i<l.length-1;i++)
		{
			if(l[i]!=a[1] )
			{
				ln = ln + l[i] + "|";
			}
		
		}
	}
	else
	{
		ln = listae + a[1] + "|";
	}
	
	document.getElementById('masivo').value = ln;	
}

function intercambioTablas(i, idtablaOrig, tbodyFin) {

	var tabla = document.getElementById(idtablaOrig);
	var columnas = tabla.rows[i].getElementsByTagName("td");
	var idinm = columnas[0].innerHTML;
	var inmuebleS = columnas[1].getElementsByTagName("font");
	var inmueble = inmuebleS[0].innerHTML;

   	var newRow = document.createElement("tr");
   	var td1 = document.createElement("td");
	td1.innerHTML = idinm;
	var td2 = document.createElement("td");
	var texto = "intercambioTablas(this.parentNode.parentNode.rowIndex ,\"SelectTable\",\"TbodyList\")";
	td2.innerHTML = "<p onClick='" + texto + "'><font size='2'>" + inmueble + "</font></p>";
	newRow.appendChild(td1);
	newRow.appendChild(td2);
	
	document.getElementById(tbodyFin).appendChild(newRow);
	document.getElementById(idtablaOrig).deleteRow(i);

}

function reporteI(idtablaFin) {
	var tabla = document.getElementById(idtablaFin);
	var idArray = "";
	for(var i=2; i < tabla.rows.length; i++){
		var columnas = tabla.rows[i].getElementsByTagName("td");
			var idinm = columnas[0].innerHTML;
			idArray = idArray + idinm + "," ;
	}
	idArray = idArray.substring(0,idArray.length -1);
	cargarSeccion('scripts/reportes/pendientesSelect.php','contenido','ver=1&idinmuebles=' + idArray);
}

function reporteP(idtablaFin) {
	var tabla = document.getElementById(idtablaFin);
	var idArray = "";
	for(var i=2; i < tabla.rows.length; i++){
		var columnas = tabla.rows[i].getElementsByTagName("td");
			var idinm = columnas[0].innerHTML;
			idArray = idArray + idinm + "," ;
	}
	idArray = idArray.substring(0,idArray.length -1);
	cargarSeccion('scripts/reportes/pendientesSelectP.php','contenido','ver=1&idpropietarios=' + idArray);
}

function calculoTotal(cant,accion)
{
	valor= parseFloat(document.getElementById('totalCant').value);
	//alert(valor);
	//alert(c);
	
	if(accion==0){
		valor = valor - cant;
	}else{
		valor = valor + cant;
	}

	valor = valor.toFixed(2);
	//alert(new Intl.NumberFormat("en-US").format(valor));

	document.getElementById('total').innerHTML = new Intl.NumberFormat("en-US").format(valor);
	document.getElementById('totalCant').value = valor;	
}

function validarCURP()
{
	var curp = document.getElementById('curp').value;
	var fechanacimiento = document.getElementById('fechanacimiento').value;
	var rfc = document.getElementById('rfc').value;
	var fechacurp = curp.substr(4,2) + "-" + curp.substr(6,2) + "-" + curp.substr(8,2);
	var fecharfc = rfc.substr(4,2) + "-" + rfc.substr(6,2) + "-" + rfc.substr(8,2);
	var fechana = fechanacimiento.substr(2,8);
	if(fechacurp == fechana && fechacurp == fecharfc){
		return true;
	}else{
		alert("Revisar que el RFC, CURP y Fecha de nacimiento sean correctas");
		return false;
	}
}

function validarCURPFiador()
{
	var curp = document.getElementById('curp').value;
	var rfc = document.getElementById('rfc').value;
	var fechacurp = curp.substr(4,2) + "-" + curp.substr(6,2) + "-" + curp.substr(8,2);
	var fecharfc = rfc.substr(4,2) + "-" + rfc.substr(6,2) + "-" + rfc.substr(8,2);
	if(fechacurp == fecharfc){
		return true;
	}else{
		alert("Revisar que el RFC y CURP sean correctos");
		return false;
	}
}

function acccionInquilinos(thiss) {
	ok=0;
	This = formInquilino;
	aus = This.rfc.value;
	fech = This.fechanacimiento.value; 
	
	if(aus.length==12){
		if(This.nombre.value != '' && This.fechanacimiento.value!='' && fech.length==10  ){
			ok=1
		}
		else{
			if (This.nombre.value == '') {alert("El nombre del inquilino es un dato necesario para poder continuar.")}
			else if (This.fechanacimiento.value=='') {alert("Es necesaria la fecha de nacimiento.")}
			else if (fech.length!=10) {alert("El texto de la fecha es incorrecto.")}
		}
	}else{
		if( This.nombre.value != '' && 
			This.apaterno.value!='' && 
			This.amaterno.value!='' && 
			This.curp.value!='' && 
			This.fechanacimiento.value!='' && 
			validarCURP()==true && 
			fech.length==10 )
		{
				ok=1
		}else{
			if (This.nombre.value == '') {alert("El nombre del inquilino es un dato necesario para poder continuar.")}
			else if (This.apaterno.value == '') {alert("El apellido paterno del inquilino es un dato necesario para poder continuar.")}
			else if (This.amaterno.value == '') {alert("El apellido materno del inquilino es un dato necesario para poder continuar.")}
			else if (This.curp.value=='') {alert("Es necesario el CURP del inquilino.")}
			else if (This.fechanacimiento.value=='') {alert("Es necesaria la fecha de nacimiento.")}
			else if (fech.length!=10) {alert("El texto de la fecha es incorrecto.")}
		}
	};   
	if(ok==1) { 
		if(thiss.value=='Actualizar')
			if(This.priveditar.value==1){ 
				thiss.disabled=true;
				cargarSeccion('scripts/catalogos/inquilinos.php','contenido','accion=' + This.accion.value + 
					'&id=' + This.ids.value + 
					'&nombre=' + This.nombre.value + 
					'&apaterno=' + This.apaterno.value + 
					'&amaterno=' + This.amaterno.value + 
					'&nombre2=' + This.nombre2.value + 
					'&nombrenegocio=' + This.nombernegocio.value + 
					'&tel=' + This.tel.value + 
					'&rfc=' + This.rfc.value + 
					'&direccionf=' + This.direccionf.value + 
					'&email=' + This.email.value +  
					'&email1=' + This.email1.value + 
					'&email2=' + This.email2.value + 
					'&delegacion=' + This.delegacion.value  + 
					'&colonia=' + This.colonia.value  + 
					'&cp=' + This.cp.value  + 
					'&idestado=' + This.idestado.value + 
					'&noexterior=' + This.noexterior.value  + 
					'&nointerior=' + This.nointerior.value  + 
					'&localidad=' + This.localidad.value  + 
					'&referencia=' + This.referencia.value  + 
					'&pais=' + This.pais.value + 
					'&callei=' + This.callei.value  + 
					'&delmuni=' + This.delmuni.value + 
					'&coloniai=' + This.coloniai.value + 
					'&cpi=' + This.cpi.value + 
					'&idestadoi=' + This.idestadoi.value + 
					'&idactividadeconomica=' + This.idactividadeconomica.value + 
					'&idgirocomercial=' + This.idgirocomercial.value + 
					'&idnacionalidad=' + This.idnacionalidad.value + 
					'&curp=' + document.getElementById('curp').value + 
					'&fechanacimiento=' + document.getElementById('fechanacimiento').value + 
					'&idc_usocfdi=' + This.idc_usocfdi.value + 
					'&tipofactura=' + This.tipofactura.value)
			}else{
				alert("No cuentas con permiso para editar la información en este catálogo.");
			}
		else if(thiss.value=='Agregar')
			if(This.privagregar.value==1){ 
				thiss.disabled=true;
				cargarSeccion('scripts/catalogos/inquilinos.php','contenido','accion=' + This.accion.value + 
					'&id=' + This.ids.value + 
					'&nombre=' + This.nombre.value + 
					'&apaterno=' + This.apaterno.value + 
					'&amaterno=' + This.amaterno.value + 
					'&nombre2=' + This.nombre2.value + 
					'&nombrenegocio=' + This.nombernegocio.value + 
					'&tel=' + This.tel.value + 
					'&rfc=' + This.rfc.value + 
					'&direccionf=' + This.direccionf.value+ 
					'&email=' + This.email.value +  
					'&email1=' + This.email1.value + 
					'&email2=' + This.email2.value + 
					'&delegacion=' + This.delegacion.value  + 
					'&colonia=' + This.colonia.value  + 
					'&cp=' + This.cp.value  + 
					'&idestado=' + This.idestado.value + 
					'&noexterior=' + This.noexterior.value  + 
					'&nointerior=' + This.nointerior.value  + 
					'&localidad=' + This.localidad.value  + 
					'&referencia=' + This.referencia.value  + 
					'&pais=' + This.pais.value + 
					'&callei=' + This.callei.value  + 
					'&delmuni=' + This.delmuni.value + 
					'&coloniai=' + This.coloniai.value + 
					'&cpi=' + This.cpi.value + 
					'&idestadoi=' + This.idestadoi.value + 
					'&idactividadeconomica=' + This.idactividadeconomica.value + 
					'&idgirocomercial=' + This.idgirocomercial.value + 
					'&idnacionalidad=' + This.idnacionalidad.value + 
					'&curp=' + document.getElementById('curp').value + 
					'&fechanacimiento=' + document.getElementById('fechanacimiento').value + 
					'&idc_usocfdi=' + This.idc_usocfdi.value  + 
					'&tipofactura=' + This.tipofactura.value)
			}else{
				alert("No cuentas con permiso para agregar en este catálogo.");
			}
	};
}

function accionFiador(thiss) {
	This = formFiador;
	tamrfc=rfc.value;
	if(This.nombre.value!='' && tamrfc.length==13 && validarCURPFiador()==true){ 
		if(thiss.value=='Actualizar')
			if(This.priveditar.value==1){ 
				thiss.disabled=true;
				cargarSeccion('scripts/catalogos/fiador.php','contenido','accion=' + This.accion.value + 
					'&id=' + This.ids.value + 
					'&nombre=' + This.nombre.value + 
					'&apaterno=' + This.apaterno.value + 
					'&amaterno=' + This.amaterno.value + 
					'&nombre2=' + This.nombre2.value + 
					'&identificacion=' + This.identificacion.value + 
					'&direccion=' + This.direccion.value + 
					'&datosinmueble=' + This.datosinmueble.value + 
					'&tel=' + This.tel.value  + 
					'&delegacion=' + This.delegacion.value  + 
					'&colonia=' + This.colonia.value  + 
					'&cp=' + This.cp.value  + 
					'&idestado=' + This.idestado.value + 
					'&emailf=' + This.emailf.value + 
					'&curp=' + This.curp.value + 
					'&rfc=' + This.rfc.value )
			}else{
				alert("No cuentas con permiso para editar la información en este catálogo.");
			}
		else if(thiss.value=='Agregar')
			if(This.privagregar.value==1){ 
				thiss.disabled=true;
				cargarSeccion('scripts/catalogos/fiador.php','contenido','accion=' + This.accion.value + 
					'&id=' + This.ids.value + 
					'&nombre=' + This.nombre.value + 
					'&apaterno=' + This.apaterno.value + 
					'&amaterno=' + This.amaterno.value + 
					'&nombre2=' + This.nombre2.value + 
					'&identificacion=' + This.identificacion.value + 
					'&direccion=' + This.direccion.value + 
					'&datosinmueble=' + This.datosinmueble.value + 
					'&tel=' + This.tel.value  + 
					'&delegacion=' + This.delegacion.value  + 
					'&colonia=' + This.colonia.value  + 
					'&cp=' + This.cp.value  + 
					'&idestado=' + This.idestado.value + 
					'&emailf=' + This.emailf.value + 
					'&curp=' + This.curp.value + 
					'&rfc=' + This.rfc.value)
			}else{
				alert("No cuentas con permiso para agregar en este catálogo.");
			}
	}; 
	if(This.nombre.value!='' &&  tamrfc.length==12){ 
		if(thiss.value=='Actualizar')
			if(This.priveditar.value==1){ 
				thiss.disabled=true;
				cargarSeccion('scripts/catalogos/fiador.php','contenido','accion=' + This.accion.value + 
					'&id=' + This.ids.value + 
					'&nombre=' + This.nombre.value + 
					'&apaterno=' + This.apaterno.value + 
					'&amaterno=' + This.amaterno.value + 
					'&nombre2=' + This.nombre2.value + 
					'&identificacion=' + This.identificacion.value + 
					'&direccion=' + This.direccion.value + 
					'&datosinmueble=' + This.datosinmueble.value + 
					'&tel=' + This.tel.value  + 
					'&delegacion=' + This.delegacion.value  + 
					'&colonia=' + This.colonia.value  + 
					'&cp=' + This.cp.value  + 
					'&idestado=' + This.idestado.value + 
					'&emailf=' + This.emailf.value + 
					'&curp=' + This.curp.value + 
					'&rfc=' + This.rfc.value )
			}else{
				alert("No cuentas con permiso para editar la información en este catálogo.");
			}
		else if(thiss.value=='Agregar')
			if(This.privagregar.value==1){ 
				thiss.disabled=true;
				cargarSeccion('scripts/catalogos/fiador.php','contenido','accion=' + This.accion.value + 
					'&id=' + This.ids.value + 
					'&nombre=' + This.nombre.value + 
					'&apaterno=' + This.apaterno.value + 
					'&amaterno=' + This.amaterno.value + 
					'&nombre2=' + This.nombre2.value + 
					'&identificacion=' + This.identificacion.value + 
					'&direccion=' + This.direccion.value + 
					'&datosinmueble=' + This.datosinmueble.value + 
					'&tel=' + This.tel.value  + 
					'&delegacion=' + This.delegacion.value  + 
					'&colonia=' + This.colonia.value  + 
					'&cp=' + This.cp.value  + 
					'&idestado=' + This.idestado.value + 
					'&emailf=' + This.emailf.value + 
					'&curp=' + This.curp.value + 
					'&rfc=' + This.rfc.value)
			}else{
				alert("No cuentas con permiso para agregar en este catálogo.");
			}
	}else{
		if (This.nombre.value=='') {alert("El nombre del Obligado Solidario es necsario para poder continuar.")}
		else if (tamrfc.length!=12 && tamrfc.length!=13) {alert("Verifica que el RFC sea correcto.")}
	};
}

function accionDuenios(thiss) {
	This = formDuenios;
	if(This.nombre.value!='' ){ 
		if(thiss.value=='Actualizar')
			if(This.priveditar.value==1){ 
				thiss.disabled = true;
				cargarSeccion('scripts/catalogos/duenios.php','contenido','accion=' + This.accion.value + 
					'&id=' + This.ids.value + 
					'&nombre=' + This.nombre.value + 
					'&apaterno=' + This.apaterno.value + 
					'&amaterno=' + This.amaterno.value + 
					'&nombre2=' + This.nombre2.value +  
					'&usuario=' + This.usuario.value + 
					'&pwd=' + This.pwd.value   + 
					'&rfcd=' + This.rfcd.value  + 
					'&banco=' + This.banco.value  + 
					'&titularbanco=' + This.titularbanco.value  + 
					'&cuenta=' + This.cuenta.value  + 
					'&clabe=' + This.clabe.value  + 
					'&called=' + This.called.value  + 
					'&noexteriord=' + This.noexteriord.value  + 
					'&nointeriord=' + This.nointeriord.value  + 
					'&coloniad=' + This.coloniad.value  + 
					'&delmund=' + This.delmund.value  + 
					'&estadod=' + This.estadod.value  + 
					'&paisd=' + This.paisd.value  + 
					'&cpd=' + This.cpd.value  +  
					'&honorarios=' + This.honorarios.value + 
					'&iva=' + This.iva.value + 
					'&callep=' + This.callep.value  + 
					'&noexteriorp=' + This.noexteriorp.value  + 
					'&nointeriorp=' + This.nointeriorp.value  + 
					'&coloniap=' + This.coloniap.value  + 
					'&delmunp=' + This.delmunp.value  + 
					'&estadop=' + This.estadop.value  + 
					'&paisp=' + This.paisp.value  + 
					'&cpp=' + This.cpp.value   +
					'&curp=' + This.curp.value  +
					'&tell=' + This.tell.value +
					'&maill=' + This.maill.value +
					'&idasesor=' + This.idasesor.value +
					'&diasapagar=' + This.diasapagar.value  )
			}else{
				alert("No cuentas con permiso para editar la información en este catálogo.");
			}
		else if(thiss.value=='Agregar')
			if(This.privagregar.value==1){ 
				thiss.disabled = true;
				cargarSeccion('scripts/catalogos/duenios.php','contenido','accion=' + This.accion.value +
					'&id=' + This.ids.value +
					'&nombre=' + This.nombre.value +
					'&apaterno=' + This.apaterno.value +
					'&amaterno=' + This.amaterno.value +
					'&nombre2=' + This.nombre2.value +
					'&usuario=' + This.usuario.value +
					'&pwd=' + This.pwd.value    +
					'&rfcd=' + This.rfcd.value  +
					'&banco=' + This.banco.value  +
					'&titularbanco=' + This.titularbanco.value  +
					'&cuenta=' + This.cuenta.value  +
					'&clabe=' + This.clabe.value  +
					'&called=' + This.called.value  +
					'&noexteriord=' + This.noexteriord.value  +
					'&nointeriord=' + This.nointeriord.value  +
					'&coloniad=' + This.coloniad.value  +
					'&delmund=' + This.delmund.value  +
					'&estadod=' + This.estadod.value  +
					'&paisd=' + This.paisd.value  +
					'&cpd=' + This.cpd.value   +
					'&honorarios=' + This.honorarios.value +
					'&iva=' + This.iva.value +
					'&callep=' + This.callep.value  +
					'&noexteriorp=' + This.noexteriorp.value  +
					'&nointeriorp=' + This.nointeriorp.value  +
					'&coloniap=' + This.coloniap.value  +
					'&delmunp=' + This.delmunp.value  +
					'&estadop=' + This.estadop.value  +
					'&paisp=' + This.paisp.value  +
					'&cpp=' + This.cpp.value   +
					'&curp=' + This.curp.value +
					'&tell=' + This.tell.value +
					'&maill=' + This.maill.value +
					'&idasesor=' + This.idasesor.value +
					'&diasapagar=' + This.diasapagar.value )
			}else{
				alert("No tienes permisos para agregar en este catálogo");
			}
	}else{
		alert("Debes indicar el nombre del duenio para poder continuar.");
	};
}