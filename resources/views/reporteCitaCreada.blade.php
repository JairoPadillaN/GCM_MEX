<div class="table-responsive">
    <h2 align='center'>Citas creadas</h2><br>
</div>
<div id="listCreada" class="table-responsive"></div>


<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var fechaInicio = "<?php echo $fechaInicio ?>";
    var fechaFin = "<?php echo $fechaFin ?>";
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
            url: 'filtroCitasCreadasActAbajo',
            fechaInicio,
            fechaFin,
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'idCita',
            columna_orden: 'DESC'
        });
    })
</script>