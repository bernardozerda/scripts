<?php

chdir( __DIR__ );
include ("./conexion.php");

$corte = 12;

$sql[] = "SET FOREIGN_KEY_CHECKS=0";

$sql[] = " 
    delete
    from t_vee_adjuntos_titulos
    where seqEstudioTitulosVeeduria in(
      select seqEstudioTitulosVeeduria
      from t_vee_estudio_titulos
      where seqDesembolsoVeeduria in (
        select seqDesembolsoVeeduria
        from t_vee_desembolso
        where seqFormularioVeeduria in (
          select seqFormularioVeeduria
          from t_vee_formulario
          where seqCorte = $corte
        )
      )
    )
";

$sql[] = "
    delete
    from t_vee_estudio_titulos
    where seqDesembolsoVeeduria in (
      select seqDesembolsoVeeduria
      from t_vee_desembolso
      where seqFormularioVeeduria in (
        select seqFormularioVeeduria
        from t_vee_formulario
        where seqCorte = $corte
      )
    )
";

$sql[] = "
    delete
    from t_vee_solicitud
    where seqDesembolsoVeeduria in (
      select seqDesembolsoVeeduria
      from t_vee_desembolso
      where seqFormularioVeeduria in (
        select seqFormularioVeeduria
        from t_vee_formulario
        where seqCorte = $corte
      )
    )
";

$sql[] = "
    delete
    from t_vee_desembolso
    where seqFormularioVeeduria in (
      select seqFormularioVeeduria
      from t_vee_formulario
      where seqCorte = $corte
    )
";

$sql[] = "
    delete
    from t_vee_escrituracion
    where seqFormularioVeeduria in (
        select seqFormularioVeeduria
      from t_vee_formulario
      where seqCorte = $corte
    )
";

$sql[] = "
    delete
    from t_vee_hogar
    where seqFormularioVeeduria in (
      select seqFormularioVeeduria
      from t_vee_formulario
      where seqCorte = $corte
    )
";

$sql[] = "
    delete
    from t_vee_formulario
    where seqCorte = $corte
";

$sql[] = "
    delete
    from t_vee_ciudadano
    where seqCorte = $corte
";

$sql[] = "
    delete
    from t_vee_unidad_proyecto
    where seqProyectoVeeduria in (
        select seqProyectoVeeduria
      from t_vee_proyecto
      where seqCorte = $corte
    )
";

$sql[] = "
    delete
    from t_vee_proyecto
    where seqCorte = $corte
";

$sql[] = "
    delete
    from t_vee_unidades_vinculadas
    where seqUnidadActoVeeduria in (
        select seqUnidadActoVeeduria
        from t_vee_unidad_acto
        where seqCorte = $corte
    )
";

$sql[] = "
    delete
    from t_vee_unidad_acto
    where seqCorte = $corte
";

$sql[] = "
    delete
    from t_vee_corte
    where seqCorte = $corte
";

$sql[] = "SET FOREIGN_KEY_CHECKS=1";

foreach($sql as $i => $query){
    echo $i . "\r\n";
    $aptBd->execute($query);
}

?>