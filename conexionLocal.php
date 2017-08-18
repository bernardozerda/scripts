<?php

    /***********************************************************
     * HACE LA CONEXION A LA BASES DE DATOS LOCAL
     * @author Bernardo Zerda
     * @version 1.0 Mar 2017
     ***********************************************************/
    
    // Inclusion de las librerias
    include ("./adodb5/adodb-exceptions.inc.php");
    include ("./adodb5/adodb.inc.php");
    
    // CONEXION A LA BASE DE DATOS LOCAL
    try {
        $aptLocal = ADONewConnection("mysqli");
        $aptLocal->PConnect("localhost", "sdht_usuario", "Ochochar*1", "sipive");
        $aptLocal->SetFetchMode(ADODB_FETCH_ASSOC); // solo respuestas con arreglos asociativos
        $aptLocal->execute("SET CHARSET utf8");
        $aptLocal->execute("set sql_mode=''");
    } catch (Exception $objError) {
        mensajeLog("Error Conexion BD Local: " . $objError->getMessage());
    }

?>
    