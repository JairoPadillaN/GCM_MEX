<option value="">Seleccione un pieza</option>
@foreach($consultaPartesEquipo as $cPe)
        <option value='{{$cPe->idParte}}'>{{$cPe->GCMidParte}} {{$cPe->nombreParte}}</option>
@endforeach