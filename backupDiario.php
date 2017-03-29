<?php

    /******************************************
     * OBTIENE EL BACKUP DE LA BASE DE DATOS
     * DE PRODUCCION DIARIAMENTE
     * @author Bernardo Zerda
     * @version 1.0 Mar 2017
     *******************************************/

    // Archivo de funciones
    include( getcwd() . "/funciones.php");
    
    $txtComando = "mysqldump -u" . USUARIO_BD . " -p" . CLAVE_BD . " -h" . SERVIDOR_PRODUCCION . " " . NOMBRE_BD . " > " . DESTINO_BACKUP_BD . "/" . date("yyyy-m-d") . "-sdht_subsidios.sql";
    
    echo $txtComando . "\r\n";

    //$txtSalida = shell_exec($txtComando);
    
    



?>