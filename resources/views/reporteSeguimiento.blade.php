@extends('principal')
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
@section('contenido')
<div class="col-md-12">
    <div class="panel panel-default" style="margin-top:-55px">                
        <div class="panel-heading">
            <h1>Reporte de proyectos</h1>           
        </div>
        
        <div class="panel-body">

            <div class="form-group" style="background-color:#D4EFDF">
                <div style="border-left:solid;border-top:solid;border-right:solid">
                    <center>
                        PROYECTOS CREADOS
                    </center>
                </div>
                <div class="col-md-4 siPreciona" style="background-color:#00b050; border-left:solid; border-bottom:solid" id="cerradasCreadas">
                    <center>
                        <div style="color:#FFFFFF">
                            Actividades cerradas: 
                            <font size=2>
                                <b>{{$cuantasCerradasCreadas}}</b>
                            </font>                            
                        </div>
                        <i class="icon-check icon-3x" style="color:#FFFFFF"></i>
                    </center>
                </div>
                <div class="col-md-4 siPreciona" style="background-color:#2f5597;border-bottom:solid" id="abiertasCreadas">
                    <center>
                        <div style="color:#FFFFFF">
                            Actividades abiertas: 
                            <font size=2>
                                <b>{{$cuantasAbiertasCreadas}}</b>
                            </font>                            
                        </div>
                        <i class="icon-spinner icon-3x" style="color:#FFFFFF"></i>
                    </center>
                </div>
                <div class="col-md-4 siPreciona" style="background-color:#c00000;border-bottom:solid;" id="expiranCreadas">
                    <center>
                        <div style="color:#FFFFFF">
                            Expiran esta semana: 
                            <font size=2>
                                <b>{{$cuantasExpiranCreadas}}</b>
                            </font>                            
                        </div>
                        <i class="icon-dashboard icon-3x" style="color:#FFDD00"></i>
                    </center>
                </div>
                <!-- <div class="col-md-3 siPreciona" style="background-color:#FF7F00;border-bottom:solid;border-right:solid" id="seguimientoCreadas">
                    <center>
                        <div style="color:#FFFFFF">
                            Seguimiento en esta semana: 
                            <font size=2>
                                <b>{{$cuantasSeguimientoCreadas}}</b>
                            </font>                            
                        </div>
                        <i class="icon-bell-alt icon-3x" style="color:#F1C40F"></i>
                    </center>
                </div> -->
            </div>
            
            <br><br><br>
            
            <div class="form-group" style="background-color:#955d5d45">
                <div style="border-left:solid;border-top:solid;border-right:solid">
                    <center>
                        PROYECTOS ASIGNADOS
                    </center>
                </div>
                <div class="col-md-3 siPreciona" style="background-color:#00b050; border-left:solid; border-bottom:solid" id="cerradasAsignadas">
                    <center>                        
                        <div style="color:#FFFFFF">
                            Actividades cerradas: 
                            <font size=2>
                                <b>{{$cuantasCerradasAsignadas}}</b>
                            </font>                            
                        </div>
                        <i class="icon-check icon-3x" style="color:#FFFFFF"></i>                        
                    </center>
                </div>
                <div class="col-md-3 siPreciona" style="background-color:#2f5597;border-bottom:solid" id="abiertasAsignadas">
                    <center>
                        <div style="color:#FFFFFF">
                            Actividades abiertas: 
                            <font size=2>
                                <b>{{$cuantasAbiertasAsignadas}}</b>
                            </font>                            
                        </div>
                        <i class="icon-spinner icon-3x" style="color:#FFFFFF"></i>
                    </center>
                </div>
                <div class="col-md-3 siPreciona" style="background-color:#c00000;border-bottom:solid;" id="expiranAsignadas">
                    <center>
                        <div style="color:#FFFFFF">
                            Expiran esta semana: 
                            <font size=2>
                                <b>{{$cuantasExpiranAsignadas}}</b>
                            </font>                            
                        </div>
                        <i class="icon-dashboard icon-3x" style="color:#FFDD00"></i>
                    </center>
                </div>
                <div class="col-md-3 siPreciona" style="background-color:#FF7F00;border-bottom:solid;border-right:solid" id="seguimientoAsignadas">
                    <center>
                        <div style="color:#FFFFFF">
                            Seguimiento en esta semana: 
                            <font size=2>
                                <b>{{$cuantasSeguimientoAsignadas}}</b>
                            </font>                            
                        </div>
                        <i class="icon-bell-alt icon-3x" style="color:#F1C40F"></i>
                    </center>
                </div>
            </div>
            
            <br><br><br><br>            
            <input type="hidden" id="tipoReporte" value="creadas">
            <input type="hidden" id="valoresIds" style="width:100%" value="{{$idParaConsultar}}">
            <div class="form-group row" style="margin-top:;">
                <div class="col-md-3" style="margin-top:19px">
                    <a href="{{asset('altaSeg')}}">
                        <button type="button" class="btn btn-primary ">Agregar nuevo proyecto</button>
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
                <div class="tab-content">
                    <div class="tab-pane active creadasReporte" id="creadas" style="display:block">                                                
                        <div>
                            <center>
                                PROYECTOS CREADOS
                            </center>
                        </div>
                        <div id="list" class="table-responsive"></div>
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
                { leyenda: 'Actividad', ordenable: true, filtro:true, columna: 'nombreActividad', style: 'text-align:center'},
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
                // { propiedad: 'porcentaje' ,style:'text-align:center'},
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
            url: 'anexgridSeguimiento',
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'idSegActividad',
            columna_orden: 'DESC'
        });        
    });
</script>
<!-- TERMINA ANEXGRID -->

<script>
    $("#filtrar").click(function() {            
        var fechaInicio = $("#fechaInicio").val();
        var fechaFin = $("#fechaFin").val();
        var idSeguimientos = $("#valoresIds").val();
        var tipoReporte = $("#tipoReporte").val();        

        $("#reporte").load('{{url('reporteFiltrosVista')}}' + '?' +'&fechaInicio=' + fechaInicio +'&fechaFin=' + fechaFin + '&idSeguimientos='+ idSeguimientos+'&tipoReporte='+tipoReporte);        
    });

    $("#limpiarFiltro").click(function() {
        
        location.reload();
        
        // document.getElementById("valoresIds").value ='abiertasCreadas';
    
        // $("#fechaInicio,#fechaFin").val('');
        // $("#reporte").load('{{url('reporteSegConFiltro')}}' + '?' +'&fechaInicio=' + '' +'&fechaFin=' + ''+'&tipoReporte='+'creadas' + '&tipoFiltro=' + 'abiertasCreadas') ;
    });
    
    $("#cerradasCreadas").click(function() {
        var idCerradasCreadas = '<?php echo $cerradasCreadas?>';
        $(".siPreciona").css('border-color', 'black');
        $("#cerradasCreadas").css('border-bottom-color', '#FFC300');
        document.getElementById("valoresIds").value =idCerradasCreadas;
        document.getElementById("tipoReporte").value ='creadas';
        $('.creadasReporte').css('display', 'block');
        $('.asignadasReporte').css('display', 'none');
        $("#reporte").load('{{url('reporteFiltrosVista')}}'+'?' +'&fechaInicio=' + '' +'&fechaFin=' + '' +'&idSeguimientos='+ idCerradasCreadas+'&tipoReporte='+'creadas');        
    });
    $("#abiertasCreadas").click(function() {
        var idAbiertasCreadas = '<?php echo $abiertasCreadas?>';
        $(".siPreciona").css('border-color', 'black');
        $("#abiertasCreadas").css('border-bottom-color', '#FFC300');
        document.getElementById("valoresIds").value =idAbiertasCreadas;
        document.getElementById("tipoReporte").value ='creadas';
        $('.creadasReporte').css('display', 'block');
        $('.asignadasReporte').css('display', 'none');
        $("#reporte").load('{{url('reporteFiltrosVista')}}'+'?' +'&fechaInicio=' + '' +'&fechaFin=' + '' +'&idSeguimientos='+ idAbiertasCreadas+'&tipoReporte='+'creadas');        
    });
    $("#expiranCreadas").click(function() {
        var idExpiranCreadas = '<?php echo $expiranCreadas?>';        
        $(".siPreciona").css('border-color', 'black');
        $("#expiranCreadas").css('border-bottom-color', '#FFC300');
        document.getElementById("valoresIds").value =idExpiranCreadas;
        document.getElementById("tipoReporte").value ='creadas';
        $('.creadasReporte').css('display', 'block');
        $('.asignadasReporte').css('display', 'none');
        $("#reporte").load('{{url('reporteFiltrosVista')}}'+'?' +'&fechaInicio=' + '' +'&fechaFin=' + '' +'&idSeguimientos='+ idExpiranCreadas+'&tipoReporte='+'creadas');        
    });
    $("#seguimientoCreadas").click(function() {
        var idSeguimientoCreadas = '<?php echo $seguimientoCreadas?>';        
        $(".siPreciona").css('border-color', 'black');
        $("#seguimientoCreadas").css('border-bottom-color', '#FFC300');
        document.getElementById("valoresIds").value =idSeguimientoCreadas;
        document.getElementById("tipoReporte").value ='creadas';
        $('.creadasReporte').css('display', 'block');
        $('.asignadasReporte').css('display', 'none');
        $("#reporte").load('{{url('reporteFiltrosVista')}}'+'?' +'&fechaInicio=' + '' +'&fechaFin=' + '' +'&idSeguimientos='+ idSeguimientoCreadas+'&tipoReporte='+'creadas');
    });


    $("#cerradasAsignadas").click(function() {
        var idCerradasAsignadas = '<?php echo $cerradasAsignadas?>';
        $(".siPreciona").css('border-color', 'black');
        $("#cerradasAsignadas").css('border-bottom-color', '#FFC300');        
        document.getElementById("valoresIds").value =idCerradasAsignadas;
        document.getElementById("tipoReporte").value ='asignadas';
        $('.creadasReporte').css('display', 'none');
        $('.asignadasReporte').css('display', 'block');
        $("#reporte").load('{{url('reporteFiltrosVista')}}'+'?' +'&fechaInicio=' + '' +'&fechaFin=' + '' +'&idSeguimientos='+ idCerradasAsignadas+'&tipoReporte='+'asignadas');        
    });
    $("#abiertasAsignadas").click(function() {
        var idAbiertasAsignadas = '<?php echo $abiertasAsignadas?>';
        $(".siPreciona").css('border-color', 'black');
        $("#abiertasAsignadas").css('border-bottom-color', '#FFC300');        
        document.getElementById("valoresIds").value =idAbiertasAsignadas; 
        document.getElementById("tipoReporte").value ='asignadas';
        $('.creadasReporte').css('display', 'none');
        $('.asignadasReporte').css('display', 'block');
        $("#reporte").load('{{url('reporteFiltrosVista')}}'+'?' +'&fechaInicio=' + '' +'&fechaFin=' + '' +'&idSeguimientos='+ idAbiertasAsignadas+'&tipoReporte='+'asignadas');        
    });
    $("#expiranAsignadas").click(function() {
        var idExpiranAsignadas = '<?php echo $expiranAsignadas?>';
        $(".siPreciona").css('border-color', 'black');
        $("#expiranAsignadas").css('border-bottom-color', '#FFC300');        
        document.getElementById("valoresIds").value =idExpiranAsignadas;      
        document.getElementById("tipoReporte").value ='asignadas';
        $('.creadasReporte').css('display', 'none');
        $('.asignadasReporte').css('display', 'block');
        $("#reporte").load('{{url('reporteFiltrosVista')}}'+'?' +'&fechaInicio=' + '' +'&fechaFin=' + '' +'&idSeguimientos='+ idExpiranAsignadas+'&tipoReporte='+'asignadas');        
    });
    $("#seguimientoAsignadas").click(function() {
        var idSeguimientoAsignadas = '<?php echo $seguimientoAsignadas?>';
        $(".siPreciona").css('border-color', 'black');
        $("#seguimientoAsignadas").css('border-bottom-color', '#FFC300');        
        document.getElementById("valoresIds").value =idSeguimientoAsignadas;      
        document.getElementById("tipoReporte").value ='asignadas';
        $('.creadasReporte').css('display', 'none');
        $('.asignadasReporte').css('display', 'block');
        $("#reporte").load('{{url('reporteFiltrosVista')}}'+'?' +'&fechaInicio=' + '' +'&fechaFin=' + '' +'&idSeguimientos='+ idSeguimientoAsignadas+'&tipoReporte='+'asignadas');        
    });
    
</script>
<style>
th {
    background-color: #D5F5E3;
}
</style>
@stop