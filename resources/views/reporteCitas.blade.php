@extends('principal')
@section('contenido')

<script type="text/javascript">

$(document).ready(function() {
    $("#divinfo").hide(1500);
    $(".cargar").click();
});

$(document).ready(function() {
    $("#agrega").click(function() {
        //      alert ("hola");
        $("#filtroCitasAsignadas").load('{{url('reporteCitasAsignada')}}' + '?' + $(this).closest('form').serialize());

    });
    $("#limpiar").click(function() {
        location.reload();
    });

});

$(document).ready(function() {
    $("#agrega").click(function() {
        //      alert ("hola");
        $("#filtroCitasCreadas").load('{{url('reporteCitasCreada')}}' + '?' + $(this).closest('form').serialize());

    });

});

</script>

<form>
<div class="col-md-12">
    <!-- Advanced Tables -->
    <div class="panel panel-default" style="margin-top:-45px" >
        <div class="panel-heading">
            <h1 align='center'>Reporte de citas</h1>
        </div><br>
        <div class="panel-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                Fecha de inicio <input type='date' name='fechaInicio' id='fechaInicio' class="form-control rounded-0">
            </div>
            <div class="col-xs-6 col-md-3">
                Fecha de Fin <input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
            </div>
            
        </div><br>

        <div class="row">
                    <div class="col-xs-6">
                        <button type="button" class="btn btn-default" name="agrega" id="agrega">Filtrar Citas</button>
                        <button type="button" class="btn  btn-default" name="agrega" id="limpiar">Todas las citas</button>
                        <a href="{{asset('altaCitas')}}">
                            <button type="button" class="btn btn-primary ">Crear cita
                            </button>
                        </a>
                        
                    </div>
                    
                </div>
                    
        <div><br>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                
                <li class="nav-item">
                    <a class="cargar" class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Citas creadas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Citas asignadas</a>
                </li>
                
            </ul>
            </div><br>
        <div class="tab-content" id="pills-tabContent">
            
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div id='filtroCitasCreadas'>
                    <div class="table-responsive">
                        <h2 align='center'>Citas creadas</h2><br>
                            <div id="listCreada" class="table-responsive"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
            <div id='filtroCitasAsignadas'>
            <div class="table-responsive">
                <h2 align='center'>Citas asignadas</h2><br>
                    <div id="listAsignada" class="table-responsive"></div>
            
			</div>
            </div>
        </div>
            
        </div>
    </div>
</div>
<form>

<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var tipoSesion = "<?php echo $tipoSession ?>";
    $(document).ready(function(){

        $("#listAsignada").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            columnas: [
                { leyenda: 'Fecha', ordenable: true, columna: 'fecha', style: 'width:90px;background-color: #c2f0c2;'},
                { leyenda: 'Cliente', columna: 'razonSocial', ordenable: true, filtro: true, style: 'background-color: #c2f0c2;'},
                { leyenda: 'Sucursal', columna: 'sucursal', ordenable: true, filtro: true , style: 'background-color: #c2f0c2;'},
                { leyenda: 'Creada por', columna: 'registradoPor', ordenable: true, filtro: true , style: 'background-color: #c2f0c2;'},
                { leyenda: 'Atendida por', columna: 'atendida', ordenable: true, filtro: true , style: 'background-color: #c2f0c2;'},
                { leyenda: 'Detalle cita', columna: 'observacionCita', ordenable: true, filtro: true, style: 'background-color: #c2f0c2;'},
                { leyenda: 'Reporte cita', columna: 'resultados', ordenable: true, filtro: true , style: 'background-color: #c2f0c2;'},
                { leyenda: 'Estatus', columna: 'estatus', ordenable: true, style: 'background-color: #c2f0c2;', filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Cancelada', contenido: 'Cancelada' },
                            { valor: 'Espera', contenido: 'Espera' },
                            { valor: 'Atendida', contenido: 'Atendida' },
                        ]
                        });
                } },
                { leyenda: 'Opciones', style: 'background-color: #c2f0c2;'},
            ],
            modelo: [
                { propiedad: 'fecha' ,class:'text-center'},
                { propiedad: 'razonSocial' ,class:'text-center'},                     
                { propiedad: 'sucursal' ,class:'text-center'},
                { propiedad: 'registradoPor' ,class:'text-center'},
                { propiedad: 'atendida',class:'text-center' },
                { propiedad: 'observacionCita',class:'text-center' },
                { propiedad: 'resultados',class:'text-center' },
                { propiedad: 'estatus',class:'text-center' },
                { class:'text-center', formato: function(tr, obj,celda){
                                        
                    let botones ='';
                        

                            if (obj.activo=='Si') {
                                if (obj.estatus=='Atendida') {
                                    botones += anexGrid_link({
                                        class: "btn btn-xs btn-warning",
                                        contenido: 'Detalle',
                                        href: 'detalleCitas/' + obj.idCita
                                    });
                                }else if (obj.estatus=='Cancelada' || obj.estatus=='Espera' ) {
                                    botones += anexGrid_link({
                                        class: 'btn btn-sm btn-danger',
                                        contenido: '<i class="fa fa-trash-o"> Eliminar</i>',
                                        href: 'eliminarCitas/' + obj.idCita
                                    });
                                    
                                    botones += anexGrid_link({
                                        class: "btn btn-xs btn-warning",
                                        contenido: 'Detalle',
                                        href: 'detalleCitas/' + obj.idCita
                                    });

                                    botones += anexGrid_link({
                                        class: 'btn btn-sm btn-info',
                                        contenido: '<i class="ace-icon fa fa-pencil bigger"> Editar</i>',
                                        href: 'modificarCitaAsignada/' + obj.idCita
                                    });
                                }else{
                                    botones += anexGrid_link({
                                        class: 'btn btn-sm btn-info',
                                        contenido: '<i class="ace-icon fa fa-pencil bigger"> Editar</i>',
                                        href: 'modificarCitaAsignada/' + obj.idCita
                                    });
                                }

                                
                            }else{

                                botones += anexGrid_link({
                                    class: 'btn btn-xs btn-warning',
                                    contenido: 'Restaurar',
                                    href: 'restaurarCitas/' + obj.idCita
                                });

                            }

                        return botones;

                    },
                },
            ],
            url: 'filtroCitasAsignadasAct',
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'idCita',
            columna_orden: 'DESC'
        });
    })
</script>

<script>
    var tipoSesion = "<?php echo $tipoSession ?>";
    $(document).ready(function(){

        $("#listCreada").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            columnas: [
                { leyenda: 'Fecha', ordenable: true, columna: 'fecha', style: 'width:90px;background-color: #f2e6d9;'},
                { leyenda: 'Cliente', columna: 'razonSocial', ordenable: true, filtro: true, style: 'background-color: #f2e6d9;'},
                { leyenda: 'Sucursal', columna: 'sucursal', ordenable: true, filtro: true, style: 'background-color: #f2e6d9;'},
                { leyenda: 'Creada por', columna: 'registradoPor', ordenable: true, filtro: true, style: 'background-color: #f2e6d9;'},
                { leyenda: 'Atendida por', columna: 'atendida', ordenable: true, filtro: true, style: 'background-color: #f2e6d9;'},
                { leyenda: 'Detalle cita', columna: 'observacionCita', ordenable: true, filtro: true, style: 'background-color: #f2e6d9;'},
                { leyenda: 'Reporte cita', columna: 'resultados', ordenable: true, filtro: true , style: 'background-color: #f2e6d9;'},
                { leyenda: 'Estatus', columna: 'estatus', ordenable: true, style: 'background-color: #f2e6d9;', filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Cancelada', contenido: 'Cancelada' },
                            { valor: 'Espera', contenido: 'Espera' },
                            { valor: 'Atendida', contenido: 'Atendida' },
                        ]
                        });
                } },
                { leyenda: 'Opciones', style: 'background-color: #f2e6d9;'},
            ],
            modelo: [
                { propiedad: 'fecha' ,class:'text-center'},
                { propiedad: 'razonSocial' ,class:'text-center'},                     
                { propiedad: 'sucursal' ,class:'text-center'},
                { propiedad: 'registradoPor' ,class:'text-center'},
                { propiedad: 'atendida',class:'text-center' },
                { propiedad: 'observacionCita',class:'text-center' },
                { propiedad: 'resultados',class:'text-center' },
                { propiedad: 'estatus',class:'text-center' },
                { class:'text-center', formato: function(tr, obj,celda){
                                        
                    let botones ='';
                        

                            if (obj.activo=='Si') {
                                if (obj.estatus=='Atendida') {
                                    botones += anexGrid_link({
                                        class: "btn btn-xs btn-warning",
                                        contenido: 'Detalle',
                                        href: 'detalleCitas/' + obj.idCita
                                    });
                                }else if (obj.estatus=='Cancelada' || obj.estatus=='Espera' ) {
                                    botones += anexGrid_link({
                                        class: 'btn btn-sm btn-danger',
                                        contenido: '<i class="fa fa-trash-o"> Eliminar</i>',
                                        href: 'eliminarCitas/' + obj.idCita
                                    });
                                    
                                    botones += anexGrid_link({
                                        class: "btn btn-xs btn-warning",
                                        contenido: 'Detalle',
                                        href: 'detalleCitas/' + obj.idCita
                                    });

                                    botones += anexGrid_link({
                                        class: 'btn btn-sm btn-info',
                                        contenido: '<i class="ace-icon fa fa-pencil bigger"> Editar</i>',
                                        href: 'modificarCita/' + obj.idCita
                                    });
                                }else{
                                    botones += anexGrid_link({
                                        class: 'btn btn-sm btn-info',
                                        contenido: '<i class="ace-icon fa fa-pencil bigger"> Editar</i>',
                                        href: 'modificarCita/' + obj.idCita
                                    });
                                }

                                
                            }else{

                                botones += anexGrid_link({
                                    class: 'btn btn-xs btn-warning',
                                    contenido: 'Restaurar',
                                    href: 'restaurarCitas/' + obj.idCita
                                });

                            }

                        return botones;

                    },
                },
            ],
            url: 'filtroCitasCreadasAct',
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'idCita',
            columna_orden: 'DESC'
        });
    })
</script>
@stop