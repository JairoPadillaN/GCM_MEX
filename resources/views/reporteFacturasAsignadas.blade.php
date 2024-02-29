@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#agrega").click(function() {
        $("#filtroFacturas").load('{{url('reporteFacturasAbajoAsignadas')}}' + '?' + $(this).closest('form').serialize());
    });

    $("#limpiar").click(function() {
        document.getElementById("valorlimpiar").value=1;
        $("#filtroFacturas").load('{{url('reporteFacturasAbajoAsignadas')}}' + '?' + $(this).closest('form').serialize());
        document.getElementById("valorlimpiar").value=0;
    });
});
</script>

<form>
    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de Facturas Asignadas</h1>
            </div><br>
            <div class="panel-body">

                <div class="row">
                    <div class="col-xs-6 col-md-4">
                        Fecha de Inicio<input type='date' name='fechaInicio' id='fechaFactura'
                            class="form-control rounded-0">
                    </div>

                    <div class="col-xs-6 col-md-4">
                        Fecha de Fin<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div><br>
                    <div class="col-xs-6 col-md-4">
                        <button type="button" class="btn btn-default" name="agrega" id="agrega">Filtrar
                            Facturas</button>
                            <input type="hidden" id="valorlimpiar" name="limpiar"value="0">
                        <button type="button" class="btn btn-default" id="limpiar">Limpiar filtro</button>                        
                    </div>

                    <!-- <div class="col-xs-6 col-md-4">
                        Cliente:
                        <select class="form-control rounded-0" name='idc' id='idc'>
                        <option value="">Seleccione un cliente</option>
                        <option value="Todos">Todos</option>
                            @foreach($clientes1 as $cl)
                            <option value='{{$cl->idc}}'>{{$cl->razonSocial}} </option>
                            @endforeach
                        </select>
                    </div> -->
                </div>
                <br>
                <!-- <div class="row">
                    <div class="col-md-3">
                        <div>Estatus de Entrega:</div>
                        Pendiente <input type="radio" name="estatusEntrega" id="estatusEntrega" value="Pendiente">
                        Entregado <input type="radio" name="estatusEntrega" id="estatusEntrega" value="Entregado">
                    </div>

                    <div class="col-md-3">
                        <div>Estatus Portal:</div>
                        Pendiente <input type="radio" name="estatusPortal" id="estatusPortal"
                            value="Pendiente en subir">
                        Subido <input type="radio" name="estatusPortal" id="estatusPortal" value="Subido en el portal">
                    </div>

                    <div class="col-md-3">
                        <div>Estatus Pago:</div>
                        Pagada <input type="radio" name="estatusPago" id="estatusPago" value="Pagada">
                        Pendiente <input type="radio" name="estatusPago" id="estatusPago" value="Pendiente">
                    </div>

                    <div class="col-md-3">
                        <div>CashFlow:</div>
                        Si <input type="radio" name="cashFlow" id="cashFlow" value="Si">
                        No <input type="radio" name="cashFlow" id="cashFlow" value="No">
                    </div>
                </div> -->

                <!-- <br>
                <div class="row">
                    <div class="col-xs-6">
                        <button type="button" class="btn btn-default" name="agrega" id="agrega">Filtrar
                            Facturas</button>
                            <input type="hidden" id="valorlimpiar" name="limpiar"value="0">
                        <button type="button" class="btn btn-default" id="limpiar">Limpiar filtro</button>                        
                    </div>
                </div>
                <br> -->
                <div id='filtroFacturas'>
                <div id="list" class="table-responsive"></div>
                    <!-- <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Folio de la Factura</th>
                                    <th>Fecha de Facturaciòn</th>
                                    <th>Número de Trabajadores asignados</th>
                                    <th>Cliente</th>
                                    <th>Estatus Entrega</th>
                                    <th>Estatus Portal</th>
                                    <th>Estatus Pago</th>
                                    <th>Cash Flow</th>
                                    <th>Monto</th>
                                    <th>Opciones</th>
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
                </div>
                <!--fin del Div del filtro-->
            </div>
        </div>
    </div>
    <!--End Advanced Tables -->
</form>

<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var tipoSesion = "<?php echo $tipoSession ?>";
    $(document).ready(function(){

        $("#list").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            columnas: [
                
                { leyenda: 'Folio de Factura', ordenable: true, columna:  'numeroFactura'},
                { leyenda: 'Fecha de Facturación', ordenable: true, columna: 'fecha'},
                { leyenda: 'Número de trabajadores asignados', ordenable: true, columna: 'fecha', style:'width:10px;'},
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
                { leyenda: 'Cash Flow', columna: 'cashFlow', ordenable: true, style:'width:30px;',  filtro: function(){
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
            url: 'get-data-facturas',
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'idServicios',
            columna_orden: 'DESC'
        });
    })
</script>
@stop