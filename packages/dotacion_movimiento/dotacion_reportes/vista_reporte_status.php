<label style="float:center;">REPORTE ESTATUS DE DOTACION</label>
<br>
<span style="float:left;font-size:14px;">Fecha de Inicio: <input type="button" id="filtro_fecha" value="Buscar" onclick="crear_control(this.id,'f_d','f_h',()=>{crear_reporte()})"><input type="hidden" name="f_d" id="f_d"><input type="hidden" name="f_h" id="f_h"></span>
<span style="float:right;font-size:14px;">Mostrar por:.<select id="select_mostrar" style="width:200px;font-size:14px;" onchange="crear_tabla(this.value,estado, procesos)">
        <option value="fecha">Fechas</option>
        <option value="dias">Diferencia Dias</option>
    </select></span><br><br>
<hr />
<div style="max-height:300px;overflow:scroll;
        overflow-y:scroll;
        overflow-x:hidden;">
    <table width="100%" border="1" id="tabla_detalle">
    </table>
</div>
<div><img title="imprimir a excel" src="imagenes/excel.png" style="width:30px; cursor:pointer;" onclick="reporte('reporte_movimiento','tabla_detalle')"></div>
<script src="libs/control_fecha/fecha.js"></script>
<link rel="stylesheet" href="libs/control_fecha/fecha.css">