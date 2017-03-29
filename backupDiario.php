<?php

    /******************************************
     * OBTIENE EL BACKUP DE LA BASE DE DATOS
     * DE PRODUCCION DIARIAMENTE
     * @author Bernardo Zerda
     * @version 1.0 Mar 2017
     *******************************************/

    // Archivo de funciones
    include( getcwd() . "/funciones.php");
    
    $txtSalida = "";
//     mensajeLog("Inicia el backup de " . NOMBRE_BD);
    $txtArchivo = date("Ymd") . "-sdht_subsidios.sql";
//     $txtComando = "mysqldump -u" . USUARIO_BD . " -p" . CLAVE_BD . " -h" . SERVIDOR_PRODUCCION . " " . NOMBRE_BD . " > " . DESTINO_BACKUP_BD . "/" . $txtArchivo;
//     $txtSalida = shell_exec($txtComando);
//     mensajeLog("Termina el backup de " . NOMBRE_BD);
    
    $txtSalida = "";
    mensajeLog("Inicia compresion del backup " . $txtArchivo);
    $txtComando = "tar -zcvf " . DESTINO_BACKUP_BD . "/" . substr($txtArchivo, 0, strlen($txtArchivo) - 4) . ".tar.gz "  . DESTINO_BACKUP_BD . "/" . $txtArchivo ;
    echo $txtComando . "\r\n";
    //$txtSalida = shell_exec($txtComando);
    mensajeLog("Termina compresion del backup " . $txtArchivo);



?>