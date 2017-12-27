<?php

    chdir( __DIR__ . "/informeVeedurias");

    include ("./conexion.php");
    include ("./funciones.php");

    echo "\r";

    $directorioImagenes = "/htdocs/sipive/recursos/imagenes/desembolsos/";

    mensajeLog("Imagenes en base de datos");

    $imagenesBaseDatos[] = ".";
    $imagenesBaseDatos[] = "..";

    $sql = "select txtNombreArchivo from t_cem_adjuntos_tecnicos";
    foreach($aptBd->GetAll($sql) as $arrRegistro){
        $imagenesBaseDatos[] = $arrRegistro['txtNombreArchivo'];
    }
    $sql = "select txtNombreArchivo from t_des_adjuntos_tecnicos";
    foreach($aptBd->GetAll($sql) as $arrRegistro){
        $imagenesBaseDatos[] = $arrRegistro['txtNombreArchivo'];
    }
    $sql = "select txtNombreArchivo from t_pry_adjuntos_tecnicos";
    foreach($aptBd->GetAll($sql) as $arrRegistro){
        $imagenesBaseDatos[] = $arrRegistro['txtNombreArchivo'];
    }

    mensajeLog("Imagenes en directorio");

    $imagenesDirectorio = scandir($directorioImagenes);

    mensajeLog("Imagenes Perdidas");

    $imagenesPerdidos = array_diff($imagenesDirectorio,$imagenesBaseDatos);

    foreach($imagenesPerdidos as $imagen){
        $comando = "scp '" . $directorioImagenes . $imagen . "' sdvpruebas@192.168.3.94:/home/sdvpruebas/backups/imagenes";
        $salida = shell_exec($comando);
        unlink($directorioImagenes . $imagen);
        mensajeLog("\tImagen Copiada (" . $i++ . " de " . count($imagenesPerdidos) . ") $imagen");
    }

    $perdidos = fopen("/home/hmatamorosr/perdidos.txt","w");
    fwrite($perdidos,implode("\r\n",$imagenesPerdidos));
    fclose($perdidos);



?>