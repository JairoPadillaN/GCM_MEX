@extends('principal')
@section('contenido')

<script type="text/javascript">
$(document).ready(function() {
    $("#agrega").click(function() {
        //      alert ("hola");
        $("#filtroViajes").load('{{url('reporteViajesAbajo')}}' + '?' + $(this).closest('form').serialize());

    });

});
</script>


<form>
    <div class="col-md-12">
        <div class="panel panel-default" style="margin-top:-45px">
            <div class="panel-heading">
                <h1 class="">Reporte de viajes</h1>
            </div><br>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6 col-md-4">
                        Fecha de inicio<input type='date' name='fechaInicio' id='fechaInicio'
                            class="form-control rounded-0">
                    </div>
                    <div class="col-xs-6 col-md-4">
                        Fecha de Fin<input type='date' name='fechaFin' id='fechaFin' class="form-control rounded-0">
                    </div>
                    <div class="col-xs-6 col-md-4">
                        Conductor:
                        <select name='idu' id='idu' class="form-control rounded-0">
                        <option value="">Seleccione un conductor</option>
                            @foreach($usuario as $us)
                            <option value='{{$us->idu}}'>{{$us->nombreUsuario}} {{$us->aPaterno}} {{$us->aMaterno}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-6">
                        <button type="button" class="btn  btn-default" name="agrega" id="agrega">Muestra
                            reporte</button>
                        <a href="{{asset('altaSalidaViajes')}}">
                            <button type="button" class="btn btn-primary ">Agregar nuevo viaje
                            </button>
                        </a>
                    </div>
                </div>
                <br><br>
                <div id='filtroViajes'>
                <div id="list" class="table-responsive"></div>
                <!-- <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Conductor</th>
                                    <th>Empresa</th>
                                    <th>Sucursal</th>
                                    <th>Auto</th>
                                    <th>Kilometros recorridos</th>
                                    <th>Monto</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody> @foreach($consulta as $repViaje) <tr>
                                    <td class="sorting_1">{{$repViaje->fechaInicio}}</td>
                                    <td class="sorting_1">{{$repViaje->us}}</td>
                                    <td class="sorting_1">{{$repViaje->cli}}</td>
                                    <td class="sorting_1">{{$repViaje->suc}}</td>
                                    <td class="sorting_1">{{$repViaje->veh}}</td>
                                    <td class="sorting_1">{{$repViaje->kmr}}</td>
                                    <td class="sorting_1">${{$repViaje->montoGasto}}.00</td>
                                    <td class="sorting_1">{{$repViaje->estatus}}</td>
                                    <td> @if($repViaje->activo =='No') <a
                                            href="{{URL::action('ViajesController@restaurarSalidaViajes',['idViaje'=>$repViaje->idViaje])}}"
                                            class="btn btn-xs btn-warning" style="width:107px">Restaurar</a>
                                        @endif @if($repViaje->estatus =='En curso') <a
                                            href="{{URL::action('ViajesController@altaRegresoViajes',['idViaje'=>$repViaje->idViaje])}}"
                                            class="btn btn-xs btn-success" style="width:107px; "><i class="fa fa-check">
                                                Finalizar viaje</i></a> <a
                                            href="{{URL::action('ViajesController@modificarSalidaViajes',['idViaje'=>$repViaje->idViaje])}}"
                                            class="btn btn-xs btn-info" style="width:107px; "><i
                                                class="ace-icon fa fa-pencil bigger-120"> Editar salida</i></a>
                                        <a href="{{URL::action('ViajesController@eliminarSalidaViajes',['idViaje'=>$repViaje->idViaje])}}"
                                            class="btn btn-xs btn-danger" style="width:107px; "><i
                                                class="ace-icon fa fa-trash-o bigger-120"> Eliminar
                                                salida</i></a> @else <a
                                            href="{{URL::action('ViajesController@detalleViajes',['idViaje'=>$repViaje->idViaje])}}"
                                            class="btn btn-xs btn-danger"
                                            style="background-color:#FA8072; width:107px;"> Detalle del
                                            viaje</a> @if($repViaje->estatus =='Finalizado') <a
                                            href="{{URL::action('ViajesController@modificarViajes',['idViaje'=>$repViaje->idViaje])}}"
                                            class="btn btn-xs btn-info" style="width:107px; "><i
                                                class="ace-icon fa fa-pencil bigger-120"> Editar viaje</i></a>
                                        @endif @endif
                                </tr> @endforeach </tbody>
                        </table> -->
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

        $("#list").anexGrid({
            class: 'table table-striped table-bordered table-hover',
            columnas: [
                { leyenda: 'Fecha', ordenable: true, columna: 'fechaInicio', style:'width:100px;'},
                { leyenda: 'Conductor', ordenable: true, columna: 'usuarioViaje',filtro: true},
                { leyenda: 'Empresa', ordenable: true, columna: 'razonSocial', filtro: true},
                { leyenda: 'Sucursal', ordenable: true, columna: 'sucursal', filtro: true},
                { leyenda: 'Auto', ordenable: true, columna: 'nombreVehiculo', filtro: true},
                { leyenda: 'Kilometros recorridos', ordenable: true, columna: 'kilometros'},
                { leyenda: 'Monto', ordenable: true, columna: 'montoGasto'},
                { leyenda: 'Estatus', columna: 'estatus', ordenable: true, style:'width:100px;', filtro: function(){
                    return anexGrid_select({
                        data: [
                            { valor: '', contenido: 'Todos' },
                            { valor: 'En curso', contenido: 'En curso' },                                       
                            { valor: 'Finalizado', contenido: 'Finalizado' },                                        
                            { valor: 'Cancelado', contenido: 'Cancelado' },                                        
                        ]
                        });
                } },
                { leyenda: 'Opciones'},
            ],
            modelo: [
                { propiedad: 'fecha' ,class:'text-center'},
                { propiedad: 'usuarioViaje' ,class:'text-center'},
                { propiedad: 'razonSocial' ,class:'text-center'},
                { propiedad: 'sucursal' ,class:'text-center'},
                { propiedad: 'nombreVehiculo' ,class:'text-center'},
                { propiedad: 'kilometros' ,class:'text-center'},
                { propiedad: 'montoGasto' ,class:'text-center'},
                { propiedad: 'estatus' ,class:'text-center'},
                { formato: function(tr, obj,celda){
                                        
                    let botones ='';

                        if (obj.activo=='No') {

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-warning',
                                contenido: '<span class="glyphicon glyphicon-wrench"></span> Restaurar',
                                href: 'restaurarSalidaViajes/' + obj.idViaje
                            });
                        }

                        if (obj.estatus=='Finalizado') {

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-info ',
                                contenido: '<i class="ace-icon fa fa-pencil bigger-120"> Editar viaje</i>',
                                href: 'modificarViajes/' + obj.idViaje
                            });
                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-danger',
                                contenido: 'Detalle del viaje',
                                href: 'detalleViajes/' + obj.idViaje
                            });
                         }
                        if (obj.estatus=='En curso') {

                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-success',
                                contenido: '<i class="fa fa-check">Finalizar viaje</i>',
                                href: 'altaRegresoViajes/' + obj.idViaje
                            });
                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-info ',
                                contenido: '<i class="ace-icon fa fa-pencil bigger-120"> Editar viaje</i>',
                                href: 'modificarViajes/' + obj.idViaje
                            });
                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-danger',
                                contenido: '<i class="ace-icon fa fa-trash-o bigger-120"> Eliminar salida</i>',
                                href: 'eliminarSalidaViajes/' + obj.idViaje
                            });
                         }
                        if (obj.estatus=='Cancelado') {
                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-danger',
                                contenido: 'Detalle del viaje',
                                href: 'detalleViajes/' + obj.idViaje
                            });
                            botones += anexGrid_link({
                                class: 'btn btn-xs btn-warning',
                                contenido: '<span class="glyphicon glyphicon-wrench"></span> Restaurar',
                                href: 'restaurarSalidaViajes/' + obj.idViaje
                            });
                         }

                    return botones;

                    },
                },    
            ],
            url: 'filtroViajes',
            paginable: true,
            filtrable: true,
            limite: [5, 10, 20, 50, 100],
            columna: 'idViaje',
            columna_orden: 'DESC'
        });
    })
</script>
@stop