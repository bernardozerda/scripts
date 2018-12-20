<?php

ini_set('memory_limit', '-1');

chdir(__DIR__);

include("./funciones.php");
include("./conexion.php");

$arrTablas['hogares']['tabla'] = "t_frm_formulario";
$arrTablas['hogares']['campo'] = "seqFormulario";

$arrTablas['actos administrativos']['tabla'] = "t_aad_formulario_acto";
$arrTablas['actos administrativos']['campo'] = "seqFormularioActo";

$arrSql['hogares'] = "
    select 
        f.seqFormulario,
        c.seqCiudadano,
        c.valIngresos,
        f.valIngresoHogar,
        f.valSaldoCuentaAhorro,
        f.valSaldoCuentaAhorro2,
        f.valSubsidioNacional,
        f.valAporteLote,
        f.valSaldoCesantias,
        f.valAporteAvanceObra,
        f.valCredito,
        f.valAporteMateriales,
        f.valDonacion,
        f.valTotalRecursos
    from t_frm_formulario f
    inner join t_frm_hogar h on f.seqformulario = h.seqFormulario
    inner join t_ciu_ciudadano c on h.seqCiudadano = c.seqCiudadano
    where f.seqPlanGobierno = 3
";

$arrSql['actos administrativos'] = "
    select 
        f.seqFormularioActo as seqFormulario,
        c.seqCiudadanoActo as seqCiudadano,
        c.valIngresos,
        f.valIngresoHogar,
        f.valSaldoCuentaAhorro,
        f.valSaldoCuentaAhorro2,
        f.valSubsidioNacional,
        f.valAporteLote,
        f.valSaldoCesantias,
        f.valAporteAvanceObra,
        f.valCredito,
        f.valAporteMateriales,
        f.valDonacion,
        f.valTotalRecursos
    from t_aad_formulario_acto f
    inner join t_aad_hogar_acto h on f.seqformularioActo = h.seqFormularioActo
    inner join t_aad_ciudadano_acto c on h.seqCiudadanoActo = c.seqCiudadanoActo
    where f.seqPlanGobierno = 3
";

foreach($arrSql as $item => $value){

    mensajeLog("Inicia Procesamiento para $item");

    $objRes = $aptBd->execute($value);

    $arrIngresos = array();
    $arrFinancieros = array();
    $seqFormulario = 0;
    $seqCiudadano = 0;

    while($objRes->fields) {

        $seqFormulario = $objRes->fields['seqFormulario'];
        $seqCiudadano  = $objRes->fields['seqCiudadano'];

        // ingresos de los ciudadanos
        $arrIngresos[$seqFormulario]['valIngresoHogar'] = $objRes->fields['valIngresoHogar'];
        if(! isset($arrIngresos[$seqFormulario]['sumaIngresos'])){
            $arrIngresos[$seqFormulario]['sumaIngresos'] = $objRes->fields['valIngresos'];
        }else{
            $arrIngresos[$seqFormulario]['sumaIngresos'] += $objRes->fields['valIngresos'];
        }

        // financieros
        $arrFinancieros[$seqFormulario]['valTotalRecursos'] = $objRes->fields['valTotalRecursos'];
        $arrFinancieros[$seqFormulario]['sumaRecursos'] =
            $objRes->fields['valSaldoCuentaAhorro'] +
            $objRes->fields['valSaldoCuentaAhorro2'] +
            $objRes->fields['valSaldoCesantias'] +
            $objRes->fields['valCredito'] +
            $objRes->fields['valAporteLote'] +
            $objRes->fields['valSubsidioNacional'] +
            $objRes->fields['valDonacion'];

        $objRes->MoveNext();
    }


    try {

        $aptBd->BeginTrans();

        mensajeLog("Revisando ingresos del hogar");
        mensajeLog("Formulario\t|\tSuma BD\t|\tSuma Corregida");
        foreach ($arrIngresos as $seqFormulario => $arrIngreso) {
            if ($arrIngreso['valIngresoHogar'] != $arrIngreso['sumaIngresos']) {
                $sql = "update " . $arrTablas[$item]['tabla'] . " set valIngresoHogar = " . $arrIngreso['sumaIngresos'] . " where " . $arrTablas[$item]['campo'] . " = " . $seqFormulario;

                $aptBd->execute($sql);
                mensajeLog("$seqFormulario\t|\t" . $arrIngreso['valIngresoHogar'] . "\t|\t" . $arrIngreso['sumaIngresos']);
            }
        }

        mensajeLog("Revisando suma de recursos");
        mensajeLog("Formulario\t|\tSuma BD\t|\tSuma Corregida");
        foreach ($arrFinancieros as $seqFormulario => $arrRecursos) {
            if ($arrRecursos['valTotalRecursos'] != $arrRecursos['sumaRecursos']) {
                $sql = "update " . $arrTablas[$item]['tabla'] . " set valTotalRecursos = " . $arrRecursos['sumaRecursos'] . " where " . $arrTablas[$item]['campo'] . " = " . $seqFormulario;
                $aptBd->execute($sql);
                mensajeLog("$seqFormulario\t|\t" . $arrRecursos['valTotalRecursos'] . "\t|\t" . $arrRecursos['sumaRecursos']);
            }
        }

        $aptBd->CommitTrans();

    }catch (Exception $objError){
        $aptBd->RollbackTrans();
        mensajeLog( $objError->getMessage() );
        exit();
    }

}

?>