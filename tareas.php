<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous">
  </script>
<link rel="stylesheet" type="text/css" href="estilos.css">
<body>
	

    <?php  
	$time = time();
	$fecha = date("d-m-y", $time);
	  $fh = fopen( $_GET['cedula'].".json", 'w') or die("Se produjo un error al crear el archivo");
	  
	  $texto ="{\n\"cedula\":\"".$_GET['cedula']."\",\n";
	  $texto .="\"tareas\":[\n	{\n";
	  $texto .="	\"nombre\":\"Planear semana\",\n";
	  $texto .="	\"fecha\":\"".$fecha."\",\n";
	  $texto .="	\"terminada:\":\"false\"\n	},\n";
	  $texto .="	{\n	\"nombre\":\"Ser feliz\",\n";
	  $texto .="	\"fecha\":\"".$fecha."\",\n";
	  $texto .="	\"terminada\":\"false\"\n	}\n	]\n";
	  $texto .="}";
	  fwrite($fh, $texto) or die("No se pudo escribir en el archivo");
	  
	  fclose($fh);
	?>

	<?php
        include "usuario.php";
        $miUsuario;
        $info = file_get_contents($_GET['cedula'].".json");
        //FormatoJson
        $us = json_decode($info,true);
        //Cedula
        $miUsuario= new usuario($us['cedula']);
		//Agragamos las tareas del usuario
        foreach ($us['tareas'] as $tarea) {
        	$miUsuario->addTarea(new tarea($tarea['nombre'],$tarea['fecha'],$tarea['terminada']));
        }
        
    ?>
	<script>
	    $(document).ready(function() {
	       $("input[name='cbxTachar']").click(function(){
	       		var num = $(this).val();
	       		var mensaje="#"+num;

	       		if($(mensaje).hasClass('tachado')){
	       			$(mensaje).removeClass("tachado");
	       			$.ajax({
                    	type: "GET",
                    	url: "procesarDatos.php",
                        data:{
                        	cedula:<?php echo $_GET['cedula']?>,
                        	tarea:num,
                        	terminada:"false"
                        },
                    success: function (response) {
                        alert("Correcto");   
                    },
                   
                });

	       		}else{
	       			$(mensaje).addClass("tachado");
	       			$.ajax({
                    	type: "GET",
                    	url: "procesarDatos.php",
                        data:{
                        	cedula:<?php echo $_GET['cedula']?>,
                        	tarea:num,
                        	terminada:"true"
                        },
                    success: function (response) {
                    },
                    
                	});

	       		}



	       }) 
	    });
	 
	 
    </script>

    <div class="divCedula">
    	Cedula: <?php echo $miUsuario->getCedula()?>
    </div>
    <p>Tareas</p>
    <div class="contenedor">
  
    <?php $valor=0; 
    	foreach($miUsuario->getTareas() as $tarea):?>
    	<div  id= <?php echo "\"".$valor."\""?> class="naranja">Tarea:<?php echo $tarea->getNombre()?><br>Fecha:<?php echo $tarea->getFecha()?>
    	</div>
    	<input type="checkbox" class="cbx" name="cbxTachar" value=<?php echo "\"".$valor."\""?>>
    	 <?php  $valor+=1;?>

	<?php endforeach;?>   
	</div> 
	<div class="prueba">Hola Mundo </div>

	
	
</body>
</html>
