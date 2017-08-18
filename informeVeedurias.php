<?php

chdir( __DIR__ );
include ("./conexionLocal.php");
include ("./funciones.php");
include ("./funcionesVeedurias.php");
echo "\r";

$bolErrores = false; // HAY ERRORES true // NO HAY ERRORES false
$aptLocal->BeginTrans();

$seqCorte = crearCorte( $aptLocal );
$bolErrores = ( intval( $seqCorte ) == 0 )? true : false;

if( $bolErrores == false ){
    $arrHogares = obtenerHogares( $aptLocal );
    $bolErrores = ( empty( $arrHogares ) )? true : false;
}

if( $bolErrores == false ) {
    $bolErrores = copiarFormularios($aptLocal, $seqCorte, $arrHogares);
}

if( $bolErrores == false ) {
    $bolErrores = copiarCiudadanos($aptLocal, $seqCorte, $arrHogares);
}

if( $bolErrores == false ) {
    $bolErrores = armarHogar($aptLocal, $arrHogares);
}

if( $bolErrores == false ){
    $aptLocal->CommitTrans();
}else{
    $aptLocal->RollbackTrans();
}

$aptLocal->Close();
mensajeLog("Fin de la creacion del corte de " . date("F Y"));

?>