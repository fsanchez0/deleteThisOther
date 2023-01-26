<?php
  include_once('../general/conexion.php');
  include '../general/funcionesformato.php';
  setlocale(LC_MONETARY, 'en_US');
  ini_set("display_errors",0);
  $arrayMeses=array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic","Ene");
  @$idPropietario=$_REQUEST["id"];
  @$fechagen = $_REQUEST["f"];
    $arregloColores=array("red","#EEF440","blue","gray","teal","maroon","purple","#F76663","#3EB8B2","#96D856","#FFC4FF","#0B6121","#BCA9F5","navy","fuchsia","aqua");


    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=EdoCuenta$idPropietario.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
  
    echo "<center><h1>Estado de cuenta del Propietario No. $idPropietario</h1></center>";
    //------------------ Informacion Periodo ----------------------
        $fechamenos = @date('Y-m-') . "01";
		//$fechamenos = "2014-09-01";
		
		
		$fechamenos = $fechagen;
		//info del dueño
		$periodo = "";
		//Calculo del periodo para colocarlo en  el titulo
		//echo substr($fechamenos,5,2);
		switch (substr($fechamenos,5,2))
		{
			case 1:
				$anio = (int)substr($fechamenos,0,4);
				
				$periodo = "DICIEMBRE " . ($anio - 1) . " - ENERO $anio";
				break;
			case 2:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "ENERO - FEBRERO $anio";			
				break;
			case 3:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "FEBRERO - MARZO $anio";				
				break;
			case 4:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "MARZO - ABRIL $anio";				
				break;	
			case 5:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "ABRIL - MAYO $anio";				
				break;
			case 6:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "MAYO - JUNIO $anio";				
				break;
			case 7:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "JUNIO - JULIO $anio";				
				break;
			case 8:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "JULIO - AGOSTO $anio";				
				break;
			case 9:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "AGOSTO - SEPTIEMBRE $anio";				
				break;
			case 10:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "SEPTIEMBRE - OCTUBRE $anio";				
				break;
			case 11:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "OCTUBRE - NOVIEMBRE $anio";				
				break;
			case 12:
				$anio = (int)substr($fechamenos,0,4);
				$periodo = "NOVIEMBRE - DICIEMBRE $anio";				
				break;	
		}
    //-----------------------------------------------
    echo "<center><h2>Periodo $periodo</h2></center>";
    
    //------------------ Informacion Duenio ----------------------
        $sql = "select * from duenio where idduenio = " . $idPropietario;
		$operacion = mysql_query($sql);
		$row = mysql_fetch_array($operacion);
    //------------------------------------------------------------
    
    //---------------- Tabla Inforamcion Duenio ------------------
        echo "<table border=0 style='border:1px solid black;'> "; 
            echo "<tr style='background-color:#5C9CCF; font-color:white;border-bottom:1px solid black;'>";
                echo "<th>Cliente</th>";
            echo "</tr>";
            echo "<tr>";
                $nombre = utf8_decode($row["nombre"] . " " . $row["nombre2"]  . " " . $row["apaterno"]  . " " . $row["amaterno"]);
                echo "<td><b>$nombre</b></td>";
            echo "</tr>";
            echo "<tr>";
                $rfc = utf8_decode($row["rfcd"]);
                echo "<td>$rfc</td>";
            echo "</tr>";
            echo "<tr>";
                $calle = utf8_decode("Calle " . $row["called"] . " No." . $row["noexteriord"] . " " . $row["nointeriord"] . " " );
                echo "<td>$calle</td>";
            echo "</tr>";
            echo "<tr>";
                $col = utf8_decode("Col." . $row["coloniad"] . ", " . $row["delmund"] . " " . $row["estadod"] . " C.P. " . $row["cpd"] );
                echo "<td>$col</td>";
            echo "</tr>";

        echo "</table>";
    //-------------------------------------------------------------
    echo "<br>";
    //---------------- Tabla Conceptos ----------------------------
        echo "<table border=1>";
            
            echo "<tr style='background-color:#5C9CCF; font-color:white;'>";
                echo "<th>Descripcion</th>";
                echo "<th>Importe</th>";
            echo "</tr>";

            //***************** Abonos ******************/

            //--------------- Informacion Marcados y Generados ---------------
                
                //consulta para marcados y generados de esta ocación
                //$sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<>0  and isnull(fechagen)=true and reportado = 1  and d.idduenio=" . $idPropietario . " ";
                $sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe>0  and fechagen='" . $fechagen . "'  and reportado = 1 and e.idtipocobro>0  and d.idduenio=" . $idPropietario . " and traspaso = 0 ";
                $sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
                $operacionc = mysql_query($sqlc);

                $np=0;
                $renp=0;
                $idcontrato =0;
                while($rowc = mysql_fetch_array($operacionc))	
                {
                    if($rowc["idcontrato"] != $idcontrato)
                    {
                        
                        $idcontrato = $rowc["idcontrato"];
                        $duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
                        //$reporte .= "</table><br><br>";
                        $clase = "";
                        $inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
                        $inmueble = utf8_decode($inmueble);
                        echo "<tr>";
                            echo "<td><h3>$inmueble</h3></td>";
                        echo "</tr>";            
                    }


                
                    //$np +=(double)$c[0];
                    $np +=$rowc["importe"] + $rowc["iva"];
                    $sub = $rowc["importe"] + $rowc["iva"];
                    
                    //$np +=$rowc["importe"];// + $rowc["iva"];
                    //$sub = $rowc["importe"];// + $rowc["iva"];
                    $renp +=1;

                    $notaedo = utf8_decode($rowc["notaedo"]);
                    $importe = "$ ".number_format($sub, 2, '.', ',');
                        
                    echo "<tr>";
                        echo "<td>$notaedo</td>";
                        echo "<td>$importe</td>";
                    echo "</tr>"; 
                    

                }

                $sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe>0  and fechagen='" . $fechagen . "'    and reportado = 1 and e.idtipocobro=0  and d.idduenio=" . $idPropietario . " and traspaso = 0 ";
                $sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
                $operacionc = mysql_query($sqlc);
                
                while($rowc = mysql_fetch_array($operacionc))	
                {

                    if($rowc["idcontrato"] != $idcontrato)
                    {
                        
                        $idcontrato = $rowc["idcontrato"];
                        $duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
                        //$reporte .= "</table><br><br>";
                        $clase = "";
                        $inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
                        $inmueble = utf8_decode($inmueble);

                        echo "<tr>";
                            echo "<td><h3>$inmueble</h3></td>";
                        echo "</tr>";
            
                    }


                
                    //$np +=(double)$c[0];
                    $np +=$rowc["importe"] + $rowc["iva"];
                    $sub = $rowc["importe"] + $rowc["iva"];
                    
                    //$np +=$rowc["importe"];// + $rowc["iva"];
                    //$sub = $rowc["importe"];// + $rowc["iva"];
                    $renp +=1;                        
                    
                    $notaedo = utf8_decode($rowc["notaedo"]);
                    $importe = "$ ".number_format($sub, 2, '.', ',');
                        
                    echo "<tr>";
                        echo "<td>$notaedo</td>";
                        echo "<td>$importe</td>";
                    echo "</tr>"; 

                }

                $sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, i.idinmueble as idi from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble  and importe>0  and fechagen='" . $fechagen . "'  and reportado = 1 and idcontrato =0 and d.idduenio=" . $idPropietario . " and traspaso = 0 ";
                $sqlc .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";
                $operacionc = mysql_query($sqlc);	
            
                $idi = 0;
                while($rowc = mysql_fetch_array($operacionc))	
                {
                    if($rowc["idi"] != $idi)
                    {
                        
                        $idi = $rowc["idi"];
                        $duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
                        //$reporte .= "</table><br><br>";
                        $clase = "";
                        $inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
                        $inmueble = utf8_decode("Cargos directos ".$inmueble);

                        echo "<tr>";
                            echo "<td><h3>$inmueble</h3></td>";
                        echo "</tr>";
            
                    }

                
                    //$np +=(double)$c[0];
                    $np +=$rowc["importe"] + $rowc["iva"];
                    $sub = $rowc["importe"] + $rowc["iva"];
                    
                    //$np +=$rowc["importe"];// + $rowc["iva"];
                    //$sub = $rowc["importe"];// + $rowc["iva"];			
                    $renp +=1;
                        
                    $notaedo = utf8_decode($rowc["notaedo"]);
                    $importe = "$ ".number_format($sub, 2, '.', ',');
                        
                    echo "<tr>";
                        echo "<td>$notaedo</td>";
                        echo "<td>$importe</td>";
                    echo "</tr>";

                }

            //-----------------------------------------------------------------

            //--------------- Informacion Notas de Credito --------------------
                $sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e where d.idduenio = e.idduenio and importe<>0  and fechagen='" . $fechagen . "'  and reportado = 1 and d.idduenio=" . $idPropietario . " and notacredito = true ";
                $sqlc .= " order by e.idduenio, e.idcontrato,idedoduenio";
                $operacionc = mysql_query($sqlc);

                $idcontrato=0;
                while($rowc = mysql_fetch_array($operacionc))	
                {
                    if($rowc["idcontrato"] != $idcontrato)
                    {                        
                        $idcontrato = 1;
                        echo "<tr>";
                            echo "<td><b>Notas de Crédito</b></td>";
                        echo "</tr>";
                    }

                
                    //$np +=(double)$c[0];
                    $np +=$rowc["importe"] + $rowc["iva"];
                    $sub = $rowc["importe"] + $rowc["iva"];
                    
                    //$np +=$rowc["importe"];// + $rowc["iva"];
                    //$sub = $rowc["importe"];// + $rowc["iva"];			
                    $renp +=1;
                        
                    $notaedo = utf8_decode("(Nota de credto) " . $rowc["notaedo"] );
                    $importe = "$ ".number_format($sub, 2, '.', ',');
                    
                    echo "<tr>";
                        echo "<td>$notaedo</td>";
                        echo "<td>$importe</td>";
                    echo "</tr>";
                }
            //-----------------------------------------------------------------

            //--------------- Informacion Traspasos Positivos ------------------
                $sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, e.idinmueble as idi from duenio d, edoduenio e where d.idduenio = e.idduenio and e.idinmueble = 0  and  fechagen='" . $fechagen . "'  and reportado = 1 and idcontrato =0 and d.idduenio=" . $idPropietario . " and traspaso = 1 and importe>0";
                $sqlc .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
        
                $operacionc = mysql_query($sqlc);	
                $trs=0;
                if(mysql_num_rows($operacionc)>0)
                {
                    $idi = 0;
                    while($rowc = mysql_fetch_array($operacionc))	
                    {
                                            
                        echo "<tr>";
                            echo "<td>Traspaso</td>";
                        echo "</tr>";
                    
                        //$np +=(double)$c[0];
                        $np +=$rowc["importe"] + $rowc["iva"];
                        $sub = $rowc["importe"] + $rowc["iva"];
                        
                        //$np +=$rowc["importe"];// + $rowc["iva"];
                        //$sub = $rowc["importe"];// + $rowc["iva"];			
                        $renp +=1;
                            
                        $notaedo = utf8_decode($rowc["notaedo"]);
                        $importe = "$ ".number_format($sub, 2, '.', ',');
                            
                        echo "<tr>";
                            echo "<td>$notaedo</td>";
                            echo "<td>$importe</td>";
                        echo "</tr>";
        
                    }					
                    
                        
                }
            //------------------------------------------------------------------

            //--------------- Informacion Total de Abonos ----------------------
                echo "<tr style='background-color:#81c784;'>";
                    echo "<td style='text-align:right;'>Total de abonos:</td>";
                    echo "<td>$ $np</td>";
                echo "</tr>";
            //------------------------------------------------------------------
            
            //***************** Cobrar ******************/

            //--------------- Informacion Marcados y Generados ---------------
                $sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<0  and fechagen='" . $fechagen . "'  and  reportado = 1 and e.idtipocobro>0  and d.idduenio=" . $idPropietario . " and traspaso = 0 ";
                $sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
                $operacionc = mysql_query($sqlc);
                
                $npm=0;
                $renp=0;

                while($rowc = mysql_fetch_array($operacionc))	
                {

                    if($rowc["idcontrato"] != $idcontrato)
                    {
                        
                        $idcontrato = $rowc["idcontrato"];
                        $duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
                        //$reporte .= "</table><br><br>";
                        $clase = "";
                        $inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
                        $inmueble = utf8_decode($inmueble);
                        echo "<tr>";
                            echo "<td><h3>$inmueble</h3></td>";
                        echo "</tr>";
            
                    }
        
                
                    //$np +=(double)$c[0];
                    $npm +=$rowc["importe"] + $rowc["iva"];
                    $sub = $rowc["importe"] + $rowc["iva"];
                    
                    
                    //$np +=$rowc["importe"];// + $rowc["iva"];
                    //$sub = $rowc["importe"];// + $rowc["iva"];			
                    $renp +=1;

                    $notaedo = utf8_decode($rowc["notaedo"]);
                    $importe = "$ ".number_format($sub, 2, '.', ',');
                        
                    echo "<tr>";
                        echo "<td>$notaedo</td>";
                        echo "<td>$importe</td>";
                    echo "</tr>";
        
                }

                $sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd from duenio d, edoduenio e, inmueble i, contrato c, inquilino iq where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble and e.idcontrato = c.idcontrato and c.idinquilino = iq.idinquilino and importe<0  and fechagen='" . $fechagen . "'  and  reportado = 1 and e.idtipocobro=0  and d.idduenio=" . $idPropietario . " and traspaso = 0 ";
                $sqlc .= " order by e.idduenio, e.idcontrato, idtipocobro, idedoduenio";
                $operacionc = mysql_query($sqlc);
                
                
                while($rowc = mysql_fetch_array($operacionc))	
                {
                    if($rowc["idcontrato"] != $idcontrato)
                    {
                        
                        $idcontrato = $rowc["idcontrato"];
                        $duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
                        //$reporte .= "</table><br><br>";
                        $clase = "";
                        $inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
                        $inmueble = utf8_decode($inmueble);

                        echo "<tr>";
                            echo "<td><h3>$inmueble</h3></td>";
                        echo "</tr>";
            
                    }

                
                    //$np +=(double)$c[0];
                    $npm +=$rowc["importe"] + $rowc["iva"];
                    $sub = $rowc["importe"] + $rowc["iva"];
                    
                    
                    //$np +=$rowc["importe"];// + $rowc["iva"];
                    //$sub = $rowc["importe"];// + $rowc["iva"];			
                    $renp +=1;                        
                    
                    $notaedo = utf8_decode($rowc["notaedo"]);
                    $importe = "$ ".number_format($sub, 2, '.', ',');
                        
                    echo "<tr>";
                        echo "<td>$notaedo</td>";
                        echo "<td>$importe</td>";
                    echo "</tr>"; 

                }

                $sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, i.idinmueble as idi from duenio d, edoduenio e, inmueble i where d.idduenio = e.idduenio and e.idinmueble = i.idinmueble  and importe<0  and fechagen='" . $fechagen . "'  and reportado = 1 and idcontrato =0 and d.idduenio=" . $idPropietario . " and traspaso = 0 ";
                $sqlc .= $miwhere . " order by e.idduenio, e.idcontrato,fechaedo,idedoduenio";	

                $operacionc = mysql_query($sqlc);	
            
                $idi = 0;
                while($rowc = mysql_fetch_array($operacionc))	
                {
                    if($rowc["idi"] != $idi)
                    {
                        
                        $idi = $rowc["idi"];
                        $duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
                        //$reporte .= "</table><br><br>";
                        $clase = "";
                        $inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
                        $inmueble = utf8_decode("Cargos directos ".$inmueble);

                        echo "<tr>";
                            echo "<td><h3>$inmueble</h3></td>";
                        echo "</tr>";
            
                    }

                
                    //$np +=(double)$c[0];
                    $npm +=$rowc["importe"] + $rowc["iva"];
                    $sub = $rowc["importe"] + $rowc["iva"];
                    
                    //$np +=$rowc["importe"];// + $rowc["iva"];
                    //$sub = $rowc["importe"];// + $rowc["iva"];			
                    $renp +=1;
                    
                    $notaedo = utf8_decode($rowc["notaedo"]);
                    $importe = "$ ".number_format($sub, 2, '.', ',');
                        
                    echo "<tr>";
                        echo "<td>$notaedo</td>";
                        echo "<td>$importe</td>";
                    echo "</tr>";

                }

                $sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, e.idinmueble as idi from duenio d, edoduenio e where d.idduenio = e.idduenio and e.idinmueble = 0  and importe<0  and fechagen='" . $fechagen . "'  and reportado = 1 and idcontrato =0 and d.idduenio=" . $idPropietario . " and traspaso = 0 ";
                $sqlc .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	

                $operacionc = mysql_query($sqlc);	
            
                $idi = 0;
                while($rowc = mysql_fetch_array($operacionc))	
                {
                    
                    if($rowc["idi"] != $idi)
                    {
                        
                        $idi = $rowc["idi"];
                        $duenio=$rowc["nd"] . " " . $rowc["n2d"]  . " " . $rowc["apd"]  . " " . $rowc["amd"];
                        //$reporte .= "</table><br><br>";
                        $clase = "";
                        $inmueble = $rowc["calle"] . " No." . $rowc["numeroext"]  . " " . $rowc["numeroint"];
                        $inmueble = utf8_decode("Cargos directos ".$inmueble);

                        echo "<tr>";
                            echo "<td><h3>$inmueble</h3></td>";
                        echo "</tr>";
            
                    }

                
                    //$np +=(double)$c[0];
                    $npm +=$rowc["importe"] + $rowc["iva"];
                    $sub = $rowc["importe"] + $rowc["iva"];
                    
                    //$np +=$rowc["importe"];// + $rowc["iva"];
                    //$sub = $rowc["importe"];// + $rowc["iva"];			
                    $renp +=1;
                        
                    $notaedo = utf8_decode($rowc["notaedo"]);
                    $importe = "$ ".number_format($sub, 2, '.', ',');
                        
                    echo "<tr>";
                        echo "<td>$notaedo</td>";
                        echo "<td>$importe</td>";
                    echo "</tr>";

                }


            //----------------------------------------------------------------

            //--------------- Informacion Traspasos Positivos --------------------
                $sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, e.idinmueble as idi from duenio d, edoduenio e where d.idduenio = e.idduenio and e.idinmueble = 0  and  fechagen='" . $fechagen . "'  and reportado = 1 and idcontrato =0 and d.idduenio=" . $idPropietario . " and traspaso = 1 and importe<0";
                $sqlc .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	

                $operacionc = mysql_query($sqlc);	
                $trs=0;
                if(mysql_num_rows($operacionc)>0)
                {
                    $idi = 0;
                    while($rowc = mysql_fetch_array($operacionc))	
                    {                                           
                            
                        //$idcontrato = 1;
                    
                        echo "<tr>";
                            echo "<td>Traspaso</td>";
                        echo "</tr>";
                    
                        //$np +=(double)$c[0];
                        $npm +=$rowc["importe"] + $rowc["iva"];
                        $sub = $rowc["importe"] + $rowc["iva"];
                        
                        //$np +=$rowc["importe"];// + $rowc["iva"];
                        //$sub = $rowc["importe"];// + $rowc["iva"];			
                        $renp +=1;
                            
                        $notaedo = utf8_decode($rowc["notaedo"]);
                        $importe = "$ ".number_format($sub, 2, '.', ',');
                            
                        echo "<tr>";
                            echo "<td>$notaedo</td>";
                            echo "<td>$importe</td>";
                        echo "</tr>";

                    }					
                    
                        
                }	
            //-----------------------------------------------------------------

            //--------------- Informacion Total a Cobrar ----------------------
                echo "<tr style='background-color:#e1bee7;'>";
                    echo "<td style='text-align:right;'>Total por cobrar:</td>";
                    echo "<td>$ $npm</td>";
                echo "</tr>";
            //-----------------------------------------------------------------

            //--------------- Informacion Traspasos en Cero ----------------------
                $sqlc = "select *, d.nombre as nd, d.nombre2 as n2d, d.apaterno as apd, d.amaterno as amd, e.idinmueble as idi from duenio d, edoduenio e where d.idduenio = e.idduenio and e.idinmueble = 0  and  fechagen='" . $fechagen . "'  and reportado = 1 and idcontrato =0 and d.idduenio=" . $idPropietario . " and traspaso = 1 and importe=0";
                $sqlc .= $miwhere . " order by e.idduenio, e.idcontrato,idedoduenio";	
        
                $operacionc = mysql_query($sqlc);	
                $trs=0;
                if(mysql_num_rows($operacionc)>0)
                {
                    $idi = 0;
                    while($rowc = mysql_fetch_array($operacionc))	
                    {                                         
                            
                        //$idcontrato = 1;
                        echo "<tr>";
                            echo "<td>Traspaso</td>";
                        echo "</tr>";
                    
                        //$np +=(double)$c[0];
                        //$trs +=$rowc["importe"] + $rowc["iva"];
                        $sub = 0;
                        
                        //$np +=$rowc["importe"];// + $rowc["iva"];
                        //$sub = $rowc["importe"];// + $rowc["iva"];			
                        $renp +=1;
                            
                        $notaedo = utf8_decode($rowc["notaedo"]);
                        $importe = "$ ".number_format($sub, 2, '.', ',');
                            
                        echo "<tr>";
                            echo "<td>$notaedo</td>";
                            echo "<td>$importe</td>";
                        echo "</tr>";        
                    }					
                    
                        
                }
            //-----------------------------------------------------------------
            
            //--------------- Informacion Total a Pagar ------------------------
                $total = $npm+$np;
                echo "<tr style='background-color:#4db6ac;'>";
                    echo "<td style='text-align:right;'><h2>Total a pagar:</h2></td>";
                    echo "<td><h2>$ $total</h2></td>";
                echo "</tr>";
            //------------------------------------------------------------------

        echo "</table>";
    //-------------------------------------------------------------

	
	
function cambiarColor() {

    $colorazul = dechex(rand( 0 , 255 ));//)rand( 0 , 255 );
    if(strlen(trim($colorazul . " "))==1)
    {
        $colorazul = "0" .$colorazul;
    }
    
    $colorrojo =dechex(rand( 0 , 255 ));//rand( 0 , 255 );
    if(strlen(trim($colorrojo . " "))==1)
    {
        $colorrojo = "0" .$colorrojo;
    }
        
    $colorverde =dechex(rand( 0 , 255 ));//rand( 0 , 255 );
    if(strlen(trim($colorverde . " "))==1)
    {
        $colorverde = "0" .$colorverde;
    }    

   	return '#'.$colorrojo.$colorverde.$colorazul;
   
}	
				
?>