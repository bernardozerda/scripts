<?php

    include( "./adodb5/adodb.inc.php" );

    $aptBd = &ADONewConnection( "mysql" );
    $aptBd->PConnect(
        "localhost",
        "root",
        "Ochochar*1",
        "sdht_subsidios"
        );
    $aptBd->SetFetchMode(ADODB_FETCH_ASSOC); // solo respuestas con arreglos asociativos
    
    try {
        $sql = "SET CHARSET utf8";
        $aptBd->execute($sql);
    } catch( Exception $objError ){
        echo "No se pudo establecer el conjunto de caracteres";
        exit(0);
    }
    