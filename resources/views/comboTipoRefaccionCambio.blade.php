<option value="">Seleccione un tipo de refacción</option>
@foreach($consultaRef as $ref)

@if($ref->activo=="Si")
<option value='{{$ref->idTipoRefacciones}}'>{{$ref->nombreTipoRefaccion}}</option>

@endif
@endforeach