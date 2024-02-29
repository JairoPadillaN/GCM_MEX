

<div class="panel-body">
<form action="">
    <input type="hidden" name="fechaInicio" id="" value="{{$fechaInicio}}">
    <input type="hidden" name="fechaFin" id="" value="{{$fechaFin}}">
</form>
    <div id="list" class="table-responsive"></div>
</div>

<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var tipoSesion = "<?php echo $tipoSession ?>";
    var fechaInicio = "<?php echo $fechaInicio ?>";
    var fechaFin = "<?php echo $fechaFin ?>";

    var tipoSesion = "<?php echo $tipoSession ?>";
    var clasePersonalizada = "";
    var aplicaFiltro = true;
    var verTotal = "Si";

    if(tipoSesion == "Compras"){
        clasePersonalizada += `hidden`;
        aplicaFIltro = false;
        verTotal = "No";
    }

    $(document).ready(function(){
        
        var tablitaFiltros = $("#list").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            columnas: [
                { leyenda: 'Folio de Servicio Asignado', ordenable: true, columna: 'idServicios',filtro: true},
                { leyenda: 'Folio de Factura', ordenable: true, columna:  'numeroFactura', filtro: true},
                { leyenda: 'Cotización Equipo', ordenable: true, columna:  'numeroCotizacion', filtro: true},
                { leyenda: 'Cotizacion Servicio', ordenable: true, columna:  'numeroServicio', filtro: true},
                { leyenda: 'Fecha de Servicio', ordenable: true, columna: 'fecha'},
                { leyenda: 'Fecha de Pago', ordenable: true, columna: 'fechap'},
                { leyenda: 'Cliente', columna: 'razonSocial', ordenable: true, filtro: true},
                { leyenda: 'Sucursal', columna: 'sucursales.sucursal', ordenable: true, filtro: true},
                { leyenda: 'Estatus Entrega', columna: 'estatusEntrega', ordenable: `${aplicaFiltro}`, class:`${clasePersonalizada}`, filtro: function(){
                    var filtro ="";
                    filtro = anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Pendiente', contenido: 'Pendiente' },
                            { valor: 'Entregado', contenido: 'Entregado' },
                        ]
                    });

                    if(tipoSesion == "Compras"){
                        filtro = false;
                    }

                    return filtro;
                } },
                { leyenda: 'Estatus Portal', columna: 'estatusPortal', ordenable: `${aplicaFiltro}`, class:`${clasePersonalizada}`,filtro: function(){
                    var filtro ="";
                    filtro = anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Pendiente', contenido: 'Pendiente' },
                            { valor: 'Subido en el portal', contenido: 'Subido' },                                        
                        ]
                    });

                    if(tipoSesion == "Compras"){
                        filtro = false;
                    }

                    return filtro;
                } },
                { leyenda: 'Estatus Pago', columna: 'estatusPago', ordenable: `${aplicaFiltro}`, class:`${clasePersonalizada}`,filtro: function(){
                    var filtro ="";
                    filtro = anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Pendiente', contenido: 'Pendiente' },
                            { valor: 'Pagada', contenido: 'Pagada' },                                        
                        ]
                    });

                    if(tipoSesion == "Compras"){
                        filtro = false;
                    }

                    return filtro;
                } },
                { leyenda: 'Cash Flow', columna: 'cashFlow', ordenable: `${aplicaFiltro}`, class:`${clasePersonalizada}`,filtro: function(){
                    var filtro ="";
                    filtro = anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Si', contenido: 'Si' },
                            { valor: 'No', contenido: 'No' },                                        
                        ]
                    });

                    if(tipoSesion == "Compras"){
                        filtro = false;
                    }

                    return filtro;
                } },
                { leyenda: 'Monto cotización', class:`${clasePersonalizada}`},
                { leyenda: 'Total', class:`${clasePersonalizada}`},
                { leyenda: 'Descargar Archivos'},
                { leyenda: 'Opciones', class:`${clasePersonalizada}`},
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
                { propiedad: 'estatusEntrega',class:`text-center ${clasePersonalizada}`},
                { propiedad: 'estatusPortal',class:`text-center ${clasePersonalizada}`},
                { propiedad: 'estatusPago',class:`text-center ${clasePersonalizada}`},
                { propiedad: 'cashFlow',class:`text-center ${clasePersonalizada}`},
                { propiedad: 'monto',class:`text-center ${clasePersonalizada}`},
                { propiedad: 'total',class:'text-center'},
                { formato: function(tr, obj,celda){

                    let respuestaFinal = '';

                        if (obj.tipoServicio != 'Soporte Técnico') {
                            
                            if (obj.archivoCotizacion) {
                                respuestaFinal += '<a target="_blank" href="archivos/'+obj.archivoCotizacion+'" class="btn btn-success btn-sm">Cotización ↓</a>';
                            }   else {
                                respuestaFinal += '<a type="button" class="btn btn-default btn-sm">Cotización ↓</a>';                                
                            }
                            
                            if (obj.idCotizacion) {
                                respuestaFinal += anexGrid_boton({                                    
                                    class: 'btn btn-success btn-sm pdfCotizacionEquipos',
                                    contenido: 'Cotización del Sistema ↓',
                                    value: obj.idCotizacion
                                });
                            }else{
                                respuestaFinal += anexGrid_boton({                                    
                                    class: 'btn btn-default btn-sm',
                                    contenido: 'Cotización del Sistema ↓',
                                    value: obj.idCotizacion
                                });
                            }

                        } else {

                            if (obj.archivoCotizacionServicios) {
                                respuestaFinal += '<a target="_blank" href="archivos/'+obj.archivoCotizacionServicios+'" class="btn btn-success btn-sm">Cotización ↓</a>';
                            }   else {                                    
                                respuestaFinal += '<a type="button" class="btn btn-default btn-sm">Cotización ↓</a>';
                            }
                            
                            if (obj.idCotizacionServicios) {
                                respuestaFinal += anexGrid_boton({                                    
                                    class: 'btn btn-success btn-sm pdfCotizacionServicios',
                                    contenido: 'Cotización del Sistema ↓',
                                    value: obj.idCotizacionServicios
                                });
                            }else{
                                respuestaFinal += anexGrid_boton({                                    
                                    class: 'btn btn-default btn-sm ',
                                    contenido: 'Cotización del Sistema ↓',
                                    value: obj.idCotizacionServicios
                                });
                            }
                        }          
                        
                        if (obj.archivo == 'Sin archivo') {
                            respuestaFinal += '<a type="button" class="btn btn-default btn-sm">Orden ↓</a>';
                        }   else {                                    
                            respuestaFinal += '<a target="_blank" href="archivos/'+obj.archivo+'" class="btn btn-success btn-sm">Orden ↓</a>';
                        }                                  
                                            
                        if (obj.archivoFactura == 'Sin archivo') {
                            respuestaFinal += '<a type="button" class="btn btn-default btn-sm">Factura ↓</a>';
                        }   else {                                    
                            respuestaFinal += '<a target="_blank" href="archivos/'+obj.archivoFactura+'" class="btn btn-success btn-sm">Factura ↓</a>';
                        }                              
                                        
                        if (obj.archivoPago == 'Sin archivo') {
                            respuestaFinal += '<a type="button" class="btn btn-default btn-sm">Pago ↓</a>';
                        }   else {                                    
                            respuestaFinal += '<a target="_blank" href="archivos/'+obj.archivoPago+'" class="btn btn-success btn-sm">Pago ↓</a>';
                        }

                        if (obj.xmlFactura == 'Sin archivo') {
                            respuestaFinal += '<a type="button" class="btn btn-default btn-sm">XML ↓</a>';
                        }   else {                                    
                            respuestaFinal += '<a target="_blank" href="archivos/'+obj.xmlFactura+'" class="btn btn-success btn-sm">XML ↓</a>';
                        }                                    

                        if (obj.archivoRemision == 'Sin archivo') {
                            respuestaFinal += '<a type="button" class="btn btn-default btn-sm">Remisión ↓</a>';
                        }   else {                                    
                            respuestaFinal += '<a target="_blank" href="archivos/'+obj.archivoRemision+'" class="btn btn-success btn-sm">Remisión ↓</a>';
                        }

                        if (obj.archivoAdenda == 'Sin archivo') {
                            respuestaFinal += '<a type="button" class="btn btn-default btn-sm">Adenda ↓</a>';
                        }   else {                                    
                            respuestaFinal += '<a target="_blank" href="archivos/'+obj.archivoAdenda+'" class="btn btn-success btn-sm">Adenda ↓</a>';
                        }


                        if(tipoSesion == "Compras"){
                            respuestaFinal = "";
                            if (obj.idCotizacion) {
                                respuestaFinal += anexGrid_boton({                                    
                                    class: 'btn btn-success btn-sm pdfCotizacionEquipos',
                                    contenido: 'Cotización del Sistema ↓',
                                    value: obj.idCotizacion
                                });
                            }else{
                                respuestaFinal += anexGrid_boton({                                    
                                    class: 'btn btn-default btn-sm',
                                    contenido: 'Cotización del Sistema ↓',
                                    value: obj.idCotizacion
                                });
                            }
                        }

                        return respuestaFinal;                                
                    },style: 'width:300px;'
                },
                { class:`text-center ${clasePersonalizada}`, formato: function(tr, obj,celda){
                                        
                    let botones ='';
                        if (tipoSesion=='Administrador') {

                            if (obj.activo=='si') {

                                botones += anexGrid_link({
                                    class: 'btn btn-sm btn-info',
                                    contenido: '<i class="ace-icon fa fa-pencil bigger"></i>',
                                    href: 'modificarFacturas/' + obj.idFactura
                                });

                                botones += anexGrid_link({
                                    class: 'btn btn-sm btn-danger',
                                    contenido: '<i class="fa fa-trash-o"></i>',
                                    href: 'eliminarFacturas/' + obj.idFactura
                                });

                            }else{

                                botones += anexGrid_link({
                                    class: 'btn btn-xs btn-warning',
                                    contenido: 'Restaurar',
                                    href: 'restaurarFacturas/' + obj.idFactura
                                });

                            }

                        }else if(tipoSesion=='Vendedor'){

                            botones += anexGrid_link({
                                class: 'btn btn-sm btn-info',
                                contenido: '<i class="ace-icon fa fa-pencil bigger"></i>',
                                href: 'modificarFacturas/' + obj.idFactura
                            });
                        }

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

        tablitaFiltros.tabla().on('click', '.pdfCotizacionEquipos', function(){
            var a=$(this).val();            
            window.open('{{url('pdfCotizacion')}}' + '?idCotizacion='+a+'&verTotal='+verTotal+'&pld=1'+'&cD=Si');
        });

        tablitaFiltros.tabla().on('click', '.pdfCotizacionServicios', function(){
            var a=$(this).val();            
            window.open('{{url('pdfCotizacionServicios')}}' + '?idCotizacionServicios='+a+'&verTotal='+verTotal+'&pld=1'+'&cD=Si');
        });
    })
</script>