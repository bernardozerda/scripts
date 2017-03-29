<?php

    /******************************************
     * OBTIENE EL BACKUP DE LA BASE DE DATOS
     * DE PRODUCCION DIARIAMENTE
     * @author Bernardo Zerda
     * @version 1.0 Mar 2017
     *******************************************/

    // Archivo de funciones
    include( getcwd() . "/funciones.php");
    
    mensajeLog("Inicia el backup de " . NOMBRE_BD);
    $txtComando = "mysqldump -u" . USUARIO_BD . " -p" . CLAVE_BD . " -h" . SERVIDOR_PRODUCCION . " " . NOMBRE_BD . " > " . DESTINO_BACKUP_BD . "/" . date("Ymd") . "-sdht_subsidios.sql";
    $txtSalida = shell_exec($txtComando);
    mensajeLog("Termina el backup de " . NOMBRE_BD);
    



?>