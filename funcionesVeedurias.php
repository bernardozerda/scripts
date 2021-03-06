<?php

function mes2texto($numMes){
    switch($numMes){
        case 1: $txtMes = "Enero"; break;
        case 2: $txtMes = "Febrero"; break;
        case 3: $txtMes = "Marzo"; break;
        case 4: $txtMes = "Abril"; break;
        case 5: $txtMes = "Mayo"; break;
        case 6: $txtMes = "Junio"; break;
        case 7: $txtMes = "Julio"; break;
        case 8: $txtMes = "Agosto"; break;
        case 9: $txtMes = "Septiembre"; break;
        case 10: $txtMes = "Octubre"; break;
        case 11: $txtMes = "Noviembre"; break;
        case 12: $txtMes = "Diciembre"; break;
    }
    return $txtMes;
}

function crearCorte( $aptBd )
{
    try {
        mensajeLog("Creando la informacion del corte");
        $txtAnio = date( "Y" );
        $txtMes  = mes2texto(date( "m" ));
        $sql = "INSERT INTO t_vee_corte(txtCorte,fchCorte,seqUsuario) VALUES ('" . $txtMes . " " . $txtAnio . "',NOW(),1)";
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
                    seqConvenio,
                    valComplementario
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
                        frm.seqConvenio,
                        frm.valComplementario
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

function armarHogar($aptBd, $seqCorte , $arrHogares)
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
                    inner join t_vee_formulario frm on hog.seqFormulario = frm.seqFormulario and frm.seqCorte = $seqCorte
                    inner join t_vee_ciudadano ciu on hog.seqCiudadano = ciu.seqCiudadano and ciu.seqCorte = $seqCorte
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

function copiarDesembolso($aptBd, $seqCorte, $arrHogares)
{
    try {
        mensajeLog("Copia la informacion de los desembolsos");
        $sql = "
            INSERT INTO t_vee_desembolso
            (
                seqCorte,
                seqDesembolso,
                seqFormularioVeeduria,
                numEscrituraPublica,
                numCertificadoTradicion,
                numCartaAsignacion,
                numAltoRiesgo,
                numHabitabilidad,
                numBoletinCatastral,
                numLicenciaConstruccion,
                numUltimoPredial,
                numUltimoReciboAgua,
                numUltimoReciboEnergia,
                numOtros,
                txtNombreVendedor,
                numDocumentoVendedor,
                txtDireccionInmueble,
                txtBarrio,
                seqLocalidad,
                txtEscritura,
                numNotaria,
                fchEscritura,
                numAvaluo,
                valInmueble,
                txtMatriculaInmobiliaria,
                numValorInmueble,
                txtEscrituraPublica,
                txtCertificadoTradicion,
                txtCartaAsignacion,
                txtAltoRiesgo,
                txtHabitabilidad,
                txtBoletinCatastral,
                txtLicenciaConstruccion,
                txtUltimoPredial,
                txtUltimoReciboAgua,
                txtUltimoReciboEnergia,
                txtOtro,
                txtViabilizoJuridico,
                txtViabilizoTecnico,
                bolViabilizoJuridico,
                bolviabilizoTecnico,
                bolPoseedor,
                txtChip,
                numActaEntrega,
                txtActaEntrega,
                numCertificacionVendedor,
                txtCertificacionVendedor,
                numAutorizacionDesembolso,
                txtAutorizacionDesembolso,
                numFotocopiaVendedor,
                txtFotocopiaVendedor,
                seqTipoDocumento,
                txtCompraVivienda,
                txtNit,
                txtRit,
                txtRut,
                numNit,
                numRit,
                numRut,
                txtTipoPredio,
                numTelefonoVendedor,
                txtCedulaCatastral,
                numAreaConstruida,
                numAreaLote,
                txtTipoDocumentos,
                numEstrato,
                txtCiudad,
                fchCreacionBusquedaOferta,
                fchActualizacionBusquedaOferta,
                fchCreacionEscrituracion,
                fchActualizacionEscrituracion,
                numTelefonoVendedor2,
                txtPropiedad,
                fchSentencia,
                numJuzgado,
                txtCiudadSentencia,
                numResolucion,
                fchResolucion,
                txtEntidad,
                txtCiudadResolucion,
                numContratoArrendamiento,
                txtContratoArrendamiento,
                numAperturaCAP,
                txtAperturaCAP,
                numCedulaArrendador,
                txtCedulaArrendador,
                numCuentaArrendador,
                txtCuentaArrendador,
                numRetiroRecursos,
                txtRetiroRecursos,
                numServiciosPublicos,
                txtServiciosPublicos,
                txtCorreoVendedor,
                seqCiudad,
                seqAplicacionSubsidio,
                seqProyectosSoluciones,
                seqFrmulario_Des
            )
            select
                $seqCorte,
                des.seqDesembolso,
                frm.seqFormularioVeeduria,
                des.numEscrituraPublica,
                des.numCertificadoTradicion,
                des.numCartaAsignacion,
                des.numAltoRiesgo,
                des.numHabitabilidad,
                des.numBoletinCatastral,
                des.numLicenciaConstruccion,
                des.numUltimoPredial,
                des.numUltimoReciboAgua,
                des.numUltimoReciboEnergia,
                des.numOtros,
                des.txtNombreVendedor,
                des.numDocumentoVendedor,
                des.txtDireccionInmueble,
                des.txtBarrio,
                des.seqLocalidad,
                des.txtEscritura,
                des.numNotaria,
                des.fchEscritura,
                des.numAvaluo,
                des.valInmueble,
                des.txtMatriculaInmobiliaria,
                des.numValorInmueble,
                des.txtEscrituraPublica,
                des.txtCertificadoTradicion,
                des.txtCartaAsignacion,
                des.txtAltoRiesgo,
                des.txtHabitabilidad,
                des.txtBoletinCatastral,
                des.txtLicenciaConstruccion,
                des.txtUltimoPredial,
                des.txtUltimoReciboAgua,
                des.txtUltimoReciboEnergia,
                des.txtOtro,
                des.txtViabilizoJuridico,
                des.txtViabilizoTecnico,
                des.bolViabilizoJuridico,
                des.bolviabilizoTecnico,
                des.bolPoseedor,
                des.txtChip,
                des.numActaEntrega,
                des.txtActaEntrega,
                des.numCertificacionVendedor,
                des.txtCertificacionVendedor,
                des.numAutorizacionDesembolso,
                des.txtAutorizacionDesembolso,
                des.numFotocopiaVendedor,
                des.txtFotocopiaVendedor,
                des.seqTipoDocumento,
                des.txtCompraVivienda,
                des.txtNit,
                des.txtRit,
                des.txtRut,
                des.numNit,
                des.numRit,
                des.numRut,
                des.txtTipoPredio,
                des.numTelefonoVendedor,
                des.txtCedulaCatastral,
                des.numAreaConstruida,
                des.numAreaLote,
                des.txtTipoDocumentos,
                des.numEstrato,
                des.txtCiudad,
                des.fchCreacionBusquedaOferta,
                des.fchActualizacionBusquedaOferta,
                des.fchCreacionEscrituracion,
                des.fchActualizacionEscrituracion,
                des.numTelefonoVendedor2,
                des.txtPropiedad,
                des.fchSentencia,
                des.numJuzgado,
                des.txtCiudadSentencia,
                des.numResolucion,
                des.fchResolucion,
                des.txtEntidad,
                des.txtCiudadResolucion,
                des.numContratoArrendamiento,
                des.txtContratoArrendamiento,
                des.numAperturaCAP,
                des.txtAperturaCAP,
                des.numCedulaArrendador,
                des.txtCedulaArrendador,
                des.numCuentaArrendador,
                des.txtCuentaArrendador,
                des.numRetiroRecursos,
                des.txtRetiroRecursos,
                des.numServiciosPublicos,
                des.txtServiciosPublicos,
                des.txtCorreoVendedor,
                des.seqCiudad,
                des.seqAplicacionSubsidio,
                des.seqProyectosSoluciones,
                des.seqFrmulario_Des
            from t_des_desembolso des
            inner join t_vee_formulario frm on des.seqFormulario = frm.seqFormulario and frm.seqCorte = $seqCorte
            where des.seqFormulario in (" . implode(",",$arrHogares) . ")                                    
        ";
        $aptBd->execute($sql);
        $bolErrores = false; // no hubo errores
    }catch(Exception $objError){
        mensajeLog( "Problemas al copiar los desembolsos" );
        mensajeLog( $objError->getMessage() );
        $bolErrores = true;
    }
    return $bolErrores;
}

function copiarEscrituracion($aptBd, $seqCorte, $arrHogares)
{
    try {
        mensajeLog("Copia la informacion de las escrituraciones de los desembolsos");
        $sql = "
            INSERT INTO t_vee_escrituracion
            (
                seqEscrituracion,
                seqDesembolsoVeeduria,
                seqFormularioVeeduria,
                numEscrituraPublica,
                numCertificadoTradicion,
                numCartaAsignacion,
                numAltoRiesgo,
                numHabitabilidad,
                numBoletinCatastral,
                numLicenciaConstruccion,
                numUltimoPredial,
                numUltimoReciboAgua,
                numUltimoReciboEnergia,
                numOtros,
                txtNombreVendedor,
                numDocumentoVendedor,
                txtDireccionInmueble,
                txtBarrio,
                seqLocalidad,
                txtEscritura,
                numNotaria,
                fchEscritura,
                numAvaluo,
                valInmueble,
                txtMatriculaInmobiliaria,
                numValorInmueble,
                txtEscrituraPublica,
                txtCertificadoTradicion,
                txtCartaAsignacion,
                txtAltoRiesgo,
                txtHabitabilidad,
                txtBoletinCatastral,
                txtLicenciaConstruccion,
                txtUltimoPredial,
                txtUltimoReciboAgua,
                txtUltimoReciboEnergia,
                txtOtro,
                txtViabilizoJuridico,
                txtViabilizoTecnico,
                bolViabilizoJuridico,
                bolviabilizoTecnico,
                bolPoseedor,
                txtChip,
                numActaEntrega,
                txtActaEntrega,
                numCertificacionVendedor,
                txtCertificacionVendedor,
                numAutorizacionDesembolso,
                txtAutorizacionDesembolso,
                numFotocopiaVendedor,
                txtFotocopiaVendedor,
                seqTipoDocumento,
                txtCompraVivienda,
                txtNit,
                txtRit,
                txtRut,
                numNit,
                numRit,
                numRut,
                txtTipoPredio,
                numTelefonoVendedor,
                txtCedulaCatastral,
                numAreaConstruida,
                numAreaLote,
                txtTipoDocumentos,
                numEstrato,
                txtCiudad,
                fchCreacionBusquedaOferta,
                fchActualizacionBusquedaOferta,
                fchCreacionEscrituracion,
                fchActualizacionEscrituracion,
                numTelefonoVendedor2,
                txtPropiedad,
                fchSentencia,
                numJuzgado,
                txtCiudadSentencia,
                numResolucion,
                fchResolucion,
                txtEntidad,
                txtCiudadResolucion,
                numContratoArrendamiento,
                txtContratoArrendamiento,
                numAperturaCAP,
                txtAperturaCAP,
                numCedulaArrendador,
                txtCedulaArrendador,
                numCuentaArrendador,
                txtCuentaArrendador,
                numRetiroRecursos,
                txtRetiroRecursos,
                numServiciosPublicos,
                txtServiciosPublicos,
                txtCorreoVendedor,
                seqCiudad,
                seqAplicacionSubsidio,
                seqProyectosSoluciones
            )
            select
                esc.seqEscrituracion,
                des.seqDesembolsoVeeduria,
                des.seqFormularioVeeduria,
                esc.numEscrituraPublica,
                esc.numCertificadoTradicion,
                esc.numCartaAsignacion,
                esc.numAltoRiesgo,
                esc.numHabitabilidad,
                esc.numBoletinCatastral,
                esc.numLicenciaConstruccion,
                esc.numUltimoPredial,
                esc.numUltimoReciboAgua,
                esc.numUltimoReciboEnergia,
                esc.numOtros,
                esc.txtNombreVendedor,
                esc.numDocumentoVendedor,
                esc.txtDireccionInmueble,
                esc.txtBarrio,
                esc.seqLocalidad,
                esc.txtEscritura,
                esc.numNotaria,
                esc.fchEscritura,
                esc.numAvaluo,
                esc.valInmueble,
                esc.txtMatriculaInmobiliaria,
                esc.numValorInmueble,
                esc.txtEscrituraPublica,
                esc.txtCertificadoTradicion,
                esc.txtCartaAsignacion,
                esc.txtAltoRiesgo,
                esc.txtHabitabilidad,
                esc.txtBoletinCatastral,
                esc.txtLicenciaConstruccion,
                esc.txtUltimoPredial,
                esc.txtUltimoReciboAgua,
                esc.txtUltimoReciboEnergia,
                esc.txtOtro,
                esc.txtViabilizoJuridico,
                esc.txtViabilizoTecnico,
                esc.bolViabilizoJuridico,
                esc.bolviabilizoTecnico,
                esc.bolPoseedor,
                esc.txtChip,
                esc.numActaEntrega,
                esc.txtActaEntrega,
                esc.numCertificacionVendedor,
                esc.txtCertificacionVendedor,
                esc.numAutorizacionDesembolso,
                esc.txtAutorizacionDesembolso,
                esc.numFotocopiaVendedor,
                esc.txtFotocopiaVendedor,
                esc.seqTipoDocumento,
                esc.txtCompraVivienda,
                esc.txtNit,
                esc.txtRit,
                esc.txtRut,
                esc.numNit,
                esc.numRit,
                esc.numRut,
                esc.txtTipoPredio,
                esc.numTelefonoVendedor,
                esc.txtCedulaCatastral,
                esc.numAreaConstruida,
                esc.numAreaLote,
                esc.txtTipoDocumentos,
                esc.numEstrato,
                esc.txtCiudad,
                esc.fchCreacionBusquedaOferta,
                esc.fchActualizacionBusquedaOferta,
                esc.fchCreacionEscrituracion,
                esc.fchActualizacionEscrituracion,
                esc.numTelefonoVendedor2,
                esc.txtPropiedad,
                esc.fchSentencia,
                esc.numJuzgado,
                esc.txtCiudadSentencia,
                esc.numResolucion,
                esc.fchResolucion,
                esc.txtEntidad,
                esc.txtCiudadResolucion,
                esc.numContratoArrendamiento,
                esc.txtContratoArrendamiento,
                esc.numAperturaCAP,
                esc.txtAperturaCAP,
                esc.numCedulaArrendador,
                esc.txtCedulaArrendador,
                esc.numCuentaArrendador,
                esc.txtCuentaArrendador,
                esc.numRetiroRecursos,
                esc.txtRetiroRecursos,
                esc.numServiciosPublicos,
                esc.txtServiciosPublicos,
                esc.txtCorreoVendedor,
                esc.seqCiudad,
                esc.seqAplicacionSubsidio,
                esc.seqProyectosSoluciones
            from t_des_escrituracion esc
            inner join t_vee_desembolso des on des.seqDesembolso = esc.seqDesembolso
            inner join t_vee_formulario frm on des.seqFormularioVeeduria = frm.seqFormularioVeeduria and frm.seqCorte = $seqCorte
            where frm.seqFormulario in (" . implode(",",$arrHogares) . ")           
        ";
        $aptBd->execute($sql);
        $bolErrores = false; // no hubo errores
    }catch(Exception $objError){
        mensajeLog( "Problemas al copiar las escrituraciones de los desembolsos" );
        mensajeLog( $objError->getMessage() );
        $bolErrores = true;
    }
    return $bolErrores;
}

function copiarEstudioTitulos($aptBd, $seqCorte, $arrHogares)
{
    try {
        mensajeLog("Copia la informacion de los estudios de titulos de desembolsos");
        $sql = "
            INSERT INTO t_vee_estudio_titulos(
                seqEstudioTitulos,
                seqDesembolsoVeeduria,
                numEscrituraIdentificacion,
                fchEscrituraIdentificacion,
                numNotariaIdentificacion,
                numEscrituraTitulo,
                fchEscrituraTitulo,
                numNotariaTitulo,
                numFolioMatricula,
                txtZonaMatricula,
                fchMatricula,
                bolSubsidioSDHT,
                bolSubsidioFonvivienda,
                numResolucionFonvivienda,
                numAnoResolucionFonvivienda,
                txtAprobo,
                fchCreacion,
                fchActualizacion,
                txtCiudadTitulo,
                txtCiudadIdentificacion,
                txtElaboro
            )
            SELECT 
                seqEstudioTitulos,
                seqDesembolsoVeeduria,
                numEscrituraIdentificacion,
                fchEscrituraIdentificacion,
                numNotariaIdentificacion,
                numEscrituraTitulo,
                fchEscrituraTitulo,
                numNotariaTitulo,
                numFolioMatricula,
                txtZonaMatricula,
                fchMatricula,
                bolSubsidioSDHT,
                bolSubsidioFonvivienda,
                numResolucionFonvivienda,
                numAnoResolucionFonvivienda,
                txtAprobo,
                fchCreacion,
                fchActualizacion,
                txtCiudadTitulo,
                txtCiudadIdentificacion,
                txtElaboro
            FROM t_des_estudio_titulos tit
            INNER JOIN t_vee_desembolso des ON tit.seqDesembolso = des.seqDesembolso
            INNER JOIN t_vee_formulario frm ON des.seqFormularioVeeduria = frm.seqFormularioVeeduria AND frm.seqCorte = $seqCorte
            WHERE frm.seqFormulario in (" . implode(",",$arrHogares) . ")     
        ";
        $aptBd->execute($sql);

        $sql = "
            insert into t_vee_adjuntos_titulos (
                seqAdjuntoTitulos,
                seqTipoAdjunto,
                seqEstudioTitulosVeeduria,
                txtAdjunto
            )
            select
                adj.seqAdjuntoTitulos,
                adj.seqTipoAdjunto,
                tit.seqEstudioTitulosVeeduria,
                adj.txtAdjunto
            from t_des_adjuntos_titulos adj
            inner join t_vee_estudio_titulos tit on adj.seqEstudioTitulos = tit.seqEstudioTitulos
            inner join t_vee_desembolso des on tit.seqDesembolsoVeeduria = des.seqDesembolsoVeeduria
            inner join t_vee_formulario frm on des.seqFormularioVeeduria = frm.seqFormularioVeeduria and frm.seqCorte = $seqCorte
            where frm.seqFormulario in (" . implode(",",$arrHogares) . ")
        ";
        $aptBd->execute($sql);

        $bolErrores = false; // no hubo errores
    }catch(Exception $objError){
        mensajeLog( "Problemas al copiar los estudios de titulos" );
        mensajeLog( $objError->getMessage() );
        $bolErrores = true;
    }
    return $bolErrores;
}

function copiarSolicitudes($aptBd, $seqCorte, $arrHogares)
{
    try {
        mensajeLog("Copia la informacion de las solicitudes de desembolsos");
        $sql = "
            INSERT INTO t_vee_solicitud
            (
               seqSolicitud,
               numRegistroPresupuestal1,
               fchRegistroPresupuestal1,
               numRegistroPresupuestal2,
               fchRegistroPresupuestal2,
               valSolicitado,
               bolDocumentoBeneficiario,
               txtDocumentoBeneficiario,
               bolDocumentoVendedor,
               txtDocumentoVendedor,
               bolCertificacionBancaria,
               txtCertificacionBancaria,
               bolCartaAsignacion,
               txtCartaAsignacion,
               bolAutorizacion,
               txtAutorizacion,
               txtSubsecretaria,
               bolSubsecretariaEncargado,
               txtSubdireccion,
               bolSubdireccionEncargado,
               txtRevisoSubsecretaria,
               txtElaboroSubsecretaria,
               numRadiacion,
               fchRadicacion,
               numOrden,
               fchOrden,
               valOrden,
               seqDesembolsoVeeduria,
               txtConsecutivo,
               numProyectoInversion,
               txtNombreBeneficiarioGiro,
               numDocumentoBeneficiarioGiro,
               txtDireccionBeneficiarioGiro,
               numTelefonoGiro,
               numCuentaGiro,
               txtTipoCuentaGiro,
               seqBancoGiro,
               fchCreacion,
               fchActualizacion,
               bolRut,
               txtRut,
               bolNit,
               txtNit,
               bolCedulaRepresentante,
               txtCedulaRepresentante,
               bolCamaraComercio,
               txtCamaraComercio,
               bolGiroTercero,
               txtGiroTercero,
               bolBancoArrendador,
               txtBancoArrendador,
               bolActaEntregaFisica,
               txtActaEntregaFisica,
               bolActaLiquidacion,
               txtActaLiquidacion,
               txtCorreoGiro
            )
            select
              sol.seqSolicitud,
              sol.numRegistroPresupuestal1,
              sol.fchRegistroPresupuestal1,
              sol.numRegistroPresupuestal2,
              sol.fchRegistroPresupuestal2,
              sol.valSolicitado,
              sol.bolDocumentoBeneficiario,
              sol.txtDocumentoBeneficiario,
              sol.bolDocumentoVendedor,
              sol.txtDocumentoVendedor,
              sol.bolCertificacionBancaria,
              sol.txtCertificacionBancaria,
              sol.bolCartaAsignacion,
              sol.txtCartaAsignacion,
              sol.bolAutorizacion,
              sol.txtAutorizacion,
              sol.txtSubsecretaria,
              sol.bolSubsecretariaEncargado,
              sol.txtSubdireccion,
              sol.bolSubdireccionEncargado,
              sol.txtRevisoSubsecretaria,
              sol.txtElaboroSubsecretaria,
              sol.numRadiacion,
              sol.fchRadicacion,
              sol.numOrden,
              sol.fchOrden,
              sol.valOrden,
              des.seqDesembolsoVeeduria,
              sol.txtConsecutivo,
              sol.numProyectoInversion,
              sol.txtNombreBeneficiarioGiro,
              sol.numDocumentoBeneficiarioGiro,
              sol.txtDireccionBeneficiarioGiro,
              sol.numTelefonoGiro,
              sol.numCuentaGiro,
              sol.txtTipoCuentaGiro,
              sol.seqBancoGiro,
              sol.fchCreacion,
              sol.fchActualizacion,
              sol.bolRut,
              sol.txtRut,
              sol.bolNit,
              sol.txtNit,
              sol.bolCedulaRepresentante,
              sol.txtCedulaRepresentante,
              sol.bolCamaraComercio,
              sol.txtCamaraComercio,
              sol.bolGiroTercero,
              sol.txtGiroTercero,
              sol.bolBancoArrendador,
              sol.txtBancoArrendador,
              sol.bolActaEntregaFisica,
              sol.txtActaEntregaFisica,
              sol.bolActaLiquidacion,
              sol.txtActaLiquidacion,
              sol.txtCorreoGiro
            from t_des_solicitud sol
            inner join t_vee_desembolso des on des.seqDesembolso = sol.seqDesembolso
            inner join t_vee_formulario frm on des.seqFormularioVeeduria = frm.seqFormularioVeeduria and frm.seqCorte = $seqCorte
            where frm.seqFormulario in (" . implode(",",$arrHogares) . ")           
        ";
        $aptBd->execute($sql);
        $bolErrores = false; // no hubo errores
    }catch(Exception $objError){
        mensajeLog( "Problemas al copiar las solicitudes de desembolsos" );
        mensajeLog( $objError->getMessage() );
        $bolErrores = true;
    }
    return $bolErrores;
}

function copiarProyectos($aptBd, $seqCorte)
{
    try {
        mensajeLog("Copia la informacion de los proyectos");
        $sql = "
            INSERT INTO t_vee_proyecto
            (
                seqCorte,
                bolActivo,
                bolAprobacion,
                bolDireccion,
                bolEquipamientoComunal,
                bolTipoPersonaInterventor,
                fchEscritura,
                fchInscripcion,
                fchRadicadoFinanciero,
                fchRadicadoJuridico,
                fchRadicadoTecnico,
                fchRegistroEnajenacion,
                fchUltimaActualizacion,
                numCedulaInterventor,
                numNitInterventor,
                numNitProyecto,
                numNitVendedor,
                numNotaria,
                numRadicadoFinanciero,
                numRadicadoJuridico,
                numRadicadoTecnico,
                numTelefonoRepLegalInterventor,
                numTProfesionalInterventor,
                seqBarrio,
                seqConstructor,
                seqLocalidad,
                seqOperador,
                seqOpv,
                seqPlanGobierno,
                seqProfesionalResponsable,
                seqProyecto,
                seqProyectoPadre,
                seqPryEstadoProceso,
                seqPryTipoModalidad,
                seqTipoEsquema,
                seqTipoModalidadDesembolso,
                seqTipoOrganizacion,
                seqTipoProyecto,
                seqTipoSolucion,
                seqTipoUrbanizacion,
                seqTutorProyecto,
                seqUsuario,
                txtCedulaCatastral,
                txtChipLote,
                txtCorreoInterventor,
                txtCorreoRepLegalInterventor,
                txtDescEquipamientoComunal,
                txtDescripcionProyecto,
                txtDireccion,
                txtDireccionInterventor,
                txtDireccionRepLegalInterventor,
                txtEscritura,
                txtMatriculaInmobiliariaLote,
                txtNombreComercial,
                txtNombreInterventor,
                txtNombreOperador,
                txtNombrePlanParcial,
                txtNombreProyecto,
                txtNombreRepLegalInterventor,
                txtNombreVendedor,
                txtObjetoProyecto,
                txtOtrosBarrios,
                txtRegistroEnajenacion,
                valAreaConstruida,
                valAreaLote,
                valCierreFinanciero,
                valCostoProyecto,
                valCostosDirectos,
                valCostosIndirectos,
                valCreditoEntidadFinanciera,
                valCreditoParticulares,
                valDevolucionIVA,
                valGastosFinancieros,
                valGastosVentas,
                valMaximoSubsidio,
                valNumeroSoluciones,
                valOtros,
                valRecursosPropios,
                valSDVE,
                valTerreno,
                valTorres,
                valTotalCostos,
                valTotalProyectosVIP,
                valTotalRecursos,
                valTotalVentas,
                valUtilidadProyecto,
                valVentasProyecto
            )
            select distinct 
                $seqCorte,
                pry.bolActivo,
                pry.bolAprobacion,
                pry.bolDireccion,
                pry.bolEquipamientoComunal,
                pry.bolTipoPersonaInterventor,
                pry.fchEscritura,
                pry.fchInscripcion,
                pry.fchRadicadoFinanciero,
                pry.fchRadicadoJuridico,
                pry.fchRadicadoTecnico,
                pry.fchRegistroEnajenacion,
                pry.fchUltimaActualizacion,
                pry.numCedulaInterventor,
                pry.numNitInterventor,
                pry.numNitProyecto,
                pry.numNitVendedor,
                pry.numNotaria,
                pry.numRadicadoFinanciero,
                pry.numRadicadoJuridico,
                pry.numRadicadoTecnico,
                pry.numTelefonoRepLegalInterventor,
                pry.numTProfesionalInterventor,
                pry.seqBarrio,
                pry.seqConstructor,
                pry.seqLocalidad,
                pry.seqOperador,
                pry.seqOpv,
                pry.seqPlanGobierno,
                pry.seqProfesionalResponsable,
                pry.seqProyecto,
                pry.seqProyectoPadre,
                pry.seqPryEstadoProceso,
                pry.seqPryTipoModalidad,
                pry.seqTipoEsquema,
                pry.seqTipoModalidadDesembolso,
                pry.seqTipoOrganizacion,
                pry.seqTipoProyecto,
                pry.seqTipoSolucion,
                pry.seqTipoUrbanizacion,
                pry.seqTutorProyecto,
                pry.seqUsuario,
                pry.txtCedulaCatastral,
                pry.txtChipLote,
                pry.txtCorreoInterventor,
                pry.txtCorreoRepLegalInterventor,
                pry.txtDescEquipamientoComunal,
                pry.txtDescripcionProyecto,
                pry.txtDireccion,
                pry.txtDireccionInterventor,
                pry.txtDireccionRepLegalInterventor,
                pry.txtEscritura,
                pry.txtMatriculaInmobiliariaLote,
                pry.txtNombreComercial,
                pry.txtNombreInterventor,
                pry.txtNombreOperador,
                pry.txtNombrePlanParcial,
                pry.txtNombreProyecto,
                pry.txtNombreRepLegalInterventor,
                pry.txtNombreVendedor,
                pry.txtObjetoProyecto,
                pry.txtOtrosBarrios,
                pry.txtRegistroEnajenacion,
                pry.valAreaConstruida,
                pry.valAreaLote,
                pry.valCierreFinanciero,
                pry.valCostoProyecto,
                pry.valCostosDirectos,
                pry.valCostosIndirectos,
                pry.valCreditoEntidadFinanciera,
                pry.valCreditoParticulares,
                pry.valDevolucionIVA,
                pry.valGastosFinancieros,
                pry.valGastosVentas,
                pry.valMaximoSubsidio,
                pry.valNumeroSoluciones,
                pry.valOtros,
                pry.valRecursosPropios,
                pry.valSDVE,
                pry.valTerreno,
                pry.valTorres,
                pry.valTotalCostos,
                pry.valTotalProyectosVIP,
                pry.valTotalRecursos,
                pry.valTotalVentas,
                pry.valUtilidadProyecto,
                pry.valVentasProyecto
            from t_pry_proyecto pry
            inner join t_pry_unidad_proyecto upr on pry.seqProyecto = upr.seqProyecto                   
        ";
        $aptBd->execute($sql);
        $bolErrores = false; // no hubo errores
    }catch(Exception $objError){
        mensajeLog( "Problemas al copiar los proyectos" );
        mensajeLog( $objError->getMessage() );
        $bolErrores = true;
    }
    return $bolErrores;
}

function copiarUnidades($aptBd, $seqCorte)
{
    try {
        mensajeLog("Copia la informacion de las unidades de los proyectos");
        $sql = "
            INSERT INTO t_vee_unidad_proyecto
            (
               seqUnidadProyecto,
               seqProyectoVeeduria,
               txtNombreUnidad,
               txtNombreUnidadReal,
               txtNombreUnidadAux,
               txtMatriculaInmobiliaria,
               txtChipLote,
               valSDVEAprobado,
               valSDVEActual,
               valSDVEComplementario,
               txtSMMLV,
               txtObservacionComplemento,
               valSDVEComercial,
               valCierreFinanciero,
               seqFormulario,
               bolLegalizado,
               fchLegalizado,
               fchRadicacion,
               txtRadicadoForest,
               fchInformacionSolucion,
               fchInformacionTitulos,
               fchDevolucionExpediente,
               bolActivo,
               seqPlanGobierno,
               seqModalidad,
               seqTipoEsquema
            )
            select
              upr.seqUnidadProyecto,
              pry.seqProyectoVeeduria,
              upr.txtNombreUnidad,
              upr.txtNombreUnidadReal,
              upr.txtNombreUnidadAux,
              upr.txtMatriculaInmobiliaria,
              upr.txtChipLote,
              upr.valSDVEAprobado,
              upr.valSDVEActual,
              upr.valSDVEComplementario,
              upr.txtSMMLV,
              upr.txtObservacionComplemento,
              upr.valSDVEComercial,
              upr.valCierreFinanciero,
              upr.seqFormulario,
              upr.bolLegalizado,
              upr.fchLegalizado,
              upr.fchRadicacion,
              upr.txtRadicadoForest,
              upr.fchInformacionSolucion,
              upr.fchInformacionTitulos,
              upr.fchDevolucionExpediente,
              upr.bolActivo,
              upr.seqPlanGobierno,
              upr.seqModalidad,
              upr.seqTipoEsquema
            from t_vee_proyecto pry
            inner join t_pry_unidad_proyecto upr on pry.seqProyecto = upr.seqProyecto
            where pry.seqCorte = $seqCorte
        ";
        $aptBd->execute($sql);
        $bolErrores = false; // no hubo errores
    }catch(Exception $objError){
        mensajeLog( "Problemas al copiar las unidades de los proyectos" );
        mensajeLog( $objError->getMessage() );
        $bolErrores = true;
    }
    return $bolErrores;
}

function copiarActosProyectos($aptBd, $seqCorte)
{
    try {
        mensajeLog("Copia la informacion de actos administrativos de los proyectos");
        $sql = "
            INSERT INTO t_vee_unidad_acto
            (
              seqCorte,
              seqUnidadActo,
              numActo,
              fchActo,
              seqTipoActoUnidad,
              txtDescripcion,
              fchCreacion,
              seqUsuario
            )
            SELECT 
              $seqCorte,
              uac.seqUnidadActo,
              uac.numActo,
              uac.fchActo,
              uac.seqTipoActoUnidad,
              uac.txtDescripcion,
              uac.fchCreacion,
              uac.seqUsuario
            FROM t_pry_aad_unidad_acto uac            
        ";
        $aptBd->execute($sql);
        $bolErrores = false; // no hubo errores
    }catch(Exception $objError){
        mensajeLog( "Problemas al copiar de actos administrativos de los proyectos" );
        mensajeLog( $objError->getMessage() );
        $bolErrores = true;
    }
    return $bolErrores;
}

function copiarUnidadesVinculadas($aptBd, $seqCorte)
{
    try {
        mensajeLog("Copia la informacion de unidades vinculadas");
        $sql = " 
            INSERT INTO t_vee_unidades_vinculadas
            (
              seqUnidadVinculado,
              seqUnidadActoVeeduria,
              seqUnidadProyectoVeeduria,
              valIndexado
            )
            SELECT 
              uvi.seqUnidadVinculado, 
              uac.seqUnidadActoVeeduria,
              upr.seqUnidadProyectoVeeduria,
              uvi.valIndexado
            FROM t_vee_unidad_acto uac
            inner join t_pry_aad_unidades_vinculadas uvi on uac.seqUnidadActo = uvi.seqUnidadActo
            inner join t_vee_unidad_proyecto upr on uvi.seqUnidadProyecto = upr.seqUnidadProyecto
            inner join t_vee_proyecto pry on upr.seqProyectoVeeduria = pry.seqProyectoVeeduria and pry.seqCorte = $seqCorte
            WHERE uac.seqCorte = $seqCorte                
        ";
        $aptBd->execute($sql);
        $bolErrores = false; // no hubo errores
    }catch(Exception $objError){
        mensajeLog( "Problemas al copiar de unidades vinculadas" );
        mensajeLog( $objError->getMessage() );
        $bolErrores = true;
    }
    return $bolErrores;
}


?>