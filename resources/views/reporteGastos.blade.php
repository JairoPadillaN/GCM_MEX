@extends('principal')
@section('contenido')

<form class="form">
     <input type="hidden" class="csrf-token" name="csrf-token" value="{{ csrf_token() }}">
    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de Gastos</h1>
            </div><br>
            <div class="panel-body">

                <div class="row">
                    <div class="col-xs-6 col-md-4">
                        Fecha de Inicio<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>

                    <div class="col-xs-6 col-md-4">
                        Fecha de Fin<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>
                    
                    <div style="margin-top: 11px;" class="col-xs-6 col-md-4">
                         <button type="button" class="btn btn-primary" name="agrega" id="agrega">Aplicar filtro</button>
                            <input type="hidden" id="valorlimpiar" name="limpiar"value="0">
                            <input type="hidden" id="nombreCuenta" name="nombreCuenta"value="">
                        <button type="button" class="btn btn-default" id="limpiar">Limpiar filtro</button>
                         <a id="export" href="{{route('excelGastos',['null','null','null','null','null','null','null','null','null'])}}"><img
                                src="{{asset('img/excelico.png')}}" height=50 width=50></a>

                    </div>

                   
                </div>
                <br>
                
                <div id='filtroFacturas'>
                <div id="list" class="table-responsive"></div>
                    
                </div>
                <!--fin del Div del filtro-->
                <!--table class="table table-striped table-bordered table-responsive" id="dataTables-example">
                        <tbody>
                            
                        </tbody>
                    </table-->
            </div>

        </div>
    </div>
    <!--End Advanced Tables -->
</form>
<style>
    .anexgrid-list-filtro-control{
        width: 80px !important;
    }
    .swal2-popup {
   font-size : 1.6rem !important ;
}
</style>
<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{asset('js/sweetalert2.all.min.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
     var fechaInicio = $('#fechaInicio').val();
     var fechaFin = $('#fechaFin').val();
     totales(fechaInicio,fechaFin);
     function totales(fecha_inicio,fecha_fin,nombreGasto,beneficiario,empresa,cuenta,cliente,sucursal,referencia,etiquetas) {
            url = '{{route('gastosTotales')}}'

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="csrf-token"]').val()
                }
            });

            $.ajax({
                type: "POST",
                dataType : "json",
                url: url ,
                data:  {'fecha_inicio':fecha_inicio,'fecha_fin':fecha_fin,'nombreGasto':nombreGasto,'beneficiario':beneficiario,'empresa':empresa,'cuenta':cuenta,'cliente':cliente,'sucursal':sucursal,'referencia':referencia,'etiquetas':etiquetas},
                success: function (data) {
                    //console.log(data);
                   
                    $('#agrega').removeAttr('disabled');
                    setTimeout(prueba, 3000);
                     /*setTimeout(prueba2, 3000);
                
                function prueba2(){
                     $('.anexgrid-list-filtro-limpiar').attr('disabled','disabled');
                }*/
                function prueba(){
                //$('.glyphicon').trigger('click');
                if ($('.totales').length > 0) {

                }else{
                    //$('.anexgrid-list-filtro-limpiar').removeAttr('disabled');
                    $('.glyphicon').click(function(){
               var fechaInicio = $('#fechaInicio').val();
               var fechaFin = $('#fechaFin').val();
               var nombreGasto = $( "input[data-columna='nombreGasto']" ).val();
               var beneficiario = $( "input[data-columna='beneficiario']" ).val();
               var empresa = $( "input[data-columna='empresa']" ).val();
               var cuenta = $( "input[data-columna='nombreCuenta']" ).val();
               var cliente = $( "input[data-columna='cliente']" ).val();
               var sucursal = $( "input[data-columna='sucursal']" ).val();
               var referencia = $( "input[data-columna='referencia']" ).val();
               var etiquetas = $( "input[data-columna='etiquetas']" ).val();
               totales(fechaInicio,fechaFin,nombreGasto,beneficiario,empresa,cuenta,cliente,sucursal,referencia,etiquetas);
                    })
                    $('.anexgrid-list-paginador-por-pagina').change(function(){
               var fechaInicio = $('#fechaInicio').val();
               var fechaFin = $('#fechaFin').val();
               var nombreGasto = $( "input[data-columna='nombreGasto']" ).val();
               var beneficiario = $( "input[data-columna='beneficiario']" ).val();
               var empresa = $( "input[data-columna='empresa']" ).val();
               var cuenta = $( "input[data-columna='nombreCuenta']" ).val();
               var cliente = $( "input[data-columna='cliente']" ).val();
               var sucursal = $( "input[data-columna='sucursal']" ).val();
               var referencia = $( "input[data-columna='referencia']" ).val();
               var etiquetas = $( "input[data-columna='etiquetas']" ).val();
               totales(fechaInicio,fechaFin,nombreGasto,beneficiario,empresa,cuenta,cliente,sucursal,referencia,etiquetas);
                    })
                    $('.anexgrid-list-filtro-limpiar').click(function(){
               var p = $(this).parent().parent().find('.anexgrid-list-filtro-control').val();
               var fechaInicio = $('#fechaInicio').val();
               var fechaFin = $('#fechaFin').val();
               var nombreGasto = $( "input[data-columna='nombreGasto']" ).val()!=p ? $("input[data-columna='nombreGasto']").val() : '';
               var beneficiario = $( "input[data-columna='beneficiario']" ).val()!=p ? $("input[data-columna='beneficiario']").val() : '';
               var empresa = $( "input[data-columna='empresa']" ).val()!=p ? $("input[data-columna='empresa']").val() : '';
               var cuenta = $( "input[data-columna='nombreCuenta']" ).val()!=p ? $("input[data-columna='nombreCuenta']").val() : '';
               var cliente = $( "input[data-columna='cliente']" ).val()!=p ? $("input[data-columna='cliente']").val() : '';
               var sucursal = $( "input[data-columna='sucursal']" ).val()!=p ? $("input[data-columna='sucursal']").val() : '';
               var referencia = $( "input[data-columna='referencia']" ).val()!=p ? $("input[data-columna='referencia']").val() : '';
               
               totales(fechaInicio,fechaFin,nombreGasto,beneficiario,empresa,cuenta,cliente,sucursal,referencia,etiquetas);
               
               url_temp = '{{route('excelGastos',['temp','temp1','temp2','temp3','temp4','temp5','temp6','temp7','temp8'])}}';
               url = url_temp.replace('temp', fechaInicio!='' ? fechaInicio : 'null').replace('temp1', fechaFin!='' ? fechaFin : 'null').replace('temp2', nombreGasto!='' ? nombreGasto : 'null').replace('temp3', beneficiario!='' ? beneficiario : 'null').replace('temp4', empresa!='' ? empresa : 'null').replace('temp5', cuenta!='' ? cuenta : 'null').replace('temp6', cliente!='' ? cliente : 'null').replace('temp7', sucursal!='' ? sucursal : 'null').replace('temp8', referencia!='' ? referencia : 'null');
              $('#export').attr('href',url);
                    })
                    var sum_total_iva = 0;
                    var sum_total_isr = 0;
                    var sum_total = 0;
                    var sum_total1 = 0;
               $.each(data,function(el, index){
               
                sum_total_iva += parseFloat(index.sum_total_iva);
                sum_total_isr += parseFloat(index.sum_total_isr);
                sum_total1 += parseFloat(index.sum_total1);
                sum_total += parseFloat(index.sum_total);
                 });
               $('.anexgrid-list-filas').append('<tr data-fila="" class="totales"><td colspan="10" class="" style="font-weight:bold;text-align:end;">Total: </td><td class="text-center" style="font-weight:bold;">MXN $'+sum_total_iva.toFixed(2)+'</td><td class="text-center" style="font-weight:bold;">MXN $'+sum_total_isr.toFixed(2)+'</td><td class="text-center" style="font-weight:bold;">USD $'+sum_total1.toFixed(2)+'</td><td class="text-center" style="font-weight:bold;">MXN $'+sum_total.toFixed(2)+'</td><td></td></tr>');
               }
               $(".anexgrid-list-filtro-control").off('keyup').on('keyup', function (e) {
               if (e.key === 'Enter' || e.keyCode === 13) {
               var fechaInicio = $('#fechaInicio').val();
               var fechaFin = $('#fechaFin').val();
               var nombreGasto = $( "input[data-columna='nombreGasto']" ).val();
               var beneficiario = $( "input[data-columna='beneficiario']" ).val();
               var empresa = $( "input[data-columna='empresa']" ).val();
               var cuenta = $( "input[data-columna='nombreCuenta']" ).val();
               var cliente = $( "input[data-columna='cliente']" ).val();
               var sucursal = $( "input[data-columna='sucursal']" ).val();
               var referencia = $( "input[data-columna='referencia']" ).val();
               totales(fechaInicio,fechaFin,nombreGasto,beneficiario,empresa,cuenta,cliente,sucursal,referencia);
               //var nombreGasto = $(this).data('columna'); 
               //console.log(beneficiario,nombreGasto);
               url_temp = '{{route('excelGastos',['temp','temp1','temp2','temp3','temp4','temp5','temp6','temp7','temp8'])}}';
               url = url_temp.replace('temp', fechaInicio!='' ? fechaInicio : 'null').replace('temp1', fechaFin!='' ? fechaFin : 'null').replace('temp2', nombreGasto!='' ? nombreGasto : 'null').replace('temp3', beneficiario!='' ? beneficiario : 'null').replace('temp4', empresa!='' ? empresa : 'null').replace('temp5', cuenta!='' ? cuenta : 'null').replace('temp6', cliente!='' ? cliente : 'null').replace('temp7', sucursal!='' ? sucursal : 'null').replace('temp8', referencia!='' ? referencia : 'null');
              $('#export').attr('href',url);
               
                }
               
               });
                }
                
                }
            })
        }
        
    $("#agrega").click(function() {
        var fechaInicio = $('#fechaInicio').val();
        var fechaFin = $('#fechaFin').val();
        if(fechaInicio =='' || fechaFin ==''){
            if(fechaInicio ==''){
               Swal.fire({/*position: 'top-end',*/icon: 'warning',title: 'Selecciona una fecha de inicio',/*showConfirmButton: false,timer: 1500*/})
            }
            if(fechaFin ==''){
                Swal.fire({/*position: 'top-end',*/icon: 'warning',title: 'Selecciona una fecha de término',/*showConfirmButton: false,timer: 1500*/})

            }
        }else{
    var fechaInicio = $('#fechaInicio').val()!='' ? $('#fechaInicio').val() : 'null';;
    var fechaFin = $('#fechaFin').val()!='' ? $('#fechaFin').val() : 'null';
    url_temp = '{{route('excelGastos',['temp','temp1','null','null','null','null','null','null','null'])}}';
    url = url_temp.replace('temp', fechaInicio).replace('temp1', fechaFin);
    $('#export').attr('href',url);
        $(this).attr('disabled','disabled');
        totales(fechaInicio,fechaFin);
        $("#filtroFacturas").load('{{url('reporteGastosAbajo')}}' + '?' + $(this).closest('form').serialize());
        }
        
        
    });

    $("#limpiar").click(function() {
    	$('#fechaInicio').val('');
    	$('#fechaFin').val('');
        totales();
    //var fechaInicio = $('#fechaInicio').val()!='' ? $('#fechaInicio').val() : 'null';;
    //var fechaFin = $('#fechaFin').val()!='' ? $('#fechaFin').val() : 'null';
    url = '{{route('excelGastos',['null','null','null','null','null','null','null','null','null'])}}';
    //url = url_temp.replace('temp', fechaInicio).replace('temp1', fechaFin);
    $('#export').attr('href',url);
        document.getElementById("valorlimpiar").value=1;
        $("#filtroFacturas").load('{{url('reporteGastosAbajo')}}' + '?' + $(this).closest('form').serialize());
        document.getElementById("valorlimpiar").value=0;
    });

    $('#export').click(function(){
    
   })

    var tipoSesion = "<?php echo $tipoSession ?>";
    
        $("#list").anexGrid({

            class: 'table table-striped table-bordered table-hover',
            columnas: [
                
                { leyenda: 'Folio de Factura', ordenable: true, columna:'numeroFactura',filtro:true},
                { leyenda: 'Folio de Servicio', ordenable: true, columna:'idServicios',filtro:true},
                { leyenda: 'Nombre del Gasto', ordenable: true, columna:'nombreGasto', style:'width:50px;',filtro:true},
                { leyenda: 'Beneficiario', ordenable: true, columna: 'beneficiario', style:'width:30px;',filtro:true},
                { leyenda: 'Empresa', ordenable: true, columna: 'empresa',filtro:true},
                { leyenda: 'Cuenta', ordenable: true, columna: 'nombreCuenta', style:'width:30px;',filtro:true},
                { leyenda: 'Cliente', ordenable: true, columna: 'cliente', style:'width:30px;',filtro:true},
                { leyenda: 'Sucursal', ordenable: true, columna: 'sucursal', style:'width:30px;',filtro:true},
                { leyenda: 'Fecha del Pago', columna: 'fecha_pago', ordenable: true/*, filtro: true*/},
                { leyenda: 'Referencia', columna: 'referencia', ordenable: true, style:'width:30px;',filtro: true},
                { leyenda: 'Total IVA',columna:'total_iva',ordenable:true},
                { leyenda: 'Total ISR',columna:'total_isr',ordenable:true},
                { leyenda: 'Total USD',columna:'total_USD',ordenable:true},
                { leyenda: 'Total MXN',columna:'total_MXN',ordenable:true},
                { leyenda: 'Acciones',columna:'acciones'},
            ],
            modelo: [
                
                { propiedad: 'numeroFactura' ,class:'text-center'},
                { propiedad: 'idServicios' ,class:'text-center'},
                { propiedad: 'nombreGasto' ,class:'text-center'},  
                { propiedad: 'beneficiario' ,class:'text-center'},
                { propiedad: 'empresa' ,class:'text-center'}, 
                { propiedad: 'nombreCuenta' ,class:'text-center'}, 
                { propiedad: 'cliente' ,class:'text-center'}, 
                { propiedad: 'sucursal' ,class:'text-center'}, 
                { propiedad: 'fecha_pago' ,class:'text-center'},   
                { propiedad: 'referencia' ,class:'text-center'},                   
                { propiedad: 'total_iva' },
                { propiedad: 'total_isr',class:'text-center'},
                { propiedad: 'total_USD',class:'text-center'},
                { propiedad: 'total_MXN',class:'text-center'},
                
                { formato: function(tr, obj,celda){
                                        
                    let botones ='';

                            botones += anexGrid_link({
                                    class: 'btn btn-sm btn-info',
                                    contenido: '<i class="ace-icon fa fa-pencil bigger"></i>',
                                    href: 'editarGastos/' + obj.id + '/' +obj.idServicios
                                });
                            /* botones += anexGrid_link({
                                    class: 'btn btn-sm btn-danger',
                                    contenido: '<i class="fa fa-trash-o"></i>',
                                    href: 'eliminarGastos/' + obj.idFactura
                                });*/
                        

                        return botones;

                    },
                },
            ],
            url: 'get-data-gastos',
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'fecha_pago',
            columna_orden: 'DESC'

        });

    })

</script>
@stop