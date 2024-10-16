<?php
$sql_almacen = "SELECT almacenes.codigo, almacenes.descripcion 
                FROM almacenes 
                WHERE almacenes.status = 'T'
                ORDER BY almacenes.descripcion ASC";

$sql_region = "SELECT regiones.codigo, regiones.descripcion
                 FROM regiones
                ORDER BY regiones.descripcion ASC ";

$sql_contrato = "SELECT contractos.codigo, contractos.descripcion
                   FROM contractos
                  ORDER BY descripcion ASC ";

$sql_contracto = "SELECT contractos.codigo, contractos.descripcion
                    FROM contractos
                ORDER BY descripcion ASC ";

$sql_n_contracto = " SELECT ficha_n_contracto.codigo, ficha_n_contracto.descripcion
                       FROM ficha_n_contracto
					  ORDER BY 2 ASC ";

$sql_concepto    = " SELECT conceptos.codigo, conceptos.descripcion
                      FROM conceptos
				     ORDER BY 2 ASC ";

$sql_apertura = "SELECT a.codigo,CONCAT(a.fecha_inicio,' - ',a.fecha_fin) fecha FROM planif_cliente a
             WHERE a.status = 'T'
          ORDER BY 1 DESC";

$sql_concepto_as = " SELECT conceptos.codigo,  conceptos.abrev, conceptos.descripcion
                      FROM conceptos WHERE conceptos.asist_diaria = 'T'
				     ORDER BY 2 ASC ";

$sql_concepto_categoria = " SELECT concepto_categoria.codigo, concepto_categoria.descripcion
                             FROM concepto_categoria
				            ORDER BY 2 ASC ";

if ($_SESSION['r_rol'] == "F") {
  $sql_rol           = "SELECT roles.codigo, roles.descripcion
							FROM roles
						ORDER BY descripcion ASC ";

  $select_rol     = '<option value="TODOS">TODOS</option>';
} else {
  $sql_rol = " SELECT roles.codigo, roles.descripcion
					   FROM roles, usuario_roles
					  WHERE roles.codigo = usuario_roles.cod_rol
						AND usuario_roles.cod_usuario = '" . $_SESSION['usuario_cod'] . "'
				   ORDER BY 2 ASC ";
  $select_rol     = '<option value="">Seleccione...</option>';
}


$sql_servicio_rol = "SELECT servicio_rol.codigo, servicio_rol.descripcion
                       FROM servicio_rol ORDER BY descripcion ASC ";

$sql_estado = "SELECT estados.codigo, estados.descripcion
                 FROM estados , control
               WHERE estados.cod_pais = control.cod_pais
  		       ORDER BY 2 ASC ";

$sql_ciudad = "SELECT ciudades.codigo, ciudades.descripcion
                 FROM ciudades
				ORDER BY descripcion ASC ";

$sql_usuario = "SELECT men_usuarios.codigo, men_usuarios.cedula,
                       men_usuarios.nombre, men_usuarios.apellido
                  FROM men_usuarios
       	      ORDER BY descripcion ASC ";

$sql_trabajador = "SELECT v_ficha.cod_ficha, v_ficha.cedula, v_ficha.nombres
                  FROM v_ficha
       	      ORDER BY nombres ASC ";

$sql_ficha_status = "SELECT ficha_status.codigo, ficha_status.descripcion
                       FROM ficha_status
       	              ORDER BY 2 ASC ";

$sql_cargo      = "SELECT cargos.codigo, cargos.descripcion
                     FROM cargos ORDER BY 2 ASC";

$sql_turno      = "SELECT turno.codigo, turno.descripcion
                     FROM turno
					ORDER BY 2 ASC";

$sql_horario       = " SELECT horarios.codigo, horarios.nombre
                         FROM horarios
                        ORDER BY 2 ASC";

$sql_rotacion       = " SELECT rotacion.codigo, rotacion.descripcion
                         FROM rotacion
                        ORDER BY 2 ASC";

$sql_documento   = "SELECT documentos.codigo, documentos.descripcion
                     FROM documentos WHERE documentos.`status` = 'T'
					ORDER BY 2 ASC";

$sql_nivel_academico = "SELECT nivel_academico.codigo, nivel_academico.descripcion
                          FROM nivel_academico
						 ORDER BY 2 ASC";

$sql_parentesco = "SELECT parentescos.codigo, parentescos.descripcion
                     FROM parentescos
					ORDER BY 2 ASC";

$sql_preing_status = "SELECT preing_status.codigo, preing_status.descripcion
                        FROM preing_status
					   ORDER BY 2 ASC";

$sql_t_camisa = "SELECT preing_camisas.codigo, preing_camisas.descripcion
                   FROM preing_camisas
				  ORDER BY 2 ASC ";

$sql_t_pantalon = "SELECT preing_pantalon.codigo, preing_pantalon.descripcion
                     FROM preing_pantalon
			     ORDER BY 2 ASC";

$sql_n_zapato  = " SELECT preing_zapatos.codigo, preing_zapatos.descripcion
                     FROM preing_zapatos
                    ORDER BY 2 ASC ";

if ($_SESSION['r_cliente'] == "F") {
  $sql_cliente    = "SELECT clientes.codigo, clientes.nombre
						 FROM clientes
						ORDER BY 2 ASC ";
  $select_cl     = '<option value="TODOS">TODOS</option>';
} else {
  $sql_cliente = " SELECT clientes.codigo, clientes.nombre
                       FROM clientes
                      WHERE clientes.codigo IN (SELECT DISTINCT clientes_ubicacion.cod_cliente
                       FROM usuario_clientes, clientes_ubicacion
                      WHERE usuario_clientes.cod_usuario = '" . $_SESSION['usuario_cod'] . "'
                        AND usuario_clientes.cod_ubicacion = clientes_ubicacion.codigo)
				   ORDER BY 2 ASC ";
  $select_cl     = '<option value="">Seleccione...</option>';
}

$sql_precliente    = "SELECT preclientes.codigo, preclientes.nombre
FROM preclientes WHERE preclientes.status = 'T'
ORDER BY 2 ASC ";
$select_precliente     = '<option value="TODOS">TODOS</option>';

$sql_ruta_venta    = "SELECT ruta_de_ventas.codigo, ruta_de_ventas.descripcion
FROM ruta_de_ventas WHERE ruta_de_ventas.status = 'T'
ORDER BY 2 ASC ";

$sql_cliente_ch    = "SELECT DISTINCT clientes.codigo, clientes.nombre
  		                    FROM clientes_ub_ch, clientes, clientes_ubicacion
                         WHERE clientes_ub_ch.cod_cl_ubicacion = clientes_ubicacion.codigo
                           AND clientes_ubicacion.cod_cliente = clientes.codigo
                      ORDER BY 2 ASC ";


$sql_ubicacion   = "SELECT clientes_ubicacion.codigo, clientes_ubicacion.descripcion
                      FROM clientes_ubicacion
       	             ORDER BY 2 ASC ";

$sql_armamento    = "SELECT productos.codigo, productos.descripcion, productos.item AS serial
                       FROM productos , control
                      WHERE productos.cod_linea = control.control_arma_linea
                      ORDER BY 2, 3 ASC";

$sql_producto     = "SELECT productos.codigo, productos.descripcion, productos.item AS serial
                       FROM productos
                   ORDER BY 2, 3 ASC";


$sql_prod_tipo     = "SELECT prod_tipos.codigo, prod_tipos.descripcion
                        FROM prod_tipos ORDER BY 2 ASC";

$sql_linea       = "SELECT prod_lineas.codigo, prod_lineas.descripcion
                      FROM prod_lineas ORDER BY 2 ASC";

$sql_sub_lineas  = "SELECT prod_sub_lineas.codigo, prod_sub_lineas.descripcion
                      FROM prod_sub_lineas ORDER BY 2 ASC";

$sql_tipo_mov      = " SELECT prod_mov_tipo.codigo, prod_mov_tipo.descripcion,
                              prod_mov_tipo.tipo_movimiento
                         FROM prod_mov_tipo ORDER BY 2 ASC ";

$sql_novedad     = "SELECT novedades.codigo, novedades.descripcion
                      FROM novedades ORDER BY 2 ASC";

$sql_nov_clasif  = "SELECT nov_clasif.codigo, nov_clasif.descripcion
                      FROM nov_clasif ORDER BY 2 ASC";

$sql_nov_status = "SELECT nov_status.codigo, nov_status.descripcion
                     FROM nov_status ORDER BY 2 ASC";

$sql_nov_tipo = "SELECT nov_tipo.codigo, nov_tipo.descripcion
                      FROM nov_tipo ORDER BY 2 ASC ";

$sql_nov_agrupacion = "SELECT nov_agrupacion.codigo, nov_agrupacion.descripcion
                      FROM nov_agrupacion ORDER BY 2 ASC ";

$sql_perfil = "SELECT men_perfiles.codigo, men_perfiles.descripcion
                 FROM men_perfiles ORDER BY 2 ASC";

$sql_area_proyecto = "SELECT area_proyecto.codigo, area_proyecto.descripcion
                  FROM area_proyecto WHERE area_proyecto.status = 'T' ORDER BY 2 ASC";


$sql_proyecto = "SELECT planif_proyecto.codigo, planif_proyecto.descripcion
                  FROM planif_proyecto WHERE planif_proyecto.status = 'T' ORDER BY 2 ASC";


$sql_proyecto_paticipantes = "SELECT
                  planif_proyecto.codigo,
                  planif_proyecto.descripcion
                FROM
                  planif_proyecto,
                  planif_actividad
                WHERE
                  planif_actividad.cod_proyecto = planif_proyecto.codigo
                  AND planif_actividad.participantes = 'T'
                GROUP BY
                  planif_proyecto.codigo
                ORDER BY 2 ASC";

$sql_actividad = "SELECT planif_actividad.codigo, planif_actividad.descripcion
                    FROM planif_actividad ORDER BY 2 ASC";

$sql_actividad_paticipantes = "SELECT planif_actividad.codigo, planif_actividad.descripcion
                    FROM planif_actividad WHERE planif_actividad.participantes = 'T' ORDER BY 2 ASC";
