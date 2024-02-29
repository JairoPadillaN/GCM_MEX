<option value="">Seleccione Categoría</option>
@foreach ($tipos as $t)
    <option value='{{ $t->idTipoRefacciones }}'>{{ $t->nombreTipoRefaccion }}</option>
@endforeach
