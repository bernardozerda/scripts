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

$txtBaseDatosDestino = ( isset($argv[1] ) )? $argv[1] : "";

if( $txtBaseDatosDestino != ""){

	$txtNombreArchivoTar = date("Ymd") . "-sipive";
	if( file_exists( DESTINO_BACKUP_BD . "/" . $txtNombreArchivoTar . ".tar.gz" )){

    		mensajeLog("Descomprimiendo backup " . $txtNombreArchivoTar . ".tar.gz" );
    		$txtComando = "tar -zxvf " . DESTINO_BACKUP_BD . "/" . $txtNombreArchivoTar . ".tar.gz -C /";
    		//mensajeLog($txtComando);
    		exec($txtComando);
	
    		mensajeLog("Reemplaza " . NOMBRE_BD . " por " . $txtBaseDatosDestino);
    		$txtComando = "sed -i 's/`" . NOMBRE_BD . "/`" . $txtBaseDatosDestino . "/g' " .  DESTINO_BACKUP_BD . "/" . $txtNombreArchivoTar . ".sql";
    		mensajeLog($txtComando);
    		//exec($txtComando);
	
    		mensajeLog("Montando la base de datos");
    		$txtComando = "mysql -u" . USUARIO_BD . " -p" . CLAVE_BD . " " . $txtBaseDatosDestino . " < " . DESTINO_BACKUP_BD . "/" . $txtNombreArchivoTar . ".sql";
    		mensajeLog($txtComando);
    		//exec($txtComando);
	
    		mensajeLog("Limpiando archivos");
    		$txtComando = "rm -rf " . DESTINO_BACKUP_BD . "/" . $txtNombreArchivoTar . ".sql";
    		mensajeLog($txtComando);
    		//exec($txtComando);

	}else{
    		mensajeLog("No se encontró el backup " . $txtNombreArchivoTar);
	}
}else{
	mensajeLog("Debe definir la base de datos destino");
}
?>
