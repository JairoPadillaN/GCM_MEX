

<div id="listProspectos" class="table-responsive"></div>



    <?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var tipoSesion = "<?php echo $tipoSession ?>";
    var fechaInicio = "<?php echo $fechaInicio ?>";
    var fechaFin = "<?php echo $fechaFin ?>";
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
            url: 'filtroProspectosActAbajo',
            fechaInicio,
            fechaFin,
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'idInvProspectos',
            columna_orden: 'DESC'
        });
    })
</script>