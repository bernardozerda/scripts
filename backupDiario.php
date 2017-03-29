<?php

/******************************************
 * OBTIENE EL BACKUP DE LA BASE DE DATOS
 * DE PRODUCCION DIARIAMENTE
 * @author Bernardo Zerda
 * @version 1.0 Mar 2017
 *******************************************/

// Archivo de funciones
include (getcwd() . "/funciones.php");

// Verifica la existencia de la carpeta de backups DESTINO_BACKUP_BD
if (! is_dir(DESTINO_BACKUP_BD)) {
    mensajeLog("No existe la carpeta " . DESTINO_BACKUP_BD);
} else {
    
    // Genera el backup directo de produccion
    $arrSalida = array();
    mensajeLog("Inicia el backup de " . NOMBRE_BD);
    $txtArchivo = date("Ymd") . "-sdht_subsidios.sql";
    $txtComando = "mysqldump -u" . USUARIO_BD . " -p" . CLAVE_BD . " -h" . SERVIDOR_PRODUCCION . " " . NOMBRE_BD . " > " . DESTINO_BACKUP_BD . "/" . $txtArchivo;
    exec($txtComando,$arrSalida);
    print_r($arrSalida);
    mensajeLog("Termina el backup de " . NOMBRE_BD);
    
    // Comprime el backup generado
    $arrSalida = array();
    mensajeLog("Inicia compresion del backup " . $txtArchivo);
    $txtComando = "tar -zcvf " . DESTINO_BACKUP_BD . "/" . substr($txtArchivo, 0, strlen($txtArchivo) - 4) . ".tar.gz " . DESTINO_BACKUP_BD . "/" . $txtArchivo;
    exec($txtComando,$arrSalida);
    print_r($arrSalida);
    $txtComando = "rm -f " . DESTINO_BACKUP_BD . "/" . $txtArchivo;
    exec($txtComando,$arrSalida);
    print_r($arrSalida);
    mensajeLog("Termina compresion del backup " . $txtArchivo);
    
    // Limpia los backups de antes de DIAS_RETENCION dias (configurado en funciones.php)
    mensajeLog("Inicia limpieza de archivos anteriores a " . DIAS_RETENCION . " dias");
    $txtArchivo = "";
    if ($aptDirectorio = opendir(DESTINO_BACKUP_BD)) {
        while (($txtArchivo = readdir($aptDirectorio)) !== false) {
            if($txtArchivo != "." and $txtArchivo != ".."){
                $numFechaArchivo = filemtime(DESTINO_BACKUP_BD . "/" . $txtArchivo);
                $numFechaRetencion = strtotime("-" . DIAS_RETENCION . " day" ); 
                if( $numFechaArchivo < $numFechaRetencion ){
                    $txtComando = "rm -f " . DESTINO_BACKUP_BD . "/" . $txtArchivo;
                    exec($txtComando,$arrSalida);
                    print_r($arrSalida);
                    mensajeLog("\tArchivo " . $txtArchivo . " eliminado");
                }
            }
        }
        closedir($aptDirectorio);
    }
    mensajeLog("Inicia limpieza de archivos anteriores a " . DIAS_RETENCION . " dias");
}

?>