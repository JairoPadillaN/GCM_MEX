@if($cuantos>=1)

<table class="table table-striped table-bordered table-hover" id="dataTables-example">
  <thead>
    <tr>
      <th>Fecha</th>
      <th>Usuario</th>
      <th>Km recorridos</th>
      <th>Vehículo</th>
      <th>Empresa</th>
      <th>Monto</th>
      <th>Opciones</th>
    </tr>
  </thead>

<tbody>
  @foreach($reporte as $r)
  <tr>
    <td class="sorting_1">{{$r->fecha}}</td>
    <td class="sorting_1">{{$r->us}}</td>
    <td class="sorting_1">{{$r->kmr}}</td>
    <td class="sorting_1">{{$r->nombreVehiculo}}</td>
    <td class="sorting_1">{{$r->razonSocial}}</td>
    <td class="sorting_1">{{$r->montoGasto}}</td>
    <td>
      @if($r->activo =='No')
            <a href="{{URL::action('ViajesController@restaurarSalidaViajes',['idViaje'=>$r->idViaje])}}" class="btn btn-xs btn-warning" style="width:107px">Restaurar</a>
        @endif
        @if($r->estatus =='En curso')
              <a href="{{URL::action('ViajesController@altaRegresoViajes',['idViaje'=>$r->idViaje])}}" class="btn btn-xs btn-success" style="width:107px; "><i class="fa fa-check"> Finalizar viaje</i></a>
              <a href="{{URL::action('ViajesController@modificarSalidaViajes',['idViaje'=>$r->idViaje])}}" class="btn btn-xs btn-info" style="width:107px; "><i class="ace-icon fa fa-pencil bigger-120"> Editar salida</i></a>
              <a href="{{URL::action('ViajesController@eliminarSalidaViajes',['idViaje'=>$r->idViaje])}}" class="btn btn-xs btn-danger" style="width:107px; "><i class="ace-icon fa fa-trash-o bigger-120"> Eliminar salida</i></a>
             @else
              <a href="{{URL::action('ViajesController@detalleViajes',['idViaje'=>$r->idViaje])}}" class="btn btn-xs btn-danger" style="background-color:#FA8072; width:107px;"> Detalle del viaje</a>
                @if($r->estatus =='Finalizado')
                  <a href="{{URL::action('ViajesController@modificarViajes',['idViaje'=>$r->idViaje])}}" class="btn btn-xs btn-info"  style="width:107px; "><i class="ace-icon fa fa-pencil bigger-120"> Editar viaje</i></a>
                @endif
             @endif
    </td>
  </tr>
  @endforeach
  <tr>
    <th colspan=7><center>Totales</center></th>
  </tr>
  <tr>
    <td colspan=6>Numero de viajes</td>
    <td align='right'>{{$totales->cuantos}}</td>
  </tr>
  <tr>
    <td colspan=6>Total de km recorridos</td>
    <td align='right'>{{$totales->totalkm}}</td>
  </tr>
  <tr>
    <td colspan=6>Total de gastos:</td>
  <td align='right'>{{$totales->totalpago}}</td>
  </tr>
</tbody>
  </table>
@else
No existen viajes para el Conductor seleccionado
@endif
