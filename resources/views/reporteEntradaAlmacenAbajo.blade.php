<div id="list" class="table-responsive"></div>


<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var tipoSesion = "<?php echo $tipoSession ?>";
    var fechaInicio = "<?php echo $fechaInicio ?>";
    var fechaFin = "<?php echo $fechaFin ?>";
    if(tipoSesion=='Vendedor'){
        $(document).ready(function(){

            $("#list").anexGrid({
                class: 'table table-striped table-bordered table-hover',
                
                columnas: [
                    { leyenda: 'Número Factura', ordenable: true, columna: 'numeroFactura',filtro: true, style:'background:#C5EBFB;'},
                    { leyenda: 'Fecha', ordenable: true, columna: 'fecha', style:'background:#C5EBFB;'},
                    { leyenda: 'Proveedor', ordenable: true, columna: 'razonSocialProv', filtro: true, style:'background:#C5EBFB;'},
                    { leyenda: 'No. Partidas', ordenable: true, columna: 'numeroItems', style:'background:#C5EBFB;'},
                    // { leyenda: 'Monto factura', style:'background:#C5EBFB;'},
                    // { leyenda: 'Archivo', style:'background:#C5EBFB;'},
                    { leyenda: 'Opciones', style:'background:#C5EBFB;'},
                ],
                modelo: [
                    { propiedad: 'numeroFactura' ,class:'text-center'},
                    { propiedad: 'fecha' ,class:'text-center'},
                    { propiedad: 'razonSocialProv' ,class:'text-center'},
                    { propiedad: 'numeroItems' ,class:'text-center'},
                    // { propiedad: 'montoFactura' ,class:'text-center'},
                    // {class:'text-center', formato: function(tr, obj,celda){

                    //     let respuestaFinal = '';
                    //     if(obj.archivoFactura){
                    //         respuestaFinal += '<a target="_blank" href="archivos/'+obj.archivoFactura+'" class="btn btn-info btn-sm">Ver archivo</a>';                   
                    //     }else{
                    //         respuestaFinal += '<a class="btn btn-default btn-sm">Sin archivo</a>';                   
                                
                    //     }
                    // return respuestaFinal;
                    // }
                    // },
                    
                
                    { class:'text-center', formato: function(tr, obj,celda){
                        
                        let botones ='';
    
                            if (obj.activo=='Si') {
    
                                botones += anexGrid_link({
                                    class: 'btn btn-xs btn-warning',
                                    contenido: 'Detalle',
                                    href: 'detalleEntradaAlmacen/' + obj.idEntrada
                                });
    
                            }
                        return botones;
    
                        },
                    },
                    
                ],
                url: 'filtroEntradaAbajo',
                fechaInicio,
                fechaFin,
                paginable: true,
                filtrable: true,
                limite: [5, 10, 20, 50, 100],
                columna: 'idEntrada',
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
                            respuestaFinal += '<a target="_blank" href="archivos/'+obj.archivoFactura+'" class="btn btn-info btn-sm">Ver archivo</a>';                   
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
                                    href: 'eliminarEntrada/' + obj.idEntrada
                                });
    
                                botones += anexGrid_link({
                                    class: 'btn btn-xs btn-info',
                                    contenido: '<i class="ace-icon fa fa-pencil bigger-120"> Editar</i>',
                                    href: 'modificarEntradaAlmacen/' + obj.idEntrada
                                });
    
                            }else{
    
                                botones += anexGrid_link({
                                    class: 'btn btn-xs btn-warning',
                                    contenido: 'Restaurar',
                                    href: 'restaurarEntrada/' + obj.idEntrada
                                });
    
                            }
                        return botones;
    
                        },
                    },
                    
                ],
                url: 'filtroEntradaAbajo',
                fechaInicio,
                fechaFin,
                paginable: true,
                filtrable: true,
                limite: [5, 10, 20, 50, 100],
                columna: 'idEntrada',
                columna_orden: 'DESC'
            });
        })
    }
</script>

<style>
.estilo { 'width:100px;background:red;' }

</style>