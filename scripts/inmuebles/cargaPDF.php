<?php
include("../general/conexion.php");
ini_set("display_errors",0);
$idcontrato=@$_GET["idcontrato"];
//$anio = @$_GET["anio"];
$servicio = @$_GET["servicio"];
//define('BASE_DIR','/home/wwwarchivos/cfdi');
?>
 <html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
            url: 'http://rentascdmx.com//scripts/inmuebles/recibe.php',  
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
                message = $("<span class='success'>El archivo PDF se ha subido correctamente.</span>");
                showMessage(message);

            },
            //si ha ocurrido un error
            error: function(){
                message = $("<span class='error'>Ha ocurrido un error, extension no permitida.</span>");
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
    <form enctype="multipart/form-data" class="formulario">
        
        <input type="hidden" name="idcontrato" value="<?php echo $idcontrato;?>">
        <input type="hidden" name="servicio" value="<?php echo $servicio;?>">
        <label>Subir un archivo: </label>
        <input name="archivo" type="file" id="imagen" />
        <input type="button" value="Subir PDF" />

    </form>
    <!--div para visualizar mensajes-->
    <!--<div class="messages"></div>-->
    <!--div para visualizar en el caso de imagen-->
   <!-- <div class="showImage"></div>-->
<?php
    $directorio = opendir("files"); //ruta actual
while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
{
    if (is_dir($archivo))//verificamos si es o no un directorio
    {
        //echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
    }
    else
    {
      //echo "<a href='files/$archivo' target='_blank'>".$archivo . "</a><br />";
    }
}
?>
</body>
</html>