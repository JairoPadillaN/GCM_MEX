<form>
    <div class="col-md-12">
        <div  style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Publicidad</h1>
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
                    <div class="col-lg-3">
                        <br>
                        <button type="button" class="btn  btn-primary" name="agrega" id="filtrar">Aplicar filtro</button>
                        <button type="button" class="btn  btn-default" name="limpiar" id="limpia">Limpiar filtro</button>

                    </div>
                </div>
                <br><br>
                <div id="filtroPublicidad">

                    <!-- <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Fecha </th>
                                    <th>Cliente</th>
                                    <th>Sucursal</th>
                                    <th>Vendedor</th>
                                    <th>Persona</th>
                                    <th>Detalle actividad</th>
                                    <th>Respuesta</th>
                                    <th>Estatus</th>
                                    <th>Operaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach($publicidad as $p)
                                    <td>{{$p->fecha}}</td>
                                    <td>{{$p->razonSocial}}</td>
                                    <td>{{$p->sucursal}}</td>
                                    <td>{{$p->nombreUsuario}}</td>
                                    <td>{{$p->contacto}}</td>
                                    <td>{{$p->descripcionActividad}}</td>
                                    <td>{{$p->contesto}}</td>
                                    <td>{{$p->estatus}}</td>
                                    <td>@if($p->activo=='Si')
                                <a href="{{URL::action('servPublicidadController@eliminarServPublicidad',['idServPublicidad'=>$p->idServPublicidad])}}"
                                    type="submit" class="btn btn-xs btn-danger"><i
                                        class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i></a>

                                <a href="{{URL::action('servPublicidadController@modificarServPublicidad',['idServPublicidad'=>$p->idServPublicidad])}}"
                                    class="btn btn-xs btn-info" style="width:67px;"><i
                                        class="ace-icon fa fa-pencil bigger-120"> Editar</i></a>
                                @else
                                <a href="{{URL::action('servPublicidadController@restaurarServPublicidad',['idServPublicidad'=>$p->idServPublicidad])}}"
                                    class="btn btn-xs btn-warning" style="width:67px">
                                    Restaurar</a>
                                @endif
                            </td>

                                </tr>
                                @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div> -->
                    <div id="listPublicidad" class="table-responsive"></div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
$("#filtrar").click(function(){
    $("#filtroPublicidad").load('{{url('reportePublicidadActAbajo')}}' + '?' + $(this).closest('form').serialize());
});

$("#limpia").click(function(){
    location.reload();
});
</script>

<?php $tipoSession = Session::get('sesiontipo'); ?>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    var tipoSesion = "<?php echo $tipoSession ?>";
    $(document).ready(function(){

        $("#listPublicidad").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            columnas: [
                { leyenda: 'Fecha', ordenable: true, columna: 'fecha', style: 'width:90px;background-color: #ccddff;'},
                { leyenda: 'Cliente', columna: 'razonSocial', ordenable: true, filtro: true, style: 'background-color: #ccddff;'},
                { leyenda: 'Sucursal', columna: 'sucursal', ordenable: true, filtro: true, style: 'background-color: #ccddff;'},
                { leyenda: 'Vendedor', columna: 'nombreUsuario', ordenable: true, filtro: true, style: 'background-color: #ccddff;'},
                { leyenda: 'Persona', columna: 'contacto', ordenable: true, filtro: true, style: 'background-color: #ccddff;'},
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
                { propiedad: 'razonSocial' ,class:'text-center'},                     
                { propiedad: 'sucursal' ,class:'text-center'},
                { propiedad: 'nombreUsuario' ,class:'text-center'},
                { propiedad: 'contacto' ,class:'text-center'},
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
            url: 'filtroPublicidadAct',
            paginable: true,
            filtrable: true,
            limite: [10, 20, 50, 100],
            columna: 'idServPublicidad',
            columna_orden: 'DESC'
        });
    })
</script>