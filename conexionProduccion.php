<?php

    /***********************************************************
     * HACE LA CONEXION A LA BASES DE DATOS DE PRODUCCION
     * @author Bernardo Zerda
     * @version 1.0 Mar 2017
     ***********************************************************/
    
    // Inclusion de las librerias
    include ("./adodb5/adodb.inc.php");
    
    // CONEXION A LA BASE DE DATOS LOCAL
    try {
        $aptProduccion = &ADONewConnection("mysql");
        $aptProduccion->PConnect("192.168.6.214", "sdht_usuario", "Ochochar*1", "sdht_subsidios");
        $aptProduccion->SetFetchMode(ADODB_FETCH_ASSOC); // solo respuestas con arreglos asociativos
        $sql = "SET CHARSET utf8";
        $aptProduccion->execute($sql);
    } catch (Exception $objError) {
        mensajeLog("Error Conexion BD Produccion: " . $objError->getMessage());
    }

?>
    