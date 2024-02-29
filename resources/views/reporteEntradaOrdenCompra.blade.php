@extends('principal')
@section('contenido')
<script type="text/javascript">
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
        $("#filtroEntradas").load('{{url('reporteEntradaAbajo')}}' + '?' + $(this).closest('form').serialize());
        }
    });
    $("#limpiar").click(function() {
        location.reload();
    });
});
</script>   
<form>
    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de Entrada Almacen: Ordenes de Compra</h1>
            </div><br>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        Fecha de Inicio:<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>

                    <div class="col-md-3">
                        Fecha de Fin:<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>
                    <div class="col-md-6" style="margin-top:10px">
                    <button type="button" class="btn  btn-default" name="agrega" id="agrega">Aplicar filtro</button>
                        <button type="button" class="btn  btn-default" name="agrega" id="limpiar">Limpiar filtro</button>
                        @if(Session::get('sesiontipo')=="Administrador")
                            <a href="{{asset('altaEntradaOrdenCompra')}}">
                                <button type="button" class="btn btn-primary ">Agregar entrada a Almacen
                                </button>
                            </a>
                            @endif
                    </div>
                </div>
                <br><br>
                <div id='filtroEntradas'>
                    
                    <div id="list" class="table-responsive"></div>
                    
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
    if(tipoSesion=='Vendedor'){
        $(document).ready(function(){

        $("#list").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            
            columnas: [
                { leyenda: 'Número Factura', ordenable: true, columna: 'numeroFactura',filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'Fecha', ordenable: true, columna: 'fecha', style:'background:#C5EBFB;'},
                { leyenda: 'Proveedor', ordenable: true, columna: 'razonSocialProv', filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'No. Partidas', ordenable: true, columna: 'numeroItems', style:'background:#C5EBFB;'},
                { leyenda: 'Opciones', style:'background:#C5EBFB;'},
            ],
            modelo: [
                { propiedad: 'numeroFactura' ,class:'text-center'},
                { propiedad: 'fecha' ,class:'text-center'},
                { propiedad: 'razonSocialProv' ,class:'text-center'},
                { propiedad: 'numeroItems' ,class:'text-center'},            
                { class:'text-center', formato: function(tr, obj,celda){
                                        
                    let botones ='';

                        if (obj.activo=='Si') {

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-warning',
                                contenido: 'Detalle',
                                href: 'detalleEntradaOrden/' + obj.idEntradaOrden
                            });

                        }
                    return botones;

                    },
                },
                
            ],
            url: 'filtroEntradaOrden',
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'idEntradaOrden',
            columna_orden: 'DESC'
        });
        })
    }else{
        $(document).ready(function(){

        $("#list").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            
            columnas: [
                { leyenda: 'Número Factura', ordenable: true, columna: 'numeroFactura',filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'Fecha', ordenable: true, columna: 'fecha', style:'background:#C5EBFB;'},
                { leyenda: 'Proveedor', ordenable: true, columna: 'razonSocialProv', filtro: true, style:'background:#C5EBFB;'},
                { leyenda: 'No. Partidas', ordenable: true, columna: 'numeroItems', style:'background:#C5EBFB;'},
                { leyenda: 'Monto factura', style:'background:#C5EBFB;'},
                { leyenda: 'Archivo', style:'background:#C5EBFB;'},
                { leyenda: 'Opciones', style:'background:#C5EBFB;'},
            ],
            modelo: [
                { propiedad: 'numeroFactura' ,class:'text-center'},
                { propiedad: 'fecha' ,class:'text-center'},
                { propiedad: 'razonSocialProv' ,class:'text-center'},
                { propiedad: 'numeroItems' ,class:'text-center'},
                { propiedad: 'montoFactura' ,class:'text-center'},
                {class:'text-center', formato: function(tr, obj,celda){

                    let respuestaFinal = '';
                        if(obj.archivoFactura){
                            respuestaFinal += '<a target="_blank" href="public/archivos/'+obj.archivoFactura+'" class="btn btn-info btn-sm">Ver archivo</a>';                   
                        }else{
                            respuestaFinal += '<a class="btn btn-default btn-sm">Sin archivo</a>';                               
                        }
                    return respuestaFinal;
                }
                },
                
            
                { class:'text-center', formato: function(tr, obj,celda){
                                        
                    let botones ='';

                        if (obj.activo=='Si') {

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-danger',
                                contenido: '<i class="ace-icon fa fa-trash-o bigger-150"> Eliminar</i>',
                                href: 'eliminarEntradaOrden/' + obj.idEntradaOrden
                            });

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-info',
                                contenido: '<i class="ace-icon fa fa-pencil bigger-120"> Editar</i>',
                                href: 'modificarEntradaOrden/' + obj.idEntradaOrden
                            });

                        }else{

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-warning',
                                contenido: 'Restaurar',
                                href: 'restaurarEntradaOrden/' + obj.idEntradaOrden
                            });

                        }
                    return botones;

                    },
                },
                
            ],
            url: 'filtroEntradaOrden',
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'idEntradaOrden',
            columna_orden: 'DESC'
        });
        })
    }
    
</script>
@stop

<style>
.estilo { 'width:100px;background:red;' }

</style>