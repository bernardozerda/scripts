<?php

    /***********************************************************
     * HACE LA CONEXION A LA BASES DE DATOS DE PRUEBAS
     * @author Bernardo Zerda
     * @version 1.0 Mar 2017
     ***********************************************************/
    
    // Inclusion de las librerias
    include ("./adodb5/adodb.inc.php");
    
    // CONEXION A LA BASE DE DATOS LOCAL
    try {
        $aptPruebas = &ADONewConnection("mysql");
        $aptPruebas->PConnect("192.168.3.94", "sdht_subsidios", "Ochochar*1", "sdht_subsidios");
        $aptPruebas->SetFetchMode(ADODB_FETCH_ASSOC); // solo respuestas con arreglos asociativos
        $sql = "SET CHARSET utf8";
        $aptPruebas->execute($sql);
    } catch (Exception $objError) {
        mensajeLog("Error Conexion BD Pruebas: " . $objError->getMessage());
    }

?>