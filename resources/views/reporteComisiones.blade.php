@extends('principal')
@section('contenido')
<script>
    $(document).ready(function() {
    
    $("#agrega").click(function(e) {
        var fechaInicio =$("#fechaInicio").val();
        var fechaFin =$("#fechaFin").val();

        if(fechaInicio =='' || fechaFin ==''){
            if(fechaInicio ==''){
                alert ("Debes seleccionar una fecha inicial")
                e.preventDefault();

            }
            if(fechaFin ==''){
                alert ("Debes seleccionar una fecha final")
                e.preventDefault();

            }
        }else{
            $("#filtroFacturas").load('{{url('reporteFacturasAbajo')}}' + '?' + $(this).closest('form').serialize());
            }
        });
    });
</script>
<form>
    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de Comisiones</h1>
            </div><br>
            <div class="panel-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item active">
                        <a class="cargar" class="nav-link" id="pills-comision-tab" data-toggle="pill" href="#pills-comision"
                            role="tab" aria-controls="pills-comision" >Reporte de Comisiones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" class="nav-link" id="pills-agregar-tab" data-toggle="pill" href="#pills-agregar"
                            role="tab" aria-controls="pills-agregar" aria-selected="false">Resumen Reporte de Comisiones</a>
                    </li>
                </ul>
                
                <div class="tab-content" id="pills-tabContent" style="margin-top: 10px">
                
                <div class="tab-pane fade active in" id="pills-comision" role="tabpanel" aria-labelledby="pills-comision-tab">
                <div class="row">
                    <div class="col-md-3">
                        Fecha de Inicio:<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>

                    <div class="col-md-3">
                        Fecha de Fin:<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>
                    <div class="col-md-6" style="margin-top:10px">
                        <button type="button" class="btn btn-primary" name="agrega" id="agrega">Aplicar filtro</button>
                        <a href="{{asset('reporteFacturas')}}"><button type="button" class="btn btn-default">Limpiar
                                filtro</button></a>
                        <!--
                        <a href="{{URL::action('HomeController@export')}}"><img
                                src="{{asset('img/excelico.png')}}" height=50 width=50></a>
                        -->
                    </div>
                </div>
                <br><br>
                <div id='filtroFacturas'>
                    
                    <div id="list" class="table-responsive"></div>
                    
                </div>
                
            </div>
            <div class="tab-pane fade" id="pills-agregar" role="tabpanel" aria-labelledby="pills-agregar-tab">
        <br>
       <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr style="background-color:#D6EAF8">
                                    <th colspan="5">Resumen de Comisiones</th>                            
                                </tr>                            
                            </thead> 
                                    
                            <tbody>
                                <tr>
                                    <th></th>
                                    <th colspan="4">Vendedores</th>
                                </tr>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Yanet</th>
                                    <th>Limón</th>
                                    <th>Norberto</th>
                                    <th>Heidi</th>
                                </tr>
                                <tr>
                                    <th>145</th>
                                    <th>$ 2,500</th>
                                    <th>$ 5,000</th>
                                    <th>$ 7,000</th>
                                    <th>$ 5,000</th>
                                </tr>
                            </tbody>                               
                            <tfoot>
                                <tr style="background-color:#D6EAF8">
                                    <th>Total</th>
                                    <th>MXN $</th>
                                    <th>MXN $</th>
                                    <th>MXN $</th>
                                    <th>MXN $</th>
                                </tr>
                                <tr style="background-color:#66BB6A">
                                    <th>Comisión</th>
                                    <th>MXN $</th>
                                    <th>MXN $</th>
                                    <th>MXN $</th>
                                    <th>MXN $</th>
                                </tr>
                            </tfoot>  
                        </table>   
    </div>
    </div>
        </div>
    </div>
</form>
<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var tipoSesion = "<?php echo $tipoSession ?>";
    $(document).ready(function(){

        var tablita = $("#list").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            columnas: [
                { leyenda: 'Folio de Servicio Asignado', ordenable: true, columna: 'idServicios', filtro: true},
                { leyenda: 'Folio de Factura', ordenable: true, columna:  'numeroFactura', filtro: true},
                { leyenda: 'Cotización Equipo', ordenable: true, columna:  'numeroCotizacion', filtro: true},
                { leyenda: 'Cotizacion Servicio', ordenable: true, columna:  'numeroServicio', filtro: true},
                { leyenda: 'Fecha de Servicio', ordenable: true, columna: 'fecha'},
                { leyenda: 'Fecha de Pago', ordenable: true, columna: 'fechap'},
                { leyenda: 'Cliente', columna: 'razonSocial', ordenable: true, filtro: true},
                { leyenda: 'Sucursal', columna: 'sucursales.sucursal', ordenable: true, filtro: true},
                
                { leyenda: 'Estatus Pago', columna: 'estatusPago', ordenable: true, filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Pendiente', contenido: 'Pendiente' },
                            { valor: 'Pagada', contenido: 'Pagada' },                                        
                        ]
                        });
                } },
                
                { leyenda: 'Monto cotización'},
                { leyenda: 'Total'},
                { leyenda: 'Usuarios'},
                { leyenda: 'Opciones'},
            ],
            modelo: [
                { propiedad: 'idServicios' ,class:'text-center'},
                { propiedad: 'numeroFactura' ,class:'text-center'},                     
                { propiedad: 'numeroCoti' ,class:'text-center'},                   
                { propiedad: 'numeroSer' ,class:'text-center'}, 
                { propiedad: 'fecha' ,class:'text-center'},
                { propiedad: 'fechap' ,class:'text-center'},
                { propiedad: 'razonSocial' },
                { propiedad: 'sucursal' ,class:'text-center'},

                { propiedad: 'estatusPago',class:'text-center'},

                { propiedad: 'monto',class:'text-center'},
                { propiedad: 'total',class:'text-center'},
                { propiedad: 'sumusuario',class:'text-center'},
                { formato: function(tr, obj,celda){
                                    
                    let botones ='';
                        if (tipoSesion=='Administrador') {

                            if (obj.activo=='si') {

                                botones += anexGrid_link({
                                    class: 'btn btn-sm btn-info',
                                    contenido: '<i class="ace-icon fa fa-pencil bigger"></i>',
                                    href: 'modificarComisiones/' + obj.idFactura
                                });
                                

                            }else{

                            }

                        }
                        return botones;

                    },
                },
            ],
            url: 'get-data-comisiones',
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'idServicios',
            columna_orden: 'DESC'
        });
        
        tablita.tabla().on('click', '.pdfCotizacionEquipos', function(){
            var a=$(this).val();            
            window.open('{{url('pdfCotizacion')}}' + '?idCotizacion='+a+'&verTotal=Si'+'&pld=1'+'&cD=Si');
        });

        tablita.tabla().on('click', '.pdfCotizacionServicios', function(){
            var a=$(this).val();            
            window.open('{{url('pdfCotizacionServicios')}}' + '?idCotizacionServicios='+a+'&verTotal=Si'+'&pld=1'+'&cD=Si');
        });
    })
</script>


    
@stop