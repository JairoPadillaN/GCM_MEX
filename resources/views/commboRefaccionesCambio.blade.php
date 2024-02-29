<option value="">Seleccione una refacción</option>
@foreach($consulta as $consulta)

@if($consulta->activo=="Si")
<option value='{{$consulta->idRefaccion}}'>{{$consulta->codigoRefaccion}}-{{$consulta->nombreRefaccion}}-{{$consulta->serie}}</option>

@endif
@endforeach