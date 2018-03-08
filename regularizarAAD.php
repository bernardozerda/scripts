<?php

    include("./conexion.php");

    $aptBd->BeginTrans();

    $sql = "
        select seqTipoActo, seqCaracteristica
        from t_aad_caracteristica_acto
    ";
    $objRes = $aptBd->execute($sql);
    $arrCaracteristicas = array();
    while($objRes->fields){
        $seqTipoActo = $objRes->fields['seqTipoActo'];
        $seqCaracteristica = $objRes->fields['seqCaracteristica'];
        $arrCaracteristicas[$seqTipoActo][$seqCaracteristica] = $objRes->fields['seqCaracteristica'];
        $objRes->MoveNext();
    }

    $sql = "
        select
            seqTipoActo,
            numActo,
            fchActo,
            seqCaracteristica,
            txtValorCaracteristica
        from t_aad_acto_administrativo
        order by seqTipoActo, numActo, fchActo, seqCaracteristica 
    ";
    $objRes = $aptBd->execute($sql);
    while($objRes->fields){
        $seqTipoActo = $objRes->fields['seqTipoActo'];
        $txtActo = $objRes->fields['numActo'] . "#" . $objRes->fields['fchActo'];
        $seqCaracteristica = $objRes->fields['seqCaracteristica'];
        $arrActosAdministrativos[$seqTipoActo][$txtActo][$seqCaracteristica] = $objRes->fields['txtValorCaracteristica'];
        $objRes->MoveNext();
    }

    // completando las calves
    foreach($arrActosAdministrativos as $seqTipoActo => $arrActos){
        foreach($arrActos as $txtActo => $arrCaracteristicasActo ){
            list($numActo,$fchActo) = mb_split("#", $txtActo);
            foreach(array_diff_key($arrCaracteristicas[$seqTipoActo], $arrCaracteristicasActo) as $seqCaracteristica){
                $sql = "insert into t_aad_acto_administrativo (seqTipoActo,numActo,fchActo,seqCaracteristica,txtValorCaracteristica) values($seqTipoActo,$numActo,'$fchActo',$seqCaracteristica,null)";
                $aptBd->execute($sql);
            }
        }
    }

    $aptBd->CommitTrans();

    // migrando

    $arrMigracion = array();
    $arrMigracion[2][7]    = "fchActoReferencia";
    $arrMigracion[2][4]    = "numActoReferencia";
    $arrMigracion[4][6]    = "fchActoReferencia";
    $arrMigracion[4][5]    = "numActoReferencia";
    $arrMigracion[6][19]   = "fchActoReferencia";
    $arrMigracion[6][18]   = "numActoReferencia";
    $arrMigracion[9][50]   = "fchActoReferencia";
    $arrMigracion[9][49]   = "numActoReferencia";
    $arrMigracion[10][92]  = "fchActoReferencia";
    $arrMigracion[10][91]  = "numActoReferencia";
    $arrMigracion[11][141] = "fchActoReferencia";
    $arrMigracion[11][140] = "numActoReferencia";

    foreach($arrMigracion as $seqTipoActo => $arrCarcteristicasMover){
        foreach($arrCarcteristicasMover as $seqCaracteristica => $txtCampo){
            $sql = "
                select 
                    numActo,
                    fchActo,
                    txtValorCaracteristica
                from t_aad_acto_administrativo
                where seqTipoActo = $seqTipoActo
                  and seqCaracteristica = $seqCaracteristica            
            ";
            $objRes = $aptBd->execute($sql);
            while($objRes->fields){
                $txtValor = ($txtCampo == "numActoReferencia")? intval($objRes->fields['txtValorCaracteristica']): "'" . $objRes->fields['txtValorCaracteristica'] . "'";
                $sql = "
                    update t_aad_hogares_vinculados set " . $txtCampo . " = $txtValor 
                    where numActo = " . $objRes->fields['numActo'] . " and fchActo = '" . $objRes->fields['fchActo'] . "'
                     and (
                        (numActoReferencia = 0 or numActoReferencia is null) or 
                        (fchActoReferencia <> '' or fchActoReferencia is null)
                     ) 
                ";
                $aptBd->execute($sql);
                $objRes->MoveNext();
            }
        }
    }

    $sql = "delete from t_aad_acto_administrativo where seqCaracteristica in (4,7,5,6,18,19,49,50,91,92,140,141)";
    $aptBd->execute($sql);

    $sql = "delete from t_aad_caracteristica_acto where seqCaracteristica in (4,7,5,6,18,19,49,50,91,92,140,141);";
    $aptBd->execute($sql);

    $aptBd->CommitTrans();

?>