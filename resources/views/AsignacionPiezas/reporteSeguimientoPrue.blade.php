@extends('principal')
@section('contenido')
<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-55px">                
        <div class="panel-heading">
            <h1>Reporte de seguimientos</h1>           
        </div>
        
        <div class="panel-body">

            <div class="form-group" style="background-color:#D4EFDF">
                <div style="border-left:solid;border-top:solid;border-right:solid">
                    <center>
                        ACTIVIDADES CREADAS
                    </center>
                </div>
                <div class="col-md-4" style="background-color:#00b050; border-left:solid; border-bottom:solid" id="cerradasCreadas">
                    <center>
                        <img src="{!! asset('archivos/botonCerradas.PNG')!!}">                        
                        <font size=3 style="color:#FFFFFF">
                            <b>{{$cuantasCerradas}}</b>
                        </font>                        
                    </center>
                </div>
                <div class="col-md-4" style="background-color:#2f5597;border-bottom:solid" id="abiertasCreadas">
                    <center>
                        <img src="{!! asset('archivos/botonAbiertas.PNG')!!}">
                        <font size=3 style="color:#FFFFFF">
                            <b>{{$cuantasAbiertas}}</b>
                        </font>
                    </center>
                </div>
                <div class="col-md-4" style="background-color:#c00000;border-bottom:solid;border-right:solid" id="expiranCreadas">
                    <center>
                        <img src="{!! asset('archivos/botonExpiran.PNG')!!}">
                        <font size=3 style="color:#FFFFFF">
                            <b>{{$cuantasExpiran}}</b>
                        </font>
                    </center>
                </div>
            </div>
            
            <br><br><br>
            
            <div class="form-group" style="background-color:#955d5d45">
                <div style="border-left:solid;border-top:solid;border-right:solid">
                    <center>
                        ACTIVIDADES ASIGNADAS
                    </center>
                </div>
                <div class="col-md-4" style="background-color:#00b050; border-left:solid; border-bottom:solid" id="cerradasAsignadas">
                    <center>
                        <img src="{!! asset('archivos/botonCerradas.PNG')!!}">                        
                        <font size=3 style="color:#FFFFFF">
                            <b>{{$cuantasCerradasAsignadas}}</b>
                        </font>                        
                    </center>
                </div>
                <div class="col-md-4" style="background-color:#2f5597;border-bottom:solid" id="abiertasAsignadas">
                    <center>
                        <img src="{!! asset('archivos/botonAbiertas.PNG')!!}">
                        <font size=3 style="color:#FFFFFF">
                            <b>{{$cuantasAbiertasAsignadas}}</b>
                        </font>
                    </center>
                </div>
                <div class="col-md-4" style="background-color:#c00000;border-bottom:solid;border-right:solid" id="expiranAsignadas">
                    <center>
                        <img src="{!! asset('archivos/botonExpiran.PNG')!!}">
                        <font size=3 style="color:#FFFFFF">
                            <b>{{$cuantasExpiranAsignadas}}</b>
                        </font>
                    </center>
                </div>
            </div>
            
            <br><br><br><br>            
            <input type="hidden" id="tipoReporte" value="creadas">
            <input type="hidden" id="valfil" value="abiertasCreadas">
            <div class="form-group row" style="margin-top:;">
                <div class="col-md-3" style="margin-top:19px">
                    <a href="{{asset('altaSeg')}}">
                        <button type="button" class="btn btn-primary ">Agregar nuevo seguimiento</button>
                    </a>
                </div>                            

                <div class="col-md-3">
                    Fecha de Inicio:<input type='date' name='fechaInicio' id='fechaInicio' class="form-control rounded-0">
                </div>
                <div class="col-md-3">
                    Fecha de Fin:<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                </div>                    

                <div class="col-md-3" style="margin-top:19px;text-align:right;">                    
                    <button type="button" class="btn btn-default" id="filtrar">
                        Aplicar filtro
                    </button>
                    <button type="button" class="btn btn-default" id="limpiarFiltro">
                        Limpiar Filtro
                    </button>                    
                </div>
            </div>

            <br>

            <div class="form-group" id="reporte" style="margin-top:;">
                <ul class="nav nav-tabs">
                    <li class="active creadasReporte" id='' style="display:block "><a href="#creadas" data-toggle="tab"><label for="">Actividades creadas</label></a></li>
                    <li class="asignadasReporte" id='' style="display:none"><a href="#asignadas" data-toggle="tab"><label for="">Actividades asignadas</label></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active creadasReporte" id="creadas" style="display:block">                        
                        
                        <div id="list" class="table-responsive"></div>  
                        
                    </div>

                    <div class="tab-pane fade asignadasReporte" id="asignadas" style="display:none">
                        <div id="listAsignadas" class="table-responsive"></div>
                    </div>  
                </div>
            </div>
                       
        </div>
    </div>
</div>

<!-- INICIA ANEXGRID -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>

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
                { leyenda: 'Actividad', columna: 'nombreActividad',  filtro:true, ordenable: true,style: 'text-align:center'},
                { leyenda: 'Avance Personal', ordenable: true,style: 'text-align:center'},
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
                    if(obj.porcentajePersonal==0){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 100%">0% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentajePersonal>1 && obj.porcentajePersonal<11){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 10%">10% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentajePersonal>11 && obj.porcentajePersonal<21){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 20%">20% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentajePersonal>21 && obj.porcentajePersonal<31){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 30%">30% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentajePersonal>31 && obj.porcentajePersonal<41){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 40%">40% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentajePersonal>41 && obj.porcentajePersonal<51){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 50%">50% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentajePersonal>51 && obj.porcentajePersonal<61){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 60%">60% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentajePersonal>61 && obj.porcentajePersonal<71){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 70%">70% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentajePersonal>71 && obj.porcentajePersonal<81){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 80%">80% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentajePersonal>81 && obj.porcentajePersonal<91){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 90%">90% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentajePersonal>91 && obj.porcentajePersonal<100){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 95%">95% completado <span class="sr-only"></span></div></div>'
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
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 10%">10% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentaje>11 && obj.porcentaje<21){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 20%">20% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentaje>21 && obj.porcentaje<31){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 30%">30% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentaje>31 && obj.porcentaje<41){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 40%">40% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentaje>41 && obj.porcentaje<51){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 50%">50% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentaje>51 && obj.porcentaje<61){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 60%">60% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentaje>61 && obj.porcentaje<71){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 70%">70% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentaje>71 && obj.porcentaje<81){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 80%">80% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentaje>81 && obj.porcentaje<91){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 90%">90% completado <span class="sr-only"></span></div></div>'
                    }
                    if(obj.porcentaje>91 && obj.porcentaje<100){
                        barra = '<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="0" style="width: 95%">95% completado <span class="sr-only"></span></div></div>'
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
            // url: 'anexgridCreadas',
            url: 'anexgridCreadas',
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'idSegActividad',
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
            url: 'anexgridCreadas',
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'created_at',
            columna_orden: 'DESC'
        });

    });
</script>
<!-- TERMINA ANEXGRID -->

<script>
    $("#filtrar").click(function() {            
        var fechaInicio = $("#fechaInicio").val();
        var fechaFin = $("#fechaFin").val();
        var tipoFiltro = $("#valfil").val();
        var tipoReporte = $("#tipoReporte").val();
        
        $("#reporte").load('{{url('reporteSegConFiltro')}}' + '?' +'&fechaInicio=' + fechaInicio +'&fechaFin=' + fechaFin + '&tipoFiltro='+ tipoFiltro+'&tipoReporte='+tipoReporte) ;
    });
    $("#limpiarFiltro").click(function() {
        
        location.reload();
        
        // document.getElementById("valfil").value ='abiertasCreadas';
        // document.getElementById("tipoReporte").value ='creadas';
        // $("#fechaInicio,#fechaFin").val('');
        // $("#reporte").load('{{url('reporteSegConFiltro')}}' + '?' +'&fechaInicio=' + '' +'&fechaFin=' + ''+'&tipoReporte='+'creadas' + '&tipoFiltro=' + 'abiertasCreadas') ;
    });
    
    $("#cerradasCreadas").click(function() {
        document.getElementById("valfil").value ='cerradasCreadas';
        document.getElementById("tipoReporte").value ='creadas';
        $('.creadasReporte').css('display', 'block');
        $('.asignadasReporte').css('display', 'none');
        $("#reporte").load('{{url('reporteCerradasPrueba')}}'+'?'+'&tipoReporte='+'creadas') ;
    });
    $("#abiertasCreadas").click(function() {
        document.getElementById("valfil").value ='abiertasCreadas';
        document.getElementById("tipoReporte").value ='creadas';
        $('.creadasReporte').css('display', 'block');
        $('.asignadasReporte').css('display', 'none');
        $("#reporte").load('{{url('reporteAbiertasPrueba')}}'+'?'+'&tipoReporte='+'creadas');
    });
    $("#expiranCreadas").click(function() {
        document.getElementById("valfil").value ='expiranCreadas';
        document.getElementById("tipoReporte").value ='creadas';
        $('.creadasReporte').css('display', 'block');
        $('.asignadasReporte').css('display', 'none');
        $("#reporte").load('{{url('reporteExpiran')}}'+'?'+'&tipoReporte='+'creadas');
    });


    $("#cerradasAsignadas").click(function() {
        document.getElementById("tipoReporte").value ='asignadas';
        document.getElementById("valfil").value ='cerradasAsignadas';
        $('.creadasReporte').css('display', 'none');
        $('.asignadasReporte').css('display', 'block');
        $("#reporte").load('{{url('reporteCerradasPrueba')}}'+'?'+'&tipoReporte='+'asignadas');
    });
    $("#abiertasAsignadas").click(function() {
        document.getElementById("tipoReporte").value ='asignadas';
        document.getElementById("valfil").value ='abiertasAsignadas'; 
        $('.creadasReporte').css('display', 'none');
        $('.asignadasReporte').css('display', 'block');
        $("#reporte").load('{{url('reporteAbiertas')}}'+'?'+'&tipoReporte='+'asignadas');
    });
    $("#expiranAsignadas").click(function() {
        document.getElementById("tipoReporte").value ='asignadas';
        document.getElementById("valfil").value ='expiranAsignadas';      
        $('.creadasReporte').css('display', 'none');
        $('.asignadasReporte').css('display', 'block');
        $("#reporte").load('{{url('reporteExpiran')}}'+'?'+'&tipoReporte='+'asignadas');
    });
</script>
<style>
th {
    background-color: #D5F5E3;
}
</style>
@stop