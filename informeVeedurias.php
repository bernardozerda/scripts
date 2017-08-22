<?php

chdir( __DIR__ );
include ("./conexionLocal.php");
include ("./funciones.php");
include ("./funcionesVeedurias.php");
echo "\r";

$bolErrores = false; // HAY ERRORES true // NO HAY ERRORES false
$aptLocal->BeginTrans();

/*****************************************************************************************
 * CREACION DEL CORTE
 *****************************************************************************************/

$seqCorte = crearCorte( $aptLocal );
$bolErrores = ( intval( $seqCorte ) == 0 )? true : false;

/*****************************************************************************************
 * HOGARES RELACIONADOS CON ACTOS ADMINISTRATIVOS
 *****************************************************************************************/

if( $bolErrores == false ){
    $arrHogares = obtenerHogares( $aptLocal );
    $bolErrores = ( empty( $arrHogares ) )? true : false;
}

/*****************************************************************************************
 * COPIA DEL MODULO DE HOGARES
 *****************************************************************************************/

if( $bolErrores == false ) {
    $bolErrores = copiarFormularios($aptLocal, $seqCorte, $arrHogares);
}

if( $bolErrores == false ) {
    $bolErrores = copiarCiudadanos($aptLocal, $seqCorte, $arrHogares);
}

if( $bolErrores == false ) {
    $bolErrores = armarHogar($aptLocal, $seqCorte, $arrHogares);
}

/*****************************************************************************************
 * COPIA DEL MODULO DE DESEMBOLSOS
 *****************************************************************************************/

if( $bolErrores == false ) {
    $bolErrores = copiarDesembolso($aptLocal, $seqCorte, $arrHogares);
}

if( $bolErrores == false ) {
    $bolErrores = copiarEscrituracion($aptLocal, $seqCorte, $arrHogares);
}

if( $bolErrores == false ) {
    $bolErrores = copiarSolicitudes($aptLocal, $seqCorte, $arrHogares);
}

/*****************************************************************************************
 * COPIA DEL MODULO DE PROYECTOS
 *****************************************************************************************/

if( $bolErrores == false ) {
    $bolErrores = copiarProyectos($aptLocal, $seqCorte);
}

if( $bolErrores == false ) {
    $bolErrores = copiarUnidades($aptLocal, $seqCorte);
}

if( $bolErrores == false ) {
    $bolErrores = copiarActosProyectos($aptLocal, $seqCorte);
}

if( $bolErrores == false ) {
    $bolErrores = copiarUnidadesVinculadas($aptLocal, $seqCorte);
}

/*****************************************************************************************
 * CONFIRMANDO TRANSACCION
 *****************************************************************************************/

if( $bolErrores == false ){
    $aptLocal->CommitTrans();
}else{
    $aptLocal->RollbackTrans();
}

$aptLocal->Close();
mensajeLog("Fin de la creacion del corte de " . mes2texto(date("m")) . " " . date("Y") );

?>