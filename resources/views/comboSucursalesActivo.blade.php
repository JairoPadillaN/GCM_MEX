<option value="">Seleccionar sucursal</option>
@foreach($consulta as $s)
@if($s->activo=="Si")
@if($s->tipoSucursal=="prospecto" || $s->tipoSucursal=="activo")
<option value='{{$s->idSucursal}}'>{{$s->sucursal}}</option>


@endif
@endif
@endforeach