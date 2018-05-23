<?php

ini_set("memory_limit","-1");

include("./conexion.php");

echo "\r\n";

$aptBd->BeginTrans();

$archivo = file("./archivo.txt");

unset($archivo[0]);
$total = count($archivo);
foreach($archivo as $linea => $registro){

    echo "Procesando linea " . ($linea) . " de " . $total . "\r\n";

    $registro = explode("\t",$registro);

    try {

        $formulario = intval($registro[6]);
        $estado     = intval($registro[20]);
        $plan       = intval($registro[21]);
        $modalidad  = intval($registro[22]);
        $esquema    = intval($registro[23]);
        $comentario = trim(mb_ereg_replace("\n","",$registro[24]));

        // obtiene documento
        $sql = "
          select c2.numDocumento
          from t_frm_hogar hog
          inner join t_ciu_ciudadano c2 on hog.seqCiudadano = c2.seqCiudadano
          where hog.seqFormulario = $formulario
          order by hog.seqParentesco
          limit 1
        ";
        $documento = $aptBd->GetAll($sql);
        $documento = $documento[0]['numDocumento'];

        // obtiene nombre principal
        $sql = "
          select upper(concat(c2.txtNombre1,' ',c2.txtNombre2,' ',c2.txtApellido1,' ',c2.txtApellido2)) as txtNombre
          from t_frm_hogar hog
          inner join t_ciu_ciudadano c2 on hog.seqCiudadano = c2.seqCiudadano
          where hog.seqFormulario = $formulario
          order by hog.seqParentesco
          limit 1
        ";
        $nombre = $aptBd->GetAll($sql);
        $nombre = $nombre[0]['txtNombre'];

        // obtiene datos actuales
        $sql = "
          select
            seqEstadoProceso,
            seqPlanGobierno,
            seqModalidad,
            seqTipoEsquema
          from t_frm_formulario
          where seqFormulario = $formulario
        ";
        $original = $aptBd->GetAll($sql);
        $original = $original[0];

        $cambios = "<b>[ $formulario ] Cambios en el formulario</b><br>";
        $cambios .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;seqEstadoProceso, Valor Anterior: " . intval($original['seqEstadoProceso']) . ", Valor Nuevo: " . $estado . "<br>";
        $cambios .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;seqPlanGobierno, Valor Anterior: " . intval($original['seqPlanGobierno']) . ", Valor Nuevo: " . $plan . "<br>";
        $cambios .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;seqModalidad, Valor Anterior: " . intval($original['seqModalidad']) . ", Valor Nuevo: " . $modalidad . "<br>";
        $cambios .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;seqTipoEsquema, Valor Anterior: " . intval($original['seqTipoEsquema']) . ", Valor Nuevo: " . $esquema . "<br>";

        // cambios formulario
        $sql = "
          update t_frm_formulario set
            seqEstadoProceso = $estado,
            seqPlanGobierno = $plan,
            seqModalidad = $modalidad,
            seqTipoEsquema = $esquema,
            fchPostulacion = null,
            txtFormulario = '',
            fchUltimaActualizacion = now()
          where seqFormulario = $formulario
        ";
        $aptBd->execute($sql);

        // seguimiento
        if(doubleval($documento) != 0 and $nombre != "" ) {
            $sql = "
              insert into t_seg_seguimiento(
                seqFormulario,
                fchMovimiento,
                seqUsuario,
                txtComentario,
                txtCambios,
                numDocumento,
                txtNombre,
                seqGestion
              ) values(
                $formulario,
                now(),
                5,
                '$comentario',
                '$cambios',
                $documento,
                '$nombre',
                46
              );
            ";
            $aptBd->execute($sql);
        }

    }catch(Exception $error){
        echo "\t" . $error->getMessage() . "\r\n";
        $aptBd->RollbackTrans();
        exit();
    }

}

$aptBd->CommitTrans();
$aptBd->Close();

?>