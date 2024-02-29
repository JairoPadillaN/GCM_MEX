<option value="">Seleccione SKU</option>
@foreach ($skus as $s)
    <option value='{{ $s->idPartesVenta }}'>{{ $s->parte }}</option>
@endforeach
