<?php
    define("SPECIALCONSTANT", true);
    require "../autentificacion/aut_config.inc.php" ;
    require "../".class_bdI;
    require "../".Leng;
    include "../".Funcion;
    $bd = new DataBase();
    
    //incluye la configuracion por defecto de la libreria dompdf
    require_once('../'.ConfigDomPdf);
//Variable con que se ancla a la base de datos
//$ficha='2435';


$precliente=$_POST['codigo'];

ini_set("memory_limit", "128M");

$sql = " SELECT preclientes.codigo,
preclientes.cod_cl_tipo, clientes_tipos.descripcion cl_tipo,
preclientes.cod_vendedor, vendedores.nombre  vendedor,
              preclientes.cod_region, regiones.descripcion region,
            preclientes.abrev, preclientes.rif,
preclientes.nit, preclientes.nombre, preclientes.telefono,
preclientes.fax, preclientes.direccion, preclientes.dir_entrega,
preclientes.email, preclientes.website, preclientes.contacto,
preclientes.observacion,
preclientes.juridico, preclientes.contribuyente, preclientes.lunes,
preclientes.martes, preclientes.miercoles,  preclientes.jueves,
preclientes.viernes, preclientes.sabado, preclientes.domingo,
preclientes.limite_cred, preclientes.plazo_pago, preclientes.desc_global,
preclientes.desc_p_pago,
preclientes.campo01, preclientes.campo02, preclientes.campo03,
preclientes.campo04,  preclientes.cod_us_ing, preclientes.fec_us_ing,
preclientes.cod_us_mod, preclientes.fec_us_mod, preclientes.status,
IF
	( preclientes.venta_cerrada = 'T', 'SI', 'NO' ) venta_cerrada,
	preclientes.fec_venta_cerrada,
	CONCAT( men_usuarios.nombre, ' ', men_usuarios.apellido ) usuario_venta_cerrada 
FROM preclientes LEFT JOIN men_usuarios ON preclientes.cod_us_venta_cerrada = men_usuarios.codigo,
 clientes_tipos, vendedores, regiones
WHERE preclientes.cod_cl_tipo = clientes_tipos.codigo
AND preclientes.cod_vendedor = vendedores.codigo
AND preclientes.cod_region = regiones.codigo
AND preclientes.codigo = '$precliente'  ";

    $query = $bd->consultar($sql);

    if(!$precliente = $bd->obtener_name($query)){
        echo "<h1>".strlen($precliente)."</h1>";
    echo "<h1>Lo sentimos. No se pudo encontrar una coincidencia para este precliente. Inténtelo de nuevo.</h1>";
    exit;
    };

//Ahora consulto los datos de las cargar familiares
$sqlf="SELECT
b.descripcion ruta,
c.descripcion subruta,
a.comentario,
a.cod_us_ing fecha,
CONCAT(men_usuarios.nombre, ' ', men_usuarios.apellido) usuario
FROM
precliente_rutaventa a,
ruta_de_ventas b,
subruta_de_ventas c,
men_usuarios
WHERE
a.cod_subrutaventa = c.codigo
ANd c.cod_ruta = b.codigo
AND a.cod_us_ing = men_usuarios.codigo
AND a.cod_precliente ='$precliente'
ORDER BY a.codigo ASC";
$queryruta = $bd->consultar($sqlf);


$dompdf= new DOMPDF();

ob_start();
$titulo= 'REPORTE precliente';
require_once('../'.PlantillaDOM.'/unicas/preclientes_ibarti.php');
    $dompdf->load_html(ob_get_clean(),'UTF-8');
    $dompdf->render();
    $pdf=$dompdf->output();
    $dompdf->stream('ficha_ibarti.pdf', array('Attachment' => 0));
    ?>
