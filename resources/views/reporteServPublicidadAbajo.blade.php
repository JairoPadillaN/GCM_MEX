
<div id="listPublicidad" class="table-responsive"></div>


<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var fechaInicio = "<?php echo $fechaInicio ?>";
    var fechaFin = "<?php echo $fechaFin ?>";
    var tipoSesion = "<?php echo $tipoSession ?>";
    $(document).ready(function(){

        $("#listPublicidad").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            columnas: [
                { leyenda: 'Fecha', ordenable: true, columna: 'fecha', style: 'width:90px;background-color: #ccddff;'},
                { leyenda: 'Usuario', columna: 'nombreUsuario', ordenable: true, filtro: true, style: 'background-color: #ccddff;'},
                { leyenda: 'Cliente', columna: 'razonSocial', ordenable: true, filtro: true, style: 'background-color: #ccddff;'}, 
                { leyenda: 'Tipo de publicidad', columna: 'tipoPublicidad', ordenable: true, filtro: true, style: 'background-color: #ccddff;'},
                { leyenda: 'Detalle actividad', columna: 'descripcionActividad', ordenable: true,filtro: true, style: 'background-color: #ccddff;'},
                { leyenda: 'Respuesta', columna: 'contesto', ordenable: true, filtro: true, style: 'background-color: #ccddff;'},
                { leyenda: 'Estatus', columna: 'estatus', ordenable: true, style: 'background-color: #ccddff;', filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'Atendió Publicidad', contenido: 'Atendió Publicidad' },
                            { valor: 'Pendiente de respuesta', contenido: 'Pendiente de respuesta' },
                        ]
                        });
                } },
                { leyenda: 'Opciones', style: 'background-color: #ccddff;'},
            ],
            modelo: [
                { propiedad: 'fecha' ,class:'text-center'},
                { propiedad: 'nombreUsuario' ,class:'text-center'},
                { propiedad: 'razonSocial' ,class:'text-center'},                     
                { propiedad: 'tipoPublicidad' ,class:'text-center'},
                { propiedad: 'descripcionActividad',class:'text-center' },
                { propiedad: 'contesto',class:'text-center' },
                { propiedad: 'estatus',class:'text-center' },
                { class:'text-center', formato: function(tr, obj,celda){
                                        
                    let botones ='';
                        

                            if (obj.activo=='Si') {

                                botones += anexGrid_link({
                                    class: 'btn btn-sm btn-info',
                                    contenido: '<i class="ace-icon fa fa-pencil bigger"> Editar</i>',
                                    href: 'modificarServPublicidad/' + obj.idServPublicidad
                                });

                                botones += anexGrid_link({
                                    class: 'btn btn-sm btn-danger',
                                    contenido: '<i class="fa fa-trash-o"> Eliminar</i>',
                                    href: 'eliminarServPublicidad/' + obj.idServPublicidad
                                });

                            }else{

                                botones += anexGrid_link({
                                    class: 'btn btn-xs btn-warning',
                                    contenido: 'Restaurar',
                                    href: 'restaurarServPublicidad/' + obj.idServPublicidad
                                });

                            }

                        return botones;

                    },
                },
            ],
            url: 'filtroPublicidadActAbajo',
            fechaInicio,
            fechaFin,
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'idServPublicidad',
            columna_orden: 'DESC'
        });
    })
</script>