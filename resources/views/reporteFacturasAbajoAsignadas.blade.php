@if($cuantos>=1)
<div id="list" class="table-responsive">
    
</div>

<!-- <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
        <thead>
            <tr>
                <th class="b">Folio de la Factura</th>
                <th class="c">Fecha de Facturaciòn</th>
                <th class="c">Número de Trabajadores asignados</th>
                <th>Cliente</th>
                <th class="b">Estatus Entrega</th>
                <th class="d">Estatus Portal</th>
                <th class="b">Estatus Pago</th>
                <th class="a">Cash Flow</th>
                <th class="b">Monto</th>
                <th class="b">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consulta as $v)
            <tr>
                <td>{{$v->numeroFactura}}</td>
                <td>{{$v->fecha}}</td>
                <td>{{$v->canti}}</td>
                <td>{{$v->cli}}</td>
                <td>{{$v->estatusEntrega}}</td>
                <td>{{$v->estatusPortal}}</td>
                <td>{{$v->estatusPago}}</td>
                <td>{{$v->cashFlow}}</td>
                @if($v->tipoMoneda== 'USD')
                <td>USD-${{$v->montoFactura}}</td>
                @endif

                @if($v->tipoMoneda== 'MXN')
                <td>MXM-${{$v->montoFactura}}</td>
                @endif
                <td>
                    <a href="{{URL::action('AsignacionController@asignacion',['idFactura'=>$v->idFactura])}}"
                        type="submit" class="btn btn-success btn-sm">
                        Asignar trabajadores
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div> -->

<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var tipoSesion = "<?php echo $tipoSession ?>";
    var fechaInicio = "<?php echo $fechaInicio ?>";
    var fechaFin = "<?php echo $fechaFin ?>";
    $(document).ready(function(){

        $("#list").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            columnas: [
                
                { leyenda: 'Folio de Factura', ordenable: true, columna:  'numeroFactura'},
                { leyenda: 'Fecha de Facturación', ordenable: true, columna: 'fecha'},
                { leyenda: 'Número de trabajadores asignados', ordenable: true, columna: 'fecha'},
                { leyenda: 'Cliente', columna: 'razonSocial', ordenable: true, filtro: true},
                { leyenda: 'Estatus Entrega', columna: 'estatusEntrega', ordenable: true, filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Pendiente', contenido: 'Pendiente' },
                            { valor: 'Entregado', contenido: 'Entregado' },                                        
                        ]
                        });
                } },
                { leyenda: 'Estatus Portal', columna: 'estatusPortal', ordenable: true, filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Pendiente', contenido: 'Pendiente' },
                            { valor: 'Subido en el portal', contenido: 'Subido' },                                        
                        ]
                        });
                } },
                { leyenda: 'Estatus Pago', columna: 'estatusPago', ordenable: true, filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Pendiente', contenido: 'Pendiente' },
                            { valor: 'Pagada', contenido: 'Pagada' },                                        
                        ]
                        });
                } },
                { leyenda: 'Cash Flow', columna: 'cashFlow', ordenable: true, filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Si', contenido: 'Si' },
                            { valor: 'No', contenido: 'No' },                                        
                        ]
                        });
                } },
                { leyenda: 'Monto'},
                { leyenda: 'Opciones'},
            ],
            modelo: [
                
                { propiedad: 'numeroFactura' ,class:'text-center'},                     
                { propiedad: 'fecha' ,class:'text-center'},
                { propiedad: 'canti' ,class:'text-center'},
                { propiedad: 'razonSocial' },
                { propiedad: 'estatusEntrega',class:'text-center'},
                { propiedad: 'estatusPortal',class:'text-center'},
                { propiedad: 'estatusPago',class:'text-center'},
                { propiedad: 'cashFlow',class:'text-center'},
                { propiedad: 'monto',class:'text-center'},
                
                { formato: function(tr, obj,celda){
                                        
                    let botones ='';

                            botones += anexGrid_link({
                                class: 'btn btn-success btn-sm',
                                contenido: 'Asignar trabajadores',
                                href: 'asignacion/' + obj.idFactura
                            });
                        

                        return botones;

                    },
                },
            ],
            url: 'get-data-facturas-abajo',
            fechaInicio,
            fechaFin,
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'fechaFactura',
            columna_orden: 'DESC'
        });
    })
</script>
@else
No existen Servicios con los filtros seleccionados
@endif