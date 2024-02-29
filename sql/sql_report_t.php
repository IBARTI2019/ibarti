<?php
$sql_region   = "SELECT regiones.codigo, regiones.descripcion
                   FROM regiones WHERE regiones.`status` = 'T'
                  ORDER BY regiones.descripcion ASC ";

$sql_contrato  = "SELECT contractos.codigo, contractos.descripcion
                    FROM contractos WHERE contractos.`status` = 'T'
                ORDER BY descripcion ASC ";

$sql_contracto = "SELECT contractos.codigo, contractos.descripcion
                    FROM contractos WHERE contractos.`status` = 'T'
                ORDER BY descripcion ASC ";

$sql_proveedor  = "SELECT proveedores.codigo, proveedores.nombre descripcion
                    FROM proveedores WHERE proveedores.`status` = 'T'
                ORDER BY descripcion ASC ";

$sql_n_contracto = " SELECT ficha_n_contracto.codigo, ficha_n_contracto.descripcion
                       FROM ficha_n_contracto
					  ORDER BY 2 ASC ";


$sql_dosis_covid19 = " SELECT ficha_dosis_covid19.codigo, ficha_dosis_covid19.descripcion
                       FROM ficha_dosis_covid19
					  ORDER BY 2 ASC ";

$sql_concepto    = " SELECT conceptos.codigo, conceptos.descripcion
                      FROM conceptos
				     ORDER BY 2 ASC ";

$sql_concepto_as = " SELECT conceptos.codigo,  conceptos.abrev, conceptos.descripcion
                      FROM conceptos WHERE conceptos.asist_diaria = 'T'
				     ORDER BY 2 ASC ";

$sql_concepto_categoria = " SELECT concepto_categoria.codigo, concepto_categoria.descripcion
                             FROM concepto_categoria
				            ORDER BY 2 ASC ";


if ($_SESSION['r_rol'] == "F") {
  $sql_rol           = "SELECT roles.codigo, roles.descripcion
							FROM roles  WHERE roles.`status` = 'T'
						ORDER BY descripcion ASC ";

  $select_rol     = '<option value="TODOS">TODOS</option>';
} else {
  $sql_rol = " SELECT roles.codigo, roles.descripcion
				           FROM roles, usuario_roles
                          WHERE roles.`status` = 'T'
                            AND roles.codigo = usuario_roles.cod_rol
                            AND usuario_roles.cod_usuario = '" . $_SESSION['usuario_cod'] . "'
				       ORDER BY 2 ASC ";
  $select_rol     = '<option value="">Seleccione...</option>';
}

$sql_servicio_rol = "SELECT servicio_rol.codigo, servicio_rol.descripcion
                       FROM servicio_rol ORDER BY descripcion ASC ";

$sql_estado   = "SELECT estados.codigo, estados.descripcion
                   FROM estados , control
                  WHERE estados.cod_pais = control.cod_pais AND estados.`status` = 'T'
				  ORDER BY 2 ASC ";

$sql_ciudad = "SELECT ciudades.codigo, ciudades.descripcion
                 FROM ciudades
				ORDER BY descripcion ASC ";

$sql_usuario = "SELECT men_usuarios.codigo, men_usuarios.cedula,
                       CONCAT(men_usuarios.nombre,' ',men_usuarios.apellido) AS usuario
                  FROM men_usuarios WHERE men_usuarios.`status` = 'T'
       	      ORDER BY usuario ASC ";

$sql_trabajador = "SELECT v_ficha.cod_ficha, v_ficha.cedula, v_ficha.nombres
                  FROM v_ficha
       	      ORDER BY nombres ASC ";

$sql_ficha_status = "SELECT ficha_status.codigo, ficha_status.descripcion
                       FROM ficha_status
       	              ORDER BY 2 ASC ";

$sql_cargo      = "SELECT cargos.codigo, cargos.descripcion
                     FROM cargos
					WHERE cargos.`status` = 'T' ORDER BY 2 ASC";

$sql_turno         = " SELECT turno.codigo, turno.descripcion
                        FROM turno WHERE turno.`status` = 'T'
				       ORDER BY 2 ASC";

$sql_horario       = " SELECT horarios.codigo, horarios.nombre
                         FROM horarios
                        WHERE horarios.`status` = 'T'
				        ORDER BY 2 ASC";

$sql_rotacion       = " SELECT rotacion.codigo, rotacion.descripcion
                         FROM rotacion
                        WHERE rotacion.`status` = 'T'
				        ORDER BY 2 ASC";

$sql_documento   = "SELECT documentos.codigo, documentos.descripcion
                     FROM documentos
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

if ($_SESSION['r_cliente'] == "F") {
  $sql_cliente    = "SELECT clientes.codigo, clientes.nombre
						 FROM clientes WHERE clientes.`status` = 'T'
						ORDER BY 2 ASC ";

  $select_cl     = '<option value="TODOS">TODOS</option>';
} else {
  $sql_cliente = " SELECT clientes.codigo, clientes.nombre
                       FROM clientes
					   WHERE clientes.`status` = 'T'
                        AND clientes.codigo IN (SELECT DISTINCT clientes_ubicacion.cod_cliente
                       FROM usuario_clientes, clientes_ubicacion
                      WHERE usuario_clientes.cod_usuario = '" . $_SESSION['usuario_cod'] . "'
                        AND usuario_clientes.cod_ubicacion = clientes_ubicacion.codigo)
				   ORDER BY 2 ASC ";

  $select_cl     = '<option value="">Seleccione...</option>';
}

$sql_cliente_ch    = "SELECT DISTINCT clientes.codigo, clientes.nombre
  		                    FROM clientes_ub_ch, clientes, clientes_ubicacion
                         WHERE clientes_ub_ch.cod_cl_ubicacion = clientes_ubicacion.codigo
                           AND clientes_ubicacion.cod_cliente = clientes.codigo
                           AND clientes.`status` = 'T'
         	               ORDER BY 2 ASC ";

$sql_ubicacion    = "SELECT clientes_ubicacion.codigo, CONCAT(clientes.abrev, ' - ', clientes_ubicacion.descripcion) AS descripcion
                       FROM clientes, clientes_ubicacion
                      WHERE clientes.`status` = 'T'
                        AND clientes.codigo = clientes_ubicacion.cod_cliente
					    AND clientes_ubicacion.`status` = 'T'
       	              ORDER BY 2 ASC ";

$sql_armamento    = "SELECT productos.codigo, productos.descripcion, productos.item AS serial
                       FROM productos , control
                      WHERE productos.cod_linea = control.control_arma_linea
                      ORDER BY 2, 3 ASC";

$sql_producto     = "SELECT productos.codigo, productos.descripcion, productos.item AS serial
                       FROM productos , control
                   ORDER BY 2, 3 ASC";

$sql_prod_tipo     = "SELECT prod_tipos.codigo, prod_tipos.descripcion
                        FROM prod_tipos  WHERE prod_tipos.`status` = 'T' ORDER BY 2 ASC";


$sql_linea       = "SELECT prod_lineas.codigo, prod_lineas.descripcion
                      FROM prod_lineas WHERE prod_lineas.`status` = 'T'
          ORDER BY 2 ASC";

$sql_tipo_mov_alcance =   "SELECT prod_mov_tipo.codigo, prod_mov_tipo.descripcion, prod_mov_tipo.tipo_movimiento 
          FROm prod_mov_tipo WHERE prod_mov_tipo.codigo = 'AJUS-' OR prod_mov_tipo.codigo = 'AJUS+'
          ORDER BY 2 ASC";

$sql_tipo_mov      = "SELECT prod_mov_tipo.codigo, prod_mov_tipo.descripcion,
                             prod_mov_tipo.tipo_movimiento
                        FROM prod_mov_tipo WHERE prod_mov_tipo.`status` = 'T'
            ORDER BY 2 ASC";

$sql_tipo_mov      = "SELECT prod_mov_tipo.codigo, prod_mov_tipo.descripcion,
                             prod_mov_tipo.tipo_movimiento
                        FROM prod_mov_tipo WHERE prod_mov_tipo.`status` = 'T'
				    ORDER BY 2 ASC";

$sql_novedad     = "SELECT novedades.codigo, novedades.descripcion
                      FROM novedades
					 WHERE novedades.`status` = 'T' ORDER BY 2 ASC";


$sql_nov_novedad = "SELECT novedades.codigo, novedades.descripcion
				               FROM novedades , nov_perfiles, nov_clasif
                              WHERE novedades.`status` = 'T'
                                AND novedades.cod_nov_clasif = nov_clasif.codigo
                                AND nov_clasif.codigo = nov_perfiles.cod_nov_clasif
                                AND nov_perfiles.cod_perfil = '" . $_SESSION['cod_perfil'] . "'
                                AND nov_clasif.campo04 = 'F'
                           ORDER BY 2 ASC";


$sql_nov_novedad_ing = "SELECT novedades.codigo, novedades.descripcion
                       FROM novedades , nov_perfiles, nov_clasif
                              WHERE novedades.`status` = 'T'
                                AND novedades.cod_nov_clasif = nov_clasif.codigo
                                AND nov_clasif.codigo = nov_perfiles.cod_nov_clasif
                                AND nov_perfiles.cod_perfil = '" . $_SESSION['cod_perfil'] . "'
                                AND nov_perfiles.ingreso = 'T'
                                AND nov_clasif.campo04 = 'F'
                           ORDER BY 2 ASC";


$sql_nov_novedad_resp = "SELECT novedades.codigo, novedades.descripcion
                       FROM novedades , nov_perfiles, nov_clasif
                              WHERE novedades.`status` = 'T'
                                AND novedades.cod_nov_clasif = nov_clasif.codigo
                                AND nov_clasif.codigo = nov_perfiles.cod_nov_clasif
                                AND nov_perfiles.cod_perfil = '" . $_SESSION['cod_perfil'] . "'
                                AND nov_perfiles.respuesta = 'T'
                                AND nov_clasif.campo04 = 'F'
                           ORDER BY 2 ASC";

$sql_nov_status = "SELECT nov_status.codigo, nov_status.descripcion
                     FROM nov_status
				    WHERE nov_status.`status` = 'T' ORDER BY 2 ASC";

$sql_nov_clasif = "SELECT nov_clasif.codigo, nov_clasif.descripcion
                     FROM nov_clasif
					WHERE nov_clasif.`status` = 'T' ORDER BY 2 ASC";

$sql_nov_clasif2 = "SELECT nov_clasif.codigo,  nov_clasif.descripcion
				               FROM nov_perfiles, nov_clasif
                              WHERE nov_perfiles.cod_nov_clasif = nov_clasif.codigo
                                AND nov_perfiles.cod_perfil = '" . $_SESSION['cod_perfil'] . "'
                                AND nov_clasif.campo04 = 'F'
                           ORDER BY 2 ASC";



$sql_nov_tipo = "SELECT nov_tipo.codigo, nov_tipo.descripcion
                   FROM nov_tipo
				  WHERE nov_tipo.`status` = 'T' ORDER BY 2 ASC";

$sql_nov_agrupacion = "SELECT nov_agrupacion.codigo, nov_agrupacion.descripcion
                      FROM nov_agrupacion
                      WHERE nov_agrupacion.`status` = 'T'  ORDER BY 2 ASC ";


$sql_perfil = "SELECT men_perfiles.codigo, men_perfiles.descripcion
                 FROM men_perfiles ORDER BY 2 ASC";
