<?php
    
    include("./conexion.php");
    
    $sql = "SHOW TABLES";
    $objRes = $aptBd->execute($sql);
    
    while ($objRes->fields){
        
        try{
            $sql = "ALTER TABLE " . $objRes->fields["Tables_in_sdht_subsidios"] . " RENAME " . strtoupper( $objRes->fields["Tables_in_sdht_subsidios"] );
            $aptBd->execute($sql);
            echo $objRes->fields["Tables_in_sdht_subsidios"] . " Renombrada<br>";
        }catch (Exception $e ){
            echo "Error en " . $sql . "<br>";
            echo $e->errorMsg();
            die();
        }
        
        $objRes->MoveNext();
    }
    
    echo "Finaliza";
    
?>