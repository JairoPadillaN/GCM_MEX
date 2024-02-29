<script>
$("#filtroCreaciones").click(function() {
    $("#filtroCreadas").load('{{url('reporteCitasCreadasAbajo')}}' + '?' + $(this).closest('form').serialize());
    // alert("hola");
});

$("#limpiarCreadas").click(function() {
    location.reload();
});
</script>

<form>
    <div class="col-md-12">
        <div  style="margin-top: -45px">
            <div class="panel-heading">
                <h1 class="">Citas creadas por {{$sname}}</h1>
            </div><br>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-3"> Fecha de inicio:<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>
                    <div class="col-lg-3">
                        Fecha de Fin:<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>
                    <!-- @if($stipo == 'Administrador')
                    <div class="col-lg-3">Usuario:
                    <select name='idu' id='idu' class="form-control">
                        <option value="">Seleccionar responsable</option>
                        @foreach($usuario as $usuario)
                        @if($usuario->activo=="Si")
                        <option value='{{$usuario->idu}}'>{{$usuario->nombreUsuario}} {{$usuario->aPaterno}} {{$usuario->aMaterno}}</option>
                        @endif
                        @endforeach
                    </select>
                    </div>
                    @endif -->
                    <br>
                    <button type="button" class="btn  btn-primary" name="filtro" id="filtroCreaciones">Aplicar filtro</button>
                    <button type="button" class="btn  btn-default" name="limpiarCreadas" id="limpiarCreadas">Limpiar filtro</button>

                </div>
                <br><br>
                <div id="filtroCreadas">
                    @if($cuantasCreadas>=1)
                    <!-- <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Fecha </th>
                                    <th>Cliente</th>
                                    <th>Sucursal</th>
                                    <th>Creada por</th>
                                    <th>Atendida por</th>
                                    <th>Detalle cita</th>
                                    <th>Reporte cita</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach($citasCreadas as $creadas)
                                    <td>{{$creadas->fecha}}</td>
                                    <td>{{$creadas->razonSocial}}</td>
                                    <td>{{$creadas->sucursal}}</td>
                                    <td>{{$creadas->registradoPor}}</td>
                                    <td>{{$creadas->nombreUsuario}} {{$creadas->aPaterno}} {{$creadas->aMaterno}}</td>
                                    <td>{{$creadas->observacionCita}}</td>
                                    <td>{{$creadas->resultados}}</td>
                                    <td>{{$creadas->estatus}}</td>
                                    <td> @if($creadas->activo =='No') <a
                                            href="{{URL::action('CitasController@restaurarCitas',['idCita'=>$creadas->idCita])}}"
                                            class="btn btn-xs btn-warning" style="width:107px">Restaurar</a>
                                        <a href="{{URL::action('CitasController@detalleCitas',['idCita'=>$creadas->idCita])}}"
                                            class="btn btn-xs btn-danger"
                                            style="background-color:#FA8072; width:107px;"> Detalle del cita
                                        </a>
                                        @endif
                                        @if($creadas->activo =='Si')
                                        @if($creadas->estatus =='Espera')
                                        <a href="{{URL::action('CitasController@modificarCita',['idCita'=>$creadas->idCita])}}"
                                            class="btn btn-xs btn-info" style="width:107px; ">
                                            <i class="ace-icon fa fa-pencil bigger-120"> Editar cita</i>
                                        </a>
                                        <a href="{{URL::action('CitasController@detalleCitas',['idCita'=>$creadas->idCita])}}"
                                            class="btn btn-xs btn-danger"
                                            style="background-color:#FA8072; width:107px;"> Detalle del cita
                                        </a>
                                        <a href="{{URL::action('CitasController@eliminarCitas',['idCita'=>$creadas->idCita])}}"
                                            class="btn btn-xs btn-danger" style="width:107px; ">
                                            <i class="ace-icon fa fa-trash-o bigger-120"> Eliminar cita</i>
                                        </a>
                                        @endif
                                        @if($creadas->estatus =='Atendida')
                                        <a href="{{URL::action('CitasController@detalleCitas',['idCita'=>$creadas->idCita])}}"
                                            class="btn btn-xs btn-danger"
                                            style="background-color:#FA8072; width:107px;"> Detalle del cita
                                        </a>
                                        @endif
                                        @if($creadas->estatus =='' || $creadas->estatus =='Cancelada')
                                        <a href="{{URL::action('CitasController@modificarCita',['idCita'=>$creadas->idCita])}}"
                                            class="btn btn-xs btn-info" style="width:107px; ">
                                            <i class="ace-icon fa fa-pencil bigger-120"> Editar cita</i>
                                        </a>
                                        <a href="{{URL::action('CitasController@eliminarCitas',['idCita'=>$creadas->idCita])}}"
                                            class="btn btn-xs btn-danger" style="width:107px; ">
                                            <i class="ace-icon fa fa-trash-o bigger-120"> Eliminar cita</i>
                                        </a>
                                        @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> -->
                    <div id="listCreada" class="table-responsive"></div>
                    @else
                    <center>
                        <div class="alert alert-danger" role="alert">No ha creado citas.</div>
                        @endif
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