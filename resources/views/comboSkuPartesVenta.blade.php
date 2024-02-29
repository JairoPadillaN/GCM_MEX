<option value="">Seleccionar sku</option>
@foreach($consultaPartesVenta as $cPv)
    <option value='{{$cPv->idPartesVenta}}'>
        {{$cPv->codigo}} - {{$cPv->nombreRefaccion}}
        <!-- - {{$cPv->nombreRefaccion}} -->
    </option>
@endforeach