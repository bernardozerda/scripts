<?php

/******************************************
 * OBTIENE EL BACKUP DE LA BASE DE DATOS
 * DE PRODUCCION DIARIAMENTE
 * @author Bernardo Zerda
 * @version 1.0 Mar 2017
 *******************************************/

// Archivo de funciones
chdir("/home/sdvpruebas/backups/scripts");

include ("./funciones.php");

// Verifica la existencia de la carpeta de backups DESTINO_BACKUP_BD
if (! is_dir(DESTINO_BACKUP_BD)) {
    mensajeLog("No existe la carpeta " . DESTINO_BACKUP_BD);
} else {
    
    // Genera el backup directo de produccion
    $arrSalida = array();
    mensajeLog("Inicia el backup de " . NOMBRE_BD);
    $txtArchivo = date("Ymd") . "-sipive.sql";
    $txtComando = "mysqldump --routines -u" . USUARIO_BD . " -p" . CLAVE_BD . " -h" . SERVIDOR_PRODUCCION . " " . NOMBRE_BD . " > " . DESTINO_BACKUP_BD . "/" . $txtArchivo;
    exec($txtComando);
    mensajeLog("Termina el backup de " . NOMBRE_BD);
    
    // Comprime el backup generado
    $arrSalida = array();
    mensajeLog("Inicia compresion del backup " . $txtArchivo);
    $txtComando = "tar -zcvf " . DESTINO_BACKUP_BD . "/" . substr($txtArchivo, 0, strlen($txtArchivo) - 4) . ".tar.gz " . DESTINO_BACKUP_BD . "/" . $txtArchivo;
    exec($txtComando);
    $txtComando = "rm -f " . DESTINO_BACKUP_BD . "/" . $txtArchivo;
    exec($txtComando);
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
                	if( intval(substr($txtArchivo,6,2)) != DIA_BLOQUEADO ){ // Nunca borrara los backup del primer dia del mes
                		$txtComando = "rm -f " . DESTINO_BACKUP_BD . "/" . $txtArchivo;
                		exec($txtComando);
                		mensajeLog("\tArchivo " . $txtArchivo . " eliminado");
                		
                	}
                }
            }
        }
        closedir($aptDirectorio);
    }
    mensajeLog("Inicia limpieza de archivos anteriores a " . DIAS_RETENCION . " dias");
}

?>