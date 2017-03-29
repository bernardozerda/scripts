<?php
    
    /***********************************************************
     * FUNCIONES GENERALES PARA TODOS LOS SCRIPTS
     * @author Bernardo Zerda
     * @version 1.0 Mar 2017
     ***********************************************************/
    
    // CONSTANTES
    define(SERVIDOR_PRODUCCION, "192.168.6.214");
    define(USUARIO_BD, "sdht_usuario");
    define(CLAVE_BD, "Ochochar*1");
    define(NOMBRE_BD, "sdht_subsidios");
    define(DESTINO_BACKUP_BD, "/home/sdvpruebas/backups/produccion");
    
    /**
     * FUNCION PARA IMPRIMIR LOS MENSAJES DE LOG
     * @author Bernardo Zerda
     * @param string txtMensaje
     * @version 1.0 Mar 2017
     */
    
    function mensajeLog($txtMensaje){
        echo date("YYYY-m-d H:i:s") . " | " . $txtMensaje . "\r\n";
    }

?>