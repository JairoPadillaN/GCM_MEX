<option value="">Seleccione un número de parte</option>
@foreach($consulta as $ref)
@if($ref->activo=="Si")
<option value='{{$ref->idPartesVenta}}'>{{$ref->codigo}} - {{$ref->nombreRefaccion}}</option>
@endif
@endforeach
