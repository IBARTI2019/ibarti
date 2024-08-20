<?php
define("SPECIALCONSTANT", true);
include_once('../../../../funciones/funciones.php');
require("../../../../autentificacion/aut_config.inc.php");
require_once("../../../../" . class_bdI);

class Marcaje
{
    private $datos;
    private $bd;

    function __construct()
    {
        $this->datos   = array();
        $this->bd = new Database;
    }

    function get_actividades($ficha, $cliente, $ubicacion)
    {
        $this->datos  = array();
        $where = " WHERE
        p.codigo = pd.cod_planif_cl_trab
        AND pd.cod_proyecto = pp.codigo
        AND pd.cod_actividad = pa.codigo
        ANd p.cod_ubicacion = cu.codigo
        AND DATE_FORMAT(p.fecha_inicio, '%Y-%m-%d') = DATE_FORMAT(CURDATE(), '%Y-%m-%d')
        -- AND TIME(pd.fecha_fin) <= CURRENT_TIME()
        AND p.cod_ficha = '$ficha'
        ";

        if ($cliente != 'TODOS' && $cliente != "" && $cliente != null) {
            $where .= " AND p.cod_cliente = '$cliente'";
        }

        if ($ubicacion != 'TODOS' && $ubicacion != "" && $ubicacion != null) {
            $where .= " AND p.cod_ubicacion = '$ubicacion'";
        }

        $sql = "SELECT
            pd.codigo, cu.descripcion ubicacion, pd.cod_proyecto, pp.descripcion proyecto, pd.cod_actividad, pa.descripcion actividad, 
            IF(pd.realizado = 'T', 'SI', 'NO') realizado, TIME(pd.fecha_inicio) hora_inicio, TIME(pd.fecha_fin) hora_fin,
            pa.participantes,
            (
                SELECT
                    COUNT(b.codigo)
                FROM
                    planif_clientes_superv_trab_det a,
                    planif_clientes_superv_trab_det_observ b
                WHERE
                    a.codigo = b.cod_det
                AND a.codigo = pd.codigo
            ) observaciones,
            (SELECT
                    COUNT(b.codigo)
                    FROM
                        planif_clientes_superv_trab_det a,
                        planif_clientes_superv_trab_det_participantes b
                    WHERE
                        a.codigo = b.cod_det
            AND a.codigo = pd.codigo) fichas
            FROM
                planif_clientes_superv_trab p,
                planif_clientes_superv_trab_det pd,
                planif_proyecto pp,
                planif_actividad pa,
                clientes_ubicacion cu 
                " . $where . " ORDER BY hora_inicio ASC";

        $query = $this->bd->consultar($sql);
        while ($datos = $this->bd->obtener_fila($query)) {
            $this->datos[] = $datos;
        }
        return $this->datos;
    }

    function get_participantes($codigo)
    {
        $this->datos  = array();

        $sql = "SELECT
            v_ficha.cod_ficha,
            v_ficha.ap_nombre,
            v_ficha.cod_cargo,
            v_ficha.cargo,
            b.fec_us_ing fecha,
            b.codigo
        FROM
            planif_clientes_superv_trab_det a,
            planif_clientes_superv_trab_det_participantes b,
            v_ficha
        WHERE
            a.codigo = b.cod_det
        AND v_ficha.cod_ficha = b.cod_ficha
        AND a.codigo = $codigo";

        $query = $this->bd->consultar($sql);
        while ($datos = $this->bd->obtener_fila($query)) {
            $this->datos[] = $datos;
        }
        return $this->datos;
    }

    function get_observaciones($codigo)
    {
        $this->datos  = array();

        $sql = "SELECT
            b.observacion,
            b.codigo
        FROM
            planif_clientes_superv_trab_det a,
            planif_clientes_superv_trab_det_observ b
        WHERE
            a.codigo = b.cod_det
            AND a.codigo = $codigo";

        $query = $this->bd->consultar($sql);
        while ($datos = $this->bd->obtener_fila($query)) {
            $this->datos[] = $datos;
        }
        return $this->datos;
    }
}
