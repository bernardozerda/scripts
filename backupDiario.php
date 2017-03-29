<?php

    /******************************************
     * OBTIENE EL BACKUP DE LA BASE DE DATOS
     * DE PRODUCCION DIARIAMENTE
     * @author Bernardo Zerda
     * @version 1.0 Mar 2017
     *******************************************/
    
    // Archivo de funciones
    include("./funciones.php");
    
    $txtComando = "mysqldump -u" . __USUARIO_BD__ . " -p" . CLAVE_BD . " -h" . SERVIDOR_PRODUCCION . " " . NOMBRE_BD . " > " . DESTINO_BACKUP_BD . "/" . date("yyyy-m-d") . "-sdht_subsidios.sql";
    
    echo $txtComando . "\r\n";
    echo __FILE__;
    
                  
    //$txtSalida = shell_exec($txtComando);
    
    



?>