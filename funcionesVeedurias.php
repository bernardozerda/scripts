<?php

function crearCorte( $aptBd )
{
    try {
        mensajeLog("Creando la informacion del corte");
        $sql = "INSERT INTO t_vee_corte(txtCorte,fchCorte,seqUsuario) VALUES ('" . date("F Y") . "',NOW(),1)";
        $aptBd->execute($sql);
        $seqCorte = $aptBd->Insert_ID();
    }catch( Exception $objError ){
        mensajeLog( "Error al insertar la informacion del corte" );
        mensajeLog( $objError->getMessage() );
        $seqCorte = 0;
    }
    return $seqCorte;
}

function obtenerHogares( $aptBd )
{
    try{
        mensajeLog("Obtiene los formularios relacionados con actos administrativos");
        $arrHogares = array();
        $objRes = null;
        $sql = "SELECT DISTINCT fac.seqFormulario FROM t_aad_hogares_vinculados hvi INNER JOIN t_aad_formulario_acto fac ON hvi.seqFormularioActo = fac.seqFormularioActo";
        $objRes = $aptBd->execute($sql);
        while($objRes->fields){
            $arrHogares[] = $objRes->fields['seqFormulario'];
            $objRes->MoveNext();
        }
    }catch( Exception $objError ){
        $arrHogares = array();
        mensajeLog( "Error al insertar la informacion del formulario" );
        mensajeLog( $objError->getMessage() );
    }
    return $arrHogares;
}

function copiarFormularios($aptBd , $seqCorte , $arrHogares){
    try {
        mensajeLog("Copia los formularios");
        $sql = "
            INSERT INTO 
                t_vee_formulario(
                    seqCorte,
                    seqFormulario,
                    txtDireccion,
                    numTelefono1,
                    numTelefono2,
                    numCelular,
                    txtMatriculaInmobiliaria,
                    txtChip,
                    seqUnidadProyecto,
                    bolViabilizada,
                    bolIdentificada,
                    bolDesplazado,
                    seqSolucion,
                    valPresupuesto,
                    valAvaluo,
                    valTotal,
                    seqModalidad,
                    seqBancoCuentaAhorro,
                    fchAperturaCuentaAhorro,
                    bolInmovilizadoCuentaAhorro,
                    valSaldoCuentaAhorro,
                    txtSoporteCuentaAhorro,
                    seqBancoCuentaAhorro2,
                    fchAperturaCuentaAhorro2,
                    bolInmovilizadoCuentaAhorro2,
                    valSaldoCuentaAhorro2,
                    txtSoporteCuentaAhorro2,
                    valSubsidioNacional,
                    seqEntidadSubsidio,
                    txtSoporteSubsidio,
                    valAporteLote,
                    txtSoporteAporteLote,
                    seqCesantias,
                    valSaldoCesantias,
                    txtSoporteCesantias,
                    valAporteAvanceObra,
                    txtSoporteAvanceObra,
                    valAporteMateriales,
                    txtSoporteAporteMateriales,
                    valDonacion,
                    txtSoporteDonacion,
                    seqBancoCredito,
                    valCredito,
                    txtSoporteCredito,
                    valTotalRecursos,
                    valAspiraSubsidio,
                    seqVivienda,
                    valArriendo,
                    bolPromesaFirmada,
                    fchInscripcion,
                    fchPostulacion,
                    fchVencimiento,
                    bolIntegracionSocial,
                    bolSecSalud,
                    bolSecEducacion,
                    bolSecMujer,
                    bolAltaCon,
                    bolIpes,
                    txtOtro,
                    seqSisben,
                    numAdultosNucleo,
                    numNinosNucleo,
                    seqUsuario,
                    bolCerrado,
                    seqLocalidad,
                    valIngresoHogar,
                    seqEstadoProceso,
                    txtDireccionSolucion,
                    txtCorreo,
                    seqEmpresaDonante,
                    seqPuntoAtencion,
                    fchAprobacionCredito,
                    txtBarrio,
                    txtFormulario,
                    numCortes,
                    fchNotificacion,
                    fchUltimaActualizacion,
                    seqProyecto,
                    seqProyectoHijo,
                    seqTipoFinanciacion,
                    seqPeriodo,
                    txtSoporteSubsidioNacional,
                    fchArriendoDesde,
                    txtComprobanteArriendo,
                    bolSancion,
                    fchVigencia,
                    seqCiudad,
                    numPuntajeSisben,
                    seqPlanGobierno,
                    seqTipoDireccion,
                    seqBarrio,
                    seqUpz,
                    seqTipoEsquema,
                    temporal,
                    numHabitaciones,
                    numHacinamiento,
                    bolViabilidadLeasing,
                    valCartaLeasing,
                    numDuracionLeasing,
                    seqConvenio
                )
                    SELECT
                        $seqCorte,
                        frm.seqFormulario,
                        frm.txtDireccion,
                        frm.numTelefono1,
                        frm.numTelefono2,
                        frm.numCelular,
                        frm.txtMatriculaInmobiliaria,
                        frm.txtChip,
                        frm.seqUnidadProyecto,
                        frm.bolViabilizada,
                        frm.bolIdentificada,
                        frm.bolDesplazado,
                        frm.seqSolucion,
                        frm.valPresupuesto,
                        frm.valAvaluo,
                        frm.valTotal,
                        frm.seqModalidad,
                        frm.seqBancoCuentaAhorro,
                        if( frm.fchAperturaCuentaAhorro = '0000-00-00', NULL,frm.fchAperturaCuentaAhorro) as fchAperturaCuentaAhorro,
                        frm.bolInmovilizadoCuentaAhorro,
                        frm.valSaldoCuentaAhorro,
                        frm.txtSoporteCuentaAhorro,
                        frm.seqBancoCuentaAhorro2,
                        frm.fchAperturaCuentaAhorro2,
                        frm.bolInmovilizadoCuentaAhorro2,
                        frm.valSaldoCuentaAhorro2,
                        frm.txtSoporteCuentaAhorro2,
                        frm.valSubsidioNacional,
                        frm.seqEntidadSubsidio,
                        frm.txtSoporteSubsidio,
                        frm.valAporteLote,
                        frm.txtSoporteAporteLote,
                        frm.seqCesantias,
                        frm.valSaldoCesantias,
                        frm.txtSoporteCesantias,
                        frm.valAporteAvanceObra,
                        frm.txtSoporteAvanceObra,
                        frm.valAporteMateriales,
                        frm.txtSoporteAporteMateriales,
                        frm.valDonacion,
                        frm.txtSoporteDonacion,
                        frm.seqBancoCredito,
                        frm.valCredito,
                        frm.txtSoporteCredito,
                        frm.valTotalRecursos,
                        frm.valAspiraSubsidio,
                        frm.seqVivienda,
                        frm.valArriendo,
                        frm.bolPromesaFirmada,
                        frm.fchInscripcion,
                        frm.fchPostulacion,
                        frm.fchVencimiento,
                        frm.bolIntegracionSocial,
                        frm.bolSecSalud,
                        frm.bolSecEducacion,
                        frm.bolSecMujer,
                        frm.bolAltaCon,
                        frm.bolIpes,
                        frm.txtOtro,
                        frm.seqSisben,
                        frm.numAdultosNucleo,
                        frm.numNinosNucleo,
                        frm.seqUsuario,
                        frm.bolCerrado,
                        frm.seqLocalidad,
                        frm.valIngresoHogar,
                        frm.seqEstadoProceso,
                        frm.txtDireccionSolucion,
                        frm.txtCorreo,
                        frm.seqEmpresaDonante,
                        frm.seqPuntoAtencion,
                        frm.fchAprobacionCredito,
                        frm.txtBarrio,
                        frm.txtFormulario,
                        frm.numCortes,
                        frm.fchNotificacion,
                        frm.fchUltimaActualizacion,
                        frm.seqProyecto,
                        frm.seqProyectoHijo,
                        frm.seqTipoFinanciacion,
                        frm.seqPeriodo,
                        frm.txtSoporteSubsidioNacional,
                        frm.fchArriendoDesde,
                        frm.txtComprobanteArriendo,
                        frm.bolSancion,
                        frm.fchVigencia,
                        frm.seqCiudad,
                        frm.numPuntajeSisben,
                        frm.seqPlanGobierno,
                        frm.seqTipoDireccion,
                        frm.seqBarrio,
                        frm.seqUpz,
                        frm.seqTipoEsquema,
                        frm.temporal,
                        frm.numHabitaciones,
                        frm.numHacinamiento,
                        frm.bolViabilidadLeasing,
                        frm.valCartaLeasing,
                        frm.numDuracionLeasing,
                        frm.seqConvenio
                    FROM
                        t_frm_formulario frm
                    WHERE
                        seqFormulario IN (
                          " . implode(",",$arrHogares) . "
                        )            

        ";
        $aptBd->execute($sql);
        $bolErrores = false;
    }catch(Exception $objError){
        mensajeLog( "Problemas al copiar el formulario" );
        mensajeLog( $objError->getMessage() );
        $bolErrores = true;
    }
    return $bolErrores;
}

function copiarCiudadanos($aptBd , $seqCorte , $arrHogares)
{
    try {
        mensajeLog("Copia los ciudadanos");
        $sql = "
            INSERT INTO
                t_vee_ciudadano(
                    seqCorte,
                    seqCiudadano,
                    txtNombre1,
                    txtNombre2,
                    txtApellido1,
                    txtApellido2,
                    fchNacimiento,
                    seqTipoDocumento,
                    numDocumento,
                    valIngresos,
                    seqCajaCompensacion,
                    seqNivelEducativo,
                    seqEtnia,
                    seqEstadoCivil,
                    seqOcupacion,
                    seqCondicionEspecial,
                    seqCondicionEspecial2,
                    seqCondicionEspecial3,
                    seqSexo,
                    bolLgtb,
                    bolBeneficiario,
                    seqSalud,
                    bolCertificadoElectoral,
                    seqTipoVictima,
                    seqGrupoLgtbi,
                    numAnosAprobados,
                    numAfiliacionSalud
                )
                    SELECT
                        $seqCorte,
                        ciu.seqCiudadano,
                        ciu.txtNombre1,
                        ciu.txtNombre2,
                        ciu.txtApellido1,
                        ciu.txtApellido2,
                        ciu.fchNacimiento,
                        ciu.seqTipoDocumento,
                        ciu.numDocumento,
                        ciu.valIngresos,
                        ciu.seqCajaCompensacion,
                        ciu.seqNivelEducativo,
                        ciu.seqEtnia,
                        ciu.seqEstadoCivil,
                        ciu.seqOcupacion,
                        ciu.seqCondicionEspecial,
                        ciu.seqCondicionEspecial2,
                        ciu.seqCondicionEspecial3,
                        ciu.seqSexo,
                        ciu.bolLgtb,
                        ciu.bolBeneficiario,
                        ciu.seqSalud,
                        ciu.bolCertificadoElectoral,
                        ciu.seqTipoVictima,
                        ciu.seqGrupoLgtbi,
                        ciu.numAnosAprobados,
                        ciu.numAfiliacionSalud
                        FROM
                          t_ciu_ciudadano ciu
                        WHERE seqCiudadano IN (
                            select seqCiudadano 
                            from t_frm_hogar
                            where seqFormulario in (
                              " . implode(",",$arrHogares) . "
                            )
                        )
        ";
        $aptBd->execute($sql);
        $bolErrores = false; // no hubo errores
    }catch(Exception $objError){
        mensajeLog( "Problemas al copiar los ciudadanos" );
        mensajeLog( $objError->getMessage() );
        $bolErrores = true;
    }
    return $bolErrores;
}

function armarHogar($aptBd, $arrHogares)
{
    try {
        mensajeLog("Contruye el hogar del formulario y ciudadanos copiados");
        $sql = "
            INSERT INTO
                t_vee_hogar(
                    seqCiudadanoVeeduria,
                    seqFormularioVeeduria,
                    bolSoporteDocumento,
                    seqParentesco
                )
                    select
                        ciu.seqCiudadanoVeeduria,
                        frm.seqFormularioVeeduria,
                        hog.bolSoporteDocumento,
                        hog.seqParentesco
                    from
                      t_frm_hogar hog
                    inner join t_vee_formulario frm on hog.seqFormulario = frm.seqFormulario
                    inner join t_vee_ciudadano ciu on hog.seqCiudadano = ciu.seqCiudadano
                    where
                    hog.seqFormulario in (
                      " . implode(",",$arrHogares) . "
                    )
        ";
        $aptBd->execute($sql);
        $bolErrores = false; // no hubo errores
    }catch(Exception $objError){
        mensajeLog( "Problemas al relacionar formularios y ciudadanos" );
        mensajeLog( $objError->getMessage() );
        $bolErrores = true;
    }
    return $bolErrores;
}

?>