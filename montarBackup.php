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

$txtNombreArchivoTar = date("Ymd") . "-sipive.tar.gz";
if( file_exists( DESTINO_BACKUP_BD . "/" . $txtNombreArchivoTar )){

    $txtComando = "tar -zxvf " . DESTINO_BACKUP_BD . "/" . $txtNombreArchivoTar;
    mensajeLog( $txtComando );
    //exec($txtComando);

}else{
    mensajeLog("No se encontró el backup " . $txtNombreArchivoTar);
}



?>