<option value="">Selecciona un producto</option>
@foreach($consultaOrden as $orden)
    <option value="{{$orden->idOcupar}}">{{$orden->gcmid}} - {{$orden->nombreRefaccion}}</option>
@endforeach