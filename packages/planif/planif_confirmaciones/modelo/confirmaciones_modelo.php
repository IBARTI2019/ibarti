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
                    a.codigo,
                    clientes.nombre cliente,
                    clientes_ubicacion.descripcion ubicacion,
                    a.cod_ficha ficha,
                    ficha.cedula,
                    ficha.telefono,
                    CONCAT( ficha.apellidos, ' ', ficha.nombres ) ap_nombre,
                    turno.descripcion turno,
                    horarios.nombre horario,
                    conceptos.abrev concepto,
                    a.fecha,
                    IFNULL(
                        (SELECT hora_entrada FROM horario_cl_ubicacion
                            WHERE horario_cl_ubicacion.cod_cl_ubicacion = clientes_ubicacion.codigo 
                            AND horario_cl_ubicacion.cod_horario = horarios.codigo), 
                        horarios.hora_entrada
                    ) hora_entrada,
                    -- TIMESTAMPDIFF(
                    --    MINUTE,
                    --    CURRENT_TIMESTAMP,
                    -- CONCAT( CURRENT_DATE, ' ', horarios.hora_entrada )) diff_min,
                    a.confirm,
                    a.in_transport,
            		TIME(a.fec_confirm) fec_confirm,
					TIME(a.fec_in_transport) fec_in_transport
                FROM
                    planif_clientes_trab_det a,
                    clientes,
                    clientes_ubicacion,
                    clientes_ub_puesto,
                    ficha,
                    turno,
                    horarios,
                    conceptos 
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
        $this->data  = array("total" => 0, "confirm" => 0, "in_transport" => 0);
        $where = " WHERE a.fecha = CURRENT_DATE 
            AND a.cod_cliente = clientes.codigo 
            AND a.cod_ubicacion = clientes_ubicacion.codigo 
            AND a.cod_puesto_trabajo = clientes_ub_puesto.codigo 
            AND a.cod_ficha = ficha.cod_ficha 
            AND a.cod_turno = turno.codigo 
            AND turno.cod_horario = horarios.codigo 
            AND horarios.cod_concepto = conceptos.codigo 
            AND conceptos.asist_perfecta = 'T' 
            AND ficha.cod_cargo NOT IN ('ARLAT', '063', 'CES', '071', '100', 'ASLL')
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

        return $this->data;
    }
}
