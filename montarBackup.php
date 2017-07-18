<?php

/******************************************
 * MONTA EL BACKUP DE LA BASE DE DATOS
 * DE PRODUCCION DIARIAMENTE HACIA CAPACITACION
 * @author Bernardo Zerda
 * @version 1.0 Jul 2017
 *******************************************/

// Archivo de funciones
chdir("/home/sdvpruebas/backups/scripts");

include ("./funciones.php");

$txtNombreArchivoTar = date("Ymd") . "-sipive";
if( file_exists( DESTINO_BACKUP_BD . "/" . $txtNombreArchivoTar . ".tar.gz" )){

    mensajeLog("Descomprimiendo backup " . $txtNombreArchivoTar );
    $txtComando = "tar -zxvf " . DESTINO_BACKUP_BD . "/" . $txtNombreArchivoTar . ".tar.gz";
    //exec($txtComando);

    mensajeLog("Reemplaza " . NOMBRE_BD . " por sipive_capacitacion");
    $txtComando = "sed -i 's/" . NOMBRE_BD . "/sipive_capacitacion/g' " .  DESTINO_BACKUP_BD . "/" . $txtNombreArchivoTar . ".sql";
    //exec($txtComando);

    mensajeLog("Montando la base de datos");
    $txtComando = "mysql -u" . USUARIO_BD . " -p" . CLAVE_BD . " sipive_capacitacion < " . DESTINO_BACKUP_BD . "/" . $txtNombreArchivoTar . ".sql";
    //exec($txtComando);

}else{
    mensajeLog("No se encontró el backup " . $txtNombreArchivoTar);
}



?>