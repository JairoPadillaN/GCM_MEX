@extends('principal')

@section('contenido')
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha512.js"></script>
<script src='https://cdn.zinggrid.com/zinggrid.min.js' defer></script>
<style>
    .margin{
        margin: 4px;
    }
</style>
<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-45px">
        <div class="panel-heading">
            <h1 class="">Reporte Ordenes de Compra</h1>
        </div><br>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12" style="margin-bottom:10px;text-align:right">
                    <a href="{{URL::action('ordenCompraController@altaOrdenCompra')}}">
                        <button type="button" class="btn btn-primary ">Agregar nueva orden</button>
                    </a>
                </div>
            </div>
            <ul class="nav nav-tabs">
                <li class="active" id='tab1'><a href="#1" data-toggle="tab"><label for="">Activas</label></a></li>
                <li class="" id='tab2'><a href="#2" data-toggle="tab"><label for="">Canceladas</label></a></li>
                <div class="text-right">
                    <button type="button" class="btn btn-success" id='exportarExcel'  title="Exportar a Excel">
                        <i class="fa fa-table"></i>
                    </button>
                </div>
            </ul>
            <div class="tab-content">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <div class="tab-pane active" id="1">                        
                    <div id="reporteOCactivas"></div>
                </div>
                <div class="tab-pane fade" id="2">
                    <div id="reporteOCcanceladas"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var tabla='';
    var panelActivo = 1;
    
    $(document).ready(function(){
        /* Se ejecuta primero la tabla de oc activas y se espera 2.7 segundos 
        para ejecutar la tabla de canceladas para que no cause conflicto al mostrar los totales
        ya que aun no me queda las funciones asincronas*/
        cargarReporteOC('reporteOCactivas','Si')

        setTimeout(function(){
            cargarReporteOC('reporteOCcanceladas','No')
        }, 2700)
    });

    function cargarReporteOC(div,estatusOrden){
        tabla = $("#"+div+"").anexGrid({
            class: 'table table-striped table-bordered table-hover tablaAnexgrid layui-table',            
            columnas: [
                {leyenda: 'Folio de Orden', columna: 'codigoOrden', ordenable: true, filtro: true, style: 'background-color: #C5EBFB;text-align:center',
                    filtro: function(){
                        return anexGrid_input({
                            type: 'text',
                            style: 'width:69px',
                            attr: ['id="folioOrden'+estatusOrden+'"']
                        });
                    }
                },
                {leyenda: 'Fecha Inicial', columna: 'fechaInicioOrden', ordenable: true, style: 'background-color: #C5EBFB;text-align:center;', 
                    filtro: function(){
                        return anexGrid_input({
                            type: 'date',
                            style: 'width:103px',
                            /* Para los filtros de fechas se debe agregar la clase fechasFiltro*/
                            class: 'fechasFiltro',
                            attr: ['id="fechaInicio'+estatusOrden+'"']
                        });
                    }
                },
                {leyenda: 'Fecha Final', columna: 'fechaFinOrden', style: 'background-color: #C5EBFB;text-align:center',
                    filtro: function(){
                        return anexGrid_input({
                            type: 'date',
                            style: 'width:103px',
                            /* Para los filtros de fechas se debe agregar la clase fechasFiltro*/
                            class: 'fechasFiltro',
                            attr: ['id="fechaFin'+estatusOrden+'"']
                        });
                    }
                },
                {leyenda: 'Proveedor', columna: 'razonSocialProv', ordenable: true, style: 'background-color: #C5EBFB;text-align:center',
                    filtro: function(){
                        return anexGrid_input({
                            type: 'text',
                            style: 'width:100px',
                            attr: ['id="proveedor'+estatusOrden+'"']
                        });
                    }
                },
                {leyenda: 'Empresa', columna: 'nombreEmpresa', ordenable: true, filtro: true , style: 'background-color: #C5EBFB;text-align:center',
                    filtro: function(){
                        return anexGrid_input({
                            type: 'text',
                            attr: ['id="empresa'+estatusOrden+'"']
                        });
                    }
                },
                {leyenda: 'Importe', columna: 'importeOrden', ordenable: true, style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'IVA', columna: 'iva', ordenable: true, style:'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'ISR', columna: 'isr', ordenable: true, style:'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Retención',columna: 'retencion', ordenable: true, style: 'background-color: #C5EBFB;text-align:center'},
                {leyenda: 'Total', columna: 'totalOrden', ordenable: true, style:'background-color: #C5EBFB;text-align:center'},
                /* Se coloco el atributo id_Totales: para enviar anexgrtid y lo coloque en el 
                link de ordenar, sirve para identificar en que coluna mostrar la suma de totales;
                Para colocar id al <th> colocar el atributo id:*/
                {leyenda: 'Total MXN', columna: 'totalMXN', ordenable: true, style:'background-color: #C5EBFB;text-align:center', id_Totales:'total_'+div},
                {leyenda: 'Estatus', columna: 'estatus', ordenable: true, filtro: true, style:'background-color: #C5EBFB;text-align:center',
                    filtro: function(){
                        return anexGrid_input({
                            type: 'text',
                            attr: ['id="estatus'+estatusOrden+'"']
                        });
                    }
                },
                {leyenda: 'Pago Final', columna: 'totalFinalPagado', ordenable: true, style:'background-color: #C5EBFB;text-align:center'},
                {leyenda: '', style: 'background-color: #C5EBFB;text-align:center'},
            ],
            modelo: [
                {propiedad: 'codigoOrden', style: 'text-align:center'},
                { propiedad: 'fechaInicioOrden' ,style:'text-align:center',colspan:'2',
                    formato: function(tr, obj,celda){
                        return obj.fecha_formato;
                    }
                },
                {propiedad: 'razonSocialProv', style: 'text-align:center'},
                {propiedad: 'nombreEmpresa', style: 'text-align:center'},
                {propiedad: 'importeOrden', style: 'text-align:center'},
                {propiedad: 'iva', style: 'text-align:center'},
                {propiedad: 'isr', style: 'text-align:center'},
                {propiedad: 'retencion', style: 'text-align:center'},
                {propiedad: 'totalOrden', style: 'text-align:center'},
                {propiedad: 'totalMXN', style: 'text-align:center'},
                {propiedad: 'estatus', style: 'text-align:center'},
                {propiedad: 'totalFinalPagado', style: 'text-align:center'},
                {propiedad: '',style:'text-align:letf', 
                    formato:function(tr, obj,celda){
                        let botones = '';
                            
                        if(estatusOrden != 'No'){
                            
                            if(parseInt(obj.totalSinFormat) > 1){
                                botones += anexGrid_boton({
                                    attr: ['title = "Generar PDF"'],
                                    class: 'btn btn-default btn-xs pdf margin',
                                    contenido: '<i class="fa fa-file-pdf-o" style="font-size:13px;color:#CC5035"></i>',
                                    value: obj.idOrden
                                });
                            }

                            botones += anexGrid_link({
                                attr: ['title = "Notas de entrada"'],
                                style: 'color: #fff;background-color: #E172CC;border-color: #E172CC;width:185px',
                                class: 'btn btn-default btn-xs notasEntrada margin',
                                contenido: '<i class="fa fa-file-text-o"></i>',
                                href: 'notasEntrada/' + obj.idOrden   
                            });

                            botones += anexGrid_link({
                                attr: ['title = "Editar OC"'],
                                class: 'btn btn-info btn-xs editar margin',
                                contenido: '<i class="fa fa-pencil"></i>',
                                href: 'modificarOrden/' + obj.idOrden
                            });

                            // if (obj.idOrden) {
                                botones += anexGrid_boton({
                                    attr: ['title = "Cancelar OC"'],
                                    class: 'btn btn-danger btn-xs eliminar margin',
                                    contenido: '<i class="fa fa-trash"></i>',
                                    value: obj.idOrden
                                    // href: 'eliminarOrden/' + obj.idOrden
                                });
                            // }

                        }else{
                            botones += anexGrid_link({
                                class: 'btn btn-warning btn-xs margin',
                                contenido: '<i class="fa fa-reply-all"></i>',
                                href: 'restaurarOrden/' + obj.idOrden
                            });
                        }

                        return botones;
                    }
                },
            
            ],
            url: 'anexgridOrdenesCompra',
            /*OC ACTIVAS estatusOrden = '' , OC CANCELADAS estatusOrden = Cancelado */
            parametros:[estatusOrden],
            paginable: true,
            filtrable: true,
            limite: [5,10,15,20,50],
            columna: 'idOrden',
            columna_orden: 'DESC'
        });

        tabla.tabla().on('click', '.pdf', function(e){
           encriptarIdPdf($(this).val())
        });

        tabla.tabla().on('click', '.eliminar', function(e){
            Swal.fire({
            title: 'Cancelar Orden de compra',
            text: "La orden de compra pasará a la pestaña de Canceladas, ¿Desa continuar?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Continuar',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `eliminarOrden/${$(this).val()}`,
                        type: "GET",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (res) {},
                        error: function (err) {}
                    });
                    setTimeout(function(){
                        location.reload();
                    }, 1200)
                }
            })
        });
        /* Se espera 2.7 segundos para ejecutar la funcion que muestra la suma de los registros
        para que no cause conflicto al mostrar los totales
        ya que aun no me queda las funciones asincronas*/
        setTimeout(function(){
            cargarTotal(div)
        }, 2700)

        // tabla.tabla().on('change', '.filtroAnexGrid', function(e){
        //     cargarTotal(div)
        // });
    }

    function cargarTotal(div){
        /* La funcion tabla.cargarSumaTotales() esta en anexgrid*/
        document.getElementById("total_"+div+"").innerHTML = 'Total '+ tabla.cargarSumaTotales();
    };    

    /* Recibe el idOrden y lo manda a una funcion en el controlador que lo encripta y 
    regresael id encriptado para que lo reciba en el controlador de pdf y se desencripte ahí */
    function encriptarIdPdf(idOrden){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('encriptarIdPdf') }}",
            type: "post",
            data: {idOrden: idOrden}
        }).done(function(res){
            window.open('{{url('pdfOrdenCompras')}}'+'/'+ res);
        });
    }

    $("#tab1").click(function(){
        panelActivo = 1;
    });

    $("#tab2").click(function(){
        panelActivo = 2;
    });

    /* Funcion para exportar la tabla con filtros a excel
    Recibe los valores que contegan los filtros de anexgrid
    para exportar lo que el usuario ve en la tabla y recibe tambien desde que Tab
    se esta exportando el excel si desde las OC activas o canceladas
    */
    $("#exportarExcel").click(function(){
        var folioOrden,fechaInicio,fechaFin,proveedor,empresa,estatus,reporte;
        if(panelActivo == 1){
            folioOrden = $("#folioOrden").val()
            fechaInicio = $("#fechaInicio").val()
            fechaFin = $("#fechaFin").val()
            proveedor = $("#proveedor").val()
            empresa = $("#empresa").val()
            estatus = $("#estatus").val()
            reporte = 'all'
        }else{
            folioOrden = $("#folioOrdenCancelado").val()
            fechaInicio = $("#fechaInicioCancelado").val()
            fechaFin = $("#fechaFinCancelado").val()
            proveedor = $("#proveedorCancelado").val()
            empresa = $("#empresaCancelado").val()
            estatus = 'Cancelado'
            reporte = ''
        }

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('consultaParaExcelOC') }}",
            type: "post",
            xhrFields: {responseType: 'blob'},
            data:{
                'codigoOrden':folioOrden,
                'proveedor':proveedor,
                'empresa':empresa,
                'estatus':estatus,
                'fechaInicioOrden':fechaInicio,
                'fechaFinOrden':fechaFin,
                'reporte':reporte
            },
            success: function(response) {
                let blob = new Blob([response]);
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "reporte_oc.xlsx";
                console.log (link);
                link.click();
            },
        })

    });

</script>
@stop