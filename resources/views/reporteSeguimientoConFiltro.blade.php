
<div class="tab-content">
    <div class="tab-pane active creadasReporte" id="creadas">
    <!-- <input type="text" value="{{$tipoReporte}}"> -->
        <div id="reporteBotones" class="table-responsive"></div>
    </div>    
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    var idParaConsulta = '<?php echo $idSeguimientos?>';
    var fechaInicio = '<?php echo $fechaInicio?>';
    var fechaFin = '<?php echo $fechaFin?>';
    var tipoReporte = '<?php echo $tipoReporte?>';
    if(tipoReporte=='creadas'){
        $(document).ready(function(){
            $("#reporteBotones").anexGrid({
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
                    { leyenda: 'Actividad', ordenable: true, filtro:true, columna: 'nombreActividad', style: 'text-align:center'},
                    // { leyenda: 'Avance Personal', columna: 'montoMoneda', ordenable: true,style: 'text-align:center'},
                    { leyenda: 'Avance General', columna: 'montoMoneda', ordenable: true,style: 'text-align:center'},
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
                    { propiedad: 'fechaCreacion' ,style:'text-align:center'},
                    { propiedad: 'asunto' ,style:'text-align:center'},
                    { propiedad: 'nombreUsuario' ,style:'text-align:center'},
                    { propiedad: 'cliente' ,style:'text-align:center'},
                    { propiedad: 'periodo' ,style:'text-align:center'},
                    { propiedad: 'importanciaSeguimiento' ,style:'text-align:center'},
                    { propiedad: 'nombreArea' ,style:'text-align:center'},
                    { propiedad: 'nombreActividad' ,style:'text-align:center'},
                    // { propiedad: 'porcentajePersonal' ,style:'text-align:center'},
                    
                    { formato: function(tr, obj, celda){
                        let barra='';
                        if(obj.porcentaje==0){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 100%">0% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>1 && obj.porcentaje<11){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 10%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>11 && obj.porcentaje<21){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 20%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>21 && obj.porcentaje<31){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 30%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>31 && obj.porcentaje<41){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 40%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>41 && obj.porcentaje<51){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 50%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>51 && obj.porcentaje<61){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 60%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>61 && obj.porcentaje<71){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 70%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>71 && obj.porcentaje<81){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 80%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>81 && obj.porcentaje<91){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 90%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>91 && obj.porcentaje<100){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 95%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje==100){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 100%">100% completado <span class="sr-only"></span></div></div>'
                        }
                    
                        return barra;
                        },
                    },
                    { propiedad: 'atendidopor' ,style:'text-align:center'},
                    { formato: function(tr, obj,celda){
                                            
                        let botones ='';
                            if(obj.activo=='Si'){
                            if (tipoReporte != 'asignadas') {
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
                            } else {
                                    botones += anexGrid_link({
                                        class: 'btn btn-xs btn-success',
                                        contenido: '<i class="ace-icon fa fa-eye bigger-120"></i>',
                                        href: 'seguimientos/' + obj.idSegActividad
                                    });
                            }
                                
                            }else{
                                botones += anexGrid_link({
                                    class: 'btn btn-xs btn-warning',
                                    contenido: 'Restaurar',
                                    href: 'restaurarSeg/' + obj.idSegActividad
                                });
                            }
                            return botones;                        

                        },
                    }, 

                ],
                url: 'anexgridSeguimientoConFiltro?&idSeguimientos='+idParaConsulta+'&fechaInicio='+fechaInicio+'&fechaFin='+fechaFin,
                paginable: true,
                filtrable: true,
                limite: [10, 20, 50, 100],
                columna: 'idSegActividad',
                columna_orden: 'DESC'
            });        
        });
    }else{
        $(document).ready(function(){
            $("#reporteBotones").anexGrid({
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
                    { leyenda: 'Actividad', ordenable: true, filtro:true, columna: 'nombreActividad', style: 'text-align:center'},
                    { leyenda: 'Fecha de siguiente avance', columna: 'fechasig', ordenable: true,style: 'text-align:center'},
                    { leyenda: 'Avance Personal', columna: 'montoMoneda', ordenable: true,style: 'text-align:center'},
                    { leyenda: 'Avance General', columna: 'montoMoneda', ordenable: true,style: 'text-align:center'},
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
                    { propiedad: 'fechaCreacion' ,style:'text-align:center'},
                    { propiedad: 'asunto' ,style:'text-align:center'},
                    { propiedad: 'nombreUsuario' ,style:'text-align:center'},
                    { propiedad: 'cliente' ,style:'text-align:center'},
                    { propiedad: 'periodo' ,style:'text-align:center'},
                    { propiedad: 'importanciaSeguimiento' ,style:'text-align:center'},
                    { propiedad: 'nombreArea' ,style:'text-align:center'},
                    { propiedad: 'nombreActividad' ,style:'text-align:center'},
                    { propiedad: 'fechasig' ,style:'text-align:center'},
                    // { propiedad: 'porcentajePersonal' ,style:'text-align:center'},
                    { formato: function(tr, obj, celda){
                        let barra='';
                        if(obj.porcentajePersonal==0){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 100%">0% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentajePersonal>1 && obj.porcentajePersonal<11){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 10%">'+ obj.porcentajePersonal +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentajePersonal>11 && obj.porcentajePersonal<21){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 20%">'+ obj.porcentajePersonal +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentajePersonal>21 && obj.porcentajePersonal<31){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 30%">'+ obj.porcentajePersonal +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentajePersonal>31 && obj.porcentajePersonal<41){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 40%">'+ obj.porcentajePersonal +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentajePersonal>41 && obj.porcentajePersonal<51){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 50%">'+ obj.porcentajePersonal +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentajePersonal>51 && obj.porcentajePersonal<61){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 60%">'+ obj.porcentajePersonal +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentajePersonal>61 && obj.porcentajePersonal<71){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 70%">'+ obj.porcentajePersonal +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentajePersonal>71 && obj.porcentajePersonal<81){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 80%">'+ obj.porcentajePersonal +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentajePersonal>81 && obj.porcentajePersonal<91){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 90%">'+ obj.porcentajePersonal +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentajePersonal>91 && obj.porcentajePersonal<100){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 95%">'+ obj.porcentajePersonal +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentajePersonal==100){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 100%">100% completado <span class="sr-only"></span></div></div>'
                        }
                    
                        return barra;
                        },
                    },
                    { formato: function(tr, obj, celda){
                        let barra='';
                        if(obj.porcentaje==0){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 100%">0% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>1 && obj.porcentaje<11){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 10%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>11 && obj.porcentaje<21){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 20%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>21 && obj.porcentaje<31){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 30%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>31 && obj.porcentaje<41){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 40%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>41 && obj.porcentaje<51){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 50%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>51 && obj.porcentaje<61){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 60%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>61 && obj.porcentaje<71){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 70%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>71 && obj.porcentaje<81){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 80%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>81 && obj.porcentaje<91){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 90%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje>91 && obj.porcentaje<100){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 95%">'+ obj.porcentaje +'% completado <span class="sr-only"></span></div></div>'
                        }
                        if(obj.porcentaje==100){
                            barra = '<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 100%">100% completado <span class="sr-only"></span></div></div>'
                        }
                    
                        return barra;
                        },
                    },
                    { propiedad: 'atendidopor' ,style:'text-align:center'},
                    { formato: function(tr, obj,celda){
                                            
                        let botones ='';
                            if(obj.activo=='Si'){
                            if (tipoReporte != 'asignadas') {
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
                            } else {
                                    botones += anexGrid_link({
                                        class: 'btn btn-xs btn-success',
                                        contenido: '<i class="ace-icon fa fa-eye bigger-120"></i>',
                                        href: 'seguimientos/' + obj.idSegActividad
                                    });
                            }
                                
                            }else{
                                botones += anexGrid_link({
                                    class: 'btn btn-xs btn-warning',
                                    contenido: 'Restaurar',
                                    href: 'restaurarSeg/' + obj.idSegActividad
                                });
                            }
                            return botones;                        

                        },
                    }, 

                ],
                url: 'anexgridSeguimientoConFiltro?&idSeguimientos='+idParaConsulta+'&fechaInicio='+fechaInicio+'&fechaFin='+fechaFin,
                paginable: true,
                filtrable: true,
                limite: [10, 20, 50, 100],
                columna: 'idSegActividad',
                columna_orden: 'DESC'
            });        
        });
    }

</script>