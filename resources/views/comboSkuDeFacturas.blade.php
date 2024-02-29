<option value="">Seleccionar sku</option>
@foreach($consultaOrden as $co)
    <option value='{{$co->idPartesVenta}}'>{{$co->codigo}} - {{$co->nombreRefaccion}}</option>
@endforeach