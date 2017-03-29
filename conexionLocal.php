<?php

    /***********************************************************
     * HACE LA CONEXION A LA BASES DE DATOS LOCAL
     * @author Bernardo Zerda
     * @version 1.0 Mar 2017
     ***********************************************************/
    
    // Inclusion de las librerias
    include ("./adodb5/adodb.inc.php");
    
    // CONEXION A LA BASE DE DATOS LOCAL
    try {
        $aptLocal = &ADONewConnection("mysql");
        $aptLocal->PConnect("localhost", "sdht_usuario", "Ochochar*1", "sdht_subsidios");
        $aptLocal->SetFetchMode(ADODB_FETCH_ASSOC); // solo respuestas con arreglos asociativos
        $sql = "SET CHARSET utf8";
        $aptLocal->execute($sql);
    } catch (Exception $objError) {
        mensajeLog("Error Conexion BD Local: " . $objError->getMessage());
    }

?>
    