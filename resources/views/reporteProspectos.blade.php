@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#agrega").click(function() {
        //  alert ("hola");
        $("#filtroProspectos").load('{{url('reporteProspectosAbajo')}}' + '?' + $(this).closest('form').serialize());

    });
    $("#limpiar").click(function() {
        location.reload();
    });
});
</script>

<form>
    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de investigación de prospectos</h1>
            </div><br>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-3"> Fecha de inicio<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>
                    <div class="col-lg-3">
                        Fecha de Fin<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-6">
                        <button type="button" class="btn  btn-default" name="agrega" id="agrega">Aplicar filtro</button>
                        <button type="button" class="btn  btn-default" name="agrega" id="limpiar">Limpiar
                            filtro</button>
                        <a href="{{asset('altaProspectos')}}">
                            <button type="button" class="btn btn-primary ">Agregar seguimiento a prospecto
                            </button>
                        </a>
                    </div>
                </div>
                <br>
                <div id='filtroProspectos'>
                    <div id="listProspectos" class="table-responsive"></div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var tipoSesion = "<?php echo $tipoSession ?>";
    $(document).ready(function(){

        $("#listProspectos").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            columnas: [
                { leyenda: 'Fecha', ordenable: true, columna: 'fecha', style: 'width:90px;background-color: #b3ffb3;'},
                { leyenda: 'Cliente', columna: 'razonSocial', ordenable: true, filtro: true, style: 'width:90px;background-color: #b3ffb3;'},
                { leyenda: 'Sucursal', columna: 'sucursal', ordenable: true, filtro: true, style: 'background-color: #b3ffb3;'},
                { leyenda: 'Descripción', columna: 'descripcionActividad', ordenable: true,filtro: true, style: 'background-color: #b3ffb3;'},
                { leyenda: 'Vendedor', columna: 'registradoPor', ordenable: true, filtro: true, style: 'background-color: #b3ffb3;'},
                { leyenda: 'Opciones', style: 'background-color: #b3ffb3;'},
            ],
            modelo: [
                { propiedad: 'fecha' ,class:'text-center'},
                { propiedad: 'razonSocial' ,class:'text-center'},                     
                { propiedad: 'sucursal' ,class:'text-center'},
                { propiedad: 'descripcionActividad' ,class:'text-center'},
                { propiedad: 'registradoPor',class:'text-center' },
                { class:'text-center', formato: function(tr, obj,celda){
                                        
                    let botones ='';
                        

                            if (obj.activo=='Si') {

                                botones += anexGrid_link({
                                    class: 'btn btn-sm btn-info',
                                    contenido: '<i class="ace-icon fa fa-pencil bigger"> Editar</i>',
                                    href: 'modificarProspectos/' + obj.idInvProspectos
                                });

                                botones += anexGrid_link({
                                    class: 'btn btn-sm btn-danger',
                                    contenido: '<i class="fa fa-trash-o"> Eliminar</i>',
                                    href: 'eliminarProspectos/' + obj.idInvProspectos
                                });

                            }else{

                                botones += anexGrid_link({
                                    class: 'btn btn-xs btn-warning',
                                    contenido: 'Restaurar',
                                    href: 'restaurarProspectos/' + obj.idInvProspectos
                                });

                            }

                        return botones;

                    },
                },
            ],
            url: 'filtroProspectosAct',
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'idInvProspectos',
            columna_orden: 'DESC'
        });
    })
</script>
@stop