<option value="">Seleccione un tipo de refacción</option>
@foreach($consulta as $ref)

@if($ref->activo=="Si")
<option value='{{$ref->idTipoRefacciones}}'>{{$ref->nombreTipoRefaccion}}</option>

@endif
@endforeach