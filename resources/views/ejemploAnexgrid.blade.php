@extends('principal')
@section('contenido')
<div id="list"></div>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    $(document).ready(function(){
        $("#list").anexGrid({
            class: 'table table-striped',
            columnas: [
                {leyenda: 'Nombre', ordenable: true, columna: 'nombreRefaccion', filtro: true},
                {leyenda: 'Marca', ordenable: true, columna: 'marcaRefaccion', filtro: true},
                {leyenda: 'Tipo de refacción', ordenable: true, columna: 'nombreTipoRefaccion', filtro: true},
                { leyenda: 'Presentacion', ordenable: true, columna: 'presentacion', filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Pieza', contenido: 'Pieza' },
                            { valor: 'Caja', contenido: 'Caja' },
                        ]
                        });
                } },
                { leyenda: 'Estatus', columna: 'estatus', ordenable: true, filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Stock', contenido: 'Stock' },
                            { valor: 'Solicitado', contenido: 'Solicitado' },
                            { valor: 'Enviado a reparar', contenido: 'Enviado a reparar' },
                        ]
                        });
                } },
                { leyenda: 'Opciones'},
            ],
             modelo: [
                 {propiedad:'nombreRefaccion'},
                 {propiedad:'marcaRefaccion'},
                 {propiedad:'nombreTipoRefaccion'},
                 {propiedad:'presentacion'},
                 {propiedad:'estatus'},
                 { class:'text-center', formato: function(tr, obj,celda){               
                    let botones ='';

                        if (obj.activo=='Si') {

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-danger',
                                contenido: 'Eliminar',
                                href: 'eliminarPartesVenta/' + obj.idPartesVenta
                            });

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-info',
                                contenido: 'Editar',
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
            url: 'filtro',
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'idPartesVenta',
            columna_orden: 'DESC'
        });
    })
</script>
@stop