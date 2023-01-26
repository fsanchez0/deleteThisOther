<?php
include("../general/conexion.php");
ini_set("display_errors",0);
$idduenio1=$_GET["idduenio"];
//$anio = @$_GET["anio"];
$mes1 = @$_GET["mes"];
$periodo=$_GET["periodos"];
$idduenio=trim($idduenio1);
$idd="";
$mes=trim($mes1);
$sql=mysql_query("SELECT nombre,nombre2,apaterno,amaterno FROM duenio WHERE idduenio='$mes'");
while ($reg=mysql_fetch_array($sql)) {
   $anio=$reg[0]." ".$reg[1]." ".$reg[2]." ".$reg[3];
}
$consulta = "SELECT * FROM edoduenio WHERE idedoduenio=$idduenio1";
$sql=mysql_query($consulta );
//$sql=mysql_query("SELECT * FROM edoduenio WHERE idedoduenio='$idduenio1'");
while ($reg=mysql_fetch_array($sql)) {
   $periodo=$reg["fechagen"];
   $idd=$reg["idduenio"];
}

//define('BASE_DIR','/home/wwwarchivos/cfdi');
?>
 <html>
<head>
    <title></title>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
        
$(document).ready(function(){
 
    $(".messages").hide();
    //queremos que esta variable sea global
    var fileExtension = "";
    //función que observa los cambios del campo file y obtiene información
    $(':file').change(function()
    {
        //obtenemos un array con los datos del archivo
        var file = $("#imagen")[0].files[0];
        //obtenemos el nombre del archivo
        var fileName = file.name;
        //obtenemos la extensión del archivo
        fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        //obtenemos el tamaño del archivo
        var fileSize = file.size;
        //obtenemos el tipo de archivo image/png ejemplo
        var fileType = file.type;
        //mensaje con la información del archivo
        showMessage("<span class='info'>"+fileName+"</span>");
    });
 
    //al enviar el formulario
    $(':button').click(function(){
        var file = $("#imagen")[0].files[0];
        var fileName = file.name;
        //información del formulario
        fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        if(fileExtension=="pdf"){
        var formData = new FormData($(".formulario")[0]);
        var message = ""; 
        //hacemos la petición ajax  

        $.ajax({
            url: 'recibe.php',  
            type: 'POST',
            // Form data
            //datos del formulario
            data: formData,
            //necesario para subir archivos via ajax
            cache: false,
            contentType: false,
            processData: false,
            //mientras enviamos el archivo
            beforeSend: function(){
                message = $("<span class='before'>Subiendo el archivo PDF, por favor espere...</span>");
                showMessage(message)        
            },
            //una vez finalizado correctamente
            success: function(data){
                message = $("<span class='success'>El archivo PDF se ha subido correctamente.</span>" + data);
                showMessage(message);

            },
            //si ha ocurrido un error
            error: function(){
                message = $("<span class='error'>Ha ocurrido un error, extension no permitida.</span>" + data);
                showMessage(message);
            }
        });

}
    else{
        message = $("<span class='before'>Ha ocurrido un error, extension no permitida.</span>");
                showMessage(message)
    }

    });
})
 
//como la utilizamos demasiadas veces, creamos una función para 
//evitar repetición de código
function showMessage(message){
    $(".messages").html("").show();
    $(".messages").html(message);
}



    </script>
 
<style type="text/css">
    .messages{
        float: left;
        font-family: sans-serif;
        display: none;
    }
    .info{
        padding: 10px;
        border-radius: 10px;
        background: orange;
        color: #fff;
        font-size: 10px;
        text-align: center;
    }
    .before{
        padding: 10px;
        border-radius: 10px;
        background: blue;
        color: #fff;
        font-size: 10px;
        text-align: center;
    }
    .success{
        padding: 10px;
        border-radius: 10px;
        background: green;
        color: #fff;
        font-size: 10px;
        text-align: center;
    }
    .error{
        padding: 10px;
        border-radius: 10px;
        background: red;
        color: #fff;
        font-size: 10px;
        text-align: center;
    }
</style>
</head>
<body>
    <!--el enctype debe soportar subida de archivos con multipart/form-data-->
    <input type="button" value="Actualizar" onClick="location.reload();" />
    <form enctype="multipart/form-data" class="formulario">
        <label>Subir un archivo</label><br />
        <input type="hidden" name="idduenio" value="<?php echo $idduenio;?>">
        <input type="hidden" name="anio" value="<?php echo $anio;?>">
        <input type="hidden" name="mes" value="<?php echo $mes;?>">
        <input type="hidden" name="periodo" value="<?php echo $periodo;?>">
        <input name="archivo" type="file" id="imagen" /><br />
        <input type="button" value="Subir PDF" />
    </form>
    <!--div para visualizar mensajes-->
    <div class="messages"></div><br />
    <!--div para visualizar en el caso de imagen-->
    <div class="showImage"></div>
<?php    
    //$directorio = opendir("files"); //ruta actual
    $rutas="contratos/" . $mes . "_" . $periodo;
    $directorio = opendir($rutas); //ruta actual
while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
    if (is_dir($archivo))//verificamos si es o no un directorio
    {
        //echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
    }
    else
    {
        //echo "<a href='files/$archivo' target='_blank'>".$archivo . "</a><br />";
        echo "<a href='$rutas/$archivo' target='_blank'>".$archivo . "</a><br />";
    }
}
?>
</body>
</html>