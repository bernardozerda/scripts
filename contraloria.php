<?php

include ("./conexion.php");
include ("./funciones.php");

$sql = "
    select
        hvi.seqTipoActo,
        hvi.numActo,
        hvi.fchActo,
        hvi.seqFormularioActo,
        fac.seqFormulario
    from t_aad_hogares_vinculados hvi
    inner join t_aad_formulario_acto fac ON hvi.seqFormularioActo = fac.seqFormularioActo
    order by hvi.seqTipoActo, hvi.fchActo
";
$res = $aptBd->execute($sql);
while($res->fields){
    $numero = $res->fields['numActo'];
    $fecha = new DateTime($res->fields['fchActo']);
    $tipo = $res->fields['seqTipoActo'];
    $formulario = $res->fields['seqFormulario'];
    $asignaciones[$formulario]['actos'][$tipo][] = "Res. $numero de " . $fecha->format("Y");
    $asignaciones[$formulario]['ordenes'] = array();
    $res->MoveNext();
}

$sql = "
    select 
        sol.seqSolicitud,
        des.seqFormulario,
        sol.numOrden, 
        sol.fchOrden, 
        sol.valOrden
    from t_des_desembolso des
    inner join t_des_solicitud sol on des.seqDesembolso = sol.seqDesembolso
    where sol.fchOrden is not null and valOrden <> 0
    order by sol.fchOrden
";
$res = $aptBd->execute($sql);
$maximo = 0;
$cantidad = 0;
while ($res->fields){
    $solicitud = $res->fields['seqSolicitud'];
    $formulario = $res->fields['seqFormulario'];
    $cantidad = count($asignaciones[$formulario]['ordenes']);
    $maximo = ($cantidad > $maximo)? $cantidad : $maximo;
    $asignaciones[$formulario]['ordenes'][$solicitud]['numero'] = $res->fields['numOrden'];
    $asignaciones[$formulario]['ordenes'][$solicitud]['fecha'] = new DateTime($res->fields['fchOrden']);
    $asignaciones[$formulario]['ordenes'][$solicitud]['valor'] = $res->fields['valOrden'];
    $res->MoveNext();
}

echo "Formulario\t";
echo "Asignación / Vinculación\t";
echo "Resolución Modificatoria\t";
echo "Resolución de Inhabilitados\t";
echo "Recurso de Reposicion\t";
echo "Resolucion de No Asignado\t";
echo "Renuncia\t";
echo "Notificaciones\t";
echo "Resolución de Indexación\t";
echo "Resolución de Pérdida\t";
echo "Revocatoria / Desvinculación\t";
echo "Resolución de Exclusión\t";
for($i = 1 ; $i <= $maximo ; $i++){
    echo "OP $i\t";
    echo "Fecha OP $i\t";
    echo "Año de desembolso OP $i\t";
    echo "Valor OP $i\t";
}
echo "\r\n";

foreach($asignaciones as $formulario => $datos){
    echo $formulario . "\t";
    for($i = 1; $i < 12; $i++){
        if(isset($datos['actos'][$i])) {
            echo implode(",", $datos['actos'][$i]);
        }
        echo "\t";
    }
    if(! empty($datos['ordenes'])) {
        foreach ($datos['ordenes'] as $solicitud => $orden) {
            echo $orden['numero'] . "\t";
            echo $orden['fecha']->format("Y-m-d") . "\t";
            echo $orden['fecha']->format("Y") . "\t";
            echo $orden['valor'] . "\t";
        }
    }
    echo "\r\n";
}

?>