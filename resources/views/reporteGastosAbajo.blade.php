

<div class="panel-body">
<form action="">
    <input type="hidden" name="fechaInicio" id="" value="{{$fechaInicio}}">
    <input type="hidden" name="fechaFin" id="" value="{{$fechaFin}}">
    <input type="hidden" name="reset" id="" value="{{$reset}}">
</form>
    <div id="list" class="table-responsive"></div>
</div>

<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>

    $(document).ready(function(){
        var tipoSesion = "<?php echo $tipoSession ?>";
    var fechaInicio = "<?php echo $fechaInicio ?>";
    var fechaFin = "<?php echo $fechaFin ?>";
    var reset = "<?php echo $reset ?>";

         $("#list").anexGrid({

            class: 'table table-striped table-bordered table-hover',
            columnas: [
                
                { leyenda: 'Folio de Factura', ordenable: true, columna:'numeroFactura'},
                { leyenda: 'Folio de Servicio', ordenable: true, columna:'idServicios'},
                { leyenda: 'Nombre del Gasto', ordenable: true, columna:'nombreGasto', style:'width:50px;',filtro:true},
                { leyenda: 'Beneficiario', ordenable: true, columna: 'beneficiario', style:'width:30px;',filtro:true},
                { leyenda: 'Empresa', ordenable: true, columna: 'empresa',filtro:true},
                { leyenda: 'Cuenta', ordenable: true, columna: 'nombreCuenta', style:'width:30px;',filtro:true},
                { leyenda: 'Cliente', ordenable: true, columna: 'cliente', style:'width:30px;',filtro:true},
                { leyenda: 'Sucursal', ordenable: true, columna: 'sucursal', style:'width:30px;',filtro:true},
                { leyenda: 'Fecha del Pago', columna: 'fecha_pago', ordenable: true, filtro: true},
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
                                    href: 'editarGastos/' + obj.id + '/' + obj.idServicios
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
            url: 'get-data',
            parametros : {fechaInicio: fechaInicio,fechaFin: fechaFin,reset:reset},
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'fecha_pago',
            columna_orden: 'DESC'
        });
   })
</script>
