@extends('principal')
@section('contenido')

<form>
    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte partes para venta</h1>
            </div><br>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <!-- @if($stipo == 'Administrador')
                            <a href="{{asset('altaPartesVenta')}}">
                                <button type="button" class="btn btn-primary ">Agregar Parte para venta
                                </button>
                            </a>
                        @endif -->

                            <a href="{{asset('altaPartesVenta')}}">
                                <button type="button" class="btn btn-primary ">Agregar Parte para venta
                                </button>
                            </a>


                    </div>
                </div>
                <br><br>

                <div id='filtroEntradas'>
                <ul class="nav nav-tabs">
                    <li class="active" id='tab2' ><a href="#22" data-toggle="tab"><label for="">Equipos Venta/Reparación/Refurbished</label></a></li>
                    <li class="" id='tab1' ><a href="#11" data-toggle="tab"><label for="">Servicios de paquete</label></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="22">
                        <div id="listEquipos" class="table-responsive"></div>
                    </div>  
                    <div class="tab-pane fade" id="11">
                        <div id="listServPaq" class="table-responsive"></div>                                        
                    </div>
                </div>
            </div>


            </div>
        </div>
    </div>
</form>


<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
var tipoSesion = "<?php echo $tipoSession ?>";
console.log(tipoSesion)
        $(document).ready(function(){

        $("#listServPaq").anexGrid({
            class: 'table table-striped table-bordered table-hover',

            columnas: [
                { leyenda: 'Nombre SKU', ordenable: true, columna: 'nombreRefaccion',filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'Marca', ordenable: true, columna: 'marcaRefaccion',filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'Tipo de SKU', ordenable: true, columna: 'nombreTipoRefaccion', filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'SKU', ordenable: true, columna: 'skuEquivalente', filtro:true, style:'width:500px; background:#C5EBFB;'},
                { leyenda: 'Cantidad stock', ordenable: true, columna: 'cantidad', filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'Precio Venta (pesos)', style:'background:#C5EBFB;'},
                { leyenda: 'Precio Venta (dolares)', style:'background:#C5EBFB;'},
                { leyenda: 'Tipo producto', columna: 'tipoProducto', ordenable: true, style: 'background-color: #C5EBFB;', filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Nuevo', contenido: 'Nuevo' },
                            { valor: 'Refurbished', contenido: 'Refurbished' },
                        ]
                        });
                } },
                { leyenda: 'Presentacion', columna: 'presentacion', ordenable: true, style: 'background-color: #C5EBFB;', filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Pieza', contenido: 'Pieza' },
                            { valor: 'Caja', contenido: 'Caja' },
                            { valor: 'Equipo', contenido: 'Equipo' },
                            { valor: 'Servicio', contenido: 'Servicio' },
                        ]
                        });
                } },
                { leyenda: 'Estatus', columna: 'estatus', ordenable: true, style: 'background-color: #C5EBFB;', filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Stock', contenido: 'Stock' },
                            { valor: 'Solicitado', contenido: 'Solicitado' },
                            { valor: 'Enviado a reparar', contenido: 'Enviado a reparar' },
                        ]
                        });
                } },
                { leyenda: 'Opciones', style:'background:#C5EBFB;'},
            ],
            modelo: [
                { propiedad: 'nombreRefaccion' ,class:'text-center'},
                { propiedad: 'marcaRefaccion' ,class:'text-center'},
                { propiedad: 'nombreTipoRefaccion' ,class:'text-center'},
                { propiedad: 'skuEquivalente' ,class:'text-center'},
                { propiedad: 'cantidad' ,class:'text-center'},
                { propiedad: 'precioVentaPe' ,class:'text-center'},
                { propiedad: 'precioVentaDol' ,class:'text-center'},
                { propiedad: 'tipoProducto' ,class:'text-center'},
                { propiedad: 'presentacion' ,class:'text-center'},
                { propiedad: 'estatus' ,class:'text-center'},
                { class:'text-center', formato: function(tr, obj,celda){

                    let botones ='';

                        if (obj.activo=='Si') {

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-danger',
                                contenido: '<i class="ace-icon fa fa-trash-o bigger-150"> Eliminar</i>',
                                href: 'eliminarPartesVenta/' + obj.idPartesVenta
                            });

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-info',
                                contenido: '<i class="ace-icon fa fa-pencil bigger-120"> Editar</i>',
                                href: 'modificarPartesVenta/' + obj.idPartesVenta
                            });

                        }else{

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-warning',
                                contenido: 'Restaurar',
                                href: 'restaurarPartesVenta/' + obj.idPartesVenta
                            });

                        }
                    return botones;

                    },
                },

            ],
            url: 'filtroPartesVenta',
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'idPartesVenta',
            columna_orden: 'DESC'
        });
        })

</script>

<script>
var tipoSesion = "<?php echo $tipoSession ?>";
console.log(tipoSesion)
        $(document).ready(function(){

        $("#listEquipos").anexGrid({
            class: 'table table-striped table-bordered table-hover',

            columnas: [
                { leyenda: 'Nombre SKU', ordenable: true, columna: 'nombreRefaccion',filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'Marca', ordenable: true, columna: 'marcaRefaccion',filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'Tipo de SKU', ordenable: true, columna: 'nombreTipoRefaccion', filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'SKU', ordenable: true, columna: 'skuEquivalente', filtro:true, style:'width:500px; background:#C5EBFB;'},
                { leyenda: 'Cantidad stock', ordenable: true, columna: 'cantidad', filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'Precio Venta (pesos)', style:'background:#C5EBFB;'},
                { leyenda: 'Precio Venta (dolares)', style:'background:#C5EBFB;'},
                { leyenda: 'Tipo producto', columna: 'tipoProducto', ordenable: true, style: 'background-color: #C5EBFB;', filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Nuevo', contenido: 'Nuevo' },
                            { valor: 'Refurbished', contenido: 'Refurbished' },
                        ]    
                        });
                } },
                { leyenda: 'Presentacion', columna: 'presentacion', ordenable: true, style: 'background-color: #C5EBFB;', filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Pieza', contenido: 'Pieza' },
                            { valor: 'Caja', contenido: 'Caja' },
                            { valor: 'Equipo', contenido: 'Equipo' },
                            { valor: 'Servicio', contenido: 'Servicio' },
                        ]
                        });
                } },
                { leyenda: 'Estatus', columna: 'estatus', ordenable: true, style: 'background-color: #C5EBFB;', filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Stock', contenido: 'Stock' },
                            { valor: 'Solicitado', contenido: 'Solicitado' },
                            { valor: 'Enviado a reparar', contenido: 'Enviado a reparar' },
                        ]
                        });
                } },
                { leyenda: 'Opciones', style:'background:#C5EBFB;'},
            ],
            modelo: [
                { propiedad: 'nombreRefaccion' ,class:'text-center'},
                { propiedad: 'marcaRefaccion' ,class:'text-center'},
                { propiedad: 'nombreTipoRefaccion' ,class:'text-center'},
                { propiedad: 'skuEquivalente' ,class:'text-center'},
                { propiedad: 'cantidad' ,class:'text-center'},
                { propiedad: 'precioVentaPe' ,class:'text-center'},
                { propiedad: 'precioVentaDol' ,class:'text-center'},
                { propiedad: 'tipoProducto' ,class:'text-center'},
                { propiedad: 'presentacion' ,class:'text-center'},
                { propiedad: 'estatus' ,class:'text-center'},
                { class:'text-center', formato: function(tr, obj,celda){

                    let botones ='';

                        if (obj.activo=='Si') {

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-danger',
                                contenido: '<i class="ace-icon fa fa-trash-o bigger-150"> Eliminar</i>',
                                href: 'eliminarPartesVenta/' + obj.idPartesVenta
                            });

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-info',
                                contenido: '<i class="ace-icon fa fa-pencil bigger-120"> Editar</i>',
                                href: 'modificarPartesVenta/' + obj.idPartesVenta
                            });

                        }else{

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-warning',
                                contenido: 'Restaurar',
                                href: 'restaurarPartesVenta/' + obj.idPartesVenta
                            });

                        }
                    return botones;

                    },
                },

            ],
            url: 'filtroEquiposVenta',
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'idPartesVenta',
            columna_orden: 'DESC'
        });
        })

</script>
@stop
