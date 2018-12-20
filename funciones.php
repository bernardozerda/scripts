<?php
    
    /***********************************************************
     * FUNCIONES GENERALES PARA TODOS LOS SCRIPTS
     * @author Bernardo Zerda
     * @version 1.0 Mar 2017
     ***********************************************************/
    
    // CONSTANTES
    define("SERVIDOR_PRODUCCION", "localhost");
    define("USUARIO_BD", "sdht_usuario");
    define("CLAVE_BD", "Ochochar*1");
    define("NOMBRE_BD", "sipive");
    define("DESTINO_BACKUP_BD", "/home/sdvpruebas/backups/baseDatos");
    define("DIAS_RETENCION", 180);
    define("DIA_BLOQUEADO", 1);
    
    /**
     * FUNCION PARA IMPRIMIR LOS MENSAJES DE LOG
     * @author Bernardo Zerda
     * @param string txtMensaje
     * @version 1.0 Mar 2017
     */
    
    function mensajeLog($txtMensaje){
        echo date("Ymd H:i:s") . " | " . $txtMensaje . "\r\n";
    }

?>