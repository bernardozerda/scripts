<?php

include ("./conexion.php");
include ("./aadMejoramientoConf.php");

$sql = "
    select distinct
      aad.seqActo,
      aad.numActo, 
      aad.fchActo, 
      aad.seqCaracteristica, 
      cac.txtNombreCaracteristica,
      aad.txtValorCaracteristica,
      moa.txtModalidad
    from t_aad_acto_administrativo aad
    left join t_aad_caracteristica_acto cac on aad.seqCaracteristica = cac.seqCaracteristica
    inner join t_aad_hogares_vinculados hvi on aad.numActo = hvi.numActo and aad.fchActo = hvi.fchActo
    inner join t_aad_formulario_acto fac on hvi.seqFormularioActo = fac.seqFormularioActo
    inner join t_frm_modalidad moa on fac.seqModalidad = moa.seqModalidad
    where aad.seqTipoActo = 1
      and aad.seqCaracteristica <> 1
      and fac.seqModalidad in (3,4,8,9,10)
      -- and aad.numActo = 6
    order by 
      aad.numActo, 
      aad.fchActo, 
      aad.seqCaracteristica
";
$res = $aptBd->execute($sql);
$archivo = array();
while($res->fields){

    $acto           = $res->fields['seqActo'];
    $numero         = $res->fields['numActo'];
    $fecha          = $res->fields['fchActo'];
    $caracteristica = $res->fields['seqCaracteristica'];
    $nombre         = $res->fields['txtNombreCaracteristica'];
    $valor          = $res->fields['txtValorCaracteristica'];
    $modalidad      = $res->fields['txtModalidad'];

    $clave = $numero . $fecha;
    $incremento = $conf[$caracteristica]['incremento'];
    $columna = $conf[$caracteristica]['columna'];

//    echo $columna . "\t" . $valor . "\r\n";

    $archivo[$clave][$incremento][0] = $numero;
    $archivo[$clave][$incremento][1] = $fecha;
    $archivo[$clave][$incremento][2] = $modalidad;
    $archivo[$clave][$incremento][$columna] = $valor;
    $archivo[$clave][$incremento][ ($columna + 9) ] = $acto;

    $res->MoveNext();
}

foreach($archivo as $clave => $linea){
    foreach($linea as $incremento => $datos) {
        ksort($archivo[$clave][$incremento]);
    }
}

$nombreArchivo = "./aadMejoramiento.txt";
$apuntador = fopen($nombreArchivo,"w");

foreach($titulos as $texto){
    fwrite($apuntador,$texto."\t");
}
fwrite($apuntador,"\r\n");

foreach($archivo as $clave => $actoAdministrativo){
    foreach($actoAdministrativo as $datos){
        for($columna = 0; $columna < 22; $columna++){
            if(isset($datos[$columna])) {
                fwrite($apuntador, $datos[$columna]);
            }
            fwrite($apuntador, "\t");
        }
        fwrite($apuntador,"\r\n");
    }
}

fclose($apuntador);

?>