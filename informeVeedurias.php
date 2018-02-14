<?php

chdir( __DIR__ );
include ("./conexion.php");
include ("./funciones.php");
include ("./funcionesVeedurias.php");
echo "\r";

$bolErrores = false; // HAY ERRORES true // NO HAY ERRORES false
$aptBd->BeginTrans();

/*****************************************************************************************
 * CREACION DEL CORTE
 *****************************************************************************************/

$seqCorte = crearCorte( $aptBd );
$bolErrores = ( intval( $seqCorte ) == 0 )? true : false;

/*****************************************************************************************
 * HOGARES RELACIONADOS CON ACTOS ADMINISTRATIVOS
 *****************************************************************************************/

if( $bolErrores == false ){
    $arrHogares = obtenerHogares( $aptBd );
    $bolErrores = ( empty( $arrHogares ) )? true : false;
}

/*****************************************************************************************
 * COPIA DEL MODULO DE HOGARES
 *****************************************************************************************/

if( $bolErrores == false ) {
    $bolErrores = copiarFormularios($aptBd, $seqCorte, $arrHogares);
}

if( $bolErrores == false ) {
    $bolErrores = copiarCiudadanos($aptBd, $seqCorte, $arrHogares);
}

if( $bolErrores == false ) {
    $bolErrores = armarHogar($aptBd, $seqCorte, $arrHogares);
}

/*****************************************************************************************
 * COPIA DEL MODULO DE DESEMBOLSOS
 *****************************************************************************************/

if( $bolErrores == false ) {
    $bolErrores = copiarDesembolso($aptBd, $seqCorte, $arrHogares);
}

if( $bolErrores == false ) {
    $bolErrores = copiarEscrituracion($aptBd, $seqCorte, $arrHogares);
}

if( $bolErrores == false ) {
    $bolErrores = copiarEstudioTitulos($aptBd, $seqCorte, $arrHogares);
}

if( $bolErrores == false ) {
    $bolErrores = copiarSolicitudes($aptBd, $seqCorte, $arrHogares);
}

/*****************************************************************************************
 * COPIA DEL MODULO DE PROYECTOS
 *****************************************************************************************/

if( $bolErrores == false ) {
    $bolErrores = copiarProyectos($aptBd, $seqCorte);
}

if( $bolErrores == false ) {
    $bolErrores = copiarUnidades($aptBd, $seqCorte);
}

if( $bolErrores == false ) {
    $bolErrores = copiarActosProyectos($aptBd, $seqCorte);
}

if( $bolErrores == false ) {
    $bolErrores = copiarUnidadesVinculadas($aptBd, $seqCorte);
}

/*****************************************************************************************
 * CONFIRMANDO TRANSACCION
 *****************************************************************************************/

if( $bolErrores == false ){
    $aptBd->CommitTrans();
}else{
    $aptBd->RollbackTrans();
}

$aptBd->Close();
mensajeLog("Fin de la creacion del corte de " . mes2texto(date("m")) . " " . date("Y") );

?>