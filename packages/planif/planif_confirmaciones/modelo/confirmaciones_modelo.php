<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../" . class_bdI);

class Confirmaciones
{
    private $datos;
    private $data;
    private $bd;

    function __construct()
    {
        $this->datos   = array();
        $this->data  = array("total" => 0, "confirm" => 0, "in_transport" => 0);
        $this->bd = new Database;
    }

    function get_planif($ficha, $cliente, $ubicacion, $horarios)
    {
        $this->datos  = array();
        $where = " WHERE a.fecha = CURRENT_DATE 
            AND a.cod_cliente = clientes.codigo 
            AND a.cod_ubicacion = clientes_ubicacion.codigo 
            AND clientes_ubicacion.cod_estado = estados.codigo
            AND a.cod_puesto_trabajo = clientes_ub_puesto.codigo 
            AND a.cod_ficha = ficha.cod_ficha 
            AND a.cod_turno = turno.codigo 
            AND turno.cod_horario = horarios.codigo 
            AND horarios.cod_concepto = conceptos.codigo 
            AND conceptos.asist_perfecta = 'T' 
            AND cargos.codigo = ficha.cod_cargo
            AND ficha.cod_cargo NOT IN (SELECT cod_cargo FROM cargos_excl_confirm)
        ";

        if ($cliente != 'TODOS' && $cliente != "" && $cliente != null) {
            $where .= " AND a.cod_cliente = '$cliente'";
        }

        if ($ubicacion != 'TODOS' && $ubicacion != "" && $ubicacion != null) {
            $where .= " AND a.cod_ubicacion = '$ubicacion'";
        }

        if ($ficha != 'TODOS' && $ficha != "" && $ficha != null) {
            $where .= " AND ficha.cod_ficha = '$ficha'";
        }

        if($horarios != "" && $horarios != null){
            if(!in_array("TODOS", $horarios)){
                $i = 0;
                foreach ($horarios as $value) {
                    if ($i == 0) {
                        $where .= " AND ((horarios.codigo  = " . $value . ") ";
                    } else {
                        $where .= " OR (horarios.codigo = " . $value .")";
                    }
                    $i++;
                };
                $where .= ") ";
            }
        }

        $sql = "SELECT
                    a.codigo,
                    estados.descripcion estado,
                    clientes.nombre cliente,
                    clientes.abrev abrev_cliente,
                    clientes_ubicacion.descripcion ubicacion,
                    a.cod_ficha ficha,
                    ficha.cedula,
                    ficha.telefono,
                    CONCAT( ficha.apellidos, ' ', ficha.nombres ) ap_nombre,
                    turno.descripcion turno,
                    cargos.descripcion cargo,
                    conceptos.abrev concepto,
                    a.fecha,
                    IFNULL(
                        (SELECT hora_entrada FROM horario_cl_ubicacion
                            WHERE horario_cl_ubicacion.cod_cl_ubicacion = clientes_ubicacion.codigo 
                            AND ficha.cod_cargo = horario_cl_ubicacion.cod_cargo
                            AND horario_cl_ubicacion.cod_horario = horarios.codigo), 
                        horarios.hora_entrada
                    ) hora_entrada,
                    -- TIMESTAMPDIFF(
                    --    MINUTE,
                    --    CURRENT_TIMESTAMP,
                    -- CONCAT( CURRENT_DATE, ' ', horarios.hora_entrada )) diff_min,
                    a.confirm,
                    a.in_transport,
                    a.asistencia,
            		TIME(a.fec_confirm) fec_confirm,
					TIME(a.fec_in_transport) fec_in_transport,
                    TIME(a.fec_asistencia) fec_asistencia,
                    a.cierre_confirmado,
                    clientes_ubicacion.reconocimiento_facial,
                    a.cod_observacion_asisto,
                    a.cod_observacion_asistencia
                FROM
                    planif_clientes_trab_det a,
                    clientes,
                    clientes_ubicacion,
                    clientes_ub_puesto,
                    ficha,
                    turno,
                    horarios,
                    conceptos,
                    cargos,
                    estados
                " . $where . " 
                -- HAVING diff_min >= 0
                -- HAVING ((diff_min > 60 AND diff_min < 120 AND confirm = 'F') OR (diff_min > 15 AND diff_min < 60 AND confirm = 'T' AND in_transport = 'F') OR (confirm = 'T' AND in_transport = 'T'))
                ORDER BY hora_entrada ASC, clientes_ubicacion.codigo ASC;";

        $query = $this->bd->consultar($sql);
        while ($datos = $this->bd->obtener_fila($query)) {
            $this->datos[] = $datos;
        }
        return $this->datos;
    }

    function get_estadistica($ficha, $cliente, $ubicacion, $horarios)
    {
        $this->datos  = array();
        $this->data  = array("total" => 0, "confirm" => 0, "in_transport" => 0, "asistencia" => 0);
        $where = " WHERE a.fecha = CURRENT_DATE 
            AND a.cod_cliente = clientes.codigo 
            AND a.cod_ubicacion = clientes_ubicacion.codigo 
            AND a.cod_puesto_trabajo = clientes_ub_puesto.codigo 
            AND a.cod_ficha = ficha.cod_ficha 
            AND a.cod_turno = turno.codigo 
            AND turno.cod_horario = horarios.codigo 
            AND horarios.cod_concepto = conceptos.codigo 
            AND conceptos.asist_perfecta = 'T' 
            AND ficha.cod_cargo NOT IN (SELECT cod_cargo FROM cargos_excl_confirm)
        ";

        if ($cliente != 'TODOS' && $cliente != "" && $cliente != null) {
            $where .= " AND a.cod_cliente = '$cliente'";
        }

        if ($ubicacion != 'TODOS' && $ubicacion != "" && $ubicacion != null) {
            $where .= " AND a.cod_ubicacion = '$ubicacion'";
        }

        if ($ficha != 'TODOS' && $ficha != "" && $ficha != null) {
            $where .= " AND ficha.cod_ficha = '$ficha'";
        }

        if($horarios != "" && $horarios != null){
            if(!in_array("TODOS", $horarios)){
                $i = 0;
                foreach ($horarios as $value) {
                    if ($i == 0) {
                        $where .= " AND ((horarios.codigo  = " . $value . ") ";
                    } else {
                        $where .= " OR (horarios.codigo = " . $value .")";
                    }
                    $i++;
                };
                $where .= ") ";
            }
        }

        $sql = "SELECT
                    COUNT(a.codigo) total
                FROM
                    planif_clientes_trab_det a,
                    clientes,
                    clientes_ubicacion,
                    clientes_ub_puesto,
                    ficha,
                    turno,
                    horarios,
                    conceptos 
                " . $where . ";";

        $query = $this->bd->consultar($sql);
        $this->datos = $this->bd->obtener_fila($query, 0);
        $this->data["total"] = $this->datos["total"];

        $sql2 = "SELECT
                COUNT(a.codigo) total
            FROM
                planif_clientes_trab_det a,
                clientes,
                clientes_ubicacion,
                clientes_ub_puesto,
                ficha,
                turno,
                horarios,
                conceptos 
            " . $where . " AND a.confirm = 'T';";

        $query2 = $this->bd->consultar($sql2);
        $this->datos = $this->bd->obtener_fila($query2, 0);
        $this->data["confirm"] = $this->datos["total"];

        $sql3 = "SELECT
                COUNT(a.codigo) total
            FROM
                planif_clientes_trab_det a,
                clientes,
                clientes_ubicacion,
                clientes_ub_puesto,
                ficha,
                turno,
                horarios,
                conceptos 
            " . $where . " AND a.in_transport = 'T';";

        $query3 = $this->bd->consultar($sql3);
        $this->datos = $this->bd->obtener_fila($query3, 0);
        $this->data["in_transport"] = $this->datos["total"];

        $sql4 = "SELECT
                COUNT(a.codigo) total
            FROM
                planif_clientes_trab_det a,
                clientes,
                clientes_ubicacion,
                clientes_ub_puesto,
                ficha,
                turno,
                horarios,
                conceptos 
            " . $where . " AND a.asistencia = 'T' AND clientes_ubicacion.reconocimiento_facial = 'T';";

        $query4 = $this->bd->consultar($sql4);
        $this->datos = $this->bd->obtener_fila($query4, 0);
        $this->data["asistencia"] = $this->datos["total"];
        
        return $this->data;
    }

    function get_observaciones()
    {
        $this->datos  = array();

        $sql = "SELECT codigo, descripcion FROM observaciones_asisto WHERE status = 'T';";

        $query = $this->bd->consultar($sql);
        while ($datos = $this->bd->obtener_fila($query)) {
            $this->datos[] = $datos;
        }
        return $this->datos;
    }

    function get_data_base($codigo)
    {
        $this->datos  = array();
        $sql = "SELECT
                    planif_clientes_trab_det.cod_ficha,
                    v_ficha.ap_nombre,
                    v_ficha.cliente,
                    v_ficha.ubicacion,
                    v_ficha.cargo,
                    IFNULL(
                        (
                            SELECT
                                hora_entrada 
                            FROM
                                horario_cl_ubicacion 
                            WHERE
                                horario_cl_ubicacion.cod_cl_ubicacion = planif_clientes_trab_det.cod_ubicacion 
                                AND v_ficha.cod_cargo = horario_cl_ubicacion.cod_cargo
                                AND horario_cl_ubicacion.cod_horario = horarios.codigo 
                        ),
                        horarios.hora_entrada 
                    ) hora_entrada,
                    planif_clientes_trab_det.observacion,
                    planif_clientes_trab_det.cod_observacion_asisto,
                    observaciones_asisto.descripcion observacion_asisto,
                    planif_clientes_trab_det.cod_observacion_asistencia,
                    oas.descripcion observacion_asistencia,
                    planif_clientes_trab_det.observacion_asistencia observacion_asistencia_ad
                FROM
                    planif_clientes_trab_det 
                    LEFT JOIN observaciones_asisto ON planif_clientes_trab_det.cod_observacion_asisto = observaciones_asisto.codigo
                    LEFT JOIN observaciones_asisto as oas ON planif_clientes_trab_det.cod_observacion_asistencia = oas.codigo,
                    v_ficha,
                    turno,
                    horarios
                WHERE
                    planif_clientes_trab_det.codigo = $codigo 
                    AND planif_clientes_trab_det.cod_ficha = v_ficha.cod_ficha 
                    AND planif_clientes_trab_det.cod_turno = turno.codigo 
                    AND turno.cod_horario = horarios.codigo;";

        $query = $this->bd->consultar($sql);
        return $this->datos = $this->bd->obtener_fila($query);
    }

    function planif_sin_verify($ubicacion, $horarios){
        $this->datos  = array();

        $where = " WHERE
                    a.fecha = CURRENT_DATE 
                    AND a.cod_ficha = ficha.cod_ficha 
                    AND a.cod_turno = turno.codigo 
                    AND turno.cod_horario = horarios.codigo 
                    AND horarios.cod_concepto = conceptos.codigo 
                    AND conceptos.asist_perfecta = 'T' 
                    AND ficha.cod_cargo NOT IN ( SELECT cod_cargo FROM cargos_excl_confirm ) 
                    AND a.cod_ubicacion = $ubicacion 
                    AND a.cod_ubicacion = clientes_ubicacion.codigo 
                    AND (
                        ( a.cod_observacion_asisto = '' OR a.cod_observacion_asisto IS NULL ) 
                        OR ( 
                            clientes_ubicacion.reconocimiento_facial = 'T' 
                            AND ( a.cod_observacion_asistencia = '' OR a.cod_observacion_asistencia IS NULL ) 
                        ) 
                    ) 
                    AND a.confirm = 'F' 
                    AND a.asistencia = 'F'
        ";


        if($horarios != "" && $horarios != null){
            if(!in_array("TODOS", $horarios)){
                $i = 0;
                foreach ($horarios as $value) {
                    if ($i == 0) {
                        $where .= " AND ((horarios.codigo  = " . $value . ") ";
                    } else {
                        $where .= " OR (horarios.codigo = " . $value .")";
                    }
                    $i++;
                };
                $where .= ") ";
            }
        }

        $sql = "SELECT
                    a.codigo
                FROM
                    planif_clientes_trab_det a,
                    clientes_ubicacion,
                    ficha,
                    turno,
                    horarios,
                    conceptos
                $where;";

        $query = $this->bd->consultar($sql);
        while ($datos = $this->bd->obtener_fila($query)) {
            $this->datos[] = $datos;
        }
        return $this->datos;

    }
}
