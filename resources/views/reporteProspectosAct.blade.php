<form>
    <div class="col-md-12">
        <div style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Investigación de prospectos</h1>
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
                    <button type="button" class="btn  btn-primary" name="agrega" id="filtro">Aplicar filtro</button>
                    <button type="button" class="btn  btn-default" name="limpiar" id="limpiar">Limpiar filtro</button>

                </div>
                </div>
                <br><br>
                <div id="filtroProspectos">

                    <!-- <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Fecha </th>
                                    <th>Cliente</th>
                                    <th>Sucursal</th>
                                    <th>Descripción de actvidad</th>
                                    <th>Vendedor</th>
                                    <th>Operaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach($prospectos as $ip)
                                    <td>{{$ip->fecha}}</td>
                                    <td>{{$ip->razonSocial}}</td>
                                    <td>{{$ip->sucursal}}</td>
                                    <td>{{$ip->descripcionActividad}}</td>
                                    <td>{{$ip->registradoPor}}</td>
                                    <td align="center">@if($ip->activo=='Si')
                                <a href="{{URL::action('invProspectosController@eliminarProspectos',['idInvProspectos'=>$ip->idInvProspectos])}}"
                                    type="submit" class="btn btn-xs btn-danger"><i
                                        class="ace-icon fa fa-trash-o bigger-120"> Eliminar</i>
                                </a>

                                <a href="{{URL::action('invProspectosController@modificarProspectos',['idInvProspectos'=>$ip->idInvProspectos])}}"
                                    class="btn btn-xs btn-info">
                                    <i class="ace-icon fa fa-pencil bigger-120"> Editar</i>
                                </a>
                                @else
                                <a href="{{URL::action('invProspectosController@restaurarProspectos',['idInvProspectos'=>$ip->idInvProspectos])}}"
                                    class="btn btn-xs btn-warning"> Restaurar</a>
                                @endif
                            </td>
                                </tr>
                                @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div> -->
                    <div id="listProspectos" class="table-responsive"></div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
$("#filtro").click(function(){
    $("#filtroProspectos").load('{{url('reporteProspectosActAbajo')}}' + '?' + $(this).closest('form').serialize());
});

$("#limpiar").click(function(){
    location.reload();
});

</script>

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