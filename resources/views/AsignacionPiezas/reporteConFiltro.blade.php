<form action="">
    <input type="text" name="actividad" id="" value="{{$actividad}}">
    
</form>
<ul class="nav nav-tabs">
    @if($tipoReporte == "creadas")
        <li class="active creadasReporte" id='' style=""><a href="#creadas" data-toggle="tab"><label for="">Actividades creadas</label></a></li>
    @else
        <li class="active asignadasReporte" id='' style=""><a href="#asignadas" data-toggle="tab"><label for="">Actividades asignadas</label></a></li>
    @endif
</ul>
<div class="tab-content">
    @if($tipoReporte == "creadas")
        <div class="tab-pane active creadasReporte" id="creadas">                        
            <div class="table-responsive" style="">
                <div id="list" class="table-responsive"></div>  
            </div>
        </div>
    @else
        <div class="tab-pane active asignadasReporte" id="asignadas" style="">
            <div class="table-responsive">
                <div id="listAsignadas" class="table-responsive"></div>
            </div>
        </div>
    @endif
    
</div>

<!-- INICIA ANEXGRID -->

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    var actividad = "<?php echo $actividad ?>";

    $(document).ready(function(){
        $("#list").anexGrid({
            class: 'table table-striped table-bordered table-hover',            
            columnas: [
                { leyenda: 'Folio', ordenable: true, filtro: true, columna: 'folio',style: 'text-align:center'},
                { leyenda: 'Fecha de creación', ordenable: true, columna:  'fechaCotizacion',style: 'text-align:center'},
                { leyenda: 'Asunto', ordenable: true, columna: 'usuario',style: 'text-align:center'},
                { leyenda: 'Creado por', ordenable: true, filtro:true, columna: 'nombreUsuario',style: 'text-align:center'},
                { leyenda: 'Cliente / Sucursal', ordenable: true, filtro:true, columna: 'cliente',style: 'text-align:center'},
                { leyenda: 'Periódo de atención',  ordenable: true, columna: 'periodo',style: 'text-align:center'},
                { leyenda: 'Nivel de atencion', ordenable: true, filtro:true, columna: 'importanciaSeguimiento',style: 'text-align:center'},
                { leyenda: 'Área responsable', ordenable: true, filtro:true, columna: 'nombreArea', style: 'text-align:center'},
                { leyenda: 'Avance', columna: 'montoMoneda', ordenable: true,style: 'text-align:center'},
                { leyenda: 'Atendido por', columna: 'montoMoneda', ordenable: true,style: 'text-align:center'},
                // { leyenda: 'Estatus ', columna: 'estatus',style:'background-color: #C5EBFB;text-align:center', ordenable: true, filtro: function(){
                //     return anexGrid_select({
                //         data: [
                //             { valor: '', contenido: 'Todos' },
                //             { valor: 'En revisión', contenido: 'En revisión' },
                //             { valor: 'Pendiente de autorización', contenido: 'Pendiente de autorización' },                                        
                //             { valor: 'Aceptada con orden de compra', contenido: 'Aceptada con orden de compra' },                                        
                //         ]
                //         });
                // } },                
                { leyenda: 'Opciones',style: 'text-align:center'},
            ],
            modelo: [
                { propiedad: 'folio' ,style:'text-align:center'},
                { propiedad: 'fecha' ,style:'text-align:center'},
                { propiedad: 'asunto' ,style:'text-align:center'},
                { propiedad: 'nombreUsuario' ,style:'text-align:center'},
                { propiedad: 'cliente' ,style:'text-align:center'},
                { propiedad: 'periodo' ,style:'text-align:center'},
                { propiedad: 'importanciaSeguimiento' ,style:'text-align:center'},
                { propiedad: 'nombreArea' ,style:'text-align:center'},
                { propiedad: '' ,style:'text-align:center'},
                { propiedad: '' ,style:'text-align:center'},
                { formato: function(tr, obj,celda){
                                        
                    let botones ='';
                        if(obj.activo=='Si'){
                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-warning',
                                contenido: '<i class="ace-icon fa fa-user bigger-120"></i>',
                                href: 'agregarUsuariosAct/' + obj.idSegActividad
                            });

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-success',
                                contenido: '<i class="ace-icon fa fa-eye bigger-120"></i>',
                                href: 'detalleSeg/' + obj.idSegActividad
                            });

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-info',
                                contenido: '<i class="ace-icon fa fa-pencil bigger-120"></i>',
                                href: 'modificarSeg/' + obj.idSegActividad
                            });
                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-danger',
                                contenido: '<i class="ace-icon fa fa-trash-o bigger-120"> </i>',
                                href: 'eliminarSeg/' + obj.idSegActividad
                            });
                        }
                        return botones;

                    },
                }, 

            ],
            url: 'anexgridCreadas',
            actividad,
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'created_at',
            columna_orden: 'DESC'
            
        });


        $("#listAsignadas").anexGrid({
            class: 'table table-striped table-bordered table-hover',            
            columnas: [
                { leyenda: 'Folio', ordenable: true, filtro: true, columna: 'folio',style: 'text-align:center'},
                { leyenda: 'Fecha de creación', ordenable: true, columna:  'fechaCotizacion',style: 'text-align:center'},
                { leyenda: 'Asunto', ordenable: true, columna: 'usuario',style: 'text-align:center'},
                { leyenda: 'Creado por', ordenable: true, filtro:true, columna: 'nombreUsuario',style: 'text-align:center'},
                { leyenda: 'Cliente / Sucursal', ordenable: true, filtro:true, columna: 'cliente',style: 'text-align:center'},
                { leyenda: 'Periódo de atención',  ordenable: true, columna: 'periodo',style: 'text-align:center'},
                { leyenda: 'Nivel de atencion', ordenable: true, filtro:true, columna: 'importanciaSeguimiento',style: 'text-align:center'},
                { leyenda: 'Área responsable', ordenable: true, filtro:true, columna: 'nombreArea', style: 'text-align:center'},
                { leyenda: 'Avance', columna: 'montoMoneda', ordenable: true,style: 'text-align:center'},
                { leyenda: 'Atendido por', columna: 'montoMoneda', ordenable: true,style: 'text-align:center'},
                // { leyenda: 'Estatus ', columna: 'estatus',style:'background-color: #C5EBFB;text-align:center', ordenable: true, filtro: function(){
                //     return anexGrid_select({
                //         data: [
                //             { valor: '', contenido: 'Todos' },
                //             { valor: 'En revisión', contenido: 'En revisión' },
                //             { valor: 'Pendiente de autorización', contenido: 'Pendiente de autorización' },                                        
                //             { valor: 'Aceptada con orden de compra', contenido: 'Aceptada con orden de compra' },                                        
                //         ]
                //         });
                // } },                
                { leyenda: 'Opciones',style: 'text-align:center'},
            ],
            modelo: [
                { propiedad: 'folio' ,style:'text-align:center'},
                { propiedad: 'fecha' ,style:'text-align:center'},
                { propiedad: 'asunto' ,style:'text-align:center'},
                { propiedad: 'nombreUsuario' ,style:'text-align:center'},
                { propiedad: 'cliente' ,style:'text-align:center'},
                { propiedad: 'periodo' ,style:'text-align:center'},
                { propiedad: 'importanciaSeguimiento' ,style:'text-align:center'},
                { propiedad: 'nombreArea' ,style:'text-align:center'},
                { propiedad: '' ,style:'text-align:center'},
                { propiedad: '' ,style:'text-align:center'},
                { formato: function(tr, obj,celda){
                                        
                    let botones ='';
                        if(obj.activo=='Si'){
                            // botones += anexGrid_link({
                            //     class: 'btn btn-xs btn-warning',
                            //     contenido: '<i class="ace-icon fa fa-user bigger-120"></i>',
                            //     href: 'agregarUsuariosAct/' + obj.idSegActividad
                            // });

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-success',
                                contenido: '<i class="ace-icon fa fa-eye bigger-120"></i>',
                                href: 'seguimientos/' + obj.idSegActividad
                            });

                            // botones += anexGrid_link({
                            //     class: 'btn btn-xs btn-info',
                            //     contenido: '<i class="ace-icon fa fa-pencil bigger-120"></i>',
                            //     href: 'modificarSeg/' + obj.idSegActividad
                            // });
                            // botones += anexGrid_link({
                            //     class: 'btn btn-xs btn-danger',
                            //     contenido: '<i class="ace-icon fa fa-trash-o bigger-120"> </i>',
                            //     href: 'eliminarSeg/' + obj.idSegActividad
                            // });
                        }else{
                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-warning',
                                // contenido: '<i class="ace-icon fa fa-trash-o bigger-120"> </i>',
                                href: 'restaurarSeg/' + obj.idSegActividad
                            });
                        }
                        return botones;

                    },
                }, 

            ],
            url: 'anexgridSeguimientoAsignadas',
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'created_at',
            columna_orden: 'DESC'
        });

    });
</script>
<!-- TERMINA ANEXGRID -->