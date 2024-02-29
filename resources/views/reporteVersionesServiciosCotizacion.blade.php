
    <form action="">
        <input type="hidden" name="idc" id="" value="{{$idc}}">
        <input type="hidden" name="fechaInicio" id="" value="{{$fechaInicio}}">
        <input type="hidden" name="fechaFin" id="" value="{{$fechaFin}}">
    </form>
<!-- hola -->
    <ul class="nav nav-tabs">
        <li class="active" id='tab1' ><a href="#11" data-toggle="tab"><label for="">Activas</label></a></li>
        <li class="" id='tab2' ><a href="#22" data-toggle="tab"><label for="">Canceladas</label></a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="11">
            <div id="list1" class="table-responsive"></div>
        </div>

        <div class="tab-pane fade" id="22">
            <div id="listCanceladas2" class="table-responsive"></div>
        </div>  
    </div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    var idc = "<?php echo $idc ?>";
    var fechaInicio = "<?php echo $fechaInicio ?>";
    var fechaFin = "<?php echo $fechaFin ?>";
    $(document).ready(function(){
        var tablita = $("#list1").anexGrid({
            class: 'table table-striped table-bordered table-hover tablaAnexgrid',
            columnas: [
                { leyenda: 'Número cotización', ordenable: true, filtro: true, columna: 'numeroCotizacion',style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Fecha', ordenable: true, columna:  'fechaCotizacion',style: 'width:105px;background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Cliente / Sucursal', ordenable: true, filtro: true, columna: 'clienteSucursal',style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Creada por', ordenable: true,filtro: true, columna: 'nombreUsuario',style:'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'No. Servicios', ordenable: true, columna: 'noServicios',style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Monto', columna: 'montoMoneda', ordenable: true,style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Estatus ', columna: 'estatus',style:'background-color: #C5EBFB;text-align:center', ordenable: true, filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'En revisión', contenido: 'En revisión' },
                            { valor: 'Pendiente de atorización', contenido: 'Pendiente de atorización' },                                        
                            { valor: 'Aceptada con orden de compra', contenido: 'Aceptada con orden de compra' },                                        
                        ]
                        });
                } },                
                { leyenda: 'Opciones',style: 'width:240px;background-color: #C5EBFB;text-align:center'},
            ],
            modelo: [
                { propiedad: 'numeroCotizacion' ,style:'text-align:center'},
                { propiedad: 'fechaCotizacion' ,style:'text-align:center'},                     
                { propiedad: 'clienteSucursal' ,style:'text-align:center'},                     
                { propiedad: 'usuario' ,style:'text-align:center'},
                { propiedad: 'noServicios' ,style:'text-align:center'},      
                { propiedad: 'montoMoneda' ,style:'text-align:center', formato: function(tr, obj,celda){
                        let sinMonto ='';
                            // if (obj.montoMoneda=='MXN $0.00' || obj.montoMoneda=='USD $0.00') {
                            //     sinMonto +='Sin monto por equipo repetido';
                            // }else{
                            //     sinMonto += obj.montoMoneda;                                
                            // }

                            if (obj.repetidos =='si') {
                                sinMonto +='Contiene equipos repetidos <br>'+obj.montoMoneda;
                            }else{
                                sinMonto += obj.montoMoneda;
                            }

                        return sinMonto;
                    }                    
                },      
                { propiedad: 'estatus' ,style:'text-align:center'},
                { propiedad: 'idCotizacionServicios',style:'text-align:center', formato: function(tr, obj,celda){
                                        
                    let botones ='';

                        if (obj.estatus=='Aceptada con orden de compra') {
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px;height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacionServicios                                
                            });

                            botones += anexGrid_boton({
                                class: 'btn btn-sm btn-info editar',
                                contenido: '<i class="ace-icon fa fa-pencil bigger"></i>',
                                value: obj.idCotizacionServicios,
                                style: 'margin-right:3px;height:30px',

                            });
                            
                            botones += anexGrid_boton({
                                class: 'btn btn-warning btn-sm actualizar',
                                contenido: '<i class="fa fa-refresh"></i> Actualizar cotización',
                                value: obj.idCotizacionServicios
                            });
                        }else if(obj.clonActual=='Si'){
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px;height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacionServicios                                
                            });

                            botones += anexGrid_boton({
                                class: 'btn btn-sm btn-info editar',
                                contenido: '<i class="ace-icon fa fa-pencil bigger"></i>',
                                value: obj.idCotizacionServicios,
                                style: 'margin-right:3px;height:30px',

                            });
                            
                            botones += anexGrid_boton({
                                class: 'btn btn-warning btn-sm actualizar',
                                contenido: '<i class="fa fa-refresh"></i> Actualizar cotización',
                                value: obj.idCotizacionServicios
                            });

                            if (obj.numCotizacion > 1 && obj.estatus=='En revisión') {
                                botones += anexGrid_boton({
                                    style: 'color: #fff;background-color: #CEBBE1;border-color: #CEBBE1; margin-top: 2px;',
                                    class: 'btn btn-warning btn-sm rollback',
                                    contenido: '<span class="glyphicon glyphicon-share-alt"></span> Revertir cotización',
                                    value: obj.idCotizacionServicios
                                });
                            }

                        }else{
                            
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px; height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacionServicios
                            });

                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #E17272;border-color: #E17272;width:185px',
                                class: 'btn btn-default btn-sm',
                                contenido: '<span class="glyphicon glyphicon-remove"></span> Cotización cancelada',
                                attr: ['disabled'],                                
                            });
                        }                      

                        return botones;

                    }
                },      
            ],
            url: 'anexgridVersionesServiciosCotizacionConFiltros',
            idc,
            fechaInicio,
            fechaFin,
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'created_at',
            columna_orden: 'DESC'
        });

        tablita.tabla().on('click', '.editar', function(){
            $('.actualizar').attr("disabled", true);
            $('.editar').attr("disabled", true);    
            $("#altaVersion").empty();
            $("#altaVersion").load('{{url('editarCotizacionServicios')}}' + '?' +'idCotizacionServicios='+ $(this).val());            
        });

        tablita.tabla().on('click', '.actualizar', function(){
            $("#altaVersion").empty();
            $('.actualizar').attr("disabled", true);
            $('.editar').attr("disabled", true);
            $("#altaVersion").load('{{url('actualizarCotizacionServicios')}}' + '?' +'idCotizacionServicios='+ $(this).val()); 
        });

        tablita.tabla().on('click', '.ver', function(){
            $("#altaVersion").empty();
            $('.actualizar').attr("disabled", true);
            $('.editar').attr("disabled", true);
            $("#altaVersion").load('{{url('verCotizacionServicios')}}' + '?' +'idCotizacionServicios='+ $(this).val()); 
        });
        
        tablita.tabla().on('click', '.rollback', function(){                        
            if(confirm("¿Desea revertir la versión de cotización?")){
                $("#altaVersion").empty();            
                $("#altaVersion").load('{{url('rollbackCotizacionServicios')}}' + '?' +'idCotizacionServicios='+ $(this).val()); 
                location.reload();
                alert("Operacion exitosa");
            }else{
                alert("Operación cancelada");
            }

        });
        
        /////////////////////////////////////////                

        var tablitaCanceladas2 = $("#listCanceladas2").anexGrid({
            class: 'table table-striped table-bordered table-hover tablaAnexgrid',            
            columnas: [
                { leyenda: 'Número cotización', ordenable: true, filtro: true, columna: 'numeroCotizacion',style: 'width:90px;background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Fecha', ordenable: true, columna:  'fechaCotizacion',style: 'width:105px;background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Cliente / Sucursal', ordenable: true, filtro: true, columna: 'clienteSucursal',style: 'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Creada por', ordenable: true,filtro: true, columna: 'nombreUsuario',style:'background-color: #C5EBFB;text-align:center'},
                { leyenda: 'No. Servicios', ordenable: true, columna: 'noServicios',style: 'width:70px;background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Monto', columna: 'montoMoneda', ordenable: true,style: 'width:120px;background-color: #C5EBFB;text-align:center'},
                { leyenda: 'Estatus ', columna: 'estatus',style:'background-color: #C5EBFB;text-align:center', ordenable: true},                
                { leyenda: 'Opciones',style: 'width:240px;background-color: #C5EBFB;text-align:center'},
            ],
            modelo: [
                { propiedad: 'numeroCotizacion' ,style:'text-align:center'},
                { propiedad: 'fechaCotizacion' ,style:'text-align:center'},                     
                { propiedad: 'clienteSucursal' ,style:'text-align:center'},                     
                { propiedad: 'usuario' ,style:'text-align:center'},
                { propiedad: 'noServicios' ,style:'text-align:center'},      
                { propiedad: 'montoMoneda' ,style:'text-align:center', formato: function(tr, obj,celda){
                        let sinMonto ='';
                            // if (obj.montoMoneda=='MXN $0.00' || obj.montoMoneda=='USD $0.00') {
                            //     sinMonto +='Sin monto por equipo repetido';
                            // }else{
                            //     sinMonto += obj.montoMoneda;                                
                            // }

                            if (obj.repetidos =='si') {
                                sinMonto +='Contiene equipos repetidos <br>'+obj.montoMoneda;
                            }else{
                                sinMonto += obj.montoMoneda;
                            }

                        return sinMonto;
                    }                    
                },
                { propiedad: 'estatus' ,style:'text-align:center'},
                { propiedad: 'idCotizacionServicios',style:'text-align:center', formato: function(tr, obj,celda){
                                        
                    let botones ='';

                        if (obj.estatus=='Aceptada con orden de compra') {
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px;height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacionServicios
                            });

                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #E172CC;border-color: #E172CC;width:185px',
                                class: 'btn btn-default btn-sm',
                                contenido: '<i class="fa fa-handshake-o" aria-hidden="true"></i> Cotización cerrada',
                                attr: ['disabled'],                                
                            });                            
                        }else if(obj.clonActual=='Si'){
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px;height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacionServicios                                
                            });

                            botones += anexGrid_boton({
                                class: 'btn btn-sm btn-info editar',
                                contenido: '<i class="ace-icon fa fa-pencil bigger"></i>',
                                value: obj.idCotizacionServicios,
                                style: 'margin-right:3px;height:30px',

                            });
                            
                            botones += anexGrid_boton({
                                class: 'btn btn-warning btn-sm actualizar',
                                contenido: '<i class="fa fa-refresh"></i> Actualizar cotización',
                                value: obj.idCotizacionServicios
                            });

                        }else{
                            
                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #939C07;border-color: #939C07;margin-right:3px; height:30px',
                                class: 'btn btn-default btn-sm ver',
                                contenido: '<i class="icon-eye-open"></i>',
                                value: obj.idCotizacionServicios
                            });

                            botones += anexGrid_boton({
                                style: 'color: #fff;background-color: #E17272;border-color: #E17272;width:185px',
                                class: 'btn btn-default btn-sm',
                                contenido: '<span class="glyphicon glyphicon-remove"></span> Cotización cancelada',
                                attr: ['disabled'],                                
                            });
                        }                      

                        return botones;

                    }
                },      
            ],
            url: 'anexgridVersionesServiciosCanceladasConFiltros',
            idc,
            fechaInicio,
            fechaFin,
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'created_at',
            columna_orden: 'DESC'
        });

        tablitaCanceladas2.tabla().on('click', '.editar', function(){
            $('.actualizar').attr("disabled", true);
            $('.editar').attr("disabled", true);    
            $('.ver').attr("disabled", true);    
            $("#altaVersion").empty();
            $("#altaVersion").load('{{url('editarCotizacionServicios')}}' + '?' +'idCotizacionServicios='+ $(this).val());            
        });

        tablitaCanceladas2.tabla().on('click', '.actualizar', function(){
            $("#altaVersion").empty();
            $('.actualizar').attr("disabled", true);
            $('.editar').attr("disabled", true);
            $('.ver').attr("disabled", true);    
            $("#altaVersion").load('{{url('actualizarCotizacionServicios')}}' + '?' +'idCotizacionServicios='+ $(this).val()); 
        });

        tablitaCanceladas2.tabla().on('click', '.ver', function(){
            $("#altaVersion").empty();
            $('.actualizar').attr("disabled", true);
            $('.editar').attr("disabled", true);
            $("#altaVersion").load('{{url('verCotizacionServicios')}}' + '?' +'idCotizacionServicios='+ $(this).val()); 
        });

    });
</script>
