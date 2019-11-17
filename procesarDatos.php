<?php
		include "usuario.php";
		
    	$miUsuario;
        $info = file_get_contents($_GET['cedula'].".json");
        //FormatoJson
        $usuario = json_decode($info,true);
        //Cedula
        $miUsuario= new usuario($usuario['cedula']);
		//Agregamos las tareas del usuario
		$i=0;
        foreach ($usuario['tareas'] as $tarea) {
        	if($i== $_GET['tarea']){
        		$miUsuario->addTarea(new tarea($tarea['nombre'],$tarea['fecha'],$_GET['terminada']));
        	}else{

        		$miUsuario->addTarea(new tarea($tarea['nombre'],$tarea['fecha'],$tarea['terminada']));
        	}
        	$i+=1;
        }
        
        
        unlink($_GET['cedula'].".json");

	    $time = time();
		$fecha = date("d-m-y", $time);
		$fh = fopen( $_GET['cedula'].".json", 'w') or die("Se produjo un error al crear el archivo");
		  
		$texto ="{\n\"cedula\":\"".$miUsuario->getCedula()."\",\n";

		$texto .="\"tareas\":[\n";
		$size=count($miUsuario->getTareas());
		for ($j=0; $j <$size ; $j++) { 
			$texto .="	{	\n	\"nombre\":\"".$miUsuario->getTareas()[$j]->getNombre()."\",\n";
			$texto .="	\"fecha\":\"".$miUsuario->getTareas()[$j]->getFecha()."\",\n";	
			$texto .="	\"terminada\":\"".$miUsuario->getTareas()[$j]->getTerminada()."\"\n	}";
			if($j==($size-1)){
				$texto.="\n	]\n";
			}else{
				$texto .=",\n";
			}
			
		}
		$texto .="}";

		
		fwrite($fh, $texto) or die("No se pudo escribir en el archivo");
		  
		fclose($fh);
		
    
?>
